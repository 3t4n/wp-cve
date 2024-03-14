<?php
class SME_Settings_Menu {
	/**
	 * Menu slug
	 *
	 * @var string
	 */
	protected $slug = 'smugmug-embed-plugin-settings';
	/**
	 * URL for assets
	 *
	 * @var string
	 */
	protected $assets_url;
	
	protected $setting_macros= array(
						"{Aperture}",
						"{Caption}",
						"{Copyright}",
						"{Date}",
						"{DateCreated}",
						"{Exposure}",
						"{FileName}",
						"{Flash}",
						"{FocalLength}",
						"{ISO}",
						"{Keywords}",
						"{Lens}",
						"{Make}",
						"{Model}",
						"{Title}");
		
	/**
	 * Constructor.
	 *
	 * @param string $assets_url URL for assets
	 */
	public function __construct( $assets_url ) {
		$this->assets_url = $assets_url;
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );

	}
 

 
function SME_settings_add_help_tab () {
	global $SME_Helper;
    $screen = get_current_screen();
 
    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'    => 'SME_settings_documentation_tab',
        'title' => __('Documentation'),
        'content'   => '<p>' . __( '<a href="https://www.wicklundphotography.com/smugmug-embed-documentation/" target=_blank>Click here</a> to view our online documentation.' ) . '</p>',
    ) );
	$content = "";
	if ($SME_Helper->getLicenseType() =="Active") 
		$content = '<p>' . __( 'For premium support, please see our knowledge base <a href="https://www.wicklundphotography.com/smugmug-embed-support/" target="_blank">here.</a> Feel free to ask a new question if you can\'t the answer you need and our support team will get back to you ASAP!' ) . '</p>';
		
    $screen->add_help_tab( array(
        'id'    => 'SME_settings_get_help_tab',
        'title' => __('Get Help'),
        'content'   => $content.'<p>' . __( 'Search the free Wordpress.org <a href="https://wordpress.org/support/plugin/smugmug-embed/" target="_blank" >support forum.</a> If you can\'t find what your\'re looking for, ask a question...' ) . '</p>',
    ) );
	}


	public function add_page(){
		$SME_settings_page = add_menu_page(
			__( 'SmugMug Embed Page', 'text-domain' ),
			__( 'SmugMug Embed', 'text-domain' ),
			'manage_options',
			$this->slug,
			array( $this, 'render_admin' ) );
    // Adds my_help_tab when my_admin_page loads
    add_action( 'load-'.$SME_settings_page, array($this,'SME_settings_add_help_tab' ));

	}


	/**
	 * Register CSS and JS for page
	 *
	 * @uses "admin_enqueue_scripts" action
	 */
	public function register_assets()
	{
		wp_register_script( $this->slug, $this->assets_url . '/includes/SME_Ajax.js?t='.time(), array( 'jquery') );
		wp_register_style( $this->slug, $this->assets_url . '/css/admin.css' );
		wp_enqueue_script("jquery-ui-draggable");
		wp_enqueue_script("jquery-ui-droppable");		
		wp_localize_script( $this->slug, 'SME', array(
			'strings' => array(
				'saved' => __( 'Settings Saved', 'text-domain' ),
				'error' => __( 'Error', 'text-domain' )
			),
			'api'     => array(
				'url'   => esc_url_raw( rest_url( 'sme-api/v1/settings' ) ),
				'nonce' => wp_create_nonce( 'wp_rest' )
			)
		) );
	}
	/**
	 * Enqueue CSS and JS for page
	 */
	public function enqueue_assets(){
		if( ! wp_script_is( $this->slug, 'registered' ) ){
			$this->register_assets();
		}
		wp_enqueue_script( $this->slug );
		wp_enqueue_style( $this->slug );
	}
	/**
	 * Render plugin admin page
	 */
	public function render_admin(){
		global $SME_api,$SME_Helper;
		$this->enqueue_assets();
		$SME_api_progress = get_option('SME_api_progress');
		$verified = $SME_api_progress== "Verified" ?true :false;
		//plugin license settings
		$licenseType=$SME_Helper->getLicenseType();
		$licenseEmail=$SME_Helper->getLicenseEmail();
		$licenseRenewalLink = $SME_Helper->getLicenseRenewalLink();
		$licenseExpiration=$SME_Helper->getLicenseExpiration();
		if (!$licenseExpiration) $licenseExpiration="Demo Version Never Expires";
		$licenseKey = $SME_Helper->getLicenseKey();
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_options';	
		if (is_multisite())
			$siteURL = get_blog_details()->siteurl;
		else
			$siteURL = get_bloginfo('url');
		// - orig       $callback = $protocol.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'].'?page='.$this->slug;
        $callback = admin_url( 'admin.php?page=' . $this->slug );
		
		?>
						<h2>SmugMugEmbed Options</h2>
				<h2 class="nav-tab-wrapper">
				<a href="<?php echo $callback ?>&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>">License Options</a>
				<?php if ($verified || isset($_SESSION['SmugGalReqToken'])) { ?>
				<a href="<?php echo $callback ?>&tab=gellery_options" class="nav-tab <?php echo $active_tab == 'gellery_options' ? 'nav-tab-active' : ''; ?>">Gallery Options</a>
				<a href="<?php echo $callback ?>&tab=image_options" class="nav-tab <?php echo $active_tab == 'image_options' ? 'nav-tab-active' : ''; ?>">Image / Video Options</a>
				<?php } ?>
				</h2>
			
		<?php
			echo '<div id="SME_spinner" class="SME_loading"><img class="SME_loading-image" src="'.plugin_dir_url( __FILE__ ).'/images/spinner.gif" />&nbsp;</div>';			
		if ($active_tab == 'general_options') {
			echo "<div class='SME_Settings_Page'>";
			echo "<div class='SME_account_info'>";
			if (!$verified) {
				if (!isset($_SESSION['SmugGalReqToken']) || !isset($_GET['oauth_verifier'])) {
					// Step 1: Get a request token using an optional callback URL back to ourselves

					$request_token = $SME_api->getRequestToken($callback);

					$_SESSION['SmugGalReqToken'] = serialize($request_token);

					// Step 2: Get the User to login to SmugMug and authorise this demo
					echo '<p>Click <a href="'.$SME_api->getAuthorizeURL(array("Access"=>"Full")).'"><strong>HERE</strong></a> to authorize this application to speak with SmugMug.</p>';
				// Alternatively, automatically direct your visitor by commenting out the above line in favour of this:
					//header("Location:".$client->getAuthorizeURL());
				} else {
					$reqToken = unserialize($_SESSION['SmugGalReqToken']);
					unset($_SESSION['SmugGalReqToken']);
					// Step 3: Use the Request token obtained in step 1 to get an access token
					$SME_api->setToken($reqToken['oauth_token'], $reqToken['oauth_token_secret']);
					$oauth_verifier = $_GET['oauth_verifier'];  // This comes back with the callback request.
					$token = $SME_api->getAccessToken($oauth_verifier);  // The results of this call is what your application needs to store.
					update_option('SME_api_progress','Verified');
					update_option('SME_api_token',$token);
					echo "Please wait...Cleaning up a few things.";
					echo '<script>location.reload();</script>';
					die;
				}
			}
			$SME_api_progress = get_option('SME_api_progress');
			
			$verified = $SME_api_progress== "Verified" ?true :false;
			
			if ($verified) {
				$username = $SME_api->get('!authuser')->User->NickName;
				$plan = $SME_api->get('!authuser')->User->Plan;
				$imageCount = $SME_api->get('!authuser')->User->ImageCount;
				echo "<h3>Logged into SmugMug as <em>$username</em></h3>";
				echo '<form method="post" action="options.php">';
				settings_fields( 'SME_smugmugembed_api_group' );
				echo '    <input type="hidden" name="SME_smugmugembed_api" value="" />';
				echo '    <input type="hidden" name="SME_api_progress" value="" />';
				echo '<input type="submit" class="button-secondary" value="Delete SmugMug Authorization" /></form>';
				echo "<h4>SmugMug Plan: <em>$plan</em></h4>";	
				echo "<h4>Image Count: <em>$imageCount</em></h4>";
			}
			echo "<h4>SmugMug Embed Plugin License: <em>$licenseType</em></h4>";
			echo "<h4>License Expiration: <em>$licenseExpiration</em>";
			if ($licenseType=="Expired") {
					echo "<div style='display:flex;padding:5px 0px 5px 0px;' ><a target='_blank' class='button-secondary' href='".$licenseRenewalLink."'>Click to Renew</a></div>";
			}
			echo "</h4>";			
			if ($licenseType == "DEMO") echo "<div><a target='_blank' href='".$licenseRenewalLink."'>Click to upgrade</a> <div class='SME_tooltip'><span class='SME_tooltiptext'>Upgrade to the full version to unlock all image settings, add video embedding capabilities, plus get premium support and updates for a full year!</span></div></div> ";	
			else if($licenseType!="Expired") {
				echo "Thank you for being a valued customer";
				//echo 
			}
			echo '<div id="SME_feedback">&nbsp;</div>';

			
			echo '<form id="SME_Settings-form">';

			echo "<div style='display:inline-block;'>";
			echo "<div style='display:flex;padding:5px 0px 5px 0px;' ><div style='width:220px;'>SmugMug Embed License Email: </div> <div><input id='license_email' size=45 /></div></div>";
			echo "<div style='display:flex;padding:5px 0px 5px 0px;' ><div style='width:220px;'>SmugMug Embed License Key: </div> <div><input id='license_key' size=45  /></div></div>";
			
			echo "</div>";
			submit_button( __( 'Save', 'text-domain' ) ); 
			echo "</form>";
			echo "</div>";
			echo "</div>";
		} else if ($active_tab == 'image_options') {
			if ($verified) $this->display_settings();
		} else if ($active_tab =='gellery_options') {
			if ($verified) $this->display_galleries();

		}
	}
	public function display_galleries() {
				?>
				<div  class="SME_Galleries">
				<div class="SME_Gallery_Instructions">
				<div class="SME_tooltip"><span class="SME_tooltiptext">Selected galleries will be available when selecting an image to insert into a post. Only images contained within these galleries will show.</span></div>Choose Galleries
				</div>
				<?php 
				$this->SME_SmugMugEmbed_options_gallery_display();
				?>
				</div></div></div>
	<?php
	}
	public function display_settings(){
		?>
			<div id="SME_feedback">&nbsp;
				</div>
			<div class="SME_Settings_form">
			  <form id="SME_Settings-form">
				<div class="SME_Gallery_Instructions">
				<div class="SME_tooltip"><span class="SME_tooltiptext">These settings control the default settings on each image embedded from SmugMug. They can be overwritten on an individual basis.</span></div>Editor Defaults
				</div>
				<div class="SME_row">
					<div class="SME_column">
						<label for="caption" >
							<?php esc_html_e( 'Caption', 'text-domain' ); ?>
							</label>
							<div class="SME_tooltip"><span class="SME_tooltiptext">Type the default caption. Use the macros to pull  information from the image in SmugMug. The caption can be edited in the editor for each image.</span></div>
							<textarea class='SME_txtDropTarget ui-droppable' rows=5 id='caption' placeholder="Enter the default caption. Use the macros to pull  information from the image in SmugMug."></textarea>
					</div>
					<div class="SME_column">
						<label for="alttext" >
							<?php esc_html_e( 'Alt Text', 'text-domain' ); ?>
							</label>
							<div class="SME_tooltip"><span class="SME_tooltiptext">Type the default alt text. Use the macros to pull  information from the image in SmugMug. The alt text can be edited in the editor for each image.</span></div>
							<textarea class='SME_txtDropTarget ui-droppable'  rows=5 id='alttext' placeholder="Type the default alt text. Use the macros to pull  information from the image in SmugMug."></textarea>
					</div>
					<div class="SME_column">
						<label for="title" >
							<?php esc_html_e( 'Title', 'text-domain' ); ?>
						</label>
						<div class="SME_tooltip"><span class="SME_tooltiptext">Type the default alt text. Use the macros to pull  information from the image in SmugMug. The alt text can be edited in the editor for each image.</span></div>
						<textarea class='SME_txtDropTarget ui-droppable'  rows=5 id='title' ></textarea>
						</div>
				</div>
				<div class="SME_row">
					<fieldset>
						<Legend>
							Drag to Insert
						</legend>
					<ul id='SME_dragwordlist'>
						<?php 
						foreach ($this->setting_macros as $macro){
							echo "<li class='ui-draggable'>$macro</li>";
						}
						?>
					</ul>
					</fieldset>

				</div>
				<div>
					<label for="editorimagesize">
						<?php esc_html_e( 'Editor Image Size', 'text-domain' ); ?>
					</label><div class="SME_tooltip"><span class="SME_tooltiptext">This is the default image size to display in the editor.</span></div>
					<select id="editorimagesize"/>
								 <?php 
			foreach (  SME_Helper::getAvailableSizes() as $size => $sizevalue ) {
				?>
				<option value="<?php echo $size ?>" ><?php echo $sizevalue ?></option>                            
				<?php
			}
			?>
					</select>			
				</div>		
				<div>
					<label for="defaultwidesize">
						<?php esc_html_e( 'Wide/Full Size', 'text-domain' ); ?>
					</label><div class="SME_tooltip"><span class="SME_tooltiptext">This is the image size which will be rendered from SmugMug when Full or Wide alignments are chosen. This should correspond to the width of your website in pixels.</span></div>
					<input  id="defaultwidesize" type="number" min="0" max="3200" />
				</div>	
				<div>
					<label for="defaultclickresponse">
						<?php esc_html_e('Default Click Response','text-domain'); ?>
					</label><div class="SME_tooltip"><span class="SME_tooltiptext">This is the default setting for the click response when inserting an image.</span></div>
					<select id="defaultclickresponse"/>
						<?php
						foreach (SME_Helper::getAvailableClickResponses() as $responses =>$responsevalue) {
							?>
							<option value="<?php echo $responses ?>" ><?php echo $responsevalue?></option>
							<?php
						}
						?>
					</select>
				</div>
				<div>
					<label for="defaultnewwindow">
						<?php esc_html_e('New Window by Default','text-domain'); ?>
					</label><div class="SME_tooltip"><span class="SME_tooltiptext">This is the default setting for the option to open a new window when an image is clicked in a post or page.</span></div>
					<input type="checkbox" id="defaultnewwindow" />
				</div>				
				<?php submit_button( __( 'Save', 'text-domain' ) ); ?>
			</form>
		</div>
		<?php
	}
	function SME_SmugMugEmbed_options_gallery_display() {
		global $SME_api, $SME_Settings, $SME_Helper;
		$selectedAlbums = get_option("SME_SelectedAlbums", array());
		if (is_array($selectedAlbums))
			echo "<script>var selectedAlbums = ". json_encode($selectedAlbums) .";</script>";		
		else 
			echo "<script>var selectedAlbums = [];</script>";	
				echo '<div id="SME_feedback">&nbsp;</div>';
		
		echo $SME_Helper->getGalleryAlbums($SME_api);

		echo "<div style='width:100%;padding-top:2px;text-align:center;border-top:2px solid #3c3e43;'><input type='button' class='button button-primary' value='Save Albums' onclick='SME_saveSelectedAlbums()' style='width:95%;'/></div>";

	}	
	
}

