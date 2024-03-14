<?php
class BeRocket_terms_cond_popup_lib extends BeRocket_custom_post_class {
    public $conditions;
    protected static $instance;
    function __construct() {
        add_action('terms_cond_popup_framework_construct', array($this, 'init_conditions'));
        $this->post_type_parameters = array(
            'sortable' => true
        );
        $this->default_settings = array(
            'page'    => '',
        );
        $this->add_meta_box('conditions', __( 'Conditions', 'terms-and-conditions-popup-for-woocommerce' ));
        parent::__construct();
        add_filter ( 'BeRocket_updater_menu_order_custom_post', array($this, 'menu_order_custom_post') );
    }
    public function init_conditions() {
        $this->conditions = new BeRocket_conditions_terms_cond_popup($this->post_name.'[data]', $this->hook_name, array(
            'condition_week_day',
            'condition_user_status',
            'condition_user_role',
            'condition_shipping_zone',
            'condition_country',
            'condition_product_in_cart',
        ));
    }
    public function conditions($post) {
        $options = $this->get_option( $post->ID );
        echo $this->conditions->build($options['data']);
    }
    public function meta_box_shortcode($post) {
        global $pagenow;
        if( in_array( $pagenow, array( 'post-new.php' ) ) ) {
            _e( 'You need save it to get shortcode', 'terms-and-conditions-popup-for-woocommerce' );
        } else {
            echo "[br_filter_single filter_id={$post->ID}]";
        }
    }
    public function information_faq($post) {
        include splash_popup_TEMPLATE_PATH . "filters_information.php";
    }
    public function settings($post) {
        $options = $this->get_option( $post->ID );
        $BeRocket_terms_cond_popup = BeRocket_terms_cond_popup::getInstance();
        $settings = $BeRocket_terms_cond_popup->get_option();
        $pages = get_all_page_ids();
        $pages_option = array(array('value' => '', 'text' => __('Default', 'terms-and-conditions-popup-for-woocommerce')));
        foreach($pages as $page) {
            $pages_option[] = array('value' => $page, 'text' => get_the_title($page));
        }
        echo '<div class="br_framework_settings br_alabel_settings">';
        $BeRocket_terms_cond_popup->display_admin_settings(
            array(
                'General' => array(
                    'icon' => 'cog',
                ),
            ),
            array(
                'General' => array(
                    'page' => array(
                        "label"     => __( "Page", 'terms-and-conditions-popup-for-woocommerce' ),
                        "name"     => 'page',   
                        "type"     => "selectbox",
                        "options"  => $pages_option,
                        "value"    => '',
                    ),
                ),
            ),
            array(
                'name_for_filters' => $this->hook_name,
                'hide_header' => true,
                'hide_form' => true,
                'hide_additional_blocks' => true,
                'hide_save_button' => true,
                'settings_name' => $this->post_name,
                'options' => $options
            )
        );
        echo '</div>';
    }
    public function replace_terms_page($page_id) {
        $this->get_custom_posts();
        $posts = $this->get_custom_posts();
        foreach($posts as $post_id) {
            $options = $this->get_option($post_id);
            if( empty($options['data']) || $this->conditions->check($options['data'], $this->hook_name) ) {
                if( ! empty($options['page']) ) {
                    $page_id = $options['page'];
                }
                break;
            }
        }
        return $page_id;
    }
    public function wc_save_product( $post_id, $post ) {
        $order_position = get_post_meta( $post_id, 'berocket_post_order', true );
        $order_position = (int)$order_position;
        update_post_meta( $post_id, 'berocket_post_order', $order_position );
        parent::wc_save_product($post_id, $post);
    }

    public function menu_order_custom_post($compatibility) {
        $compatibility[$this->post_name] = 'br-terms_cond_popup';
        return $compatibility;
    }
}
