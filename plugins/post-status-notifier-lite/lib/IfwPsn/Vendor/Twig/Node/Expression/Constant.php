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
class IfwPsn_Vendor_Twig_Node_Expression_Constant extends IfwPsn_Vendor_Twig_Node_Expression
{
    public function __construct($value, $lineno)
    {
        parent::__construct([], ['value' => $value], $lineno);
    }

    public function compile(IfwPsn_Vendor_Twig_Compiler $compiler)
    {
        $compiler->repr($this->getAttribute('value'));
    }
}

//class_alias('IfwPsn_Vendor_Twig_Node_Expression_Constant', 'Twig\Node\Expression\ConstantExpression', false);
