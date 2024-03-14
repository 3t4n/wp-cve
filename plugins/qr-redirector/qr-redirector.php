<?php
/**
 * @package QR Redirector
 * @version 2.0.2
 */
/*
Plugin Name: QR Redirector
Plugin URI: http://nlb-creations.com/2012/10/19/wordpress-plugin-qr-redirector/
Description: QR Redirector lets you create dynamic QR Codes by a generating a QR code for a URL on your site, and redirecting that URL anywhere you want.
Author: Nikki Blight <nblight@nlb-creations.com>
Version: 2.0.2
Author URI: http://www.nlb-creations.com
*/

/** 
 * Load the neccessary vendor files for QR Generation.
 * 
 * See documentation at https://github.com/endroid/qr-code 
 */
include('vendor/autoload.php');

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelQuartile;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

/**
 * Load styles and scripts for the admin dashboard
 */
function load_qr_admin_style() {
	global $post_type;
	if( 'qrcode' == $post_type ) {
		wp_register_style( 'qr_admin_css', plugins_url('/assets/admin.css', __FILE__), false, '1.0.0' );
		wp_enqueue_style( 'qr_admin_css' );
		wp_enqueue_style( 'wp-color-picker' );
		
		wp_enqueue_script('quick-edit-script', plugins_url( '/assets/post-quick-edit-script.js', __FILE__), array('jquery','inline-edit-post' ));
		wp_enqueue_script( 'qr-color-script', plugins_url( '/assets/color-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}
add_action('admin_enqueue_scripts', 'load_qr_admin_style');

/*
 * Create a custom post type to hold QR redirect data
 */
function qr_create_post_types() {
	register_post_type( 'qrcode',
			array(
					'labels' => array(
							'name' => __( 'QR Redirects' ),
							'singular_name' => __( 'QR Redirect' ),
							'add_new' => __( 'Add QR Redirect'),
							'add_new_item' => __( 'Add QR Redirect'),
							'edit_item' => __( 'Edit QR Redirect' ),
							'new_item' => __( 'New QR Redirect' ),
							'view_item' => __( 'View QR Redirect' )
					),
					'show_ui' => true,
					'description' => 'Post type for QR Redirects',
					//'menu_position' => 5,
					'menu_icon' => WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'assets/qr-menu-icon.png',
					'public' => true,
					'exclude_from_search' => true,
					'supports' => array('title'),
					'rewrite' => array('slug' => 'qr'),
					'can_export' => true
			)
			);
}
add_action( 'init', 'qr_create_post_types' );

/**
 * Intercept a QR Code post before it actually renders, and redirect to the specified URL
 */
function qr_redirect_to_url() {
	global $post;
	
	//for backwards compatibility
	if(!isset($post->ID)) {
		//get the post_name so we can look up the post id
		if(stristr($_SERVER['REQUEST_URI'], "/") && stristr($_SERVER['REQUEST_URI'], "/qr/")) {
			$uri = explode("/", $_SERVER['REQUEST_URI']);
			
			foreach($uri as $i => $u) {
				if($u == '') {
					unset($uri[$i]);
				}
			}
			$uri = array_pop($uri);
		}
		else {
			$uri = $_SERVER['REQUEST_URI'];
		}
	
		$post = get_page_by_path($uri,'OBJECT','qrcode');
	}
	
	if(!is_admin() && is_singular( 'qrcode' )) {
		$url = get_post_meta($post->ID, 'qr_redirect_url', true);
		$response = get_post_meta($post->ID, 'qr_redirect_response', true);
		
		if($url != '') {
			qr_add_count($post->ID); //increment the redirect count
			
			if($response == '') {
				header( 'Cache-Control: no-store, no-cache, must-revalidate' ); //prevent browers from caching the redirect url
				header( 'Location: '.$url, true );
			}
			else {
				header( 'Cache-Control: no-store, no-cache, must-revalidate' ); //prevent browers from caching the redirect url
				header( 'Location: '.$url, true, $response );
			}
			exit();
		}
		else {
			//if for some reason there's no url, redirect to homepage
			header( 'Cache-Control: no-store, no-cache, must-revalidate' ); //prevent browers from caching the redirect url
			header( 'Location: '.get_bloginfo('url'));
			exit();
		}
	}
}
add_action( 'wp', 'qr_redirect_to_url' );


/**
 * Keep some very basic stats on how mant times a QR Code has been used
 * 
 * @param int $post_id - the ID of the QR Code post
 */
//simple function to keep some stats on how many times a QR Code has been used
function qr_add_count($post_id) {
	$count = get_post_meta($post_id,'qr_redirect_count',true);
	if(!$count) { //for new QR codes, set count to 0
		$count = 0;
	}
	
	$count = $count + 1;
	update_post_meta($post_id,'qr_redirect_count',$count);
}

/**
 * Reset the count for a given QR Code.  Called via AJAX ( see qr_clear_count_javascript() and qr_image_custom_box() functions).
 * 
 * @param int $post_id
 */
function qr_clear_count($post_id) {
	if(!$post_id) {
		$post_id = $_POST['post_id'];
	}
	
	$count = 0;
	update_post_meta($post_id,'qr_redirect_count',$count);
}

/**
 * Add custom meta boxes to the edit screen for a qrcode post type 
 */
function qr_dynamic_add_custom_box() {
    //the redirect url
	add_meta_box(
		'dynamic_url',
		__( 'Redirect URL', 'myplugin_textdomain' ),
		'qr_redirect_custom_box',
		'qrcode');
        
	//the actual generated qr code
	add_meta_box(
		'dynamic_qr',
		__( 'QR Code', 'myplugin_textdomain' ),
		'qr_image_custom_box',
		'qrcode',
		'side');
}
add_action( 'add_meta_boxes', 'qr_dynamic_add_custom_box' );

/**
 * Outputs the HTML for the custom meta box containing the post_meta fields for the qrcode post type on the edit page
 */
function qr_redirect_custom_box() {
    global $post;
    
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'dynamicMeta_noncename' );
    
    echo '<div id="meta_inner">';

    //get the saved metadata, if there is any
    $url = get_post_meta($post->ID,'qr_redirect_url',true);
    $ecl = get_post_meta($post->ID,'qr_redirect_ecl',true);
    $size = get_post_meta($post->ID,'qr_redirect_size',true);
    $response = get_post_meta($post->ID,'qr_redirect_response',true);
    $notes = get_post_meta($post->ID,'qr_redirect_notes',true);
    $fgcolor = get_post_meta($post->ID,'qr_fg_color',true);
    $bgcolor = get_post_meta($post->ID,'qr_bg_color',true);
    $bg_trans = get_post_meta($post->ID,'qr_transparent',true);
	
    //set defaults for foreground and background colors if they're not already set
    if(!$fgcolor) {
    	$fgcolor = '#000000'; //black
    }
    
    if(!$bgcolor) {
    	$bgcolor = '#ffffff'; //white
    }
    
    //output the form
	echo '<p> <strong>URL to Redirect to:</strong> <input type="text" name="qr_redirect[url]" value="'.$url.'" style="width: 80%;" /> </p>';
	
	//Error Correction Level Field
	echo '<p>';
	echo '<div class="tooltip"><strong style="width: 150px; display: inline-block;">Error Correction Level:</strong> ';
	echo '<span class="tooltiptext">The Error Correction Level is the amount of "backup" data in the QR code to account for damage it may receive in its intended environment.  Higher levels result in a more complex QR image.</span>';
	echo '</div>';
	echo '<select name="qr_redirect[ecl]">';
	echo '<option value="L"';
	if($ecl == "L") { echo ' selected="selected"'; }
	echo '>L - recovery of up to 7% data loss</option>';
	echo '<option value="M"';
	if($ecl == "M") {
		echo ' selected="selected"';
	}
	echo '>M - recovery of up to 15% data loss</option>';
	echo '<option value="Q"';
	if($ecl == "Q") {
		echo ' selected="selected"';
	}
	echo'>Q - recovery of up to 25% data loss</option>';
	echo '<option value="H"';
	if($ecl == "H") {
		echo ' selected="selected"';
	}
	echo '>H - recovery of up to 30% data loss</option>';
	echo '</select></p>';
	
	//Size Field
	echo '<p>';
	echo '<div class="tooltip"><strong style="width: 150px; display: inline-block;">Size:</strong> ';
	echo '<span class="tooltiptext">The size in pixels of the generated QR code.</span>';
	echo '</div>';
	echo '<select name="qr_redirect[size]">';
	for($i=1; $i<=10; $i++) {
		echo '<option value="'.$i.'00"';
		if((!$size || $size <= 30) && $i==3) {
			echo ' selected="selected"';
		}
		elseif($size == $i.'00') {
			echo ' selected="selected"';
		}
		echo '>'.$i.'00 x '.$i.'00 pixels';
		echo '</option>';
	}
	echo '</select>';
	
	//set a notification for QR Codes saved using the old library that they need to update their size settings
	if($size <= 30) {
		echo '<span style="color: #d02e34; background-color: #fbbbc0; border: 1px solid #d02e34; padding: 10px; margin-left: 25px;">* This QR code uses a size from a previous version of this plugin.  Please select a new size.  300x300 has been selected by default.</span>';
	}
	
	echo '</p>';
	
	//Reponse Code Field
	echo '<p>';
	echo '<div class="tooltip"><strong style="width: 150px; display: inline-block;">HTTP Response Code:</strong> ';
	echo '<span class="tooltiptext">The HTTP Response Code defaults to 302 - Found.  You may set it to any of the specified options, if needed.</span>';
	echo '</div>';
	echo '<select name="qr_redirect[response]">';
	echo '<option value="301"';
	if($response == "301") { echo ' selected="selected"'; }
	echo '>301 - Moved Permanently</option>';
	echo '<option value="302"';
	if($response == "302" || $response == '') {
		echo ' selected="selected"';
	}
	echo '>302 - Found</option>';
	echo '<option value="307"';
	if($response == "307") {
		echo ' selected="selected"';
	}
	echo'>307 - Temporary Redirect';
	echo '<option value="308"';
	if($response == "308") {
		echo ' selected="selected"';
	}
	echo '>308 - Permanent Redirect</option>';
	echo '</select></p>';
	
	//Foreground and Background Color Picker Fields
	echo '<p><strong>Foreground Color:</strong> ';
	echo '<input class="color-field" name="qr_redirect[qr_fg_color]" value="'.$fgcolor.'" />';
	echo '</p>';
	
	echo '<p><strong>Background Color:</strong> ';
	echo '<input class="color-field" name="qr_redirect[qr_bg_color]" value="'.$bgcolor.'" />';
	
	echo '<input type="checkbox" name="qr_redirect[qr_transparent]"';
	if($bg_trans == 'on') {
		echo ' checked="checked"';
	}
	echo '></input> Make Background Transparent (ignores set background color)';
	echo '</p>';
	
	//Admin Notes Field
	echo '<p>';
	echo '<div class="tooltip"><strong>Admin Notes:</strong> ';
	echo '<span class="tooltiptext">Anything entered here is for your reference only and will not appear outside of the WordPress backend.</span>';
	echo '</div>';
	echo '<br /> <textarea style="width: 75%; height: 150px;" name="qr_redirect[notes]">'.$notes.'</textarea></p>';
	
	echo '</div>';
}

/**
 * Outputs the HTML for the custom meta box containing the QR image and redirect count for the qrcode post type on the edit page
 */
//print the qr code image and meta info
function qr_image_custom_box() {
    global $post;
    $img = get_post_meta($post->ID, 'qr_image_url', true);
    
    echo '<div id="meta_inner" style="text-align: center;">';
	
	if($post->post_status == "publish") {
		echo '<p><strong>Shortcode:</strong><br />';
		echo 'Copy and paste this short code into your posts or pages to display this QR Code:';
		echo '<br /><br /><code>[qr-code id="'.$post->ID.'"]</code></p>';
		echo '<hr />';
		echo '<em>Click to download image at actual size</em><br />';
		echo '<a href="'.$img.'" target="_blank" download><img src="'.$img.'" style="max-width: 250px; max-height: 250px;" /></a>';
		echo '<hr />';
		echo '<strong>'.get_permalink($post->ID).'</strong>';
		echo '<br />will redirect to:<br />';
		echo '<strong>'.get_post_meta($post->ID,'qr_redirect_url',true).'</strong>';
		
		//retrieve and display the redirect count
		$count = get_post_meta($post->ID,'qr_redirect_count',true);
		if(!$count) {
			$count = 0;
		}
		
		echo '<div class="qr-clearcount">This QR has redirected <strong><span id="qr_count_value">'.$count.'</span></strong> time(s).';
		
		//create a button to clear count
		echo '<div class="button" id="clear_count_button">Clear Count</div></div>';
	}
	else {
		echo '<strong>Publish to generate QR Code</strong>';
	}
	echo '</div>';
}

/**
 * Generate the javascript to make an AJAX call to the qr_clear_count() function on the qrcode edit page
 */
function qr_clear_count_javascript() { 
	global $post_type;
	
	if( 'qrcode' == $post_type ) {
		global $post;
		
		?>
		<script type="text/javascript" >
		jQuery("#clear_count_button").click(function($) {
			var data = {
				'action': 'qr_clear_count',
				'post_id': <?php echo $post->ID; ?>
			};
	
			if (confirm("Are you sure you want to clear the redirect count?") == true) {
				jQuery.post(ajaxurl, data, function(response) {
					jQuery("#qr_count_value").text("0");
				});
			}
		});
		</script> <?php
	}
}
add_action( 'admin_footer', 'qr_clear_count_javascript' ); //insert the javascript
add_action( 'wp_ajax_qr_clear_count', 'qr_clear_count' ); //connect the AJAX call to the PHP function

/**
 * When the post is saved, save our custom post_meta fields and generate the QR Code image  
 */
function qr_dynamic_save_postdata( $post_id ) {
	//if our form has not been submitted, we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { 
		return;
	}

	// verify this came from our site and with proper authorization
	if (isset($_POST['dynamicMeta_noncename'])){
		if ( !wp_verify_nonce( $_POST['dynamicMeta_noncename'], plugin_basename( __FILE__ ) ) )
			return;
	}
	else {
		return;
	}
	
	//format and save the data
	$url = sanitize_url($_POST['qr_redirect']['url']);
	
	if(!stristr($url, "://")) {
		$url = "http://".$url;
	}
	
	$permalink = get_permalink($post_id);
	
	$errorCorrectionLevel = $_POST['qr_redirect']['ecl'];
	$matrixPointSize = $_POST['qr_redirect']['size'];
	$responseCode = $_POST['qr_redirect']['response'];
	$adminNotes = sanitize_text_field($_POST['qr_redirect']['notes']);
	$fgColor = $_POST['qr_redirect']['qr_fg_color'];
	$bgColor = $_POST['qr_redirect']['qr_bg_color'];
	$bgTrans = isset($_POST['qr_redirect']['qr_transparent']) ? $_POST['qr_redirect']['qr_transparent'] : 'off';
	
	//the color picker will only save as hex, but we need RGB for the QR function
	$fgColor_rgb = sscanf($fgColor, "#%02x%02x%02x");
	$bgColor_rgb = sscanf($bgColor, "#%02x%02x%02x");
	
	//generate the image file according to the specifications set by the user
	$upload_dir = wp_upload_dir();
	$PNG_TEMP_DIR = $upload_dir['basedir'].'/qrcodes/'; //where we're storing the QR code images
	
	if (!file_exists($PNG_TEMP_DIR)) { //for new installs, we need to make the storage directory
		mkdir($PNG_TEMP_DIR);
	}
	
	//set the filename to something unique
	$filename = $PNG_TEMP_DIR.'qr'.md5($permalink.'|'.$errorCorrectionLevel.'|'.$matrixPointSize.'|'.rand()).'.png';
	
	//if we're updating a QR code post, we dont want to keep the old image file
	$oldfile = str_replace($upload_dir['baseurl'].'/qrcodes/', $PNG_TEMP_DIR, get_post_meta($post_id,'qr_image_url',true));
	if ($oldfile != '' && file_exists($oldfile)) {
		unlink($oldfile);
	}
	
	// Create QR code image
	$writer = new PngWriter();
	$qrCode = QrCode::create($permalink)
						->setEncoding(new Encoding('UTF-8'))
						->setSize($matrixPointSize)
						->setMargin(10)
						->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
						->setForegroundColor(new Color($fgColor_rgb[0], $fgColor_rgb[1], $fgColor_rgb[2]))
						->setBackgroundColor(new Color($bgColor_rgb[0], $bgColor_rgb[1], $bgColor_rgb[2]));
	
	if($bgTrans == 'on') { //if the transparent box has been checked, override the background color settings and set the alpha level to max
		$qrCode->setBackgroundColor(new Color($bgColor_rgb[0], $bgColor_rgb[1], $bgColor_rgb[2], 127));
	}

	//set the error correction level
	if($errorCorrectionLevel == 'L') {
		$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelLow());
	}
	elseif($errorCorrectionLevel == 'M') {
		$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelMedium());
	}
	elseif($errorCorrectionLevel == 'Q') {
		$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelQuartile());
	}
	elseif($errorCorrectionLevel == 'H') {
		$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());
	}
	else { //set low as the default, just as a backup.  We should never trigger this else statement.
		$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelLow());
	}
	
	$result = $writer->write($qrCode);
	
	// Save it to a file
	$result->saveToFile($filename);
	
	//update the post meta
	$img = $upload_dir['baseurl'].'/qrcodes/'.basename($filename);
	
	update_post_meta($post_id,'qr_image_url',$img);
	update_post_meta($post_id,'qr_redirect_url',$url);
	update_post_meta($post_id,'qr_redirect_ecl',$errorCorrectionLevel);
	update_post_meta($post_id,'qr_redirect_size',$matrixPointSize);
	update_post_meta($post_id,'qr_redirect_response',$responseCode);
	update_post_meta($post_id,'qr_redirect_notes',$adminNotes);
	update_post_meta($post_id,'qr_fg_color',$fgColor);
	update_post_meta($post_id,'qr_bg_color',$bgColor);
	update_post_meta($post_id,'qr_transparent',$bgTrans);
	
}
add_action( 'save_post', 'qr_dynamic_save_postdata' );

