<?php
/*
 * @package hype-social-buffer
 */

require_once( 'lib/helpers.php' );
require_once( 'hsb_log.php' );
if ( ! class_exists( 'HsbCore' ) ) {

	/*
	 * class HsbCore
	 */

	class HsbCore {

		public $accessToken;

		public function __construct() {


			if ( function_exists( 'w3tc_pgcache_flush' ) ) {
				w3tc_pgcache_flush();
				w3tc_dbcache_flush();
				w3tc_minify_flush();
				w3tc_objectcache_flush();
				$cache = ' and W3TC Caches cleared';
			}
			$this->accessToken = get_option( "hsb_opt_access_token" );
			add_action( 'init', array( $this, 'hsb_buffer_my_post' ) );
		}

		/*
		 * initialize buffer and trigger functions to check if any posts to send to buffer
		 *
		 * @return
		 */
		public function hsb_buffer_my_post() {

			//add new image size that fills requirements for certian services like linkedin and twitter
			add_image_size( 'buffer_image', 640, 640, true );

			//check last post time against set interval and span and if buffer pause is not set
			$hsb_disable_buffer = get_option( 'hsb_disable_buffer' );

			if ( $this->hsb_opt_update_time() && ! $hsb_disable_buffer ) {

				update_option( 'hsb_opt_last_update', time() );
				$this->hsb_opt_buffer_my_post();
				$ready = false;
			}
		}


		public function hsb_currentPageURL() {

			if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
				$serverrequri = $_SERVER['PHP_SELF'];
			}
			else {
				$serverrequri = $_SERVER['REQUEST_URI'];
			}
			$s        = empty( $_SERVER["HTTPS"] ) ? '' : ( $_SERVER["HTTPS"] == "on" ) ? "s" : "";
			$protocol = self::hsb_strleft( strtolower( $_SERVER["SERVER_PROTOCOL"] ), "/" ) . $s;
			$port     = ( $_SERVER["SERVER_PORT"] == "80" ) ? "" : ( ":" . $_SERVER["SERVER_PORT"] );

			return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $serverrequri;
		}

		public function hsb_strleft( $s1, $s2 ) {
			return substr( $s1, 0, strpos( $s1, $s2 ) );
		}

		//get random post and post
		public function hsb_opt_buffer_my_post() {
			return $this->hsb_generate_query();
		}

		public function hsb_generate_query( $can_requery = true ) {

			global $wpdb;
			$rtrn_msg           = "";
			$omitCats           = get_option( 'hsb_opt_omit_cats' );
			$omitCustomCats     = get_option( 'hsb_opt_omit_custom_cats' );
			$maxAgeLimit        = get_option( 'hsb_opt_max_age_limit' );
			$ageLimit           = get_option( 'hsb_opt_age_limit' );
			$exposts            = get_option( 'hsb_opt_excluded_post' );
			$exposts            = preg_replace( '/,,+/', ',', $exposts );
			$hsb_opt_post_type  = get_option( 'hsb_opt_post_type' );
			$hsb_opt_no_of_post = get_option( 'hsb_opt_no_of_post' );

			$hsb_opt_posted_posts = array();
			$hsb_opt_posted_posts = get_option( 'hsb_opt_posted_posts' );
			//$hsb_opt_posted_posts = 0; // for debug
			if ( ! $hsb_opt_posted_posts ) {
				$hsb_opt_posted_posts = array();
			}

			if ( $hsb_opt_posted_posts != null ) {
				$already_posted = implode( ",", $hsb_opt_posted_posts );
			}
			else {
				$already_posted = "";
			}

			if ( substr( $exposts, 0, 1 ) == "," ) {
				$exposts = substr( $exposts, 1, strlen( $exposts ) );
			}
			if ( substr( $exposts, - 1, 1 ) == "," ) {
				$exposts = substr( $exposts, 0, strlen( $exposts ) - 1 );
			}

			if ( ! ( isset( $ageLimit ) && is_numeric( $ageLimit ) ) ) {
				$ageLimit = hsb_opt_AGE_LIMIT;
			}

			if ( ! ( isset( $maxAgeLimit ) && is_numeric( $maxAgeLimit ) ) ) {
				$maxAgeLimit = hsb_opt_MAX_AGE_LIMIT;
			}
			if ( ! isset( $omitCats ) ) {
				$omitCats = hsb_opt_OMIT_CATS;
			}
			if ( ! isset( $omitCustomCats ) ) {
				$omitCustomCats = hsb_opt_OMIT_CUSTOM_CATS;
			}

			if ( $hsb_opt_no_of_post <= 0 ) {
				$hsb_opt_no_of_post = 1;
			}

			if ( $hsb_opt_no_of_post > 10 ) {
				$hsb_opt_no_of_post = 10;
			}

			if ( $hsb_opt_post_type != 'both' ) {
				if ( $hsb_opt_post_type == 'post' ) {
					$post_type = "post_type NOT IN('page','attachment','revision', 'nav_menu_item') AND ";
				}
				elseif ( $hsb_opt_post_type == 'page' ) {

					$post_type = "post_type  IN ('page') AND ";
				}
				else {
					$custom_posts = hsb_get_custom_posts( 'objects' );
					if ( ! empty( $custom_posts ) ) {
						//get post taxonomies
						$post_taxonomies = hsb_get_post_taxonomies( $custom_posts, 'object' );
						//get post name label array $post_labels
						$post_labels = hsb_get_post_labels( $custom_posts, 'object' );
						//get post names array
						$post_names = array_keys( $post_labels );
						//get post names from posts that have taxonomies
						$filtered_post_names = array_keys( $post_taxonomies );
						$customPostNames     = implode( ',', array_map( 'hsb_add_quotes', $filtered_post_names ) );
						$postnames           = '';
					}
					if ( ! empty ( $customPostNames ) ) {
						$postnames = $customPostNames . ',' . '\'post\'';
					}
					else {
						$postnames = "'post'";
					}
					$post_type = "post_type NOT IN(" . $postnames . ",'attachment','revision', 'nav_menu_item') AND ";
				}

			}
			else {
				$post_type = "post_type NOT IN('attachment','revision', 'nav_menu_item') AND ";
			}

			$sql
				= "SELECT ID,POST_TITLE
		            FROM $wpdb->posts
		            WHERE $post_type post_status = 'publish' ";

			if ( is_numeric( $ageLimit ) ) {
				if ( $ageLimit > 0 ) {
					$sql = $sql . " AND post_date <= curdate( ) - INTERVAL " . $ageLimit . " day";
				}
			}

			if ( $maxAgeLimit != 0 ) {
				$sql = $sql . " AND post_date >= curdate( ) - INTERVAL " . $maxAgeLimit . " day";
			}

			if ( isset( $exposts ) ) {
				if ( trim( $exposts ) != '' ) {
					$sql = $sql . " AND ID Not IN (" . $exposts . ") ";
				}
			}

			if ( isset( $already_posted ) ) {
				if ( trim( $already_posted ) != "" ) {
					$sql = $sql . " AND ID Not IN (" . $already_posted . ") ";
				}
			}

			//
			//if post is of type page //no categories
			if ( $hsb_opt_post_type == 'page' ) {
				$sql = $sql . " AND (ID NOT IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id))";
			} //if post is of type both // page & post yes and no categories
			elseif ( $hsb_opt_post_type == 'both' ) {

				if ( $omitCats != '' && $omitCustomCats != '' ) {
					$sql = $sql . " AND  (ID IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.term_id IN (" . $omitCats . ',' . $omitCustomCats . ")))";
				}
				elseif ( $omitCats != '' ) {
					$sql = $sql . " AND (ID  IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.term_id IN (" . $omitCats . ")))";
				}
				elseif ( $omitCustomCats != '' ) {
					$sql = $sql . " AND (ID IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.term_id IN (" . $omitCustomCats . ")))";
				}
				else {
					$sql = $sql . " AND (ID NOT IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id))";
				}

			} //if post is of type post
			else {

				if ( $omitCats != '' && $omitCustomCats != '' ) {
					$sql = $sql . " AND  (ID IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.term_id IN (" . $omitCats . ',' . $omitCustomCats . ")))";
				}
				elseif ( $omitCats != '' ) {
					$sql = $sql . " AND (ID  IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.term_id IN (" . $omitCats . ")))";

				}
				elseif ( $omitCustomCats != '' ) {
					$sql = $sql . " AND (ID IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.term_id IN (" . $omitCustomCats . ")))";

				}
				else {
					$sql = $sql . " AND (ID NOT IN (SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id))";

				}
			}

			$sql = $sql . "
		            ORDER BY RAND()
		            LIMIT $hsb_opt_no_of_post ";

			$oldest_post = $wpdb->get_results( $sql );

			if ( $oldest_post == null ) {
				if ( $can_requery ) {
					$hsb_opt_posted_posts = array();
					update_option( 'hsb_opt_posted_posts', $hsb_opt_posted_posts );

					return $this->hsb_generate_query( false );
				}
				else {
					return __( "No post found to post. Please check your settings and try again. <br/> For Post Type <strong>post</strong> please make sure to select at least one category.", 'HYPESocialBuffer' );
				}
			}

			if ( isset( $oldest_post ) and ! get_option('hsb_disable_buffer') ) {
				$ret = '';
				foreach ( $oldest_post as $k => $odp ) {
					$buffer_repeat = get_post_meta( $odp->ID, 'hsb_buffer_repeat', true );
					if ( $buffer_repeat && $buffer_repeat != 0 && ( $buffer_repeat - 1 ) != 0 ) {
						$buffer_repeat = $buffer_repeat - 1;
					}
					else {
						$buffer_repeat = 0;
						array_push( $hsb_opt_posted_posts, $odp->ID );
					}
					update_post_meta( $odp->ID, 'hsb_buffer_repeat', $buffer_repeat );
					$ret .= 'Status Title: (' . $odp->POST_TITLE . ')' . $this->hsb_publish( $odp->ID ) . '<br/>';
				}

				if ( function_exists( 'w3tc_pgcache_flush' ) ) {
					w3tc_pgcache_flush();
					w3tc_dbcache_flush();
					w3tc_minify_flush();
					w3tc_objectcache_flush();
					$cache = ' and W3TC Caches cleared';
				}

				update_option( 'hsb_opt_posted_posts', $hsb_opt_posted_posts );

				return $ret;
			}

			return $rtrn_msg;
		}


		//send request to passed url and return the response
		public function hsb_send_request( $url, $method = 'GET', $data = '', $auth_user = '', $auth_pass = '' ) {
			$ch = curl_init( $url );
			if ( strtoupper( $method ) == "POST" ) {
				curl_sehsbt( $ch, CURLOPT_POST, 1 );
				curl_sehsbt( $ch, CURLOPT_POSTFIELDS, $data );
			}
			if ( ini_get( 'open_basedir' ) == '' && ini_get( 'safe_mode' ) == 'Off' ) {
				curl_sehsbt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			}
			curl_sehsbt( $ch, CURLOPT_HEADER, 0 );
			curl_sehsbt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			if ( $auth_user != '' && $auth_pass != '' ) {
				curl_sehsbt( $ch, CURLOPT_USERPWD, "{$auth_user}:{$auth_pass}" );
			}
			$response = curl_exec( $ch );
			$httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			curl_close( $ch );
			if ( $httpcode != 200 ) {
				return $httpcode;
			}

			return $response;
		}

		//check time and update the last post time
		public function hsb_opt_update_time() {
			return $this->hsb_to_update();
		}

		public function hsb_to_update() {
			global $wpdb;
			$ret = 0;
			//prevention from caching
			$last     = $wpdb->get_var( "select SQL_NO_CACHE option_value from $wpdb->options where option_name = 'hsb_opt_last_update';" );
			$interval = get_option( 'hsb_opt_interval' );

			if ( ( trim( $last ) == '' ) || ! ( isset( $last ) ) ) {
				$last = 0;
			}

			if ( ! ( isset( $interval ) ) ) {
				$interval = hsb_opt_INTERVAL;
			}
			else if ( ! ( is_numeric( $interval ) ) ) {
				$interval = hsb_opt_INTERVAL;
			}

			$interval = $interval * 60 * 60;

			if ( is_numeric( $last ) ) {
				$ret = ( ( time() - $last ) > ( $interval ) );
			}
			else {
				$ret = 0;
			}

			return $ret;
		}

		public function hsb_get_settings() {
			global $hsb_defaults;

			$settings = $hsb_defaults;

			$wordpress_settings = get_option( 'hsb_settings' );
			if ( $wordpress_settings ) {
				foreach ( $wordpress_settings as $key => $value ) {
					$settings[ $key ] = $value;
				}
			}

			return $settings;
		}

		public function hsb_save_settings( $settings ) {
			update_option( 'hsb_settings', $settings );
		}

		public function hsb_reset_settings() {
			delete_option( 'hsb_settings' );
			update_option( 'hsb_disable_buffer', false );
			update_option( 'hsb_enable_log', '' );
			update_option( 'hsb_opt_add_text', '' );
			update_option( 'hsb_opt_add_text_at', 'beginning' );
			update_option( 'hsb_opt_age_limit', 30 );
			update_option( 'hsb_opt_include_link', 'no' );
			update_option( 'hsb_opt_interval', 4 );
			delete_option( 'hsb_opt_last_update' );
			update_option( 'hsb_opt_max_age_limit', 60 );
			update_option( 'hsb_opt_omit_cats', '' );
			update_option( 'hsb_opt_omit_custom_cats', '' );
			update_option( 'hsb_opt_post_type', 'title' );
			delete_option( 'hsb_opt_posted_posts' );
			update_option( 'hsb_opt_admin_url', '' );
		}

		/*
		 * Called when any Page, Post or Custom Post Type is published or updated, live or for a scheduled post
		 *
		 * @param int $postID Post ID
		 */
		public function hsb_publish( $postID, $isPublishAction = false, $schedule = false, $now = false ) {

			$post                  = get_post( $postID );
			$content               = "";
			$to_short_url          = true;



			//hashtags newcontent
			$newcontent = "";

			$meta = get_post_meta( $postID, 'hype-social-buffer', true ); // Get post meta
			update_post_meta( $post->ID, "hsb_buffer_image_skip", 1 );
			$hsb_buffer_image_skip = get_post_meta( $postID, 'hsb_buffer_image_skip', true ); // Get post meta

			$defaults              = get_option( 'hype-social-buffer' ); // Get settings
			// Get post
			$post = get_post( $postID );

			//twit_update_status($message)
			// 1. Get post categories if any exist
			$catNames = '';
			$cats     = wp_get_post_categories( $postID, array( 'fields' => 'ids' ) );
			if ( is_array( $cats ) AND count( $cats ) > 0 ) {
				foreach ( $cats as $key => $catID ) {
					$cat     = get_category( $catID );
					$catName = strtolower( str_replace( ' ', '', $cat->name ) );
					$catNames .= '#' . $catName . ' ';
				}
			}

			// 2. Get author
			$author = get_user_by( 'id', $post->post_author );

			// 3. Check if we have an excerpt. If we don't (i.e. it's a Page or CPT with no excerpt functionality), we need
			// to create an excerpt
			if ( empty( $post->post_excerpt ) ) {
				$excerpt = wp_trim_words( strip_shortcodes( $post->post_content ) );
			}
			else {
				$excerpt = $post->post_excerpt;
			}
			$permalink = get_permalink( $postID );
			$shorturl = $permalink;
			// 4. Parse text and description
			$params['text'] = get_option( 'hsb_opt_post_format' );
			$params['text'] = str_replace( '{sitename}', get_bloginfo( 'name' ), $params['text'] );
			$params['text'] = str_replace( '{title}', $post->post_title, $params['text'] );
			$params['text'] = str_replace( '{excerpt}', $excerpt, $params['text'] );
			$params['text'] = str_replace( '{category}', trim( $catNames ), $params['text'] );
			$params['text'] = str_replace( '{date}', date( 'dS F Y', strtotime( $post->post_date ) ), $params['text'] );
			$params['text'] = str_replace( '{url}', $shorturl, $params['text'] );
			$params['text'] = str_replace( '{author}', $author->display_name, $params['text'] );

			$acntids = get_option( "hsb_opt_acnt_id" );
			if ( isset( $acntids ) ) {
				$arracntid = explode( ",", $acntids );
				foreach ( $arracntid as $profid ) {
					$params['profile_ids'][] = $profid;
				}
			}
			// 7. Send to Buffer and store response
			$result = $this->hsb_request( 'updates/create.json', 'post', $params );
			update_post_meta( $postID, 'hype-social-buffer' . '-log', $result );
		}

		/*
		 * Sends a GET request to the Buffer API
		 *
		 * @param string $this   ->accessToken Access Token
		 * @param string $cmd    Command
		 * @param string $method Method (get|post)
		 * @param array  $params Parameters (optional)
		 *
		 * @return mixed JSON decoded object or error string
		 */
		public function hsb_request( $cmd, $method = 'get', $params = array() ) {

			$result = '';
			if ( $this->accessToken == '' ) {
				return 'Invalid access token';
			}
			HSB_DEBUG( 'request is: ' . print_r( $params, true ) );
			// Send request
			switch ( $method ) {
				case 'get':
					$result = wp_remote_get( 'https://api.bufferapp.com/1/' . $cmd . '?access_token=' . $this->accessToken, array(
						'body'      => $params,
						'sslverify' => false
					) );
					break;
				case 'post':
					$result = wp_remote_post( 'https://api.bufferapp.com/1/' . $cmd . '?access_token=' . $this->accessToken, array(
						'body'      => $params,
						'sslverify' => false
					) );
					break;
			}

			HSB_DEBUG( 'result is: ' . print_r( $result, true ) );
			$log = new log();
			// Check the request is valid
			if ( is_wp_error( $result ) ) {
				return $result;
			}
			if ( $result['response']['code'] != 200 ) {
				$log->general("params: " . print_r($params,TRUE) );
				$log->general("code != 200: " . print_r($result,TRUE) );//use for general errors
				return $result;
			}
			else
			{
				return json_decode( $result['body'] );
				$log->general("code != 200 " . print_r($result,TRUE) );
			}

		}
	}
}
?>