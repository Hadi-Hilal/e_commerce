<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardCategoryPermissionTest extends TestCase
{
    public function test_dashboard_renders_for_user_with_category_permissions(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create(['type' => 'admin']);
        }

        $user->givePermissionTo('dashboard.view');
        $user->givePermissionTo('shop.categories.view');

        $this->withoutMiddleware([
            \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Categories');
    }
}
