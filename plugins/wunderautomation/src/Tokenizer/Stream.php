<?php

namespace WunderAuto\Tokenizer;

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 *
 * Adopted to the WunderAuto namespace to avoid conflicts
 */

/**
 * Stream of tokens.
 */
class Stream
{
    /**
     * @var Token[]
     */
    public $tokens;

    /**
     * @var int
     */
    public $position = -1;

    /**
     * @var array<int, int>
     */
    public $ignored = [];

    /**
     * Constructor
     *
     * @param Token[] $tokens
     */
    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Returns current token.
     *
     * @return Token|null
     */
    public function currentToken()
    {
        return isset($this->tokens[$this->position]) ? $this->tokens[$this->position] : null;
    }

    /**
     * Returns current value
     *
     * @return string|null
     */
    public function currentValue()
    {
        return isset($this->tokens[$this->position]->value) ?
            $this->tokens[$this->position]->value :
            null;
    }

    /**
     * Returns next token.
     *
     * @param string|int ...$args Desired token type or value
     *
     * @return Token|mixed|array|string|null
     */
    public function nextToken(...$args)
    {
        // onlyFirst, advance
        return $this->scan($args, true, true);
    }

    /**
     * Looks for (first) (not) wanted tokens.
     *
     * @param array<int, string|int> $wanted
     * @param bool                   $onlyFirst
     * @param bool                   $advance
     * @param bool                   $strings
     * @param bool                   $until
     * @param bool                   $prev
     *
     * @return mixed
     */
    protected function scan(array $wanted, $onlyFirst, $advance, $strings = false, $until = false, $prev = false)
    {
        $res = $onlyFirst ? null : ($strings ? '' : []);
        $pos = $this->position + ($prev ? -1 : 1);
        do {
            if (!isset($this->tokens[$pos])) {
                if (!$wanted && $advance && !$prev && $pos <= count($this->tokens)) {
                    $this->next();
                }
                return $res;
            }

            $token = $this->tokens[$pos];
            if (
                !$wanted || (
                    in_array($token->value, $wanted, true)
                    || in_array($token->type, $wanted, true)
                ) ^ $until
            ) {
                while ($advance && !$prev && $pos > $this->position) {
                    $this->next();
                }

                if ($onlyFirst) {
                    return $strings ? $token->value : $token;
                } elseif ($strings) {
                    $res .= $token->value;
                } else {
                    $res[] = $token;
                }
            } elseif ($until || !in_array($token->type, $this->ignored, true)) {
                return $res;
            }
            $pos += $prev ? -1 : 1;
        } while (true);
    }

    /**
     * Moves cursor to next token.
     *
     * @return void
     */
    protected function next()
    {
        $this->position++;
    }

    /**
     * Returns next token value.
     *
     * @param string|int ...$args Desired token type or value
     *
     * @return Token|mixed|array|string|null
     */
    public function nextValue(...$args)
    {
        // onlyFirst, advance, strings
        return $this->scan($args, true, true, true);
    }

    /**
     * Returns all next tokens.
     *
     * @param string|int ...$args Desired token type or value
     *
     * @return Token[]
     */
    public function nextAll(...$args)
    {
        // advance
        return $this->scan($args, false, true);
    }

    /**
     * Returns all next tokens until it sees a given token type or value.
     *
     * @param string|int ...$args Token type or value to stop before (required)
     *
     * @return Token[]
     */
    public function nextUntil(...$args)
    {
        // advance, until
        return $this->scan($args, false, true, false, true);
    }

    /**
     * Returns next token value or throws exception.
     *
     * @param string|int ...$args Desired token type or value
     *
     * @throws Exception
     *
     * @return string
     */
    public function consumeValue(...$args)
    {
        return $this->consumeToken(...$args)->value;
    }

    /**
     * Returns next token or throws exception.
     *
     * @param string|int ...$args Desired token type or value
     *
     * @throws Exception
     *
     * @return Token|mixed|array|string|null
     */
    public function consumeToken(...$args)
    {
        // onlyFirst, advance
        if ($token = $this->scan($args, true, true)) {
            return $token;
        }

        $pos = $this->position + 1;
        while (
            ($next = isset($this->tokens[$pos]) ? $this->tokens[$pos] : null)
            && in_array($next->type, $this->ignored, true)
        ) {
            $pos++; // skip ignored
        }
        if (!$next) {
            throw new Exception('Unexpected end of string');
        }

        $s = '';
        do {
            $s = $this->tokens[$pos]->value . $s;
        } while ($pos--);

        $coordinates = Tokenizer::getCoordinates($s, $next->offset);
        $line        = $coordinates[0];
        $col         = $coordinates[1];

        throw new Exception("Unexpected '$next->value' on line $line, column $col.");
    }

    /**
     * Returns concatenation of all next token values.
     *
     * @param string|int ...$args Token type or value to be joined
     *
     * @return Token|mixed|array|string|null
     */
    public function joinAll(...$args)
    {
        // advance, strings
        return $this->scan($args, false, true, true);
    }

    /**
     * Returns concatenation of all next tokens until it sees a given token type or value.
     *
     * @param string|int ...$args Token type or value to stop before (required)
     *
     * @return Token|mixed|array|string|null
     */
    public function joinUntil(...$args)
    {
        // advance, strings, until
        return $this->scan($args, false, true, true, true);
    }

    /**
     * Checks the current token.
     *
     * @param string|int ...$args Token type or value
     *
     * @return bool
     */
    public function isCurrent(...$args)
    {
        if (!isset($this->tokens[$this->position])) {
            return false;
        }
        $token = $this->tokens[$this->position];
        return in_array($token->value, $args, true)
            || in_array($token->type, $args, true);
    }

    /**
     * Checks the next token existence.
     *
     * @param string|int ...$args Token type or value
     *
     * @return bool
     */
    public function isNext(...$args)
    {
        // onlyFirst
        return (bool)$this->scan($args, true, false);
    }

    /**
     * Checks the previous token existence.
     *
     * @param string|int ...$args Token type or value
     *
     * @return bool
     */
    public function isPrev(...$args)
    {
        // onlyFirst, prev
        return (bool)$this->scan($args, true, false, false, false, true);
    }

    /**
     * @return static
     */
    public function reset()
    {
        $this->position = -1;
        return $this;
    }
}
