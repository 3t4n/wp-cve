<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Type;

use WPPayVendor\JMS\Serializer\Type\Exception\SyntaxError;
/**
 * @internal
 */
final class Parser implements \WPPayVendor\JMS\Serializer\Type\ParserInterface
{
    /**
     * @var Lexer
     */
    private $lexer;
    /**
     * @var bool
     */
    private $root = \true;
    public function parse(string $string) : array
    {
        $this->lexer = new \WPPayVendor\JMS\Serializer\Type\Lexer();
        $this->lexer->setInput($string);
        $this->lexer->moveNext();
        return $this->visit();
    }
    /**
     * @return mixed
     */
    private function visit()
    {
        $this->lexer->moveNext();
        if (!$this->lexer->token) {
            throw new \WPPayVendor\JMS\Serializer\Type\Exception\SyntaxError('Syntax error, unexpected end of stream');
        }
        if (\WPPayVendor\JMS\Serializer\Type\Lexer::T_FLOAT === $this->lexer->token->type) {
            return \floatval($this->lexer->token->value);
        } elseif (\WPPayVendor\JMS\Serializer\Type\Lexer::T_INTEGER === $this->lexer->token->type) {
            return \intval($this->lexer->token->value);
        } elseif (\WPPayVendor\JMS\Serializer\Type\Lexer::T_NULL === $this->lexer->token->type) {
            return null;
        } elseif (\WPPayVendor\JMS\Serializer\Type\Lexer::T_STRING === $this->lexer->token->type) {
            return $this->lexer->token->value;
        } elseif (\WPPayVendor\JMS\Serializer\Type\Lexer::T_IDENTIFIER === $this->lexer->token->type) {
            if ($this->lexer->isNextToken(\WPPayVendor\JMS\Serializer\Type\Lexer::T_TYPE_START)) {
                return $this->visitCompoundType();
            } elseif ($this->lexer->isNextToken(\WPPayVendor\JMS\Serializer\Type\Lexer::T_ARRAY_START)) {
                return $this->visitArrayType();
            }
            return $this->visitSimpleType();
        } elseif (!$this->root && \WPPayVendor\JMS\Serializer\Type\Lexer::T_ARRAY_START === $this->lexer->token->type) {
            return $this->visitArrayType();
        }
        throw new \WPPayVendor\JMS\Serializer\Type\Exception\SyntaxError(\sprintf('Syntax error, unexpected "%s" (%s)', $this->lexer->token->value, $this->getConstant($this->lexer->token->type)));
    }
    /**
     * @return string|mixed[]
     */
    private function visitSimpleType()
    {
        $value = $this->lexer->token->value;
        return ['name' => $value, 'params' => []];
    }
    private function visitCompoundType() : array
    {
        $this->root = \false;
        $name = $this->lexer->token->value;
        $this->match(\WPPayVendor\JMS\Serializer\Type\Lexer::T_TYPE_START);
        $params = [];
        if (!$this->lexer->isNextToken(\WPPayVendor\JMS\Serializer\Type\Lexer::T_TYPE_END)) {
            while (\true) {
                $params[] = $this->visit();
                if ($this->lexer->isNextToken(\WPPayVendor\JMS\Serializer\Type\Lexer::T_TYPE_END)) {
                    break;
                }
                $this->match(\WPPayVendor\JMS\Serializer\Type\Lexer::T_COMMA);
            }
        }
        $this->match(\WPPayVendor\JMS\Serializer\Type\Lexer::T_TYPE_END);
        return ['name' => $name, 'params' => $params];
    }
    private function visitArrayType() : array
    {
        /*
         * Here we should call $this->match(Lexer::T_ARRAY_START); to make it clean
         * but the token has already been consumed by moveNext() in visit()
         */
        $params = [];
        if (!$this->lexer->isNextToken(\WPPayVendor\JMS\Serializer\Type\Lexer::T_ARRAY_END)) {
            while (\true) {
                $params[] = $this->visit();
                if ($this->lexer->isNextToken(\WPPayVendor\JMS\Serializer\Type\Lexer::T_ARRAY_END)) {
                    break;
                }
                $this->match(\WPPayVendor\JMS\Serializer\Type\Lexer::T_COMMA);
            }
        }
        $this->match(\WPPayVendor\JMS\Serializer\Type\Lexer::T_ARRAY_END);
        return $params;
    }
    private function match(int $token) : void
    {
        if (!$this->lexer->lookahead) {
            throw new \WPPayVendor\JMS\Serializer\Type\Exception\SyntaxError(\sprintf('Syntax error, unexpected end of stream, expected %s', $this->getConstant($token)));
        }
        if ($this->lexer->lookahead->type === $token) {
            $this->lexer->moveNext();
            return;
        }
        throw new \WPPayVendor\JMS\Serializer\Type\Exception\SyntaxError(\sprintf('Syntax error, unexpected "%s" (%s), expected was %s', $this->lexer->lookahead->value, $this->getConstant($this->lexer->lookahead->type), $this->getConstant($token)));
    }
    private function getConstant(int $value) : string
    {
        $oClass = new \ReflectionClass(\WPPayVendor\JMS\Serializer\Type\Lexer::class);
        return \array_search($value, $oClass->getConstants());
    }
}
