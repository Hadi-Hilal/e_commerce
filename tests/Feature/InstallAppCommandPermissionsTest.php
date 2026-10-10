<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class InstallAppCommandPermissionsTest extends TestCase
{
    public function test_install_command_registers_category_and_slide_permissions(): void
    {
        $permissionsToCheck = [
            'cms.slides.view',
            'cms.slides.create',
            'cms.slides.edit',
            'cms.slides.delete',
            'shop.categories.view',
            'shop.categories.create',
            'shop.categories.edit',
            'shop.categories.delete',
        ];

        // Call the app:install command without fresh or sql import to trigger createPermissions
        $this->artisan('app:install', [
            '--skip-sql' => true,
        ])->assertSuccessful();

        foreach ($permissionsToCheck as $permissionName) {
            $this->assertDatabaseHas('permissions', [
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }
    }
}
