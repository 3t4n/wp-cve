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
use Goracash\Config as Config;

class ClientTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Goracash\Client
     */
    public $Client;

    public function setUp()
    {
        $this->Client = new Client();
    }

    public function testConstruct()
    {
        // Empty
        $Client = new Client();
        $result = $Client->getApplicationName();
        $this->assertEquals('', $result);

        // String
        $configFile = dirname(__FILE__) . '/../testdata/test.ini';
        $Client = new Client($configFile);
        $result = $Client->getApplicationName();
        $this->assertEquals('My Test application', $result);

        // Config
        $Config = new Config();
        $Config->setApplicationName('myNewApplication');
        $Client = new Client($Config);
        $result = $Client->getApplicationName();
        $this->assertEquals('myNewApplication', $result);
    }

    public function testSetClientID()
    {
        $this->Client->setClientId('myClientId');
        $result = $this->Client->getClassConfig('Goracash\Service\Authentication', 'client_id');
        $this->assertEquals('myClientId', $result);
    }

    public function testSetClientSecret()
    {
        $this->Client->setClientSecret('myClientSecret');
        $result = $this->Client->getClassConfig('Goracash\Service\Authentication', 'client_secret');
        $this->assertEquals('myClientSecret', $result);
    }

    public function testSetAccessToken()
    {
        $this->Client->setAccessToken('myClientToken');
        $result = $this->Client->getClassConfig('Goracash\Service\Authentication', 'access_token');
        $this->assertEquals('myClientToken', $result);
        $result = $this->Client->getClassConfig('Goracash\Service\Authentication', 'access_token_limit');
        $this->assertEquals('', $result);

        $this->Client->setAccessToken('myClientToken2', '2015-12-01 00::12:03');
        $result = $this->Client->getClassConfig('Goracash\Service\Authentication', 'access_token');
        $this->assertEquals('myClientToken2', $result);
        $result = $this->Client->getClassConfig('Goracash\Service\Authentication', 'access_token_limit');
        $this->assertEquals('2015-12-01 00::12:03', $result);
    }

    public function testGetAccessToken()
    {
        $this->Client->setAccessToken('myClientToken');
        $result = $this->Client->getAccessToken();
        $this->assertEquals('myClientToken', $result);
    }

    public function testGetAccessTokenLimit()
    {
        $this->Client->setAccessToken('myClientToken', '2015-04-01 05:26:13');
        $result = $this->Client->getAccessTokenLimit();
        $this->assertEquals('2015-04-01 05:26:13', $result);
    }

    public function testGetClientId()
    {
        $this->Client->setClientId('myClientId');
        $result = $this->Client->getClientId();
        $this->assertEquals('myClientId', $result);
    }

    public function testGetClientSecret()
    {
        $this->Client->setClientSecret('myClientSecret');
        $result = $this->Client->getClientSecret();
        $this->assertEquals('myClientSecret', $result);
    }

    public function testGetApplicationName()
    {
        $result = $this->Client->getApplicationName();
        $this->assertEquals('', $result);
    }

    public function testSetApplicationName()
    {
        $this->Client->setApplicationName('myApplicationName');
        $result = $this->Client->getApplicationName();
        $this->assertEquals('myApplicationName', $result);
    }


    public function testGetClassConfig()
    {
        $result = $this->Client->getClassConfig('Not existed class');
        $this->assertNull($result);

        $result = $this->Client->getClassConfig('Goracash\Service\Authentication');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('client_id', $result);
        $this->assertEquals('', $result['client_id']);
        $this->assertArrayHasKey('client_secret', $result);
        $this->assertEquals('', $result['client_secret']);

        $result = $this->Client->getClassConfig('Goracash\Service\Authentication', 'client_id');
        $this->assertEquals('', $result);
    }

    public function testGetClassConfigInvalidKey()
    {
        $result = $this->Client->getClassConfig('Goracash\Service\Authentication', 'Not existed key');
        $this->assertNull($result);
    }

    public function testSetClassConfigStringEmptyValue()
    {
        $this->Client->setClassConfig('myNewClass', 'myKey');
        $result = $this->Client->getClassConfig('myNewClass');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('myKey', $result);
        $this->assertNull($result['myKey']);

        $result = $this->Client->getClassConfig('myNewClass', 'myKey');
        $this->assertNull($result);
    }

    public function testSetClassConfigStringNotEmptyValue()
    {
        $this->Client->setClassConfig('myNewClass', 'myKey', 'myValue');
        $result = $this->Client->getClassConfig('myNewClass');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('myKey', $result);

        $result = $this->Client->getClassConfig('myNewClass', 'myKey');
        $this->assertEquals('myValue', $result);
    }

    public function testSetClassConfigArray()
    {
        $params = array(
            'myKey1' => 'myValue1',
            'myKey2' => 'myValue2',
        );
        $this->Client->setClassConfig('myNewClass', $params);
        $result = $this->Client->getClassConfig('myNewClass');
        $this->assertInternalType('array', $result);
        foreach ($params as $key => $value) {
            $this->assertArrayHasKey($key, $result);
            $this->assertEquals($value, $result[$key]);

            $value_result = $this->Client->getClassConfig('myNewClass', $key);
            $this->assertEquals($value, $value_result );
        }
    }

    public function testSetLogger()
    {
        $Logger = new \Goracash\Logger\File($this->Client);
        $this->Client->setLogger($Logger);
        $Logger = $this->Client->getLogger();
        $this->assertInstanceOf('Goracash\Logger\File', $Logger);
    }

    public function testGetLogger()
    {
        $Logger = $this->Client->getLogger();
        $this->assertInstanceOf('Goracash\Logger\Clean', $Logger);
    }

    public function testSetIo()
    {
        $Io = new \Goracash\IO\Curl($this->Client);
        $this->Client->setIo($Io);
        $Io = $this->Client->getIo();
        $this->assertInstanceOf('Goracash\IO\Curl', $Io);
    }

    public function testAuthenticate()
    {
        $configFile = dirname(__FILE__) . '/../testdata/test.ini';
        $Client = new Client($configFile);
        $Client->authenticate();
        $result = $Client->hasAuthenticated();
        $this->assertTrue($result);
    }

    public function testGetBasePath()
    {
        $result = $this->Client->getBasePath();
        $this->assertEquals('https://ws.goracash.com', $result);
    }

    public function testGetLibrary()
    {
        $Client = new Client();
        $result = $Client->getLibraryVersion();
        $this->assertEquals(Client::LIBVER, $result);
    }

    public function testSetAuthConfig()
    {
        $Client = new Client();
        $data = array(
            'client_id' => 'myClientID',
            'client_secret' => 'myClientSecret',
        );
        $Client->setAuthConfig(json_encode($data));
        $this->assertEquals($data['client_id'], $Client->getClientId());
        $this->assertEquals($data['client_secret'], $Client->getClientSecret());
    }

    public function testSetAuthConfigFile()
    {
        $Client = new Client();
        $Client->setAuthConfigFile(dirname(__FILE__) . '/../testdata/test.json');
        $this->assertEquals('MyGoracashId', $Client->getClientId());
        $this->assertEquals('MyGoracashSecret', $Client->getClientSecret());
    }

}