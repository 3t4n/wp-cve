<?php
/*
Plugin Name: Pinterest Site Verification plugin using Meta Tag
Plugin URI: http://twitter.com/himanshumaker
Description: Simply insert your Pinterest meta tag verification code using this helpful plugin.
Version: 1.2
Author: Himanshu Parashar
Author URI: http://twitter.com/himanshumaker
License: GPLv3

*/

// FUNCTIONS

function Pinterest_head_tag_verification_get_defaults() {
	$defaults = '';
	return $defaults;
}

function Pinterest_head_tag_verification_set_plugin_meta( $links, $file ) {
/*	short desc: define additional plugin meta links (appearing under plugin on Plugins page)
	parameters:
		$links = (array) passed from wp
		$file = (array) passed from wp*/
	$plugin = plugin_basename( __FILE__ ); // '/nofollow/nofollow.php' by default
    if ( $file == $plugin ) { // if called for THIS plugin then:
		$newlinks = array( '<a href="options-general.php?page=Pinterest-meta-tag-verification">' . __( 'Settings' ) . '</a>'	); // array of links to add
		return array_merge( $links, $newlinks ); // merge new links into existing $links
	}
return $links; // return the $links (merged or otherwise)
}

function Pinterest_head_tag_verification_options_init() {
// short desc: add plugin's options to white list
	register_setting( 'Pinterest_head_tag_options_options', 'Pinterest_head_tag_verification_item', 'Pinterest_head_tag_verification_options_validate' );
}

function Pinterest_head_tag_verification_options_add_page() {
// add link to plugin's settings page under 'settings' on the admin menu
	add_options_page( __( 'Pinterest Site Verification Settings' ), __( 'Pinterest Site Verification'), 'manage_options', 'Pinterest-meta-tag-verification', 'Pinterest_head_tag_verification_options_do_page');
}

function Pinterest_head_tag_verification_options_validate( $input ) {
	return $input;
}

function Pinterest_head_tag_verification_options_do_page() {
// short desc: draw the html/css for the settings page

	?>

	<div class="wrap">
    <div class="icon32" id="icon-options-general"><br /></div>
		<h2><?php _e( 'Pinterest Site Verification Meta Tag Settings' ); ?></h2>
		<form name="form1" method="post" action="options.php" id="pinterestform">
			<?php settings_fields( 'Pinterest_head_tag_options_options' ); // nonce settings page ?>
			<?php $options = get_option( 'Pinterest_head_tag_verification_item', Pinterest_head_tag_verification_get_defaults() ); // populate $options array from database ?>
<?php if(is_array($options)){
	$options = $options['account'];
}?>
			<!-- Description -->
			<p style="font-size:0.95em"><?php
				_e( sprintf( 'You may post a comment on this plugin\'s %1$shomepage%2$s if you have any questions, bug reports, or feature suggestions.', '<a target="_blank" href="http://twitter.com/himanshumaker" rel="help">', '</a>' ) ); ?></p>

			<div>
			 1. Login to <a target="_blank" href="http://Pinterest.com">Pinterest </a> to add your site under Settings menu and click verify website. Copy your verification tag in below box.
			</div>

			<div style="margin-top:20px;">
			2. Enter Verification tag

			<table class="form-table" style="margin-left:30px;">

				<tr>
					<th scope="row"><label for="">Example:</label></th>
					<td>
						<p><?php echo esc_html('<meta name="p:domain_verify" content="XXXXXXXXXXXXXXXX"/>');  ?> </p> (Copy the whole tag from Pinterest.)
					</td>

				</tr>
            	<tr valign="top"><th scope="row"><label for="Pinterest_head_tag_verification_item"><?php _e( 'Pinterest Meta Tag Content' ); ?>: </label></th>
					<td>
						<textarea type="textarea" rows="4" cols="50" id="Pinterest_head_tag_verification_item" name="Pinterest_head_tag_verification_item"><?php if($options!=null){ echo '<meta name="p:domain_verify" content="' . $options .'"/>' ;} ?></textarea>
							<?php
							 ?>

					 </td>
				</tr>



			</table>
			<p class="submit">
				<input type="submit" id="btnpinterest" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
			</p>
			</div>

		</form>


	</div>

	<?php
}

function Pinterest_head_tag_verification_print_code() {


	$options = get_option( 'Pinterest_head_tag_verification_item', Pinterest_head_tag_verification_get_defaults() );
	if(is_array($options)){
		$options = $options['account'];
}

$code = '<!--
Plugin: Pinterest meta tag Site Verification Plugin
Tracking Code.

-->

<meta name="p:domain_verify" content="' . $options . '"/>';


	echo $code;
	return;

}

// HOOKS AND FILTERS
add_filter( 'plugin_row_meta', 'Pinterest_head_tag_verification_set_plugin_meta', 10, 2 ); // add plugin page meta links
add_action( 'admin_init', 'Pinterest_head_tag_verification_options_init' ); // whitelist options page
add_action( 'admin_menu', 'Pinterest_head_tag_verification_options_add_page' ); // add link to plugin's settings page in 'settings' menu on admin menu initilization

// insert html code on page head initilization
$options = get_option( 'Pinterest_head_tag_verification_item', Pinterest_head_tag_verification_get_defaults() );
if($options!=null)
	add_action( 'wp_head', 'Pinterest_head_tag_verification_print_code', 99999 );


	function test_ajax_load_scripts() {
	// load our jquery file that sends the $.post request
	wp_enqueue_script( "ajax-test", plugin_dir_url( __FILE__ ) . '/verification.js', array( 'jquery' ) );

	// make the ajaxurl var available to the above script
	wp_localize_script( 'ajax-test', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_print_scripts', 'test_ajax_load_scripts');

function text_ajax_process_request() {
	// first check if data is being sent and that it is the data we want
  	if ( isset( $_POST["post_var"] ) ) {
		// now set our response var equal to that of the POST var (this will need to be sanitized based on what you're doing with with it)
		$response = $_POST["post_var"];
		// send the response back to the front end

		$options = get_option( 'Pinterest_head_tag_verification_item', Pinterest_head_tag_verification_get_defaults() );



		$new_value = getMetaTags($response);
		if($new_value=='error'){
			echo 'Missing tag';
		}
		else{
			$option_name = "Pinterest_head_tag_verification_item";

			if ( get_option( $option_name ) !== false ) {

			    update_option( $option_name, $new_value );
					echo 'updated successfully';
			} else {


			    $deprecated = null;
			    $autoload = 'no';
			    add_option( $option_name, $new_value, $autoload );
					echo 'Saved successfully';
			}
		}


		die();
	}
}


function getMetaTags($str)
{
  $str= stripcslashes($str);
  if (strpos(' '.$str,'<meta')) {
             preg_match_all(
"/<meta[^>]+(http\-equiv|name)=\"([^\"]*)\"[^>]" . "+content=\"([^\"]*)\"[^>]*>/i",
$str, $split_content[],PREG_PATTERN_ORDER);;
        }
				else{
					return 'error';
				}

				 return $split_content[0][3][0];
}
add_action('wp_ajax_test_response', 'text_ajax_process_request');
?>
