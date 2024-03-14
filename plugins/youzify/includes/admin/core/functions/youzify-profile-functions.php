<?php

/**
 * Xprofile Fields Functions.
 */
function youzify_add_fields_group_icon_field() {

	// Get Group ID
	$group_id = isset( $_GET['group_id'] ) ? absint( $_GET['group_id'] ) : null;

	// Get Group Icon.
	$icon = youzify_get_xprofile_group_icon( $group_id );

	?>

	<div class="postbox">
		<h2><?php _e( 'Field Group Icon', 'youzify' ); ?></h2>
		<div class="inside">
			<div id="fields_group_icon" class="ukai_iconPicker" data-icons-type="web_application">
				<div class="ukai_icon_selector">
					<i class="<?php echo $icon; ?>"></i>
					<span class="ukai_select_icon">
						<i class="fas fa-sort-down"></i>
					</span>
				</div>
				<input type="hidden" class="ukai-selected-icon" name="fields_group_icon" value="<?php echo $icon; ?>">
			</div>
		</div>
	</div>

	<?php

}

add_action( 'xprofile_group_before_submitbox', 'youzify_add_fields_group_icon_field' );

/**
 * Save Fields Group Icon
 */
function youzify_save_xprofile_group_icon( $group ) {

	// Get Group Icon.
	$group_icon = sanitize_text_field( $_POST['fields_group_icon'] );

	// Save Group Icon.
	update_option( 'youzify_xprofile_group_icon_' . $group->id , $group_icon, 'no' );

}

add_action( 'xprofile_groups_saved_group', 'youzify_save_xprofile_group_icon' );

/**
 * Xprofile Groups Script
 */
function youzify_xprofile_groups_scripts( $hook ) {

    if ( isset( $_GET['page'] ) && 'bp-profile-setup' == $_GET['page'] ) {
        wp_enqueue_style( 'youzify-icons' );
        youzify_iconpicker_scripts();
    }

}

add_action( 'admin_enqueue_scripts','youzify_xprofile_groups_scripts', 10, 1 );