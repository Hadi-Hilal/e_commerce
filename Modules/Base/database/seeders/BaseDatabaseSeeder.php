<?php

namespace Modules\Base\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Models\AdminConfig;
use Modules\Base\Models\Currency;

class BaseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedAdminConfigs();
        $this->seedDefaultCurrencies();
    }

    private function seedAdminConfigs(): void
    {
        // Fixer API Configuration
        AdminConfig::firstOrCreate(
            ['key' => 'api_key', 'group' => 'fixer_api'],
            ['value' => '', 'group' => 'fixer_api']
        );

        AdminConfig::firstOrCreate(
            ['key' => 'base_currency', 'group' => 'fixer_api'],
            ['value' => 'USD', 'group' => 'fixer_api']
        );

        AdminConfig::firstOrCreate(
            ['key' => 'status', 'group' => 'fixer_api'],
            ['value' => '0', 'group' => 'fixer_api']
        );

        // Currency Settings
        AdminConfig::firstOrCreate(
            ['key' => 'default_currency', 'group' => 'currencies'],
            ['value' => 'USD', 'group' => 'currencies']
        );

        AdminConfig::firstOrCreate(
            ['key' => 'decimals', 'group' => 'currencies'],
            ['value' => '2', 'group' => 'currencies']
        );

        AdminConfig::firstOrCreate(
            ['key' => 'symbol_position', 'group' => 'currencies'],
            ['value' => 'before', 'group' => 'currencies']
        );
    }

    private function seedDefaultCurrencies(): void
    {
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'exchange_rate' => 1.0, 'is_default' => true, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 0.92, 'is_default' => false, 'is_active' => true],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'exchange_rate' => 0.79, 'is_default' => false, 'is_active' => true],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'exchange_rate' => 149.5, 'is_default' => false, 'is_active' => true],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'exchange_rate' => 1.53, 'is_default' => false, 'is_active' => true],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'exchange_rate' => 1.36, 'is_default' => false, 'is_active' => true],
            ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF', 'exchange_rate' => 0.89, 'is_default' => false, 'is_active' => true],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'exchange_rate' => 7.24, 'is_default' => false, 'is_active' => true],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼', 'exchange_rate' => 3.75, 'is_default' => false, 'is_active' => true],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ', 'exchange_rate' => 3.67, 'is_default' => false, 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }
}