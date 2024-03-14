<?php

namespace CalculatedFields;

/**
 * Class Fields
 * @package CalculatedFields
 */
class Fields
{

    /**
     * Current state of all fields
     *
     * @var array
     */
    private $fields;

    /**
     * Returns internal state
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Initialize internal state when being called from
     * ACF save_post handler.
     */
    public function init()
    {
        $this->fields = [];
        $postedFields = (object)[];
        if (isset($_REQUEST['acf']) && is_array($_REQUEST['acf'])) {
            foreach ($_REQUEST['acf'] as $key => $value) {
                $key = sanitize_key($key);
                $value = sanitize_text_field($value);
                $postedFields->$key = $value;
            }
        }

        foreach ($postedFields as $key => $postedField) {
            $fieldMeta = acf_get_field($key);

            if (!in_array($fieldMeta['type'], ['repeater', 'group'])) {
                $this->fields[] = (object)[
                    'name' => $fieldMeta['_name'],
                    'key' => $key,
                    'type' => $fieldMeta['type'],
                    'value' => $postedField,
                    'sortOrder' => $fieldMeta['menu_order'],
                    'formula' => isset($fieldMeta['formula']) ? trim($fieldMeta['formula']) : null,
                    'format' => isset($fieldMeta['calculated_format']) ? trim($fieldMeta['calculated_format']) : null,
                    'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                ];
            }

            if ($fieldMeta['type'] === 'group') {
                $values = [];
                foreach ($postedField as $subKey => $subField) {
                    $subFieldMeta = $this->findSubFieldMeta($fieldMeta, $subKey);
                    $values[] = (object)[
                        'name' => $subFieldMeta['_name'],
                        'subFieldName' => "{$fieldMeta['name']}_{$subFieldMeta['_name']}",
                        'key' => $subKey,
                        'type' => $subFieldMeta['type'],
                        'value' => $subField,
                        'sortOrder' => $subFieldMeta['menu_order'],
                        'formula' => isset($subFieldMeta['formula']) ? trim($subFieldMeta['formula']) : null,
                        'format' => isset($fieldMeta['calculated_format']) ?
                            trim($fieldMeta['calculated_format']) :
                            null,
                        'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                    ];
                }

                $this->fields[] = (object)[
                    'name' => $fieldMeta['_name'],
                    'key' => $key,
                    'type' => $fieldMeta['type'],
                    'value' => $values,
                    'sortOrder' => $fieldMeta['menu_order'],
                    'formula' => isset($fieldMeta['formula']) ? trim($fieldMeta['formula']) : null,
                    'format' => isset($fieldMeta['calculated_format']) ? trim($fieldMeta['calculated_format']) : null,
                    'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                ];
            }

            if ($fieldMeta['type'] === 'repeater') {
                $values = [];
                $rowIndex = 0;
                foreach ($postedField as $rowId => $row) {
                    $rowValues = [];
                    $order = 0;
                    foreach ($row as $subKey => $value) {
                        $subFieldMeta = $this->findSubFieldMeta($fieldMeta, $subKey);
                        if (!$subFieldMeta) {
                            continue;
                        }
                        $rowValues[] = (object)[
                            'name' => $subFieldMeta['_name'],
                            'subFieldName' => "{$fieldMeta['name']}_{$rowIndex}_{$subFieldMeta['_name']}",
                            'key' => $subKey,
                            'type' => $subFieldMeta['type'],
                            'value' => $row[$subKey],
                            'sortOrder' => $order++,
                            'formula' => isset($subFieldMeta['formula']) ? trim($subFieldMeta['formula']) : null,
                            'format' => isset($fieldMeta['calculated_format']) ?
                                trim($fieldMeta['calculated_format']) :
                                null,
                            'blankIfZero' => isset($fieldMeta['blank_if_zero']) ?
                                trim($fieldMeta['blank_if_zero']) :
                                null,
                        ];
                    }
                    $values[] = $rowValues;
                    $rowIndex++;
                }
                $this->fields[] = (object)[
                    'name' => $fieldMeta['_name'],
                    'key' => $key,
                    'type' => $fieldMeta['type'],
                    'value' => $values,
                    'sortOrder' => $fieldMeta['menu_order'],
                    'formula' => isset($fieldMeta['formula']) ? trim($fieldMeta['formula']) : null,
                    'format' => isset($fieldMeta['calculated_format']) ? trim($fieldMeta['calculated_format']) : null,
                    'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                ];
            }
        }
        $i = 0;
    }

    /**
     * Initialize interal state when being called from ajax
     */
    public function ajaxInit()
    {
        $this->fields = [];
        $postedFields = (object)[];
        $insecure = json_decode(stripslashes($_REQUEST['acf']));
        foreach ($insecure as $key => $value) {
            $key = sanitize_text_field($key);
            $value = (object)[
                'id' => isset($value->id) ? sanitize_key($value->id) : 0,
                'value' => isset($value->value) ? sanitize_text_field($value->value) : '',
            ];
            $postedFields->$key = $value;
        }

        foreach ($postedFields as $postedKey => $postedRow) {
            if (strlen(trim($postedKey)) === 0 || $postedKey === '_empty_') {
                continue;
            }
            parse_str($postedKey, $parsedKey);
            $rootKey = key($parsedKey);
            if (!is_array($parsedKey[$rootKey])) {
                continue;
            }
            $key = key($parsedKey[$rootKey]);
            $fieldMeta = acf_get_field($key);

            if (!$fieldMeta) {
                continue;
            }

            if (!in_array($fieldMeta['type'], ['repeater', 'group'])) {
                $this->fields[] = (object)[
                    'name' => $fieldMeta['_name'],
                    'key' => $key,
                    'htmlId' => $postedRow->id,
                    'type' => $fieldMeta['type'],
                    'value' => (float)$postedRow->value,
                    'originalValue' => (float)$postedRow->value,
                    'sortOrder' => $fieldMeta['menu_order'],
                    'formula' => isset($fieldMeta['formula']) ? trim($fieldMeta['formula']) : null,
                    'format' => isset($fieldMeta['calculated_format']) ?
                        trim($fieldMeta['calculated_format']) :
                        null,
                    'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                ];
            }

            if ($fieldMeta['type'] === 'group') {
                $subFieldKey = key($parsedKey[$rootKey][$key]);

                $fieldIndex = $this->findFieldIndexByKey($key);
                if (is_null($fieldIndex)) {
                    $this->fields[] = (object)[
                        'name' => $fieldMeta['_name'],
                        'key' => $key,
                        'type' => $fieldMeta['type'],
                        'value' => [],
                        'sortOrder' => $fieldMeta['menu_order'],
                        'formula' => isset($fieldMeta['formula']) ? trim($fieldMeta['formula']) : null,
                        'format' => isset($fieldMeta['calculated_format']) ?
                            trim($fieldMeta['calculated_format']) :
                            null,
                        'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                    ];
                    $fieldIndex = count($this->fields) - 1;
                }

                $subFieldMeta = $this->findSubFieldMeta($fieldMeta, $subFieldKey);
                if (!$subFieldMeta) {
                    continue;
                }

                $this->fields[$fieldIndex]->value[] = (object)[
                    'name' => $subFieldMeta['_name'],
                    'subFieldName' => "{$fieldMeta['name']}_{$subFieldMeta['_name']}",
                    'key' => $subFieldKey,
                    'htmlId' => $postedRow->id,
                    'type' => $subFieldMeta['type'],
                    'value' => (float)$postedRow->value,
                    'originalValue' => (float)$postedRow->value,
                    'sortOrder' => $fieldMeta['menu_order'],
                    'formula' => isset($subFieldMeta['formula']) ? $subFieldMeta['formula'] : null,
                    'format' => isset($fieldMeta['calculated_format']) ? trim($fieldMeta['calculated_format']) : null,
                    'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                ];
            }

            if ($fieldMeta['type'] === 'repeater') {
                $rowIndex = key($parsedKey[$rootKey][$key]);
                $subFieldKey = key($parsedKey[$rootKey][$key][$rowIndex]);

                if ($rowIndex === 'acfcloneindex') {
                    continue;
                }

                $fieldIndex = $this->findFieldIndexByKey($key);
                if (is_null($fieldIndex)) {
                    $this->fields[] = (object)[
                        'name' => $fieldMeta['_name'],
                        'key' => $key,
                        'type' => $fieldMeta['type'],
                        'value' => [],
                        'sortOrder' => $fieldMeta['menu_order'],
                        'formula' => isset($fieldMeta['formula']) ? trim($fieldMeta['formula']) : null,
                        'format' => isset($fieldMeta['calculated_format']) ?
                            trim($fieldMeta['calculated_format']) :
                            null,
                        'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                    ];
                    $fieldIndex = count($this->fields) - 1;
                }

                if (!isset($this->fields[$fieldIndex]->value[$rowIndex])) {
                    $this->fields[$fieldIndex]->value[$rowIndex] = [];
                }
                $subFieldMeta = $this->findSubFieldMeta($fieldMeta, $subFieldKey);
                if (!$subFieldMeta) {
                    continue;
                }
                $this->fields[$fieldIndex]->value[$rowIndex][] = (object)[
                    'name' => $subFieldMeta['_name'],
                    'subFieldName' => "{$fieldMeta['name']}_{$rowIndex}_{$subFieldMeta['_name']}",
                    'key' => $subFieldKey,
                    'htmlId' => $postedRow->id,
                    'type' => $subFieldMeta['type'],
                    'value' => (float)$postedRow->value,
                    'originalValue' => (float)$postedRow->value,
                    'sortOrder' => is_array($this->fields[$fieldIndex]->value[$rowIndex]) ?
                        count($this->fields[$fieldIndex]->value[$rowIndex]):
                        0,
                    'formula' => isset($subFieldMeta['formula']) ? $subFieldMeta['formula'] : null,
                    'format' => isset($fieldMeta['calculated_format']) ? trim($fieldMeta['calculated_format']) : null,
                    'blankIfZero' => isset($fieldMeta['blank_if_zero']) ? trim($fieldMeta['blank_if_zero']) : null,
                ];
            }
        }
    }

    /**
     * Makes a "best guess" of which fields that are dependencies for all
     * calculated fields. Passed on to front-end javascript to determine when
     * fields needs to be recalculated
     *
     * @param $fieldsMeta
     * @return array
     */
    public function getDependantFields($fieldsMeta)
    {
        $names = [];
        $depedencies = [];

        foreach ($fieldsMeta as $fieldMeta) {
            if (isset($fieldMeta['formula']) && strlen(trim($fieldMeta['formula'])) > 0) {
                foreach ($names as $name => $obj) {
                    if (empty($name)) {
                        $i = 0;
                    }
                    if (strpos($fieldMeta['formula'], $name) !== false) {
                        $depedencies[] = $obj->key;
                    }
                }
            }

            if (isset($fieldMeta['sub_fields'])) {
                foreach ($fieldMeta['sub_fields'] as $subField) {
                    if (isset($subField['formula']) && strlen(trim($subField['formula'])) > 0) {
                        foreach ($names as $name => $obj) {
                            if (empty($name)) {
                                $i = 0;
                            }

                            if (strpos($subField['formula'], $name) !== false) {
                                $depedencies[] = $obj->key;
                            }
                        }
                    }

                    $fieldName = $this->getFieldName($subField);
                    if (!empty($fieldName)) {
                        $names[$fieldName] = (object)[
                            'name' => $fieldName,
                            'key' => $subField['key'],
                        ];
                    }
                }
            }

            $fieldName = $this->getFieldName($fieldMeta);
            if (!empty($fieldName)) {
                $names[$fieldName] = (object)[
                    'name' => $fieldName,
                    'key' => $fieldMeta['key'],
                ];
            }
        }
        return array_unique($depedencies);
    }

    /**
     * Find an internal field based on its acf key
     *
     * @param $key
     * @return int|string|null
     */
    private function findFieldIndexByKey($key)
    {
        foreach ($this->fields as $index => $field) {
            if ($field->key === $key) {
                return $index;
            }
        }
        return null;
    }

    /**
     * Find sub field meta data based on parent key and field key
     *
     * @param $parentMeta
     * @param $key
     * @return mixed|null
     */
    private function findSubFieldMeta($parentMeta, $key)
    {
        foreach ($parentMeta['sub_fields'] as $sub_field) {
            if ($sub_field['key'] === $key) {
                return $sub_field;
            }
        }

        return null;
    }

    /**
     * Extract a field name from ACF field meta
     *
     * @param $fieldMeta
     * @return mixed|string
     */
    private function getFieldName($fieldMeta)
    {
        if (isset($fieldMeta['_name']) && !empty($fieldMeta['_name'])) {
            return $fieldMeta['_name'];
        }

        if (isset($fieldMeta['name']) && !empty($fieldMeta['name'])) {
            return $fieldMeta['name'];
        }

        return '';
    }
}
