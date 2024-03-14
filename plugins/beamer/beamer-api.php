<?php

	/* ----------------------------------------------------------------------------------------------------

		BEAMER API
		Connects to the API

	---------------------------------------------------------------------------------------------------- */

	// Include metabox options and styles
	include('beamer-api-metabox.php');

	// Get the beamer ID by post ID
	function bmr_api_get_id( $id ){
		$beamer_id = get_post_meta($id, 'bmr_id', true);
		if( isset( $beamer_id ) ){
			return  $beamer_id;
		}else{
			return null;
		}
	}

	// Check if post has beamer ID
	function bmr_api_has_id( $id ){
		$check = bmr_api_get_id( $id );
		if($check != null && $check > 0){
			return true;
		}else{
			return false;
		}
	}

	// Get the API key
	function bmr_api_get_key(){
		if( bmr_get_setting('api_key') != '' ){
			return bmr_get_setting('api_key');
		}else{
			return null;
		}
	}

	// Protect fields
	function bmr_protect_fields( $protected, $meta_key ) {
		$secured = array(
			'bmr_ignore',
			'bmr_category',
			'bmr_link_text',
			'bmr_feedback',
			'bmr_reactions',
		);
	    if( in_array( $meta_key, $secured ) ) {
			return true;
	    }
		return $protected;
	}
	//add_filter( 'is_protected_meta', 'bmr_protect_fields', 10, 2 );

	function bmr_truncate( $text, $length = 100, $ending = '&hellip;', $exact = false, $considerHtml = true ) {
	    if ($considerHtml) {
	        if (strlen(utf8_decode(preg_replace( array( '/<.*?>/', '/\n/', '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i' ), array( '', '', ' ' ), $text))) <= $length) { return $text; }
	        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
	            $total_length = ( $ending === '&hellip;' ) ? 2 : strlen( utf8_decode($ending) );
	            $open_tags = array();
	            $truncate = '';
	        foreach ($lines as $line_matchings) {
	            if (!empty($line_matchings[1])) {
	                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
	                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
	                    $pos = array_search($tag_matchings[1], $open_tags);
	                    if ($pos !== false) { unset($open_tags[$pos]); }
	                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
	                    array_unshift($open_tags, strtolower($tag_matchings[1]));
	                }
	                $truncate .= $line_matchings[1];
	            }
	            $content_length = strlen(utf8_decode(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2])));
	            if ($total_length+$content_length> $length) {
	                $left = $length - $total_length;
	                $entities_length = 0;
	                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
	                    foreach ($entities[0] as $entity) {
	                        if ($entity[1]+1-$entities_length <= $left) {
	                            $left--;
	                            $entities_length += strlen(utf8_decode($entity[0]));
	                        } else { break; }
	                    }
	                }
	                $truncate .= substr($line_matchings[2], 0, $left+$entities_length); break;
	            } else {
	                $truncate .= $line_matchings[2];
	                $total_length += $content_length;
	            }
	            if($total_length >= $length) { break; }
	        }
	    } else {
	        if (strlen(utf8_decode($text)) <= $length) {
	            return $text;
	        } else {
	            $truncate = substr($text, 0, $length - strlen(utf8_decode($ending)));
	        }
	    }
	    if (!$exact) {
	        $spacepos = strrpos($truncate, ' ');
	        if (isset($spacepos)) { $truncate = substr($truncate, 0, $spacepos); }
	    }
	    $truncate .= $ending;
	    if($considerHtml) {
	        foreach ($open_tags as $tag) { $truncate .= '</' . $tag . '>'; }
	    }
	    return $truncate;
	}

	// Trim content
	function bmr_api_trim_content( $obj, $num = 160 ){
		//$result = wp_trim_words($obj, $num, '...');
		$result = bmr_truncate( wpautop( $obj ), $num, ' (...)', false, true );
		return $result;
	}

	// CALL API ---------------------------------------------------------------------------
	function bmr_api_call($post_ID, $post_after, $post_before){
		$beamer_id = bmr_api_has_id($post_ID) ? bmr_api_get_id($post_ID) : 0;
		$edit_check = array('draft', 'pending', 'publish', 'future');
		$post_type = $post_after->post_type;
		$post_type_obj = get_post_type_object( $post_type );
		// check if post type is public
		if( $post_type_obj->publicly_queryable == false OR $post_type_obj->public == false ){
			$post_type_public = false;
		}else{
			$post_type_public = true;
		}

		if( $post_type == 'post' && $post_type_public == true ){
			$type_switch = true;
		}elseif( bmr_get_setting('api_types') == true && $post_type != 'attachment'  && $post_type_public == true ){
			$type_switch = true;
		}elseif( bmr_get_setting('api_page') == true && $post_type == 'page' ){
			$type_switch = true;
		}else{
			$type_switch = false;
		}

		if( $post_after->post_status != 'auto-draft' && $type_switch == true ){

			if( $post_after->post_status == 'trash' OR $post_after->post_status == 'draft' OR $post_after->post_status == 'pending' OR bmr_get_meta( 'bmr_ignore' ) == 'ignore' ){
				// DELETE ---------------------------------------------------------------------------
				$api_key = bmr_api_get_key();
				$api_url = bmr_api_url('posts', $beamer_id);

				// JSON here
				$args = array(
					'method' => 'DELETE',
				    'headers' => array(
				        'Content-Type' => 'application/json',
				        'Beamer-Api-Key' => $api_key,
				        'User-Agent' => 'WordPress Plugin DELETE (v'.bmr_version().'/php'.phpversion().')'
				    )
				);
				$response = wp_remote_request( $api_url, $args );
				$http_code = wp_remote_retrieve_response_code( $response ); // Don't need to print the results

				// Update post meta with the Beamer custom fields
				$prefix = 'bmr_';
				$beamer_meta = array(
					$prefix.'title' => $post_after->post_title,
					$prefix.'content' => apply_filters( 'the_content', $post_after->post_content),
					$prefix.'publish' => true,
					$prefix.'linkUrl' => get_permalink($post_after->ID), //$post_after->guid,
					$prefix.'date' => $date,
					$prefix.'id' => null
				);
				foreach($beamer_meta as $key => $var){
					update_post_meta($post_ID, $key, $var);
				}

			}elseif( $post_after->post_status == 'publish' OR $post_after->post_status == 'future' ){

				// POST ---------------------------------------------------------------------------
				$api_key = bmr_api_get_key();
				$api_url = bmr_api_has_id($post_ID) ? bmr_api_url('posts', $beamer_id) : bmr_api_url('posts');

				// Set timezone
				function bmr_convertTime($t) {
					// check for negative numbers and create mods
					if($t < 0): $mod = '-'; $t = $t * -1; else: $mod = '+'; endif;
					// convert $t to seconds
				    $s = ($t * 3600);
				    // get hours and remove them from seconds
				    $h = floor($t); $s -= $h * 3600;
				    // calculate minutes and remove them from seconds
				    $m = floor($s / 60); $s -= $m * 60;
				    // format
				    if($h > 9 OR $h < -9): $hours = $h; else: $hours = str_pad($h, 2, '0', STR_PAD_LEFT); endif;
				    if($m > 9): $minutes = $m; else: $minutes = str_pad($m, 2, '0', STR_PAD_LEFT); endif;
				    // return the time (HH:MM)
				    return $mod.$hours.':'.$minutes;
				}

					function bmr_get_timezoneOffset() {
						// get the WP timezone offset
						$wp_tz = get_option('gmt_offset');
						if(!empty($wp_tz) && $wp_tz != null && $wp_tz != ''): return bmr_convertTime($wp_tz); endif;
					}

				// Set date
				$date = $post_after->post_date;
				$date = str_replace(' ', 'T', $date).bmr_get_timezoneOffset(); // v3.9 former: $date = str_replace(' ', 'T', $date);

				if(bmr_get_setting('api_page') == true && $post_type == 'page'){
					// Set content if it's a page (custom page mode)
					$text = $post_after->post_content; // keep WP paragraphs
					$content = strip_shortcodes( $text ); // remove shortcodes
					$content_iframe_filter = preg_replace('/<iframe.*?\/iframe>/i', '', $content);
					$content_script_filter = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content_iframe_filter);
					$limit = bmr_get_setting('api_excerpt');
					$body = $limit ? bmr_api_trim_content( $content_script_filter, $limit ) : bmr_api_trim_content( $content_script_filter );
				}else{
					// Set content if it's not a page (default mode)
					if( $post_after->post_excerpt != '' ){
						// Look for the excerpt
						$body = $post_after->post_excerpt;
					}else{
						 if( strpos( $post_after->post_content, '<!--more-->' ) ){
							// Look for read more tag
							$text = wpautop( $post_after->post_content ); // keep WP paragraphs
							$content = strip_shortcodes( $text ); // remove shortcodes
							$content_extended = get_extended( $content );
							$content_iframe_filter = preg_replace('/<iframe.*?\/iframe>/i', '', $content_extended['main']);
							$content_script_filter = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content_iframe_filter);
							$body = $content_script_filter;
						 }else{
						 	// Create a custom exerpt
							$text = $post_after->post_content; // keep WP paragraphs
							$content = strip_shortcodes( $text ); // remove shortcodes
							$content_iframe_filter = preg_replace('/<iframe.*?\/iframe>/i', '', $content);
							$content_script_filter = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content_iframe_filter);
							$limit = bmr_get_setting('api_excerpt');
							$body = $limit ? bmr_api_trim_content( $content_script_filter, $limit ) : bmr_api_trim_content( $content_script_filter );
						 }
					}
				}

				// Set Featured Image
				if( has_post_thumbnail($post_ID) ){
					$thumbnail_url = get_the_post_thumbnail_url($post_ID, 'full');
					$thumbnail = '<img src="'.$thumbnail_url.'" alt="'.$thumbnail_url.'"/>';
				}

				// Check if ignore thumbnail is enabled
				if( bmr_get_setting('api_thumbnail') != true ){
					$content = $thumbnail ? $thumbnail.' '.$body : $body;
				}else{
					$content = $body;
				}


				// Set category
				$category = bmr_get_meta('bmr_category');

				// Set Read More
				if( bmr_get_meta('bmr_link_text') ){
					// Manual
					$readmore = bmr_get_meta('bmr_link_text');
				}elseif( bmr_get_setting('api_readmore') ){
					// Default
					$readmore = bmr_get_setting('api_readmore');
				}

				// Set feedback
				if( bmr_get_meta( 'bmr_feedback' ) === 'off' ){
					$feedback = false;
				}else{
					$feedback = true;
				}

				// Set reactions
				if( bmr_get_meta( 'bmr_reactions' ) === 'off' ){
					$react = false;
				}else{
					$react = true;
				}

				// Create data array
				$data = array();

					// Check array elements
					if( is_bool( $post_after->post_title ) === true OR is_null( $post_after->post_title ) === true ) {
						$data['title'] = array( '' );
					} else {
						$data['title'] = array( $post_after->post_title );
					}

					if( is_bool( $content ) === true OR is_null( $content ) === true ) {
						$data['content'] = array( '' );
					} else {
						$data['content'] = array( $content );
					}

					if( is_bool( $category ) === true OR is_null( $category ) === true ) {
						$data['category'] = 'new';
					} else {
						$data['category'] = $category;
					}

					$data['publish'] = true;

					if( is_bool( $post_after->guid ) === true OR is_null( $post_after->guid ) === true ) {
						$data['linkUrl'] = array( '' );
					} else {
						$data['linkUrl'] = array( get_permalink($post_after->ID) ); //$post_after->guid );
					}

					if( is_bool( $readmore ) === true OR is_null( $readmore ) === true ) {
						$data['linkText'] = array( 'Read More' );
					} else {
						$data['linkText'] = array( $readmore ?: 'Read more' );
					}

					$data['date'] = $date;
					$data['enableFeedback'] = $feedback;
					$data['enableReactions'] = $react;
					$data['autoOpen'] = false;
					$data['language'] = array( 'EN' );

				// Set request
				$request = bmr_api_has_id($post_ID) ? 'PUT' : 'POST';

				// Check if ignore
				if( bmr_get_meta( 'bmr_ignore' ) == null ){

					// JSON here
					$data_string = json_encode($data);
					$args = array(
						'method' => $request,
					    'headers' => array(
					        'Content-Type' => 'application/json',
					        'Beamer-Api-Key' => $api_key,
					        'User-Agent' => 'WordPress Plugin '.$request.' (v'.bmr_version().'/php'.phpversion().')'
					    ),
					    'body' => $data_string
					);
					$response = wp_remote_request( $api_url, $args );
					$http_code = wp_remote_retrieve_response_code( $response );
					$body     = wp_remote_retrieve_body( $response );
					$decoded = json_decode($body, true);

				}

				// Update post meta with the Beamer custom fields
				$prefix = 'bmr_';
				$beamer_meta = array(
					$prefix.'title' => $post_after->post_title,
					$prefix.'content' => $content,
					$prefix.'publish' => true,
					$prefix.'linkUrl' => get_permalink($post_after->ID), // $post_after->guid,
					$prefix.'date' => $date
				);
				if( !bmr_api_has_id($post_ID) ){
					$beamer_meta[$prefix.'id'] = $decoded['id'];
				}
				foreach($beamer_meta as $key => $var){
					update_post_meta($post_ID, $key, $var);
				}
			}

		}
	}
	add_action( 'post_updated', 'bmr_api_call', 11, 3 );

?>