<?php

namespace CKPL\Pay\Definition\Payment\Builder;

use CKPL\Pay\Definition\StoreCustomer\StoreCustomerInterface;

/**
 * Interface StoreCustomerBuilderInterface.
 *
 * @package CKPL\Pay\Definition\Payment\Builder
 */
interface StoreCustomerBuilderInterface
{
    /**
     * Customer first name.
     *
     * @param string $firstName
     *
     * @return StoreCustomerBuilderInterface
     */
    public function setFirstName(string $firstName): StoreCustomerBuilderInterface;

    /**
     * Customer last name.
     *
     * @param string $lastName
     *
     * @return StoreCustomerBuilderInterface
     */
    public function setLastName(string $lastName): StoreCustomerBuilderInterface;

    /**
     * Customer email.
     *
     * @param string $email
     *
     * @return StoreCustomerBuilderInterface
     */
    public function setEmail(string $email): StoreCustomerBuilderInterface;

    /**
     * Returns Store customer definition.
     *
     * @return StoreCustomerInterface
     */
    public function getStoreCustomer(): StoreCustomerInterface;
}
