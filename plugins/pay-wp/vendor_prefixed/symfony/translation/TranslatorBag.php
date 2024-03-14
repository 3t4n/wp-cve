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

use WPPayVendor\Symfony\Component\Translation\Catalogue\AbstractOperation;
use WPPayVendor\Symfony\Component\Translation\Catalogue\TargetOperation;
final class TranslatorBag implements \WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface
{
    /** @var MessageCatalogue[] */
    private $catalogues = [];
    public function addCatalogue(\WPPayVendor\Symfony\Component\Translation\MessageCatalogue $catalogue) : void
    {
        if (null !== ($existingCatalogue = $this->getCatalogue($catalogue->getLocale()))) {
            $catalogue->addCatalogue($existingCatalogue);
        }
        $this->catalogues[$catalogue->getLocale()] = $catalogue;
    }
    public function addBag(\WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface $bag) : void
    {
        foreach ($bag->getCatalogues() as $catalogue) {
            $this->addCatalogue($catalogue);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getCatalogue(?string $locale = null) : \WPPayVendor\Symfony\Component\Translation\MessageCatalogueInterface
    {
        if (null === $locale || !isset($this->catalogues[$locale])) {
            $this->catalogues[$locale] = new \WPPayVendor\Symfony\Component\Translation\MessageCatalogue($locale);
        }
        return $this->catalogues[$locale];
    }
    /**
     * {@inheritdoc}
     */
    public function getCatalogues() : array
    {
        return \array_values($this->catalogues);
    }
    public function diff(\WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface $diffBag) : self
    {
        $diff = new self();
        foreach ($this->catalogues as $locale => $catalogue) {
            if (null === ($diffCatalogue = $diffBag->getCatalogue($locale))) {
                $diff->addCatalogue($catalogue);
                continue;
            }
            $operation = new \WPPayVendor\Symfony\Component\Translation\Catalogue\TargetOperation($diffCatalogue, $catalogue);
            $operation->moveMessagesToIntlDomainsIfPossible(\WPPayVendor\Symfony\Component\Translation\Catalogue\AbstractOperation::NEW_BATCH);
            $newCatalogue = new \WPPayVendor\Symfony\Component\Translation\MessageCatalogue($locale);
            foreach ($catalogue->getDomains() as $domain) {
                $newCatalogue->add($operation->getNewMessages($domain), $domain);
            }
            $diff->addCatalogue($newCatalogue);
        }
        return $diff;
    }
    public function intersect(\WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface $intersectBag) : self
    {
        $diff = new self();
        foreach ($this->catalogues as $locale => $catalogue) {
            if (null === ($intersectCatalogue = $intersectBag->getCatalogue($locale))) {
                continue;
            }
            $operation = new \WPPayVendor\Symfony\Component\Translation\Catalogue\TargetOperation($catalogue, $intersectCatalogue);
            $operation->moveMessagesToIntlDomainsIfPossible(\WPPayVendor\Symfony\Component\Translation\Catalogue\AbstractOperation::OBSOLETE_BATCH);
            $obsoleteCatalogue = new \WPPayVendor\Symfony\Component\Translation\MessageCatalogue($locale);
            foreach ($operation->getDomains() as $domain) {
                $obsoleteCatalogue->add(\array_diff($operation->getMessages($domain), $operation->getNewMessages($domain)), $domain);
            }
            $diff->addCatalogue($obsoleteCatalogue);
        }
        return $diff;
    }
}
