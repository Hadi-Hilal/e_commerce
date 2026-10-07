<?php

namespace Modules\Base\Console\Commands;

use Illuminate\Console\Command;
use Modules\Base\Application\Currency\CurrencyApplicationService;
use Modules\Base\Services\FixerCurrencyService;

class SyncExchangeRatesCommand extends Command
{
    protected $signature = 'currency:sync-rates
                            {--force : Force sync even if API key is not configured}';

    protected $description = 'Sync exchange rates from Fixer API';

    public function __construct(
        private readonly CurrencyApplicationService $currencyService,
        private readonly FixerCurrencyService $fixerService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting exchange rate sync from Fixer API...');

        if (! $this->option('force') && ! $this->fixerService->isConfigured()) {
            $this->error('Fixer API key is not configured. Please set it in API Configs.');
            $this->info('Use --force to attempt sync anyway.');

            return Command::FAILURE;
        }

        try {
            $result = $this->currencyService->syncRates();

            $this->info("Sync completed successfully!");
            $this->info("Base Currency: {$result['base_currency']}");
            $this->info("Currencies Synced: {$result['count']}");

            if (! empty($result['errors'])) {
                $this->warn("Errors encountered: " . count($result['errors']));
                foreach ($result['errors'] as $code => $error) {
                    $this->line("  - {$code}: {$error}");
                }
            }

            $this->table(['Currency', 'Rate'], collect($result['synced'])->map(fn ($rate, $code) => [$code, $rate])->toArray());

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Sync failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}