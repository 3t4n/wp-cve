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
use Goracash\Service\LeadAcademic as LeadAcademic;
use Goracash\Utils as Utils;

class LeadAcademicTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $Client;

    /**
     * @var LeadAcademic
     */
    public $Service;

    public function setUp()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $this->Client = new Client($configPath);
        $this->Client->authenticate();
        $this->Service = new LeadAcademic($this->Client);
    }

    public function testGetLevels()
    {
        $levels = $this->Service->getAvailableLevels();
        $this->assertInternalType('array', $levels);
        $this->assertGreaterThan(0, count($levels));
        foreach ($levels as $level) {
            $this->assertArrayHasKey('id', $level);
            $this->assertArrayHasKey('key', $level);
            $this->assertArrayHasKey('label', $level);
        }
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

    public function testGetSubjects()
    {
        $subjects = $this->Service->getAvailableSubjects();
        $this->assertInternalType('array', $subjects);
        $this->assertGreaterThan(0, count($subjects));
        foreach ($subjects as $subject) {
            $this->assertArrayHasKey('id', $subject);
            $this->assertArrayHasKey('key', $subject);
            $this->assertArrayHasKey('label', $subject);
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
        $leads = $this->Service->getLeads('2013-12-20 00:00:00', '2013-12-25 00:00:00');
        $this->assertInternalType('array', $leads);
        $this->assertGreaterThan(0, count($leads));
        foreach ($leads as $lead) {
            $this->assertInternalType('array', $lead);
            $this->assertArrayHasKey('id', $lead);
            $this->assertArrayHasKey('status', $lead);
            $this->assertArrayHasKey('status_date', $lead);
            $this->assertArrayHasKey('date', $lead);
            $this->assertArrayHasKey('level', $lead);
            $this->assertInternalType('array', $lead['level']);
            $this->assertArrayHasKey('id', $lead['level']);
            $this->assertArrayHasKey('key', $lead['level']);
            $this->assertArrayHasKey('label', $lead['level']);
            $this->assertArrayHasKey('subject', $lead);
            $this->assertInternalType('array', $lead['subject']);
            $this->assertArrayHasKey('id', $lead['subject']);
            $this->assertArrayHasKey('key', $lead['subject']);
            $this->assertArrayHasKey('label', $lead['subject']);
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
        $lead = $this->Service->getLead(1574660);
        $this->assertInternalType('array', $lead);
        $this->assertArrayHasKey('id', $lead);
        $this->assertArrayHasKey('status', $lead);
        $this->assertArrayHasKey('status_date', $lead);
        $this->assertArrayHasKey('date', $lead);
        $this->assertArrayHasKey('level', $lead);
        $this->assertInternalType('array', $lead['level']);
        $this->assertArrayHasKey('id', $lead['level']);
        $this->assertArrayHasKey('key', $lead['level']);
        $this->assertArrayHasKey('label', $lead['level']);
        $this->assertArrayHasKey('subject', $lead);
        $this->assertInternalType('array', $lead['subject']);
        $this->assertArrayHasKey('id', $lead['subject']);
        $this->assertArrayHasKey('key', $lead['subject']);
        $this->assertArrayHasKey('label', $lead['subject']);
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
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'invalid email',
            'phone' => '0612345678',
            'child_name' => 'Julie',
            'subject' => 'MATHEMATICS',
            'level' => '1ST_ES',
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
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'child_name' => 'Julie',
            'subject' => 'MATHEMATICS',
            'level' => '1ST_ES',
            'tracker' => 'monTracker',
            'zipcode' => 'invalid zipcode',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidGender()
    {
        $data = array(
            'gender' => 'invalid gender',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'child_name' => 'Julie',
            'subject' => 'MATHEMATICS',
            'level' => '1ST_ES',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidSubject()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'child_name' => 'Julie',
            'subject' => 'invalid subject',
            'level' => '1ST_ES',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    /**
     * @expectedException Exception
     */
    public function testPushLead_InvalidLevel()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'child_name' => 'Julie',
            'subject' => 'MATHEMATICS',
            'level' => 'invalid level',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $this->Service->pushLead($data);
    }

    public function testPushLead()
    {
        $data = array(
            'gender' => 'MONSIEUR',
            'firstname' => 'David',
            'lastname' => 'P.',
            'email' => 'test@test.fr',
            'phone' => '0612345678',
            'child_name' => 'Julie',
            'subject' => 'MATHEMATICS',
            'level' => '1ST_ES',
            'tracker' => 'monTracker',
            'zipcode' => '75006',
            'city' => 'Paris',
        );
        $date_lbound = Utils::now();
        $result = $this->Service->pushLead($data);
        $this->assertInternalType('integer', $result);
        $this->assertGreaterThan(0, (int)$result);

        $lead = $this->Service->getLead($result);
        $this->assertInternalType('array', $lead);
        $this->assertEquals('1ere ES', $lead['level']['label']);
        $this->assertEquals('Mathématiques', $lead['subject']['label']);

        $this->assertInternalType('array', $lead['trackers']);
        $this->assertGreaterThan(0, count($lead['trackers']));
        $this->assertArrayHasKey('id', $lead['trackers'][0]);
        $this->assertGreaterThan(0, $lead['trackers'][0]['id']);
        $this->assertArrayHasKey('title', $lead['trackers'][0]);
        $this->assertEquals('monTracker', $lead['trackers'][0]['title']);
        $this->assertArrayHasKey('slug', $lead['trackers'][0]);
        $this->assertEquals('montracker', $lead['trackers'][0]['slug']);
        $this->assertEquals('En attente', $lead['status']);

        $date_ubound = Utils::now();
        $this->assertGreaterThanOrEqual($date_lbound, $lead['date']);
        $this->assertLessThanOrEqual($date_ubound, $lead['date']);
    }
}