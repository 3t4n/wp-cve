<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Dumper;

use WPPayVendor\Symfony\Component\Translation\Exception\LogicException;
use WPPayVendor\Symfony\Component\Translation\MessageCatalogue;
use WPPayVendor\Symfony\Component\Translation\Util\ArrayConverter;
use WPPayVendor\Symfony\Component\Yaml\Yaml;
/**
 * YamlFileDumper generates yaml files from a message catalogue.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class YamlFileDumper extends \WPPayVendor\Symfony\Component\Translation\Dumper\FileDumper
{
    private $extension;
    public function __construct(string $extension = 'yml')
    {
        $this->extension = $extension;
    }
    /**
     * {@inheritdoc}
     */
    public function formatCatalogue(\WPPayVendor\Symfony\Component\Translation\MessageCatalogue $messages, string $domain, array $options = [])
    {
        if (!\class_exists(\WPPayVendor\Symfony\Component\Yaml\Yaml::class)) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\LogicException('Dumping translations in the YAML format requires the Symfony Yaml component.');
        }
        $data = $messages->all($domain);
        if (isset($options['as_tree']) && $options['as_tree']) {
            $data = \WPPayVendor\Symfony\Component\Translation\Util\ArrayConverter::expandToTree($data);
        }
        if (isset($options['inline']) && ($inline = (int) $options['inline']) > 0) {
            return \WPPayVendor\Symfony\Component\Yaml\Yaml::dump($data, $inline);
        }
        return \WPPayVendor\Symfony\Component\Yaml\Yaml::dump($data);
    }
    /**
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return $this->extension;
    }
}
