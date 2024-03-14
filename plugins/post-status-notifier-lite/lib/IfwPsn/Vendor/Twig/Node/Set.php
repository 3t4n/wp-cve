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
 * Represents a set node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IfwPsn_Vendor_Twig_Node_Set extends IfwPsn_Vendor_Twig_Node implements IfwPsn_Vendor_Twig_NodeCaptureInterface
{
    public function __construct($capture, IfwPsn_Vendor_Twig_NodeInterface $names, IfwPsn_Vendor_Twig_NodeInterface $values, $lineno, $tag = null)
    {
        parent::__construct(['names' => $names, 'values' => $values], ['capture' => $capture, 'safe' => false], $lineno, $tag);

        /*
         * Optimizes the node when capture is used for a large block of text.
         *
         * {% set foo %}foo{% endset %} is compiled to $context['foo'] = new IfwPsn_Vendor_Twig_Markup("foo");
         */
        if ($this->getAttribute('capture')) {
            $this->setAttribute('safe', true);

            $values = $this->getNode('values');
            if ($values instanceof IfwPsn_Vendor_Twig_Node_Text) {
                $this->setNode('values', new IfwPsn_Vendor_Twig_Node_Expression_Constant($values->getAttribute('data'), $values->getTemplateLine()));
                $this->setAttribute('capture', false);
            }
        }
    }

    public function compile(IfwPsn_Vendor_Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        if (count($this->getNode('names')) > 1) {
            $compiler->write('list(');
            foreach ($this->getNode('names') as $idx => $node) {
                if ($idx) {
                    $compiler->raw(', ');
                }

                $compiler->subcompile($node);
            }
            $compiler->raw(')');
        } else {
            if ($this->getAttribute('capture')) {
                $compiler
                    ->write("ob_start();\n")
                    ->subcompile($this->getNode('values'))
                ;
            }

            $compiler->subcompile($this->getNode('names'), false);

            if ($this->getAttribute('capture')) {
                $compiler->raw(" = ('' === \$tmp = ob_get_clean()) ? '' : new IfwPsn_Vendor_Twig_Markup(\$tmp, \$this->env->getCharset())");
            }
        }

        if (!$this->getAttribute('capture')) {
            $compiler->raw(' = ');

            if (count($this->getNode('names')) > 1) {
                $compiler->write('[');
                foreach ($this->getNode('values') as $idx => $value) {
                    if ($idx) {
                        $compiler->raw(', ');
                    }

                    $compiler->subcompile($value);
                }
                $compiler->raw(']');
            } else {
                if ($this->getAttribute('safe')) {
                    $compiler
                        ->raw("('' === \$tmp = ")
                        ->subcompile($this->getNode('values'))
                        ->raw(") ? '' : new IfwPsn_Vendor_Twig_Markup(\$tmp, \$this->env->getCharset())")
                    ;
                } else {
                    $compiler->subcompile($this->getNode('values'));
                }
            }
        }

        $compiler->raw(";\n");
    }
}

//class_alias('IfwPsn_Vendor_Twig_Node_Set', 'Twig\Node\SetNode', false);
