<?php

namespace Database\Seeders;

use App\Models\Reference;
use App\Models\Region;
use Illuminate\Database\Seeder;

class ReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Reference::getInitValues() as $refType => $refValues) {
            foreach ($refValues as $label) {
                Reference::firstOrCreate([
                    'key_' => $refType,
                    'label' => $label,
                ]);
            }
        };
    }
}
