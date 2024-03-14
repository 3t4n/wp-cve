<?php
/**
 * UpStream_Options_General
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Options_General' ) ) :

	/**
	 * CMB2 Theme Options
	 *
	 * @version 0.1.0
	 */
	class UpStream_Options_General {


		/**
		 * Array of metaboxes/fields
		 *
		 * @var array
		 */
		public $id = 'upstream_general';

		/**
		 * Page title
		 *
		 * @var string
		 */
		protected $title = '';

		/**
		 * Menu Title
		 *
		 * @var string
		 */
		protected $menu_title = '';

		/**
		 * Menu Title
		 *
		 * @var string
		 */
		protected $description = '';

		/**
		 * Holds an instance of the object
		 *
		 * @var Myprefix_Admin
		 **/
		public static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 0.1.0
		 */
		public function __construct() {
			// Set our title.
			$this->title       = __( 'General', 'upstream' );
			$this->menu_title  = $this->title;
			$this->description = '';

			add_action( 'wp_ajax_upstream_admin_reset_capabilities', array( $this, 'reset_capabilities' ) );
			add_action( 'wp_ajax_upstream_admin_refresh_projects_meta', array( $this, 'refresh_projects_meta' ) );
			add_action( 'wp_ajax_upstream_admin_cleanup_update_cache', array( $this, 'cleanup_update_cache' ) );
			add_action(
				'wp_ajax_upstream_admin_migrate_milestones_get_projects',
				array( $this, 'migrate_milestones_get_projects' )
			);
			add_action(
				'wp_ajax_upstream_admin_migrate_milestones_for_project',
				array( $this, 'migrate_milestones_for_project' )
			);

			add_action( 'wp_ajax_upstream_admin_import_file_prepare', array( $this, 'import_file_prepare' ) );
			add_action( 'wp_ajax_upstream_admin_import_file_section', array( $this, 'import_file_section' ) );
			add_action( 'wp_ajax_upstream_admin_signup', array( $this, 'signup' ) );

		}

		/**
		 * Returns the running object
		 *
		 * @return Myprefix_Admin
		 **/
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get a list of user roles.
		 *
		 * @return array
		 */
		protected function get_roles() {
			$list  = array();
			$roles = get_editable_roles();

			foreach ( $roles as $role => $data ) {
				$list[ $role ] = $data['name'];
			}

			return $list;
		}


		/**
		 * Add the options metabox to the array of metaboxes
		 *
		 * @since  0.1.0
		 */
		public function options() {
			$project_url = '<a target="_blank" href="' . home_url( 'projects' ) . '">' . home_url( 'projects' ) . '</a>';

			$roles = $this->get_roles();

			$options = apply_filters(
				$this->id . '_option_fields',
				array(
					'id'         => $this->id, // upstream_tasks.
					'title'      => $this->title,
					'menu_title' => $this->menu_title,
					'desc'       => $this->description,
					'show_on'    => array(
						'key'   => 'options-page',
						'value' => array( $this->id ),
					),
					'show_names' => true,
					'fields'     => array(

						/**
						 * General
						 */
						array(
							'name' => __( 'General', 'upstream' ),
							'id'   => 'general_title',
							'type' => 'title',
						),
						array(
							'name'    => __( 'Filter Closed Items', 'upstream' ),
							'id'      => 'filter_closed_items',
							'type'    => 'radio_inline',
							'default' => '0',
							'desc'    => __(
								'Choose whether Projects, Tasks and Bugs will only display items that have “open” statuses. Items with “closed” statuses will still be loaded on the page, but users will have to use filters to view them. This option only applies if “Archive Closed Items” is set to “No”',
								'upstream'
							),
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Archive Closed Items', 'upstream' ),
							'id'      => 'archive_closed_items',
							'type'    => 'radio_inline',
							'default' => '1',
							'desc'    => __(
								'Using the Archive feature means that Closed items are not loaded on the frontend. This can speed up your site if you have projects with many items. Do not use the Archive feature if you want users to find Closed items.',
								'upstream'
							),
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Show Users\' Names', 'upstream' ),
							'id'      => 'show_users_name',
							'type'    => 'radio_inline',
							'default' => '0',
							'desc'    => __(
								'Show names on Project list (Front page)',
								'upstream'
							),
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Project Users Roles', 'upstream' ),
							'id'      => 'project_user_roles',
							'desc'    => __(
								'Select the user roles that should be used to filter the list of users on projects.',
								'upstream'
							),
							'type'    => 'multicheck',
							'default' => array( 'administrator', 'upstream_manager', 'upstream_user' ),
							'options' => $roles,
						),

						/**
						 * Labels
						 */
						array(
							'name'       => __( 'Labels', 'upstream' ),
							'id'         => 'labels_title',
							'type'       => 'title',
							'desc'       => __(
								'Here you can change the labels of various items. You could change Client to Customer or Bugs to Issues for example.<br>These labels will change on the frontend as well as in the admin area.',
								'upstream'
							),
							'before_row' => '<hr>',
						),
						array(
							'name' => __( 'Project Label', 'upstream' ),
							'id'   => 'project',
							'type' => 'labels',
						),
						array(
							'name' => __( 'Client Label', 'upstream' ),
							'id'   => 'client',
							'type' => 'labels',
						),
						array(
							'name' => __( 'Milestone Label', 'upstream' ),
							'id'   => 'milestone',
							'type' => 'labels',
						),
						array(
							'name' => __( 'Milestone Categories Label', 'upstream' ),
							'id'   => 'milestone_categories',
							'type' => 'labels',
						),
						array(
							'name' => __( 'Task Label', 'upstream' ),
							'id'   => 'task',
							'type' => 'labels',
						),
						array(
							'name' => __( 'Bug Label', 'upstream' ),
							'id'   => 'bug',
							'type' => 'labels',
						),
						array(
							'name' => __( 'File Label', 'upstream' ),
							'id'   => 'file',
							'type' => 'labels',
						),
						array(
							'name' => __( 'Discussion Label', 'upstream' ),
							'id'   => 'discussion',
							'type' => 'labels',
						),

						/**
						 * Client
						 */
						array(
							'name'       => sprintf(
								// translators: $s: upstream_client_label.
								__( '%s Area', 'upstream' ),
								upstream_client_label()
							),
							'id'         => 'client_area_title',
							'type'       => 'title',
							'desc'       => sprintf(
								// translators: %1$1s: upstream_client_label.
								// translators: %2$2s: upstream_client_label_plural.
								// translators: %3$4s: project_url.
								// translators: %4$s: upstream_project_label.
								__(
									'Various options for the %1$1s login page and the frontend view. <br>%2$2s can view their projects by visiting %3$3s (URL is available after adding a %4$s).',
									'upstream'
								),
								upstream_client_label(),
								upstream_client_label_plural(),
								$project_url,
								upstream_project_label()
							),
							'before_row' => '<hr>',
						),
						array(
							'name' => __( 'Login Page Heading', 'upstream' ),
							'id'   => 'login_heading',
							'type' => 'text',
							'desc' => __( 'The heading used on the client login page.', 'upstream' ),
						),
						array(
							'name' => __( 'Login Page Text', 'upstream' ),
							'id'   => 'login_text',
							'type' => 'textarea_small',
							'desc' => __( 'Text or instructions that can be added below the login form.', 'upstream' ),

						),
						array(
							'name'    => __( 'Login Page Client Logo', 'upstream' ),
							'id'      => 'login_client_logo',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether Client\'s Logo should be displayed on login page if available.',
								'upstream'
							),
							'default' => '1',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Login Page Project Name', 'upstream' ),
							'id'      => 'login_project_name',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether Project\'s name should be displayed on login page.',
								'upstream'
							),
							'default' => '1',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name' => __( 'Admin Email', 'upstream' ),
							'id'   => 'admin_email',
							'type' => 'text',
							'desc' => __( 'The email address that clients can use to contact you.', 'upstream' ),
						),
						array(
							'name'    => __( 'Admin Support Link Label', 'upstream' ),
							'id'      => 'admin_support_label',
							'type'    => 'text',
							'desc'    => __( 'Label that describes the Admin Support Link.', 'upstream' ),
							'default' => __( 'Contact Admin', 'upstream' ),
						),
						array(
							'name'    => __( 'Admin Support Link', 'upstream' ),
							'id'      => 'admin_support_link',
							'type'    => 'text',
							'desc'    => __(
								'Link to contact form or knowledge base to help clients obtain support.',
								'upstream'
							),
							'default' => 'mailto:' . upstream_admin_email(),
						),
						/**
						 * MEDIA
						 */
						array(
							'name'       => __( 'Media', 'upstream' ),
							'id'         => 'media_filter',
							'type'       => 'title',
							'desc'       => __( 'Options to configure the list of media attachments.', 'upstream' ),
							'before_row' => '<hr>',
						),
						array(
							'name'    => __( 'Who can see all the media?', 'upstream' ),
							'id'      => 'media_unrestricted_roles',
							'desc'    => __(
								'For security, UpStream users can normally only access their own media uploads. Select the roles who can see all the entire media library.',
								'upstream'
							),
							'type'    => 'multicheck',
							'default' => array( 'administrator' ),
							'options' => $roles,

						),
						array(
							'name'    => __( 'Who can post images in comments?', 'upstream' ),
							'id'      => 'media_comment_images',
							'desc'    => __(
								'By default, not all WordPress users can upload images. Select the roles who can add images to UpStream comments.',
								'upstream'
							),
							'type'    => 'multicheck',
							'default' => array_keys( $roles ),
							'options' => $roles,

						),
						/**
						 * Collapse Sections
						 */
						array(
							'name'       => __( 'Collapse Sections', 'upstream' ),
							'id'         => 'frontend_collapse_sections',
							'type'       => 'title',
							'desc'       => __(
								'Options to collapse different sections on the client area on frontend.',
								'upstream'
							),
							'before_row' => '<hr>',
						),
						array(
							'name'    => __( 'Collapse Project Details box', 'upstream' ),
							'id'      => 'collapse_project_details',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to collapse the Project Details box automatically when a user opens a project page.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Collapse Project Progress box', 'upstream' ),
							'id'      => 'collapse_project_progress',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to collapse the Project progress box automatically when a user opens a project page.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Collapse Project Milestones box', 'upstream' ),
							'id'      => 'collapse_project_milestones',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to collapse the Milestones box automatically when a user opens a project page.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Collapse Project Tasks box', 'upstream' ),
							'id'      => 'collapse_project_tasks',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to collapse the Tasks box automatically when a user opens a project page.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Collapse Project Bugs box', 'upstream' ),
							'id'      => 'collapse_project_bugs',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to collapse the Bugs box automatically when a user opens a project page.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Collapse Project Files box', 'upstream' ),
							'id'      => 'collapse_project_files',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to collapse the Files box automatically when a user opens a project page.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Collapse Project Discussion box', 'upstream' ),
							'id'      => 'collapse_project_discussion',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to collapse the Discussion box automatically when a user opens a project page.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),

						/**
						 * Toggle Features
						 */
						array(
							'name'       => __( 'Toggle Features', 'upstream' ),
							'id'         => 'toggle_features',
							'type'       => 'title',
							'desc'       => __( 'Options to toggle different sections and features.', 'upstream' ),
							'before_row' => '<hr>',
						),
						array(
							'name'    => __( 'Disable Project Progress box', 'upstream' ),
							'id'      => 'disable_project_progress',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to disable the Project progress box on the front end.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Clients and Client Users', 'upstream' ),
							'id'      => 'disable_clients',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether if Clients and Client Users can be added and used on Projects.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Select all client\'s users by default', 'upstream' ),
							'id'      => 'pre_select_users',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether if all client\'s users should be checked by default after change or select the client.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Projects Categorization', 'upstream' ),
							'id'      => 'disable_categories',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether Projects can be sorted into categories by managers and users.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Project Progress Icons', 'upstream' ),
							'id'      => 'disable_project_overview',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to display the Project Progress Icons section on frontend.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								1 => __( 'Do not show', 'upstream' ),
								0 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Project Details', 'upstream' ),
							'id'      => 'disable_project_details',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose whether to display the Project Details section on frontend.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
						),
						array(
							'name'              => __( 'Disable Bugs', 'upstream' ),
							'id'                => 'disable_bugs',
							'type'              => 'multicheck',
							'desc'              => __(
								'Ticking this box will disable the Bugs section on both the frontend and the admin area.',
								'upstream'
							),
							'default'           => '',
							'options'           => array(
								'yes' => __( 'Disable the Bugs section?', 'upstream' ),
							),
							'select_all_button' => false,
						),
						array(
							'name'              => __( 'Disable Tasks', 'upstream' ),
							'id'                => 'disable_tasks',
							'type'              => 'multicheck',
							'desc'              => __(
								'Ticking this box will disable the Tasks section on both the frontend and the admin area.',
								'upstream'
							),
							'default'           => '',
							'options'           => array(
								'yes' => __( 'Disable the Tasks section?', 'upstream' ),
							),
							'select_all_button' => false,
						),
						array(
							'name'              => __( 'Disable Milestones', 'upstream' ),
							'id'                => 'disable_milestones',
							'type'              => 'multicheck',
							'desc'              => __(
								'Ticking this box will disable the Milestones section on both the frontend and the admin area. <strong>Warning: The project timeline and some other features require milestones to work properly.</strong>',
								'upstream'
							),
							'default'           => '',
							'options'           => array(
								'yes' => __( 'Disable the Milestones section?', 'upstream' ),
							),
							'select_all_button' => false,
						),
						array(
							'name'              => __( 'Disable Milestone Categories', 'upstream' ),
							'id'                => 'disable_milestone_categories',
							'type'              => 'radio_inline',
							'desc'              => __(
								'Ticking this box will disable the Milestone Categories section on both the frontend and the admin area.',
								'upstream'
							),
							'default'           => '1',
							'options'           => array(
								0 => __( 'No', 'upstream' ),
								1 => __( 'Yes', 'upstream' ),
							),
							'select_all_button' => false,
						),
						array(
							'name'              => __( 'Disable Files', 'upstream' ),
							'id'                => 'disable_files',
							'type'              => 'multicheck',
							'desc'              => __(
								'Ticking this box will disable the Files section on both the frontend and the admin area.',
								'upstream'
							),
							'default'           => '',
							'options'           => array(
								'yes' => __( 'Disable the Files section?', 'upstream' ),
							),
							'select_all_button' => false,
						),
						array(
							'name'    => __( 'File Upload Manager (NOTE: DO NOT change after you have added files)', 'upstream' ),
							'id'      => 'use_upfs',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Choose which file upload system to use.  <B>DO NOT CHANGE THIS SETTING AFTER YOU HAVE ADDED FILES</B>, since it will clear all files.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								'0' => __( 'Use WordPress built-in file uploads', 'upstream' ),
								'1' => __( 'Use UpStream secure file uploads', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'UpStream secure file upload location', 'upstream' ),
							'id'      => 'upfs_location',
							'type'    => 'text',
							'desc'    => __(
								'If UpStream secure file uploads is enabled, this must be set to a path on your web server that is writeable. Contact your system administrator for help.',
								'upstream'
							),
							'default' => '',
						),
						array(
							'name'    => __( 'Disable Reports', 'upstream' ),
							'id'      => 'disable_reports',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Disable the reports section on the sidebar.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								'0' => __( 'Show reports section', 'upstream' ),
								'1' => __( 'Disable section', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Discussion on Projects', 'upstream' ),
							'id'      => 'disable_project_comments',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Either allow comments on projects on both the frontend and the admin area or hide the section.',
								'upstream'
							),
							'default' => '1',
							'options' => array(
								'1' => __( 'Allow comments on projects', 'upstream' ),
								'0' => __( 'Disable section', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Discussion on Projects', 'upstream' ),
							'id'      => 'disable_project_comments',
							'type'    => 'radio_inline',
							'desc'    => __(
								'Either allow comments on projects on both the frontend and the admin area or hide the section.',
								'upstream'
							),
							'default' => '1',
							'options' => array(
								'1' => __( 'Allow comments on projects', 'upstream' ),
								'0' => __( 'Disable section', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Discussion on Milestones', 'upstream' ),
							'id'      => 'disable_comments_on_milestones',
							'type'    => 'radio_inline',
							'desc'    => sprintf(
								// translators: $s: Milestones label.
								__( 'Either allow comments on %s or hide the section.', 'upstream' ),
								__( 'Milestones', 'upstream' )
							),
							'default' => '1',
							'options' => array(
								'1' => __( 'Allow comments on Milestones', 'upstream' ),
								'0' => __( 'Disable section', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Discussion on Tasks', 'upstream' ),
							'id'      => 'disable_comments_on_tasks',
							'type'    => 'radio_inline',
							'desc'    => sprintf(
								// translators: $s: Tasks label.
								__( 'Either allow comments on %s or hide the section.', 'upstream' ),
								__( 'Tasks', 'upstream' )
							),
							'default' => '1',
							'options' => array(
								'1' => __( 'Allow comments on Tasks', 'upstream' ),
								'0' => __( 'Disable section', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Discussion on Bugs', 'upstream' ),
							'id'      => 'disable_comments_on_bugs',
							'type'    => 'radio_inline',
							'desc'    => sprintf(
								// translators: $s: Bugs label.
								__( 'Either allow comments on %s or hide the section.', 'upstream' ),
								__( 'Bugs', 'upstream' )
							),
							'default' => '1',
							'options' => array(
								'1' => __( 'Allow comments on Bugs', 'upstream' ),
								'0' => __( 'Disable section', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Disable Discussion on Files', 'upstream' ),
							'id'      => 'disable_comments_on_files',
							'type'    => 'radio_inline',
							'desc'    => sprintf(
								// translators: $s: Files label.
								__( 'Either allow comments on %s or hide the section.', 'upstream' ),
								__( 'Files', 'upstream' )
							),
							'default' => '1',
							'options' => array(
								'1' => __( 'Allow comments on Files', 'upstream' ),
								'0' => __( 'Disable section', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Show all projects in the frontend sidebar', 'upstream' ),
							'id'      => 'show_all_projects_sidebar',
							'type'    => 'radio_inline',
							'desc'    => __(
								'If enabled, all projects will be displayed in the sidebar on frontend.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								'0' => __( 'Show only the current project', 'upstream' ),
								'1' => __( 'Show all projects', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Override project locking on front end', 'upstream' ),
							'id'      => 'override_locking',
							'type'    => 'radio_inline',
							'desc'    => __(
								'If enabled, users will be allowed to edit projects regardless of whether another person is making edits (only applies on the front end). Note that if you allow multiple users to edit a project simultaneously, there is a chance that changes may be overwritten.',
								'upstream'
							),
							'default' => '0',
							'options' => array(
								'0' => __( 'Users cannot edit simultaneously (safe)', 'upstream' ),
								'1' => __( 'Multiple users can edit a project simultaneously', 'upstream' ),
							),
						),
						array(
							'name'    => __( 'Send Notifications for New Comments', 'upstream' ),
							'id'      => 'send_notifications_for_new_comments',
							'type'    => 'radio_inline',
							'options' => array(
								'1' => __( 'Enabled' ),
								'0' => __( 'Disabled' ),
							),
							'default' => '1',
							'desc'    => __( 'Check this to send a notification to the owner and creator of a milestone, task, or bug when someone comments on it.' ),
						),
						/**
						 * Localization
						 */
						array(
							'name'       => __( 'Localization', 'upstream' ),
							'id'         => 'local_title',
							'type'       => 'title',
							'before_row' => '<hr>',
							'desc'       => __(
								'General options for localization, such as times.',
								'upstream'
							),
						),
						array(
							'name'    => __( 'Work Hours Per Day', 'upstream' ),
							'id'      => 'local_work_hours_per_day',
							'type'    => 'text',
							'desc'    => __( 'The number of work hours per day (used in determining days of work).', 'upstream' ),
							'default' => 8,
						),
						array(
							'name'    => __( 'Currency Symbol', 'upstream' ),
							'id'      => 'local_monetary_symbol',
							'type'    => 'text',
							'desc'    => __( 'The local currency symbol.', 'upstream' ),
							'default' => '$',
						),

						/**
						 * Maintenance
						 */
						array(
							'name'       => __( 'Maintenance', 'upstream' ),
							'id'         => 'maintenance_title',
							'type'       => 'title',
							'before_row' => '<hr>',
							'desc'       => __(
								'General options for maintenance only. Be careful enabling any of these options.',
								'upstream'
							),
						),
						array(
							'name'    => __( 'Add default UpStream capabilities', 'upstream' ),
							'id'      => 'add_default_capabilities',
							'type'    => 'up_buttonsgroup',
							'count'   => 4,
							'labels'  => array(
								__( 'Administrator', 'upstream' ),
								__( 'UpStream Manager', 'upstream' ),
								__( 'UpStream User', 'upstream' ),
								__( 'UpStream Client User', 'upstream' ),
							),
							'slugs'   => array(
								'administrator',
								'upstream_manager',
								'upstream_user',
								'upstream_client_user',
							),
							'desc'    => __(
								'Clicking this button will reset all the capabilities to the default set for the following user roles: administrator, upstream_manager, upstream_user and upstream_client_user. This can\'t be undone.',
								'upstream'
							),
							'onclick' => 'upstream_reset_capabilities(event);',
							'nonce'   => wp_create_nonce( 'upstream_reset_capabilities' ),
						),
						array(
							'name'    => __( 'Update Project Data', 'upstream' ),
							'id'      => 'refresh_projects_meta',
							'type'    => 'up_button',
							'label'   => __( 'Update', 'upstream' ),
							'desc'    => __(
								'Clicking this button will recalculate the data for all the projects, including: project members, milestones\' tasks statuses, created time, project author. This can\'t be undone and can take some time if you have many projects.',
								'upstream'
							),
							'onclick' => 'upstream_refresh_projects_meta(event);',
							'nonce'   => wp_create_nonce( 'upstream_refresh_projects_meta' ),
						),
						array(
							'name'    => __( 'Migrate Legacy Milestones', 'upstream' ),
							'id'      => 'migrate_milestones',
							'type'    => 'up_button',
							'label'   => __( 'Start migration', 'upstream' ),
							'desc'    => __(
								'Clicking this button will force to migrate again all the legacy milestones (project meta data) to the new post type. Only do this if you had any issue with the migrated data after updating to the version 1.24.0. This can\'t be undone and can take some time if you have many projects.',
								'upstream'
							),
							'onclick' => 'upstream_migrate_milestones(event);',
							'nonce'   => wp_create_nonce( 'upstream_migrate_milestones' ),
						),
						array(
							'name'    => __( 'Cleanup Plugin\'s Update Cache', 'upstream' ),
							'id'      => 'cleanup_update_cache',
							'type'    => 'up_button',
							'label'   => __( 'Cleanup', 'upstream' ),
							'desc'    => __(
								'If you’re having problems seeing UpStream extension updates, click this button and you see any new plugin releases.',
								'upstream'
							),
							'onclick' => 'upstream_cleanup_update_cache(event);',
							'nonce'   => wp_create_nonce( 'upstream_cleanup_update_cache' ),
						),
						array(
							'name'              => __( 'Debug', 'upstream' ),
							'id'                => 'debug',
							'type'              => 'multicheck',
							'desc'              => __(
								'Ticking this box will enable special debug code and a new menu to inspect the debug information.',
								'upstream'
							),
							'default'           => '',
							'options'           => array(
								'1' => __( 'Enabled', 'upstream' ),
							),
							'select_all_button' => false,
						),
						array(
							'name'              => __( 'Beta Features', 'upstream' ),
							'id'                => 'beta_features',
							'type'              => 'multicheck',
							'desc'              => __(
								'Ticking this box will enable beta features.',
								'upstream'
							),
							'default'           => '',
							'options'           => array(
								'1' => __( 'Enabled', 'upstream' ),
							),
							'select_all_button' => false,
						),
						array(
							'name'              => __( 'Remove Data', 'upstream' ),
							'id'                => 'remove_data',
							'type'              => 'multicheck',
							'desc'              => __(
								'Ticking this box will delete all UpStream data when plugin is uninstalled.',
								'upstream'
							),
							'default'           => '',
							'options'           => array(
								'yes' => __( 'Remove all data on uninstall?', 'upstream' ),
							),
							'select_all_button' => false,
						),

					),
				)
			);

			return $options;
		}

		/**
		 * Reset Capabilities
		 *
		 * @return void
		 */
		public function reset_capabilities() {
			$return    = '';
			$abort     = false;
			$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

			if ( ! isset( $post_data['nonce'] ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream_reset_capabilities' ) ) {
				$return = 'error';
				$abort  = true;
			}

			$valid_roles = array(
				'administrator',
				'upstream_manager',
				'upstream_user',
				'upstream_client_user',
			);

			$check_role = sanitize_text_field( $post_data['role'] );
			if ( ! isset( $post_data['role'] ) || ! in_array( $check_role, $valid_roles ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! $abort ) {
				// Reset capabilities.
				$roles = new UpStream_Roles();
				$roles->add_default_caps( $check_role );

				$return = 'success';
			}

			echo wp_json_encode( $return );
			exit();
		}

		/**
		 * Signup
		 *
		 * @return void
		 */
		public function signup() {
			$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

			if ( ! isset( $post_data['nonce'] ) ) {
				wp_die( esc_html__( 'Invalid Nonce' ), 'Forbidden', array( 'response' => 403 ) );
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream_signup' ) ) {
				wp_die( esc_html__( 'Invalid Nonce' ), 'Forbidden', array( 'response' => 403 ) );
			}

			$response = wp_remote_post(
				'https://www.getdrip.com/forms/934026428/submissions',
				array(
					'body' => array(
						'fields[email]' => sanitize_email( $post_data['email'] ),
					),
				)
			);

		}

		/**
		 * Import File Prepare
		 *
		 * @return void
		 */
		public function import_file_prepare() {
			$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();

			if ( ! isset( $get_data['nonce'] ) ) {
				wp_die( esc_html__( 'Invalid Nonce' ), 'Forbidden', array( 'response' => 403 ) );
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $get_data['nonce'] ), 'upstream_import_file' ) ) {
				wp_die( esc_html__( 'Invalid Nonce' ), 'Forbidden', array( 'response' => 403 ) );
			}

			$return = array();

			$file_id = (int) $get_data['fileId'];
			if ( ! $file_id ) {
				wp_die( esc_html__( 'Invalid File' ), 'Forbidden', array( 'response' => 403 ) );
			}
			$file = get_attached_file( $file_id );

			if ( ! current_user_can( 'administrator' ) ) {
				$return = array( 'error' => __( 'You must be an administrator to import data.', 'upstream' ) );
			} elseif ( ! $file ) {
				$return = array( 'error' => __( 'No file found.', 'upstream' ) );
			} else {

				$res = UpStream_Import::prepare_file( $file );
				if ( $res['message'] ) {
					$return = array( 'error' => $res['message'] );
				} else {
					$return = array( 'total' => $res['lines'] );
				}
			}

			echo wp_json_encode( $return );
			exit();
		}

		/**
		 * Import File Section
		 *
		 * @return void
		 */
		public function import_file_section() {
			$return    = '';
			$abort     = false;
			$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

			if ( ! isset( $post_data['nonce'] ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream_import_file' ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! isset( $post_data['lineNo'] ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! $abort ) {

				if ( ! current_user_can( 'administrator' ) ) {
					$return = array(
						'success' => false,
						'message' => __( 'You must be an administrator to import data.', 'upstream' ),
					);
				} else {

					$file_id     = intval( $post_data['fileId'] );
					$line_start = intval( $post_data['lineNo'] );

					// returns false if there is no file with that ID.
					$file = get_attached_file( $file_id );

					if ( $file ) {

						$res = UpStream_Import::import_file( $file, $line_start );
						if ( $res ) {
							$return = array(
								'success' => false,
								'message' => $res,
							);
						} else {
							$return = array( 'success' => true );
						}
					} else {
						$return = array(
							'success' => false,
							'message' => __( 'The file could not be found.', 'upstream' ),
						);
					}
				}
			} else {
				$return = array(
					'success' => false,
					'message' => __( 'A general error occurred.', 'upstream' ),
				);
			}

			echo wp_json_encode( $return );
			exit();
		}

		/**
		 * Refresh Projects Meta
		 *
		 * @return void
		 */
		public function refresh_projects_meta() {
			$return    = '';
			$abort     = false;
			$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

			if ( ! isset( $_POST['nonce'] ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream_refresh_projects_meta' ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! $abort ) {
				if ( ! class_exists( 'Upstream_Counts' ) ) {
					include_once UPSTREAM_PLUGIN_DIR . '/includes/class-upstream-counts.php';
				}

				$counts   = new Upstream_Counts( 0 );
				$projects = $counts->projects;

				if ( ! empty( $projects ) ) {
					foreach ( $projects as $project ) {
						$project_object = new UpStream_Project( $project->ID );
						$project_object->update_project_meta();
					}
				}

				$return = 'success';
			}

			echo wp_json_encode( $return );
			exit();
		}

		/**
		 * Cleanup Update Cache
		 *
		 * @return void
		 */
		public function cleanup_update_cache() {
			$return    = '';
			$abort     = false;
			$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

			if ( ! isset( $_POST['nonce'] ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream_cleanup_update_cache' ) ) {
				$return = 'error';
				$abort  = true;
			}

			if ( ! $abort ) {
				$addons = apply_filters( 'allex_addons', array(), 'upstream' );

				foreach ( $addons as $extension ) {
					$extension = str_replace( 'upstream-', '', $extension['slug'] );
					delete_transient( 'upstream.' . $extension . ':plugin_latest_version' );
				}
				delete_site_transient( 'update_plugins' );

				$return = 'success';
			}

			echo wp_json_encode( $return );
			exit();
		}

		/**
		 * Migrate the project milestones
		 */
		public function migrate_milestones_get_projects() {
			$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();

			if ( ! isset( $get_data['nonce'] ) ) {
				wp_die( esc_html__( 'Invalid Nonce' ), 'Forbidden', array( 'response' => 403 ) );
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $get_data['nonce'] ), 'upstream_migrate_milestones' ) ) {
				wp_die( esc_html__( 'Invalid Nonce' ), 'Forbidden', array( 'response' => 403 ) );
			}

			$return = array();

			$projects = get_posts(
				array(
					'post_type'      => 'project',
					'post_status'    => 'any',
					'meta_key'       => '_upstream_project_milestones',
					'posts_per_page' => -1,
				)
			);

			if ( ! empty( $projects ) ) {
				foreach ( $projects as $project ) {
					$milestones = get_post_meta( $project->ID, '_upstream_project_milestones', true );

					$return[] = array(
						'id'    => $project->ID,
						'title' => $project->post_title,
						'count' => count( $milestones ),
					);
				}
			}

			echo wp_json_encode( $return );
			exit();
		}

		/**
		 * Migrate Milestones For Project
		 *
		 * @return void
		 */
		public function migrate_milestones_for_project() {
			$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

			if ( ! isset( $post_data['nonce'] ) ) {
				wp_die( esc_html__( 'Invalid Nonce' ), 'Forbidden', array( 'response' => 403 ) );
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream_migrate_milestones' ) ) {
				wp_die( esc_html__( 'Invalid Nonce' ), 'Forbidden', array( 'response' => 403 ) );
			}

			$return = array();

			if ( ! isset( $post_data['projectId'] ) || empty( absint( $post_data['projectId'] ) ) ) {
				wp_die( esc_html__( 'Invalid project id' ), 'Project not found', array( 'response' => 400 ) );
			}

			// next function will do nothing if project does not exists.
			$project_id = absint( $post_data['projectId'] );

			$return['success'] = \UpStream\Milestones::migrate_legacy_milestones_for_project( $project_id );

			echo wp_json_encode( $return );
			exit();
		}
	}
endif;
