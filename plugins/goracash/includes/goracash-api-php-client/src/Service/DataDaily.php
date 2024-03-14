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

class DataDaily extends Data
{
    public $serviceName = 'DataDaily';

    public $servicePath = '/v1/data/daily/';

    const MIN_LIMIT_PERIOD = '-1 day 00:00';
    const MAX_LIMIT_PERIOD = '+1 day 00:00';

    /**
     * @return array
     */
    public function getAvailableSigns()
    {
        $response = $this->execute('/signs');
        $data = $this->normalize($response);
        return $data['signs'];
    }

    /**
     * @return array
     */
    public function getAvailableLangs()
    {
        $response = $this->execute('/langs');
        $data = $this->normalize($response);
        return $data['langs'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getHoroscopes(array $params = array())
    {
        $this->normalizeHoroscopeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/horoscope', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getLoveHoroscopes(array $params = array())
    {
        $this->normalizeHoroscopeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/loveHoroscope', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getLoveTips(array $params = array())
    {
        $this->normalizeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/loveTip', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getSurnames(array $params = array())
    {
        $this->normalizeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/surname', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getDreams(array $params = array())
    {
        $this->normalizeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/dream', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getRituals(array $params = array())
    {
        $this->normalizeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/ritual', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getTestimonials(array $params = array())
    {
        $this->normalizeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/testimonial', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getSpotlights(array $params = array())
    {
        $this->normalizeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/spotlight', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * Get seeing of the day
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getSeeings(array $params = array())
    {
        $this->normalizeParams($params);
        $this->checkParams($params);
        $response = $this->execute('/seeing', $params);
        $data = $this->normalize($response);
        return $data['contents'];
    }

    /**
     * @param  array $params
     * @throws Exception
     */
    public function checkParams(array &$params)
    {
        $this->checkDates($params);
    }

    /**
     * @param  array $params
     * @throws InvalidArgumentException
     */
    public function checkDates(array &$params)
    {
        if (!empty($params['dates'])) {
            foreach ($params['dates'] as $date) {
                if (!$this->utils->isSystemDate($date)) {
                    throw new InvalidArgumentException('Invalid params: Only system date has available YYYY-MM-DDD');
                }
                $epoch = strtotime($date);
                if ($epoch < strtotime(static::MIN_LIMIT_PERIOD) || $epoch > strtotime(static::MAX_LIMIT_PERIOD)) {
                    throw new InvalidArgumentException('Invalid date params');
                }
            }
        }
    }

    /**
     * @param  array $params
     * @return array
     * @throws Exception
     */
    public function normalizeHoroscopeParams(array &$params)
    {
        $availableParams = array(
            'date' => '',
            'dates' => array(),
            'sign' => '',
            'signs' => array(),
            'lang' => '',
            'langs' => array(),
        );
        $params = array_merge($availableParams, $params);
        $params = array_intersect_key($params, $availableParams);

        $this->normalizeArray($params, (array)$params['dates'], 'dates');
        $this->normalizeArray($params, (array)$params['signs'], 'signs');
        $this->normalizeArray($params, (array)$params['langs'], 'langs');
        return $params;
    }

    /**
     * @param  array $params
     * @return array
     */
    public function normalizeParams(array &$params)
    {
        $availableParams = array(
            'date' => '',
            'dates' => array(),
            'lang' => '',
            'langs' => array(),
        );
        $params = array_merge($availableParams, $params);
        $params = array_intersect_key($params, $availableParams);

        $this->normalizeArray($params, (array)$params['dates'], 'dates');
        $this->normalizeArray($params, (array)$params['langs'], 'langs');
        return $params;
    }
}
