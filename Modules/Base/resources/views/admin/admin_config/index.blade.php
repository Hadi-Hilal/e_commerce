@section('title' , __('API Configs'))

@section('toolbar')
    @php
        $breadcrumbItems = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Settings'],
            ['label' => 'Configs'],
            ['label' => 'API Configs'],
        ];
    @endphp
    <x-admin.breadcrumb :pageTitle="__('API Configs')" :breadcrumbItems="$breadcrumbItems"/>
    <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
@endsection

<x-admin-layout>
    <!-- Fixer API Configuration -->
    <x-admin.create-card title="Fixer API Configuration" :formUrl="route('admin.admin-configs.store')">
        <input type="hidden" name="group" value="fixer_api"/>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-3">
                <div class="fs-6 fw-bold mt-2 mb-3">
                    <i class="bi bi-key mx-1 text-primary"></i> {{__('Fixer API Key')}}
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-9 fv-row">
                <input type="password" class="form-control form-control-solid" name="data[api_key]"
                       value="{{$fixerApiConfig['api_key'] ?? ''}}" placeholder="Enter your Fixer API key"/>
                <div class="form-text">{{__('Get your API key from <a href="https://fixer.io" target="_blank">fixer.io</a>')}}</div>
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-3">
                <div class="fs-6 fw-bold mt-2 mb-3">
                    <i class="bi bi-currency-exchange mx-1 text-success"></i> {{__('Base Currency')}}
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-9 fv-row">
                <select class="form-select form-select-solid" name="data[base_currency]">
                    @foreach(\Modules\Base\Models\Currency::active()->orderBy('code')->get() as $currency)
                        <option value="{{$currency->code}}" {{($fixerApiConfig['base_currency'] ?? 'USD') === $currency->code ? 'selected' : ''}}>
                            {{$currency->code}} - {{$currency->name}}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">{{__('Base currency for exchange rates (must be supported by Fixer)')}}</div>
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-3">
                <div class="fs-6 fw-bold mt-2 mb-3">
                    <i class="bi bi-toggle-on mx-1 text-info"></i> {{__('Fixer API Status')}}
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-9 fv-row">
                <div class="form-check form-check-custom form-check-solid form-check-success">
                    <input class="form-check-input" type="checkbox" name="data[status]" value="1"
                           {{($fixerApiConfig['status'] ?? false) ? 'checked' : ''}}/>
                    <label class="form-check-label">{{__('Enable Fixer API Integration')}}</label>
                </div>
                <div class="form-text">{{__('When enabled, you can sync live exchange rates from Fixer API')}}</div>
            </div>
        </div>

        <div class="row mb-8">
            <div class="col-xl-3"></div>
            <div class="col-xl-9">
                <a href="{{route('admin.currencies.syncRates')}}"
                   class="btn btn-info me-2"
                   onclick="return confirm('{{__('This will fetch live rates from Fixer API. Continue?')}}')">
                    <i class="bi bi-arrow-repeat me-1"></i>{{__('Sync Live Rates Now')}}
                </a>
            </div>
        </div>
    </x-admin.create-card>

    <!-- Currency Settings -->
    <x-admin.create-card title="{{__('Currency Settings')}}" :formUrl="route('admin.admin-configs.store')">
        <input type="hidden" name="group" value="currencies"/>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-3">
                <div class="fs-6 fw-bold mt-2 mb-3">
                    <i class="bi bi-cash-coin mx-1 text-warning"></i> {{__('Default Currency')}}
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-9 fv-row">
                <select class="form-select form-select-solid" name="data[default_currency]">
                    @foreach(\Modules\Base\Models\Currency::active()->orderBy('code')->get() as $currency)
                        <option value="{{$currency->code}}" {{($currenciesConfig['default_currency'] ?? '') === $currency->code ? 'selected' : ''}}>
                            {{$currency->code}} - {{$currency->name}}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">{{__('Default currency for the application')}}</div>
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-3">
                <div class="fs-6 fw-bold mt-2 mb-3">
                    <i class="bi bi-123 mx-1 text-secondary"></i> {{__('Number of Decimals')}}
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-9 fv-row">
                <input type="number" min="0" max="8" class="form-control form-control-solid" name="data[decimals]"
                       value="{{$currenciesConfig['decimals'] ?? 2}}"/>
                <div class="form-text">{{__('Number of decimal places for currency display')}}</div>
            </div>
        </div>

        <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-xl-3">
                <div class="fs-6 fw-bold mt-2 mb-3">
                    <i class="bi bi-currency-dollar mx-1 text-success"></i> {{__('Currency Symbol Position')}}
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-9 fv-row">
                <select class="form-select form-select-solid" name="data[symbol_position]">
                    <option value="before" {{($currenciesConfig['symbol_position'] ?? 'before') === 'before' ? 'selected' : ''}}>{{__('Before Amount (e.g., $100)')}}</option>
                    <option value="after" {{($currenciesConfig['symbol_position'] ?? 'before') === 'after' ? 'selected' : ''}}>{{__('After Amount (e.g., 100$)')}}</option>
                </select>
            </div>
        </div>
    </x-admin.create-card>
</x-admin-layout>