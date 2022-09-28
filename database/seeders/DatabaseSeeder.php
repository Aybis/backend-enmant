<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(RegionalSeeder::class);
        $this->call(WitelSeeder::class);
        $this->call(PICSeeder::class);
        $this->call(BiayaAdminSeeder::class);
        $this->call(DayaSeeder::class);
        $this->call(TarifSeeder::class);
        $this->call(PelangganSeeder::class);
    }
}
