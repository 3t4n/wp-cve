<?php
/*
Plugin Name: Basic SEO Pack
Plugin URI: http://project.ulil-albab.info/basic-seo-pack/
Description: Simple but complete SEO Pack to make your site SEO Friendly. Quick way to add meta tags to your post and pages using WP custom fields. Setting your default meta tag on your home page with easily. Configure Search engine verification and analytics code with simple way.
Author: Ahmad Ulil Albab
Author URI: http://ulil-albab.info
Version: 1.1.4

	Copyright (c) 2012, Ahmad Ulil Albab.

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

// Defining

if ( ! defined( 'BSEOP_VERSION' ) )
    define( 'BSEOP_VERSION', '1.1.4' );

if ( ! defined( 'BSEOP_PLUGIN_DIR' ) )
    define( 'BSEOP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'BSEOP_PLUGIN_BASENAME' ) )
    define( 'BSEOP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'BSEOP_PLUGIN_DIRNAME' ) )
    define( 'BSEOP_PLUGIN_DIRNAME', dirname( BSEOP_PLUGIN_BASENAME ) );

if ( ! defined( 'BSEOP_PLUGIN_URL' ) )
    define( 'BSEOP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'BSEOP_PLUGIN_IMAGES_URL' ) )
    define( 'BSEOP_PLUGIN_IMAGES_URL', BSEOP_PLUGIN_URL . 'images/' );

/**
 * Main BasicSEO plugin class
 */

class BasicSEO {
	
	public static $prefix = 'bseop_';
	public static $metabox = array();
	
	/**
	 * Initialize the plugin
	 */
	function init() {
		
		// Add custom menu handler
		add_action('admin_menu', array('BasicSEO', 'init_menu'));

		// Add meta boxes for posts and pages
		BasicSEO::$metabox = array(
			'id'       => BasicSEO::$prefix . 'primary-meta-box',
			'title'    => __('Basic SEO Meta Tags', 'basic-seo-pack'),
			'page'     => array('page', 'post'),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
					array(
						'name' => __('Keywords', 'basic-seo-pack'),
						'desc' => __('Insert a list of desired keywords', 'basic-seo-pack'),
						'id'   => '_' . BasicSEO::$prefix . 'meta_keywords',
						'type' => 'text',
						),
					array(
						'name' => __('Description', 'basic-seo-pack'),
						'desc' => __('Insert a short description for this page or post', 'basic-seo-pack'),
						'id'   => '_' . BasicSEO::$prefix . 'meta_description',
						'type' => 'textarea',
						),
					array(
						'name' => __('Use global settings (not recomended)', 'basic-seo-pack'),
						'id'   => '_' . BasicSEO::$prefix . 'use_global_settings',
						'type' => 'checkbox',
						),
				)
			);

		add_action('add_meta_boxes', array('BasicSEO', 'add_metabox'));
		add_action('save_post', array('BasicSEO', 'save_postdata'));
		
		// Add header meta tags
		add_action('wp_head', array('BasicSEO', 'print_title'));
		add_action('wp_head', array('BasicSEO', 'print_keywords'));
		add_action('wp_head', array('BasicSEO', 'print_description'));
		add_action('wp_head', array('BasicSEO', 'print_google_verification'));
		add_action('wp_head', array('BasicSEO', 'print_bing_verification'));
		add_action('wp_head', array('BasicSEO', 'print_alexa_verification'));
		
