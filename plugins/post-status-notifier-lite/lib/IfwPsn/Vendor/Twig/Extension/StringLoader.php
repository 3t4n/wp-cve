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
 * @final
 */
class IfwPsn_Vendor_Twig_Extension_StringLoader extends IfwPsn_Vendor_Twig_Extension
{
    public function getFunctions()
    {
        return [
            new IfwPsn_Vendor_Twig_SimpleFunction('template_from_string', 'ifwpsn_twig_template_from_string', ['needs_environment' => true]),
        ];
    }

    public function getName()
    {
        return 'string_loader';
    }
}

/**
 * Loads a template from a string.
 *
 * <pre>
 * {{ include(template_from_string("Hello {{ name }}")) }}
 * </pre>
 *
 * @param IfwPsn_Vendor_Twig_Environment $env      A IfwPsn_Vendor_Twig_Environment instance
 * @param string           $template A template as a string or object implementing __toString()
 *
 * @return IfwPsn_Vendor_Twig_Template
 */
function ifwpsn_twig_template_from_string(IfwPsn_Vendor_Twig_Environment $env, $template)
{
    return $env->createTemplate((string) $template);
}

//class_alias('IfwPsn_Vendor_Twig_Extension_StringLoader', 'Twig\Extension\StringLoaderExtension', false);
