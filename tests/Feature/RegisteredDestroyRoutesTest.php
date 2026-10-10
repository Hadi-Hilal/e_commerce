<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RegisteredDestroyRoutesTest extends TestCase
{
    public function test_registered_module_destroy_routes_target_existing_controller_actions(): void
    {
        $routeNames = [
            'admin.currencies.destroy',
            'admin.pages.destroy',
            'admin.blogs.destroy',
            'admin.blogs_categories.destroy',
            'admin.faqs.destroy',
            'admin.attributes.destroy',
            'admin.attribute_families.destroy',
        ];

        foreach ($routeNames as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);

            $this->assertNotNull($route, "Route [{$routeName}] is registered.");
            $controller = $route->getControllerClass();
            $action = $route->getActionMethod();

            $this->assertTrue(
                method_exists($controller, $action),
                "Route [{$routeName}] targets missing action [{$controller}::{$action}]."
            );
        }
    }
}
