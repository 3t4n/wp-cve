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

class LeadHealthPro extends Lead
{
    public $serviceName = 'leadHealthPro';

    public $servicePath = '/v1/lead/health_pro/';

    /**
     * @var array
     */
    protected $requiredFields = array(
        'title',
        'firstname',
        'lastname',
        'email',
        'phone',
        'profession',
        'zipcode',
        'city'
    );

    /**
     * @var array
     */
    protected $availableFields = array(
        'title' => '',
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'phone' => '',
        'profession' => '',
        'tracker' => '',
        'zipcode' => '',
        'city' => '',
    );

    /**
     * @var array
     */
    protected $availableParams = array(
        'profession' => '',
        'professions' => array(),
    );

    /**
     * @return array
     */
    public function getAvailableTitles()
    {
        $response = $this->execute('/titles');
        $data = $this->normalize($response);
        return $data['titles'];
    }

    /**
     * @return array
     */
    public function getAvailableProfessions()
    {
        $response = $this->execute('/professions');
        $data = $this->normalize($response);
        return $data['professions'];
    }

    /**
     * @param array $params
     * @return array
     * @throws InvalidArgumentException
     */
    public function normalizeParams(array &$params)
    {
        $params = parent::normalizeParams($params);
        $this->normalizeArray($params, (array)$params['professions'], 'professions');
        return $params;
    }

}
