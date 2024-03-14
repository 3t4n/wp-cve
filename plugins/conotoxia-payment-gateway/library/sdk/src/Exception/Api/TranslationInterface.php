<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

/**
 * Interface TranslationInterface.
 *
 * @package CKPL\Pay\Exception\Api
 */
interface TranslationInterface
{
    /**
     * Gets translated message.
     *
     * @param $languageCode string Language iso code.
     * @return string|null
     */
    public function getTranslatedMessage(string $languageCode): ?string;
}