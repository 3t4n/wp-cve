<?php

namespace WunderAuto;

use WunderAuto\Tokenizer\Exception;
use WunderAuto\Tokenizer\Stream;
use WunderAuto\Tokenizer\Tokenizer;

/**
 * Class TemplateParser
 */
class TemplateParser
{
    // @phpcs:disable
    // Token constants
    const WA_OPEN_IF             = 20001;
    const WA_CLOSE_IF            = 20002;
    const WA_OPEN_FOREACH        = 20003;
    const WA_CLOSE_FOREACH       = 20004;
    const WA_AS                  = 20005;
    const WA_PIPE                = 20006;
    const WA_OTHER               = 20007;
    const WA_OPEN_CURLY_BRACKET  = 20008;
    const WA_CLOSE_CURLY_BRACKET = 20009;
    const WA_NUMBER              = 20010;
    const WA_WHITESPACE          = 20011;
    const WA_STRING              = 20012;
    // @phpcs:enable

    /**
     * @var EvalMath
     */
    public $evalMath;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var Stream
     */
    private $stream;

    /**
     * @var array<int, array<string, \stdClass>>
     */
    private $variableContexts;

    /**
     * @var array<int, \stdClass>
     */
    private $contexts;

    /**
     * @var ExpressionParser
     */
    private $expressionParser;

    /**
     * @param array<string, mixed>     $parameters
     * @param array<string, \stdClass> $objectTypes
     */
    public function __construct($parameters, $objectTypes)
    {
        $this->evalMath                  = new EvalMath();
        $this->evalMath->suppress_errors = true;
        $this->expressionParser          = new ExpressionParser($parameters, $objectTypes);
        $this->tokenizer                 = $this->getTokenizer();

        $this->variableContexts = [];
    }

    /**
     * @return Tokenizer
     */
    private function getTokenizer()
    {
        $characters = '[](){}<>=+-*/|\.,:;\'"#$%&!@_';
        $other      = preg_quote($characters, '/');

        return new Tokenizer(
            [
                self::WA_OPEN_IF             => '{{\s*if\s*',
                self::WA_CLOSE_IF            => '{{\s*endif\s*}}',
                self::WA_OPEN_FOREACH        => '{{\s*foreach\s*',
                self::WA_CLOSE_FOREACH       => '{{\s*endforeach\s*}}',
                self::WA_OPEN_CURLY_BRACKET  => '{{\s*',
                self::WA_CLOSE_CURLY_BRACKET => '\s*}}',
                self::WA_AS                  => '\s*as\s*',
                //self::WA_PIPE => '\s*|\s*',
                self::WA_OTHER               => ".",
                self::WA_NUMBER              => '\d+',
                self::WA_WHITESPACE          => '\s+',
                self::WA_STRING              => '\w+',
            ]
        );
    }

    /**
     * Parses a template
     *
     * @param string                   $expression
     * @param array<string, \stdClass> $context
     *
     * @throws Exception
     *
     * @return string
     */
    public function parse($expression, $context)
    {
        $this->stream           = $this->tokenizer->tokenize($expression);
        $this->variableContexts = [$context];
        $this->contexts         = [];

        $this->contextsPush('template');

        return $this->parseStream();
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    private function contextsPush($type)
    {
        $this->contexts[] = (object)[
            'type'    => $type,
            'output'  => '',
            'context' => null,
            'include' => true,
        ];

        return $this->currentContext();
    }

    /**
     * @return mixed
     */
    private function currentContext()
    {
        return $this->contexts[count($this->contexts) - 1];
    }

    /**
     * @return string
     */
    private function parseStream()
    {
        while ($token = $this->stream->nextToken()) {
            $currentContext = $this->currentContext();
            switch ($token->type) {
                case self::WA_CLOSE_CURLY_BRACKET:
                case self::WA_CLOSE_IF:
                    $ret = $this->contextsPop();
                    $this->addOutput($ret);
                    break;
                case self::WA_OPEN_IF:
                    $this->contextsPush('if');
                    break;
                case self::WA_OPEN_FOREACH:
                    // Not implemented yet
                    break;
                case self::WA_OPEN_CURLY_BRACKET:
                    // An opening bracket that is neither an if nor a foreach
                    $this->contextsPush('expression');
                    break;

                default:
                    if ($currentContext->include) {
                        $this->addOutput($token->value);
                    }
            }
        }

        return $this->contextsPop();
    }

    /**
     * Evaluates the current context and removes it from the
     * contexts array
     *
     * @return string
     */
    private function contextsPop()
    {
        $currentContext = $this->currentContext();
        $ret            = $this->contextEval();
        array_pop($this->contexts);
        switch ($currentContext->type) {
            case 'if':
                $currentContext          = $this->contextsPush('conditional');
                $currentContext->include = $ret;
                $ret                     = '';
                break;
        }
        return $ret;
    }

    /**
     * Evaluate the current context
     *
     * @return string
     */
    private function contextEval()
    {
        $ret            = '';
        $currentContext = $this->currentContext();
        switch ($currentContext->type) {
            case 'template':
                $ret = $currentContext->output;
                break;
            case 'expression':
                try {
                    $ret = $this->expressionParser->parse($currentContext->output, $this->variableContexts);
                } catch (\Exception $e) {
                    $ret = '';
                }
                break;
            case 'if':
                try {
                    $ret = (bool)$this->expressionParser->parse(
                        $currentContext->output,
                        $this->variableContexts
                    );
                } catch (\Exception $e) {
                    $ret = false;
                }
                break;
            case 'conditional':
                $ret = $currentContext->include ? $currentContext->output : '';
                break;
            case 'foreach':
                break;
        }

        return $ret;
    }

    /**
     * @param object|array<int, mixed>|string $string
     *
     * @return void
     */
    private function addOutput($string)
    {
        $currentContext = $this->currentContext();
        if (is_array($string) || is_object($string)) {
            $currentContext->output = $string;
            return;
        }

        $currentContext->output .= $string;
    }
}
