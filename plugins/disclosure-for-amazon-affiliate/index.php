<?php
/*
Plugin Name: Azon Affiliate Disclosure
Description: Custom plugin
	Version: 1.0
	 Author: Website Income
 Author URI: https://www.websiteincome.com
*/

if( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'AAD_BEFORE_TEXT' ))
	define( 'AAD_BEFORE_TEXT', 'As an Amazon Associate I earn from qualifying purchases.' );

if( !defined( 'AAD_AFTER_TEXT' ))
	define( 'AAD_AFTER_TEXT', 'Amazon and the Amazon logo are trademarks of Amazon.com, Inc, or its affiliates.' );

function aad_enqueue_scripts()
{
	wp_enqueue_style( 'aad-style', plugins_url( '/css/style.css', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'aad_enqueue_scripts');

add_action( 'admin_menu', 'add_admin_menu' );
add_action( 'admin_init', 'aad_settings_init' );

function add_admin_menu(  ) {
	add_options_page( 'Affiliate Disclosure Options', 'Affiliate Disclosure', 'manage_options', 'affiliate-disclosure-options', 'stp_api_options_page' );
}

function aad_settings_init()
{
	register_setting( 'aad-settings', 'aad-settings' );

	add_settings_section( 'aad_options_section', __( 'The fastest way to help your site be compliant with Amazon Associates / FTC affiliate and Amazon trademark disclosures', 'aad' ), 'print_options_section', 'aad-settings' );
	add_settings_field( 'aad_before_text_text', __( 'Affiliate Disclosure Before Post', 'aad' ), 'print_aad_before_text', 'aad-settings', 'aad_options_section', array() );
	add_settings_field( 'aad_before_on', 'Show on:', 'print_aad_before_on', 'aad-settings', 'aad_options_section' );

	add_settings_field( 'aad_after_text_text', __( 'Trademark Disclosure After Post', 'aad' ), 'print_aad_after_text', 'aad-settings', 'aad_options_section', array() );
	add_settings_field( 'aad_after_on',	'Show on:', 'print_aad_after_on', 'aad-settings', 'aad_options_section' );

	add_settings_section( 'aad_resources_section', __( 'Helpful Resources', 'aad' ), 'print_resources_section', 'aad-settings' );
}


function print_aad_before_on()
{
	$options = get_option( 'aad-settings' );
	$value = isset( $options['aad_before_on'] ) ?  $options['aad_before_on'] : array();

	$html = '<label><input type="checkbox" name="aad-settings[aad_before_on][]" value="post"' . ( in_array( 'post', $value ) ? 'checked' : false ) . '/>Posts</label> &nbsp; ';
	$html .= '<label><input type="checkbox" name="aad-settings[aad_before_on][]" value="page"' . ( in_array( 'page', $value ) ? 'checked' : false ) . '/>Pages</label>';
	$html .= '<p class="description">The disclosure can be locally controlled (shown / not shown) at each page or post or globally below.</p>';
	echo $html;

	}

function print_aad_after_on()
{
	$options = get_option( 'aad-settings' );
	$value = isset( $options['aad_after_on'] ) ?  $options['aad_after_on'] : array();

	$html = '<label><input type="checkbox" name="aad-settings[aad_after_on][]" value="post"' . ( in_array( 'post', $value ) ? 'checked' : false ) . '/>Posts</label> &nbsp; ';
	$html .= '<label><input type="checkbox" name="aad-settings[aad_after_on][]" value="page"' . ( in_array( 'page', $value ) ? 'checked' : false ) . '/>Pages</label>';
	$html .= '<p class="description">The disclosure can be locally controlled (shown / not shown) at each page or post or globally below.</p>';

	echo $html;

	}

function print_aad_before_text($args)
{
	extract( $args );

	$options = get_option( 'aad-settings' );

	$value = isset( $options['aad_before_text'] ) || !empty( $options['aad_before_text'] ) ?  $options['aad_before_text'] : AAD_BEFORE_TEXT;


	$id = 'aad_before_text';
	$class = 'sffff';

	$settings = array(
		'textarea_name' => 'aad-settings[aad_before_text]',
		'editor_class' => $class,
		'media_buttons' => false,
		'tinymce' => true,
		'editor_height' => 85,
		'textarea_rows' => 5,
	);

	echo "<div style='width:75%;'>";
	wp_editor($value, $id, $settings );
	echo "</div>";
}



function print_aad_after_text($args)
{
	extract( $args );

	$options = get_option( 'aad-settings' );

	$value = isset( $options['aad_after_text'] ) || !empty( $options['aad_after_text'] ) ?  $options['aad_after_text'] : AAD_AFTER_TEXT;


	$id = 'aad_after_text';
	$class = 'sffff';

	$settings = array(
		'textarea_name' => 'aad-settings[aad_after_text]',
		'editor_class' => $class,
		'media_buttons' => false,
		'tinymce' => true,
		'editor_height' => 85,
		'textarea_rows' => 5,
	);

	echo "<div style='width:75%;'>";
	wp_editor($value, $id, $settings );
	echo "</div>";

}

function print_options_section()
{
	echo '<p class="description">' . __( 'Learn more about your requirements as an Amazon Associate <a href="https://authoritywebsiteincome.com/aad/disclosure">here</a>.', 'aad' ) . '</p>';
	echo '<p class="description">' . __( 'No text entered in this box will be crawled / indexed by Google. It is locally toggled off using Google on/off tags.', 'aad' ) . '</p>';
}

function print_resources_section()
{
	?>

<div class="acf-input">
	<ul>
		<li>Make Sure Your <a href="https://authoritywebsiteincome.com/aad/azon-audit" target="_blank">Links are Working</a></li>
		<li>How Much is Your Website Worth? – <a href="https://authoritywebsiteincome.com/aad/buy-sell-website" target="_blank">Buy and Sell Amazon Associate Websites</a></li>
		<li><a href="https://authoritywebsiteincome.com/click/content" target="_blank">Get Content</a> for Your Amazon Associate Sites</li>
		<li>Best Amazon Associate <a href="https://authoritywebsiteincome.com/aad/links" target="_blank">Link Building Service</a></li>
	</ul>
	<p><strong><em>Amazon and the Amazon logo are trademarks of Amazon.com, Inc, or its affiliates</em></strong></p>
</div><?php

}

function stp_api_options_page()
{
	?>

	<div class="wrap">
		<h1><?php echo __( 'Amazon Affiliate Disclosure', 'aad' ); ?></h1>
		<form action="options.php" method="post"><?php

			settings_fields( 'aad-settings' );
			do_settings_sections( 'aad-settings' );
			submit_button();
			?>

		</form>
	</div><?php

}

function aad_post_meta()
{
	add_meta_box( 'prfx_meta', __( 'Amazon Affiliate Disclosure', 'aad' ), 'print_aad_post_meta', 'post', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'aad_post_meta' );

function print_aad_post_meta( $post )
{
	wp_nonce_field( basename( __FILE__ ), 'aad_post_update_nonce' );
	$aad_post_meta = get_post_meta( $post->ID );

	$aad_custom_rules = isset( $aad_post_meta['aad_custom_rules'] ) ? $aad_post_meta['aad_custom_rules'] : 0;
	?>

<p>
	<label><strong><?php _e( 'Follow:', 'aad' )?></strong></label>
</p>
<p>
	<select name="aad_custom_rules">
		<option value="0" <?php selected( $aad_custom_rules[0], 0 ); ?>>Global rules</option>
		<option value="1" <?php selected( $aad_custom_rules[0], 1 ); ?>>Custom rules</option>
	</select>
</p>

<p>
	<label><strong><?php _e( 'Add to:', 'aad' )?></strong></label>
	<ul>
		<li><label><input type="checkbox" name="aad_before_on" value="1" <?php if ( isset ( $aad_post_meta['aad_before_on'] ) ) checked( $aad_post_meta['aad_before_on'][0], "1" ); ?> /><?php _e( 'Posts', 'aad' )?></label></li>
		<li><label><input type="checkbox" name="aad_after_on" value="1" <?php if ( isset ( $aad_post_meta['aad_after_on'] ) ) checked( $aad_post_meta['aad_after_on'][0], "1" ); ?> /><?php _e( 'Pages', 'aad' )?></label></li>
	</ul>
</p><?php

}

function aad_meta_save( $post_id )
{
	// Checks save status - overcome autosave, etc.
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'aad_post_update_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	if ( $is_autosave || $is_revision || !$is_valid_nonce )
	{
		return;
	}

	if( isset( $_POST[ 'aad_before_on' ] ))
		update_post_meta( $post_id, 'aad_before_on', 1 );
	else
		update_post_meta( $post_id, 'aad_before_on', 0 );

	if( isset( $_POST[ 'aad_after_on' ] ))
		update_post_meta( $post_id, 'aad_after_on', 1 );
	else
		update_post_meta( $post_id, 'aad_after_on', 0 );

	if( isset( $_POST[ 'aad_custom_rules' ] ) && $_POST[ 'aad_custom_rules' ] == 1 )
		update_post_meta( $post_id, 'aad_custom_rules', 1 );
	else
		update_post_meta( $post_id, 'aad_custom_rules', 0 );
}
add_action( 'save_post', 'aad_meta_save' );

function print_aad_content($content)
{
	$options = get_option( 'aad-settings' );

	$aad_before_text = isset( $options['aad_before_text'] ) ? $options['aad_before_text'] : AAD_BEFORE_TEXT;
	$aad_after_text  = isset( $options['aad_after_text'] )  ? $options['aad_after_text']  : AAD_AFTER_TEXT;

	$aad_before_on   = isset( $options['aad_before_on'] )   ? $options['aad_before_on']   : 0;
	$aad_after_on    = isset( $options['aad_after_on'] )    ? $options['aad_after_on']    : 0;

	if( is_single( ))
	{
		$post_type = 'post';
	}
	elseif( is_page() )
	{
		$post_type = 'page';
	}
	else
		return $content;

	$post_id = get_the_ID();

	$text_affiliate = '';
	$text_trademark = '';

	$show_affiliate = false;
	$show_trademark  = false;

	if( get_post_meta( $post_id, 'aad_custom_rules', true ) == 1 )
	{
		if( get_post_meta( $post_id, 'aad_before_on', true ) == 1 )
		{
			$show_affiliate = true;
		}
		if( get_post_meta( $post_id, 'aad_after_on', true ) == 1 )
		{
			$show_trademark = true;
		}
	}
	else
	{
		if( in_array( $post_type, $aad_before_on ))
			$show_affiliate = true;

		if( in_array( $post_type, $aad_after_on ))
			$show_trademark = true;
	}

	if( $show_affiliate )
	{
		$text_affiliate = $aad_before_text;

		if( !empty( $text_affiliate ))
		{
			$text_affiliate = '<div class="aff-disclosure aff-before-post"><p>' . $text_affiliate . '</p></div>';

			// Exclude from Google Index
			$text_affiliate = '<!--googleoff: index-->' . $text_affiliate . '<!--googleon: index-->';
		}

	}

	if( $show_trademark )
	{
		$text_trademark = $aad_after_text;

		if( !empty( $text_trademark ))
		{
			$text_trademark = '<div class="aff-disclosure aff-after-post"><p>' . $text_trademark . '</p></div>';

			// Exclude from Google Index
			$text_trademark = '<!--googleoff: index-->' . $text_trademark . '<!--googleon: index-->';
		}
	}

	$content = $text_affiliate . $content . $text_trademark;

	return $content;
}
add_filter('the_content', 'print_aad_content');


function print_aad_settings_link( $links )
{
	$links[] = '<a href="' . admin_url( 'options-general.php?page=affiliate-disclosure-options' ) . '">' . __('Settings') . '</a>';
	return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'print_aad_settings_link' );