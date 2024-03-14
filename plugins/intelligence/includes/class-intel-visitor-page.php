<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intl
 * @subpackage Intl/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intl
 * @subpackage Intl/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel_Visitor_Page  {

	public static function details_page($entity) {
		//$props = intl()->get_visitor_property_info_all();
		//d($props);
		//return;
		//require_once drupal_get_path('module', 'intel') . "/intel.pages.inc";
		//drupal_add_css(drupal_get_path('module', 'intel') . "/css/intel.visitor_profile.css");
		//wp_enqueue_style('intel-visitor-profile', INTEL_URL . 'admin/css/intel-visitor-profile.css');

		$view_mode = 'full';
		$langcode = 'UND';
		if (intel()->is_debug()) {
			$entity->apiVisitorLoad();
			d($entity);//
		}

		intel_df()->set_title('Visitor profile');

		if (is_string($entity->data)) {
			$entity->data = unserialize($entity->data);
		}

		if (is_string($entity->ext_data)) {
			$entity->ext_data = unserialize($entity->ext_data);
		}

		if (!isset($langcode)) {
			//$langcode = $GLOBALS['language_content']->language;
		}

		// Retrieve all profile fields and attach to $entity->content.
		IntelVisitor::build_content($entity);

		$build = $entity->content;
		$build = array(
			'elements' => $entity->content,
		);

		$markup = Intel_Df::theme('intel_visitor_profile', $build);

		$output = Intel_Df::theme('intel_page', array('markup' => $markup));

		print $output;
		return;
	}

	public static function clickstream_page($visitor) {
		require_once INTEL_DIR . 'reports/class-intel-visitor-clickstream-report.php';
		Intel_Visitor_Clickstream_Report::report_page('-', 'clickstream', '-', 'Intel_Visitor', $visitor);
	}

	public static function sync_page($visitor) {
		intel()->sync_visitordata_page($visitor);

	}
}
