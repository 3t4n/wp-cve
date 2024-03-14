<?php 
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	echo wp_kses_post($args['before_widget']);
	if (!empty($title)){
		echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
	}

	echo '<div class=" directorypress-widget directorypress_bids_widget">';
		echo '<ul>';
		
			$listing = $GLOBALS['listing_id'];
			echo '<li><span class="bid-item-label">'. esc_html__('Total Bids', 'DIRECTORYPRESS') .'</span><span class="bid-item-value">'. esc_html($listing->bidcount()) .'</span></li>';
			echo '<li><span class="bid-item-label">'. esc_html__('Highest Bid', 'DIRECTORYPRESS') .'</span><span class="bid-item-value">'. esc_html(round($listing->highestbid(), 2)) .'</li>';
			echo '<li><span class="bid-item-label">'. esc_html__('Lowest Bid', 'DIRECTORYPRESS') .'</span><span class="bid-item-value">'. esc_html(round($listing->lowestbid(),2)) .'</li>'; 
			echo '<li><span class="bid-item-label">'. esc_html__('Average Bid', 'DIRECTORYPRESS') .'</span><span class="bid-item-value">'. wp_kses_post(round($listing->avgbid(),2)) .'</li>';
		echo '</ul>';
	echo '</div>';
	echo wp_kses_post($args['after_widget']);