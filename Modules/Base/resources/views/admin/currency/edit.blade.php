@section('title' , __('Edit Currency'))

@section('toolbar')
    @php
        $breadcrumbItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Settings'],
            ['label' => 'Currencies', 'url' => route('admin.currencies.index')],
            ['label' => 'Edit Currency'],
        ];
    @endphp
    <x-admin.breadcrumb :pageTitle="__('Edit Currency')" :breadcrumbItems="$breadcrumbItems"/>
    <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
@endsection

<x-admin-layout>
    <x-admin.create-card title="Edit Currency: {{$currency->code}} - {{$currency->name}}" :formUrl="route('admin.currencies.update', $currency->id)">
        @method('PUT')
        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-4">
                <div class="fs-6 fw-bold mt-2 mb-3">{{__('Currency Code')}}</div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-8 fv-row">
                <input type="text" class="form-control form-control-solid" name="code"
                       value="{{$currency->code}}" maxlength="3" style="text-transform: uppercase;"/>
                <div class="form-text">{{__('ISO 4217 currency code (e.g., USD, EUR, GBP)')}}</div>
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-4">
                <div class="fs-6 fw-bold mt-2 mb-3">{{__('Currency Name')}}</div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-8 fv-row">
                <input type="text" class="form-control form-control-solid" name="name"
                       value="{{$currency->name}}"/>
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-4">
                <div class="fs-6 fw-bold mt-2 mb-3">{{__('Symbol')}}</div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-8 fv-row">
                <input type="text" class="form-control form-control-solid" name="symbol"
                       value="{{$currency->symbol}}"/>
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-4">
                <div class="fs-6 fw-bold mt-2 mb-3">{{__('Exchange Rate')}}</div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-8 fv-row">
                <input type="number" step="0.00000001" class="form-control form-control-solid" name="exchange_rate"
                       value="{{$currency->exchange_rate}}"/>
                <div class="form-text">{{__('Exchange rate relative to base currency')}}</div>
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-4">
                <div class="fs-6 fw-bold mt-2 mb-3">{{__('Is Default')}}</div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-8 fv-row">
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1"
                           {{$currency->is_default ? 'checked' : ''}} {{$currency->is_default ? 'disabled' : ''}}/>
                    <label class="form-check-label">
                        {{__('Set as default currency')}}
                        @if($currency->is_default)
                            <span class="text-muted ms-2">({{__('Already default')}})</span>
                        @endif
                    </label>
                </div>
                @if(!$currency->is_default)
                    <a href="{{route('admin.currencies.setDefault', $currency->id)}}"
                       class="btn btn-sm btn-light-primary ms-2"
                       onclick="return confirm('{{__('Set this currency as default?')}}')">
                        <i class="bi bi-star-fill me-1"></i>{{__('Set as Default')}}
                    </a>
                @endif
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-4">
                <div class="fs-6 fw-bold mt-2 mb-3">{{__('Is Active')}}</div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-8 fv-row">
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           {{$currency->is_active ? 'checked' : ''}}/>
                    <label class="form-check-label">{{__('Active')}}</label>
                </div>
            </div>
        </div>
    </x-admin.create-card>
</x-admin-layout>