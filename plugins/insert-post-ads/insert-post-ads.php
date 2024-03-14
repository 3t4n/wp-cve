<?php
/**
* Plugin Name: Insert Post Ads
* Version: 1.3.2
* Author: namithjawahar
* Description: Allows you to insert ads after paragraphs of your post content
* License: GPL2
*/

/*  Copyright 2017 WPBeginner

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
* Insert Post Ads Class
*/
class InsertPostAds {
	/**
	* Constructor
	*/
	public function __construct() {

		// Plugin Details
        $this->plugin               = new stdClass;
        $this->plugin->name         = 'insert-post-ads'; // Plugin Folder
        $this->plugin->displayName  = 'Post Adverts'; // Plugin Name
        $this->plugin->posttype 	= 'insertpostads';
        $this->plugin->version      = '1.3.2';
        $this->plugin->folder       = plugin_dir_path( __FILE__ );
        $this->plugin->url          = plugin_dir_url( __FILE__ );
        $this->plugin->ads_screen_key = $this->plugin->name . '-ads-display-chosen-once';
        $this->plugin->db_welcome_dismissed_key = $this->plugin->name . '-dashboard-welcome';

        // Check if the global wpb_feed_append variable exists. If not, set it.
        if ( ! array_key_exists( 'wpb_feed_append', $GLOBALS ) ) {
              $GLOBALS['wpb_feed_append'] = false;
        }

		// Hooks
		add_action( 'init', array( &$this, 'registerPostTypes' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'adminScriptsAndCSS' ) );
        add_action( 'admin_menu', array( &$this, 'adminPanelsAndMetaBoxes' ) );
        add_action( 'plugins_loaded', array( &$this, 'loadLanguageFiles' ) );
        add_action( 'save_post', array( &$this, 'save' ) );
        add_action( 'wp_feed_options', array( &$this, 'dashBoardRss' ), 10, 2 );
        add_action( 'admin_notices', array( &$this, 'dashboardNotices' ) );
        add_action( 'wp_ajax_' . $this->plugin->name . '_dismiss_dashboard_notices', array( &$this, 'dismissDashboardNotices' ) );


        // Filters
		add_filter( 'enter_title_here', array( &$this, 'changeTitlePlaceholder' ) ); // Change title placeholder
		add_filter( 'post_updated_messages', array( &$this, 'changeUpdatedMessages' ) ); // Appropriate messages for the post type
		add_filter( 'the_content', array( &$this, 'checkAdvertsRequired' ) );
		add_filter( 'dashboard_secondary_items', array( &$this, 'dashboardSecondaryItems' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( &$this, 'addSettingsLink' ) );
	}

	/**
	* Register Custom Post Type
	*/

	function registerPostTypes() {
		register_post_type( $this->plugin->posttype, array(
            'labels' => array(
                'name' => _x( 'Post Adverts', 'post type general name' ),
                'singular_name' => _x( 'Post Advert', 'post type singular name' ),
                'add_new' => _x( 'Add New Post', 'insertpostads' ),
                'add_new_item' => __( 'Add New Post Advert' ),
                'edit_item' => __( 'Edit Post Advert' ),
                'new_item' => __( 'New Post Advert' ),
                'view_item' => __( 'View Post Adverts' ),
                'search_items' => __( 'Search Post Adverts' ),
                'not_found' =>  __( 'No post adverts found' ),
                'not_found_in_trash' => __( 'No post adverts found in Trash' ),
                'parent_item_colon' => ''
            ),
            'description' => 'Post Adverts',
            'public' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 100,
            'menu_icon' => 'dashicons-migrate',
            'capability_type' => 'post',
            'hierarchical' => false,
            'has_archive' => false,
            'show_in_nav_menus' => false,
            'supports' => array( 'title' ),
			'capabilities' => array(
				'edit_post'          => 'manage_options',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'delete_posts'       => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options'
			),
        ));
	}

	/**
	 * Add Settings links in the plugin list page
	 */
	function addSettingsLink( $links ) {
	    $settings_link = '<a href="' . admin_url( 'edit.php?post_type=' . $this->plugin->posttype . '&page=' . $this->plugin->name ) . '">' . __( 'Settings' ) . '</a>';
	    array_push( $links, $settings_link );
	  	return $links;
	}

	/**
    * Register and enqueue any JS and CSS for the WordPress Administration
    */
    function adminScriptsAndCSS() {
    	// JS
    	wp_enqueue_script($this->plugin->name.'-admin', $this->plugin->url.'js/admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-dialog'), $this->plugin->version);
		wp_enqueue_script($this->plugin->name.'chart-admin', $this->plugin->url.'js/Chart.bundle.min.js', array(), $this->plugin->version);
		wp_enqueue_script($this->plugin->name.'colors-admin', $this->plugin->url.'js/controls.js', array(), $this->plugin->version);

    	// CSS
        wp_enqueue_style( $this->plugin->name.'-admin', $this->plugin->url.'css/admin.css', array(), $this->plugin->version );
		wp_enqueue_style( $this->plugin->name.'ads-admin', $this->plugin->url.'css/insert-post.css', array(), $this->plugin->version );
		wp_enqueue_style( $this->plugin->name.'colors', $this->plugin->url.'css/controls.css', array(), $this->plugin->version );
		wp_enqueue_style( 'jquery-ui-admin', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', array(), $this->plugin->version );
		wp_enqueue_style( 'fawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->plugin->version );
    }

	/**
    * Register the plugin settings panel
    */
    function adminPanelsAndMetaBoxes() {
		add_submenu_page( 'edit.php?post_type='.$this->plugin->posttype,'Add vi stories', 'Add vi stories', 'manage_options', 'vi_integration', array( &$this, 'addVi' ) );
        add_submenu_page( 'edit.php?post_type='.$this->plugin->posttype, __( 'Settings', $this->plugin->name ), __( 'Settings', $this->plugin->name ), 'manage_options', $this->plugin->name, array( &$this, 'adminPanel' ) );
		add_meta_box( 'ipa_meta', __( 'New Advert', $this->plugin->name ), array( &$this, 'displayMetaBox' ), $this->plugin->posttype, 'normal', 'high' );
		
		$postTypes = get_post_types( array(
			'public' => true,
		), 'objects' );
		if ( $postTypes ) {
			foreach ( $postTypes as $postType ) {
				// Skip attachments
				if ( $postType->name == 'attachment' ) {
					continue;
				}

				// Skip our CPT
				if ( $postType->name == $this->plugin->posttype ) {
					continue;
				}
				add_meta_box( 'ipa_meta', __( $this->plugin->displayName, $this->plugin->name ), array( &$this, 'displayOptionsMetaBox' ), $postType->name, 'normal', 'high' );
			}
		}

    }

    /**
    * Output the Administration Panel
    * Save POSTed data from the Administration Panel into a WordPress option
    */
    function adminPanel() {
		// only admin user can access this page
		if ( !current_user_can( 'administrator' ) ) {
			echo '<p>' . __( 'Sorry, you are not allowed to access this page.', $this->plugin->name ) . '</p>';
			return;
		}
    	// Save Settings
		if ( isset( $_REQUEST['submit'] ) ) {
			if ( ! wp_verify_nonce( $_REQUEST['_nonce'], $this->plugin->name . '-nonce' ) ) {
			$this->errorMessage = __( 'Something went wrong. Please try to save again.', $this->plugin->name );
			} else {
				delete_option( $this->plugin->name );
				if ( isset( $_REQUEST[$this->plugin->name] ) ) {
					// if user save setting contains post or page or anyother cpt, then set an option
					// that can be used later to let user know about choosing where to
					// display post ads after first ad is created
					if( ( count( $_REQUEST[$this->plugin->name] ) == 1 && !isset( $_REQUEST[$this->plugin->name]['css'] ) ) || count( $_REQUEST[$this->plugin->name] ) > 1  ) {
						update_option( $this->plugin->ads_screen_key, 1 );
					}
					// sanitise the array
					$tempArr = $_REQUEST[$this->plugin->name];
					unset( $_REQUEST[$this->plugin->name] );
					foreach( $tempArr as $key => $value ) {
						$_REQUEST[$this->plugin->name][sanitize_text_field( $key )] = sanitize_text_field( $value );
					}
					unset( $tempArr );
					update_option( $this->plugin->name, $_REQUEST[$this->plugin->name] );
				}
				$this->message = __( 'Post Advert Settings Saved.', $this->plugin->name );
			}
		}

        // Get latest settings
        $this->settings = get_option( $this->plugin->name );

		// Load Settings Form
        include_once( $this->plugin->folder . '/views/settings.php' );
    }
	function addVi() {
		// only admin user can access this page
		if ( !current_user_can( 'administrator' ) ) {
			echo '<p>' . __( 'Sorry, you are not allowed to access this page.', $this->plugin->name ) . '</p>';
			return;
		}
    	// Save Settings
		if ( isset( $_REQUEST['submit'] ) ) {
			if ( ! wp_verify_nonce( $_REQUEST['_nonce'], $this->plugin->name . '-nonce' ) ) {
			$this->errorMessage = __( 'Something went wrong. Please try to save again.', $this->plugin->name );
			} else {
				delete_option( $this->plugin->name );
				if ( isset( $_REQUEST[$this->plugin->name] ) ) {
					// if user save setting contains post or page or anyother cpt, then set an option
					// that can be used later to let user know about choosing where to
					// display post ads after first ad is created
					if( ( count( $_REQUEST[$this->plugin->name] ) == 1 && !isset( $_REQUEST[$this->plugin->name]['css'] ) ) || count( $_REQUEST[$this->plugin->name] ) > 1  ) {
						update_option( $this->plugin->ads_screen_key, 1 );
					}
					// sanitise the array
					$tempArr = $_REQUEST[$this->plugin->name];
					unset( $_REQUEST[$this->plugin->name] );
					foreach( $tempArr as $key => $value ) {
						$_REQUEST[$this->plugin->name][sanitize_text_field( $key )] = sanitize_text_field( $value );
					}
					unset( $tempArr );
					update_option( $this->plugin->name, $_REQUEST[$this->plugin->name] );
				}
				$this->message = __( 'Post Advert Settings Saved.', $this->plugin->name );
			}
		}

        // Get latest settings
        $this->settings = get_option( $this->plugin->name );

		// Load Settings Form
        include_once( $this->plugin->folder . '/views/add-vi.php' );
    }

    /**
	* Loads plugin textdomain
	*/
	function loadLanguageFiles() {
		load_plugin_textdomain( $this->plugin->name, false, $this->plugin->name . '/languages/' );
	}

	/**
	* Displays the meta box on the Custom Post Type
	*
	* @param object $post Post
	*/
	function displayMetaBox( $post ) {
		// Get meta
		$adCode = get_post_meta( $post->ID, '_ad_code', true );
		$adUnit = get_post_meta( $post->ID, '_ad_unit', true );
		$adPosition = get_post_meta( $post->ID, '_ad_position', true );
		$paragraphNumber = get_post_meta( $post->ID, '_paragraph_number', true );

		// Nonce field
		wp_nonce_field( $this->plugin->name, $this->plugin->name . '_nonce' );
		?>
		<p>
			<label for="ad_code">Advert code:</label>
			<textarea name="ad_code" id="ad_code" style="width: 86%; height: 100px; font-size: 14px; float:left;overflow:hidden;"><?php echo esc_html( wp_unslash( $adCode ) ); ?></textarea>
		</p>
		<div style="clear:both"></div>
		<p>
			<label for="ad_position"><?php _e( 'Display the advert:', $this->plugin->name ); ?></label>
			<select onChange="javascript:handleIpaAdOptionChange( this.value )" name="ad_position" size="1">
				<option value="top"<?php echo ( ( $adPosition == 'top' ) ? ' selected' : '' ); ?>><?php _e( 'Before Content', $this->plugin->name ); ?></option>
				<option value=""<?php echo ( ( $adPosition == '' ) ? ' selected' : '' ); ?>><?php _e( 'After Paragraph Number', $this->plugin->name ); ?></option>
				<option value="bottom"<?php echo ( ( $adPosition == 'bottom' ) ? ' selected' : '' ); ?>><?php _e( 'After Content', $this->plugin->name ); ?></option>
			</select>
			<input type="number" name="paragraph_number" value="<?php echo $paragraphNumber; ?>" min="1" max="999" step="1" id="paragraph_number" <?php if ( $adPosition != '' ) { echo ' style="display: none;"'; } ?> />
			<script type="text/javascript">
				var ipaParaNumberElem = document.getElementById( 'paragraph_number' );
				var handleIpaAdOptionChange = function( value ) {
					if ( value != "" ) {
						ipaParaNumberElem.style.display = 'none';
					} else {
						ipaParaNumberElem.style.display = 'inline-block';
					}
				}
			</script>
		</p>
		<p><input readonly name="ad_unit" id="ad_unit" type="text" value="<?php echo $adUnit ? $adUnit : 'Advert post'; ?>"></p>
		<?php
	}

	/**
	* Displays the meta box on Pages, Posts and CPTs
	*
	* @param object $post Post
	*/
	function displayOptionsMetaBox( $post ) {
		// Get meta
		$disable = get_post_meta( $post->ID, '_ipa_disable_ads', true );

		// Nonce field
		wp_nonce_field( $this->plugin->name, $this->plugin->name . '_nonce' );
		?>
		<p>
			<label for="ipa_disable_ads"><?php _e( 'Disable Adverts', $this->plugin->name ); ?></label>
			<input type="checkbox" name="ipa_disable_ads" id="ipa_disable_ads" value="1"<?php echo ( $disable ? ' checked' : '' ); ?> />
		</p>
		<p class="description">
			<?php _e( 'Check this option if you wish to disable all Post Ads from displaying on this content.', $this->plugin->name ); ?>
		</p>
		<?php
	}

	/**
	* Saves the meta box field data
	*
	* @param int $post_id Post ID
	*/
	function save( $post_id ) {
		// Check if our nonce is set.
		if ( !isset($_REQUEST[$this->plugin->name . '_nonce'] ) ) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if ( !wp_verify_nonce( $_REQUEST[$this->plugin->name.'_nonce'], $this->plugin->name ) ) {
			return $post_id;
		}

		// Check the logged in user has permission to edit this post
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// OK to save meta data
		if ( isset( $_REQUEST['ipa_disable_ads'] ) ) {
		 	update_post_meta( $post_id, '_ipa_disable_ads', sanitize_text_field( $_REQUEST['ipa_disable_ads'] ) );
		} else {
			delete_post_meta( $post_id, '_ipa_disable_ads' );
		}

		if ( isset( $_REQUEST['ad_code'] ) ) {
			// $_REQUEST has already been slashed by wp_magic_quotes in wp-settings
			// so do nothing before saving
			update_post_meta( $post_id, '_ad_code', $_REQUEST['ad_code'] );
		}
		if ( isset( $_REQUEST['ad_unit'] ) ) {
			// $_REQUEST has already been slashed by wp_magic_quotes in wp-settings
			// so do nothing before saving
			update_post_meta( $post_id, '_ad_unit', $_REQUEST['ad_unit'] );
		}
		if ( isset( $_REQUEST['ad_position'] ) ) {
			update_post_meta( $post_id, '_ad_position', sanitize_text_field( $_REQUEST['ad_position'] ) );
		}
		if ( isset( $_REQUEST['paragraph_number'] ) ) {
			update_post_meta( $post_id, '_paragraph_number', sanitize_text_field( $_REQUEST['paragraph_number'] ) );
		}
	}

	/**
	* Changes the 'Enter title here' placeholder on the Ad Custom Post Type
	*
	* @param string $title Title
	* @return string Title
	*/
	function changeTitlePlaceholder( $title ) {
		global $post;
		if ( $post->post_type == $this->plugin->posttype ) {
			$title = __( 'Advert Title', $this->plugin->name );
		}

		return $title;
	}

	/**
	* Updates the saved, deleted, updated messages when saving an Ad Custom Post Type
	*
	* @param array $messages Messages
	* @return array Messages
	*/
	function changeUpdatedMessages( $messages ) {
		$published_msg = __( 'Advert published.', $this->plugin->name );
		$updated_msg = __( 'Advert updated.', $this->plugin->name );
		// change the messages for first time user, if where to display ads options are not set
		if ( !get_option( $this->plugin->ads_screen_key ) ) {
			$published_msg = sprintf( __( 'Advert published. Now, go to the <a href="%s">settings page</a> to select where you want to display your ads.', $this->plugin->name ), admin_url( 'edit.php?post_type=' . $this->plugin->posttype . '&page=' . $this->plugin->name ) );
			$updated_msg = sprintf( __( 'Advert updated. Now, go to the <a href="%s">settings page</a> to select where you want to display your ads.', $this->plugin->name ), admin_url( 'edit.php?post_type=' . $this->plugin->posttype . '&page=' . $this->plugin->name ) );
		}
		$messages[$this->plugin->posttype] = array(
			1 =>  	$updated_msg,
		    2 => 	$updated_msg,
		    3 => 	$updated_msg,
		    4 => 	$updated_msg,
			6 => 	$published_msg,
		);

		return $messages;
	}

	/**
	* Checks if the current screen on the frontend needs advert(s) adding to it
	*/
	function checkAdvertsRequired( $content ) {
		/**
		 * Filter insert_post_ads_enabled Whether ads should be printed.
		 * This filter can be used to temporarily stop ads from printing,
		 * say, for AMP pages
		 *
		 * @param bool $enabled Whether ads should be printed
		 */
		if ( ! apply_filters( 'insert_post_ads_enabled', true ) ) {
			return $content;
		}

		global $post;

		// Settings
		$this->settings = get_option( $this->plugin->name );
		if ( !is_array( $this->settings ) ) {
			return $content;
		}
		if ( count( $this->settings ) == 0 ) {
			return $content;
		}

		// Check if we are on a singular post type that's enabled
		foreach ( $this->settings as $postType=>$enabled ) {
			if ( is_singular( $postType ) ) {
				// Check the post hasn't disabled adverts
				$disable = get_post_meta( $post->ID, '_ipa_disable_ads', true );
				if ( !$disable ) {
					return $this->insertAds( $content );
				}
			}
		}

		return $content;
	}

	/**
	* Inserts advert(s) into content
	*
	* @param string $content Content
	* @return string Content
	*/
	function insertAds( $content ) {
		$ads = new WP_Query( array(
			'post_type' => $this->plugin->posttype,
			'post_status' => 'publish',
			'posts_per_page' => -1,
		) );
		if ( $ads->have_posts() ) {
			while ( $ads->have_posts() ) {
				$ads->the_post();

				$adID = get_the_ID();
				$adCode = get_post_meta( $adID, '_ad_code', true );
				$adPosition = get_post_meta( $adID, '_ad_position', true );
				$paragraphNumber = get_post_meta( $adID, '_paragraph_number', true );

				switch ( $adPosition ) {
					case 'top':
						$content = $adCode . $content;
						break;
					case 'bottom':
						$content = $content . $adCode;
						break;
					default:
						$content = $this->insertAdAfterParagraph( $adCode, $paragraphNumber, $content );
						break;
				}
			}
		}

		wp_reset_postdata();
		return $content;
	}

	/**
	* Insert something after a specific paragraph in some content.
	*
	* @param  string $insertion    Likely HTML markup, ad script code etc.
	* @param  int    $paragraph_id After which paragraph should the insertion be added. Starts at 1.
	* @param  string $content      Likely HTML markup.
	*
	* @return string               Likely HTML markup.
	*/
	function insertAdAfterParagraph( $insertion, $paragraph_id, $content ) {
		$closing_p = '</p>';
		$paragraphs = explode( $closing_p, $content );
		foreach ( $paragraphs as $index => $paragraph ) {
			// Only add closing tag to non-empty paragraphs
			if ( trim( $paragraph ) ) {
				// Adding closing markup now, rather than at implode, means insertion
				// is outside of the paragraph markup, and not just inside of it.
				$paragraphs[$index] .= $closing_p;
			}

			// + 1 allows for considering the first paragraph as #1, not #0.
			if ( $paragraph_id == $index + 1 ) {
				$paragraphs[$index] .= '<div class="' . $this->generateRandomString( 8 ) . '"' . ( isset( $this->settings['css'] ) ? '' : ' style="clear:both;float:left;width:100%;margin:0 0 20px 0;"' ) . '>' . $insertion . '</div>';
			}
		}
		return implode( '', $paragraphs );
	}

    /**
     * Dismiss the welcome notice for the plugin
     */
    function dismissDashboardNotices() {
    	check_ajax_referer( $this->plugin->name . '-nonce', 'nonce' );
        // user has dismissed the welcome notice
        update_option( $this->plugin->db_welcome_dismissed_key, 1 );
        exit;
    }

    /**
     * Show relevant notices for the plugin
     */
    function dashboardNotices() {
        global $typenow;

        // if no ad has been created yet
        // and page type in not the ads
        // and the welcome dismissed key is not set, then show a notice
        $ads_created = get_posts(
                            array(
                                'numberposts' => 1,
                                'post_type'   => $this->plugin->posttype,
                                'post_status' => 'publish'
                            )
                        );

        if ( empty( $ads_created ) && $typenow != $this->plugin->posttype && !get_option( $this->plugin->db_welcome_dismissed_key ) ) {
            // load the notices view
            include_once( $this->plugin->folder . '/views/dashboard-notices.php' );
        }
    }

    /**
     * Number of Secondary feed items to show
     */
    function dashboardSecondaryItems() {
        return 6;
    }

    /**
     * Update the planet feed to add the WPB feed
     */
    function dashboardRss( $feed, $url ) {
        // Return early if not on the right page.
        global $pagenow;
        if ( 'admin-ajax.php' !== $pagenow ) {
            return;
        }

        // Return early if not on the right feed.
        if ( strpos( $url, 'planet.wordpress.org' ) === false ) {
            return;
        }

        // Only move forward if this action hasn't been done already.
        if ( ! $GLOBALS['wpb_feed_append'] ) {
            $GLOBALS['wpb_feed_append'] = true;
            $urls = array( 'http://www.wpbeginner.com/feed/', $url );
            $feed->set_feed_url( $urls );
        }
    }

    /**
     * Generate a random string of length N
     */
	function generateRandomString( $length = 10 ) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen( $characters );
		$randomString = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$randomString .= $characters[rand( 0, $charactersLength - 1 )];
		}
		return $randomString;
	}
}

