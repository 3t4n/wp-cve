<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/single-loop-all.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

global $inventory_display;
$inventory_display = apply_filters( 'wpim_display_listing_settings', $inventory_display );

?>
<div class="<?php wpinventory_class(); ?>">
	<?php foreach ( $inventory_display AS $sort => $field ) {
	  ?>
        <p class="<?php esc_attr_e( $field ); ?>"><?php echo apply_filters( 'wpim_listing_open_link_tag', '<a href="' . wpinventory_get_permalink() . '">', $field ) . wpinventory_get_field( $field ) . apply_filters( 'wpim_listing_close_link_tag', '</a>', $field ); ?></p>
	<?php } ?>
</div>