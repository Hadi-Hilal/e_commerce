<?php

namespace Modules\Base\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Application\Currency\CurrencyApplicationService;
use Modules\Base\Http\Requests\CurrencyRequest;
use Modules\Base\Models\Currency;
use Modules\Core\Http\Requests\DeleteMultiRequest;

class CurrencyController extends Controller
{
    public function __construct(private readonly CurrencyApplicationService $currencyService)
    {
        $this->setActive('settings');
        $this->setActive('currencies');
    }

    public function index(Request $request)
    {
        $this->setActive('settings');
        $this->setActive('currencies');

        $filters = [
            'search' => $request->query('search'),
            'is_active' => $request->query('is_active'),
        ];

        $model = $this->currencyService->paginate(array_filter($filters), [
            'id', 'code', 'name', 'symbol', 'exchange_rate', 'is_default', 'is_active', 'created_at',
        ]);

        return view('base::admin.currency.index', compact('model'));
    }

    public function create()
    {
        return view('base::admin.currency.create');
    }

    public function store(CurrencyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->currencyService->store($data);

        return redirect()->route('admin.currencies.index');
    }

    public function edit(Currency $currency)
    {
        return view('base::admin.currency.edit', compact('currency'));
    }

    public function update(CurrencyRequest $request, Currency $currency): RedirectResponse
    {
        $data = $request->validated();
        $this->currencyService->update($currency, $data);

        return redirect()->route('admin.currencies.index');
    }

    public function deleteMulti(DeleteMultiRequest $request): RedirectResponse
    {
        $this->currencyService->deleteMulti($request->input('ids'));

        return back();
    }

    public function setDefault(Currency $currency): RedirectResponse
    {
        $this->currencyService->setDefault($currency->id);

        return back();
    }

    public function syncRates(): RedirectResponse
    {
        $this->currencyService->syncRates();

        return back();
    }
}