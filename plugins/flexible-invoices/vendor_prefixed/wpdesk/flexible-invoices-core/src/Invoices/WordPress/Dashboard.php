<?php

/**
 * Integration. Register custom post type.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use Exception;
use WP_Post;
use WP_Query;
use WP_User;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentNumber;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Dashboard important hooks.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class Dashboard implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
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
     * @var PostTypeCapabilities
     */
    private $capabilities;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var Request
     */
    private $request;
    /**
     * Dashboard constructor.
     *
     * @param DocumentFactory      $document_factory
     * @param SettingsStrategy     $strategy
     * @param PostTypeCapabilities $capabilities
     * @param Renderer             $renderer
     * @param Settings             $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities $capabilities, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->document_factory = $document_factory;
        $this->strategy = $strategy;
        $this->renderer = $renderer;
        $this->capabilities = $capabilities;
        $this->settings = $settings;
        $this->request = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request();
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        if (\is_admin()) {
            \add_filter('default_title', [$this, 'new_invoice_default_title'], 80, 2);
            \add_action('admin_init', [$this, 'set_default_layout_action']);
            \add_action('admin_init', [$this->capabilities, 'assign_basic_roles_capabilities_action']);
            \add_action('restrict_manage_posts', [$this, 'add_invoice_bulk_selects']);
            \add_filter('months_dropdown_results', [$this, 'modify_invoice_listing_months_filter'], 80, 2);
            \add_filter('parse_query', [$this, 'filter_invoices']);
            \add_filter('views_edit-inspire_invoice', [$this, 'add_duplicated_filter']);
            \add_filter('post_updated_messages', [$this, 'replace_post_messages_filter']);
        }
    }
    /**
     * @param string  $post_title
     * @param WP_Post $post
     *
     * @return string
     *
     * @throws Exception
     * @internal You should not use this directly from another application
     */
    public function new_invoice_default_title(string $post_title, \WP_Post $post) : string
    {
        if ($post->post_status === 'auto-draft' && $post->post_type === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME) {
            $document_type = $_GET['document_type'] ?? \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE;
            $this->document_factory->set_document_type($document_type);
            $creator = $this->document_factory->get_document_creator($post->ID);
            $document = $creator->get_document();
            $numbering = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentNumber($this->settings, $document, $creator->get_name());
            return $numbering->get_formatted_number();
        }
        return $post_title;
    }
    /**
     * Set layout action
     *
     * @internal You should not use this directly from another application
     */
    public function set_default_layout_action()
    {
        $user = \wp_get_current_user();
        if ($user) {
            $columns = \get_user_meta($user->ID, 'screen_layout_inspire_invoice', \true);
            if (empty($columns)) {
                \update_user_meta($user->ID, 'screen_layout_inspire_invoice', 1);
            }
            $hidden = \get_user_meta($user->ID, 'manageedit-inspire_invoicecolumnshidden', \true);
            if ($hidden === '') {
                $hidden = ['sale', 'currency', 'paymethod'];
                \update_user_meta($user->ID, 'manageedit-inspire_invoicecolumnshidden', $hidden);
            }
        }
    }
    /**
     * Add user select to bulk actions
     *
     * @internal You should not use this directly from another application
     */
    public function add_invoice_bulk_selects()
    {
        global $typenow;
        if ($typenow === 'inspire_invoice') {
            $selected = $this->get_selected_user();
            $this->renderer->output_render('wordpress/bulk-status-select', ['selected' => $selected, 'statuses' => $this->strategy->get_payment_statuses()]);
        }
    }
    /**
     * Get selected user from list
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    private function get_selected_user() : array
    {
        $user_data = [];
        if ($this->request->param_exists('get.user')) {
            $user_id = (int) $this->request->param('get.user')->get();
            $user = \get_userdata($user_id);
            if ($user) {
                $user_data = ['id' => $user_id, 'text' => $this->prepare_option_text($user)];
            }
        }
        return $user_data;
    }
    /**
     * @param $user
     *
     * @return string
     *
     * @internal You should not use this directly from another application
     */
    private function prepare_option_text($user) : string
    {
        $name = '';
        $user_meta = \get_user_meta($user->ID);
        if (isset($user_meta['billing_company'][0])) {
            $company = $user_meta['billing_company'][0];
            if (!empty($company)) {
                $name .= $company . ', ';
            }
        }
        if (isset($user_meta['billing_first_name'][0])) {
            $billing_first_name = $user_meta['billing_first_name'][0];
            if (!empty($billing_first_name)) {
                $name .= $user_meta['billing_first_name'][0] . ' ';
            }
        }
        if (isset($user_meta['billing_last_name'][0])) {
            $billing_last_name = $user_meta['billing_last_name'][0];
            if (!empty($billing_last_name)) {
                $name .= $user_meta['billing_last_name'][0] . ', ';
            }
        }
        $name .= $user->first_name . ' ';
        return $name . $user->last_name . ' (' . $user->user_login . ')';
    }
    /**
     * @param string $months
     * @param string $post_type
     *
     * @return array|object|null
     *
     * @internal You should not use this directly from another application
     */
    public function modify_invoice_listing_months_filter($months, $post_type)
    {
        if ($post_type === 'inspire_invoice') {
            global $wpdb;
            //phpcs:disable
            $months = $wpdb->get_results($wpdb->prepare("\n\t                SELECT DISTINCT YEAR( FROM_UNIXTIME( pm.meta_value ) ) AS year, MONTH( FROM_UNIXTIME ( pm.meta_value ) ) AS month\n\t                FROM\n\t                   {$wpdb->posts} p,\n\t                   {$wpdb->postmeta} pm\n\t                WHERE\n\t                   pm.post_id = p.id AND\n\t                   p.post_type = %s AND\n\t                   pm.meta_key = '_date_issue'\n\t                ORDER BY\n\t                   pm.meta_value DESC\n\t                ", $post_type));
            //phpcs:enable
        }
        return $months;
    }
    /**
     * @param WP_Query $query
     *
     * @return WP_Query
     *
     * @internal You should not use this directly from another application
     */
    public function filter_invoices(\WP_Query $query) : \WP_Query
    {
        global $pagenow;
        $qv =& $query->query_vars;
        if ($pagenow === 'edit.php' && isset($qv['post_type']) && $qv['post_type'] === 'inspire_invoice') {
            $meta_query = [];
            if ('show_duplicated' === $this->request->param('get.filter')->get()) {
                $qv['post__in'] = $this->get_duplicated_posts_ids();
            }
            $payment_status = $this->request->param('get.paystatus')->get();
            if ($payment_status) {
                if ($payment_status === 'exceeded') {
                    $meta_query[] = ['key' => '_payment_status', 'value' => 'topay', 'compare' => 'LIKE'];
                    $meta_query[] = ['key' => '_date_pay', 'value' => \strtotime(\date('Y-m-d 00:00:00')), 'compare' => '<'];
                } else {
                    $meta_query[] = ['key' => '_payment_status', 'value' => \esc_sql($payment_status), 'compare' => 'LIKE'];
                }
            }
            $user_id = $this->request->param('get.user')->get();
            if ($user_id) {
                $user = new \WP_User((int) $user_id);
                if (empty($user->billing_company)) {
                    $name = $user->billing_first_name . ' ' . $user->billing_last_name;
                } else {
                    $name = $user->billing_company;
                }
                $meta_query[] = ['key' => '_client_filter_field', 'value' => $name, 'compare' => 'LIKE'];
            }
            $pm = $this->request->param('get.m')->get();
            if (!empty($pm)) {
                unset($qv['m']);
                $m = \strtotime(\substr($pm, 0, 4) . '-' . \substr($pm, 4, 2) . '-01 00:00:00');
                $meta_query[] = ['key' => '_date_issue', 'value' => [$m, \strtotime(\date('Y-m-t 23:59:59', $m))], 'compare' => 'BETWEEN', 'type' => 'UNSIGNED'];
            }
            if (!empty($meta_query)) {
                $qv['meta_query'] = $meta_query;
                //phpcs:ignore
            }
        }
        return $query;
    }
    /**
     * @param array $messages
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function replace_post_messages_filter($messages)
    {
        global $post_ID;
        $post_type = \get_post_type($post_ID);
        $messages = \is_array($messages) ? $messages : [];
        $post_type_object = \get_post_type_object($post_type);
        if ($post_type_object) {
            $singular = $post_type_object->labels->singular_name;
            $revision = $this->request->param_exists('get.revision');
            $messages['inspire_invoice'] = [
                0 => '',
                // Unused. Messages start at index 1.
                1 => \esc_html__('Invoice updated.', 'flexible-invoices'),
                2 => \esc_html__('Custom field updated.', 'flexible-invoices'),
                3 => \esc_html__('Custom field deleted.', 'flexible-invoices'),
                4 => \esc_html__('Invoice updated.', 'flexible-invoices'),
                // translators: %s revision ID.
                5 => $revision !== null ? \sprintf(\esc_html__($singular . ' rolled back to revision %s.', 'flexible-invoices'), \wp_post_revision_title((int) $revision, \false)) : \false,
                6 => \esc_html__('Invoice issued.', 'flexible-invoices'),
                7 => \esc_html__('Invoice saved.', 'flexible-invoices'),
                8 => \esc_html__('Invoice submitted.', 'flexible-invoices'),
                9 => \esc_html__('Invoice scheduled', 'flexible-invoices'),
                10 => \esc_html__('Invoice draft updated', 'flexible-invoices'),
            ];
        }
        return $messages;
    }
    /**
     * @param array $views
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function add_duplicated_filter(array $views) : array
    {
        $views['duplicated'] = \sprintf(\__('<a href="%s">Duplicated <span class="count">(%d)</span></a>', 'flexible-invoices'), \admin_url('edit.php?post_type=' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME . '&filter=show_duplicated'), \count($this->get_duplicated_posts_ids()));
        return $views;
    }
    /**
     * @return array
     */
    private function get_duplicated_posts_ids() : array
    {
        global $wpdb;
        $post_ids = $wpdb->get_var($wpdb->prepare("SELECT GROUP_CONCAT(p.ID) FROM {$wpdb->posts} as p WHERE p.post_type = %s AND p.post_status = %s GROUP BY p.post_title HAVING COUNT( p.post_title ) > 1", \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'publish'));
        if (!empty($post_ids)) {
            return \explode(',', $post_ids);
        }
        return [];
    }
}
