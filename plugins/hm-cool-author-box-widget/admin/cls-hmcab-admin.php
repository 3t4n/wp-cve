<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
*	Admin Panel Master Class
*/
class Hmcabw_Admin
{
	use Cab_Core, Hmcab_Common, Hmcab_Personal_Settings, Hmcab_Social_Settings, Hmcab_Template_Settings, Hmcab_Styles_Post_Settings;
	
	private $hmcabw_version;
	private $hmcabw_option_group;
	private $hmcabw_assets_prefix;

	public function __construct( $version ) {

		$this->hmcabw_version = $version;
		$this->hmcabw_assets_prefix = substr(HMCABW_PREFIX, 0, -1) . '-';
	}
	
	/**
	*	Loading admin panel styles
	*/
	public function hmcabw_enqueue_assets() {
		
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');

		wp_enqueue_style(
            $this->hmcabw_assets_prefix . 'font-awesome',
            HMCABW_ASSETS . 'css/fontawesome/css/all.min.css',
            array(),
            $this->hmcabw_version,
            FALSE
        );

		wp_enqueue_style(
			$this->hmcabw_assets_prefix . 'admin',
			HMCABW_ASSETS . 'css/' . $this->hmcabw_assets_prefix . 'admin.css',
			array(),
			$this->hmcabw_version,
			FALSE
		);

		wp_enqueue_media();

		if ( ! wp_script_is( 'jquery' ) ) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script(
			$this->hmcabw_assets_prefix . 'admin',
			HMCABW_ASSETS . 'js/' . $this->hmcabw_assets_prefix . 'admin.js',
			array('jquery'),
			$this->hmcabw_version,
			TRUE
		);

	}
	
	/**
	*	Function for loading admin menu
	*/
	public function hmcabw_admin_menu() {
		
		add_menu_page(	
			__('Cool Author Box', 'hm-cool-author-box-widget'),		
			__('Cool Author Box', 'hm-cool-author-box-widget'),
			'manage_options',
			'hm-cool-author-box',
			array( $this, 'hmcab_admin_settings' ),
			'dashicons-businessperson',
			99
		);
		
		add_submenu_page(
			'hm-cool-author-box',
			__('Settings', 'hm-cool-author-box-widget'),
			__('Settings', 'hm-cool-author-box-widget'),
			'manage_options',
			'hmcab-settings',
			array( $this, 'hmcab_admin_settings' )
		);

		add_submenu_page(
			'hm-cool-author-box',
			__('Post Layout', 'hm-cool-author-box-widget'),
			__('Post Layout', 'hm-cool-author-box-widget'),
			'manage_options',
			'hmcab-post-layout',
			array( $this, 'hmcab_post_layout' )
		);

		add_submenu_page(
			'hm-cool-author-box',
			__('How it works', 'hm-cool-author-box-widget'),
			__('How it works', 'hm-cool-author-box-widget'),
			'manage_options',
			'hmcab-help-usage',
			array($this, 'hmcab_help_usage_settings')
		);
	}
	
	/**
	*	Loading admin panel view/forms
	*/
	function hmcab_post_layout() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$hmcabNotiMessage = false;
		
		require_once HMCABW_PATH . 'admin/view/post-layout.php';
	}

	function hmcab_help_usage_settings() {
		require_once HMCABW_PATH . 'admin/view/help.php';
	}

	function hmcab_admin_settings() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : null;

		$hmcabNotiMessage = false;


		if ( isset( $_POST['updatePersonalSettings'] ) ) {

			$hmcabNotiMessage = $this->set_personal_settings( $_POST );
		}

		$hmcabSocialNetworks	= $this->get_social_network();

		if ( isset( $_POST['updateSocialSettings'] ) ) {

			$hmcabNotiMessage = $this->set_social_settings( $hmcabSocialNetworks, $_POST );
		}

		if ( isset( $_POST['updateTempSettings'] ) ) {

			$hmcabNotiMessage = $this->set_template_settings( $_POST );
		}

		if ( isset( $_POST['updateStylesPost'] ) ) {
			
			$hmcabNotiMessage = $this->set_styles_post_settings( $_POST );
		}

		$cabPersonalSettings	= $this->get_personal_settings();
		$hmcabwSocialSettings 	= $this->get_social_settings();
		$hmcabwTempSettings		= $this->get_template_settings();
		$hmcabStylesPost 		= $this->get_styles_post_settings();

		include_once HMCABW_PATH . 'admin/view/settings.php';
	}

	function hmcabw_get_image() {

		if ( isset( $_GET['id'] ) ) {

			$image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'full', false );
			$data = array(
				'image' => $image,
			);

			wp_send_json_success( $data );
		
		} else {

			wp_send_json_error();
		}
	}
	
	protected function hmcab_display_notification( $type, $msg ) { 
		?>
		<div class="hmcabw-alert <?php printf('%s', $type); ?>">
			<span class="hmcabw-closebtn">&times;</span> 
			<strong><?php esc_html_e( ucfirst( $type ), HMCABW_TXT_DOMAIN ); ?>!</strong> <?php esc_html_e($msg, HMCABW_TXT_DOMAIN); ?>
		</div>
		<?php 
	}

	/**
	*	User Profile UI
	*/
	function cab_user_profile_new_fileds( $user ) {

		include_once HMCABW_PATH . 'admin/view/author-profile-settings.php';

    }

	/**
	*	User Profile Save
	*/
	function cab_user_profile_save_fileds( $user_id ) {
        
        if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
          return;
        }

        if ( ! current_user_can( 'edit_user', $user_id ) ) { 
          return false; 
        }

        $cab_title = sanitize_text_field( $_POST['cab_title'] );
        update_user_meta($user_id, 'cab_title', $cab_title);

		$hmcabw_photograph = sanitize_text_field( $_POST['hmcabw_photograph'] );
        update_user_meta($user_id, 'hmcabw_photograph', $hmcabw_photograph);

		$socials = $this->get_social_network();

		$socialArr = array();

        foreach ( $socials as $network ) {

            $socialArr[$network . '_enable'] = ( isset( $_POST['hmcabw_user_' . $network . '_enable'] ) && filter_var( $_POST['hmcabw_user_' . $network . '_enable'], FILTER_SANITIZE_NUMBER_INT ) ) ? $_POST['hmcabw_user_' . $network . '_enable'] : '';
            $socialArr[$network . '_link']   = isset( $_POST['hmcabw_user_' . $network . '_link'] ) ? sanitize_text_field( $_POST['hmcabw_user_' . $network . '_link'] ) : '';
        
        }

		update_user_meta( $user_id, 'cab_user_socials', $socialArr );
    }
}
?>