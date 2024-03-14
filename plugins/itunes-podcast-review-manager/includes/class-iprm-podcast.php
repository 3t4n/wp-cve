<?php

/* EXIT IF FILE IS CALLED DIRECTLY */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* PODCAST CLASS

*  FUNCTIONS:
*  get_itunes_metadata()
*  get meta_tag_content()
*  display_itunes_feed_summary()
*  display_page_reviews()
*  get_itunes_feed_contents()

*/

class IPRM_Podcast {
	public $reviews              = array();
	public $review_cache_history = array();
	public $settings             = array();
	public $itunes_url           = '';
	public $itunes_id            = '';
	public $itunes_feed_image    = '';
	public $itunes_feed_name     = '';
	public $itunes_feed_artist   = '';
	public $itunes_feed_summary  = '';

	function __construct( $url ) {
		$this->settings             = $this->get_itunes_metadata( $url );
		$this->itunes_url           = $url;
		$this->itunes_id            = $this->settings['itunes_id'];
		$this->itunes_feed_image    = $this->settings['itunes_feed_image'];
		$this->itunes_feed_name     = $this->settings['itunes_feed_name'];
		$this->itunes_feed_artist   = $this->settings['itunes_feed_artist'];
		$this->itunes_feed_summary  = $this->settings['itunes_feed_summary'];
		$this->review_cache_history = unserialize( iprm_get_option( 'iprm_review_cache_history' . $this->itunes_id ) );

		$file = WP_PLUGIN_DIR . "/itunes-podcast-review-manager/cache/cache_$this->itunes_id.cache";

		if ( file_exists( $file ) ) {
			$this->reviews = unserialize( file_get_contents( $file ) );
		} else {
			$this->reviews = array();
		}

		/* WRITE TO DB NEW LIST OF PODCASTS AND WHAT ONE IS ACTIVE */
		if ( 'https://itunes.apple.com/us/' !== $url ) {
			iprm_update_option( 'iprm_active_product', $url );
		}
		$podcast_array = iprm_get_option( 'iprm_podcasts' );
		if ( ! is_array( $podcast_array ) ) {
			$podcast_array = array();
		}
		$key = array_search( $url, $podcast_array, true );

		if ( ! $key ) { /* NOT FOUND IN EXISITNG DB */
			$podcast_array[] = $url;
		}
		$podcast_array = array_unique( $podcast_array );

		/* DONT WRITE DEFAULT TO DB */
		if ( ( 'https://itunes.apple.com/us/' !== $url ) && ( isset( $this->itunes_id ) ) ) {
			iprm_update_option( 'iprm_podcasts', $podcast_array );
		}
	}

	function get_meta_tag_content( $html_content, $meta_tag_attribute_identifier, $meta_tag_attribute_value ) {
		$to_find           = '<meta ' . $meta_tag_attribute_identifier . '="' . $meta_tag_attribute_value . '"';
		$pos1              = strpos( $html_content, $to_find );
		$content_start_pos = strpos( $html_content, 'content', $pos1 ) + strlen( 'content="' );
		$content_end_pos   = strpos( $html_content, '" ', $content_start_pos + 1 );
		$content           = substr( $html_content, $content_start_pos, $content_end_pos - $content_start_pos );
		return $content;
	}

