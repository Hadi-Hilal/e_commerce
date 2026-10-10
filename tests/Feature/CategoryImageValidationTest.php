<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;
use Modules\Shop\Application\Category\CategoryApplicationService;
use Modules\Shop\Data\CategoryData;
use Modules\Shop\Models\Category;
use Modules\Shop\Repositories\Category\CategoryRepository;
use Tests\TestCase;

class CategoryImageValidationTest extends TestCase
{
    public function test_category_uploads_must_be_images_but_media_paths_are_allowed(): void
    {
        $rules = CategoryData::rules();

        $this->assertTrue(Validator::make(
            ['image' => UploadedFile::fake()->image('category.png')],
            ['image' => $rules['image']]
        )->passes());

        $this->assertTrue(Validator::make(
            ['seo_data' => ['meta_image' => UploadedFile::fake()->image('meta.webp')]],
            ['seo_data.meta_image' => $rules['seo_data.meta_image']]
        )->passes());

        $this->assertFalse(Validator::make(
            ['seo_data' => ['meta_image' => UploadedFile::fake()->create('document.pdf', 1, 'application/pdf')]],
            ['seo_data.meta_image' => $rules['seo_data.meta_image']]
        )->passes());

        $this->assertTrue(Validator::make(
            ['seo_data' => ['meta_image' => 'categories/meta.png']],
            ['seo_data.meta_image' => $rules['seo_data.meta_image']]
        )->passes());
    }

    public function test_removing_category_meta_image_clears_saved_path_and_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('categories/meta.png', 'saved image');

        $category = new Category([
            'seo_data' => ['title' => 'Category SEO', 'meta_image' => 'categories/meta.png'],
        ]);
        $data = new CategoryData(
            name: 'Category',
            attribute_family_id: 1,
            seo_data: ['title' => 'Category SEO', '_remove_meta_image' => true],
        );

        $repository = $this->mock(CategoryRepository::class);
        $repository->shouldReceive('update')
            ->once()
            ->withArgs(function (array $payload): bool {
                $this->assertNull($payload['seo_data']['meta_image']);
                $this->assertArrayNotHasKey('_remove_meta_image', $payload['seo_data']);

                return true;
            })
            ->andReturn($category);

        $flashMessenger = $this->mock(FlashMessengerInterface::class);
        $flashMessenger->shouldReceive('success')->once();

        $service = new CategoryApplicationService($repository, $flashMessenger);

        $this->assertTrue($service->update($category, $data));
        Storage::disk('public')->assertMissing('categories/meta.png');
    }
}
