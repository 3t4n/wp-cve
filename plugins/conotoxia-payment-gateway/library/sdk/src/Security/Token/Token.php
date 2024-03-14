<?php

declare(strict_types=1);

namespace CKPL\Pay\Security\Token;

use DateTime;
use Exception;
use const CKPL\Pay\DATETIME_FORMAT_ATOM;
use CKPL\Pay\Storage\DataConverter\ConvertibleInterface;

/**
 * Class Token.
 *
 * @package CKPL\Pay\Security\Token
 */
class Token implements TokenInterface, ConvertibleInterface
{
    /**
     * @type string
     */
    const ITEM_TOKEN = 'token';

    /**
     * @type string
     */
    const ITEM_EXPIRES_IN = 'expires_in';

    /**
     * @type string
     */
    const ITEM_TYPE = 'type';

    /**
     * @type string
     */
    const ITEM_REQUESTED_AT = 'requested_at';

    /**
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $expiresIn;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var DateTime|null
     */
    protected $requestedAt;

    /**
     * @param array $data
     *
     * @return ConvertibleInterface
     */
    public static function restore($data): ConvertibleInterface
    {
        return new Token(
            $data[static::ITEM_TOKEN],
            $data[static::ITEM_EXPIRES_IN],
            $data[static::ITEM_TYPE],
            DateTime::createFromFormat(DATETIME_FORMAT_ATOM, $data[static::ITEM_REQUESTED_AT])
        );
    }

    /**
     * Token constructor.
     *
     * @param string         $token
     * @param int            $expiresIn
     * @param string         $type
     * @param DateTime|null $requestedAt
     */
    public function __construct(string $token, int $expiresIn, string $type, DateTime $requestedAt = null)
    {
        $this->token = $token;
        $this->expiresIn = $expiresIn;
        $this->type = $type;
        $this->requestedAt = $requestedAt;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return DateTime|null
     */
    public function getRequestedAt(): ?DateTime
    {
        return $this->requestedAt;
    }

    /**
     * @throws Exception
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return (new DateTime())->getTimestamp() > ($this->getExpiresIn() + $this->requestedAt->getTimestamp());
    }

    /**
     * @return array
     */
    public function convert()
    {
        return [
            static::ITEM_TOKEN => $this->getToken(),
            static::ITEM_EXPIRES_IN => $this->getExpiresIn(),
            static::ITEM_TYPE => $this->getType(),
            static::ITEM_REQUESTED_AT => $this->getRequestedAt()->format(DATETIME_FORMAT_ATOM),
        ];
    }
}
