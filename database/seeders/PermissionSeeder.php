<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
      /**
      * Run the database seeds.
      *
      * @return void
      */
      public function run()
      {
            $permissions = [
                  'roles-list',
                  'roles-view',
                  'roles-create',
                  'roles-edit',
                  'roles-delete',

                  'users-list',
                  'users-view',
                  'users-create',
                  'users-edit',
                  'users-delete',

                  'customers-list',
                  'customers-view',
                  'customers-create',
                  'customers-edit',
                  'customers-delete',

                'templates-list',
                  'templates-view',
                  'templates-create',
                  'templates-edit',
                  'templates-delete',

                  'audits-list',
                  'audits-view',
                  'audits-create',
                  'audits-edit',
                  'audits-delete',

                  'logs-list',
                  'logs-view',
                  'logs-create',
                  'logs-edit',
                  'logs-delete',

                  'settings-list',
                  'settings-create',
            ];

            foreach ($permissions as $permission) {
                  Permission::create(['name' => $permission]);
            }
      }
}
