<?php

namespace Modules\Shop\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ShopDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Shop group
            'Shop Management',
            
            // Attributes permissions
            'shop.attributes.view',
            'shop.attributes.create',
            'shop.attributes.edit',
            'shop.attributes.delete',
            
            // Attribute Families permissions
            'shop.attribute_families.view',
            'shop.attribute_families.create',
            'shop.attribute_families.edit',
            'shop.attribute_families.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}