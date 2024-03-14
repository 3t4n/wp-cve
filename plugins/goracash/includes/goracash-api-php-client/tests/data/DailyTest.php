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
use Goracash\Service\DataDaily as Daily;

class DailyTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $Client;

    /**
     * @var Daily
     */
    public $Service;

    public function setUp()
    {
        $configPath = dirname(__FILE__) . '/../testdata/test.ini';
        $this->Client = new Client($configPath);
        $this->Client->authenticate();
        $this->Service = new Daily($this->Client);
    }

    public function testGetSigns()
    {
        $signs = $this->Service->getAvailableSigns();
        $this->assertInternalType('array', $signs);
        $this->assertGreaterThan(0, count($signs));
        foreach ($signs as $sign) {
            $this->assertArrayHasKey('id', $sign);
            $this->assertArrayHasKey('key', $sign);
            $this->assertArrayHasKey('label', $sign);
        }
    }

    public function testGetLangs()
    {
        $langs = $this->Service->getAvailableLangs();
        $this->assertInternalType('array', $langs);
        $this->assertGreaterThan(0, count($langs));
        foreach ($langs as $lang) {
            $this->assertArrayHasKey('id', $lang);
            $this->assertArrayHasKey('key', $lang);
            $this->assertArrayHasKey('label', $lang);
        }
    }

    public function testGetHoroscopes()
    {
        $date = date('Y-m-d 00:00:00');
        $horoscopes = $this->Service->getHoroscopes();
        $this->assertInternalType('array', $horoscopes);
        $this->assertGreaterThan(0, count($horoscopes));
        foreach ($horoscopes as $horoscope) {
            $this->assertArrayHasKey('title', $horoscope);
            $this->assertArrayHasKey('sign', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['sign']);
            $this->assertArrayHasKey('label', $horoscope['sign']);
            $this->assertArrayHasKey('key', $horoscope['sign']);
            $this->assertArrayHasKey('content', $horoscope);
            $this->assertArrayHasKey('lang', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['lang']);
            $this->assertArrayHasKey('label', $horoscope['lang']);
            $this->assertEquals('fr_FR', $horoscope['lang']['label']);
            $this->assertArrayHasKey('key', $horoscope['lang']);
            $this->assertArrayHasKey('date', $horoscope);
            $this->assertTrue(Utils::isSystemDate($horoscope['date']));
            $this->assertEquals($date, $horoscope['date']);
        }
    }

    /**
     * @expectedException Exception
     */
    public function testGetHoroscopesParamsArraySingle()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'sign' => 'ARIES',
            'signs' => array(
                'CANCER'
            ),
            'lang' => 'ES_ES',
            'langs' => array(
                'FR_FR',
            ),
            'date' => strtotime($date.'-1 day'),
            'dates' => array(
                $date
            ),
        );
        $horoscopes = $this->Service->getHoroscopes($params);
    }

    public function testGetHoroscopesParamsSingle()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'sign' => 'CANCER',
            'lang' => 'FR_FR',
            'date' => $date,
        );
        $horoscopes = $this->Service->getHoroscopes($params);
        $this->assertInternalType('array', $horoscopes);
        $this->assertGreaterThan(0, count($horoscopes));
        foreach ($horoscopes as $horoscope) {
            $this->assertArrayHasKey('title', $horoscope);
            $this->assertArrayHasKey('sign', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['sign']);
            $this->assertArrayHasKey('label', $horoscope['sign']);
            $this->assertArrayHasKey('key', $horoscope['sign']);
            $this->assertEquals('CANCER', $horoscope['sign']['key']);
            $this->assertArrayHasKey('content', $horoscope);
            $this->assertArrayHasKey('lang', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['lang']);
            $this->assertArrayHasKey('label', $horoscope['lang']);
            $this->assertEquals('fr_FR', $horoscope['lang']['label']);
            $this->assertArrayHasKey('key', $horoscope['lang']);
            $this->assertArrayHasKey('date', $horoscope);
            $this->assertTrue(Utils::isSystemDate($horoscope['date']));
            $this->assertEquals($date, $horoscope['date']);
        }
    }

    public function testGetHoroscopesParamsArray()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'signs' => array(
                'CANCER',
                'ARIES',
            ),
            'langs' => array(
                'FR_FR',
                'ES_ES',
            ),
            'dates' => array(
                $date,
                date($date, strtotime('-1 day')),
            ),
        );
        $horoscopes = $this->Service->getHoroscopes($params);
        $this->assertInternalType('array', $horoscopes);
        $this->assertGreaterThan(0, count($horoscopes));
        foreach ($horoscopes as $horoscope) {
            $this->assertArrayHasKey('title', $horoscope);
            $this->assertArrayHasKey('sign', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['sign']);
            $this->assertArrayHasKey('label', $horoscope['sign']);
            $this->assertArrayHasKey('key', $horoscope['sign']);
            $this->assertContains($horoscope['sign']['key'], $params['signs']);
            $this->assertArrayHasKey('content', $horoscope);
            $this->assertArrayHasKey('lang', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['lang']);
            $this->assertArrayHasKey('label', $horoscope['lang']);
            $this->assertContains($horoscope['lang']['key'], $params['langs']);
            $this->assertArrayHasKey('key', $horoscope['lang']);
            $this->assertArrayHasKey('date', $horoscope);
            $this->assertTrue(Utils::isSystemDate($horoscope['date']));
            $this->assertContains($horoscope['date'], $params['dates']);
        }
    }

    public function testGetLoveHoroscopes()
    {
        $date = date('Y-m-d 00:00:00');
        $horoscopes = $this->Service->getLoveHoroscopes();
        $this->assertInternalType('array', $horoscopes);
        $this->assertGreaterThan(0, count($horoscopes));
        foreach ($horoscopes as $horoscope) {
            $this->assertArrayHasKey('title', $horoscope);
            $this->assertArrayHasKey('sign', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['sign']);
            $this->assertArrayHasKey('label', $horoscope['sign']);
            $this->assertArrayHasKey('key', $horoscope['sign']);
            $this->assertArrayHasKey('content', $horoscope);
            $this->assertArrayHasKey('lang', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['lang']);
            $this->assertArrayHasKey('label', $horoscope['lang']);
            $this->assertEquals('fr_FR', $horoscope['lang']['label']);
            $this->assertArrayHasKey('key', $horoscope['lang']);
            $this->assertArrayHasKey('date', $horoscope);
            $this->assertTrue(Utils::isSystemDate($horoscope['date']));
            $this->assertEquals($date, $horoscope['date']);
        }
    }

    public function testGetLoveHoroscopesParamsArray()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'signs' => array(
                'ARIES'
            ),
            'langs' => array(
                'ES_ES',
            ),
            'dates' => array(
                $date
            ),
        );
        $horoscopes = $this->Service->getLoveHoroscopes($params);
        $this->assertInternalType('array', $horoscopes);
        $this->assertGreaterThan(0, count($horoscopes));
        foreach ($horoscopes as $horoscope) {
            $this->assertArrayHasKey('title', $horoscope);
            $this->assertArrayHasKey('sign', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['sign']);
            $this->assertArrayHasKey('label', $horoscope['sign']);
            $this->assertArrayHasKey('key', $horoscope['sign']);
            $this->assertEquals('ARIES', $horoscope['sign']['key']);
            $this->assertArrayHasKey('content', $horoscope);
            $this->assertArrayHasKey('lang', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['lang']);
            $this->assertArrayHasKey('label', $horoscope['lang']);
            $this->assertEquals('es_ES', $horoscope['lang']['label']);
            $this->assertArrayHasKey('key', $horoscope['lang']);
            $this->assertArrayHasKey('date', $horoscope);
            $this->assertTrue(Utils::isSystemDate($horoscope['date']));
            $this->assertEquals($date, $horoscope['date']);
        }
    }

    public function testGetLoveHoroscopesParamsSingle()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'sign' => 'ARIES',
            'lang' => 'ES_ES',
            'date' => $date,
        );
        $horoscopes = $this->Service->getLoveHoroscopes($params);
        $this->assertInternalType('array', $horoscopes);
        $this->assertGreaterThan(0, count($horoscopes));
        foreach ($horoscopes as $horoscope) {
            $this->assertArrayHasKey('title', $horoscope);
            $this->assertArrayHasKey('sign', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['sign']);
            $this->assertArrayHasKey('label', $horoscope['sign']);
            $this->assertArrayHasKey('key', $horoscope['sign']);
            $this->assertEquals('ARIES', $horoscope['sign']['key']);
            $this->assertArrayHasKey('content', $horoscope);
            $this->assertArrayHasKey('lang', $horoscope);
            $this->assertArrayHasKey('id', $horoscope['lang']);
            $this->assertArrayHasKey('label', $horoscope['lang']);
            $this->assertEquals('es_ES', $horoscope['lang']['label']);
            $this->assertArrayHasKey('key', $horoscope['lang']);
            $this->assertArrayHasKey('date', $horoscope);
            $this->assertTrue(Utils::isSystemDate($horoscope['date']));
            $this->assertEquals($date, $horoscope['date']);
        }
    }

    public function testGetLoveTips()
    {
        $date = date('Y-m-d 00:00:00');
        $tips = $this->Service->getLoveTips();
        $this->assertInternalType('array', $tips);
        $this->assertGreaterThan(0, count($tips));
        foreach ($tips as $tip) {
            $this->assertArrayHasKey('title', $tip);
            $this->assertArrayHasKey('content', $tip);
            $this->assertArrayHasKey('lang', $tip);
            $this->assertArrayHasKey('id', $tip['lang']);
            $this->assertArrayHasKey('label', $tip['lang']);
            $this->assertEquals('fr_FR', $tip['lang']['label']);
            $this->assertArrayHasKey('key', $tip['lang']);
            $this->assertArrayHasKey('date', $tip);
            $this->assertTrue(Utils::isSystemDate($tip['date']));
            $this->assertEquals($date, $tip['date']);
        }
    }

    public function testGetLoveTipsParams()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'langs' => array(
                'FR_FR',
            ),
            'dates' => array(
                $date,
                date($date, strtotime('+1 day'))
            ),
        );
        $contents = $this->Service->getLoveTips($params);
        $this->assertInternalType('array', $contents);
        $this->assertGreaterThan(0, count($contents));
        foreach ($contents as $content) {
            $this->assertArrayHasKey('title', $content);
            $this->assertArrayHasKey('content', $content);
            $this->assertArrayHasKey('lang', $content);
            $this->assertArrayHasKey('id', $content['lang']);
            $this->assertArrayHasKey('label', $content['lang']);
            $this->assertEquals('fr_FR', $content['lang']['label']);
            $this->assertArrayHasKey('key', $content['lang']);
            $this->assertArrayHasKey('date', $content);
            $this->assertTrue(Utils::isSystemDate($content['date']));
            $this->assertContains($content['date'], $params['dates']);
        }
    }

    public function testGetSurnamesParams()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'langs' => array(
                'FR_FR',
            ),
            'dates' => array(
                $date,
                date($date, strtotime('+1 day'))
            ),
        );
        $contents = $this->Service->getSurnames($params);
        $this->assertInternalType('array', $contents);
        $this->assertGreaterThan(0, count($contents));
        foreach ($contents as $content) {
            $this->assertArrayHasKey('title', $content);
            $this->assertArrayHasKey('content', $content);
            $this->assertArrayHasKey('lang', $content);
            $this->assertArrayHasKey('id', $content['lang']);
            $this->assertArrayHasKey('label', $content['lang']);
            $this->assertEquals('fr_FR', $content['lang']['label']);
            $this->assertArrayHasKey('key', $content['lang']);
            $this->assertArrayHasKey('date', $content);
            $this->assertTrue(Utils::isSystemDate($content['date']));
            $this->assertContains($content['date'], $params['dates']);
        }
    }

    public function testGetDreamsParams()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'langs' => array(
                'FR_FR',
            ),
            'dates' => array(
                $date,
                date($date, strtotime('+1 day'))
            ),
        );
        $contents = $this->Service->getDreams($params);
        $this->assertInternalType('array', $contents);
        $this->assertGreaterThan(0, count($contents));
        foreach ($contents as $content) {
            $this->assertArrayHasKey('title', $content);
            $this->assertArrayHasKey('content', $content);
            $this->assertArrayHasKey('lang', $content);
            $this->assertArrayHasKey('id', $content['lang']);
            $this->assertArrayHasKey('label', $content['lang']);
            $this->assertEquals('fr_FR', $content['lang']['label']);
            $this->assertArrayHasKey('key', $content['lang']);
            $this->assertArrayHasKey('date', $content);
            $this->assertTrue(Utils::isSystemDate($content['date']));
            $this->assertContains($content['date'], $params['dates']);
        }
    }

    public function testGetRitualsParams()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'langs' => array(
                'FR_FR',
            ),
            'dates' => array(
                $date,
                date($date, strtotime('+1 day'))
            ),
        );
        $contents = $this->Service->getRituals($params);
        $this->assertInternalType('array', $contents);
        $this->assertGreaterThan(0, count($contents));
        foreach ($contents as $content) {
            $this->assertArrayHasKey('title', $content);
            $this->assertArrayHasKey('content', $content);
            $this->assertArrayHasKey('lang', $content);
            $this->assertArrayHasKey('id', $content['lang']);
            $this->assertArrayHasKey('label', $content['lang']);
            $this->assertEquals('fr_FR', $content['lang']['label']);
            $this->assertArrayHasKey('key', $content['lang']);
            $this->assertArrayHasKey('date', $content);
            $this->assertTrue(Utils::isSystemDate($content['date']));
            $this->assertContains($content['date'], $params['dates']);
        }
    }

    public function testGetTestimonialsParams()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'langs' => array(
                'FR_FR',
            ),
            'dates' => array(
                $date,
                date($date, strtotime('+1 day'))
            ),
        );
        $contents = $this->Service->getTestimonials($params);
        $this->assertInternalType('array', $contents);
        $this->assertGreaterThan(0, count($contents));
        foreach ($contents as $content) {
            $this->assertArrayHasKey('title', $content);
            $this->assertArrayHasKey('content', $content);
            $this->assertArrayHasKey('lang', $content);
            $this->assertArrayHasKey('id', $content['lang']);
            $this->assertArrayHasKey('label', $content['lang']);
            $this->assertEquals('fr_FR', $content['lang']['label']);
            $this->assertArrayHasKey('key', $content['lang']);
            $this->assertArrayHasKey('date', $content);
            $this->assertTrue(Utils::isSystemDate($content['date']));
            $this->assertContains($content['date'], $params['dates']);
        }
    }

    public function testGetSpotlightsParams()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'langs' => array(
                'FR_FR',
            ),
            'dates' => array(
                $date,
                date($date, strtotime('+1 day'))
            ),
        );
        $contents = $this->Service->getSpotlights($params);
        $this->assertInternalType('array', $contents);
        $this->assertGreaterThan(0, count($contents));
        foreach ($contents as $content) {
            $this->assertArrayHasKey('title', $content);
            $this->assertArrayHasKey('content', $content);
            $this->assertArrayHasKey('lang', $content);
            $this->assertArrayHasKey('id', $content['lang']);
            $this->assertArrayHasKey('label', $content['lang']);
            $this->assertEquals('fr_FR', $content['lang']['label']);
            $this->assertArrayHasKey('key', $content['lang']);
            $this->assertArrayHasKey('date', $content);
            $this->assertTrue(Utils::isSystemDate($content['date']));
            $this->assertContains($content['date'], $params['dates']);
        }
    }

    public function testGetSeeingsParams()
    {
        $date = date('Y-m-d 00:00:00');
        $params = array(
            'langs' => array(
                'FR_FR',
            ),
            'dates' => array(
                $date,
                date($date, strtotime('+1 day'))
            ),
        );
        $contents = $this->Service->getSeeings($params);
        $this->assertInternalType('array', $contents);
        $this->assertGreaterThan(0, count($contents));
        foreach ($contents as $content) {
            $this->assertArrayHasKey('title', $content);
            $this->assertArrayHasKey('content', $content);
            $this->assertArrayHasKey('lang', $content);
            $this->assertArrayHasKey('id', $content['lang']);
            $this->assertArrayHasKey('label', $content['lang']);
            $this->assertEquals('fr_FR', $content['lang']['label']);
            $this->assertArrayHasKey('key', $content['lang']);
            $this->assertArrayHasKey('date', $content);
            $this->assertTrue(Utils::isSystemDate($content['date']));
            $this->assertContains($content['date'], $params['dates']);
        }
    }
}