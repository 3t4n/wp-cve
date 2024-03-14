<?php

namespace CKPL\Pay\Definition\Payment\Builder;

use CKPL\Pay\Definition\StoreCustomer\StoreCustomer;
use CKPL\Pay\Definition\StoreCustomer\StoreCustomerInterface;

/**
 * Class StoreCustomerBuilder.
 *
 * @package CKPL\Pay\Definition\Payment\Builder
 */
class StoreCustomerBuilder implements StoreCustomerBuilderInterface
{
    /**
     * @var StoreCustomer
     */
    protected $storeCustomer;

    /**
     * StoreCustomerBuilder constructor.
     */
    public function __construct()
    {
        $this->initializeStoreCustomer();
    }

    /**
     * Customer first name.
     *
     * @param string $firstName
     *
     * @return StoreCustomerBuilderInterface
     */
    public function setFirstName(string $firstName): StoreCustomerBuilderInterface
    {
        $this->storeCustomer->setFirstName($firstName);

        return $this;
    }

    /**
     * Customer last name.
     *
     * @param string $lastName
     *
     * @return StoreCustomerBuilderInterface
     */
    public function setLastName(string $lastName): StoreCustomerBuilderInterface
    {
        $this->storeCustomer->setLastName($lastName);

        return $this;
    }

    /**
     * Customer email.
     *
     * @param string $email
     *
     * @return StoreCustomerBuilderInterface
     */
    public function setEmail(string $email): StoreCustomerBuilderInterface
    {
       $this->storeCustomer->setEmail($email);

       return $this;
    }

    /**
     * Returns Store customer definition.
     *
     * @return StoreCustomerInterface
     */
    public function getStoreCustomer(): StoreCustomerInterface
    {
        return $this->storeCustomer;
    }

    /**
     * @return void
     */
    protected function initializeStoreCustomer(): void
    {
        $this->storeCustomer = new StoreCustomer();
    }
}
