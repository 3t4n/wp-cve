<?php
/**
 * Podcast player display class.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 */

namespace Podcast_Player\Frontend\Inc;

use Podcast_Player\Frontend\Inc\Render;
use Podcast_Player\Frontend\Inc\Instance_Counter;
use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Core\Singleton;

/**
 * Display podcast player instance.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 * @author     vedathemes <contact@vedathemes.com>
 */
class Feed extends Singleton {
	/**
	 * Holds podcast episodes script data for each Podcast instance.
	 *
	 * @since  1.2.0
	 * @access private
	 * @var    array
	 */
	private $script_data = array();

	/**
	 * Display current podcast player.
	 *
	 * @since  1.0.0
	 *
	 * @param array $args Podcast display args.
	 */
	public function display_podcast( $args ) {
		
		// Validate podcast feed URL.
		$args['url'] = Get_Fn::get_valid_feed_url( $args['url'] );
		if ( is_wp_error( $args['url'] ) ) {
			// Display error and return if proper feed URL is not provided.
			$this->display_error_message( $args['url'], $args );
			return;
		}
		
		// Get feed data modification params.
		$mod_data = $this->get_feed_mods( $args );

		// Let's add fixed item in args to be send to front-end script.
		$args['fixed'] = isset( $mod_data['fixed'] ) ? $mod_data['fixed'] : $args['fixed'];

		// Required item data fields.
		$data_fields = $this->get_required_data_fields( $args );

		// Get feed data.
		$feed = Get_Fn::get_feed_data( $args['url'], $mod_data, $data_fields );

		// Display error and return if cannot get required feed data.
		if ( is_wp_error( $feed ) ) {
			$this->display_error_message( $feed, $args );
			return;
		}

		// Define feed variables.
		$is_title    = isset( $args['hide-title'] ) && $args['hide-title'] ? false : true;
		$title       = $is_title ? $feed['title'] : '';
		$description = $args['description'] ? $args['description'] : $feed['desc'];
		$link        = $feed['link'];
		$feed_items  = $feed['items'];
		$totalitems  = $feed['total'];
		$maxitems    = count( $feed_items );

		// Create current podcast player's instance.
		$inst_class = Instance_Counter::get_instance();
		$number     = $inst_class->get();

		// Add image url args to podcast player args.
		list( $imgurl, $oricov, $imgset, $ratio ) = $this->get_cover_image( $args, $feed );
		$args['imgurl']   = $imgurl;
		$args['imgset']   = $imgset;
		$args['oricov']   = $oricov;
		$args['imgratio'] = $ratio;

		// Add total items in display args.
		$args['total'] = $totalitems;

		// Add all podcast episode categories to display args.
		$args['categories'] = isset( $feed['categories'] ) ? $feed['categories'] : array();

		// Prepare feed items for further use.
		$feed_items = $this->prepare_feed_items( $feed_items, $number, 0, $args );

		list( $fitems, $floaded ) = apply_filters( 'podcast_player_data_protect', array( $feed_items, $maxitems ) );

		// Add script data for current podcast instance.
		$this->add_podcast_script_data( $floaded, $totalitems, $args['url'], $args, $number, $title, $fitems );
		$feed_items = array_values( $feed_items );

		$this->render_podcast_player(
			array(
				$title,
				$description,
				$link,
				$feed_items,
				$number,
				$args,
				$this->script_data,
			)
		);
	}

	/**
	 * Display error message from WP Error object.
	 *
	 * @since 6.4.0
	 *
	 * @param object $error WP Error object.
	 * @param array  $args Podcast display args.
	 */
	private function display_error_message( $error, $args = array() ) {
		if ( is_wp_error( $error ) ) {
			if ( is_admin() || current_user_can( 'manage_options' ) ) {
				echo '<p><strong>' . esc_html__( 'RSS Error:', 'podcast-player' ) . '</strong> ' . esc_html( $error->get_error_message() ) . '</p>';
			} else {
				echo apply_filters( 'podcast_player_feed_error_msg', '', $error, $args );
			}
		}
	}

