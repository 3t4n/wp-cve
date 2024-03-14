<?php
/*
Plugin Name: Mail Subscribe List
Plugin URI: http://www.webfwd.co.uk/packages/wordpress-hosting/
Description: Simple customisable plugin that displays a name/email form where visitors can submit their information, managable in the WordPress admin.
Version: 2.1.10
Author: Richard Leishman t/a Webforward
Author URI: http://www.webfwd.co.uk/
License: GPL


Copyright 2012 Richard Leishman t/a Webforward  (email : richard@webfwd.co.uk)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

GNU General Public License: http://www.gnu.org/licenses/gpl.html

*/

// Disables the block editor from managing widgets in the Gutenberg plugin.
//add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
//add_filter( 'use_widgets_block_editor', '__return_false' );

// Plugin Activation
function smlp_install() {
    global $wpdb;
    $table = $wpdb->prefix."sml";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        sml_name VARCHAR(200) NOT NULL,
        sml_email VARCHAR(200) NOT NULL,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
	
}
register_activation_hook( __FILE__, 'smlp_install' );

// Plugin Deactivation
function smlp_uninstall() {
    global $wpdb;
	
}
register_deactivation_hook( __FILE__, 'smlp_uninstall' );

// Left Menu Button
function register_sml_menu() {
	add_menu_page('Subscribers', 'Subscribers', 'add_users', dirname(__FILE__).'/index.php', '',   plugins_url('sml-admin-icon.png', __FILE__), 58.122);
}
add_action('admin_menu', 'register_sml_menu');
add_action('admin_init', 'sml_export_csv');

// Generate Subscribe Form 

function smlsubform($atts=array()){
	extract(shortcode_atts(array(
		"prepend" => '',  
        "showname" => true,
		"nametxt" => 'Name:',
		"nameholder" => 'Name...',
		"emailtxt" => 'Email:',
		"emailholder" => 'Email Address...',
		"showsubmit" => true,
		"submittxt" => 'Submit',
		"jsthanks" => false,
		"thankyou" => 'Thank you for subscribing to our mailing list'
    ), $atts));
	
	$return = '<form class="sml_subscribe" method="post"><input class="sml_hiddenfield" name="sml_subscribe" type="hidden" value="1">';
	
	if ($prepend) $return .= '<p class="prepend">'.$prepend.'</p>';
	
	if (array_key_exists('sml_subscribe', $_POST) && $_POST['sml_subscribe'] && $thankyou) { 
		if ($jsthanks) {
			$return .= "<script>window.onload = function() { alert('".esc_html($thankyou)."'); }</script>";
		} else {
			$return .= '<p class="sml_thankyou">'.esc_html($thankyou).'</p>'; 
		}
	}
	
	
	if ($showname) $return .= '<p class="sml_name"><label class="sml_namelabel" for="sml_name">'.esc_html($nametxt).'</label><input class="sml_nameinput" placeholder="'.esc_attr($nameholder).'" name="sml_name" type="text" value=""></p>';
	$return .= '<p class="sml_email"><label class="sml_emaillabel" for="sml_email">'.esc_html($emailtxt).'</label><input class="sml_emailinput" name="sml_email" placeholder="'.esc_attr($emailholder).'" type="text" value=""></p>';
	if ($showsubmit) $return .= '<p class="sml_submit"><input name="submit" class="btn sml_submitbtn" type="submit" value="'.($submittxt?esc_attr($submittxt):'Submit').'"></p>';
	$return .= '</form>';
	
 	return $return;
}
add_shortcode( 'smlsubform', 'smlsubform' );

// Ability to use the shortcode within the text widget, - Suggested by Joel Dare, Thank you.
add_filter('widget_text', 'do_shortcode', 11);

//////

// Lets create a Wordpress Widget

// Widget Controller

