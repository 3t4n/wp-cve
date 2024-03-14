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
 * Interface implemented by lexer classes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @deprecated since 1.12 (to be removed in 3.0)
 */
interface IfwPsn_Vendor_Twig_LexerInterface
{
    /**
     * Tokenizes a source code.
     *
     * @param string|IfwPsn_Vendor_Twig_Source $code The source code
     * @param string             $name A unique identifier for the source code
     *
     * @return IfwPsn_Vendor_Twig_TokenStream
     *
     * @throws IfwPsn_Vendor_Twig_Error_Syntax When the code is syntactically wrong
     */
    public function tokenize($code, $name = null);
}
