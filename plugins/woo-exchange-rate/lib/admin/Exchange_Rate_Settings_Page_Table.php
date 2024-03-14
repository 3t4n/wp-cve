<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

class Exchange_Rate_Settings_Page_Table extends \WP_List_Table {

    /**
     * Initialize the webhook table list.
     */
    public function __construct() {
        parent::__construct(array(
            'singular' => __('Currency', 'woocommerce'),
            'plural' => __('Currencies', 'woocommerce'),
            'ajax' => false
        ));
    }

    /**
     * Get list columns.
     *
     * @return array
     */
    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'currency_code' => __('Currency', 'woocommerce'),
            'currency_pos' => __( 'Currency Position', 'woocommerce' ),
            'currency_exchange_rate' => __('Exchange rate', 'woo-exchange-rate'),
        );
    }

    /**
     * Column checkbox
     *
     * @param  array $key
     * @return string
     */
    public function column_cb($key) {
        return sprintf('<input type="checkbox" name="id[]" value="%1$s" />', $key['id']);
    }

    /**
     * Column currency_code.
     *
     * @param  array $key
     * @return string
     */
    public function column_currency_code($key) {
        $home_url = (new Exchange_Rate_Settings_Page())->get_home_url();
        $url = $home_url . '&edit-id=' . $key['id'];
        $currencies = get_woocommerce_currencies();
        $code = $key['currency_code'];

        $output = '<strong>';
        $output .= '<a href="' . esc_url($url) . '" class="row-title">';
        $output .= $currencies[$code] . ' (' . get_woocommerce_currency_symbol($code) . ')';
        $output .= '</a>';
        $output .= '</strong>';

        // Get actions
        $actions = array(
            'id' => sprintf(__('ID: %d', 'woocommerce'), $key['id']),
            'edit' => '<a href="' . esc_url($url) . '">' . __('View/Edit', 'woocommerce') . '</a>',
            'trash' => '<a class="submitdelete" title="' . esc_attr__('Revoke API Key', 'woocommerce') .
            '" href="' . esc_url(wp_nonce_url(add_query_arg(array('remove-id' => $key['id']), $home_url), 'remove')) . '">' .
            __('Remove', 'woocommerce') . '</a>'
        );

        $row_actions = array();

        foreach ($actions as $action => $link) {
            $row_actions[] = '<span class="' . esc_attr($action) . '">' . $link . '</span>';
        }

        $output .= '<div class="row-actions">' . implode(' | ', $row_actions) . '</div>';

        return $output;
    }
    
    /**
     * Column currency_pos.
     *
     * @param  array $key
     * @return string
     */
    public function column_currency_pos($key) {
        $symbol = get_woocommerce_currency_symbol($key['currency_code']);
        $position_list = Currency_Manager::wooer_currency_pos_list($symbol);
        return $position_list[$key['currency_pos']];
    }

    /**
     * Column currency_exchange_rate.
     *
     * @param  array $key
     * @return string
     */
    public function column_currency_exchange_rate($key) {
        return '<code>' . esc_html($key['currency_exchange_rate']) . '</code>';
    }

    /**
     * Get bulk actions.
     *
     * @return array
     */
    protected function get_bulk_actions() {
        return array(
            'remove' => __('Remove', 'woocommerce')
        );
    }

    /**
     * Prepare table list items.
     */
    public function prepare_items() {
        $per_page = 10;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // Column headers
        $this->_column_headers = array($columns, $hidden, $sortable);

        $current_page = $this->get_pagenum();
        if (1 < $current_page) {
            $offset = $per_page * ($current_page - 1);
        } else {
            $offset = 0;
        }

        $items = Exchange_Rate_Model::get_instance()->select(array('*'), 'ASC', $per_page, $offset);
        $count = Exchange_Rate_Model::get_instance()->get_count();

        $this->items = $items;

        // Set the pagination
        $this->set_pagination_args(array(
            'total_items' => $count,
            'per_page' => $per_page,
            'total_pages' => ceil($count / $per_page)
        ));
    }

}
