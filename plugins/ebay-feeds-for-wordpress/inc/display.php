<?php

/**
* Main old function
*
* Was previously a carbon copy of the ebay_feeds_for_wordpress_notecho function. Now it simply calls it since 2.0. Easier to maintain.
*
* @param  string $url 		The URL of the Feed
* @param  string $num 		The number of items to display
* @return mixed 			String of the feed if echo is false, string if true
*/
function ebay_feeds_for_wordpress( $url = "", $num = "", $echo = false, $args = array(), $header = '' ) {

	$display = ebay_feeds_for_wordpress_notecho( $url, $num, $args, $header );

	if ( true == $echo ) {
		echo $display;
	} else {
		return $display;
	}
}


/**
* Function to return the eBay Feed.
*
* @param  string $dispurls The URL of the Feed
* @param  string $dispnum  The number of items to display
* @return string          	String of the feed content
*/
function ebay_feeds_for_wordpress_notecho( $dispurls = "", $dispnum = "", $args = array(), $header = '' ) {

	// If the user agent is a bot, return nothing
	$canview = true;
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$botsarray = array( "slurp", "crawl", "spider", "curl", "track", "ltx", "msn" );
	$botsarray = apply_filters( 'wp_ebay_product_feed_bots', $botsarray );

	foreach ( $botsarray as $bot ) {
		if ( stripos($u_agent, $bot) !== FALSE ) {
			$canview = false;
			echo "<!-- Broke on: " . $bot . "-->";
			break;
		}
	}

	if ( $canview ) {
		if ( ! preg_match('#(MSIE|Trident|(?!Gecko.+)Firefox|(?!AppleWebKit.+Chrome.+)Safari(?!.+Edge)|(?!AppleWebKit.+)Chrome(?!.+Edge)|(?!AppleWebKit.+Chrome.+Safari.+)Edge|AppleWebKit(?!.+Chrome|.+Safari)|Gecko(?!.+Firefox))(?: |\/)([\d\.apre]+)#', $_SERVER['HTTP_USER_AGENT'] ) ) {
			$canview = false;
		}
	}

	// This will allow us to filter this out if needed.
	$canview = apply_filters( 'wp_ebay_product_feed_bot_blocker', $canview );

	// We can now show text to bots if need be.
	if ( !$canview ) {
		return apply_filters( 'wp_ebay_product_feed_blocked_text', '', $dispurls, $dispnum, $args, $header );
	}

	$link          = get_option( "ebay-feeds-for-wordpress-link" );
	$blank         = get_option( "ebay-feeds-for-wordpress-link-open-blank" );
	$nofollow      = get_option( "ebay-feeds-for-wordpress-nofollow-links" );
	$debug         = get_option( "ebay-feeds-for-wordpress-debug" );
	$class         = get_option( 'ebay-feeds-for-wordpress-item-div-wrapper' );
	$disableheader = get_option( 'ebay-feeds-for-wordpress-disable-header' );
	$disprss_items = "";
	$display = "";
	$ssl 	= get_option( 'ebay-feed-for-wordpress-ssl' );

	if ( $dispnum == "" || $dispnum == "null" ) {

		$dispnum = get_option( 'ebay-feeds-for-wordpress-default-number' );

	}

	if ( $dispurls == "" || $dispurls == "null" ) {

		$dispurldefault = esc_attr( get_option( 'ebay-feeds-for-wordpress-default' ) );
		$dispurldefault = apply_filters( 'wp_ebay_product_feed_url', $dispurldefault );
		
		$dispurldefault = str_replace( '&amp;', '&', $dispurldefault );
		$disprss        = fetch_feed( $dispurldefault );

		if ( $disprss ) {

			if ( !is_wp_error( $disprss ) ) {
				$disprss->enable_order_by_date(false);
				$maxitems      = $disprss->get_item_quantity( $dispnum );
				$disprss_items = $disprss->get_items( 0, $maxitems );
				$dispurls      = $dispurldefault;
			}

		} else {

			$fallback = get_option( 'ebay_feeds_for_wordpress_fallback' );

			$display .=  "<div class='ebayfeed'>";
			$display .= $fallback;
			$display .=  "</div>";
		}

	} else {

		$dispurls = apply_filters( 'wp_ebay_product_feed_url', $dispurls );
		$dispurls = str_replace( '&amp;', '&', $dispurls );

		$disprss  = fetch_feed( $dispurls );
		//wp_die( print_r( $disprss ) );

		if ( !is_wp_error( $disprss ) ) {
			$disprss->enable_order_by_date(false);
			$maxitems      = $disprss->get_item_quantity( $dispnum );
			$disprss_items = $disprss->get_items( 0, $maxitems );
			//print_r( $disprss_items );
		} else {

			if ( current_user_can( 'manage_options' ) && 1 == $debug ) {

				$error_string = $disprss->get_error_message();

				$display .= '<div id="message" class="error"><p>' . $error_string . '</p></div>';

			}

			$fallback = get_option( 'ebay_feeds_for_wordpress_fallback' );
			$display .=  "<div class='ebayfeed'>";
			$display .= $fallback;
			$display .=  "</div>";
			return $display;

		}

	}

	if ( false !== strpos( $dispurls, 'rssground' ) ) {
		$extrawrapperclass = "rssground";
	} else {
		$extrawrapperclass = '';
	}

	$display .=  "<div class='ebayfeed " . $extrawrapperclass . "'>";

	//print_r( $disprss_items );

	if ( $disprss_items && $maxitems != 0 ) {

		$temptitle = false;
		$tempperm  = false;
		$temdesc   = false;

		if ( ! $disableheader && $header ) {
			$display .=  "<h3>" . $header . "</h3>";
		}

		$itemno = 0;

		foreach ( $disprss_items as $dispitem ) {

			if ( ! $temptitle && '' != $dispitem->get_title() ) {
				$temptitle = true;
			}

			if ( !$tempperm == '' && '' != $dispitem->get_permalink() ) {
				$tempperm = true;
			}

			if ( $temdesc == '' && '' != $dispitem->get_description() ) {
				$temdesc = true;
			}

			$title = "<h4 class='ebayfeedtitle'><a class='ebayfeedlink' ";

			if ( $blank == "1" ) {
				$title .= "target='_blank' ";
			}

			if ( $nofollow == "1" ) {
				$title .= " rel='nofollow' ";
			}

			$itemno++;

			if ( $class ) {

				$classstring = '<div class="' . $class . ' ebay-feed-item-' . $itemno .'">';

			} else {
				$classstring = '<div class="ebay-feed-item-' . $itemno .'">';
			}


			$classstring   = apply_filters( 'ebay_feeds_for_wordpress_change_class_string', $classstring, $args );

			$feeditemtitle = apply_filters( 'ebay_feeds_for_wordpress_change_feed_item_name', $dispitem->get_title(), $args );

			$title        .= "href='" . $dispitem->get_permalink()."'>" . $feeditemtitle . "</a></h4>";
			$title         = apply_filters( 'ebay_feeds_for_wordpress_title_string', $title, $args );

			$display .= $classstring . $title;

			if ( $blank != "1" ) {
				$newdescription = str_replace( "target='_blank'", "", $dispitem->get_description() );
				$newdescription = str_replace( 'target="_blank"', '', $dispitem->get_description() );
			} else {
				$newdescription = $dispitem->get_description();
			}

			if ( $nofollow == "1" ) {
				$newdescription = str_replace( "<a href=", "<a rel='nofollow' href=", $newdescription );
			}

			if ( $ssl == "1" ) {

				$newdescription = str_replace( "<img src='http://","<img src='https://", $newdescription );

			}

			$newdescription = apply_filters( 'ebay_feeds_for_wordpress_description_string', $newdescription, $args );

			$display .= $newdescription;

			$display .= "</div>";

			if ( !$temptitle && ! $tempperm && ! $tempdesc ) {
				$fallback = get_option( 'ebay_feeds_for_wordpress_fallback' );
				$display =  "<div class='ebayfeed'>" . $fallback;
			}

		}
	} else {
		$fallback = get_option( 'ebay_feeds_for_wordpress_fallback' );
		$display .= $fallback;
	}

	$display .= "</div>";

	if ( $link == 1 ) {
		$display .= __( '<a href="https://www.winwar.co.uk/plugins/ebay-feeds-wordpress/">WP eBay Product Feeds</a> by <a href="http://winwar.co.uk/">Winwar Media</a><br/><br/>', 'ebay-feeds-for-wordpress' );
	}


	return $display;

}


