@section('title' , __('Attributes'))

@section('toolbar')
    @php
        $breadcrumbItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Shop'],
            ['label' => 'Attributes'],
        ];
    @endphp
    <x-admin.breadcrumb :pageTitle="__('Attributes')" :breadcrumbItems="$breadcrumbItems"/>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#createAttributeModal">
            {{__('Create Attribute')}}
        </button>
    </div>
@endsection
@section('js')
@endsection
<x-admin-layout>
    <x-admin.table :model="$model" search="Search In Attributes"
                   :form-url="route('admin.attributes.deleteMulti')">
        <thead>
        <tr class="text-start text-muted fw-bold fs-7 gs-0">
            <th class="w-10px pe-2" data-orderable="false">
                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                           data-kt-check-target="#dataTable .form-check-input" value="1"/>
                </div>
            </th>
            <th class="min-w-150px">{{__('Code')}}</th>
            <th class="min-w-200px">{{__('Admin Name')}}</th>
            <th class="min-w-150px">{{__('Type')}}</th>
            <th class="min-w-100px">{{__('Required')}}</th>
            <th class="min-w-100px">{{__('Unique')}}</th>
            <th class="min-w-150px">{{__('Created At')}}</th>
            <th class="min-w-200px text-end rounded-end"></th>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
        @foreach($model as $attribute)
            <tr>
                <td>
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="ids[]" value="{{$attribute->id}}"/>
                    </div>
                </td>
                <td>{{$attribute->code}}</td>
                <td>{{$attribute->admin_name}}</td>
                <td>
                    <span class="badge badge-light-{{ $attribute->type === 'select' ? 'success' : ($attribute->type === 'boolean' ? 'info' : 'primary') }} fs-7">
                        {{__('shop.attributes.types.'.$attribute->type)}}
                    </span>
                </td>
                <td>
                    <span class="badge badge-light-{{ $attribute->is_required ? 'success' : 'danger' }} fs-7">
                        {{ $attribute->is_required ? __('Yes') : __('No') }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-light-{{ $attribute->is_unique ? 'success' : 'danger' }} fs-7">
                        {{ $attribute->is_unique ? __('Yes') : __('No') }}
                    </span>
                </td>
                <td>{{$attribute->created_at->diffForHumans() }}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                            data-bs-toggle="modal" data-bs-target="#editAttributeModal{{$attribute->id}}"
                            title="{{ __('Edit Attribute') }}">
                        <i class="ki-duotone ki-message-edit fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </x-admin.table>
    @foreach($model as $attribute)
        @include('shop::admin.attribute.edit', ['attribute' => $attribute, 'modal' => true])
    @endforeach
    @include('shop::admin.attribute.create', ['modal' => true])
</x-admin-layout>