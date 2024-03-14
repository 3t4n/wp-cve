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
use WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException;
use WPPayVendor\Symfony\Component\Translation\Exception\NotFoundResourceException;
use WPPayVendor\Symfony\Component\Translation\MessageCatalogue;
/**
 * IcuResFileLoader loads translations from a resource bundle.
 *
 * @author stealth35
 */
class IcuDatFileLoader extends \WPPayVendor\Symfony\Component\Translation\Loader\IcuResFileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($resource, string $locale, string $domain = 'messages')
    {
        if (!\stream_is_local($resource . '.dat')) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException(\sprintf('This is not a local file "%s".', $resource));
        }
        if (!\file_exists($resource . '.dat')) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\NotFoundResourceException(\sprintf('File "%s" not found.', $resource));
        }
        try {
            $rb = new \ResourceBundle($locale, $resource);
        } catch (\Exception $e) {
            $rb = null;
        }
        if (!$rb) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException(\sprintf('Cannot load resource "%s".', $resource));
        } elseif (\intl_is_failure($rb->getErrorCode())) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException($rb->getErrorMessage(), $rb->getErrorCode());
        }
        $messages = $this->flatten($rb);
        $catalogue = new \WPPayVendor\Symfony\Component\Translation\MessageCatalogue($locale);
        $catalogue->add($messages, $domain);
        if (\class_exists(\WPPayVendor\Symfony\Component\Config\Resource\FileResource::class)) {
            $catalogue->addResource(new \WPPayVendor\Symfony\Component\Config\Resource\FileResource($resource . '.dat'));
        }
        return $catalogue;
    }
}
