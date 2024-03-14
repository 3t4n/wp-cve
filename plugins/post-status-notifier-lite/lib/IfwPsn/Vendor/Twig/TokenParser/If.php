<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Tests a condition.
 *
 * <pre>
 * {% if users %}
 *  <ul>
 *    {% for user in users %}
 *      <li>{{ user.username|e }}</li>
 *    {% endfor %}
 *  </ul>
 * {% endif %}
 * </pre>
 *
 * @final
 */
class IfwPsn_Vendor_Twig_TokenParser_If extends IfwPsn_Vendor_Twig_TokenParser
{
    public function parse(IfwPsn_Vendor_Twig_Token $token)
    {
        $lineno = $token->getLine();
        $expr = $this->parser->getExpressionParser()->parseExpression();
        $stream = $this->parser->getStream();
        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideIfFork']);
        $tests = [$expr, $body];
        $else = null;

        $end = false;
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case 'else':
                    $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);
                    $else = $this->parser->subparse([$this, 'decideIfEnd']);
                    break;

                case 'elseif':
                    $expr = $this->parser->getExpressionParser()->parseExpression();
                    $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);
                    $body = $this->parser->subparse([$this, 'decideIfFork']);
                    $tests[] = $expr;
                    $tests[] = $body;
                    break;

                case 'endif':
                    $end = true;
                    break;

                default:
                    throw new IfwPsn_Vendor_Twig_Error_Syntax(sprintf('Unexpected end of template. Twig was looking for the following tags "else", "elseif", or "endif" to close the "if" block started at line %d).', $lineno), $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        }

        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);

        return new IfwPsn_Vendor_Twig_Node_If(new IfwPsn_Vendor_Twig_Node($tests), $else, $lineno, $this->getTag());
    }

    public function decideIfFork(IfwPsn_Vendor_Twig_Token $token)
    {
        return $token->test(['elseif', 'else', 'endif']);
    }

    public function decideIfEnd(IfwPsn_Vendor_Twig_Token $token)
    {
        return $token->test(['endif']);
    }

    public function getTag()
    {
        return 'if';
    }
}

//class_alias('IfwPsn_Vendor_Twig_TokenParser_If', 'Twig\TokenParser\IfTokenParser', false);
