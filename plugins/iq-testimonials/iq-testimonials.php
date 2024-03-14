<?php 
/*

 Plugin Name: IQ Testimonials
  
  Description: With this plugin you can easily add, edit, and delete client testimonials for your website. Your users can submit testimonials and with your approval they will instantly be displayed on your website.
  Author: Roger MacRae
  Author URI: http://www.rogermacrae.com/

  Version: 2.2.7
*/

$upload_dir = ABSPATH.'wp-content/uploads';
$url_upload_dir = wp_upload_dir();
define('UPLOADED_IMAGE_DESTINATION', $upload_dir.'/iqt-images/');
define('PROCESSED_IMAGE_DESTINATION', $url_upload_dir['baseurl'].'/iqt-images/');
include_once('lib/iq-testimonials-form.php');
include_once('lib/iq-testimonials-widget.php');
// Settings current DB version for future upgrades
define('IQ_TESTIMONIALS_DB_VERSION', '2.2.7');


// Let's run the install/upgrade function
register_activation_hook(__FILE__,'iq_testimonials_install');

function iq_testimonials_install(){
	global $wpdb;
	
	// Create the image directory iqt-images if it doesn't exist
			$target_path = ABSPATH.'wp-content/uploads/iqt-images';

			if(!file_exists($target_path)) { @mkdir($target_path."/"); }
	// Grabbing DB prefix and settings table names to variable
	$testimonials = $wpdb->prefix . "iq_testimonials";
	$iq_testimonials_settings = $wpdb->prefix . "iq_testimonials_settings";
	
	// Does the database already exist?
	if ($wpdb->get_var("show tables like '$testimonials'") != $testimonials){ // No, it doesn't
	
	update_option('iq-testimonials-db-version', IQ_TESTIMONIALS_DB_VERSION);
		
		// Creating the testimonials table!
		$sql1 = "CREATE TABLE " . $testimonials . " (
		id INT(12) NOT NULL AUTO_INCREMENT,
		status INT(12) NOT NULL DEFAULT '0',
		featured INT(12) NOT NULL DEFAULT '0',
		date DATE NULL,
		name VARCHAR(255),
		location VARCHAR(255),
		quote TEXT,
		website VARCHAR(255),
		imagelink VARCHAR(255),
		PRIMARY KEY (id)
		);";
		
		// Creating the testimonials settings table!
		$sql2 = "CREATE TABLE " . $iq_testimonials_settings . " (
			id INT(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(255) NOT NULL,
			value VARCHAR(100)
		);";

		// Requiring WP upgrade and running SQL query
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql1);
		dbDelta($sql2);

		// Populating the DB with test entry
		$name = "Roger MacRae";
		$date = date("Y-m-d");
		$location = "Abbotsford, BC";
		$quote = "This is a sample testimonial. Feel free to delete it.";
		$website = "http://www.rogermacrae.com/";
		$imagelink = "http://1.gravatar.com/avatar/99f21a11259ba33a874c6d80546a55c1?s=125&d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D60&r=G";
		$website_final = "";
		if (!empty($website)) {
			$website_final = (substr(ltrim($website), 0, 7) != 'http://' ? 'http://' : '') . $website;
		}
		
		// Inserting testimonial into the DB
			$insert = "INSERT INTO " . $testimonials .
			" (id, status, date, name, location, quote, website, imagelink) " .
			"VALUES ('', '" . $wpdb->escape($status) . "', '" . $wpdb->escape($currdate) . "', '" . $wpdb->escape($name) . "', '" . $wpdb->escape($location) . "', '" . $wpdb->escape($quote) . "', '" . $wpdb->escape($website_final) . "', '" . $wpdb->escape($imagelink) . "')";
		
		// Running the query
		$results = $wpdb->query($insert);
		
		$admin_email = get_option('admin_email');
		
		// Inserting default settings into the DB		
		$insert = "INSERT INTO " . $iq_testimonials_settings .
		" (id, name, value) " .
		"VALUES ('1','admin_email','$admin_email'), ('2','date','1'), ('3','max_widget_testimonials','1'), ('4','max_page_testimonials','15'), ('5','max_image_width','125'), ('6','max_image_height','125'), ('7','randomize','true'), ('8','form_name','true'), ('9','form_email','true'), ('10', 'form_location',''), ('11', 'form_website',''), ('12', 'dynamically_rotate','true'), ('13', 'rotation_speed','12000'), ('14', 'fade_speed','1000'), ('15', 'name_field',''), ('16', 'email_field',''), ('17', 'location_field',''), ('18', 'website_field',''), ('19', 'image_field',''), ('20', 'show_more',''), ('21', 'show_more_link',''), ('22', 'show_more_text','Read more testimonials'), ('23', 'recaptcha_toggle',''), ('24', 'recaptcha_public_key',''), ('25', 'recaptcha_private_key','')";
		
		// Running the query
		$results = $wpdb->query($insert);
	}
	
	else {
		 
	    if (get_option('iq-testimonials-db-version') != IQ_TESTIMONIALS_DB_VERSION) {
			
	// Deleting Old Settings
		mysql_query("DROP table $iq_testimonials_settings");
		
		$sql1 = "CREATE TABLE " . $iq_testimonials_settings . " (
			id INT(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(255) NOT NULL,
			value VARCHAR(100)
		);";

		// Requiring WP upgrade and running SQL query
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql1);

		$admin_email = get_option('admin_email');
		
		$insert = "INSERT INTO " . $iq_testimonials_settings .
		" (id, name, value) " .
		"VALUES ('1','admin_email','$admin_email'), ('2','date','1'), ('3','max_widget_testimonials','1'), ('4','max_page_testimonials','15'), ('5','max_image_width','125'), ('6','max_image_height','125'), ('7','randomize','true'), ('8','form_name','true'), ('9','form_email','true'), ('10', 'form_location',''), ('11', 'form_website',''), ('12', 'dynamically_rotate','true'), ('13', 'rotation_speed','12000'), ('14', 'fade_speed','1000'), ('15', 'name_field',''), ('16', 'email_field',''), ('17', 'location_field',''), ('18', 'website_field',''), ('19', 'image_field',''), ('20', 'show_more',''), ('21', 'show_more_link',''), ('22', 'show_more_text','Read more testimonials'), ('23', 'recaptcha_toggle',''), ('24', 'recaptcha_public_key',''), ('25', 'recaptcha_private_key','')";
		
		// Running the query
		$results = $wpdb->query($insert);
		
		update_option('iq-testimonials-db-version', IQ_TESTIMONIALS_DB_VERSION);
		}
	}		
}