	function get_itunes_metadata( $url ) {
		$path   = parse_url( $url, PHP_URL_PATH );
		$pieces = explode( '/', $path );
		$id     = '';
		if ( array_key_exists( 4, $pieces ) ) {
			$id = $pieces[4];
			$id = str_ireplace( 'id', '', $id );
		}

		/* ONLY CONTINUE IF WE HAVE GOOD URL AND PARSED AN ID */
		if ( ( filter_var( $url, FILTER_VALIDATE_URL ) ) && ( ! empty( $id ) ) ) {

			/* POPULATE METADATA ARRAY */
			$metadata_array['itunes_id'] = $id;

			/* BLACK MAGIC ? */
			/*$itunes_json1 = json_encode( wp_remote_get( $url ) );
			$data1        = json_decode( $itunes_json1, true );
			$url_xml      = $data1['body'];/**/

			//$itunes_xml = wp_safe_remote_get( $url );
			//$url_xml = $itunes_xml['body'];

			//$url      = 'http://example.org/api';
			//$response = json_encode( wp_remote_get( esc_url_raw( $url . '.json' ) ) );
			//$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
			//$temp = $api_response['body'];
			//print_r( $temp );
			//print_r( $url_xml );
			//$url_xml = '';

			$args = array(
				'timeout' => 10,
			);
			$response = wp_remote_get( $url, $args );
			if ( is_wp_error( $response ) ) {
				return;
			}
			$url_xml = wp_remote_retrieve_body( $response );

			// Check for error
			if ( is_wp_error( $url_xml ) ) {
				return;
			}
			//echo esc_html($url_xml);
			//echo '<br /><br />';
			//echo esc_html( $this->get_meta_tag_content( $url_xml, 'name', 'apple:title' ) );

			// Get title
			$metadata_array['itunes_feed_name'] = esc_html( $this->get_meta_tag_content( $url_xml, 'name', 'apple:title' ) );
			/*$title_div        = iprm_get_contents_inside_tag( $url_xml, '<div id="title" class="intro">', '</div>' );
			$itunes_feed_name = iprm_get_contents_inside_tag( $title_div, '<h1>', '</h1>' );
			if ( 'EMPTYSTR' !== $itunes_feed_name ) {
				$metadata_array['itunes_feed_name'] = $itunes_feed_name;
			} else {
				$itunes_feed_name                   = iprm_get_contents_inside_tag( $title_div, '<h1 itemprop="name">', '</h1>' );
				$metadata_array['itunes_feed_name'] = $itunes_feed_name;
			}/**/

			// Get artist
			$metadata_array['itunes_feed_artist'] = '';
			/*$itunes_feed_artist = iprm_get_contents_inside_tag( $title_div, '<h2>', '</h2>' );
			if ( 'EMPTYSTR' !== $itunes_feed_artist ) {
				$metadata_array['itunes_feed_artist'] = $itunes_feed_artist;
			} else {
				$itunes_feed_artist                   = iprm_get_contents_inside_tag( $title_div, '<h2 itemprop="name">', '</h2>' );
				$metadata_array['itunes_feed_artist'] = $itunes_feed_artist;
			}/**/

			// Get summary
			$metadata_array['itunes_feed_summary'] = esc_html( $this->get_meta_tag_content( $url_xml, 'name', 'apple:description' ) );
			/*$summary_div = iprm_get_contents_inside_tag( $url_xml, '<div metrics-loc="Titledbox_Description" class="product-review">', '</div>' );
			$itunes_feed_summary = iprm_get_contents_inside_tag( $summary_div, '<p>', '</p>' );
			if ( 'EMPTYSTR' !== $itunes_feed_summary ) {
				$metadata_array['itunes_feed_summary'] = $itunes_feed_summary;
			} else {
				$metadata_array['itunes_feed_summary'] = '';
			}/**/

			// Get artwork
			$metadata_array['itunes_feed_image'] = esc_html( $this->get_meta_tag_content( $url_xml, 'name', 'twitter:image' ) );
			/* CANT GET ART THIS WAY, HAVE TO CHECK XML FEED */
			/*$url_xml           = 'https://itunes.apple.com/us/rss/customerreviews/id=' . $metadata_array['itunes_id'] . '/xml';
			$itunes_json1      = json_encode( wp_remote_get( $url_xml ) );
			$data1             = json_decode( $itunes_json1, true );
			$url_xml           = $data1['body'];
			$itunes_feed_image = iprm_get_contents_inside_tag( $url_xml, '<im:image height="170">', '</im:image>' );

			$metadata_array['itunes_feed_image'] = $itunes_feed_image;/**/
			return $metadata_array;
		} else {
			return false;
		}
	}

	function display_itunes_feed_summary() {
		$output = '';
		/* CHECKS TO MAKE SURE ITUNES PODCAST URL IS DEFINED */
		if ( '' !== $this->itunes_url ) {
			$output = "
			<div class='iprm_panel' id='iprm_metadata'>
				<div id='iprm_meta_img' style='background-image:url(" . $this->itunes_feed_image . ");'>
					<!-- <img src='$this->itunes_feed_image'> -->
				</div>
				<div class='iprm_panel_content'>
					<h2>$this->itunes_feed_name</h2>
					<p>$this->itunes_feed_artist</p>
					<p>$this->itunes_feed_summary</p>
				</div>
			</div>";
		}
		return $output;
	}

