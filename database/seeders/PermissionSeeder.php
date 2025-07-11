<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'quote_access',
            'quote_add',
            'quote_edit',
            'quote_delete',
            'quote_to_invoice',
            'invoice_access',
            'invoice_add',
            'invoice_edit',
            'invoice_delete',
            'tax_access',
            'tax_add',
            'tax_edit',
            'tax_delete',
            'product_list',
            'product_add',
            'product_edit',
            'product_delete',
            'category_access',
            'category_add',
            'category_edit',
            'category_delete',
            'client_list',
            'client_add',
            'client_edit',
            'client_delete',
            'user_list',
            'user_add',
            'user_edit',
            'user_delete',
            'setting',
            'db_backup',
            'role_management',
            'activity_log',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
