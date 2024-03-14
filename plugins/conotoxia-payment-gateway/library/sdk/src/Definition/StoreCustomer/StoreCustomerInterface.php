<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\StoreCustomer;

/**
 * Interface StoreCustomerInterface
 *
 * @package CKPL\Pay\Definition\StoreCustomer
 */
interface StoreCustomerInterface
{
    /**
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;
}
