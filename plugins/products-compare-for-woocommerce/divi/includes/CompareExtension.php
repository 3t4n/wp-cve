<?php

class BRCMP_CompareExtension extends DiviExtension {
	public $gettext_domain = 'brcompare-my-extension';
	public $name = 'brcompare-extension';
	public $version = '1.0.0';
	public function __construct( $name = 'brcompare-extension', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );
        add_action('wp_ajax_brcompare_compare_table', array($this, 'compare_table'));
        add_action('wp_ajax_brcompare_compare_button', array($this, 'compare_button'));
        add_action('wp_ajax_brcompare_compare_widget', array($this, 'compare_widget'));
	}
    public function compare_widget() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die();
        }
        $atts = berocket_sanitize_array($_POST);
        $atts = self::convert_on_off($atts);
        if( ! empty($atts['toolbar']) ) {
            echo '<div style="height:50px;"></div>';
        }
        the_widget( 'berocket_compare_products_widget', $atts );
        wp_die();
    }
    public function compare_table() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die();
        }
        $filter = do_shortcode('[br_compare_table]');
        echo $filter;
        wp_die();
    }
    public function compare_button() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die();
        }
        $atts = berocket_sanitize_array($_POST);
        $atts = self::convert_on_off($atts) ;
        if( ! empty($atts['product']) ) {
            if( $atts['product'] == 'latest' ) {
                global $wpdb;
                $atts['product'] = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish' ORDER BY ID DESC LIMIT 1");
            } elseif( $atts['product'] == 'current' ) {
                $atts['product'] = '';
            }
        }
        do_action('br_compare_button_options', $atts);
        wp_die();
    }
	public function wp_hook_enqueue_scripts() {
		if ( $this->_debug ) {
			$this->_enqueue_debug_bundles();
		} else {
			$this->_enqueue_bundles();
		}

		if ( et_core_is_fb_enabled() && ! et_builder_bfb_enabled() ) {
			$this->_enqueue_backend_styles();
		}

		// Normalize the extension name to get actual script name. For example from 'divi-custom-modules' to `DiviCustomModules`.
		$extension_name = str_replace( ' ', '', ucwords( str_replace( '-', ' ', $this->name ) ) );

		// Enqueue frontend bundle's data.
		if ( ! empty( $this->_frontend_js_data ) ) {
			wp_localize_script( "{$this->name}-frontend-bundle", "{$extension_name}FrontendData", $this->_frontend_js_data );
		}

		// Enqueue builder bundle's data.
		if ( et_core_is_fb_enabled() && ! empty( $this->_builder_js_data ) ) {
			wp_localize_script( "{$this->name}-builder-bundle", "{$extension_name}BuilderData", $this->_builder_js_data );
		}
	}
    public static function convert_on_off($atts) {
        foreach($atts as &$attr) {
            if( $attr === 'on' || $attr === 'off' ) {
                $attr = ( $attr === 'on' ? TRUE : FALSE );
            }
        }
        return $atts;
    }
}

new BRCMP_CompareExtension;
