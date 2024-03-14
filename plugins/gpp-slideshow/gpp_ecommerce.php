<?php

/*-----------------------------------------------------------------------------------*/
/* Add Price Groups page for new post type menu */
/*-----------------------------------------------------------------------------------*/

// Add options to admin_init
add_action('admin_init', 'gpp_gallery_price_groups_init' );
// Add menu link to admin_menu
add_action('admin_menu', 'gpp_gallery_add_price_groups_menu');

// Add the sub menu to the new post type
function gpp_gallery_add_price_groups_menu() {
	add_submenu_page('edit.php?post_type=gallery', 'Price Groups', 'Price Groups', 'manage_options', 'gallery-price-Groups', 'gpp_gallery_price_groups_page' );
}

// Init plugin options to white list our options
function gpp_gallery_price_groups_init(){
	register_setting( 'gpp_gallery_price_groups', 'gpp_gallery_slug', 'gpp_gallery_price_groups_validate' );
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function gpp_gallery_price_groups_validate($input) {

	// Safe text with no HTML tags
	$input['gallery'] =  wp_filter_nohtml_kses($input['gallery']);
	$input['galleries'] =  wp_filter_nohtml_kses($input['galleries']);

	return $input;
}

// Function to print the content of the sub menu page
function gpp_gallery_price_groups_page(){ ?>

  	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>

		<?php
			if ( isset ( $_GET['settings-updated'] ) && ( $_GET['settings-updated'] == true ) )
				echo "<div id=\"message\" class=\"updated\"><p>Permalinks must be updated each time you change slug names. <a class=\"button\" href=\"options-permalink.php\">Update Permalinks Now &raquo;</a></p></div>";
		?>

		<h2>Price Groups</h2>
		<form action="options.php" method="post">

			<?php settings_fields('gpp_gallery_price_groups'); ?>
			<?php $options = get_option('gpp_gallery_slug'); ?>

			<?php
				if (get_option('permalink_structure') <> '') { ?>
			<h3>Slug</h3>
			<p>These options will allow you to change the titles and permalink structure for all <?php gpp_gallery_slug(); ?>.</p>
			<?php } ?>
			<?php
				if (get_option('permalink_structure') != ''):
					echo "<label for=\"gpp_gallery_slug[gallery]\">Singular Name</label>",
								"<br />",
								"<input type=\"text\" name=\"gpp_gallery_slug[gallery]\" value=\"" . $options['gallery'] . "\" id=\"gpp_gallery_slug[gallery]\" />",
								"<p class=\"description\">Example: Gallery</p>",
								"<br />",
								"<label for=\"gpp_gallery_slug[galleries]\">Plural Name</label>",
								"<br />",
								"<input type=\"text\" name=\"gpp_gallery_slug[galleries]\" value=\"" . $options['galleries'] . "\" id=\"gpp_gallery_slug[galleries]\" />",
								"<p class=\"description\">Example: Galleries</p>";
				else:
					echo "<div id=\"message\" class=\"error\"><p>Before you can proceed, please set your Permalinks to something other than the Default setting.  <a class=\"button\" href=\"options-permalink.php\">Change Permalinks Now &raquo;</a></p></div>";
				endif;
			?>
			<?php
				if (get_option('permalink_structure') <> '') { ?>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			<?php } ?>

		</form>

		<?php
			if (get_option('permalink_structure') <> '')
				echo gpp_gallery_credits();
		?>

		</div>
<?php
}

/*
This plugin requires WordPress >= 2.7 and tested with PHP Interpreter >= 5.2.9
*/
//avoid direct calls to this file, because now WP core and framework has been used
if ( !function_exists('add_action') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
} elseif ( version_compare(phpversion(), '5.0.0', '<') ) {
	$exit_msg = 'The plugin require PHP 5 or higher';
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit($exit_msg);
}
if ( !class_exists('add_attachment_fields') ) {

	class add_attachment_fields {
		function __construct() {
			if ( !is_admin() )
				return FALSE;
			add_filter( 'attachment_fields_to_edit', array(&$this, 'gpp_gallery_attachment_fields_edit'), 5, 2);
			add_filter( 'attachment_fields_to_save', array(&$this, 'gpp_gallery_attachment_fields_save'), 5, 2);
		}

		// Add a custom field to an attachment in WordPress
		function gpp_gallery_attachment_fields_edit($form_fields, $post) {

			// HTML
			$form_fields['custom_html']['input'] = 'html';
			$form_fields['custom_html']['html'] = '
			<h2>Photo Sales</h2>
			<p class="description">This is the plugin description.  You can put <a href="options.php">links</a> in here and other stuff.</p>';

			// Text input
			$form_fields['custom_textbox']['label'] = __( 'Custom Textbox' );
			$form_fields['custom_textbox']['input'] = 'text';
			$form_fields['custom_textbox']['value'] = get_post_meta($post->ID, '_custom_textbox', true);
			$form_fields['custom_textbox']['helps'] = __( 'Help text goes here.' );

			// Selectbox
			$form_fields['custom_selectbox']['label'] = __( 'Custom Selectbox' );
			$form_fields['custom_selectbox']['value'] = get_post_meta($post->ID, '_custom_selectbox', true);
			$form_fields['custom_selectbox']['helps'] = __( 'Help text goes here.' );
			$form_fields['custom_selectbox']['input'] = 'html';
			$form_fields['custom_selectbox']['html'] = "
			<select name='attachments[{$post->ID}][custom_selectbox]' id='attachments[{$post->ID}][custom_selectbox]'>
				<option value='1'>Option 1</option>
				<option value='2'>Option 2</option>
				<option value='3'>Option 3</option>
			</select>";

			// Checkbox
			$form_fields['custom_checkbox']['label'] = __('Custom Checkbox');
			$form_fields['custom_checkbox']['input'] = 'html';
			$form_fields['custom_checkbox']['html'] = 'the html output goes here, like a checkbox:
			<input type="checkbox" value="1"
				name="attachments[{$post->ID}][custom_checkbox]"
				id="attachments[{$post->ID}][custom_checkbox]" />';

			return $form_fields;
		}
		// save custom field to post_meta
		function gpp_gallery_attachment_fields_save($post, $attachment) {
			if ( isset($attachment['custom_textbox']) )
				update_post_meta($post['ID'], '_custom_textbox', $attachment['custom_textbox']);
			if ( isset($attachment['custom_selectbox']) )
				update_post_meta($post['ID'], '_custom_selectbox', $attachment['custom_selectbox']);
			return $post;
		}
	}
	function gpp_gallery_add_attachment_fields_start() {
		new add_attachment_fields();
	}
	add_action( 'plugins_loaded', 'gpp_gallery_add_attachment_fields_start' );
}