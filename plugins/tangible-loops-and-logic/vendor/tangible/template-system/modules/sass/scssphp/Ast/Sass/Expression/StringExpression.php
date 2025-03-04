<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace Tangible\ScssPhp\Ast\Sass\Expression;

use Tangible\ScssPhp\Ast\Sass\Expression;
use Tangible\ScssPhp\Ast\Sass\Interpolation;
use Tangible\ScssPhp\Parser\InterpolationBuffer;
use Tangible\ScssPhp\SourceSpan\FileSpan;
use Tangible\ScssPhp\Util\Character;
use Tangible\ScssPhp\Visitor\ExpressionVisitor;

/**
 * A string literal.
 *
 * @internal
 */
final class StringExpression implements Expression
{
    private readonly Interpolation $text;

    private readonly bool $quotes;

    public function __construct(Interpolation $text, bool $quotes = false)
    {
        $this->text = $text;
        $this->quotes = $quotes;
    }

    /**
     * Returns a string expression with no interpolation.
     */
    public static function plain(string $text, FileSpan $span, bool $quotes = false): self
    {
        return new self(new Interpolation([$text], $span), $quotes);
    }

    /**
     * Returns Sass source for a quoted string that, when evaluated, will have
     * $text as its contents.
     */
    public static function quoteText(string $text): string
    {
        $quote = self::bestQuote([$text]);
        $buffer = $quote;
        $buffer .= self::quoteInnerText($text, $quote, true);
        $buffer .= $quote;

        return $buffer;
    }

    public function getText(): Interpolation
    {
        return $this->text;
    }

    public function hasQuotes(): bool
    {
        return $this->quotes;
    }

    public function getSpan(): FileSpan
    {
        return $this->text->getSpan();
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitStringExpression($this);
    }

    public function asInterpolation(bool $static = false, string $quote = null): Interpolation
    {
        if (!$this->quotes) {
            return $this->text;
        }

        $quote = $quote ?? self::bestQuote($this->text->getContents());
        $buffer = new InterpolationBuffer();

        $buffer->write($quote);

        foreach ($this->text->getContents() as $value) {
            if ($value instanceof Expression) {
                $buffer->add($value);
            } else {
                $buffer->write(self::quoteInnerText($value, $quote, $static));
            }
        }

        $buffer->write($quote);

        return $buffer->buildInterpolation($this->text->getSpan());
    }

    private static function quoteInnerText(string $value, string $quote, bool $static = false): string
    {
        $buffer = '';
        $length = \strlen($value);

        for ($i = 0; $i < $length; $i++) {
            $char = $value[$i];

            if (Character::isNewline($char)) {
                $buffer .= '\\a';

                if ($i !== $length - 1) {
                    $next = $value[$i + 1];

                    if (Character::isWhitespace($next) || Character::isHex($next)) {
                        $buffer .= ' ';
                    }
                }
            } else {
                if ($char === $quote || $char === '\\' || ($static && $char === '#' && $i < $length - 1 && $value[$i + 1] === '{')) {
                    $buffer .= '\\';
                }

                if (\ord($char) < 0x80) {
                    $buffer .= $char;
                } else {
                    if (!preg_match('/./usA', $value, $m, 0, $i)) {
                        throw new \UnexpectedValueException('Invalid UTF-8 char');
                    }

                    $buffer .= $m[0];
                    $i += \strlen($m[0]) - 1; // skip over the extra bytes that have been processed.
                }
            }
        }

        return $buffer;
    }

    /**
     * @param array<string|Expression> $parts
     */
    private static function bestQuote(array $parts): string
    {
        $containsDoubleQuote = false;

        foreach ($parts as $part) {
            if (!\is_string($part)) {
                continue;
            }

            if (str_contains($part, "'")) {
                return '"';
            }

            if (str_contains($part, '"')) {
                $containsDoubleQuote = true;
            }
        }

        return $containsDoubleQuote ? "'" : '"';
    }

    public function __toString(): string
    {
        return (string) $this->asInterpolation();
    }
}
