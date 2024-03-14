<?php
/**
 * UpStream_Admin_Options
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Admin_Options' ) ) :

	/**
	 * CMB2 Theme Options
	 *
	 * @version 0.1.0
	 */
	class UpStream_Admin_Options {


		/**
		 * Array of metaboxes/fields
		 *
		 * @var array
		 */
		public $option_metabox = array();

		/**
		 * Array of metaboxes/fields
		 *
		 * @var array
		 */
		public $metabox_id = '';

		/**
		 * Options Page title
		 *
		 * @var string
		 */
		protected $title = '';

		/**
		 * Options Page title
		 *
		 * @var string
		 */
		protected $menu_title = '';

		/**
		 * Options Tab Pages
		 *
		 * @var array
		 */
		public $options_pages = array();

		/**
		 * Holds an instance of the object
		 *
		 * @var Myprefix_Admin
		 **/
		private static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 0.1.0
		 */
		private function __construct() {
			// Set our title.
			$this->menu_title = __( 'UpStream', 'upstream' );
			$this->title      = __( 'UpStream Project Manager', 'upstream' );
		}

		/**
		 * Returns the running object
		 *
		 * @return Myprefix_Admin
		 **/
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->hooks();
			}

			return self::$instance;
		}

		/**
		 * Initiate our hooks
		 *
		 * @since 0.1.0
		 */
		public function hooks() {
			add_action( 'admin_init', array( $this, 'init' ) );
			add_action( 'admin_menu', array( $this, 'add_options_pages' ) );

			add_filter( 'cmb2_set_options', array( $this, 'filter_cmb2_set_options' ), 10, 2 );
			add_filter( 'allex_upgrade_show_sidebar_ad', array( $this, 'filter_allex_upgrade_show_sidebar_ad' ), 20, 3 );
		}

		/**
		 * Register our setting to WP
		 *
		 * @since  0.1.0
		 */
		public function init() {
			$option_tabs = self::option_fields();
			foreach ( $option_tabs as $index => $option_tab ) {
				register_setting( $option_tab['id'], $option_tab['id'] );
			}
		}

		/**
		 * Add Options Pages
		 *
		 * @return void
		 */
		public function add_options_pages() {
			$option_tabs = self::option_fields();

			foreach ( $option_tabs as $index => $option_tab ) {
				if ( 0 === $index ) {
					$this->options_pages[] = add_menu_page(
						$this->title,
						$this->menu_title,
						'manage_upstream',
						$option_tab['id'],
						array( $this, 'admin_page_display' ),
						'dashicons-arrow-up-alt'
					); // Link admin menu to first tab.

					add_submenu_page(
						$option_tabs[0]['id'],
						$this->menu_title,
						$option_tab['menu_title'],
						'manage_upstream',
						$option_tab['id'],
						array( $this, 'admin_page_display' )
					); // Duplicate menu link for first submenu page.
				} else {
					$this->options_pages[] = add_submenu_page(
						$option_tabs[0]['id'],
						$this->menu_title,
						$option_tab['menu_title'],
						'manage_upstream',
						$option_tab['id'],
						array( $this, 'admin_page_display' )
					);
				}
			}

			foreach ( $this->options_pages as $page ) {
				// Include CMB CSS in the head to avoid FOUC.
				add_action( "admin_print_styles-{$page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
			}
		}

		/**
		 * Block displaying the sidebar twice, when viewing the extensions' page.
		 *
		 * @param mixed  $show_display Show Display.
		 * @param string $plugin_name Plugin Name.
		 * @param string $context Context.
		 *
		 * @return bool
		 */
		public function filter_allex_upgrade_show_sidebar_ad( $show_display, $plugin_name = '', $context = '' ) {
			if ( 'upstream' === $plugin_name && 'addons' === $context ) {
				return false;
			}

			return $show_display;
		}

		/**
		 * Admin page markup. Mostly handled by CMB2
		 *
		 * @since  0.1.0
		 */
		public function admin_page_display() {
			$option_tabs = apply_filters( 'upstream_option_metaboxes', self::option_fields() ); // get all option tabs.
			$tab_forms   = array();

			/**
			 * Filter to return a boolean value to say if it should display or not a sidebar.
			 *
			 * @var bool
			 */
			$show_sidebar = apply_filters( 'allex_upgrade_show_sidebar_ad', true, 'upstream' ); ?>

			<div class="wrap upstream_options <?php echo $show_sidebar ? 'upstream_with_sidebar container' : ''; ?>">

				<h2><?php echo esc_html( $this->title ); ?></h2>

				<div class="row">
					<div class="<?php echo $show_sidebar ? 'col-md-8' : 'col-md-12'; ?>">
						<!-- Options Page Nav Tabs -->
						<h2 class="nav-tab-wrapper">
							<?php
							foreach ( $option_tabs as $option_tab ) :
								$tab_slug  = $option_tab['id'];
								$nav_class = 'nav-tab';
								$get_data  = isset( $_GET ) ? wp_unslash( $_GET ) : array();

								if ( sanitize_text_field( $get_data['page'] ) === $tab_slug ) {
									$nav_class  .= ' nav-tab-active'; // add active class to current tab.
									$tab_forms[] = $option_tab; // add current tab to forms to be rendered.
								}
								?>
								<a class="<?php echo esc_attr( $nav_class ); ?>"
									href="<?php esc_url( menu_page_url( $tab_slug ) ); ?>">
									<?php
									echo esc_attr( $option_tab['title'] );
									?>
								</a>
							<?php endforeach; ?>
						</h2>
						<!-- End of Nav Tabs -->

						<?php foreach ( $tab_forms as $tab_form ) : // render all tab forms (normally just 1 form). ?>
							<div id="<?php echo esc_attr( $tab_form['id'] ); ?>" class="cmb-form group">
								<div class="metabox-holder">
									<div class="postbox pad">
										<h3 class="title"><?php // esc_html_e($tab_form['title'], 'upstream');. ?></h3>
										<div class="desc"><?php echo esc_html( $tab_form['desc'] ); ?></div>
										<?php cmb2_metabox_form( $tab_form, $tab_form['id'] ); ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ( $show_sidebar ) : ?>
						<div class="col-md-3">
							<?php do_action( 'allex_upgrade_sidebar_ad', 'upstream' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php
		}


		/**
		 * Add the options metabox to the array of metaboxes
		 *
		 * @since  0.1.0
		 */
		public function option_fields() {
			// hook in our save notices
			// add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );.

			// Only need to initiate the array once per page-load.
			if ( ! empty( $this->option_metabox ) ) {
				return $this->option_metabox;
			}

			$general_options        = new UpStream_Options_General();
			$this->option_metabox[] = $general_options->options();

			$project_options        = new UpStream_Options_Projects();
			$this->option_metabox[] = $project_options->options();

			// $milestone_options      = new UpStream_Options_Milestones();
			// $this->option_metabox[] = $milestone_options->options();

			if ( ! upstream_disable_tasks() ) {
				$task_options           = new UpStream_Options_Tasks();
				$this->option_metabox[] = $task_options->options();
			}

			if ( ! upstream_disable_bugs() ) {
				$bug_options            = new UpStream_Options_Bugs();
				$this->option_metabox[] = $bug_options->options();
			}

			$options = (array) get_option( 'upstream_general' );
			$enable  = ! empty( $options['beta_features'] ) && ! empty( $options['beta_features'][0] ) ? '1' === (string) $options['beta_features'][0] : false;

			if ( $enable ) {
				if ( class_exists( 'UpStream_Options_Import' ) ) {
					$import_options         = new UpStream_Options_Import();
					$this->option_metabox[] = $import_options->options();
				}
			}

			$container              = UpStream::instance()->get_container();
			$ext_options            = new UpStream_Options_Extensions( $container );
			$this->option_metabox[] = $ext_options->get_options();

			return apply_filters( 'upstream_option_metaboxes', $this->option_metabox );
		}

		/**
		 * Public getter method for retrieving protected/private variables
		 *
		 * @since 0.1.0
		 *
		 * @param string $field Field to retrieve.
		 * @throws Exception Exception.
		 * @return mixed  Field value or exception is thrown.
		 */
		public function __get( $field ) {
			// Allowed fields to retrieve.
			if ( in_array( $field, array( 'key', 'fields', 'title', 'options_pages' ), true ) ) {
				return $this->{$field};
			}
			if ( 'option_metabox' === $field ) {
				return $this->option_fields();
			}

			throw new Exception( 'Invalid property: ' . $field );
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
		 * Filter Cmb2 Set Options
		 *
		 * @param string $key Key.
		 * @param array  $options Options.
		 */
		public function filter_cmb2_set_options( $key, $options ) {
			if ( 'upstream_general' === $key ) {
				// For "Who can post images in comments", update capabilities based on the selected roles.
				$selected = $options['media_comment_images'];

				$roles = $this->get_roles();

				foreach ( $roles as $index => $role_name ) {
					$role = get_role( $index );

					if ( in_array( $index, $selected ) ) {
						$role->add_cap( 'upstream_comment_images' );
					} else {
						$role->remove_cap( 'upstream_comment_images' );
					}
				}
			}

			return $options;
		}
	}

	/**
	 * Helper function to get/return the UpStream_Admin_Options object
	 *
	 * @since  0.1.0
	 * @return UpStream_Admin_Options object
	 */
	function upstream_admin_options() {
		return UpStream_Admin_Options::get_instance();
	}

	// Get it started.
	upstream_admin_options();

endif;
