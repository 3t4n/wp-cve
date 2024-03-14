<?php
/**
 * An extension for the Connections Business Directory plugin that adds useful links and resources to the WordPress Admin Bar.
 *
 * @package   Connections Business Directory Extension - Toolbar
 * @category  Extension
 * @author    Steven A. Zahm
 * @license   GPL-2.0+
 * @link      https://connections-pro.com
 * @copyright 2023 Steven A. Zahm
 *
 * @wordpress-plugin
 * Plugin Name:       Connections Business Directory Extension - Toolbar
 * Plugin URI:        https://connections-pro.com/add-on/toolbar/
 * Description:       An extension for the Connections Business Directory plugin that adds useful links and resources to the WordPress Admin Bar.
 * Version:           1.4
 * Requires at least: 5.6
 * Requires PHP:      7.0
 * Author:            Steven A. Zahm
 * Author URI:        https://connections-pro.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connections-toolbar
 * Domain Path:       /languages
 */

use Connections_Directory\Taxonomy\Registry;
use Connections_Directory\Utility\_nonce;

if ( ! class_exists( 'CN_Toolbar' ) ) {

	final class CN_Toolbar {

		/**
		 * The plugin version.
		 *
		 * @since 1.3
		 */
		const VERSION = '1.4';

		/**
		 * @var CN_Toolbar Instance of this class.
		 */
		private static $instance;

		/**
		 * @var string The absolute path this file.
		 *
		 * @since 1.4
		 */
		private $file = '';

		/**
		 * @var string The URL to the plugin's folder.
		 *
		 * @since 1.4
		 */
		private $url = '';

		/**
		 * @var string The absolute path to this plugin's folder.
		 *
		 * @since 1.4
		 */
		private $path = '';

		/**
		 * @var string The basename of the plugin.
		 *
		 * @since 1.4
		 */
		private $basename = '';

		/**
		 * A dummy constructor to prevent class from being loaded more than once.
		 *
		 * @since 1.0
		 */
		private function __construct() { /* Do nothing here */ }

		/**
		 * Insures that only one instance exists at any one time.
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @return CN_Toolbar
		 */
		public static function getInstance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {

				$self = new self();

				$self->file     = __FILE__;
				$self->url      = plugin_dir_url( $self->file );
				$self->path     = plugin_dir_path( $self->file );
				$self->basename = plugin_basename( $self->file );


				/**
				 * This should run on the `plugins_loaded` action hook. Since the extension loads on the
				 * `plugins_loaded` action hook, load immediately.
				 */
				cnText_Domain::register(
					'connections-toolbar',
					$self->basename,
					'load'
				);

				$self->hooks();

				self::$instance = $self;
			}

			return self::$instance;
		}

		/**
		 * Initiate the plugin.
		 *
		 * @internal
		 * @since 1.0
		 */
		private static function hooks() {

			/*
			 * Add the toolbar and menu items.
			 */
			add_action( 'admin_bar_menu', array( __CLASS__, 'toolbar' ), 99 );

			/*
			 * Add the styles to the page head.
			 */
			add_action( 'wp_head', array( __CLASS__, 'css' ) );
			add_action( 'admin_head', array( __CLASS__, 'css' ) );
		}

		/**
		 * @param WP_Admin_Bar $admin_bar
		 */
		public static function toolbar( $admin_bar ) {

			// Bail if the user is not an admin that can manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$admin_bar->add_node(
				array(
					'id'    => 'cn-toolbar',
					'title' => __( 'Connections', 'connections-toolbar' ),
					'href'  => add_query_arg(
						array( 'page' => 'connections_dashboard' ),
						self_admin_url( 'admin.php' )
					),
					'meta'  => array(
						'class' => 'icon-connections',
						'title' => _x(
							'Connections Dashboard',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-dashboard',
					'parent' => 'cn-toolbar',
					'title'  => __( 'Dashboard', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_dashboard' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x( 'Dashboard', 'This is a tooltip shown on mouse hover.', 'connections-toolbar' ),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-manage',
					'parent' => 'cn-toolbar',
					'title'  => __( 'Manage', 'connections-toolbar' ),
					'href'   => esc_url(
						_nonce::url(
							add_query_arg(
								array(
									'page'      => 'connections_manage',
									'cn-action' => 'filter',
									'status'    => 'all',
								),
								self_admin_url( 'admin.php' )
							),
							'filter'
						)
					),
					'meta'   => array(
						'title' => _x( 'Manage', 'This is a tooltip shown on mouse hover.', 'connections-toolbar' ),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-manage-filter-approved',
					'parent' => 'cn-toolbar-manage',
					'title'  => __( 'Filter: Approved', 'connections-toolbar' ),
					'href'   => esc_url(
						_nonce::url(
							add_query_arg(
								array(
									'page'      => 'connections_manage',
									'cn-action' => 'filter',
									'status'    => 'approved',
								),
								self_admin_url( 'admin.php' )
							),
							'filter'
						)
					),
					'meta'   => array(
						'title' => _x(
							'Show Only Approved Entries',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-manage-filter-pending',
					'parent' => 'cn-toolbar-manage',
					'title'  => __( 'Filter: Pending', 'connections-toolbar' ),
					'href'   => esc_url(
						_nonce::url(
							add_query_arg(
								array(
									'page'      => 'connections_manage',
									'cn-action' => 'filter',
									'status'    => 'pending',
								),
								self_admin_url( 'admin.php' )
							),
							'filter'
						)
					),
					'meta'   => array(
						'title' => _x(
							'Show Entries Awaiting Moderation',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-manage-add-entry',
					'parent' => 'cn-toolbar-manage',
					'title'  => __( 'Add New Entry', 'connections-toolbar' ),
					'href'   => add_query_arg( array( 'page' => 'connections_add' ), self_admin_url( 'admin.php' ) ),
					'meta'   => array(
						'title' => _x(
							'Add New Entry',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$taxonomies = Registry::get()->getTaxonomies();

			foreach ( $taxonomies as $taxonomy ) {

				$admin_bar->add_node(
					array(
						'id'     => "cn-toolbar-manage-{$taxonomy->getSlug()}",
						'parent' => 'cn-toolbar-manage',
						'title'  => $taxonomy->getLabels()->menu_name,
						'href'   => add_query_arg(
							array( 'page' => "connections_manage_{$taxonomy->getSlug()}_terms" ),
							self_admin_url( 'admin.php' )
						),
						'meta'   => array(
							'title' => $taxonomy->getLabels()->menu_name,
						),
					)
				);
            }

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-templates',
					'parent' => 'cn-toolbar',
					'title'  => __( 'Templates', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_templates' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Manage Templates',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-templates-filter-individual',
					'parent' => 'cn-toolbar-templates',
					'title'  => __( 'Filter: Individual', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_templates', 'type' => 'individual' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Show the "Individual" Template Type',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-templates-filter-organization',
					'parent' => 'cn-toolbar-templates',
					'title'  => __( 'Filter: Organization', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_templates', 'type' => 'organization' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Show the "Organization" Template Type',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-templates-filter-family',
					'parent' => 'cn-toolbar-templates',
					'title'  => __( 'Filter: Family', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_templates', 'type' => 'family' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Show the "Family" Template Type',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-templates-filter-anniversary',
					'parent' => 'cn-toolbar-templates',
					'title'  => __( 'Filter: Anniversary', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_templates', 'type' => 'anniversary' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Show the "Anniversary" Template Type',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-templates-filter-birthday',
					'parent' => 'cn-toolbar-templates',
					'title'  => __( 'Filter: Birthday', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_templates', 'type' => 'birthday' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Show the "Birthday" Template Type',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-templates-secondary-group',
					'parent' => 'cn-toolbar-templates',
					'group'  => TRUE,
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-purchase-templates',
					'parent' => 'cn-toolbar-templates-secondary-group',
					'title'  => __( 'Get More', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/templates/' ),
					'meta'   => array(
						'title'  => _x(
							'Purchase Premium Templates',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-settings',
					'parent' => 'cn-toolbar',
					'title'  => __( 'Settings', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_settings' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x( 'Settings', 'This is a tooltip shown on mouse hover.', 'connections-toolbar' ),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-settings-general',
					'parent' => 'cn-toolbar-settings',
					'title'  => __( 'General', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_settings' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'General Settings',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-settings-display',
					'parent' => 'cn-toolbar-settings',
					'title'  => __( 'Display', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_settings', 'tab' => 'display' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Display Settings',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-settings-images',
					'parent' => 'cn-toolbar-settings',
					'title'  => __( 'Images', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_settings', 'tab' => 'images' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Images Settings',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-settings-search',
					'parent' => 'cn-toolbar-settings',
					'title'  => __( 'Search', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_settings', 'tab' => 'search' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Search Settings',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-settings-seo',
					'parent' => 'cn-toolbar-settings',
					'title'  => __( 'SEO', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_settings', 'tab' => 'seo' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'SEO Settings',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-settings-roles',
					'parent' => 'cn-toolbar-settings',
					'title'  => __( 'Roles', 'connections-toolbar' ),
					'href'   => add_query_arg( array( 'page' => 'connections_roles' ), self_admin_url( 'admin.php' ) ),
					'meta'   => array(
						'title' => _x(
							'Roles and Capabilities',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-settings-advanced',
					'parent' => 'cn-toolbar-settings',
					'title'  => __( 'Advanced', 'connections-toolbar' ),
					'href'   => add_query_arg(
						array( 'page' => 'connections_settings', 'tab' => 'advanced' ),
						self_admin_url( 'admin.php' )
					),
					'meta'   => array(
						'title' => _x(
							'Advanced Settings',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-add-ons-group',
					'parent' => 'cn-toolbar',
					'group'  => TRUE,
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-add-ons',
					'parent' => 'cn-toolbar-add-ons-group',
					'title'  => __( 'Extensions &amp; Templates', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/' ),
					'meta'   => array(
						'title'  => _x(
							'Extensions &amp; Extensions',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-add-on-extensions',
					'parent' => 'cn-toolbar-add-ons',
					'title'  => __( 'Extensions', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/extensions/' ),
					'meta'   => array(
						'title'  => _x(
							'Purchase Extensions',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-add-on-templates',
					'parent' => 'cn-toolbar-add-ons',
					'title'  => __( 'Templates', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/templates/' ),
					'meta'   => array(
						'title'  => _x(
							'Purchase Premium Templates',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-group',
					'parent' => 'cn-toolbar',
					'group'  => TRUE,
					'meta'   => array(
						'class' => 'ab-sub-secondary',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-forums',
					'parent' => 'cn-toolbar-support-group',
					'title'  => __( 'Support Forums', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://wordpress.org/support/plugin/connections/' ),
					'meta'   => array(
						'title'  => _x(
							'Support Forums',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-forum-feature-requests',
					'parent' => 'cn-toolbar-support-forums',
					'title'  => __( 'Feature Requests', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://wordpress.org/support/plugin/connections/' ),
					'meta'   => array(
						'title'  => _x(
							'Feature Requests',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-forum-pre-sales',
					'parent' => 'cn-toolbar-support-forums',
					'title'  => __( 'Pre Sales Questions', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/contact/' ),
					'meta'   => array(
						'title'  => _x(
							'Pre Sales Questions',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-forum-general',
					'parent' => 'cn-toolbar-support-forums',
					'title'  => __( 'General', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://wordpress.org/support/plugin/connections/' ),
					'meta'   => array(
						'title'  => _x( 'General', 'This is a tooltip shown on mouse hover.', 'connections-toolbar' ),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-forum-extension',
					'parent' => 'cn-toolbar-support-forums',
					'title'  => __( 'Extensions', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://wordpress.org/support/plugin/connections/' ),
					'meta'   => array(
						'title'  => _x(
							'Extensions',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-forum-template',
					'parent' => 'cn-toolbar-support-forums',
					'title'  => __( 'Templates', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://wordpress.org/support/plugin/connections/' ),
					'meta'   => array(
						'title'  => _x( 'Templates', 'This is a tooltip shown on mouse hover.', 'connections-toolbar' ),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-forum-plugin-conflicts',
					'parent' => 'cn-toolbar-support-forums',
					'title'  => __( 'Plugin Conflicts', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://wordpress.org/support/plugin/connections/' ),
					'meta'   => array(
						'title'  => _x(
							'Plugin Conflicts',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-forum-theme-conflicts',
					'parent' => 'cn-toolbar-support-forums',
					'title'  => __( 'Theme Conflicts', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://wordpress.org/support/plugin/connections/' ),
					'meta'   => array(
						'title'  => _x(
							'Theme Conflicts',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-documentation',
					'parent' => 'cn-toolbar-support-group',
					'title'  => __( 'Documentation', 'connections-toolbar' ),
					'href'   => FALSE,
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-documentation-faqs',
					'parent' => 'cn-toolbar-support-documentation',
					'title'  => __( 'FAQs', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/faq/' ),
					'meta'   => array(
						'title'  => _x(
							'Frequently Asked Questions',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-documentation-quicktips',
					'parent' => 'cn-toolbar-support-documentation',
					'title'  => __( 'QuickTips', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/quicktips/' ),
					'meta'   => array(
						'title'  => _x( 'QuickTips', 'This is a tooltip shown on mouse hover.', 'connections-toolbar' ),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-documentation-shortcodes',
					'parent' => 'cn-toolbar-support-documentation',
					'title'  => __( 'Shortcodes', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/documentation/shortcodes/' ),
					'meta'   => array(
						'title'  => _x(
							'Shortcodes',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			$admin_bar->add_node(
				array(
					'id'     => 'cn-toolbar-support-documentation-translation',
					'parent' => 'cn-toolbar-support-documentation',
					'title'  => __( 'Translation', 'connections-toolbar' ),
					'href'   => esc_url_raw( 'https://connections-pro.com/documentation/translation/' ),
					'meta'   => array(
						'title'  => _x(
							'Translation',
							'This is a tooltip shown on mouse hover.',
							'connections-toolbar'
						),
						'target' => '_blank',
					),
				)
			);

			//$strSearch = __( 'Search', 'connections-toolbar' );

			/* Disable this for now as it causes PHP errors on the site. reason is unknown at the moment. */
			/*$admin_bar->add_node( array(
				'id'    => 'cn-toolbar-support-documentation-search',
				'parent' => 'cn-toolbar-support-group',
				'title' => '
					<form method="get" action="https://connections-pro.com/" class=" " target="_blank">
					<input type="text" placeholder="' . $strSearch . '" onblur="this.value=(this.value==\'\') ? \'' . $strSearch . '\' : this.value;" onfocus="this.value=(this.value==\'' . $strSearch . '\') ? \'\' : this.value;" value="' . $strSearch . '" name="s" value="" class="text cn-toolbar-search-input" />
					<input type="hidden" name="post_type[]" value="documentation" />
					<input type="hidden" name="post_type[]" value="faqs" />
					<input type="submit" value="' . __( 'GO', 'connections-toolbar' ) . '" class="cn-toolbar-search-submit"  /></form>',
				'href'  => FALSE,
				'meta'  => array(
					'title' => _x( 'Search the documentation.', 'This is a tooltip shown on mouse hover.', 'connections-toolbar' ),
					'target' => '_blank',
				),
			));*/

			/*
			 * Rather than create a bunch of hooks or filters
			 * to allow adding/removing nodes; provide an action
			 * passing $menu_bar that way one knows the core
			 * toolbar nodes have been added.
			 */
			do_action( 'cn_admin_bar_menu', $admin_bar );
		}

		public static function css() {

			// No styles if admin bar is disabled or user is not logged in.
			if ( ! is_admin_bar_showing() || ! is_user_logged_in() ) {
				return;
			}

			?>
<style>
	#wpadminbar.nojs .ab-top-menu > li.menupop.icon-connections:hover > .ab-item,
	#wpadminbar .ab-top-menu > li.menupop.icon-connections.hover > .ab-item,
	#wpadminbar.nojs .ab-top-menu > li.menupop.icon-connections > .ab-item,
	#wpadminbar .ab-top-menu > li.menupop.icon-connections > .ab-item {
		background-image: url(<?php echo esc_url_raw( plugins_url( 'connections/assets/images/menu.png' ) ); ?>);
		background-repeat: no-repeat;
		background-position: 0.85em 50%;
		padding-left: 30px;
	}

	#wpadminbar .cn-toolbar-search-input {
		width: 140px;
	}

	#wp-admin-bar-ddw-edd-eddsupportsections .ab-item,
	#wp-admin-bar-ddw-edd-edddocsquick .ab-item,
	#wp-admin-bar-ddw-edd-edddocssections .ab-item,
	#wpadminbar .cn-toolbar-search-input,
	#wpadminbar .cn-toolbar-search-submit {
		color: #21759b !important;
		text-shadow: none;
	}

	#wpadminbar .cn-toolbar-search-input,
	#wpadminbar .cn-toolbar-search-submit {
		background-color: #fff;
		height: 18px;
		line-height: 18px;
		padding: 1px 4px;
	}

	#wpadminbar .cn-toolbar-search-submit {
		-webkit-border-radius: 11px;
		-moz-border-radius: 11px;
		border-radius: 11px;
		font-size: 0.67em;
		margin: 0 0 0 2px;
	}
</style>
			<?php
		}
	}

	/**
	 * Start up the class.
	 *
	 * @access public
	 * @since  1.0
	 * @return CN_Toolbar
	 */
	function Connections_Toolbar() {

		return CN_Toolbar::getInstance();
	}

	/**
	 * Start the plugin.
	 *
	 * Connections loads at default priority 10, this add-on is dependent on Connections,
	 * and other add-ons; load at priority 10.1 that we'll want to be able to hook into the toolbar,
	 * we'll load with priority 10.1, so we know Connections and its other add-ons will be loaded
	 * and ready first.
	 */
	add_action( 'Connections_Directory/Loaded', 'Connections_Toolbar' );
}
