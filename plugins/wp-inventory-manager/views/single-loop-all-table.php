<?php

/**
 * Loop All Items Single Item - Table Layout
 * version 1.2.1
 */

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled
 * wpinventory/views/single-loop-all.php While inventory does not use the WP post types, it does model functions after
 * the WP core functions to provide similar functionality.
 *
 * NOTICE:
 * This file is designed to be "automatic", and display the fields you have selected in your Display Settings.
 * You can completely customize the file - if that's your intention, it is recommended that you refer to
 * the view file titled "single-loop-all-sample.php" for examples of functions to use for complete control.
 * */

global $inventory_display;
$inventory_display = apply_filters( 'wpim_display_listing_settings', $inventory_display );
do_action( 'wpim_template_single_loop_table', $inventory_display );
?>
<tr class="<?php wpinventory_class(); ?>">
	<?php foreach ( $inventory_display AS $sort => $field ) {
  ?>
    <td class="<?php echo esc_attr( $field ); ?>">
		<?php if ( $field != 'inventory_description' ) { ?>
			<?php echo apply_filters( 'wpim_listing_open_link_tag', '<a href="' . wpinventory_get_permalink() . '">', $field ) . wpinventory_get_field( $field ) . apply_filters( 'wpim_listing_close_link_tag', '</a>', $field ); ?>
		<?php } else { ?>
			<?php wpinventory_the_field( $field ); ?>
		<?php }
		} ?>
    </td>
	<?php do_action( 'wpim_template_loop_all_item_end', 'table' ); ?>
</tr>
