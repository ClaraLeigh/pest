<?php

declare(strict_types=1);

namespace Pest\ArchPresets;

/**
 * @internal
 */
final class Security extends AbstractPreset
{
    /**
     * Executes the arch preset.
     */
    public function execute(): void
    {
        $this->expectations[] = expect([
            'md5',
            'sha1',
            'uniqid',
            'rand',
            'mt_rand',
            'tempnam',
            'str_shuffle',
            'shuffle',
            'array_rand'
        ])->not->toBeUsed();
    }
}