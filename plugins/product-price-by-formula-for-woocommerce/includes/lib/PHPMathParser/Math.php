<?php

require_once 'Stack.php';
require_once 'TerminalExpression.php';
require_once 'Expressions.php';

class ProWC_PPBF_Math {

    protected $variables = array();

    public function evaluate($string) {
        $stack = $this->parse($string);
        return $this->run($stack);
    }

    public function parse($string) {
        $tokens = $this->tokenize($string);
        $output = new ProWC_PPBF_Stack();
        $operators = new ProWC_PPBF_Stack();
        foreach ($tokens as $token) {
            $token = $this->extractVariables($token);
            $expression = ProWC_PPBF_TerminalExpression::factory($token);
            if ($expression->isOperator()) {
                $this->parseOperator($expression, $output, $operators);
            } elseif ($expression->isParenthesis()) {
                $this->parseParenthesis($expression, $output, $operators);
            } else {
                $output->push($expression);
            }
        }
        while (($op = $operators->pop())) {
            if ($op->isParenthesis()) {
                throw new RuntimeException('Mismatched Parenthesis');
            }
            $output->push($op);
        }
        return $output;
    }

    public function registerVariable($name, $value) {
        $this->variables[$name] = $value;
    }

    public function run(ProWC_PPBF_Stack $stack) {
        while (($operator = $stack->pop()) && $operator->isOperator()) {
            $value = $operator->operate($stack);
            if (!is_null($value)) {
                $stack->push(ProWC_PPBF_TerminalExpression::factory($value));
            }
        }
        return $operator ? $operator->render() : $this->render($stack);
    }

    protected function extractVariables($token) {
        if ($token[0] == '$') {
            $key = substr($token, 1);
            return isset($this->variables[$key]) ? $this->variables[$key] : 0;
        }
        return $token;
    }

    protected function render(ProWC_PPBF_Stack $stack) {
        $output = '';
        while (($el = $stack->pop())) {
            $output .= $el->render();
        }
        if ($output) {
            return $output;
        }
        throw new RuntimeException('Could not render output');
    }

    protected function parseParenthesis(ProWC_PPBF_TerminalExpression $expression, ProWC_PPBF_Stack $output, ProWC_PPBF_Stack $operators) {
        if ($expression->isOpen()) {
            $operators->push($expression);
        } else {
            $clean = false;
            while (($end = $operators->pop())) {
                if ($end->isParenthesis()) {
                    $clean = true;
                    break;
                } else {
                    $output->push($end);
                }
            }
            if (!$clean) {
                throw new RuntimeException('Mismatched Parenthesis');
            }
        }
    }

    protected function parseOperator(ProWC_PPBF_TerminalExpression $expression, ProWC_PPBF_Stack $output, ProWC_PPBF_Stack $operators) {
        $end = $operators->poke();
        if (!$end) {
            $operators->push($expression);
        } elseif ($end->isOperator()) {
            do {
                if ($expression->isLeftAssoc() && $expression->getPrecedence() <= $end->getPrecedence()) {
                    $output->push($operators->pop());
                } elseif (!$expression->isLeftAssoc() && $expression->getPrecedence() < $end->getPrecedence()) {
                    $output->push($operators->pop());
                } else {
                    break;
                }
            } while (($end = $operators->poke()) && $end->isOperator());
            $operators->push($expression);
        } else {
            $operators->push($expression);
        }
    }

    protected function tokenize($string) {
        $parts = preg_split('((\f+|\+|-|\(|\)|\*|\^|/)|\s+)', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $parts = array_map('trim', $parts);
        return $parts;
    }

}
