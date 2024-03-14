<?php

use function Attire\Blocks\blocks\post_grid\atbs_excerpt;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'atbs', '/get_post_types', array(
		'methods'             => 'GET',
		'callback'            => 'get_types',
		'permission_callback' => '__return_true',
	) );

//    register_rest_route('atbs', '/get_taxonomies/(?P<post_type>\w+)', array(
//        'methods' => 'GET',
//        'callback' => 'atbs_get_taxonomies',
//        'permission_callback' => '__return_true',
//    ));
//
//    register_rest_route('atbs', '/search_category/(?P<taxonomy>\w+)/(?P<search_term>\w+)', array(
//        'methods' => 'GET',
//        'callback' => 'atbs_get_tax_by_search',
//        'permission_callback' => '__return_true',
//    ));

	register_rest_route( 'atbs', '/get_filtered_posts', array(
		'methods'             => 'GET',
		'callback'            => 'atbs_get_filtered_posts',
		'permission_callback' => '__return_true',
	) );

	register_rest_route( 'atbs', '/search_custom_post', array(
		'methods'             => 'GET',
		'callback'            => 'atbs_search_custom_post',
		'permission_callback' => '__return_true',
	) );

	register_rest_route( 'atbs', '/upvote', array(
		'methods'             => 'POST',
		'callback'            => 'atbs_upvote',
		'permission_callback' => function () {
			return is_user_logged_in();
		}
	) );

	register_rest_route( 'atbs', '/downvote', array(
		'methods'             => 'POST',
		'callback'            => 'atbs_downvote',
		'permission_callback' => function () {
			return is_user_logged_in();
		}
	) );
} );


function atbs_upvote( WP_REST_Request $request ) {

	$type          = 'upvote';
	$previous_vote = maybe_unserialize( get_user_meta( get_current_user_id(), 'atbs_voting_' . $_POST['vote_id'], true ) );
//	remove vote
	if ( $previous_vote === 'upvote' ) {
		$type = '';
		atbs_remove_upvote( 'upvote', $previous_vote, $_POST['post_id'] );
		delete_user_meta( get_current_user_id(), 'atbs_voting_' . $_POST['vote_id'] );
	} elseif ( $previous_vote === 'downvote' ) {
		update_user_meta( get_current_user_id(), 'atbs_voting_' . $_POST['vote_id'], 'upvote' );
		atbs_remove_downvote( 'upvote', $previous_vote, $_POST['post_id'] );
		atbs_add_upvote( 'upvote', $previous_vote, $_POST['post_id'] );
	} else {
		update_user_meta( get_current_user_id(), 'atbs_voting_' . $_POST['vote_id'], 'upvote' );
		atbs_add_upvote( 'upvote', $previous_vote, $_POST['post_id'] );
	}

	return send_updated_vote_count( $type, $_POST['post_id'], $_POST['vote_id'] );
}

function atbs_downvote( WP_REST_Request $request ) {
	$type          = 'downvote';
	$previous_vote = maybe_unserialize( get_user_meta( get_current_user_id(), 'atbs_voting_' . $_POST['vote_id'], true ) );
	//	remove vote
	if ( $previous_vote === 'downvote' ) {
		$type = '';
		delete_user_meta( get_current_user_id(), 'atbs_voting_' . $_POST['vote_id'] );
		atbs_remove_downvote( 'downvote', $previous_vote, $_POST['post_id'] );
	} elseif ( $previous_vote === 'upvote' ) {
		update_user_meta( get_current_user_id(), 'atbs_voting_' . $_POST['vote_id'], "downvote" );
		atbs_remove_upvote( 'downvote', $previous_vote, $_POST['post_id'] );
		atbs_add_downvote( 'downvote', $previous_vote, $_POST['post_id'] );
	} else {
		update_user_meta( get_current_user_id(), 'atbs_voting_' . $_POST['vote_id'], "downvote" );
		atbs_add_downvote( 'downvote', $previous_vote, $_POST['post_id'] );
	}

	return send_updated_vote_count( $type, $_POST['post_id'], $_POST['vote_id'] );
}