	/**
	 * Get Podcast feed mods.
	 *
	 * @since 6.4.0
	 *
	 * @param array $args Podcast Display args.
	 */
	private function get_feed_mods( $args ) {
		$offset = ( isset( $args['offset'] ) && $args['offset'] ) ? absint( $args['offset'] ) : 0;
		// Feed data modification params.
		$mod_data = array(
			'start'    => $offset,
			'end'      => 2 * $args['number'],
			'filterby' => $args['filterby'],
			'sortby'   => $args['sortby'],
			'fixed'    => $args['fixed'],
		);

		/**
		 * Filter Feed data modification params.
		 *
		 * @since 3.3.0
		 *
		 * @param array  $mods Feed episode filter args.
		 * @param array  $args Podcast display args.
		 */
		return apply_filters( 'podcast_player_feed_mods', $mod_data, $args );
	}

	/**
	 * Display current podcast player.
	 *
	 * @since  1.0.0
	 *
	 * @param array $args Podcast display args.
	 */
	private function get_required_data_fields( $args ) {
		$default_fields = array( 'title', 'date', 'link', 'src', 'mediatype', 'duration', 'categories' );
		$conditional    = array(
			'description' => 'hide-content',
			'author'      => 'hide-author',
			'featured'    => 'hide-featured',
			'featured_id' => 'hide-featured',
		);
		$conditional    = array_keys(
			array_filter(
				$conditional,
				function( $field ) use ( $args ) {
					return ! isset( $args[ $field ] ) || ! $args[ $field ];
				}
			)
		);
		return array_merge( $default_fields, $conditional );
	}

	/**
	 * Get podcast cover image url and markup.
	 *
	 * @since 1.0.0
	 *
	 * @param arr $args settings for current podcast player instance.
	 * @param arr $feed Feed information array.
	 * @return array
	 */
	private function get_cover_image( $args, $feed ) {
		$oricov = wp_strip_all_tags( $feed['image'] );
		$ori_id = isset( $feed['cover_id'] ) ? $feed['cover_id'] : 0;
		$url    = ! empty( $args['img_url'] ) ? wp_strip_all_tags( $args['img_url'] ) : '';
		$id     = ! empty( $args['image'] ) ? absint( $args['image'] ) : '';
		$srcset = '';
		$ratio  = 1;

		// Get Image id from original feed image.
		if ( ! $id && ! $url && $ori_id ) {
			$id = $ori_id;
		}

		if ( $id ) {
			$imgdata = Get_Fn::get_image_src_set( $id, 'medium_large' );
			if ( $imgdata['src'] ) {
				$url    = $imgdata['src'];
				$srcset = $imgdata['srcset'];
				$ratio  = $imgdata['ratio'];
			}
		}

		if ( ! $url && $oricov ) {
			$url = $oricov;
		}

		return array( $url, $oricov, $srcset, $ratio );
	}

	/**
	 * Prepare feed episodes for current podcast player instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $items       Array of podcast episodes objects.
	 * @param int   $counter     Current podcast player instance number.
	 * @param int   $items_count Item number counter.
	 * @param int   $args        Additional Feed Args.
	 * @return array
	 */
	private function prepare_feed_items( $items, $counter, $items_count, $args = array() ) {
		$feed_items = array();
		foreach ( $items as $key => $item ) {
			$items_count++;
			$id = $counter . '-' . $items_count;

			$item['key'] = esc_html( $key );
			if ( isset( $item['featured'] ) ) {
				list( $featured, $srcset, $ratio ) = $this->get_item_featured( $item, $args );

				$item['featured'] = esc_attr( esc_url( $featured ) );
				$item['fset']     = esc_attr( $srcset );
				$item['fratio'] = floatval( $ratio );
			}

			$feed_items[ 'ppe-' . $id ] = $item;
		}

		return apply_filters( 'podcast_player_feed_items', $feed_items, 'feed', $args );
	}

