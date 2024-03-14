<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
/**
 * Interface ImportMapperServiceInterface
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper
 */
interface ImportMapperServiceInterface
{
    public function get_mapped_content(string $string) : string;
    public function map(string $key, string $group = '');
    public function has_value_to_map(string $key, string $group = '') : bool;
    public function has_option_value_to_map(string $key, string $group = '') : bool;
    /**
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @return void
     */
    public function set_mapped_value(string $key, $value, string $group = '');
    /**
     * Get raw value from ImportMapperDataProvider
     *
     * @param string $key
     * @param string $group
     * @return mixed
     */
    public function get_raw_value(string $key, string $group = '');
    /**
     * Get raw value from ImportOptionDataProvider
     *
     * @param string $key
     * @param string $group
     * @return mixed
     */
    public function get_raw_option_value(string $key, string $group = '');
    public function get_xpath_from_content(string $string) : string;
    public function set_analysers(array $analysers);
    public function add_analyser(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $analyser, bool $add_as_first = \false);
    public function get_analysers() : array;
}
