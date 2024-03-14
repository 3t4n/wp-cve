<?php
namespace SirvElementorWidget\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SirvControl extends \Elementor\Base_Data_Control{

    public function get_type() {
		return 'sirvcontrol';
	}


	public function enqueue() {
		$isRtl = is_rtl();
		$dir = $isRtl ? 'rtl': 'ltr';

		wp_register_style('sirv_toast_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/vendor/toastr.css');
		wp_enqueue_style('sirv_toast_style');
		wp_enqueue_script('sirv_toast_js', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/toastr.min.js', array('jquery'), false);

		wp_register_style( 'sirv_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv.css' );
		wp_enqueue_style('sirv_style');
		wp_register_style( 'sirv_mce_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv-shortcode-view.css' );
		wp_enqueue_style('sirv_mce_style');
		wp_register_script( 'sirv_logic', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv.js', array( 'jquery', 'jquery-ui-sortable, sirv_toast_js' ), false);
		wp_localize_script( 'sirv_logic', 'sirv_ajax_object', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajaxnonce' => wp_create_nonce('sirv_logic_ajax_validation_nonce'),
			'assets_path' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'assets',
			'plugin_subdir_path' => SIRV_PLUGIN_RELATIVE_SUBDIR_PATH,
			'sirv_cdn_url' => get_option('SIRV_CDN_URL') )
		);

		wp_enqueue_script('sirv_logic');
		wp_enqueue_script( 'sirv_logic-md5', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-md5.min.js', array(), '1.0.0');
		wp_enqueue_script( 'sirv_modal', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-bpopup.min.js', array('jquery'), '1.0.0');
		wp_enqueue_script( 'sirv_modal-logic', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-modal.js', array('jquery', 'sirv_modal', 'sirv_logic-md5'), false);

		wp_register_style('sirv_frontend_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/sirv-responsive-frontend.css');
		wp_enqueue_style('sirv_frontend_style');

		$isNotEmptySirvOptions = sirv_check_empty_options_on_backend();
		wp_localize_script( 'sirv_modal-logic', 'modal_object', array(
			'media_add_url' =>  SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/media_add.html',
			'login_error_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/login_error.html',
			'featured_image_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/featured_image.html',
			'woo_media_add_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/woo_media_add.html',
			'isNotEmptySirvOptions' => $isNotEmptySirvOptions,
			'sirv_cdn_url' => get_option('SIRV_CDN_URL')));
		wp_enqueue_script('sirv-shortcodes-page', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-shortcodes-page.js', array( 'jquery'), false);

		wp_enqueue_script( 'sirv_control_manager', plugins_url('/assets/js/sirvControlManager.js', __FILE__), array('jquery'), false, true);

		/* wp_register_style('wp-standard-styles', get_admin_url() . "load-styles.php?c=0&dir=ltr&load[]=dashicons,buttons,media-views,common,forms,dashboard,list-tables,edit,media,nav-menu"); */
		wp_register_style('wp-standard-styles', get_admin_url() . "load-styles.php?c=0&dir={$dir}&load[]=dashicons,buttons,common,forms,dashboard,list-tables,edit,media,nav-menu");
		wp_enqueue_style('wp-standard-styles');

		wp_register_style( 'sirv-elementor-plugin-css', plugins_url('/assets/css/sirv-elementor.css', __FILE__), array(), null, 'all');
		wp_enqueue_style('sirv-elementor-plugin-css');
	}


	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<div>
			<div id="<?php echo $control_uid; ?>" class="sirv-elementor-add-media sirv-modal-click">
				<span class="sirv-elementor-add-media-text">
					<?php echo __( 'Add Sirv Media', 'sirv' ); ?>
				</span>
			</div>
			<div class="sirv-data-elementor"></div>
			<div class="sirv-modal">
				<div class="modal-content"></div>
			</div>
		</div>
		<?php
	}


	/*protected function get_default_settings() {
		return [
			'label_block' => true,
			'sirv_data' => ['test' => 'accepted']
		];
	}*/


	/*public function get_default_value() {
		return [];
	}*/
}
