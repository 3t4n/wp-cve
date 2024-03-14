<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore;

use WPDeskFIVendor\Psr\Log\LoggerInterface;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Beacon\BeaconLoader;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\InvoiceCreator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields\InvoiceAsk;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields\VatNumber;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderNote;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
use WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;
use WPDeskFIVendor\WPDesk_Plugin_Info;
/**
 * Main class for integrate library with plugin.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
class InvoicesIntegration implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const VAT_NUMBER_FIELD_ID = 'vat_number';
    const PLUGIN_NAME = 'flexible-invoices-woocommerce';
    use HookableParent;
    /**
     * @var Renderer
     */
    protected $renderer;
    /**
     * @var LibraryInfo
     */
    protected $library_info;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var DocumentCreator[]
     */
    private $creators = [];
    /**
     * @var DataSourceFactory
     */
    private $data_factory;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var array
     */
    private $document_creators;
    /**
     * @var string
     */
    public static $plugin_url;
    /**
     * @var Integration\DocumentFactory
     */
    private $document_factory;
    /**
     * @var SettingsStrategy\SettingsStrategy
     */
    private $strategy;
    /**
     * @var Integration\SaveDocument
     */
    public $save_document;
    /**
     * @var PDF
     */
    public $pdf;
    /**
     * @var bool
     */
    private static $is_super = \false;
    /**
     * @var string
     */
    public static $plugin_filename = '';
    /**
     * @param WPDesk_Plugin_Info $plugin_info
     * @param LoggerInterface    $logger
     */
    public function __construct(\WPDeskFIVendor\WPDesk_Plugin_Info $plugin_info, \WPDeskFIVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->library_info = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo($plugin_info);
        $this->set_is_super();
        $this->logger = $logger;
        $this->settings = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();
        $this->set_renderer();
        $this->set_strategy();
        $this->set_source_factory();
        $this->set_library_url($this->library_info->get_library_url());
        $this->set_documents_creators();
        $this->set_document_factory();
        $this->set_document_saver();
        $this->set_pdf_writer();
        self::$plugin_filename = $this->library_info->get_plugin_info()->get_plugin_file_name();
    }
    /**
     * Set document creators.
     */
    public function set_documents_creators()
    {
        $this->add_creator(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\InvoiceCreator($this->get_source_factory(), \__('Issue Invoice', 'flexible-invoices'), \__('Invoice', 'flexible-invoices')));
    }
    /**
     * Is pro version.
     *
     * @return bool
     */
    public static final function is_pro() : bool
    {
        return self::$is_super;
    }
    /**
     * @return Settings
     */
    public function get_settings() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings
    {
        return $this->settings;
    }
    /**
     * @param $plugin
     */
    public function set_library_url($plugin)
    {
        self::$plugin_url = \trailingslashit($plugin);
    }
    /**
     * @return LoggerInterface
     */
    public function get_logger() : \WPDeskFIVendor\Psr\Log\LoggerInterface
    {
        return $this->logger;
    }
    /**
     * Set renderer.
     */
    private function set_renderer()
    {
        $resolver = new \WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver();
        $resolver->appendResolver(new \WPDeskFIVendor\WPDesk\View\Resolver\DirResolver(\get_stylesheet_directory() . '/flexible-invoices/'));
        $resolver->appendResolver(new \WPDeskFIVendor\WPDesk\View\Resolver\DirResolver($this->library_info->get_plugin_dir() . 'templates/'));
        $resolver->appendResolver(new \WPDeskFIVendor\WPDesk\View\Resolver\DirResolver($this->library_info->get_template_dir()));
        $this->renderer = new \WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer($resolver);
    }
    /**
     * @return Renderer
     */
    public function get_renderer() : \WPDeskFIVendor\WPDesk\View\Renderer\Renderer
    {
        return $this->renderer;
    }
    /**
     * Set strategy
     */
    protected function set_strategy()
    {
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $this->strategy = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsWordpressStrategy($this->settings);
        } else {
            $this->strategy = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsWoocommerceStrategy($this->settings);
        }
    }
    /**
     * @return SettingsStrategy\SettingsStrategy
     */
    public function get_strategy() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy
    {
        return $this->strategy;
    }
    /**
     * @param DocumentCreator $creator
     */
    public function add_creator(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator $creator)
    {
        $this->creators[] = $creator;
    }
    /**
     * @return DocumentCreator[]
     */
    public function get_creators() : array
    {
        return $this->creators;
    }
    /**
     * Set data factory.
     */
    private function set_source_factory()
    {
        $this->data_factory = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory($this->settings);
    }
    /**
     * @return DataSourceFactory
     */
    public function get_source_factory() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory
    {
        return $this->data_factory;
    }
    /**
     * Set document factory.
     */
    private function set_document_factory()
    {
        $creator_container = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\CreatorContainer();
        foreach ($this->get_creators() as $creator) {
            $creator_container->add_creator($creator);
        }
        $this->document_factory = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory($creator_container);
    }
    /**
     * @return Integration\DocumentFactory
     */
    public function get_document_factory() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory
    {
        return $this->document_factory;
    }
    /**
     * Set PDF writer.
     */
    private function set_pdf_writer()
    {
        $this->pdf = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF($this->library_info, $this->renderer, $this->document_factory, $this->strategy);
    }
    /**
     * @return PDF
     */
    public function get_pdf_writer() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF
    {
        return $this->pdf;
    }
    private function set_is_super()
    {
        $plugin_slug = \trim(\dirname($this->library_info->get_plugin_info()->get_plugin_file_name()), '/ ');
        if ($plugin_slug === self::PLUGIN_NAME) {
            self::$is_super = \true;
        }
    }
    /**
     * @return bool
     */
    public static final function is_super() : bool
    {
        return self::$is_super;
    }
    /**
     * Set document saver.
     */
    private function set_document_saver()
    {
        $this->save_document = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\SaveDocument($this->document_factory, $this->settings, $this->strategy, $this->logger, $this->library_info->get_plugin_version());
    }
    /**
     * @return Integration\SaveDocument
     */
    public function get_document_saver() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\SaveDocument
    {
        return $this->save_document;
    }
    /**
     * Fire hook for external integrations.
     */
    public function fire_external_integration_actions()
    {
        $document_factory = $this->get_document_factory();
        $document_saver = $this->get_document_saver();
        $strategy = $this->get_strategy();
        $settings = $this->get_settings();
        $library_info = $this->library_info;
        $logger = $this->get_logger();
        $pdf = $this->get_pdf_writer();
        $external_plugin_access = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\ExternalPluginsAccess($library_info->get_plugin_info()->get_version(), $document_factory, $document_saver, $strategy, $settings, $library_info, $logger, $this->renderer, $pdf);
        /**
         * Hook for integrate with external plugins.
         *
         * @param Integration\ExternalPluginsAccess $external_plugin_access External plugin access.
         *
         * @since 3.0.0
         */
        \do_action('fi/core/initialized', $external_plugin_access);
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        $this->wordpress_integration_hooks();
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $this->woocommerce_integration_hooks();
        }
        $this->hooks_on_hookable_objects();
        \add_action('init', [$this, 'fire_external_integration_actions']);
    }
    /**
     * Register WordPress hooks.
     */
    private function wordpress_integration_hooks()
    {
        $capabilities = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities($this->settings);
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\DefaultSettings());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Assets($this->library_info->get_assets_url()));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm($this->strategy, $this->library_info->get_template_dir(), $this->library_info->get_assets_url()));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType($capabilities));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterMetaBoxes($this->strategy, $this->document_factory, $this->renderer, $this->settings));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeColumns($this->strategy, $this->document_factory));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Dashboard($this->document_factory, $this->strategy, $capabilities, $this->renderer, $this->settings));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\BulkActions());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\User());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\FindProducts());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\DuplicatesNotice());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Reports\GenerateReport($this->get_settings(), $this->document_factory, $this->renderer, $this->library_info));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Reports\ReportsMenuPage($this->library_info->get_template_dir()));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Download\DownloadMenuPage($this->library_info->get_template_dir()));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Download\BatchDocumentsDownload($this->get_pdf_writer(), $this->document_factory));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\SearchCustomer());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Beacon\BeaconLoader($this->library_info));
        $this->add_hookable($this->save_document);
        $this->add_hookable($this->get_pdf_writer());
    }
    /**
     * Register WooCommerce hooks.
     */
    private function woocommerce_integration_hooks()
    {
        $order_note = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderNote();
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\CheckoutAssets($this->settings, $this->library_info->get_assets_url()));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\MyAccount($this->document_factory, $this->renderer));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\CreateDocumentForOrder($this->document_factory, $this->settings, $this->save_document, $this->renderer, $this->get_pdf_writer()));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\DocumentPostMeta());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Order\FormattedOrderMeta());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Order\DeleteDocumentRelation($this->document_factory));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Order\RegisterMetaBox($this->document_factory));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Taxes());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Subscriptions());
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\SequentialOrderNumber($this->settings));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderPaymentUrl($this->settings));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\RegisterEmails($this->document_factory));
        $this->add_hookable($order_note);
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\EmailIntegration($this->document_factory, $this->get_pdf_writer(), $order_note));
        $this->add_hookable(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Checkout($this->settings));
        $this->add_checkout_fields();
    }
    /**
     * Add checkout fields
     */
    private function add_checkout_fields()
    {
        if ($this->settings->get('woocommerce_add_nip_field') === 'yes') {
            $vat_number_field = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields\VatNumber(
                self::VAT_NUMBER_FIELD_ID,
                \__($this->settings->get('woocommerce_nip_label'), 'flexible-invoices'),
                //phpcs:ignore
                \__($this->settings->get('woocommerce_nip_placeholder', ''), 'flexible-invoices')
            );
            if ($this->settings->get('woocommerce_nip_required') === 'yes') {
                $vat_number_field->set_required();
            }
            $this->add_hookable($vat_number_field);
        }
        if ($this->settings->get('woocommerce_add_invoice_ask_field') === 'yes') {
            $invoice_ask = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields\InvoiceAsk('invoice_ask', \__('I want an invoice', 'flexible-invoices'));
            $this->add_hookable($invoice_ask);
        }
    }
}
