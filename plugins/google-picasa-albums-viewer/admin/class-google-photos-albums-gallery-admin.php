<?php

use App\GooglePhotoService;
use App\Photocontroller;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       nakunakifi.com
 * @since      4.0.0
 *
 * @package    Google_Photos_Albums_Gallery
 * @subpackage Google_Photos_Albums_Gallery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Google_Photos_Albums_Gallery
 * @subpackage Google_Photos_Albums_Gallery/admin
 * @author     Ian Kennerley <iankennerley@gmail.com>
 */
class Google_Photos_Albums_Gallery_Admin {

	var $debug = false;

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $isPro    Is this Pro version of plugin
	 */
	private $isPro;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $isPro ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->isPro = $isPro;

		// Include required files
		$this->includes();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function includes() {

		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::includes()' );
		
		if ( is_admin() ) $this->admin_includes();

		include_once( dirname(__FILE__) . '/../cws-gpp-functions.php' );				// TODO: split file out in admin and non-admin functions
		include_once( dirname(__FILE__) . '/../shortcodes/shortcode-init.php' );		// Init the shortcodes
		// include_once( dirname(__FILE__) . '/../widgets/widget-init.php' );				// Widget classes

		if( $this->isPro == 1 ) {
			add_shortcode( 'cws_gpp_images_by_albumid', 'cws_gpp_shortcode_images_in_album_google_photos' );  // new one, shortcode provides album id
			add_shortcode( 'cws_gpp_images_by_albumid_gp', 'cws_gpp_shortcode_images_in_album_google_photos' );  // Google Photos API, shortcode provides album id
		}
	}

    public function admin_includes() {

      if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::admin_includes()' );

		include_once( dirname(__FILE__) . '/../cws-gpp-functions.php' );				// TODO: split file out in admin and non-admin functions
	}

	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Google_Photos_Albums_Gallery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Google_Photos_Albums_Gallery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/google-photos-albums-gallery-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Google_Photos_Albums_Gallery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Google_Photos_Albums_Gallery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/google-photos-albums-gallery-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the Top Level Menu Page for the admin area.
	 * 
	 * @link    https://developer.wordpress.org/reference/functions/add_menu_page/
	 *
	 * @since    4.0.0
	 */    
	public function add_menu_page() {

		// $strIsPro = $this->get_Pro( $this->isPro );

		add_menu_page( 'Page Title', 'Google Photos', 'manage_options', 'cws_gpp', array( $this, 'options_page_fn') );
		// This adds top sub menu with different name to Top-Level Menu
		add_submenu_page( 'cws_gpp', 'Google Photos Settings', 'Settings', 'manage_options', 'cws_gpp', array( $this, 'options_page_fn') );
	} 

