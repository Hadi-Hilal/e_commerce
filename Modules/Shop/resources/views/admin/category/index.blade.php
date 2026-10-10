@section('title' , __('Categories'))

@section('toolbar')
    @php
        $breadcrumbItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Shop'],
            ['label' => 'Categories'],
        ];
    @endphp
    <x-admin.breadcrumb :pageTitle="__('Categories')" :breadcrumbItems="$breadcrumbItems"/>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm fw-bold btn-primary">
            {{__('Create Category')}}
        </a>
        @if(request()->has('attribute_family_id'))
            <a href="{{ route('admin.categories.index') }}"
               class="btn btn-sm btn-link text-muted">
                {{__('Back to All Categories')}}
            </a>
        @endif
    </div>
@endsection
@section('js')
@parent
@endsection
<x-admin-layout>
    <x-admin.table :model="$model" search="Search In Categories"
                   :form-url="route('admin.categories.deleteMulti')">
        <thead>
        <tr class="text-start text-muted fw-bold fs-7 gs-0">
            <th class="w-10px pe-2" data-orderable="false">
                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                           data-kt-check-target="#dataTable .form-check-input" value="1"/>
                </div>
            </th>
            <th class="min-w-80px">{{__('Image')}}</th>
            <th class="min-w-150px">{{__('Name')}}</th>
            <th class="min-w-150px">{{__('Slug')}}</th>
            <th class="min-w-150px">{{__('Attribute Family')}}</th>
            <th class="min-w-150px">{{__('Parent Category')}}</th>
            <th class="min-w-150px">{{__('Created At')}}</th>
            <th class="min-w-200px text-end rounded-end"></th>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
        @foreach($model as $category)
            <tr>
                <td>
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="ids[]" value="{{$category->id}}"/>
                    </div>
                </td>
                <td>
                    <img src="{{ $category->image_link }}" alt="{{ __('Category image') }}"
                         style="max-height: 50px; object-fit: cover;">
                </td>
                <td>
                    {{$category->name}}
                    @if($category->parent_id)
                        <span class="text-muted small ms-1">(Child)</span>
                    @endif
                </td>
                <td>{{$category->slug}}</td>
                <td>
                    @if($category->attributeFamily)
                        {{ $category->attributeFamily->name[$locale] ?? $category->attributeFamily->name['en'] ?? $category->attributeFamily->code }}
                    @else
                        {{__('Not assigned')}}
                    @endif
                </td>
                <td>
                    @if($category->parent)
                        {{$category->parent->name}}
                    @else
                        {{__('Root category')}}
                    @endif
                </td>
                <td>{{$category->created_at->diffForHumans() }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                            title="{{ __('Edit Category') }}">
                        <i class="ki-duotone ki-message-edit fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm ms-1"
                                title="{{ __('Delete Category') }}"
                                onclick="return confirm('{{__('Are you sure you want to delete this category?')}}')">
                            <i class="ki-duotone ki-message-trash fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </x-admin.table>
</x-admin-layout>