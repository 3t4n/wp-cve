<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
class Hooks
{
    /**
     * @param array $users
     * @param array $site_users
     *
     * @return array
     */
    public static function signature_user_filter(array $users, array $site_users) : array
    {
        /**
         * Filters the default signature users passed to select in general settings.
         *
         * @param array $users      An array of prepared users.
         * @param array $site_users An array of site users.
         *
         * @return array
         * @since 1.3.5
         */
        return \apply_filters('fi/core/settings/general/signature_users', $users, $site_users);
    }
    /**
     * @param Document $document
     * @param string   $client_country
     * @param bool     $hide_vat
     * @param bool     $hide_vat_number
     */
    public static function template_correction_after_notes(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, string $client_country, bool $hide_vat, bool $hide_vat_number)
    {
        /**
         * Fire hook after correction notes (deprecated - use fi/core/template/correction/after_notes).
         *
         * @param string   $client_country  Client country.
         * @param bool     $hide_vat        Hide vat?.
         * @param bool     $hide_vat_number Hide vat number?.
         * @param Document $document        Document object.
         *
         * @deprecated
         *
         * @since 3.0.0
         */
        \do_action('flexible_invoices_after_notes', $client_country, $hide_vat, $hide_vat_number, $document);
        /**
         * Fire hook after correction notes.
         *
         * @param Document $document        Document object.
         * @param string   $client_country  Client country.
         * @param bool     $hide_vat        Hide vat?.
         * @param bool     $hide_vat_number Hide vat number?.
         *
         * @since 3.0.0
         */
        \do_action('fi/core/template/correction/after_notes', $document, $client_country, $hide_vat, $hide_vat_number);
    }
    /**
     * @param Document $document
     * @param string   $client_country
     * @param bool     $hide_vat
     * @param bool     $hide_vat_number
     */
    public static function template_invoice_after_notes(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, string $client_country, bool $hide_vat, bool $hide_vat_number)
    {
        /**
         * Fire hook after invoice notes (deprecated - use fi/core/template/invoice/after_notes).
         *
         * @param string   $client_country  Client country.
         * @param bool     $hide_vat        Hide vat?.
         * @param bool     $hide_vat_number Hide vat number?.
         * @param Document $document        Document object.
         *
         * @deprecated
         *
         * @since 3.0.0
         */
        \do_action('flexible_invoices_after_notes', $client_country, $hide_vat, $hide_vat_number, $document);
        /**
         * Fire hook after invoice notes.
         *
         * @param Document $document        Document object.
         * @param string   $client_country  Client country.
         * @param bool     $hide_vat        Hide vat?.
         * @param bool     $hide_vat_number Hide vat number?.
         *
         * @since 3.0.0
         */
        \do_action('fi/core/template/invoice/after_notes', $document, $client_country, $hide_vat, $hide_vat_number);
    }
    /**
     * @param string   $output_street
     * @param Customer $customer
     *
     * @return string
     */
    public static function template_customer_street_filter(string $output_street, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer $customer) : string
    {
        /**
         * Filters client street.
         *
         * @param string   $output_street Customer street.
         * @param Customer $customer      Customer object.
         *
         * @since 3.0.0
         */
        return \apply_filters('fi/core/template/invoice/client/street', $output_street, $customer);
    }
    /**
     * @param PersistentContainer $settings Settings container.
     */
    public static function template_custom_css_hook(\WPDeskFIVendor\WPDesk\Persistence\PersistentContainer $settings)
    {
        /**
         * Fires in custom CSS section.
         *
         * @param PersistentContainer $settings Settings.
         */
        \do_action('fi/core/template/invoice/custom_css', $settings);
    }
    /**
     * @param Document $document Document object (invoice, correction etc.)
     * @param array    $products Products.
     * @param Customer $customer Customer object
     *
     * @return mixed|void
     */
    public static function template_exchange_vertical_filter(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, array $products, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer $customer)
    {
        /**
         * Filters template totals section.
         *
         * @param Document $document Document object (invoice, correction etc.).
         * @param array    $products Document products.
         * @param Customer $customer Customer object.
         */
        return \apply_filters('fi/core/template/invoice/exchange/vertical', '', $document, $products, $customer);
    }
    /**
     * @param string $wpml_user_lang
     */
    public static function wpml_switch_language_hook(string $wpml_user_lang)
    {
        /**
         * WPML language switch.
         *
         * @param string $wpml_user_lang Current lang.
         */
        \do_action('wpml_switch_language', $wpml_user_lang);
    }
    /**
     * @param string $value
     * @param string $textdomain
     * @param string $id
     * @param string $current_lang
     *
     * @return string
     */
    public static function wpml_translate_single_string_filter(string $value, string $textdomain, string $id, string $current_lang) : string
    {
        /**
         * @ignore WPML hook.
         */
        return \apply_filters('wpml_translate_single_string', $value, $textdomain, $id, $current_lang);
    }
    /**
     * @param WC_Order $order
     * @param bool     $sent_to_admin
     * @param string   $plain_text
     * @param string   $email
     */
    public static function woocommerce_email_after_order_table_hook(\WC_Order $order, bool $sent_to_admin, string $plain_text, string $email)
    {
        /**
         * Fires in email template.
         *
         * @param WC_Order $order         Order.
         * @param bool     $sent_to_admin Sent to admin.
         * @param string   $plain_text    Plain text,
         * @param string   $email         Recipient email.
         */
        \do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email);
    }
    /**
     * @param WC_Order $order
     * @param bool     $sent_to_admin
     * @param string   $plain_text
     * @param string   $email
     */
    public static function woocommerce_email_order_meta_hook(\WC_Order $order, bool $sent_to_admin, string $plain_text, string $email)
    {
        /**
         * Fires in email template.
         *
         * @param WC_Order $order         Order.
         * @param bool     $sent_to_admin Sent to admin.
         * @param string   $plain_text    Plain text,
         * @param string   $email         Recipient email.
         */
        \do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);
    }
    /**
     * @param bool   $email_heading Sent to admin.
     * @param string $email         Recipient email.
     */
    public static function woocommerce_email_header_hook($email_heading, string $email)
    {
        /**
         * Fires in email template.
         *
         * @param string $email_heading Email heading.
         * @param string $email         Recipient email.
         */
        \do_action('woocommerce_email_header', $email_heading, $email);
    }
    /**
     * @param string $format Date format.
     */
    public static function document_date_general_format_filter(string $format = 'Y-m-d')
    {
        /**
         * General document date format.
         *
         * @param string $format Date format.
         */
        return \apply_filters('fi/core/document/date/format', $format);
    }
    /**
     * @param string $format Date format.
     */
    public static function document_date_issue_format_filter(string $format = 'Y-m-d')
    {
        /**
         * Date format for issue date.
         *
         * @param string $format Date format.
         *
         * @since 3.8.2
         */
        return \apply_filters('fi/core/document/date/issue/format', $format);
    }
    /**
     * @param string $format Date format.
     */
    public static function document_date_pay_format_filter(string $format = 'Y-m-d')
    {
        /**
         * Date format for payment date.
         *
         * @param string $format Date format.
         *
         * @since 3.8.2
         */
        return \apply_filters('fi/core/document/date/payment/format', $format);
    }
    /**
     * @param string $format Date format.
     */
    public static function document_date_paid_format_filter(string $format = 'Y-m-d')
    {
        /**
         * Date format for the date paid.
         *
         * @param string $format Date format.
         *
         * @since 3.8.2
         */
        return \apply_filters('fi/core/document/date/paid/format', $format);
    }
    /**
     * @param string $format Date format.
     */
    public static function document_date_sale_format_filter(string $format = 'Y-m-d')
    {
        /**
         * Date format for the sale date.
         *
         * @param string $format Date format.
         *
         * @since 3.8.2
         */
        return \apply_filters('fi/core/document/date/sale/format', $format);
    }
}