function sml_subscribe_widget_control($args=array(), $params=array()) {
	
	if (isset($_POST['sml_subscribe_submitted']) && current_user_can('edit_theme_options')) {
		update_option('sml_subscribe_widget_title', sanitize_text_field($_POST['sml_subscribe_widget_title']));
		update_option('sml_subscribe_widget_prepend', sanitize_text_field($_POST['sml_subscribe_widget_prepend']));
		update_option('sml_subscribe_widget_jsthanks', sanitize_text_field($_POST['sml_subscribe_widget_jsthanks']));
		update_option('sml_subscribe_widget_thankyou', sanitize_text_field($_POST['sml_subscribe_widget_thankyou']));
		update_option('sml_subscribe_widget_showname', sanitize_text_field($_POST['sml_subscribe_widget_showname']));
		update_option('sml_subscribe_widget_nametxt', sanitize_text_field($_POST['sml_subscribe_widget_nametxt']));
		update_option('sml_subscribe_widget_nameholder', sanitize_text_field($_POST['sml_subscribe_widget_nameholder']));
		update_option('sml_subscribe_widget_emailtxt', sanitize_text_field($_POST['sml_subscribe_widget_emailtxt']));
		update_option('sml_subscribe_widget_emailholder', sanitize_text_field($_POST['sml_subscribe_widget_emailholder']));
		update_option('sml_subscribe_widget_showsubmit', sanitize_text_field($_POST['sml_subscribe_widget_showsubmit']));
		update_option('sml_subscribe_widget_submittxt', sanitize_text_field($_POST['sml_subscribe_widget_submittxt']));
	}
	
	$sml_subscribe_widget_title = get_option('sml_subscribe_widget_title');
	$sml_subscribe_widget_prepend = get_option('sml_subscribe_widget_prepend');
	$sml_subscribe_widget_jsthanks = get_option('sml_subscribe_widget_jsthanks');
	$sml_subscribe_widget_thankyou = get_option('sml_subscribe_widget_thankyou');
	$sml_subscribe_widget_showname = get_option('sml_subscribe_widget_showname');
	$sml_subscribe_widget_nametxt = get_option('sml_subscribe_widget_nametxt');
	$sml_subscribe_widget_nameholder = get_option('sml_subscribe_widget_nameholder');
	$sml_subscribe_widget_emailtxt = get_option('sml_subscribe_widget_emailtxt');
	$sml_subscribe_widget_emailholder = get_option('sml_subscribe_widget_emailholder');
	$sml_subscribe_widget_showsubmit = get_option('sml_subscribe_widget_showsubmit');
	$sml_subscribe_widget_submittxt = get_option('sml_subscribe_widget_submittxt');
	?>

	<p>
		<label for="sml_subscribe_widget_title">Title:</label>
		<textarea class="widefat sml_subscribe_widget_title" rows="5" id="sml_subscribe_widget_title" name="sml_subscribe_widget_title"><?php echo esc_textarea($sml_subscribe_widget_title); ?></textarea>
	</p>

	
	<p>
		<label for="sml_subscribe_widget_prepend">Header Text:</label>
		<textarea class="widefat sml_subscribe_widget_prepend" rows="5" id="sml_subscribe_widget_prepend" name="sml_subscribe_widget_prepend"><?php echo esc_textarea($sml_subscribe_widget_prepend); ?></textarea>
	<p>
    
	<p>
		<label for="sml_subscribe_widget_jsthanks">Thank You Type</label>
		<select class="sml_subscribe_widget_jsthanks" id="sml_subscribe_widget_jsthanks" ">
			<option <?php echo ($sml_subscribe_widget_jsthanks?'selected="selected"':''); ?> value="1">JavaScript Alert</option>
			<option <?php echo (!$sml_subscribe_widget_jsthanks?'selected="selected"':''); ?> value="0">Widget Header</option>
		</select>
	</p>
    
    <p>
		<label for="sml_subscribe_widget_thankyou">Thank You Message</label>
		<textarea class="widefat sml_subscribe_widget_thankyou" rows="5" id="sml_subscribe_widget_thankyou" name="sml_subscribe_widget_thankyou"><?php echo esc_textarea($sml_subscribe_widget_thankyou); ?></textarea>
	</p>
    
    Show Name Field <input class="sml_subscribe_widget_showname" name="sml_subscribe_widget_showname" type="checkbox"<?php echo $sml_subscribe_widget_showname?'checked="checked"':''; ?> />
	<br /><br />
    
    <div class="sml_subscribe_nameoptions" style="display:none">
		<p>
			<label for="sml_subscribe_widget_nametxt">Name Label text</label>
			<input type="text" class="widefat sml_subscribe_widget_nametxt" id="sml_subscribe_widget_nametxt" name="sml_subscribe_widget_nametxt" value="<?php echo esc_attr($sml_subscribe_widget_nametxt); ?>" />
		</p>
		
		<p>
			<label for="sml_subscribe_widget_nameholder">Name Placeholder Text</label>
			<input type="text" class="widefat sml_subscribe_widget_nameholder" id="sml_subscribe_widget_nameholder" name="sml_subscribe_widget_nameholder" value="<?php echo esc_attr($sml_subscribe_widget_nameholder); ?>" />
		</p>
    </div>
    
	<p>
    	<label for="sml_subscribe_widget_emailtxt">Email Label Text</label>
		<input type="text" class="widefat sml_subscribe_widget_emailtxt" id="sml_subscribe_widget_emailtxt" name="sml_subscribe_widget_emailtxt" value="<?php echo esc_attr($sml_subscribe_widget_emailtxt); ?>" />
	</p>
    
	<p>
		<label for="sml_subscribe_widget_emailholder">Email Placeholder Text</label>
		<input type="text" class="widefat sml_subscribe_widget_emailholder" id="sml_subscribe_widget_emailholder" name="sml_subscribe_widget_emailholder" value="<?php echo esc_attr($sml_subscribe_widget_emailholder); ?>" />
	</p>
    
    Show Submit Button <input class="sml_subscribe_widget_showsubmit" name="sml_subscribe_widget_showsubmit" type="checkbox"<?php echo $sml_subscribe_widget_showsubmit?'checked="checked"':''; ?> />
	<br /><br />
    
    <div class="sml_subscribe_submitoptions" style="display:none">
    
	<p>
		<label for="sml_subscribe_widget_submittxt">Submit Button Text</label>
		<input type="text" class="widefat sml_subscribe_widget_submittxt" id="sml_subscribe_widget_submittxt" name="sml_subscribe_widget_submittxt" value="<?php echo esc_attr($sml_subscribe_widget_submittxt); ?>" />
	</p>
    
    </div>

	<input type="hidden" name="sml_subscribe_submitted" value="1" />
    <script>
		function sml_subscribe_nameoptions_check() {
			if (jQuery('.sml_subscribe_widget_showname').is(':checked')) jQuery(".sml_subscribe_nameoptions").fadeIn();
			else jQuery(".sml_subscribe_nameoptions").fadeOut();
		}
		function sml_subscribe_submitoptions_check() {
			if (jQuery('.sml_subscribe_widget_showsubmit').is(':checked')) jQuery(".sml_subscribe_submitoptions").fadeIn();
			else jQuery(".sml_subscribe_submitoptions").fadeOut();
		}
		jQuery(document).ready(function(){
			sml_subscribe_nameoptions_check();
			sml_subscribe_submitoptions_check();
			jQuery(".sml_subscribe_widget_showname").click(function(){ sml_subscribe_nameoptions_check(); });
			jQuery(".sml_subscribe_widget_showsubmit").click(function(){ sml_subscribe_submitoptions_check(); });
		});
    </script>
	<?php
}

