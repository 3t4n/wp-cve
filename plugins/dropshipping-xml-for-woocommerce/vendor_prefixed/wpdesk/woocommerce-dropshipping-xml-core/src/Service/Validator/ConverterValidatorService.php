<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use InvalidArgumentException;
/**
 * Class ConverterValidatorService, validator for converter service.
 * @package WPDesk\Library\DropshippingXmlCore\Service\Validator
 */
class ConverterValidatorService
{
    private $file_locator;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator)
    {
        $this->file_locator = $file_locator;
    }
    public function is_save_path_valid(string $save_location) : bool
    {
        if (!$this->file_locator->check_if_dir_exists($save_location)) {
            throw new \InvalidArgumentException('Error, file destination directory not exists.');
        }
        if ($this->file_locator->check_if_file_is_dir($save_location)) {
            throw new \InvalidArgumentException('Error, convert destination must be the file - dir is used.');
        }
        return \true;
    }
}
