<?php

namespace WunderAuto;

use WunderAuto\Tokenizer\Exception;
use WunderAuto\Tokenizer\Stream;
use WunderAuto\Tokenizer\Tokenizer;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class ExpressionParser
 */
class ExpressionParser
{
    // @phpcs:disable
    // Token constants
    const WA_VARIABLE          = 20000;
    const WA_DOT               = 20001;
    const WA_OPERATOR          = 20002;
    const WA_INTEGER           = 20003;
    const WA_OPEN_PARENTHESIS  = 20004;
    const WA_CLOSE_PARENTHESIS = 20005;
    const WA_WHITESPACE        = 20006;
    const WA_PIPE              = 20007;
    const WA_EQUALS            = 20008;
    const WA_OTHER             = 20009;
    const WA_FUNCTIONS         = 20010;
    const WA_CONSTANTS         = 20011;
    const VARIABLE_NOT_FOUND   = 1;
    // @phpcs:enable

    /**
     * @var EvalMath
     */
    private $evalMath;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var Stream
     */
    private $stream;

    /**
     * @var array<int, \stdClass>
     */
    private $contexts;

    /**
     * Keep track of special object types that we need to
     * handle via a Parameter class.
     *
     * @var array<string, \stdClass>
     */
    private $objectTypes;

    /**
     * All parameters that are used to access properties on the
     * $objectTypes objects
     *
     * @var array<string, mixed>
     */
    private $parameters;

    /**
     * @var array<int, array<string, \stdClass>>
     */
    private $rootVariableContexts;

    /**
     * @param array<string, mixed>     $parameters
     * @param array<string, \stdClass> $objectTypes
     */
    public function __construct($parameters = [], $objectTypes = [])
    {
        $this->objectTypes = $objectTypes;
        $this->parameters  = $parameters;
        $this->tokenizer   = $this->getTokenizer();

        $this->evalMath = new EvalMath();

        $this->evalMath->suppress_errors = true;
    }

    /**
     * @return Tokenizer
     */
    private function getTokenizer()
    {
        $operators = preg_quote('+-*/', '/');
        return new Tokenizer(
            [
                self::WA_FUNCTIONS         => '\b(?:round\(|floor\(|ceil\(|sqrt\()\b',
                self::WA_CONSTANTS         => '\b(?:pi|e)\b',
                self::WA_VARIABLE          => '[A-Za-z_]+[A-Za-z0-9_]*',
                self::WA_PIPE              => '\s*\|\s*',
                self::WA_INTEGER           => '\d+',
                self::WA_DOT               => '\.',
                self::WA_WHITESPACE        => '\s+',
                self::WA_OPERATOR          => "[$operators]",
                self::WA_OPEN_PARENTHESIS  => '\(',
                self::WA_CLOSE_PARENTHESIS => '\)',
                self::WA_OTHER             => '.',
            ]
        );
    }

    /**
     * Parse expression
     *
     * @param string                               $expression
     * @param array<int, array<string, \stdClass>> $variableContexts
     *
     * @throws Exception
     *
     * @return string
     */
    public function parse($expression, $variableContexts)
    {
        $this->rootVariableContexts = $variableContexts;
        $this->contextsPush('expression', 0);

        $this->stream = $this->tokenizer->tokenize($expression);
        return $this->parseStream();
    }

    /**
     * Push a new context to the stack
     *
     * @param string $type
     * @param int    $start
     *
     * @return mixed
     */
    private function contextsPush($type, $start)
    {
        $this->contexts[] = (object)[
            'type'         => $type,
            'start'        => $start,
            'output'       => '',
            'variable'     => '',
            'variableName' => '',
            'afterPipe'    => false,
            'modifiers'    => false,
            'context'      => null,
            'function'     => '',
        ];

        return $this->currentContext();
    }

    /**
     * Get the current context
     *
     * @return mixed
     */
    private function currentContext()
    {
        return $this->contexts[count($this->contexts) - 1];
    }

