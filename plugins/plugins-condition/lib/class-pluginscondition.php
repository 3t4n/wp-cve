<?php
/**
 * Plugins Condition
 *
 * @package    Plugins Condition
 * @subpackage PluginsCondition Main Functions
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

$pluginscondition = new PluginsCondition();

/** ==================================================
 * Main Functions
 */
class PluginsCondition {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_filter( 'plugin_row_meta', array( $this, 'installed_plugins' ), 10, 2 );
		add_action( 'wp_dashboard_setup', array( $this, 'plugins_condition_dashboard_widgets' ) );

		add_action( 'plugins_condition_cron', array( $this, 'plugins_condition_wp_cron' ) );
		register_activation_hook( plugin_dir_path( __DIR__ ) . 'pluginscondition.php', array( $this, 'cron_start' ) );
		register_deactivation_hook( plugin_dir_path( __DIR__ ) . 'pluginscondition.php', array( $this, 'cron_stop' ) );

		add_filter( 'cron_schedules', array( $this, 'plugins_condition_add_intervals' ) );
		add_action( 'plugins_condition_notify_cron', array( $this, 'plugins_condition_notify_wp_cron' ) );
		add_action( 'plugins_condition_notify_cron_start', array( $this, 'notify_cron_start' ) );
		add_action( 'plugins_condition_notify_cron_stop', array( $this, 'notify_cron_stop' ) );
		register_activation_hook( plugin_dir_path( __DIR__ ) . 'pluginscondition.php', array( $this, 'notify_cron_start' ) );
		register_deactivation_hook( plugin_dir_path( __DIR__ ) . 'pluginscondition.php', array( $this, 'notify_cron_stop' ) );
	}

	/** ==================================================
	 * View Installed Plugins
	 *
	 * @param array  $links  links.
	 * @param string $file  file.
	 * @return array $links  links.
	 * @since 1.00
	 */
	public function installed_plugins( $links, $file ) {

		list( $html_ver, $html_date ) = $this->main_func( $file, true );

		$links[] .= $html_ver;
		if ( ! empty( $html_date ) ) {
			$links[] .= $html_date;
		}

		if ( ! wp_next_scheduled( 'plugins_condition_cron' ) ) {
			wp_schedule_event( time() + 86400, 'daily', 'plugins_condition_cron' );
		}

		if ( ! wp_next_scheduled( 'plugins_condition_notify_cron' ) ) {
			$notify_interval_int = get_option( 'plg_cond_notify_interval', 30 ) * 86400;
			$notify_interval_str = 'plc_' . strval( get_option( 'plg_cond_notify_interval', 30 ) ) . 'days';
			wp_schedule_event( time() + $notify_interval_int, $notify_interval_str, 'plugins_condition_notify_cron' );
		}

		if ( function_exists( 'wp_date' ) ) {
			$current = wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
		} else {
			$current = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
		}

		update_option( 'plg_cond_update_date_time', 'Plugins Condition ' . __( 'Last updated' ) . ' : ' . $current );

		return $links;
	}

	/** ==================================================
	 * Plugins Condition wp cron for dashboard
	 *
	 * @since 1.03
	 */
	public function plugins_condition_wp_cron() {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		foreach ( $plugins as $file => $plugin ) {
			list( $html_ver, $html_date ) = $this->main_func( $file, false );
		}

		if ( function_exists( 'wp_date' ) ) {
			$current = wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
		} else {
			$current = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
		}

		update_option( 'plg_cond_update_date_time', 'Plugins Condition ' . __( 'Last updated' ) . ' : ' . $current );
	}

	/** ==================================================
	 * Cron Start
	 *
	 * @return bool $result fail >> false | success >> void
	 * @since 1.03
	 */
	public function cron_start() {

		if ( ! wp_next_scheduled( 'plugins_condition_cron' ) ) {
			$result = wp_schedule_event( time() + 86400, 'daily', 'plugins_condition_cron' );
		} else {
			wp_clear_scheduled_hook( 'plugins_condition_cron' );
			$result = wp_schedule_event( time() + 86400, 'daily', 'plugins_condition_cron' );
		}

		return $result;
	}

	/** ==================================================
	 * Cron Stop
	 *
	 * @since 1.03
	 */
	public function cron_stop() {

		wp_clear_scheduled_hook( 'plugins_condition_cron' );
	}

	/** ==================================================
	 * Plugins Condition wp cron for notify mail
	 *
	 * @since 1.04
	 */
	public function plugins_condition_notify_wp_cron() {

		$adminmail = get_option( 'admin_email' );
		$subject   = 'Plugins Condition - ' . get_option( 'blogname' );

		$plcaution = $this->plugins_condition_caution();
		/* translators: interval days */
		$content   = '<blockquote>' . sprintf( __( 'This email is delivered every %1$s days to the administrator by Plugins Condition.', 'plugins-condition' ), get_option( 'plg_cond_notify_interval', 30 ) ) . '</blockquote>';
		$content  .= '<strong>' . get_option( 'plg_cond_update_date_time' ) . '</strong>' . $plcaution;

		/* Mail Use HTML-Mails */
		add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );

		wp_mail( $adminmail, $subject, $content );

		/* Mail default */
		remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
	}

	/** ==================================================
	 * Mail content type
	 *
	 * @return string 'text/html'
	 * @since 1.04
	 */
	public function set_html_content_type() {
		return 'text/html';
	}

	/** ==================================================
	 * Custom Intervals
	 *
	 * @param string $schedules  schedules.
	 * @return array $schedules
	 * @since 1.04
	 */
	public function plugins_condition_add_intervals( $schedules ) {

		$notify_interval_int = get_option( 'plg_cond_notify_interval', 30 ) * 86400;
		$notify_interval_str = 'plc_' . strval( get_option( 'plg_cond_notify_interval', 30 ) ) . 'days';

		$schedules[ $notify_interval_str ] = array(
			'interval' => $notify_interval_int,
			'display'  => $notify_interval_str,
		);

		return $schedules;
	}

	/** ==================================================
	 * Notify Cron Start
	 *
	 * @return bool $result fail >> false | success >> void
	 * @since 1.04
	 */
	public function notify_cron_start() {

		$notify_interval_int = get_option( 'plg_cond_notify_interval', 30 ) * 86400;
		$notify_interval_str = 'plc_' . strval( get_option( 'plg_cond_notify_interval', 30 ) ) . 'days';

		if ( ! wp_next_scheduled( 'plugins_condition_notify_cron' ) ) {
			$result = wp_schedule_event( time() + $notify_interval_int, $notify_interval_str, 'plugins_condition_notify_cron' );
		} else {
			wp_clear_scheduled_hook( 'plugins_condition_notify_cron' );
			$result = wp_schedule_event( time() + $notify_interval_int, $notify_interval_str, 'plugins_condition_notify_cron' );
		}

		return $result;
	}

	/** ==================================================
	 * Notify Cron Stop
	 *
	 * @since 1.04
	 */
	public function notify_cron_stop() {

		wp_clear_scheduled_hook( 'plugins_condition_notify_cron' );
	}

	/** ==================================================
	 * Main
	 *
	 * @param string $file  file.
	 * @param bool   $installed_plugin  installed_plugin.
	 * @return array $html_ver, $html_date
	 * @since 1.00
	 */
	private function main_func( $file, $installed_plugin ) {

		$plugin_name = wp_basename( $file );
		$slug = untrailingslashit( str_replace( $plugin_name, '', $file ) );

		$call_apis = array();
		if ( ! empty( $slug ) ) {
			if ( $installed_plugin && get_transient( 'plg_cond_datas_' . $slug . '_' . get_locale() ) ) {
				/* Get cache */
				$call_apis = get_transient( 'plg_cond_datas_' . $slug . '_' . get_locale() );
			} else {
				if ( ! function_exists( 'plugins_api' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				}
				/* Call API */
				$call_api = plugins_api(
					'plugin_information',
					array(
						'slug' => $slug,
						'fields' => array(
							'short_description' => false,
							'description' => false,
							'sections' => false,
							'tested' => true,
							'requires' => false,
							'rating' => false,
							'ratings' => false,
							'downloaded' => false,
							'downloadlink' => false,
							'last_updated' => true,
							'added' => false,
							'tags' => false,
							'compatibility' => false,
							'homepage' => false,
							'versions' => false,
							'donate_link' => false,
							'reviews' => false,
							'banners' => false,
							'icons' => false,
							'active_installs' => false,
							'group' => false,
							'contributors' => false,
						),
					)
				);
				if ( is_wp_error( $call_api ) ) {
					$dummy = 0; /* Skip */
				} else {
					$call_apis = array(
						'tested' => $call_api->tested,
						'last_updated' => $call_api->last_updated,
					);

					/* Set cache */
					set_transient( 'plg_cond_datas_' . $slug . '_' . get_locale(), $call_apis, 86400 );
				}
			}
		}

		$html_ver  = null;
		$html_date = null;
		$caution   = null;
		if ( ! empty( $call_apis ) ) {
			$wp_ver = get_bloginfo( 'version' );
			$pg_ver = $call_apis['tested'];
			if ( $pg_ver === $wp_ver ) {
				$html_ver = '<span style="color: green;">' . $pg_ver . '</span>';
			} else {
				$html_ver = '<span style="color: red;">' . $pg_ver . '</span>';
				$caution .= '<span style="color: red;">' . $pg_ver . '</span>';
			}
			$pg_update_time = strtotime( $call_apis['last_updated'] );
			$now            = time();
			$time_lag       = $now - $pg_update_time;
			$time_lag_date  = $time_lag / 86400;
			if ( 1 > $time_lag_date ) {
				$color_date = 'green';
				$pg_date    = __( 'within 24 hours', 'plugins-condition' );
			} else if ( 1 <= $time_lag_date && 7 > $time_lag_date ) {
				$color_date = 'green';
				$day        = floor( $time_lag_date );
				if ( 1 == $day ) {
					/* translators: day */
					$pg_date = sprintf( __( '%1$d day ago', 'plugins-condition' ), 1 );
				} else {
					/* translators: days */
					$pg_date = sprintf( __( '%1$d days ago', 'plugins-condition' ), $day );
				}
			} else if ( 7 <= $time_lag_date && 30 > $time_lag_date ) {
				$color_date = 'green';
				$week       = floor( $time_lag_date / 7 );
				if ( 1 == $week ) {
					/* translators: week */
					$pg_date = sprintf( __( '%1$d week ago', 'plugins-condition' ), 1 );
				} else {
					/* translators: weeks */
					$pg_date = sprintf( __( '%1$d weeks ago', 'plugins-condition' ), $week );
				}
			} else if ( 30 <= $time_lag_date && 365 > $time_lag_date ) {
				$color_date = 'green';
				$month      = floor( $time_lag_date / 30 );
				if ( 1 == $month ) {
					/* translators: month */
					$pg_date = sprintf( __( '%1$d month ago', 'plugins-condition' ), 1 );
				} else {
					/* translators: months */
					$pg_date = sprintf( __( '%1$d months ago', 'plugins-condition' ), $month );
				}
			} else {
				$color_date = 'red';
				$year       = floor( $time_lag_date / 365 );
				if ( 1 == $year ) {
					/* translators: year */
					$pg_date = sprintf( __( '%1$d year ago', 'plugins-condition' ), 1 );
				} else {
					/* translators: years */
					$pg_date = sprintf( __( '%1$d years ago', 'plugins-condition' ), $year );
				}
				$caution .= ' <span style="color: red;">' . $pg_date . '</span>';
			}
			$html_date = '<span style="color: ' . $color_date . ';">' . $pg_date . '</span>';
		} else {
			$html_ver = '<span style="color: red;">' . __( 'unofficial', 'plugins-condition' ) . '</span>';
			$caution .= $html_ver;
		}

		$pg_name = $this->plugin_name( $file );
		if ( $caution ) {
			$text = '<div>' . $pg_name . ' : ' . $caution . '</div>';
			update_option( 'plg_cond_text_' . $pg_name, $text );
		} else {
			delete_option( 'plg_cond_text_' . $pg_name );
		}

		return array( $html_ver, $html_date );
	}

	/** ==================================================
	 * Plugins Condition for Dashboard
	 *
	 * @since 1.00
	 */
	public function plugins_condition_dashboard_widgets() {

		if ( current_user_can( 'manage_options' ) ) {
			global $wp_meta_boxes;
			wp_add_dashboard_widget( 'custom_help_widget', get_option( 'plg_cond_update_date_time' ), array( $this, 'dashboard_text' ) );
		}
	}

	/** ==================================================
	 * Dashboard text
	 *
	 * @since 1.00
	 */
	public function dashboard_text() {

		$screen = get_current_screen();
		if ( 'dashboard' === $screen->id ) {

			$plcaution = $this->plugins_condition_caution();

			if ( is_multisite() ) {
				$installed_plugin_url = network_admin_url( 'plugins.php' );
			} else {
				$installed_plugin_url = admin_url( 'plugins.php' );
			}
			$installed_plugin_html = '<a href="' . $installed_plugin_url . '" style="text-decoration: none; word-break: break-all;">' . __( 'Installed Plugins' ) . '</a>';

			?>
			<h3>
			<?php
			/* translators: %s: Installed Plugins */
			echo wp_kses_post( sprintf( __( 'Please read %s for the latest information.', 'plugins-condition' ), $installed_plugin_html ) );
			?>
			</h3>
			<?php
			echo wp_kses_post( $plcaution );
		}
	}

	/** ==================================================
	 * Plugins Condition Caution
	 *
	 * @return string $plcaution  plcaution.
	 * @since 1.04
	 */
	private function plugins_condition_caution() {

		global $wpdb;
		$option_names = array();
		$wp_options = $wpdb->get_results(
			"
			SELECT option_name
			FROM {$wpdb->prefix}options
			WHERE option_name LIKE '%%plg_cond_text_%%'
			"
		);

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins   = get_plugins();
		$plcaution = null;
		if ( ! empty( $wp_options ) ) {
			foreach ( $wp_options as $wp_option ) {
				$option_names[] = $wp_option->option_name;
			}
			foreach ( $option_names as $option_name ) {
				$pl_name = str_replace( 'plg_cond_text_', '', $option_name );
				$is_plg  = false;
				foreach ( $plugins as $file => $plugin ) {
					if ( $pl_name === $plugin['Name'] ) {
						$is_plg = true;
					}
				}
				if ( $is_plg ) {
					$plcaution .= get_option( $option_name );
				} else {
					delete_option( $option_name );
				}
			}
		}

		return $plcaution;
	}

	/** ==================================================
	 * Plugin Name
	 *
	 * @param string $file  file.
	 * @return string $plugin_name  plugin_name.
	 * @since 1.00
	 */
	private function plugin_name( $file ) {

		$this_plugin_path = untrailingslashit( wp_normalize_path( plugin_dir_path( __DIR__ ) ) );
		$this_plugin_name = wp_basename( $this_plugin_path );
		$plugin_path = str_replace( $this_plugin_name, '', $this_plugin_path );

		$plugin_datas = get_file_data(
			$plugin_path . $file,
			array(
				'name'    => 'Plugin Name',
				'version' => 'Version',
			)
		);

		$plugin_name    = null;
		$plugin_ver_num = null;
		if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) &&
			array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
			$plugin_name    = $plugin_datas['name'];
			$plugin_ver_num = $plugin_datas['version'];
			$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		}

		return $plugin_name;
	}
}


