<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Forms\Field;
use WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\Col;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ColorPickerField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ResetField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\Row;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SelectImageField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
/**
 * Invoice Template Settings Tab Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs
 */
final class InvoiceTemplate extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    /** @var string slug od administrator role */
    const ADMIN_ROLE = 'administrator';
    const EDITOR_ROLE = 'editor';
    const SHOP_MANAGER_ROLE = 'shop_manager';
    /**
     * @var string
     */
    private $assets_url;
    public function __construct(string $assets_url)
    {
        $this->assets_url = $assets_url;
    }
    /**
     * @return array
     */
    private function get_signature_users() : array
    {
        $users = [];
        $site_users = \get_users(['role__in' => [self::ADMIN_ROLE, self::EDITOR_ROLE, self::SHOP_MANAGER_ROLE]]);
        foreach ($site_users as $user) {
            $users[$user->ID] = $user->display_name ?: $user->user_login;
        }
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::signature_user_filter($users, $site_users);
    }
    /**
     * @return string[]
     */
    private function get_beacon_translations() : array
    {
        return ['company' => 'Company', 'main' => 'Main Settings', 'woocommerce' => 'Main Settings for WooCommerce'];
    }
    /**
     * @return string
     */
    private function get_doc_link() : string
    {
        $docs_link = 'https://docs.flexibleinvoices.com/article/1017-customizing-the-invoice-template?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=template';
        if (\get_locale() === 'pl_PL') {
            $docs_link = 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=template#szablon-faktury';
        }
        $output = \sprintf('<a href="%s" target="_blank">%s</a><br/>', \esc_url($docs_link), \esc_html__('Read user\'s manual &rarr;', 'flexible-invoices'));
        if (\get_locale() !== 'pl_PL') {
            $docs_link1 = 'https://docs.flexibleinvoices.com/article/790-how-to-create-custom-templates-of-invoices?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=custom-template';
            $docs_link2 = 'https://docs.flexibleinvoices.com/article/789-how-to-add-custom-fields-for-generated-invoice-pdf?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=custom-fields';
            $docs_link3 = 'https://docs.flexibleinvoices.com/article/791-translating-documents?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=translations';
        } else {
            $docs_link1 = 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=custom-template#wlasny-szablon-faktury-w-folderze-motywu';
            $docs_link2 = 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=gtu-invoice#gtu-na-fakturach';
            $docs_link3 = '';
        }
        $output .= \sprintf(\__('Also, learn how to <a href="%s" target="_blank">adjust the invoice template</a>, add <a href="%s" target="_blank">more data</a> and <a href="%s" target="_blank">manage translations</a>.', 'flexible-invoices'), $docs_link1, $docs_link2, $docs_link3);
        return $output;
    }
    /**
     * @return array|Field[]
     */
    protected function get_fields() : array
    {
        $beacon = $this->get_beacon_translations();
        $pro_url = \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/zaawansowane-szablony-faktur-woocommerce/?utm_source=wp-admin-plugins&utm_medium=button&utm_campaign=flexible-invoices-advanced-templates' : 'https://flexibleinvoices.com/products/advanced-templates-for-flexible-invoices/?utm_source=wp-admin-plugins&utm_medium=button&utm_campaign=flexible-invoices-advanced-templates';
        $pro_description = '';
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_template_addon_is_disabled()) {
            $pro_description = \sprintf('<a target="_blank" href="%1$s" >%2$s</a>', $pro_url, \esc_html__('To customize PDF layout of your invoices, buy the Advanced Templates for Flexible Invoices add-on &rarr;', 'flexible-invoices'));
            if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
                $pro_description .= '<br><span>' . \esc_html__('The add-on requires Flexible Invoices PRO.', 'flexible-invoices') . '</span>';
            }
        }
        $color_picker_class = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_template_addon_is_disabled() ? 'color-picker disabled' : 'color-picker';
        $fields = [(new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\esc_html__('Invoice Template', 'flexible-invoices'))->set_description($this->get_doc_link()), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('hide_vat_number')->set_label(\esc_html__('Seller\'s VAT Number on Invoices', 'flexible-invoices'))->set_sublabel(\esc_html__('If tax is 0 hide seller\'s VAT Number on PDF invoices.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('hide_vat')->set_label(\esc_html__('Tax Cells on Invoices', 'flexible-invoices'))->set_sublabel(\esc_html__('If tax is 0 hide all tax cells on PDF invoices.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter('woocommerce_shipping_address', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('shipping-address')->set_label(\esc_html__('Shipping Address', 'flexible-invoices'))->set_description(\esc_html__('Enable if you want to show the customer\'s shipping address on the invoice.', 'flexible-invoices'))->set_options(['none' => \esc_html__('Do not show', 'flexible-invoices'), 'always' => \esc_html__('Show customer\'s address', 'flexible-invoices'), 'ifempty' => \esc_html__('Show customer\'s address if different from billing', 'flexible-invoices')])->set_default_value('none')->set_attribute('data-beacon_search', $beacon['woocommerce'])->add_class('hs-beacon-search '), \true))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_get_sku')->set_label(\esc_html__('SKU', 'flexible-invoices'))->set_sublabel(\esc_html__('Use SKU numbers on invoices', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['woocommerce'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter('show_discount', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('discounts')->set_label(\esc_html__('Discounts', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable to show column with discounts on the invoice.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search '), \true))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('show_signatures')->set_label(\esc_html__('Show Signatures', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable if you want to display place for signatures.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('signature_user')->set_label(\esc_html__('Seller signature', 'flexible-invoices'))->set_description(\esc_html__('Choose a user whose display name will be visible on the invoice in the signature section.', 'flexible-invoices'))->set_options($this->get_signature_users())->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('pdf_numbering')->set_label(\esc_html__('PDF Numbering', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable page numbering.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_headers', (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_name('')->set_label(\__('Advanced Invoice Template', 'flexible-invoices'))->set_description($pro_description)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_layout', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SelectImageField())->set_name('')->set_label(\__('Layout', 'flexible-invoices'))->set_options($this->get_layouts())->set_default_value('default')->set_attribute('is_disabled', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_template_addon_is_disabled() ? 'yes' : 'no')))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\Row())->set_name('row_open'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_text', (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_name('')->set_label(\__('Text', 'flexible-invoices'))->set_description(\__('Document body text.', 'flexible-invoices'))->set_header_size('3')))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_text_font_family', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_default_value('dejavusanscondensed')->set_attribute('data-default_value', 'dejavusanscondensed')->set_options($this->font_families())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_text_font_size', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_default_value(8)->set_attribute('data-default_value', 8)->set_options($this->text_font_sizes())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_text_font_color', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ColorPickerField())->set_name('')->set_default_value('#000000')->set_attribute('data-default_value', '#000000')->add_class($color_picker_class)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\Col())->set_name('col_open'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading1', (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_name('')->set_label(\__('Heading 1', 'flexible-invoices'))->set_description(\__('Invoice number.', 'flexible-invoices'))->set_header_size('3')))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading1_font_family', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_default_value('dejavusanscondensed')->set_attribute('data-default_value', 'dejavusanscondensed')->set_options($this->font_families())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading1_font_size', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_default_value(18)->set_attribute('data-default_value', 18)->set_options($this->header_font_sizes())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading1_font_color', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ColorPickerField())->set_name('')->set_default_value('#000000')->set_attribute('data-default_value', '#000000')->add_class($color_picker_class)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\Col())->set_name('col_open'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading2', (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_name('')->set_label(\__('Heading 2', 'flexible-invoices'))->set_description(\__('Section headers.', 'flexible-invoices'))->set_header_size('3')))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading2_font_family', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_default_value('dejavusanscondensed')->set_attribute('data-default_value', 'dejavusanscondensed')->set_options($this->font_families())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading2_font_size', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_default_value(12)->set_attribute('data-default_value', 12)->set_options($this->header_font_sizes())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading2_font_color', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ColorPickerField())->set_name('')->set_default_value('#000000')->set_attribute('data-default_value', '#000000')->add_class($color_picker_class)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\Col())->set_name('col_open'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading3', (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_name('')->set_label(\__('Heading 3', 'flexible-invoices'))->set_description(\__('Names of columns in the table.', 'flexible-invoices'))->set_header_size('3')))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading3_font_family', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_default_value('dejavusanscondensed')->set_attribute('data-default_value', 'dejavusanscondensed')->set_options($this->font_families())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading3_font_size', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_default_value(9)->set_attribute('data-default_value', 9)->set_options($this->text_font_sizes())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_heading3_font_color', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ColorPickerField())->set_name('')->set_default_value('#000000')->set_attribute('data-default_value', '#000000')->add_class($color_picker_class)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\Row(\false))->set_name('row-close'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_table_header', (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_name('')->set_label(\__('Table design', 'flexible-invoices'))->set_description(\__('Customize table element styles.', 'flexible-invoices'))->set_header_size('3')))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_table_border_size', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_label(\__('Table border thickness', 'flexible-invoices'))->set_default_value(1)->set_attribute('data-default_value', 1)->set_options($this->border_sizes())))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_table_border_color', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ColorPickerField())->set_name('')->set_label(\__('Table border color', 'flexible-invoices'))->set_default_value('#000000')->set_attribute('data-default_value', '#000000')->add_class($color_picker_class)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_table_header_bg', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ColorPickerField())->set_name('')->set_label(\__('Table header background', 'flexible-invoices'))->set_default_value('#F1F1F1')->set_attribute('data-default_value', '#F1F1F1')->add_class($color_picker_class)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_table_rows_even', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ColorPickerField())->set_name('')->set_label(\__('Rows color (even)', 'flexible-invoices'))->set_default_value('#FFFFFF')->set_attribute('data-default_value', '#FFFFFF')->add_class($color_picker_class)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableTemplateFieldAdapter('template_reset_settings', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ResetField())->set_label(\__('Reset appearance', 'flexible-invoices'))->set_name('')->add_class('reset-pdf-template button-secondary')))->get_field(), (new \WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm::NONCE_ACTION))->set_name(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm::NONCE_NAME), (new \WPDeskFIVendor\WPDesk\Forms\Field\SubmitField())->set_name('save')->set_label(\esc_html__('Save changes', 'flexible-invoices'))->add_class('button-primary')];
        /**
         * Filters invoice template settings fields.
         *
         * @param array $fields Collection of fields.
         * @param array $beacon Beacon strings.
         *
         * @since 2.0.0
         */
        return \apply_filters('fi/core/settings/tabs/invoice_template/fields', $fields, $beacon);
    }
    /**
     * @return int[]
     */
    public function border_sizes() : array
    {
        for ($i = 1; $i <= 4; $i++) {
            $n[$i] = $i . 'px';
        }
        return $n;
    }
    /**
     * @return int[]
     */
    public function text_font_sizes() : array
    {
        for ($i = 8; $i <= 12; $i++) {
            $n[$i] = $i . 'px';
        }
        return $n;
    }
    /**
     * @return int[]
     */
    public function header_font_sizes() : array
    {
        $n = [];
        for ($i = 10; $i <= 32; $i++) {
            if ($i % 2 !== 0) {
                continue;
            }
            $n[$i] = $i . 'px';
        }
        return $n;
    }
    /**
     * @return string[]
     */
    public function font_families() : array
    {
        return ['dejavusans' => 'DeJaVu Sans', 'dejavuserif' => 'DeJaVu Serif', 'dejavusanscondensed' => 'DeJaVu Sans Condensed', 'freeserif' => 'FreeSerif', 'montserrat' => 'Montserrat', 'opensans' => 'OpenSans', 'opensanscondensed' => 'OpenSansCondensed', 'roboto' => 'Roboto', 'robotoslab' => 'RobotoSlab', 'rubik' => 'Rubik', 'titilliumweb' => 'TitilliumWeb'];
    }
    /**
     * @return array
     */
    private function get_layouts() : array
    {
        return ['default' => ['name' => \__('Default', 'flexible-invoices'), 'thumb_src' => $this->assets_url . 'images/template1_min.jpg', 'large_src' => $this->assets_url . 'images/template1.jpg'], 'layout1' => ['name' => \__('Layout no. 1', 'flexible-invoices'), 'thumb_src' => $this->assets_url . 'images/template2_min.jpg', 'large_src' => $this->assets_url . 'images/template2.jpg'], 'layout2' => ['name' => \__('Layout no. 2', 'flexible-invoices'), 'thumb_src' => $this->assets_url . 'images/template3_min.jpg', 'large_src' => $this->assets_url . 'images/template3.jpg'], 'layout3' => ['name' => \__('Layout no. 3', 'flexible-invoices'), 'thumb_src' => $this->assets_url . 'images/template4_min.jpg', 'large_src' => $this->assets_url . 'images/template4.jpg']];
    }
    /**
     * @return string
     */
    public static function get_tab_slug() : string
    {
        return 'invoice-template';
    }
    /**
     * @return string
     */
    public function get_tab_name() : string
    {
        return \esc_html__('Invoice Template', 'flexible-invoices');
    }
}
