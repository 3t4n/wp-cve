<?php

class EasyOptInsShortcodes {

	var $settings;
	var $prerequisites = array();
	var $assets_enqueued = false;

	public function __construct( $settings = array() ) {
		global $pagenow, $typenow;

		$this->settings = $settings;

		// Add shortcode
		add_shortcode( $this->settings[ 'shortcode' ], array( $this, 'shortcode_content' ) );

		// Add shortcode aliases
		foreach ( $settings[ 'shortcode_aliases' ] as $shortcode) {
			add_shortcode( $shortcode, array( $this, 'shortcode_content' ) );
		}

	}

	public function enqueue_assets() {
		$protocol = is_ssl() ? 'https' : 'http';
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'fca-eoi-font-awesome', $protocol . '://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css', array(), FCA_EOI_VER );

		wp_enqueue_script( 'fca_eoi_tooltipster_js', FCA_EOI_PLUGIN_URL . '/assets/vendor/tooltipster/tooltipster.bundle.min.js', array(), FCA_EOI_VER, true );
		wp_enqueue_style( 'fca_eoi_tooltipster_css', FCA_EOI_PLUGIN_URL . '/assets/vendor/tooltipster/tooltipster.bundle.min.css', array(), FCA_EOI_VER );
		wp_enqueue_style( 'fca_eoi_tooltipster_theme_css', FCA_EOI_PLUGIN_URL . '/assets/vendor/tooltipster/tooltipster-borderless.min.css', array(), FCA_EOI_VER );
		
		wp_enqueue_script( 'fca_eoi_featherlight_js', FCA_EOI_PLUGIN_URL . '/assets/vendor/featherlight/release/featherlight.min.js', array(), FCA_EOI_VER, true );
		wp_enqueue_style( 'fca_eoi_featherlight_css', FCA_EOI_PLUGIN_URL . '/assets/vendor/featherlight/release/featherlight.min.css', array(), FCA_EOI_VER );
		
		wp_enqueue_script( 'fca_eoi_jstz', FCA_EOI_PLUGIN_URL . '/assets/vendor/jstz/jstz.min.js', array(), FCA_EOI_VER, true );
		
		wp_enqueue_style( 'fca-eoi-common-css', FCA_EOI_PLUGIN_URL . '/assets/style-new.min.css', array(), FCA_EOI_VER );
		wp_enqueue_script( 'fca_eoi_script_js', FCA_EOI_PLUGIN_URL . '/assets/script.js', array( 'fca_eoi_jstz', 'jquery', 'fca_eoi_tooltipster_js', 'fca_eoi_featherlight_js'), FCA_EOI_VER, true );

		//PASS VARIABLES TO JAVASCRIPT
		$options = get_option( 'fca_eoi_settings' );
		$consent_msg = empty( $options['consent_msg'] ) ? '' : $options['consent_msg'];
		$consent_headline = empty( $options['consent_headline'] ) ? '' : $options['consent_headline'];
		$data = array (
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' =>  wp_create_nonce( 'fca_eoi_submit_form' ),
			'gdpr_checkbox' =>  fca_eoi_show_gdpr_checkbox(),
			'consent_headline' =>  $consent_headline,
			'consent_msg' =>  $consent_msg
		);
		wp_localize_script( 'fca_eoi_script_js', 'fcaEoiScriptData', $data );	
	}

	public function shortcode_content( $atts ) {
		if ( empty ( $atts['id'] ) ) {
			return "The selected Optin Cat form doesn't exist.";
		} else {
			$form_id = $atts['id'];
			$post = get_post( $form_id );
		}
		
		if( !is_object( $post ) OR $post->post_status == 'trash' ) {
			return "The selected Optin Cat form doesn't exist.";
		}
		
		$animation = get_post_meta( $form_id, 'fca_eoi_animation', true);
		if ( !empty( $animation ) ) {
			wp_enqueue_style( 'fca_eoi_powerups_animate', FCA_EOI_PLUGIN_URL . '/assets/vendor/animate/animate.min.css', array(), FCA_EOI_VER );
		}
		
		$this->enqueue_assets();
		$head = get_post_meta( $form_id, 'fca_eoi_head', true );
		
		$layout_id = get_post_meta ( $form_id, 'fca_eoi_layout', true );
		$layout = new EasyOptInsLayout( $layout_id );
		
		if ( $layout->layout_type !== 'lightbox' && $layout->layout_type !== 'banner' && $layout->layout_type !== 'overlay' ) {
			
			require_once FCA_EOI_PLUGIN_DIR . 'includes/classes/RobotDetector/RobotDetector.php';
			$robot_detector = new RobotDetector();
			
			if ( get_post( $form_id ) && !is_user_logged_in() && !$robot_detector->is_robot() ) {
				EasyOptInsActivity::get_instance()->add_impression( $form_id );
			}
			
		}
		
		return $head;
	}
}
