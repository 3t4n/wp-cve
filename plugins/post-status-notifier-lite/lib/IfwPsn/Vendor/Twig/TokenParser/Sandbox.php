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
 * Marks a section of a template as untrusted code that must be evaluated in the sandbox mode.
 *
 * <pre>
 * {% sandbox %}
 *     {% include 'user.html' %}
 * {% endsandbox %}
 * </pre>
 *
 * @see https://twig.symfony.com/doc/api.html#sandbox-extension for details
 *
 * @final
 */
class IfwPsn_Vendor_Twig_TokenParser_Sandbox extends IfwPsn_Vendor_Twig_TokenParser
{
    public function parse(IfwPsn_Vendor_Twig_Token $token)
    {
        $stream = $this->parser->getStream();
        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideBlockEnd'], true);
        $stream->expect(IfwPsn_Vendor_Twig_Token::BLOCK_END_TYPE);

        // in a sandbox tag, only include tags are allowed
        if (!$body instanceof IfwPsn_Vendor_Twig_Node_Include) {
            foreach ($body as $node) {
                if ($node instanceof IfwPsn_Vendor_Twig_Node_Text && ctype_space($node->getAttribute('data'))) {
                    continue;
                }

                if (!$node instanceof IfwPsn_Vendor_Twig_Node_Include) {
                    throw new IfwPsn_Vendor_Twig_Error_Syntax('Only "include" tags are allowed within a "sandbox" section.', $node->getTemplateLine(), $stream->getSourceContext());
                }
            }
        }

        return new IfwPsn_Vendor_Twig_Node_Sandbox($body, $token->getLine(), $this->getTag());
    }

    public function decideBlockEnd(IfwPsn_Vendor_Twig_Token $token)
    {
        return $token->test('endsandbox');
    }

    public function getTag()
    {
        return 'sandbox';
    }
}

//class_alias('IfwPsn_Vendor_Twig_TokenParser_Sandbox', 'Twig\TokenParser\SandboxTokenParser', false);
