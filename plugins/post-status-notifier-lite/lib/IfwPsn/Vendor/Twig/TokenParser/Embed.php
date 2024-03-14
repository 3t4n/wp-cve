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
 * Embeds a template.
 *
 * @final
 */
class IfwPsn_Vendor_Twig_TokenParser_Embed extends IfwPsn_Vendor_Twig_TokenParser_Include
{
    public function parse(IfwPsn_Vendor_Twig_Token $token)
    {
        $stream = $this->parser->getStream();

        $parent = $this->parser->getExpressionParser()->parseExpression();

        list($variables, $only, $ignoreMissing) = $this->parseArguments();

        $parentToken = $fakeParentToken = new IfwPsn_Vendor_Twig_Token(IfwPsn_Vendor_Twig_Token::STRING_TYPE, '__parent__', $token->getLine());
        if ($parent instanceof IfwPsn_Vendor_Twig_Node_Expression_Constant) {
            $parentToken = new IfwPsn_Vendor_Twig_Token(IfwPsn_Vendor_Twig_Token::STRING_TYPE, $parent->getAttribute('value'), $token->getLine());
        } elseif ($parent instanceof IfwPsn_Vendor_Twig_Node_Expression_Name) {
            $parentToken = new IfwPsn_Vendor_Twig_Token(IfwPsn_Vendor_Twig_Token::NAME_TYPE, $parent->getAttribute('name'), $token->getLine());
        }

        // inject a fake parent to make the parent() function work
        $stream->injectTokens([
            new IfwPsn_Vendor_Twig_Token(IfwPsn_Vendor_Twig_Token::BLOCK_START_TYPE, '', $token->getLine()),
            new IfwPsn_Vendor_Twig_Token(IfwPsn_Vendor_Twig_Token::NAME_TYPE, 'extends', $token->getLine()),
            $parentToken,
            new IfwPsn_Vendor_Twig_Token(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE, '', $token->getLine()),
        ]);

        $module = $this->parser->parse($stream, [$this, 'decideBlockEnd'], true);

        // override the parent with the correct one
        if ($fakeParentToken === $parentToken) {
            $module->setNode('parent', $parent);
        }

        $this->parser->embedTemplate($module);

        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);

        return new IfwPsn_Vendor_Twig_Node_Embed($module->getTemplateName(), $module->getAttribute('index'), $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
    }

    public function decideBlockEnd(IfwPsn_Vendor_Twig_Token $token)
    {
        return $token->test('endembed');
    }

    public function getTag()
    {
        return 'embed';
    }
}

//class_alias('IfwPsn_Vendor_Twig_TokenParser_Embed', 'Twig\TokenParser\EmbedTokenParser', false);
