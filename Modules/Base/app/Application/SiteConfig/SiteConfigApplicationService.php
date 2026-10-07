<?php

namespace Modules\Base\Application\SiteConfig;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Modules\Base\Repositories\SiteConfig\SiteConfigRepository;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;
use Modules\Core\Traits\FileTrait;

class SiteConfigApplicationService
{
    use FileTrait;

    public function __construct(
        private readonly SiteConfigRepository $siteConfigRepository,
        private readonly FlashMessengerInterface $flashMessenger
    ) {}

    public function allKeyValue(): Collection
    {
        return $this->siteConfigRepository->allKeyValue();
    }

    /**
     * @param  array<string, UploadedFile>  $images
     * @param  array<string, mixed>  $data
     * @param  array<string, string|null>  $mediaPaths
     */
    public function update(array $images = [], array $data = [], array $mediaPaths = []): void
    {
        foreach ($images as $key => $file) {
            $oldFile = $this->siteConfigRepository->get($key);
            $path = $this->upload($file, 'site_configs', $key, $oldFile ?: null);
            $this->siteConfigRepository->set($key, $path);
        }

        foreach ($mediaPaths as $key => $path) {
            if (is_string($path) && trim($path) !== '') {
                $this->siteConfigRepository->set((string) $key, trim($path));
            }
        }

        foreach ($data as $key => $value) {
            $this->siteConfigRepository->set((string) $key, is_scalar($value) ? (string) $value : null);
        }

        cache()->forget('site_configs');
        $this->flashMessenger->success();
    }
}
