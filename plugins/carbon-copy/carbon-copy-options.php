<?php
// Options page
if( ! defined( 'ABSPATH' ) )
{
  exit(); // No direct access
}

// Admin actions
if( is_admin() )
{
	add_action( 'admin_menu', 'carbon_copy_menu' );
	add_action( 'admin_init', 'carbon_copy_register_settings' );
}

// Whitelisted options
function carbon_copy_register_settings()
{
	register_setting( 'carbon_copy_group', 'carbon_copy_copytitle' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copydate' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copystatus' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copyslug' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copyexcerpt' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copycontent' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copythumbnail' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copytemplate' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copyformat' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copyauthor' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copypassword' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copyattachments' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copychildren' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copycomments' );
	register_setting( 'carbon_copy_group', 'carbon_copy_copymenuorder' );

	register_setting( 'carbon_copy_group', 'carbon_copy_widgets_classic' );
	register_setting( 'carbon_copy_group', 'carbon_copy_widgets' );
	register_setting( 'carbon_copy_group', 'carbon_copy_menus' );

	register_setting( 'carbon_copy_group', 'carbon_copy_roles' );

	register_setting( 'carbon_copy_group', 'carbon_copy_types_enabled' );

	register_setting( 'carbon_copy_group', 'carbon_copy_taxonomies_blacklist' );

	register_setting( 'carbon_copy_group', 'carbon_copy_title_prefix' );
	register_setting( 'carbon_copy_group', 'carbon_copy_title_suffix' );
	register_setting( 'carbon_copy_group', 'carbon_copy_increase_menu_order_by' );
	register_setting( 'carbon_copy_group', 'carbon_copy_blacklist ');

	register_setting( 'carbon_copy_group', 'carbon_copy_show_row' );
	register_setting( 'carbon_copy_group', 'carbon_copy_show_adminbar' );
	register_setting( 'carbon_copy_group', 'carbon_copy_show_submitbox' );
	register_setting( 'carbon_copy_group', 'carbon_copy_show_bulkactions' );

	register_setting( 'carbon_copy_group', 'carbon_copy_show_original_column' );
	register_setting( 'carbon_copy_group', 'carbon_copy_show_original_in_post_states' );
	register_setting( 'carbon_copy_group', 'carbon_copy_show_original_meta_box' );

	register_setting( 'carbon_copy_group', 'carbon_copy_cleaner' );
}

// Admin backend menu
function carbon_copy_menu()
{
	add_options_page( __( "Carbon Copy Options", 'carbon-copy' ), __( "Carbon Copy", 'carbon-copy' ), 'manage_options', 'carboncopy', 'carbon_copy_options' );
}

function carbon_copy_options()
{
	if( current_user_can( 'promote_users' ) && ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) )
	{
		global $wp_roles;
		
		$roles = $wp_roles->get_names();
		$cc_roles = get_option( 'carbon_copy_roles' );

		if( $cc_roles == "" )
			$cc_roles = array();

		foreach( $roles as $name => $display_name )
		{
			$role = get_role( $name );
			// If role capability does not exsist, add it.
			if( ! $role->has_cap( 'copy_posts' ) && in_array( $name, $cc_roles ) )
				$role->add_cap( 'copy_posts' );
			// If role capability exists but was not selected, remove it.
			elseif( $role->has_cap( 'copy_posts' ) && !in_array($name, $cc_roles ) )
				$role->remove_cap( 'copy_posts' );
		}
	}
?>
<div class="wrap">

	<h1><?php esc_html_e( "Carbon Copy Options", 'default' ); ?></h1>

<script>
function toggle_private_taxonomies()
{
	jQuery( '.taxonomy_private' ).toggle( 300 );
}

jQuery( function()
{
	jQuery( '.taxonomy_private' ).hide( 300 );
});
</script>
	
<form method="post" action="options.php" style="clear: both" id="carbon_copy_settings_form">

	<?php settings_fields('carbon_copy_group'); ?>

<div style="padding:10px 0px;"></div>
<h2 id="elements-copy">Elements to be Copied</h2>
<hr />

<label>
<input type="checkbox" name="carbon_copy_copytitle" value="1" <?php if( get_option( 'carbon_copy_copytitle' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Title", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copydate" value="1" <?php if( get_option( 'carbon_copy_copydate' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Date", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copystatus" value="1" <?php if( get_option( 'carbon_copy_copystatus' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Status", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copyslug" value="1" <?php if( get_option( 'carbon_copy_copyslug' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Slug", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copyexcerpt" value="1" <?php if( get_option( 'carbon_copy_copyexcerpt' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Excerpt", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copycontent" value="1" <?php if( get_option( 'carbon_copy_copycontent' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Content", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copythumbnail" value="1" <?php if( get_option( 'carbon_copy_copythumbnail' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Featured Image", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copytemplate" value="1" <?php if( get_option( 'carbon_copy_copytemplate' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Template", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copyformat" value="1" <?php if( get_option( 'carbon_copy_copyformat' ) == 1 ) echo 'checked="checked"'; ?> /><?php echo esc_html_x( "Format", 'post format', 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copyauthor" value="1" <?php if( get_option( 'carbon_copy_copyauthor' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Author", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copypassword" value="1" <?php if( get_option( 'carbon_copy_copypassword' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Password", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copyattachments" value="1" <?php if( get_option( 'carbon_copy_copyattachments' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Attachments", 'default' ); ?>
 <span class="description">(<em><?php esc_html_e( "leave unchecked, unless you need special requirements", 'default' ); ?></em>)</span>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copychildren" value="1" <?php if( get_option( 'carbon_copy_copychildren' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Children", 'default' ); ?>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copycomments" value="1" <?php if( get_option( 'carbon_copy_copycomments' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Comments", 'default' ); ?>
 <span class="description">(<em><?php esc_html_e( "except pingbacks and trackbacks", 'default' ); ?></em>)</span>
</label><br />
<label>
<input type="checkbox" name="carbon_copy_copymenuorder" value="1" <?php if( get_option( 'carbon_copy_copymenuorder' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Menu order", 'default' ); ?>
</label><br />

	<div style="padding:10px 0px;"></div>
	<h3>Others</h3>

	<label>
		<input type="checkbox" name="carbon_copy_widgets_classic" value="1" <?php if( get_option( 'carbon_copy_widgets_classic' ) == 1 ) echo 'checked="checked"'; ?> /><strong><?php esc_html_e( "Classic Widgets:", 'default' ); ?></strong> <?php esc_html_e( "Disables Widget Block Editor Management", 'default' ); ?>
	</label><?php if( get_option( 'carbon_copy_widgets_classic' ) == 1 ) echo ' | <a href="widgets.php" title="Opens Widgets page in New Window" target="_blank">Widgets Page</a>'; ?><br />

	<label>
		<input type="checkbox" name="carbon_copy_widgets" value="1" <?php if( get_option( 'carbon_copy_widgets' ) == 1 ) echo 'checked="checked"'; ?> /><strong><?php esc_html_e( "Widgets Carbon Copier:", 'default' ); ?></strong> <?php esc_html_e( "(only works with Classic Widgets) Located under Appearance > Widgets", 'default' ); ?>
	</label><?php if( get_option( 'carbon_copy_widgets' ) == 1 ) echo ' | <a href="widgets.php" title="Opens Widgets page in New Window" target="_blank">Widgets Page</a>'; ?><br />
  
	<label>
		<input type="checkbox" name="carbon_copy_menus" value="1" <?php if( get_option( 'carbon_copy_menus' ) == 1 ) echo 'checked="checked"'; ?> /><strong><?php esc_html_e( "Menus Carbon Copy:", 'default' ); ?></strong> <?php esc_html_e( "Located under Appearance > Menus Carbon Copy", 'default' ); ?>
	</label><?php if( get_option( 'carbon_copy_menus' ) == 1 ) echo ' | <a href="themes.php?page=carbon-copy-menu" title="Opens Menus Carbon Copy page in New Window" target="_blank">Menus Carbon Copy</a>'; ?><br />

<div style="padding:10px 0px;"></div>
<h2 id="permissions">Copy Permissions</h2>
<hr />


<?php if( current_user_can( 'promote_users' ) )
{
	?>

	<h3>Roles Allowed to Copy</h3>
	<span class="description"><?php esc_html_e( "NOTICE: If enabled, users role can copy ALL posts, even from other users!", 'default' ); ?></span><br />
	<span class="description"><?php esc_html_e( "Password protected posts and contents will be visible users and visitors.", 'default' ); ?></span><br />
	<br />

	<?php
	global $wp_roles;
	$roles = $wp_roles->get_names();
	$post_types = get_post_types( array( 'show_ui' => true ), 'objects' );
	$edit_capabilities = array( 'edit_posts' => true );
	foreach( $post_types as $post_type )
	{
		$edit_capabilities[$post_type->cap->edit_posts] = true;
	}
	foreach( $roles as $name => $display_name ):
		$role = get_role( $name );
		if( count( array_intersect_key( $role->capabilities, $edit_capabilities ) ) > 0 ): ?>

<label>
	<input type="checkbox" name="carbon_copy_roles[]" value="<?php echo $name; ?>" <?php if( $role->has_cap( 'copy_posts' ) ) echo 'checked="checked"'; ?> /><?php echo translate_user_role( $display_name ); ?>
</label><br />

	<?php
		endif;
	endforeach;
}
?>

	<div style="padding:10px 0px;"></div>
	<h3>Permitted Post Types</h3>
	<span class="description"><?php esc_html_e( "Select post types to be enabled. Custom Post Types might not display here as they are dependent upon your Theme or Plugin.", 'default' ); ?></span><br />
	<br />

	<?php
	$post_types = get_post_types( array( 'show_ui' => true ), 'objects' );
	foreach( $post_types as $post_type_object ) :
		if( $post_type_object->name == 'attachment' )
			continue;
	?>

<label>
	<input type="checkbox" name="carbon_copy_types_enabled[]" value="<?php echo $post_type_object->name; ?>" <?php if( carbon_copy_is_post_type_enabled( $post_type_object->name ) ) echo 'checked="checked"'; ?> /><?php echo $post_type_object->labels->name; ?>
</label><br />

	<?php
	endforeach;
	?>

	<div style="padding:10px 0px;"></div>
	<h3>Taxonomies to be Excluded</h3>
	<span class="description"><?php esc_html_e( "Select the taxonomies to be exclude.", 'carbon-copy' ); ?></span><br />
	<a class="toggle_link" href="#" onclick="toggle_private_taxonomies();return false;"><?php esc_html_e( 'Show/hide private taxonomies', 'default' ); ?></a><br />
	<br />

	<?php
	$taxonomies = get_taxonomies( array(), 'objects' );
	usort( $taxonomies, 'carbon_copy_tax_obj_cmp' );
	$taxonomies_blacklist = get_option( 'carbon_copy_taxonomies_blacklist' );
	if( ! is_array( $taxonomies_blacklist ) )
	{
		$taxonomies_blacklist = array();
	}
	foreach( $taxonomies as $taxonomy ) : 
		if( $taxonomy->name == 'post_format' )
		{
			continue;
		}
		?>
<label class="taxonomy_<?php echo ( $taxonomy->public ) ? 'public' : 'private'; ?>">
	<input type="checkbox" name="carbon_copy_taxonomies_blacklist[]" value="<?php echo $taxonomy->name ?>" <?php if( in_array( $taxonomy->name, $taxonomies_blacklist ) ) echo 'checked="checked"' ?> /><?php echo $taxonomy->labels->name.' ['.$taxonomy->name.']'; ?>
</label><br />
	<?php
	endforeach;
	?>

<div style="padding:10px 0px;"></div>
<h2 id="customizations">Copy Customizations</h2>
<hr />

<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="carbon_copy_title_prefix">
				<?php esc_html_e( "Title prefix", 'default' ); ?>
			</label>
		</th>
		<td>
			<input type="text" name="carbon_copy_title_prefix" id="carbon_copy_title_prefix" value="<?php form_option( 'carbon_copy_title_prefix' ); ?>" /><br />
			<span class="description"><?php esc_html_e( "For example; \"Copy of\" (leave blank for no prefix)", 'default' ); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="carbon_copy_title_suffix">
				<?php esc_html_e( "Title suffix", 'default' ); ?>
			</label>
		</th>
		<td>
			<input type="text" name="carbon_copy_title_suffix" id="carbon_copy_title_suffix" value="<?php form_option( 'carbon_copy_title_suffix' ); ?>" /><br />
			<span class="description"><?php esc_html_e( "For example; \"Copy\" (leave blank for no suffix)", 'default' ); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="carbon_copy_increase_menu_order_by">
				<?php esc_html_e( "Menu order increase", 'default' ); ?>
			</label>
		</th>
		<td>
			<input type="text" name="carbon_copy_increase_menu_order_by" id="carbon_copy_increase_menu_order_by" value="<?php form_option( 'carbon_copy_increase_menu_order_by' ); ?>" /><br />
			<span class="description"><?php esc_html_e( "Add number to original menu order (leave blank/zero to retain value)", 'default' ); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="carbon_copy_blacklist">
				<?php esc_html_e( "Meta Field Exclusion List", 'default' ); ?>
			</label>
		</th>
		<td id="textfield">
			<input type="text" class="regular-text" name="carbon_copy_blacklist" id="carbon_copy_blacklist" value="<?php form_option( 'carbon_copy_blacklist' ); ?>" /><br />
			<span class="description"><?php esc_html_e( "Comma-separate list, use * to match zero or more alphanumeric characters or underscores: ex. field*", 'default' ); ?></span>
		</td>
	</tr>
</table>


<div style="padding:10px 0px;"></div>
<h2 id="display">Display</h2>
<hr />


	<div style="padding:10px 0px;"></div>
	<h3>Link Display Locations</h3>
	<span class="description"><?php esc_html_e( "Copy Links for Custom Post Types might not display, they are dependent upon your Theme or Plugin.", 'default' ); ?></span><br />
	<span class="description"><?php esc_html_e( "You can use the template tag: carbon_copy_clone_post_link( &#36;link, &#36;before, &#36;after, &#36;id )", 'default' ); ?></span><br />
	<?php printf(__( 'Learn more by visiting the <a href="%s" target="_blank" title="Opens in New Window">developer&apos;s guide for Carbon Copy</a>', 'default' ), 'https://endurtech.com/carbon-copy-wordpress-plugin/#clone_post_link'); ?></span><br />
	<br />

<label>
	<input type="checkbox" name="carbon_copy_show_row" value="1" <?php if( get_option( 'carbon_copy_show_row' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Post list", 'default' ); ?>
</label><br />
<label>
	<input type="checkbox" name="carbon_copy_show_submitbox" value="1" <?php if( get_option( 'carbon_copy_show_submitbox' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Edit screen", 'default' ); ?>
</label><br />
<label>
	<input type="checkbox" name="carbon_copy_show_adminbar" value="1" <?php if( get_option( 'carbon_copy_show_adminbar' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Admin bar", 'default' ); ?>
	<span class="description">(<em><?php esc_html_e( "Works on Edit screen, check to use with Gutenberg enabled.", 'default' ); ?></em>)</span>
</label><br />
<?php
global $wp_version;
if( version_compare($wp_version, '4.7') >= 0 )
{
	?>
<label>
	<input type="checkbox" name="carbon_copy_show_bulkactions" value="1" <?php if( get_option( 'carbon_copy_show_bulkactions' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Bulk Actions", 'default' ); ?>
</label><br />
<?php
}
?>

	<div style="padding:10px 0px;"></div>
	<h3>Original Post Link</h3>

<label>
	<input type="checkbox" name="carbon_copy_show_original_meta_box" value="1" <?php if( get_option( 'carbon_copy_show_original_meta_box' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "In a metabox on the Edit screen [Classic editor]", 'default' ); ?>
	<span class="description">(<?php esc_html_e( "You will also be able to delete the reference to the original item with a checkbox.", 'default' );  ?>)</span>
</label><br />
<label>
	<input type="checkbox" name="carbon_copy_show_original_column" value="1" <?php if( get_option( 'carbon_copy_show_original_column' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "In a column in the Post list", 'default' ); ?>
	<span class="description">(<?php esc_html_e( "You will also be able to delete the reference to the original item with a checkbox in Quick Edit.", 'default' );  ?>)</span>
</label><br />
<label>
	<input type="checkbox" name="carbon_copy_show_original_in_post_states" value="1" <?php if( get_option( 'carbon_copy_show_original_in_post_states' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "After the title in the Post list", 'default' ); ?>
</label>

	<div style="padding:10px 0px;"></div>
	<p>Did <a href="https://wordpress.org/plugins/carbon-copy/" target="_blank" title="Opens New Window">this plugin</a> save you time? <a href="https://endurtech.com/give-thanks/" target="_blank" title="Opens New Window"><strong>Share your appreciation</strong></a> and support future improvements.</p>
	<p><label>
		<input type="checkbox" name="carbon_copy_cleaner" value="1" <?php if( get_option( 'carbon_copy_cleaner' ) == 1 ) echo 'checked="checked"'; ?> /><?php esc_html_e( "Remove Plugin Database Values Upon Deactivation", 'default' ); ?>
	</label></p>

	<p class="submit"><input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes', 'default' ) ?>" /></p>

</form>
</div>
<?php
}
?>