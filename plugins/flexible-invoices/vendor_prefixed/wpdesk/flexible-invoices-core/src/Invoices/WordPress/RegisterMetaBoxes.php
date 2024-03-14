<?php

/**
 * Invoice. Add custom meta boxes.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WP_Post;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Register custom meta boxes.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class RegisterMetaBoxes implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /** @var string slug od administrator role */
    const ADMIN_ROLE = 'administrator';
    const EDITOR_ROLE = 'editor';
    const SHOP_MANAGER_ROLE = 'shop_manager';
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param SettingsStrategy $strategy
     * @param DocumentFactory  $document_factory
     * @param Renderer         $renderer
     * @param Settings         $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->document_factory = $document_factory;
        $this->strategy = $strategy;
        $this->renderer = $renderer;
        $this->settings = $settings;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('add_meta_boxes', [$this, 'register_meta_boxes'], 1, 2);
        \add_action('post_submitbox_start', [$this, 'options_box_callback'], 10);
    }
    /**
     * @param string       $post_type
     * @param WP_Post|null $post
     *
     * @internal You should not use this directly from another application
     */
    public function register_meta_boxes(string $post_type, $post = null)
    {
        if ($post_type === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME && isset($post->ID)) {
            $document = $this->document_factory->get_document_creator($post->ID)->get_document();
            $invoice = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator($document, $this->strategy);
            \add_meta_box('ocs', \esc_html__('Seller, Customer, Recipient', 'flexible-invoices'), [$this, 'ocs_box_callback'], \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'normal', 'high', ['invoice' => $invoice]);
            \add_meta_box('products', \esc_html__('Products', 'flexible-invoices'), [$this, 'products_box_callback'], \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'normal', 'high', ['invoice' => $invoice]);
            \add_meta_box('payment', \esc_html__('Payments and other info', 'flexible-invoices'), [$this, 'payment_box_callback'], \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'normal', 'high', ['invoice' => $invoice]);
            if (isset($_GET['invoice_debug']) && \current_user_can('manage_options')) {
                //phpcs:ignore
                \add_meta_box('debug', \esc_html__('Debug', 'flexible-invoices'), [$this, 'debug_meta_box_callback'], \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'normal', 'low', ['invoice' => $invoice]);
            }
        }
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
     * @param WP_Post $post
     * @param array   $args
     */
    public function ocs_box_callback(\WP_Post $post, array $args)
    {
        \wp_nonce_field('flexible_invoices_nonce', 'flexible_invoices_nonce');
        /**
         * @var Document $invoice
         */
        $invoice = $args['args']['invoice'];
        $this->renderer->output_render('invoice_edit/meta-box/all', ['invoice' => $args['args']['invoice'], 'client' => $invoice->get_customer(), 'owner' => $invoice->get_seller(), 'recipient' => $invoice->get_recipient(), 'signature_user' => $this->settings->get('signature_user'), 'plugin' => $invoice, 'post' => $post, 'signature_users' => $this->get_signature_users()]);
    }
    /**
     * @param WP_Post $post
     */
    public function options_box_callback($post)
    {
        if ($post && $post->post_type === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME) {
            $creator = $this->document_factory->get_document_creator($post->ID);
            $document = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator($creator->get_document(), $this->strategy);
            $this->renderer->output_render('invoice_edit/options_metabox', ['document' => $document]);
        }
    }
    /**
     * @param WP_Post $post
     * @param array   $args
     */
    public function products_box_callback(\WP_Post $post, array $args)
    {
        /**
         * @var Document $invoice
         */
        $document = $args['args']['invoice'];
        $template = 'products_metabox';
        if ($document->get_type() === 'correction') {
            $template = 'correction_products';
        }
        $this->renderer->output_render('invoice_edit/' . $template, ['invoice' => $document, 'vat_types' => $this->strategy->get_taxes(), 'plugin' => $this, 'post' => $post, 'show_discount' => $this->settings->get('show_discount') === 'yes']);
    }
    /**
     * @param WP_Post $post
     * @param array   $args
     */
    public function payment_box_callback(\WP_Post $post, array $args)
    {
        /**
         * @var Document $document
         */
        $document = $args['args']['invoice'];
        $this->renderer->output_render('invoice_edit/payment_metabox', ['document' => $document, 'plugin' => $this, 'payment_statuses' => $this->filter_payment_statuses($document), 'payment_currencies' => $this->strategy->get_currencies(), 'payment_methods' => $this->strategy->get_payment_methods(), 'post' => $post]);
    }
    /**
     * @param Document $document
     *
     * @return array
     */
    private function filter_payment_statuses(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : array
    {
        $payment_statuses = $this->strategy->get_payment_statuses();
        if ($document->get_type() === 'proforma') {
            unset($payment_statuses['paid']);
        }
        return $payment_statuses;
    }
    /**
     * @param WP_Post $post
     * @param array   $args
     */
    public function debug_meta_box_callback(\WP_Post $post, array $args)
    {
        /**
         * @var Document $invoice
         */
        $invoice = $args['args']['invoice'];
        print '<strong>Document Object</strong>';
        print '<pre style="overflow:auto;">';
        \print_r($invoice);
        //phpcs:ignore
        print '</pre>';
        print '<strong>Post Meta Object</strong>';
        print '<pre style="overflow:auto;">';
        $post_meta = \get_post_meta($invoice->get_id());
        if (!empty($post_meta)) {
            foreach ($post_meta as $meta_name => $meta_value) {
                $value = $meta_value[0] ?? '';
                if (\false !== \stripos($meta_name, '_date_')) {
                    $arr[$meta_name] = $value . ' (' . \date('Y-m-d H:i', $value) . ')';
                } else {
                    $arr[$meta_name] = \is_serialized($value) ? \maybe_unserialize($value) : $value;
                }
            }
            \print_r($arr);
            //phpcs:ignore
        }
        print '</pre>';
    }
}