/**
* Parse the WP eBay Product Feeds Shortcode
* @param  array  $atts  	array of attributes
* @return string 			The display feed
*/
function ebayfeedsforwordpress_shortcode( $atts ) {

	$feed   = '';
	$items  = '';
	$header = '';

	extract( shortcode_atts( array(
		'feed' => $feed, 'items' => $items, 'header' => $header
	), $atts ) );

	if ( ! $feed ) {
		$feed  = get_option( 'ebay-feeds-for-wordpress-default' );
	}

	if ( '' == $items ) {
		$items = get_option( 'ebay-feeds-for-wordpress-default-number' );
	}

	if ( '' == $header ) {
		$header = get_option( 'ebay-feeds-for-wordpress-default-header' );
	}

	$feed = html_entity_decode( $feed );

	$feeddisplay = ebay_feeds_for_wordpress( esc_attr( $feed ), esc_attr( $items ), false, array(), esc_attr( $header ) );
	return $feeddisplay;
}


/**
* Add CSS to the header to display max width
* @return void
*/
function ebayfeedsforwordpress_set_max_image_width() {
	if ( get_option( 'ebay-feeds-for-wordpress-imax-max-width' ) ) {
		?>
		<style type="text/css">
		.ebayfeed img {
			max-width: <?php echo get_option( 'ebay-feeds-for-wordpress-imax-max-width' ); ?>px;
		}
		</style>
		<?php
	}
}


/**
 * Add Google & Bing to the search arrays
 *
 * @param  array $botarray A list of bots we are blocking from the site
 * @return array           A list of bots we are blocking from the site + Google & Bing
 */
function ebay_feeds_for_wordpress_hide_from_search( $botsarray ) {
	$botsarray[] = 'google';
	$botsarray[] = 'bing';

	return $botsarray;
}
