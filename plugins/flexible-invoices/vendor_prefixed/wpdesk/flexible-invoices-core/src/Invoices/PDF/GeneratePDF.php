<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF;

use Exception;
use WPDeskFIVendor\Mpdf\Config\FontVariables;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\TemplateDocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\Mpdf\Mpdf;
use WPDeskFIVendor\Mpdf\MpdfException;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Generate PDF.
 */
class GeneratePDF implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\PdfPrinter, \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var LibraryInfo
     */
    private $library_info;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param LibraryInfo      $library_info
     * @param Renderer         $renderer
     * @param DocumentFactory  $document_factory
     * @param SettingsStrategy $strategy
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo $library_info, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy)
    {
        $this->library_info = $library_info;
        $this->renderer = $renderer;
        $this->document_factory = $document_factory;
        $this->strategy = $strategy;
        $this->settings = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();
    }
    /**
     * @return array
     */
    private function get_fonts_data() : array
    {
        $default_font_config = (new \WPDeskFIVendor\Mpdf\Config\FontVariables())->getDefaults();
        $default_font_data = $default_font_config['fontdata'];
        $fonts_data = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\FontsData();
        $fonts_data->set_font_without_italic('dejavusanscondensed', 'DejaVuSansCondensed');
        $fonts = $fonts_data->get();
        /**
         * Filters fonts data.
         *
         * Fonts should be added to the assets/fonts directory or own directory defined by flexible_coupons_font_dir filter.
         * Important. The key names must be lowercase.
         *
         * @param array     $font      Declaration of fonts used in the plugin.
         * @param string    $font_data Default fonts data from mPDF.
         * @param FontsData $font_data Class to create fonts data items.
         *
         * @return array
         *
         * @since 3.0.0
         */
        return (array) \apply_filters('fi/core/pdf/fonts/data', $fonts, $default_font_data, $fonts_data);
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_fiw_get_document', [$this, 'get_document_action']);
        \add_action('wp_ajax_nopriv_fiw_get_document', [$this, 'get_document_action']);
        \add_action('wp_ajax_fi_download_pdf', [$this, 'get_invoice_pdf_action']);
        \add_action('wp_ajax_nopriv_fi_download_pdf', [$this, 'get_invoice_pdf_action']);
    }
    /**
     * @return Mpdf
     * @throws MpdfException
     */
    public function get() : \WPDeskFIVendor\Mpdf\Mpdf
    {
        return new \WPDeskFIVendor\Mpdf\Mpdf($this->get_config());
    }
    /**
     * @return string
     */
    private function get_temp_dir() : string
    {
        $upload_dir = \wp_upload_dir();
        $temp_dir = \trailingslashit($upload_dir['basedir']) . 'mpdf/tmp/';
        \wp_mkdir_p($temp_dir);
        return $temp_dir;
    }
    /**
     * @return array
     */
    public function get_fonts_dir() : array
    {
        /**
         * Filters font paths.
         *
         * @param array $font_dir Font dirs.
         *
         * @return array
         *
         * @since 3.0.0
         */
        return (array) \apply_filters('fi/core/pdf/fonts/dir', [\trailingslashit($this->library_info->get_assets_dir() . 'fonts')]);
    }
    /**
     * Set default config
     */
    private function get_config()
    {
        $config = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config();
        $config->set_font_data($this->get_fonts_data());
        $config->set_font_dir($this->get_fonts_dir());
        $config->set_temp_dir($this->get_temp_dir());
        $config->set_mode('ja+aCJK');
        $config->set_auto_script_to_lang(\true);
        $config->set_auto_lang_to_font(\true);
        $config->set_default_font('dejavusanscondensed');
        $config->set_margin_header(10);
        $config->set_margin_footer(10);
        $config->set_margin_right(10);
        $config->set_margin_left(10);
        $config->set_margin_top(10);
        $config->set_margin_bottom(10);
        $config_data = $config->get();
        /**
         * Filters the settings for the MPDF library.
         *
         * @param array  $config_data Config data.
         * @param Config $config      Config class.
         *
         * @return array
         *
         * @since 3.0.0
         */
        return \apply_filters('fi/core/pdf/config', $config_data, $config);
    }
    /**
     * @param Document $document
     *
     * @return string
     * @throws MpdfException
     */
    public function generate_pdf_file_content(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        $mpdf = $this->get();
        if (\is_rtl()) {
            $mpdf->SetDirectionality('rtl');
        }
        if ($this->settings->get('pdf_numbering') === 'yes') {
            $mpdf->setFooter('{PAGENO}/{nbpg}');
        }
        $mpdf->img_dpi = 200;
        if (!\is_a($document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\TemplateDocumentDecorator::class)) {
            $document = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\TemplateDocumentDecorator($document, $this->strategy);
        }
        if (\function_exists('mb_convert_encoding')) {
            $html = \mb_convert_encoding($this->get_document_template($document), 'UTF-8', 'UTF-8');
        } else {
            $html = $this->get_document_template($document);
        }
        $mpdf->WriteHTML($html);
        return $mpdf->Output(\str_replace(['/'], ['_'], $document->get_formatted_number()) . '.pdf', 'S');
    }
    /**
     * @param Document $document
     *
     * @return string
     * @throws MpdfException
     */
    public function get_as_string(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        return $this->generate_pdf_file_content($document);
    }
    /**
     * Debug HTML before render.
     *
     * Define FLEXIBLE_INVOICES_DEBUG in wp-config.php if you want display HTML not PDF in browser.
     *
     * @param Document $document
     */
    public function debug_before_render_pdf(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        echo $this->get_document_template($document);
        //phpcs:ignore
        die;
    }
    /**
     * @param int $document_id
     *
     * @return void
     * @throws MpdfException
     */
    public function send_to_browser(int $document_id)
    {
        $post = \get_post($document_id);
        if (!$post) {
            \wp_die(\esc_html__('This document doesn\'t exist or was deleted.', 'flexible-invoices'));
        }
        $invoice = $this->document_factory->get_document_creator($document_id)->get_document();
        if (!\is_a($invoice, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\TemplateDocumentDecorator::class)) {
            $invoice = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\TemplateDocumentDecorator($invoice, $this->strategy);
        }
        $name = \str_replace(['/', ' '], ['_', '_'], $invoice->get_formatted_number()) . '.pdf';
        if (\defined('FLEXIBLE_INVOICES_DEBUG')) {
            $this->debug_before_render_pdf($invoice);
        }
        \header('Content-type: application/pdf');
        if (isset($_GET['save_file'])) {
            //phpcs:ignore
            \header('Content-Disposition: attachment; filename="' . $name . '"');
        } else {
            \header('Content-Disposition: inline; filename="' . $name . '"');
        }
        $pdf_data = $this->get_as_string($invoice);
        echo $pdf_data;
        //phpcs:ignore
        exit;
    }
    /**
     * @param null $id
     *
     * @throws MpdfException
     * @internal You should not use this directly from another application
     */
    public function get_invoice_pdf_action($id = null)
    {
        if (empty($id)) {
            $id = (int) $_GET['id'];
            //phpcs:ignore
        }
        if (isset($_GET['hash']) && $_GET['hash'] === \md5(NONCE_SALT . $id) || \current_user_can('manage_options') || \current_user_can('manage_woocommerce')) {
            //phpcs:ignore
            $this->send_to_browser($id);
        }
        die;
    }
    /**
     * @throws MpdfException
     * @internal You should not use this directly from another application
     */
    public function get_document_action()
    {
        $id = (int) $_GET['id'];
        //phpcs:ignore
        $creator = $this->document_factory->get_document_creator($id);
        $document = $creator->get_document();
        $hash = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice::document_hash($document);
        if (isset($_GET['hash']) && $_GET['hash'] === $hash) {
            //phpcs:ignore
            $this->send_to_browser($id);
        }
        die;
    }
    /**
     * @param Document $document
     *
     * @return string
     */
    private function get_document_template(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        $corrected_id = $document->get_corrected_id();
        $corrected_invoice = $this->document_factory->get_document_creator($corrected_id)->get_document();
        $corrected_invoice_pdf = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\TemplateDocumentDecorator($corrected_invoice, $this->strategy);
        $document_name = $this->get_template_name($document->get_type());
        \do_action('fi/core/pdf/generate/before', $document, $this->settings, $document_name);
        try {
            return $this->renderer->render('documents/' . $document_name, ['invoice' => $document, 'currency_helper' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency($document->get_currency()), 'meta' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer($document->get_id()), 'translator' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator(), 'library_info' => $this->library_info, 'settings' => $this->strategy->get_settings(), 'corrected_invoice' => $corrected_invoice_pdf, 'layout_name' => $this->get_layout_name(), 'order' => $document->get_order_id()]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * @return string
     */
    private function get_layout_name() : string
    {
        return $this->settings->get('template_layout', 'default');
    }
    /**
     * @return string
     */
    private function get_template_name(string $type) : string
    {
        if ($type === 'proforma') {
            $type = 'invoice';
        }
        $template_suffix = $this->get_layout_name() ? '-' . $this->get_layout_name() : '';
        return $type . '/' . $type . $template_suffix;
    }
}
