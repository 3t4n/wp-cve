<?php
/**
 * Organize Media Folder
 *
 * @package    Organize Media Folder
 * @subpackage OrganizeMediaFolder Management screen
	Copyright (c) 2020- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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

$organizemediafolderadmin = new OrganizeMediaFolderAdmin();

/** ==================================================
 * Management screen
 */
class OrganizeMediaFolderAdmin {

	/** ==================================================
	 * Path
	 *
	 * @var $upload_dir  upload_dir.
	 */
	private $upload_dir;

	/** ==================================================
	 * Path
	 *
	 * @var $upload_url  upload_url.
	 */
	private $upload_url;

	/** ==================================================
	 * Path
	 *
	 * @var $upload_path  upload_path.
	 */
	private $upload_path;

	/** ==================================================
	 * Add on bool
	 *
	 * @var $is_add_on_activate  is_add_on_activate.
	 */
	private $is_add_on_activate;

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		$this->is_add_on_activate = false;
		if ( function_exists( 'organize_media_folder_add_on_load_textdomain' ) ) {
			$this->is_add_on_activate = true;
		}

		add_action( 'admin_init', array( $this, 'register_settings' ) );

		/* Admin bar folder switch menu */
		add_action( 'admin_bar_menu', array( $this, 'folder_switch_admin_bar_menu' ), 9999, 1 );

		add_action( 'admin_menu', array( $this, 'add_pages' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
		add_action( 'admin_notices', array( $this, 'notices' ) );

		if ( ! class_exists( 'TT_OrganizeMediaFolder_List_Table' ) ) {
			require_once __DIR__ . '/class-tt-organizemediafolder-list-table.php';
		}

		$wp_uploads = wp_upload_dir();
		$upload_dir = wp_normalize_path( $wp_uploads['basedir'] );
		$upload_url = $wp_uploads['baseurl'];
		$upload_path = str_replace( site_url( '/' ), '', $upload_url );
		$this->upload_dir  = untrailingslashit( $upload_dir );
		$this->upload_path = untrailingslashit( $upload_path );
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
			$this_plugin = 'organize-media-folder/organizemediafolder.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'upload.php?page=organizemediafolder' ) . '">' . __( 'Folder Management', 'organize-media-folder' ) . '</a>';
		}
		return $links;
	}

	/** ==================================================
	 * Add page
	 *
	 * @since 1.00
	 */
	public function add_pages() {
		add_media_page(
			__( 'Folder Management', 'organize-media-folder' ),
			__( 'Folder Management', 'organize-media-folder' ),
			'upload_files',
			'organizemediafolder',
			array( $this, 'manage_page' )
		);
	}

	/** ==================================================
	 * Add Css and Script
	 *
	 * @since 1.00
	 */
	public function load_custom_wp_admin_style() {
		if ( $this->is_my_plugin_screen() ) {
			wp_enqueue_style( 'jquery-datetimepicker', plugin_dir_url( __DIR__ ) . 'css/jquery.datetimepicker.css', array(), '2.3.4' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-datetimepicker', plugin_dir_url( __DIR__ ) . 'js/jquery.datetimepicker.js', null, '2.3.4' );
			wp_enqueue_script( 'jquery-datetimepicker-omf', plugin_dir_url( __DIR__ ) . 'js/jquery.datetimepicker.omf.js', array( 'jquery' ), array(), '1.00', false );
			wp_enqueue_script( 'organizemediafolder-js', plugin_dir_url( __DIR__ ) . 'js/jquery.organizemediafolder.js', array( 'jquery' ), array(), '1.00', false );
		}

		$handle = 'omf-folder-change-js';
		$action = 'omf_folder';
		wp_enqueue_script( $handle, plugin_dir_url( __DIR__ ) . 'js/jquery.omf.folderchange.js', array( 'jquery' ), '1.00', false );
		wp_localize_script(
			$handle,
			'omf_fc',
			array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'action'    => $action,
				'nonce'     => wp_create_nonce( $action ),
				'menu_text' => __( 'Upload folder', 'organize-media-folder' ) . ' : ',
			)
		);
	}

