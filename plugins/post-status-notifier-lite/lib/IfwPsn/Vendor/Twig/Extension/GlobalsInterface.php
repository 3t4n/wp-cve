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
 * Enables usage of the deprecated IfwPsn_Vendor_Twig_Extension::getGlobals() method.
 *
 * Explicitly implement this interface if you really need to implement the
 * deprecated getGlobals() method in your extensions.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface IfwPsn_Vendor_Twig_Extension_GlobalsInterface
{
}

//class_alias('IfwPsn_Vendor_Twig_Extension_GlobalsInterface', 'Twig\Extension\GlobalsInterface', false);
