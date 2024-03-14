<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectAdminPanel.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectCustomFields.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectShortCodes.php' );

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetActivityFeed.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetComments.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetFacepile.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetLikeBox.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetLikeButton.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetLiveStream.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetLoginButton.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetRecommendations.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/widgets/WordpressConnectWidgetSendButton.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 16 Apr 2011
 *
 * @file WordpressConnect.php
 *
 * This class serves as the main class for the Wordpress Connect
 * wordpress plugin
 */
class WordpressConnect {

	/**
	 * @var WordpressConnectAdminPanel
	 */
	var $adminPanel;

	/**
	 * @var WordpressConnectCustomFields
	 */
	var $customFields;

	/**
	 * @var WordpressConnectShortCodes
	 */
	var $shortcodes;

	/**
	 * Creates a new WordpressConnect object
	 *
	 * @since	1.0
	 *
	 */
	function WordpressConnect(){

		$this->add_init_hook();

	}

	/**
	 * This function is executed when the plugin is actived.
	 *
	 * @since	1.01
	 */
	function activate(){

		//$this->deactivate();

		$current_version = FALSE;
		$general_options = $options[ WPC_OPTIONS ];
		
		if ( $general_options !== FALSE && isset( $general_options[ 'WPC_VERSION' ] ) ){
			$current_version = $general_options[ 'WPC_VERSION' ];		
		}
		
		if ( $current_version === FALSE || version_compare( $current_version, "2.0" ) == -1 ){
			
			// get the options from the old version
			$options = WordpressConnect::getOldVersionOptions();
			// port the options into the new version
			WordpressConnect::setOptions( $options );
			
		}		
		else {

			$options = WordpressConnect::getDefaultOptions();
			
			// apply the options from the current theme
			$options = apply_filters( 'wp_connect_options', $options );
			WordpressConnect::setOptions( $options );
			
		}
	}

	/**
	 * This function is executed when the plugin is deactived.
	 *
	 * @since	1.01
	 */
	function deactivate(){

		//update_option( WPC_OPTIONS, array() );
		//update_option( WPC_OPTIONS_COMMENTS, array() );
		//update_option( WPC_OPTIONS_LIKE_BUTTON, array() );

	}

// ---------------------------- WP Hooks - START ------------------------- //

	/**
	 * Adds init wordpress hook.
	 *
	 * @since	1.01
	 */
	function add_init_hook(){

		// Hooks need to be always added so that the plugins in the content
		// can be rendered
		add_action( 'init', array( &$this, 'add_wp_hooks' ) );

		$this->add_admin_panel();
		$this->add_custom_fields();
					
		$this->register_shortcodes();

		add_action( 'wp_head', array( &$this, 'add_og_meta' ) );
		add_action( 'wp_footer', array( &$this, 'add_fb_js' ) );

		add_action( 'switch_theme', array( &$this, 'switch_theme_handler' ) );

		add_action( 'widgets_init', array( &$this, 'load_widget' ) );
	}

	/**
	 * Adds custom fields.
	 *
	 * @access private
	 * @since	2.0
	 */
	function add_custom_fields(){

	    $this->customFields = new WordpressConnectCustomFields();

	}

	/**
	 * Registers shortcodes for this plugin
	 *
	 * @access 	private
	 * @since	2.0
	 */
	function register_shortcodes(){

		$this->shortcodes = new WordpressConnectShortCodes();

	}

	/**
	 * Adds wordpress hooks (and filters) necessary for this plugin
	 *
	 * @access private
	 * @since	1.01
	 */
	function add_wp_hooks(){

		$comments = new WordpressConnectComments();
		$like = new WordpressConnectLikeButton();

	}

	/**
	 * Adds plugin's admin panel to the wp dashboard
	 *
	 * @access private
	 * @since	1.0
	 */
	function add_admin_panel(){

		$this->adminPanel = new WordpressConnectAdminPanel();

	}

	/**
	 * Registers the Wordpress Connect widget
	 *
	 * @since	1.0
	 */
	function load_widget() {

		register_widget( 'WordpressConnectWidgetActivityFeed' );
		register_widget( 'WordpressConnectWidgetComments' );
		register_widget( 'WordpressConnectWidgetFacepile' );
		register_widget( 'WordpressConnectWidgetLikeBox' );
		register_widget( 'WordpressConnectWidgetLikeButton' );
		register_widget( 'WordpressConnectWidgetLiveStream' );
		register_widget( 'WordpressConnectWidgetLoginButton' );
		register_widget( 'WordpressConnectWidgetRecommendations' );
		register_widget( 'WordpressConnectWidgetSendButton' );

	}