	/** ==================================================
	 * For only admin style
	 *
	 * @since 1.00
	 */
	private function is_my_plugin_screen() {
		$screen = get_current_screen();
		if ( is_object( $screen ) && 'media_page_organizemediafolder' === $screen->id ) {
			return true;
		} else {
			return false;
		}
	}

	/** ==================================================
	 * Main
	 *
	 * @since 1.00
	 */
	public function manage_page() {

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$omf_admin_settings = get_option( 'omf_admin' );
		$scriptname = admin_url( 'upload.php?page=organizemediafolder' );

		if ( isset( $_POST['organize-media-folder-update1'] ) && ! empty( $_POST['organize-media-folder-update1'] ) ||
				isset( $_POST['organize-media-folder-update2'] ) && ! empty( $_POST['organize-media-folder-update2'] ) ) {
			if ( check_admin_referer( 'omf_update', 'organize_media_folder_update' ) ) {
				$update_ids = array();
				if ( ! empty( $_POST['bulk_folder_check'] ) ) {
					$update_ids = filter_var(
						wp_unslash( $_POST['bulk_folder_check'] ),
						FILTER_CALLBACK,
						array(
							'options' => function ( $value ) {
								return absint( $value );
							},
						)
					);
				}
				$target_terms = array();
				if ( ! empty( $_POST['targetdirs'] ) ) {
					$target_terms = filter_var(
						wp_unslash( $_POST['targetdirs'] ),
						FILTER_CALLBACK,
						array(
							'options' => function ( $value ) {
								return sanitize_text_field( $value );
							},
						)
					);
				}
				if ( ! empty( $update_ids ) && ! empty( $target_terms ) ) {
					$messages = array();
					foreach ( $update_ids as $update_id ) {
						$target_folder = get_term_by( 'slug', $target_terms[ $update_id ], 'omf_folders' )->name;
						$message = apply_filters( 'omf_folder_move_regist', $update_id, $target_folder );
						if ( $message ) {
							/* for Media Library folders term */
							do_action( 'omf_folders_term_change', $target_folder, $update_id );
							$messages[] = $message;
						}
					}
					/* for Term filter update */
					do_action( 'omf_term_filter_update' );
					$success_messages = array();
					$error_messages = array();
					$log_messages = array();
					foreach ( $messages as $message ) {
						/* translators: %1$d ID %2$s Filename %3$s %4$s Folder name */
						$message_txt = sprintf( __( 'ID: %1$d File: %2$s Folder: %3$s -> %4$s', 'organize-media-folder' ), $message['ID'], $message['filename'], $message['current'], $message['target'] );
						if ( array_key_exists( 'result', $message ) && 'success' === $message['result'] ) {
							$success_messages[] = '[' . $message_txt . ']';
							$log_messages[] = $message_txt;
						} else if ( array_key_exists( 'result', $message ) && 'error' === $message['result'] ) {
							$message_txt .= ' (' . $message['error'] . ') ';
							$error_messages[] = '[' . $message_txt . ']';
							$log_messages[] = __( 'Update failed : ', 'organize-media-folder' ) . $message_txt;
						} else {
							$error_messages[] = '[' . $message_txt . ']';
							$log_messages[] = __( 'Update failed : ', 'organize-media-folder' ) . $message_txt;
						}
					}
					krsort( $log_messages );
					if ( 0 < count( $success_messages ) ) {
						/* translators: Success count */
						echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Updated %1$d media files. %2$s', 'organize-media-folder' ), count( $success_messages ), implode( ' ', $success_messages ) ) ) . '</li></ul></div>';
					}
					if ( 0 < count( $error_messages ) ) {
						/* translators: Success count */
						echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Update failed %1$d media files. %2$s', 'organize-media-folder' ), count( $error_messages ), implode( ' ', $error_messages ) ) ) . '</li></ul></div>';
					}
					$logs = get_user_option( 'omf_current_logs', get_current_user_id() );
					if ( ! empty( $logs ) ) {
						$log_messages = array_merge( $log_messages, $logs );
					}
					$log_messages = array_slice( $log_messages, 0, 100 );
					update_user_option( get_current_user_id(), 'omf_current_logs', $log_messages );
				} else {
					echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'Please select medias.', 'organize-media-folder' ) . '</li></ul></div>';
				}
			}
		}

