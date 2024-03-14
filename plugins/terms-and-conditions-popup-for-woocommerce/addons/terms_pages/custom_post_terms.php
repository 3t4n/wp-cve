<?php
class BeRocket_terms_cond_popup_post extends BeRocket_terms_cond_popup_lib {
    public $hook_name = 'berocket_terms_cond_popup_post';
    public $conditions;
    protected static $instance;
    function __construct() {
        $this->post_name = 'br_terms_cond_page';
        $this->post_settings = array(
            'label' => __( 'Terms Page', 'terms-and-conditions-popup-for-woocommerce' ),
            'labels' => array(
                'name'               => __( 'Terms Pages', 'terms-and-conditions-popup-for-woocommerce' ),
                'singular_name'      => __( 'Terms Page', 'terms-and-conditions-popup-for-woocommerce' ),
                'menu_name'          => _x( 'Terms Pages', 'Admin menu name', 'terms-and-conditions-popup-for-woocommerce' ),
                'add_new'            => __( 'Add Terms Page', 'terms-and-conditions-popup-for-woocommerce' ),
                'add_new_item'       => __( 'Add New Terms Page', 'terms-and-conditions-popup-for-woocommerce' ),
                'edit'               => __( 'Edit', 'terms-and-conditions-popup-for-woocommerce' ),
                'edit_item'          => __( 'Edit Terms Page', 'terms-and-conditions-popup-for-woocommerce' ),
                'new_item'           => __( 'New Terms Page', 'terms-and-conditions-popup-for-woocommerce' ),
                'view'               => __( 'View Terms Pages', 'terms-and-conditions-popup-for-woocommerce' ),
                'view_item'          => __( 'View Terms Page', 'terms-and-conditions-popup-for-woocommerce' ),
                'search_items'       => __( 'Search Terms Pages', 'terms-and-conditions-popup-for-woocommerce' ),
                'not_found'          => __( 'No Terms Pages found', 'terms-and-conditions-popup-for-woocommerce' ),
                'not_found_in_trash' => __( 'No Terms Pages found in trash', 'terms-and-conditions-popup-for-woocommerce' ),
            ),
            'description'     => __( 'This is where you can add Terms Pages.', 'terms-and-conditions-popup-for-woocommerce' ),
            'public'          => true,
            'show_ui'         => true,
            'capability_type' => 'product',
            'map_meta_cap'    => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_in_menu'        => 'berocket_account',
            'hierarchical'        => false,
            'rewrite'             => false,
            'query_var'           => false,
            'supports'            => array( 'title' ),
            'show_in_nav_menus'   => false,
        );
        parent::__construct();
        $this->add_meta_box('settings', __( 'Terms page Settings', 'terms-and-conditions-popup-for-woocommerce' ));
        if( ! is_admin() ) {
            add_filter('woocommerce_get_terms_page_id', array($this, 'replace_terms_page'));
        }
    }
}
new BeRocket_terms_cond_popup_post();
