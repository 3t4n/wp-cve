<?php
/**
 *Plugin Name: Samandehi Logo Manager
 *Plugin URI : https://wpfile.ir
 *Author: Parsa
 *Author URI: http://wpfile.ir
 *Description: جهت قراردادن خودکار لوگوی ستاد ساماندهی در سایت،به صورت خودکار با قابلیت شورت کد و ابزارک
 *Version: 0.5
 */

/*
	     * No script kiddies please!
*/
defined('ABSPATH') or die("اللهم صل علی محمد و آل محمد و عجل فرجهم");

/*
	     * Defines
*/
$plugins_url = rtrim(plugin_dir_url(__FILE__), '/') . '/';
define('_SamandehiLogo_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('_SamandehiLogo_PATH', $plugins_url);
define('_SamandehiLogo_ver', '0.3');

/**
 * plugin shortcode
 */
function SamandehiLogo_shortcode() {
	$print_output = false;
	$is_widget = true;
	$html = Samandehi_Logo_html(array(
		'print_output' => $print_output,
		'is_widget' => $is_widget,
	));
	return $html;
}
add_shortcode('SamandehiLogo_shortcode', 'SamandehiLogo_shortcode');

/**
 * Admin panel menu
 */
require_once 'simple-class-options.php';

/**
 * add Samandehi html to site
 * @param boolean $print_output whether echo output or not
 * @param boolean $is_widget whether is in widget , shortcode or not
 * @return string
 * @since 1.0
 */
add_action('wp_footer', 'Samandehi_Logo_html', 10, 1);
function Samandehi_Logo_html($_arg = array()) {
	if (!is_array($_arg)) {
		$_arg = array();
	}
	if (!isset($_arg['print_output'])) {
		$_arg['print_output'] = true;
	}

	if (!isset($_arg['is_widget'])) {
		$_arg['is_widget'] = false;
	}

	extract($_arg);
	$settings = get_option('Samandehi_Logo');
	if (!$_arg['is_widget']) {
		if ($settings['Samandehi-enable'] != 1) {
			return;
		}

		if ($settings['Samandehi-view-method'] == 'front-page' && !is_front_page()) {
			return;
		}
	}

	$top = ($settings['Samandehi-position'] == 'top-right' || $settings['Samandehi-position'] == 'top-left') ? '0' : 'auto';
	$bottom = ($settings['Samandehi-position'] == 'bottom-right' || $settings['Samandehi-position'] == 'bottom-left') ? '0' : 'auto';
	$right = ($settings['Samandehi-position'] == 'top-right' || $settings['Samandehi-position'] == 'bottom-right') ? '0' : 'auto';
	$left = ($settings['Samandehi-position'] == 'top-left' || $settings['Samandehi-position'] == 'bottom-left') ? '0' : 'auto';
	$width = $settings['Samandehi-width'];
	$html = '';
	if (!$is_widget) {
		$html .= '<div class="Samandehi-logo-wrapper" style="width:' . $width . 'px !important;z-index:999999;height:auto; position:fixed; top:' . $top . '; right:' . $right . '; left:' . $left . ';bottom:' . $bottom . ';">';
	}
	if (trim($settings['Samandehi-code']) != '') {
		$html .= stripcslashes($settings['Samandehi-code']);
	} else {
		$html .= '<iframe src="/SamandehiLogo.htm" frameborder="0" scrolling="no" allowtransparency="true" style="width: 150px; height:150px;"></iframe>';
	}

	if (!$is_widget) {$html .= '</div>';}

	if ($print_output) {echo $html;} else {return $html;}

}

/**
 * on plugin activation
 * @since 0.1
 */
register_activation_hook(__FILE__, 'SamandehiLogo_activate');
function SamandehiLogo_activate() {
	//Removed from 0.2
	//$html_file = _SamandehiLogo_PATH . 'SamandehiLogo.htm';
	//file_put_contents(ABSPATH . DIRECTORY_SEPARATOR . 'SamandehiLogo.htm', file_get_contents($html_file));
}

/**
 * on plugin deactivation
 * @since 0.1
 */
register_deactivation_hook(__FILE__, 'SamandehiLogo_deactivate');
function SamandehiLogo_deactivate() {
	//Removed from 0.2
	// $html_file = ABSPATH . DIRECTORY_SEPARATOR . 'SamandehiLogo.htm';
	// if (function_exists('delete')) {delete($html_file);}
	// if (function_exists('unlink')) {unlink($html_file);}
}

/**
 * enable widget
 * @since 0.1
 */
class Samandehi_widget extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'Samandehi_widget', 'description' => 'نماد ساماندهی');
		$this->WP_Widget('Samandehi_widget', 'نماد ساماندهی', $widget_ops);
		}

	function form($instance) {
		$title = $instance['title'];
		?>
	<p>
		 عنوان ابزارک ::: <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>">
	</p>
	<?php
}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	function widget($args, $instance) {
		$settings = get_option('Samandehi_Logo');
		// if ($settings['Samandehi-enable'] != 1) {
		//     return;
		// }

		if ($settings['Samandehi-view-method'] == 'front-page' && !is_front_page()) {
			return;
		}

		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? 'نماد ساماندهی' : apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		echo $before_title . $title . $after_title;
		$print_output = true;
		$is_widget = true;
		Samandehi_Logo_html(array(
			'print_output' => $print_output,
			'is_widget' => $is_widget,
		));
		echo $after_widget;
	}

}
add_action('widgets_init', create_function('', 'return register_widget("Samandehi_widget");'));

/**
 * Notice After install/update
 */
add_action('admin_init', 'Samandehi_after_install_actions');
function Samandehi_after_install_actions() {
	if (get_option('Samandehi_new_ver_notice_applied_0_2') != 'ok' && (version_compare(0.3, _SamandehiLogo_ver) > 0)) {
		add_action('admin_notices', 'Samandehi_update_admin_message');
	}

	//delete this option to prevent more show
	if (isset($_GET['update_Samandehi_new_ver_notice_applied_0_2'])) {
		update_option('Samandehi_new_ver_notice_applied_0_2', 'ok');
		wp_redirect(menu_page_url('SamandehiLogo-options', FALSE));
		die();
	}
}
function Samandehi_update_admin_message() {
	$Message = sprintf(
		__('نسخه جدید نماد ساماندهی دچار تغییراتی شده،لطفا جهت تنظیمات به %sاینجا%s رفته و کد جدید را در قسمت مربوطه وارد نمایید.<a href="' . menu_page_url('SamandehiLogo-options', FALSE) . '&update_Samandehi_new_ver_notice_applied_0_2">× حذف این پیام</a>')
		, '<a href="' . menu_page_url('SamandehiLogo-options', FALSE) . '">', '</a>'
	);
	echo '<div class="updated"><p>' . $Message . '</p></div>';
}