//
/**
 * Shortcode function to show a QR code in a post
 *  
 * @param array $atts - shortcode attributes (id => qr code post id)
 * @return boolean|string $output - the HTML code for display or false if no post id is provided
 */
function qr_show_code($atts) {
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
	
	//if no id is specified, we have nothing to display
	if(!$id) {
		return false;
	}
	$output = '';
	$img = get_post_meta($id, 'qr_image_url', true);
	$output .= '<img src="'.$img.'" class="qr-code" />';	
	return $output;
}
add_shortcode( 'qr-code', 'qr_show_code');

/**
 * Add our custom post_meta fields to the column list and quick edit in the QR Code section of the WP Dashboard 
 * 
 * @param array $column_array
 */
function qr_post_and_quick_edit_columns( $column_array ) {
 
	$column_array['qr_redirect_response'] = 'HTTP Response Code';
	$column_array['qr_redirect_size'] = 'Size';
	$column_array['qr_redirect_ecl'] = 'Error Correction Level';
	$column_array['qr_redirect_count'] = 'Redirect Count';
	$column_array['qr_redirect_shortcode'] = 'Short Code';
	$column_array['qr_redirect_qr_code'] = 'Download QR Code';
 
	return $column_array;
}
add_filter('manage_qrcode_posts_columns', 'qr_post_and_quick_edit_columns');
 
