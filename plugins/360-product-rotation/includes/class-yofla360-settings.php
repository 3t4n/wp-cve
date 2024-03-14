<?php

class YoFLA360Settings
{

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	protected $options;

	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action('admin_menu', array($this, 'add_plugin_admin_page'));
		add_action('admin_init', array($this, 'page_init'));
	}

	/**
	 * Add options page
	 */
	public function add_plugin_admin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'360&deg; Product Rotation',
			'360&deg; Product Rotation',
			'manage_options',
			'yofla-360-admin',
			array($this, 'create_plugin_admin_page')
		);
	}

	/**
	 * Options page callback
	 */
	public function create_plugin_admin_page()
	{
		// Set class property
		$this->options = get_option('yofla_360_options');
		$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
		?>
		<div class="wrap">
			<h2 class="nav-tab-wrapper">
				<a
						href="?page=yofla-360-admin&tab=general"
						class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"
				>
					General
				</a>
				<a
						href="?page=yofla-360-admin&tab=cache"
						class="nav-tab <?php echo $active_tab == 'cache' ? 'nav-tab-active' : ''; ?>"
				>
					Cache
				</a>
			</h2>

			<?php
			if ($active_tab == 'general') {
				?>
				<h2>360&deg; Product Rotation Plugin Settings</h2>
				<form method="post" action="options.php">
					<?php
					// This prints out all hidden setting fields
					settings_fields('yofla_360_option_group');
					submit_button();
					do_settings_sections('yofla-360-admin');
					submit_button();
					?>
				</form>
				<?php
			} elseif ($active_tab == 'x_legacy') {
				echo '<form action="options.php" method="post">';
				// This prints out all hidden setting fields
				settings_fields('yofla_360_option_group');
				do_settings_sections('yofla-360-admin-legacy');
				submit_button();
				echo '</form>';

			} elseif ($active_tab == 'x_woocommerce') {
				echo '<form action="options.php" method="post">';
				// This prints out all hidden setting fields
				settings_fields('yofla_360_option_group');
				do_settings_sections('yofla-360-admin-woocommerce');
				submit_button();
				echo '</form>';
			} else {
				?>
				<h2>Cache Settings</h2>
				<?php
				$isDeleteRequest = isset($_POST['delete_cache_trigger']) ? true : false;
				if ($isDeleteRequest) {
					if (YoFLA360()->Utils()->clear_cache()) {
						echo '<strong>Cache cleared!</strong>';
					} else {
						echo '<strong>Failed deleting cache!</strong>';
					}
				}
				?>
				<p>
					Delete the content of the <em>wp-content/uploads/yofla360/_cache</em> directory. Useful when
					migrating
					the site from http to https.
				</p>
				<?php
				echo '<form action="options-general.php?page=yofla-360-admin&tab=cache" method="post">';

				wp_nonce_field('test_button_clicked');
				echo '<input type="hidden" value="true" name="delete_cache_trigger" />';
				submit_button('Delete Cache');
				echo '</form>';

				echo '</div>';

			} // end if/else
			?>

		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public
	function page_init()
	{
		register_setting(
			'yofla_360_option_group', // Option group
			'yofla_360_options', // Option name
			array($this, 'sanitize') // Sanitize
		);

		add_settings_section(
			'yofla_360_settings_section_general', // ID
			'', // Title
			array($this, 'print_section_info_general'), // Callback
			'yofla-360-admin' // Page
		);


		add_settings_section(
			'yofla_360_settings_section_legacy', // ID
			'Legacy Player Settings', // Title
			array($this, 'print_legacy_section_info'), // Callback
			'yofla-360-admin' // Page
		);

		add_settings_section(
			'yofla_360_settings_section_shortcode', // ID
			'Shortcode defaults', // Title
			array($this, 'print_section_info_shortcode'), // Callback
			'yofla-360-admin' // Page
		);

		add_settings_section(
			'yofla_360_settings_section_legacy_endwrapper', // ID
			'', // Title
			array($this, 'print_legacy_section_info_endwrapper'), // Callback
			'yofla-360-admin' // Page
		);


		// /SECTIONS END

		// license key also in normal section
		add_settings_field(
			'license_key',
			'<strong>License Key:</strong>',
			array($this, 'licensekey_callback'),
			'yofla-360-admin',
			'yofla_360_settings_section_general'
		);



		//license id in legacy settings
		add_settings_field(
			'license_id',
			'<strong>License Key or Id:</strong>',
			array($this, 'licenseid_callback'),
			'yofla-360-admin',
			'yofla_360_settings_section_legacy'
		);

		//local engine
		add_settings_field(
			'local_engine',
			'<strong>Local Engine:</strong>',
			array($this, 'local_engine_callback'),
			'yofla-360-admin',
			'yofla_360_settings_section_legacy'
		);


		//rotatetooljs_url
		add_settings_field(
			'rotatetooljs_url',
			'<strong>rotatetool.js url:</strong><div style="font-size: 12px">(optional)</div>',
			array($this, 'rotatetooljs_url_callback'),
			'yofla-360-admin',
			'yofla_360_settings_section_legacy'
		);

		//global player styles
		add_settings_field(
			'theme_url',
			'<strong>Override player theme url:</strong><div style="font-size: 12px">(optional)</div>',
			array($this, 'theme_url_callback'),
			'yofla-360-admin',
			'yofla_360_settings_section_legacy'
		);

		//iframe styles
		add_settings_field(
			'iframe_styles',
			'<strong>Iframe Styles:</strong>',
			array($this, 'iframe_styles_callback'),
			'yofla-360-admin',
			'yofla_360_settings_section_general'
		);


		//gaEnabled
		add_settings_field(
			'ga_enabled',
			'<strong>ga_enabled:</strong>',
			array($this, 'ga_enabled_callback'),
			'yofla-360-admin',
			'yofla_360_settings_section_shortcode'
		);

		//ga tracking id
		add_settings_field(
			'ga_tracking_id',
			'<strong>ga_tracking_id:</strong>',
			array($this, 'ga_tracking_id_callback'),
			'yofla-360-admin',
			'yofla_360_settings_section_shortcode'
		);

		if (YoFLA360::$isWooCommerce) {

			add_settings_section(
				'yofla_360_settings_section_woocommerce', // ID
				'WooCommerce', // Title
				array($this, 'print_section_info_woocommerce'), // Callback
				'yofla-360-admin' // Page
			);

			//url settings
			add_settings_field(
				'woocommerce_360thumb_url',
				'<strong>360 Thumb Url:</strong>',
				array($this, 'woocommerce_360thumb_url_callback'),
				'yofla-360-admin',
				'yofla_360_settings_section_woocommerce'
			);

			//woocomemrce alternate embedding
			add_settings_field(
				'woocommerce_alternate_embedding',
				'<strong>WooCommerce alternate embedding:</strong>',
				array($this, 'woocommerce_alternate_embedding_callback'),
				'yofla-360-admin',
				'yofla_360_settings_section_woocommerce'
			);
		}

	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 * @return array
	 */
	public function sanitize($input)
	{
		$new_input = array();

		if (isset($input['license_id'])) {

			$firstTwoChars = strtolower( substr($this->options['license_id'],0,2));
			if($firstTwoChars == 'yc'){
				$new_input['license_id'] = 'please_do_not_use_cloud-based_license_key_here';
			}
			else{
				$new_input['license_id'] = sanitize_text_field($input['license_id']);
			}
		}

		if (isset($input['license_key'])) {
			$new_input['license_key'] = sanitize_text_field($input['license_key']);
		}

		if (isset($input['rotatetooljs_url'])) {
			$new_input['rotatetooljs_url'] = sanitize_text_field($input['rotatetooljs_url']);
			$new_input['rotatetooljs_url'] = YoFLA360()->Utils()->addHttp($new_input['rotatetooljs_url']);
		}

		if (isset($input['theme_url'])) {
			$new_input['theme_url'] = sanitize_text_field($input['theme_url']);
		}

		if (isset($input['iframe_styles'])) {
			$new_input['iframe_styles'] = sanitize_text_field($input['iframe_styles']);
			//remove trailing/leading " or '
			$new_input['iframe_styles'] = trim($new_input['iframe_styles'], " '\"");
		}

		if (isset($input['ga_tracking_id'])) {
			$new_input['ga_tracking_id'] = sanitize_text_field($input['ga_tracking_id']);
			//remove trailing/leading " or '
			$new_input['ga_tracking_id'] = trim($new_input['ga_tracking_id'], " '\"");
		}

		if (isset($input['ga_enabled'])) {
			$new_input['ga_enabled'] = 1;
		}

		if (isset($input['local_engine'])) {
			$new_input['local_engine'] = 1;
		}

		if (isset($input['woocommerce_alternate_embedding'])) {
			$new_input['woocommerce_alternate_embedding'] = 1;
		}


		if (isset($input['woocommerce_360thumb_url'])) {
			$new_input['woocommerce_360thumb_url'] = sanitize_text_field($input['woocommerce_360thumb_url']);
		}

		return $new_input;
	}

	public function print_section_info_general()
	{

		$link_plugin_media = '<a href="'.admin_url( 'upload.php?page=yofla-360-media').'">360 Views Library</a>';
		echo $link_plugin_media;
	}

	/**
	 * Print the Legacy Section text
	 */
	public function print_legacy_section_info()
	{

		echo '<a href="#" onclick="jQuery(\'#yofla-legacy-info\').toggle()">Show legacy player settings</a> (for local 360 views created using the desktop application "3DRT Setup Utility")';
		echo '<div id="yofla-legacy-info" style="display: none">';

		$this->print_section_info_legacy();

		if (isset($this->options['license_id']) && strlen($this->options['license_id']) > 0) {
			$firstTwoChars = strtolower( substr($this->options['license_id'],0,2));
			if($firstTwoChars == 'yc'){
				echo '<p><span style="color: blue">Cloud based license key should not be used here! (only legacy license keys)</span></p>';
				$this->options['license_id'] = '';
				update_option('yofla_360_options', $this->options);
			}
			else{

				$data = $this->_get_order_data($this->options['license_id']);
				if (gettype($data) == 'array') {
					//$updates_end_days = round((strtotime($data['updatesend'])-time())/(60*60*24));
					//$updatesend = date_format(date_create(strtotime($data['updatesend'])), 'g:ia \o\n l jS F Y');

					$updatesend = date('jS F Y', strtotime($data['updatesend']));

					//$updatesend = $data['updatesend'];

					$out = "<table>";
					$out .= "<tr>";
					$out .= "    <td>";
					$out .= "    360&deg; Rotations by:";
					$out .= "    </td>";
					$out .= "    <td>";
					$out .= "    <strong>{$data['license_holder']}</strong>";
					$out .= "    </td>";
					$out .= "</tr>";
					$out .= "<tr>";
					$out .= "    <td>";
					$out .= "    License type:";
					$out .= "    </td>";
					$out .= "    <td>";
					$out .= "    {$data['productid']}";
					$out .= "    </td>";
					$out .= "</tr>";
					$out .= "<tr>";
					$out .= "    <td>";
					$out .= "    Free updates until:";
					$out .= "    </td>";
					$out .= "    <td>";
					$out .= "    {$updatesend}";
					$out .= "    </td>";
					$out .= "</tr>";
					$out .= "</table>";

					echo $out;

					//update license id
					if (isset($data['orderuid'])) {
						$this->options['license_id'] = $data['orderuid'];
						update_option('yofla_360_options', $this->options);
					}

				} elseif (gettype($data) == 'string') {
					echo '<p><span style="color: red">' . $data . '</span></p>';
				} else {
					echo '<p><span style="color: red">License Key is invalid!</span></p>';
					//remove void option
					$this->options['license_id'] = '';
					update_option('yofla_360_options', $this->options);

				}
			}

		}

		$msg = '<p>Please enter your License Key or License ID to replace the free 360&deg; player with a licensed version. License Key will be converted to License ID after submitting.';
		$msg .= ' If you are already using a licensed player, entering the License Key here will make ';
		$msg .= ' all 360&deg; product rotations use the latest 360&deg; player from the cloud.</p>';
		echo $msg;
	}

	public function print_legacy_section_info_endwrapper()
	{
		echo '</div> <!-- yofla-legacy-info>';
	}


	/**
	 * Print the Section text for shortcode options
	 */
	public function print_section_info_woocommerce()
	{
		//$msg = '<p>.';
		//$msg .= '</p>';
		$msg = 'WooCommerce Settings';
		echo $msg;
	}

	/**
	 * Print the Section text for shortcode options
	 */
	public function print_section_info_advanced()
	{
		//$msg = '<p>.';
		//$msg .= '</p>';
		$msg = '';
		echo $msg;
	}

	/**
	 * Print the Section text for shortcode options
	 */
	public function print_section_cache()
	{
		$msg = '<h2> Cache Management';
		$msg .= '</h2>';
		$msg = '';
		echo $msg;
	}

	/**
	 * Print the Section text for shortcode options
	 */
	public function print_section_info_shortcode()
	{
		$msg = '<p>Set default site-wide default shortcode values for embedding the 360&deg; product rotation.';
		$msg .= '</p>';
		echo $msg;

	}

	public function print_section_info_legacy()
	{
		$msg = '<p>Settings for the 360&deg; views created by the desktop application "3DRT Setup Utility" ';
		$msg .= 'The application is being gradually replaced by the <a href="https://www.y360.at/creator/?utm_source=wordpress_site&utm_medium=plugin&utm_content=settings_page" target="_blank">web-based version</a>';
		$msg .= '</p>';
		echo $msg;
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function licenseid_callback()
	{
		printf(
			'<input style="min-width: 190px;" type="text" id="license_id" name="yofla_360_options[license_id]" value="%s" />',
			isset($this->options['license_id']) ? esc_attr($this->options['license_id']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function licensekey_callback()
	{

		wp_nonce_field('license_key_submit_nonce', 'license_key_submit');

		printf(
			'<input style="min-width: 190px;" type="text" id="license_key" name="yofla_360_options[license_key]" value="%s" />',
			isset($this->options['license_key']) ? esc_attr($this->options['license_key']) : ''
		);

		// check if license key is valid, if submitted
		if (isset($this->options['license_key']) && strlen($this->options['license_key']) > 0) {
			$firstTwoChars = strtolower(substr($this->options['license_key'], 0, 2));

			if ($firstTwoChars == 'yc') {

				$licenseCheckResult= $this->_get_account_data($this->options['license_key']);

				if($licenseCheckResult == 'valid'){
					echo "  ok :: valid license key";
				}
				elseif ($licenseCheckResult == 'invalid'){
					echo "  License key not valid!";
				}
			}
			else{
				echo "  This is not a cloud-based license key, please use it in the Legacy Player Settings";
			}
		}
		else{
			echo "  You can also get your free trial license key here: <a target='_blank' href='https://www.y360.at/creator/?utm_source=wordpress_site&utm_medium=plugin&utm_content=page_settings'>360&deg; View Creator</a>";
		}


	}

	public
	function local_engine_callback()
	{

		$desc = '<br />If checked, the local rotatetool.js uploaded with the 360 view will be used, instead of the global one.';

		printf(
			'<input type="checkbox" id="local_engine" value="1" name="yofla_360_options[local_engine]" %s />%s',
			isset($this->options['local_engine']) ? 'checked' : '', $desc
		);
	}


	public function woocommerce_alternate_embedding_callback()
	{

		$desc = '<br />If checked, the 360 player is injected into the woocommerce product gallery using pure JavaScript. This might help when multiple product galleries for one product are used in one page.';

		printf(
			'<input type="checkbox" id="woocommerce_alternate_embedding" value="1" name="yofla_360_options[woocommerce_alternate_embedding]" %s />%s',
			isset($this->options['woocommerce_alternate_embedding']) ? 'checked' : '', $desc
		);
	}


	/**
	 * Get the settings option array and print one of its values
	 */
	public
	function rotatetooljs_url_callback()
	{
		printf(
			'<input type="text" style="min-width: 320px;" id="rotatetooljs_url" name="yofla_360_options[rotatetooljs_url]" value="%s" />',
			isset($this->options['rotatetooljs_url']) ? esc_attr($this->options['rotatetooljs_url']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function theme_url_callback()
	{
		$desc = '<br />If provided, all 360 views will use this theme. Example: http://www.example.com/360/themes/pure-white/';
		printf(
			'<input type="text" style="min-width: 320px;" id="theme_url" name="yofla_360_options[theme_url]" value="%s" /> %s',
			isset($this->options['theme_url']) ? esc_attr($this->options['theme_url']) : '', $desc
		);
	}


	/**
	 * Get the settings option array and print one of its values
	 */
	public
	function iframe_styles_callback()
	{
		$desc = '<br />When not set, this default is used: "max-width: 100%; border: 1px solid silver;"';
		$desc .= '<br />You can disable the 360&deg; view border by entering: "max-width: 100%; border: 0px;"';
		printf(
			'<input type="text" style="min-width: 320px;" id="iframe_styles" name="yofla_360_options[iframe_styles]" value="%s" />%s',
			isset($this->options['iframe_styles']) ? esc_attr($this->options['iframe_styles']) : '', $desc
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public
	function ga_enabled_callback()
	{

		$desc = '<br />Turns on Google Analytics Events Tracking. <a href="http://www.yofla.com/3d-rotate/support/manuals/tracking-user-engagement-using-google-analytics/" target="_blank">More Info.</a>';

		printf(
			'<input type="checkbox" id="ga_enabled" value="1" name="yofla_360_options[ga_enabled]" %s />%s',
			isset($this->options['ga_enabled']) ? 'checked' : '', $desc
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public
	function ga_tracking_id_callback()
	{
		$desc = '<br />Your Google Analytics profile tracking ID, e.g. UA-123456-7';
		printf(
			'<input type="text" id="ga_tracking_id" name="yofla_360_options[ga_tracking_id]" value="%s" />%s',
			isset($this->options['ga_tracking_id']) ? esc_attr($this->options['ga_tracking_id']) : '', $desc
		);
	}


	/**
	 * Get the settings html form code
	 */
	public
	function woocommerce_360thumb_url_callback()
	{
		printf(
			'<input type="text" style="min-width: 320px;" id="woocommerce_360thumb_url" name="yofla_360_options[woocommerce_360thumb_url]" value="%s" />',
			isset($this->options['woocommerce_360thumb_url']) ? esc_attr($this->options['woocommerce_360thumb_url']) : ''
		);
	}

	/**
	 * Checks if order data is valid
	 *
	 * @param $license_id
	 * @return array|null
	 */
	private function _get_order_data($license_id)
	{

		$url = YOFLA_LICENSE_ID_CHECK_URL . $license_id;

		add_filter('https_local_ssl_verify', '__return_false');
		$response = wp_remote_get($url);

		if (is_wp_error($response)) {
			$error = $response->get_error_message();
			$msg = '<div id="message" class="error"><p>' . 'Error communicating with server! ' . $error . '</p>';
			return $msg;
		}


		if ($response && isset($response['body'])) {
			$body = $response['body'];
			$data = explode('|', $body);

			//sucess
			if ($data[0] == 'ok') {
				$order_data = array();
				$order_data['license_holder'] = $data[1];
				$order_data['updatesend'] = $data[2];
				$order_data['productid'] = $data[3];
				$order_data['orderuid'] = $data[4];
				return $order_data;
			} //error
			else {
				return $body;
			}
		}

		return null;
	}

	/**
	 * Checks if license key is valid
	 *
	 * @param $license_id
	 * @return array|null
	 */
	private function _get_account_data($license_id)
	{

		$url = YOFLA_LICENSE_ID_CHECK_URL_CLOUD;

		$response =  wp_remote_post($url,array('body' => array('licenseKey'=>$license_id) ) );

		if (is_wp_error($response)) {
			$error = $response->get_error_message();
			$msg = '<div id="message" class="error"><p>' . 'Error communicating with server! ' . $error . '</p>';
			return $msg;
		}

		if ($response && isset($response['body'])) {
			$body = $response['body'];
			return $body;
		}

		return null;
	}

}
