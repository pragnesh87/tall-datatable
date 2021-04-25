<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Sub Admin', 'slug' => 'sub-admin'],
            ['name' => 'Employee', 'slug' => 'employee'],
            ['name' => 'Manager', 'slug' => 'manager'],
        ]);
    }
}
