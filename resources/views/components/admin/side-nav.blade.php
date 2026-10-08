<div class="menu-item">
    @can('dashboard.view')
        <a class="menu-link {{ isset($active['dashboard']) ? 'active' : '' }}"
           href="{{ route('admin.dashboard.index') }}">
            <span class="menu-icon">
                <i class="bi bi-speedometer2"></i>
            </span>
            <span class="menu-title">{{ __('Dashboard') }}</span>
        </a>
    @endcan
</div>

@can('Settings Management')
    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion {{ isset($active['settings']) ? 'show hover' : '' }}">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="bi bi-gear"></i>
            </span>
            <span class="menu-title">{{ __('Settings') }}</span>
            <span class="menu-arrow"></span>
        </span>

        @can('settings.website-config.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['websiteConfigurations'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['websiteConfigurations']) ? 'active' : '' }}"
                       href="{{ route('admin.site-configs.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Website Configurations') }}</span>
                    </a>
                </div>
            </div>
        @endcan

        @can('settings.seo.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['seo'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['seo']) ? 'active' : '' }}"
                       href="{{ route('admin.seo.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Seo Configurations') }}</span>
                    </a>
                </div>
            </div>
        @endcan

        @can('settings.api-config.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['configs'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['apiConfigs']) ? 'active' : '' }}"
                       href="{{ route('admin.admin-configs.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('API Configs') }}</span>
                    </a>
                </div>
            </div>
        @endcan

        @can('settings.currencies.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['configs'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['currencies']) ? 'active' : '' }}"
                       href="{{ route('admin.currencies.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Currencies') }}</span>
                    </a>
                </div>
            </div>
        @endcan

    </div>
@endcan

@can('CMS Management')
    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion {{ isset($active['cms']) ? 'show hover' : '' }}">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="bi bi-intersect"></i>
            </span>
            <span class="menu-title">{{ __('CMS') }}</span>
            <span class="menu-arrow"></span>
        </span>

        @can('cms.pages.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['pages'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['pages']) ? 'active' : '' }}"
                       href="{{ route('admin.pages.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Pages') }}</span>
                    </a>
                </div>
            </div>
        @endcan

        @can('cms.blog-categories.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['blogs_categories'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['blogs_categories']) ? 'active' : '' }}"
                       href="{{ route('admin.blogs_categories.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Blog Categories') }}</span>
                    </a>
                </div>
            </div>
        @endcan

        @can('cms.blogs.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['blogs'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['blogs']) ? 'active' : '' }}"
                       href="{{ route('admin.blogs.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Blogs') }}</span>
                    </a>
                </div>
            </div>
        @endcan

        @can('cms.faqs.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['faqs'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['faqs']) ? 'active' : '' }}"
                       href="{{ route('admin.faqs.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('FAQs') }}</span>
                    </a>
                </div>
            </div>
        @endcan

    </div>
@endcan

@can('Media Library Management')
    @can('media-library.view')
        <div class="menu-item">
            <a class="menu-link {{ isset($active['media_library']) ? 'active' : '' }}"
               href="{{ route('admin.media_library.index') }}">
                <span class="menu-icon">
                    <i class="bi bi-images"></i>
                </span>
                <span class="menu-title">{{ __('Media Library') }}</span>
            </a>
        </div>
    @endcan
@endcan

@can('Shop Management')
    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion {{ isset($active['shop']) ? 'show hover' : '' }}">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="bi bi-shop"></i>
            </span>
            <span class="menu-title">{{ __('Shop') }}</span>
            <span class="menu-arrow"></span>
        </span>

        @can('shop.attributes.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['attributes'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['attributes']) ? 'active' : '' }}"
                       href="{{ route('admin.attributes.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Attributes') }}</span>
                    </a>
                </div>
            </div>
        @endcan

        @can('shop.attribute-families.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['attribute_families'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['attribute_families']) ? 'active' : '' }}"
                       href="{{ route('admin.attribute_families.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Attribute Families') }}</span>
                    </a>
                </div>
            </div>
        @endcan

    </div>
@endcan

@can('Support Management')
    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion {{ isset($active['support']) ? 'show hover' : '' }}">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="bi bi-headset"></i>
            </span>
            <span class="menu-title">{{ __('Support Hub') }}</span>
            <span class="menu-arrow"></span>
        </span>

        @can('support.contact-forms.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['contact_forms'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['contact_forms']) ? 'active' : '' }}"
                       href="{{ route('admin.contact_forms.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Contacts') }}</span>
                    </a>
                </div>
            </div>
        @endcan

        @can('support.subscribers.view')
            <div class="menu-sub menu-sub-accordion {{ isset($active['subscribers'])  ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['subscribers']) ? 'active' : '' }}"
                       href="{{ route('admin.subscribers.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Newsletter Subscribers') }}</span>
                    </a>
                </div>
            </div>
        @endcan

    </div>
@endcan

@can('Hr Management')
    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion {{ isset($active['hr']) ? 'show hover' : '' }}">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="bi bi-journal-text"></i>
            </span>
            <span class="menu-title">{{ __('HR') }}</span>
            <span class="menu-arrow"></span>
        </span>

        @can('hr.roles.view')
            <div
                class="menu-sub menu-sub-accordion {{ isset($active['roles']) || isset($active['staffs']) || isset($active['users']) ? 'show' : '' }}">
                <div class="menu-item">
                    <a class="menu-link {{ isset($active['roles']) ? 'active' : '' }}"
                       href="{{ route('admin.roles.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ __('Roles') }}</span>
                    </a>
                </div>

                @can('hr.staffs.view')
                    <div class="menu-item">
                        <a class="menu-link {{ isset($active['staffs']) ? 'active' : '' }}"
                           href="{{ route('admin.staffs.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">{{ __('Staffs') }}</span>
                        </a>
                    </div>
                @endcan

                @can('hr.users.view')
                    <div class="menu-item">
                        <a class="menu-link {{ isset($active['users']) ? 'active' : '' }}"
                           href="{{ route('admin.users.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">{{ __('Users') }}</span>
                        </a>
                    </div>
                @endcan

            </div>
        @endcan

    </div>
@endcan

@can('Logs Management')
    @can('logs.view')
        <div class="menu-item">
            <a class="menu-link {{ isset($active['logs']) ? 'active' : '' }}"
               href="{{ route('admin.logs.index') }}">
                <span class="menu-icon">
                    <i class="bi bi-window-stack"></i>
                </span>
                <span class="menu-title">{{ __('Logs & Bugs') }}</span>
            </a>
        </div>
    @endcan
@endcan