	/**
	 * Adds open graph facebook meta data
	 * @since	2.0
	 */
	function add_og_meta(){

		global $post;

		$options = get_option( WPC_OPTIONS );

		$image_url = $options[ WPC_OPTIONS_IMAGE_URL ];

		// check if the post has a feature image set and if the
		// feature image is at least 50x50 and has aspect ratio of 3:1 at most
		if ( function_exists( 'has_post_thumbnail' ) ){
			if ( has_post_thumbnail( $post->ID ) ){
	
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' );
	
				$image_width = $image[1];
				$image_height = $image[2];
				if ( floatval( $image_height ) > 0 ){
					$aspect_ratio = floatval( $image_width ) / floatval( $image_height );
					
					if ( $image_width >= 50 && $image_height >= 50 && $aspect_ratio <= 3.0 ){
						$image_url = $image[0];
					}
				}
			}
		}

		$separator = '|';
		$title = get_bloginfo( 'name' ) . wp_title( $separator, FALSE, 'left' );

		$description = str_replace( "\n", " ", $options[ WPC_OPTIONS_DESCRIPTION ] );
		$url = home_url();
		$type = 'blog';

		if( !is_home() ){

			$url = get_permalink( $post->ID );
			$type = 'website';

			if ( !empty( $post->post_excerpt ) ){
				$description = $post->post_excerpt;
			}
			else {
				$description = $post->post_content;
			}

			if ( is_page() ){}
			elseif( is_sticky() ){}
			elseif( is_attachment() ){}
			elseif ( is_tag() ){

				$tagname = get_query_var( 'tag' );
				$tags = get_tags( 'slug=' . $tagname );
				$tag = $tags[0];

				$description = $tag->description;
				$url = get_tag_link( $tag->term_id );

			}
			elseif ( is_single() ){
				$type = 'article';
			}
			elseif ( is_category() ){

				$categories = get_the_category();
				$category = $categories[0];

				$description = $category->category_description;
				$url = get_category_link( $category->cat_ID );

			}
			elseif ( is_archive() ){

				$description = $title;
				$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

			}
		}

		$description = $this->format_description( $post->post_content );

		// make sure there is a title for the url linter
		if ( empty( $title ) ){ $title = get_home_url(); }

		$title = htmlentities( $title, ENT_QUOTES, "UTF-8" );
?>
		<!-- Wordpress Connect v<?php echo WPC_VERSION; ?> - Open Graph Meta START -->
		<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>" />
		<?php if ( !empty( $image_url ) ) : ?><meta property="og:image" content="<?php echo $image_url; ?>" /><?php endif; ?>

		<meta property="fb:admins" content="<?php echo $options[ WPC_OPTIONS_APP_ADMINS ]; ?>" />
		<meta property="fb:app_id" content="<?php echo $options[ WPC_OPTIONS_APP_ID ]; ?>" />
		<meta property="og:title" content="<?php echo $title; ?>" />
		<meta property="og:type" content="<?php echo $type; ?>" />
		<meta property="og:description" content="<?php echo $description; ?>" />
		<meta property="og:url" content="<?php echo $url ?>" />
		<!-- Wordpress Connect - Open Graph Meta END -->
<?php

	}

	/**
	 * Formats the description string. The function removes shortcodes and
	 * html tags and trims to description so that it ends at the end of the
	 * sentences passed the 200th character (if the content is longer than
	 * that '...' is inserted at the end)
	 *
	 * @param string $description	The description to format
	 *
	 * @return string 	formatted description
	 *
	 * @access 	private
	 * @since 	2.0
	 */
	private function format_description( $description ){

		$description = strip_tags( strip_shortcodes( $description ) );
		if ( strlen( $description ) > 200 ){
			$split_position = strpos( $description, '.', 200 );
			if ( $split_position === FALSE ){
				$split_position = strpos( $description, ' ', 200 ); 
			}
			$description = substr( $description, 0, $split_position + 1 ) . ' ...';
		}
		return htmlentities( $description, ENT_QUOTES, "UTF-8" );

	}