wp_register_widget_control(
	'sml_subscribe_widget',
	'sml_subscribe_widget',
	'sml_subscribe_widget_control'
);

// Widget Display

function sml_subscribe_widget_display($args=array(), $params=array()) {

	$sml_subscribe_widget_title = get_option('sml_subscribe_widget_title');
	$sml_subscribe_widget_prepend = get_option('sml_subscribe_widget_prepend');
	$sml_subscribe_widget_jsthanks = get_option('sml_subscribe_widget_jsthanks');
	$sml_subscribe_widget_thankyou = get_option('sml_subscribe_widget_thankyou');
	$sml_subscribe_widget_showname = get_option('sml_subscribe_widget_showname');
	$sml_subscribe_widget_nametxt = get_option('sml_subscribe_widget_nametxt');
	$sml_subscribe_widget_nameholder = get_option('sml_subscribe_widget_nameholder');
	$sml_subscribe_widget_emailtxt = get_option('sml_subscribe_widget_emailtxt');
	$sml_subscribe_widget_emailholder = get_option('sml_subscribe_widget_emailholder');
	$sml_subscribe_widget_showsubmit = get_option('sml_subscribe_widget_showsubmit');
	$sml_subscribe_widget_submittxt = get_option('sml_subscribe_widget_submittxt');

	//widget output
	echo $args['before_widget'];

	echo $args['before_title'];
	echo esc_html($sml_subscribe_widget_title);
	echo $args['after_title'];

	echo '<div class="textwidget">';

	$argss = array(
		'prepend' => $sml_subscribe_widget_prepend, 
		'showname' => $sml_subscribe_widget_showname,
		'nametxt' => $sml_subscribe_widget_nametxt, 
		'nameholder' => $sml_subscribe_widget_nameholder, 
		'emailtxt' => $sml_subscribe_widget_emailtxt,
		'emailholder' => $sml_subscribe_widget_emailholder, 
		'showsubmit' => $sml_subscribe_widget_showsubmit,
		'submittxt' => $sml_subscribe_widget_submittxt, 
		'jsthanks' => $sml_subscribe_widget_jsthanks,
		'thankyou' => $sml_subscribe_widget_thankyou
	);
	echo smlsubform($argss);

	echo '</div>';
  echo $args['after_widget'];
}