	/**
	 * Get proper featured image for the item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item Feed item fields array.
	 * @param int   $args Additional Feed Args.
	 * @return array
	 */
	private function get_item_featured( $item, $args = array() ) {
		$no_featured = $args['hide-featured'];

		$featured = '';
		$srcset   = '';
		$ratio    = 1;
		if ( ! $no_featured ) {
			$featured = $item['featured'];
			if ( $args['imgurl'] && ( ! $featured || $args['oricov'] === $featured ) ) {
				$featured = $args['imgurl'];
				$srcset   = $args['imgset'];
				$ratio  = isset( $args['imgratio'] ) ? $args['imgratio'] : 1;
			}
			if ( isset( $item['featured_id'] ) && $item['featured_id'] ) {
				$imgdata = Get_Fn::get_image_src_set( $item['featured_id'], 'medium_large' );
				if ( $imgdata['src'] ) {
					$featured = $imgdata['src'];
					$srcset   = $imgdata['srcset'];
					$ratio    = $imgdata['ratio']; 
				}
			}
		}
		return array( $featured, $srcset, $ratio );
	}

	/**
	 * Populate podcast player cdata.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Podcast data.
	 * @return array
	 */
	public function scripts_data( $data = array() ) {
		if ( ! isset( $data['ajax_info'] ) ) {
			$data['ajax_info'] = array(
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'podcast-player-ajax-nonce' ),
			);
		}

