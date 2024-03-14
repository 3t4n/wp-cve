<?php
/*
Plugin Name: Replace Text
Plugin URI: 
Description: This plugin will help you to replace a text in whole Wordpress website with the required one.
Version: 1.0
Author: Yarddiant 
Author URI:https://www.yarddiant.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

/*Or use this function to replace multiple words or phrases at once*/
function yardsan_add_menu() {

	add_menu_page( 'Registration', 'Replace Text', 'manage_options', 'yard-replace', 'yardsan_page' );
}
add_action ( "admin_menu", "yardsan_add_menu" );

/**
 * Setting Page Options
 * - add setting page
 * - save setting page
 *
 * @since 1.0
 */
function yardsan_page() {
	?>
	<style type="text/css">
		.submit{
			text-align: center !important;
			margin-top: 0px !important;
		}
		.submit input{

			height: 40px !important;
			width: 20% !important;
			font-size: 20px !important;
		}
	</style>

	<?php
  if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	 ?>
	<div class="wrap">
		<h1 style="color: #000000; font-weight: 900;font-size: 25px;text-align: center;">
			<a style="color: #702323; font-weight: 900;text-decoration: none; text-transform: uppercase; font-family: cursive;
			font-size: 30px;
			letter-spacing: 2px;" href="#" target="_blank">Replace Text</a>
		</h1>

		<form method="post" action="options.php">
			<?php
			settings_fields ( "yardsan_config" );
			do_settings_sections ( "crunchify-hello-world" );
			$attributes = array( 'data-style' => 'custom' );
			submit_button ( 'REPLACE', 'primary mystyle', 'submit', true, $attributes );
			?>
				<span>*Note : Last updated values are shown in the textboxes.</span>
		</form>
	</div>

	<?php
}

/**
 * Init setting section, Init setting field and register settings page
 *
 * @since 1.0
 */
function yardsan_settings() {
	add_settings_section ( "yardsan_config", "", null, "crunchify-hello-world" );
	add_settings_field ( "first-text", "", "yardsan_options", "crunchify-hello-world", "yardsan_config", "", "second-text", "", "" );
	register_setting ( "yardsan_config", "first-text" );

	register_setting ( "yardsan_config", "second-text" );

}
add_action ( "admin_init", "yardsan_settings" );

/**
 * Add simple textfield value to setting page
 *
 * @since 1.0
 */
function yardsan_options() {
	yardsan_update_posts();
	?>
	<div class="postbox" style="width: 65%; padding: 30px; background: linear-gradient(90deg, #fce3e6d1, #220101); font-weight: 700;    border-radius: 15px;">
		<label style="margin: 28px 0; width: 30%; float: left;">TEXT TO REPLACE </label>
		<input style="width: 55%; height: 42px; border-radius: 4px !important; margin: 14px 0;" required type="text" placeholder="TEXT TO REPLACE" name="first-text"
		value="<?php
		echo stripslashes_deep ( esc_attr ( get_option ( 'first-text' ) ) );
		?>"/> <br />

		<label style="margin: 28px 0; width: 30%; float: left;">TEXT RELACE WITH </label><input style="width: 55%; height: 42px; border-radius: 4px !important; margin: 14px 0;" type="text" required placeholder="TEXT TO BE REPLACE" name="second-text"
		value="<?php
		echo stripslashes_deep ( esc_attr ( get_option ( 'second-text' ) ) );
		?>" /> <br />
/**
 This plugin is powered by yarddiant the web & wordpress development company
 https://www.yarddiant.com  https://www.yarddiant.com/wordpress-development.html
 */
 


	</div>

	<?php
}

function yardsan_update_posts() {


	$ab = sanitize_text_field(stripslashes_deep ( esc_attr ( get_option ( 'first-text' )) ) );
	$dc = sanitize_text_field(stripslashes_deep ( esc_attr ( get_option ( 'second-text' ) ) ));

	$search  = array($ab);
	$replace = array($dc);

    $args = array(
        'post_type' => 'post',
        'numberposts' => -1
    );
    $myposts = get_posts($args);
    foreach ($myposts as $mypost){

       $my_post = array(
      'ID'           => $mypost->ID,
      'post_title'   => $mypost->post_title,
      'post_content' =>  preg_replace('/\b'.$ab.'\b/', $dc ,$mypost->post_content),
  );
 
// Update the post into the database
  wp_update_post( $my_post );
  }

    $pages = get_pages(); 

     foreach ($pages as $page){

       $page = array(
      'ID'           => $page->ID,
      'post_title'   => $page->post_title,
      'post_content' => preg_replace('/\b'.$ab.'\b/', $dc ,$page->post_content),
  );

         wp_update_post( $page );
       
    }


}
