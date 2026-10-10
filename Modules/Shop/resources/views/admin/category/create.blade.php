@section('title', __('Add New Category'))

@section('toolbar')
    @php
        $breadcrumbItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Shop'],
            ['label' => 'Categories', 'url' => route('admin.categories.index')],
            ['label' => 'Add New Category'],
        ];
    @endphp
    <x-admin.breadcrumb :pageTitle="__('Add New Category')" :breadcrumbItems="$breadcrumbItems"/>
@endsection

<x-admin-layout>
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row gx-5 gx-xl-10">
            <div class="col-xxl-8 col-xl-8 mb-5 mb-xl-0">
                <div class="card card-flush mb-7">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="d-flex align-items-center">
                                <i class="bi bi-tags text-primary fs-3 me-2"></i>
                                {{ __('General') }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <x-admin.form-group label="Name" name="name" required translatable
                                            helper="Display name for the category.">
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control form-control-solid"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="{{ __('Category name') }}"/>
                        </x-admin.form-group>

                        <x-admin.form-group label="Slug" name="slug" required
                                            helper="A short label containing only letters, numbers, and hyphens.">
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   class="form-control form-control-solid"
                                   value="{{ old('slug') }}"
                                   required
                                   placeholder="category-slug"/>
                        </x-admin.form-group>

                        <x-admin.form-group label="Image"
                                            helper="Optional category image (PNG, JPEG, or WebP).">
                            <x-admin.image-input name="img"/>
                        </x-admin.form-group>

                        <x-admin.form-group label="SEO Data" name="seo_data"
                                            helper="Optional JSON: title, description, keywords.">
                            <textarea name="seo_data"
                                      id="seo_data"
                                      class="form-control form-control-solid"
                                      rows="4"
                                      placeholder='{"title": "", "description": "", "keywords": ""}'>{{ old('seo_data') }}</textarea>
                        </x-admin.form-group>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-xl-4">
                <div class="card card-flush mb-7">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="d-flex align-items-center">
                                <i class="bi bi-diagram-3 text-primary fs-3 me-2"></i>
                                {{ __('Organization') }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <x-admin.form-group label="Attribute Family" name="attribute_family_id" required>
                            <select name="attribute_family_id"
                                    id="attribute_family_id"
                                    class="form-select form-select-solid"
                                    required>
                                <option value="">{{ __('Select Attribute Family') }}</option>
                                @foreach($attributeFamilies as $family)
                                    <option value="{{ $family->id }}"
                                        @selected((string) old('attribute_family_id') === (string) $family->id)>
                                        {{ $family->name[$locale] ?? $family->name['en'] ?? $family->code }}
                                    </option>
                                @endforeach
                            </select>
                        </x-admin.form-group>

                        <x-admin.form-group label="Parent Category" name="parent_id"
                                            helper="Select a parent from the tree, or leave as root.">
                            @include('shop::admin.category._parent_tree', [
                                'tree' => $tree,
                                'selected' => old('parent_id'),
                                'familyFilter' => old('attribute_family_id'),
                            ])
                        </x-admin.form-group>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end py-6">
            <a href="{{ route('admin.categories.index') }}"
               class="btn btn-light btn-active-light-primary me-3">{{ __('Discard') }}</a>
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">{{ __('Save Changes') }}</span>
            </button>
        </div>
    </form>
</x-admin-layout>
