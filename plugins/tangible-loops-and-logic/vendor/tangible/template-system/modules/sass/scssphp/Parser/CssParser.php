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

namespace Tangible\ScssPhp\Parser;

use Tangible\ScssPhp\Ast\Sass\ArgumentInvocation;
use Tangible\ScssPhp\Ast\Sass\Expression;
use Tangible\ScssPhp\Ast\Sass\Expression\FunctionExpression;
use Tangible\ScssPhp\Ast\Sass\Expression\ParenthesizedExpression;
use Tangible\ScssPhp\Ast\Sass\Expression\StringExpression;
use Tangible\ScssPhp\Ast\Sass\Import\StaticImport;
use Tangible\ScssPhp\Ast\Sass\Interpolation;
use Tangible\ScssPhp\Ast\Sass\Statement;
use Tangible\ScssPhp\Ast\Sass\Statement\ImportRule;
use Tangible\ScssPhp\Compiler;

/**
 * A parser for imported CSS files.
 *
 * @internal
 */
final class CssParser extends ScssParser
{
    /**
     * Sass global functions which are shadowing a CSS function are allowed in CSS files.
     */
    private const CSS_ALLOWED_FUNCTIONS = [
        'rgb' => true, 'rgba' => true, 'hsl' => true, 'hsla' => true, 'grayscale' => true,
        'invert' => true, 'alpha' => true, 'opacity' => true, 'saturate' => true,
        'min' => true, 'max' => true, 'round' => true, 'abs' => true,
    ];

    protected function isPlainCss(): bool
    {
        return true;
    }

    protected function silentComment(): void
    {
        $start = $this->scanner->getPosition();
        parent::silentComment();
        $this->error("Silent comments aren't allowed in plain CSS.", $this->scanner->spanFrom($start));
    }

    protected function atRule(callable $child, bool $root = false): Statement
    {
        $start = $this->scanner->getPosition();

        $this->scanner->expectChar('@');
        $name = $this->interpolatedIdentifier();
        $this->whitespace();

        switch ($name->getAsPlain()) {
            case 'at-root':
            case 'content':
            case 'debug':
            case 'each':
            case 'error':
            case 'extend':
            case 'for':
            case 'function':
            case 'if':
            case 'include':
            case 'mixin':
            case 'return':
            case 'warn':
            case 'while':
                $this->almostAnyValue();
                $this->error("This at-rule isn't allowed in plain CSS.", $this->scanner->spanFrom($start));

            case 'import':
                return $this->cssImportRule($start);

            case 'media':
                return $this->mediaRule($start);

            case '-moz-document':
                return $this->mozDocumentRule($start, $name);

            case 'supports':
                return $this->supportsRule($start);

            default:
                return $this->unknownAtRule($start, $name);
        }
    }

    private function cssImportRule(int $start): ImportRule
    {
        $urlStart = $this->scanner->getPosition();
        $next = $this->scanner->peekChar();

        if ($next === 'u' || $next === 'U') {
            $url = $this->dynamicUrl();
        } else {
            $url = new StringExpression($this->interpolatedString()->asInterpolation(true));
        }
        $urlSpan = $this->scanner->spanFrom($urlStart);

        $this->whitespace();
        $modifiers = $this->tryImportModifiers();
        $this->expectStatementSeparator('@import rule');

        return new ImportRule([
            new StaticImport(new Interpolation([$url], $urlSpan), $this->scanner->spanFrom($start), $modifiers)
        ], $this->scanner->spanFrom($start));
    }

    protected function parentheses(): Expression
    {
        // Expressions are only allowed within calculations, but we verify this at
        // evaluation time.
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('(');
        $this->whitespace();
        $expression = $this->expressionUntilComma();
        $this->scanner->expectChar(')');

        return new ParenthesizedExpression($expression, $this->scanner->spanFrom($start));
    }

    protected function identifierLike(): Expression
    {
        $start = $this->scanner->getPosition();
        $identifier = $this->interpolatedIdentifier();
        $plain = $identifier->getAsPlain();
        assert($plain !== null); // CSS doesn't allow non-plain identifiers

        $lower = strtolower($plain);
        $specialFunction = $this->trySpecialFunction($lower, $start);

        if ($specialFunction !== null) {
            return $specialFunction;
        }

        $beforeArguments = $this->scanner->getPosition();
        // `namespacedExpression()` is just here to throw a clearer error.
        if ($this->scanner->scanChar('.')) {
            return $this->namespacedExpression($plain, $start);
        }
        if (!$this->scanner->scanChar('(')) {
            return new StringExpression($identifier);
        }

        $allowEmptySecondArg = $lower === 'var';
        $arguments = [];

        if (!$this->scanner->scanChar(')')) {
            do {
                $this->whitespace();

                if ($allowEmptySecondArg && \count($arguments) === 1 && $this->scanner->peekChar() === ')') {
                    $arguments[] = StringExpression::plain('', $this->scanner->getEmptySpan());
                    break;
                }

                $arguments[] = $this->expressionUntilComma(true);
                $this->whitespace();
            } while ($this->scanner->scanChar(','));
            $this->scanner->expectChar(')');
        }

        if ($plain === 'if' || (!isset(self::CSS_ALLOWED_FUNCTIONS[$plain]) && Compiler::isNativeFunction($plain))) {
            $this->error("This function isn't allowed in plain CSS.", $this->scanner->spanFrom($start));
        }

        return new FunctionExpression(
            $plain,
            new ArgumentInvocation($arguments, [], $this->scanner->spanFrom($beforeArguments)),
            $this->scanner->spanFrom($start)
        );
    }

    protected function namespacedExpression(string $namespace, int $start): Expression
    {
        $expression = parent::namespacedExpression($namespace, $start);

        $this->error("Module namespaces aren't allowed in plain CSS.", $expression->getSpan());
    }
}