$insertPostAds = new InsertPostAds();


/*function myplugin_activate($wpdb, $table_prefix) {
global $wpdb;
$prefix = $wpdb->prefix;
//$wpdb->query('UPDATE wp_usermeta SET meta_value = "" WHERE meta_key = "insert_ads_1.6_admin_notice_dismissed"');
$wpdb->query('DELETE FROM '.$prefix.'usermeta WHERE `meta_key` = "insert_ads_1.6_admin_notice_dismissed"');
}
register_activation_hook( __FILE__, 'myplugin_activate' );*/

if(!defined('WP_INSADS_URL'))
	define('WP_INSADS_URL', plugin_dir_url(__FILE__));
if(!defined('WP_INSADS_DIR'))
	define('WP_INSADS_DIR', plugin_dir_path(__FILE__));
if(!defined('WP_INSADS_VERSION'))
	define('WP_INSADS_VERSION', '1.3');
require_once(dirname(__FILE__).'/includes/vi-integration.php');
require_once(dirname(__FILE__).'/apis/vi/api.php');
require_once(dirname(__FILE__).'/includes/inpostads.php');
require_once(dirname(__FILE__).'/includes/rules.php');
require_once(dirname(__FILE__).'/includes/common.php');
require_once(dirname(__FILE__).'/includes/geotargeting.php');
require_once(dirname(__FILE__).'/includes/gdpr.php');
require_once(dirname(__FILE__).'/adstxt.php');
require_once(dirname(__FILE__).'/includes/adstxt.php');
require_once(dirname(__FILE__).'/controls/controls.php');

