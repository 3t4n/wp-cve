<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	$ttbm_post_id = $ttbm_post_id ?? get_the_id();
		?>
		<div class="ttbm_description mp_wp_editor" data-placeholder="">
			<?php the_content(); ?>
		</div>