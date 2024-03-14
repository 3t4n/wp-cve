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

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Conditions\ConditionsHelper;
use FPFramework\Base\Extension;
use FPFramework\Base\FieldsParser;

class ConditionBuilder
{
    public static function pass($rules, $factory = null)
    {
        $rules = self::prepareRules($rules);

        if (empty($rules))
        {
            return true;
        }

        return ConditionsHelper::getInstance($factory)->passSets($rules);
    }

    /**
     * Prepare rules object to run checks
     *
     * @return void
     */
    public static function prepareRules($rules = [])
    {
        if (!$rules || !is_array($rules))
        {
            return [];
        }
        
        $rules_ = [];

        foreach ($rules as $key => $group)
        {
            if (isset($group['enabled']) AND !(bool) $group['enabled'])
            {
                continue;
            }

            // A group without rules, doesn't make sense.
            if (!isset($group['rules']) OR (isset($group['rules']) AND empty($group['rules'])))
            {
                continue;
            }

            $validRules = [];

            foreach ($group['rules'] as $rule)
            {
                // Make sure rule has a name.
                if (!isset($rule['name']) OR (isset($rule['name']) AND empty($rule['name'])))
                {
                    continue;
                }

                // Rule is invalid if both value and params properties are empty
                if (!isset($rule['value']) && !isset($rule['params']))
                {
                    continue;
                }

                if (isset($rule['value']) && is_array($rule['value']) && empty($rule['value']))
                {
                    continue;
                }

                // Skip disabled rules
                if (isset($rule['enabled']) && !(bool) $rule['enabled'])
                {
                    continue;
                }

                // We don't need this property.
                unset($rule['enabled']);

                // Prepare rule value if necessary
                if (isset($rule['value']))
                {
                    $rule['value'] = self::prepareRepeaterValue($rule['value']);
                }

                // Verify operator
                if (!isset($rule['operator']) OR (isset($rule['operator']) && empty($rule['operator'])))
                {
                    $rule['operator'] = isset($rule['params']['operator']) ? $rule['params']['operator'] : '';
                }

                $validRules[] = $rule;
            }

            if (count($validRules) > 0)
            {
                $group['rules'] = $validRules;

                if (!isset($group['matching_method']) OR (isset($group['matching_method']) AND empty($group['matching_method'])))
                {
                    $group['matching_method'] = 'all';
                }

                unset($group['enabled']);
                $rules_[] = $group;
            }
        }

        return $rules_;
    }
    
    /**
     * Parse the value of the FPF Repeater Input field.
     * 
     * @param   array  $selection
     * 
     * @return  mixed
     */
    private static function prepareRepeaterValue($selection)
    {
        // Only proceed when we have an array of arrays selection.
        if (!is_array($selection))
        {
            return $selection;
        }

        $new_selection = [];

        foreach ($selection as $value)
        {
            /**
            * We expect a `value` key for Repeater fields or a key,value pair
            * for plain arrays.
            */
            if (!isset($value['value']))
            {
                $new_selection[] = $value;
                continue;
            }

            // value must not be empty
            if (empty(trim($value['value'])))
            {
                continue;
            }

            $new_selection[] = count($value) === 1 ? $value['value'] : $value;
        }

        return $new_selection;
    }

    /**
     * Prepares the given rules list.
     * 
     * @param   array  $list
     * 
     * @return  array
     */
    public static function prepareXmlRulesList($list)
    {
        if (is_array($list))
        {
            $list = implode(',', array_map('trim', $list));
        }
        else if (is_string($list))
        {
            $list = str_replace(' ', '', $list);
        }

        return $list;
    }

