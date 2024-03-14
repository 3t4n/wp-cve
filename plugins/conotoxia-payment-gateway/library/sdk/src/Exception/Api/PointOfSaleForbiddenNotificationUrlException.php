<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class PointOfSaleForbiddenNotificationUrlException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class PointOfSaleForbiddenNotificationUrlException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'point-of-sale-forbidden-notification-url';

    protected $messages = [
        'pl' => 'Podany url dla otrzymywania powiadomień nie został zdefiniowany w punkcie płatności.',
        'en' => 'The given url for receiving notifications has not been defined in the point of sale.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