	function display_page_reviews() {
		$review_number = 0;
		$output        = '';
		$rating_total  = 0;
		if ( is_admin() ) {
			$sort_colspan = 2;
		} else {
			$sort_colspan = 1;
		}
		$sort_colspan = 2;
		/* CHECKS TO MAKE SURE ITUNES PODCAST URL IS DEFINED */
		if ( '' !== $this->itunes_url ) {
			/* GENERATES TABLE ROWS FOR ALL REVIEWS */
			ob_start(); ?>	
				<div id="iprm_main_table" class="iprm_panel">
					<h2 id="iprm_review_h2">REVIEWS</h2>
					<table id="iprm_main_table_body" class="iprm_table sortable"  border="0" cellpadding="0" cellspacing="0">
						<!-- TABLE HEADINGS -->
						<tr>
							<th class="unsortable">
								FLAG
							</th>
							<th>
								COUNTRY
								<div id="iprm_COUNTRY_controls" class="iprm_sort_control">
								<a href="#iprm_main_table" id="iprm_sort_country_asc"><span class="dashicons dashicons-arrow-up"></span></a><br>
								<a href="#iprm_main_table" id="iprm_sort_country_asc"><span class="dashicons dashicons-arrow-down"></span></a>
								</div>
							</th>
							<th>
								DATE
								<div id="iprm_DATE_controls" class="iprm_sort_control">
								<a href="#iprm_main_table" id="iprm_sort_DATE_asc"><span class="dashicons dashicons-arrow-up"></span></a><br>
								<a href="#iprm_main_table" id="iprm_sort_DATE_asc"><span class="dashicons dashicons-arrow-down"></span></a>
								</div>
							</th>
							<th>
								RATING
								<div id="iprm_author_controls" class="iprm_sort_control">
								<a href="#iprm_main_table" id="iprm_sort_RATING_asc"><span class="dashicons dashicons-arrow-up"></span></a><br>
								<a href="#iprm_main_table" id="iprm_sort_RATING_asc"><span class="dashicons dashicons-arrow-down"></span></a>
								</div>
							</th>
							<th>
								AUTHOR
								<div id="iprm_AUTHOR_controls" class="iprm_sort_control">
								<a href="#iprm_main_table" id="iprm_sort_AUTHOR_asc"><span class="dashicons dashicons-arrow-up"></span></a><br>
								<a href="#iprm_main_table" id="iprm_sort_AUTHOR_asc"><span class="dashicons dashicons-arrow-down"></span></a>
								</div>
							</th>
							<th>
								TITLE
								<div id="iprm_TITLE_controls" class="iprm_sort_control">
								<a href="#iprm_main_table" id="iprm_sort_TITLE_asc"><span class="dashicons dashicons-arrow-up"></span></a><br>
								<a href="#iprm_main_table" id="iprm_sort_TITLE_asc"><span class="dashicons dashicons-arrow-down"></span></a>
								</div>
							</th>
							<th>
								REVIEW
								<div id="iprm_REVIEW_controls" class="iprm_sort_control">
								<a href="#iprm_main_table" id="iprm_sort_review_asc"><span class="dashicons dashicons-arrow-up"></span></a><br>
								<a href="#iprm_main_table" id="iprm_sort_review_desc"><span class="dashicons dashicons-arrow-down"></span></a>
								</div>
							</th>
						</tr>				
						<!-- REVIEWS -->
			<?php
			if ( count( $this->reviews ) > 0 ) {
				foreach ( $this->reviews as $review ) {
					$review_number++;
					$rating_total  += $review['rating'];
					$date           = date_create( $review['review_date'] );
					$date           = date_format( $date, 'Y-m-d' );
					$review_country = $review['country'];
					if ( strlen( $review_country ) === 2 ) {
						$code = $review_country;
					} else {
						$code = iprm_get_country_data( '', $review_country );
					}
					$flag_image = 'images/flags/' . $code . '.png';
					$flag_td    = '<img src="' . plugins_url( $flag_image, dirname( __FILE__ ) ) . '" alt="' . $review_country . '" title="' . $review_country . '" />';
					echo '<tr>';
					echo "<td class='flag'>" . $flag_td . '</td>';
					echo '<td>' . $review_country . '</td>';
					echo '<td>' . $date . '</td>';
					echo '<td>' . $review['rating'] . '</td>';
					echo '<td>' . $review['name'] . '</td>';
					echo '<td>' . $review['title'] . '</td>';
					echo '<td>' . $review['content'] . '</td>';
					echo '</tr>';
				}
			} else {
				echo '<p>No reviews found.</p>';
			}
			?>
					</table>	
				</div>
			<?php
			/* SEND OUTPUT */
			return ob_get_clean();
		}
		return false;
	}
	/* END DISPLAY REVIEW FUNCTION */

