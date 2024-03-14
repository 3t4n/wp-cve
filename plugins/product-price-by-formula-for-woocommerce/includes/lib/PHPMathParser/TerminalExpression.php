<?php

abstract class ProWC_PPBF_TerminalExpression {

    protected $value = '';

    public function __construct($value) {
        $this->value = $value;
    }

    public static function factory($value) {

        if (is_object($value) && $value instanceof ProWC_PPBF_TerminalExpression) {
            return $value;
        } elseif (is_numeric($value)) {
            return new ProWC_PPBF_Number($value);
        } elseif ($value == '+') {
            return new ProWC_PPBF_Addition($value);
        } elseif ($value == '-') {
            return new ProWC_PPBF_Subtraction($value);
        } elseif ($value == '*') {
            return new ProWC_PPBF_Multiplication($value);
        } elseif ($value == '/') {
            return new ProWC_PPBF_Division($value);
        } elseif (in_array($value, array('(', ')'))) {
            return new ProWC_PPBF_Parenthesis($value);
        } elseif ($value == '^') {
            return new ProWC_PPBF_Power($value);
        }
        throw new Exception('Undefined Value ' . $value);
    }

    abstract public function operate(ProWC_PPBF_Stack $stack);

    public function isOperator() {
        return false;
    }

    public function isParenthesis() {
        return false;
    }

    public function isNoOp() {
        return false;
    }

    public function render() {
        return $this->value;
    }
}