    /**
     * Adds a new condition item or group.
     * 
     * @param   string  $controlGroup       The name of the input used to store the data.
     * @param   string  $groupKey           The group index ID.
     * @param   string  $conditionKey       The added condition item index ID.
     * @param   array   $condition          The condition name we are adding.
     * @param   string  $include_rules      The list of included conditions that override the available conditions.
     * @param   string  $exclude_rules      The list of excluded conditions that override the available conditions.
     * @param   string  $exclude_rules_pro  Whether excluded rules should appear as "Pro" features.
     * @param   string  $plugin             The plugin we are currently displaying the Condition Builder.
     * 
     * @return  string
     */
    public static function add($controlGroup, $groupKey, $conditionKey, $condition = null, $include_rules = [], $exclude_rules = [], $exclude_rules_pro = false, $plugin = null)
    {
        $conditionName = isset($condition['name']) ? $condition['name'] : false;

        // Do not allow user to add a rule that's excluded
        if (in_array($conditionName, $exclude_rules))
        {
            return;
        }
        
        $controlGroup_ = $controlGroup . "[$groupKey][rules][$conditionKey]";

        $options = [
            'name'              => $controlGroup_,
            'enabled'           => !isset($condition['enabled']) ? true : (string) $condition['enabled'] == '1',
            'conditions'        => \FPFramework\Helpers\HTML::renderConditions(['name' => $controlGroup_ . '[name]', 'value' => $conditionName, 'include_rules' => $include_rules, 'exclude_rules' => $exclude_rules, 'exclude_rules_pro' => $exclude_rules_pro]),
            'groupKey'          => $groupKey,
            'conditionKey'      => $conditionKey,
            'options'           => ''
        ];

        if ($conditionName)
        {
            $optionsHTML = self::renderOptions($conditionName, $controlGroup_, $condition, $plugin);
            $options['condition_name'] = $conditionName;
            $options['options'] = $optionsHTML;
        }

        return fpframework()->renderer->fields->render('conditionbuilder/row', $options, true);
    }

    /**
     * Render condition item settings.
     * 
     * @param   string  $name          The name of the condition item.
     * @param   string  $controlGroup  The name of the input used to store the data.
     * @param   object  $formData      The data that will be bound to the form.
     * @param   string  $plugin_name   The plugin we are currently displaying the Condition Builder.
     * 
     * @return  string
     */
    public static function renderOptions($name, $controlGroup = null, $formData = null, $plugin_name = null)
    {
        $ds = DIRECTORY_SEPARATOR;

        // Path to condition file
        $path = implode($ds, [dirname(dirname(dirname(__FILE__))), 'Forms', 'Conditions', str_replace('\\', $ds, $name) . '.php']);

        // Ensure it exists
        if (!file_exists($path))
        {
            return;
        }

        // require file that contains condition fields
        $fields = require $path;

        // Set ruleName for ConditionRuleValueHint
        foreach ($fields as &$field)
        {
            if ($field['type'] === 'ConditionRuleValueHint')
            {
                $field['ruleName'] = $name;
                $field['label'] = '&nbsp;';
            }

            $field['class'][] = 'fpf-flex-row-fields';
            $field['description_class'] = ['bottom'];
            if ($field['type'] === 'Comparator')
            {
                if (isset($field['input_class']) && is_array($field['input_class']))
                {
                    $field['input_class'][] = 'fullwidth';
                }
                else
                {
                    $field['input_class'] = ['fullwidth'];
                }
            }
            $field['control_inner_class'] = [];
        }

        // Init the FieldsParser
        $fieldsParser = new FieldsParser([
			'fields_name_prefix' => $controlGroup,
            'bind_data' => $formData
		]);

        // Grab the HTML
        ob_start();
        $fieldsParser->renderFields($fields);
        $html = ob_get_contents();
        ob_end_clean();

        // Return it
        return $html;
    }

    /**
     * Handles loading condition builder given a payload.
     * 
     * @param   array   $payload
     * 
     * @return  string
     */
    public static function initLoad($payload = [])
    {
        if (!$payload)
        {
            return;
        }

        if (!isset($payload['data']) &&
            !isset($payload['name']))
        {
            return;
        }

        if (!$data = json_decode($payload['data']))
        {
            return;
        }

        // transform object to assosiative array
        $data = is_string($data) ? json_decode($data, true) : json_decode(wp_json_encode($data), true);

        if (!is_array($data))
        {
            return;
        }

        // html of condition builder
        $html = '';

        $plugin = isset($payload['plugin']) ? $payload['plugin'] : '';
        $include_rules = isset($payload['include_rules']) ? $payload['include_rules'] : [];
        $exclude_rules = isset($payload['exclude_rules']) ? $payload['exclude_rules'] : [];
        $exclude_rules_pro = isset($payload['exclude_rules_pro']) ? $payload['exclude_rules_pro'] : false;

        foreach ($data as $groupKey => $groupConditions)
        {
            $payload = [
                'name' => $payload['name'],
                'plugin' => $plugin,
                'groupKey' => $groupKey,
                'groupConditions' => $groupConditions,
                'include_rules' => $include_rules,
                'exclude_rules' => $exclude_rules,
                'exclude_rules_pro' => $exclude_rules_pro
            ];
            
            $html .= fpframework()->renderer->fields->render('conditionbuilder/group', $payload, true);
            
        }

        return $html;
    }
}