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

class SubscriptionAcademic extends Subscription
{

    public $serviceName = 'subscriptionAcademic';

    public $servicePath = '/v1/subscription/academic/';

    /**
     * @return array
     */
    public function getAvailableChildLevels()
    {
        $response = $this->execute('/childLevels');
        $data = $this->normalize($response);
        return $data['child_levels'];
    }

    /**
     * @return array
     */
    public function getAvailableOffers()
    {
        $response = $this->execute('/offers');
        $data = $this->normalize($response);
        return $data['offers'];
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
     * @param array $fields
     * @return array
     */
    public function pushSubscription(array $fields)
    {
        $this->normalizeFormFields($fields);
        $this->checkFormFields($fields);
        $response = $this->execute('/create', $fields, 'POST');
        $data = $this->normalize($response);
        return array(
            'id' => $data['id'],
            'status' => $data['subscription_status'],
            'redirect_url' => $data['redirect_url'],
        );
    }

    public function pushLeadAndRedirect(array $fields)
    {
        $result = $this->pushSubscription($fields);
        if ($result['status'] != 'ok') {
            throw new Exception('Subscription #' . $result['id'] . ' on status ' . $result['status']);
        }
        $this->redirectTo($result['redirect_url']);
    }

    /**
     * @param array $fields
     * @return array
     */
    public function normalizeFormFields(array &$fields)
    {
        $availableFields = array(
            'gender' => '',
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'phone' => '',
            'children' => array(),
            'offer' => '',
            'tracker' => '',
        );
        $availableChildFields = array(
            'firstname' => '',
            'level' => '',
        );
        $fields = array_merge($availableFields, $fields);
        $fields = array_intersect_key($fields, $availableFields);
        foreach ($fields['children'] as $i => $childFields) {
            $childFields = array_merge($availableChildFields, $childFields);
            $fields['children'][$i] = array_intersect_key($childFields, $availableChildFields);
        }
        $this->normalizeArray($fields, $fields['children'], 'children');
        return $fields;
    }

    public function checkFormFields(array &$fields)
    {
        $requiredFields = array('gender', 'firstname', 'lastname', 'email', 'phone', 'offer');
        foreach ($requiredFields as $requiredField) {
            if ($this->utils->isEmpty($fields[$requiredField])) {
                throw new InvalidArgumentException('Empty field ' . $requiredField);
            }
        }
        if (!$this->utils->isEmail($fields['email'])) {
            throw new InvalidArgumentException('Invalid email');
        }
        $exist = false;
        for ($i = 0; $i < 5; $i++) {
            $firstnameField = sprintf('children[%s][firstname]', $i);
            $levelField = sprintf('children[%s][level]', $i);
            if (!array_key_exists($firstnameField, $fields)) {
                break;
            }
            $exist = true;
            if ($this->utils->isEmpty($fields[$firstnameField])) {
                throw new InvalidArgumentException('Empty child #' . $i . ' field firstname');
            }
            if (!$fields[$levelField]) {
                throw new InvalidArgumentException('Empty child #' . $i . ' field level');
            }
        }
        if (!$exist) {
            throw new InvalidArgumentException('Empty field children');
        }
    }

}