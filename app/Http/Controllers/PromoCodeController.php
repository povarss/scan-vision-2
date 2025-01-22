<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePromoCodesRequest;
use App\Http\Resources\PromoCodeResource;
use App\Models\PromoCode;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PromoCodeController extends Controller
{
    // Register a new user
    public function list(Request $request)
    {
        $model = PromoCode::with('user');

        if (!empty($request->q)) {
            $model->where(function (Builder $query) use ($request) {
                $query->whereLike('code', '%' . $request->q . '%');
            });
        }

        return DataTables::eloquent($model)
            ->only(['id', 'status', 'code', 'created_at', 'activated_at', 'user_email', 'days', 'minutes','isActive'])
            ->editColumn('status', function (PromoCode $promoCode) {
                return $promoCode->isActive() ? __('labels.Active') : __('labels.InActive');
            })
            ->addColumn('isActive', function (PromoCode $promoCode) {
                return $promoCode->isActive();
            })
            ->editColumn('created_at', function (PromoCode $promoCode) {
                return $promoCode->created_at->format('d.m.Y H:i');
            })
            ->editColumn('activated_at', function (PromoCode $promoCode) {
                return !empty($promoCode->activated_at) ? $promoCode->activated_at->format('d.m.Y H:i') : '';
            })
            ->addColumn('user_email', function (PromoCode $promoCode) {
                return !empty($promoCode->user_id) ? $promoCode->user->email : '';
            })
            ->toJson();
    }

    public function store(StorePromoCodesRequest $request)
    {
        $fields = $request->validated();
        DB::beginTransaction();

        $codes = collect(explode("\n", $request->codes))
            ->map(function ($item) {
                return is_string($item) ? trim($item) : $item;
            })
            ->filter(function ($item) {
                return !empty($item);
            });

        foreach ($codes as $code) {
            $promoCode = new PromoCode();
            $trimmedCode = preg_replace('/\s+/', '', $code);
            $promoCode->code = $trimmedCode;
            $promoCode->status = PromoCode::STATUS_ACTIVE;
            $promoCode->days = $request->days;
            $promoCode->minutes = $request->minutes;
            $promoCode->save();
        }
        DB::commit();



        return response()->json([
            'isStored' => 1,
            'message' => __('PromoCodes saved successfully'),
        ]);
    }

    public function get(PromoCode $promoCode)
    {
        return new PromoCodeResource($promoCode);
    }

    public function delete(Request $request)
    {
        $promoCode = PromoCode::findOrFail($request->input('id'));
        if (empty($promoCode->activated_at)) {
            return response(['message' => __('PromoCode already activated')], 500);
        }
        $promoCode->remove();
    }
}
