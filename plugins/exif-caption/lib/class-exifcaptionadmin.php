<?php
/**
 * Exif Caption
 *
 * @package    Exif Caption
 * @subpackage ExifCaptionAdmin Main & Management screen
/*
	Copyright (c) 2015- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$exifcaptionadmin = new ExifCaptionAdmin();

/** ==================================================
 * Management screen
 */
class ExifCaptionAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 2.07
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'register_settings' ) );

		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'add_pages' ) );

		if ( ! class_exists( 'TT_ExifCaption_List_Table' ) ) {
			require_once __DIR__ . '/class-tt-exifcaption-list-table.php';
		}
	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'exif-caption/exifcaption.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'upload.php?page=exifcaption-settings' ) . '">Exif Caption</a>';
		}
			return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function add_pages() {
		add_media_page(
			'Exif Caption',
			'Exif Caption',
			'upload_files',
			'exifcaption-settings',
			array( $this, 'settings_page' )
		);
	}

	/** ==================================================
	 * Sub Menu
	 *
	 * @since 1.00
	 */
	public function settings_page() {

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$exifcaption_settings = get_user_option( 'exifcaption', get_current_user_id() );
		$scriptname = admin_url( 'upload.php?page=exifcaption-settings' );

		if ( isset( $_POST['per_page_change'] ) && ! empty( $_POST['per_page_change'] ) ) {
			if ( check_admin_referer( 'excp_update', 'exif_caption_update' ) ) {
				if ( ! empty( $_POST['per_page'] ) ) {
					update_user_option( get_current_user_id(), 'excp_per_page', absint( $_POST['per_page'] ) );
				}
			}
		}

		if ( isset( $_POST['exif-caption-update1'] ) && ! empty( $_POST['exif-caption-update1'] ) ||
				isset( $_POST['exif-caption-update2'] ) && ! empty( $_POST['exif-caption-update2'] ) ) {
			if ( check_admin_referer( 'excp_update', 'exif_caption_update' ) ) {
				$update_ids = array();
				if ( ! empty( $_POST['bulk_exif_caption_check'] ) ) {
					$update_ids = filter_var(
						wp_unslash( $_POST['bulk_exif_caption_check'] ),
						FILTER_CALLBACK,
						array(
							'options' => function ( $value ) {
								return absint( $value );
							},
						)
					);
				}
				$update_exifcaption = array();
				if ( ! empty( $_POST['bulk_exif_caption_update'] ) ) {
					$update_exifcaption = filter_var(
						wp_unslash( $_POST['bulk_exif_caption_update'] ),
						FILTER_CALLBACK,
						array(
							'options' => function ( $value ) {
								return sanitize_text_field( $value );
							},
						)
					);
				}
				if ( ! empty( $update_ids ) && ! empty( $update_exifcaption ) ) {
					$messages = array();
					$log_messages = array();
					foreach ( $update_ids as $update_id ) {
						$post = get_post( $update_id );
						$org_caption = $post->post_excerpt;
						do_action( 'excp_update', $update_id, $update_exifcaption[ $update_id ], $exifcaption_settings['and_alt'] );
						/* translators: %1$d ID %2$s Title %3$s Org Caption %4$s Update Caption */
						$message_txt = sprintf( __( 'ID: %1$d Title: %2$s Caption: %3$s -> %4$s', 'exif-caption' ), $update_id, $post->post_title, $org_caption, $update_exifcaption[ $update_id ] );
						$messages[] = '[' . $message_txt . ']';
						$log_messages[] = $message_txt;
					}
					krsort( $log_messages );
					if ( 0 < count( $messages ) ) {
						/* translators: %1$d: message count %2$s: message */
						echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Updated %1$d items. %2$s', 'exif-caption' ), count( $messages ), implode( ' ', $messages ) ) ) . '</li></ul></div>';
					}
					$logs = get_user_option( 'exifcaption_current_logs', get_current_user_id() );
					if ( ! empty( $logs ) ) {
						$log_messages = array_merge( $log_messages, $logs );
					}
					$log_messages = array_slice( $log_messages, 0, 100 );
					update_user_option( get_current_user_id(), 'exifcaption_current_logs', $log_messages );
				}
			}
		}

		?>
		<div class="wrap">
		<h2>Exif Caption</h2>

		<details>
		<summary><strong><?php esc_html_e( 'Various links of this plugin', 'exif-caption' ); ?></strong></summary>
		<?php $this->credit(); ?>
		</details>

		<details style="margin-bottom: 5px;">
		<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Settings' ); ?></strong></summary>
		<div class="wrap" style="background: #ffffff;">
			<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
				<?php wp_nonce_field( 'efc_set', 'exifcaption_settings' ); ?>
				<div class="wrap">
					<details style="margin-bottom: 5px;" open>
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Insertion into the caption', 'exif-caption' ); ?></strong></summary>
						<div style="display: block; padding:5px 5px;">
							<input type="checkbox" name="and_alt" value="1" <?php checked( $exifcaption_settings['and_alt'], true ); ?> />
							<?php esc_html_e( 'Insert into alt at the same time', 'exif-caption' ); ?>
						</div>
						<div style="display: block; padding:5px 5px;">
							<input type="radio" name="exifcaption_insert" value="overwrite" 
							<?php
							if ( 'overwrite' === $exifcaption_settings['caption_insert'] ) {
								echo 'checked';}
							?>
							>
							<?php esc_html_e( 'Overwrite', 'exif-caption' ); ?>
						</div>
						<div style="display: block; padding:5px 5px;">
							<input type="radio" name="exifcaption_insert" value="left" 
							<?php
							if ( 'left' === $exifcaption_settings['caption_insert'] ) {
								echo 'checked';}
							?>
							>
							<?php esc_html_e( 'Insert to left.', 'exif-caption' ); ?>
						</div>
						<div style="display: block; padding:5px 5px;">
							<input type="radio" name="exifcaption_insert" value="right" 
							<?php
							if ( 'right' === $exifcaption_settings['caption_insert'] ) {
								echo 'checked';}
							?>
							>
							<?php esc_html_e( 'Insert to right.', 'exif-caption' ); ?>
						</div>
						<div style="display: block; padding:5px 20px;">
							Exif <?php esc_html_e( 'Tags' ); ?>
							<input type="submit" style="position:relative; top:-5px;" class="button" name="ExifDefault" value="<?php esc_attr_e( 'Default' ); ?>" />
							<div style="display: block; padding:5px 20px;">
							<textarea name="exifcaption_exif_text" style="width: 100%;"><?php echo esc_textarea( $exifcaption_settings['exif_text'] ); ?></textarea>
								<div>
								<a href="https://developer.wordpress.org/reference/functions/wp_read_image_metadata/" target="_blank" rel="noopener noreferrer" style="text-decoration: none; word-break: break-all;"><?php esc_html_e( 'For Exif tags, please read here.', 'exif-caption' ); ?></a>
								</div>
							</div>
						</div>
						<?php
						if ( is_multisite() ) {
							$exifdetails_install_url = network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=exif-details' );
							$omf_install_url = network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=organize-media-folder' );
							$bdtc_install_url = network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=bulk-datetime-change' );
						} else {
							$exifdetails_install_url = admin_url( 'plugin-install.php?tab=plugin-information&plugin=exif-details' );
							$omf_install_url = admin_url( 'plugin-install.php?tab=plugin-information&plugin=organize-media-folder' );
							$bdtc_install_url = admin_url( 'plugin-install.php?tab=plugin-information&plugin=bulk-datetime-change' );
						}
							$exifdetails_install_html = '<a href="' . $exifdetails_install_url . '" style="text-decoration: none; word-break: break-all;">Exif Details</a>';
							$omf_install_html = '<a href="' . $omf_install_url . '" style="text-decoration: none; word-break: break-all;">Organize Media Folder</a>';
							$bdtc_install_html = '<a href="' . $bdtc_install_url . '" style="text-decoration: none; word-break: break-all;">Bulk Datetime Change</a>';
						?>
						<div style="display: block; padding:5px 5px;">
							<?php
							/* translators: Plugin install link */
							echo wp_kses_post( sprintf( __( 'If you want to extend the Exif tags, Please use the %1$s.', 'exif-caption' ), $exifdetails_install_html ) );
							?>
						</div>
						<div style="display: block; padding:5px 5px;">
							<?php
							/* translators: Plugin install link */
							echo wp_kses_post( sprintf( __( 'If you want to insert the Exif at the media registration, Please use the %1$s and its add-on.', 'exif-caption' ), $omf_install_html ) );
							?>
						</div>
						<div style="display: block; padding:5px 5px;">
							<?php
							/* translators: Plugin install link */
							echo wp_kses_post( sprintf( __( 'If you want to apply the Exif shooting date time at the media registration, Please use the %1$s and its add-on.', 'exif-caption' ), $omf_install_html ) );
							?>
						</div>
						<div style="display: block; padding:5px 5px;">
							<?php
							/* translators: Plugin install link */
							echo wp_kses_post( sprintf( __( 'If you want to bulk change the date time to Exif shooting date time, Please use the %1$s and its add-on.', 'exif-caption' ), $bdtc_install_html ) );
							?>
						</div>
					</details>
					<details style="margin-bottom: 5px;">
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Logs', 'exif-caption' ); ?></strong></summary>
					<p class="description">
					<?php esc_html_e( 'Displays the last 100 logs.', 'exif-caption' ); ?>
					</p>
					<?php
					$logs = get_user_option( 'exifcaption_current_logs', get_current_user_id() );
					if ( ! empty( $logs ) ) {
						foreach ( $logs as $value ) {
							?>
							<div style="display: block;padding:5px 5px"><?php echo esc_html( $value ); ?></div>
							<?php
						}
					}
					?>
					</details>
				</div>
			<?php submit_button( __( 'Save Changes' ), 'large', 'exifcaption_apply', true ); ?>
			</form>
		</div>
		</details>

			<div style="margin: 5px; padding: 5px;">
				<form method="post" id="exifcaption_forms">
				<?php wp_nonce_field( 'excp_update', 'exif_caption_update' ); ?>
				</form>
				<?php
				$exif_caption_list_table = new TT_ExifCaption_List_Table();
				$exif_caption_list_table->prepare_items();
				submit_button( __( 'Update' ), 'primary', 'exif-caption-update1', false, array( 'form' => 'exifcaption_forms' ) );
				do_action( 'excp_per_page_set', get_current_user_id() );
				$exif_caption_list_table->display();
				submit_button( __( 'Update' ), 'primary', 'exif-caption-update2', false, array( 'form' => 'exifcaption_forms' ) );
				?>
			</div>

		</div>
		<?php
	}

	/** ==================================================
	 * Credit
	 *
	 * @since 1.00
	 */
	private function credit() {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		/* translators: FAQ Link & Slug */
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'exif-caption' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'exif-caption' );

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo esc_html( $plugin_version ); ?> | 
		<a style="text-decoration: none;" href="<?php echo esc_url( $faq ); ?>" target="_blank" rel="noopener noreferrer">FAQ</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $support ); ?>" target="_blank" rel="noopener noreferrer">Support Forums</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $review ); ?>" target="_blank" rel="noopener noreferrer">Reviews</a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo esc_url( $translate ); ?>" target="_blank" rel="noopener noreferrer">
		<?php
		/* translators: Plugin translation link */
		echo esc_html( sprintf( __( 'Translations for %s' ), $plugin_name ) );
		?>
		</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'exif-caption' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php
	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 1.00
	 */
	public function register_settings() {

		/* Old option 2.16 -> New option 2.17 */
		if ( get_option( 'exifcaption_settings_' . get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'exifcaption', get_option( 'exifcaption_settings_' . get_current_user_id() ) );
			delete_option( 'exifcaption_settings_' . get_current_user_id() );
		}

		if ( ! get_user_option( 'exifcaption', get_current_user_id() ) ) {
			$caption_insert = 'overwrite';
			$exif_text = '%title% %credit% %camera% %caption% %created_timestamp% %copyright% %aperture% %shutter_speed% %iso% %focal_length% %white_balance% %orientation%';
			$exifcaption_tbl = array(
				'and_alt' => false,
				'caption_insert' => $caption_insert,
				'exif_text' => $exif_text,
			);
			update_user_option( get_current_user_id(), 'exifcaption', $exifcaption_tbl );
		} else {
			$exifcaption_settings = get_user_option( 'exifcaption', get_current_user_id() );
			if ( ! array_key_exists( 'and_alt', $exifcaption_settings ) ) {
				$exifcaption_settings['and_alt'] = false;
				update_user_option( get_current_user_id(), 'exifcaption', $exifcaption_settings );
			}
		}

		if ( ! get_user_option( 'excp_per_page', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'excp_per_page', 20 );
		}

		if ( ! get_user_option( 'exifcaption_filter_monthly', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'exifcaption_filter_monthly', null );
		}
	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 1.00
	 */
	private function options_updated() {

		if ( isset( $_POST['ExifDefault'] ) && ! empty( $_POST['ExifDefault'] ) ) {
			if ( check_admin_referer( 'efc_set', 'exifcaption_settings' ) ) {
				$exifcaption_settings = get_user_option( 'exifcaption', get_current_user_id() );
				$exifcaption_settings['exif_text'] = '%title% %credit% %camera% %caption% %created_timestamp% %copyright% %aperture% %shutter_speed% %iso% %focal_length% %orientation%';
				update_user_option( get_current_user_id(), 'exifcaption', $exifcaption_settings );
				echo '<div class="notice notice-success is-dismissible"><ul><li>Exif ' . esc_html__( 'Tags' ) . ' --> ' . esc_html__( 'Default' ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['exifcaption_apply'] ) && ! empty( $_POST['exifcaption_apply'] ) ) {
			if ( check_admin_referer( 'efc_set', 'exifcaption_settings' ) ) {
				$exifcaption_settings = get_user_option( 'exifcaption', get_current_user_id() );
				if ( ! empty( $_POST['and_alt'] ) ) {
					$exifcaption_settings['and_alt'] = true;
				} else {
					$exifcaption_settings['and_alt'] = false;
				}
				if ( ! empty( $_POST['exifcaption_insert'] ) ) {
					$exifcaption_settings['caption_insert'] = sanitize_text_field( wp_unslash( $_POST['exifcaption_insert'] ) );
				}
				if ( ! empty( $_POST['exifcaption_exif_text'] ) ) {
					$exifcaption_settings['exif_text'] = wp_strip_all_tags( wp_unslash( $_POST['exifcaption_exif_text'] ) );
				}
				if ( ! empty( $_POST['ExifDefault'] ) ) {
					$exifcaption_settings['exif_text'] = '%title% %credit% %camera% %caption% %created_timestamp% %copyright% %aperture% %shutter_speed% %iso% %focal_length% %orientation%';
				}
				update_user_option( get_current_user_id(), 'exifcaption', $exifcaption_settings );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Settings' ) . ' --> ' . esc_html__( 'Changes saved.' ) . '</li></ul></div>';
			}
		}
	}
}


