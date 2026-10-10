<?php

namespace Modules\Cms\Repositories\Slide;

use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Cms\Models\Slide;
use Modules\Core\Traits\FileTrait;

class SlideRepository
{
    use FileTrait;

    public function all(array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return Slide::select($columns)
            ->when(isset($filters['publish']) && $filters['publish'] !== null && $filters['publish'] !== '',
                fn ($q) => $q->where('status', $filters['publish']))
            ->orderBy('rank', 'asc')
            ->paginate(Config::get('core.page_size', 10));
    }

    public function store(array $payload): Model
    {
        return Slide::create($payload);
    }

    public function update(array $payload, Model $model, bool $updateTranslations = false): Model
    {
        $model->update($payload);

        return $model;
    }

    public function deleteMulti(array $ids): void
    {
        $images = Slide::whereIn('id', $ids)->pluck('image')->filter()->all();
        Slide::whereIn('id', $ids)->delete();

        foreach ($images as $image) {
            $this->deleteFile((string) $image);
        }
    }
}
