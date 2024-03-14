<?php
/**
 * Plugin Name: Category and Taxonomy Image
 * Plugin URI: https://aftabhusain.wordpress.com/
 * Description: Category and Taxonomy Image Plugin allow you to add image with category/taxonomy.
 * Version: 1.0.0
 * Author: Aftab Husain
 * Author URI: https://aftabhusain.wordpress.com/
 * Author Email: amu02.aftab@gmail.com
 * License: GPLv2
 */


$options = get_option('aft_options');
$aft_taxonomies = $options['checked_taxonomies'];

if(!empty($aft_taxonomies)){
	
	foreach ($aft_taxonomies as $aft_taxonomy) {
	add_action($aft_taxonomy.'_add_form_fields','addcategoryimage');
	add_action($aft_taxonomy.'_edit_form_fields','editcategoryimage');
    }
}

//Function to add category/taxonomy image
function addcategoryimage($taxonomy){ ?>
    <div class="form-field">
	<label for="tag-image">Image</label>
	<input type="text" name="tag-image" id="tag-image" value="" />	
	<p class="description">Click on the text box to add taxonomy/category image.</p>
</div>

<?php aft_script_css(); }


//Function to edit category/taxonomy image
function editcategoryimage($taxonomy){ ?>
<tr class="form-field">
	<th scope="row" valign="top"><label for="tag-image">Image</label></th>
	<td>
	<?php 
	if(get_option('_category_image'.$taxonomy->term_id) != ''){ ?>
		<img src="<?php echo get_option('_category_image'.$taxonomy->term_id); ?>" width="100"  height="100"/>
	<?php	
	}
	?><br />
	<input type="text" name="tag-image" id="tag-image" value="<?php echo get_option('_category_image'.$taxonomy->term_id); ?>" /><p class="description">Click on the text box to add taxonomy/category image.</p>
	</td>
</tr>              
<?php aft_script_css(); }

function aft_script_css(){ ?>
                
<script type="text/javascript" src="<?php echo plugins_url(); ?>/wp-custom-taxonomy-image/includes/thickbox.js"></script>
<link rel='stylesheet' id='thickbox-css'  href='<?php echo includes_url(); ?>js/thickbox/thickbox.css' type='text/css' media='all' />
<script type="text/javascript">    
    jQuery(document).ready(function() {
	var fileInput = ''; 
	jQuery('#tag-image').live('click',
	function() {
		fileInput = jQuery('#tag-image');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	}); 
        window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html) {
		if (fileInput) {
			fileurl = jQuery('img', html).attr('src');
			if (!fileurl) {
				fileurl = jQuery(html).attr('src');
			}
			jQuery(fileInput).val(fileurl);

			tb_remove();
		} else {
			window.original_send_to_editor(html);
		}
	};
    });
   
</script>
<?php }
//edit_$taxonomy
add_action('edit_term','categoryimagesave');
add_action('create_term','categoryimagesave');
function categoryimagesave($term_id){
    if(isset($_POST['tag-image'])){
        if(isset($_POST['tag-image']))
            update_option('_category_image'.$term_id,$_POST['tag-image'] );
    }
}


// New menu submenu for plugin options in Settings menu
add_action('admin_menu', 'aft_options_menu');
function aft_options_menu() {
	add_options_page('Taxonomy Image settings', 'Taxonomy Image', 'manage_options', 'aft-options', 'aft_options');
	add_action('admin_init', 'aft_register_settings');
}

// Register plugin settings
function aft_register_settings() {
	register_setting('aft_options', 'aft_options', 'aft_options_validate');
	add_settings_section('aft_settings', 'Taxonomy Image settings' , 'aft_section_text', 'aft-options');
	add_settings_field('aft_checked_taxonomies', 'Taxonomy Image settings' , 'aft_checked_taxonomies', 'aft-options', 'aft_settings');
}

// Settings section description
function aft_section_text() {
	echo '<p>Please select the taxonomies you want to include it in WP Custom Taxonomy Image</p>';
}

// Included taxonomies checkboxs
function aft_checked_taxonomies() {
	$options = get_option('aft_options');
	
	$disabled_taxonomies = array('nav_menu', 'link_category', 'post_format');
	foreach (get_taxonomies() as $tax) : if (in_array($tax, $disabled_taxonomies)) continue; ?>
		<input type="checkbox" name="aft_options[checked_taxonomies][<?php echo $tax ?>]" value="<?php echo $tax ?>" <?php checked(isset($options['checked_taxonomies'][$tax])); ?> /> <?php echo $tax ;?><br />
	<?php endforeach;
}

// Validating options
function aft_options_validate($input) {
	return $input;
}

// Plugin option page
function aft_options() {
	if (!current_user_can('manage_options'))
		wp_die('You do not have sufficient permissions to access this page.');
		$options = get_option('aft_options');
	?>
	<div class="wrap">
		
		<h2><?php echo 'Taxonomy Image'; ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields('aft_options'); ?>
			<?php do_settings_sections('aft-options'); ?>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}



//get taxonomy/category image
function get_wp_term_image($term_id){
	
	return get_option('_category_image'.$term_id);	
}

?>