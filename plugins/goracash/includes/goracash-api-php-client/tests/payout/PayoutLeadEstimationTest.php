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
use Goracash\Utils as Utils;
use Goracash\Service\PayoutLead as PayoutLead;

class PayoutLeadEstimationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $Client;

    /**
     * @var PayoutLead
     */
    public $Service;

    public function setUp()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $this->Client = new Client($configPath);
        $this->Client->authenticate();
        $this->Service = new PayoutLead($this->Client);
    }

    public function testGetTypes()
    {
        $types = $this->Service->getAvailableEstimationTypes();
        $this->assertInternalType('array', $types);
        $this->assertGreaterThan(0, count($types));
        foreach ($types as $type) {
            $this->assertArrayHasKey('id', $type);
            $this->assertInternalType('integer', $type['id']);
            $this->assertGreaterThan(0, $type['id']);
            $this->assertArrayHasKey('label', $type);
            $this->assertInternalType('string', $type['label']);
            $this->assertNotNull($type['label']);
            $this->assertArrayHasKey('key', $type);
            $this->assertInternalType('string', $type['key']);
            $this->assertNotNull($type['key']);
        }
    }

    public function testGetForEstimations()
    {
        $date = '2015-11-03 00:00:00';
        $types = array(
            'types' => array(
                'MASONRY',
                'BOILER_MAINTENANCE',
            ),
        );
        $payouts = $this->Service->getForEstimations($date, $types);
        $this->assertInternalType('array', $payouts);
        $this->assertCount(2, $payouts);
        foreach ($payouts as $payout) {
            $this->assertArrayHasKey('id', $payout);
            $this->assertInternalType('integer', $payout['id']);
            $this->assertGreaterThan(0, $payout['id']);
            $this->assertArrayHasKey('amount', $payout);
            $this->assertInternalType('integer', $payout['amount']);
            $this->assertArrayHasKey('type', $payout);
            $this->assertInternalType('array', $payout['type']);
            $this->assertArrayHasKey('id', $payout['type']);
            $this->assertInternalType('integer', $payout['type']['id']);
            $this->assertGreaterThan(0, $payout['type']['id']);
            $this->assertArrayHasKey('label', $payout['type']);
            $this->assertInternalType('string', $payout['type']['label']);
            $this->assertNotNull($payout['type']['label']);
            $this->assertArrayHasKey('key', $payout['type']);
            $this->assertInternalType('string', $payout['type']['key']);
            $this->assertNotNull($payout['type']['key']);
            $this->assertArrayHasKey('start_date', $payout);
            $this->assertArrayHasKey('end_date', $payout);
            $this->assertTrue(Utils::isSystemDate($payout['start_date']));
            $this->assertTrue((Utils::isSystemDate($payout['end_date']) || is_null($payout['end_date'])));
        }
    }

    public function testGetForEstimationsByType()
    {
        $date = '2015-11-03 00:00:00';
        $params = array(
            'type' => 'MASONRY',
        );
        $payouts = $this->Service->getForEstimations($date, $params);
        $this->assertInternalType('array', $payouts);
        $this->assertCount(1, $payouts);
        foreach ($payouts as $payout) {
            $this->assertArrayHasKey('id', $payout);
            $this->assertInternalType('integer', $payout['id']);
            $this->assertGreaterThan(0, $payout['id']);
            $this->assertArrayHasKey('amount', $payout);
            $this->assertInternalType('integer', $payout['amount']);
            $this->assertArrayHasKey('type', $payout);
            $this->assertInternalType('array', $payout['type']);
            $this->assertArrayHasKey('id', $payout['type']);
            $this->assertInternalType('integer', $payout['type']['id']);
            $this->assertGreaterThan(0, $payout['type']['id']);
            $this->assertArrayHasKey('label', $payout['type']);
            $this->assertInternalType('string', $payout['type']['label']);
            $this->assertNotNull($payout['type']['label']);
            $this->assertArrayHasKey('key', $payout['type']);
            $this->assertInternalType('string', $payout['type']['key']);
            $this->assertNotNull($payout['type']['key']);
            $this->assertArrayHasKey('start_date', $payout);
            $this->assertArrayHasKey('end_date', $payout);
            $this->assertTrue(Utils::isSystemDate($payout['start_date']));
            $this->assertTrue((Utils::isSystemDate($payout['end_date']) || is_null($payout['end_date'])));
        }
    }

    public function testGetForEstimationsByDate()
    {
        $date = '2015-11-03 00:00:00';
        $payouts = $this->Service->getForEstimations($date);
        $this->assertInternalType('array', $payouts);
        $this->assertGreaterThan(0, count($payouts));
        foreach ($payouts as $payout) {
            $this->assertArrayHasKey('id', $payout);
            $this->assertInternalType('integer', $payout['id']);
            $this->assertGreaterThan(0, $payout['id']);
            $this->assertArrayHasKey('amount', $payout);
            $this->assertInternalType('integer', $payout['amount']);
            $this->assertArrayHasKey('type', $payout);
            $this->assertInternalType('array', $payout['type']);
            $this->assertArrayHasKey('id', $payout['type']);
            $this->assertInternalType('integer', $payout['type']['id']);
            $this->assertGreaterThan(0, $payout['type']['id']);
            $this->assertArrayHasKey('label', $payout['type']);
            $this->assertInternalType('string', $payout['type']['label']);
            $this->assertNotNull($payout['type']['label']);
            $this->assertArrayHasKey('key', $payout['type']);
            $this->assertInternalType('string', $payout['type']['key']);
            $this->assertNotNull($payout['type']['key']);
            $this->assertArrayHasKey('start_date', $payout);
            $this->assertArrayHasKey('end_date', $payout);
            $this->assertTrue(Utils::isSystemDate($payout['start_date']));
            $this->assertTrue((Utils::isSystemDate($payout['end_date']) || is_null($payout['end_date'])));
        }
    }

    /**
     * @expectedException Exception
     */
    public function testGetForEstimationsInvalidDate()
    {
        $date = 'Invalid date param';
        $this->Service->getForEstimations($date);
    }
}