		// Add analytics
		add_action('wp_head', array('BasicSEO', 'print_google_analytics'));

	} // end function
	
	/**
	 * Adds options menu
	 */
	function init_menu() {

		// Add general options menu
		add_options_page(__('Basic SEO', 'basic-seo-pack'), __('Basic SEO', 'basic-seo-pack'), 'manage_options', 'bseop-options', array('BasicSEO', 'manage_options'));
		
	} // end function

	/**
	 * Manages the Basic SEO option page
	 */
	function manage_options() {

		// Open page
		echo '<div class="wrap">';
		echo "<h2>" . __('Basic SEO Options', 'basic-seo-pack') . "</h2>"; ?>
			<div style="width:98%; text-align:justify;float:left;background-color:white;border: 1px solid #ddd;padding:10px;margin-right:5px;margin-bottom:2px;">
				<div style="width:40%; float:left;background-color:white;border: 1px solid #ddd;padding: 5px;margin-right:5px;height:200px;margin-bottom:2px;">
					<h3>WordPress Themes & Plugins Design</h3>
					<em><a href="http://project.ulil-albab.info/" target="_blank">auastyle Studio</a> allows you to download premium themes for your own wordpress website! All of themes and plugins are full free downloadable. auastyle studio create with soul.</em>
					<center><br><a target="_blank" title="<?php _e('auastyle studio', 'basic-seo-pack') ?>" href="http://project.ulil-albab.info/">
					<img src="<?php echo BSEOP_PLUGIN_IMAGES_URL; ?>as-234x60.png" alt="<?php _e('auastyle studio', 'basic-seo-pack') ?>" /></a></center>
				</div>
				<div style="width:55%; float:left;background-color:white;border: 1px solid #ddd;padding: 5px;margin-right:5px;height:200px;margin-bottom:2px;">
					<h3>Donate</h3>
					<em>If you like this plugin and find it useful, help keep this plugin free and actively developed by clicking the <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7DMZUYTN798NW" target="_blank"><strong>donate</strong></a> button or send me a gift from my <a href="http://www.amazon.com/gp/registry/wishlist/3K3S1LDJALPP4/ref=topnav_lists_3" target="_blank"><strong>Amazon wishlist</strong></a> Or just <a href="http://wordpress.org/support/view/plugin-reviews/basic-seo-pack?rate=5#postform" target="_blank"><strong>give 5 stars and review</strong></a> this plugin.  Also, don't forget to follow me on <a href="http://twitter.com/auastyle/" target="_blank"><strong>Twitter</strong></a>.</em>
					<br><center><a target="_blank" title="<?php echo 'Donate' ?>" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7DMZUYTN798NW">
					<img src="<?php echo BSEOP_PLUGIN_IMAGES_URL; ?>donate.jpg" alt="<?php _e('Donate with Paypal', 'basic-seo-pack') ?>" />	</a>
					<a target="_blank" title="Amazon Wish List" href="http://www.amazon.com/gp/registry/wishlist/3K3S1LDJALPP4/ref=topnav_lists_3">
					<img src="<?php echo BSEOP_PLUGIN_IMAGES_URL; ?>amazon.jpg" alt="<?php _e('My Amazon Wish List', 'basic-seo-pack') ?>" /> </a>
					<a target="_blank" title="<?php _e('Follow us on Twitter', 'basic-seo-pack') ?>" href="http://twitter.com/auastyle/">
					<img src="<?php echo BSEOP_PLUGIN_IMAGES_URL; ?>twitter.jpg" alt="<?php _e('Follow Us on Twitter', 'basic-seo-pack') ?>" />	</a>
					<a target="_blank" title="<?php _e('Give 5 start and review', 'basic-seo-pack') ?>" href="http://wordpress.org/support/view/plugin-reviews/basic-seo-pack?rate=5#postform">
					<img src="<?php echo BSEOP_PLUGIN_IMAGES_URL; ?>5-stars.png" alt="<?php _e('Give 5 start and review', 'basic-seo-pack') ?>" />	</a></center>
				</div>
			</div>

<?php
		echo"<div style='width:98%; float:left;background-color:white;padding: 10px;margin-right:15px;border: 1px solid #ddd;min-height:200px;margin-bottom:2px;'>";
		echo "<h2>" . __('Basic Settings', 'basic-seo-pack') . "</h2>";
		echo "<p>" . __("From here you can set the default values for the <code>keywords</code> and <code>description</code> meta tags. These values will be used for all the posts and pages which don't have their custom ones.", 'basic-seo-pack') . "</p>";
		// Open form
		echo '<form method="post" action="options.php">';
		wp_nonce_field('update-options');

		// General options
		
		echo "<h3>" . __('Keywords', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert 3-12 kewords or key phrases separating them with commas.", 'basic-seo-pack') . "</p>";
		 
		$keywords = get_option(BasicSEO::$prefix . 'keywords');
		echo '<textarea name="' . BasicSEO::$prefix . 'keywords" cols="60" rows="4" style="width: 98%; font-size: 12px;" class="code">';
		echo $keywords;
		echo '</textarea>';

		echo "<h3>" . __('Description', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert a description containing more or less 5-24 words.", 'basic-seo-pack') . "</p>";
		
		$description = get_option(BasicSEO::$prefix . 'description');
		echo '<textarea name="' . BasicSEO::$prefix . 'description" cols="60" rows="4" style="width: 98%; font-size: 12px;" class="code">';
		echo $description;
		echo '</textarea>';
		
		echo"</div>";
		
		echo"<div style='width:98%; float:left;background-color:white;padding: 10px;margin-right:15px;border: 1px solid #ddd;min-height:200px;margin-bottom:2px;'>";
		echo "<h2>" . __('Search Engine and Analytics options', 'basic-seo-pack') . "</h2>";

		echo "<p>" . __("From here you can fill the SEO verification meta tags like <code>Google</code>, <code>bing</code> and <code>alexa</code> webmaster tools meta tags and <code>Google Analytics</code>. If you don't like, just give it away.", 'basic-seo-pack') . "</p>";
		
		echo "<h3>" . __('Google verification', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert a google webmaster verification meta tag.", 'basic-seo-pack') . "</p>";
		 
		$google_verification = get_option(BasicSEO::$prefix . 'google_verification');
		echo '<textarea name="' . BasicSEO::$prefix . 'google_verification" cols="60" rows="1" style="width: 98%; font-size: 12px;" class="code">';
		echo $google_verification;
		echo '</textarea>';
		
		echo "<h3>" . __('Bing verification', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert a bing webmaster verification meta tag.", 'basic-seo-pack') . "</p>";
		 
		$bing_verification = get_option(BasicSEO::$prefix . 'bing_verification');
		echo '<textarea name="' . BasicSEO::$prefix . 'bing_verification" cols="60" rows="1" style="width: 98%; font-size: 12px;" class="code">';
		echo $bing_verification;
		echo '</textarea>';
		
		echo "<h3>" . __('Alexa verification', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert a alexa verification meta tag.", 'basic-seo-pack') . "</p>";
		 
		$alexa_verification = get_option(BasicSEO::$prefix . 'alexa_verification');
		echo '<textarea name="' . BasicSEO::$prefix . 'alexa_verification" cols="60" rows="1" style="width: 98%; font-size: 12px;" class="code">';
		echo $alexa_verification;
		echo '</textarea>';
		
		echo "<h3>" . __('Google Analytics ID', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert a google analytics tracker code. Sample: UA-XXXXXXXX-X", 'basic-seo-pack') . "</p>";
		 
		$google_analytics = get_option(BasicSEO::$prefix . 'google_analytics');
		echo '<input type="text" name="' . BasicSEO::$prefix . 'google_analytics" style="width: 30%; font-size: 12px;" class="code" value="';
		echo $google_analytics;
		echo '">';
		
		echo"</div>";
		
		/*
		echo"<div style='width:98%; float:left;background-color:white;padding: 10px;margin-right:15px;border: 1px solid #ddd;min-height:200px;margin-bottom:2px;'>";
		
		echo "<h2>" . __('Social Network options', 'basic-seo-pack') . "</h2>";

		echo "<p>" . __("From here you can fill the Social network profile like <code>Twitter</code>, <code>Facebook</code> and <code>Google+ One</code>.", 'basic-seo-pack') . "</p>";
		
		echo "<h3>" . __('Twitter Username', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert your twitter username without <code>@</code>.", 'basic-seo-pack') . "</p>";
		 
		$the_twitter = get_option(BasicSEO::$prefix . 'the_twitter');
		echo '<input type="text" name="' . BasicSEO::$prefix . 'the_twitter" style="width: 30%; font-size: 12px;" class="code" value="';
		echo $the_twitter;
		echo '">';
		
		echo "<h3>" . __('Facebook Profile URL', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert your facebook profile Link. Sample: http://www.facebook.com/auastyle", 'basic-seo-pack') . "</p>";
		 
		$the_facebook = get_option(BasicSEO::$prefix . 'the_facebook');
		echo '<input type="text" name="' . BasicSEO::$prefix . 'the_facebook" style="width: 30%; font-size: 12px;" class="code" value="';
		echo $the_facebook;
		echo '">';
		
		echo "<h3>" . __('Google+ Profile URL', 'basic-seo-pack') . "</h3>";
		echo "<p>" . __("Insert your google+ profile link. Sample: http://plus.google.com/AMOLJDLIJ009", 'basic-seo-pack') . "</p>";
		 
		$google_plus = get_option(BasicSEO::$prefix . 'google_plus');
		echo '<input type="text" name="' . BasicSEO::$prefix . 'google_plus" style="width: 30%; font-size: 12px;" class="code" value="';
		echo $google_plus;
		echo '">';
		
		echo"</div>";*/

		// WP inteface
		echo '<input type="hidden" name="action" value="update" />';
		echo '<input type="hidden" name="page_options" value="' 
				. BasicSEO::$prefix . 'keywords,' 
				. BasicSEO::$prefix . 'description,' 
				. BasicSEO::$prefix . 'google_verification,' 
				. BasicSEO::$prefix . 'bing_verification,' 
				. BasicSEO::$prefix . 'alexa_verification,' 
				. BasicSEO::$prefix . 'google_analytics" />';

		// Second submit button

		echo '<p class="submit">
		<input type="submit" name="Submit" value="' . __('Save Changes', 'basic-seo-pack') . '" />
		</p>';

		// Close document
		echo '</form>';
		echo '</div>';

	} // end function manage_options
	
	/*
	 *Print Meta Title 
	 */
	function print_title() {
		global $page, $paged;
		echo"<meta name=\"title\" value=\"";
		wp_title(' | ',true,'right');
		bloginfo('name'); 
		$site_description = get_bloginfo( 'description', 'display' );
		if($site_description && (is_home() || is_front_page()) && !is_paged())	echo " | $site_description";
		if($paged >= 2 || $page >= 2)	echo ' | Page '.max( $paged, $page );
		echo"\" />\n";
		
		echo"<meta itemprop=\"name\" value=\"";
		wp_title(' | ',true,'right');
		bloginfo('name'); 
		$site_description = get_bloginfo( 'description', 'display' );
		if($site_description && (is_home() || is_front_page()) && !is_paged())	echo " | $site_description";
		if($paged >= 2 || $page >= 2)	echo ' | Page '.max( $paged, $page );
		echo"\" />\n";
		
		echo"<meta property=\"og:title\" value=\"";
		wp_title(' | ',true,'right');
		bloginfo('name'); 
		$site_description = get_bloginfo( 'description', 'display' );
		if($site_description && (is_home() || is_front_page()) && !is_paged())	echo " | $site_description";
		if($paged >= 2 || $page >= 2)	echo ' | Page '.max( $paged, $page );
		echo"\" />\n";
	}
	
	/**
	 * Prints post or page keywords
	 */
	function print_keywords() {
		global $post;
		
		if (!is_object($post)) return;

		if (is_archive()) return;
		
		if (is_home()) {
			if (!$keywords = get_option(BasicSEO::$prefix . 'keywords')) {
				return;
			} // end if
			
			$keywords = apply_filters('bseop_keywords', $keywords);
		
		if (!empty($keywords)) {
			$html = '<meta name="keywords" content="' . $keywords . '" />';

			echo $html . "\n";
		  } // end if
		
		}

		if ('on' == get_post_meta($post->ID, '_' . BasicSEO::$prefix . 'use_global_settings', true)) {
			
			if (!$keywords = get_option(BasicSEO::$prefix . 'keywords')) {
				return false;
			} // end if

		} else if (!$keywords = get_post_meta($post->ID, '_' . BasicSEO::$prefix . 'meta_keywords', true)) {
				return false;
		} // end if

		$keywords = apply_filters('bseop_keywords', $keywords);
		if (!is_home()) {
		if (!empty($keywords)) {
			$html = '<meta name="keywords" content="' . $keywords . '" />';

			echo $html . "\n";
		 } // end if
		}
	} // end function print_keywords

	/**
	 * Prints post or page description
	 */
	function print_description() {
		global $post;
		
		if (!is_object($post)) return;

		if (is_archive()) return;
		
		if (is_home()) {
			if (!$description = get_option(BasicSEO::$prefix . 'description')) {
				return;
			} // end if
			
			$description = apply_filters('bseop_description', $description);
		
		if (!empty($description)) {
			$html = '<meta name="description" content="' . $description . '" />';

			echo $html . "\n";
		  } // end if
		
		}

		if ('on' == get_post_meta($post->ID, '_' . BasicSEO::$prefix . 'use_global_settings', true)) {
			
			if (!$description = get_option(BasicSEO::$prefix . 'description')) {
				return false;
			} // end if

		} else if (!$description = get_post_meta($post->ID, '_' . BasicSEO::$prefix . 'meta_description', true)) {
				return false;
		} // end if

		$description = apply_filters('bseop_description', $description);
		if (!is_home()) {
		if (!empty($description)) {
			$html = '<meta name="description" content="' . $description . '" />';

			echo $html . "\n";
		 } // end if
		}
	} // end function print_description
	
	/*
	 * Print Search Engine webmaster's verifications and analytics
	 */
	 
	 function print_google_verification() {
		 if (!$google_verification = get_option(BasicSEO::$prefix . 'google_verification')) {
				return;
			} // end if
			
			$google_verification = apply_filters('bseop_google_verification', $google_verification);
		
			if(is_home()) {
				if (!empty($google_verification)) {
					$html = $google_verification;
					echo $html . "\n";
				}
			}
	 }
	 
	 function print_bing_verification() {
		 if (!$bing_verification = get_option(BasicSEO::$prefix . 'bing_verification')) {
				return;
			} // end if
			
			$bing_verification = apply_filters('bseop_bing_verification', $bing_verification);
		
			if(is_home()) {
				if (!empty($bing_verification)) {
					$html = $bing_verification;
					echo $html . "\n";
				}
			}
	 }
	 
	 function print_alexa_verification() {
		 if (!$alexa_verification = get_option(BasicSEO::$prefix . 'alexa_verification')) {
				return;
			} // end if
			
			$alexa_verification = apply_filters('bseop_alexa_verification', $alexa_verification);
		
			if(is_home()) {
				if (!empty($alexa_verification)) {
					$html = $alexa_verification;
					echo $html . "\n";
				}
			}
	 }
	 
	 function print_google_analytics() {
		 if (!$google_analytics = get_option(BasicSEO::$prefix . 'google_analytics')) {
				return;
			} // end if
			
			$google_analytics = apply_filters('bseop_google_analytics', $google_analytics);
		
			//if(is_home()) {
				if (!empty($google_analytics)) {
					$html = $google_analytics;
					echo"\n<!-- Begin bseop_google_analytics -->\n"; ?>
					<script type="text/javascript">
						var _gaq = _gaq || [];
						_gaq.push(['_setAccount', '<?php echo $html;?>']);
						_gaq.push(['_trackPageview']);
						(function() {
							var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
							ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
							var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
						})();
					</script>
					<?php echo "\n<!-- End bseop_google_analytics -->\n";
				}
			//}
	 }
	 
	/**
	 * Setup the metabox data for posts and pages
	 */
	function add_metabox() {
		
		$metabox = &BasicSEO::$metabox;
		foreach ($metabox['page'] as $post_type) {
			add_meta_box($metabox['id'], $metabox['title'], array('BasicSEO', 'show_metabox'), $post_type, $metabox['context'], $metabox['priority'], $metabox['fields']);
		} // end foreach
		
	} // end function
	

	/**
	 * Displays the metabox content
	 */
	function show_metabox($post, $params) {
		
		// Use nonce for verification
		echo '<input type="hidden" name="' . BasicSEO::$prefix . 'meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		$requiredFields = array();
		
		echo '<div id="bseop-data">';
		
		foreach ($params['args'] as $field) {
			
			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);

			$field_id = str_replace(BasicSEO::$prefix, '', $field['id']);
			$field_id = $field['id'];
			
			$required = '';
			if (in_array($field['id'], $requiredFields)) {
				$required = ' required';
			} // end if
			
			$beforeField = '';

			echo '<p><label for="', $field_id, '" style="font-weight:bold;">', $field['name'], ' </label>';
			
			switch ($field['type']) {

				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : '', '" size="30" class="large-text' . $required . '" />', '
					', $field['desc'];
				break;

				case 'small-text':
					echo  $beforeField, '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : "", '" size="30" class="small-text' . $required . '" />', '
					', $field['desc'];
				break;

				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:99%">', $meta ? $meta : "", '</textarea>', '
					', $field['desc'];
				break;

				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					} // end foreach
					echo '</select>';
				break;

				case 'radio':
					foreach ($field['options'] as $option) {
						echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
					} // end foreach
				break;

				case 'checkbox':
					echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
					break;
				break;

			} // end switch
			echo '</p>';

		} // end foreach
		echo '</div>';
	} // end function
	
	
	/**
	 * Check posted data and saves metabox data
	 */
	function save_postdata($post_id) {

		// verify nonce
	    if (!wp_verify_nonce($_POST[BasicSEO::$prefix . 'meta_box_nonce'], basename(__FILE__))) {
	        return $post_id;
	    } // end if
	    
        if ( wp_is_post_revision( $post_id ) ) {
            return $post_id;
        } // end if

	    // check autosave
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	        return $post_id;
	    } // end if

	    // check permissions
	    if ('page' == $_POST['post_type']) {
	        if (!current_user_can('edit_page', $post_id)) {
	            return $post_id;
	        } // end if
	    } elseif (!current_user_can('edit_post', $post_id)) {
	        return $post_id;
	    } // end if

	    if ( !wp_is_post_revision( $post_id ) ) {

    		$bseop_fields = BasicSEO::$metabox['fields'];

    	    foreach ($bseop_fields as $field) {

    	        $old = get_post_meta($post_id, $field['id'], true);
    	        $new = (isset($_POST[$field['id']])) ? $_POST[$field['id']]: null;

    	        if ($new && $new != $old) {
    	            update_post_meta($post_id, $field['id'], $new);
    	        } elseif ('' == $new && $old) {
    	            delete_post_meta($post_id, $field['id'], $old);
    	        } // end if

    	    } // end foreach

	    } // end if

	} // end function
	
} // end class


/// MAIN----------------------------------------------------------------------

add_action('plugins_loaded', array('BasicSEO', 'init'));

?>
