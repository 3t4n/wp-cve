<?php

use WunderAuto\Types\Internal\FieldDescriptor;

/**
 * @return FieldDescriptor[]
 */
function getParameterFields()
{
    $parameterFields = [
        new FieldDescriptor(
            [
                'label'       => 'parameters[editor.phpClass].customFieldNameCaption',
                'description' => 'parameters[editor.phpClass].customFieldNameDesc',
                'type'        => 'text',
                'model'       => 'fieldName',
                'variable'    => 'field',
                'condition'   => "parameters[editor.phpClass].usesFieldName",
                'dynamic'     => true,
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => 'parameters[editor.phpClass].customFieldNameCaption',
                'description' => 'parameters[editor.phpClass].customFieldNameDesc',
                'type'        => 'text',
                'model'       => 'optionName',
                'variable'    => 'name',
                'condition'   => "parameters[editor.phpClass].usesName",
                'dynamic'     => true,
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('Advanced Custom Field', 'wunderauto'),
                'description' => __('Advanced Custom Field', 'wunderauto'),
                'type'        => 'dynamic-select-grouped',
                'options'     => '[groupKey, group] in Object.entries($root.shared.acfFields)',
                'model'       => 'acfFieldKey',
                'variable'    => 'key',
                'condition'   => "parameters[editor.phpClass].usesAcfFieldName",
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('Object path', 'wunderauto'),
                'description' => __(
                    'Optional. If the returned field is an array or object, use JSONPath notation to select the ' .
                    'element or subfield',
                    'wunderauto'
                ),
                'type'        => 'text',
                'model'       => 'objectPath',
                'variable'    => 'path',
                'condition'   => "parameters[editor.phpClass].usesObjectPath",
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('Return field as', 'wunderauto'),
                'description' => __(
                    'By default, parameters will return its internal value. Some parameters can also have a label, ' .
                    'or "human readable", representation. I.e the post status "publish" is almost always written ' .
                    'using the label "Published".',
                    'wunderauto'
                ),
                'type'        => 'select',
                'options'     => [
                    (object)['value' => '', 'label' => __('Value (default)', 'wunderauto')],
                    (object)['value' => 'label', 'label' => __('Label', 'wunderauto')],
                ],
                'model'       => 'returnAs',
                'variable'    => 'return',
                'condition'   => "parameters[editor.phpClass].usesReturnAs",
                'prio'        => 50,
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('Treat field as', 'wunderauto'),
                'description' => __(
                    'Enable formatting rules for some value types',
                    'wunderauto'
                ),
                'type'        => 'select',
                'options'     => [
                    (object)['value' => '', 'label' => __('Text (default)', 'wunderauto')],
                    (object)['value' => 'date', 'label' => __('Date', 'wunderauto')],
                    (object)['value' => 'phone', 'label' => __('Phone', 'wunderauto')],
                ],
                'model'       => 'treatAs',
                'variable'    => 'type',
                'condition'   => "parameters[editor.phpClass].usesTreatAsType",
                'prio'        => 50,
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('Date format', 'wunderauto'),
                'description' => __(
                    'Formats the date using PHP date() function (I.e Y-m-d H:i:s). If left blank defaults to using ' .
                    'WunderAutomation standard date time format',
                    'wunderauto'
                ),
                'type'        => 'text',
                'model'       => 'dateFormat',
                'variable'    => 'format',
                'condition'   => "parameters[editor.phpClass].usesDateFormat || editor.values.treatAs == 'date'",
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('Add or subtract', 'wunderauto'),
                'description' => __(
                    'Add or subtract time from the returned date. Uses PHP strtotime() modifiers.',
                    'wunderauto'
                ),
                'type'        => 'text',
                'model'       => 'dateAdd',
                'variable'    => 'add',
                'condition'   => "parameters[editor.phpClass].usesDateFormat || editor.values.treatAs == 'date'",
                'prio'        => 20,
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('Phone format', 'wunderauto'),
                'description' => __(
                    'Optionally formats the phone number in E.164 format for sending. SMS etc. If no ' .
                    'country code is typed in by customer, E.164 formatting will use ' .
                    'country code from customer billing country or WooCommerce shop address',
                    'wunderauto'
                ),
                'type'        => 'select',
                'options'     => [
                    (object)['value' => '', 'label' => __('No formatting', 'wunderauto')],
                    (object)['value' => 'e.164', 'label' => __('E.164 (API usage)', 'wunderauto')],
                ],
                'model'       => 'phoneFormat',
                'variable'    => 'format',
                'condition'   => "parameters[editor.phpClass].usesPhoneFormat || editor.values.treatAs == 'phone'",
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('Output format', 'wunderauto'),
                'description' => '',
                'type'        => 'dynamic-select',
                'options'     => '[key, value] in Object.entries(parameters[editor.phpClass].outputFormats)',
                'model'       => 'outputFormat',
                'variable'    => 'format',
                'condition'   => "parameters[editor.phpClass].usesOutputFormat",
            ]
        ),

        /**
         *  Default value
         */
        new FieldDescriptor(
            [
                'label'       => __('Default value', 'wunderauto'),
                'description' => __('Replacement value if the parameter doesn\'t exist or is empty', 'wunderauto'),
                'type'        => 'text',
                'model'       => 'defaultValue',
                'variable'    => 'default',
                'condition'   => "parameters[editor.phpClass].usesDefault",
                'prio'        => 998,
            ]
        ),

        new FieldDescriptor(
            [
                'label'       => __('URL Encode', 'wunderauto'),
                'description' => __(
                    'URL Encode the return value, sometimes needed for some API usage',
                    'wunderauto'
                ),
                'type'        => 'checkbox',
                'model'       => 'urlEncode',
                'variable'    => 'urlenc',
                'condition'   => "parameters[editor.phpClass].usesUrlEncode",
                'prio'        => 999,
            ]
        ),

        /**
         *  Last standard field
         */
        new FieldDescriptor(
            [
                'label'       => __('Escape new lines', 'wunderauto'),
                'description' => __('Escape new lines with \\\\n in the return value', 'wunderauto'),
                'type'        => 'checkbox',
                'model'       => 'escNL',
                'variable'    => 'escnl',
                'condition'   => "parameters[editor.phpClass].usesEscapeNewLines",
                'prio'        => 999,
            ]
        ),
    ];

    $parameterFields = apply_filters('wunderauto/parameters/editorfields', $parameterFields);
    usort($parameterFields, function ($a, $b) {
        return $a->getPrio() > $b->getPrio() ? 1 : -1;
    });

    return $parameterFields;
}
