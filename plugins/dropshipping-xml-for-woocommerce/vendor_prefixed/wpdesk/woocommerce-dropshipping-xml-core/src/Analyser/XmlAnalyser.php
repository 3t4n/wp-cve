<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use OutOfBoundsException;
use RuntimeException;
use SimpleXMLElement;
/**
 * Class XmlAnalyser, analyse xml file.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Analyser
 */
class XmlAnalyser
{
    const FILTER_NAME_MAX_ANALYSE_DEPTH = 'wpdesk_dropshipping_analyser_max_depth';
    const FILTER_NAME_ANALYSE_TAG_NAME = 'wpdesk_dropshipping_analyser_tag_name';
    const FILTER_NAME_ANALYSE_TAG_KEY = 'wpdesk_dropshipping_analyser_tag_key';
    const MAX_ANALYSE_TIME_DELAY = 5;
    const MAX_ANALYSE_TIME = 60;
    const FIND_MAX_DEPTH = 4;
    const DEFAULT_PREFIX_NAME = 'default_namespace';
    /**
     * @var array
     */
    private $analysed = [];
    /**
     * @var SimpleXMLElement
     */
    private $xml;
    /**
     *
     * @var int
     */
    private $max_depth = 0;
    public function get_max_depth() : int
    {
        if (empty($this->max_depth)) {
            $this->max_depth = \apply_filters(self::FILTER_NAME_MAX_ANALYSE_DEPTH, self::FIND_MAX_DEPTH);
        }
        return $this->max_depth;
    }
    public function __destruct()
    {
        unset($this->xml);
        unset($this->analysed);
    }
    public function load_from_file(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $file)
    {
        $this->analysed = [];
        $this->xml = \simplexml_load_file($file->getRealPath(), 'SimpleXMLElement', \LIBXML_COMPACT | \LIBXML_PARSEHUGE);
    }
    public function load_from_content(string $content)
    {
        $this->analysed = [];
        $this->xml = \simplexml_load_string($content, 'SimpleXMLElement', \LIBXML_COMPACT | \LIBXML_PARSEHUGE);
    }
    public function find_item_element() : string
    {
        $analysed = $this->get_analysed();
        $filtered = \array_map(function ($val) {
            return $val['depth'] > $this->get_max_depth() ? 0 : \pow((int) $val['count'], $this->get_max_depth() + 1 - (int) $val['depth']);
        }, $analysed);
        \asort($filtered);
        \end($filtered);
        return $analysed[\key($filtered)]['full_name'];
    }
    public function count_element(string $element_full_name) : int
    {
        $analysed = $this->get_analysed();
        $key = $this->find_element_key($element_full_name);
        return $analysed[$key]['count'];
    }
    public function has_element(string $element_full_name) : bool
    {
        try {
            $key = $this->find_element_key($element_full_name);
            return !empty($key);
        } catch (\OutOfBoundsException $e) {
            return \false;
        }
    }
    /**
     * @see XmlAnalyser::get_analysed()
     */
    public function get_all_elements() : array
    {
        return $this->get_analysed();
    }
    public function get_element_as_object(string $element, string $node) : \SimpleXMLElement
    {
        return $this->get_first_object_by_xpath('//' . $element . '[' . $node . ']');
    }
    public function get_element_as_xml($element, $node, $add_namespace = \false) : string
    {
        $dom_element = $this->get_element_as_object($element, $node);
        $namespaces = $dom_element->getDocNamespaces(\true);
        if ($add_namespace && !empty($namespaces)) {
            foreach ($namespaces as $key => $value) {
                if (!empty($key)) {
                    $dom_element->addAttribute('xmlns:xmlns:' . $key, $value);
                }
            }
        }
        $string = $dom_element->asXML();
        unset($dom_element);
        unset($namespaces);
        return $string;
    }
    public function get_content_by_xpath(string $xpath) : string
    {
        $dom_element = $this->get_first_object_by_xpath($xpath);
        $string = $dom_element->asXML();
        unset($dom_element);
        return $string;
    }
    public function get_first_object_by_xpath(string $xpath) : \SimpleXMLElement
    {
        $this->register_namespaces($this->load_xml());
        $result = $this->load_xml()->xpath($xpath);
        if (!isset($result[0])) {
            throw new \RuntimeException(\sprintf(\__('Error: element %1$s not found.', 'dropshipping-xml-for-woocommerce'), $xpath));
        }
        $res = $result[0];
        unset($result);
        return $res;
    }
    public function get_objects_by_xpath(string $xpath) : array
    {
        $this->register_namespaces($this->load_xml());
        $result = $this->load_xml()->xpath($xpath);
        return \is_array($result) ? $result : [];
    }
    public function get_value_by_xpath(string $xpath) : string
    {
        $dom_element = $this->get_first_object_by_xpath($xpath);
        $string = (string) $dom_element;
        unset($dom_element);
        return $string;
    }
    public function get_as_xml()
    {
        return $this->load_xml()->asXML();
    }
    public function count_values_by_xpath(string $xpath) : int
    {
        $dom_elements = $this->get_objects_by_xpath($xpath);
        return \count($dom_elements);
    }
    private function load_xml() : \SimpleXMLElement
    {
        if (!isset($this->xml)) {
            throw new \RuntimeException(\__('Error, load XML content first.', 'dropshipping-xml-for-woocommerce'));
        }
        if (!$this->xml instanceof \SimpleXMLElement) {
            throw new \RuntimeException(\__('The import file does not contain XML content.', 'dropshipping-xml-for-woocommerce'));
        }
        return $this->xml;
    }
    /**
     * Analyse xml file.
     *
     * @return array with in below format
     * [
     *  'node_name' => ['depth' => int $depth, 'count' => int $count]
     *  ...
     * ]
     */
    private function get_analysed() : array
    {
        if (empty($this->analysed)) {
            $this->analysed = $this->read_child_nodes($this->load_xml(), 0);
        }
        return $this->analysed;
    }
    private function get_element_path(\SimpleXMLElement $element, string $name) : string
    {
        $new_parts = [];
        $dom = \dom_import_simplexml($element);
        $path = $dom->getNodePath();
        $path = \preg_replace('/\\[[^)]+\\]/', '', $path);
        $node_path = \substr($path, 0, \strrpos($path, '/'));
        $parts = \explode('/', $node_path);
        foreach ($parts as $part) {
            if (!empty($part)) {
                $new_parts[] = $part;
            }
        }
        if (!empty($new_parts)) {
            $node_path = \implode('/', $new_parts);
        }
        return $node_path . '/' . $name;
    }
    private function read_child_nodes(\SimpleXMLElement $element, int $depth) : array
    {
        $name = $this->get_element_key($element);
        if (isset($this->analysed[$name])) {
            $this->analysed[$name]['count'] = (int) $this->analysed[$name]['count'] + 1;
        } else {
            $full_name = $this->get_element_name($element);
            $this->analysed[$name] = ['full_name' => $this->get_element_name($element), 'full_path' => $this->get_element_path($element, $full_name), 'depth' => $depth, 'count' => 1];
        }
        if ($depth < $this->get_max_depth()) {
            foreach ($element->xpath('child::*') as $child) {
                $this->read_child_nodes($child, $depth + 1);
            }
        }
        return $this->analysed;
    }
    private function find_element_key(string $element_full_name) : string
    {
        $analysed = $this->get_analysed();
        if (isset($analysed[$element_full_name])) {
            return $element_full_name;
        }
        $result = \array_filter($analysed, function ($v, $k) use($element_full_name) {
            return $v['full_name'] === $element_full_name || $v['full_path'] === $element_full_name;
        }, \ARRAY_FILTER_USE_BOTH);
        if (empty($result)) {
            throw new \OutOfBoundsException(\sprintf(\__('Error: element %1$s not found.', 'dropshipping-xml-for-woocommerce'), $element_full_name));
        }
        $keys = \array_keys($result);
        return \reset($keys);
    }
    private function get_element_key(\SimpleXMLElement $element) : string
    {
        $name = \apply_filters(self::FILTER_NAME_ANALYSE_TAG_KEY, null, $element);
        if (!empty($name)) {
            return $name;
        }
        $namespace = $element->getNamespaces();
        $name = $element->getName();
        $postfix = '';
        if (!empty($namespace)) {
            $keys = \array_keys($namespace);
            $keys = \array_map(function ($val) {
                return !empty($val) ? $val : self::DEFAULT_PREFIX_NAME;
            }, $keys);
            if (!empty($keys)) {
                $postfix = ':' . \implode(':', $keys);
            }
        }
        return $name . $postfix;
    }
    private function get_element_name(\SimpleXMLElement $element) : string
    {
        $name = \apply_filters(self::FILTER_NAME_ANALYSE_TAG_NAME, null, $element);
        if (!empty($name)) {
            return $name;
        }
        $namespace = $element->getNamespaces();
        $name = $element->getName();
        if (!empty($namespace)) {
            $keys = \array_keys($namespace);
            foreach ($keys as $key) {
                if (empty($key)) {
                    $key = self::DEFAULT_PREFIX_NAME;
                }
                try {
                    $result = $this->get_first_object_by_xpath('//' . $key . ':' . $name);
                    if ($result !== \false) {
                        unset($result);
                        return $key . ':' . $name;
                    }
                } catch (\Exception $e) {
                }
            }
        }
        return $name;
    }
    private function register_namespaces(\SimpleXMLElement $xml)
    {
        foreach ($xml->getNamespaces(\true) as $prefix => $namespace) {
            if (\strlen($prefix) === 0) {
                $prefix = self::DEFAULT_PREFIX_NAME;
            }
            $xml->registerXPathNamespace($prefix, $namespace);
        }
    }
}