/* Begin Admin Notice */
add_action('admin_notices', 'insert_ads_admin_notices');
function insert_ads_admin_notices() {	
	
		$userId = get_current_user_id();
		if(!get_user_meta($userId, 'insert_ads_'.WP_INSADS_VERSION.'_admin_notice_dismissed', true)) {
			echo '<div class="notice notice-success insert_ads_notice is-dismissible" style="padding: 15px;">';
				echo '<div class="blurb-info" style="float: left; font-size: 14px; font-family: Arial; line-height: 18px; color: #232323;">';
					echo '<p>Thank you for updating <b>Insert Post ADS</b>!</p>';
					echo '<p>This update features <strong>vi stories</strong> from video intelligence - a video player that supplies both content and video advertising. Watch a <a href="http://demo.vi.ai/ViewsterBlog_Nintendo.html">demo</a>.';
					echo '<br>To begin earning sign up to vi stories and place the ad live now! ';
					echo 'Read the <a href="https://www.vi.ai/frequently-asked-questions-vi-stories-for-wordpress/?utm_source=WordPress&utm_medium=Plugin%20FAQ&utm_campaign=WP%20insertpostads">FAQ</a>.</p>';
					echo '<p style="display:inline-block;width:60%;font-size: 12px; font-style:italic;">Click the ‘Monetize Now’ button to activate vi stories. You’ll agree to share your domain, affiliate ID and email with video intelligence, and begin your journey to video publisher.</p>';
					echo '<p style="display:inline-block;width:30%;text-align:right;"><a target="_blank" class="monetize-btn" href="https://www.vi.ai/publisher-registration/?email='.get_bloginfo('admin_email').'&domain='.get_bloginfo('url').'&aid=WP_insertpostads&utm_source=Wordpress&utm_medium=wp_insertpostads&utm_campaign=white&utm_content=Wp_insertpostads">Monetize Now</a></p>';
				echo '</div>';
				echo '<img style="float: right; margin-right: 20px; margin-top: 13px;width: 12%;" src="'.WP_INSADS_URL.'images/logo-svg.svg?'.WP_INSADS_VERSION.'" />';
				echo '<div class="clear"></div>';				
				echo '<input type="hidden" id="insert_ads_admin_notice_nonce" name="insert_ads_admin_notice_nonce" value="'.wp_create_nonce('insert-ads-admin-notice').'" />';
				echo '<input type="hidden" id="insert_ads_admin_notice_ajax" name="insert_ads_admin_notice_ajax" value="'.admin_url('admin-ajax.php').'" />';
			echo '</div>';
		}
	
}

