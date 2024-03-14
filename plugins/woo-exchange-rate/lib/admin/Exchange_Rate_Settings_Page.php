<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WC_Admin_API_Keys.
 */
class Exchange_Rate_Settings_Page {

    const TAB = 'products';
    const SECTION = 'woo-exchange-rate';
    
    private $home_url;
    private $add_button_url;

    /**
     * Initialize admin actions.
     */
    public function __construct() {
        
        $this->home_url = admin_url(sprintf('admin.php?page=wc-settings&tab=%s&section=%s', self::TAB, self::SECTION));
        $this->add_button_url = $this->home_url . '&create=1';
    }

    /**
     * Check if is Currency Exchange settings page.
     * @return bool
     */
    private function is_settings_page() {
        return isset($_GET['page']) &&
                'wc-settings' === $_GET['page'] &&
                isset($_GET['tab']) &&
                self::TAB === $_GET['tab'] &&
                isset($_GET['section']) &&
                self::SECTION === $_GET['section'];
    }

    /**
     * Page output.
     */
    public function page_output() {
        // Hide the save button
        $GLOBALS['hide_save_button'] = true;
        
        $this->actions();
        $this->notices();

        if (isset($_GET['create']) || isset($_GET['edit-id'])) {
            $id = isset($_GET['edit-id']) ? absint($_GET['edit-id']) : 0;
            $data = Exchange_Rate_Model::get_instance()->get_data_by_id($id);
            $this->edit_form_output($data);
        } else {
            $this->table_list_output();
        }
    }

    /**
     * Table list output.
     */
    public function table_list_output() {
        echo '<h2>' . __('Exchange Rates', 'woo-exchange-rate') .
        '<a href="' . $this->add_button_url . '" class="add-new-h2">' .
        __('Add currency exchange rate', 'woo-exchange-rate') .
        '</a></h2>';

        $table_list = new Exchange_Rate_Settings_Page_Table();
        $table_list->prepare_items();

        echo '<input type="hidden" name="page" value="wc-settings" />';
        echo '<input type="hidden" name="tab" value="' . self::TAB . '" />';
        echo '<input type="hidden" name="section" value="' . self::SECTION . '" />';

        $table_list->views();
        $table_list->display();
    }
    
    /**
     * Edit form output.
     */
    public function edit_form_output($data = null) {
        $settings = array();
        $currency_code = !empty($data['currency_code']) ? $data['currency_code'] : null;
        $currencies = Currency_Manager::wooer_currencies_list();
        $currency_pos = Currency_Manager::wooer_currency_pos_list(get_woocommerce_currency_symbol($currency_code));
        
        $settings[] = array(
            'name' => __('Currency Settings', 'woo-exchange-rate'),
            'type' => 'title',
            //'description' => __('Currency Details description', 'woo-exchange-rate'),
            'id' => 'title');

        $settings[] = array(
            'name' => __('Currency', 'woocommerce'),
            'id' => 'currency_code',
            'type' => 'select',
            'options' => $currencies,
            'default' => !empty($data['id']) ? $data['currency_code'] : get_woocommerce_currency(),
            'class' => 'wc-enhanced-select'
        );
        
        $settings[] = array(
            'name' => __( 'Currency Position', 'woocommerce' ),
            'desc' => __('This controls the position of the currency symbol.', 'woocommerce'),
            'id' => 'currency_pos',
            'type' => 'select',
            'options' => $currency_pos,
            'default' => !empty($data['id']) ? $data['currency_pos'] : 'left',
            'class' => 'wc-enhanced-select',
            'desc_tip' =>  true,
        );
        
        $settings[] = array(
            'name' => __('Exchange rate', 'woo-exchange-rate'),
            'desc' => __('Decimal Separator', 'woocommerce') . ' "."',
            'id' => 'currency_exchange_rate',
            'type' => 'text',
            'css' => 'width:350px;',
            'default' => !empty($data['id']) ? $data['currency_exchange_rate'] : '',
            'desc_tip' =>  true,
        );
        
        $settings[] = array('type' => 'sectionend', 'id' => 'woo-exchange-rate');
        // Output settings fields
        \WC_Admin_Settings::output_fields($settings);

        echo '<input type="hidden" id="id" value="' . esc_attr($data['id']) . '" />';
        
        if (isset($data['id']) && !$data['id']) {
            submit_button(__('Add', 'woocommerce'), 'primary', 'save_exchange_rate');
        } else {
            echo '<p class="submit"> ';
            submit_button(__('Save changes', 'woocommerce'), 'primary', 'save_exchange_rate', false);
            echo '<a style="color: #a00; text-decoration: none; margin-left: 10px;" href="';
            echo esc_url(wp_nonce_url(add_query_arg(array('remove-id' => $data['id']), $this->home_url), 'remove'));
            echo '">' . __('Remove', 'woocommerce') . '</a>';
            echo '</p>';
        }
    }
    