	function get_itunes_feed_contents() {
		$this->get_itunes_metadata( $this->itunes_url );

		$new_reviews  = array();
		$new_settings = array();
		/* GET ARRAY OF ALL COUNTRY CODES AND COUNTRY NAMES */
		$country_codes = iprm_get_country_data( '', '' );
		if ( '' !== $country_codes ) {
			$shuffle_keys = array_keys( $country_codes );
			shuffle( $shuffle_keys );
			$temp_array = array();
			foreach ( $shuffle_keys as $key ) {
				$temp_array[ $key ] = $country_codes[ $key ];
			}
			$country_codes = $temp_array;
		}
		/* CHECKS TO MAKE SURE ITUNES PODCAST URL IS DEFINED */

		if ( isset( $this->itunes_id ) ) {
			$urls_to_crawl = array();
			/* CHECK THROUGH THE REVIEW FEEDS FOR EVERY COUNTRY */
			foreach ( $country_codes as $item ) {
				$country_code            = $item['code'];
				$url_xml                 = 'https://itunes.apple.com/' . $country_code . '/rss/customerreviews/id=' . $this->itunes_id . '/xml';
				$urls_to_crawl[]         = $url_xml;
				$itunes_json1            = json_encode( wp_remote_get( $url_xml ) );
				$data1                   = json_decode( $itunes_json1, true );
				$feed_body1              = $data1['body'];
				$first_review_page_url   = iprm_get_contents_inside_tag( $feed_body1, '<link rel="first" href="', '"/>' );
				$last_review_page_url    = iprm_get_contents_inside_tag( $feed_body1, '<link rel="last" href="', '"/>' );
				$current_review_page_url = iprm_get_contents_inside_tag( $feed_body1, '<link rel="self" href="', '"/>' );
				$last_review_page_url    = trim( $last_review_page_url );
				$first_review_page_url   = trim( $first_review_page_url );

				if ( strlen( $first_review_page_url ) !== 0 ) {
					$first_page = iprm_get_contents_inside_tag( $first_review_page_url, '/page=', '/id' );
				} else {
					$first_page = 1;
				}

				$country_code_on_url = iprm_get_contents_inside_tag( $last_review_page_url, '.com/', '/rss' );
				/* NOTE: WILL GIVE US LINKS AS LAST PAGE, THIS ONLY CONSIDERS LAST PAGE IF IT IS IN THE COUNTRY WE ARE INDEXING */
				if ( ( 0 !== strlen( $last_review_page_url ) ) && ( $country_code_on_url === $country_code ) ) {
					$last_page = iprm_get_contents_inside_tag( $last_review_page_url, '/page=', '/id' );
				} else {
					$last_page = 1;
				}

				$current_entry = iprm_get_contents_inside_tag( $feed_body1, '<entry>', '</entry>' );

				/* ONLY CRAWL IF THERE IS AT LEAST ONE REVIEW */
				if ( 'EMPTYSTR' !== $current_entry ) {
					$urls_to_crawl[] = $current_review_page_url;
				}
				if ( $first_page !== $last_page ) {
					$i = 1;
					while ( $i <= $last_page ) {
						$current_review_page_url = 'https://itunes.apple.com/' . $country_code . '/rss/customerreviews/page=' . $i . '/id=' . $this->itunes_id . '/xml';
						$urls_to_crawl[]         = $current_review_page_url;
						$i++;
					}
				}
			}
			$urls_to_crawl = array_unique( $urls_to_crawl );
			$limiter       = 0;
			foreach ( $urls_to_crawl as $url ) {
				$limiter++;
				if ( $limiter > 100 ) {
					break;
				}
				$itunes_json = json_encode( wp_remote_get( $url ) );
				$data2       = json_decode( $itunes_json, true );
				$feed_body   = $data2['body'];
				/* LOOP THROUGH THE RAW CODE */
				while ( strpos( $feed_body, '<entry>' ) !== false ) {

					/* LOOK AT CODE IN BETWEEN FIRST INSTANCE OF ENTRY TAGS */
					$opening_tag   = '<entry>';
					$closing_tag   = '</entry>';
					$pos1          = strpos( $feed_body, $opening_tag );
					$pos2          = strpos( $feed_body, $closing_tag );
					$current_entry = substr( $feed_body, ( $pos1 + strlen( $opening_tag ) ), ( $pos2 - $pos1 - strlen( $opening_tag ) ) );

					/* GET REVIEW URL AND REVIEW URL COUNTRY CODE */
					$review_url              = iprm_get_contents_inside_tag( $current_entry, '<uri>', '</uri>' );
					$review_url_country_code = substr( $review_url, ( strpos( $review_url, 'reviews' ) - 3 ), 2 );
					$name                    = iprm_get_contents_inside_tag( $current_entry, '<name>', '</name>' );

					/* ADD NEW REVIEW TO REVIEW ARRAY */
					if ( '' !== $current_entry && '' !== $name ) {
						$new_review = array(
							'country'     => iprm_get_country_data( $review_url_country_code, '' ),
							'review_date' => iprm_get_contents_inside_tag( $current_entry, '<updated>', '</updated>' ),
							'rating'      => iprm_get_contents_inside_tag( $current_entry, '<im:rating>', '</im:rating>' ),
							'name'        => iprm_get_contents_inside_tag( $current_entry, '<name>', '</name>' ),
							'title'       => iprm_get_contents_inside_tag( $current_entry, '<title>', '</title>' ),
							'content'     => iprm_get_contents_inside_tag( $current_entry, '<content type="text">', '</content>' ),
						);

						/* CHECK TO MAKE SURE THERE IS A RATING AND NAME BEFORE ADDING REVIEW TO ARRAY */
						if ( ( '' === $new_review['rating'] ) || ( '' === $new_review['name'] ) || ( 'EMPTYSTR' === $new_review['name'] ) ) {
						} else {
							array_push( $new_reviews, $new_review );
						}
					}
					/* REMOVE CODE AFTER FIRST INSTANCE OF ENTRY TAGS, SO THE NEXT LOOP ITERATION STARTS WITH THE NEXT INSTANCE OF ENTRY TAGS */
					$feed_body = substr( $feed_body, ( $pos2 + strlen( $closing_tag ) ) );
				}
			}
			/* DE-DUPE NEW REVIEWS */
			$new_reviews = iprm_remove_duplicates_from_review_array( $new_reviews );

			/* ADD CACHED REVIEWS TO NEW REVIEWS */
			if ( ! is_array( $this->reviews ) ) {
				$this->reviews = array();
			}
			$this->reviews = array_merge( $this->reviews, $new_reviews );

			/* SORT REVIEWS ARRAY BY DATE */

			foreach ( $this->reviews as $key => $row ) {
				/* TRIM ARRAY DATE */
				if ( stripos( $row['review_date'], 'T' ) ) {
					$trimmed_review_date                  = substr( $row['review_date'], 0, stripos( $row['review_date'], 'T' ) );
					$this->reviews[ $key ]['review_date'] = $trimmed_review_date;
				}
				if ( '' === $row['review_date'] ) {
					unset( $this->reviews[ $row ] );
				}
				$review_date[ $key ]    = $row['review_date'];
				$review_country[ $key ] = $row['country'];
				$review_rating[ $key ]  = $row['rating'];
				$review_name[ $key ]    = $row['name'];
				$review_title[ $key ]   = $row['title'];
				$review_content[ $key ] = $row['content'];
			}
			//array_multisort( $review_date, SORT_DESC, $review_name, SORT_ASC, $this->reviews );

			/* REMOVE DUPLICATES FROM COMBINED REVIEW ARRAY */
			$this->reviews = iprm_remove_duplicates_from_review_array( $this->reviews );

			/* ADD TIME AND REVIEW COUNT TO REVIEW CACHE HISTORY */
			$review_count = count( $this->reviews );
			$current_time = current_time( 'mysql' );
			if ( ! is_array( $this->review_cache_history ) ) {
				$this->review_cache_history = array();
			}
			array_push(
				$this->review_cache_history,
				array(
					'time'  => $current_time,
					'count' => $review_count,
				)
			);

			/* REPLACE OLD REVIEW CACHE HISTORY WITH NEW REVIEW CACHE HISTORY */

			$serial_str = serialize( $this->review_cache_history );
			$db_success = iprm_update_option( "iprm_review_cache_history$this->itunes_id", $serial_str );

			if ( ! $db_success ) {
				echo 'problem writing history cache';
			}

			$serial_str = serialize( $this->reviews );
			/* REPLACE OLD CACHED REVIEWS WITH NEW CACHED REVIEWS */
			$file       = WP_PLUGIN_DIR . "/itunes-podcast-review-manager/cache/cache_$this->itunes_id.cache";
			$file_write = file_put_contents( $file, $serial_str );

			if ( false === $file_write ) {
				echo 'problem writing review cache file';
			}

			/* RETURN COMBINED REVIEW ARRAY */
			return $this->reviews;
		} else {
			//echo "invalid itunes url";
		}
	}
	/* END DISPLAY REVIEWS FUNCTION*/
}  /* END CLASS DEFINITION */
