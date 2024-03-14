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
interface ProviderInterface
{
    public function __toString() : string;
    /**
     * Translations available in the TranslatorBag only must be created.
     * Translations available in both the TranslatorBag and on the provider
     * must be overwritten.
     * Translations available on the provider only must be kept.
     */
    public function write(\WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface $translatorBag) : void;
    public function read(array $domains, array $locales) : \WPPayVendor\Symfony\Component\Translation\TranslatorBag;
    public function delete(\WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface $translatorBag) : void;
}
