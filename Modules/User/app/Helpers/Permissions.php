<?php

namespace Modules\User\Helpers;

use Spatie\Permission\Models\Permission;

class Permissions
{
    /**
     * Get permissions organized by sidebar section/module
     *
     * @return array
     */
    public static function organized(): array
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'icon' => 'bi-speedometer2',
                'permissions' => [
                    ['name' => 'dashboard.view', 'label' => 'View', 'action' => 'view'],
                ],
            ],
            'settings' => [
                'label' => 'Settings',
                'icon' => 'bi-gear',
                'permissions' => [
                    ['name' => 'settings.view', 'label' => 'Settings Overview', 'action' => 'view'],
                    ['name' => 'settings.website-config.view', 'label' => 'Website Config - View', 'action' => 'view', 'group' => 'Website Configurations'],
                    ['name' => 'settings.website-config.create', 'label' => 'Website Config - Create', 'action' => 'create', 'group' => 'Website Configurations'],
                    ['name' => 'settings.website-config.edit', 'label' => 'Website Config - Edit', 'action' => 'edit', 'group' => 'Website Configurations'],
                    ['name' => 'settings.website-config.delete', 'label' => 'Website Config - Delete', 'action' => 'delete', 'group' => 'Website Configurations'],
                    ['name' => 'settings.seo.view', 'label' => 'SEO Config - View', 'action' => 'view', 'group' => 'SEO Configurations'],
                    ['name' => 'settings.seo.create', 'label' => 'SEO Config - Create', 'action' => 'create', 'group' => 'SEO Configurations'],
                    ['name' => 'settings.seo.edit', 'label' => 'SEO Config - Edit', 'action' => 'edit', 'group' => 'SEO Configurations'],
                    ['name' => 'settings.seo.delete', 'label' => 'SEO Config - Delete', 'action' => 'delete', 'group' => 'SEO Configurations'],
                    ['name' => 'settings.api-config.view', 'label' => 'API Config - View', 'action' => 'view', 'group' => 'API Configurations'],
                    ['name' => 'settings.api-config.create', 'label' => 'API Config - Create', 'action' => 'create', 'group' => 'API Configurations'],
                    ['name' => 'settings.api-config.edit', 'label' => 'API Config - Edit', 'action' => 'edit', 'group' => 'API Configurations'],
                    ['name' => 'settings.api-config.delete', 'label' => 'API Config - Delete', 'action' => 'delete', 'group' => 'API Configurations'],
                    ['name' => 'settings.currencies.view', 'label' => 'Currencies - View', 'action' => 'view', 'group' => 'Currencies'],
                    ['name' => 'settings.currencies.create', 'label' => 'Currencies - Create', 'action' => 'create', 'group' => 'Currencies'],
                    ['name' => 'settings.currencies.edit', 'label' => 'Currencies - Edit', 'action' => 'edit', 'group' => 'Currencies'],
                    ['name' => 'settings.currencies.delete', 'label' => 'Currencies - Delete', 'action' => 'delete', 'group' => 'Currencies'],
                ],
            ],
            'cms' => [
                'label' => 'CMS',
                'icon' => 'bi-intersect',
                'permissions' => [
                    ['name' => 'cms.view', 'label' => 'CMS Overview', 'action' => 'view'],
                    ['name' => 'cms.pages.view', 'label' => 'Pages - View', 'action' => 'view', 'group' => 'Pages'],
                    ['name' => 'cms.pages.create', 'label' => 'Pages - Create', 'action' => 'create', 'group' => 'Pages'],
                    ['name' => 'cms.pages.edit', 'label' => 'Pages - Edit', 'action' => 'edit', 'group' => 'Pages'],
                    ['name' => 'cms.pages.delete', 'label' => 'Pages - Delete', 'action' => 'delete', 'group' => 'Pages'],
                    ['name' => 'cms.slides.view', 'label' => 'Slides - View', 'action' => 'view', 'group' => 'Slides'],
                    ['name' => 'cms.slides.create', 'label' => 'Slides - Create', 'action' => 'create', 'group' => 'Slides'],
                    ['name' => 'cms.slides.edit', 'label' => 'Slides - Edit', 'action' => 'edit', 'group' => 'Slides'],
                    ['name' => 'cms.slides.delete', 'label' => 'Slides - Delete', 'action' => 'delete', 'group' => 'Slides'],
                    ['name' => 'cms.blog-categories.view', 'label' => 'Blog Categories - View', 'action' => 'view', 'group' => 'Blog Categories'],
                    ['name' => 'cms.blog-categories.create', 'label' => 'Blog Categories - Create', 'action' => 'create', 'group' => 'Blog Categories'],
                    ['name' => 'cms.blog-categories.edit', 'label' => 'Blog Categories - Edit', 'action' => 'edit', 'group' => 'Blog Categories'],
                    ['name' => 'cms.blog-categories.delete', 'label' => 'Blog Categories - Delete', 'action' => 'delete', 'group' => 'Blog Categories'],
                    ['name' => 'cms.blogs.view', 'label' => 'Blogs - View', 'action' => 'view', 'group' => 'Blogs'],
                    ['name' => 'cms.blogs.create', 'label' => 'Blogs - Create', 'action' => 'create', 'group' => 'Blogs'],
                    ['name' => 'cms.blogs.edit', 'label' => 'Blogs - Edit', 'action' => 'edit', 'group' => 'Blogs'],
                    ['name' => 'cms.blogs.delete', 'label' => 'Blogs - Delete', 'action' => 'delete', 'group' => 'Blogs'],
                    ['name' => 'cms.faqs.view', 'label' => 'FAQs - View', 'action' => 'view', 'group' => 'FAQs'],
                    ['name' => 'cms.faqs.create', 'label' => 'FAQs - Create', 'action' => 'create', 'group' => 'FAQs'],
                    ['name' => 'cms.faqs.edit', 'label' => 'FAQs - Edit', 'action' => 'edit', 'group' => 'FAQs'],
                    ['name' => 'cms.faqs.delete', 'label' => 'FAQs - Delete', 'action' => 'delete', 'group' => 'FAQs'],
                ],
            ],
            'media-library' => [
                'label' => 'Media Library',
                'icon' => 'bi-images',
                'permissions' => [
                    ['name' => 'media-library.view', 'label' => 'View', 'action' => 'view'],
                    ['name' => 'media-library.create', 'label' => 'Upload', 'action' => 'create'],
                    ['name' => 'media-library.edit', 'label' => 'Edit', 'action' => 'edit'],
                    ['name' => 'media-library.delete', 'label' => 'Delete', 'action' => 'delete'],
                ],
            ],
            'shop' => [
                'label' => 'Shop',
                'icon' => 'bi-shop',
                'permissions' => [
                    ['name' => 'shop.view', 'label' => 'Shop Overview', 'action' => 'view'],
                    ['name' => 'shop.attributes.view', 'label' => 'Attributes - View', 'action' => 'view', 'group' => 'Attributes'],
                    ['name' => 'shop.attributes.create', 'label' => 'Attributes - Create', 'action' => 'create', 'group' => 'Attributes'],
                    ['name' => 'shop.attributes.edit', 'label' => 'Attributes - Edit', 'action' => 'edit', 'group' => 'Attributes'],
                    ['name' => 'shop.attributes.delete', 'label' => 'Attributes - Delete', 'action' => 'delete', 'group' => 'Attributes'],
                    ['name' => 'shop.attribute-families.view', 'label' => 'Attribute Families - View', 'action' => 'view', 'group' => 'Attribute Families'],
                    ['name' => 'shop.attribute-families.create', 'label' => 'Attribute Families - Create', 'action' => 'create', 'group' => 'Attribute Families'],
                    ['name' => 'shop.attribute-families.edit', 'label' => 'Attribute Families - Edit', 'action' => 'edit', 'group' => 'Attribute Families'],
                    ['name' => 'shop.attribute-families.delete', 'label' => 'Attribute Families - Delete', 'action' => 'delete', 'group' => 'Attribute Families'],
                    ['name' => 'shop.categories.view', 'label' => 'Categories - View', 'action' => 'view', 'group' => 'Categories'],
                    ['name' => 'shop.categories.create', 'label' => 'Categories - Create', 'action' => 'create', 'group' => 'Categories'],
                    ['name' => 'shop.categories.edit', 'label' => 'Categories - Edit', 'action' => 'edit', 'group' => 'Categories'],
                    ['name' => 'shop.categories.delete', 'label' => 'Categories - Delete', 'action' => 'delete', 'group' => 'Categories'],
                ],
            ],
            'support' => [
                'label' => 'Support Hub',
                'icon' => 'bi-headset',
                'permissions' => [
                    ['name' => 'support.view', 'label' => 'Support Overview', 'action' => 'view'],
                    ['name' => 'support.contact-forms.view', 'label' => 'Contact Forms - View', 'action' => 'view', 'group' => 'Contact Forms'],
                    ['name' => 'support.contact-forms.create', 'label' => 'Contact Forms - Create', 'action' => 'create', 'group' => 'Contact Forms'],
                    ['name' => 'support.contact-forms.edit', 'label' => 'Contact Forms - Edit', 'action' => 'edit', 'group' => 'Contact Forms'],
                    ['name' => 'support.contact-forms.delete', 'label' => 'Contact Forms - Delete', 'action' => 'delete', 'group' => 'Contact Forms'],
                    ['name' => 'support.subscribers.view', 'label' => 'Subscribers - View', 'action' => 'view', 'group' => 'Newsletter Subscribers'],
                    ['name' => 'support.subscribers.create', 'label' => 'Subscribers - Create', 'action' => 'create', 'group' => 'Newsletter Subscribers'],
                    ['name' => 'support.subscribers.edit', 'label' => 'Subscribers - Edit', 'action' => 'edit', 'group' => 'Newsletter Subscribers'],
                    ['name' => 'support.subscribers.delete', 'label' => 'Subscribers - Delete', 'action' => 'delete', 'group' => 'Newsletter Subscribers'],
                ],
            ],
            'hr' => [
                'label' => 'HR',
                'icon' => 'bi-journal-text',
                'permissions' => [
                    ['name' => 'hr.view', 'label' => 'HR Overview', 'action' => 'view'],
                    ['name' => 'hr.roles.view', 'label' => 'Roles - View', 'action' => 'view', 'group' => 'Roles'],
                    ['name' => 'hr.roles.create', 'label' => 'Roles - Create', 'action' => 'create', 'group' => 'Roles'],
                    ['name' => 'hr.roles.edit', 'label' => 'Roles - Edit', 'action' => 'edit', 'group' => 'Roles'],
                    ['name' => 'hr.roles.delete', 'label' => 'Roles - Delete', 'action' => 'delete', 'group' => 'Roles'],
                    ['name' => 'hr.staffs.view', 'label' => 'Staffs - View', 'action' => 'view', 'group' => 'Staffs'],
                    ['name' => 'hr.staffs.create', 'label' => 'Staffs - Create', 'action' => 'create', 'group' => 'Staffs'],
                    ['name' => 'hr.staffs.edit', 'label' => 'Staffs - Edit', 'action' => 'edit', 'group' => 'Staffs'],
                    ['name' => 'hr.staffs.delete', 'label' => 'Staffs - Delete', 'action' => 'delete', 'group' => 'Staffs'],
                    ['name' => 'hr.users.view', 'label' => 'Users - View', 'action' => 'view', 'group' => 'Users'],
                    ['name' => 'hr.users.create', 'label' => 'Users - Create', 'action' => 'create', 'group' => 'Users'],
                    ['name' => 'hr.users.edit', 'label' => 'Users - Edit', 'action' => 'edit', 'group' => 'Users'],
                    ['name' => 'hr.users.delete', 'label' => 'Users - Delete', 'action' => 'delete', 'group' => 'Users'],
                ],
            ],
            'logs' => [
                'label' => 'Logs & Bugs',
                'icon' => 'bi-window-stack',
                'permissions' => [
                    ['name' => 'logs.view', 'label' => 'View', 'action' => 'view'],
                    ['name' => 'logs.delete', 'label' => 'Delete', 'action' => 'delete'],
                ],
            ],
            'app-monitoring' => [
                'label' => 'App Monitoring',
                'icon' => 'bi-graph-up',
                'permissions' => [
                    ['name' => 'app-monitoring.view', 'label' => 'View', 'action' => 'view'],
                ],
            ],
            'profile' => [
                'label' => 'Profile',
                'icon' => 'bi-person',
                'permissions' => [
                    ['name' => 'profile.view', 'label' => 'View Profile', 'action' => 'view'],
                    ['name' => 'profile.edit', 'label' => 'Edit Profile', 'action' => 'edit'],
                ],
            ],
        ];
    }

    /**
     * Get permissions grouped by section with CRUD actions
     *
     * @return array
     */
    public static function groupedBySection(): array
    {
        $organized = self::organized();
        $result = [];

        foreach ($organized as $sectionKey => $section) {
            $groups = [];

            foreach ($section['permissions'] as $permission) {
                $groupName = $permission['group'] ?? $section['label'];
                $action = $permission['action'];

                if (!isset($groups[$groupName])) {
                    $groups[$groupName] = [
                        'label' => $groupName,
                        'permissions' => [],
                    ];
                }

                $groups[$groupName]['permissions'][$action] = [
                    'name' => $permission['name'],
                    'label' => $permission['label'],
                    'action' => $action,
                ];
            }

            $result[$sectionKey] = [
                'label' => $section['label'],
                'icon' => $section['icon'],
                'groups' => array_values($groups),
            ];
        }

        return $result;
    }

    /**
     * Get all permission names for a section
     *
     * @param string $section
     * @return array
     */
    public static function getSectionPermissions(string $section): array
    {
        $organized = self::organized();

        if (!isset($organized[$section])) {
            return [];
        }

        return array_column($organized[$section]['permissions'], 'name');
    }

    /**
     * Get all permissions as flat array for backward compatibility
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all(): \Illuminate\Support\Collection
    {
        return Permission::all();
    }
}
