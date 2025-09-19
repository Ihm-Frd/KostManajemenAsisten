<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\DataPenghuniSeeder;
use Database\Seeders\DataKamarSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DataKamarSeeder::class,
            UserSeeder::class,
            DataPenghuniSeeder::class,
        ]);
    }
}
