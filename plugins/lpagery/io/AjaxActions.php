<?php

require_once plugin_dir_path( __FILE__ ) . '../controller/CreatePostDelegate.php';
require_once plugin_dir_path( __FILE__ ) . '../data/LPageryDao.php';
require_once plugin_dir_path( __FILE__ ) . '../controller/CustomSanitizer.php';
require_once plugin_dir_path( __FILE__ ) . '../controller/SettingsController.php';
require_once plugin_dir_path( __FILE__ ) . '../controller/SubstitutionHandler.php';
require_once plugin_dir_path( __FILE__ ) . '../controller/DuplicateSlugHandler.php';
require_once plugin_dir_path( __FILE__ ) . '../utils/Utils.php';
require_once plugin_dir_path( __FILE__ ) . '../utils/MemoryUtils.php';
require_once plugin_dir_path( __FILE__ ) . 'Mapper.php';
add_action( 'wp_ajax_lpagery_fetch_permalink', 'lpagery_fetch_permalink' );
function lpagery_fetch_permalink()
{
    check_ajax_referer( 'lpagery_ajax' );
    $post_id = (int) $_GET['post_id'];
    if ( empty($_GET['slug']) ) {
        return false;
    }
    $slug = strtolower( LPageryCustomSanitizer::lpagery_sanitize_title_with_dashes( $_GET['slug'] ) );
    echo  site_url( get_page_uri( $post_id ) . "/" . $slug ) ;
    wp_die();
}

add_action( 'wp_ajax_lpagery_custom_sanitize_title', 'lpagery_custom_sanitize_title' );
function lpagery_custom_sanitize_title()
{
    check_ajax_referer( 'lpagery_ajax' );
    if ( empty($_GET['slug']) ) {
        return false;
    }
    $slug = strtolower( urldecode( LPageryCustomSanitizer::lpagery_sanitize_title_with_dashes( $_GET['slug'] ) ) );
    echo  esc_html( $slug ) ;
    wp_die();
}

add_action( 'wp_ajax_lpagery_create_posts', 'lpagery_create_posts' );
function lpagery_create_posts()
{
    $nonce_validity = check_ajax_referer( 'lpagery_ajax' );
    try {
        $response = LPageryCreatePostDelegate::lpagery_create_post( $_POST );
    } catch ( Throwable $exception ) {
        print_r( json_encode( array(
            "success"   => false,
            "exception" => $exception->__toString(),
        ) ) );
        wp_die();
    }
    $memory_usage = array();
    try {
        $memory_usage = LPageryMemoryUtils::lpagery_get_memory_usage();
    } catch ( Throwable $e ) {
    }
    try {
        $is_last_page = filter_var( $_POST["is_last_page"], FILTER_VALIDATE_BOOLEAN );
        if ( $is_last_page ) {
            LPageryDao::lpagery_update_process_sync_status( (int) $_POST['process_id'], "FINISHED" );
        }
    } catch ( Throwable $e ) {
        error_log( $e->__toString() );
    }
    
    if ( array_key_exists( "mode", $response ) ) {
        $mode = $response["mode"];
    } else {
        $mode = "ignored";
    }
    
    $replaced_slug = "";
    if ( array_key_exists( "replaced_slug", $response ) ) {
        $replaced_slug = $response["replaced_slug"];
    }
    $result_array = array(
        "success"     => true,
        "mode"        => $mode,
        "used_memory" => $memory_usage,
        "slug"        => $replaced_slug,
    );
    if ( $nonce_validity == 2 ) {
        $result_array["new_nonce"] = wp_create_nonce( "lpagery_ajax" );
    }
    print_r( json_encode( $result_array ) );
    wp_die();
}

add_action( 'wp_ajax_lpagery_save_settings', 'lpagery_save_settings' );
function lpagery_save_settings()
{
    check_ajax_referer( 'lpagery_ajax' );
    $settings = LPageryUtils::lpagery_sanitize_object( $_POST['settings'] );
    $spintax_enabled = rest_sanitize_boolean( $settings['spintax'] );
    $image_processing_enabled = rest_sanitize_boolean( $settings['image_processing'] );
    $author_id = intval( $settings['author_id'] );
    $google_sync_interval = sanitize_text_field( $settings['google_sheet_sync_interval'] );
    $schedules = array_keys( wp_get_schedules() );
    if ( !in_array( $google_sync_interval, $schedules ) ) {
        $google_sync_interval = null;
    }
    $custom_post_types = ( isset( $settings['custom_post_types'] ) ? array_map( 'sanitize_text_field', $settings['custom_post_types'] ) : array() );
    $next_google_sheet_sync = null;
    if ( isset( $settings["next_google_sheet_sync"] ) ) {
        $next_google_sheet_sync = strtotime( get_gmt_from_date( $settings["next_google_sheet_sync"] ) );
    }
    LPagerySettingsController::lpagery_save_settings(
        $spintax_enabled,
        $custom_post_types,
        $image_processing_enabled,
        $author_id,
        $google_sync_interval,
        $next_google_sheet_sync
    );
    wp_die();
}

