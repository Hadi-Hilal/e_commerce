<?php

namespace Modules\Core\Console;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InstallAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:install
                            {--fresh : Drop all tables and re-run all migrations}
                            {--skip-sql : Skip importing Modules/Core/database/db.sql}
                            {--admin-name=Admin : Initial admin display name}
                            {--admin-email=admin@example.com : Initial admin email}
                            {--admin-password=12345678 : Initial admin password}
                            {--admin-mobile=0905000000000 : Initial admin mobile}';

    /**
     * The console command description.
     */
    protected $description = 'This Command Will Install App.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Starting HadoSaaS installation...');

        if (! config('app.key')) {
            $this->components->task('Generating APP_KEY', function () {
                Artisan::call('key:generate', ['--force' => true]);
            });
        } else {
            $this->line('APP_KEY already exists, skipping key generation.');
        }

        $this->runMigrations();

        // Create all permissions (broad + granular) after migrations
        $this->createPermissions();

        if (! $this->option('skip-sql')) {
            if (! $this->importCoreSqlDump()) {
                return self::FAILURE;
            }
        }

        $this->bootstrapAdmin();

        $this->newLine();
        $this->components->info('Application installed successfully.');
        $this->components->twoColumnDetail('Admin Email', $this->option('admin-email'));
        $this->components->twoColumnDetail('Admin Password', $this->option('admin-password'));

        return self::SUCCESS;
    }

    private function runMigrations(): void
    {
        if ($this->option('fresh')) {
            $this->components->task('Running migrate:fresh', function () {
                Artisan::call('migrate:fresh', ['--force' => true]);
                $this->output->write(Artisan::output());
            });

            return;
        }

        $this->components->task('Running migrations', function () {
            Artisan::call('migrate', ['--force' => true]);
            $this->output->write(Artisan::output());
        });
    }

    /**
     * Create all permissions (broad for backward compatibility + granular for new RBAC)
     */
    private function createPermissions(): void
    {
        $this->components->task('Creating permissions', function () {
            // Broad permissions (for backward compatibility with existing @can checks)
            $broadPermissions = [
                'Settings Management',
                'CMS Management',
                'Media Library Management',
                'Shop Management',
                'Support Management',
                'Hr Management',
                'Logs Management',
                'App Monitoring',
            ];

            foreach ($broadPermissions as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }

            // Granular permissions (new RBAC system)
            $granularPermissions = [
                // Dashboard
                'dashboard.view',

                // Profile
                'profile.view',
                'profile.edit',

                // Settings Management
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

                // CMS Management
                'cms.view',
                'cms.pages.view',
                'cms.pages.create',
                'cms.pages.edit',
                'cms.pages.delete',
                'cms.slides.view',
                'cms.slides.create',
                'cms.slides.edit',
                'cms.slides.delete',
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

                // Media Library Management
                'media-library.view',
                'media-library.create',
                'media-library.edit',
                'media-library.delete',

                // Shop Management
                'shop.view',
                'shop.attributes.view',
                'shop.attributes.create',
                'shop.attributes.edit',
                'shop.attributes.delete',
                'shop.attribute-families.view',
                'shop.attribute-families.create',
                'shop.attribute-families.edit',
                'shop.attribute-families.delete',
                'shop.categories.view',
                'shop.categories.create',
                'shop.categories.edit',
                'shop.categories.delete',

                // Support Management
                'support.view',
                'support.contact-forms.view',
                'support.contact-forms.create',
                'support.contact-forms.edit',
                'support.contact-forms.delete',
                'support.subscribers.view',
                'support.subscribers.create',
                'support.subscribers.edit',
                'support.subscribers.delete',

                // HR Management
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

                // Logs Management
                'logs.view',
                'logs.delete',

                // App Monitoring
                'app-monitoring.view',
            ];

            foreach ($granularPermissions as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }

            $this->line('Created ' . count($broadPermissions) . ' broad permissions and ' . count($granularPermissions) . ' granular permissions.');
        });
    }

    private function importCoreSqlDump(): bool
    {
        $sqlFilePath = module_path('Core', 'database/db.sql');
        if (! file_exists($sqlFilePath)) {
            $this->components->error('SQL file not found at path: '.$sqlFilePath);

            return false;
        }

        if ($this->coreSeedDataAlreadyImported()) {
            $this->line('Core SQL data already exists, skipping SQL import.');

            return true;
        }

        $this->components->task('Importing Core SQL dump', function () use ($sqlFilePath) {
            DB::unprepared(file_get_contents($sqlFilePath));
        });

        return true;
    }

    private function coreSeedDataAlreadyImported(): bool
    {
        // Only treat Core SQL as imported when seed data that lives exclusively in db.sql is present.
        // Do not use `permissions`: migrations may insert rows before import (e.g. module permissions),
        // which would skip the dump and leave Admin with only those migration-defined permissions.
        return Schema::hasTable('countries') && DB::table('countries')->exists();
    }

    private function bootstrapAdmin(): void
    {
        DB::transaction(function () {
            $adminEmail = (string) $this->option('admin-email');
            $adminMobile = (string) $this->option('admin-mobile');

            $role = Role::firstOrCreate([
                'name' => 'Admin',
                'guard_name' => 'web',
            ]);
            $role->syncPermissions(Permission::all());

            $userAttributes = [
                'name' => $this->option('admin-name'),
                'password' => Hash::make((string) $this->option('admin-password')),
                'email' => $adminEmail,
                'mobile' => $adminMobile,
                'type' => 'admin',
            ];

            $user = User::query()
                ->where('email', $adminEmail)
                ->orWhere('mobile', $adminMobile)
                ->first();

            if ($user) {
                $user->fill($userAttributes)->save();
            } else {
                $user = User::query()->create($userAttributes);
            }

            if (! $user->hasRole($role->name)) {
                $user->assignRole($role);
            }
        });
    }
}
