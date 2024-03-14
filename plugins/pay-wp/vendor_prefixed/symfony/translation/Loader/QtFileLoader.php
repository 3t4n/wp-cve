<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Loader;

use WPPayVendor\Symfony\Component\Config\Resource\FileResource;
use WPPayVendor\Symfony\Component\Config\Util\XmlUtils;
use WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException;
use WPPayVendor\Symfony\Component\Translation\Exception\NotFoundResourceException;
use WPPayVendor\Symfony\Component\Translation\Exception\RuntimeException;
use WPPayVendor\Symfony\Component\Translation\MessageCatalogue;
/**
 * QtFileLoader loads translations from QT Translations XML files.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
class QtFileLoader implements \WPPayVendor\Symfony\Component\Translation\Loader\LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load($resource, string $locale, string $domain = 'messages')
    {
        if (!\class_exists(\WPPayVendor\Symfony\Component\Config\Util\XmlUtils::class)) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\RuntimeException('Loading translations from the QT format requires the Symfony Config component.');
        }
        if (!\stream_is_local($resource)) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException(\sprintf('This is not a local file "%s".', $resource));
        }
        if (!\file_exists($resource)) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\NotFoundResourceException(\sprintf('File "%s" not found.', $resource));
        }
        try {
            $dom = \WPPayVendor\Symfony\Component\Config\Util\XmlUtils::loadFile($resource);
        } catch (\InvalidArgumentException $e) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException(\sprintf('Unable to load "%s".', $resource), $e->getCode(), $e);
        }
        $internalErrors = \libxml_use_internal_errors(\true);
        \libxml_clear_errors();
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->evaluate('//TS/context/name[text()="' . $domain . '"]');
        $catalogue = new \WPPayVendor\Symfony\Component\Translation\MessageCatalogue($locale);
        if (1 == $nodes->length) {
            $translations = $nodes->item(0)->nextSibling->parentNode->parentNode->getElementsByTagName('message');
            foreach ($translations as $translation) {
                $translationValue = (string) $translation->getElementsByTagName('translation')->item(0)->nodeValue;
                if (!empty($translationValue)) {
                    $catalogue->set((string) $translation->getElementsByTagName('source')->item(0)->nodeValue, $translationValue, $domain);
                }
                $translation = $translation->nextSibling;
            }
            if (\class_exists(\WPPayVendor\Symfony\Component\Config\Resource\FileResource::class)) {
                $catalogue->addResource(new \WPPayVendor\Symfony\Component\Config\Resource\FileResource($resource));
            }
        }
        \libxml_use_internal_errors($internalErrors);
        return $catalogue;
    }
}
