<?php

/**
 * 
 * You can absolutely override this utilizing WP Inventory's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 * 
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

global $wpim_widget_page_id;
if (wpinventory_have_items()) { ?>
	<ul>
		<?php while (wpinventory_have_items()) {
				wpinventory_the_item();
				wpinventory_get_template_part('widget-latest-items-single');
			} ?>
	</ul>
<?php 
	} else { ?>
	<p class="wpinventory_warning"><?php WPIMCore::_e('No Inventory Items'); ?></p>
<?php } ?>