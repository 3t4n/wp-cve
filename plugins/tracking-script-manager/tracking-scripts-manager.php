<?php
/**
 * Plugin Name: Tracking Script Manager
 * Plugin URI: http://wordpress.org/plugins/tracking-script-manager/
 * Description: A plugin that allows you to add tracking scripts to your site.
 * Version: 2.0.11
 * Author: Red8 Interactive
 * Author URI: http://red8interactive.com
 * License: GPLv2 or later
 */
/*
	Copyright 2019 Red8 Interactive  (email : james@red8interactive.com)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'Tracking_Scripts' ) ) {

	class Tracking_Scripts {
		/**
		 * @var TSM_Process_Tracking_Scripts
		 */
		protected $process_all;

		function __construct() {
		}

		public function initialize() {

			// Constants
			define( 'TRACKING_SCRIPT_PATH', plugins_url( ' ', __FILE__ ) );
			define( 'TRACKING_SCRIPT_BASENAME', plugin_basename( __FILE__ ) );
			define( 'TRACKING_SCRIPT_DIR_PATH', plugin_dir_path( __FILE__ ) );
			define( 'TRACKING_SCRIPT_TEXTDOMAIN', 'tracking-scripts-manager' );
			// Actions
			add_action( 'init', array( $this, 'register_scripts_post_type' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'wp_head', array( $this, 'find_header_tracking_codes' ), 10 );
			add_action( 'wp_footer', array( $this, 'find_footer_tracking_codes' ), 10 );
			add_action( 'admin_menu', array( $this, 'tracking_scripts_create_menu' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_script_metaboxes' ) );
			add_action( 'wp_ajax_tracking_scripts_get_posts', array( $this, 'tracking_scripts_posts_ajax_handler' ) );
			add_action(
				'manage_r8_tracking_scripts_posts_custom_column',
				array(
					$this,
					'tracking_script_column_content',
				),
				10,
				2
			);
			add_action( 'wp_body_open', array( $this, 'find_page_tracking_codes' ) );
			add_action( 'tsm_page_scripts', array( $this, 'find_page_tracking_codes' ) );
			add_action( 'admin_init', array( $this, 'process_handler' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			// fallback for page scripts if wp_body_open action isn't supported
			add_action(
				'get_footer',
				function () {
					if ( did_action( 'wp_body_open' ) === 0 ) {
						add_action( 'wp_footer', array( $this, 'find_page_tracking_codes' ) );
					}
				}
			);
			// Filters
			add_filter( 'manage_r8_tracking_scripts_posts_columns', array( $this, 'add_tracking_script_columns' ) );
			add_filter(
				'manage_edit-r8_tracking_scripts_sortable_columns',
				array(
					$this,
					'tracking_scripts_column_sort',
				)
			);
			// Includes
			require_once plugin_dir_path( __FILE__ ) . 'classes/wp-async-request.php';
			require_once plugin_dir_path( __FILE__ ) . 'classes/wp-background-process.php';
			require_once plugin_dir_path( __FILE__ ) . 'classes/class-process-tracking-scripts.php';
			$this->process_all = new TSM_Process_Tracking_Scripts();
		}

		/*************************************************
		 * Front End
		 **************************************************/
		public function process_handler() {
			if ( ! isset( $_GET['tsm_update_scripts'] ) || ! isset( $_GET['_wpnonce'] ) ) {
				return;
			}
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'tsm_update_scripts' ) ) {
				return;
			}
			if ( 'true' === $_GET['tsm_update_scripts'] ) {
				update_option( 'tsm_is_processing', true );
				$this->handle_all();
			}
		}

		protected function handle_all() {
			$scripts = $this->get_tracking_scripts();
			if ( ! empty( $scripts ) ) {
				foreach ( $scripts as $script ) {
					$this->process_all->push_to_queue( $script );
				}
				$this->process_all->save()->dispatch();
			}
		}


		protected function get_tracking_scripts() {
			$scripts        = array();
			$header_scripts = get_option( 'header_tracking_script_code' ) ? json_decode( get_option( 'header_tracking_script_code' ) ) : null;
			$page_scripts   = get_option( 'page_tracking_script_code' ) ? json_decode( get_option( 'page_tracking_script_code' ) ) : null;
			$footer_scripts = get_option( 'footer_tracking_script_code' ) ? json_decode( get_option( 'footer_tracking_script_code' ) ) : null;
			if ( ! empty( $header_scripts ) ) {
				$scripts = array_merge( $scripts, $header_scripts );
			}
			if ( ! empty( $page_scripts ) ) {
				$scripts = array_merge( $scripts, $page_scripts );
			}
			if ( ! empty( $footer_scripts ) ) {
				$scripts = array_merge( $scripts, $footer_scripts );
			}

			return $scripts;
		}

		function admin_notices() {
			$class                = 'notice notice-info is-dismissible';
			$header_scripts       = get_option( 'header_tracking_script_code' );
			$page_scripts         = get_option( 'page_tracking_script_code' );
			$footer_scripts       = get_option( 'footer_tracking_script_code' );
			$is_processing        = get_option( 'tsm_is_processing' );
			$has_tracking_scripts = $header_scripts || $page_scripts || $footer_scripts;
			$is_admin             = current_user_can( 'manage_options' );
			if ( $has_tracking_scripts && $is_processing && $is_admin ) {
				$message = __( 'Your scripts are currently processing. This may take several minutes. If you don’t see all of your scripts please wait a moment and refresh the page.', TRACKING_SCRIPT_TEXTDOMAIN );
				$notice  = sprintf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
				echo esc_html( $notice );
			}
			if ( $has_tracking_scripts && ! $is_processing && $is_admin ) {
				$url     = wp_nonce_url( admin_url( 'edit.php?post_type=r8_tracking_scripts&tsm_update_scripts=true&tsm_is_processing=true' ), 'tsm_update_scripts' );
				$message = __( 'Tracking Scripts Manager has updated to a new version, click OK to update your scripts to the updated version.', TRACKING_SCRIPT_TEXTDOMAIN );
				$notice  = sprintf( '<div class="%1$s"><p>%2$s</p><a class="button button-primary" href="%3$s" style="margin-bottom: .5em;">OK</a></div>', esc_attr( $class ), esc_html( $message ), esc_url( $url ) );
				echo esc_html( $notice );
			}
		}



		public function print_tsm_scripts( $script_id, $page, $page_id, $expiry_info ) {
			$expiry_data = $this->expiry_data( $expiry_info );
			$if_expire   = $this->check_expiry_script( $expiry_data['type'], $expiry_data['start_date'], $expiry_data['end_date'], $script_id );
			$script      = get_post_meta( $script_id, 'r8_tsm_script_code', true );

			$encoded_save = get_post_meta( $script_id, 'r8_tsm_encoded_save', true );
			if(!$encoded_save){
				$script=base64_encode($script);
				$this->save_script($script_id,$script);
			}

			$page_script = $this->esc_script( $script );


			// Check if this is the right page
			if ( ( is_array( $page ) && in_array( intval( $page_id ), $page, true ) ) || empty( $page ) ) {
				// Is it scheduled and not expired or set never to expire?
				if ( 'Schedule' === $expiry_data['type'] && ! $if_expire || 'Never' === $expiry_data['type'] ) {
					// Render script
					echo( $page_script );
				}
			}
		}

		// Header Tracking Codes
		function find_header_tracking_codes() {
			global $wp_query;
			$page_id        = $wp_query->post->ID;
			$args           = array(
				'post_type'      => 'r8_tracking_scripts',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'meta_key'       => 'r8_tsm_script_order',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => 'r8_tsm_script_location',
						'value'   => 'header',
						'compare' => '=',
					),
					array(
						'key'     => 'r8_tsm_active',
						'value'   => 'active',
						'compare' => '=',
					),
				),
			);
			$header_scripts = new WP_Query( $args );


			if ( $header_scripts->have_posts() ) {
				while ( $header_scripts->have_posts() ) :
					$header_scripts->the_post();
					$page        = get_post_meta( get_the_ID(), 'r8_tsm_script_page', true );
					$expiry_info = get_post_meta( get_the_ID(), 'r8_tsm_script_expiry_info', true );
					$this->print_tsm_scripts( get_the_ID(), $page, $page_id, $expiry_info );
				endwhile;
				wp_reset_postdata();
			}
		}

		function find_page_tracking_codes() {
			global $wp_query;
			$page_id      = $wp_query->post->ID;
			$args         = array(
				'post_type'      => 'r8_tracking_scripts',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'meta_key'       => 'r8_tsm_script_order',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => 'r8_tsm_script_location',
						'value'   => 'page',
						'compare' => '=',
					),
					array(
						'key'     => 'r8_tsm_active',
						'value'   => 'active',
						'compare' => '=',
					),
				),
			);
			$page_scripts = new WP_Query( $args );
			if ( $page_scripts->have_posts() ) {
				while ( $page_scripts->have_posts() ) :
					$page_scripts->the_post();
					$page        = get_post_meta( get_the_ID(), 'r8_tsm_script_page', true );
					$expiry_info = get_post_meta( get_the_ID(), 'r8_tsm_script_expiry_info', true );
					$this->print_tsm_scripts( get_the_ID(), $page, $page_id, $expiry_info );
				endwhile;
				wp_reset_postdata();
			}
		}

		function find_footer_tracking_codes() {
			global $wp_query;
			$page_id        = $wp_query->post->ID;
			$args           = array(
				'post_type'      => 'r8_tracking_scripts',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'meta_key'       => 'r8_tsm_script_order',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => 'r8_tsm_script_location',
						'value'   => 'footer',
						'compare' => '=',
					),
					array(
						'key'     => 'r8_tsm_active',
						'value'   => 'active',
						'compare' => '=',
					),
				),
			);
			$footer_scripts = new WP_Query( $args );
			if ( $footer_scripts->have_posts() ) {
				while ( $footer_scripts->have_posts() ) :
					$footer_scripts->the_post();
					$page        = get_post_meta( get_the_ID(), 'r8_tsm_script_page', true );
					$expiry_info = get_post_meta( get_the_ID(), 'r8_tsm_script_expiry_info', true );
					$this->print_tsm_scripts( get_the_ID(), $page, $page_id, $expiry_info );
				endwhile;
				wp_reset_postdata();
			}
		}

		function add_tracking_script_columns( $columns ) {
			$columns = array(
				'cb'       => '<input type="checkbox" />',
				'title'    => __( 'Script Title', TRACKING_SCRIPT_TEXTDOMAIN ),
				'global'   => __( 'Global', TRACKING_SCRIPT_TEXTDOMAIN ),
				'location' => __( 'Location', TRACKING_SCRIPT_TEXTDOMAIN ),
				'status'   => __( 'Status', TRACKING_SCRIPT_TEXTDOMAIN ),
				'schedule' => __( 'Schedule', TRACKING_SCRIPT_TEXTDOMAIN ),
			);

			return $columns;
		}

		function tracking_script_column_content( $column_name, $post_ID ) {
			$expiry_info      = get_post_meta( $post_ID, 'r8_tsm_script_expiry_info', true );
			$expiry_data      = $this->expiry_data( $expiry_info );
			$if_expire        = $this->check_expiry_script( $expiry_data['type'], $expiry_data['start_date'], $expiry_data['end_date'], $post_ID );
			$scheduled_status = $this->scheduled_status( $if_expire, $expiry_data['type'], $expiry_data['start_date'], $expiry_data['end_date'] );

			if ( $column_name === 'status' ) {
				$active = get_post_meta( $post_ID, 'r8_tsm_active', true );
				echo ( $active === 'inactive' ) ? '<span class="expired">' : '<span>';
				if ( $active === 'active' ) {
					echo 'Active';
				} else {
					echo 'Inactive';
				}
					echo esc_attr( $scheduled_status );
				echo '</span>';
			}

			if ( $column_name === 'global' ) {
				$global = get_post_meta( $post_ID, 'r8_tsm_script_page', true );
				if ( empty( $global ) ) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&#10003;';
				} else {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&cross;';
				}
			}
			if ( $column_name === 'location' ) {
				$location = get_post_meta( $post_ID, 'r8_tsm_script_location', true );
				if ( $location ) {
					echo esc_html( ucwords( $location ) );
				}
			}
			if ( $column_name === 'schedule' ) {
				if ( $expiry_data['type'] === 'Schedule' ) {
					echo esc_html(
						sprintf(
							__( 'Scheduled <b>%1$s</b> to <b>%2$s</b>', TRACKING_SCRIPT_TEXTDOMAIN ),
							( $expiry_data['start_date'] ),
							( $expiry_data['end_date'] )
						)
					);
				} else {
					esc_html_e( 'Never expires', TRACKING_SCRIPT_TEXTDOMAIN );
				}
			}
		}

		function tracking_scripts_column_sort( $columns ) {
			$columns['global']   = 'global';
			$columns['location'] = 'location';
			$columns['status']   = 'status';
			$columns['schedule'] = 'schedule';

			return $columns;
		}

		public function add_script_metaboxes() {
			add_meta_box(
				'r8_tsm_script_code_wrapper',
				__( 'Script Code', TRACKING_SCRIPT_TEXTDOMAIN ),
				array(
					$this,
					'script_code_metabox',
				),
				'r8_tracking_scripts',
				'normal'
			);
			add_meta_box(
				'r8_tsm_script_active',
				__( 'Script Status', TRACKING_SCRIPT_TEXTDOMAIN ),
				array(
					$this,
					'script_active_metabox',
				),
				'r8_tracking_scripts',
				'side'
			);
			add_meta_box(
				'r8_tsm_script_expiry',
				__( 'Schedule', TRACKING_SCRIPT_TEXTDOMAIN ),
				array(
					$this,
					'script_expiry_metabox',
				),
				'r8_tracking_scripts',
				'side'
			);
			add_meta_box(
				'r8_tsm_script_order',
				__( 'Script Order', TRACKING_SCRIPT_TEXTDOMAIN ),
				array(
					$this,
					'script_order_metabox',
				),
				'r8_tracking_scripts',
				'side'
			);
			add_meta_box(
				'r8_tsm_script_location',
				__( 'Script Location', TRACKING_SCRIPT_TEXTDOMAIN ),
				array(
					$this,
					'script_location_metabox',
				),
				'r8_tracking_scripts',
				'normal'
			);
			add_meta_box(
				'r8_tsm_script_page',
				__( 'Specific Script Placement (Page(s) or Post(s))', TRACKING_SCRIPT_TEXTDOMAIN ),
				array(
					$this,
					'script_page_metabox',
				),
				'r8_tracking_scripts',
				'normal'
			);
		}

		function script_code_metabox() {
			global $post;
			$script_code = get_post_meta( $post->ID, 'r8_tsm_script_code', true );
			/**
			 * Check if script was saved using base64 encode
			 */
			$encoded_save = get_post_meta( $post->ID, 'r8_tsm_encoded_save', true );
			if ( !$encoded_save ) {
				$script_code=base64_encode($script_code);
				$this->save_script($post->ID,$script_code);
			}

			if($this->is_file_modification_allowed()){
				?>
				<div class="red8_script_notice" style="    padding: 1rem;    border: 1px solid lightcoral;    box-shadow: 0 2px 6px rgb(0 0 0 / 25%);    border-radius: 11px;}">
					<h1>Heads up!</h1>
					<p>
						Adding custom scripts is not recommended and could break your site.
					</p>
					<p>
						Please double check that the code you are adding is secure and make sure your WordPress site is backed up
					in the likely event that something breaks.</p>

					<p>
						<button type="button" class="button button-primary consent">I understand</button>
					</p>

				</div>
				<script type="text/javascript">
					jQuery(function($){
						$(".red8_script_notice button.consent").on("click",function(){
							$(".red8_script_notice").hide();
							$("#red8_code_editor_wrapper")
							.css("opacity",1)
							.css('height','auto');

						})
					})
				</script>

				<div id="red8_code_editor_wrapper" style="opacity: 0; height: 0;">
					<textarea name="r8_tsm_script_code" id="r8_tsm_script_code" rows="5" ><?php
						if ( $script_code ) {
							echo stripslashes(html_entity_decode( base64_decode($script_code), ENT_QUOTES, 'cp1252' ));
						}
						?></textarea>
				</div>

				<?php
			}
			else{
				?>
				<div class="notice notice-error ">
					<p>File modification & custom scripts have been disallowed by your WordPress config.</p>
				</div>
				<?php
			}

		}

		function script_active_metabox() {
			global $post;
			$active           = get_post_meta( $post->ID, 'r8_tsm_active', true );
			$expiry_info      = get_post_meta( $post->ID, 'r8_tsm_script_expiry_info', true );
			$expiry_data      = $this->expiry_data( $expiry_info );
			$if_expire        = $this->check_expiry_script( $expiry_data['type'], $expiry_data['start_date'], $expiry_data['end_date'], $post->ID );
			$scheduled_status = $this->scheduled_status( $if_expire, $expiry_data['type'], $expiry_data['start_date'], $expiry_data['end_date'] );

			include_once TRACKING_SCRIPT_DIR_PATH . '/templates/script-active-metabox.php';
		}

		function script_expiry_metabox() {
			global $post;
			$expiry_info      = get_post_meta( $post->ID, 'r8_tsm_script_expiry_info', true );
			$expiry_data      = $this->expiry_data( $expiry_info );
			$if_expire        = $this->check_expiry_script( $expiry_data['type'], $expiry_data['start_date'], $expiry_data['end_date'], $post->ID );
			$scheduled_status = $this->scheduled_status( $if_expire, $expiry_data['type'], $expiry_data['start_date'], $expiry_data['end_date'] );
			include_once TRACKING_SCRIPT_DIR_PATH . '/templates/script-expiry-metabox.php';
		}

		function script_order_metabox() {
			global $post;
			$order = get_post_meta( $post->ID, 'r8_tsm_script_order', true );
			include_once TRACKING_SCRIPT_DIR_PATH . '/templates/script-order-metabox.php';
		}

		function script_location_metabox() {
			global $post;
			$location = get_post_meta( $post->ID, 'r8_tsm_script_location', true );
			include_once TRACKING_SCRIPT_DIR_PATH . '/templates/script-location-metabox.php';
		}

		function script_page_metabox() {
			global $post;
			$script_page = get_post_meta( $post->ID, 'r8_tsm_script_page', true );

			include_once TRACKING_SCRIPT_DIR_PATH . '/templates/script-page-metabox.php';
		}

		public function get_date_time( $timespan, $format ) {
			$current_time = new DateTime();
			$current_time->add( new DateInterval( $timespan ) );
			$expire_time = $current_time->format( $format );

			return $expire_time;
		}

		public function check_expiry_script( $expiry_date_type, $expiry_start_date, $expiry_end_date, $script_id ) {
			$result = false;
			if ( $expiry_date_type === 'Never' ) {
				return $result;
			}
			if ( empty( $expiry_start_date ) ) {
				return $result;
			}
			if ( empty( $expiry_end_date ) ) {
				return $result;
			}

			$date_range = array();
			$start_time = $expiry_start_date;
			$interval   = new DateInterval( 'P1D' );
			$end_time   = new DateTime( $expiry_end_date );
			$end_time->add( $interval );
			$period     = new DatePeriod( new DateTime( $start_time ), $interval, $end_time );
			$today      = new DateTime();
			$today_date = $today->format( 'Y-m-d' );
			foreach ( $period as $key => $value ) {
				$array[] = $value->format( 'Y-m-d' );
			}
			if ( ! in_array( $today_date, $array, true ) ) {
				$result = true;
			}
			$this->set_script_status( $script_id, $result );
			return $result;
		}

		public function set_script_status( $script_id, $result ) {
			global $post;
			if ( ! empty( $post->post_type ) ) {
				if ( $post->post_type === 'r8_tracking_scripts' ) {
					if ( $script_id === $post->ID ) {
						$active = get_post_meta( $post->ID, 'r8_tsm_active', true );
						if ( $result === true ) { // expire true
							if ( 'active' === $active ) {
								update_post_meta( $post->ID, 'r8_tsm_active', 'inactive' );
							}
						} else { // expire false
							if ( 'inactive' === $active ) {
								update_post_meta( $post->ID, 'r8_tsm_active', 'active' );
							}
						}
					}
				}
			}
		}

		public function expiry_data( $expiry_info ) {
			$type       = is_object( $expiry_info ) ? $expiry_info->type : 'Never';
			$start_date = is_object( $expiry_info ) ? $expiry_info->schedule_start : '';
			$end_date   = is_object( $expiry_info ) ? $expiry_info->schedule_end : '';
			return array(
				'type'       => $type,
				'start_date' => $start_date,
				'end_date'   => $end_date,
			);
		}

		public function scheduled_status( $if_expire, $expiry_date_type, $expiry_start_date, $expiry_end_date ) {
			$status = '';
			$start  = new DateTime( $expiry_start_date );
			$end    = new DateTime( $expiry_end_date );
			$today  = new DateTime();
			if ( $expiry_date_type === 'Schedule' ) {
				if ( ! $if_expire ) {
					$status = '';
				} else {
					if ( $today < $start ) {
						$diff      = strtotime( $today->format( 'y-m-d' ) ) - strtotime( $start->format( 'y-m-d' ) );
						$count     = abs( round( $diff / 86400 ) );
						$next_date = sprintf( _n( 'tomorrow', 'in %s days', $count, 'tracking-scripts-manager' ), $count );
						$status    = sprintf( '(Starting %s) ', $next_date );
					}
					if ( $today > $end ) {
						$status = ' (Expired)';
					}
				}
			}
			return $status;
		}

		public function register_scripts_post_type() {
			$labels = array(
				'name'               => _x( 'Tracking Scripts', TRACKING_SCRIPT_TEXTDOMAIN ),
				'singular_name'      => _x( 'Tracking Script', TRACKING_SCRIPT_TEXTDOMAIN ),
				'menu_name'          => _x( 'Tracking Scripts', TRACKING_SCRIPT_TEXTDOMAIN ),
				'name_admin_bar'     => _x( 'Tracking Scripts', TRACKING_SCRIPT_TEXTDOMAIN ),
				'add_new'            => _x( 'Add New Tracking Script', TRACKING_SCRIPT_TEXTDOMAIN ),
				'add_new_item'       => __( 'Add New Tracking Script', TRACKING_SCRIPT_TEXTDOMAIN ),
				'new_item'           => __( 'New Tracking Script', TRACKING_SCRIPT_TEXTDOMAIN ),
				'edit_item'          => __( 'Edit Tracking Script', TRACKING_SCRIPT_TEXTDOMAIN ),
				'view_item'          => __( 'View Tracking Script', TRACKING_SCRIPT_TEXTDOMAIN ),
				'all_items'          => __( 'All Tracking Scripts', TRACKING_SCRIPT_TEXTDOMAIN ),
				'search_items'       => __( 'Search Tracking Scripts', TRACKING_SCRIPT_TEXTDOMAIN ),
				'parent_item_colon'  => __( 'Parent Tracking Scripts:', TRACKING_SCRIPT_TEXTDOMAIN ),
				'not_found'          => __( 'No Tracking Scripts found.', TRACKING_SCRIPT_TEXTDOMAIN ),
				'not_found_in_trash' => __( 'No Tracking Scripts found in Trash.', TRACKING_SCRIPT_TEXTDOMAIN ),
			);
			$args   = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', TRACKING_SCRIPT_TEXTDOMAIN ),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => false,
				'query_var'          => false,
				'rewrite'            => array( 'slug' => 'tracking-scripts' ),
				'capability_type'    => 'post',
				'capabilities'       => array(
					'edit_post'          => 'manage_options',
					'read_post'          => 'manage_options',
					'delete_post'        => 'manage_options',
					'edit_posts'         => 'manage_options',
					'edit_others_posts'  => 'manage_options',
					'delete_posts'       => 'manage_options',
					'publish_posts'      => 'manage_options',
					'read_private_posts' => 'manage_options',
				),
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array(
					'title',
					'script-code',
					'script-active',
					'script-location',
					'script-order',
				),
			);
			register_post_type( 'r8_tracking_scripts', $args );
		}

		/*************************************************
		 * Admin Area
		 **************************************************/
		function admin_enqueue_scripts( $hook ) {
			global $post;
			if ( $hook === 'post.php' || $hook === 'post-new.php' ) {
				if ( ! empty( $post->post_type ) && ( $post->post_type === 'r8_tracking_scripts' ) ) {
					wp_enqueue_style( 'r8-tsm-edit-script', plugins_url( '/css/tracking-script-edit.css', __FILE__ ), array(), md5_file( plugins_url( '/css/tracking-script-edit.css', __FILE__ ) ) );
					wp_enqueue_style( 'r8-tsm-select2-css', plugins_url( '/css/select2.min.css', __FILE__ ), array(), md5_file( plugins_url( '/css/select2.min.css', __FILE__ ) ) );
					wp_enqueue_script( 'r8-tsm-select2-js', plugins_url( '/js/select2.min.js', __FILE__ ), array(), md5_file( plugins_url( '/js/select2.min.js', __FILE__ ) ), true );
					wp_enqueue_script(
						'r8-tsm-post-edit-js',
						plugins_url( '/js/post-edit.js', __FILE__ ),
						array(
							'jquery',
							'r8-tsm-select2-js',
						),
						md5_file( plugins_url( '/js/post-edit.js', __FILE__ ) ),
						true
					);
					wp_enqueue_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css' );
					wp_enqueue_script( 'jquery-ui-datepicker' );
				}
			}
			if ( $hook === 'post.php' || $hook === 'edit.php' ) {
				if ( ! empty( $post->post_type ) && ( $post->post_type === 'r8_tracking_scripts' ) ) {
					wp_enqueue_style( 'r8-tsm-post-list', plugins_url( '/css/post-list.css', __FILE__ ), array(), md5_file( plugins_url( '/css/post-list.css', __FILE__ ) ) );
					wp_enqueue_script( 'r8-tsm-post-list-js', plugins_url( '/js/post-list.js', __FILE__ ), array( 'jquery' ), md5_file( plugins_url( '/js/post-list.js', __FILE__ ) ), true );
				}
			}
			if ( ! empty( $post->post_type ) && ( $post->post_type === 'r8_tracking_scripts' ) ) {
				// code editor support
				$html_editor = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
				if ( false !== $html_editor ) {
					wp_add_inline_script(
						'code-editor',
						sprintf(
							'jQuery( function() { wp.codeEditor.initialize( "r8_tsm_script_code", %s ); } );',
							wp_json_encode( $html_editor )
						)
					);
				}
			}
		}

		private function esc_script( $script ) {
			return stripslashes(html_entity_decode( base64_decode($script), ENT_QUOTES, 'cp1252' ));
		}

		private function save_script($post_id,$script_code){
			$script_code = stripslashes( wp_unslash($script_code));
			update_post_meta( $post_id, 'r8_tsm_script_code', $script_code );
			update_post_meta( $post_id, 'r8_tsm_encoded_save', true );
		}


		private function is_file_modification_allowed(){
			if (defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS){
				return false;
			}
			return true;
		}

		function save_post() {
			global $post;
			if ( ! empty( $post->post_type ) ) {
				if ( $post->post_type === 'r8_tracking_scripts' ) {
					$expiry_obj                 = new \stdClass();
					$expiry_obj->schedule_start = '';
					$expiry_obj->schedule_end   = '';
					$expiry_obj->type           = '';
					if ( ! empty( $_POST['r8_tsm_script_code'] ) ) {
						$script_code = base64_encode($_POST['r8_tsm_script_code'] );
						$this->save_script($post->ID,$script_code);

					}
					if ( ! empty( $_POST['r8_tsm_active'] ) ) {
						$tsm_active = sanitize_text_field( wp_unslash( $_POST['r8_tsm_active'] ) );
						update_post_meta( $post->ID, 'r8_tsm_active', $tsm_active );
					}
					if ( ! empty( $_POST['r8_tsm_script_order'] ) ) {
						update_post_meta( $post->ID, 'r8_tsm_script_order', intval( $_POST['r8_tsm_script_order'] ) );
					}
					if ( ! empty( $_POST['r8_tsm_script_location'] ) ) {
						update_post_meta( $post->ID, 'r8_tsm_script_location', sanitize_text_field( wp_unslash( $_POST['r8_tsm_script_location'] ) ) );
					}
					if ( ! empty( $_POST['r8_tsm_script_expiry'] ) || ( ! empty( $_POST['schedule_start'] ) && ! empty( $_POST['schedule_end'] ) ) ) {
						$expiry_obj->type           = sanitize_text_field( wp_unslash( $_POST['r8_tsm_script_expiry'] ) ) ? : 'Never';
						$expiry_obj->schedule_start = sanitize_text_field( wp_unslash( $_POST['schedule_start'] ) ) ?: '';
						$expiry_obj->schedule_end   = sanitize_text_field( wp_unslash( $_POST['schedule_end'] ) ) ?: '';
						update_post_meta( $post->ID, 'r8_tsm_script_expiry_info', $expiry_obj );
						// status updated based on schedule
						if ( $expiry_obj->type === 'Schedule' ) {
							$this->check_expiry_script( $expiry_obj->type, $expiry_obj->schedule_start, $expiry_obj->schedule_end, $post->ID );
						}
					}
					if ( ! empty( $_POST['r8_tsm_script_page'] ) && is_array( $_POST['r8_tsm_script_page'] ) ) {

						$script_pages =  array_map( 'intval', wp_unslash($_POST['r8_tsm_script_page']) );

						update_post_meta( $post->ID, 'r8_tsm_script_page', $script_pages );
					} else {
						update_post_meta( $post->ID, 'r8_tsm_script_page', array() );
					}
				}
			}
		}

		public function tracking_scripts_create_menu() {
			add_menu_page( 'Tracking Script Manager', 'Tracking Script Manager', 'manage_options', 'edit.php?post_type=r8_tracking_scripts', null );
			add_submenu_page( 'edit.php?post_type=r8_tracking_scripts', 'Add New Tracking Script', 'Add New Tracking Script', 'manage_options', 'post-new.php?post_type=r8_tracking_scripts', null );
		}

		// Admin Scripts
		public function tracking_scripts_admin_scripts() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'tracking_script_js', plugin_dir_url( __FILE__ ) . '/js/built.min.js', array(), md5_file( plugin_dir_url( __FILE__ ) . '/js/built.min.js' ), true );
			wp_localize_script( 'tracking_script_js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}

		// Ajax Functions
		public function tracking_scripts_posts_ajax_handler() {
			$post_type = isset( $_POST['postType'] ) ? sanitize_text_field( wp_unslash( $_POST['postType'] ) ) : 'post';
			$args      = array(
				'post_type'      => $post_type,
				'posts_per_page' => - 1,
				'orderby'        => 'name',
				'order'          => 'ASC',
			);
			ob_start();
			$query = new WP_Query( $args );
			echo '<option value="none" id="none">Choose ' . esc_html( ucwords( $post_type ) ) . '</option>';
			while ( $query->have_posts() ) :
				$query->the_post();
				echo '<option value="' . esc_attr( get_the_ID() ) . '" id="' . esc_attr( get_the_ID() ) . '">' . esc_html( ucwords( get_the_title() ) ) . '</option>';
			endwhile;
			wp_reset_postdata();
			echo esc_html( ob_get_clean() );
			die();
		}
	}

	function tracking_scripts() {

		// globals
		global $tracking_scripts;
		// initialize
		if ( ! isset( $tracking_scripts ) ) {
			$tracking_scripts = new Tracking_Scripts();
			$tracking_scripts->initialize();
		}

		// return
		return $tracking_scripts;
	}

	// initialize
	tracking_scripts();
}
