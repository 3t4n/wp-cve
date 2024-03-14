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
use Goracash\Service\LeadRefractiveSurgery as LeadRefractiveSurgery;
use Goracash\Utils as Utils;

class LeadRefractiveSurgeryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $Client;

    /**
     * @var LeadRefractiveSurgery
     */
    public $Service;

    public function setUp()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $this->Client = new Client($configPath);
        $this->Client->authenticate();
        $this->Service = new LeadRefractiveSurgery($this->Client);
    }

    public function testGetTypes()
    {
        $enums = $this->Service->getAvailableTypes();
        $this->assertInternalType('array', $enums);
        $this->assertGreaterThan(0, count($enums));
        foreach ($enums as $enum) {
            $this->assertArrayHasKey('id', $enum);
            $this->assertArrayHasKey('key', $enum);
            $this->assertArrayHasKey('label', $enum);
        }
    }

    /**
     * @expectedException Exception
     */
    public function testGetLeadsInvalidDateLbound()
    {
        $this->Service->getLeads('invalid date', '2013-12-25 00:00:00');
    }

    /**
     * @expectedException Exception
     */
    public function testGetLeadsInvalidDateUbound()
    {
        $this->Service->getLeads('2013-12-20 00:00:00', 'invalid date');
    }

    public function testGetLeads()
    {
        $leads = $this->Service->getLeads('2016-06-14 00:00:00', '2016-06-15 00:00:00');
        $this->assertInternalType('array', $leads);
        $this->assertGreaterThan(0, count($leads));
        foreach ($leads as $lead) {
            $this->assertInternalType('array', $lead);
            $this->assertArrayHasKey('id', $lead);
            $this->assertArrayHasKey('status', $lead);
            $this->assertArrayHasKey('status_date', $lead);
            $this->assertArrayHasKey('date', $lead);
            $this->assertArrayHasKey('type', $lead);
            $this->assertInternalType('array', $lead['type']);
            $this->assertArrayHasKey('id', $lead['type']);
            $this->assertArrayHasKey('key', $lead['type']);
            $this->assertArrayHasKey('label', $lead['type']);
            $this->assertArrayHasKey('payout', $lead);
            $this->assertArrayHasKey('payout_date', $lead);
            $this->assertArrayHasKey('trackers', $lead);
        }
    }

    /**
     * @expectedException Exception
     */
    public function testGetLeadInvalidId()
    {
        $this->Service->getLead('invalid params');
    }

    /**
     * @expectedException Exception
     */
    public function testGetLeadUnauthorizedId()
    {
        $this->Service->getLead(1000);
    }

    public function testGetLead()
    {
        $lead = $this->Service->getLead(16785750);
        $this->assertInternalType('array', $lead);
        $this->assertArrayHasKey('id', $lead);
        $this->assertArrayHasKey('status', $lead);
        $this->assertArrayHasKey('status_date', $lead);
        $this->assertArrayHasKey('date', $lead);
        $this->assertArrayHasKey('type', $lead);
        $this->assertInternalType('array', $lead['type']);
        $this->assertArrayHasKey('id', $lead['type']);
        $this->assertArrayHasKey('key', $lead['type']);
        $this->assertArrayHasKey('label', $lead['type']);
        $this->assertArrayHasKey('payout', $lead);
        $this->assertArrayHasKey('payout_date', $lead);
        $this->assertArrayHasKey('trackers', $lead);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidEmail()
    {
        $data = array(
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'invalid email',
            'phone' => '0612345678',
            'type' => 'MYOPIA',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
            'birth_date' => '1991-08-28',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidZipcode()
    {
        $data = array(
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'type' => 'MYOPIA',
            'birth_date' => '1991-08-28',
            'tracker' => 'monTracker',
            'zipcode' => 'invalid zipcode',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidBirthDate()
    {
        $data = array(
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'type' => 'MYOPIA',
            'birth_date' => 'invalid birthdate format',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidType()
    {
        $data = array(
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'birth_date' => '1991-08-28',
            'type' => 'invalid subject',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    public function testPushLead()
    {
        $data = array(
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'type' => 'MYOPIA',
            'birth_date' => '1991-08-28',
            'tracker' => 'monTracker2',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $date_lbound = Utils::now();
        $result = $this->Service->pushLead($data);
        $this->assertInternalType('integer', $result);
        $this->assertGreaterThan(0, (int)$result);

        $lead = $this->Service->getLead($result);
        $this->assertInternalType('array', $lead);
        $this->assertEquals('Myopie', $lead['type']['label']);

        $this->assertInternalType('array', $lead['trackers']);
        $this->assertGreaterThan(0, count($lead['trackers']));
        $this->assertArrayHasKey('id', $lead['trackers'][0]);
        $this->assertGreaterThan(0, $lead['trackers'][0]['id']);
        $this->assertArrayHasKey('title', $lead['trackers'][0]);
        $this->assertEquals('monTracker2', $lead['trackers'][0]['title']);
        $this->assertArrayHasKey('slug', $lead['trackers'][0]);
        $this->assertEquals('montracker2', $lead['trackers'][0]['slug']);
        $this->assertEquals('En attente', $lead['status']);

        $date_ubound = Utils::now();
        $this->assertGreaterThanOrEqual($date_lbound, $lead['date']);
        $this->assertLessThanOrEqual($date_ubound, $lead['date']);
    }
}
