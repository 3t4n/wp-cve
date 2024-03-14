<?php

/**
 * Loop All Items - Sample
 * version 1.2.1
 */

/**
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled
 * wpinventory/views/loop-shortcode.php While inventory does not use the WP post types, it does model functions after
 * the WP core functions to provide similar functionality
 * */

if ( wpinventory_is_single() ) {
	wpinventory_get_template_part( 'single-item' );

	return;
}

?>
    <style>
        tr.wpinventory {
            border: 1px solid #ccc;
        }

        tr.wpinventory td {
            padding: 5px 0;
        }

        tr.wpinventory_even td {
            background: #eee;
        }

        tr.wpinventory_odd td {
            background: #ffe;
        }
    </style>
<?php
wpinventory_get_items();

echo wpinventory_filter_form( 'filter=true&sort=true' );

if ( ! empty( $_GET['message'] ) ) {
	echo '<div class="wpinventory_message">';
	echo wp_kses( urldecode( $_GET['message'] ), 'post' );
	echo '</div>';
}

if ( wpinventory_have_items() ) { ?>
    <table>
        <thead>
        <tr>
            <th><?php wpinventory_the_label( "inventory_number" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_name" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_description" ); ?></th>
            <th>Image</th>
            <th><?php wpinventory_the_label( "inventory_size" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_manufacturer" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_make" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_model" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_year" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_serial" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_quantity" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_quantity_reserved" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_price" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_category" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_date_added" ); ?></th>
            <th><?php wpinventory_the_label( "inventory_date_updated" ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php while ( wpinventory_have_items() ) {
			wpinventory_the_item();
			wpinventory_get_template_part( 'single-loop-all' );
		}
		do_action( 'wpim_template_loop_all_item_end', 'grid' ); ?>
        </tbody>
    </table>
	<?php
	echo wpinventory_pagination();
} else { ?>
    <p class="wpinventory_warning">No Inventory Items</p>
<?php } ?>