class SME_Settings {
	/**
	 * Option key to save settings
	 *
	 * @var string
	 */
	protected static $option_key = 'SME_Settings';
	protected static $license_key = 'SME_License';
	
	/**
	 * Default settings
	 *
	 * @var array
	 */
	protected static $defaults = array(
		'caption' => '',
		'alttext' => '',
		'title' => '',
		'editorimagesize' => 'M',
		'defaultwidesize' => 2000,
		'defaultclickresponse' => 'None',
		'defaultnewwindow' => '',
		'license_email' => '',
		'license_key' => '',
	);
	/**
	 * Get saved settings
	 *
	 * @return array
	 */
	public static function get_settings(){
		$saved = get_option( self::$option_key, array() );
		$license = get_option(self::$license_key,array());
		$saved = array_merge($saved,$license);
		if( ! is_array( $saved ) || empty( $saved )){
			return self::$defaults;
		}

		return wp_parse_args( $saved, self::$defaults );
	}
	/**
	 * Save settings
	 *
	 * Array keys must be whitelisted (IE must be keys of self::$defaults
	 *
	 * @param array $settings
	 */
	public static function save_settings( array  $settings ){
		global $SME_Helper;

		//remove any non-allowed indexes before save
		foreach ( $settings as $i => $setting ){
			if( !array_key_exists( $i, self::$defaults ) || empty($setting) ){
				unset( $settings[ $i ] );
			}
		}
		if (array_key_exists("license_email",$settings) || array_key_exists("license_key",$settings)) {
			update_option(self::$license_key,$settings);
			$SME_Helper->update_license(true);
		} else
		update_option( self::$option_key, $settings );
	}
}
class SME_Settings_API {

