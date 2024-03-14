<?php 

/**
 * API Get all categories
 */
add_action("wp_ajax_ms_get_categories", "ms_get_categories");

function ms_get_categories_raw(){
    $categories = get_terms([
        'taxonomy' => MS_TAXONOMY,
        'hide_empty' => false,
        'posts_per_page' => -1,
    ]);
    $cat = [];

    foreach($categories as $category) {
        array_push($cat,[
            "id" => $category->term_id,
            "name" => $category->name,
            "count" => $category->count
        ]);
    }

    return $cat;
}

function ms_get_categories(){
	ms_protect_ajax_route();
	header("Content-Type: application/json");

	echo json_encode([
		"success" => true,
		"list" => ms_get_categories_raw(),
	]);
	
	die();
}

/**
 * API Get all published stories
 */

add_action("wp_ajax_ms_get_stories", "ms_get_stories");

function ms_get_story_raw(){
    $posts = get_posts([
        "post_type" => MS_POST_TYPE,
        'post_status' => 'publish',
        'numberposts' => -1,
        'order'    => 'ASC'
    ]);

    $postName = [];

    foreach($posts as $post) {
        array_push($postName,[
            "id" => $post->ID,
            "name" => $post->post_name,

        ]);
    }

    return $postName;
}

function ms_get_stories(){
	ms_protect_ajax_route();
	header("Content-Type: application/json");

	echo json_encode([
		"success" => true,
		"storiesList" => ms_get_story_raw(),
	]);
	
	die();
}

/**
 * API Get all published widget
 */

add_action("wp_ajax_ms_get_widget", "ms_get_widget");

function ms_get_widget_raw(){
    $posts = get_posts([
        "post_type" => MS_POST_WIDGET_TYPE,
        'post_status' => 'publish',
        'numberposts' => -1,
        'order'    => 'ASC'
    ]);

    $postName = [];

    foreach($posts as $post) {
        array_push($postName,[
            "id" => $post->ID,
            "name" => $post->post_name,

        ]);
    }

    return $postName;
}

function ms_get_widget(){
	ms_protect_ajax_route();
	header("Content-Type: application/json");

	echo json_encode([
		"success" => true,
		"storiesList" => ms_get_widget_raw(),
	]);
	
	die();
}