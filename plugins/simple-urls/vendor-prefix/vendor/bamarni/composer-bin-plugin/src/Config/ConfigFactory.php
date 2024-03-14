<?php

declare (strict_types=1);
namespace LassoLiteVendor\Bamarni\Composer\Bin\Config;

use LassoLiteVendor\Composer\Config as ComposerConfig;
use LassoLiteVendor\Composer\Factory;
use LassoLiteVendor\Composer\Json\JsonFile;
use LassoLiteVendor\Composer\Json\JsonValidationException;
use LassoLiteVendor\Seld\JsonLint\ParsingException;
final class ConfigFactory
{
    /**
     * @throws JsonValidationException
     * @throws ParsingException
     */
    public static function createConfig() : ComposerConfig
    {
        $config = Factory::createConfig();
        $file = new JsonFile(Factory::getComposerFile());
        if (!$file->exists()) {
            return $config;
        }
        $file->validateSchema(JsonFile::LAX_SCHEMA);
        $config->merge($file->read());
        return $config;
    }
    private function __construct()
    {
    }
}
