<?php

namespace WunderAuto\Tokenizer;

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 *
 * Adopted to the WunderAuto namespace to avoid conflicts
 */

/**
 * Simple lexical analyser.
 */
class Tokenizer
{
    /**
     * @var string
     */
    private $re;

    /**
     * @var array<int, int>
     */
    private $types;

    /**
     * Constructor
     *
     * @param array<int, string> $patterns Of [(int|string) token type => (string) pattern]
     * @param string             $flags    Regular expression flags
     */
    public function __construct(array $patterns, $flags = '')
    {
        $this->re    = '~(' . implode(')|(', $patterns) . ')~A' . $flags;
        $this->types = array_keys($patterns);
    }

    /**
     * Tokenizes string.
     *
     * @param string $input
     *
     * @throws \Exception
     *
     * @return Stream
     */
    public function tokenize($input)
    {
        preg_match_all($this->re, $input, $tokens, PREG_SET_ORDER);
        if (preg_last_error()) {
            throw new Exception(array_flip(get_defined_constants(true)['pcre'])[preg_last_error()]);
        }
        $len   = 0;
        $count = count($this->types);
        foreach ($tokens as &$token) {
            $type = -1;
            for ($i = 1; $i <= $count; $i++) {
                if (!isset($token[$i])) {
                    break;
                } elseif ($token[$i] != null) {
                    $type = $this->types[$i - 1];
                    break;
                }
            }
            $token = new Token($token[0], $type, $len);
            $len  += strlen($token->value);
        }
        if ($len !== strlen($input)) {
            $coordinates = $this->getCoordinates($input, $len);
            $line        = $coordinates[0];
            $col         = $coordinates[1];
            $token       = str_replace("\n", '\n', substr($input, $len, 10));
            throw new Exception("Unexpected '$token' on line $line, column $col.");
        }
        return new Stream($tokens);
    }

    /**
     * Returns position of token in input string.
     *
     * @param string $text
     * @param int    $offset
     *
     * @return array<int, int>
     */
    public static function getCoordinates($text, $offset)
    {
        $text = substr($text, 0, $offset);
        return [substr_count($text, "\n") + 1, $offset - strrpos("\n" . $text, "\n") + 1];
    }
}
