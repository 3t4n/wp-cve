<?php

class ProWC_PPBF_Parenthesis extends ProWC_PPBF_TerminalExpression {

    protected $precedence = 6;

    public function operate(ProWC_PPBF_Stack $stack) {
    }

    public function getPrecedence() {
        return $this->precedence;
    }

    public function isNoOp() {
        return true;
    }

    public function isParenthesis() {
        return true;
    }

    public function isOpen() {
        return $this->value == '(';
    }

}

class ProWC_PPBF_Number extends ProWC_PPBF_TerminalExpression {

    public function operate(ProWC_PPBF_Stack $stack) {
        return $this->value;
    }

}

abstract class ProWC_PPBF_Operator extends ProWC_PPBF_TerminalExpression {

    protected $precedence = 0;
    protected $leftAssoc = true;

    public function getPrecedence() {
        return $this->precedence;
    }

    public function isLeftAssoc() {
        return $this->leftAssoc;
    }

    public function isOperator() {
        return true;
    }

}

class ProWC_PPBF_Addition extends ProWC_PPBF_Operator {

    protected $precedence = 4;

    public function operate(ProWC_PPBF_Stack $stack) {
        return $stack->pop()->operate($stack) + $stack->pop()->operate($stack);
    }

}

class ProWC_PPBF_Subtraction extends ProWC_PPBF_Operator {

    protected $precedence = 4;

    public function operate(ProWC_PPBF_Stack $stack) {
        $left = $stack->pop()->operate($stack);
        $right = $stack->pop()->operate($stack);
        return $right - $left;
    }

}

class ProWC_PPBF_Multiplication extends ProWC_PPBF_Operator {

    protected $precedence = 5;

    public function operate(ProWC_PPBF_Stack $stack) {
        return $stack->pop()->operate($stack) * $stack->pop()->operate($stack);
    }

}

class ProWC_PPBF_Division extends ProWC_PPBF_Operator {

    protected $precedence = 5;

    public function operate(ProWC_PPBF_Stack $stack) {
        $left = $stack->pop()->operate($stack);
        $right = $stack->pop()->operate($stack);
        return $right / $left;
    }

}

class ProWC_PPBF_Power extends ProWC_PPBF_Operator {

    protected $precedence = 5;

    public function operate(ProWC_PPBF_Stack $stack) {
        $left = $stack->pop()->operate($stack);
        $right = $stack->pop()->operate($stack);
        return pow($left,$right);
    }
}
