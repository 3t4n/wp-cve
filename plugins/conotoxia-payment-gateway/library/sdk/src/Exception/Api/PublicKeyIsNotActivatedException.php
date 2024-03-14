<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class PublicKeyIsNotActivatedException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class PublicKeyIsNotActivatedException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'public-key-is-not-activated';

    protected $messages = [
        'pl' => 'Klucz użyty do weryfikacji podpisu nie został aktywowany.',
        'en' => 'Key used for verification is not activated.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
