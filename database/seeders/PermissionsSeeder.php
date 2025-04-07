<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            /* ------------------------------- Admin Roles ------------------------------ */
            [
                'name' => 'roles-list',
                'display_name' => 'Admin Role List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'roles-create',
                'display_name' => 'Admin Role Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'roles-view',
                'display_name' => 'Admin Role View',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'roles-edit',
                'display_name' => 'Admin Role Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'roles-delete',
                'display_name' => 'Admin Role Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],


            /* ---------------------------- Admin Permissions --------------------------- */
            [
                'name' => 'permissions-list',
                'display_name' => 'Admin Permission List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'permissions-edit',
                'display_name' => 'Admin Permission Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            /* ------------------------------ Dashboard ----------------------------- */
            [
                'name' => 'dashboard-view',
                'display_name' => 'Dashboard View',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            /* ---------------------------------- Users Staff --------------------------------- */
            [
                'name' => 'users-list',
                'display_name' => 'User List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'users-view',
                'display_name' => 'User View',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'users-create',
                'display_name' => 'User Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'users-edit',
                'display_name' => 'User Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'users-delete',
                'display_name' => 'User Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'users-profile',
                'display_name' => 'User Profile',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],


            /* ---------------------------------- AdminSetting --------------------------------- */
            [
                'name' => 'adminSettings-list',
                'display_name' => 'adminSettings List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'adminSettings-create',
                'display_name' => 'adminSettings Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'adminSettings-edit',
                'display_name' => 'adminSettings Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'adminSettings-delete',
                'display_name' => 'adminSettings Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            /* ---------------------------------- Product Category --------------------------------- */
            [
                'name' => 'productCategory-list',
                'display_name' => 'productCategory List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'productCategory-create',
                'display_name' => 'productCategory Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'productCategory-edit',
                'display_name' => 'productCategory Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'productCategory-delete',
                'display_name' => 'productCategory Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],


             /* ---------------------------------- services Permission --------------------------------- */
            [
                'name' => 'products-list',
                'display_name' => 'products List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'products-create',
                'display_name' => 'products Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'products-edit',
                'display_name' => 'products Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'products-view',
                'display_name' => 'products Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'products-delete',
                'display_name' => 'products Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],





            /* ---------------------------------- aboutUs --------------------------------- */
            [
                'name' => 'aboutUs-list',
                'display_name' => 'About Us List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'aboutUs-create',
                'display_name' => 'About Us Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'aboutUs-edit',
                'display_name' => 'About Us Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'aboutUs-delete',
                'display_name' => 'About Us Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],


            /* ---------------------------------- blogs --------------------------------- */
            [
                'name' => 'blogs-list',
                'display_name' => 'blogs List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'blogs-create',
                'display_name' => 'blogs Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'blogs-edit',
                'display_name' => 'blogs Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'blogs-delete',
                'display_name' => 'blogs Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],



            /* ---------------------------------- hompage --------------------------------- */
            [
                'name' => 'homepage-list',
                'display_name' => 'homepage List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'homepage-create',
                'display_name' => 'homepage  Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'homepage-edit',
                'display_name' => 'homepage Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'homepage-delete',
                'display_name' => 'homepage Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],






            /* ---------------------------------- faqs --------------------------------- */
            [
                'name' => 'faqs-list',
                'display_name' => 'faqs List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'faqs-create',
                'display_name' => 'faqs  Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'faqs-edit',
                'display_name' => 'faqs Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'faqs-delete',
                'display_name' => 'faqs Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],


            /* ---------------------------------- privacy policy --------------------------------- */
            [
                'name' => 'privacyPolicy-list',
                'display_name' => 'privacyPolicy List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'privacyPolicy-create',
                'display_name' => 'privacyPolicy  Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'privacyPolicy-edit',
                'display_name' => 'privacyPolicy Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'privacyPolicy-delete',
                'display_name' => 'privacyPolicy Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],


            /* ---------------------------------- term conditions --------------------------------- */
            [
                'name' => 'termConditions-list',
                'display_name' => 'termConditions List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'termConditions-create',
                'display_name' => 'termConditions  Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'termConditions-edit',
                'display_name' => 'termConditions Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'termConditions-delete',
                'display_name' => 'termConditions Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],



        ]);
    }
}

