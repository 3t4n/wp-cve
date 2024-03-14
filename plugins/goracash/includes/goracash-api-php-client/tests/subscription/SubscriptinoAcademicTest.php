<?php
/**
 * Copyright 2015 Goracash
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use Goracash\Client as Client;
use Goracash\Service\SubscriptionAcademic as SubscriptionAcademic;
use Goracash\Utils as Utils;

class SubscriptionAcademicTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $Client;

    /**
     * @var SubscriptionAcademic
     */
    public $Service;

    public function setUp()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $this->Client = new Client($configPath);
        $this->Client->authenticate();
        $this->Service = new SubscriptionAcademic($this->Client);
    }

    public function testGetAvailableChildLevels()
    {
        $enums = $this->Service->getAvailableChildLevels();
        $this->assertInternalType('array', $enums);
        $this->assertGreaterThan(0, count($enums));
        foreach ($enums as $enum) {
            $this->assertArrayHasKey('id', $enum);
            $this->assertNotEmpty($enum['id']);
            $this->assertInternalType('integer', $enum['id']);
            $this->assertGreaterThan(0, (int)$enum['id']);
            $this->assertArrayHasKey('key', $enum);
            $this->assertNotEmpty($enum['key']);
            $this->assertArrayHasKey('label', $enum);
            $this->assertNotEmpty($enum['label']);
        }
    }

    public function testGetGenders()
    {
        $enums = $this->Service->getAvailableGenders();
        $this->assertInternalType('array', $enums);
        $this->assertGreaterThan(0, count($enums));
        foreach ($enums as $enum) {
            $this->assertArrayHasKey('id', $enum);
            $this->assertNotEmpty($enum['id']);
            $this->assertInternalType('integer', $enum['id']);
            $this->assertGreaterThan(0, (int)$enum['id']);
            $this->assertArrayHasKey('key', $enum);
            $this->assertNotEmpty($enum['key']);
            $this->assertArrayHasKey('label', $enum);
            $this->assertNotEmpty($enum['label']);
        }
    }

    public function testGetAvailableOffers()
    {
        $products = $this->Service->getAvailableOffers();
        $this->assertInternalType('array', $products);
        $this->assertGreaterThan(0, count($products));
        foreach ($products as $product) {
            $this->assertArrayHasKey('id', $product);
            $this->assertNotEmpty($product['id']);
            $this->assertInternalType('integer', $product['id']);
            $this->assertGreaterThan(0, (int)$product['id']);
            $this->assertArrayHasKey('slug', $product);
            $this->assertNotEmpty($product['slug']);
            $this->assertArrayHasKey('title', $product);
            $this->assertNotEmpty($product['title']);
        }
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testPushSubscription_InvalidEmail()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'invalid email',
            'phone' => '0612345678',
            'children' => array(
                array(
                    'firstname' => 'David',
                    'level' => 'S_TERMINAL',
                ),
                array(
                    'firstname' => 'Sabrina',
                    'level' => 'L_TERMINAL',
                ),
            ),
            'offer' => 'bordas-content-subscription-monthly-14-99-with-free',
        );
        $this->Service->pushSubscription($data);
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testPushSubscription_EmptyChidren()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'children' => array(),
            'offer' => 'bordas-content-subscription-monthly-14-99-with-free',
        );
        $this->Service->pushSubscription($data);
    }

    /**
     * @expectedException Goracash\Service\Exception
     */
    public function testPushSubscription_InvalidGender()
    {
        $data = array(
            'gender' => 'invalid gender',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'children' => array(
                array(
                    'firstname' => 'David',
                    'level' => 'S_TERMINAL',
                ),
                array(
                    'firstname' => 'Sabrina',
                    'level' => 'L_TERMINAL',
                ),
            ),
            'offer' => 'bordas-content-subscription-monthly-14-99-with-free',
        );
        $this->Service->pushSubscription($data);
    }

    /**
     * @expectedException Goracash\Service\Exception
     */
    public function testPushSubscription_InvalidChildrenLevel()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'children' => array(
                array(
                    'firstname' => 'David',
                    'level' => 'invalid level',
                ),
                array(
                    'firstname' => 'Sabrina',
                    'level' => 'L_TERMINAL',
                ),
            ),
            'offer' => 'bordas-content-subscription-monthly-14-99-with-free',
        );
        $this->Service->pushSubscription($data);
    }

    /**
     * @expectedException Goracash\Service\Exception
     */
    public function testPushSubscription_InvalidOffer()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'children' => array(
                array(
                    'firstname' => 'David',
                    'level' => 'S_TERMINAL',
                ),
                array(
                    'firstname' => 'Sabrina',
                    'level' => 'L_TERMINAL',
                ),
            ),
            'offer' => 'invalid offer',
        );
        $this->Service->pushSubscription($data);
    }

    public function testPushSubscription()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test1@test.fr',
            'phone' => '0612345678',
            'children' => array(
                array(
                    'firstname' => 'David',
                    'level' => 'S_TERMINAL',
                ),
                array(
                    'firstname' => 'Sabrina',
                    'level' => 'L_TERMINAL',
                ),
            ),
            'offer' => 'bordas-content-subscription-monthly-14-99-with-free',
        );
        $result = $this->Service->pushSubscription($data);
        $this->assertArrayHasKey('id', $result);
        $this->assertInternalType('integer', $result['id']);
        $this->assertGreaterThan(0, (int)$result['id']);

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('ok', $result['status']);

        $this->assertArrayHasKey('redirect_url', $result);
        $this->assertNotEmpty($result['redirect_url']);
    }
}