		?>
		<div class="wrap">

		<h2>Organize Media Folder</h2>
		<div style="clear: both;"></div>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'organize-media-folder' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>
			<form method="post" id="organizemediafolder_settings" action="<?php echo esc_url( $scriptname ); ?>">
			<?php wp_nonce_field( 'omf_settings', 'organize_media_folder_settings' ); ?>
			<?php
			if ( current_user_can( 'manage_options' ) ) {
				if ( get_user_option( 'omf_filter_term', get_current_user_id() ) ) {
					?>
					<details>
					<?php
				} else {
					?>
					<details open>
					<?php
				}
				?>
				<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Settings' ); ?></strong></summary>
				<?php $this->settings_page( $omf_admin_settings ); ?>
				</details>
				<hr>
				<?php
			}
			?>
			</form>
			<?php
			if ( get_user_option( 'omf_filter_term', get_current_user_id() ) ) {
				?>
				<div style="margin: 5px; padding: 5px;">
					<form method="post" id="organizemediafolder_forms">
					<?php wp_nonce_field( 'omf_update', 'organize_media_folder_update' ); ?>
					</form>
					<?php
					$organize_media_folder_list_table = new TT_OrganizeMediaFolder_List_Table();
					$organize_media_folder_list_table->prepare_items();
					submit_button( __( 'Update' ), 'primary', 'organize-media-folder-update1', false, array( 'form' => 'organizemediafolder_forms' ) );
					do_action( 'omf_per_page_set', get_current_user_id() );
					$organize_media_folder_list_table->display();
					submit_button( __( 'Update' ), 'primary', 'organize-media-folder-update2', false, array( 'form' => 'organizemediafolder_forms' ) );
					?>
				</div>
				<?php
			}
			?>