/**
 * Populate the new columns created by qr_post_and_quick_edit_columns() with data
 * 
 * @param string $column_name
 * @param int $id
 */
function qr_populate_both_columns( $column_name, $id ) {
 
	// if you have to populate more that one columns, use switch()
	switch( $column_name ) :
		case 'qr_redirect_response': {
			if(get_post_meta( $id, 'qr_redirect_response', true )) {
				//put the post_ID in the id for a container div so we can grab it with javascript for bulk editing
				echo '<div id="qr_redirect_response_'.$id.'">'.get_post_meta( $id, 'qr_redirect_response', true ).'</div>';
			}
			else {
				echo 'Not set';
			}
			break;
		}
		case 'qr_redirect_size': {
			if(get_post_meta( $id, 'qr_redirect_size', true )) {
				$qr_size = get_post_meta( $id, 'qr_redirect_size', true );
				
				//because the sizing method changed when we switched libraries, we'll need to add some extra code for backwards compatibility
				if($qr_size > 30) {
					echo $qr_size.'x'.$qr_size.' pixels';
				}
				else {
					echo ($qr_size*29).'x'.($qr_size*29).' pixels';
				}
			}
			else {
				echo 'Not set';
			}
			break;
		}
		case 'qr_redirect_ecl': {
			if(get_post_meta( $id, 'qr_redirect_ecl', true )) {
				echo get_post_meta( $id, 'qr_redirect_ecl', true );
			}
			else {
				echo 'Not set';
			}
			break;
		}
		case 'qr_redirect_count': {
			if(get_post_meta( $id, 'qr_redirect_count', true )) {
				echo get_post_meta( $id, 'qr_redirect_count', true );
			}
			else {
				echo '0';
			}
			break;
		}
		case 'qr_redirect_shortcode': {
			echo '<code>[qr-code id="'.$id.'"]</code>';
			break;
		}
		case 'qr_redirect_qr_code': {
			$img = get_post_meta($id, 'qr_image_url', true);
			echo '<div style="width: 100%; text-align: center;"><a href="'.$img.'" target="_blank" download><img src="'.$img.'" style="width: 50px; height: auto;" /><a/></div>';
			break;
		}
	endswitch;
}
add_action('manage_posts_custom_column', 'qr_populate_both_columns', 10, 2);