	/**
	 * Adds facebok plugins javascript
	 *
	 * @since	1.0
	 */
	function add_fb_js(){

		$options = get_option( WPC_OPTIONS );

		$protocol = ( isset( $_SERVER['HTTPS'] ) ) ? 'https' : 'http';
?>
	<!-- Wordpress Connect FBJS v<?php echo WPC_VERSION; ?> - START -->
		<div id="fb-root"></div>
		<script>
            window.fbAsyncInit = function() {
                if ( FB && FB.init ){
            		FB.init( {appId: '<?php echo $options[ WPC_OPTIONS_APP_ID ]; ?>', status: true, cookie: true, xfbml: true });
                }
            };
            (function() {
                var e = document.createElement('script'); e.async = true;
                e.src = "<?php echo $protocol; ?>://connect.facebook.net/<?php echo $options[ WPC_OPTIONS_LANGUAGE ]; ?>/all.js";
                document.getElementById('fb-root').appendChild(e);
            }());
		</script>
	<!-- Wordpress Connect FBJS - END -->
 		
<?php

	}

	/**
	 * Handles theme switching. Enables theme developers to specify
	 * default options by adding the '
	 *
	 * @param string $theme		The name of the new theme
	 */
	public function switch_theme_handler( $theme ){

		$options = WordpressConnect::getCurrentOptions();

		$options = apply_filters( 'wp_connect_options', $options );

		WordpressConnect::setOptions( $options );

	}

	/**
	 * Returns an array containing default options
	 *
	 * @access 	public
	 * @return 	an array containing default options
	 * @since 	2.0
	 * @static
	 */
	public static function getDefaultOptions(){

		$options = array(
			WPC_OPTIONS => array(
				WPC_OPTIONS_LANGUAGE => 'en_US',
				WPC_OPTIONS_THEME => WPC_THEME_LIGHT
			),
			WPC_OPTIONS_COMMENTS => array(
				WPC_OPTIONS_COMMENTS_NUMBER => 6,
				WPC_OPTIONS_COMMENTS_WIDTH => 480,
				WPC_OPTIONS_COMMENTS_POSITION => WPC_CUSTOM_FIELD_VALUE_POSITION_BOTTOM,
				WPC_OPTIONS_COMMENTS_ENABLED => WPC_OPTION_ENABLED,
				WPC_OPTIONS_DISPLAY_EVERYWHERE => 'on',
				WPC_OPTIONS_DISPLAY_NOWHERE => ''
			),
			WPC_OPTIONS_LIKE_BUTTON => array(
				WPC_OPTIONS_LIKE_BUTTON_SEND => WPC_OPTION_ENABLED,
				WPC_OPTIONS_LIKE_BUTTON_LAYOUT => WPC_LAYOUT_STANDARD,
				WPC_OPTIONS_LIKE_BUTTON_WIDTH => 480,
				WPC_OPTIONS_LIKE_BUTTON_FACES => WPC_OPTION_ENABLED,
				WPC_OPTIONS_LIKE_BUTTON_VERB => WPC_ACTION_LIKE,
				WPC_OPTIONS_LIKE_BUTTON_FONT => WPC_FONT_DEFAULT,
				WPC_OPTIONS_LIKE_BUTTON_POSITION => WPC_CUSTOM_FIELD_VALUE_POSITION_TOP,
				WPC_OPTIONS_LIKE_BUTTON_ENABLED => WPC_OPTION_ENABLED,
				WPC_OPTIONS_DISPLAY_EVERYWHERE => 'on'
			)
		);

		return $options;

	}

	/**
	 * Returns an array containing the current options
	 *
	 * @return an array containing the current options
	 *
	 * @access	private
	 * @since 	2.0
	 * @static
	 */
	public static function getCurrentOptions(){

		$general_options = get_option( WPC_OPTIONS );
		$comments_options = get_option( WPC_OPTIONS_COMMENTS );
		$like_button_options = get_option( WPC_OPTIONS_LIKE_BUTTON );

		return array(
			WPC_OPTIONS => $general_options,
			WPC_OPTIONS_COMMENTS => $comments_options,
			WPC_OPTIONS_LIKE_BUTTON => $like_button_options
		);

	}