		</div>
		<?php
	}

	/** ==================================================
	 * Settings page
	 *
	 * @param array $omf_admin_settings  Settings.
	 * @since 1.00
	 */
	private function settings_page( $omf_admin_settings ) {

		?>
		<div class="wrap">
			<?php
			if ( get_user_option( 'omf_filter_term', get_current_user_id() ) ) {
				?>
				<div class="wrap" style="background: #ffffff;">
					<details style="margin-bottom: 5px;">
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Exclude folders', 'organize-media-folder' ); ?></strong></summary>
						<p><?php esc_html_e( 'Exclude the folders that you do not want to be displayed. If you change this setting and save it, be sure to "Initialization" it afterwards.', 'organize-media-folder' ); ?></p>
						<ol style="list-style-type: disc">
						<li><?php echo esc_html_e( 'Exclude leading and trailing slashes.', 'organize-media-folder' ); ?></li>
						<li><?php echo esc_html( sprintf( __( 'For a single folder, specify the folder name.', 'organize-media-folder' ), '|' ) ); ?>
							<ol style="list-style-type: disc">
							<li>
							<?php /* translators: %1$s folder */ ?>
							<?php echo esc_html( sprintf( __( 'Sample: Exclude %1$s.', 'organize-media-folder' ), 'test/test2' ) ); ?> [<code>test/test2</code>]
							</li>
							</ol>
						</li>
						<li>
							<?php /* translators: sepalater */ ?>
							<?php echo esc_html( sprintf( __( 'If there are multiple folders, specify them by separating them with "%1$s".', 'organize-media-folder' ), '|' ) ); ?>
							<ol style="list-style-type: disc">
							<li>
							<?php /* translators: %1$s %2$s folders */ ?>
							<?php echo esc_html( sprintf( __( 'Sample: Exclude %1$s and %2$s.', 'organize-media-folder' ), 'test/test2', 'test3' ) ); ?> [<code>test/test2|test3</code>]
							</li>
							</ol>
						</li>
						</ol>
						<textarea name="exclude_folders" rows="3" style="width: 100%;"><?php echo esc_textarea( $omf_admin_settings['exclude_folders'] ); ?></textarea>
					</details>
					<details style="margin-bottom: 5px;" open>
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Make folder', 'organize-media-folder' ); ?></strong></summary>
						<input type="checkbox" name="subdir_change" <?php checked( true, $omf_admin_settings['subdir_change'] ); ?>  value="1" > <?php esc_html_e( 'Make the created folder an upload folder', 'organize-media-folder' ); ?>
						<div style="display: block; padding: 5px 5px;">
							<code><?php echo esc_html( $this->upload_path . '/' ); ?></code>
							<input type="text" name="newdir">
						</div>
					</details>
					<details style="margin-bottom: 5px;" open>
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Upload folder', 'organize-media-folder' ); ?></strong></summary>
						<div style="display: block; padding: 5px 5px;">
							<?php do_action( 'omf_upload_folder_select' ); ?>
						</div>
					</details>
					<details style="margin-bottom: 5px;">
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Uploads folder to be displayed in the admin bar', 'organize-media-folder' ); ?></strong></summary>
						<p class="description">
							<?php esc_html_e( 'A reload is required to reflect this setting.', 'organize-media-folder' ); ?>
						</p>
						<div style="display: block; padding: 5px 5px;">
							<?php do_action( 'omf_admin_upload_folders_select' ); ?>
						</div>
					</details>
					<details style="margin-bottom: 5px;">
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Date' ); ?></strong></summary>
						<div style="display: block;padding: 5px 5px;">
						<input type="radio" name="dateset" value="new" <?php checked( 'new', $omf_admin_settings['dateset'] ); ?> /><?php esc_html_e( 'Update to use of the current date/time.', 'organize-media-folder' ); ?>
						</div>
						<?php
						if ( $this->is_add_on_activate ) {
							do_action( 'omf_exif_settings', $omf_admin_settings );
						} else {
							?>
							<div style="display: block; padding: 5px 5px;">
								<input type="radio" disabled="disabled" /><?php esc_html_e( 'Update to use of the exif information date/time.', 'organize-media-folder' ); ?> <span style="color: red;"><?php esc_html_e( 'Add On is required.', 'organize-media-folder' ); ?></span>
							</div>
							<?php
						}
						?>
						<div style="display: block; padding: 5px 5px;">
						<input type="radio" name="dateset" value="fixed" <?php checked( 'fixed', $omf_admin_settings['dateset'] ); ?> /><?php esc_html_e( 'Update to use of fixed the date/time.', 'organize-media-folder' ); ?>
						</div>
						<div style="display: block; padding: 5px 40px;">
						<input type="text" id="datetimepicker-omf" name="datefixed" value="<?php echo esc_attr( $omf_admin_settings['datefixed'] ); ?>">
						</div>
					</details>
					<details style="margin-bottom: 5px;">
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'EXIF caption', 'organize-media-folder' ); ?></strong></summary>
						<?php
						if ( $this->is_add_on_activate ) {
							do_action( 'omf_exifcaption_settings' );
						} else {
							?>
							<div style="display: block; padding: 5px 5px;">
								<span style="color: red;"><?php esc_html_e( 'Add On is required.', 'organize-media-folder' ); ?></span>
							</div>
							<?php
						}
						?>
					</details>
					<details style="margin-bottom: 5px;">
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Add on', 'organize-media-folder' ); ?></strong></summary>
					<?php
					if ( $this->is_add_on_activate ) {
						do_action( 'omf_addon_license' );
					} else {
						$plugin_base_dir = untrailingslashit( wp_normalize_path( plugin_dir_path( __DIR__ ) ) );
						$slugs = explode( '/', $plugin_base_dir );
						$slug = end( $slugs );
						$plugin_dir = untrailingslashit( rtrim( $plugin_base_dir, $slug ) );
						$add_on_dir = $plugin_dir . '/organize-media-folder-add-on';
						?>
						<div style="display: block;padding: 5px 5px;">
							<h2>Organize Media Folder Add On</h2>
							<p class="description">
							<?php esc_html_e( 'This Add-on When registering media by "Organize Media Folder", add EXIF to Media Library caption and EXIF date/time to media.', 'organize-media-folder' ); ?>
							</p>
							<div style="display: block;padding: 5px 5px;">
							<?php
							if ( is_dir( $add_on_dir ) ) {
								?>
								<span style="color: red;"><?php esc_html_e( 'Installed & Deactivated', 'organize-media-folder' ); ?>
								<?php
							} else {
								?>
								<a href="<?php echo esc_url( __( 'https://shop.riverforest-wp.info/organize-media-folder-add-on/', 'organize-media-folder' ) ); ?>" target="_blank" rel="noopener noreferrer" class="page-title-action"><?php esc_html_e( 'BUY', 'organize-media-folder' ); ?></a>
								<?php
							}
							?>
							</div>
						</div>
						<?php
					}
					?>
					</details>
					<details style="margin-bottom: 5px;">
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Logs', 'organize-media-folder' ); ?></strong></summary>
					<p class="description">
					<?php esc_html_e( 'Displays the last 100 logs.', 'organize-media-folder' ); ?>
					</p>
					<?php
					$logs = get_user_option( 'omf_current_logs', get_current_user_id() );
					if ( ! empty( $logs ) ) {
						foreach ( $logs as $value ) {
							?>
							<div style="display: block;padding: 5px 5px;"><?php echo esc_html( $value ); ?></div>
							<?php
						}
					}
					?>
					</details>
					<details style="margin-bottom: 5px;">
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Initialization', 'organize-media-folder' ); ?></strong></summary>
						<ul style="display: block;padding: 15px 15px; list-style-type: disc;">
							<li>
							<?php esc_html_e( 'Update the folder structure and taxonomy. This is necessary if you have created or deleted folders in another way (e.g. FTP). It is also required if you have set "Exclude folders".', 'organize-media-folder' ); ?>
							</li>
							<li>
							<?php esc_html_e( 'This process takes a long time when there are a large number of files and folders, and the process may be interrupted by a timeout. In that case, increase the "max_execution_time" value specified in "php.ini".', 'organize-media-folder' ); ?>
							</li>
							<li>
							<?php esc_html_e( 'If you have a large number of files and folders, you may run out of memory. In that case, increase the "memory_limit" value specified in "php.ini".', 'organize-media-folder' ); ?>
							</li>
						</ul>
						<?php submit_button( __( 'Initialization', 'organize-media-folder' ), 'primary', 'organize-media-folder-initial-settings', true ); ?>
						<hr>
					</details>

					<?php submit_button( __( 'Save Changes' ), 'large', 'organize-media-folder-settings-options-apply', true ); ?>
				</div>
				<?php
			} else {
				?>
				<div class="wrap" style="background: #ffffff;">
					<details style="margin-bottom: 5px;" open>
					<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Initialization', 'organize-media-folder' ); ?></strong></summary>
						<ul style="display: block;padding: 15px 15px; list-style-type: disc;">
							<li>
							<?php esc_html_e( 'This process takes a long time when there are a large number of files and folders, and the process may be interrupted by a timeout. In that case, increase the "max_execution_time" value specified in "php.ini".', 'organize-media-folder' ); ?>
							</li>
							<li>
							<?php esc_html_e( 'If you have a large number of files and folders, you may run out of memory. In that case, increase the "memory_limit" value specified in "php.ini".', 'organize-media-folder' ); ?>
							</li>
						</ul>
					</details>

					<?php submit_button( __( 'Initialization', 'organize-media-folder' ), 'primary', 'organize-media-folder-initial-settings', true ); ?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	/** ==================================================
	 * Admin Bar
	 *
	 * @param object $wp_admin_bar  wp_admin_bar.
	 * @since 1.20
	 */
	public function folder_switch_admin_bar_menu( $wp_admin_bar ) {

		if ( current_user_can( 'manage_options' ) ) {
			$omf_admin_settings = get_option( 'omf_admin' );
			if ( empty( $omf_admin_settings['subdir'] ) ) {
				$omf_admin_settings['subdir'] = '/';
			}

			$wp_admin_bar->add_menu(
				array(
					'id'    => 'omf-folder-switch',
					'title' => __( 'Upload folder', 'organize-media-folder' ) . ' : ' . $omf_admin_settings['subdir'],
				)
			);

			$folders = apply_filters( 'omf_dir_selectbox_admin_bar', array() );
			foreach ( $folders as $value ) {
				$wp_admin_bar->add_menu( $value );
			}
		}
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
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'organize-media-folder' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'organize-media-folder' );

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
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'organize-media-folder' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php
	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 1.00
	 */
	private function options_updated() {

		if ( isset( $_POST['organize-media-folder-initial-settings'] ) && ! empty( $_POST['organize-media-folder-initial-settings'] ) ) {
			if ( check_admin_referer( 'omf_settings', 'organize_media_folder_settings' ) ) {
				do_action( 'omf_dirs_tree', true );
				do_action( 'omf_folders_term_create' );
				do_action( 'omf_term_filter_update' );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Folder structure and taxonomy have been updated.', 'organize-media-folder' ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['per_page_change'] ) && ! empty( $_POST['per_page_change'] ) ) {
			if ( check_admin_referer( 'omf_settings', 'organize_media_folder_settings' ) ) {
				if ( ! empty( $_POST['per_page'] ) ) {
					$per_page = absint( $_POST['per_page'] );
					update_user_option( get_current_user_id(), 'omf_per_page', $per_page );
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( __( 'Settings' ) . ' --> ' . __( 'Changes saved.' ) ) . '</li></ul></div>';
				}
			}
		}

		if ( isset( $_POST['organize-media-folder-settings-options-apply'] ) && ! empty( $_POST['organize-media-folder-settings-options-apply'] ) ) {
			if ( check_admin_referer( 'omf_settings', 'organize_media_folder_settings' ) ) {
				if ( current_user_can( 'manage_options' ) ) {
					$omf_admin_settings = get_option( 'omf_admin' );
					if ( ! empty( $_POST['exclude_folders'] ) ) {
						$omf_admin_settings['exclude_folders'] = sanitize_text_field( wp_unslash( $_POST['exclude_folders'] ) );
					} else {
						$omf_admin_settings['exclude_folders'] = null;
					}
					if ( ! empty( $_POST['subdir_change'] ) ) {
						$omf_admin_settings['subdir_change'] = true;
					} else {
						$omf_admin_settings['subdir_change'] = false;
					}
					if ( ! empty( $_POST['dateset'] ) ) {
						$omf_admin_settings['dateset'] = sanitize_text_field( wp_unslash( $_POST['dateset'] ) );
					}
					if ( ! empty( $_POST['datefixed'] ) ) {
						$omf_admin_settings['datefixed'] = sanitize_text_field( wp_unslash( $_POST['datefixed'] ) );
					}
					if ( ! empty( $_POST['subdir'] ) ) {
						$omf_key = sanitize_text_field( wp_unslash( $_POST['subdir'] ) );
						$subdir = get_term_by( 'slug', $omf_key, 'omf_folders' )->name;
						$omf_admin_settings['subdir'] = $subdir;
					}
					if ( ! empty( $_POST['admin_bar_folders'] ) ) {
						$selected_folders = filter_var(
							wp_unslash( $_POST['admin_bar_folders'] ),
							FILTER_CALLBACK,
							array(
								'options' => function ( $value ) {
									return sanitize_text_field( $value );
								},
							)
						);
						$omf_admin_settings['admin_bar_folders'] = array_keys( $selected_folders );
					} else {
						$omf_admin_settings['admin_bar_folders'] = array();
					}
					update_option( 'omf_admin', $omf_admin_settings );
					$newdir = null;
					if ( ! empty( $_POST['newdir'] ) ) {
						$newdir = wp_strip_all_tags( wp_unslash( $_POST['newdir'] ) );
						$mkdir_new_realdir = $this->upload_dir . '/' . $newdir;
						if ( ! file_exists( $mkdir_new_realdir ) ) {
							$err_mkdir = @wp_mkdir_p( $mkdir_new_realdir );
							if ( ! $err_mkdir ) {
								/* translators: Error message */
								echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Unable to create folder[%1$s].', 'organize-media-folder' ), wp_normalize_path( apply_filters( 'omf_mb_utf8', $mkdir_new_realdir ) ) ) ) . '</li></ul></div>';
								return;
							} else {
								do_action( 'omf_folders_term_new', '/' . $newdir );
								do_action( 'omf_term_filter_update' );
								/* translators: Error message */
								echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Created folder[%1$s].', 'organize-media-folder' ), wp_normalize_path( apply_filters( 'omf_mb_utf8', $mkdir_new_realdir ) ) ) ) . '</li></ul></div>';
								if ( ! empty( $_POST['subdir_change'] ) ) {
									$subdir = '/' . $newdir;
									$omf_admin_settings['subdir'] = $subdir;
									update_option( 'omf_admin', $omf_admin_settings );
									/* translators: Error message */
									echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'The upload folder has been changed to %s.', 'organize-media-folder' ), $subdir ) ) . '</li></ul></div>';
								}
							}
						} else {
							/* translators: Error message */
							echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Folder[%1$s] already exists.', 'organize-media-folder' ), wp_normalize_path( apply_filters( 'omf_mb_utf8', $mkdir_new_realdir ) ) ) ) . '</li></ul></div>';
						}
					}
				}
				do_action( 'omf_exifcaption_options_updated' );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( __( 'Settings' ) . ' --> ' . __( 'Changes saved.' ) ) . '</li></ul></div>';
			}
		}
	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 1.00
	 */
	public function register_settings() {

		$subdir = '/';

		$dateset = 'new';
		if ( function_exists( 'wp_date' ) ) {
			$datefixed = wp_date( 'Y-m-d H:i:s' );
		} else {
			$datefixed = date_i18n( 'Y-m-d H:i:s' );
		}

		if ( ! get_option( 'omf_admin' ) ) {
			$omf_admin_settings_tbl = array(
				'exclude_folders' => null,
				'subdir' => $subdir,
				'subdir_change' => true,
				'dateset' => $dateset,
				'datefixed' => $datefixed,
				'admin_bar_folders' => array(),
			);
			update_option( 'omf_admin', $omf_admin_settings_tbl );
		} else {
			$omf_admin_settings = get_option( 'omf_admin' );
			if ( array_key_exists( 'character_code', $omf_admin_settings ) ) {
				unset( $omf_admin_settings['character_code'] );
				update_option( 'omf_admin', $omf_admin_settings );
			}
			if ( ! array_key_exists( 'admin_bar_folders', $omf_admin_settings ) ) {
				$omf_admin_settings['admin_bar_folders'] = array();
				update_option( 'omf_admin', $omf_admin_settings );
			} else if ( is_null( $omf_admin_settings['admin_bar_folders'] ) ) { /* for version 1.23 */
				$omf_admin_settings['admin_bar_folders'] = array();
				update_option( 'omf_admin', $omf_admin_settings );
			}
		}

		if ( ! get_user_option( 'omf_per_page', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'omf_per_page', 20 );
		}

		if ( ! get_user_option( 'omf_filter_user', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'omf_filter_user', null );
		}
		if ( ! get_user_option( 'omf_filter_mime_type', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'omf_filter_mime_type', null );
		}
		if ( ! get_user_option( 'omf_filter_monthly', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'omf_filter_monthly', null );
		}
	}

	/** ==================================================
	 * Notices
	 *
	 * @since 1.00
	 */
	public function notices() {

		if ( $this->is_my_plugin_screen() ) {
			if ( class_exists( 'ExtendMediaUpload' ) ) {
				/* translators: Unnecessary plugin name */
				echo '<div class="notice notice-error is-dismissible"><ul><li>' . wp_kses_post( sprintf( __( 'The plugin "%1$s" is activated. Please stop it as it may cause unexpected errors.', 'organize-media-folder' ), 'Extend Media Upload' ) ) . '</li></ul></div>';
			}
			if ( class_exists( 'OrganizeMediaLibrary' ) ) {
				/* translators: Unnecessary plugin name */
				echo '<div class="notice notice-error is-dismissible"><ul><li>' . wp_kses_post( sprintf( __( 'The plugin "%1$s" is activated. Please stop it as it may cause unexpected errors.', 'organize-media-folder' ), 'Organize Media Library by Folders' ) ) . '</li></ul></div>';
			}
			if ( class_exists( 'UploadMediaExifDate' ) ) {
				/* translators: Unnecessary plugin name */
				echo '<div class="notice notice-error is-dismissible"><ul><li>' . wp_kses_post( sprintf( __( 'The plugin "%1$s" is activated. Please stop it as it may cause unexpected errors.', 'organize-media-folder' ), 'Upload Media Exif Date' ) ) . '</li></ul></div>';
			}
		}
	}
}