		$data = array_merge( $data, $this->script_data );
		return $data;
	}

	/**
	 * Add episodes data of current podcast instance to script data array.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $loaded Total episodes fetched from the feed.
	 * @param int   $total  Total number of episodes to be displayed.
	 * @param str   $url    Podcast feed url.
	 * @param array $args   Podcast settings.
	 * @param int   $counter Podcast player instance.
	 * @param str   $title Podcast title.
	 * @param array $script_data Podcast data.
	 */
	private function add_podcast_script_data( $loaded, $total, $url, $args, $counter, $title, $script_data = array() ) {
		global $wp;

		// Settings for current podcast instance.
		$ajax_args = array(
			'imgurl'  => esc_url( $args['imgurl'] ),
			'imgset'  => esc_attr( $args['imgset'] ),
			'display' => esc_html( $args['display-style'] ),
			'hddesc'  => $args['hide-content'] ? 1 : 0,
			'hdfeat'  => $args['hide-featured'] ? 1 : 0,
			'oricov'  => esc_url( $args['oricov'] ),
			'elength' => absint( $args['excerpt-length'] ),
		);

		// Converting url to a valid link.
		$url = Get_Fn::get_valid_feed_url( $url );

		// Add information to load new episodes.
		$script_data['load_info'] = array(
			'loaded'    => absint( $loaded ),
			'displayed' => absint( $args['number'] ), // Initial count.
			'offset'    => absint( $args['offset'] ),
			'maxItems'  => absint( $total ),
			'src'       => esc_html( md5( $url ) ),
			'step'      => absint( $args['number'] ),
			'sortby'    => esc_html( $args['sortby'] ),
			'filterby'  => esc_html( $args['filterby'] ),
			'fixed'     => esc_html( $args['fixed'] ),
			'args'      => $ajax_args,
		);

		// Add information to render data properly using JavaScript.
		$script_data['rdata'] = array(
			'permalink' => esc_url( home_url( add_query_arg( array(), $wp->request ) ) ),
			'fprint'    => esc_html( md5( $url ) ),
			'from'      => 'feedurl',
			'elen'      => absint( $args['excerpt-length'] ),
			'eunit'     => isset( $args['excerpt-unit'] ) ? esc_html( $args['excerpt-unit'] ) : '',
			'teaser'    => isset( $args['teaser-text'] ) ? esc_html( $args['teaser-text'] ) : '',
			'title'     => esc_html( str_replace( '&quot;', '&#8221;', $title ) ),
		);

		/**
		 * Make current podcast instance data available to front-end scripts.
		 *
		 * @since 3.3.0
		 *
		 * @param string $script_data Podcast data to be sent to front-end script.
		 * @param array  $args        Podcast display args.
		 */
		$this->script_data[ 'pp-podcast-' . $counter ] = apply_filters( 'podcast_player_instance_data', $script_data, $args );
	}

	/**
	 * Display podcast episodes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $props Podcast player display props.
	 */
	private function render_podcast_player( $props ) {
		new Render( $props );
	}

	/**
	 * Fetch podcast episodes for Ajax calls.
	 *
	 * @since 1.0.0
	 */
	public function fetch_episodes() {
		// Get sanitized values of ajax variables.
		$val = $this->get_ajax_variables();
		if ( false === $val ) {
			wp_die( -1, 403 );
		}

		list(
			$loaded, $displayed, $max_items, $instance, $sortby, $filterby, $fixed, $args, $lotsize, $term, $offset
		) = $val;

		// Get remaining episodes which are not yet loaded to front-end.
		$remaining = $max_items - $loaded;
		$maxitems  = min( $remaining, $lotsize );

		// Feed data modification params.
		$mod_data = array(
			'start'    => $loaded + $offset,
			'end'      => $maxitems,
			'filterby' => $filterby,
			'sortby'   => $sortby,
			'fixed'    => $fixed,
		);

		// Add additional filters received from args.
		$mod_data = array_merge( $mod_data, $args['filters'] );

		// Required item data fields.
		$data_fields = $this->get_required_data_fields( $args );

		// Get feed url.
		$feed_url = isset( $args['url'] ) ? $args['url'] : '';

		// Get feed data.
		$feed = Get_Fn::get_feed_data( $feed_url, $mod_data, $data_fields );
		if ( is_wp_error( $feed ) ) {
			echo wp_json_encode( array() );
			wp_die();
		}
		$items = $this->prepare_feed_items( $feed['items'], $instance, $loaded, $args );

		// Ajax output to be returened.
		$output = array(
			'loaded'   => $maxitems + $loaded,
			'episodes' => $items,
		);
		echo wp_json_encode( $output );

		wp_die();
	}

	/**
	 * Fetch podcast episodes for Ajax calls.
	 *
	 * @since 1.0.0
	 */
	public function search_episodes() {

		// Get sanitized values of ajax variables.
		$val = $this->get_ajax_variables();
		if ( false === $val ) {
			wp_die( -1, 403 );
		}

		list(
			$loaded, $displayed, $max_items, $instance, $sortby, $filterby, $fixed, $args, $lotsize, $term, $offset
		) = $val;

		// Return if search term has not beed povided.
		if ( ! $term || ! ( str_replace( ' ', '', $term ) ) ) {
			// Check if we are only searching by the categories (New category filter).
			if ( ! isset( $args['filters']['catfilter'] ) || empty( $args['filters']['catfilter'] ) ) {
				echo wp_json_encode( array() );
				wp_die();
			}
		}

		// Feed data modification params.
		$mod_data = array(
			'start'    => 0,
			'end'      => $max_items,
			'filterby' => $filterby,
			'sortby'   => $sortby,
			'fixed'    => false,
		);

		// Add additional filters received from args.
		$mod_data = array_merge( $mod_data, $args['filters'] );

		// Required item data fields.
		$data_fields = $this->get_required_data_fields( $args );

		// Get feed url.
		$feed_url = isset( $args['url'] ) ? $args['url'] : '';

		// Get feed data.
		$feed = Get_Fn::get_feed_data( $feed_url, $mod_data, $data_fields );
		if ( is_wp_error( $feed ) ) {
			echo wp_json_encode( array() );
			wp_die();
		}

		$items = $feed['items'];

		/*
		 * Get array of items where item title contains the search term.
		 * No need to directly search into displayed episodes as it is already
		 * done by javascript on front-end. However, those items
		 * must be removed from further processing. Hence a modified list
		 * of items also returned.
		 *
		 * @param $r array Search Results.
		 * @param $i array Modified list of items for further processing.
		 */
		list( $r, $i ) = $this->get_search_results( $items, $term, $displayed, $offset );

		/**
		 * Feed items search results.
		 *
		 * @since 3.3.0
		 *
		 * @param string $r     Search Results.
		 * @param array  $i     List of remaining items for further search.
		 * @param string $term  Search Term
		 */
		$results = apply_filters( 'podcast_player_search_results', $r, $i, $term );
		$count   = count( $results );
		if ( ! $count ) {
			echo wp_json_encode( array() );
			wp_die();
		}

		// Prepare feed items for further use.
		$results = $this->prepare_feed_items( $results, 's', 0, $args );

		// Ajax output to be returened.
		$output = array( 'episodes' => $results );
		echo wp_json_encode( $output );

		wp_die();
	}

	/**
	 * Fetch podcast episodes for Ajax calls.
	 *
	 * @since 1.0.0
	 */
	private function get_ajax_variables() {

		// Nounce Verification.
		$nounce = check_ajax_referer( 'podcast-player-ajax-nonce', 'security', false );
		if ( ! $nounce ) {
			return false;
		}

		// Get common variables from Ajax request.
		$loaded    = isset( $_POST['loaded'] ) ? wp_unslash( $_POST['loaded'] ) : '';
		$displayed = isset( $_POST['displayed'] ) ? wp_unslash( $_POST['displayed'] ) : '';
		$max_items = isset( $_POST['maxItems'] ) ? wp_unslash( $_POST['maxItems'] ) : '';
		$feed_url  = isset( $_POST['feedUrl'] ) ? wp_unslash( $_POST['feedUrl'] ) : '';
		$instance  = isset( $_POST['instance'] ) ? wp_unslash( $_POST['instance'] ) : '';
		$sortby    = isset( $_POST['sortby'] ) ? wp_unslash( $_POST['sortby'] ) : 'sort_date_desc';
		$filterby  = isset( $_POST['filterby'] ) ? wp_unslash( $_POST['filterby'] ) : '';
		$fixed     = isset( $_POST['fixed'] ) ? wp_unslash( $_POST['fixed'] ) : '';
		$args      = isset( $_POST['args'] ) ? wp_unslash( $_POST['args'] ) : array();
		$term      = isset( $_POST['search'] ) ? wp_unslash( $_POST['search'] ) : false;
		$lotsize   = isset( $_POST['step'] ) ? wp_unslash( $_POST['step'] ) : '';
		$offset    = isset( $_POST['offset'] ) ? wp_unslash( $_POST['offset'] ) : 0;

		// Prepare and sanitize/validate feed args array.
		// Feed URL has been encrypted using md5. Therefore, using esc_html.
		$new_args = array(
			'url'            => esc_html( $feed_url ),
			'imgurl'         => esc_url_raw( $args['imgurl'] ),
			'imgset'         => esc_attr( $args['imgset'] ),
			'display-style'  => sanitize_text_field( $args['display'] ),
			'hide-content'   => $args['hddesc'] ? 1 : 0,
			'hide-featured'  => $args['hdfeat'] ? 1 : 0,
			'oricov'         => esc_url_raw( $args['oricov'] ),
			'excerpt-length' => absint( $args['elength'] ),
			'filters'        => array(),
		);

		/**
		 * Sanitize additional args from Ajax request.
		 *
		 * @since 3.3.0
		 *
		 * @param string $new_args Args sanitzied for further use.
		 * @param array  $args     All args received from Ajax request.
		 */
		$args = apply_filters( 'podcast_player_ajax_args', $new_args, $args );

		return array(
			absint( $loaded ),
			absint( $displayed ),
			absint( $max_items ),
			absint( $instance ),
			sanitize_text_field( $sortby ),
			sanitize_text_field( $filterby ),
			sanitize_text_field( $fixed ),
			$args, // Array values alreay sanitized.
			absint( $lotsize ),
			sanitize_text_field( $term ),
			absint( $offset )
		);
	}

	/**
	 * Fetch podcast episodes for Ajax calls.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $items     Feed items to be searched.
	 * @param string $term      Search Term.
	 * @param int    $displayed Feed items already displayed.
	 * @param int    $offset    Episode offset.
	 */
	private function get_search_results( $items, $term, $displayed, $offset ) {

		// Search results are NOT case sensitive.
		$term = strtolower( $term );

		/*
		 * Directly searching term with-in episode title.
		 * No need to directly search into already displayed episodes
		 * as it is already done by javascript on front-end.
		 */
		$i       = 1;
		$results = array();
		foreach ( $items as $key => $item ) {
			$item_title = strtolower( $item['title'] );
			if ( ! $term || ! str_replace( ' ', '', $term ) || false !== strpos( $item_title, $term ) ) {
				if ( $i > ( $offset + $displayed ) ) {
					$results[ $key ] = $item;
				}
				unset( $items[ $key ] );
			}
			$i++;
		}

		// Return array of all items where title contains the search term.
		return array( $results, $items );
	}
}
