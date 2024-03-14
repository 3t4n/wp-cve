<?php
/**
 * Options View
 *
 * @package    Options View
 * @subpackage OptionsView Main function
	Copyright (c) 2019- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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

$optionsview = new OptionsView();

/** ==================================================
 * Management screen
 */
class OptionsView {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );

		/* Original hook */
		add_action( 'opv_option_select', array( $this, 'option_select_form' ) );
		add_action( 'opv_filter_form', array( $this, 'filter_form' ) );
		add_action( 'opv_per_page_set', array( $this, 'per_page_set' ) );

		if ( ! class_exists( 'TT_OptionsView_List_Table' ) ) {
			require_once __DIR__ . '/class-tt-optionsview-list-table.php';
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
			$this_plugin = 'options-view/optionsview.php';
		}
		if ( $file === $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'tools.php?page=optionsview' ) . '">' . __( 'View' ) . '</a>';
		}
			return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_menu() {
		add_management_page( 'Options View Options', 'Options View', 'manage_options', 'optionsview', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$scriptname = admin_url( 'tools.php?page=optionsview' );

		global $wpdb;

		/* options select */
		if ( isset( $_POST['options-view-select'] ) && ! empty( $_POST['options-view-select'] ) ) {
			if ( check_admin_referer( 'opv_select', 'options_view_select' ) ) {
				if ( ! empty( $_POST['option_select'] ) ) {
					$option_select = sanitize_text_field( wp_unslash( $_POST['option_select'] ) );
					update_option( 'options-view', $option_select );
				}
			}
		}

		/* per_page change */
		if ( isset( $_POST['per_page_change'] ) && ! empty( $_POST['per_page_change'] ) ) {
			if ( check_admin_referer( 'opv_update', 'options_view_update' ) ) {
				if ( ! empty( $_POST['per_page'] ) ) {
					update_option( 'opv_per_page', absint( $_POST['per_page'] ) );
				}
			}
		}

		/* update option value */
		if ( isset( $_POST['options-view-update1'] ) && ! empty( $_POST['options-view-update1'] ) ||
				isset( $_POST['options-view-update2'] ) && ! empty( $_POST['options-view-update2'] ) ) {
			if ( check_admin_referer( 'opv_update', 'options_view_update' ) ) {
				$update_ids = array();
				if ( ! empty( $_POST['bulk_options_check'] ) ) {
					$update_ids = filter_var(
						wp_unslash( $_POST['bulk_options_check'] ),
						FILTER_CALLBACK,
						array(
							'options' => function ( $value ) {
								return absint( $value );
							},
						)
					);
				} else {
					echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html( __( 'Please select.', 'options-view' ) ) . '</li></ul></div>';
				}
				$update_options = array();
				if ( ! empty( $_POST['bulk_options_update'] ) ) {
					$update_options = filter_var(
						wp_unslash( $_POST['bulk_options_update'] ),
						FILTER_CALLBACK,
						array(
							'options' => function ( $value ) {
								return sanitize_text_field( $value );
							},
						)
					);
				}
				if ( ! empty( $update_ids ) && ! empty( $update_options ) ) {
					$update_message = array();
					foreach ( $update_ids as $update_id ) {
						$option_name = null;
						if ( 'options' == get_option( 'options-view', 'options' ) ) {
							$option_name = $wpdb->get_var( $wpdb->prepare( "SELECT option_name FROM {$wpdb->prefix}options WHERE option_id = %d", $update_id ) );
							$org_value = $wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM {$wpdb->prefix}options WHERE option_id = %d", $update_id ) );
							$update_array = array(
								'option_id' => $update_id,
								'option_value' => $update_options[ $update_id ],
							);
							$id_array = array( 'option_id' => $update_id );
							$wpdb->show_errors();
							$wpdb->update( $wpdb->prefix . 'options', $update_array, $id_array, array( '%s' ), array( '%d' ) );
							/* translators: ID: %1$d Name: %2$s OrgValue: %3$s NewValue: %4$s */
							$message_txt = sprintf( __( 'ID: %1$d Name: %2$s Value: %3$s -> %4$s', 'options-view' ), $update_id, $option_name, $org_value, $update_options[ $update_id ] );
						} else if ( 'user_options' == get_option( 'options-view', 'options' ) ) {
							$option_name = $wpdb->get_var( $wpdb->prepare( "SELECT meta_key FROM {$wpdb->prefix}usermeta WHERE umeta_id = %d", $update_id ) );
							$user_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->prefix}usermeta WHERE umeta_id = %d", $update_id ) );
							$org_value = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE umeta_id = %d", $update_id ) );
							$update_array = array(
								'umeta_id' => $update_id,
								'meta_value' => $update_options[ $update_id ],
							);
							$id_array = array( 'umeta_id' => $update_id );
							$wpdb->show_errors();
							$wpdb->update( $wpdb->prefix . 'usermeta', $update_array, $id_array, array( '%s' ), array( '%d' ) );
							/* translators: ID: %1$d UserID: %2$s Name: %3$s OrgValue: %4$s NewValue: %5$s */
							$message_txt = sprintf( __( 'ID: %1$d UserID: %2$s Name: %3$s Value: %4$s -> %5$s', 'options-view' ), $update_id, $user_id, $option_name, $org_value, $update_options[ $update_id ] );
						}
						$messages[] = '[' . $message_txt . ']';
						$log_messages[] = $message_txt;
					}
					krsort( $log_messages );
					if ( 0 < count( $messages ) ) {
						/* translators: %1$d: message count %2$s: message */
						echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Updated %1$d items. %2$s', 'options-view' ), count( $messages ), implode( ' ', $messages ) ) ) . '</li></ul></div>';
					}
					$logs = get_option( 'opv_current_logs' );
					if ( ! empty( $logs ) ) {
						$log_messages = array_merge( $log_messages, $logs );
					}
					$log_messages = array_slice( $log_messages, 0, 100 );
					update_option( 'opv_current_logs', $log_messages );
				}
			}
		}

		/* delete option */
		if ( isset( $_POST['options-view-delete1'] ) && ! empty( $_POST['options-view-delete1'] ) ||
				isset( $_POST['options-view-delete2'] ) && ! empty( $_POST['options-view-delete2'] ) ) {
			if ( check_admin_referer( 'opv_update', 'options_view_update' ) ) {
				$delete_ids = array();
				if ( ! empty( $_POST['bulk_options_check'] ) ) {
					$delete_ids = filter_var(
						wp_unslash( $_POST['bulk_options_check'] ),
						FILTER_CALLBACK,
						array(
							'options' => function ( $value ) {
								return absint( $value );
							},
						)
					);
					$delete_message = array();
					foreach ( $delete_ids as $delete_id ) {
						$option_name = null;
						if ( 'options' == get_option( 'options-view', 'options' ) ) {
							$option_name = $wpdb->get_var( $wpdb->prepare( "SELECT option_name FROM {$wpdb->prefix}options WHERE option_id = %d", $delete_id ) );
							$wpdb->show_errors();
							$wpdb->delete( $wpdb->prefix . 'options', array( 'option_id' => $delete_id ), array( '%d' ) );
							/* translators: ID: %1$d Name: %2$s */
							$message_txt = sprintf( __( 'ID: %1$d Name: %2$s', 'options-view' ), $delete_id, $option_name );
						} else if ( 'user_options' == get_option( 'options-view', 'options' ) ) {
							$option_name = $wpdb->get_var( $wpdb->prepare( "SELECT meta_key FROM {$wpdb->prefix}usermeta WHERE umeta_id = %d", $delete_id ) );
							$user_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->prefix}usermeta WHERE umeta_id = %d", $delete_id ) );
							$org_value = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE umeta_id = %d", $delete_id ) );
							$wpdb->show_errors();
							$wpdb->delete( $wpdb->prefix . 'usermeta', array( 'umeta_id' => $delete_id ), array( '%d' ) );
							/* translators: ID: %1$d UserID: %2$s Name: %3$s */
							$message_txt = sprintf( __( 'ID: %1$d UserID: %2$s Name: %3$s', 'options-view' ), $delete_id, $user_id, $option_name );
						}
						$messages[] = '[' . $message_txt . ']';
						$log_messages[] = __( 'Deleted : ', 'options-view' ) . $message_txt;
					}
					krsort( $log_messages );
					if ( 0 < count( $messages ) ) {
						/* translators: %1$d: message count %2$s: message */
						echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Deleted %1$d items. %2$s', 'options-view' ), count( $messages ), implode( ' ', $messages ) ) ) . '</li></ul></div>';
					}
					$logs = get_option( 'opv_current_logs' );
					if ( ! empty( $logs ) ) {
						$log_messages = array_merge( $log_messages, $logs );
					}
					$log_messages = array_slice( $log_messages, 0, 100 );
					update_option( 'opv_current_logs', $log_messages );
				} else {
					echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html( __( 'Please select.', 'options-view' ) ) . '</li></ul></div>';
				}
			}
		}

		?>
		<div class="wrap">
		<h2>Options View</h2>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'options-view' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>
			<details style="margin-bottom: 5px;">
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><strong><?php esc_html_e( 'Logs', 'options-view' ); ?></strong></summary>
			<p class="description">
			<?php esc_html_e( 'Displays the last 100 logs.', 'options-view' ); ?>
			</p>
			<?php
			$logs = get_option( 'opv_current_logs' );
			if ( ! empty( $logs ) ) {
				foreach ( $logs as $value ) {
					?>
					<div style="display: block;padding:5px 5px"><?php echo esc_html( $value ); ?></div>
					<?php
				}
			}
			?>
			</details>

			<div style="margin: 5px; padding: 5px;">
				<form method="post" id="optionsview_forms">
				<?php wp_nonce_field( 'opv_update', 'options_view_update' ); ?>
				</form>
				<?php
				do_action( 'opv_option_select' );
				$options_view_list_table = new TT_OptionsView_List_Table();
				$options_view_list_table->prepare_items();
				submit_button( __( 'Update' ), 'primary', 'options-view-update1', false, array( 'form' => 'optionsview_forms' ) );
				?>
				&nbsp;&nbsp;&nbsp;
				<?php
				submit_button( __( 'Delete' ), 'large', 'options-view-delete1', false, array( 'form' => 'optionsview_forms' ) );
				do_action( 'opv_per_page_set' );
				$options_view_list_table->display();
				submit_button( __( 'Update' ), 'primary', 'options-view-update2', false, array( 'form' => 'optionsview_forms' ) );
				?>
				&nbsp;&nbsp;&nbsp;
				<?php
				submit_button( __( 'Delete' ), 'large', 'options-view-delete2', false, array( 'form' => 'optionsview_forms' ) );
				?>
			</div>

		</div>
		<?php
	}

	/** ==================================================
	 * Option select form
	 *
	 * @since 2.02
	 */
	public function option_select_form() {

		$scriptname = admin_url( 'tools.php?page=optionsview' );
		?>
		<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
		<?php
		wp_nonce_field( 'opv_select', 'options_view_select' );
		?>
		<select name="option_select">
		<?php
		if ( 'options' == get_option( 'options-view', 'options' ) ) {
			?>
			<option value="options" selected><?php echo esc_attr( __( 'Options', 'options-view' ) ); ?></option>
			<option value="user_options"><?php echo esc_attr( __( 'User Options', 'options-view' ) ); ?></option>
			<?php
		} else if ( 'user_options' == get_option( 'options-view', 'options' ) ) {
			?>
			<option value="options"><?php echo esc_attr( __( 'Options', 'options-view' ) ); ?></option>
			<option value="user_options" selected><?php echo esc_attr( __( 'User Options', 'options-view' ) ); ?></option>
			<?php
		}
		?>
		</select>
		<?php submit_button( __( 'Select' ), 'large', 'options-view-select', false ); ?>
		</form>
		<?php
	}

	/** ==================================================
	 * Filter form
	 *
	 * @since 2.00
	 */
	public function filter_form() {

		$scriptname = admin_url( 'tools.php?page=optionsview' );
		?>
		<div style="margin: 0px; text-align: right;">
		<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
		<?php
		wp_nonce_field( 'opv_filter', 'options_view_filter' );

		if ( 'user_options' == get_option( 'options-view', 'options' ) ) {
			$users = get_users(
				array(
					'orderby' => 'nicename',
					'order' => 'ASC',
				)
			);
			$user_filter = get_option( 'opv_filter_user' );
			?>
			<select name="user_id">
			<?php
			$selected_user = false;
			foreach ( $users as $user ) {
				if ( $user_filter == $user->ID ) {
					?>
					<option value="<?php echo esc_attr( $user->ID ); ?>" selected><?php echo esc_html( $user->display_name . '(User ID:' . $user->ID . ')' ); ?></option>
					<?php
					$selected_user = true;
				} else {
					?>
					<option value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_html( $user->display_name . '(User ID:' . $user->ID . ')' ); ?></option>
					<?php
				}
			}
			if ( ! $selected_user ) {
				?>
				<option value="" selected><?php esc_html_e( 'All users', 'options-view' ); ?></option>
				<?php
			} else {
				?>
				<option value=""><?php esc_html_e( 'All users', 'options-view' ); ?></option>
				<?php
			}
			?>
			</select>
			<?php
		}

		$search_text = get_option( 'optionsview_search_text' );
		if ( ! $search_text ) {
			?>
			<input style="vertical-align: middle;" name="search_text" type="text" value="" placeholder="<?php echo esc_attr__( 'Search' ); ?>">
			<?php
		} else {
			?>
			<input style="vertical-align: middle;" name="search_text" type="text" value="<?php echo esc_attr( $search_text ); ?>">
			<?php
		}

		submit_button( __( 'Filter' ), 'large', 'options-view-filter', false );
		?>
		</form>
		</div>
		<?php
	}

	/** ==================================================
	 * Per page input form
	 *
	 * @since 2.00
	 */
	public function per_page_set() {

		?>
		<div style="margin: 0px; text-align: right;">
			<?php esc_html_e( 'Number of items per page:' ); ?><input type="number" step="1" min="1" max="9999" style="width: 80px;" name="per_page" value="<?php echo esc_attr( get_option( 'opv_per_page', 20 ) ); ?>" form="optionsview_forms" />
			<?php submit_button( __( 'Change' ), 'large', 'per_page_change', false, array( 'form' => 'optionsview_forms' ) ); ?>
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
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'options-view' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'options-view' );

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
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'options-view' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php
	}
}


