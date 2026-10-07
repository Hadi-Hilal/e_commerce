@section('title' , __('Add Currency'))

@section('toolbar')
    @php
        $breadcrumbItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Settings'],
            ['label' => 'Currencies', 'url' => route('admin.currencies.index')],
            ['label' => 'Add Currency'],
        ];
    @endphp
    <x-admin.breadcrumb :pageTitle="__('Add Currency')" :breadcrumbItems="$breadcrumbItems"/>
    <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
@endsection

<x-admin-layout>
    <x-admin.create-card title="Add Currency" :formUrl="route('admin.currencies.store')">
        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-4">
                <div class="fs-6 fw-bold mt-2 mb-3">{{__('Currency Code')}}</div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-8 fv-row">
                <input type="text" class="form-control form-control-solid" name="code"
                       placeholder="USD" maxlength="3" style="text-transform: uppercase;"/>
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
                       placeholder="US Dollar"/>
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
                       placeholder="$"/>
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
                       value="1.00000000" placeholder="1.00000000"/>
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
                    <input class="form-check-input" type="checkbox" name="is_default" value="1"/>
                    <label class="form-check-label">{{__('Set as default currency')}}</label>
                </div>
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
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked/>
                    <label class="form-check-label">{{__('Active')}}</label>
                </div>
            </div>
        </div>
    </x-admin.create-card>
</x-admin-layout>