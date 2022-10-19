<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('permissions') && DB::table('permissions')->count() == 0) {
            $permissions = [
                ['name' => 'view_admin_dashboard', 'title' => 'View Admin Dashboard'],
                ['name' => 'view_user_dashboard', 'title' => 'View User Dashboard'],
            ];
            DB::table('permissions')
                ->insert($permissions);
        }

    }
}
