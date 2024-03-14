<?php

/**
 * Class ValidationException
 */

declare(strict_types=1);

namespace Paytrail\SDK\Exception;

/**
 * Class ValidationException
 *
 * This exception holds the general expection message
 *
 * @package Paytrail\SDK\Exception
 */
class ValidationException extends \Exception
{
    /**
     * Holds all error messages.
     *
     * @var array
     */
    protected $messages;

    /**
     * Set multiple messages.
     *
     * @param array $messages The error messages.
     * @return ValidationException Return self to enable chaining.
     */
    public function setMessages(array $messages = []): ValidationException
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get the messages.
     *
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
