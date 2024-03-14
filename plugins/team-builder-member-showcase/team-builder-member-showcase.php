<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Plugin Name:       Team Builder Member Showcase
 * Plugin URI:        https://awplife.com/
 * Description:       Create and display your dream team on your WordPress website in few minutes.
 * Version:           0.1.7
 * Requires at least: 5.4
 * Requires PHP:      7.2
 * Author:            A WP Life
 * Author URI:        https://profiles.wordpress.org/awordpresslife
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       team-builder-member-showcase
 * Domain Path:       /languages
 * License:           GPL2

Team Builder Member Showcase is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Team Builder Member Showcase is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Team Builder Member Showcase. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

add_image_size( 'tbms-custom-300', 300, 300, array( 'top', 'center' ) );
add_image_size( 'tbms-custom-500', 500, 500, array( 'top', 'center' ) );
add_option( 'awplife_tbms_plugin_version', '0.1.7' );

if ( ! class_exists( 'TBMS_AWPLIFE' ) ) {
	class TBMS_AWPLIFE {

		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}

		protected function _constants() {
			// Plugin Version
			define( 'TBMS_PLUGIN_VER', '0.1.7' );

			// Plugin Text Domain
			define( 'TBMS_TXTDM', 'team-builder-member-showcase' );

			// Plugin Name
			define( 'TBMS_PLUGIN_NAME', __( 'Team Builder Member Showcase', TBMS_TXTDM ) );

			// Plugin Slug
			define( 'TBMS_PLUGIN_SLUG', 'tbms_cpt_name' );

			// Plugin Directory Path
			define( 'TBMS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin Directory URL
			define( 'TBMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			define( 'TBMS_SECURE_KEY', md5( NONCE_KEY ) );

		} // end of constructor function

		protected function _hooks() {
			// Load text domain
			add_action( 'plugins_loaded', array( $this, 'tbms_load_textdomain' ) );

			// create TBMS custom post callback
			add_action( 'init', array( $this, 'tbms_cpt' ) );

			// add TBMS meta box to custom post
			add_action( 'add_meta_boxes', array( $this, 'tbms_add_meta_box' ) );

			// loaded during admin init
			add_action( 'admin_init', array( $this, 'tbms_add_meta_box' ) );

			add_action( 'wp_ajax_tbms_add_member_li', array( &$this, 'tbms_ajax_add_member_li_callback' ) );

			add_action( 'save_post', array( &$this, 'tbms_save_settings' ) );

			// TBMS shortcode compatibility in text widgets
			add_action( 'widget_text', 'do_shortcode' );

			// add tbms cpt shortcode column - manage_{$post_type}_posts_columns
			add_filter( 'manage_tbms_cpt_name_posts_columns', array( &$this, 'tbms_set_shortcode_column_name' ) );

			// add tbms cpt shortcode column data - manage_{$post_type}_posts_custom_column
			add_action( 'manage_tbms_cpt_name_posts_custom_column', array( &$this, 'tbms_shodrcode_column_data' ), 10, 2 );

			add_action( 'wp_enqueue_scripts', array( &$this, 'tbms_jquery_support' ) );
		}
		// end of hook function

		public function tbms_jquery_support() {
			wp_enqueue_script( 'jquery' );
		}

		// TBMS cpt shortcode column before date columns
		public function tbms_set_shortcode_column_name( $defaults ) {
			$new       = array();
			$shortcode = $columns['tbms_shortcode'];    // save the tags column
			unset( $defaults['tags'] );   // remove it from the columns list

			foreach ( $defaults as $key => $value ) {
				if ( $key == 'date' ) {  // when we find the date column
					$new['tbms_shortcode'] = __( 'Shortcode', 'team-builder-member-showcase' );  // put the tags column before it
				}
				$new[ $key ] = $value;
			}
			return $new;
		}

		// TBMS cpt shortcode column data
		public function tbms_shodrcode_column_data( $column, $post_id ) {
			switch ( $column ) {
				case 'responsive_slider_shortcode':
					echo "<input type='text' class='button button-primary' id='tbms_cpt_name-shortcode-" . esc_attr( $post_id ) . "' value='[TBMS id=" . esc_attr( $post_id ) . "]' style='font-weight:bold; background-color:#32373C; color:#FFFFFF; text-align:center;' />";
					echo "<input type='button' class='button button-primary' onclick='return TMCopyShortcode" . esc_attr( $post_id ) . "();' readonly value='Copy' style='margin-left:4px;' />";
					echo "<span id='copy-msg-" . esc_attr( $post_id ) . "' class='button button-primary' style='display:none; background-color:#32CD32; color:#FFFFFF; margin-left:4px; border-radius: 4px;'>copied</span>";
					echo '<script>
						function TMCopyShortcode' . esc_attr( $post_id ) . "() {
							var copyText = document.getElementById('tbms_cpt_name-shortcode-" . esc_attr( $post_id ) . "');
							copyText.select();
							document.execCommand('copy');
							
							//fade in and out copied message
							jQuery('#copy-msg-" . esc_attr( $post_id ) . "').fadeIn('1000', 'linear');
							jQuery('#copy-msg-" . esc_attr( $post_id ) . "').fadeOut(2500,'swing');
						}
						</script>
					";
					break;
			}
		}


		public function tbms_load_textdomain() {
			load_plugin_textdomain( 'team-builder-member-showcase', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public function tbms_cpt() {
			$labels = array(
				'name'               => __( 'TBMS', 'team-builder-member-showcase' ),
				'singular_name'      => __( 'TBMS', 'team-builder-member-showcase' ),
				'menu_name'          => __( 'TBMS', 'team-builder-member-showcase' ),
				'name_admin_bar'     => __( 'TBMS', 'team-builder-member-showcase' ),
				'add_new'            => __( 'Add Team', 'team-builder-member-showcase' ),
				'add_new_item'       => __( 'Add Team', 'team-builder-member-showcase' ),
				'new_item'           => __( 'New Team', 'team-builder-member-showcase' ),
				'edit_item'          => __( 'Edit Team', 'team-builder-member-showcase' ),
				'view_item'          => __( 'View Team', 'team-builder-member-showcase' ),
				'all_items'          => __( 'All Team', 'team-builder-member-showcase' ),
				'search_items'       => __( 'Search Team', 'team-builder-member-showcase' ),
				'parent_item_colon'  => __( 'Parent Team', 'team-builder-member-showcase' ),
				'not_found'          => __( 'No Team', 'team-builder-member-showcase' ),
				'not_found_in_trash' => __( 'No Team found in Trash', 'team-builder-member-showcase' ),
			);

			$args = array(
				'labels'             => __( 'TBMS', 'team-builder-member-showcase' ),
				'description'        => __( 'Description.', 'team-builder-member-showcase' ),
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'capability_type'    => 'page',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_icon'          => 'dashicons-groups',
				'menu_position'      => null,
				'supports'           => array( 'title' ),
			);
			register_post_type( 'tbms_cpt_name', $args );
		}

		public function tbms_add_meta_box() {
			add_meta_box( __( 'Create Team Shortcode And Configure Settings', 'team-builder-member-showcase' ), __( 'Create Team Shortcode And Configure Settings', 'team-builder-member-showcase' ), array( &$this, 'tbms_team_upload' ), 'tbms_cpt_name', 'normal', 'default' );
			add_meta_box( __( 'Team Shortcode', 'team-builder-member-showcase' ), __( 'Team Shortcode', 'team-builder-member-showcase' ), array( &$this, 'tbms_maker_shortcode' ), 'tbms_cpt_name', 'side', 'default' );
			add_meta_box( __( 'Configure Settings', 'team-builder-member-showcase' ), __( 'Configure Settings', 'team-builder-member-showcase' ), array( &$this, 'tbms_settings' ), 'tbms_cpt_name', 'side', 'default' );
			add_meta_box( __( 'Upgrade Team Builder Showcase', 'team-builder-member-showcase' ), __( 'Upgrade Team Builder Showcase', 'team-builder-member-showcase' ), array( &$this, 'tbms_upgrade_pro' ), 'tbms_cpt_name', 'side', 'default' );
			add_meta_box( __( 'Rate Our Plugin', 'team-builder-member-showcase' ), __( 'Rate Our Plugin', 'team-builder-member-showcase' ), array( &$this, 'tbms_rate_plugin' ), 'tbms_cpt_name', 'side', 'default' );
		}

		// meta upgrade pro
		public function tbms_upgrade_pro() { ?>
			<img src="<?php echo esc_url(plugin_dir_url( __FILE__ ) . 'include/image/Untitled-1.png');?>"/ width="250" height="280">
			<a href="https://awplife.com/demo/team-builder-member-showcase-premium/" target="_new" class="button button-primary button-large" style="text-shadow: none; margin-top:10px"><span class="dashicons dashicons-search" style="line-height:1.4;" ></span> Live Demo</a>
			<a href="https://awplife.com/wordpress-plugins/team-builder-member-showcase-premium/" target="_new" class="button button-primary button-large" style="text-shadow: none; margin-top:10px"><span class="dashicons dashicons-unlock" style="line-height:1.4;" ></span> Upgrade Pro</a>
			<?php
		}
		// meta rate us
		public function tbms_rate_plugin() {
			?>
		<div style="text-align:center">
			<p>If you like our plugin then please <b>Rate us</b> on WordPress</p>
		</div>
		<div style="text-align:center">
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
		</div>
		<br>
		<div style="text-align:center">
			<a href="https://wordpress.org/support/plugin/team-builder-member-showcase/reviews/?filter=5" target="_new" class="button button-primary button-large" style="text-shadow: none;"><span class="dashicons dashicons-heart" style="line-height:1.4;" ></span> Please Rate Us</a>
		</div>	
			<?php
		}

		public function tbms_maker_shortcode( $post ) {
			?>
			<div class="team-shortcode">
				<input type="text" name="tbms_cpt_name-shortcode" id="tbms_cpt_name-shortcode" value="<?php echo esc_attr("[TBMS id=".$post->ID."]"); ?>" readonly style="height: 60px; text-align: center; width:100%;  font-size: 24px; border: 2px dashed;">
				<p id="tbms-copt-code">Shortcode copied to clipboard!</p>
				<p style="margin-top: 10px"><?php esc_html_e( 'Copy & Embed shortcode into any Page / Post to display your Team on site.', 'team-builder-member-showcase' ); ?><br></p>
			</div>
			<span onclick="tbmsCopyToClipboard('#tbms_cpt_name-shortcode')" class="tbms-copy dashicons dashicons-clipboard"></span>
			<style>
			.tbms-copy {
				position: absolute;
				top: 9px;
				right: 24px;
				font-size: 26px;
				cursor: pointer;
			}
			</style>
			<script>
			jQuery( "#tbms-copt-code" ).hide();
			function tbmsCopyToClipboard(element) {
				var $temp = jQuery("<input>");
				jQuery("body").append($temp);
				$temp.val(jQuery(element).val()).select();
				document.execCommand("copy");
				$temp.remove();
				jQuery( "#tbms_cpt_name-shortcode" ).select();
				jQuery( "#tbms-copt-code" ).fadeIn();
			}
			</script>
			<?php
		}
		public function tbms_settings( $post ) {
			require_once 'include/settings.php';
		}

		public function tbms_team_upload( $post ) {
			// Meta content js
			wp_enqueue_script( 'jquery' );

			// Meta content css
			wp_enqueue_style( 'awplife-tbms-setting-css', TBMS_PLUGIN_URL . 'assets/css/team-setting.css' );

			require_once 'include/add-team.php';
			wp_nonce_field( 'tbms_save_settings_nonce_action', 'tbms_save_settings_nonce_name' );
		}

		// Plugin
		public function tbms_ajax_plugin_add_member( $id ) {
			$attachment              = get_post( $id ); // get all of image
			$tbms_member_image       = wp_get_attachment_image_src( $id, 'tbms-custom-300', true ); // return is array	medium image URL
			$tbms_member_image_alt   = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
			$tbms_member_name        = $attachment->post_title; // attachment title
			$tbms_member_description = $attachment->post_content;
			?>
			<div id="tbms-member-<?php echo esc_attr( $id ); ?>" class="team-panel-body">
				<div class="t-panel-body" style="position:relative">
					<div class="team-panel-class">
						<ul>
							<li>
								<div class="row">
									<div class="col-md-3">
										<img class="team-thumbnail-upload" src="<?php echo esc_url( $tbms_member_image[0] ); ?>" alt="<?php echo esc_html( $tbms_member_image_alt ); ?>">
										<input type="hidden" id="tbms_template_column_ids[]" name="tbms_template_column_ids[]" value="<?php echo esc_attr( $id ); ?>" />
									</div>
									<div class="col-md-9">
										<div class="row">
											<div class="col-md-6 ">
												<input type="text" id="tbms_member_name[]" name="tbms_member_name[]" class="form-control team-style" placeholder="<?php esc_html_e( 'Member Name', 'team-builder-member-showcase' ); ?>" value="<?php echo esc_html( $tbms_member_name ); ?>">
												<input type="text" id="tbms_designation[]" name="tbms_designation[]" class="form-control team-style" placeholder="<?php esc_html_e( 'Member Designation', 'team-builder-member-showcase' ); ?>" value="<?php echo esc_html( $tbms_designation ); ?>">	
												<textarea type="text" id="tbms_member_description[]" name="tbms_member_description[]" class="form-control"  placeholder="<?php esc_html_e( 'Member Bio', 'team-builder-member-showcase' ); ?>" rows="6"><?php echo esc_html( $tbms_member_description ); ?></textarea>
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control team-style-two" id="tbms_icon_link_url_first[]" name="tbms_icon_link_url_first[]" placeholder="<?php esc_html_e( 'Facebook URL', 'team-builder-member-showcase' ); ?>" value="#">
												<input type="text" id="tbms_icon_link_url_second[]" name="tbms_icon_link_url_second[]" class="form-control team-style-two" placeholder="<?php esc_html_e( 'Twitter URL', 'team-builder-member-showcase' ); ?>" value="#">
												<input type="text" id="tbms_icon_link_url_third[]" name="tbms_icon_link_url_third[]" class="form-control team-style-two" placeholder="<?php esc_html_e( 'LinkedIn URL', 'team-builder-member-showcase' ); ?>" value="#">
												<button class="btn btn-block btn-danger" id="team_column_delete" name="team_column_delete" value="tbms-member-<?php echo esc_attr( $id ); ?>">
													<i class="fa fa-trash"></i> &nbsp; <?php esc_html_e( 'Delete Team Member', 'team-builder-member-showcase' ); ?>
												</button>
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<script>
			function TBMSgetRandomColor() {
				var letters = '0123456789ABCDEF';
				var color = '#';
				for (var i = 0; i < 6; i++) {
					color += letters[Math.floor(Math.random() * 16)];
				}
				return color;
			}
			jQuery('.t-panel-body').each(function( val, i ) {
				jQuery(this).css("border-left", "5px solid "+ TBMSgetRandomColor() + "");
			});
			</script>
			<?php
		} //end of if

		public function tbms_ajax_add_member_li_callback() {
			echo esc_attr( $this->tbms_ajax_plugin_add_member( $_POST['slideId'] ) );
			die;
		}

		public function tbms_save_settings( $post_id ) {
			if ( isset( $_POST['tbms_save_settings_nonce_name'] ) ) {
				if ( ! isset( $_POST['tbms_save_settings_nonce_name'] ) || ! wp_verify_nonce( $_POST['tbms_save_settings_nonce_name'], 'tbms_save_settings_nonce_action' ) ) {
					print 'Sorry, your nonce did not verify.';
					exit;
				} else {
					$tbms_template_design       = sanitize_text_field( $_POST['tbms_template_design'] );
					$tbms_image_size            = sanitize_text_field( $_POST['tbms_image_size'] );
					$tbms_total_column          = sanitize_text_field( $_POST['tbms_total_column'] );
					$tbms_background_team_color = sanitize_hex_color( $_POST['tbms_background_team_color'] );
					$tbms_decription_color      = sanitize_hex_color( $_POST['tbms_decription_color'] );
					$tbms_link_tab              = sanitize_text_field( $_POST['tbms_link_tab'] );
					$tbms_custom_css            = sanitize_text_field( $_POST['tbms_custom_css'] );
					$i                          = 0;

					$tbms_image_ids_val = array_map( 'sanitize_text_field', $_POST['tbms_template_column_ids'] );
					foreach ( $tbms_image_ids_val as $image_id ) {
						$tbms_image_ids[]          = sanitize_text_field( $_POST['tbms_template_column_ids'][ $i ] );
						$tbms_member_designation[] = sanitize_text_field( $_POST['tbms_designation'][ $i ] );
						$tbms_member_link_frst[]   = sanitize_text_field( $_POST['tbms_icon_link_url_first'][ $i ] );
						$tbms_member_link_second[] = sanitize_text_field( $_POST['tbms_icon_link_url_second'][ $i ] );
						$tbms_member_link_third[]  = sanitize_text_field( $_POST['tbms_icon_link_url_third'][ $i ] );

						// update member image name and bio
						$tbms_member_image_details = array(
							'ID'           => sanitize_text_field( $image_id ),
							'post_title'   => sanitize_text_field( $_POST['tbms_member_name'][ $i ] ),
							'post_content' => sanitize_text_field( $_POST['tbms_member_description'][ $i ] ),
						);
						wp_update_post( $tbms_member_image_details );
						$i++;
					}

					$tbms_settings = array(
						'tbms_template_column_ids'   => $tbms_image_ids,
						'tbms_designation'           => $tbms_member_designation,
						'tbms_image_size'            => $tbms_image_size,
						'tbms_icon_link_url_first'   => $tbms_member_link_frst,
						'tbms_icon_link_url_second'  => $tbms_member_link_second,
						'tbms_icon_link_url_third'   => $tbms_member_link_third,
						'tbms_template_design'       => $tbms_template_design,
						'tbms_total_column'          => $tbms_total_column,
						'tbms_background_team_color' => $tbms_background_team_color,
						'tbms_decription_color'      => $tbms_decription_color,
						'tbms_link_tab'              => $tbms_link_tab,
						'tbms_custom_css'            => $tbms_custom_css,
					);

					$tbms_settings_meta_key = 'tbms_post_data_' . $post_id;
					update_post_meta( $post_id, $tbms_settings_meta_key, $tbms_settings );
				}
			}//// end save setting
		}//end tbms_save_settings()
	}

	$tbms_object = new TBMS_AWPLIFE();
	require_once 'shotcode.php';
}
?>
