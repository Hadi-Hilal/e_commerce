@section('title' , __('Attribute Families'))

@section('toolbar')
    @php
        $breadcrumbItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Shop'],
            ['label' => 'Attribute Families'],
        ];
    @endphp
    <x-admin.breadcrumb :pageTitle="__('Attribute Families')" :breadcrumbItems="$breadcrumbItems"/>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#createFamilyModal">
            {{__('Create Attribute Family')}}
        </button>
    </div>
@endsection
@section('js')
@endsection
<x-admin-layout>
    <x-admin.table :model="$model" search="Search In Attribute Families"
                   :form-url="route('admin.attribute_families.deleteMulti')">
        <thead>
        <tr class="text-start text-muted fw-bold fs-7 gs-0">
            <th class="w-10px pe-2" data-orderable="false">
                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                           data-kt-check-target="#dataTable .form-check-input" value="1"/>
                </div>
            </th>
            <th class="min-w-150px">{{__('Code')}}</th>
            <th class="min-w-200px">{{__('Name')}}</th>
            <th class="min-w-150px">{{__('Created At')}}</th>
            <th class="min-w-200px text-end rounded-end"></th>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
        @foreach($model as $family)
            <tr>
                <td>
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="ids[]" value="{{$family->id}}"/>
                    </div>
                </td>
                <td>{{$family->code}}</td>
                <td>{{$family->name}}</td>
                <td>{{$family->created_at->diffForHumans() }}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                            data-bs-toggle="modal" data-bs-target="#editFamilyModal{{$family->id}}"
                            title="{{ __('Edit Attribute Family') }}">
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
    @foreach($model as $family)
        @include('shop::admin.attribute_family.edit', ['attribute_family' => $family, 'attributes' => $attributes, 'selectedAttributes' => $family->attributes->pluck('id')->toArray(), 'modal' => true])
    @endforeach
    @include('shop::admin.attribute_family.create', ['modal' => true])
</x-admin-layout>