<?php

namespace CalculatedFields;

/**
 * Class Evaluator
 *
 * @package CalculatedFields
 */
class Evaluator
{
    /**
     * Current state of all fields
     *
     * @var array
     */
    private $fields;

    private $containers;

    /**
     * Initiate using a pre-parsed array of fields.
     *
     * @param $fields
     */
    public function init($fields)
    {
        $this->fields = $fields;
        $math = new EvalMath();
        $math->suppress_errors = true;
        $this->containers = ['parent'];

        foreach ($this->fields as &$field) {
            $this->evaluateField($math, $field);
        }
        $i = 0;
    }

    /**
     * Evaluates each field using the math object as context. Calculates
     * repeater fields by calling itself recursively
     *
     * @param $math
     * @param $field
     */
    private function evaluateField($math, &$field)
    {
        if (!is_object($field->value) && !is_array($field->value)) {
            $math->v[$field->name] = $field->value;
        }

        if ($field->type === 'group') {
            $this->containers[] = $field->name;
            $newMath = new EvalMath();
            $newMath->suppress_errors = true;
            foreach ($math->v as $name => $value) {
                $newMath->v["parent_$name"] = $value;
            }
            foreach ($field->value as $subField) {
                $this->evaluateField($newMath, $subField);
                $math->v[$field->name . '_' . $subField->name] = $subField->value;
            }
        }

        if ($field->type === 'repeater') {
            foreach ($field->value as $row) {
                $newMath = new EvalMath();
                $newMath->suppress_errors = true;
                foreach ($math->v as $name => $value) {
                    $newMath->v["parent_$name"] = $value;
                }
                foreach ($row as $subField) {
                    $this->evaluateField($newMath, $subField);
                }
            }
        }

        if (isset($field->formula) && strlen(trim($field->formula)) > 0) {
            $formula = $field->formula;
            foreach ($this->containers as $container) {
                $formula = str_replace("{$container}.", "{$container}_", $formula);
            }
            $formula = $this->prepareFormula($formula, null, null);
            $field->value = $math->evaluate($formula);
            $math->v[$field->name] = $field->value;
            if ($field->value === false) {
                $field->value = 0;
            }
        }
    }

