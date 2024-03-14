<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

@trigger_error('The IfwPsn_Vendor_Twig_Test class is deprecated since version 1.12 and will be removed in 2.0. Use IfwPsn_Vendor_Twig_SimpleTest instead.', E_USER_DEPRECATED);

/**
 * Represents a template test.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @deprecated since 1.12 (to be removed in 2.0)
 */
abstract class IfwPsn_Vendor_Twig_Test implements IfwPsn_Vendor_Twig_TestInterface, IfwPsn_Vendor_Twig_TestCallableInterface
{
    protected $options;
    protected $arguments = [];

    public function __construct(array $options = [])
    {
        $this->options = array_merge([
            'callable' => null,
        ], $options);
    }

    public function getCallable()
    {
        return $this->options['callable'];
    }
}
