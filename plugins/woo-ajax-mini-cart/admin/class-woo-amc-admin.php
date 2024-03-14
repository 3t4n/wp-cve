<?php

/**
 * The admin-specific functionality of the plugin.
 */
class WooAmcAdmin {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


	}

	/**
	 * Add styles for admin
	 */
	public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-amc-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'wp-color-picker', '', array(), $this->version, 'all' );

	}

	/**
	 * Add js code for admin
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-amc-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );

	}


    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_submenu_page(
            'woocommerce',
            'Ajax Mini Cart',
            'Ajax Mini Cart',
            'manage_options',
            'woocommerce-ajax-mini-cart',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'woo_amc_options' );
        ?>
        <div class="wrap">
            <h1>Ajax Mini Cart</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'woo_amc_group' );
                do_settings_sections( 'woocommerce-ajax-mini-cart' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'woo_amc_group', // Option group
            'woo_amc_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'woo_amc_section_general', // ID
            'General settings', // Title
            '', // Callback
            'woocommerce-ajax-mini-cart' // Page
        );

        add_settings_section(
            'woo_amc_section_button', // ID
            'Button settings', // Title
            '', // Callback
            'woocommerce-ajax-mini-cart' // Page
        );

        add_settings_section(
            'woo_amc_section_bg', // ID
            'Background settings', // Title
            '', // Callback
            'woocommerce-ajax-mini-cart' // Page
        );

        add_settings_section(
            'woo_amc_section_cart', // ID
            'Cart block', // Title
            '', // Callback
            'woocommerce-ajax-mini-cart' // Page
        );

        add_settings_section(
            'woo_amc_section_cart_header', // ID
            'Cart block header', // Title
            '', // Callback
            'woocommerce-ajax-mini-cart' // Page
        );

        add_settings_section(
            'woo_amc_section_cart_item', // ID
            'Cart block item', // Title
            '', // Callback
            'woocommerce-ajax-mini-cart' // Page
        );

        add_settings_section(
            'woo_amc_section_cart_footer', // ID
            'Cart block footer', // Title
            '', // Callback
            'woocommerce-ajax-mini-cart' // Page
        );

        add_settings_field(
            'enabled', // ID
            'Enabled', // Title
            array( $this, 'enabled_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_general' // Section
        );

        add_settings_field(
            'cart_type', // ID
            'Type', // Title
            array( $this, 'cart_type_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_general' // Section
        );

        add_settings_field(
            'button_icon_color', // ID
            'Icon Color', // Title
            array( $this, 'button_icon_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_button' // Section
        );
        add_settings_field(
            'button_bg_color', // ID
            'Background Color', // Title
            array( $this, 'button_bg_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_button' // Section
        );

        add_settings_field(
            'button_border_radius', // ID
            'Border Radius', // Title
            array( $this, 'button_border_radius_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_button' // Section
        );

        add_settings_field(
            'button_position', // ID
            'Position', // Title
            array( $this, 'button_position_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_button' // Section
        );

        add_settings_field(
            'button_count_bg', // ID
            'Count background', // Title
            array( $this, 'button_count_bg_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_button' // Section
        );

        add_settings_field(
            'button_count_color', // ID
            'Count color', // Title
            array( $this, 'button_count_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_button' // Section
        );

        add_settings_field(
            'bg_color', // ID
            'Color', // Title
            array( $this, 'bg_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_bg' // Section
        );

        add_settings_field(
            'bg_opacity', // ID
            'Opacity', // Title
            array( $this, 'bg_opacity_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_bg' // Section
        );

        add_settings_field(
            'cart_bg', // ID
            'Background', // Title
            array( $this, 'cart_bg_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart' // Section
        );

        add_settings_field(
            'cart_loader_color', // ID
            'Loader Color', // Title
            array( $this, 'cart_loader_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart' // Section
        );

        add_settings_field(
            'cart_header_bg', // ID
            'Background', // Title
            array( $this, 'cart_header_bg_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_header' // Section
        );
        add_settings_field(
            'cart_header_title', // ID
            'Title', // Title
            array( $this, 'cart_header_title_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_header' // Section
        );
        add_settings_field(
            'cart_header_title_size', // ID
            'Title font size', // Title
            array( $this, 'cart_header_title_size_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_header' // Section
        );
        add_settings_field(
            'cart_header_title_color', // ID
            'Title color', // Title
            array( $this, 'cart_header_title_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_header' // Section
        );
        add_settings_field(
            'cart_header_close_color', // ID
            'Close button color', // Title
            array( $this, 'cart_header_close_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_header' // Section
        );

        add_settings_field(
            'cart_item_bg', // ID
            'Background', // Title
            array( $this, 'cart_item_bg_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_border_width', // ID
            'Border width', // Title
            array( $this, 'cart_item_border_width_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_border_color', // ID
            'Border color', // Title
            array( $this, 'cart_item_border_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_border_radius', // ID
            'Border radius', // Title
            array( $this, 'cart_item_border_radius_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_padding', // ID
            'Padding', // Title
            array( $this, 'cart_item_padding_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_close_color', // ID
            'Close button color', // Title
            array( $this, 'cart_item_close_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_title_color', // ID
            'Title color', // Title
            array( $this, 'cart_item_title_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_title_size', // ID
            'Title font size', // Title
            array( $this, 'cart_item_title_size_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_text_color', // ID
            'Text color', // Title
            array( $this, 'cart_item_text_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_text_size', // ID
            'Text font size', // Title
            array( $this, 'cart_item_text_size_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_old_price_color', // ID
            'Old price color', // Title
            array( $this, 'cart_item_old_price_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_price_color', // ID
            'Price color', // Title
            array( $this, 'cart_item_price_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_quantity_buttons_color', // ID
            'Quantity buttons color', // Title
            array( $this, 'cart_item_quantity_buttons_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_quantity_color', // ID
            'Quantity color', // Title
            array( $this, 'cart_item_quantity_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_quantity_bg', // ID
            'Quantity background', // Title
            array( $this, 'cart_item_quantity_bg_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_quantity_border_radius', // ID
            'Quantity border radius', // Title
            array( $this, 'cart_item_quantity_border_radius_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_big_price_size', // ID
            'Big price font size', // Title
            array( $this, 'cart_item_big_price_size_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );
        add_settings_field(
            'cart_item_big_price_color', // ID
            'Big price color', // Title
            array( $this, 'cart_item_big_price_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_item' // Section
        );

        add_settings_field(
            'cart_footer_bg', // ID
            'Background', // Title
            array( $this, 'cart_footer_bg_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_products_size', // ID
            'Products line font size', // Title
            array( $this, 'cart_footer_products_size_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_products_label', // ID
            'Products line label', // Title
            array( $this, 'cart_footer_products_label_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_products_label_color', // ID
            'Products line label color', // Title
            array( $this, 'cart_footer_products_label_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_products_count_color', // ID
            'Products line count color', // Title
            array( $this, 'cart_footer_products_count_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_total_size', // ID
            'Total line font size', // Title
            array( $this, 'cart_footer_total_size_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_total_label', // ID
            'Total line label', // Title
            array( $this, 'cart_footer_total_label_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_total_label_color', // ID
            'Total line label color', // Title
            array( $this, 'cart_footer_total_label_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_total_price_color', // ID
            'Total line price color', // Title
            array( $this, 'cart_footer_total_price_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_link_text', // ID
            'Link text', // Title
            array( $this, 'cart_footer_link_text_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_link_size', // ID
            'Link font size', // Title
            array( $this, 'cart_footer_link_size_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );
        add_settings_field(
            'cart_footer_link_color', // ID
            'Link color', // Title
            array( $this, 'cart_footer_link_color_callback' ), // Callback
            'woocommerce-ajax-mini-cart', // Page
            'woo_amc_section_cart_footer' // Section
        );

    }

    /**
     * Sanitize each setting field as needed
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['enabled'] ) )
            $new_input['enabled'] = sanitize_text_field( $input['enabled'] );

        if( isset( $input['cart_type'] ) )
            $new_input['cart_type'] = sanitize_text_field( $input['cart_type'] );

        if( isset( $input['button_icon_color'] ) )
            $new_input['button_icon_color'] = sanitize_text_field( $input['button_icon_color'] );

        if( isset( $input['button_bg_color'] ) )
            $new_input['button_bg_color'] = sanitize_text_field( $input['button_bg_color'] );

        if( isset( $input['button_border_radius'] ) )
            $new_input['button_border_radius'] = absint( $input['button_border_radius'] );

        if( isset( $input['button_position'] ) )
            $new_input['button_position'] = sanitize_text_field( $input['button_position'] );

        if( isset( $input['button_count_bg'] ) )
            $new_input['button_count_bg'] = sanitize_text_field( $input['button_count_bg'] );

        if( isset( $input['button_count_color'] ) )
            $new_input['button_count_color'] = sanitize_text_field( $input['button_count_color'] );

        if( isset( $input['bg_color'] ) )
            $new_input['bg_color'] = sanitize_text_field( $input['bg_color'] );

        if( isset( $input['bg_opacity'] ) )
            $new_input['bg_opacity'] = absint( $input['bg_opacity'] );

        if( isset( $input['cart_bg'] ) )
            $new_input['cart_bg'] = sanitize_text_field( $input['cart_bg'] );

        if( isset( $input['cart_loader_color'] ) )
            $new_input['cart_loader_color'] = sanitize_text_field( $input['cart_loader_color'] );

        if( isset( $input['cart_header_bg'] ) )
            $new_input['cart_header_bg'] = sanitize_text_field( $input['cart_header_bg'] );

        if( isset( $input['cart_header_title'] ) )
            $new_input['cart_header_title'] = sanitize_text_field( $input['cart_header_title'] );

        if( isset( $input['cart_header_title_size'] ) )
            $new_input['cart_header_title_size'] = sanitize_text_field( $input['cart_header_title_size'] );

        if( isset( $input['cart_header_title_color'] ) )
            $new_input['cart_header_title_color'] = sanitize_text_field( $input['cart_header_title_color'] );

        if( isset( $input['cart_header_close_color'] ) )
            $new_input['cart_header_close_color'] = sanitize_text_field( $input['cart_header_close_color'] );

        if( isset( $input['cart_item_bg'] ) )
            $new_input['cart_item_bg'] = sanitize_text_field( $input['cart_item_bg'] );

        if( isset( $input['cart_item_border_width'] ) )
            $new_input['cart_item_border_width'] = absint( $input['cart_item_border_width'] );

        if( isset( $input['cart_item_border_color'] ) )
            $new_input['cart_item_border_color'] = sanitize_text_field( $input['cart_item_border_color'] );

        if( isset( $input['cart_item_border_radius'] ) )
            $new_input['cart_item_border_radius'] = absint( $input['cart_item_border_radius'] );

        if( isset( $input['cart_item_padding'] ) )
            $new_input['cart_item_padding'] = absint( $input['cart_item_padding'] );

        if( isset( $input['cart_item_close_color'] ) )
            $new_input['cart_item_close_color'] = sanitize_text_field( $input['cart_item_close_color'] );

        if( isset( $input['cart_item_title_color'] ) )
            $new_input['cart_item_title_color'] = sanitize_text_field( $input['cart_item_title_color'] );

        if( isset( $input['cart_item_title_size'] ) )
            $new_input['cart_item_title_size'] = absint( $input['cart_item_title_size'] );

        if( isset( $input['cart_item_text_color'] ) )
            $new_input['cart_item_text_color'] = sanitize_text_field( $input['cart_item_text_color'] );

        if( isset( $input['cart_item_text_size'] ) )
            $new_input['cart_item_text_size'] = absint( $input['cart_item_text_size'] );

        if( isset( $input['cart_item_old_price_color'] ) )
            $new_input['cart_item_old_price_color'] = sanitize_text_field( $input['cart_item_old_price_color'] );

        if( isset( $input['cart_item_price_color'] ) )
            $new_input['cart_item_price_color'] = sanitize_text_field( $input['cart_item_price_color'] );

        if( isset( $input['cart_item_quantity_buttons_color'] ) )
            $new_input['cart_item_quantity_buttons_color'] = sanitize_text_field( $input['cart_item_quantity_buttons_color'] );

        if( isset( $input['cart_item_quantity_color'] ) )
            $new_input['cart_item_quantity_color'] = sanitize_text_field( $input['cart_item_quantity_color'] );

        if( isset( $input['cart_item_quantity_bg'] ) )
            $new_input['cart_item_quantity_bg'] = sanitize_text_field( $input['cart_item_quantity_bg'] );

        if( isset( $input['cart_item_quantity_border_radius'] ) )
            $new_input['cart_item_quantity_border_radius'] = absint( $input['cart_item_quantity_border_radius'] );

        if( isset( $input['cart_item_big_price_size'] ) )
            $new_input['cart_item_big_price_size'] = absint( $input['cart_item_big_price_size'] );

        if( isset( $input['cart_item_big_price_color'] ) )
            $new_input['cart_item_big_price_color'] = sanitize_text_field( $input['cart_item_big_price_color'] );

        if( isset( $input['cart_footer_bg'] ) )
            $new_input['cart_footer_bg'] = sanitize_text_field( $input['cart_footer_bg'] );

        if( isset( $input['cart_footer_products_size'] ) )
            $new_input['cart_footer_products_size'] = absint( $input['cart_footer_products_size'] );

        if( isset( $input['cart_footer_products_label'] ) )
            $new_input['cart_footer_products_label'] = sanitize_text_field( $input['cart_footer_products_label'] );

        if( isset( $input['cart_footer_products_label_color'] ) )
            $new_input['cart_footer_products_label_color'] = sanitize_text_field( $input['cart_footer_products_label_color'] );

        if( isset( $input['cart_footer_products_count_color'] ) )
            $new_input['cart_footer_products_count_color'] = sanitize_text_field( $input['cart_footer_products_count_color'] );

        if( isset( $input['cart_footer_total_size'] ) )
            $new_input['cart_footer_total_size'] = absint( $input['cart_footer_total_size'] );

        if( isset( $input['cart_footer_total_label'] ) )
            $new_input['cart_footer_total_label'] = sanitize_text_field( $input['cart_footer_total_label'] );

        if( isset( $input['cart_footer_total_label_color'] ) )
            $new_input['cart_footer_total_label_color'] = sanitize_text_field( $input['cart_footer_total_label_color'] );

        if( isset( $input['cart_footer_total_price_color'] ) )
            $new_input['cart_footer_total_price_color'] = sanitize_text_field( $input['cart_footer_total_price_color'] );

        if( isset( $input['cart_footer_link_text'] ) )
            $new_input['cart_footer_link_text'] = sanitize_text_field( $input['cart_footer_link_text'] );

        if( isset( $input['cart_footer_link_size'] ) )
            $new_input['cart_footer_link_size'] = absint( $input['cart_footer_link_size'] );

        if( isset( $input['cart_footer_link_color'] ) )
            $new_input['cart_footer_link_color'] = sanitize_text_field( $input['cart_footer_link_color'] );

        return $new_input;
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function enabled_callback()
    {
        ?>
        <select name="woo_amc_options[enabled]">
            <option value="yes" <?php selected($this->options['enabled'], "yes"); ?>>Yes</option>
            <option value="no" <?php selected($this->options['enabled'], "no"); ?>>No</option>
        </select>
        <?php
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_type_callback()
    {
        ?>
        <select name="woo_amc_options[cart_type]">
            <option value="left" <?php selected($this->options['cart_type'], "left"); ?>>Left</option>
            <option value="center" <?php selected($this->options['cart_type'], "center"); ?>>Center</option>
            <option value="right" <?php selected($this->options['cart_type'], "right"); ?>>Right</option>
        </select>
        <?php
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function button_icon_color_callback()
    {
        printf(
            '<input type="text" id="button_icon_color" class="woo_amc_select_color" name="woo_amc_options[button_icon_color]" value="%s" />',
            isset( $this->options['button_icon_color'] ) ? esc_attr( $this->options['button_icon_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function button_bg_color_callback()
    {
        printf(
            '<input type="text" id="button_bg_color" class="woo_amc_select_color" name="woo_amc_options[button_bg_color]" value="%s" />',
            isset( $this->options['button_bg_color'] ) ? esc_attr( $this->options['button_bg_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function button_border_radius_callback()
    {
        printf(
            '<input type="text" id="button_border_radius" name="woo_amc_options[button_border_radius]" value="%s" size="4" /> px',
            isset( $this->options['button_border_radius'] ) ? esc_attr( $this->options['button_border_radius']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function button_position_callback()
    {
        ?>
        <select name="woo_amc_options[button_position]">
            <option value="right-top" <?php selected($this->options['button_position'], "right-top"); ?>>Right Top</option>
            <option value="left-top" <?php selected($this->options['button_position'], "left-top"); ?>>Left Top</option>
            <option value="right-top-fixed" <?php selected($this->options['button_position'], "right-top-fixed"); ?>>Right Top Fixed</option>
            <option value="left-top-fixed" <?php selected($this->options['button_position'], "left-top-fixed"); ?>>Left Top Fixed</option>
            <option value="right-bottom-fixed" <?php selected($this->options['button_position'], "right-bottom-fixed"); ?>>Right Bottom Fixed</option>
            <option value="left-bottom-fixed" <?php selected($this->options['button_position'], "left-bottom-fixed"); ?>>Left Bottom Fixed</option>
        </select>
        <?php
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function button_count_bg_callback()
    {
        printf(
            '<input type="text" id="button_count_bg" class="woo_amc_select_color" name="woo_amc_options[button_count_bg]" value="%s" />',
            isset( $this->options['button_count_bg'] ) ? esc_attr( $this->options['button_count_bg']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function button_count_color_callback()
    {
        printf(
            '<input type="text" id="button_count_color" class="woo_amc_select_color" name="woo_amc_options[button_count_color]" value="%s" />',
            isset( $this->options['button_count_color'] ) ? esc_attr( $this->options['button_count_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function bg_color_callback()
    {
        printf(
            '<input type="text" id="bg_color" class="woo_amc_select_color" name="woo_amc_options[bg_color]" value="%s" />',
            isset( $this->options['bg_color'] ) ? esc_attr( $this->options['bg_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function bg_opacity_callback()
    {
        printf(
            '<input type="text" id="bg_opacity" size="4" name="woo_amc_options[bg_opacity]" value="%s" /> %%',
            isset( $this->options['bg_opacity'] ) ? esc_attr( $this->options['bg_opacity']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_bg_callback()
    {
        printf(
            '<input type="text" id="cart_bg" class="woo_amc_select_color" name="woo_amc_options[cart_bg]" value="%s" />',
            isset( $this->options['cart_bg'] ) ? esc_attr( $this->options['cart_bg']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_loader_color_callback()
    {
        printf(
            '<input type="text" id="cart_loader_color" class="woo_amc_select_color" name="woo_amc_options[cart_loader_color]" value="%s" />',
            isset( $this->options['cart_bg'] ) ? esc_attr( $this->options['cart_loader_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_header_bg_callback()
    {
        printf(
            '<input type="text" id="cart_header_bg" class="woo_amc_select_color" name="woo_amc_options[cart_header_bg]" value="%s" />',
            isset( $this->options['cart_header_bg'] ) ? esc_attr( $this->options['cart_header_bg']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_header_title_callback()
    {
        printf(
            '<input type="text" id="cart_header_title" name="woo_amc_options[cart_header_title]" value="%s" />',
            isset( $this->options['cart_header_title'] ) ? esc_attr( $this->options['cart_header_title']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_header_title_size_callback()
    {
        printf(
            '<input type="text" id="cart_header_title_size" name="woo_amc_options[cart_header_title_size]" size="4" value="%s" /> px',
            isset( $this->options['cart_header_title_size'] ) ? esc_attr( $this->options['cart_header_title_size']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_header_title_color_callback()
    {
        printf(
            '<input type="text" id="cart_header_title_color" class="woo_amc_select_color" name="woo_amc_options[cart_header_title_color]" value="%s" />',
            isset( $this->options['cart_header_title_color'] ) ? esc_attr( $this->options['cart_header_title_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_header_close_color_callback()
    {
        printf(
            '<input type="text" id="cart_header_close_color" class="woo_amc_select_color" name="woo_amc_options[cart_header_close_color]" value="%s" />',
            isset( $this->options['cart_header_close_color'] ) ? esc_attr( $this->options['cart_header_close_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_bg_callback()
    {
        printf(
            '<input type="text" id="cart_item_bg" class="woo_amc_select_color" name="woo_amc_options[cart_item_bg]" value="%s" />',
            isset( $this->options['cart_item_bg'] ) ? esc_attr( $this->options['cart_item_bg']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_border_width_callback()
    {
        printf(
            '<input type="text" id="cart_item_border_width" name="woo_amc_options[cart_item_border_width]" value="%s" size="4" /> px',
            isset( $this->options['cart_item_border_width'] ) ? esc_attr( $this->options['cart_item_border_width']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_border_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_border_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_border_color]" value="%s" />',
            isset( $this->options['cart_item_border_color'] ) ? esc_attr( $this->options['cart_item_border_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_border_radius_callback()
    {
        printf(
            '<input type="text" id="cart_item_border_radius" size="4" name="woo_amc_options[cart_item_border_radius]" value="%s" /> px',
            isset( $this->options['cart_item_border_radius'] ) ? esc_attr( $this->options['cart_item_border_radius']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_padding_callback()
    {
        printf(
            '<input type="text" id="cart_item_padding" size="4" name="woo_amc_options[cart_item_padding]" value="%s" /> px',
            isset( $this->options['cart_item_padding'] ) ? esc_attr( $this->options['cart_item_padding']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_close_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_close_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_close_color]" value="%s" />',
            isset( $this->options['cart_item_close_color'] ) ? esc_attr( $this->options['cart_item_close_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_title_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_title_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_title_color]" value="%s" />',
            isset( $this->options['cart_item_title_color'] ) ? esc_attr( $this->options['cart_item_title_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_title_size_callback()
    {
        printf(
            '<input type="text" id="cart_item_title_size" size="4" name="woo_amc_options[cart_item_title_size]" value="%s" /> px',
            isset( $this->options['cart_item_title_size'] ) ? esc_attr( $this->options['cart_item_title_size']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_text_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_text_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_text_color]" value="%s" />',
            isset( $this->options['cart_item_text_color'] ) ? esc_attr( $this->options['cart_item_text_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_text_size_callback()
    {
        printf(
            '<input type="text" id="cart_item_text_size" size="4" name="woo_amc_options[cart_item_text_size]" value="%s" /> px',
            isset( $this->options['cart_item_text_size'] ) ? esc_attr( $this->options['cart_item_text_size']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_old_price_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_old_price_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_old_price_color]" value="%s" />',
            isset( $this->options['cart_item_old_price_color'] ) ? esc_attr( $this->options['cart_item_old_price_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_price_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_price_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_price_color]" value="%s" />',
            isset( $this->options['cart_item_price_color'] ) ? esc_attr( $this->options['cart_item_price_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_quantity_buttons_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_quantity_buttons_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_quantity_buttons_color]" value="%s" />',
            isset( $this->options['cart_item_quantity_buttons_color'] ) ? esc_attr( $this->options['cart_item_quantity_buttons_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_quantity_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_quantity_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_quantity_color]" value="%s" />',
            isset( $this->options['cart_item_quantity_color'] ) ? esc_attr( $this->options['cart_item_quantity_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_quantity_bg_callback()
    {
        printf(
            '<input type="text" id="cart_item_quantity_bg" class="woo_amc_select_color" name="woo_amc_options[cart_item_quantity_bg]" value="%s" />',
            isset( $this->options['cart_item_quantity_bg'] ) ? esc_attr( $this->options['cart_item_quantity_bg']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_quantity_border_radius_callback()
    {
        printf(
            '<input type="text" id="cart_item_quantity_border_radius" size="4" name="woo_amc_options[cart_item_quantity_border_radius]" value="%s" /> px',
            isset( $this->options['cart_item_quantity_border_radius'] ) ? esc_attr( $this->options['cart_item_quantity_border_radius']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_big_price_size_callback()
    {
        printf(
            '<input type="text" id="cart_item_big_price_size" size="4" name="woo_amc_options[cart_item_big_price_size]" value="%s" /> px',
            isset( $this->options['cart_item_big_price_size'] ) ? esc_attr( $this->options['cart_item_big_price_size']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_item_big_price_color_callback()
    {
        printf(
            '<input type="text" id="cart_item_big_price_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_big_price_color]" value="%s" />',
            isset( $this->options['cart_item_big_price_color'] ) ? esc_attr( $this->options['cart_item_big_price_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_bg_callback()
    {
        printf(
            '<input type="text" id="cart_footer_bg" class="woo_amc_select_color" name="woo_amc_options[cart_footer_bg]" value="%s" />',
            isset( $this->options['cart_footer_bg'] ) ? esc_attr( $this->options['cart_footer_bg']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_products_size_callback()
    {
        printf(
            '<input type="text" id="cart_footer_products_size" size="4" name="woo_amc_options[cart_footer_products_size]" value="%s" /> px',
            isset( $this->options['cart_footer_products_size'] ) ? esc_attr( $this->options['cart_footer_products_size']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_products_label_callback()
    {
        printf(
            '<input type="text" id="cart_footer_products_label" name="woo_amc_options[cart_footer_products_label]" value="%s" />',
            isset( $this->options['cart_footer_products_label'] ) ? esc_attr( $this->options['cart_footer_products_label']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_products_label_color_callback()
    {
        printf(
            '<input type="text" id="cart_footer_products_label_color" class="woo_amc_select_color" name="woo_amc_options[cart_footer_products_label_color]" value="%s" />',
            isset( $this->options['cart_footer_products_label_color'] ) ? esc_attr( $this->options['cart_footer_products_label_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_products_count_color_callback()
    {
        printf(
            '<input type="text" id="cart_footer_products_count_color" class="woo_amc_select_color" name="woo_amc_options[cart_footer_products_count_color]" value="%s" />',
            isset( $this->options['cart_footer_products_count_color'] ) ? esc_attr( $this->options['cart_footer_products_count_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_total_size_callback()
    {
        printf(
            '<input type="text" id="cart_footer_total_size" size="4" name="woo_amc_options[cart_footer_total_size]" value="%s" /> px',
            isset( $this->options['cart_footer_total_size'] ) ? esc_attr( $this->options['cart_footer_total_size']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_total_label_callback()
    {
        printf(
            '<input type="text" id="cart_footer_total_label" name="woo_amc_options[cart_footer_total_label]" value="%s" />',
            isset( $this->options['cart_footer_total_label'] ) ? esc_attr( $this->options['cart_footer_total_label']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function www()
    {
        printf(
            '<input type="text" id="cart_item_big_price_color" class="woo_amc_select_color" name="woo_amc_options[cart_item_big_price_color]" value="%s" />',
            isset( $this->options['cart_item_big_price_color'] ) ? esc_attr( $this->options['cart_item_big_price_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_total_label_color_callback()
    {
        printf(
            '<input type="text" id="cart_footer_total_label_color" class="woo_amc_select_color" name="woo_amc_options[cart_footer_total_label_color]" value="%s" />',
            isset( $this->options['cart_footer_total_label_color'] ) ? esc_attr( $this->options['cart_footer_total_label_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_total_price_color_callback()
    {
        printf(
            '<input type="text" id="cart_footer_total_price_color" class="woo_amc_select_color" name="woo_amc_options[cart_footer_total_price_color]" value="%s" />',
            isset( $this->options['cart_footer_total_price_color'] ) ? esc_attr( $this->options['cart_footer_total_price_color']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_link_text_callback()
    {
        printf(
            '<input type="text" id="cart_footer_link_text" name="woo_amc_options[cart_footer_link_text]" value="%s" />',
            isset( $this->options['cart_footer_link_text'] ) ? esc_attr( $this->options['cart_footer_link_text']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_link_size_callback()
    {
        printf(
            '<input type="text" id="cart_footer_link_size" size="4" name="woo_amc_options[cart_footer_link_size]" value="%s" /> px',
            isset( $this->options['cart_footer_link_size'] ) ? esc_attr( $this->options['cart_footer_link_size']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function cart_footer_link_color_callback()
    {
        printf(
            '<input type="text" id="cart_footer_link_color" class="woo_amc_select_color" name="woo_amc_options[cart_footer_link_color]" value="%s" />',
            isset( $this->options['cart_footer_link_color'] ) ? esc_attr( $this->options['cart_footer_link_color']) : ''
        );
    }

}
