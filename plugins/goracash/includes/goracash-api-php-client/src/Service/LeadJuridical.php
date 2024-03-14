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

class LeadJuridical extends Lead
{
    public $serviceName = 'leadJuridical';

    public $servicePath = '/v1/lead/juridical/';

    /**
     * @var array
     */
    protected $requiredFields = array(
        'gender',
        'firstname',
        'lastname',
        'email',
        'phone',
        'description',
        'type',
        'subtype',
        'available_period',
        'contact_type',
        'delivery',
        'zipcode',
        'city'
    );

    /**
     * @var array
     */
    protected $availableParams = array();

    /**
     * @var array
     */
    protected $availableFields = array(
        'gender' => '',
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'phone' => '',
        'type' => '',
        'subtype' => '',
        'available_period' => '',
        'contact_type' => '',
        'is_legal_aid' => '',
        'delivery' => '',
        'description' => '',
        'zipcode' => '',
        'city' => '',
        'tracker' => '',
    );

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
    public function getAvailableGenders()
    {
        $response = $this->execute('/genders');
        $data = $this->normalize($response);
        return $data['genders'];
    }

    /**
     * @return array
     */
    public function getAvailableDeliveries()
    {
        $response = $this->execute('/deliveries');
        $data = $this->normalize($response);
        return $data['deliveries'];
    }

    /**
     * @return array
     */
    public function getAvailableContactTypes()
    {
        $response = $this->execute('/contactTypes');
        $data = $this->normalize($response);
        return $data['contact_types'];
    }

    /**
     * @return array
     */
    public function getAvailablePeriods()
    {
        $response = $this->execute('/availablePeriods');
        $data = $this->normalize($response);
        return $data['available_periods'];
    }

}
