<?php

namespace Modules\Base\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Base\Application\Seo\SeoApplicationService;
use Modules\Base\Repositories\SiteConfig\SiteConfigRepository;

class SeoController extends Controller
{
    public function __construct(
        private readonly SeoApplicationService $seoService,
        private readonly SiteConfigRepository $siteConfigRepository,
    ) {
        $this->setActive('websiteConfigurations');
    }

    public function index()
    {
        $this->setActive('seo');
        $seo = $this->seoService->allKeyValue();
        $robotsTxt = (string) ($this->siteConfigRepository->get('robots_txt') ?: "User-agent: *\nDisallow:");

        return view('base::admin.seo.index', compact('seo', 'robotsTxt'));
    }

    public function store(Request $request)
    {
        $this->seoService->update(
            data: $request->input('data', []),
            updateTranslations: $request->boolean('update_translations')
        );

        if ($request->has('robots_txt')) {
            $this->siteConfigRepository->set('robots_txt', (string) $request->input('robots_txt', ''));
            cache()->forget('site_configs');
        }

        return back();
    }
}
