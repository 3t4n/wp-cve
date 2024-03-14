<?php
/*
	Plugin Name: MouseWheel Smooth Scroll
	Plugin URI: https://kubiq.sk
	Description: MouseWheel smooth scrolling for your WordPress website
	Version: 6.4.1
	Author: KubiQ
	Author URI: https://kubiq.sk
	Text Domain: wpmss
	Domain Path: /languages
*/

class wpmss{
	var $plugin_admin_page;
	var $settings;
	var $tab;
	var $uploads;

	function __construct(){
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'admin_menu', array( $this, 'plugin_menu_link' ) );
		add_action( 'init', array( $this, 'plugin_init' ) );
	}

	function plugins_loaded(){
		load_plugin_textdomain( 'wpmss', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	function filter_plugin_actions( $links, $file ){
		$settings_link = '<a href="options-general.php?page=' . basename( __FILE__ ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	function plugin_menu_link(){
		$this->plugin_admin_page = add_submenu_page(
			'options-general.php',
			__( 'Smooth Scroll', 'wpmss' ),
			__( 'Smooth Scroll', 'wpmss' ),
			'manage_options',
			basename( __FILE__ ),
			array( $this, 'admin_options_page' )
		);
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'filter_plugin_actions' ), 10, 2 );
	}

	function plugin_init(){
		$this->settings = get_option( 'wpmss_settings', [] );
		if( ! isset( $this->settings['general']['timestamp'] ) ){
			$this->settings['general']['timestamp'] = time();
			$this->settings['general']['pulseAlgorithm'] = 1;
			$this->settings['general']['keyboardSupport'] = 1;
			update_option( 'wpmss_settings', $this->settings );
		}

		$this->uploads = wp_get_upload_dir();

		$this->process_settings();

		add_action( 'wp_enqueue_scripts', array( $this, 'plugin_scripts_load' ) );
	}

	function process_settings(){
		$this->settings['general']['frameRate'] = isset( $this->settings['general']['frameRate'] ) && trim( $this->settings['general']['frameRate'] ) ? intval( $this->settings['general']['frameRate'] ) : 150;
		$this->settings['general']['animationTime'] = isset( $this->settings['general']['animationTime'] ) && trim( $this->settings['general']['animationTime'] ) ? intval( $this->settings['general']['animationTime'] ) : 1000;
		$this->settings['general']['stepSize'] = isset( $this->settings['general']['stepSize'] ) && trim( $this->settings['general']['stepSize'] ) ? intval( $this->settings['general']['stepSize'] ) : 100;
		$this->settings['general']['pulseAlgorithm'] = isset( $this->settings['general']['pulseAlgorithm'] ) ? 1 : 0;
		$this->settings['general']['pulseScale'] = isset( $this->settings['general']['pulseScale'] ) && trim( $this->settings['general']['pulseScale'] ) ? intval( $this->settings['general']['pulseScale'] ) : 4;
		$this->settings['general']['pulseNormalize'] = isset( $this->settings['general']['pulseNormalize'] ) && trim( $this->settings['general']['pulseNormalize'] ) ? intval( $this->settings['general']['pulseNormalize'] ) : 1;
		$this->settings['general']['accelerationDelta'] = isset( $this->settings['general']['accelerationDelta'] ) && trim( $this->settings['general']['accelerationDelta'] ) ? intval( $this->settings['general']['accelerationDelta'] ) : 50;
		$this->settings['general']['accelerationMax'] = isset( $this->settings['general']['accelerationMax'] ) && trim( $this->settings['general']['accelerationMax'] ) ? intval( $this->settings['general']['accelerationMax'] ) : 3;
		$this->settings['general']['keyboardSupport'] = isset( $this->settings['general']['keyboardSupport'] ) ? 1 : 0;
		$this->settings['general']['arrowScroll'] = isset( $this->settings['general']['arrowScroll'] ) && trim( $this->settings['general']['arrowScroll'] ) ? intval( $this->settings['general']['arrowScroll'] ) : 50;
		$this->settings['general']['allowedBrowsers'] = isset( $this->settings['general']['allowedBrowsers'] ) ? (array)$this->settings['general']['allowedBrowsers'] : array( 'IEWin7', 'Chrome', 'Safari' );

		if( ! file_exists( $this->uploads['basedir'] . '/wpmss/wpmss.min.js' ) ){
			$this->save_js_config();
		}
	}

	function plugin_scripts_load(){
		wp_enqueue_script( 'wpmssab', $this->uploads['baseurl'] . '/wpmss/wpmssab.min.js', array(), $this->settings['general']['timestamp'] + 6411410, 1 );
		wp_enqueue_script( 'SmoothScroll', plugins_url( 'js/SmoothScroll.min.js', __FILE__ ), array('wpmssab'), '1.4.10', 1 );
		wp_enqueue_script( 'wpmss', $this->uploads['baseurl'] . '/wpmss/wpmss.min.js', array('SmoothScroll'), $this->settings['general']['timestamp'] + 6411410, 1 );
	}

	function plugin_admin_tabs( $current = 'general' ){
		$tabs = array( 'general' => __('General'), 'info' => __('Help') ); ?>
		<h2 class="nav-tab-wrapper">
		<?php foreach( $tabs as $tab => $name ){ ?>
			<a class="nav-tab <?php echo ( $tab == $current ) ? "nav-tab-active" : "" ?>" href="?page=<?php echo basename( __FILE__ ) ?>&amp;tab=<?php echo $tab ?>"><?php echo $name ?></a>
		<?php } ?>
		</h2><br><?php
	}

	function save_js_config(){
		if( ! file_exists( $this->uploads['basedir'] . '/wpmss' ) ){
			mkdir( $this->uploads['basedir'] . '/wpmss', 0777, true );
		}
		$allowedBrowsers = sprintf(
			'var allowedBrowsers=["%s"];',
			implode( '","', $this->settings['general']['allowedBrowsers'] )
		);
		file_put_contents( $this->uploads['basedir'] . '/wpmss/wpmssab.min.js', $allowedBrowsers );

		$content = sprintf(
			'SmoothScroll({'.
				'frameRate:%d,'.
				'animationTime:%d,'.
				'stepSize:%d,'.
				'pulseAlgorithm:%d,'.
				'pulseScale:%d,'.
				'pulseNormalize:%d,'.
				'accelerationDelta:%d,'.
				'accelerationMax:%d,'.
				'keyboardSupport:%d,'.
				'arrowScroll:%d,'.
			'})',
			intval( $this->settings['general']['frameRate'] ),
			intval( $this->settings['general']['animationTime'] ),
			intval( $this->settings['general']['stepSize'] ),
			intval( $this->settings['general']['pulseAlgorithm'] ),
			intval( $this->settings['general']['pulseScale'] ),
			intval( $this->settings['general']['pulseNormalize'] ),
			intval( $this->settings['general']['accelerationDelta'] ),
			intval( $this->settings['general']['accelerationMax'] ),
			intval( $this->settings['general']['keyboardSupport'] ),
			intval( $this->settings['general']['arrowScroll'] )
		);
		file_put_contents( $this->uploads['basedir'] . '/wpmss/wpmss.min.js', $content );
	}

	function admin_options_page(){
		if( get_current_screen()->id != $this->plugin_admin_page ) return;
		$this->tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		if( isset( $_POST['plugin_sent'], $_POST['wpmss_nonce'] ) ){
			if( check_admin_referer( 'wpmss_data', 'wpmss_nonce' ) ){
				$this->settings[ $this->tab ] = $_POST;
				update_option( 'wpmss_settings', $this->settings );
				$this->process_settings();
				$this->save_js_config();
			}
		} ?>
		<div class="wrap">
			<h2><?php _e( 'MouseWheel Smooth Scroll', 'wpmss' ); ?></h2>
			<?php if( isset( $_POST['plugin_sent'] ) ) echo '<div id="message" class="below-h2 updated"><p>' . __('Settings saved.') . '</p></div>' ?>
			<form method="post" action="<?php admin_url( 'options-general.php?page=' . basename( __FILE__ ) ) ?>">
				<input type="hidden" name="plugin_sent" value="1"><?php
				wp_nonce_field( 'wpmss_data', 'wpmss_nonce' );
				$this->plugin_admin_tabs( $this->tab );
				switch( $this->tab ):
					case 'general':
						$this->plugin_general_options();
						break;
					case 'info':
						$this->plugin_info_options();
						break;
				endswitch; ?>
			</form>
		</div><?php
	}

	function plugin_general_options(){ ?>
		<style>.default{color:#a0a5aa}</style>
		<input type="hidden" name="timestamp" value="<?php echo time() ?>">
		<table class="form-table">
			<tr>
				<th colspan="2">
					<h3><?php _e( 'Scrolling Core', 'wpmss' ) ?></h3>
				</th>
			</tr>
			<tr>
				<th>
					<label for="q_field_1"><?php _e( 'frameRate', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="number" name="frameRate" placeholder="150" value="<?php echo $this->settings[ $this->tab ]['frameRate'] ?>" id="q_field_1">
					[Hz]&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> 150</small>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_2"><?php _e( 'animationTime', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="number" name="animationTime" placeholder="1000" value="<?php echo $this->settings[ $this->tab ]['animationTime'] ?>" id="q_field_2">
					[ms]&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> 1000</small>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_3"><?php _e( 'stepSize', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="number" name="stepSize" placeholder="100" value="<?php echo $this->settings[ $this->tab ]['stepSize'] ?>" id="q_field_3">
					[px]&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> 100</small>
				</td>
			</tr>

			<tr>
				<th colspan="2">
					<h3><?php _e( 'Pulse (less tweakable)<br>ratio of "tail" to "acceleration"', 'wpmss' ) ?></h3>
				</th>
			</tr>
			<tr>
				<th>
					<label for="q_field_35"><?php _e( 'pulseAlgorithm', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="checkbox" name="pulseAlgorithm" value="1" <?php echo $this->settings[ $this->tab ]['pulseAlgorithm'] ? 'checked="checked"' : '' ?> id="q_field_35">
					&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> on</small>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_4"><?php _e( 'pulseScale', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="number" name="pulseScale" placeholder="4" value="<?php echo $this->settings[ $this->tab ]['pulseScale'] ?>" id="q_field_4">
					&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> 4</small>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_5"><?php _e( 'pulseNormalize', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="number" name="pulseNormalize" placeholder="1" value="<?php echo $this->settings[ $this->tab ]['pulseNormalize'] ?>" id="q_field_5">
					&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> 1</small>
				</td>
			</tr>

			<tr>
				<th colspan="2">
					<h3><?php _e( 'Acceleration', 'wpmss' ) ?></h3>
				</th>
			</tr>
			<tr>
				<th>
					<label for="q_field_6"><?php _e( 'accelerationDelta', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="number" name="accelerationDelta" placeholder="50" value="<?php echo $this->settings[ $this->tab ]['accelerationDelta'] ?>" id="q_field_6">
					&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> 50</small>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_7"><?php _e( 'accelerationMax', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="number" name="accelerationMax" placeholder="3" value="<?php echo $this->settings[ $this->tab ]['accelerationMax'] ?>" id="q_field_7">
					&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> 3</small>
				</td>
			</tr>

			<tr>
				<th colspan="2">
					<h3><?php _e( 'Keyboard Settings', 'wpmss' ) ?></h3>
				</th>
			</tr>
			<tr>
				<th>
					<label for="q_field_75"><?php _e( 'keyboardSupport', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="checkbox" name="keyboardSupport" value="1" <?php echo $this->settings[ $this->tab ]['keyboardSupport'] ? 'checked="checked"' : '' ?> id="q_field_75">
					&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> on</small>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_8"><?php _e( 'arrowScroll', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<input type="number" name="arrowScroll" placeholder="50" value="<?php echo $this->settings[ $this->tab ]['arrowScroll'] ?>" id="q_field_8">
					[px]&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> 50</small>
				</td>
			</tr>

			<tr>
				<th colspan="2">
					<h3><?php _e( 'Other', 'wpmss' ) ?></h3>
				</th>
			</tr>
			<tr>
				<th>
					<label for="q_field_11"><?php _e( 'allowedBrowsers', 'wpmss' ) ?>:</label> 
				</th>
				<td>
					<select name="allowedBrowsers[]" id="q_field_11" multiple="multiple" style="height:150px">
						<?php foreach( array(
							'Mobile' => 'mobile browsers',
							'IEWin7' => 'IEWin7',
							'Edge' => 'Edge',
							'Chrome' => 'Chrome',
							'Safari' => 'Safari',
							'Firefox' => 'Firefox',
							'other' => 'all other browsers',
						) as $key => $value ){
							echo '<option value="' . $key . '"' . ( in_array( $key, $this->settings[ $this->tab ]['allowedBrowsers'] ) ? ' selected="selected"' : '' ) . '>' . $value . '</option>';
						} ?>
					</select>
					&emsp;<small class="default"><?php _e( 'default:', 'wpmss' ) ?> IEWin7, Chrome, Safari</small>
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" class="button button-primary button-large" value="<?php _e('Save') ?>"></p><?php
	}

	function plugin_info_options(){ ?>
		<p>This plugin is only WordPress implementation of JS script from <strong title="Blaze (Balázs Galambosi)">gblazex</strong>.</p>
		<p>Find more <a href="https://github.com/gblazex/smoothscroll-for-websites" target="_blank">on Github</a></p><?php
	}
}

$wpmss = new wpmss();