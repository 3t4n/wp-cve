<?php
/*
Plugin Name: Bot Block
Description: Redirects or blocks spam traffic from bots, or any other site.
Version: 2.6
Author: Sean & Ricky Dawn
Author URI: http://www.webresultsdirect.com
License: GPL v3

Copyright (C) 2015 Web Results Direct - ricky@webresultsdirect.com
All rights reserved.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

define( "BOT_BLOCK_NAME", "Bot Block" );
define( "BOT_BLOCK_TAGLINE", "Blocks spam traffic from reaching your site." );
define( "BOT_BLOCK_URL", "http://www.webresultsdirect.com" );

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

//Run when plugin is activated
register_activation_hook( __FILE__, 'bot_block_activate' );

function bot_block_activate()
{
	$bot_block = new Bot_Block();
	$bot_block->bot_block_activate();
}

//Initialize class construct
add_action( 'plugins_loaded', 'bot_block_run' );

function bot_block_run()
{
	$bot_block = new Bot_Block();
}

//The class
class Bot_Block
{
	function __construct() 
	{
		//Variables
		global $wpdb;
		
		$this->master_table_name = $wpdb->prefix . 'bot_block_block_list';
		$this->log_table_name = $wpdb->prefix . 'bot_block_log';
		
		//Run when plugin is deactivated
		register_deactivation_hook( __FILE__, array( $this, 'bot_block_deactivate' ) );

		//Call function to check the referrer
		add_action( 'parse_request', array( $this, 'bot_block_parse' ) );
		
		//Register settings in admin area
		if ( is_admin() )
		{
			add_action( 'admin_init', array( $this, 'register_admin_settings' ) );
		}
		
		//Call function to add the admin menu link
		add_action( 'admin_menu', array( $this, 'show_bot_block_options' ) );

		//Call function to add the admin menu link
		$plugin = plugin_basename( __FILE__ );
		add_filter( "plugin_action_links_$plugin", array( $this, 'bot_block_link' ) );
		
		//Call the cron update function
		add_action( 'bot_block_cron', array( $this, 'update_master_list') );
		
		//Call the send stats function
		add_action( 'bot_block_send_stats', array( $this, 'send_stats' ) );
	}
	
	function bot_block_activate()
	{
		//Assign initial options
		$activate_options = array
		(
			'subdomains'=> 'on',
			'url'=> 'http://google.com',
			'domains'=> '',
			'update_url'=> 'http://botblock.rickydawn.com/block_list.php',
			'last_update_time'=> '',
			'blocked_count'=> 0,
			'send_stats' => 'on'
		);
		
		add_option( 'bot_block', $activate_options );
		
		//Create the master table
		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $this->master_table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		domain text NOT NULL,
		UNIQUE KEY id (id)
		) $charset_collate;";
		
		dbDelta( $sql );
		
		//Create the log table
		$sql = "CREATE TABLE IF NOT EXISTS $this->log_table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		domain text NOT NULL,
		UNIQUE KEY id (id)
		) $charset_collate;";
		
		dbDelta( $sql );
		
		//Get master list
		$this->update_master_list();
		
		//Set update schedule
		if( wp_next_scheduled( 'bot_block_cron' ) == false )
		{
			wp_schedule_event( time(), 'daily', 'bot_block_cron' );
		}
		
		//Set post stats schedule
		if( wp_next_scheduled( 'bot_block_send_stats' ) == false )
		{
			wp_schedule_event( time(), 'daily', 'bot_block_send_stats' );
		}
	}
	
	//On deactivation
	function bot_block_deactivate()
	{
		//Remove options and variables
		delete_option( 'bot_block' );
		delete_option( 'bot_block_master_table' );
		delete_option( 'bot_block_log' );
		
		//Delete database tables
		global $wpdb;
		
		$wpdb->query( "DROP TABLE IF EXISTS $this->master_table_name" );
		$wpdb->query( "DROP TABLE IF EXISTS $this->log_table_name" );
		
		//Remove cron update
		wp_clear_scheduled_hook( 'bot_block_cron' );
		wp_clear_scheduled_hook( 'bot_block_send_stats' );
	}
	
	//Update master list from server
	function update_master_list()
	{
		//Get updated domain list
		$data = $this->get_domain_list();
		
		//Import the urls
		$import = $this->import_urls( $data );
		
		//If import was successful update update time
		if( $import === true )
		{
			$this->update_update_time();
		}
	}
	
	//Check referrer function
	function bot_block_parse()
	{
		//Get the options for the plugin
		$options = get_option( 'bot_block' );
		
		//See if the request was from another site
		if( isset( $_SERVER['HTTP_REFERER'] ) )
		{
			//Split the URL into it's components
			$referrer = parse_url( $_SERVER['HTTP_REFERER'] );
			
			//Trim the components
			$referrer = array_map( 'trim', $referrer );
			
			//Get the domain name
			$referrer = $referrer['host'];
			
			//Get the block list
			$list = $this->create_block_list();
			
			//Loop through all the blocked domains
			foreach( $list as $site )
			{
				//Trim the domain
				$site = trim( $site );
				
				//Set the prefix for domains that aren't sub domains
				$prefix = 'www';
				
				//Split domain into smaller components
				$domainParts = explode( ".", $referrer );
				
				//See if the domain that referred is in the current block url
				if ( $site == '' || !$site ) {
					   $pos = false;
					} else {
					   $pos = strpos( $referrer, $site );
					}
				
				//See if block subdomains is checked
				if( isset( $options['subdomains'] ) )
				{
					//Check to see if the domain was the current blocked site and if the prefix is not www
					if( $pos !== false && $domainParts[0] != $prefix )
					{
						//Log spam
						$this->log_spam( $site );
						
						//Call the redirect function to see where to send the user
						$this->bot_block_redirect();
						exit;
					}
				}
				
				//See if the domain was the current site blocked and the prefix is www
				if( $pos !== false && $domainParts[0] == $prefix )
				{
					//Log spam
					$this->log_spam( $site );
					
					//Call the redirect function to see where to send the user
					$this->bot_block_redirect();
					exit;
				}
			}
		}
	}
	
	//Register Admin settings
	function register_admin_settings() 
	{
		register_setting( 'bot_block_options', 'bot_block' );
	}
	
	//Add Admin options link
	function show_bot_block_options() 
	{
		add_options_page( 'Bot Block Options', 'Bot Block Options', 'manage_options', 'bot_block', array( $this, 'bot_block_options' ) );
	}
	
	//Add settings link on plugin page
	function bot_block_link( $links ) 
	{
		$settings_link = '<a href="options-general.php?page=bot_block">Settings</a>';
		array_unshift( $links, $settings_link );
		
		return $links;
	}
	
	//Log the spam
	function log_spam( $site )
	{
		global $wpdb;
		
		if( !$this->does_log_table_exist() )
		{
			if( !get_option( 'bot_block_log' ) )
			{
				$log = array();
				
				$log[] = $site;
				add_option( 'bot_block_log', $log );
			}
			else
			{
				$log = get_option( 'bot_block_log' );
				$log[] = $site;
				
				update_option( 'bot_block_log', $log );
			}
		}
		else
		{
			$wpdb->insert
			(
				$this->log_table_name,
				array
				(
					'domain'=> $site
				),
				array
				(
					'%s'
				)
			);
		}
		
		$options = get_option( 'bot_block' );
		$options['blocked_count']++;
		update_option( 'bot_block', $options );
	}
	
	//Get stats
	function get_stats()
	{
		$log = array();
		
		//Get all of the log data
		if( !$this->does_log_table_exist() )
		{
			$data = get_option( 'bot_block_log' );
			
			foreach( $data as $site )
			{
				if( array_key_exists( $site, $log ) )
				{
					$log[$site]++;
				}
				else
				{
					$log[$site] = 1;
				}
			}
		}
		else
		{
			global $wpdb;
			
			$data = $wpdb->get_results( "SELECT domain FROM $this->log_table_name", 'ARRAY_A' );
			
			foreach( $data as $site )
			{
				if( array_key_exists( $site['domain'], $log ) )
				{
					$log[$site['domain']]++;
				}
				else
				{
					$log[$site['domain']] = 1;
				}
			}
		}
		
		if( count( $log ) >= 2 )
		{
			//$log = arsort( $log, SORT_NUMERIC );
			//error_log( print_r($log, true) );
		}
		
		$log = json_encode($log);
		
		return $log;
	}
	
	//Send stats
	function send_stats()
	{
		//Get the plugin options
		$options = get_option( 'bot_block' );
		
		//See if send stats is enabled
		if( !isset( $options['send_stats'] ) )
		{
			return;
		}
		
		//Get the stats
		$data = $this->get_stats();
		
		$url = 'http://botblock.rickydawn.com/bb_stats_upload.php';
		$myvars = 'data=' . $data;
		
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		$response = curl_exec( $ch );
		
		//If the data was successfully sent
		if( $response === true )
		{
			//If the data as successfully sent then clear the log table
			if( !$this->does_log_table_exist() )
			{
				//Get the plugin options
				$options = get_option( 'bot_block_log' );
				
				//Clear the log
				$options = array();
				
				//Update the log with empty array
				update_option( 'bot_block_log', $options );
			}
			else
			{
				global $wpdb;
				
				//Truncate the table
				$wpdb->query("TRUNCATE TABLE $this->log_table_name");
			}
		}
	}
	
	//See if master table exists
	function does_master_table_exist()
	{
		global $wpdb;
		
		if( $wpdb->get_var("SHOW TABLES LIKE '$this->master_table_name'") != $this->master_table_name )
		{
			return false;
		}
		
		return true;
	}
	
	//See if log table exists
	function does_log_table_exist()
	{
		global $wpdb;
		
		if( $wpdb->get_var("SHOW TABLES LIKE '$this->log_table_name'") != $this->log_table_name )
		{
			return false;
		}
		
		return true;
	}
	
	//Redirect function
	function bot_block_redirect()
	{
		//Get the plugin options
		$options = get_option( 'bot_block' );
		
		//See if redirect is set
		if( isset( $options['redirect'] ) )
		{
			//If not then 403 error
			wp_die( 'Nope', 'Nope', array( 'response'=> 403 ) );
			exit;
		}
		else
		{
			//If it is then redirect to the chosen URL
			wp_redirect( $options['url'] );
			exit;
		}
	}
	
	//Get the domain list from server
	function get_domain_list()
	{
		//Get main options
		$options = get_option( 'bot_block' );
		
		//Get the web page
		$ch = curl_init();
		$timeout = 5;
		
		curl_setopt( $ch, CURLOPT_URL, $options['update_url'] );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		
		$data = curl_exec( $ch );
		curl_close( $ch );
		
		//See if fetch was successful
		if( $data === false )
		{
			return null;
		}
		
		//Convert html to new line
		$breaks = array( "<br />", "<br>", "<br/>" );
		$data = str_ireplace( $breaks, "\r\n", $data );
		
		return $data;
	}
	
	//Import URLS into database
	function import_urls( $data )
	{
		//See if there is data to import
		if( !$data || $data === null )
		{
			return false;
		}
		
		//Create the array
		$urls = array_filter( explode( "\n", $data ), 'strlen' );
		
		//See if table exists		
		if( !$this->does_master_table_exist() )
		{
			//Table not created so store domain list in server array
			if( !get_option( 'bot_block_master_table' ) )
			{
				add_option( 'bot_block_master_table', $urls );
			}
			else
			{
				update_option( 'bot_block_master_table', $urls );
			}
			
			return true;
		}
		else
		{
			//Truncate table to make sure its clear
			global $wpdb;
			
			$wpdb->query("TRUNCATE TABLE $this->master_table_name");
			 
			//Import into database
			foreach( $urls as $url )
			{
				$wpdb->insert
				(
					$this->master_table_name,
					array
					(
						'domain'=> $url
					),
					array
					(
						'%s'
					)
				);
			}
			
			return true;
		}
	}
	
	//Count all rows in master table
	function count_master_table()
	{
		if( !$this->does_master_table_exist() )
		{
			return count( get_option( 'bot_block_master_table' ) );
		}
		else
		{
			global $wpdb;
		
			$wpdb->get_results( "SELECT * FROM `$this->master_table_name`" );
		
			return empty($wpdb->num_rows) ? '<span style="color: red;">No sites in database.</span>' : $wpdb->num_rows;
		}
	}
	
	//Update the time the plugin last updated
	function update_update_time()
	{
		$options = get_option( 'bot_block' );
		$options['last_update_time'] = time();
		update_option( 'bot_block', $options );
	}
	
	//Get the last time that the master table was updated
	function get_last_update_time()
	{
		//Get main options
		$options = get_option( 'bot_block' );
		
		return date( "d/m/Y H:i", $options['last_update_time'] );
	}
	
	//Create the block list
	function create_block_list()
	{
		//Get main options
		$options = get_option( 'bot_block' );
		
		//Create the custom list
		$custom_list = $options['domains'];
		$custom_list = array_filter( explode( "\n", $custom_list ), 'strlen' );
		
		//See if master table exists
		if( !$this->does_master_table_exist() )
		{
			return get_option( 'bot_block_master_table' );
		}
		else
		{
			//Get the master list
			global $wpdb;
			
			$master_list = $wpdb->get_results( "SELECT domain FROM $this->master_table_name", 'ARRAY_A' );
			
			//Merge lists
			if( !empty( $custom_list ) )
			{
				foreach( $master_list as $site )
				{
					array_push( $custom_list, $site['domain'] );
				}
				
				return $custom_list;
			}
			else
			{
				$list = array();
				
				foreach( $master_list as $site )
				{
					$list[] = $site['domain'];
				}
				
				return $list;
			}
		}
	}
	
	//Options page
	function bot_block_options() 
	{
		//Add CSS
		wp_register_style( 'bot_block_css', plugins_url( 'bot-block.css', __FILE__ ) );
		wp_enqueue_style( 'bot_block_css' );
		
		//Get options
		$options = get_option( 'bot_block' );
		?>
		<div class="wrap">
			<h2>
				<?php echo BOT_BLOCK_NAME ?> - <?php echo BOT_BLOCK_TAGLINE ?>
			</h2>
			<form method="post" action="options.php">
				<input type="hidden" name="bot_block[blocked_count]" value="<?php echo $options['blocked_count']; ?>">
				<input type="hidden" name="bot_block[last_update_time]" value="<?php echo $options['last_update_time']; ?>">
				<?php
					//Get settings fields
					settings_fields( 'bot_block_options' );
					do_settings_sections( 'bot_block_options' );
				?>
					<!-- //Left hand side column
					//
					// -->
				<div class="bb_left bb_column">
						<h3>
							Options
						</h3>
						<hr />
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									<label>Block sub domains</label>
								</th>
								<td>
									<input id="enabled" name="bot_block[subdomains]" type="checkbox" value="on" <?php echo isset( $options['subdomains'] ) ? 'checked="checked"' : ''; ?> />
									<p>
										Eg, block <code>subdomain.semalt.com</code>.
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label>Send 403 rather than redirect</label>
								</th>
								<td>
									<input id="enabled" name="bot_block[redirect]" type="checkbox" value="on" <?php echo isset( $options['redirect'] ) ? 'checked="checked"' : ''; ?> />
									<p>
										Check this to display a 403 error rather than re-directing.
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label>URL to redirect to</label>
								</th>
								<td>
									<input id="url" type="text" name="bot_block[url]" value="<?php echo $options['url']; ?>" />
									<p>
										The website the user will be re-directed to if the above box is unchecked.
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label>Additional domains to block</label>
								</th>
								<td>
									<textarea rows="6" cols="50" id="domains" name="bot_block[domains]"><?php echo $options['domains']; ?></textarea>
									<p>
										Aside from the domains updated from the central database you can also add your own custom domains.
									</p>
									<p>
										Enter one domain per line, you don't need to add http:// - for example: <code>semalt.com</code>.
									</p>
								</td>
							</tr>
						</table>
						<?php submit_button(); ?>
						
						<a href="http://www.webresultsdirect.com/digital-marketing-agency/google-analytics-consulting-services/?utm_source=BotBlock&utm_medium=plugin&utm_campaign=plugin" target="_blank"><img src="/wp-content/plugins/bot-block-stop-spam-google-analytics-referrals/analyticsbanner.png"></a>
				</div>
								<!-- //Right hand side column
				//
				// -->
				<div class="bb_right bb_column">				
					<h3>
						Statistics
					</h3>
					<hr />
					<table class="form-table">
						<tr valign="top">
							<th scope="row">
								<label>Send anonymous stats to Bot Block to help us make this plugin better</label>
							</th>
							<td>
								<input id="enabled" name="bot_block[send_stats]" type="checkbox" value="on" <?php echo isset( $options['send_stats'] ) ? 'checked="checked"' : ''; ?> />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label>Number of blocks since last activation</label>
							</th>
							<td>
								<?php echo $options['blocked_count']; ?>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label>Number of sites in block list</label>
							</th>
							<td>
								<?php echo $this->count_master_table(); ?>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label>Last time block list was updated</label>
							</th>
							<td>
								<?php echo $this->get_last_update_time(); ?>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label>Top 5 blocked sites</label>
							</th>
							<td>
								<?php
									$stats = $this->get_stats();
									
									$stats = json_decode( $stats, true );
									
									//echo print_r( $stats, true );
									
									if( empty( $stats ) )
									{
										echo '<span style="color: red;">Nothing has been blocked yet.</span>';
									}
									else
									{
										$count = 1;
										
										foreach( $stats as $key => $stat )
										{
											if( $count <= 5 )
											{
												echo "$count. $key ($stat)<br>";
												
												$count++;
											}
											else
											{
												break;
											}
										}
									}
								?>
							</td>
						</tr>
					</table>
				</form>
		</div>
		
		<div class="bb_sidebar bb_column">
		<h3>Still Seeing Spam Traffic?</h3>
				<hr />
				<p>Setting up bot block to block 100% of the spam referral traffic is a two step process. You also need to setup a filter in Analytics, see the video below for a full tutorial on how to do this, it takes just 5 minutes!</p>
				
				<style>.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class='embed-container'><iframe src='https://player.vimeo.com/video/131195228' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
				
							<h3>Keep This Plugin Alive</h3>
			<p>Buy the developers a beer and keep this plugin up to date</p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="DRHZFMXEWHWWY">
					<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
				</form>
		</div>
		
		</div>	
<?php
	}
}
?>