function send_updated_vote_count( $type, $post_id, $vote_id ) {
	$upvote_count   = (int) get_post_meta( $post_id, 'atbs_upvotes_' . $vote_id, true );
	$downvote_count = (int) get_post_meta( $post_id, 'atbs_downvotes_' . $vote_id, true );

	return json_encode( [ 'type' => $type, 'upvotes' => $upvote_count, 'downvotes' => $downvote_count ] );
}

function atbs_remove_upvote( $vote, $previous_vote, $post_id ) {
	$upvotes = (int) maybe_unserialize( get_post_meta( $post_id, 'atbs_upvotes_' . $_POST['vote_id'], true ) );
	update_post_meta( $post_id, 'atbs_upvotes_' . $_POST['vote_id'], $upvotes - 1 );
}

function atbs_remove_downvote( $vote, $previous_vote, $post_id ) {
	$downvotes = (int) maybe_unserialize( get_post_meta( $post_id, 'atbs_downvotes_' . $_POST['vote_id'], true ) );
	update_post_meta( $post_id, 'atbs_downvotes_' . $_POST['vote_id'], $downvotes - 1 );

}

function atbs_add_upvote( $vote, $previous_vote, $post_id ) {
	$upvotes = (int) maybe_unserialize( get_post_meta( $post_id, 'atbs_upvotes_' . $_POST['vote_id'], true ) );
	update_post_meta( $post_id, 'atbs_upvotes_' . $_POST['vote_id'], $upvotes + 1 );

}

function atbs_add_downvote( $vote, $previous_vote, $post_id ) {
	$downvotes = (int) maybe_unserialize( get_post_meta( $post_id, 'atbs_downvotes_' . $_POST['vote_id'], true ) );
	update_post_meta( $post_id, 'atbs_downvotes_' . $_POST['vote_id'], $downvotes + 1 );
}

function get_types( WP_REST_Request $request ) {

	$types = get_post_types();

	if ( empty( $types ) ) {
		return new WP_Error( 'no_layout', 'Invalid query', array( 'status' => 404 ) );
	}

	return $types;
}

function atbs_get_taxonomies( WP_REST_Request $request ) {

	$taxes = get_object_taxonomies( $request['post_type'] );

	if ( empty( $taxes ) ) {
		return new WP_Error( 'no_taxonomies', 'Invalid query', array( 'status' => 404 ) );
	}

	return $taxes;
}

function atbs_get_tax_by_search( WP_REST_Request $request ) {

	$args  = array(
		'taxonomy'   => array( $request['taxonomy'] ),
		'orderby'    => 'id',
		'order'      => 'ASC',
		'hide_empty' => true,
		'fields'     => 'all',
		'name__like' => $request['search_term']
	);
	$terms = get_terms( $args );

	if ( empty( $terms ) ) {
		return new WP_Error( 'no_taxonomies', 'Invalid query', array( 'status' => 404 ) );
	}

	return $terms;
}

function atbs_search_custom_post( WP_REST_Request $request ) {
	$pattern      = '/^[\w\_\-\s]+$/';
	$type_matches = [];
	$term_matches = [];
	preg_match( $pattern, $request['type'], $type_matches );
	preg_match( $pattern, $request['term'], $term_matches );
	$args = array(
		'post_status' => 'publish',
		'post_type'   => $type_matches[0],
		's'           => $term_matches[0]
	);

	$query = new \WP_Query( $args );

	if ( empty( $query->posts ) ) {
		return new WP_Error( 'no_posts', 'Invalid query', array( 'status' => 404 ) );
	}

	return $query->posts;
}