	/**
	 * Returns an array containing the options from version 1.x
	 * parsed into the correct options array compatible with 2.0
	 *
	 * @return an array containing the options from version 1.x
	 *
	 * @access	private
	 * @since 	2.0
	 * @static
	 */
	public static function getOldVersionOptions(){

		$options = WordpressConnect::getDefaultOptions();

		// general options
		$language = get_option( WPC_OPTIONS_LANGUAGE );
		if ( $language !== FALSE ){
			$options[ WPC_OPTIONS ][ WPC_OPTIONS_LANGUAGE ] = $language; 
			delete_option( WPC_OPTIONS_LANGUAGE ); 
		}

		$app_id = get_option( WPC_OPTIONS_APP_ID );
		if ( $app_id !== FALSE ){ 
			$options[ WPC_OPTIONS ][ WPC_OPTIONS_APP_ID ] = $app_id;
			delete_option( WPC_OPTIONS_APP_ID ); 
		}

		$app_admins = get_option( WPC_OPTIONS_APP_ADMINS );
		if ( $app_admins !== FALSE ){ 
			$options[ WPC_OPTIONS ][ WPC_OPTIONS_APP_ADMINS ] = $app_admins;
			delete_option( WPC_OPTIONS_APP_ADMINS ); 
		}

		$image = get_option( WPC_OPTIONS_IMAGE_URL );
		if ( $image !== FALSE ){ 
			$options[ WPC_OPTIONS ][ WPC_OPTIONS_IMAGE_URL ] = $image;
			delete_option( WPC_OPTIONS_IMAGE_URL ); 
		}

		$description = get_option( WPC_OPTIONS_DESCRIPTION );
		if ( $description !== FALSE ){
			$options[ WPC_OPTIONS ][ WPC_OPTIONS_DESCRIPTION ] = $description;
			delete_option( WPC_OPTIONS_DESCRIPTION ); 
		}

		// comments

		$comments_number = get_option( WPC_OPTIONS_COMMENTS_NUMBER );
		if ( $comments_number !== FALSE && is_int( $comments_number ) ){
			$options[ WPC_OPTIONS_COMMENTS ][ WPC_OPTIONS_COMMENTS_NUMBER ] = $comments_number;
			delete_option( WPC_OPTIONS_COMMENTS_NUMBER );
		}

		$comments_width = get_option( WPC_OPTIONS_COMMENTS_WIDTH );
		if ( $comments_width !== FALSE && is_int( $comments_width ) ){
			$options[ WPC_OPTIONS_COMMENTS ][ WPC_OPTIONS_COMMENTS_WIDTH ] = $comments_width;
			delete_option( WPC_OPTIONS_COMMENTS_WIDTH );
		}

		$comments_display_homepage = get_option( WPC_OPTIONS_COMMENTS_SHOW_ON_HOMEPAGE );
		if ( $comments_display_homepage !== FALSE && !empty( $comments_display_homepage ) ){
			$options[ WPC_OPTIONS_COMMENTS ][ WPC_OPTIONS_DISPLAY_HOMEPAGE ] = 'on';
		}
		else { $options[ WPC_OPTIONS_COMMENTS ][ WPC_OPTIONS_DISPLAY_HOMEPAGE ] = ''; }
		delete_option( WPC_OPTIONS_COMMENTS_SHOW_ON_HOMEPAGE );

		$comments_display_categories = get_option( WPC_OPTIONS_COMMENTS_SHOW_ON_CATEGORIES );
		if ( $comments_display_homepage !== FALSE && !empty( $comments_display_homepage ) ){
			$options[ WPC_OPTIONS_COMMENTS ][ WPC_OPTIONS_DISPLAY_CATEGORIES ] = 'on';
		}
		else { $options[ WPC_OPTIONS_COMMENTS ][ WPC_OPTIONS_DISPLAY_CATEGORIES ] = ''; }
		delete_option( WPC_OPTIONS_COMMENTS_SHOW_ON_CATEGORIES );
		
		// like button

		$like_layout = get_option( WPC_OPTIONS_LIKE_BUTTON_LAYOUT );
		if ( $like_layout !== FALSE ){ 
			$options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_LIKE_BUTTON_LAYOUT ] = $like_layout; 
			delete_option( WPC_OPTIONS_LIKE_BUTTON_LAYOUT );
		}

