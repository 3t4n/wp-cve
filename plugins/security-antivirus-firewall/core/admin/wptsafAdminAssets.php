<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

class wptsafAdminAssets{

	protected $allowScreens = array(
		wptsafAdminPageExtensions::MENU_SLUG,
		wptsafAdminPageSettings::MENU_SLUG,
		wptsafAdminPageMalwareScanner::MENU_SLUG,
	);

	protected $isAllowScreen;

	public function __construct(){
		add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		add_filter('admin_body_class', array($this, 'adminBodyClass'));
	}

	public function enqueueScripts(){

		wp_enqueue_style('wptsaf-allpages-css', 	WPTSAF_URL . 'assets/dist/css/wpsaf.allpages.css', array(), 1);

		if (!$this->isAllowScreen()) {
			return;
		}


	   /*wp_deregister_script('jquery');
	   wp_register_script('jquery', "//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js", false, null);
	   wp_enqueue_script('jquery');*/


		wp_enqueue_style('wptsafVendor-css', 		WPTSAF_URL . 'assets/dist/css/vendor.css', array(), 1);
		wp_enqueue_style('wptsafPlugin-css', 		WPTSAF_URL . 'assets/dist/css/plugin.css', array(), 1);
		wp_enqueue_style('wptsafPlugin-custom-css', WPTSAF_URL . 'assets/dist/css/custom.css', array(), 1);

		wp_enqueue_script('wptsafVendor-js', 		WPTSAF_URL . 'assets/dist/js/vendor.js', array('jquery'), false, true);
		wp_enqueue_script('wptsafPlugin-js', 		WPTSAF_URL . 'assets/dist/js/plugin.js', array('jquery'), false, true);
		wp_enqueue_script('wptsafPlugin-mnr-js', 	WPTSAF_URL . 'assets/dist/js/masonry.pkgd.min.js', array('jquery'), false, true);

		wp_localize_script( 'wptsafPlugin-js', 'wptsafSecurity', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'ajaxNonce' => wp_create_nonce(WPTSAF_NONCE),
			'translations' => array(
				'ajax_invalid' => 		__('An "invalid format" error prevented the request from completing as expected. The format of data returned could not be recognized. This could be due to a plugin/theme conflict or a server configuration issue.', 'security-antivirus-firewall'),
				'ajax_forbidden' => 	__('A "request forbidden" error prevented the request from completing as expected. The server returned a 403 status code, indicating that the server configuration is prohibiting this request. This could be due to a plugin/theme conflict or a server configuration issue. Please try refreshing the page and trying again. If the request continues to fail, you may have to alter plugin settings or server configuration that could account for this AJAX request being blocked.', 'security-antivirus-firewall'),
				'ajax_not_found' => 	__('A "not found" error prevented the request from completing as expected. The server returned a 404 status code, indicating that the server was unable to find the requested admin-ajax.php file. This could be due to a plugin/theme conflict, a server configuration issue, or an incomplete WordPress installation. Please try refreshing the page and trying again. If the request continues to fail, you may have to alter plugin settings, alter server configurations, or reinstall WordPress.', 'security-antivirus-firewall'),
				'ajax_server_error' => 	__('A "internal server" error prevented the request from completing as expected. The server returned a 500 status code, indicating that the server was unable to complete the request due to a fatal PHP error or a server problem. This could be due to a plugin/theme conflict, a server configuration issue, a temporary hosting issue, or invalid custom PHP modifications. Please check your server\'s error logs for details about the source of the error and contact your hosting company for assistance if required.', 'security-antivirus-firewall'),
				'ajax_unknown' => 		__('An unknown error prevented the request from completing as expected. This could be due to a plugin/theme conflict or a server configuration issue.', 'security-antivirus-firewall'),
				'ajax_timeout' => 		__('A timeout error prevented the request from completing as expected. The site took too long to respond. This could be due to a plugin/theme conflict or a server configuration issue.', 'security-antivirus-firewall'),
				'ajax_parsererror' => 	__('A parser error prevented the request from completing as expected. The site sent a response that jQuery could not process. This could be due to a plugin/theme conflict or a server configuration issue.', 'security-antivirus-firewall'),
			),
			'daterangepicker' => array(
				'settings' => array(
					'format' => WPTSAF_DATE_FORMAT_DATEPICKER,
					'locale' => array(
						'applyLabel' => 	__('Apply', 'security-antivirus-firewall'),
		                'cancelLabel' => 	__('Cancel', 'security-antivirus-firewall'),
		                'fromLabel' => 		__('From', 'security-antivirus-firewall'),
		                'toLabel' => 		__('To', 'security-antivirus-firewall'),
		                'customRangeLabel' => __('Custom', 'security-antivirus-firewall'),
		                'weekLabel' => 		__('W', 'security-antivirus-firewall'),
		                'daysOfWeek' => array(
			                __('Su', 'security-antivirus-firewall'),
			                __('Mo', 'security-antivirus-firewall'),
			                __('Tu', 'security-antivirus-firewall'),
			                __('We', 'security-antivirus-firewall'),
			                __('Th', 'security-antivirus-firewall'),
			                __('Fr', 'security-antivirus-firewall'),
			                __('Sa', 'security-antivirus-firewall')
		                ),
						'monthNames' => array(
							__('January', 'security-antivirus-firewall'),
							__('February', 'security-antivirus-firewall'),
							__('March', 'security-antivirus-firewall'),
							__('April', 'security-antivirus-firewall'),
							__('May', 'security-antivirus-firewall'),
							__('June', 'security-antivirus-firewall'),
							__('July', 'security-antivirus-firewall'),
							__('August', 'security-antivirus-firewall'),
							__('September', 'security-antivirus-firewall'),
							__('October', 'security-antivirus-firewall'),
							__('November', 'security-antivirus-firewall'),
							__('December', 'security-antivirus-firewall'),
						),
		                'firstDay' => 1
					)
				)
			)
		));
	}

	public function adminBodyClass($classes){
		if ($this->isAllowScreen()) {
			$classes .= ' ' . WPTSAF_BODY_CLASS;;
		}
		return $classes;
	}

	protected function isAllowScreen(){
		if (null === $this->isAllowScreen) {
			$this->isAllowScreen = false;
			$screen = get_current_screen();
			foreach ($this->allowScreens as $allowScreen) {
				if (false !== strpos($screen->base, $allowScreen)) {
					$this->isAllowScreen = true;
					break;
				}
			}
		}

		return $this->isAllowScreen;
	}
}