/**
  * Register IQ Testimonials Widget.
  *
  * Calls 'widgets_init' action after_title the IQ Testimonials widget has been registered.
  */
function iq_testimonials_widget_Init(){
	register_widget('IQTWidget');
}

// Add a settings link to the Plugins page
function iq_testimonials_plugin_actions($links, $file){
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if (!$this_plugin){ $this_plugin = plugin_basename(__FILE__); }
	
	if ($file == $this_plugin){
		$settings_link = '<a href="admin.php?page=iq_testimonials_settings">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

function iq_testimonial_style(){
        wp_register_style('iq-testimonials', plugins_url('css/iq-testimonials-style.css', __FILE__));
		wp_enqueue_style('iq-testimonials');
}

function iq_testimonials_admin_styles() {
        wp_register_style('iq-testimonials-admin', plugins_url('css/admin.css', __FILE__));
		wp_enqueue_style('iq-testimonials-admin');
				//	echo  '<link type="text/css" rel="stylesheet" href="' . WP_PLUGIN_URL. '/iq-testimonials/css/admin.css" />';
}

//widget was here

/* WordPress Menu */
function iq_testimonials_admin(){
	include_once('admin/iq-testimonials-admin.php');
}

function iq_testimonials_settings(){
	include_once('admin/iq-testimonials-settings.php');
}

function iq_testimonials_help(){
	include_once('admin/iq-testimonials-help.php');
}

/*
	Creates the menu with the following options in order.
	(Page Title, Menu Title, Permission Level, File, Admin Page Function, Icon URL)
*/

function iq_testimonials_config_menu(){	
	
	if (function_exists('add_menu_page')){
		add_menu_page('IQ Testimonials', 'IQ Testimonials', '10', "iq_testimonials_admin", "iq_testimonials_admin", get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/testimonials.png');			
		add_submenu_page('iq_testimonials_admin', 'IQ Testimonials Settings', 'Settings', '10', 'iq_testimonials_settings', 'iq_testimonials_settings');
		add_submenu_page('iq_testimonials_admin', 'IQ Testimonials Help', 'Help', '10', 'iq_testimonials_help', 'iq_testimonials_help');
	}
}

// Main Function Code, to be included on themes
function iq_testimonials($id='', $is_shortcode){
	// Required for all WordPress database manipulations
	global $wpdb;
	
	// Grabbing DB prefix and settings table names to variable
	$testimonials = $wpdb->prefix . "iq_testimonials";
	$iq_testimonials_settings = $wpdb->prefix . "iq_testimonials_settings";
	$max_image_width = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='max_image_width'", ARRAY_A);
	$max_image_width = $max_image_width['value'];
	$max_image_height = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='max_image_height'", ARRAY_A);
	$max_image_height = $max_image_height['value'];
	$randomize_testimonials = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='randomize'", ARRAY_A);
	$randomize_testimonials = $randomize_testimonials['value'];
	$dynamically_rotate = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='dynamically_rotate'", ARRAY_A);
	$dynamically_rotate = $dynamically_rotate['value'];
	$rotation_speed = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='rotation_speed'", ARRAY_A);
	$rotation_speed = $rotation_speed['value'];
	$fade_speed = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='fade_speed'", ARRAY_A);
	$fade_speed = $fade_speed['value'];
	$show_more = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='show_more'", ARRAY_A);
	$show_more = $show_more['value'];
	$show_more_link = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='show_more_link'", ARRAY_A);
	$show_more_link = $show_more_link['value'];
	$show_more_text = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='show_more_text'", ARRAY_A);
	$show_more_text = $show_more_text['value'];
	
	$non_featured_testimonials_count = 0;
	
	$query = "SELECT COUNT(*) AS total FROM $testimonials";
	$total_testimonials_count = $wpdb->get_var($query);
	
	$query = "SELECT COUNT(*) AS total FROM $testimonials WHERE featured=1";
	$featured_testimonials_count = $wpdb->get_var($query);
	
	// How many testimonials are we going to show when called?
	if ($is_shortcode == true) {
		$max_widget_testimonials = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='max_page_testimonials'", ARRAY_A);
	}
	else
	{
		$max_widget_testimonials = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='max_widget_testimonials'", ARRAY_A);	
	}
		$max_widget_testimonials = $max_widget_testimonials['value'];
		
	if ($featured_testimonials_count < $max_widget_testimonials) {
	$non_featured_testimonials_count = $max_widget_testimonials - $featured_testimonials_count;
	}
	else
	if ($featured_testimonials_count > $max_widget_testimonials) {
	$featured_testimonials_count = $max_widget_testimonials;
	}
				
	if (!empty($id)){ // If the id has been set, show that testimonial instead!
		$query = "SELECT * FROM $testimonials WHERE status!='1' AND status!='2' AND id IN($id) ORDER BY FIELD(id, $id)";
	}
	else
	{
		if ((!$is_shortcode == true) && ($dynamically_rotate == true)) {
		$query = "(SELECT * FROM $testimonials WHERE status!='1' AND status!='2' AND featured='1')";
		$query .= " UNION";
		$query .= " (SELECT * FROM $testimonials WHERE status!='1' AND status!='2' AND featured='0' ORDER BY RAND())";
		$query .= " ORDER BY featured='1' DESC";
		}
		else
		{
		$query = "(SELECT * FROM $testimonials WHERE status!='1' AND status!='2' AND featured='1' LIMIT $max_widget_testimonials)";
		if ($non_featured_testimonials_count > 0) {
		$query .= " UNION";
		$query .= " (SELECT * FROM $testimonials WHERE status!='1' AND status!='2' AND featured='0'";
		if ($randomize_testimonials == true) {
		$query .= " ORDER BY RAND()";
		}
		$query .= " LIMIT $non_featured_testimonials_count)";
		$query .= " ORDER BY featured='1' DESC";
		}			
		}
	}

		$results = $wpdb->get_results($query, ARRAY_A);
		$i=1;
		foreach ($results as $data) {
			// Setting the testimonial information to variables for easy access
			if (($data['featured'] == 1) && (!$dynamically_rotate == true)) {
				$featuredpre = '<div class="iq-testimonial-wrap"><div class="iq-testimonial-featured">';
			}
			else
			{
				$featuredpre = '<div class="iq-testimonial-wrap"><div class="iq-testimonial-body">';
			}
			$name = stripslashes($data['name']);
			if (!empty($name)) {$name = '<span class="iq-testimonial-name">'.$name.'</span>';}
			
			$location = '<span class="iq-testimonial-location">'.stripslashes($data['location']).'</span>';
			if ((!empty($name)) && (!empty($location))) {$location = ', <span class="iq-testimonial-location">'.$location.'</span>';}
			$quote = '<span class="iq-testimonial-quote">'.stripslashes($data['quote']).'</span>';
			$websitepre = '';
			$websitesuf = '';
			$website = str_replace('http://','',$data['website']);
				if (!empty($website)){					
					$websitepre = '<br /><a class="iq-testimonial-website" href="http://'.$website.'" target="_blank">';
					$websitesuf = '</a>';
				}
			$imagelink = $data['imagelink'];
				if (!empty($imagelink)) {
					list($width_orig, $height_orig) = getimagesize($imagelink);
					$ratio_orig = $width_orig/$height_orig;
					if ($max_image_width/$max_image_height > $ratio_orig) {
   						$width = $max_image_height*$ratio_orig;
						$height = $max_image_height;
					} else {
   						$height = $max_image_width/$ratio_orig;
						$width = $max_image_width;
					}
					$imagelink = '<img class="iq-testimonial-image" src="'.$imagelink.'" width="'.$width.'" height="'.$height.'" />';
				}
			if (($show_more == true) && ($show_more_link > '')) {
				$show_more_html = '<div class="iq-testimonials-more"><a class="iq-testimonials-more-link" href="'.$show_more_link.'">'.$show_more_text.'</a></div>';
			}
	
			// Final HTML Output
			if ((!$is_shortcode == true) && ($dynamically_rotate == true) && ($i > 0)) {
			if ($i == 1) {
				$theTestimonial .= '<div class="iq-testimonial-wrap"><div class="iq-testimonial-body">' . $imagelink .' '. $quote . '<br />' .$name. $location . ' ' . $websitepre . $website . $websitesuf . '</div>'.$show_more_html.'</div>';
				}
				else
				{
			$theTestimonial .= '<div class="iq-testimonial-wrap" style="display: none;"><div class="iq-testimonial-body">' . $imagelink .' '. $quote . '<br />' .$name.$location . ' ' . $websitepre . $website . $websitesuf . '</div>'.$show_more_html.'</div>';
				}
			}
			else
			{
			$theTestimonial .= $featuredpre . $imagelink .' '. $quote . '<br />' .$name.$location . ' ' . $websitepre . $website . $websitesuf . '</div></div>';
			}
			$i++;
			//echo $theTestimonial;
		}
		
		if ((!$is_shortcode == true) && ($dynamically_rotate == true)) {
		$rotate_script = '<script language="javascript">';
		$rotate_script .= ' jQuery(document).ready(function(){';
		$rotate_script .= " jQuery('#iq-testimonials-box .iq-testimonial-wrap');";
		$rotate_script .= ' setInterval(function(){';
		$rotate_script .= " jQuery('#iq-testimonials-box .iq-testimonial-wrap').filter(':visible').fadeOut(".$fade_speed.",function(){";
		$rotate_script .= "	if(jQuery(this).next('.iq-testimonial-wrap').size()){";
		$rotate_script .= "	jQuery(this).next().fadeIn(".$fade_speed.");";
		$rotate_script .= '	}';
		$rotate_script .= '	else{';
		$rotate_script .= " jQuery('#iq-testimonials-box .iq-testimonial-wrap').eq(0).fadeIn(".$fade_speed.");";
		$rotate_script .= '	}';
		$rotate_script .= '	});';
		$rotate_script .= '	},'.$rotation_speed.');';
		$rotate_script .= '	});';
		$rotate_script .= '	</script>';
		
		//return $rotate_script.$theTestimonial;
		return $rotate_script.'<div id="iq-testimonials-box">'.$theTestimonial.'</div>';
	}
	else
	{
		return $theTestimonial;
	}
	}
//}

// Function to display all testimonials on a testimonials page
function iq_testimonials_page($atts){
	// Required for all WordPress database manipulations
	global $wpdb;
	
extract( shortcode_atts( array(
      'id' => '',
      ), $atts ) );
	  
if (!empty($id)) { 
return iq_testimonials($id, true);
}
else
{
return iq_testimonials('', true);
}
}

function iq_testimonials_update_db_check() {
    if (get_option('iq-testimonials-db-version') != IQ_TESTIMONIALS_DB_VERSION) {
        iq_testimonials_install();
    }
}

add_action('plugins_loaded', 'iq_testimonials_update_db_check');

// Adding the filters to get the stylesheet into the head.
add_action('wp_enqueue_scripts', 'iq_testimonial_style');
add_action('admin_enqueue_scripts', 'iq_testimonials_admin_styles');

// Registers the widget when plugin is loaded.
add_action('widgets_init', 'iq_testimonials_widget_Init');

// Registers the settings menu with the testimonials_config_menu function.
add_action('admin_menu', 'iq_testimonials_config_menu');

// Add Settings link on Plugins page
add_filter('plugin_action_links', 'iq_testimonials_plugin_actions', 10, 2);

//Add the shortcodes
add_shortcode('iq-testimonials-page', 'iq_testimonials_page');
add_shortcode('iq-testimonials-form', 'iq_testimonials_form');

?>