	/**
	 * Register the API credentials for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function add_options_submenu_page() {
		add_submenu_page( 'cws_gpp', 'Google Photos API Settings', 'API Settings', 'manage_options', 'cws_gapi', array( $this, 'options_page_gapi_fn') );
		add_submenu_page( 'cws_gpp', 'Google Photos Getting Started', 'Getting Started', 'manage_options', 'cws_gs', array( $this, 'options_page_gs_fn') );
        // add_submenu_page( 'cws_gpp', 'AlbumID Helper', 'Album ID Finder', 'manage_options', 'cws_gpaidh', array( $this, 'options_page_gpaidh_fn') );
        add_submenu_page( 'cws_gpp', 'Google Photos Album Shortcodes', 'Shortcode Examples', 'manage_options', 'cws_sc', array( $this, 'options_page_sc_fn') );
	}

	/**
	 * Register Settings, Settings Section and Settings Fileds.
	 * 
	 * @link    https://codex.wordpress.org/Function_Reference/register_setting
	 * @link    https://developer.wordpress.org/reference/functions/add_settings_section/
	 * @link    https://developer.wordpress.org/reference/functions/add_settings_field/
	 *
	 * @since    4.0.0
	 */
	// WIP
	public function register_settings()
	{	
		// Connect With Google Settings
		register_setting( 'cws_gpp_code', 'cws_gpp_code', array( $this, 'validate_options_fn' ) ); // the authorisation code from google
		add_settings_section( 'cws_gpp_add_code', 'Authenticate with Google', array( $this, 'missing_credentials_text_fn' ), 'cws_gpp_code' );
			// removing the line below broke the authorization!!! 26/6		
			add_settings_field( 'cws_myplugin_oauth2_code', '', array( $this, 'auth_code_input_fn' ), 'cws_gpp_code', 'cws_gpp_add_code' );

		// Plugin Options Settings
		register_setting( 'cws_gpp_options', 'cws_gpp_options', array( $this, 'validate_main_options_fn' ) ); // all the default setting options
		add_settings_section( 'cws_gpp_add_options', 'Default Settings', array( $this, 'section_main_text_fn' ), 'cws_gpp_defaults' );
			add_settings_field( 'cws_gpp_thumbnail_size', 'Thumbnail Size (px)', array( $this, 'options_thumbnail_size_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );	// Add default option field - Thumbnail Size 
			add_settings_field( 'cws_gpp_album_thumbnail_size', 'Album Thumbnail Size (px)', array( $this, 'options_album_thumbnail_size_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );	// Add default option field - Album Thumbnail Size 
		
			//if( $this->isPro )
			{
				// PRO ONLY
				// Add default option field - Lighbox Image Size 
				add_settings_field( 'cws_gpp_lightbox_image_size', 'Lightbox Image Size (px)', array( $this, 'options_lightbox_image_size_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );        
			}
			add_settings_field( 'cws_gpp_num_image_results', 'Number of images per page', array( $this, 'options_num_image_results_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );
			add_settings_field( 'cws_gpp_num_album_results', 'Number of albums per page', array( $this, 'options_num_album_results_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );
			add_settings_field( 'cws_gpp_show_album_title', 'Show Album Title', array( $this, 'options_show_album_title_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );   
			add_settings_field( 'cws_gpp_show_image_title', 'Show Image Title', array( $this, 'options_show_image_title_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' ); 
			add_settings_field( 'cws_gpp_show_album_details', 'Show Album Details', array( $this, 'options_show_album_details_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );   
			add_settings_field( 'cws_gpp_show_image_details', 'Show Image Details', array( $this, 'options_show_image_details_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' ); 
		
		if( $this->isPro ) {
			// PRO ONLY
			// Add default option checkbox - Enable Cache
			// add_settings_field( 'cws_gpp_enable_cache', 'Enable Cache', array( $this, 'options_enable_cache' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );
			// Add default option checkbox - Expose Original file
			// add_settings_field( 'cws_gpp_enable_download', 'Download Original Image Link', array( $this, 'options_enable_download_fn' ), 'cws_gpp_defaults', 'cws_gpp_add_options' );
		}

		// Deauthorise Google Account Settings
		register_setting( 'cws_gpp_reset', 'cws_gpp_reset', array( $this, 'validate_reset_options_fn' ) ); // deauth account
		add_settings_section( 'cws_gpp_add_reset', 'Deauthorise Plugin from your Google Account', array( $this, 'section_reset_text_fn' ), 'cws_gpp_reset' );
			add_settings_field( 'cws_gpp_reset', 'Click here to confirm you want to deauthorise plugin from your google account', array( $this, 'options_reset_fn' ), 'cws_gpp_reset', 'cws_gpp_add_reset' );   

		// API Settings
		register_setting( 'cws_gpp_gapi', 'cws_gpp_gapi', array( $this, 'validate_options_gapi_fn' ) );
		add_settings_section( 'cws_gpp_add_gapi', 'Client ID and Client Secret', array( $this, 'section_text_gapi_fn' ), 'cws_gpp_gapi' );
			add_settings_field( 'cws_gpp_client_id', 'Client Id', array( $this, 'options_client_id_fn' ), 'cws_gpp_gapi', 'cws_gpp_add_gapi' );
			add_settings_field( 'cws_gpp_client_secret', 'Client Secret', array( $this, 'options_client_secret_fn' ), 'cws_gpp_gapi', 'cws_gpp_add_gapi' );
		//
	}

	/**
	 * Draw the Options page for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function options_page_fn() {

		if($this->deauthorizeGoogleAccount())
		{
			// Delete all Google Account related options from database
			delete_option('cws_gpp_reset');
			delete_option('cws_gpp_code');
			delete_option('cws_gpp_token');
			delete_option('cws_gpp_gapi');
			delete_option('cws_gpp_refresh_token');
		}
		?>

		<div class="wrap">

		<?php if( $this->isPro ) { ?>
			<h2>Google Photos Pro Albums Gallery Settings</h2>
		<?php } else { ?>
			<h2>Google Photos Albums Gallery Settings</h2>
<?php } ?>

			<div class="widget-liquid-left">
				<form action="options.php" method="post">
					<?php 
					// Step 1:  Missing client id / client secret so cannot start authorization process
					if( $this->haveGapiSettings() === false ) 
					{
						echo "<strong>Missing Google API Credentials (client id / client secret).</strong><br>";
					?>
						<p>You must create Google Photo API credentials before authorising plugin.</p>
						<a href="<?php echo admin_url('admin.php?page=cws_gapi'); ?>" class="button-primary"><?php _e( 'Google Photos API Settings', 'cws_gpp_' ); ?></a>
					<?php
					}
					// we have client id / client secret and have authorised plugin 
					elseif ( $this->haveGapiSettings() === true && $this->isAuthenticated() == false ) 
					{
						settings_fields( 'cws_gpp_code' ); // adds a hidden form fields
						do_settings_sections( 'cws_gpp_code' ); 
					?>
						<!-- <input name="Submit" type="submit" value="Save Changes" />   -->
					</form> 
					<?php
					}

					// we have client id / client secret and have authorised plugin 
					elseif ( $this->haveGapiSettings() === true && $this->isAuthenticated() == true ) {
						/**
						 * User is authenticated so display plugin config settings
						 * 
						 */

						settings_fields( 'cws_gpp_options' );
						do_settings_sections( 'cws_gpp_defaults' );
					?>
						<input name="Submit" type="submit" value="Save Changes" />
						</form> 

						<form action="options.php" method="post">

							<?php
								settings_fields( 'cws_gpp_reset' );
								do_settings_sections( 'cws_gpp_reset' );  
							?>
							<input name="Submit" type="submit" value="Deauthorise" onclick="if(!this.form.reset.checked){alert('You must click the checkbox to confirm you want to deauthorize current Google account.');return false}" />

						</form>
				<?php
					}
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Draw the Options page for the API admin area.
	 *
	 * @since    4.0.0
	 */
	public function options_page_gapi_fn() {
		?>
		<div class="wrap">
			<h2>Google API Credentials</h2>
			<div class="widget-liquid-left">
				<form action="options.php" method="post">
				<?php
					settings_fields( 'cws_gpp_gapi' );
					do_settings_sections( 'cws_gpp_gapi' );
				?>
					<input name="Submit" type="submit" value="Save Changes" />  
				</form>
				<?php 
				// // try add accesstoken
				// // Grab code from url
				// if( isset($_GET['cws_code'])){
				// 	$cws_code = $_GET[ 'cws_code' ]; 
				// 	echo "$cws_code";
				// 	// need to store this in db
				// 	update_option( 'cws_gpp_code', $cws_code );
				// }
				?>

			</div>
		</div>
		<?php
	}


    /**
     * Draw the Options page for the admin area. This contains simple shortcode snippets
     *
     * @since    4.0.0
     */
    public function options_page_gs_fn() {
		?>
		<div class="wrap">
		 <?php // screen_icon(); ?>
		 <h2>Getting Started</h2>
 
		 <!-- <div class="widget-liquid-left"> -->
		 <div>
 
			 <form action="options.php" method="post">
 
				 <?php 
				 // Step 1:  The user has not authenticated we give them a link to login    
				 if( $this->isAuthenticated() !== true ) {
					 ?>
 
					 Not authenticated...
					 <!-- <input name="Submit" type="submit" value="Save Changes" />  -->
				 </form> 
				 <?php
			 } else {
				 ?>
				 <style>
				 span.sc-highlight{
					 background-color:#f7f7f7;padding:6px;border:1px solid #c2c2c2; border-radius:4px;
				 }
				 </style>
				 <div style="width: 95%;" class="postbox-container">
					 <h2>1. Basic Setup</h2>
					 <p>To have your albums covers on one page and display the your images from within the selected album on another page.</p>
					 <p>See example on <a href="http://wordpress-plugin-google-photos.cheshirewebsolutions.com/display-albums-grid/" target="_blank">demo site</a><!--  or <a href="https://youtu.be/grHI9sTCtZI" target="_blank">watch video</a> --> of what we are trying to achieve.</p>
					 
					 <p>First, it's important to realise that 2 shortcodes are needed for this. One shortcode (a) to display the album covers and and the second shortcode (b) to display the results of the clicked album.</p>
					 <p>Second, it's also important to realise that you must put the slug of the page from the second shortcode (b) into the first shortcode (a).</p>
 
					 <p>Stick with it, it's not nearly as complicated as it sounds.</p>
					 <br/>                 
					 <h3>Display Album Covers</h3>
 
					 <p><strong>(a) Shortcode to display album covers</strong></p>
					 <p> This is the basic shortcode <strong>[cws_gpp_albums_gphotos theme='grid' results_page='']</strong> to display your album covers.</p>
					 <p>Below is an example of the shortcode to display the album covers. Place the shortcode on a page and update the <span class="sc-highlight"><i>results_page='results-grid'</i></span> to the slug of the page where you place shortcode (b)</p>
					 <p><strong>[cws_gpp_albums_gphotos theme='grid' results_page='results-grid' show_title=1 show_details=1 num_results=6 ]</strong></p>
					 <p>The option <i>results_page='results-grid'</i> is set to the slug of the page where you place shortcode (b). So if you placed shortcode (b) on a page called 'images' then 
						 you would use <span class="sc-highlight"><i>results-page='images'</i></span></p>
 
						 <h4>Shortcode (a) Options</h4>
						 <p>The album title and details (Date created and number of images in album) can be hidden using options <span class="sc-highlight"><i>show_title=0</i></span> and <span class="sc-highlight"><i>show_details=0</i></span> respectively.</p>
						 <p>Control the number of albums covers per page using <span class="sc-highlight"><i>num_results=6</i></span>
							 <!-- <p>Hide unwanted albums using <span class="sc-highlight"><i>hide_albums='Auto Backup,Profile Photos'</i></span>, obviously replacing with the names of the albums you want to hide</p> -->
 
							 <br/> 
							 <h3>Display Images in Clicked Album</h3>
 
 
							 <p><strong>(b) Shortcode to display images in clicked album</strong></p>
							 <p>This is the basic shortcode to display images with the clicked album cover from (a) <strong>[cws_gpp_images_in_album_gphotos]</strong><!-- theme=grid show_title=1 show_details=1 album_title=1 --></p>
							 <h4>Shortcode (b) Options</h4>
							 <p>The album title can be hidden using option <span class="sc-highlight"><i>album_title=0</i></span></p>
							 <p>The image titles and details can be hidden using options <span class="sc-highlight"><i>show_title=0</i></span> and <span class="sc-highlight"><i>show_details=0</i></span> respectively.</p>
 
							 <br/> 
							 <!-- <h3>What is the <i>theme</i> option all about?</h3>
							 <p>This option controls the display of the Albums and Images, it has 3 options (grid, list, carousel)</p>
							 <p>To display albums / images in a <strong>grid</strong> format use <span class="sc-highlight"><i>theme=grid</i></span>
								 <p>To display albums / images in a <strong>list</strong> format use <span class="sc-highlight"><i>theme=list</i></span>
									 <p>To display albums / images in a <strong>carousel</strong> format use <span class="sc-highlight"><i>theme=carousel</i></span>
										 <p>This option is supported in both shortcode (a) and shortcode (b)</p> -->

										<?php // Only display shortcode snippets to Pro users...
											// if( $plugin->isPro == 1 ) {
											if( 1  ) {
 
										 ?>
										 <div class="metabox-holder">
 
											 <div class="postbo" id="settings">
												 <?php

												?>
											</div> <!-- / . postbox -->
 
							 </div> <!-- / meta holder -->
						 </div> <!-- / .postbox-container -->
						 <?php   } else { ?>
			 <?php       // Display upgrade content if not Pro
						 // echo $plugin_admin->cws_gpp_upgrade_content(); 
		 }
 
	 } 
 
	 ?>
 
 </div><!-- / left -->
 </div>
 <?php
	 } // end function options_page_gs()




	/**
	 * Draw the Options page for the Album ID Helper admin area.
	 *
	 * @since    4.0.2
	 */
	public function options_page_gpaidh_fn() {
		?>
		<div class="wrap">
			<h2>Album ID Helper</h2>
			<div class="widget-liquid-left">
				Album ID Helper text
			</div>
		</div>
		<?php
	}

	/**
	 * Draw the Options page for the Shortcode admin area.
	 *
	 * @since    4.0.0
	 */
	public function options_page_sc_fn() {
		?>
		<div class="wrap">
			<h2>Shortcode Examples</h2>
			<div class="widget-liquid-left">
				
			<h4>Display Album Covers in a Grid View</h4>
            <input size="100%" type="text" class="shortcode-in-list-table wp-ui-text-highlight code" value="[cws_gpp_albums_gphotos theme='progrid' results_page='page-slug-here']" readonly="readonly" onfocus="this.select();">  

			<h4>Display Images from Clicked Album Cover in a Grid View</h4>
			<p>The page slug from this results page goes in the 'results_page' option of the above shortcode</p>
            <input size="100%" type="text" class="shortcode-in-list-table wp-ui-text-highlight code" value="[cws_gpp_images_in_album_gphotos theme='grid']" readonly="readonly" onfocus="this.select();">  
			<?php
			if( $this->isPro ) {
				var_dump($this->isPro);
			?>
			<h4>Display Images in a Specific Album. Only one album per page.</h4>
			<input size="100%" type="text" class="shortcode-in-list-table wp-ui-text-highlight code" value="[cws_gpp_images_by_albumid_gp id='YOUR_ALBUM_ID_HERE' theme='progrid']" readonly="readonly" onfocus="this.select();">  
			<?php
			}
			?>
			</div>
		</div>
		<?php
	}


	function section_main_text_fn() {
		echo "These settings effect how your albums are displayed";
    }
    function section_reset_text_fn() {

    } 

	/**
	 * Draw the Section Header for the admin area.
	 *
	 * @since    4.0.0
	 */
	function missing_credentials_text_fn() {
		$code = get_option("cws_gpp_code");

		$photoController = new Photocontroller();
		$url = $photoController->start();

		$authUrl = "$url";

        echo 'Click the button at the bottom of this page to connect your Google account and display your&nbsp;photos.<br><br>';

			// TODO:
			// NOTE FOR ME - Do I really need the scope [Manage and add to shared albums on your behalf.] ???
			
			echo "<p><b>Important steps to follow when connecting your Google Account</b></p>";
		echo "<ol>
				<li>If you have multiple Google accounts, select the Google Account to associate plugin with</li>
				<li>Click 'Advanced'</li>
				<li>You should see a link to your WordPress site, click on that</li>
				<li>Select All checkbox for access</li>
				<li>Click Continue - you should then be redirected back to your WordPress Admin</li>
		</ol>";

		// display the google authorisation url
		echo $this->createAuthLink( $authUrl );
	}


    /**
     * Get the authourisation link.
     *
     * @since    4.0.0
     */     
    public function createAuthLink( $authUrl ) {

        if ( isset( $authUrl ) ) {
            $output = "<br><a class='login button-primary' href='$authUrl' target='_blank'>Connect My Google Account</a>"; 
        } else {
            $output = "There was a problem generating the Google Autherisation link";
        }

        return $output;
    }

	/**
	 * Draw the Section Header for the admin area.
	 *
	 * @since    4.0.0
	 */
	function section_text_gapi_fn() {
		echo "You must store these credentials before authorizing the plugin.<br>";
		echo "Click here for <a href='https://www.cheshirewebsolutions.com/create-google-photos-api-credentials/' target='_blank'>instructions on how to create credentials</a><br>";
		$redirectUri = get_admin_url(null, 'admin.php?page=cws_gpp');
		echo "<br>You will need to copy this url <b>{$redirectUri }</b> into the Authorised redirect URIs input field on the Google API credentials page.<br><br>";

		// echo "<h2>Redirect Uri</h2>{$redirectUri}cws_gpp";
	}



	/* ---------------------------------------
			settings fields
	--------------------------------------- */

	/**
	 * Display and fill the form field.
	 *
	 * @since    4.0.0
	 */
	function auth_code_input_fn() {
		
		// Add accesstoken
		// Grab code from url
		if( isset($_GET['code'])){
			// $code = $_GET[ 'code' ]; 
			// Safely Grab code from url
			$code = sanitize_text_field( $_GET['code'] );
			
			// store this in db
			if( update_option( 'cws_gpp_code', $code ) == TRUE)
			{
				$service = new GooglePhotoService();
				$token = $service->getAccessToken($code);
				var_dump( $token );
				//die();
				// need to save token to db
				update_option("cws_gpp_token", $token);
				// also save refresh token seperately
				if (!get_option('cws_gpp_refresh_token')){
					update_option("cws_gpp_refresh_token", $token['refresh_token']);
				}

				wp_redirect( admin_url( 'admin.php?page=cws_gpp'));
			}
			else{
				echo "NOT Updated!";
			}
			
		}

		// Don't need this input anymore as all handled above
		// echo "<input id='oauth2_code' name='cws_gpp_code[oauth2_codeggg]' type='text' value='$xxxcode' >";
	}


	/**
	 * Display and fill the form fields for storing defaults.
     *
     * Thumbnail Size in pixels
	 *
	 * @since    4.0.0
	 */
	function options_thumbnail_size_fn() {

		// get option 'thumb_size' value from the database
		$options = get_option( 'cws_gpp_options' );
		$thumb_size = $options['thumb_size'];

		echo "<input id='thumb_size' name='cws_gpp_options[thumb_size]' type='text' value='$thumb_size' >";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Album Thumbnail Size in pixels
	 *
	 * @since    4.0.0
	 */
	function options_album_thumbnail_size_fn() {

		// get option 'album_thumb_size' value from the database
		$options = get_option( 'cws_gpp_options' );
		$album_thumb_size = $options['album_thumb_size'];

		echo "<input id='album_thumb_size' name='cws_gpp_options[album_thumb_size]' type='text' value='$album_thumb_size' >";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Lightbox Image Size in pixels
	 *
	 * @since    4.0.0
	 */
	function options_lightbox_image_size_fn() {

		// get option 'lightbox_image_size' value from the database
		$options = get_option( 'cws_gpp_options' );
		$lightbox_image_size = $options['lightbox_image_size'];

		echo "<input id='lightbox_image_size' name='cws_gpp_options[lightbox_image_size]' type='text' value='$lightbox_image_size' >";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Number of images results to display per page
	 *
	 * @since    4.0.0
	 */
	function options_num_image_results_fn() {

		// get option 'num_image_results' value from the database
		$options = get_option( 'cws_gpp_options' );
		$num_image_results = $options['num_image_results'];

		echo "<input id='num_image_results' name='cws_gpp_options[num_image_results]' type='text' value='$num_image_results' >";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Number of albums results to display per page
	 *
	 * @since    4.0.0
	 */    
	function options_num_album_results_fn() {

		// get option 'num_album_results' value from the database
		$options = get_option( 'cws_gpp_options' );
		$num_album_results = $options['num_album_results'];

		echo "<input id='num_album_results' name='cws_gpp_options[num_album_results]' type='text' value='$num_album_results' >";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Show Album Title
	 *
	 * @since    4.0.0
	 */
	function options_show_album_title_fn() {

		// set some defaults...
		$checked = '';

		// get option 'show_album_title' value from the database
		$options = get_option( 'cws_gpp_options' );
		$show_album_title = $options['show_album_title'];

		if($show_album_title) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='show_album_title' name='cws_gpp_options[show_album_title]' type='checkbox' />";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Show Image Title
	 *
	 * @since    4.0.0
	 */
	function options_show_image_title_fn() {

		// set some defaults...
		$checked = '';

		// get option 'show_image_title' value from the database
		$options = get_option( 'cws_gpp_options' );
		$show_album_title = $options['show_image_title'];

		if($show_album_title) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='show_image_title' name='cws_gpp_options[show_image_title]' type='checkbox' />";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Show Album Details
	 *
	 * @since    4.0.0
	 */

	function options_show_album_details_fn() {

		// set some defaults...
		$checked = '';

		// get option 'show_album_details' value from the database
		$options = get_option( 'cws_gpp_options' );
		$show_album_details = $options['show_album_details'];

		if($show_album_details) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='show_album_details' name='cws_gpp_options[show_album_details]' type='checkbox' />";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Show Image Details
	 *
	 * @since    2.0.0
	 */
	function options_show_image_details_fn() {

		// set some defaults...
		$checked = '';

		// get option 'show_album_details' value from the database
		$options = get_option( 'cws_gpp_options' );
		$options['show_image_details'] = isset($options['show_image_details']) ? $options['show_image_details'] : "";
		// $show_image_details = $options['show_image_details'];

		if($options['show_image_details']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='show_image_details' name='cws_gpp_options[show_image_details]' type='checkbox' />";
	}

    /**
     * Enable Download
     *
     * Exposes download link next to each thumbnail to allow user to access original image file
     * Pro Only
     *
     * @since    4.0.0
     */
	function options_enable_download_fn() {

		// set some defaults...
		$checked = '';

		// get option 'enable_download' value from the database
		$options = get_option( 'cws_gpp_options' );

		$options['enable_download'] = isset($options['enable_download']) ? $options['enable_download'] : "";

		//$enable_cache = $options['enable_download'];

		if($options['enable_download']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='enable_download' name='cws_gpp_options[enable_download]' type='checkbox' /><small>Allow user to download original image file.</small>";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Show Album Details
	 *
	 * @since    2.0.0
	 */    
	function options_reset_fn() {

		// set some defaults...
		$checked = '';

		// get option 'show_album_details' value from the database
		$options = get_option( 'cws_gpp_reset' );

		if($options) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='reset' name='cws_gpp_reset[reset]' type='checkbox' required />";
	}


	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Client Id
	 *
	 * @since    3.1.6
	 */
	function options_client_id_fn() {
		$client_id = '';
		// get option 'client_id' value from the database
		$options = get_option( 'cws_gpp_gapi' );
		if(isset($options['client_id']) && !empty($options['client_id'])){
			$client_id = $options['client_id'];
		}

		echo "<input id='client_id' name='cws_gpp_gapi[client_id]' type='text' value='$client_id' size='100%'>";
	}

	/**
	 * Display and fill the form fields for storing defaults.
	 *
	 * Client Secret
	 *
	 * @since    4.0.0
	 */    
	function options_client_secret_fn() {
		$client_secret = '';
		// get option 'client_secret' value from the database
		$options = get_option( 'cws_gpp_gapi' );
		if(isset($options['client_secret']) && !empty($options['client_secret'])){
			$client_secret = $options['client_secret'];
		}

		echo "<input id='client_secret' name='cws_gpp_gapi[client_secret]' type='text' value='$client_secret' size='100%'>";
	} 	


	/* ---------------------------------------
			validation functions
	--------------------------------------- */
	/**
	 * Validate user input (we want text only).
	 *
	 * @since    4.0.0
	 */        
	function validate_options_fn( $input ) {
		// var_dump($input);
		// $valid['oauth2_code'] = esc_attr ( $input['oauth2_code'] );
		$valid = esc_attr ( $input );
		return $valid;
	}

	/**
	 * Validate user input.
	 *
	 * @since    4.0.0
	 */
	function validate_main_options_fn( $input ) {

		$valid['thumb_size']            = esc_attr( $input['thumb_size'] );
		$valid['album_thumb_size']      = esc_attr( $input['album_thumb_size'] );
		$valid['num_image_results']     = esc_attr( $input['num_image_results'] );
		$valid['num_album_results']     = esc_attr( $input['num_album_results'] );
		$valid['lightbox_image_size']   = esc_attr( $input['lightbox_image_size'] );
		// $valid['private_albums']        = esc_attr( $input['private_albums'] );

		// Correct validation of checkboxes
		$valid['show_album_title'] = ( isset( $input['show_album_title'] ) && true == $input['show_album_title'] ? true : false );
		$valid['show_album_details'] = ( isset( $input['show_album_details'] ) && true == $input['show_album_details'] ? true : false );

		$valid['show_image_title'] = ( isset( $input['show_image_title'] ) && true == $input['show_image_title'] ? true : false );
		//$valid['enable_cache'] = ( isset( $input['enable_cache'] ) && true == $input['enable_cache'] ? true : false );
		$valid['show_image_details'] = ( isset( $input['show_image_details'] ) && true == $input['show_image_details'] ? true : false );
		$valid['enable_download'] = ( isset( $input['enable_download'] ) && true == $input['enable_download'] ? true : false );

		return $valid;
	}

	/**
	 * Validate user input.
	 *
	 * @since    4.0.0
	 */
	function validate_reset_options_fn( $input ) {

		// Correct validation of checkboxes
		$valid['reset'] = ( isset( $input['reset'] ) && true == $input['reset'] ? true : false );

		return $valid;
	}


	/**
	 * Validate user input.
	 *
	 * @since    4.0.0
	 */
	// WIP
    function validate_options_gapi_fn( $input ) {    

        $valid['client_id']            = esc_attr( trim( $input['client_id'] ) );
        $valid['client_secret']        = esc_attr( trim( $input['client_secret'] ) );

        return $valid;
    } 	


    // Dispay upgrade notice
    function cws_gpp_admin_installed_notice( $userObj ) {

        // var_dump($userObj->ID);

            // check if already Pro
        if( !$this->isPro ) {

                // Check if user has dismissed notice previously
                // if ( ! get_user_meta( $current_user->getID(), 'cws_gpp_ignore_upgrade' ) ) 
            if ( ! get_user_meta( $userObj->ID, 'cws_gpp_ignore_upgrade' ) ) {
                global $pagenow;
                    // Only show upgrade notice if on this page
                if ( $pagenow == 'options-general.php' || $pagenow == 'admin.php' ) {
                    ?>
                    <div id="message" class="updated cws-gpp-message">
                        <div class="squeezer">
                            <h4><?php _e( '<strong>Google Photos Viewer has been installed &#8211; Get the Pro version</strong>', 'cws_gpp_' ); ?></h4>
                            <h4><?php _e( '<strong>GET 20% OFF! &#8211; use discount code WPMEGA20 on checkout</strong>', 'cws_gpp_' ); ?></h4>
                            <p class="submit">
                                <a href="http://www.cheshirewebsolutions.com/?utm_source=cws_gpp_config&utm_medium=button&utm_content=upgrade_notice_message&utm_campaign=cws_gpp_plugin" class="button-primary"><?php _e( 'Visit Site', 'cws_gpp_' ); ?></a>
                                <a href="<?php echo admin_url('admin.php?page=cws_gpp'); ?>" class="button-primary"><?php _e( 'Settings', 'cws_gpp_' ); ?></a>
                                <a href="?cws_gpp_ignore_upgrade=0" class="secondary-button">Hide Notice</a>
                            </p>
                        </div>
                    </div>
                    <?php
                }                
                } // end check if already dismissed

            } // end isPro check

            // Set installed option
            //update_option( 'cws_gpp_installed', 0);
        }

    // Allow user to dismiss upgrade notice :)
	function cws_gpp_ignore_upgrade( $userObj2 ) {   

		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET['cws_gpp_ignore_upgrade'] ) && '0' == $_GET['cws_gpp_ignore_upgrade'] ) {
		// add_user_meta($current_user->ID, 'cws_gpp_ignore_upgrade', 'true', true);
			add_user_meta($userObj2->ID, 'cws_gpp_ignore_upgrade', 'true', true);

		// Redirect to plugin settings page
			wp_redirect( admin_url( 'admin.php?page=cws_gpp' ) );
		}
	}   

	/* ---------------------------------------
			helper functions
	--------------------------------------- */
	// Do we have client id and client secret
	// return true
	public function haveGapiSettings(){

		// get options from db
		$gapi = get_option( 'cws_gpp_gapi' );
		
		if ( !empty( $gapi['client_id'] ) && !empty( $gapi['client_secret'] ) ) {
			// echo "we have gapi settings<br>";
			// show authorization code

			return true;
		} else {
			// echo "MISSING gapi settings!<br>";
			// show link to GAPI page and website instructions how to get client secret / client id
			return false;
		}

		return false;
	} 

	/**
	 * Get the Reset option stored in the db.
	 */
	public function deauthorizeGoogleAccount(){
		// get option from db
		if(get_option('cws_gpp_reset')){
			return true;
		}
	}

    public function isAuthenticated(){
		// if we have an auth code and access_token we can assume we are authenticated 
		// could I derive this from GooglePhotoService instead?
		// get options from db
        $token = get_option( 'cws_gpp_token' );

		if( isset($token['access_token']) && isset($token['refresh_token'])){
			return true;
		}

		return false;
    }

}
/*
class WP_PM_User extends WP_User {

	function getID() {
		return $this->ID;
	}

}


class WP_PM {

  protected $user;

  function __construct ( WP_PM_User $user = NULL) {
	if ( ! is_null( $user ) && $user->exists() ) $this->user = $user;
	}

	function getUser() {
		return $this->user;
	}

}
*/