    /**
     * Parse the tokenized stream
     *
     * @throws Exception
     *
     * @return string
     */
    private function parseStream()
    {
        $fullVariableName = [];

        while ($token = $this->stream->nextToken()) {
            $currentContext = $this->currentContext();
            switch ($token->type) {
                case self::WA_OPEN_PARENTHESIS:
                    $this->contextsPush('parenthesis', $this->stream->position);
                    break;
                case self::WA_CLOSE_PARENTHESIS:
                    $ret = $this->contextsPop();
                    $this->addOutput($ret);
                    break;
                case self::WA_FUNCTIONS:
                    $currentContext           = $this->contextsPush('function', $this->stream->position);
                    $currentContext->function = $token->value;
                    break;
                case self::WA_VARIABLE:
                    if ($currentContext->afterPipe) {
                        $this->addModifiers($token->value);
                        break;
                    }

                    if (strlen($currentContext->output) > 0) {
                        $currentContext = $this->contextsPush('variable', $this->stream->position);
                    }

                    $variableName       = $token->value;
                    $fullVariableName[] = $variableName;

                    if ($this->stream->isNext(self::WA_DOT)) {
                        $this->stream->nextToken();
                        // Determine if the current context has a matching variable
                        if (is_null($currentContext->context)) {
                            // Go look in the root context
                            $currentContext->context = $this->findInRootContext($variableName);
                            if (is_null($currentContext->context)) {
                                $fullName = join('.', $fullVariableName);
                                throw(new Exception("Variable $fullName not found"));
                            }
                        } else {
                            try {
                                $currentContext->context = $this->getValue($currentContext->context, $variableName);
                            } catch (\Exception $e) {
                                $parentName = join('.', array_slice($fullVariableName, 0, -1));
                                throw(new Exception("Property $variableName in $parentName not found"));
                            }
                        }
                    } else {
                        // We've got a complete variable name
                        $currentContext->variableName = $variableName;
                        if ($currentContext->type === 'variable') {
                            $this->addOutput($this->contextsPop());
                        }

                        if ($this->stream->isNext(self::WA_PIPE)) {
                            $currentContext->afterPipe = true;
                            break;
                        } else {
                            $this->addOutput($this->contextEval());
                            break;
                        }
                    }
                    break;
                case self::WA_PIPE:
                    // Just eat it.
                    break;
                default:
                    if ($currentContext->afterPipe) {
                        $this->addModifiers($token->value);
                        break;
                    }
                    $this->addOutput($token->value);
            }
        }

        return $this->contextsPop();
    }

    /**
     * Evaluate the current context and remove it from the stack
     *
     * @throws Exception
     *
     * @return mixed|array|string|bool
     */
    private function contextsPop()
    {
        $ret = $this->contextEval();
        array_pop($this->contexts);
        return $ret;
    }

    /**
     * Evaluate the context considering context type etc.
     *
     * @throws Exception
     *
     * @return mixed|array|string|bool
     */
    private function contextEval()
    {
        $currentContext = $this->currentContext();

        if (strlen($currentContext->variableName)) {
            $modifiers = (object)[];
            if (strlen($currentContext->modifiers) > 0) {
                $json                      = $this->convertToJson($currentContext->modifiers);
                $currentContext->modifiers = json_decode($json);
            }
            $ret = $this->getValue(
                $currentContext->context,
                $currentContext->variableName,
                $currentContext->modifiers
            );
        } else {
            $ret = $currentContext->output;
        }

        if (!is_object($ret) && !is_array($ret)) {
            if ($currentContext->type === 'function') {
                $ret = $currentContext->function . $ret . ')';
            }

            $mathResolved = $this->evalMath->evaluate($ret);
            if ($mathResolved !== false) {
                $currentContext->output = '';
                $ret                    = $mathResolved;
            }
        }

        // Neutralize
        $currentContext->variableName = '';
        $currentContext->modifiers    = '';
        $currentContext->afterPipe    = false;
        $currentContext->context      = null;

        return $ret;
    }

