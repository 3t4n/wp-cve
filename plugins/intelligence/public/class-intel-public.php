<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intel
 * @subpackage Intel/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Intel
 * @subpackage Intel/public
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Intel_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Intel_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/intel-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Intel_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Intel_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		// don't add public scripts if in framework mode
    if (intel_is_framework()) {
      return;
    }

    // core js support functions
		wp_register_script('intel', INTEL_URL . 'js/intel.js', array('jquery'), $this->version, true);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/intel-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds Intelligence link to admin bar
	 * @param $wp_admin_bar
	 */
	public function admin_bar_menu($wp_admin_bar) {

		// check permissions to access reports
		if (!Intel_Df::user_access('view all intel reports') || intel_is_framework_only()) {
			return;
		}

		$context = '';
		$label_subjects = array();
		$id_subjects = array();
		$filters = array();
		$contexts = array();

		if (!is_admin()) {
			global $wp;
			global $wp_query;
			$contexts[] = 'page';
			$label_subjects[] = Intel_Df::t('Page');
			$id_subjects[] = 'content';
			$filters[] = 'pagePath:' . Intel_Df::current_pagepath();
			$queried_obj = get_queried_object();
			$queried_obj_class = '';
			if (!empty($queried_obj)) {
				$queried_obj_class = get_class($queried_obj);
			}

			if ($queried_obj_class == 'WP_Term') {
				$attr_key = '';
				if ($queried_obj->taxonomy == 'post_tag') {
					$attr_key = 'b';
					$tax_label = Intel_Df::t('Tag');
				}
				elseif ($queried_obj->taxonomy == 'category') {
					$attr_key = 'c';
					$tax_label = Intel_Df::t('Category');
				}
				if ($attr_key) {
					$contexts[] = 'page-attr';
					$label_subjects[] = Intel_Df::t('Content Segment: !taxonomy', array(
						'!taxonomy' => $tax_label,
					));
					$id_subjects[] = 'content-' . $queried_obj->taxonomy;
					$filters[] = 'pa-' . $attr_key  . ':' . $queried_obj->term_id;
				}
			}
		}
		else {

			$screen = get_current_screen();

			// enable page context when editing a post
			if ( $screen->base == 'post' && $screen->action != 'add') {
				$contexts[] = 'page';
				$label_subjects[] = Intel_Df::t('Page');
				$id_subjects[] = 'content';
				$filters[] = 'pagePath:' . Intel_Df::current_pagepath();
			}
			elseif ($screen->base == 'edit' && !empty($screen->post_type)) {
				$contexts[] = 'page-attr';
				$label_subjects[] = Intel_Df::t('Content Segment: Content Type');
				$id_subjects[] = 'page-attr';
				$filters[] = 'pa-rt2:' . $screen->post_type;
			}
			elseif($screen->base == 'user-edit' || $screen->base == 'profile') {
				$uid = '';
				if (!empty($_GET['user_id'])) {
					$uid = $_GET['user_id'];
				}
				elseif ($screen->base == 'profile') {
					$uid = get_current_user_id();
				}
				if ($uid) {
					$visitor = intel_visitor_load_by_identifiers(array('uid' => $uid));

					if (!empty($visitor->vtkid)) {
						$contexts[] = 'visitor';
						$label_subjects[] = Intel_Df::t('Visitor');
						$id_subjects[] = 'user-behavior';
						$filters[] = 'vtk:' . $visitor->vtkid;
					}
					$contexts[] = 'page-attr';
					$label_subjects[] = Intel_Df::t('Content Segment: Author');
					$id_subjects[] = 'content-author';
					$filters[] = 'pa-a:' . $uid;
				}
			}
		}

		// only include toolbar link on front end
		if (empty($contexts)) {
			return;
		}



		$args = array(
			'id'    => 'intel',
			'title' => '<span class="icon ab-icon dashicons-before dashicons-analytics"></span>' . Intel_Df::t('Intelligence'),
			//'href'  => Intel_Df::url('admin/reports/intel/scorecard', $l_options),
			'meta'  => array( 'class' => 'intel-toolbar-item' ),
		);
		$wp_admin_bar->add_node( $args );

		foreach ($contexts as $i => $context) {
			$label_subject = $label_subjects[$i];
			$id_subject = $id_subjects[$i];
			$filter = $filters[$i];
			$l_options = array(
				'query' => array(
					'report_params' => 'f0=' . $filter,
				),
			);

			if ($context == 'visitor') {
				$args = array(
					'parent' => 'intel',
					'id'    => 'intel-report-' . $id_subject . '-profile',
					'title' => Intel_Df::t('Report: !subject Profile', array(
						'!subject' => $label_subject,
					)),
					'href'  => Intel_Df::url('visitor/' . $visitor->vid),
					'meta'  => array( 'class' => 'intel-toolbar-subitem' ),
				);
				$wp_admin_bar->add_node( $args );

				$args = array(
					'parent' => 'intel',
					'id'    => 'intel-report-' . $id_subject . '-clickstream',
					'title' => Intel_Df::t('Report: !subject Clickstream', array(
						'!subject' => $label_subject,
					)),
					'href'  => Intel_Df::url('visitor/' . $visitor->vid . '/clickstream'),
					'meta'  => array( 'class' => 'intel-toolbar-subitem' ),
				);
				$wp_admin_bar->add_node( $args );
			}

			$args = array(
				'parent' => 'intel',
				'id'    => 'intel-report-' . $id_subject . '-scorecard',
				'title' => Intel_Df::t('Report: !subject Scorecard', array(
					'!subject' => $label_subject,
				)),
				'href'  => Intel_Df::url('admin/reports/intel/scorecard', $l_options),
				'meta'  => array( 'class' => 'intel-toolbar-subitem' ),
			);
			$wp_admin_bar->add_node( $args );

			$args = array(
				'parent' => 'intel',
				'id'    => 'intel-report-' . $id_subject . '-trafficsource',
				'title' => Intel_Df::t('Report: !subject Sources', array(
					'!subject' => $label_subject,
				)),
				'href'  => Intel_Df::url('admin/reports/intel/trafficsource', $l_options),
				'meta'  => array( 'class' => 'intel-toolbar-subitem' ),
			);
			$wp_admin_bar->add_node( $args );

			if ($context != 'page') {
				$args = array(
					'parent' => 'intel',
					'id'    => 'intel-report-' . $id_subject . '-content',
					'title' => Intel_Df::t('Report: !subject Content', array(
						'!subject' => $label_subject,
					)),
					'href'  => Intel_Df::url('admin/reports/intel/content', $l_options),
					'meta'  => array( 'class' => 'intel-toolbar-subitem' ),
				);
				$wp_admin_bar->add_node( $args );
			}

			if ($context != 'visitor') {
				$args = array(
					'parent' => 'intel',
					'id'    => 'intel-report-' . $id_subject . '-visitor',
					'title' => Intel_Df::t('Report: !subject Visitors', array(
						'!subject' => $label_subject,
					)),
					'href'  => Intel_Df::url('admin/reports/intel/visitor', $l_options),
					'meta'  => array( 'class' => 'intel-toolbar-subitem' ),
				);
				$wp_admin_bar->add_node( $args );
			}

			if (
				intel_is_intel_script_enabled('admin')
				&& Intel_Df::user_access('admin intel')
				&& !is_admin()
				&& $i == (count($contexts) -1) // append to last if multiple contexts
			) {
				if (!empty($_GET['io-admin'])) {
					$query = $_GET;
					unset($query['io-admin']);
					$l_options_admin = Intel_Df::l_options_add_query($query);
					$title_mode = Intel_Df::t('Disable');
				}
				else {

					$l_options_admin = Intel_Df::l_options_add_query(array('io-admin' => 1));
					$title_mode = Intel_Df::t('Enable');
				}

				$args = array(
					'parent' => 'intel',
					'id'    => 'intel-admin-event-explorer',
					'title' => Intel_Df::t('Admin: Event Explorer') . ' ' . $title_mode,
					'href'  => Intel_Df::url(Intel_Df::current_path(), $l_options_admin),
					'meta'  => array( 'class' => 'intel-toolbar-subitem' ),
				);
				$wp_admin_bar->add_node( $args );
			}

		}


		//do_action('intel_admin_bar_menu_alter', $wp_admin_bar);

	}
}
