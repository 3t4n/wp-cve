<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Forms\Field;
use WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField;
use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\DocumentsFieldsInterface;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
/**
 * Document Settings Tab Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs
 */
final class DocumentsSettings extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    /**
     * @var array
     */
    private $form_fields = [];
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @param SettingsStrategy $strategy
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->set_sub_tab_forms();
    }
    /**
     * Set document fields form.
     */
    private function set_sub_tab_forms()
    {
        /**
         * @var DocumentsFields\DocumentsFieldsInterface[] $settings
         */
        $documents_settings = ['invoice' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\InvoicesSettingsFields($this->strategy)];
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $documents_settings['proforma'] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\ProformaSettingsFields($this->strategy);
            $documents_settings['correction'] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\CorrectionsSettingsFields();
        }
        /**
         * Definitions of settings for Documents tab.
         *
         * @param DocumentsFieldsInterface[] $documents_settings Documents settings tab.
         *
         * @return array
         *
         * @since 1.2.0
         */
        $settings = (array) \apply_filters('fi/core/settings/tabs/documents', $documents_settings);
        foreach ($settings as $setting) {
            if ($setting instanceof \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\DocumentsFieldsInterface) {
                $this->form_fields[$setting::get_tab_slug()] = $setting->get_fields();
            }
        }
        $fields = [(new \WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm::NONCE_ACTION))->set_name(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm::NONCE_NAME), (new \WPDeskFIVendor\WPDesk\Forms\Field\SubmitField())->set_name('save')->set_label(\esc_html__('Save changes', 'flexible-invoices'))->add_class('button-primary')];
        $this->form_fields[] = $fields;
    }
    /**
     * @return array|Field[]
     */
    public function get_fields() : array
    {
        $fields = [];
        foreach ($this->form_fields as $form) {
            foreach ($form as $field) {
                $fields[] = $field;
            }
        }
        return $fields;
    }
    /**
     * @return string
     */
    public static function get_tab_slug() : string
    {
        return 'documents';
    }
    /**
     * @return string
     */
    public function get_tab_name() : string
    {
        return \esc_html__('Documents', 'flexible-invoices');
    }
}
