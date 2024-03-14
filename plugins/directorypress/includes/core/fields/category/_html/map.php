<?php if (has_term('', DIRECTORYPRESS_CATEGORIES_TAX, $listing->post->ID)): ?>
	<?php echo get_the_term_list($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, '', ', ', ''); ?>
<?php endif; ?>