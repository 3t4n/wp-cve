<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Conditions;

use FPFramework\Base\Assignments;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Migrator
{
    /**
     * Migrate old Assignments data to the new Condition Builder object. 
     * 
     * @since   1.1.0
     * 
     * @param   object  $box
     * 
     * @return  void
     */
    public static function run(&$params)
    {
        if ($params->get('mirror_box') === '1')
        {
            $params->set('display_conditions_type', 'mirror_box');
            return;
        }

        $assignmentsClass = new Assignments();

        $matching_method_map = [
            'and' => 'all',
            'or'  => 'any'
        ];

        $rules = [
            0 => [
                'matching_method' => $matching_method_map[$params->get('assignmentMatchingMethod', 'and')],
                'enabled' => 1,
                'rules' => []
            ]
        ];

        $assignments = $params->get('assignments', []);

        foreach ($assignments as $paramKey => &$paramValue)
        {
            if (strpos($paramKey, 'assign_') !== 0)
            {
                continue;
            }

            $tempParamValue = isset($paramValue->selection) ? $paramValue->selection : false;

            if (!$tempParamValue)
            {
                continue;
            }
            
            $oldName = str_replace('assign_', '', $paramKey);
            $newName = $assignmentsClass->aliasToClassname($oldName);

            // Skip unknown conditions
            if (!$newName)
            {
                continue;
            }

            // Skip disabled conditions
            if ($tempParamValue == '0')
            {
                continue;
            }

            // Date assignment doesn't use the value property
            if ($newName == 'Date\Date')
            {
                $paramValue->list = true;

                $publish_up   = isset($paramValue->param_publish_up) ? $paramValue->param_publish_up : '';
                $publish_down = isset($paramValue->param_publish_down) ? $paramValue->param_publish_down : '';

                \FPFramework\Base\Functions::fixDateOffset($publish_up);
                \FPFramework\Base\Functions::fixDateOffset($publish_down);

                $paramValue->param_publish_up = $publish_up;
                $paramValue->param_publish_down = $publish_down;
            }

            // Date assignment doesn't use the value property
            if ($newName == 'Date\Time')
            {
                $paramValue->list = true;
            }

            // Skip conditions with no value
            $value = isset($paramValue->list) ? $paramValue->list : false;
            if (!$value)
            {
                continue;
            }

            $operator = $tempParamValue == '1' ? 'includes' : 'not_includes';

            // These Conditions have custom operators
            if (in_array($newName, ['Date\Date', 'Date\Time']))
            {
                $operator = $tempParamValue == '1' ? 'equal' : 'not_equal';
            }

            if ($newName == 'Cookie')
            {
                $operatorMap = [
                    'exists'   => 'exists',
                    'not_exists'  => 'not_exists',
                    'equal'    => 'equal',
                    'not_equal' => 'not_equal',
                    'contains' => 'includes',
                    'not_contains' => 'not_includes',
                    'starts'   => 'starts_with',
                    'not_start'   => 'not_starts_with',
                    'ends'     => 'ends_with',
                    'not_end'   => 'not_ends_with',
                ];

                if ($tempParamValue == '2')
                {
                    switch ($value)
                    {
                        case 'exists':
                            $value = 'not_exists';
                            break;

                        case 'equal':
                            $value = 'not_equal';
                            break;

                        case 'contains':
                            $value = 'not_contains';
                            break;

                        case 'starts':
                            $value = 'not_start';
                            break;

                        case 'ends':
                            $value = 'not_end';
                            break;
                    }
                }

                $operator = $operatorMap[$value];

                $paramValue->param_operator = $operator;

                $value = $paramValue->param_name;
            }

            if ($newName == 'Pageviews')
            {
                $operatorMap = [
                    'exactly' => 'equal',
                    'not_equal' => 'not_equal',
                    'fewer'   => 'less_than',
                    'greater' => 'greater_than',
                ];

                if ($paramValue == '2')
                {
                    switch ($value)
                    {
                        case 'exactly':
                            $value = 'not_equal';
                            break;

                        case 'fewer':
                            $value = 'greater';
                            break;

                        case 'greater':
                            $value = 'fewer';
                            break;
                    }
                }

                $operator = $operatorMap[$value];
                $value = $paramValue->param_views;
            }

            // These were textareas, convert to array
            if (in_array($newName, ['Referrer', 'IP', 'Geo\City', 'URL']))
            {
                // Break new lines into an array
                $value = $value ? array_filter(preg_split('/\r\n|[\r\n]/', $value)) : [];
            }

            // Also add to the referrers list any pre-defined selected referrers
            if ($newName === 'Referrer')
            {
                if (!is_array($value))
                {
                    $value = [];
                }
                
                // Also add any selected referrers from the pre-defined list
                if (isset($paramValue->param_predefined_list))
                {
                    $value = array_filter(array_merge($value, $paramValue->param_predefined_list));
                }
            }

            // Convert Repeater fields into required value format
            if (in_array($newName, ['Referrer', 'IP', 'Geo\City', 'URL']))
            {
                foreach ($value as $key => &$_value)
                {
                    $valueTmp = [];
                    $valueTmp['value'] = trim($_value, ',');

                    $_value = $valueTmp;
                }
            }

            if ($newName === 'WP\UserID')
            {
                $value = array_filter(array_map('trim', explode(',', $value)));
            }

            if ($newName == 'TimeOnSite')
            {
                $operator = 'greater_than_or_equal_to';
            }
            
            // Rule item value
            $data = [
                'name'     => $newName,
                'enabled'  => 1,
                'operator' => $operator,
                'value'    => $value
            ];

            // Find params
            foreach ($paramValue as $assignParamKey => $assignParamValue)
            {
                if (strpos($assignParamKey, 'param_') !== 0)
                {
                    continue;
                }

                if ($assignParamValue == '')
                {
                    continue;
                }

                $realParamName = str_replace('param_', '', $assignParamKey);

                $data['params'][$realParamName] = $assignParamValue;
            }

            $rules[0]['rules'][] = $data;
        }

        if (!empty($rules[0]['rules']))
        {
            // Finally, set the rules
            $params->set('display_conditions_type', 'custom');
            $params->set('rules', $rules);
        }
    }
}