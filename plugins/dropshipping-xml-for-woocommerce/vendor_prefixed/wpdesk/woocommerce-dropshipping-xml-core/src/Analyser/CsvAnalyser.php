<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
/**
 * Class CsvAnalyser, analyse csv file.
 * @package WPDesk\Library\DropshippingXmlCore\Analyser
 */
class CsvAnalyser
{
    const CAPTURE_MEMORY_LIMIT_IN_KB = 1;
    const FILTER_SOURCE_ENCODING = 'wpdesk_dropshipping_source_encoding';
    const DEFAULT_SEPARATOR = ['semicolon' => ';', 'comma' => ',', 'tab' => "\t", 'pipe' => '|', 'colon' => ':'];
    public function resolve_separator(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file) : string
    {
        $content = $this->get_piece_of_content($source_file);
        $separator_result = [];
        foreach (self::DEFAULT_SEPARATOR as $separator_key => $separator) {
            $separator_result[$separator_key] = \substr_count($content, $separator);
        }
        \asort($separator_result);
        unset($content);
        \end($separator_result);
        return self::DEFAULT_SEPARATOR[\key($separator_result)];
    }
    public function resolve_source_encoding(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file) : string
    {
        $result = '';
        $string = $this->get_piece_of_content($source_file);
        if (\function_exists('mb_detect_encoding') && \function_exists('mb_check_encoding')) {
            $current_encoding = \mb_detect_encoding($string);
            $result = !empty($current_encoding) ? $current_encoding : '';
        }
        $result = \apply_filters(self::FILTER_SOURCE_ENCODING, $result, $source_file);
        return $result;
    }
    private function get_piece_of_content(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file) : string
    {
        $fh = \fopen($source_file->getRealPath(), 'r');
        $content = \fread($fh, self::CAPTURE_MEMORY_LIMIT_IN_KB * 1024);
        \fclose($fh);
        return $content;
    }
}
