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
 * Defines a macro.
 *
 * <pre>
 * {% macro input(name, value, type, size) %}
 *    <input type="{{ type|default('text') }}" name="{{ name }}" value="{{ value|e }}" size="{{ size|default(20) }}" />
 * {% endmacro %}
 * </pre>
 *
 * @final
 */
class IfwPsn_Vendor_Twig_TokenParser_Macro extends IfwPsn_Vendor_Twig_TokenParser
{
    public function parse(IfwPsn_Vendor_Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(IfwPsn_Vendor_Twig_Token::NAME_TYPE)->getValue();

        $arguments = $this->parser->getExpressionParser()->parseArguments(true, true);

        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);
        $this->parser->pushLocalScope();
        $body = $this->parser->subparse([$this, 'decideBlockEnd'], true);
        if ($token = $stream->nextIf(IfwPsn_Vendor_Twig_Token::NAME_TYPE)) {
            $value = $token->getValue();

            if ($value != $name) {
                throw new IfwPsn_Vendor_Twig_Error_Syntax(sprintf('Expected endmacro for macro "%s" (but "%s" given).', $name, $value), $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        }
        $this->parser->popLocalScope();
        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);

        $this->parser->setMacro($name, new IfwPsn_Vendor_Twig_Node_Macro($name, new IfwPsn_Vendor_Twig_Node_Body([$body]), $arguments, $lineno, $this->getTag()));
    }

    public function decideBlockEnd(IfwPsn_Vendor_Twig_Token $token)
    {
        return $token->test('endmacro');
    }

    public function getTag()
    {
        return 'macro';
    }
}

//class_alias('IfwPsn_Vendor_Twig_TokenParser_Macro', 'Twig\TokenParser\MacroTokenParser', false);
