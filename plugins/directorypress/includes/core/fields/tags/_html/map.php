<?php if (has_term('', DIRECTORYPRESS_TAGS_TAX, $listing->post->ID)): ?>
	<?php echo get_the_term_list($listing->post->ID, DIRECTORYPRESS_TAGS_TAX, '', ', ', ''); ?>
<?php endif; ?>