<?php
/**
 * Validates if the incoming ajax request is from the referrer that we have set.
 * If not then aborts further execution by dying.
 */
function ms_protect_ajax_route(){
    $isValid = check_ajax_referer(MS_NONCE_REFERRER, false, false);
    if(!$isValid){
        die("Not Allowed");
    }
}

/**
 * Function to get built HTML content from MakeStories preview engine given the story ID.
 * @param $storyId string
 * @return bool|string
 */
function ms_get_story_HTML($storyId){
    $url = MS_PREVIEW_URL.$storyId.'?forWordpress&v=2';
    $options = msGetDesignOptions();
    if(isset($options['ms_v4TrackingId'])){
        $url .= "&v4TrackingId=".$options['ms_v4TrackingId'];
    }
    $response = wp_remote_get($url);
    $html = $response['body'];
    return $html;
}
function ms_get_story_cdn_url($slug){
    $site_id = ms_get_options()['site_id'];
    return MS_STORY_CDN_URL.$site_id."/".$slug."/";
}
/**
 * Function to get built HTML content from CDN given the slug.
 * @param $slug string
 * @return bool|string
 */
function ms_get_story_HTML_v2($slug){
    $url = ms_get_story_cdn_url($slug);
    $response = wp_remote_get($url);
    $html = $response['body'];
    return $html;
}

function msEndsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}
function ms_get_options(){
    $options = get_option('mscpt_makestories_settings');
    $defaults = MS_DEFAULT_OPTIONS;
    if (!$options || !is_array($options)) {
        $options = [];
    }
    if($options && is_array($options)){
        foreach ($defaults as $key => $value){
            if($key === "roles" && !is_array($options[$key])){
                $options[$key] = [];
            }
            if(isset($options[$key])){
                if($key === "roles" && !in_array("administrator", $options[$key])){
                    $options[$key][] = "administrator";
                }
                $defaults[$key] = $options[$key];
            }
        }
    }
    $options['forceUploadMedia'] = false;
    return $defaults;
}

function ms_set_options(){
    $options = ms_get_options();
    $options['categories_enabled'] = isset($_POST['categories_enabled']);
    if(isset($_POST['post_slug'])){
        $options['post_slug'] = sanitize_text_field($_POST['post_slug']);
    }
    if(isset($_POST['default_category'])){
        $options['default_category'] = sanitize_text_field($_POST['default_category']);
    }
    if(isset($_POST['roles']) && is_array($_POST['roles'])){
        $roles = ["administrator"];
        foreach($_POST['roles'] as $role){
            if($role !== "administrator"){
                $roles[] = sanitize_text_field($role);
            }
        }
        $options['roles'] = $roles;
    }
    $options['to_rewrite'] = true;
    if(isset($_POST['forceUploadMedia'])){
        $options['forceUploadMedia'] = true;
    }else{
        $options['forceUploadMedia'] = false;
    }
    update_option('mscpt_makestories_settings',$options);
    return $options;
}

function msSaveDesignOptions($options) {
    update_option('mscpt_makestories_settings',$options);
    return $options;
}

function msGetDesignOptions() {
    $options = ms_get_options();
    $designOptions = [];
    foreach (DESIGN_OPTIONS as $DESIGN_OPTION){
        if(isset($options[$DESIGN_OPTION])){
            $designOptions[$DESIGN_OPTION] = $options[$DESIGN_OPTION];
        }
    }
    return $designOptions;
}

function ms_is_categories_enabled(){
    $config = ms_get_options();
    return $config['categories_enabled'];
}
function ms_get_default_category(){
    $config = ms_get_options();
    return $config['default_category'];
}

function ms_get_slug(){
    $config = ms_get_options();
    return $config['post_slug'];
}

function ms_get_allowed_roles(){
    $config = ms_get_options();
    return $config['roles'];
}

function ms_get_categories_list() {
    $categories = get_terms([
        'taxonomy' => MS_TAXONOMY,
        'hide_empty' => false,
        'posts_per_page' => -1,
    ]);
    $cat = [];
    foreach($categories as $category) {
        array_push($cat,$category->name);
    }

    return $cat;
}

function ms_verify_site_id($token){
    $options = ms_get_options();
    if(isset($options['site_id']) && $options['site_id']){
        return $options['site_id'];
    }
    $response = wp_remote_post("https://api.makestories.io/hosting/get-wp-site",[
        "body" => json_encode([
            "slug" => ms_get_slug(),
            "domain" => get_site_url(""),
            "token" => sanitize_text_field($token),
        ]),
        "headers" => [
            'Content-Type' => 'application/json',
        ],
        "timeout" => 120
    ]);
    $parsed = json_decode($response['body'], true);
    if($parsed && $parsed['success'] && $parsed['site_id']){
        $options['site_id'] = $parsed['site_id'];
        update_option('mscpt_makestories_settings',$options);
        return $options['site_id'];
    }
    wp_die(json_encode(["success" => false, "error" => "Some error occurred while getting Site Id.!"]));
}