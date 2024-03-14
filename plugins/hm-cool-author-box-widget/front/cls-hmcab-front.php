<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
*	Front Master CLass
*/
class HMCABW_Front 
{

	use Cab_Core, Hmcab_Personal_Settings, Hmcab_Social_Settings, Hmcab_Template_Settings, Hmcab_Styles_Post_Settings;
	
	private $hmcabw_version;

	public function __construct( $version ) {

		$this->hmcabw_version = $version;
		$this->hmcabw_assets_prefix = substr(HMCABW_PREFIX, 0, -1) . '-';
	}
	
	public function hmcabw_enqueue_assets() {

		wp_enqueue_style(
            $this->hmcabw_assets_prefix . 'font-awesome',
            HMCABW_ASSETS . 'css/fontawesome/css/all.min.css',
            array(),
            $this->hmcabw_version,
            FALSE
        );

		wp_enqueue_style(
			$this->hmcabw_assets_prefix . 'front',
			HMCABW_ASSETS . 'css/' . $this->hmcabw_assets_prefix . 'front.css',
			array(),
			$this->hmcabw_version,
			FALSE
		);

		wp_enqueue_style(
			$this->hmcabw_assets_prefix . 'widget',
			HMCABW_ASSETS . 'css/' . $this->hmcabw_assets_prefix . 'widget.css',
			array(),
			$this->hmcabw_version,
			FALSE
		);

		if ( ! wp_script_is( 'jquery' ) ) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script(
			$this->hmcabw_assets_prefix . 'front',
			HMCABW_ASSETS . 'js/' . $this->hmcabw_assets_prefix . 'front.js',
			array('jquery'),
			$this->hmcabw_version,
			TRUE
		);
	}
	
	public function hmcabw_front_view_initialize() {
		
		add_filter( "the_content", array( $this, 'hmcabw_front_view_load' ) );

	}
	
	public function hmcabw_front_view_load() {

		if ( is_single() || is_page() || is_author() || is_archive() ) {
			echo get_the_content();
			echo 'Hello';
		}
		else
			echo 'Hi';
	}
	
	function hmcabw_load_shortcode() {

		add_shortcode( 'hm_cool_author_box', array( $this, 'hmcabw_load_shortcode_view' ) );
	}
	
	function hmcabw_load_shortcode_view() {

		global $post;
		
		$hmcabwCurrentUser = wp_get_current_user();

		// General Settings Data
		$hmcabwGeneralSettings	= $this->get_personal_settings();
		foreach ( $hmcabwGeneralSettings as $gs_name => $gs_value ) {
			if ( isset( $hmcabwGeneralSettings[$gs_name] ) ) {
				${"" . $gs_name}  = $gs_value;
			}
		}

		// Social Settings Data
		$hmcabwSocialSettings	= $this->get_social_settings();

		// Get all social networks
		$hmcabwSocials = $this->get_social_network();

		// Template Settings Data
		$hmcabwTempSettings 	= $this->get_template_settings();
		foreach ( $hmcabwTempSettings as $ts_name => $ts_value ) {
			if ( isset( $hmcabwTempSettings[$ts_name] ) ) {
				${"" . $ts_name}  = $ts_value;
			}
		}

		$output = '';
		ob_start();
		include HMCABW_PATH . 'front/view/author-box.php';
		$output .= ob_get_clean();
		return $output;
	}

	function hmcabw_author_info_display( $content ){

		global $post;

		//echo $author_id = $post->post_author;

		$hmcabwCurrentUser = wp_get_current_user();

		// General Settings Data
		$hmcabwGeneralSettings	= $this->get_personal_settings();
		foreach ( $hmcabwGeneralSettings as $gs_name => $gs_value ) {
			if ( isset( $hmcabwGeneralSettings[$gs_name] ) ) {
				${"" . $gs_name}  = $gs_value;
			}
		}

		// Social Settings Data
		$hmcabwSocialSettings	= $this->get_social_settings();

		// Get all social networks
		$hmcabwSocials = $this->get_social_network();

		// Template Settings Data
		$hmcabwTempSettings 	= $this->get_template_settings();
		foreach ( $hmcabwTempSettings as $ts_name => $ts_value ) {
			if ( isset( $hmcabwTempSettings[$ts_name] ) ) {
				${"" . $ts_name}  = $ts_value;
			}
		}
		
		$output = '';

		ob_start();
		
		include HMCABW_PATH . 'front/view/author-box.php';
		
		$output .= ob_get_clean();

		if ( ! $hmcabw_display_in_post_page ) {
		
			if ( is_single() ) {

				if ( $hmcabw_display_selection === "top" ) {

					return $output . $content;

				} else {
					
					return $content . $output;

				}
			}
		
		}

		return $content;
	}
}
?>