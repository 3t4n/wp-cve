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
use Goracash\Service\LeadHealthPro as LeadHealthPro;
use Goracash\Utils as Utils;

class LeadHealthProTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $Client;

    /**
     * @var LeadHealthPro
     */
    public $Service;

    public function setUp()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $this->Client = new Client($configPath);
        $this->Client->authenticate();
        $this->Service = new LeadHealthPro($this->Client);
    }

    public function testGetTitles()
    {
        $enums = $this->Service->getAvailableTitles();
        $this->assertInternalType('array', $enums);
        $this->assertGreaterThan(0, count($enums));
        foreach ($enums as $enum) {
            $this->assertArrayHasKey('id', $enum);
            $this->assertArrayHasKey('key', $enum);
            $this->assertArrayHasKey('label', $enum);
        }
    }

    public function testGetProfessions()
    {
        $enums = $this->Service->getAvailableProfessions();
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
        $leads = $this->Service->getLeads('2016-04-15 00:00:00', '2016-04-15 12:00:00');
        $this->assertInternalType('array', $leads);
        $this->assertGreaterThan(0, count($leads));
        foreach ($leads as $lead) {
            $this->assertInternalType('array', $lead);
            $this->assertArrayHasKey('id', $lead);
            $this->assertArrayHasKey('status', $lead);
            $this->assertArrayHasKey('status_date', $lead);
            $this->assertArrayHasKey('date', $lead);
            $this->assertArrayHasKey('profession', $lead);
            $this->assertInternalType('array', $lead['profession']);
            $this->assertArrayHasKey('id', $lead['profession']);
            $this->assertArrayHasKey('key', $lead['profession']);
            $this->assertArrayHasKey('label', $lead['profession']);
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
        $lead = $this->Service->getLead(16784138);
        $this->assertInternalType('array', $lead);
        $this->assertArrayHasKey('id', $lead);
        $this->assertArrayHasKey('status', $lead);
        $this->assertArrayHasKey('status_date', $lead);
        $this->assertArrayHasKey('date', $lead);
        $this->assertArrayHasKey('profession', $lead);
        $this->assertInternalType('array', $lead['profession']);
        $this->assertArrayHasKey('id', $lead['profession']);
        $this->assertArrayHasKey('key', $lead['profession']);
        $this->assertArrayHasKey('label', $lead['profession']);
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
            'title' => 'DOCTOR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'invalid email',
            'phone' => '0612345678',
            'profession' => 'NATUROPATH',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidZipcode()
    {
        $data = array(
            'title' => 'DOCTOR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'profession' => 'NATUROPATH',
            'tracker' => 'monTracker',
            'zipcode' => 'invalid zipcode',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidTitle()
    {
        $data = array(
            'title' => 'invalid title',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'profession' => 'NATUROPATH',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidProfession()
    {
        $data = array(
            'title' => 'DOCTOR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'profession' => 'invalid profession',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    public function testPushLead()
    {
        $data = array(
            'title' => 'DOCTOR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'profession' => 'NATUROPATH',
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
        $this->assertEquals('Naturopathe', $lead['profession']['label']);

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
