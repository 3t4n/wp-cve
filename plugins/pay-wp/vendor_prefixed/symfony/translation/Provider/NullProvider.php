<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Provider;

use WPPayVendor\Symfony\Component\Translation\TranslatorBag;
use WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface;
/**
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 */
class NullProvider implements \WPPayVendor\Symfony\Component\Translation\Provider\ProviderInterface
{
    public function __toString() : string
    {
        return 'null';
    }
    public function write(\WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface $translatorBag, bool $override = \false) : void
    {
    }
    public function read(array $domains, array $locales) : \WPPayVendor\Symfony\Component\Translation\TranslatorBag
    {
        return new \WPPayVendor\Symfony\Component\Translation\TranslatorBag();
    }
    public function delete(\WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface $translatorBag) : void
    {
    }
}
