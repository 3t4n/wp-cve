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
 * Remove whitespaces between HTML tags.
 *
 * <pre>
 * {% spaceless %}
 *      <div>
 *          <strong>foo</strong>
 *      </div>
 * {% endspaceless %}
 *
 * {# output will be <div><strong>foo</strong></div> #}
 * </pre>
 *
 * @final
 */
class IfwPsn_Vendor_Twig_TokenParser_Spaceless extends IfwPsn_Vendor_Twig_TokenParser
{
    public function parse(IfwPsn_Vendor_Twig_Token $token)
    {
        $lineno = $token->getLine();

        $this->parser->getStream()->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideSpacelessEnd'], true);
        $this->parser->getStream()->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);

        return new IfwPsn_Vendor_Twig_Node_Spaceless($body, $lineno, $this->getTag());
    }

    public function decideSpacelessEnd(IfwPsn_Vendor_Twig_Token $token)
    {
        return $token->test('endspaceless');
    }

    public function getTag()
    {
        return 'spaceless';
    }
}

//class_alias('IfwPsn_Vendor_Twig_TokenParser_Spaceless', 'Twig\TokenParser\SpacelessTokenParser', false);
