<?php
/**
 * Dashboard
 *
 * @package Copy the Code
 * @since 3.0.0
 */

use CopyTheCode\Elementor\Blocks;

if ( ! class_exists( 'Copy_The_Code_Dashboard' ) ) :

	/**
	 * Copy The Code Dashboard
	 *
	 * @since 3.0.0
	 */
	class Copy_The_Code_Dashboard {

		/**
		 * Instance
		 *
		 * @since 3.0.0
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 3.0.0
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 3.0.0
		 */
		public function __construct() {
			add_action( 'after_setup_theme', [ $this, 'add_menus' ] );
			add_action( 'post_row_actions', [ $this, 'post_row_actions' ], 10, 2 );
			add_action( 'get_edit_post_link', [ $this, 'edit_post_link' ], 10, 3 );
			add_action( 'wp_ajax_ctc_save_changes', [ $this, 'save' ] );
		}

		/**
		 * Save
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function save() {
			$style_type = isset( $_POST['style_type'] ) ? sanitize_text_field( $_POST['style_type'] ) : '';
			$position = isset( $_POST['position'] ) ? sanitize_text_field( $_POST['position'] ) : '';
			$format = isset( $_POST['format'] ) ? sanitize_text_field( $_POST['format'] ) : '';
			$selector = isset( $_POST['selector'] ) ? sanitize_text_field( $_POST['selector'] ) : '';
			$btn_text = isset( $_POST['btn_text'] ) ? sanitize_text_field( $_POST['btn_text'] ) : '';
			$btn_after_copy_text = isset( $_POST['btn_after_copy_text'] ) ? sanitize_text_field( $_POST['btn_after_copy_text'] ) : '';
			$btn_title = isset( $_POST['btn_title'] ) ? sanitize_text_field( $_POST['btn_title'] ) : '';
			$post = isset( $_POST['post'] ) ? $_POST['post'] : [];
			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
			$on_edit = isset( $_POST['on_edit'] ) ? sanitize_text_field( $_POST['on_edit'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'copy-the-code' ) ) {
				wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'copy-the-code' ) ] );
			}

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error( [ 'message' => __( 'You do not have permission to edit posts', 'copy-the-code' ) ] );
			}

			$post_title = isset( $post['post_title'] ) ? sanitize_text_field( $post['post_title'] ) : '';

			if ( $on_edit ) {
				$post_id = isset( $post['ID'] ) ? absint( $post['ID'] ) : 0;

				wp_update_post( [
					'ID' => $post_id,
					'post_title' => $post_title,
				] );
			} else {
				$post = [
					'post_title' => $post_title,
					'post_type' => 'copy-to-clipboard',
					'post_status' => 'publish',
				];

				$post_id = wp_insert_post( $post );
			}

			$meta = [
				'button-text' => $btn_text,
				'button-copy-text' => $btn_after_copy_text,
				'button-title' => $btn_title,
				'copy-format' => $format,
				'selector' => $selector,
				'button-position' => $position,
				'style' => $style_type,
			];

			foreach ( $meta as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}

			do_action( 'copy_the_code/dashboard/save' );

			wp_send_json_success( [
				'message' => __( 'Saved', 'copy-the-code' ),
				'edit_post_url' => get_edit_post_link( $post_id ),
			] );
		}

		/**
		 * Filters the post edit link.
		 *
		 * @since 3.0.0
		 *
		 * @param string $link    The edit link.
		 * @param int    $post_id Post ID.
		 * @param string $context The link context. If set to 'display' then ampersands
		 *                        are encoded.
		 */
		public function edit_post_link( $link, $post_id, $context ) {
			global $mode;

			if ( 'list' !== $mode ) {
				return $link;
			}

			$post_type = get_post_type( $post_id );
			if ( 'copy-to-clipboard' !== $post_type ) {
				return $link;
			}

			return esc_url( admin_url( 'options-general.php?page=copy-the-code&id=' . $post_id ) );
		}

		/**
		 * Post row actions
		 *
		 * @since 3.0.0
		 * @return array
		 */
		public function post_row_actions( $actions, $post ) {
			if ( 'copy-to-clipboard' !== $post->post_type ) {
				return $actions;
			}

			$actions['edit'] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'options-general.php?page=copy-the-code&id=' . $post->ID ) ),
				__( 'Edit', 'copy-the-code' )
			);

			return $actions;
		}

		/**
		 * Add menus
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function add_menus() {
			if ( ! current_user_can( 'edit_posts' ) ) {
				return;
			}

			add_action( 'admin_menu', array( $this, 'register' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_footer', array( $this, 'hide_menus' ) );
		}

		/**
		 * Hide menus
		 *
		 * @since 3.0.0
		 * @return void
		 */
		function hide_menus() {
			?>
			<style type="text/css">
				#adminmenu a[href="options-general.php?page=copy-the-code"],
				#adminmenu a[href="options-general.php?page=copy-the-code-contact"],
				#adminmenu a[href="options-general.php?page=copy-the-code-wp-support-forum"],
				#adminmenu a[href="options-general.php?page=copy-the-code-pricing"],
				#adminmenu a[href="options-general.php?page=copy-the-code-account"] {
					display: none !important;
				}
			</style>
			<?php

			if ( ! isset( $_GET['post_type'] ) ) {
				return;
			}

			if ( 'copy-to-clipboard' !== $_GET['post_type'] ) {
				return;
			}

			wp_enqueue_script( 'jquery' );

			$menus = array(
				'copy-the-code-wp-support-forum' => 'Support Forum',
			);

			if ( ctc_fs()->is_not_paying() ) {
				$menus['copy-the-code-pricing'] = 'Upgrade';
			} else {
				$menus['copy-the-code-account'] = 'Account';
			}

			$menus = array_merge(
				$menus,
				array(
					'copy-the-code-contact' => 'Contact Us',
					'copy-the-code'         => 'Dashboard',
				)
			);

			?>
			<script>
				// Add button tag after class .page-title-action.
				jQuery( document ).ready( function( $ ) {
					let menus = <?php echo wp_json_encode( $menus ); ?>;
					$.each( menus, function( key, value ) {
						let target = '';
						if( 'copy-the-code-wp-support-forum' === key ) {
							target = 'target="_blank"';
						}
						$( '.wrap > .page-title-action' ).after( '<a ' + target + ' class="cta-sub-menu cta-sub-menu-'+key+'" href="<?php echo admin_url( 'options-general.php?page=' ); ?>' + key + '">' + value + '</a>' );
					} );
				} );
			</script>
			<style>
				.cta-sub-menu.cta-sub-menu-copy-the-code-wp-support-forum:after {
					content: "\f504";
					font-family: dashicons;
					display: inline-block;
					line-height: 1;
					font-weight: 400;
					font-style: normal;
					speak: never;
					text-decoration: inherit;
					text-transform: none;
					text-rendering: auto;
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
					width: 20px;
					height: 20px;
					font-size: 20px;
					vertical-align: top;
					text-align: center;
					transition: color 0.1s ease-in;
					text-decoration: none;
					font-size: 14px;
					vertical-align: sub;
				}
				.wrap .page-title-action {
					margin-right: 10px;
				}
				.cta-sub-menu {
					padding: 0 5px;
					display: inline-block;
					margin: 0;
					text-decoration: underline;
					top: -3px;
					position: relative;
				}
				</style>
			<?php
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function enqueue_scripts( $hook = '' ) {
			if ( 'settings_page_copy-the-code' !== $hook ) {
				return;
			}

			wp_enqueue_script( 'copy-the-code-dashboard', COPY_THE_CODE_URI . 'assets/admin/js/dashboard.js', [ 'jquery', 'wp-element', 'wp-hooks', 'lodash', 'wp-api-fetch' ], COPY_THE_CODE_VER, true );
			wp_enqueue_style( 'copy-the-code-dashboard', COPY_THE_CODE_URI . 'assets/admin/css/dashboard.css', null, COPY_THE_CODE_VER, 'all' );
			$id = isset( $_GET['id' ] ) ? absint( $_GET['id' ] ) : 0;

			wp_localize_script(
				'copy-the-code-dashboard',
				'CopyDashboardVars',
				[
					'uri' => COPY_THE_CODE_URI,
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'copy-the-code' ),
					'onEdit' => $id ? true : false,
					'editUrl' => $id ? get_edit_post_link( $id ) : admin_url( 'edit.php?post_type=copy-to-clipboard' ),
					'post'	 => $id ? get_post( $id ) : [],
					'meta'	 => $id ? [
						'button-text' => get_post_meta( $id, 'button-text', true ),
						'button-copy-text' => get_post_meta( $id, 'button-copy-text', true ),
						'button-title' => get_post_meta( $id, 'button-title', true ),
						'copy-format' => get_post_meta( $id, 'copy-format', true ),
						'button-position' => get_post_meta( $id, 'button-position', true ),
						'selector' => get_post_meta( $id, 'selector', true ),
						'style' => get_post_meta( $id, 'style', true ),
					] : [
						'button-text' => 'Copy',
						'button-copy-text' => 'Copied',
						'button-title' => 'Copy',
						'copy-format' => '',
						'button-position' => 'outside',
						'selector' => 'pre',
						'style' => 'button',
					],
					'upgradeUrl' => admin_url( 'options-general.php?billing_cycle=annual&page=copy-the-code-pricing' ),
					'buttonStyle' => get_option( 'ctc_default_style', [
						'btn_color'          => '#424242',
						'btn_bg_color'       => '#e1e3e8',
						'btn_l_padding'      => '20',
						'btn_t_padding'      => '10',
						'btn_r_padding'      => '20',
						'btn_b_padding'      => '10',
						'btn_l_margin'       => '0',
						'btn_t_margin'       => '0',
						'btn_r_margin'       => '0',
						'btn_b_margin'       => '0',
						'btn_tl_radius'      => '0',
						'btn_tr_radius'      => '0',
						'btn_br_radius'      => '0',
						'btn_bl_radius'      => '0',
						'btn_font_size'      => '14',
						'btn_line_height'    => '18',
						'btn_h_color'          => '#424242',
						'btn_h_bg_color'       => '#e1e3e8',

						'svg_icon_color'     => "#23282d",
						'svg_icon_width'     => "20",
						'svg_icon_t_padding' => "5",
						'svg_icon_r_padding' => "5",
						'svg_icon_b_padding' => "5",
						'svg_icon_l_padding' => "5",
						'svg_icon_h_color'   => "#23282d",

						'cover_color'        => '#424242',
						'cover_font_size'    => '14',
					] ),
					'nonce' => wp_create_nonce( 'copy-the-code' ),
					'elementor' => [
						'blocks' => Blocks::get_blocks(),
					]
				]
			);

		}

		/**
		 * Register menus
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function register() {
			add_submenu_page(
				'options-general.php',
				__( 'Add New', 'copy-the-code' ),
				__( ' → Add New', 'copy-the-code' ),
				'manage_options',
				'copy-the-code',
				array( $this, 'markup' )
			);
		}

		/**
		 * Markup
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function markup() {
			?>
			<div id="ctc-dashboard-root"></div>
			<?php
		}


	}

	/**
	 * Initialize class object with 'get_instance()' method
	 */
	Copy_The_Code_Dashboard::get_instance();

endif;
