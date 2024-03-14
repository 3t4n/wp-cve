<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\StoreCustomer;

/**
 * Class StoreCustomer.
 *
 * @package CKPL\Pay\Definition\StoreCustomer
 */
class StoreCustomer implements StoreCustomerInterface
{
    /**
     * @var string|null
     */
    protected $firstName;

    /**
     * @var string|null
     */
    protected $lastName;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return StoreCustomer
     */
    public function setFirstName(string $firstName): StoreCustomer
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return StoreCustomer
     */
    public function setLastName(string $lastName): StoreCustomer
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return StoreCustomer
     */
    public function setEmail(string $email): StoreCustomer
    {
        $this->email = $email;

        return $this;
    }
}
