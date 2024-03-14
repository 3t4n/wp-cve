<?php
/*
Plugin Name: Stetic
Plugin URI: https://www.stetic.com/
Description: Adds real-time Web Analytics from Stetic with event tracking of all important actions to Wordpress. It comes with a dashboard to show you the important reports and numbers.
Author: Stetic
Version: 1.0.12
Author URI: https://www.stetic.com/
*/


if(!class_exists('Stetic'))
{
	class Stetic
	{
		
		static private $classobj = null;
		static private $tab = null;
		
		/**
		 * construct
		 *
		 * @uses
		 * @access public
		 * @since 0.0.1
		 * @return void
		 */
		public function __construct()
		{
			if(is_admin())
			{
				add_action( 'admin_menu', array( $this, 'add_page_to_navi' ) );
				
				if ( empty($GLOBALS['pagenow']) or ( !empty($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'index.php' ) && current_user_can('edit_dashboard') )
				{
					add_action(
						'wp_dashboard_setup',
						array(
							__CLASS__,
							'add_dashboard_stats'
						)
					);
				}
			}
		}
		
		
		/**
		 * points the class
		 *
		 * @access public
		 * @since 0.0.1
		 * @return object
		 */
		public static function get_object() {

			if ( NULL === self :: $classobj )
				self :: $classobj = new self;

			return self :: $classobj;
		}
		
		/**
		 * Installation hook, will be called on plugin-installation
		 *
		 */
		public static function install()
		{
			global $wpdb;
			//
		}

		/**
		 * Installation hook, will be called on plugin-uninstall
		 *
		 */
		public static function uninstall()
		{
			global $wpdb;
			//
		}

		/**
		 * Ads navigation item
		 *
		 */
		public function add_page_to_navi()
		{
			global $wpdb;
			if ( function_exists('add_options_page') && current_user_can('edit_dashboard') )
			{
				add_options_page('stetic', 'Stetic', 'manage_options', 'stetic/stetic.php&tab=settings', array('Stetic', 'config_page'));
			}
			
			if ( function_exists('add_menu_page') && current_user_can('edit_dashboard') )
			{
				add_menu_page('stetic', 'Stetic', 'read', __FILE__, array( 'Stetic', 'stats_page' ), plugins_url('stetic/img/icon.png'));
			}
			
			
		}

		public static function add_config_page_to_plugins($links, $file)
		{
			if($file ==  plugin_basename(__FILE__))
			{
				$settings_link = '<a href="options-general.php?page=stetic/stetic.php&tab=settings">' . __('Settings') . '</a>';
				array_push( $links, $settings_link );
			}
			return $links;
		}
		
		public static function the_tabs()
		{
			?>
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab<?php echo (!isset($_GET['tab']) || !$_GET['tab'] || $_GET['tab'] == "stats") ? " nav-tab-active" : ""; ?>" href="admin.php?page=stetic/stetic.php&amp;tab=stats">Stats</a>
				<a class="nav-tab<?php echo (isset($_GET['tab']) && $_GET['tab'] == "settings") ? " nav-tab-active" : ""; ?>" href="options-general.php?page=stetic/stetic.php&amp;tab=settings"><?php echo __('Settings'); ?></a>
			</h2>
			<?php
		}
		
		public static function config_page()
		{
			self::$tab = 'settings';
			self::stats_page();
		}
		
		public static function stats_page()
		{
			if( ! current_user_can('edit_dashboard') )
			{
				return;
			}
			
			if( !empty($_POST) && isset($_POST['submit']) )
			{
				check_admin_referer( '_stetic_settings__nonce' );
				$nonce = $_REQUEST['_wpnonce'];
				if ( !wp_verify_nonce( $nonce, '_stetic_settings__nonce' ) )
				{
					exit;
				}
				$options['stetic_token'] = sanitize_text_field( wp_unslash( $_POST['stetic_token'] ) ); //$_POST['stetic_token'];
				$options['stetic_api_key'] = sanitize_text_field( wp_unslash( $_POST['stetic_api_key'] ) ); //$_POST['stetic_api_key'];
				$options['stetic_show_counter'] = isset($_POST['stetic_show_counter']) && $_POST['stetic_show_counter'] == "1" ? "1" : "0";
				$options['stetic_disable_tracking'] = isset($_POST['stetic_disable_tracking']) && $_POST['stetic_disable_tracking'] == "1" ? "1" : "0";
				update_option('stetic', $options);
			}
			
			$options  = get_option('stetic');
			
			if( (isset($_GET['tab']) && $_GET['tab'] == 'settings') || self::$tab == 'settings' || !$options['stetic_api_key'])
			{
				?>
				<div class="wrap">
					<h2>Stetic Configuration</h2>
					<?php self::the_tabs(); ?>
					<form action="<?php echo esc_url( admin_url( 'options-general.php?page=stetic/stetic.php&amp;tab=settings' ) ); ?>" method="post" id="stetic-conf">
					<?php wp_nonce_field( '_stetic_settings__nonce' ); ?>
						<table class="form-table">
							<tr>
								<td colspan="2">
									<label for="stetic_project_id">Stetic Project Token:</label><br/>
									<input size="50" type="text" id="stetic_token" name="stetic_token" <?php echo 'value="' .  (isset($options['stetic_token']) ? esc_attr($options['stetic_token']) : '') . '" '; ?>/><br/>
									<small>Please enter your Stetic project token from your <a href="https://www.stetic.com/conf/project-settings/" target="_blank">project settings page</a>.</small>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="stetic_api_key">Stetic API-Key:</label><br/>
									<input size="50" type="text" id="stetic_api_key" name="stetic_api_key" <?php echo 'value="' .  (isset($options['stetic_api_key']) ? esc_attr($options['stetic_api_key']) : '') . '" '; ?>/><br/>
									<small>Please enter your Stetic API-Key from your <a href="https://www.stetic.com/conf/project-settings/" target="_blank">project settings page</a>.</small>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="stetic_hide_counter">Counter Visibility:</label><br/>
									<input size="50" type="checkbox" id="stetic_show_counter" name="stetic_show_counter" value="1" <?php echo (isset($options['stetic_show_counter']) && $options['stetic_show_counter'] == "1") ? 'checked="checked"' : ""; ?>/> Show Counter<br/>
									<small>Please select this option if you want to display a counter and have chosen a counter graphic in your project settings.</small>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="stetic_disable_tracking">Tracking Code:</label><br/>
									<input type="checkbox" id="stetic_disable_tracking" name="stetic_disable_tracking" value="1" <?php echo (isset($options['stetic_disable_tracking']) && $options['stetic_disable_tracking'] == "1") ? 'checked="checked"' : ""; ?>/> Disable Tracking<br/>
									<small>Please only choose this option if you allready have the tracking code installed manually.</small>
								</td>
							</tr>
						</table>
						<br/>
						<span class="submit" style="border: 0;"><input type="submit" name="submit" value="Save Settings" /></span>
					</form>
					<br/><br/>
					</div>
				<?php
			}
			else
			{
				/* Get plugin info */
				$plugin_info = get_plugin_data(__FILE__);

				wp_register_script(
					'google_jsapi',
					'https://www.google.com/jsapi',
					false
				);
				wp_enqueue_script('google_jsapi');
				
				wp_register_script(
					'stetic',
					plugins_url('js/stetic.min.js', __FILE__),
					array('jquery'),
					$plugin_info['Version']
				);
				wp_enqueue_script('stetic');
				
				wp_enqueue_style('stetic-css',
								plugins_url('css/stetic.css', __FILE__),
								false,
								$plugin_info['Version'],
								false);
				?>
				<div class="wrap">
					<h2>Stetic</h2>
					<?php self::the_tabs(); ?>
					<div id="contentstetic">
						<h3><?php _e('Day Performance'); ?></h3>
						<div id="chart_visitor_div" style="height: 180px; width: 100%;"></div>
						<br>
				
						<table class="widefat" id="fs_overview_stats">
							<thead>
								<tr>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					
						<br>
						<h3><?php _e('Performance last 31 days'); ?></h3>
						<div id="chart_visitor_div_last31" style="height: 180px; width: 100%;"></div>
					
						<br>
					
						<div id="fs-box-row">
						</div>

						<h3><?php _e('Performance this year'); ?></h3>
						<div id="chart_visitor_div_year" style="height: 180px; width: 100%;"></div>

						<br>
					
						<div>
							<table class="widefat" id="fs-visitor-log">
								<thead><tr><th colspan="3"><?php _e('Last 25 Visitors'); ?></th></tr></thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<script type="text/javascript">
				jQuery(document).ready(function() { 
					fs = new fourStats('<?php echo esc_html(isset($options['stetic_project_id']) ? $options['stetic_project_id'] : ""); ?>', '<?php echo esc_html($options['stetic_token']); ?>', '<?php echo esc_html($options['stetic_api_key']); ?>', '', '<?php echo date("D, d M Y H:i:s"); ?>');
					fs.statsPage();
				});				
				</script>
				<?php
			}
		}
		
		/**
		* Initialisierung des Dashboard-Chart
		*
		* @since   2.0
		* @change  2.0
		*/
		public static function add_dashboard_stats()
		{
			if ( !current_user_can('edit_dashboard') )
			{
				return;
			}

			/* Widget hinzufügen */
			wp_add_dashboard_widget(
				'fs_widget',
				'Stetic',
				array(
					__CLASS__,
					'show_dashboard_stats'
				)
			);

			/* Get plugin info */
			$plugin_info = get_plugin_data(__FILE__);

			wp_register_script(
				'google_jsapi',
				'https://www.google.com/jsapi',
				false
			);
			wp_enqueue_script('google_jsapi');
			
			wp_register_script(
				'stetic',
				plugins_url('js/stetic.min.js', __FILE__),
				array('jquery'),
				$plugin_info['Version']
			);
			wp_enqueue_script('stetic');
			
			wp_enqueue_style('stetic-css',
							plugins_url('css/dashboard.css', __FILE__),
							false,
							$plugin_info['Version'],
							false);
			
		}
		

		/**
		* Ausgabe des Dashboard-Stats
		*
		* @since   2.0
		* @change  2.0
		*/
		public static function show_dashboard_stats()
		{
			if ( !current_user_can('edit_dashboard') )
			{
				return;
			}
			$options  = get_option('stetic');
			?>
			<h3><?php _e('Day Performance'); ?></h3>
			<div id="chart_visitor_div" style="height: 120px; width: 100%;"></div>
			<br>
			<div id="fs_dashboard_stats">
			</div>
			<p class="textright">
				<a class="button" href="index.php?page=stetic/stetic.php">View All</a>
			</p>
			<script type="text/javascript">
			jQuery(document).ready(function() { 
				fs = new fourStats('<?php echo isset($options['stetic_project_id']) ? esc_html($options['stetic_project_id']) : ''; ?>', '<?php echo isset($options['stetic_token']) ? esc_html($options['stetic_token']) : ''; ?>', '<?php echo isset($options['stetic_api_key']) ? esc_html($options['stetic_api_key']) : ''; ?>', '', '<?php echo date("D, d M Y H:i:s"); ?>');
				fs.dashBoard();
			});				
			</script>
			<?php
		}
		
		public static function tracking_code_header()
		{
			$options  = get_option('stetic');
			if( ( ( isset($options['stetic_project_id']) && $options['stetic_project_id'] ) || ( isset($options['stetic_token']) && $options['stetic_token'] ) ) && $options['stetic_disable_tracking'] != 1 )
			{
				$id_key = ( isset($options['stetic_project_id']) && $options['stetic_project_id'] ) ? "siteId" : "token";
				$id_string = ( isset($options['stetic_project_id']) && $options['stetic_project_id'] ) ? $options['stetic_project_id'] : $options['stetic_token'];
				
				if( !isset($options['stetic_show_counter']) || $options['stetic_show_counter'] != '1' )
				{
					?><script type="text/javascript">
var _fss=_fss||{}; _fss.<?php echo esc_html($id_key); ?>='<?php echo esc_html($id_string); ?>';
(function(){var e="stetic",a=window,c=["track","identify","config","set","unset","register","unregister","increment","alias"],b=function(){var d=0,f=this;for(f._fs=[],d=0;c.length>d;d++){(function(j){f[j]=function(){return f._fs.push([j].concat(Array.prototype.slice.call(arguments,0))),f}})(c[d])}};a[e]=a[e]||new b;a.fourstats=a.fourstats||new b;var i=document;var h=i.createElement("script");h.type="text/javascript";h.async=true;h.src="//stetic.com/t.js";var g=i.getElementsByTagName("script")[0];g.parentNode.insertBefore(h,g)})();
</script><?php
				}
			}
		}

		public static function tracking_code_footer()
		{
			$options  = get_option('stetic');
			
			if( ( ( isset($options['stetic_project_id']) && $options['stetic_project_id'] ) 
					|| ( isset($options['stetic_token']) && $options['stetic_token'] ) ) 
							&& $options['stetic_disable_tracking'] != 1 )
			{
				$id = ( isset($options['stetic_project_id']) && $options['stetic_project_id'] ) ? $options['stetic_project_id'] : $options['stetic_token'];
				
				if( isset($options['stetic_show_counter']) && $options['stetic_show_counter'] == '1' )
				{
					?><script type="text/javascript">document.write(unescape('%3Cscr' + 'ipt src="'+'http'+(document.location.protocol=='https:'?'s':'')+'://stetic.com/de/counter?id=<?php echo esc_html($id); ?>" type="text/javascript"%3E%3C/script%3E'));</script>
<noscript><div><img src="http://stetic.com/de/stats?id=<?php echo esc_html($id); ?>" style="border: none;" alt="Stetic" /></div></noscript><?php
				}
			}
		}
	}
}

if(is_admin()) {
	register_activation_hook( __FILE__, array('Stetic', 'install'));
	register_deactivation_hook( __FILE__, array('Stetic', 'uninstall'));
	add_action( 'plugins_loaded', array('Stetic', 'get_object') );
	add_filter( 'plugin_action_links', array( 'Stetic', 'add_config_page_to_plugins'), 11, 2 );
} else {
	add_action('wp_head', array('Stetic', 'tracking_code_header'));
	add_action('wp_footer', array('Stetic', 'tracking_code_footer'));
}
