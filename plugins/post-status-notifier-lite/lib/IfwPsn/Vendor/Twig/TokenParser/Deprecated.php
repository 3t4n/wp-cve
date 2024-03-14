<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Deprecates a section of a template.
 *
 * <pre>
 * {% deprecated 'The "base.twig" template is deprecated, use "layout.twig" instead.' %}
 *
 * {% extends 'layout.html.twig' %}
 * </pre>
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 *
 * @final
 */
class IfwPsn_Vendor_Twig_TokenParser_Deprecated extends IfwPsn_Vendor_Twig_TokenParser
{
    public function parse(IfwPsn_Vendor_Twig_Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();

        $this->parser->getStream()->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);

        return new IfwPsn_Vendor_Twig_Node_Deprecated($expr, $token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return 'deprecated';
    }
}

//class_alias('IfwPsn_Vendor_Twig_TokenParser_Deprecated', 'Twig\TokenParser\DeprecatedTokenParser', false);