    /**
     * Used when called from ACF standard save_post. Called once per field
     *
     * @param $requestedField
     * @return |null
     */
    public function getField($requestedField)
    {
        // Take care of fields at level 0
        if ($requestedField['name'] === $requestedField['_name']) {
            foreach ($this->fields as $field) {
                if ($field->key === $requestedField['key']) {
                    return $this->formatFieldValue($field);
                }
            }
            return null;
        }

        // Fields inside a repeater or group
        if ($requestedField['name'] !== $requestedField['_name']) {
            foreach ($this->fields as $field) {
                if (!is_array($field->value)) {
                    continue;
                }

                if ($field->type == 'group') {
                    foreach ($field->value as $subField) {
                        if ($subField->subFieldName === $requestedField['name']) {
                            return $this->formatFieldValue($field);
                        }
                    }
                }

                if ($field->type == 'repeater') {
                    foreach ($field->value as $row) {
                        foreach ($row as $subField) {
                            if ($subField->subFieldName === $requestedField['name']) {
                                return $this->formatFieldValue($subField);
                            }
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Used when called via ajax. Returns an array of fields
     * that was changed during evaluation
     *
     * @return array
     */
    public function getUpdatedFields()
    {
        $ret = [];
        foreach ($this->fields as $field) {
            if (strlen($field->formula) > 0) {
                if ($field->value != $field->originalValue) {
                    $ret[] = (object)[
                        'id' => $field->htmlId,
                        'value' => $this->formatFieldValue($field),
                    ];
                }
            }

            if ($field->type === 'group') {
                foreach ($field->value as $subField) {
                    if (strlen($subField->formula) > 0) {
                        if ($subField->value != $subField->originalValue) {
                            $ret[] = (object)[
                                'id' => $subField->htmlId,
                                'value' => $this->formatFieldValue($subField),
                            ];
                        }
                    }
                }
            }

            if ($field->type === 'repeater') {
                foreach ($field->value as $row) {
                    foreach ($row as $subField) {
                        if (strlen($subField->formula) > 0) {
                            if ($subField->value != $subField->originalValue) {
                                $ret[] = (object)[
                                    'id' => $subField->htmlId,
                                    'value' => $this->formatFieldValue($subField),
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * Resolve array functions
     *
     * @param $formula
     * @param $acfValues
     * @param $postId
     *
     * @return mixed
     */
    private function prepareFormula($formula, $acfValues, $postId)
    {
        $functions = [
            (object)['name' => 'sum', 'func' => [$this, 'funcSum']],
            (object)['name' => 'average', 'func' => [$this, 'funcAvg']],
            (object)['name' => 'count', 'func' => [$this, 'funcCount']],
            (object)['name' => 'max', 'func' => [$this, 'funcMax']],
            (object)['name' => 'min', 'func' => [$this, 'funcMin']],
        ];

        foreach ($functions as $function) {
            $pattern = '/' . $function->name . '\s*\(\s*(.*?)\s*\)/i';

            $formula = preg_replace_callback(
                $pattern,
                function ($matches) use ($function, $acfValues, $postId) {
                    $parts = explode('.', trim($matches[1]));

                    if (count($parts) == 1) {
                        return $parts[0];
                    }

                    $ret = 0;
                    if (count($parts) > 1) {
                        $field = trim($parts[0]);
                        $expression = trim($parts[1]);
                        $ret = call_user_func($function->func, $field, $expression);
                    }
                    return $ret;
                },
                $formula
            );
        }

        return $formula;
    }

    /**
     * Calulate sum of fields
     *
     * @param $fieldName
     * @param $expression
     * @return int|mixed
     */
    private function funcSum($fieldName, $expression)
    {
        $ret = 0;
        $math = new EvalMath();
        $math->suppress_errors = true;

        $field = $this->findFieldByName($fieldName);
        if (is_null($fieldName)) {
            return 0;
        }

        if (!is_array($field->value)) {
            return 0;
        }

        foreach ($field->value as $row) {
            if (count($row) === 0) {
                continue;
            }
            foreach ($row as $subField) {
                if (is_object($subField->value) || is_array($subField->value)) {
                    continue;
                }
                $math->v[$subField->name] = $subField->value;
            }
            $ret += $math->evaluate($expression);
        }
        return $ret;
    }

    /**
     * Calulate average of fields
     *
     * @param $fieldName
     * @param $expression
     * @return int|mixed
     */
    private function funcAvg($fieldName, $expression)
    {
        $field = $this->findFieldByName($fieldName);
        if (is_null($fieldName)) {
            return 0;
        }

        if (!is_array($field->value)) {
            return 0;
        }

        $sum = $this->funcSum($fieldName, $expression);
        return $sum / count($field->value);
    }

    /**
     * Calulate count of fields
     *
     * @param $fieldName
     * @param $expression
     * @return float|int
     */
    private function funcCount($fieldName, $expression)
    {
        $field = $this->findFieldByName($fieldName);
        if (is_null($fieldName)) {
            return 0;
        }

        if (!is_array($field->value)) {
            return 0;
        }

        return count($field->value);
    }

    /**
     * Calulate max of fields
     *
     * @param $fieldName
     * @param $expression
     * @return int|mixed
     */
    private function funcMax($fieldName, $expression)
    {
        $values = [];
        $math = new EvalMath();
        $math->suppress_errors = true;

        $field = $this->findFieldByName($fieldName);
        if (is_null($fieldName)) {
            return 0;
        }

        if (!is_array($field->value)) {
            return 0;
        }

        foreach ($field->value as $row) {
            if (count($row) === 0) {
                continue;
            }
            foreach ($row as $subField) {
                if (is_object($subField->value) || is_array($subField->value)) {
                    continue;
                }
                $math->v[$subField->name] = $subField->value;
            }
            $values[] = $math->evaluate($expression);
        }

        return max($values);
    }

    /**
     * Calulate min of fields
     *
     * @param $fieldName
     * @param $expression
     * @return int|mixed
     */
    private function funcMin($fieldName, $expression)
    {
        $values = [];
        $math = new EvalMath();
        $math->suppress_errors = true;

        $field = $this->findFieldByName($fieldName);
        if (is_null($fieldName)) {
            return 0;
        }

        if (!is_array($field->value)) {
            return 0;
        }

        foreach ($field->value as $row) {
            if (count($row) === 0) {
                continue;
            }
            foreach ($row as $subField) {
                if (is_object($subField->value) || is_array($subField->value)) {
                    continue;
                }
                $math->v[$subField->name] = $subField->value;
            }
            $values[] = $math->evaluate($expression);
        }

        return min($values);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    private function findFieldByName($name)
    {
        foreach ($this->fields as $field) {
            if ($field->name === $name) {
                return $field;
            }
        }
        return null;
    }

    private function formatFieldValue($field)
    {
        $value = $field->value;

        if (isset($field->format) && !empty($field->format)) {
            $value = sprintf($field->format, $field->value);
        }

        if ($value == 0 && isset($field->blankIfZero) && $field->blankIfZero == '1') {
            $value = '';
        }

        return $value;
    }
}
