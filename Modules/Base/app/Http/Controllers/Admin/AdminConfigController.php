<?php

namespace Modules\Base\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Application\AdminConfig\AdminConfigApplicationService;
use Modules\Base\Http\Requests\AdminConfigRequest;

class AdminConfigController extends Controller
{
    public function __construct(private readonly AdminConfigApplicationService $adminConfigService)
    {
        $this->setActive('settings');
        $this->setActive('apiConfigs');
    }

    public function index(Request $request)
    {
        $this->setActive('settings');
        $this->setActive('apiConfigs');

        $fixerApiConfig = $this->adminConfigService->getGroup('fixer_api');
        $currenciesConfig = $this->adminConfigService->getGroup('currencies');

        return view('base::admin.admin_config.index', compact('fixerApiConfig', 'currenciesConfig'));
    }

    public function store(AdminConfigRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $group = $data['group'] ?? 'general';

        // Handle Fixer API config specially
        if ($group === 'fixer_api') {
            $configData = [
                'api_key' => $request->input('data.api_key'),
                'base_currency' => $request->input('data.base_currency', 'USD'),
                'status' => $request->boolean('data.status', false),
            ];
            $this->adminConfigService->update($configData, 'fixer_api');
        } else {
            // General config handling
            $configData = $request->input('data', []);
            $this->adminConfigService->update($configData, $group);
        }

        return back();
    }
}