<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Nonaktifkan pemeriksaan foreign key
        Schema::disableForeignKeyConstraints();

        // Bersihkan data dari tabel-tabel
        DB::table('tasks')->truncate();
        DB::table('priorities')->truncate();

        // Aktifkan kembali pemeriksaan foreign key
        Schema::enableForeignKeyConstraints();
    }
}
