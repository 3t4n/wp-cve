<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class PublicKeyAlreadyExistException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class PublicKeyAlreadyExistException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'public-key-already-exist';

    protected $messages = [
        'pl' => 'Klucz publiczny został już dodany.',
        'en' => 'Public key already exist.'
    ];

    /**
     * @type string
     */
    private $kid;

    /**
     * HttpException constructor.
     *
     * @param string $title
     * @param string $reason
     * @param string $kid
     */
    public function __construct(string $title, string $reason, string $kid)
    {
        parent::__construct($reason, $title);
        $this->kid = $kid;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }

    /**
     * @return string
     */
    public function getKid(): string {
        return $this->kid;
    }
}
