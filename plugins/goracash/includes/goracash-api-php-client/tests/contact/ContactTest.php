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
use Goracash\Service\Contact as Contact;

class ContactTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $Client;

    /**
     * @var Contact
     */
    public $Service;

    public function setUp()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $this->Client = new Client($configPath);
        $this->Client->authenticate();
        $this->Service = new Contact($this->Client);
    }

    public function testGetGenders()
    {
        $genders = $this->Service->getAvailableGenders();
        $this->assertInternalType('array', $genders);
        $this->assertGreaterThan(0, count($genders));
        foreach ($genders as $gender) {
            $this->assertArrayHasKey('id', $gender);
            $this->assertArrayHasKey('key', $gender);
            $this->assertArrayHasKey('label', $gender);
        }
    }

    public function testGetThematics()
    {
        $thematics = $this->Service->getAvailableThematics();
        $this->assertInternalType('array', $thematics);
        $this->assertGreaterThan(0, count($thematics));
        foreach ($thematics as $thematic) {
            $this->assertArrayHasKey('id', $thematic);
            $this->assertArrayHasKey('key', $thematic);
            $this->assertArrayHasKey('label', $thematic);
        }
    }

    public function testGetMarkets()
    {
        $markets = $this->Service->getAvailableMarkets();
        $this->assertInternalType('array', $markets);
        $this->assertGreaterThan(0, count($markets));
        foreach ($markets as $market) {
            $this->assertArrayHasKey('id', $market);
            $this->assertArrayHasKey('key', $market);
            $this->assertArrayHasKey('label', $market);
        }
    }

    public function testGetTypes()
    {
        $types = $this->Service->getAvailableTypes();
        $this->assertInternalType('array', $types);
        $this->assertGreaterThan(0, count($types));
        foreach ($types as $type) {
            $this->assertArrayHasKey('id', $type);
            $this->assertArrayHasKey('key', $type);
            $this->assertArrayHasKey('label', $type);
        }
    }

    public function testGetDoubleOptinCount()
    {
        $primary = $this->Service->getDoubleOptinCount();
        $this->assertInternalType('integer', $primary);
        $this->assertGreaterThan(0, (int)$primary);

        $params = array(
            'thematic' => 'PSYCHO',
        );
        $secondary = $this->Service->getDoubleOptinCount($params);
        $this->assertInternalType('integer', $secondary);
        $this->assertGreaterThan(0, (int)$secondary);
        $this->assertLessThan($primary, $secondary);

        $params = array(
            'thematics' => array('PSYCHO'),
        );
        $result = $this->Service->getDoubleOptinCount($params);
        $this->assertInternalType('integer', $result);
        $this->assertEquals($secondary, $result);

        $params = array(
            'thematics' => array('PSYCHO', 'ASTRO'),
        );
        $result = $this->Service->getDoubleOptinCount($params);
        $this->assertInternalType('integer', $result);
        $this->assertEquals($primary, $result);
    }

    /**
     * @expectedException Exception
     */
    public function testPushContactMissingParams()
    {
        $data = array(
            'gender' => '',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0601020304',
            'tracker' => 'MyPersonalTracker',
            'market' => 'france',
            'thematic' => 'ASTRO',
        );
        $this->Service->pushContact($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushContactMissingEmailPhone()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'tracker' => 'MyPersonalTracker',
            'market' => 'france',
            'thematic' => 'ASTRO',
        );
        $this->Service->pushContact($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushContactInvalidEmail()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'invalid email',
            'tracker' => 'MyPersonalTracker',
            'market' => 'france',
            'thematic' => 'ASTRO',
        );
        $this->Service->pushContact($data);
    }

    public function testPushContactExisting()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0600000000',
            'tracker' => 'MyPersonalTracker',
            'market' => 'france',
            'thematic' => 'ASTRO',
        );
        $result = $this->Service->pushContact($data);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('email_result', $result);
        $this->assertEquals('already_exist', $result['email_result']);
        $this->assertArrayHasKey('phone_result', $result);
        $this->assertEquals('already_exist', $result['phone_result']);
    }

    public function testPushContactNewEmail()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test' . microtime(true) . '@test.fr',
            'phone' => '0600000000',
            'tracker' => 'MyPersonalTracker',
            'market' => 'france',
            'thematic' => 'ASTRO',
        );
        $result = $this->Service->pushContact($data);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('email_result', $result);
        $this->assertEquals('inserted', $result['email_result']);
        $this->assertArrayHasKey('phone_result', $result);
        $this->assertEquals('already_exist', $result['phone_result']);
    }

    public function testPushContactNewPhone()
    {
        $num = '01';
        for ($a = 0; $a < 8; $a++) {
            $num .= (string)rand(0, 9);
        }
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => $num,
            'tracker' => 'MyPersonalTracker',
            'market' => 'france',
            'thematic' => 'ASTRO',
        );
        $result = $this->Service->pushContact($data);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('email_result', $result);
        $this->assertEquals('already_exist', $result['email_result']);
        $this->assertArrayHasKey('phone_result', $result);
        $this->assertEquals('inserted', $result['phone_result']);
    }

}
