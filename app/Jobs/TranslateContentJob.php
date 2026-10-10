<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Contracts\Translation\TranslatorInterface;

class TranslateContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $entityId,
        public readonly string $entityClass,
        public readonly string $field,
        public readonly string $value,
        public readonly array $languages
    ) {}

    public function handle(TranslatorInterface $translator): void
    {
        $entity = $this->entityClass::find($this->entityId);
        if (! $entity) {
            return;
        }

        $translations = $entity->getTranslations($this->field);

        foreach ($this->languages as $language) {
            try {
                $translations[$language] = $translator->translate($language, $this->value);
            } catch (\Exception $exception) {
                \Log::error("Translation failed for {$this->entityClass}#{$this->entityId}, field: {$this->field}, lang: {$language}");
            }
        }

        $entity->update([$this->field => $translations]);
    }
}
