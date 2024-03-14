<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Formatter;

use WPPayVendor\Symfony\Component\Translation\IdentityTranslator;
use WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface;
// Help opcache.preload discover always-needed symbols
\class_exists(\WPPayVendor\Symfony\Component\Translation\Formatter\IntlFormatter::class);
/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
class MessageFormatter implements \WPPayVendor\Symfony\Component\Translation\Formatter\MessageFormatterInterface, \WPPayVendor\Symfony\Component\Translation\Formatter\IntlFormatterInterface
{
    private $translator;
    private $intlFormatter;
    /**
     * @param TranslatorInterface|null $translator An identity translator to use as selector for pluralization
     */
    public function __construct(?\WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface $translator = null, ?\WPPayVendor\Symfony\Component\Translation\Formatter\IntlFormatterInterface $intlFormatter = null)
    {
        $this->translator = $translator ?? new \WPPayVendor\Symfony\Component\Translation\IdentityTranslator();
        $this->intlFormatter = $intlFormatter ?? new \WPPayVendor\Symfony\Component\Translation\Formatter\IntlFormatter();
    }
    /**
     * {@inheritdoc}
     */
    public function format(string $message, string $locale, array $parameters = [])
    {
        if ($this->translator instanceof \WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface) {
            return $this->translator->trans($message, $parameters, null, $locale);
        }
        return \strtr($message, $parameters);
    }
    /**
     * {@inheritdoc}
     */
    public function formatIntl(string $message, string $locale, array $parameters = []) : string
    {
        return $this->intlFormatter->formatIntl($message, $locale, $parameters);
    }
}
