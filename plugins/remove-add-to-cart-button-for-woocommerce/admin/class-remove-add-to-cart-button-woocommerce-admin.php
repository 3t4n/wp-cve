<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpartisan.net/
 * @since      1.0.0
 *
 * @package    Remove_Add_To_Cart_Button_Woocommerce
 * @subpackage Remove_Add_To_Cart_Button_Woocommerce/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Remove_Add_To_Cart_Button_Woocommerce
 * @subpackage Remove_Add_To_Cart_Button_Woocommerce/admin
 * @author     wpArtisan
 */
class Remove_Add_To_Cart_Button_Woocommerce_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function ratcw_enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Remove_Add_To_Cart_Button_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Remove_Add_To_Cart_Button_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/remove-add-to-cart-button-woocommerce-admin.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function ratcw_enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Remove_Add_To_Cart_Button_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Remove_Add_To_Cart_Button_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/remove-add-to-cart-button-woocommerce-admin.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /**
     * Register the Tab by hooking into the 'woocommerce_product_data_tabs' filter.
     *
     * @since    1.0.0
     */
    public function ratcw_remove_add_to_cart_button_data_tab( $product_data_tabs )
    {
        $new_custom_tab['ratcw-remove-add-to-cart-button'] = array(
            'label'  => esc_html__( 'Remove Add to Cart Button', 'remove-add-to-cart-button-woocommerce' ),
            'target' => 'ratcw_remove_add_to_cart_button_product_data',
            'class'  => array( '' ),
        );
        $insert_at_position = 3;
        $tabs = array_slice(
            $product_data_tabs,
            0,
            $insert_at_position,
            true
        );
        $tabs = array_merge( $tabs, $new_custom_tab );
        $tabs = array_merge( $tabs, array_slice(
            $product_data_tabs,
            $insert_at_position,
            null,
            true
        ) );
        return $tabs;
    }
    
    /**
     * CSS To Add Custom tab Icon.
     *
     * @since    1.0.0
     */
    public function ratcw_custom_style()
    {
        echo  '<style>#woocommerce-product-data ul.wc-tabs li.ratcw-remove-add-to-cart-button_options a::before {
			font-family: Dashicons;
			content: "\\f153";
		}</style>' ;
    }
    
    /**
     * Functions you can call to output the settings fields for remove add to cart button like text boxes, select boxes, etc.
     *
     * @since    1.0.0
     */
    public function ratcw_remove_add_to_cart_button_product_data_fields()
    {
        global  $post, $wp_roles ;
        $roles = $wp_roles->roles;
        $countries_obj = new WC_Countries();
        $countries = $countries_obj->get_allowed_countries();
        if ( !empty($countries) ) {
            $countries = array(
                '' => esc_html__( 'Select countries', 'remove-add-to-cart-button-woocommerce' ),
            ) + $countries;
        }
        ?> 
		<div id='ratcw_remove_add_to_cart_button_product_data' class='panel woocommerce_options_panel'>
			<div class = 'options_group' > 
			<?php 
        $options_array = array(
            ''                      => esc_html__( 'Please select', 'remove-add-to-cart-button-woocommerce' ),
            'for-all'               => esc_html__( 'For All', 'remove-add-to-cart-button-woocommerce' ),
            'hide-only-for-visitor' => esc_html__( 'Only for Visitors', 'remove-add-to-cart-button-woocommerce' ),
        );
        woocommerce_wp_select( array(
            'id'          => 'ratcw_remove_cart_button_for',
            'value'       => get_post_meta( get_the_ID(), 'ratcw_remove_cart_button_for', true ),
            'label'       => esc_html__( 'Remove Add to Cart For :', 'remove-add-to-cart-button-woocommerce' ),
            'desc_tip'    => true,
            'options'     => $options_array,
            'description' => esc_attr__( 'Select a option to remove Add to Cart button for', 'remove-add-to-cart-button-woocommerce' ),
        ) );
        woocommerce_wp_checkbox( array(
            'id'          => 'ratcw_hide_price',
            'value'       => esc_attr( get_post_meta( get_the_ID(), 'ratcw_hide_price', true ) ),
            'label'       => esc_html__( 'Hide product price :', 'remove-add-to-cart-button-woocommerce' ),
            'desc_tip'    => true,
            'description' => esc_attr__( 'Hide product price in frontend', 'remove-add-to-cart-button-woocommerce' ),
        ) );
        woocommerce_wp_checkbox( array(
            'id'          => 'ratcw_show_login_btn_when_cart_button_hidden',
            'value'       => esc_attr( get_post_meta( get_the_ID(), 'ratcw_show_login_btn_when_cart_button_hidden', true ) ),
            'label'       => esc_html__( 'Show Login/Register button ?', 'remove-add-to-cart-button-woocommerce' ),
            'desc_tip'    => true,
            'description' => esc_attr__( 'Show Login/Register button when Add to Cart button is hidden in frontend', 'remove-add-to-cart-button-woocommerce' ),
        ) );
        woocommerce_wp_textarea_input( array(
            'id'          => 'ratcw_msg_instead_cart_button',
            'value'       => esc_attr( get_post_meta( get_the_ID(), 'ratcw_msg_instead_cart_button', true ) ),
            'label'       => esc_html__( 'Message :', 'remove-add-to-cart-button-woocommerce' ),
            'desc_tip'    => true,
            'description' => esc_attr__( 'Message instead of Add to Cart for this product in frontend. If you don\'t show the message then nothings need to add here', 'remove-add-to-cart-button-woocommerce' ),
        ) );
        ?> 
			</div>
			<?php 
        
        if ( ratcbw_fs()->is_not_paying() ) {
            echo  '<div class="un-con">' ;
            echo  '<h3>' . esc_html__( 'Awesome Premium Features in Remove Add to Cart Button for WooCommerce Plugin', 'remove-add-to-cart-button-woocommerce' ) . '</h3>' ;
            echo  '<ul>' ;
            echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Remove Add to Cart Button Based on User Roles', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
            echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Remove Add to Cart Button Based on Countries', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
            echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Set Category Wise Remove Add to Cart Button Conditions', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
            echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Priority email support', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
            echo  '</ul>' ;
            echo  '<a href="' . ratcbw_fs()->get_upgrade_url() . '" class="upgradebtn">' . esc_html__( 'Upgrade Now!', 'remove-product-description-woocommerce' ) . '</a>' ;
            echo  "</div>" ;
        }
        
        ?>
		</div>
		<?php 
    }
    
    /**
     * Save extra options values.
     *
     * @since    1.0.0
     */
    public function ratcw_save_fields( $id, $post )
    {
        $ratcw_remove_cart_button_for = sanitize_text_field( $_POST['ratcw_remove_cart_button_for'] );
        $ratcw_remove_cart_button_for = ( $ratcw_remove_cart_button_for ? $ratcw_remove_cart_button_for : '' );
        update_post_meta( $id, 'ratcw_remove_cart_button_for', $ratcw_remove_cart_button_for );
        $ratcw_hide_price = sanitize_text_field( $_POST['ratcw_hide_price'] );
        $ratcw_hide_price = ( $ratcw_hide_price ? $ratcw_hide_price : '' );
        update_post_meta( $id, 'ratcw_hide_price', $ratcw_hide_price );
        $ratcw_show_login_btn_when_cart_button_hidden = sanitize_text_field( $_POST['ratcw_show_login_btn_when_cart_button_hidden'] );
        $ratcw_show_login_btn_when_cart_button_hidden = ( $ratcw_show_login_btn_when_cart_button_hidden ? $ratcw_show_login_btn_when_cart_button_hidden : '' );
        update_post_meta( $id, 'ratcw_show_login_btn_when_cart_button_hidden', $ratcw_show_login_btn_when_cart_button_hidden );
        $ratcw_msg_instead_cart_button = sanitize_textarea_field( $_POST['ratcw_msg_instead_cart_button'] );
        $ratcw_msg_instead_cart_button = ( $ratcw_msg_instead_cart_button ? $ratcw_msg_instead_cart_button : '' );
        update_post_meta( $id, 'ratcw_msg_instead_cart_button', $ratcw_msg_instead_cart_button );
    }
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     * @since    1.0.0
     */
    public function ratcw_add_settings_tab( $settings_tabs )
    {
        $settings_tabs['remove-add-to-cart-button-settings'] = esc_html__( 'Visibility Settings', 'remove-add-to-cart-button-woocommerce' );
        return $settings_tabs;
    }
    
    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses $this->wcuo_settings_tab()
     * @since    1.0.0
     */
    public function ratcw_settings_tab()
    {
        woocommerce_admin_fields( $this->ratcw_get_settings() );
        
        if ( ratcbw_fs()->is_not_paying() ) {
            echo  '<div class="un-con">' ;
            echo  '<h3>' . esc_html__( 'Awesome Premium Features in Remove Add to Cart Button for WooCommerce Plugin', 'remove-add-to-cart-button-woocommerce' ) . '</h3>' ;
            echo  '<ul>' ;
            echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Remove Add to Cart Button Based on User Roles', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
            echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Remove Add to Cart Button Based on Countries', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
            echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Set Category Wise Remove Add to Cart Button Conditions', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
            echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Priority email support', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
            echo  '</ul>' ;
            echo  '<a href="' . ratcbw_fs()->get_upgrade_url() . '" class="upgradebtn">' . esc_html__( 'Upgrade Now!', 'remove-product-description-woocommerce' ) . '</a>' ;
            echo  "</div>" ;
        }
    
    }
    
    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     * @since    1.0.0
     */
    public function ratcw_get_settings()
    {
        $settings = array(
            'section_title'         => array(
            'name' => esc_html__( 'Button Settings', 'remove-add-to-cart-button-woocommerce' ),
            'type' => 'title',
            'desc' => '',
            'id'   => 'ratcw_section_title',
        ),
            'login_reg_page_url'    => array(
            'name'     => esc_html__( 'Enter the Login/Register page URL here', 'remove-add-to-cart-button-woocommerce' ),
            'type'     => 'text',
            'desc'     => esc_html__( 'Enter the Login/Register page URL here. By default, it will go to the My Account page for Login/Register if this field is empty.', 'remove-add-to-cart-button-woocommerce' ),
            'desc_tip' => true,
            'id'       => 'ratcw_login_reg_page_url',
        ),
            'login_reg_button_text' => array(
            'name'     => esc_html__( 'Enter the Login/Register button label', 'remove-add-to-cart-button-woocommerce' ),
            'type'     => 'text',
            'desc'     => esc_html__( 'Enter the Login/Register button label which will show in frontend.', 'remove-add-to-cart-button-woocommerce' ),
            'desc_tip' => true,
            'id'       => 'ratcw_login_reg_button_text',
        ),
            'section_end'           => array(
            'type' => 'sectionend',
            'id'   => 'ratcw_section_end',
        ),
        );
        return apply_filters( 'wc_remove_add_to_cart_button_settings', $settings );
    }
    
    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses $this->get_settings()
     * @since    1.0.0
     */
    public function ratcw_update_settings()
    {
        woocommerce_update_options( $this->ratcw_get_settings() );
    }

}