function atbs_get_filtered_posts( WP_REST_Request $request ) {
	$sortBy            = explode( ',', $request['sortBy'] );
	$posts             = [];
	$excludePosts      = [];
	$categories        = [];
	$excludeCategories = [];
	$tags              = [];
	$excludeTags       = [];
	$authors           = [];
	$excludeAuthors    = [];
	$numPosts          = $request['postsPerRow'] * $request['rows'];

	if ( is_string( $request['posts'] ) && strlen( $request['posts'] ) ) {
		$posts = array_map( 'intval', explode( ',', $request['posts'] ) );
	}
	if ( is_string( $request['excludePosts'] ) && strlen( $request['excludePosts'] ) ) {
		$excludePosts = array_map( 'intval', explode( ',', $request['excludePosts'] ) );
	}
	if ( is_string( $request['categories'] ) && strlen( $request['categories'] ) ) {
		$categories = array_map( 'intval', explode( ',', $request['categories'] ) );
	}
	if ( is_string( $request['excludeCategories'] ) && strlen( $request['excludeCategories'] ) ) {
		$excludeCategories = array_map( 'intval', explode( ',', $request['excludeCategories'] ) );
	}
	if ( is_string( $request['tags'] ) && strlen( $request['tags'] ) ) {
		$tags = array_map( 'intval', explode( ',', $request['tags'] ) );
	}
	if ( is_string( $request['excludeTags'] ) && strlen( $request['excludeTags'] ) ) {
		$excludeTags = array_map( 'intval', explode( ',', $request['excludeTags'] ) );
	}
	if ( is_string( $request['authors'] ) && strlen( $request['authors'] ) ) {
		$authors = array_map( 'intval', explode( ',', $request['authors'] ) );
	}
	if ( is_string( $request['excludeAuthors'] ) && strlen( $request['excludeAuthors'] ) ) {
		$excludeAuthors = array_map( 'intval', explode( ',', $request['excludeAuthors'] ) );
	}
	if ( is_string( $request['rows'] ) && strlen( $request['rows'] ) ) {
		$numPosts = $request['postsPerRow'] * $request['rows'];
	}

	$args = array(
		'post_status'         => 'publish',
		'post__in'            => $posts,
		'post__not_in'        => $excludePosts,
		'category__in'        => $categories,
		'category__not_in'    => $excludeCategories,
		'tag__in'             => $tags,
		'tag__not_in'         => $excludeTags,
		'author__in'          => $authors,
		'author__not_in'      => $excludeAuthors,
		'orderby'             => $sortBy[0],
		'order'               => $sortBy[1],
		'post_type'           => $request['postType'],
		'ignore_sticky_posts' => true,
		'posts_per_page'      => $numPosts,
		'page'                => 1
	);

	$query = new \WP_Query( $args );

	if ( empty( $query->posts ) ) {
		return new WP_Error( 'no_posts', 'Invalid query', array( 'status' => 404 ) );
	}

	return array_map( function ( $post ) use ( $request ) {
		$post->post_thumbnail           = get_the_post_thumbnail_url( $post->ID, [
			$request['thumbnailHeight'],
			$request['thumbnailWidth']
		] );
		$post->post_categories          = get_the_category( $post->ID );
		$post->post_url                 = get_the_permalink( $post->ID );
		$post->post_author_display_name = get_the_author_meta( "display_name", $post->post_author );
		$post->post_author_url          = esc_url( get_author_posts_url( $post->post_author ) );

		$archive_year  = get_the_time( 'Y' );
		$archive_month = get_the_time( 'm' );
		$archive_day   = get_the_time( 'd' );

		$post->post_modified_date = get_the_modified_date( '', $post->ID );;
		$post->post_date_link     = get_day_link( $archive_year, $archive_month, $archive_day );
		$post->post_comment_count = get_comments_number( $post->ID );
		$post->post_excerpt       = $post->post_excerpt !== "" ? $post->post_excerpt : atbs_excerpt( $request['excerptLength'], $post );
		unset( $post->post_content );
		if ( $request['postType'] === 'product' ) {
			$product            = wc_get_product( $post->ID );
			$post->sku          = $product->get_sku();
			$post->product_id   = $product->get_id();
			$post->product_name = $product->get_name();
			$post->price_html   = $product->get_price_html();
		}

		return $post;
	}, $query->posts );
}