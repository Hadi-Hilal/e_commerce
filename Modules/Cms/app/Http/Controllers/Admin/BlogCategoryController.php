<?php

namespace Modules\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Cms\Models\BlogCategory;
use Modules\Cms\Repositories\BlogCategory\BlogCategoryRepository;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;
use Modules\Core\Http\Requests\DeleteMultiRequest;

class BlogCategoryController extends Controller
{
    protected BlogCategoryRepository $categoryRepository;

    public function __construct(
        BlogCategoryRepository $categoryRepository,
        private readonly FlashMessengerInterface $flashMessenger
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->setActive('cms');
        $this->setActive('blogs_categories');
    }

    public function index()
    {
        $model = $this->categoryRepository->all(['id', 'name', 'slug', 'created_at']);

        return view('cms::admin.blog_category.index', compact('model'));
    }

    public function create()
    {
        return view('cms::admin.blog_category.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = [
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
        ];
        $this->categoryRepository->store($data);
        $this->flashMessenger->success();

        return redirect()->route('admin.blogs_categories.index');
    }

    public function edit(BlogCategory $blogs_category)
    {
        return view('cms::admin.blog_category.edit', compact('blogs_category'));
    }

    public function update(Request $request, BlogCategory $blogs_category): RedirectResponse
    {
        $data = [
            'name' => $request->input('name'),
            'slug' => $blogs_category->slug,
        ];
        $this->categoryRepository->update($data, $blogs_category, $request->boolean('update_translations'));
        $this->flashMessenger->success();

        return redirect()->route('admin.blogs_categories.index');
    }

    public function destroy(BlogCategory $blogs_category): RedirectResponse
    {
        $this->categoryRepository->deleteMulti([$blogs_category->id]);
        $this->flashMessenger->success();

        return back();
    }

    public function deleteMulti(DeleteMultiRequest $request): RedirectResponse
    {
        $this->categoryRepository->deleteMulti($request->input('ids'));
        $this->flashMessenger->success();

        return back();
    }
}
