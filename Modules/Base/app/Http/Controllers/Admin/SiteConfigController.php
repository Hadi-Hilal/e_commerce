<?php

namespace Modules\Base\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Base\Application\SiteConfig\SiteConfigApplicationService;

class SiteConfigController extends Controller
{
    public function __construct(private readonly SiteConfigApplicationService $siteConfigService)
    {
        $this->setActive('websiteConfigurations');
    }

    public function index()
    {
        $this->setActive('websiteConfigurations');
        $siteConfigs = $this->siteConfigService->allKeyValue();

        return view('base::admin.site_config.index', compact('siteConfigs'));
    }

    public function store(Request $request)
    {
        $mediaPaths = (array) $request->input('imgs_media', []);
        $this->siteConfigService->update(
            $request->file('imgs', []),
            $request->input('data', []),
            $mediaPaths
        );

        return back();
    }
}
