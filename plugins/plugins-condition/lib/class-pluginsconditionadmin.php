<?php
/**
 * Plugins Condition
 *
 * @package    Plugins Condition
 * @subpackage PluginsConditionAdmin Management screen
/*
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

$pluginsconditionadmin = new PluginsConditionAdmin();

/** ==================================================
 * Management screen
 */
class PluginsConditionAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.14
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_settings' ) );

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
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
			$this_plugin = 'plugins-condition/pluginscondition.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=PluginsCondition' ) . '">' . __( 'Settings' ) . '</a>';
		}
		return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.01
	 */
	public function plugin_menu() {
		add_options_page( 'PluginsCondition Options', 'Plugins Condition', 'manage_options', 'PluginsCondition', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.01
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$scriptname = admin_url( 'options-general.php?page=PluginsCondition' );
		$plg_cond_notify_interval = get_option( 'plg_cond_notify_interval', 30 );

		?>
		<div class="wrap">
		<h2>Plugins Condition</h2>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'plugins-condition' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>

			<form style="padding:10px;" method="post" action="<?php echo esc_url( $scriptname ); ?>" />
				<?php wp_nonce_field( 'plg_cond_settings', 'pluginscondition_settings' ); ?>
				<div style="margin: 5px; padding: 5px;">
					<h3><?php esc_html_e( 'E-mail notification interval', 'plugins-condition' ); ?></h3>
					<p class="description">
					<?php esc_html_e( 'Specifies the notification interval of the email to notify the plugin status.', 'plugins-condition' ); ?>
					</p>
					<input type="number" name="pc_notify_interval" min="1" max="90" value="<?php echo esc_attr( $plg_cond_notify_interval ); ?>">&nbsp;&nbsp;<?php esc_html_e( 'days', 'plugins-condition' ); ?>
					<?php submit_button( __( 'Save Changes' ), 'large', 'plg-settings-apply', true ); ?>
				</div>
				<hr>
				<div style="margin: 5px; padding: 5px;">
					<h3><?php esc_html_e( 'Remove Cache', 'plugins-condition' ); ?></h3>
					<p class="description">
					<?php esc_html_e( 'It will create a cache in one-day intervals for speedup. Please delete the cache if you want to display the most recent data.', 'plugins-condition' ); ?>
					</p>
					<?php submit_button( __( 'Remove Cache', 'plugins-condition' ), 'large', 'plg-cache-remove', true ); ?>
				</div>
			</form>

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
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'plugins-condition' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'plugins-condition' );

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
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'plugins-condition' ); ?></h3>
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

		if ( isset( $_POST['plg-settings-apply'] ) && ! empty( $_POST['plg-settings-apply'] ) ) {
			if ( check_admin_referer( 'plg_cond_settings', 'pluginscondition_settings' ) ) {
				if ( ! empty( $_POST['pc_notify_interval'] ) ) {
					do_action( 'plugins_condition_notify_cron_stop' );
					update_option( 'plg_cond_notify_interval', intval( $_POST['pc_notify_interval'] ) );
					do_action( 'plugins_condition_notify_cron_start' );
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( __( 'Settings' ) . ' --> ' . __( 'Settings saved.' ) ) . '</li></ul></div>';
				}
			}
		}

		if ( isset( $_POST['plg-cache-remove'] ) && ! empty( $_POST['plg-cache-remove'] ) ) {
			if ( check_admin_referer( 'plg_cond_settings', 'pluginscondition_settings' ) ) {
				$del_cash_count = $this->delete_all_cash();
				if ( 0 < $del_cash_count ) {
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Removed the cache.', 'plugins-condition' ) . '</li></ul></div>';
				} else {
					echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'No Cache', 'plugins-condition' ) . '</li></ul></div>';
				}
			}
		}
	}

	/** ==================================================
	 * Delete all cache
	 *
	 * @return int $del_cash_count(int)
	 * @since 1.00
	 */
	private function delete_all_cash() {

		global $wpdb;
		$search_transients = '%plg_cond_datas_%';
		$del_transients = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT	option_name
				FROM	{$wpdb->prefix}options
				WHERE	option_name LIKE %s
				",
				$search_transients
			)
		);

		$del_cash_count = 0;
		foreach ( $del_transients as $del_transient ) {
			$transient = str_replace( '_transient_', '', $del_transient->option_name );
			$value_del_cash = get_transient( $transient );
			if ( false <> $value_del_cash ) {
				delete_transient( $transient );
				++$del_cash_count;
			}
		}

		return $del_cash_count;
	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 1.07
	 */
	public function register_settings() {

		if ( ! get_option( 'plg_cond_notify_interval' ) ) {
			/* ver 1.06 -> 1.07 */
			do_action( 'plugins_condition_notify_cron_stop' );
			update_option( 'plg_cond_notify_interval', 30 );
			do_action( 'plugins_condition_notify_cron_start' );
		}
	}
}


