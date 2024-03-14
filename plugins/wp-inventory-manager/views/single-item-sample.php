<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/single-item.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

wpinventory_get_items();
if (wpinventory_have_items()) {
		while(wpinventory_have_items()) {
		wpinventory_the_item(); ?>
			<div class="<?php wpinventory_class(); ?>">
				<p><?php wpinventory_the_number(); ?></p>
				<p><?php wpinventory_the_name(); ?></p>
				<p><?php wpinventory_the_description(); ?></p>
				<p><?php wpinventory_the_size(); ?></p>
				<p><?php wpinventory_the_manufacturer(); ?></p>
				<p><?php wpinventory_the_make(); ?></p>
				<p><?php wpinventory_the_model(); ?></p>
				<p><?php wpinventory_the_year(); ?></p>
				<p><?php wpinventory_the_serial(); ?></p>
				<p><?php wpinventory_the_quantity(); ?></p>
				<p><?php wpinventory_the_reserved(); ?></p>
				<p><?php wpinventory_the_price(); ?></p>
				<p><?php wpinventory_the_status(); ?></p>
				<p><?php wpinventory_the_category(); ?></p>
				<p><?php wpinventory_the_date(); ?></p>
				<p><?php wpinventory_the_date_updated(); ?></p>
				<div class="images">
					<?php wpinventory_the_images('medium'); ?>
				</div>
				<?php do_action('wpim_the_field_inventory_images'); ?>
			</div>
		<?php }
}
