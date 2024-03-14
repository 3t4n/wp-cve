<?php
class BeRocket_terms_cond_popup_Paid extends BeRocket_plugin_variations {
    public $plugin_name = 'terms_cond_popup';
    public $version_number = 15;
    public function __construct() {
        $this->info = array(
            'id'          => 13,
            'lic_id'      => 77,
            'version'     => BeRocket_terms_cond_popup_version,
            'plugin_name' => 'terms_cond_popup',
            'domain'      => 'terms-and-conditions-popup-for-woocommerce',
            'templates'   => terms_cond_popup_TEMPLATE_PATH,
        );
        $this->values = array(
            'settings_name' => 'br-terms_cond_popup-options',
            'option_page'   => 'br-terms_cond_popup',
            'premium_slug'  => 'woocommerce-terms-and-conditions-popup',
            'free_slug'     => 'terms-and-conditions-popup-for-woocommerce',
        );
        $this->default = array();
        parent::__construct();
    }
}
new BeRocket_terms_cond_popup_Paid();
