<?php
	$authorID = get_the_author_meta( 'ID', $listing->post->post_author);
	$author_nicename = get_the_author_meta('nicename', $authorID);
	// Figure
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_listview_thumbnail', $listing);
		do_action('directorypress_listing_grid_featured_tag', $listing);
	echo '</figure>';
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		echo '<div class="clearfix mod-inner-content">';
			do_action('directorypress_listing_grid_category', $listing);
			echo '<div class="listing-listview-buttons clearfix">';
				do_action('directorypress_listing_grid_bookmark', $listing, 2);
				do_action('directorypress_wcfm_add_to_cart', $listing->post->ID, 'pacz-fic-shopping-basket');
				do_action('directorypress_listing_grid_status_tag', $listing);
				do_action('directorypress_listing_grid_ratting', $listing);
			echo '</div>';
			do_action('directorypress_listing_grid_title', $listing);
			// fields
			echo '<div class="listing-content-fields">';
				do_action('directorypress_listing_grid_summary_field', $listing);
				do_action('directorypress_listing_grid_inline_fields', $listing);
				do_action('directorypress_listing_grid_block_fields', $listing);
			echo '</div>';
		echo '</div>';
		echo '<div class="modlist-bottom-area clearfix">';
			do_action('directorypress_listing_grid_address', $listing);
			do_action('directorypress_listing_grid_price_field', $listing);
		echo '</div>';
	echo '</div>';