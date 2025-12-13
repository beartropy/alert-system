<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Beartropy\AlertSystem\Models\AlertType;

class AlertTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['System', 'GRC', 'User'] as $type) {
            AlertType::firstOrCreate(['name' => $type]);
        }
    }
}
