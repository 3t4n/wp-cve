<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Command;

use WPPayVendor\Symfony\Component\Translation\MessageCatalogue;
use WPPayVendor\Symfony\Component\Translation\MessageCatalogueInterface;
use WPPayVendor\Symfony\Component\Translation\TranslatorBag;
/**
 * @internal
 */
trait TranslationTrait
{
    private function readLocalTranslations(array $locales, array $domains, array $transPaths) : \WPPayVendor\Symfony\Component\Translation\TranslatorBag
    {
        $bag = new \WPPayVendor\Symfony\Component\Translation\TranslatorBag();
        foreach ($locales as $locale) {
            $catalogue = new \WPPayVendor\Symfony\Component\Translation\MessageCatalogue($locale);
            foreach ($transPaths as $path) {
                $this->reader->read($path, $catalogue);
            }
            if ($domains) {
                foreach ($domains as $domain) {
                    $bag->addCatalogue($this->filterCatalogue($catalogue, $domain));
                }
            } else {
                $bag->addCatalogue($catalogue);
            }
        }
        return $bag;
    }
    private function filterCatalogue(\WPPayVendor\Symfony\Component\Translation\MessageCatalogue $catalogue, string $domain) : \WPPayVendor\Symfony\Component\Translation\MessageCatalogue
    {
        $filteredCatalogue = new \WPPayVendor\Symfony\Component\Translation\MessageCatalogue($catalogue->getLocale());
        // extract intl-icu messages only
        $intlDomain = $domain . \WPPayVendor\Symfony\Component\Translation\MessageCatalogueInterface::INTL_DOMAIN_SUFFIX;
        if ($intlMessages = $catalogue->all($intlDomain)) {
            $filteredCatalogue->add($intlMessages, $intlDomain);
        }
        // extract all messages and subtract intl-icu messages
        if ($messages = \array_diff($catalogue->all($domain), $intlMessages)) {
            $filteredCatalogue->add($messages, $domain);
        }
        foreach ($catalogue->getResources() as $resource) {
            $filteredCatalogue->addResource($resource);
        }
        if ($metadata = $catalogue->getMetadata('', $intlDomain)) {
            foreach ($metadata as $k => $v) {
                $filteredCatalogue->setMetadata($k, $v, $intlDomain);
            }
        }
        if ($metadata = $catalogue->getMetadata('', $domain)) {
            foreach ($metadata as $k => $v) {
                $filteredCatalogue->setMetadata($k, $v, $domain);
            }
        }
        return $filteredCatalogue;
    }
}
