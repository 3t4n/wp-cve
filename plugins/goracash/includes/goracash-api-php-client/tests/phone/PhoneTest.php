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
use Goracash\Service\Phone as Phone;

class PhoneTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $Client;

    /**
     * @var Phone
     */
    public $Service;

    public function setUp()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $this->Client = new Client($configPath);
        $this->Client->authenticate();
        $this->Service = new Phone($this->Client);
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

    public function testGetCountries()
    {
        $types = $this->Service->getAvailableCountries();
        $this->assertInternalType('array', $types);
        $this->assertGreaterThan(0, count($types));
        foreach ($types as $type) {
            $this->assertArrayHasKey('id', $type);
            $this->assertArrayHasKey('key', $type);
            $this->assertArrayHasKey('label', $type);
        }
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetAttachedNumbers_invalidDate()
    {
        $params = array(
            'date' => 'invalid format',
        );
        $this->Service->getAttachedNumbers($params);
    }

    public function testGetAttachedNumbers()
    {
        $numbers = $this->Service->getAttachedNumbers();
        $this->assertInternalType('array', $numbers);
        $this->assertGreaterThan(0, count($numbers));
        foreach ($numbers as $number) {
            $this->assertArrayHasKey('id', $number);

            $this->assertArrayHasKey('type', $number);
            $this->assertInternalType('array', $number['type']);
            $this->assertArrayHasKey('id', $number['type']);
            $this->assertArrayHasKey('key', $number['type']);
            $this->assertArrayHasKey('label', $number['type']);

            $this->assertArrayHasKey('thematic', $number);
            $this->assertInternalType('array', $number['thematic']);
            $this->assertArrayHasKey('id', $number['thematic']);
            $this->assertArrayHasKey('key', $number['thematic']);
            $this->assertArrayHasKey('label', $number['thematic']);

            $this->assertArrayHasKey('market', $number);
            $this->assertInternalType('array', $number['market']);
            $this->assertArrayHasKey('id', $number['market']);
            $this->assertArrayHasKey('key', $number['market']);
            $this->assertArrayHasKey('label', $number['market']);

            $this->assertArrayHasKey('country', $number);
            $this->assertInternalType('array', $number['country']);
            $this->assertArrayHasKey('id', $number['country']);
            $this->assertArrayHasKey('key', $number['country']);
            $this->assertArrayHasKey('label', $number['country']);

            $this->assertArrayHasKey('value', $number);
        }
    }

    public function testGetAttachedNumbers_withParams()
    {
        $params = array(
            'type' => 'PAID'
        );
        $numbers = $this->Service->getAttachedNumbers($params);
        $this->assertInternalType('array', $numbers);
        $this->assertGreaterThan(0, count($numbers));
        foreach ($numbers as $number) {
            $this->assertArrayHasKey('id', $number);

            $this->assertArrayHasKey('type', $number);
            $this->assertInternalType('array', $number['type']);
            $this->assertArrayHasKey('id', $number['type']);
            $this->assertArrayHasKey('key', $number['type']);
            $this->assertArrayHasKey('label', $number['type']);
            $this->assertEquals('Payant', $number['type']['label']);

            $this->assertArrayHasKey('thematic', $number);
            $this->assertInternalType('array', $number['thematic']);
            $this->assertArrayHasKey('id', $number['thematic']);
            $this->assertArrayHasKey('key', $number['thematic']);
            $this->assertArrayHasKey('label', $number['thematic']);

            $this->assertArrayHasKey('market', $number);
            $this->assertInternalType('array', $number['market']);
            $this->assertArrayHasKey('id', $number['market']);
            $this->assertArrayHasKey('key', $number['market']);
            $this->assertArrayHasKey('label', $number['market']);

            $this->assertArrayHasKey('country', $number);
            $this->assertInternalType('array', $number['country']);
            $this->assertArrayHasKey('id', $number['country']);
            $this->assertArrayHasKey('key', $number['country']);
            $this->assertArrayHasKey('label', $number['country']);

            $this->assertArrayHasKey('value', $number);
        }
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testPushCallback_invalidCaller()
    {
        $this->Service->pushCallback('invalidNumber', '0033601010101');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testPushCallback_invalidNumber()
    {
        $this->Service->pushCallback('0033175752585', 'invalidNumber');
    }

    public function testPushCallback()
    {
        $num = '';
        for ($a = 0; $a < 8; $a++) {
            $num .= rand(0, 9);
        }

        $callback_status = $this->Service->pushCallback('0033175752585', '00336' . $num);
        $this->assertEquals('ok', $callback_status);

        $callback_status = $this->Service->pushCallback('0033175752585', '00336' . $num);
        $this->assertEquals('already_exist', $callback_status);
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhonesCBStatsInvalidDateLbound()
    {
        $this->Service->getPhonesCBStats('invalid date', '2013-12-25 00:00:00');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhonesCBStatsInvalidDateUbound()
    {
        $this->Service->getPhonesCBStats('2013-12-20 00:00:00', 'invalid date');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhonesCBStatsOutPeriod()
    {
        $this->Service->getPhonesCBStats('2013-12-20 00:00:00', '2014-12-20 00:00:00');
    }

    public function testGetPhonesCBStats()
    {
        $stats = $this->Service->getPhonesCBStats('2014-06-01 00:00:00', '2014-07-01 00:00:00');

        $this->assertInternalType('array', $stats);
        $this->assertArrayHasKey('global', $stats);
        $this->assertInternalType('array', $stats['global']);
        $this->assertArrayHasKey('total', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['total']);
        $this->assertArrayHasKey('treated', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['treated']);
        $this->assertArrayHasKey('subscription', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['subscription']);
        $this->assertArrayHasKey('transaction', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['transaction']);
        $this->assertArrayHasKey('amount', $stats['global']);
        $this->assertInternalType('float', $stats['global']['amount']);
        $this->assertArrayHasKey('callback', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['callback']);

        $this->assertArrayHasKey('phones', $stats);
        $this->assertInternalType('array', $stats['phones']);
        $this->assertGreaterThan(0, count($stats['phones']));
        foreach ($stats['phones'] as $phone_id => $phone_data) {
            $this->assertInternalType('array', $phone_data);

            $this->assertArrayHasKey('id', $phone_data);
            $this->assertArrayHasKey('value', $phone_data);

            $this->assertArrayHasKey('type', $phone_data);
            $this->assertInternalType('array', $phone_data['type']);
            $this->assertArrayHasKey('id', $phone_data['type']);
            $this->assertArrayHasKey('key', $phone_data['type']);
            $this->assertArrayHasKey('label', $phone_data['type']);
            $this->assertEquals('Gratuit', $phone_data['type']['label']);

            $this->assertArrayHasKey('thematic', $phone_data);
            $this->assertInternalType('array', $phone_data['thematic']);
            $this->assertArrayHasKey('id', $phone_data['thematic']);
            $this->assertArrayHasKey('key', $phone_data['thematic']);
            $this->assertArrayHasKey('label', $phone_data['thematic']);

            $this->assertArrayHasKey('market', $phone_data);
            $this->assertInternalType('array', $phone_data['market']);
            $this->assertArrayHasKey('id', $phone_data['market']);
            $this->assertArrayHasKey('key', $phone_data['market']);
            $this->assertArrayHasKey('label', $phone_data['market']);

            $this->assertArrayHasKey('country', $phone_data);
            $this->assertInternalType('array', $phone_data['country']);
            $this->assertArrayHasKey('id', $phone_data['country']);
            $this->assertArrayHasKey('key', $phone_data['country']);
            $this->assertArrayHasKey('label', $phone_data['country']);

            $this->assertArrayHasKey('total', $phone_data);
            $this->assertInternalType('integer', $phone_data['total']);
            $this->assertArrayHasKey('treated', $phone_data);
            $this->assertInternalType('integer', $phone_data['treated']);
            $this->assertArrayHasKey('subscription', $phone_data);
            $this->assertInternalType('integer', $phone_data['subscription']);
            $this->assertArrayHasKey('transaction', $phone_data);
            $this->assertInternalType('integer', $phone_data['transaction']);
            $this->assertArrayHasKey('amount', $phone_data);
            $this->assertArrayHasKey('callback', $phone_data);
            $this->assertInternalType('integer', $phone_data['callback']);
        }

        $this->assertArrayHasKey('dates', $stats);
        $this->assertInternalType('array', $stats['dates']);
        $this->assertGreaterThan(0, count($stats['dates']));
        foreach ($stats['dates'] as $date => $date_data) {
            $this->assertInternalType('array', $date_data);
            $this->assertArrayHasKey('total', $date_data);
            $this->assertInternalType('integer', $date_data['total']);
            $this->assertArrayHasKey('treated', $date_data);
            $this->assertInternalType('integer', $date_data['treated']);
            $this->assertArrayHasKey('subscription', $date_data);
            $this->assertInternalType('integer', $date_data['subscription']);
            $this->assertArrayHasKey('transaction', $date_data);
            $this->assertInternalType('integer', $date_data['transaction']);
            $this->assertArrayHasKey('amount', $date_data);
            $this->assertArrayHasKey('callback', $date_data);
            $this->assertInternalType('integer', $date_data['callback']);
        }
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhonesAudiotelStatsInvalidDateLbound()
    {
        $this->Service->getPhonesAudiotelStats('invalid date', '2013-12-25 00:00:00');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhonesAudiotelStatsInvalidDateUbound()
    {
        $this->Service->getPhonesAudiotelStats('2013-12-20 00:00:00', 'invalid date');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhonesAudiotelStatsOutPeriod()
    {
        $this->Service->getPhonesAudiotelStats('2013-12-20 00:00:00', '2014-12-20 00:00:00');
    }

    public function testGetPhonesAudiotelStats()
    {
        $stats = $this->Service->getPhonesAudiotelStats('2014-06-01 00:00:00', '2014-07-01 00:00:00');

        $this->assertInternalType('array', $stats);
        $this->assertArrayHasKey('global', $stats);
        $this->assertInternalType('array', $stats['global']);
        $this->assertArrayHasKey('count', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['count']);
        $this->assertArrayHasKey('amount', $stats['global']);
        $this->assertInternalType('float', $stats['global']['amount']);
        $this->assertArrayHasKey('duration', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['duration']);

        $this->assertArrayHasKey('phones', $stats);
        $this->assertInternalType('array', $stats['phones']);
        $this->assertGreaterThan(0, count($stats['phones']));
        foreach ($stats['phones'] as $phone_id => $phone_data) {
            $this->assertInternalType('array', $phone_data);

            $this->assertArrayHasKey('id', $phone_data);
            $this->assertArrayHasKey('value', $phone_data);

            $this->assertArrayHasKey('type', $phone_data);
            $this->assertInternalType('array', $phone_data['type']);
            $this->assertArrayHasKey('id', $phone_data['type']);
            $this->assertArrayHasKey('key', $phone_data['type']);
            $this->assertArrayHasKey('label', $phone_data['type']);
            $this->assertEquals('Payant', $phone_data['type']['label']);

            $this->assertArrayHasKey('thematic', $phone_data);
            $this->assertInternalType('array', $phone_data['thematic']);
            $this->assertArrayHasKey('id', $phone_data['thematic']);
            $this->assertArrayHasKey('key', $phone_data['thematic']);
            $this->assertArrayHasKey('label', $phone_data['thematic']);

            $this->assertArrayHasKey('market', $phone_data);
            $this->assertInternalType('array', $phone_data['market']);
            $this->assertArrayHasKey('id', $phone_data['market']);
            $this->assertArrayHasKey('key', $phone_data['market']);
            $this->assertArrayHasKey('label', $phone_data['market']);

            $this->assertArrayHasKey('country', $phone_data);
            $this->assertInternalType('array', $phone_data['country']);
            $this->assertArrayHasKey('id', $phone_data['country']);
            $this->assertArrayHasKey('key', $phone_data['country']);
            $this->assertArrayHasKey('label', $phone_data['country']);

            $this->assertArrayHasKey('count', $phone_data);
            $this->assertInternalType('integer', $phone_data['count']);
            $this->assertArrayHasKey('amount', $phone_data);
            $this->assertInternalType('float', $phone_data['amount']);
            $this->assertArrayHasKey('duration', $phone_data);
            $this->assertInternalType('integer', $phone_data['duration']);
        }

        $this->assertArrayHasKey('dates', $stats);
        $this->assertInternalType('array', $stats['dates']);
        $this->assertGreaterThan(0, count($stats['dates']));
        foreach ($stats['dates'] as $date => $date_data) {
            $this->assertInternalType('array', $date_data);
            $this->assertArrayHasKey('count', $date_data);
            $this->assertInternalType('integer', $date_data['count']);
            $this->assertArrayHasKey('amount', $date_data);
            $this->assertInternalType('float', $date_data['amount']);
            $this->assertArrayHasKey('duration', $date_data);
            $this->assertInternalType('integer', $date_data['duration']);
        }
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhoneAudiotelStatsInvalidDateLbound()
    {
        $this->Service->getPhoneAudiotelStats('3015937', 'invalid date', '2013-12-25 00:00:00');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhoneAudiotelStatsInvalidDateUbound()
    {
        $this->Service->getPhoneAudiotelStats('3015937', '2013-12-20 00:00:00', 'invalid date');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhoneAudiotelStatsOutPeriod()
    {
        $this->Service->getPhoneAudiotelStats('3015937', '2013-12-20 00:00:00', '2014-12-20 00:00:00');
    }

    public function testGetPhoneAudiotelStats()
    {
        $stats = $this->Service->getPhoneAudiotelStats('0033892780031', '2014-06-01 00:00:00', '2014-07-01 00:00:00');

        $this->assertInternalType('array', $stats);
        $this->assertArrayHasKey('global', $stats);
        $this->assertInternalType('array', $stats['global']);
        $this->assertArrayHasKey('count', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['count']);
        $this->assertArrayHasKey('amount', $stats['global']);
        $this->assertInternalType('float', $stats['global']['amount']);
        $this->assertArrayHasKey('duration', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['duration']);

        $this->assertArrayHasKey('id', $stats);
        $this->assertArrayHasKey('value', $stats);

        $this->assertArrayHasKey('type', $stats);
        $this->assertInternalType('array', $stats['type']);
        $this->assertArrayHasKey('id', $stats['type']);
        $this->assertArrayHasKey('key', $stats['type']);
        $this->assertArrayHasKey('label', $stats['type']);
        $this->assertEquals('Payant', $stats['type']['label']);

        $this->assertArrayHasKey('thematic', $stats);
        $this->assertInternalType('array', $stats['thematic']);
        $this->assertArrayHasKey('id', $stats['thematic']);
        $this->assertArrayHasKey('key', $stats['thematic']);
        $this->assertArrayHasKey('label', $stats['thematic']);

        $this->assertArrayHasKey('market', $stats);
        $this->assertInternalType('array', $stats['market']);
        $this->assertArrayHasKey('id', $stats['market']);
        $this->assertArrayHasKey('key', $stats['market']);
        $this->assertArrayHasKey('label', $stats['market']);

        $this->assertArrayHasKey('country', $stats);
        $this->assertInternalType('array', $stats['country']);
        $this->assertArrayHasKey('id', $stats['country']);
        $this->assertArrayHasKey('key', $stats['country']);
        $this->assertArrayHasKey('label', $stats['country']);

        $this->assertArrayHasKey('dates', $stats);
        $this->assertGreaterThan(0, count($stats['dates']));
        foreach ($stats['dates'] as $date => $date_data) {
            $this->assertInternalType('array', $date_data);
            $this->assertArrayHasKey('count', $date_data);
            $this->assertInternalType('integer', $date_data['count']);
            $this->assertArrayHasKey('amount', $date_data);
            $this->assertArrayHasKey('duration', $date_data);
            $this->assertInternalType('integer', $date_data['duration']);
        }
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhoneCBStatsInvalidDateLbound()
    {
        $this->Service->getPhoneCBStats('3015937', 'invalid date', '2013-12-25 00:00:00');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhoneCBStatsInvalidDateUbound()
    {
        $this->Service->getPhoneCBStats('3015937', '2013-12-20 00:00:00', 'invalid date');
    }

    /**
     * @expectedException Goracash\Service\InvalidArgumentException
     */
    public function testGetPhoneCBStatsOutPeriod()
    {
        $this->Service->getPhoneCBStats('3015937', '2013-12-20 00:00:00', '2014-12-20 00:00:00');
    }

    public function testGetPhoneCBStats()
    {
        $stats = $this->Service->getPhoneCBStats('0033175752580', '2014-06-01 00:00:00', '2014-07-01 00:00:00');

        $this->assertInternalType('array', $stats);
        $this->assertArrayHasKey('global', $stats);
        $this->assertInternalType('array', $stats['global']);
        $this->assertArrayHasKey('total', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['total']);
        $this->assertArrayHasKey('treated', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['treated']);
        $this->assertArrayHasKey('subscription', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['subscription']);
        $this->assertArrayHasKey('transaction', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['transaction']);
        $this->assertArrayHasKey('amount', $stats['global']);
        $this->assertArrayHasKey('callback', $stats['global']);
        $this->assertInternalType('integer', $stats['global']['callback']);

        $this->assertArrayHasKey('id', $stats);
        $this->assertArrayHasKey('value', $stats);

        $this->assertArrayHasKey('type', $stats);
        $this->assertInternalType('array', $stats['type']);
        $this->assertArrayHasKey('id', $stats['type']);
        $this->assertArrayHasKey('key', $stats['type']);
        $this->assertArrayHasKey('label', $stats['type']);
        $this->assertEquals('Gratuit', $stats['type']['label']);

        $this->assertArrayHasKey('thematic', $stats);
        $this->assertInternalType('array', $stats['thematic']);
        $this->assertArrayHasKey('id', $stats['thematic']);
        $this->assertArrayHasKey('key', $stats['thematic']);
        $this->assertArrayHasKey('label', $stats['thematic']);

        $this->assertArrayHasKey('country', $stats);
        $this->assertInternalType('array', $stats['country']);
        $this->assertArrayHasKey('id', $stats['country']);
        $this->assertArrayHasKey('key', $stats['country']);
        $this->assertArrayHasKey('label', $stats['country']);

        $this->assertArrayHasKey('market', $stats);
        $this->assertInternalType('array', $stats['market']);
        $this->assertArrayHasKey('id', $stats['market']);
        $this->assertArrayHasKey('key', $stats['market']);
        $this->assertArrayHasKey('label', $stats['market']);

        $this->assertArrayHasKey('dates', $stats);
        $this->assertInternalType('array', $stats['dates']);
        $this->assertGreaterThan(0, count($stats['dates']));
        foreach ($stats['dates'] as $date => $date_data) {
            $this->assertInternalType('array', $date_data);
            $this->assertArrayHasKey('total', $date_data);
            $this->assertInternalType('integer', $date_data['total']);
            $this->assertArrayHasKey('treated', $date_data);
            $this->assertInternalType('integer', $date_data['treated']);
            $this->assertArrayHasKey('subscription', $date_data);
            $this->assertInternalType('integer', $date_data['subscription']);
            $this->assertArrayHasKey('transaction', $date_data);
            $this->assertInternalType('integer', $date_data['transaction']);
            $this->assertArrayHasKey('amount', $date_data);
            $this->assertArrayHasKey('callback', $date_data);
            $this->assertInternalType('integer', $date_data['callback']);
        }
    }
}