add_action('admin_notices', 'insert_ads_admin_notices_settings');
function insert_ads_admin_notices_settings() {	
		$getUrl = $_SERVER['REQUEST_URI'];
		if ($getUrl == '/wp-admin/edit.php?post_type=insertpostads'){
		?>
		<div class="notice ins-notice"> 
		<div id="poststuff">
		<form action="edit.php?post_type=insertpostads&page=insert-post-ads" method="post" class="ins-settings">
		                <div class="postbox">
		                    <h3 class="hndle"><?php _e( 'Display Settings', 'insert-post-ads'); ?></h3>

		                    <div class="inside">
		                    	<p>
									<?php
									$postTypes = get_post_types( array(
										'public' => true,
									), 'objects');
									if ( $postTypes ) {
										foreach ( $postTypes as $postType ) {
											// Skip attachments
											if ( $postType->name == 'attachment' ) {
												continue;
											}
											?>
											<label for="<?php echo $postType->name; ?>"><?php echo $postType->labels->name; ?></label>
											<input type="checkbox" name="insert-post-ads[<?php echo $postType->name; ?>]" value="1" id="<?php echo $postType->name; ?>" <?php echo ( isset( get_option( 'insert-post-ads' )[$postType->name] ) ? ' checked' : '' ); ?>/>
											<?php
										}
									}
									?>
									<label for="css"><?php _e( 'Exclude CSS', 'insert-post-ads' ) ;?></label>
									<input type="checkbox" name="insert-post-ads[css]" value="1" id="css"/>
								</p>
						
								<p class="description">
									<?php _e( 'By default, Post Ads are wrapped in a container that has some CSS to aid layout. Developers may wish to use their own CSS, and should check this Exclude CSS option.', 'insert-post-ads' ); ?>
								</p>
								<p>
									<input id="set-submit" name="submit" type="submit" name="Submit" class="button button-primary" value="<?php _e( 'Save Settings', 'insert-post-ads' ); ?>" />
								</p>
		                    </div>
		                </div>
		                <!-- /postbox -->

		                
		                <!-- /postbox -->
		                <input type="hidden" name="_nonce" value= "<?php echo wp_create_nonce( 'insert-post-ads' . '-nonce' ); ?>" />
	                </form>
					</div>
					</div>
		<?php
		}
}

