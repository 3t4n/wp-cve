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

class LeadAcademic extends Lead
{
    public $serviceName = 'leadAcademic';

    public $servicePath = '/v1/lead/academic/';

    /**
     * @var array
     */
    protected $availableParams = array(
        'level' => '',
        'levels' => array(),
        'subject' => '',
        'subjects' => array(),
    );

    /**
     * @var array
     */
    protected $availableFields = array(
        'gender' => '',
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'phone' => '',
        'child_name' => '',
        'subject' => '',
        'level' => '',
        'tracker' => '',
        'zipcode' => '',
        'city' => '',
    );
    
    /**
     * @var array
     */
    protected $requiredFields = array(
        'gender',
        'firstname',
        'lastname',
        'email',
        'phone',
        'child_name',
        'subject',
        'level',
        'zipcode',
        'city'
    );

    /**
     * @return array
     */
    public function getAvailableLevels()
    {
        $response = $this->execute('/levels');
        $data = $this->normalize($response);
        return $data['levels'];
    }

    /**
     * @return array
     */
    public function getAvailableSubjects()
    {
        $response = $this->execute('/subjects');
        $data = $this->normalize($response);
        return $data['subjects'];
    }

    /**
     * @return array
     */
    public function getAvailableGenders()
    {
        $response = $this->execute('/genders');
        $data = $this->normalize($response);
        return $data['genders'];
    }

    /**
     * @param array $params
     * @return array
     * @throws InvalidArgumentException
     */
    public function normalizeParams(array &$params)
    {
        $params = parent::normalizeParams($params);
        $this->normalizeArray($params, (array)$params['levels'], 'levels');
        $this->normalizeArray($params, (array)$params['subjects'], 'subjects');
        return $params;
    }

}