    /**
     * Convert JSON-ish modifier strings to proper JSON
     * This could perhaps be done with another parser, but
     * this existing code from WunderAutomation pre 1.6 has
     * been working great, no need to change right now.
     *
     * @param string $json
     *
     * @return string
     */
    public function convertToJson($json)
    {
        $json = trim($json);
        $json = trim($json, ' ');
        $json = str_replace('[', '__&lp;__', $json);
        $json = str_replace(']', '__&rp;__', $json);
        $json = str_replace('{', '__&lcb;__', $json);
        $json = str_replace('}', '__&rcb;__', $json);

        $json = '{' . $json . '}';
        $json = preg_replace("/(,|\{)\s*\'?(\w+)\'?\s*:\s*/", '$1"$2":', $json);
        $json = preg_replace(
            '/":\'?([^\[\]\{\}]*?)\'?\s*(,"|\}$|\]$|\}\]|\]\}|\}|\])/',
            '":"$1"$2',
            (string)$json
        );
        $json = preg_replace(
            '/""/',
            '"',
            (string)$json
        );

        $json = str_replace('__&lp;__', '[', (string)$json);
        $json = str_replace('__&rp;__', ']', (string)$json);
        $json = str_replace('__&lcb;__', '{', (string)$json);
        $json = str_replace('__&rcb;__', '}', (string)$json);

        return $json;
    }

    /**
     * Get the expression value
     *
     * @param \stdClass|array<string, \stdClass>|string|null $context
     * @param string                                         $variableName
     * @param \stdClass|null                                 $modifiers
     *
     * @throws Exception
     *
     * @return mixed|null
     */
    private function getValue($context, $variableName, $modifiers = null)
    {
        if (is_null($modifiers)) {
            $modifiers = (object)[];
        }

        if (is_null($context)) {
            // Look in the root context
            $variable = $this->findInRootContext($variableName);
            if (is_null($variable)) {
                throw(new Exception("Variable $variableName not found", self::VARIABLE_NOT_FOUND));
            }

            if ($variable instanceof BaseParameter) {
                return $variable->getValue(null, $modifiers);
            }

            return $variable;
        } else {
            $objectType = $this->parameterObjectType($context);
            if (!is_null($objectType) && ($context instanceof \stdClass)) {
                foreach ($this->parameters as $parameter) {
                    if ($parameter->title === $variableName && in_array($objectType, $parameter->objects)) {
                        $value = $parameter->getValue($context->value, $modifiers);
                        if (is_null($value) && isset($modifiers->default)) {
                            $value = $modifiers->default;
                        }
                        return $value;
                    }
                }
                throw(new Exception("Variable $variableName not found", self::VARIABLE_NOT_FOUND));
            }

            if (is_array($context)) {
                // Array
                if (isset($context[$variableName])) {
                    return $context[$variableName];
                }
            }

            if (is_object($context)) {
                // Object
                if (isset($context->$variableName)) {
                    return $context->$variableName;
                }
            }
        }

        return null;
    }

    /**
     * Get the first object in the root context that matches
     * the variable name
     *
     * @param string $variableName
     *
     * @return mixed
     */
    private function findInRootContext($variableName)
    {
        foreach ($this->rootVariableContexts as $rootContext) {
            if (isset($rootContext[$variableName])) {
                return $rootContext[$variableName];
            }
        }

        $key = "*.$variableName";
        if (isset($this->parameters[$key])) {
            return $this->parameters[$key];
        }

        return null;
    }

    /**
     * Determine if the object in the current context has its parameters
     * served by a parameter object
     *
     * @param \stdClass|array<string, \stdClass>|string $context
     *
     * @return string|null
     */
    private function parameterObjectType($context)
    {
        if (is_object($context)) {
            foreach ($this->objectTypes as $type => $object) {
                if (isset($context->type) && $context->type === $type) {
                    return $type;
                }
            }
        }

        if (is_string($context)) {
            foreach ($this->objectTypes as $type => $object) {
                if ($context === $type) {
                    return $type;
                }
            }
        }

        return null;
    }

    /**
     * Add a string to the current context output
     *
     * @param object|array<int, string>|string $string
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

    /**
     * Add a string to the current contexts modifier expression
     *
     * @param string $string
     *
     * @return void
     */
    private function addModifiers($string)
    {
        $currentStack             = $this->currentContext();
        $currentStack->modifiers .= $string;
    }
}