wp_register_sidebar_widget(
    'sml_subscribe_widget',
    'Subscribe Form',
    'sml_subscribe_widget_display',
    array(
        'description' => 'Display Subscribe Form'
    )
);

/////////

// Handle form Post
if (array_key_exists('sml_subscribe', $_POST) && $_POST['sml_subscribe']) {
	$name = sanitize_text_field($_POST['sml_name']);
	$email = sanitize_text_field($_POST['sml_email']);

	if (is_email($email)) {
		$exists = $wpdb->get_row($wpdb->prepare("SELECT COUNT(`id`) as 'count' FROM ".$wpdb->prefix."sml WHERE sml_email = %s LIMIT 1", $email));

		if ((int) $exists->count === 0) {
			$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."sml (sml_name, sml_email) VALUES (%s, %s)", $name, $email));
		}
	}
}

function smlp_plugin_get_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}

function sml_export_csv() {
	if (array_key_exists('page', $_GET) && strstr($_GET['page'], 'mail-subscribe-list') && array_key_exists('export-csv', $_GET) && current_user_can('manage_options')) {
		header("Content-type: application/force-download"); 
		header('Content-Disposition: inline; filename="subscribers'.date('YmdHis').'.csv"');
		global $wpdb;

		$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."sml");
		echo "First Name,Last Name,Email Address\r\n";
		if (count($results)) {
			foreach ($results as $row) {
				$n = Smlp_Split_name($row->sml_name);
				echo sprintf("%s,%s,%s\r\n", esc_html($n['first']), esc_html($n['last']), esc_html($row->sml_email));
			}
		}
		exit;
	}
}

/**
 * Split name field.
 *
 * @param $name - String
 * 
 * @return Array
 */
function Smlp_Split_name($name)
{
    $results = array();

    $r = explode(' ', $name);
    $size = count($r);

    if (mb_strpos($r[0], '.') === false) {
        $results['salutation'] = '';
        $results['first'] = $r[0];
    } else {
        $results['salutation'] = $r[0];
        $results['first'] = $r[1];
    }

    if (mb_strpos($r[$size - 1], '.') === false) {
        $results['suffix'] = '';
    } else {
        $results['suffix'] = $r[$size - 1];
    }

    $start = ($results['salutation']) ? 2 : 1;
    $end = ($results['suffix']) ? $size - 2 : $size - 1;

    $last = '';
    for ($i = $start; $i <= $end; $i++) {
        $last .= ' '.$r[$i];
    }
    $results['last'] = trim($last);

    return $results;
}

