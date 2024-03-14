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
 * Represents an autoescape node.
 *
 * The value is the escaping strategy (can be html, js, ...)
 *
 * The true value is equivalent to html.
 *
 * If autoescaping is disabled, then the value is false.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IfwPsn_Vendor_Twig_Node_AutoEscape extends IfwPsn_Vendor_Twig_Node
{
    public function __construct($value, IfwPsn_Vendor_Twig_NodeInterface $body, $lineno, $tag = 'autoescape')
    {
        parent::__construct(['body' => $body], ['value' => $value], $lineno, $tag);
    }

    public function compile(IfwPsn_Vendor_Twig_Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('body'));
    }
}

//class_alias('IfwPsn_Vendor_Twig_Node_AutoEscape', 'Twig\Node\AutoEscapeNode', false);
