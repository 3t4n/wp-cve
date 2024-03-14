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

abstract class Lead extends Service
{
    protected $availableParams = array();

    protected $availableFields = array();

    const LIMIT_PERIOD = '1 week';
    
    const LIMIT = 50;

    /**
     * Get leads from specific period
     * @param $startDate
     * @param $endDate
     * @param $params array
     * @return array
     * @throws InvalidArgumentException
     */
    public function getLeads($startDate, $endDate, array $params = array())
    {
        $isValidStartDate = $this->utils->isSystemDatetime($startDate);
        $isValidEndDate = $this->utils->isSystemDatetime($endDate);
        if (!$isValidEndDate || !$isValidStartDate) {
            throw new InvalidArgumentException('Invalid params: Only system date has available YYYY-MM-DDD HH:II:SS');
        }

        if ($startDate > $endDate) {
            throw new InvalidArgumentException('Invalid params: start_date > end_date');
        }

        $isOutOfLimit = $this->utils->isOutOfLimit($startDate, $endDate, LeadAcademic::LIMIT_PERIOD);
        if ($isOutOfLimit) {
            throw new InvalidArgumentException('Invalid params: Period is too large. Available only ' . LeadAcademic::LIMIT_PERIOD);
        }

        $params['date_lbound'] = $startDate;
        $params['date_ubound'] = $endDate;

        $this->normalizeParams($params);
        $response = $this->execute('/', $params);
        $data = $this->normalize($response);
        return $data['leads'];
    }

    /**
     * @param array $fields
     * @throws InvalidArgumentException
     */
    public function checkFormFields(array &$fields)
    {
        $this->checkRequiredFields($fields);
        if (!$this->utils->isEmail($fields['email'])) {
            throw new InvalidArgumentException('Invalid email');
        }
        if (!$this->utils->isZipcode($fields['zipcode'])) {
            throw new InvalidArgumentException('Invalid zipcode');
        }
    }

    /**
     * @param $leadId
     * @return array
     * @throws InvalidArgumentException
     */
    public function getLead($leadId)
    {
        if (!is_numeric($leadId)) {
            throw new InvalidArgumentException('Invalid params: Id of lead is numeric');
        }

        $response = $this->execute('/' . $leadId . '/');
        $data = $this->normalize($response);
        return $data['lead'];
    }

    /**
     * @param array $fields
     * @return integer
     */
    public function pushLead(array $fields)
    {
        $this->normalizeFormFields($fields);
        $this->checkFormFields($fields);
        $response = $this->execute('/create', $fields, 'POST');
        $data = $this->normalize($response);
        return $data['id'];
    }
    
    /**
     * @param array $fields
     * @return array
     */
    public function normalizeFormFields(array &$fields)
    {
        $fields = array_merge($this->availableFields, $fields);
        $fields = array_intersect_key($fields, $this->availableFields);
        return $fields;
    }

    /**
     * @param array $params
     * @return array
     * @throws InvalidArgumentException
     */
    protected function normalizeParams(array &$params)
    {
        $availableParams = array_merge(
            array(
                'date_lbound' => '',
                'date_ubound' => '',
                'status' => '',
                'tracker' => 0,
                'trackers' => array(),
                'limit' => static::LIMIT,
                'offset' => 0,
            ),
            $this->availableParams
        );
        $params = array_merge($availableParams, $params);
        $params = array_intersect_key($params, $availableParams);

        $this->normalizeArray($params, (array)$params['trackers'], 'trackers');

        if ($params['limit'] > static::LIMIT) {
            throw new InvalidArgumentException('Invalid params: Limit is too large. Available only < ' . static::LIMIT);
        }
        return $params;
    }

}