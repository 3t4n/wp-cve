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
 * Includes a template.
 *
 * <pre>
 *   {% include 'header.html' %}
 *     Body
 *   {% include 'footer.html' %}
 * </pre>
 */
class IfwPsn_Vendor_Twig_TokenParser_Include extends IfwPsn_Vendor_Twig_TokenParser
{
    public function parse(IfwPsn_Vendor_Twig_Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();

        list($variables, $only, $ignoreMissing) = $this->parseArguments();

        return new IfwPsn_Vendor_Twig_Node_Include($expr, $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
    }

    protected function parseArguments()
    {
        $stream = $this->parser->getStream();

        $ignoreMissing = false;
        if ($stream->nextIf(IfwPsn_Vendor_Twig_Token::NAME_TYPE, 'ignore')) {
            $stream->expect(IfwPsn_Vendor_Twig_Token::NAME_TYPE, 'missing');

            $ignoreMissing = true;
        }

        $variables = null;
        if ($stream->nextIf(IfwPsn_Vendor_Twig_Token::NAME_TYPE, 'with')) {
            $variables = $this->parser->getExpressionParser()->parseExpression();
        }

        $only = false;
        if ($stream->nextIf(IfwPsn_Vendor_Twig_Token::NAME_TYPE, 'only')) {
            $only = true;
        }

        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);

        return [$variables, $only, $ignoreMissing];
    }

    public function getTag()
    {
        return 'include';
    }
}

//class_alias('IfwPsn_Vendor_Twig_TokenParser_Include', 'Twig\TokenParser\IncludeTokenParser', false);
