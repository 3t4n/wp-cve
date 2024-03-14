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
/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
abstract class FileLoader extends \WPPayVendor\Symfony\Component\Translation\Loader\ArrayLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($resource, string $locale, string $domain = 'messages')
    {
        if (!\stream_is_local($resource)) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException(\sprintf('This is not a local file "%s".', $resource));
        }
        if (!\file_exists($resource)) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\NotFoundResourceException(\sprintf('File "%s" not found.', $resource));
        }
        $messages = $this->loadResource($resource);
        // empty resource
        if (null === $messages) {
            $messages = [];
        }
        // not an array
        if (!\is_array($messages)) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException(\sprintf('Unable to load file "%s".', $resource));
        }
        $catalogue = parent::load($messages, $locale, $domain);
        if (\class_exists(\WPPayVendor\Symfony\Component\Config\Resource\FileResource::class)) {
            $catalogue->addResource(new \WPPayVendor\Symfony\Component\Config\Resource\FileResource($resource));
        }
        return $catalogue;
    }
    /**
     * @return array
     *
     * @throws InvalidResourceException if stream content has an invalid format
     */
    protected abstract function loadResource(string $resource);
}
