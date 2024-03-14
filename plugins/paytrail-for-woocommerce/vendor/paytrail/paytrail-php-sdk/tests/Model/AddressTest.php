<?php

declare(strict_types=1);

namespace Tests\Model;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testExceptions()
    {
        $this->expectException(ValidationException::class);
        $a = new Address();
        $a->validate();
    }


    public function testIsAddressValid()
    {
        $a = new Address();

        $this->assertInstanceOf(
            Address::class,
            $a
        );

        $a->setStreetAddress('Downing street')
            ->setPostalCode('121212')
            ->setCity('London')
            ->setCountry('United Kingdom');

        try {
            $this->assertIsBool($a->validate(), 'Address::validate');
        } catch (ValidationException $e) {
        }
    }

    public function testExeceptionMessages()
    {
        $a = new Address();

        try {
            $a->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('streetAddress is empty', $e->getMessage());
        }

        $a->setStreetAddress('Piispankatu 2');

        try {
            $a->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('postalCode is empty', $e->getMessage());
        }

        $a->setPostalCode('123123');

        try {
            $a->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('city is empty', $e->getMessage());
        }

        $a->setCity('Tampere');

        try {
            $a->validate();
        } catch (ValidationException $e) {
            $this->assertStringContainsString('country is empty', $e->getMessage());
        }

        $a->setCountry('Finland');

        try {
            $this->assertIsBool($a->validate(), 'Address::validate is bool');
        } catch (ValidationException $e) {
        }
    }

    public function testSetStreetAddressAcceptsNull()
    {
        $addressModel = new Address();
        $addressModel->setStreetAddress(null);
        $this->assertNull($addressModel->getStreetAddress());
    }
}
