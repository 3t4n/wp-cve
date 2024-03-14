<?php
/*
 * Created by JetBrains PhpStorm.
 * User: Dejan Markovic
 * Date: 10/22/13
 * Time: 3:25 PM
 * To change this template use File | Settings | File Templates.
 */

/*
 * returns  custom posts
 *
 * @param  $output
 *
 * @return $custom_posts array() of names or objects
 */
function hsb_get_custom_posts( $output ) {
	/* The built-in public post types are post, page, and attachment.
	 * By setting '_builtin' to false, we will exclude them and show only the custom public post types.
	 */
	$args = array(
		'public'   => true,
		'_builtin' => false
	);

	$operator = 'and'; // 'and' or 'or'
	return $custom_posts = get_post_types( $args, $output, $operator );
}

/*
 * returns  post taxonomies
 *
 * @param  $output
 *
 * @return $post_taxonomies array() of names or objects
 */
function hsb_get_post_taxonomies( $custom_posts, $output ) {
	$post_taxonomies = '';
	if ( ! empty( $custom_posts ) ) {
		//if there is more than one item than it's array of objects

		if ( is_array( $custom_posts ) ) {
			foreach ( $custom_posts as $custom_post ) {
				$post_taxonomies[ $custom_post->name ] = get_object_taxonomies( $custom_post->name, $output );
			}
		} //othervise it's a sinlge object
		else {
			$labels = $custom_posts->labels;
			//object name
			$labels_name                     = strtolower( $labels->name );
			$post_taxonomies[ $labels_name ] = get_object_taxonomies( $labels_name, $output );
		}

		return array_filter( $post_taxonomies );
	}
	else {
		return $post_taxonomies;
	}
}


/*
 * returns  post labels
 *
 * @param  $output
 *
 * @return $post_labels array() of names or objects
 */
function hsb_get_post_labels( $custom_posts, $output ) {
	//if there is more than one item than it's array of objects
	if ( is_array( $custom_posts ) ) {
		foreach ( $custom_posts as $custom_post ) {
			$post_labels[] = $custom_post->label;
		}
	} //othervise it's a sinlge object
	else {
		$labels        = $custom_posts->labels;
		$post_name     = $custom_posts->name;
		$post_labels[] = $labels->name;
	}

	return array_filter( $post_labels );
}


/*
 * gets category (hierarchical) taxomies
 *
 * @param $post_taxonomies array of post taxonomies
 *
 * @return $category_names array
 */

function hsb_get_category_taxonomies( $post_taxonomies ) {
	//single records are objects(multi records are arrays)
	$count          = hsb_countdim( $post_taxonomies );
	$category_names = '';
	if ( $count > 1 ) {
		foreach ( $post_taxonomies as $name => $value ) {
			foreach ( $value as $val ) {
				if ( $val->hierarchical == 1 ) {
					$category_names[] = $val->name;
				}
			}
		}

		return $category_names;
	}
	else {
		foreach ( $post_taxonomies as $name => $value ) {
			if ( $value->hierarchical == 1 ) {
				$category_names[] = $value->name;
			}
		}

		return $category_names;
	}

}

/*
 * gets tag (non-hierarchical) taxomies
 *
 * @param $post_taxonomies array of post taxonomies
 *
 * @return $tag_names array
 */

function hsb_get_tag_taxonomies( $post_taxonomies ) {
	foreach ( $post_taxonomies as $name => $value ) {
		foreach ( $value as $val ) {
			if ( $val->hierarchical != 1 ) {
				$tag_names[] = $val->name;
			}
		}

		return $tag_names;
	}
}

/*
 * separates hierarchical and non-hierarchical taxomies
 *
 * @param $post_taxonomies array of post taxonomies
 *
 * @return $hierarchical_names and $non_hierarchical_names arrays
 */
function hsb_get_taxonomy_checklist( $taxonomies, $omitCustCats ) {
	if ( count( $taxonomies ) == 1 ) {
		$args = array(
			'descendants_and_self' => 0,
			'selected_cats'        => $omitCustCats,
			'popular_cats'         => false,
			'walker'               => null,
			'taxonomy'             => "$taxonomies",
			'checked_ontop'        => false
		);
		wp_terms_checklist( 0, $args );
	}
	else {
		foreach ( $taxonomies as $taxonomy ) {
			$args = array(
				'descendants_and_self' => 0,
				'selected_cats'        => $omitCustCats,
				'popular_cats'         => false,
				'walker'               => null,
				'taxonomy'             => "$taxonomy",
				'checked_ontop'        => false
			);
			wp_terms_checklist( 0, $args );
		}
	}
}

