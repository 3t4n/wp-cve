<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

use CKPL\Pay\Exception\Api\TranslationInterface;

/**
 * Class HttpConflictException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpConflictException extends HttpException implements TranslationInterface
{
    /**
     * @type int
     */
    const STATUS_CODE = 409;

    /**
     * @type array
     */
    protected $messages = [];

    /**
     * Gets translated message.
     *
     * @param $languageCode string Language iso code.
     * @return string|null
     */
    public function getTranslatedMessage(string $languageCode): ?string
    {
        $supportedLanguages = ['en', 'pl'];

        if (!in_array($languageCode, $supportedLanguages)) {
            $languageCode = 'en';
        }

        return $this->messages[$languageCode] ?? null;
    }
}
