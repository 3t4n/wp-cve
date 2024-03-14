<?php
namespace FlexMLS\Admin;

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

class Enqueue {

	static function admin_enqueue_scripts( $hook ){
        $options = get_option( 'fmc_settings' );
		$hooked_pages = array(
			'settings_page_flexmls_connect', // Remove with old options page
			'flexmls-idx_page_fmc_admin_neighborhood',
			'flexmls-idx_page_fmc_admin_settings',
			'post.php',
			'post-new.php',
			'toplevel_page_fmc_admin_intro',
			'widgets.php'
		);
		if( !in_array( $hook, $hooked_pages ) ){
			//return;
		}
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'fmc_jquery_ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/ui-lightness/jquery-ui.min.css' );
        if(!isset( $options['select2_turn_off']))
            $options['select2_turn_off'] = 0;

		if($options['select2_turn_off'] !== "admin" && $options['select2_turn_off'] !== "all") {
            wp_enqueue_script('select2-4.0.5', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js');
            wp_enqueue_style('select2-4.0.5', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css');
        }

		$version = ( defined( 'FMC_DEV' ) && FMC_DEV ) ? false : FMC_PLUGIN_VERSION;

		/* Fix error wp-color-picker for WP 5.5
		https://github.com/kallookoo/wp-color-picker-alpha/issues/35#issuecomment-670711991
		*/

		wp_register_script( 'flexmls_admin_script', plugins_url( 'assets/js/admin.js', dirname( __FILE__ ) ),
			array( 'jquery', 'wp-color-picker' ), $version );

		$color_picker_strings = array(
			'clear'            => __( 'Clear', 'fmcdomain' ),
			'clearAriaLabel'   => __( 'Clear color', 'fmcdomain' ),
			'defaultString'    => __( 'Default', 'fmcdomain' ),
			'defaultAriaLabel' => __( 'Select default color', 'fmcdomain' ),
			'pick'             => __( 'Select Color', 'fmcdomain' ),
			'defaultLabel'     => __( 'Color value', 'fmcdomain' ),
		);
		wp_localize_script( 'flexmls_admin_script', 'wpColorPickerL10n', $color_picker_strings );

		wp_enqueue_script('flexmls_admin_script');

		/*---------*/

		wp_enqueue_style( 'fmc_connect', plugins_url( 'assets/css/style_admin.css', dirname( __FILE__ ) ), array(), $version );

		wp_enqueue_style( 'fmc_connect_frontend', plugins_url( 'assets/css/style.css', dirname( __FILE__ ) ), array(), $version );

		wp_localize_script( 'fmc_connect', 'fmcAjax', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'pluginurl' => plugins_url( '', dirname( __FILE__ ) )
		) );

		add_thickbox();
	}

	/* Print the wpColorPickerL10n variable in the footer, to be sure it isn't overwritten by WordPress */
	static function admin_print_footer_scripts() {
		?>
		<script type="text/javascript">
			var wpColorPickerL10n = {
				"clear": <?php echo json_encode( __( 'Clear', 'fmcdomain' ) ); ?>,
				"clearAriaLabel": <?php echo json_encode( __( 'Clear color', 'fmcdomain' ) ); ?>,
				"defaultString": <?php echo json_encode( __( 'Default', 'fmcdomain' ) ); ?>,
				"defaultAriaLabel": <?php echo json_encode( __( 'Select default color', 'fmcdomain' ) ); ?>,
				"pick": <?php echo json_encode( __( 'Select Color', 'fmcdomain' ) ); ?>,
				"defaultLabel": <?php echo json_encode( __( 'Color value', 'fmcdomain' ) ); ?>
			};
		</script>
		<?php
	}

	static function wp_enqueue_scripts(){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		$options = get_option( 'fmc_settings' );
		$google_maps_no_enqueue = 0;
		if( isset( $options[ 'google_maps_no_enqueue' ] ) && 1 == $options[ 'google_maps_no_enqueue' ] ){
			$google_maps_no_enqueue = 1;
		}
		if( isset( $options[ 'google_maps_api_key' ] ) && !empty( $options[ 'google_maps_api_key' ] ) && 0 === $google_maps_no_enqueue ){
			wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $options[ 'google_maps_api_key' ] );
		}

    if(!isset( $options[ 'select2_turn_off' ]))
        $options[ 'select2_turn_off' ] = 0;
    if($options['select2_turn_off'] !== "user" && $options['select2_turn_off'] !== "all")  {
        wp_enqueue_script('select2-4.0.5', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js');
        wp_enqueue_style('select2-4.0.5', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css');
    }

		wp_enqueue_script( 'fmc_connect', plugins_url( 'assets/js/main.js', dirname( __FILE__ ) ), array( 'jquery' ), FMC_PLUGIN_VERSION );
		wp_enqueue_script( 'fmc_portal', plugins_url( 'assets/js/portal.js', dirname( __FILE__ ) ), array( 'jquery', 'fmc_connect' ), FMC_PLUGIN_VERSION );

		wp_enqueue_script( 'fmc_connect_flot_resize', '//cdnjs.cloudflare.com/ajax/libs/flot/4.2.2/jquery.flot.resize.min.js', array( 'jquery' ), FMC_PLUGIN_VERSION, true );

		wp_localize_script( 'fmc_connect', 'fmcAjax', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'pluginurl' => plugins_url( '', dirname( __FILE__ ) )
		) );

		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_style( 'fmc_connect', plugins_url( 'assets/css/style.css', dirname( __FILE__ ) ), FMC_PLUGIN_VERSION );

	}

}