//associative array implode
function hsb_multi_implode( $array, $glue ) {
	$ret = '';
	foreach ( $array as $item ) {
		if ( is_array( $item ) ) {
			$ret .= hsb_multi_implode( $item, $glue ) . $glue;
		}
		else {
			$ret .= $item . $glue;
		}
	}
	$ret = substr( $ret, 0, 0 - strlen( $glue ) );

	return $ret;
}

//check if array is associative
function hsb_is_assoc( $array ) {
	return (bool) count( array_filter( array_keys( $array ), 'is_string' ) );
}

//count Array dimensions
function hsb_countdim( $array ) {
	if ( is_array( reset( $array ) ) ) {
		$return = hsb_countdim( reset( $array ) ) + 1;
	}
	else {
		$return = 1;
	}

	return $return;
}

function hsb_is_custom_post_type( $post = null ) {
	$all_custom_post_types = get_post_types( array( '_builtin' => false ) );

	// there are no custom post types
	if ( empty ( $all_custom_post_types ) ) {
		return false;
	}

	$custom_types      = array_keys( $all_custom_post_types );
	$current_post_type = get_post_type( $post );

	// could not detect current type
	if ( ! $current_post_type ) {
		return false;
	}

	return in_array( $current_post_type, $custom_types );
}

function hsb_get_custom_post_categories( $post_id ) {
	$taxonomies        = get_object_taxonomies( get_post_type( $post_id ), 'objects' );
	$cat_taxonomy_name = '';
	if ( ! empty( $taxonomies ) ) {
		if ( ! is_wp_error( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( $taxonomy->hierarchical ) {
					$cat_taxonomy_name = $taxonomy->name;
				}
			}
		}
	}

	return $post_categories = wp_get_object_terms( $post_id, $cat_taxonomy_name );
}

function hsb_get_custom_post_tags( $post_id ) {
	//get custom post taxonimies
	$taxonomies        = get_object_taxonomies( get_post_type( $post_id ), 'objects' );
	$tag_taxonomy_name = '';
	if ( ! empty( $taxonomies ) ) {
		if ( ! is_wp_error( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( ! $taxonomy->hierarchical ) {
					$tag_taxonomy_name = $taxonomy->name;
				}
			}
		}
	}

	return $post_tags = wp_get_object_terms( $post_id, $tag_taxonomy_name );
}

function hsb_opt_optionselected( $opValue, $value ) {
	if ( $opValue == $value ) {
		return 'selected="selected"';
	}

	return '';
}

function hsb_add_quotes( $str ) {
	return sprintf( "'%s'", $str );
}

//Shorten long URLs with is.gd or bit.ly.
function hsb_shorten_url( $the_url, $shortener = 'is.gd', $api_key = '', $user = '' ) {
	if ( $shortener == "tinyurl" ) {
		$url      = "http://tinyurl.com/api-create.php?url={$the_url}";
		$response = hsb_send_request( $url, 'GET' );
	}
	else {
		$url      = "http://is.gd/api.php?longurl={$the_url}";
		$response = hsb_send_request( $url, 'GET' );
	}

	return $response;
}

function hsb_send_request( $url, $method ) {
	$result = '';

	HSB_DEBUG( 'request is: ' . print_r( $url, true ) );
	// Send request
	switch ( $method ) {
		case 'GET':
			$result = wp_remote_get( $url, array(
				'body'      => '',
				'sslverify' => false
			) );
			break;
		case 'POST':
			$result = wp_remote_get( $url, array(
				'body'      => '',
				'sslverify' => false
			) );
			break;
	}

	HSB_DEBUG( 'result is: ' . print_r( $result, true ) );
	// Check the request is valid
	if ( is_wp_error( $result ) ) {
		return $result;
	}

	if ( $result['response']['code'] != 200 ) {
		return 'Error ' . $result['response']['code'] . $result['response']['message'] . '. Please try again.';
	}

	return $result['body'];
}