/**
 * Add a custom field to quick edit and bulk edit
 *  
 * @param string $column_name
 * @param string $post_type
 */
function qr_add_quick_edit($column_name, $post_type) {
    if ($column_name != 'qr_redirect_response') return;
    ?>
    <fieldset class="inline-edit-col-left">
    <div class="inline-edit-col">
        
        <label class="alignleft">
			<span class="title" style="line-height: 1;">Response Code</span>
		</label>
 		<select name="qr_redirect_response" id="qr_redirect_response">
			<option value="301">301 - Moved Permanently</option>
			<option value="302">302 - Found</option>
			<option value="307">307 - Temporary Redirect</option>
			<option value="308">308 - Permanent Redirect</option>
		</select>
    </div>
    </fieldset>
    <?php
    wp_nonce_field( 'qr_redirector_q_edit_nonce', 'qr_redirector_nonce' );
}
add_action('quick_edit_custom_box',  'qr_add_quick_edit', 10, 2);
add_action('bulk_edit_custom_box',  'qr_add_quick_edit', 10, 2);
 
/**
 * Quick Edit save
 *  
 * @param int $post_id
 */
function qr_quick_edit_save( $post_id ){
	// check user capabilities
	if ( !current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if (!isset($_REQUEST['qr_redirector_nonce']) || !wp_verify_nonce( $_REQUEST['qr_redirector_nonce'], 'qr_redirector_q_edit_nonce' )) {
		return;
	}
 
	// update the response code
	if ( isset( $_POST['qr_redirect_response'] ) ) {
 		update_post_meta( $post_id, 'qr_redirect_response', $_POST['qr_redirect_response'] );
	} 
}
add_action( 'save_post', 'qr_quick_edit_save' );

/**
 * Bulk Edit save
 */
function qr_save_bulk_edit_hook() {
	// check user capabilities
	if ( !current_user_can( 'edit_posts', $post_id ) ) {
		exit;
	}
	
	if ( !wp_verify_nonce( $_REQUEST['nonce'], 'qr_redirector_q_edit_nonce' ) ) {
		exit;
	}
	
	//if post IDs are empty, it is nothing to do here
	if( empty( $_POST[ 'post_ids' ] ) ) {
		exit;
	}
 
	//for each post ID
	foreach( $_POST[ 'post_ids' ] as $id ) {
		// if qr_redirect_response is empty, don't change it
		if( !empty( $_POST[ 'qr_redirect_response' ] ) ) {
			update_post_meta( $id, 'qr_redirect_response', $_POST['qr_redirect_response'] );
		}
	}
 
	exit;
}
add_action( 'wp_ajax_qr_save_bulk', 'qr_save_bulk_edit_hook' ); // format of add_action( 'wp_ajax_{ACTION}', 'FUNCTION NAME' );

/**
 * Adds the new custom post types to the WP Dashboard's "At a Glance" widget.
 * Filters on dashboard_glance_items
 *
 * @param array $items The default list of "At a Glance" items.
 * @return array The altered list of "At a Glance" items.
 */
function qr_custom_glance_items( $items = array() ) {
	$post_types = array( 'qrcode' ); //the post types we want to add
	foreach( $post_types as $type ) {
		if( ! post_type_exists( $type ) ) continue;
		
		$num_posts = wp_count_posts( $type );
		
		if( $num_posts ) {
			$published = intval( $num_posts->publish );
			$post_type = get_post_type_object( $type );
			
			$text = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'tag' );
			$text = sprintf( $text, number_format_i18n( $published ) );
			
			if ( current_user_can( $post_type->cap->edit_posts ) ) {
				$items[] = sprintf( '<a class="%1$s-count" href="edit.php?post_type=%1$s">%2$s</a>', $type, $text ) . "\n";
			} else {
				$items[] = sprintf( '<span class="%1$s-count">%2$s</span>', $type, $text ) . "\n";
			}
		}
	}
	return $items;
}
add_filter( 'dashboard_glance_items', 'qr_custom_glance_items', 10, 1 );

/**
 * Adds style information for new items added to "At a Glance" section and the staff dashboard index.
 * Fires on admin_head
 *
 * @see qr_custom_glance_items()
 *
 * For icon codes, see the following URL:
 * @link https://developer.wordpress.org/resource/dashicons/
 */
function qr_admin_custom_style() {
	$icon = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'assets/qr-menu-icon-dark.png';
	
	echo '<style>
		.post-type-qrcode .column-tag_staff_id,
		.post-type-qrcode .column-tag_post_thumbs {
			width: 4em;
		}
						
		.post-type-qrcode tr.status-draft td {
			opacity: 0.3; /* Real browsers */
	    	filter: alpha(opacity = 30); /* MSIE */
		}
						
		#dashboard_right_now a.qrcode-count:before,
		#dashboard_right_now span.qrcode-count:before {
	  		content: url('.$icon.');
			
		}
	  </style>';
}
add_action('admin_head', 'qr_admin_custom_style');
?>