		$like_width = get_option( WPC_OPTIONS_LIKE_BUTTON_WIDTH );
		if ( $like_width !== FALSE && is_int( $like_width ) ){
			$options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_COMMENTS_WIDTH ] = $like_width;
			delete_option( WPC_OPTIONS_LIKE_BUTTON_WIDTH );
		}

		$like_show_faces = get_option( WPC_OPTIONS_LIKE_BUTTON_SHOW_FACES );
		if ( $like_show_faces !== FALSE && !empty( $like_show_faces ) ){
			$options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_LIKE_BUTTON_FACES ] = WPC_OPTION_ENABLED;
		}
		else { $options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_LIKE_BUTTON_FACES ] = WPC_OPTION_DISABLED; }
		delete_option( WPC_OPTIONS_LIKE_BUTTON_SHOW_FACES );
		
		$like_verb = get_option( WPC_OPTIONS_LIKE_BUTTON_VERB );
		if ( $like_verb !== FALSE ){
			$options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_LIKE_BUTTON_VERB ] = $like_verb;
			delete_option( WPC_OPTIONS_LIKE_BUTTON_VERB );
		}

		$like_font = get_option( WPC_OPTIONS_LIKE_BUTTON_FONT );
		if ( $like_font !== FALSE ){
			$options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_LIKE_BUTTON_FONT ] = $like_font;
			delete_option( WPC_OPTIONS_LIKE_BUTTON_FONT );
		}

		$like_display_homepage = get_option( WPC_OPTIONS_LIKE_BUTTON_SHOW_ON_HOMEPAGE );
		if ( $like_display_homepage !== FALSE && !empty( $like_display_homepage ) ){
			$options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_DISPLAY_HOMEPAGE ] = 'on';
		}
		else { $options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_DISPLAY_HOMEPAGE ] = ''; }
		delete_option( WPC_OPTIONS_LIKE_BUTTON_SHOW_ON_HOMEPAGE );


		$like_display_categories = get_option( WPC_OPTIONS_LIKE_BUTTON_SHOW_ON_CATEGORIES );
		if ( $like_display_categories !== FALSE && !empty( $like_display_categories ) ){
			$options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_DISPLAY_CATEGORIES ] = 'on';
		}
		else { $options[ WPC_OPTIONS_LIKE_BUTTON ][ WPC_OPTIONS_DISPLAY_CATEGORIES ] = ''; }
		delete_option( WPC_OPTIONS_LIKE_BUTTON_SHOW_ON_CATEGORIES );
		
		return $options;

	}

	/**
	 * Sets the options for the entire plugin. This will overwrite the settings
	 * selected by the user
	 *
	 * @param array $options	the options to set
	 *
	 * @access	private
	 * @since 	2.0
	 * @static
	 */
	private static function setOptions( $options ){

		$general_options = $options[ WPC_OPTIONS ];
		$general_options[ 'WPC_VERSION' ] = WPC_VERSION;
		$general_options_old = get_option( WPC_OPTIONS );

		// make sure the general app settings are kept if they had been set
		if ( isset( $general_options_old[ WPC_OPTIONS_APP_ID ] ) ){
			$general_options[ WPC_OPTIONS_APP_ID ] = $general_options_old[ WPC_OPTIONS_APP_ID ];
		}
		if ( isset( $general_options_old[ WPC_OPTIONS_APP_ADMINS ] ) ){
			$general_options[ WPC_OPTIONS_APP_ADMINS ] = $general_options_old[ WPC_OPTIONS_APP_ADMINS ];
		}
		if ( isset( $general_options_old[ WPC_OPTIONS_IMAGE_URL ] ) ){
			$general_options[ WPC_OPTIONS_IMAGE_URL ] = $general_options_old[ WPC_OPTIONS_IMAGE_URL ];
		}
		if ( isset( $general_options_old[ WPC_OPTIONS_DESCRIPTION ] ) ){
			$general_options[ WPC_OPTIONS_DESCRIPTION ] = $general_options_old[ WPC_OPTIONS_DESCRIPTION ];
		}

		update_option( WPC_OPTIONS, $general_options );
		update_option( WPC_OPTIONS_COMMENTS, $options[ WPC_OPTIONS_COMMENTS ] );
		update_option( WPC_OPTIONS_LIKE_BUTTON, $options[ WPC_OPTIONS_LIKE_BUTTON ] );

	}
}

?>