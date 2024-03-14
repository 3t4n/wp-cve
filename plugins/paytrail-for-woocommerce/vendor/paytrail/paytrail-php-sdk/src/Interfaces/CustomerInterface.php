<?php

/**
 * Interface Customer
 */

declare(strict_types=1);

namespace Paytrail\SDK\Interfaces;

use Paytrail\SDK\Exception\ValidationException;

/**
 * Interface Customer
 *
 * An interface for all Customer classes to implement.
 *
 * @package Paytrail\SDK
 */
interface CustomerInterface
{
    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate();

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): ?string;

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return self Return self to enable chaining.
     */
    public function setEmail(?string $email): CustomerInterface;

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName(): ?string;

    /**
     * Set first name.
     *
     * @param string|null $firstName
     *
     * @return self Return self to enable chaining.
     */
    public function setFirstName(?string $firstName): CustomerInterface;

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName(): ?string;

    /**
     * Set last name.
     *
     * @param string|null $lastName
     *
     * @return self Return self to enable chaining.
     */
    public function setLastName(?string $lastName): CustomerInterface;

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone(): ?string;

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return self Return self to enable chaining.
     */
    public function setPhone(?string $phone): CustomerInterface;

    /**
     * Get VAT id.
     *
     * @return string
     */
    public function getVatId(): ?string;

    /**
     * Set VAT id.
     *
     * @param string|null $vatId
     *
     * @return self Return self to enable chaining.
     */
    public function setVatId(?string $vatId): CustomerInterface;

    /**
     * Get Company name.
     *
     * @return string
     */
    public function getCompanyName(): ?string;

    /**
     * Set Company Name.
     *
     * @param string|null $companyName
     *
     * @return self Return self to enable chaining.
     */
    public function setCompanyName(?string $companyName): CustomerInterface;
}
