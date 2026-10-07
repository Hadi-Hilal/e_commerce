@section('title' , __('Currencies'))

@section('toolbar')
    @php
        $breadcrumbItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Settings'],
            ['label' => 'Currencies'],
        ];
    @endphp
    <x-admin.breadcrumb :pageTitle="__('Currencies')" :breadcrumbItems="$breadcrumbItems"/>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a class="btn btn-sm fw-bold  btn-primary" href="{{route('admin.currencies.create')}}">
            {{__('Add Currency')}}
        </a>
        <a class="btn btn-sm fw-bold  btn-info" href="{{route('admin.currencies.syncRates')}}"
           onclick="return confirm('{{__('This will fetch live rates from Fixer API. Continue?')}}')">
            {{__('Sync Live Rates')}}
        </a>
    </div>
@endsection

<x-admin-layout>
    <x-admin.table :model="$model" search="Search In Currencies" :form-url="route('admin.currencies.deleteMulti')">
        <!--begin::Table head-->
        <thead>
        <tr class="text-start text-muted fw-bold fs-7 gs-0">
            <th class="w-10px pe-2" data-orderable="false">
                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                           data-kt-check-target="#dataTable .form-check-input" value="1"/>
                </div>
            </th>

            <th class="min-w-100px">{{__('Code')}}</th>
            <th class="min-w-200px">{{__('Name')}}</th>
            <th class="min-w-100px">{{__('Symbol')}}</th>
            <th class="min-w-150px">{{__('Exchange Rate')}}</th>
            <th class="min-w-120px">{{__('Default')}}</th>
            <th class="min-w-120px">{{__('Status')}}</th>
            <th class="min-w-150px">{{__('Created At')}}</th>
            <th class="min-w-200px text-end rounded-end"></th>
        </tr>
        </thead>
        <!--end::Table head-->
        <!--begin::Table body-->
        <tbody class="text-gray-600 fw-semibold">
        @foreach($model as $currency)
            <tr>
                <td>
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="ids[]" value="{{$currency->id}}"/>
                    </div>
                </td>

                <td>
                    <span class="fw-bold text-primary">{{$currency->code}}</span>
                </td>
                <td>
                    <h5 class="fw-bolder text-hover-primary mb-1 fs-6">{{$currency->name}}</h5>
                </td>
                <td>
                    <span class="fs-4">{{$currency->symbol}}</span>
                </td>
                <td>
                    <span class="fw-bold">{{number_format($currency->exchange_rate, 8)}}</span>
                </td>
                <td>
                    @if($currency->is_default)
                        <span class="badge badge-light-success fs-7 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i>{{__('Yes')}}
                        </span>
                    @else
                        <form method="POST" action="{{route('admin.currencies.setDefault', $currency->id)}}">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-light-primary fw-bold"
                                    onclick="return confirm('{{__('Set this currency as default?')}}')">
                                <i class="bi bi-star-fill me-1"></i>{{__('Set Default')}}
                            </button>
                        </form>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{route('admin.currencies.update', $currency->id)}}"
                          style="display: inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="code" value="{{$currency->code}}">
                        <input type="hidden" name="name" value="{{$currency->name}}">
                        <input type="hidden" name="symbol" value="{{$currency->symbol}}">
                        <input type="hidden" name="exchange_rate" value="{{$currency->exchange_rate}}">
                        <input type="hidden" name="is_default" value="{{ $currency->is_default ? 1 : 0 }}">
                        <x-admin.toggle-switch
                            name="is_active"
                            :checked="$currency->is_active"
                            :label="$currency->is_active ? __('Active') : __('Inactive')"
                            icon="bi bi-toggle-on"
                            tone="success"
                            value="1"
                        />
                    </form>
                </td>
                <td>
                    {{$currency->created_at->diffForHumans() }}
                </td>
                <td>
                    <a href="{{route('admin.currencies.edit' , $currency->id)}}"
                       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                        <i class="ki-duotone ki-message-edit fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <!--end::Table body-->
    </x-admin.table>
</x-admin-layout>