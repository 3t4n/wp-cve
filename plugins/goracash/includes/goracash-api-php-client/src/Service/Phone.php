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

namespace Goracash\Service;

use Goracash\Service as Service;

class Phone extends Service
{
    public $serviceName = 'Phone';

    public $servicePath = '/v1/phone/';

    const LIMIT_PERIOD = '1 month';

    /**
     * @return array
     */
    public function getAvailableCountries()
    {
        $response = $this->execute('/countries');
        $data = $this->normalize($response);
        return $data['countries'];
    }

    /**
     * @return array
     */
    public function getAvailableTypes()
    {
        $response = $this->execute('/types');
        $data = $this->normalize($response);
        return $data['types'];
    }

    /**
     * @return array
     */
    public function getAvailableThematics()
    {
        $response = $this->execute('/thematics');
        $data = $this->normalize($response);
        return $data['thematics'];
    }

    /**
     * @return array
     */
    public function getAvailableMarkets()
    {
        $response = $this->execute('/markets');
        $data = $this->normalize($response);
        return $data['markets'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getAttachedNumbers(array $params = array())
    {
        $this->normalizeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/numbers', $params);
        $data = $this->normalize($response);
        return $data['numbers'];
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     * @throws Exception
     */
    public function getPhonesAudiotelStats($startDate, $endDate)
    {
        return $this->getAudiotelStats(
            '/audiotelStats',
            $startDate,
            $endDate
        );
    }

    /**
     * @param $phone : Id or number in international format
     * @param $startDate
     * @param $endDate
     * @return array
     * @throws Exception
     */
    public function getPhoneAudiotelStats($phone, $startDate, $endDate)
    {
        return $this->getAudiotelStats(
            '/' . $phone . '/audiotelStats',
            $startDate,
            $endDate
        );
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     * @throws Exception
     */
    public function getPhonesCBStats($startDate, $endDate)
    {
        $this->checkPeriod($startDate, $endDate);

        $params['date_lbound'] = $startDate;
        $params['date_ubound'] = $endDate;

        $response = $this->execute('/cbStats', $params);
        $data = $this->normalize($response);
        return $data['stats'];
    }

    /**
     * @param $phone : Id or number in international format
     * @param $startDate
     * @param $endDate
     * @return array
     * @throws Exception
     */
    public function getPhoneCBStats($phone, $startDate, $endDate)
    {
        $this->checkPeriod($startDate, $endDate);

        $params['date_lbound'] = $startDate;
        $params['date_ubound'] = $endDate;

        $response = $this->execute('/' . $phone . '/cbStats', $params);
        $data = $this->normalize($response);
        return $data['stats'];
    }


    /**
     * @param $caller string: Caller number in International format
     * @param $number string: Called number in International format
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function pushCallback($caller, $number, array $params = array())
    {
        $params['caller'] = $caller;
        $params['phone'] = $number;
        $this->normalizeCallbackParams($params);
        $this->checkCallbackParams($params);
        $response = $this->execute('/callback', $params);
        $data = $this->normalize($response);
        return $data['callback_status'];
    }

    /**
     * @param array $params
     * @return array
     */
    public function normalizeCallbackParams(array &$params)
    {
        $availableParams = array(
            'caller' => '',
            'phone' => '',
            'gender' => '',
            'firstname' => '',
            'lastname' => '',
            'tracker' => '',
        );
        $params = array_merge($availableParams, $params);
        $params = array_intersect_key($params, $availableParams);
        return $params;
    }

    /**
     * @param array $params
     * @throws InvalidArgumentException
     */
    public function checkCallbackParams(array &$params)
    {
        if (!$this->utils->isInternationalNumber($params['caller'])) {
            throw new InvalidArgumentException('Invalid params: Caller is not in internation format (ex: 0033175757575)');
        }
        if (!$this->utils->isInternationalNumber($params['phone'])) {
            throw new InvalidArgumentException('Invalid params: Phone is not in internation format (ex: 0033175757575)');
        }
    }

    /**
     * @param $startDate
     * @param $endDate
     * @throws InvalidArgumentException
     */
    public function checkPeriod($startDate, $endDate)
    {
        $isValidStartDate = $this->utils->isSystemDatetime($startDate);
        $isValidEndDate = $this->utils->isSystemDatetime($endDate);
        if (!$isValidEndDate || !$isValidStartDate) {
            throw new InvalidArgumentException('Invalid params: Only system date has available YYYY-MM-DDD HH:II:SS');
        }

        if ($startDate > $endDate) {
            throw new InvalidArgumentException('Invalid params: start_date > end_date');
        }

        $isOutOfLimit = $this->utils->isOutOfLimit($startDate, $endDate, Phone::LIMIT_PERIOD);
        if ($isOutOfLimit) {
            throw new InvalidArgumentException('Invalid params: Period is too large. Available only ' . Phone::LIMIT_PERIOD);
        }
    }

    /**
     * @param array $params
     * @throws InvalidArgumentException
     */
    public function checkParams(array &$params)
    {
        if (!$this->utils->isEmpty($params['date']) && !$this->utils->isSystemDate($params['date'])) {
            throw new InvalidArgumentException('Invalid params: Only system date has available YYYY-MM-DDD');
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function normalizeParams(array &$params)
    {
        $availableParams = array(
            'date' => '',
            'market' => 0,
            'markets' => array(),
            'type' => '',
            'types' => array(),
            'thematic' => '',
            'thematics' => array(),
            'country' => '',
            'countries' => array(),
        );
        $params = array_merge($availableParams, $params);
        $params = array_intersect_key($params, $availableParams);

        $this->normalizeArray($params, (array)$params['markets'], 'markets');
        $this->normalizeArray($params, (array)$params['types'], 'types');
        $this->normalizeArray($params, (array)$params['thematics'], 'thematics');
        $this->normalizeArray($params, (array)$params['countries'], 'countries');

        return $params;
    }
    
    /**
     * @param $path
     * @param $startDate
     * @param $endDate
     * @return mixed
     * @throws Exception
     * @throws InvalidArgumentException
     */
    protected function getAudiotelStats($path, $startDate, $endDate)
    {
        $this->checkPeriod($startDate, $endDate);

        $params = array();
        $params['date_lbound'] = $startDate;
        $params['date_ubound'] = $endDate;

        $response = $this->execute($path, $params);
        $data = $this->normalize($response);
        return $data['stats'];

    }

}