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
abstract class IfwPsn_Vendor_Twig_Node_Expression_Binary extends IfwPsn_Vendor_Twig_Node_Expression
{
    public function __construct(IfwPsn_Vendor_Twig_NodeInterface $left, IfwPsn_Vendor_Twig_NodeInterface $right, $lineno)
    {
        parent::__construct(['left' => $left, 'right' => $right], [], $lineno);
    }

    public function compile(IfwPsn_Vendor_Twig_Compiler $compiler)
    {
        $compiler
            ->raw('(')
            ->subcompile($this->getNode('left'))
            ->raw(' ')
        ;
        $this->operator($compiler);
        $compiler
            ->raw(' ')
            ->subcompile($this->getNode('right'))
            ->raw(')')
        ;
    }

    abstract public function operator(IfwPsn_Vendor_Twig_Compiler $compiler);
}

//class_alias('IfwPsn_Vendor_Twig_Node_Expression_Binary', 'Twig\Node\Expression\Binary\AbstractBinary', false);