	/**
	 * Add routes
	 */
	public function add_routes( ) {
		register_rest_route( 'sme-api/v1', '/settings',
			array(
				'methods'         => 'POST',
				'callback'        => array( $this, 'update_settings' ),
				'args' => array(
					'editorimagesize' => array(
						'type' => "string",
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					),
					'defaultclickresponse' => array(
						'type' => "string",
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					),			
					'defaultnewwindow' => array(
						'type' => "string",
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					),	
					'caption' => array(
						'type' => "string",
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					),					
					'alttext' => array(
						'type' => 'string',
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					),
					'title' => array(
						'type' => 'string',
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					),					
					'amount' => array(
						'type' => 'integer',
						'required' => false,
						'sanitize_callback' => 'absint'
					),
					'defaultwidesize' => array(
						'type' => 'integer',
						'required' => false,
						'sanitize_callback' => 'absint'
					),
					'license_key' => array(
						'type' => 'string',
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					),
					'license_email' => array(
						'type' => 'string',
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					),					
				),
				'permission_callback' => array( $this, 'permissions' )
			)
		);
		register_rest_route( 'sme-api/v1', '/settings',
			array(
				'methods'         => 'GET',
				'callback'        => array( $this, 'get_settings' ),
				'args'            => array(
				),
				'permission_callback' => array( $this, 'permissions' )
			)
		);
	}
	/**
	 * Check request permissions
	 *
	 * @return bool
	 */
	public function permissions(){
		return current_user_can( 'manage_options' );
	}
	/**
	 * Update settings
	 *
	 * @param WP_REST_Request $request
	 */
	public function update_settings( WP_REST_Request $request ){
		global $SME_Helper;
		$settings = array(
			'editorimagesize' => $request->get_param( 'editorimagesize' ),
			'caption' => $request->get_param( 'caption' ),
			'alttext' => $request->get_param( 'alttext' ),
			'title' => $request->get_param( 'title' ),
			'defaultwidesize'=>$request->get_param( 'defaultwidesize' ),
			'defaultclickresponse'=>$request->get_param( 'defaultclickresponse' ),
			'defaultnewwindow'=>$request->get_param( 'defaultnewwindow' ),
			'license_email'=>$request->get_param( 'license_email' ),
			'license_key'=>$request->get_param( 'license_key' ),
		);
		SME_Settings::save_settings( $settings );
		
		return rest_ensure_response( SME_Settings::get_settings())->set_status( 201 );
	}
	/**
	 * Get settings via API
	 *
	 * @param WP_REST_Request $request
	 */
	public function get_settings( WP_REST_Request $request ){
		return rest_ensure_response( SME_Settings::get_settings());
	}
}