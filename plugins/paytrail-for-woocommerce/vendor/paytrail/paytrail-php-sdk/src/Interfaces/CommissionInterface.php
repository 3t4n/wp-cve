<?php

/**
 * Interface Commission
 */

declare(strict_types=1);

namespace Paytrail\SDK\Interfaces;

use Paytrail\SDK\Model\Commission;
use Paytrail\SDK\Exception\ValidationException;

/**
 * Interface Commission
 *
 * An interface for all Commission classes to implement.
 *
 * @package Paytrail\SDK
 */
interface CommissionInterface
{
    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate();

    /**
     * The setter for the merchant.
     *
     * @param string $merchant
     * @return Commission Return self to enable chaining.
     */
    public function setMerchant(string $merchant): Commission;

    /**
     * The getter for the merchant.
     *
     * @return string
     */
    public function getMerchant(): string;

    /**
     * The setter for the amount.
     *
     * @param int $amount
     * @return Commission Return self to enable chaining.
     */
    public function setAmount(int $amount): Commission;

    /**
     * The getter for the amount.
     *
     * @return int
     */
    public function getAmount(): int;
}
