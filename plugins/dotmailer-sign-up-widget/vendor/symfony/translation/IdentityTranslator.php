<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dotdigital_WordPress_Vendor\Symfony\Component\Translation;

use Dotdigital_WordPress_Vendor\Symfony\Contracts\Translation\LocaleAwareInterface;
use Dotdigital_WordPress_Vendor\Symfony\Contracts\Translation\TranslatorInterface;
use Dotdigital_WordPress_Vendor\Symfony\Contracts\Translation\TranslatorTrait;
/**
 * IdentityTranslator does not translate anything.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IdentityTranslator implements TranslatorInterface, LocaleAwareInterface
{
    use TranslatorTrait;
}
