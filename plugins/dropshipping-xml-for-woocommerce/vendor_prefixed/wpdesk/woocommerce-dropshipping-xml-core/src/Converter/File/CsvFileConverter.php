<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use XMLWriter;
/**
 * Class CsvFileConverter, csv file converter.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Converter\File
 */
class CsvFileConverter implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\FileConverterInterface
{
    const FILTER_ENCODE_STRING = 'wpdesk_dropshipping_csv_encode_string';
    const FILTER_COLUMN_NAME = 'wpdesk_dropshipping_csv_column_name';
    const DEST_FILE_ENCODING = 'UTF-8';
    const SOURCE_FILE_ENCODING = 'WINDOWS-1250';
    const DEST_FILE_VERSION = '1.0';
    const CSV_LINE_LENGTH = 9999;
    const DEFAULT_COLUMN_PREFIX = 'column_';
    const DEFAULT_SEPARATOR = ',';
    const MAX_HEADER_LENGTH = 48;
    /**
     *
     * @var array
     */
    private $parameters = [];
    public function set_parameters(array $parameters)
    {
        $this->parameters = $parameters;
    }
    public function convert(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file, string $save_location) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject
    {
        return $this->convert_csv_to_xml($source_file, $save_location);
    }
    private function convert_csv_to_xml(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file, string $save_location) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject
    {
        $xml_writer = $this->init_xml_writer($save_location);
        $xml_writer->startElement('data');
        $columns = [];
        $generated_elements = 0;
        if (($handle = \fopen($source_file->getRealPath(), 'r')) !== \false) {
            while (($data = \fgetcsv($handle, self::CSV_LINE_LENGTH, $this->get_separator())) !== \false) {
                $data = \array_map(function ($s) {
                    return $this->encode_string($s ?? '');
                }, $data);
                $num = \count($data);
                if ($num === 1 && empty(\trim($data[0]))) {
                    continue;
                } else {
                    if (empty($columns)) {
                        if ($this->has_header_columns($data) && $num > 1) {
                            $columns = $this->encode_header_columns($data);
                            continue;
                        } else {
                            $columns = $this->generate_header_columns(\count($data));
                        }
                    }
                    $this->create_node_element($xml_writer, $columns, $data);
                    $generated_elements++;
                }
            }
            \fclose($handle);
        }
        if ($generated_elements === 0) {
            $this->create_node_element($xml_writer, $columns, []);
        }
        $xml_writer->endElement();
        $xml_writer->flush(\true);
        return new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject($save_location);
    }
    private function create_node_element(\XMLWriter $xml_writer, array $columns, array $data)
    {
        $xml_writer->startElement('node');
        foreach ($data as $key => $val) {
            if (isset($columns[$key])) {
                $xml_writer->startElement($columns[$key]);
                $xml_writer->writeCData(\strval($val));
                $xml_writer->endElement();
            }
        }
        $xml_writer->endElement();
    }
    private function init_xml_writer($save_location) : \XMLWriter
    {
        $xml_writer = new \XMLWriter();
        $xml_writer->openURI($save_location);
        $xml_writer->setIndent(\true);
        $xml_writer->setIndentString("\t");
        $xml_writer->startDocument($this->get_version(), $this->get_encoding());
        return $xml_writer;
    }
    private function has_header_columns(array $columns) : bool
    {
        foreach ($columns as $value) {
            $generated_column_name = $this->encode_header_column_name($value);
            if (\strlen($generated_column_name) > self::MAX_HEADER_LENGTH || \is_numeric($generated_column_name) || \preg_match('%\\W(http:|https:|ftp:|ftps:)$%i', \strtolower($value))) {
                return \false;
            }
            try {
                new \DOMElement($generated_column_name);
            } catch (\DOMException $e) {
                return \false;
            }
        }
        return \true;
    }
    private function generate_header_columns(int $columns_count) : array
    {
        $columns = [];
        for ($i = 0; $i < $columns_count; $i++) {
            $columns[$i] = self::DEFAULT_COLUMN_PREFIX . \strval($i + 1);
        }
        return $columns;
    }
    private function encode_string(string $string) : string
    {
        $result_string = \preg_replace('#<!\\[CDATA\\[(.+?)\\]\\]>#s', '', $string);
        $result_string = \iconv($this->get_source_encoding(), $this->get_encoding() . '//IGNORE', $result_string);
        $result_string = \apply_filters(self::FILTER_ENCODE_STRING, $result_string, $string);
        return \is_string($result_string) ? $result_string : '';
    }
    private function encode_header_columns(array $columns) : array
    {
        foreach ($columns as $key => $value) {
            $columns[$key] = $this->encode_header_column_name(\strval($value));
        }
        return $columns;
    }
    private function encode_header_column_name(string $raw_string) : string
    {
        $string = \trim($raw_string);
        $string = \preg_replace('~[^\\pL\\d]+~u', '-', $string);
        $string = \sanitize_title($string);
        $string = \str_replace('-', '_', $string);
        $string = \preg_replace('~[^-\\w]+~', '', $string);
        $string = \trim($string, '-');
        $string = \preg_replace('~-+~', '-', $string);
        $string = \apply_filters(self::FILTER_COLUMN_NAME, $string, $raw_string);
        return $string;
    }
    private function get_encoding() : string
    {
        return isset($this->parameters['encoding']) && \is_string($this->parameters['encoding']) && !empty($this->parameters['encoding']) ? $this->parameters['encoding'] : self::DEST_FILE_ENCODING;
    }
    private function get_source_encoding() : string
    {
        return isset($this->parameters['source_encoding']) && \is_string($this->parameters['source_encoding']) && !empty($this->parameters['source_encoding']) ? $this->parameters['source_encoding'] : self::SOURCE_FILE_ENCODING;
    }
    private function get_version() : string
    {
        return isset($this->parameters['version']) && \is_string($this->parameters['version']) && !empty($this->parameters['version']) ? $this->parameters['version'] : self::DEST_FILE_VERSION;
    }
    private function get_separator() : string
    {
        return isset($this->parameters['separator']) && \is_string($this->parameters['separator']) && !empty($this->parameters['separator']) ? $this->parameters['separator'] : self::DEFAULT_SEPARATOR;
    }
    public static function get_supported_data_format() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::CSV;
    }
}
