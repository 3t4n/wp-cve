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

use Goracash\Config as Config;

class ConfigtTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Goracash\Config
     */
    public $Config;

    public function setUp()
    {
        $this->Config = new Config();
    }

    public function testConstruct()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $Config = new Config($configPath);

        $result = $Config->getApplicationName();
        $this->assertEquals('My Test application', $result);

        $result = $Config->getClassConfig('Goracash\Service\Authentication', 'client_id');
        $this->assertEquals('2504fc3027fff14c3ba4781b7bddd7b544e66a9c.apps.goracash.local', $result);

        $result = $Config->getClassConfig('Goracash\Service\Authentication', 'client_secret');
        $this->assertEquals('0e655a0ae7d50be6a0ac2ff85362bfc6a989c4c3', $result);

        $result = $Config->getBasePath();
        $this->assertEquals('http://ws.goracash.dpatiashvili.localdev8', $result);
    }

    public function testGetClassConfig()
    {
        $result = $this->Config->getClassConfig('Not existed class');
        $this->assertNull($result);

        $result = $this->Config->getClassConfig('Goracash\Service\Authentication');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('client_id', $result);
        $this->assertEquals('', $result['client_id']);
        $this->assertArrayHasKey('client_secret', $result);
        $this->assertEquals('', $result['client_secret']);

        $result = $this->Config->getClassConfig('Goracash\Service\Authentication', 'client_id');
        $this->assertEquals('', $result);
    }

    public function testGetClassConfigInvalidKey()
    {
        $result = $this->Config->getClassConfig('Goracash\Service\Authentication', 'Not existed key');
        $this->assertNull($result);
    }

    public function testSetClassConfigStringEmptyValue()
    {
        $this->Config->setClassConfig('myNewClass', 'myKey');
        $result = $this->Config->getClassConfig('myNewClass');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('myKey', $result);
        $this->assertNull($result['myKey']);

        $result = $this->Config->getClassConfig('myNewClass', 'myKey');
        $this->assertNull($result);
    }

    public function testSetClassConfigStringNotEmptyValue()
    {
        $this->Config->setClassConfig('myNewClass', 'myKey', 'myValue');
        $result = $this->Config->getClassConfig('myNewClass');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('myKey', $result);

        $result = $this->Config->getClassConfig('myNewClass', 'myKey');
        $this->assertEquals('myValue', $result);
    }

    public function testSetClassConfigArray()
    {
        $params = array(
            'myKey1' => 'myValue1',
            'myKey2' => 'myValue2',
        );
        $this->Config->setClassConfig('myNewClass', $params);
        $result = $this->Config->getClassConfig('myNewClass');
        $this->assertInternalType('array', $result);
        foreach ($params as $key => $value) {
            $this->assertArrayHasKey($key, $result);
            $this->assertEquals($value, $result[$key]);

            $value_result = $this->Config->getClassConfig('myNewClass', $key);
            $this->assertEquals($value, $value_result );
        }
    }

    public function testGetAuthClass()
    {
        $result = $this->Config->getAuthClass();
        $this->assertEquals('Goracash\Service\Authentication', $result);
    }

    public function testSetAuthClass()
    {
        $this->Config->setAuthClass('myPersonalClass');
        $result = $this->Config->getAuthClass();
        $this->assertEquals('myPersonalClass', $result);
    }

    public function testGetLoggerClass()
    {
        $result = $this->Config->getLoggerClass();
        $this->assertEquals('Goracash\Logger\Clean', $result);
    }

    public function testSetLoggerClass()
    {
        $this->Config->setLoggerClass('myPersonalClass');
        $result = $this->Config->getLoggerClass();
        $this->assertEquals('myPersonalClass', $result);
    }

    public function testGetIoClass()
    {
        $result = $this->Config->getIoClass();
        $this->assertEquals(Config::USE_AUTO_IO_SELECTION, $result);
    }

    public function testSetIoClass()
    {
        $this->Config->setIoClass('myPersonalClass');
        $result = $this->Config->getIoClass();
        $this->assertEquals('myPersonalClass', $result);
    }

    public function testGetApplicationName()
    {
        $result = $this->Config->getApplicationName();
        $this->assertEquals('', $result);
    }

    public function testSetApplicationName()
    {
        $this->Config->setApplicationName('myApplicationName');
        $result = $this->Config->getApplicationName();
        $this->assertEquals('myApplicationName', $result);
    }

    public function testSetClientId()
    {
        $this->Config->setClientId('myClientId');
        $result = $this->Config->getClassConfig('Goracash\Service\Authentication', 'client_id');
        $this->assertEquals('myClientId', $result);
    }

    public function testSetAccessToken()
    {
        $this->Config->setAccessToken('myClientToken');
        $result = $this->Config->getClassConfig('Goracash\Service\Authentication', 'access_token');
        $this->assertEquals('myClientToken', $result);
        $result = $this->Config->getClassConfig('Goracash\Service\Authentication', 'access_token_limit');
        $this->assertEquals('', $result);

        $this->Config->setAccessToken('myClientToken2', '2015-12-01 00::12:03');
        $result = $this->Config->getClassConfig('Goracash\Service\Authentication', 'access_token');
        $this->assertEquals('myClientToken2', $result);
        $result = $this->Config->getClassConfig('Goracash\Service\Authentication', 'access_token_limit');
        $this->assertEquals('2015-12-01 00::12:03', $result);
    }

    public function testGetAccessToken()
    {
        $this->Config->setAccessToken('myClientToken');
        $result = $this->Config->getAccessToken();
        $this->assertEquals('myClientToken', $result);
    }

    public function testGetAccessTokenLimit()
    {
        $this->Config->setAccessToken('myClientToken', '2015-04-01 05:26:13');
        $result = $this->Config->getAccessTokenLimit();
        $this->assertEquals('2015-04-01 05:26:13', $result);
    }

    public function testGetClientId()
    {
        $this->Config->setClientId('myClientId');
        $result = $this->Config->getClientId();
        $this->assertEquals('myClientId', $result);
    }

    public function testSetClientSecret()
    {
        $this->Config->setClientSecret('myClientSecret');
        $result = $this->Config->getClassConfig('Goracash\Service\Authentication', 'client_secret');
        $this->assertEquals('myClientSecret', $result);
    }

    public function testGetClientSecret()
    {
        $this->Config->setClientSecret('myClientSecret');
        $result = $this->Config->getClientSecret();
        $this->assertEquals('myClientSecret', $result);
    }

    public function testGetBasePath()
    {
        $result = $this->Config->getBasePath();
        $this->assertEquals('https://ws.goracash.com', $result);
    }

}