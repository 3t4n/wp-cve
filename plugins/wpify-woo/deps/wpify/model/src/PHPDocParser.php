<?php

namespace WpifyWooDeps\Wpify\Model;

use WpifyWooDeps\PHPStan\PhpDocParser\Lexer\Lexer;
use WpifyWooDeps\PHPStan\PhpDocParser\Parser\ConstExprParser;
use WpifyWooDeps\PHPStan\PhpDocParser\Parser\TokenIterator;
use WpifyWooDeps\PHPStan\PhpDocParser\Parser\TypeParser;
class PHPDocParser
{
    static $parsed;
    public $lexer;
    public $parser;
    public function __construct()
    {
        $this->lexer = new Lexer();
        $constExprParser = new ConstExprParser();
        $this->parser = new \WpifyWooDeps\PHPStan\PhpDocParser\Parser\PhpDocParser(new TypeParser($constExprParser), $constExprParser);
    }
    public function parse($class, $type, $input, $name = '')
    {
        if ('properties' === $type && isset(self::$parsed[$class][$type][$name])) {
            return self::$parsed[$class][$type][$name];
        }
        $tokens = new TokenIterator($this->lexer->tokenize($input));
        $parsed = $this->parser->parse($tokens);
        if ($type === 'properties') {
            self::$parsed[$class][$type][$name] = $parsed;
        }
        return $parsed;
    }
}