add_action( 'wp_ajax_lpagery_get_settings', 'lpagery_get_settings' );
function lpagery_get_settings()
{
    check_ajax_referer( 'lpagery_ajax' );
    print_r( LPagerySettingsController::lpagery_get_settings() );
    wp_die();
}

add_action( 'wp_ajax_lpagery_get_pages', 'lpagery_get_pages' );
function lpagery_get_pages()
{
    check_ajax_referer( 'lpagery_ajax' );
    $user_option = unserialize( get_user_option( 'lpagery_settings', get_current_user_id() ) );
    $custom_post_types = (array) $user_option['custom_post_types'];
    array_push( $custom_post_types, "page", "post" );
    $mode = sanitize_text_field( $_GET["mode"] );
    $select = sanitize_text_field( $_GET["select"] );
    $post_id = null;
    if ( array_key_exists( "post_id", $_GET ) ) {
        $post_id = intval( $_GET["post_id"] );
    }
    $posts = LPageryDao::lpagery_search_posts(
        ( isset( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : "" ),
        $custom_post_types,
        $mode,
        $select,
        $post_id
    );
    print_r( json_encode( array_map( "LPageryMapper::lpagery_map_post", $posts ) ) );
    wp_die();
}

add_action( 'wp_ajax_lpagery_get_post_type', 'lpagery_get_post_type' );
function lpagery_get_post_type()
{
    check_ajax_referer( 'lpagery_ajax' );
    $post_id = (int) $_GET['post_id'];
    $process_id = (int) $_GET['process_id'];
    
    if ( $process_id ) {
        $process_by_id = LPageryDao::lpagery_get_process_by_id( $process_id );
        $count = LPageryDao::lpagery_count_processes();
        $post = get_post( $process_by_id->post_id );
        $lpagery_first_process_date = LPageryDao::lpagery_get_first_process_date();
        echo  json_encode( array(
            'type'               => $post->post_type,
            "process_count"      => $count,
            "first_process_date" => $lpagery_first_process_date,
        ) ) ;
        wp_die();
    }
    
    $post = get_post( $post_id );
    echo  esc_html( $post->post_type ) ;
    wp_die();
}

add_action( 'wp_ajax_lpagery_search_processes', 'lpagery_search_processes' );
function lpagery_search_processes()
{
    check_ajax_referer( 'lpagery_ajax' );
    $post_id = (int) $_GET['post_id'];
    $user_id = (int) $_GET['user_id'];
    $search_term = sanitize_text_field( urldecode( $_GET['purpose'] ) );
    $lpagery_processes = LPageryDao::lpagery_search_processes( $post_id, $user_id, $search_term );
    if ( is_null( $lpagery_processes ) ) {
        return json_encode( array() );
    }
    $return_value = array_map( "LpageryMapper::lpagery_map_process_search", $lpagery_processes );
    print_r( json_encode( $return_value, JSON_NUMERIC_CHECK ) );
    wp_die();
}

add_action( 'wp_ajax_lpagery_get_ram_usage', 'lpagery_get_ram_usage' );
function lpagery_get_ram_usage()
{
    check_ajax_referer( 'lpagery_ajax' );
    print_r( json_encode( LPageryMemoryUtils::lpagery_get_memory_usage() ) );
    wp_die();
}

add_action( 'wp_ajax_lpagery_get_post_title', 'lpagery_get_post_title' );
function lpagery_get_post_title()
{
    check_ajax_referer( 'lpagery_ajax' );
    $post_id = (int) $_GET['post_id'];
    $post = get_post( $post_id );
    echo  esc_html( $post->post_title ) ;
    wp_die();
}

add_action( 'wp_ajax_lpagery_get_users', 'lpagery_get_users' );
function lpagery_get_users()
{
    check_ajax_referer( 'lpagery_ajax' );
    print_r( json_encode( LPageryDao::lpagery_get_users_with_processes(), JSON_NUMERIC_CHECK ) );
    wp_die();
}

add_action( 'wp_ajax_lpagery_get_template_posts', 'lpagery_get_template_posts' );
function lpagery_get_template_posts()
{
    check_ajax_referer( 'lpagery_ajax' );
    print_r( json_encode( LPageryDao::lpagery_get_template_posts(), JSON_NUMERIC_CHECK ) );
    wp_die();
}

add_action( 'wp_ajax_lpagery_upsert_process', 'lpagery_upsert_process' );
function lpagery_upsert_process()
{
    check_ajax_referer( 'lpagery_ajax' );
    try {
        $post_id = ( isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : null );
        $process_id = ( isset( $_POST['id'] ) ? (int) $_POST['id'] : -1 );
        $purpose = ( isset( $_POST['purpose'] ) ? sanitize_text_field( $_POST['purpose'] ) : null );
        $process = LPageryDao::lpagery_get_process_by_id( $process_id );
        $request_google_sheet_data = $_POST["google_sheet_data"];
        $google_sheet_enabled = filter_var( $request_google_sheet_data["enabled"], FILTER_VALIDATE_BOOLEAN );
        $google_sheet_sync_enabled = filter_var( $request_google_sheet_data["sync_enabled"], FILTER_VALIDATE_BOOLEAN );
        $add = filter_var( $request_google_sheet_data["add"], FILTER_VALIDATE_BOOLEAN );
        $update = filter_var( $request_google_sheet_data["update"], FILTER_VALIDATE_BOOLEAN );
        $delete = filter_var( $request_google_sheet_data["delete"], FILTER_VALIDATE_BOOLEAN );
        $sheet_url = filter_var( urldecode( $request_google_sheet_data["url"] ), FILTER_VALIDATE_URL );
        $google_sheet_data = null;
        if ( $google_sheet_enabled ) {
            $google_sheet_data = array(
                "url"    => $sheet_url,
                "add"    => $add,
                "update" => $update,
                "delete" => $delete,
            );
        }
        $data = ( isset( $_POST['data'] ) ? lpagery_extract_process_data( $_POST['data'], $process ) : null );
        $lpagery_process_id = LPageryDao::lpagery_upsert_process(
            $post_id,
            $process_id,
            $purpose,
            $data,
            $google_sheet_data,
            $google_sheet_sync_enabled
        );
        print_r( json_encode( array(
            "success"    => true,
            "process_id" => $lpagery_process_id,
        ) ) );
    } catch ( Throwable $exception ) {
        print_r( json_encode( array(
            "success"   => false,
            "exception" => $exception->__toString(),
        ) ) );
        wp_die();
    }
    wp_die();
}

function lpagery_extract_process_data( $input_data, $process )
{
    check_ajax_referer( 'lpagery_ajax' );
    if ( !$input_data ) {
        return null;
    }
    $categories = ( isset( $input_data['categories'] ) ? $input_data['categories'] : array() );
    $categories = array_map( 'sanitize_text_field', $categories );
    $tags = $input_data['tags'] ?? array();
    $tags = array_map( 'sanitize_text_field', $tags );
    $parent_path = (int) $input_data['parent_path'];
    $slug = sanitize_text_field( $input_data['slug'] );
    $status = sanitize_text_field( $input_data['status'] );
    if ( isset( $process ) ) {
        
        if ( $status == "-1" ) {
            $unserialized_data = unserialize( $process->data );
            $status = $unserialized_data->status ?? get_post_status( $process->post_id );
        }
    
    }
    $data = array(
        "categories"  => $categories,
        "tags"        => $tags,
        "status"      => $status,
        "parent_path" => $parent_path,
        "slug"        => $slug,
    );
    return $data;
}

add_action( 'wp_ajax_lpagery_get_duplicated_slugs', 'lpagery_get_duplicated_slugs' );
function lpagery_get_duplicated_slugs()
{
    check_ajax_referer( 'lpagery_ajax' );
    $slug = ( isset( $_POST['slug'] ) ? LPageryCustomSanitizer::lpagery_sanitize_title_with_dashes( $_POST['slug'] ) : null );
    $process_id = ( isset( $_POST['process_id'] ) ? intval( $_POST['process_id'] ) : -1 );
    $data = $_POST['data'];
    echo  json_encode( LPageryDuplicateSlugHandler::lpagery_get_duplicated_slugs( $data, $process_id, $slug ) ) ;
    wp_die();
}
