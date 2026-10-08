<?php

namespace Modules\User\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        Fortify::loginView(function () {
            return Inertia::render('User::Auth/Login');
        });

        Fortify::registerView(function () {
            return Inertia::render('User::Auth/Register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('User::Auth/ForgotPassword');
        });

        Fortify::resetPasswordView(function () {
            return Inertia::render('User::Auth/ResetPassword');
        });

        // Register Gate::before for backward compatibility with broad permissions
        Gate::before(function ($user, $ability) {
            // Map broad permissions to granular ones
            $broadToGranular = [
                'Settings Management' => [
                    'settings.view',
                    'settings.website-config.view',
                    'settings.website-config.create',
                    'settings.website-config.edit',
                    'settings.website-config.delete',
                    'settings.seo.view',
                    'settings.seo.create',
                    'settings.seo.edit',
                    'settings.seo.delete',
                    'settings.api-config.view',
                    'settings.api-config.create',
                    'settings.api-config.edit',
                    'settings.api-config.delete',
                    'settings.currencies.view',
                    'settings.currencies.create',
                    'settings.currencies.edit',
                    'settings.currencies.delete',
                ],
                'CMS Management' => [
                    'cms.view',
                    'cms.pages.view',
                    'cms.pages.create',
                    'cms.pages.edit',
                    'cms.pages.delete',
                    'cms.blog-categories.view',
                    'cms.blog-categories.create',
                    'cms.blog-categories.edit',
                    'cms.blog-categories.delete',
                    'cms.blogs.view',
                    'cms.blogs.create',
                    'cms.blogs.edit',
                    'cms.blogs.delete',
                    'cms.faqs.view',
                    'cms.faqs.create',
                    'cms.faqs.edit',
                    'cms.faqs.delete',
                ],
                'Media Library Management' => [
                    'media-library.view',
                    'media-library.create',
                    'media-library.edit',
                    'media-library.delete',
                ],
                'Shop Management' => [
                    'shop.view',
                    'shop.attributes.view',
                    'shop.attributes.create',
                    'shop.attributes.edit',
                    'shop.attributes.delete',
                    'shop.attribute-families.view',
                    'shop.attribute-families.create',
                    'shop.attribute-families.edit',
                    'shop.attribute-families.delete',
                ],
                'Support Management' => [
                    'support.view',
                    'support.contact-forms.view',
                    'support.contact-forms.create',
                    'support.contact-forms.edit',
                    'support.contact-forms.delete',
                    'support.subscribers.view',
                    'support.subscribers.create',
                    'support.subscribers.edit',
                    'support.subscribers.delete',
                ],
                'Hr Management' => [
                    'hr.view',
                    'hr.roles.view',
                    'hr.roles.create',
                    'hr.roles.edit',
                    'hr.roles.delete',
                    'hr.staffs.view',
                    'hr.staffs.create',
                    'hr.staffs.edit',
                    'hr.staffs.delete',
                    'hr.users.view',
                    'hr.users.create',
                    'hr.users.edit',
                    'hr.users.delete',
                ],
                'Logs Management' => [
                    'logs.view',
                    'logs.delete',
                ],
                'App Monitoring' => [
                    'app-monitoring.view',
                ],
            ];

            // Check if the ability is a granular permission that can be implied by a broad permission
            foreach ($broadToGranular as $broadPermission => $granularPermissions) {
                if (in_array($ability, $granularPermissions) && $user->can($broadPermission)) {
                    return true;
                }
            }

            return null; // Continue to normal permission check
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}