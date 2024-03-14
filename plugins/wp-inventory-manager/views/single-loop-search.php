<?php

/**
 * The single template specifically designed for the search results.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/single-loop-all.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 *
 * NOTICE:
 * This is a sample file that demonstrates some of the functions that you can call, in order to give you
 * the capability to structure a view however you like.
 *
 * To use this, you would copy it to your overrides folder, and rename it "single-loop-search.php"
 * */

// Get the user's settings for which fields to display.
global $inventory_display;
$inventory_display = apply_filters( 'wpim_display_search_settings', $inventory_display );
// We've calculated the post ID where the shortcode is. Use it.
global $shortcode_post_id;
// Trying to keep in line with "Standard" search results, so get the first field and treat it as the title
$title_field = ( is_array( $inventory_display ) ) ? array_shift( $inventory_display ) : 'inventory_name';
global $display_labels;
?>
<article id="inventory-<?php wpinventory_the_ID(); ?>" class="<?php wpinventory_class(); ?>">
    <header class="entry-header">
        <h2 class="entry-title"><a href="<?php wpinventory_the_permalink( $shortcode_post_id ); ?>"
                                   rel="bookmark"><?php wpinventory_the_field( $title_field ); ?></a></h2>
    </header>
    <div class="entry-summary">
		<?php foreach ( (array) $inventory_display AS $sort => $field ) {
	?>
        <p class="<?php esc_attr_e( $field ); ?>">
			<?php if ( $display_labels ) { ?>
                <span class="label"><?php wpinventory_the_label( $field ); ?></span>
			<?php } ?>
			<?php if ( $field != 'inventory_description' ) { ?>
				<?php echo apply_filters( 'wpim_listing_open_link_tag', '<a href="' . wpinventory_get_permalink() . '">', $field ) . wpinventory_get_field( $field ) . apply_filters( 'wpim_listing_close_link_tag', '</a>', $field ); ?>
			<?php } else { ?>
				<?php wpinventory_the_field( $field ); ?>
			<?php } ?>
			<?php } ?>
    </div>
</article>

