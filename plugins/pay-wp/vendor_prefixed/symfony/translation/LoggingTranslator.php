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

use WPPayVendor\Psr\Log\LoggerInterface;
use WPPayVendor\Symfony\Component\Translation\Exception\InvalidArgumentException;
use WPPayVendor\Symfony\Contracts\Translation\LocaleAwareInterface;
use WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
class LoggingTranslator implements \WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface, \WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface, \WPPayVendor\Symfony\Contracts\Translation\LocaleAwareInterface
{
    private $translator;
    private $logger;
    /**
     * @param TranslatorInterface&TranslatorBagInterface&LocaleAwareInterface $translator The translator must implement TranslatorBagInterface
     */
    public function __construct(\WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface $translator, \WPPayVendor\Psr\Log\LoggerInterface $logger)
    {
        if (!$translator instanceof \WPPayVendor\Symfony\Component\Translation\TranslatorBagInterface || !$translator instanceof \WPPayVendor\Symfony\Contracts\Translation\LocaleAwareInterface) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidArgumentException(\sprintf('The Translator "%s" must implement TranslatorInterface, TranslatorBagInterface and LocaleAwareInterface.', \get_debug_type($translator)));
        }
        $this->translator = $translator;
        $this->logger = $logger;
    }
    /**
     * {@inheritdoc}
     */
    public function trans(?string $id, array $parameters = [], ?string $domain = null, ?string $locale = null)
    {
        $trans = $this->translator->trans($id = (string) $id, $parameters, $domain, $locale);
        $this->log($id, $domain, $locale);
        return $trans;
    }
    /**
     * {@inheritdoc}
     */
    public function setLocale(string $locale)
    {
        $prev = $this->translator->getLocale();
        $this->translator->setLocale($locale);
        if ($prev === $locale) {
            return;
        }
        $this->logger->debug(\sprintf('The locale of the translator has changed from "%s" to "%s".', $prev, $locale));
    }
    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->translator->getLocale();
    }
    /**
     * {@inheritdoc}
     */
    public function getCatalogue(?string $locale = null)
    {
        return $this->translator->getCatalogue($locale);
    }
    /**
     * {@inheritdoc}
     */
    public function getCatalogues() : array
    {
        return $this->translator->getCatalogues();
    }
    /**
     * Gets the fallback locales.
     *
     * @return array
     */
    public function getFallbackLocales()
    {
        if ($this->translator instanceof \WPPayVendor\Symfony\Component\Translation\Translator || \method_exists($this->translator, 'getFallbackLocales')) {
            return $this->translator->getFallbackLocales();
        }
        return [];
    }
    /**
     * Passes through all unknown calls onto the translator object.
     */
    public function __call(string $method, array $args)
    {
        return $this->translator->{$method}(...$args);
    }
    /**
     * Logs for missing translations.
     */
    private function log(string $id, ?string $domain, ?string $locale)
    {
        if (null === $domain) {
            $domain = 'messages';
        }
        $catalogue = $this->translator->getCatalogue($locale);
        if ($catalogue->defines($id, $domain)) {
            return;
        }
        if ($catalogue->has($id, $domain)) {
            $this->logger->debug('Translation use fallback catalogue.', ['id' => $id, 'domain' => $domain, 'locale' => $catalogue->getLocale()]);
        } else {
            $this->logger->warning('Translation not found.', ['id' => $id, 'domain' => $domain, 'locale' => $catalogue->getLocale()]);
        }
    }
}
