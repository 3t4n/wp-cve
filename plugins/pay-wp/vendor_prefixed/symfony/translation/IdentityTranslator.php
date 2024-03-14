<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation;

use WPPayVendor\Symfony\Contracts\Translation\LocaleAwareInterface;
use WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface;
use WPPayVendor\Symfony\Contracts\Translation\TranslatorTrait;
/**
 * IdentityTranslator does not translate anything.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IdentityTranslator implements \WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface, \WPPayVendor\Symfony\Contracts\Translation\LocaleAwareInterface
{
    use TranslatorTrait;
}
