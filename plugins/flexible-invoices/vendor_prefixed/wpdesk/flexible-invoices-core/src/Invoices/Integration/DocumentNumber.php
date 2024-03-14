<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
/**
 * This class define document number for each document types.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class DocumentNumber
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $prefix;
    /**
     * @var string
     */
    private $suffix;
    /**
     * @var int
     */
    private $document_number;
    /**
     * @var string
     */
    private $issue_date;
    /**
     * @var Document
     */
    private $document;
    /**
     * @var int
     */
    private $current_time;
    /**
     * @param Settings $settings
     * @param Document $document
     * @param string   $name
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, string $name = 'Document')
    {
        $this->settings = $settings;
        $this->document = $document;
        $this->type = $document->get_type();
        $this->issue_date = $document->get_date_of_issue();
        $this->prefix = $settings->get($this->type . '_number_prefix', $name . ' ');
        $this->suffix = $settings->get($this->type . '_number_suffix', '/{MM}/{YYYY}');
        $this->current_time = \strtotime(\current_time('mysql'));
        $this->document_number = $this->get_document_number();
    }
    /**
     * @return int
     */
    private function get_number_from_option() : int
    {
        global $wpdb;
        // phpcs:disable
        $number = (int) $wpdb->get_var($wpdb->prepare("SELECT `option_value` FROM {$wpdb->options} WHERE `option_name` = '%s' ", 'inspire_invoices_' . $this->type . '_start_number'));
        // phpcs:enable
        if (!$number) {
            return 1;
        }
        return $number;
    }
    /**
     * @param int $value
     */
    private function update_number(int $value)
    {
        global $wpdb;
        // phpcs:disable
        $wpdb->update($wpdb->options, array('option_value' => $value), array('option_name' => 'inspire_invoices_' . $this->type . '_start_number'));
        // phpcs:enable
    }
    /**
     * @return int
     */
    private function get_document_number() : int
    {
        $number_reset_type = $this->settings->get($this->type . '_number_reset_type', 'year');
        $number_reset_time = (int) $this->settings->get($this->type . '_start_number_timestamp', $this->current_time);
        $reset_number = \false;
        if ($number_reset_type === 'month' && \date('m.Y', $this->issue_date) !== \date('m.Y', $number_reset_time)) {
            $reset_number = \true;
        }
        if ($number_reset_type === 'year' && \date('Y', $this->issue_date) !== \date('Y', $number_reset_time)) {
            $reset_number = \true;
        }
        if ($reset_number) {
            return 1;
        }
        return $this->get_number_from_option();
    }
    /**
     * @return string
     */
    public function get_formatted_number() : string
    {
        $number_array = [\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::translate_meta('inspire_invoices_' . $this->type . '_number_prefix', $this->prefix), $this->document_number, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::translate_meta('inspire_invoices_' . $this->type . '_number_suffix', $this->suffix)];
        foreach ($number_array as &$value) {
            $value = \str_replace(['{DD}', '{MM}', '{YYYY}', '{AAAA}'], [\date('d', $this->issue_date), \date('m', $this->issue_date), \date('Y', $this->issue_date), \date('Y', $this->issue_date)], $value);
        }
        unset($value);
        /**
         * Filters the numbering for the document.
         *
         * @param array    $number_array Array of data for numbering that will be imploded.
         * @param Document $document     Document Object.
         *
         * @return array
         *
         * @since 3.0.0
         */
        $number_array = \apply_filters('fi/core/numbering/formatted_number', $number_array, $this->document);
        return \implode('', $number_array);
    }
    /**
     * @return int
     */
    public function get_number() : int
    {
        return $this->document_number;
    }
    /**
     * @return void
     */
    public function increase_number()
    {
        $number = $this->document_number;
        $number++;
        $this->update_number($number);
        $this->settings->set($this->type . '_start_number_timestamp', $this->current_time);
    }
}
