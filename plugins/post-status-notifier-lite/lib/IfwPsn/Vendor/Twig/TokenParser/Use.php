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
 * Imports blocks defined in another template into the current template.
 *
 * <pre>
 * {% extends "base.html" %}
 *
 * {% use "blocks.html" %}
 *
 * {% block title %}{% endblock %}
 * {% block content %}{% endblock %}
 * </pre>
 *
 * @see https://twig.symfony.com/doc/templates.html#horizontal-reuse for details.
 *
 * @final
 */
class IfwPsn_Vendor_Twig_TokenParser_Use extends IfwPsn_Vendor_Twig_TokenParser
{
    public function parse(IfwPsn_Vendor_Twig_Token $token)
    {
        $template = $this->parser->getExpressionParser()->parseExpression();
        $stream = $this->parser->getStream();

        if (!$template instanceof IfwPsn_Vendor_Twig_Node_Expression_Constant) {
            throw new IfwPsn_Vendor_Twig_Error_Syntax('The template references in a "use" statement must be a string.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
        }

        $targets = [];
        if ($stream->nextIf('with')) {
            do {
                $name = $stream->expect(IfwPsn_Vendor_Twig_Token::NAME_TYPE)->getValue();

                $alias = $name;
                if ($stream->nextIf('as')) {
                    $alias = $stream->expect(IfwPsn_Vendor_Twig_Token::NAME_TYPE)->getValue();
                }

                $targets[$name] = new IfwPsn_Vendor_Twig_Node_Expression_Constant($alias, -1);

                if (!$stream->nextIf(IfwPsn_Vendor_Twig_Token::PUNCTUATION_TYPE, ',')) {
                    break;
                }
            } while (true);
        }

        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);

        $this->parser->addTrait(new IfwPsn_Vendor_Twig_Node(['template' => $template, 'targets' => new IfwPsn_Vendor_Twig_Node($targets)]));

        return new IfwPsn_Vendor_Twig_Node();
    }

    public function getTag()
    {
        return 'use';
    }
}

//class_alias('IfwPsn_Vendor_Twig_TokenParser_Use', 'Twig\TokenParser\UseTokenParser', false);
