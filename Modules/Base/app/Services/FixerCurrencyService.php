<?php

namespace Modules\Base\Services;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Base\Models\AdminConfig;
use Modules\Base\Models\Currency;

class FixerCurrencyService
{
    private const BASE_URL = 'https://data.fixer.io/api/';

    public function __construct()
    {
        // Lazy load configuration to allow changes without recreating service
    }

    private function getApiKey(): string
    {
        return AdminConfig::get('api_key', null, 'fixer_api');
    }

    private function getBaseCurrency(): string
    {
        return AdminConfig::get('base_currency', 'USD', 'fixer_api');
    }

    /**
     * Set API key dynamically.
     */
    public function setApiKey(string $apiKey): self
    {
        AdminConfig::set('api_key', $apiKey, 'fixer_api');

        return $this;
    }

    /**
     * Set base currency.
     */
    public function setBaseCurrency(string $baseCurrency): self
    {
        AdminConfig::set('base_currency', $baseCurrency, 'fixer_api');

        return $this;
    }

    /**
     * Check if API key is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->getApiKey());
    }

    /**
     * Fetch latest exchange rates from Fixer API.
     */
    public function fetchLatestRates(): array
    {
        if (! $this->isConfigured()) {
            throw new Exception(__('Fixer API key is not configured. Please set it in API Configs.'));
        }

        $response = $this->makeRequest('latest', [
            'access_key' => $this->getApiKey(),
            'base' => $this->getBaseCurrency(),
        ]);

        if (! $response->successful()) {
            $error = $response->json();
            $message = $error['error']['info'] ?? __('Failed to fetch exchange rates from Fixer API');
            throw new Exception($message);
        }

        $data = $response->json();

        if (! $data['success']) {
            $message = $data['error']['info'] ?? __('Failed to fetch exchange rates from Fixer API');
            throw new Exception($message);
        }

        return $data['rates'] ?? [];
    }

    /**
     * Fetch supported symbols from Fixer API.
     */
    public function fetchSymbols(): array
    {
        if (! $this->isConfigured()) {
            throw new Exception(__('Fixer API key is not configured. Please set it in API Configs.'));
        }

        $response = $this->makeRequest('symbols', [
            'access_key' => $this->getApiKey(),
        ]);

        if (! $response->successful()) {
            throw new Exception(__('Failed to fetch symbols from Fixer API'));
        }

        $data = $response->json();

        if (! $data['success']) {
            throw new Exception($data['error']['info'] ?? __('Failed to fetch symbols from Fixer API'));
        }

        return $data['symbols'] ?? [];
    }

    /**
     * Sync exchange rates to the currencies table.
     */
    public function syncRates(): array
    {
        $rates = $this->fetchLatestRates();
        $symbols = $this->fetchSymbols();

        $synced = [];
        $errors = [];

        // Ensure base currency exists with rate 1.0
        $baseCurrencyCode = $this->getBaseCurrency();
        $baseCurrency = Currency::firstOrCreate(
            ['code' => $baseCurrencyCode],
            [
                'name' => $symbols[$baseCurrencyCode]['description'] ?? $baseCurrencyCode,
                'symbol' => $symbols[$baseCurrencyCode]['symbol'] ?? $baseCurrencyCode,
                'exchange_rate' => 1.0,
                'is_default' => true,
                'is_active' => true,
            ]
        );

        if (! $baseCurrency->is_default) {
            $baseCurrency->setAsDefault();
        }

        $synced[$baseCurrencyCode] = 1.0;

        // First, get all existing currency codes in a single query
        $existingCurrencies = Currency::pluck('code', 'id')->flip()->toArray();

        foreach ($rates as $code => $rate) {
            if ($code === $baseCurrencyCode) {
                continue;
            }

            try {
                $currencyData = [
                    'name' => $symbols[$code]['description'] ?? $code,
                    'symbol' => $symbols[$code]['symbol'] ?? $code,
                    'exchange_rate' => $rate,
                    'is_default' => false,
                    'is_active' => true,
                ];

                if (isset($existingCurrencies[$code])) {
                    // Update existing currency
                    Currency::where('code', $code)->update(['exchange_rate' => $rate]);
                } else {
                    // Create new currency
                    Currency::create(array_merge($currencyData, ['code' => $code]));
                }

                $synced[$code] = $rate;
            } catch (Exception $e) {
                $errors[$code] = $e->getMessage();
                Log::error("Failed to sync currency {$code}: " . $e->getMessage());
            }
        }

        return [
            'synced' => $synced,
            'errors' => $errors,
            'base_currency' => $baseCurrencyCode,
            'count' => count($synced),
        ];
    }

    /**
     * Make HTTP request to Fixer API.
     */
    private function makeRequest(string $endpoint, array $params = []): Response
    {
        return Http::timeout(30)
            ->retry(3, 1000)
            ->get(self::BASE_URL . $endpoint, $params);
    }
}