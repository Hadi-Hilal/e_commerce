<?php

namespace Modules\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Cms\Application\Slide\Commands\UpsertSlideCommand;
use Modules\Cms\Application\Slide\SlideApplicationService;
use Modules\Cms\Application\Shared\Queries\ContentListQuery;
use Modules\Cms\Data\SlideData;
use Modules\Cms\Models\Slide;
use Modules\Core\Http\Requests\DeleteMultiRequest;
use Modules\User\Enums\CmsStatus;

class SlideController extends Controller
{
    public function __construct(private readonly SlideApplicationService $slideService)
    {
        $this->setActive('cms');
        $this->setActive('slides');
    }

    public function index()
    {
        $model = $this->slideService->paginate(new ContentListQuery(
            publish: request()->query('publish')
        ), [
            'id', 'image', 'link', 'link_text', 'rank', 'status', 'created_at',
        ]);

        return view('cms::admin.slide.index', compact('model'));
    }

    public function create()
    {
        return view('cms::admin.slide.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = SlideData::validate($this->preparePayload($request));

        $this->slideService->store(UpsertSlideCommand::fromValidated($data));

        return redirect()->route('admin.slides.index');
    }

    public function edit(Slide $slide)
    {
        return view('cms::admin.slide.edit', compact('slide'));
    }

    public function update(Request $request, Slide $slide): RedirectResponse
    {
        $updateTranslations = $request->boolean('update_translations');
        $payload = $this->preparePayload($request, $slide);
        $data = SlideData::validate($payload);

        $this->slideService->update($slide, UpsertSlideCommand::fromValidated($data, $updateTranslations));

        return redirect()->route('admin.slides.index');
    }

    public function deleteMulti(DeleteMultiRequest $request): RedirectResponse
    {
        $this->slideService->deleteMulti($request->input('ids'));

        return back();
    }

    /**
     * @return array<string, mixed>
     */
    private function preparePayload(Request $request, ?Slide $slide = null): array
    {
        $linkText = (string) $request->input('link_text', '');

        return [
            'image' => $request->file('img') ?: $request->input('img_media_path'),
            'link' => $request->input('link'),
            'link_text' => $linkText,
            'rank' => (int) $request->input('rank', 0),
            'status' => $request->has('publish') ? CmsStatus::PUBLISHED : CmsStatus::ARCHIVED,
            'slug' => $slide?->slug ?: Str::slug($linkText ?: 'slide').'-'.Str::lower(Str::random(6)),
        ];
    }
}
