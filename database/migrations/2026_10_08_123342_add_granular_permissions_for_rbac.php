<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'guard_name' => 'web'],

            // Profile
            ['name' => 'profile.view', 'guard_name' => 'web'],
            ['name' => 'profile.edit', 'guard_name' => 'web'],

            // Settings Management
            ['name' => 'settings.view', 'guard_name' => 'web'],
            ['name' => 'settings.website-config.view', 'guard_name' => 'web'],
            ['name' => 'settings.website-config.create', 'guard_name' => 'web'],
            ['name' => 'settings.website-config.edit', 'guard_name' => 'web'],
            ['name' => 'settings.website-config.delete', 'guard_name' => 'web'],
            ['name' => 'settings.seo.view', 'guard_name' => 'web'],
            ['name' => 'settings.seo.create', 'guard_name' => 'web'],
            ['name' => 'settings.seo.edit', 'guard_name' => 'web'],
            ['name' => 'settings.seo.delete', 'guard_name' => 'web'],
            ['name' => 'settings.api-config.view', 'guard_name' => 'web'],
            ['name' => 'settings.api-config.create', 'guard_name' => 'web'],
            ['name' => 'settings.api-config.edit', 'guard_name' => 'web'],
            ['name' => 'settings.api-config.delete', 'guard_name' => 'web'],
            ['name' => 'settings.currencies.view', 'guard_name' => 'web'],
            ['name' => 'settings.currencies.create', 'guard_name' => 'web'],
            ['name' => 'settings.currencies.edit', 'guard_name' => 'web'],
            ['name' => 'settings.currencies.delete', 'guard_name' => 'web'],

            // CMS Management
            ['name' => 'cms.view', 'guard_name' => 'web'],
            ['name' => 'cms.pages.view', 'guard_name' => 'web'],
            ['name' => 'cms.pages.create', 'guard_name' => 'web'],
            ['name' => 'cms.pages.edit', 'guard_name' => 'web'],
            ['name' => 'cms.pages.delete', 'guard_name' => 'web'],
            ['name' => 'cms.blog-categories.view', 'guard_name' => 'web'],
            ['name' => 'cms.blog-categories.create', 'guard_name' => 'web'],
            ['name' => 'cms.blog-categories.edit', 'guard_name' => 'web'],
            ['name' => 'cms.blog-categories.delete', 'guard_name' => 'web'],
            ['name' => 'cms.blogs.view', 'guard_name' => 'web'],
            ['name' => 'cms.blogs.create', 'guard_name' => 'web'],
            ['name' => 'cms.blogs.edit', 'guard_name' => 'web'],
            ['name' => 'cms.blogs.delete', 'guard_name' => 'web'],
            ['name' => 'cms.faqs.view', 'guard_name' => 'web'],
            ['name' => 'cms.faqs.create', 'guard_name' => 'web'],
            ['name' => 'cms.faqs.edit', 'guard_name' => 'web'],
            ['name' => 'cms.faqs.delete', 'guard_name' => 'web'],

            // Media Library Management
            ['name' => 'media-library.view', 'guard_name' => 'web'],
            ['name' => 'media-library.create', 'guard_name' => 'web'],
            ['name' => 'media-library.edit', 'guard_name' => 'web'],
            ['name' => 'media-library.delete', 'guard_name' => 'web'],

            // Shop Management
            ['name' => 'shop.view', 'guard_name' => 'web'],
            ['name' => 'shop.attributes.view', 'guard_name' => 'web'],
            ['name' => 'shop.attributes.create', 'guard_name' => 'web'],
            ['name' => 'shop.attributes.edit', 'guard_name' => 'web'],
            ['name' => 'shop.attributes.delete', 'guard_name' => 'web'],
            ['name' => 'shop.attribute-families.view', 'guard_name' => 'web'],
            ['name' => 'shop.attribute-families.create', 'guard_name' => 'web'],
            ['name' => 'shop.attribute-families.edit', 'guard_name' => 'web'],
            ['name' => 'shop.attribute-families.delete', 'guard_name' => 'web'],

            // Support Management
            ['name' => 'support.view', 'guard_name' => 'web'],
            ['name' => 'support.contact-forms.view', 'guard_name' => 'web'],
            ['name' => 'support.contact-forms.create', 'guard_name' => 'web'],
            ['name' => 'support.contact-forms.edit', 'guard_name' => 'web'],
            ['name' => 'support.contact-forms.delete', 'guard_name' => 'web'],
            ['name' => 'support.subscribers.view', 'guard_name' => 'web'],
            ['name' => 'support.subscribers.create', 'guard_name' => 'web'],
            ['name' => 'support.subscribers.edit', 'guard_name' => 'web'],
            ['name' => 'support.subscribers.delete', 'guard_name' => 'web'],

            // HR Management
            ['name' => 'hr.view', 'guard_name' => 'web'],
            ['name' => 'hr.roles.view', 'guard_name' => 'web'],
            ['name' => 'hr.roles.create', 'guard_name' => 'web'],
            ['name' => 'hr.roles.edit', 'guard_name' => 'web'],
            ['name' => 'hr.roles.delete', 'guard_name' => 'web'],
            ['name' => 'hr.staffs.view', 'guard_name' => 'web'],
            ['name' => 'hr.staffs.create', 'guard_name' => 'web'],
            ['name' => 'hr.staffs.edit', 'guard_name' => 'web'],
            ['name' => 'hr.staffs.delete', 'guard_name' => 'web'],
            ['name' => 'hr.users.view', 'guard_name' => 'web'],
            ['name' => 'hr.users.create', 'guard_name' => 'web'],
            ['name' => 'hr.users.edit', 'guard_name' => 'web'],
            ['name' => 'hr.users.delete', 'guard_name' => 'web'],

            // Logs Management
            ['name' => 'logs.view', 'guard_name' => 'web'],
            ['name' => 'logs.delete', 'guard_name' => 'web'],

            // App Monitoring (Telescope)
            ['name' => 'app-monitoring.view', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissionNames = array_column([
            ['name' => 'dashboard.view'],
            ['name' => 'profile.view'],
            ['name' => 'profile.edit'],
            ['name' => 'settings.view'],
            ['name' => 'settings.website-config.view'],
            ['name' => 'settings.website-config.create'],
            ['name' => 'settings.website-config.edit'],
            ['name' => 'settings.website-config.delete'],
            ['name' => 'settings.seo.view'],
            ['name' => 'settings.seo.create'],
            ['name' => 'settings.seo.edit'],
            ['name' => 'settings.seo.delete'],
            ['name' => 'settings.api-config.view'],
            ['name' => 'settings.api-config.create'],
            ['name' => 'settings.api-config.edit'],
            ['name' => 'settings.api-config.delete'],
            ['name' => 'settings.currencies.view'],
            ['name' => 'settings.currencies.create'],
            ['name' => 'settings.currencies.edit'],
            ['name' => 'settings.currencies.delete'],
            ['name' => 'cms.view'],
            ['name' => 'cms.pages.view'],
            ['name' => 'cms.pages.create'],
            ['name' => 'cms.pages.edit'],
            ['name' => 'cms.pages.delete'],
            ['name' => 'cms.blog-categories.view'],
            ['name' => 'cms.blog-categories.create'],
            ['name' => 'cms.blog-categories.edit'],
            ['name' => 'cms.blog-categories.delete'],
            ['name' => 'cms.blogs.view'],
            ['name' => 'cms.blogs.create'],
            ['name' => 'cms.blogs.edit'],
            ['name' => 'cms.blogs.delete'],
            ['name' => 'cms.faqs.view'],
            ['name' => 'cms.faqs.create'],
            ['name' => 'cms.faqs.edit'],
            ['name' => 'cms.faqs.delete'],
            ['name' => 'media-library.view'],
            ['name' => 'media-library.create'],
            ['name' => 'media-library.edit'],
            ['name' => 'media-library.delete'],
            ['name' => 'shop.view'],
            ['name' => 'shop.attributes.view'],
            ['name' => 'shop.attributes.create'],
            ['name' => 'shop.attributes.edit'],
            ['name' => 'shop.attributes.delete'],
            ['name' => 'shop.attribute-families.view'],
            ['name' => 'shop.attribute-families.create'],
            ['name' => 'shop.attribute-families.edit'],
            ['name' => 'shop.attribute-families.delete'],
            ['name' => 'support.view'],
            ['name' => 'support.contact-forms.view'],
            ['name' => 'support.contact-forms.create'],
            ['name' => 'support.contact-forms.edit'],
            ['name' => 'support.contact-forms.delete'],
            ['name' => 'support.subscribers.view'],
            ['name' => 'support.subscribers.create'],
            ['name' => 'support.subscribers.edit'],
            ['name' => 'support.subscribers.delete'],
            ['name' => 'hr.view'],
            ['name' => 'hr.roles.view'],
            ['name' => 'hr.roles.create'],
            ['name' => 'hr.roles.edit'],
            ['name' => 'hr.roles.delete'],
            ['name' => 'hr.staffs.view'],
            ['name' => 'hr.staffs.create'],
            ['name' => 'hr.staffs.edit'],
            ['name' => 'hr.staffs.delete'],
            ['name' => 'hr.users.view'],
            ['name' => 'hr.users.create'],
            ['name' => 'hr.users.edit'],
            ['name' => 'hr.users.delete'],
            ['name' => 'logs.view'],
            ['name' => 'logs.delete'],
            ['name' => 'app-monitoring.view'],
        ], 'name');

        Permission::whereIn('name', $permissionNames)->where('guard_name', 'web')->delete();
    }
};