<?php
/**
 * [Short Description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'Featured_Image_Plus' ) ) {

	class Featured_Image_Plus {
		/**
		 * Add featured image types supported.
		 */
		public $types_supported;

		/**
		 * Add theme support.
		 */
		public $theme_support;

		/**
		 * Consturtor.
		 */
		public function __construct() {
			// Use some defaults for the Options, for initial plugin usage.
			$this->types_supported = array( 'post', 'page' );
			$this->theme_support   = ''; // No

			// Retrieve from options, if available; otherwise, use the default values.
			$this->types_supported = get_option( 'fip_types_supported', $this->types_supported );
			$this->theme_support   = get_option( 'fip_theme_support', $this->theme_support );
		}

		/**
		 * Initializor.
		 */
		public function init() {
			add_action( 'wp_loaded', array( $this, 'on_loaded' ) );
		}

		/**
		 * Plugin loaded.
		 */
		public function on_loaded() {
			add_action( 'admin_init', array( $this, 'add_theme_support' ) );
			add_action( 'admin_init', array( $this, 'manage_columns' ) );
			add_action( 'quick_edit_custom_box', array( $this, 'quick_edit' ), 10, 2 );
			add_action( 'bulk_edit_custom_box', array( $this, 'bulk_edit' ), 10, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'no_conflict' ), 11 );
		}

		/**
		 * Add featured image support if Option is yes and theme doesn't have it enabled.
		 */
		public function add_theme_support() {
			if ( function_exists( 'add_theme_support' ) ) {
				if ( 'yes' === $this->theme_support && ! current_theme_supports( 'post-thumbnails' ) ) {
					add_theme_support( 'post-thumbnails' );
				}
			}
		}

		/**
		 * Call actions based on the selected supported types.
		 */
		public function manage_columns() {
			foreach ( $this->types_supported as $type_supported ) {
				// Posts & Pages.
				$columns       = $type_supported . 's';
				$custom_column = $type_supported . 's';

				/**
				 * Add featured images head and body columns with content.
				 */
				add_action(
					'manage_' . $columns . '_columns',
					function( $columns ) use ( $type_supported ) {
						return $this->manage_post_type_columns( $columns, $type_supported );
					}
				);

				add_action(
					'manage_' . $custom_column . '_custom_column',
					function( $column_name, $post_id ) use ( $type_supported ) {
						return $this->add_custom_column_content( $column_name, $post_id, $type_supported );
					},
					10,
					2
				);

				/**
				 * Add sort and filter for the featured images.
				 */
				add_action(
					'current_screen',
					function() {
						$screen = get_current_screen();

						// Sorting.
						add_action( 'manage_' . $screen->id . '_sortable_columns', array( $this, 'manage_sortable_columns' ) );
						add_action( 'pre_get_posts', array( $this, 'sortable_columns_query' ) );

						// Filtering.
						add_action( 'restrict_manage_posts', array( $this, 'add_featured_image_filter' ) );
						add_filter( 'parse_query', array( $this, 'filter_featured_image_query' ) );
					}
				);
			}
		}

		/**
		 * Display the table head for the featured images.
		 */
		public function manage_post_type_columns( $post_columns, $type_supported ) {
			$screen = get_current_screen();

			// Limit this only for default Posts and if not selected to add other CPTs.
			if ( $type_supported === $screen->post_type ) {
				unset( $post_columns['cb'] );
				unset( $post_columns['thumb'] );

				$new_columns['cb']  = '<input type="checkbox" />';
				$new_columns['fip'] = 'Featured Image';
				$post_columns       = array_merge( $new_columns, $post_columns );
			}

			return $post_columns;
		}

		/**
		 * Display the content for the featured images.
		 */
		public function add_custom_column_content( $column_name, $post_id, $type_supported ) {
			$post_type = get_post_type( $post_id );

			if ( $post_type === $type_supported ) {
				switch ( $column_name ) {
					case 'fip':
						$featured_image = '';

						if ( has_post_thumbnail( $post_id ) ) {
							$featured_image = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
						}

						if ( $featured_image ) {
							?>
							<div class="fip-thumb-cont">
								<img src="<?php echo esc_url( $featured_image ); ?>" alt="" class="fip-thumb" />
								<input type="hidden" name="fip-id" value="<?php echo get_post_thumbnail_id( $post_id ); ?>" />
							</div>
							<?php

						} else {
							?>
							<div class="fip-thumb-cont">
								<img src="<?php echo FIP_PLUGIN_IMG_URL; ?>no-image-placeholder.png" alt="" class="fip-thumb" />
							</div>
							<?php
						}
						break;
					default:
						break;
				}
			}
		}

		/**
		 * Add sortable columns.
		 */
		public function manage_sortable_columns( $columns ) {
			$columns['fip'] = 'fip';

			return $columns;
		}

		/**
		 * Run a custom Query to sort the added columns values.
		 */
		public function sortable_columns_query( $query ) {
			if ( ! is_admin() ) {
				return;
			}

			if ( ! empty( $_REQUEST['post_type'] )
				&& in_array( $_REQUEST['post_type'], $this->types_supported, true ) ) {
				$orderby = '';

				if ( ! empty( $query->get( 'orderby' ) ) ) {
					$orderby = $query->get( 'orderby' );
				}

				switch ( $orderby ) {
					case 'fip':
						$query->set( 'meta_key', '_thumbnail_id' ); // Sort by featured image ID.
						$query->set(
							'orderby',
							array(
								'meta_value' => 'DESC',
								'ID'         => 'DESC',
							)
						);

						add_filter( 'get_meta_sql', array( $this, 'filter_get_meta_sql' ) );
						break;
					default:
						break;
				}
			}
		}

		/**
		 * Modify the SQL to show posts without _thumbnail_id when we do orderby.
		 * By default the query will exclude all posts that don't have the specified meta key.
		 */
		public function filter_get_meta_sql( $clauses ) {
			remove_filter( 'get_meta_sql', 'filter_get_meta_sql' );

			// Change the inner join to a left join,
			// and change the where so it is applied to the join, not the results of the query.
			$clauses['join']  = str_replace( 'INNER JOIN', 'LEFT JOIN', $clauses['join'] ) . $clauses['where'];
			$clauses['where'] = '';

			return $clauses;
		}

		/**
		 * Add custom filter for the featured image for the `fip`.
		 */
		public function add_featured_image_filter( $post_type ) {
			$selected = '';

			if ( ! is_admin() ) {
				return;
			}

			if ( ! in_array( $post_type, $this->types_supported, true ) ) {
				return;
			}

			if ( ! empty( $_REQUEST['fip_filter'] ) ) {
				$selected = $_REQUEST['fip_filter'];
			}

			echo sprintf(
				'<select id="fip-filter" name="fip_filter" class="postform">
					<option value="">All Featured Images</option>
					<option value="with-featured-image" %1$s>With Featured Image</option>
					<option value="without-featured-image" %2$s>Without Featured Image</option>
				</select>',
				( 'with-featured-image' === $selected ) ? 'selected' : '',
				( 'without-featured-image' === $selected ) ? 'selected' : '',
			);
		}

		/**
		 * Run a custom Query to filter out the results based on the `fip`.
		 */
		public function filter_featured_image_query( $query ) {
			global $pagenow;

			if ( ! ( is_admin() && $query->is_main_query() ) ) {
				return $query;
			}

			if ( empty( $_REQUEST['fip_filter'] ) ) {
				return $query;
			}

			if ( 'edit.php' !== $pagenow ) {
				return $query;
			}

			// Check if the there is ID set for the featured image or NOT.
			if ( 'with-featured-image' === $_REQUEST['fip_filter'] ) {
				$query->query_vars['meta_query'][] = array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				);
			} else {
				$query->query_vars['meta_query'][] = array(
					'key'     => '_thumbnail_id',
					'compare' => 'NOT EXISTS',
				);
			}
		}

		/**
		 * Use quick edit to manage featured images.
		 */
		public function quick_edit( $column_name, $post_type ) {
			if ( 'fip' === $column_name ) {
				$this->display_quick_edit_view( $post_type );
			}
		}

		/**
		 * Use bulk edit to manage featured images.
		 */
		public function bulk_edit( $column_name, $post_type ) {
			if ( 'fip' === $column_name ) {
				$this->display_quick_edit_view( $post_type, 'bulk' );
			}
		}

		/**
		 * Custom block used to manage featured images in the Quick/Bulk edit mode.
		 */
		public function display_quick_edit_view( $post_type, $type = 'quick' ) {
			require FIP_PLUGIN_DIR_PATH . 'inc/admin/views/featured-image-inline.php'; // Cannot use require_once.
		}

		/**
		 * There were some JS conflicts with 3rd party plugins that use jQuery UI Button.
		 */
		public function no_conflict( $hook_suffix ) {
			if ( 'edit.php' === $hook_suffix && ! empty( $_GET['post_type'] ) ) {
				wp_deregister_script( 'jquery-ui-button' );
			}
		}
	}

	$fip = new Featured_Image_Plus();
	$fip->init();
}