add_action('wp_ajax_insert_ads_admin_notice_dismiss', 'insert_ads_admin_notice_dismiss');
function insert_ads_admin_notice_dismiss() {
	check_ajax_referer('insert-ads-admin-notice', 'insert_ads_admin_notice_nonce');	
	$userId = get_current_user_id();
	update_user_meta($userId, 'insert_ads_'.WP_INSADS_VERSION.'_admin_notice_dismissed', 'true');
	die();
}
/* End Admin Notice */


	function single_post_insert() {
		if(get_post_status(10000)){
        $new_post = array(
			'post_title'    => $_POST['title'],
			'post_content'  => 'dsds',
			'post_status'   => 'publish',         
			'post_type'     => 'insertpostads',
			'meta_input'    => array( '_ad_unit'=>'vi stories' ),
			'ID' => '10000'
        );
        //insert the the post into database by passing $new_post to wp_insert_post
        //store our post ID in a variable $pid
        $pid = wp_insert_post($new_post);
		echo json_encode(array('flag'=>'1'));
		die;
		} else {
		$new_post = array(
			'post_title'    => $_POST['title'],
			'post_content'  => 'dsds',
			'post_status'   => 'publish',         
			'post_type'     => 'insertpostads',
			'meta_input'    => array( '_ad_unit'=>'vi stories' ),
			'import_id' => '10000'
        );
		$pid = wp_insert_post($new_post);
		echo json_encode(array('flag'=>'1'));
		die;
		}
	}
add_action( 'wp_ajax_single_post', 'single_post_insert' );    // If called from admin panel
add_action( 'wp_ajax_nopriv_single_post', 'single_post_insert' );

add_filter('manage_insertpostads_posts_columns', 'new_ad_cols');
function new_ad_cols( $columns ) 
{
    if( is_array( $columns ) && ! isset( $columns['ad_unit'] ) )
		unset( $columns['date'] );
		$columns['title'] = __( 'Title' );   
        $columns['ad_unit'] = __( 'Unit type' );   
		$columns['date'] = __( 'Date' );   	
    return $columns;
}
add_action( 'manage_insertpostads_posts_custom_column', 'get_ad_unit', 10, 2 );
function get_ad_unit( $column_name, $post_id ) {
    if ( $column_name == 'ad_unit')
        echo get_post_meta( get_the_ID(), '_ad_unit', true );
}