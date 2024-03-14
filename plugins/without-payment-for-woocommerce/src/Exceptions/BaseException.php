<?php

declare(strict_types=1);

namespace Coderun\WithoutPaymentWoocommerce\Exceptions;

use RuntimeException;

/**
 * Class BaseException
 *
 * @package Coderun\WithoutPaymentWoocommerce\Exceptions
 */
class BaseException extends RuntimeException
{
    /**
     * @var int
     */
    protected const CODE_SUCCESS = 200;
}
