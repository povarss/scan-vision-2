<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Resources\UserResource;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class DoctorController extends Controller
{
    // Register a new user
    public function list(Request $request)
    {
        $model = User::query()
            ->role(User::ROLE_DOCTOR);

        if (!empty($request->q)) {
            $model->where(function (Builder $query) use ($request) {
                $query->whereLike('name', '%' . $request->q . '%')
                    ->orWhereLike('email', '%' . $request->q . '%');
            });
        }

        return DataTables::eloquent($model)
            ->only(['id', 'name', 'email', 'expire_at'])
            ->editColumn('expire_at', function (User $user) {
                return !empty($user->expire_at) ? Carbon::parse($user->expire_at)->format('d.m.Y') : '';
            })
            ->toJson();
    }

    public function store(StoreDoctorRequest $request)
    {
        $fields = $request->validated();
        DB::beginTransaction();


        $user = !empty($request->id) ? User::findOrFail($request->id) : new User();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->expire_at = $request->expire_at;
        $user->save();

        Subscription::store($user, $request->expire_at, $request->minutes);

        $user->assignRole(User::ROLE_DOCTOR);
        DB::commit();



        return response()->json([
            'message' => 'User saved successfully',
            'user' => $user
        ]);
    }

    public function get(User $user)
    {
        return new UserResource($user);
    }

    public function delete(Request $request)
    {
        $doctor = User::findOrFail($request->input('id'));
        $doctor->remove();
    }
}
