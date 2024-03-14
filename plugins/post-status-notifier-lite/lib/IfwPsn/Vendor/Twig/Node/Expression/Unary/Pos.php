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
class IfwPsn_Vendor_Twig_Node_Expression_Unary_Pos extends IfwPsn_Vendor_Twig_Node_Expression_Unary
{
    public function operator(IfwPsn_Vendor_Twig_Compiler $compiler)
    {
        $compiler->raw('+');
    }
}

//class_alias('IfwPsn_Vendor_Twig_Node_Expression_Unary_Pos', 'Twig\Node\Expression\Unary\PosUnary', false);
