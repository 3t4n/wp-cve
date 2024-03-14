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

class PayoutLead extends Payout
{

    public $serviceName = 'PayoutLead';

    public $servicePath = '/v1/payout/lead/';

    /**
     * @return array
     */
    public function getAvailableEstimationTypes()
    {
        $response = $this->execute('/estimationTypes');
        $data = $this->normalize($response);
        return $data['types'];
    }

    /**
     * @param null $date
     * @param array $types
     * @return mixed
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function getForEstimations($date = null, array $types = array())
    {
        if (is_null($date)) {
            $date = $this->utils->now();
        }
        if (!$this->utils->isSystemDatetime($date)) {
            throw new InvalidArgumentException('Invalid params: Only system date has available YYYY-MM-DDD HH:II:SS');
        }
        $this->normalizeParams($types);
        $response = $this->execute('/estimation', $types);
        $data = $this->normalize($response);
        return $data['payouts'];
    }

    /**
     * @param array $params
     * @return array
     */
    public function normalizeParams(array &$params)
    {
        $availableParams = array(
            'type' => '',
            'types' => array(),
        );
        $params = array_merge($availableParams, $params);
        $params = array_intersect_key($params, $availableParams);

        $this->normalizeArray($params, (array)$params['types'], 'types');
        return $params;
    }
}
