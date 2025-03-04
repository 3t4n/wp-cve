<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Exception;

/**
 * @author Oskar Stark <oskarstark@googlemail.com>
 */
class MissingRequiredOptionException extends \WPPayVendor\Symfony\Component\Translation\Exception\IncompleteDsnException
{
    public function __construct(string $option, ?string $dsn = null, ?\Throwable $previous = null)
    {
        $message = \sprintf('The option "%s" is required but missing.', $option);
        parent::__construct($message, $dsn, $previous);
    }
}
