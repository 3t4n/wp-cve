<?php

/**
 * List all posts with rover links
 *
 * @return void
 */
function ebffwp_list_all_posts_with_rovers() {

	$restposts = ebay_feeds_for_wordpress_get_number_of_ebay_rest_posts();

	if ( $restposts->found_posts > 0 ) {

		$arurl = "https://www.winwar.co.uk/recommends/rss-ground-plugin/";

		echo '<tr valign="top">
			<th scope="row" style="width:400px" colspan="2">
				<h2>' . __( 'Legacy Feeds', 'ebay-feeds-for-wordpress' ) . '</h2>
			</th>
		</tr>';

		echo '<tr valign="top"><td colspan="2"><div class="ebffwp-warningstring" style="display:block;">';

		printf( __( '<p>We\'ve detected that there are %d posts on the site with old (rest.ebay.com) feeds. These feeds will not work from 1st September 2020. Please correct these with links from a new service such as <a href="%s">RSS Ground</a>.</p>', 'ebay-feeds-for-wordpress' ), $restposts->found_posts, $arurl );

		echo '<ul>';

		while( $restposts->have_posts() ) {

			$restposts->the_post();
			echo '<li><a href="' . get_the_permalink() . '" target="_blank">' . get_the_title() . '</a></li>';

		}

		echo '<ul>';

		echo '</div></td></tr>';
	}

} add_action( 'ebay_feeds_for_wordpress_added_options', 'ebffwp_list_all_posts_with_rovers', 1 );