    /**
     * Admin actions.
     */
    public function actions() {
        
        if ($this->is_settings_page()) {
            // Remove
            if (isset($_GET['remove-id'])) {
                $this->remove_action();
            }

            // Bulk actions
            if (isset($_POST['action']) && isset($_POST['id'])) {
                $this->bulk_actions();
            }
            
            // Save
            if (isset($_POST['save_exchange_rate'])) {
                $this->save_action(); 
            }

        }
        
    }
    
    /**
     * Notices.
     */
    public function notices() {
        
        if (isset($_GET['removed']) && 1 == $_GET['removed']) {
            \WC_Admin_Settings::add_message(__('Exchange rate successfully removed', 'woo-exchange-rate'));
        }
        if (isset($_GET['saved']) && 1 == $_GET['saved']) {
            \WC_Admin_Settings::add_message(__('Your settings have been saved', 'woo-exchange-rate'));
        }
        
        \WC_Admin_Settings::show_messages();
    }
  
    /**
     * Remove action
     */
    private function remove_action() {
        if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'remove')) {
            wp_die(__('Action failed. Please refresh the page and retry.', 'woocommerce'));
        }

        $id = absint($_GET['remove-id']);
        Exchange_Rate_Model::get_instance()->delete($id);

        wp_redirect(esc_url_raw(add_query_arg(array('removed' => 1), $this->home_url)));
        exit();
    }

    /**
     * Bulk actions
     */
    private function bulk_actions() {
        if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-settings')) {
            wp_die(__('Action failed. Please refresh the page and retry.', 'woocommerce'));
        }

        $ids = array_map('absint', (array) $_POST['id']);

        if ('remove' == $_POST['action']) {
            $this->bulk_remove($ids);
        }
        
        wp_redirect(esc_url_raw(add_query_arg(array('removed' => 1), $this->home_url)));
        exit();
    }

    /**
     * Bulk remove
     *
     * @param array $ids
     */
    private function bulk_remove($ids) {
        foreach ($ids as $id) {
            Exchange_Rate_Model::get_instance()->delete($id);
        }
    }
    
    private function save_action() {
        if ($this->save() === FALSE) {
            wp_die(__('Action failed. Please refresh the page and retry.', 'woocommerce'));
        }
        
        wp_redirect(esc_url_raw(add_query_arg(array('saved' => 1), $this->home_url)));
        exit();
        
    }
    
    /**
     * Saving
     * @return bool
     */
    private function save() {
        // Verify request params
        if (array_diff(array('currency_code', 'currency_exchange_rate', 'currency_pos'), array_keys($_REQUEST))) {
            return false;
        }

        $data = [
            'id' => null,
            'currency_code' => $_REQUEST['currency_code'],
            'currency_pos' => $_REQUEST['currency_pos'],
            'currency_exchange_rate' => $_REQUEST['currency_exchange_rate']
        ];

        if (isset($_GET['edit-id'])) {
            $data['id'] = $_GET['edit-id'];
        }

        return Exchange_Rate_Model::get_instance()->save($data);
    }

    /**
     * Returns plugin settings home page URL string
     * @return string
     */
    public function get_home_url() {
        return $this->home_url;        
    }

}
