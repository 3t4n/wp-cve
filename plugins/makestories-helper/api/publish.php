<?php
add_action("wp_ajax_ms_publish_post", "ms_publish_post_v2");

/**
 * Action for publishing the post. Takes the story ID and gets the HTML for that
 */

function ms_publish_post(){
    ms_protect_ajax_route();
    header("Content-Type: application/json");
    if((isset($_REQUEST["slug"]) || isset($_REQUEST["post_id"])) && isset($_REQUEST["story"])){
        $storyId = sanitize_text_field($_REQUEST["story"]);
        $r = ms_get_story_HTML($storyId);
        $parsed = json_decode($r, true);
        $html = $parsed['html'];
        $title = $parsed['publisherDetails']['title'];

        if(isset($_REQUEST['post_id'])) {
            $post = get_post((int)sanitize_text_field($_REQUEST['post_id']));
            if ($post && $post->post_status != 'trash') {
                $post = $post->ID;
                $toCreate = false;
                if(isset($_REQUEST["slides"])){
                    $pages = sanitize_text_field($_REQUEST["slides"]);
                    update_post_meta($post,"pages", $pages);
                }
            } else {
                die(json_encode(["success" => false, "error" => "Post already deleted!"]));
            }
        }else{
            $slug = sanitize_text_field($_REQUEST["slug"]);
            if (isset($_REQUEST["scheduleDate"])) {
                $day = $_REQUEST["day"];
                $time = $_REQUEST["time"];
                $datetime = $day." ".$time;
                $postdate = date($datetime);
                $postdate_gmt = date($datetime);
                $post = wp_insert_post([
                    "post_content" => $html,
                    "post_name" => $slug,
                    "post_title" => $title,
                    "post_status" => "draft",
                    // "post_status" => "future",
                    "post_type" => MS_POST_TYPE,
                    // "post_date_gmt" => $postdate_gmt,
                    // "post_date" => $postdate,
                    // 'edit_date' => 'true'
                ]);

            } else {
                
                $post = wp_insert_post([
                    "post_content" => $html,
                    "post_name" => $slug,
                    "post_title" => $title,
                    "post_status" => "publish",
                    "post_type" => MS_POST_TYPE,
                ]);

            }

            if ($post) {
                $pages = sanitize_text_field($_REQUEST["slides"]);
                add_post_meta($post,"pages", $pages);
            }
        }

        include_once( ABSPATH . 'wp-admin/includes/image.php' );
        if( ! ( function_exists( 'wp_get_attachment_by_post_name' ) ) ) {
            function wp_get_attachment_by_post_name( $post_name ) {
                $id = post_exists($post_name);
                $args           = array(
                    'posts_per_page' => 1,
                    'post_type'      => 'attachment',
                    'p'           => $id,
                );

                $get_attachment = new WP_Query( $args );

                if ( ! $get_attachment || ! isset( $get_attachment->posts, $get_attachment->posts[0] ) ) {
                    return false;
                }

                return $get_attachment->posts[0];
            }
        }
        $mediaLinksToDownload = [];
        // Uploading media to media library of wordpress
        $forceUploadMedia = ms_get_options()['forceUploadMedia'];
//        foreach ($parsed['media'] as $media) {
//            $imageurl = $media['url'];
//            $name = $imageurl;
//            $nameExploded = explode("?",$imageurl);
//            if(count($nameExploded)){
//                $nameExploded = $nameExploded[0];
//            }
//            $nameExploded = explode("/",$nameExploded);
//            if(count($nameExploded)){
//                $name = $nameExploded[count($nameExploded) - 1];
//            }
//            $filename = date('dmY').''.(int) microtime(true).basename($name);
//            $atta_title = basename( $media['url'] );
//
//            $attach_id = false;
//            if( post_exists($atta_title) && !$forceUploadMedia ) {
//                if(wp_validate_boolean(apply_filters("ms_check_for_duplicate_media", true))){
//                    $getAttachment = wp_get_attachment_by_post_name( $atta_title );
//                }
//
//                if($getAttachment) {
//                    $attach_id = $getAttachment->ID;
//                }else{
//                    $mediaLinksToDownload[] = [
//                        "imageurl" => $imageurl,
//                        "filename" => $filename,
//                        "atta_title" => $atta_title,
//                        "into_else" => true,
//                        "exists" => post_exists($atta_title),
//                    ];
//                }
//
//            } else {
//                $mediaLinksToDownload[] = [
//                    "imageurl" => $imageurl,
//                    "filename" => $filename,
//                    "atta_title" => $atta_title,
//                ];
//            }
//            if($attach_id){
//                // Get permalink of media library
//                $permalink = wp_get_attachment_url($attach_id);
//
//                // Replace permalink of image with html
//                $html = str_ireplace($media['url'], $permalink, $html);
//            }
//        }

        if(ms_is_categories_enabled() && !isset($_REQUEST['is_republish'])){
            $category = ms_get_default_category();
            if(isset($_REQUEST['category']) && !empty($_REQUEST['category'])){
                $category = sanitize_text_field($_REQUEST['category']);
            }
            wp_set_post_terms($post, $category, MS_TAXONOMY);
        }
        $link = get_post_permalink($post);
        $slug = sanitize_text_field($_REQUEST["slug"]);
        wp_update_post([
            "post_content" => str_ireplace(MS_WORDPRESS_CANONICAL_SUBSTITUTION_PLACEHOLDER, $link, $html),
            "ID" => $post,
            "post_name" => $slug,
            "post_title" => $title,
        ]);
        update_post_meta( $post, "story_id", $storyId);
        update_post_meta( $post, "publisher_details", json_encode($parsed['publisherDetails']));
        if (isset($_REQUEST["scheduleDate"])) {
            $scheduleId = uniqid();
            update_post_meta( $post, "scheduleData", $_REQUEST["scheduleDate"]);
            update_post_meta( $post, "scheduleId", $scheduleId);
        }
        print_r(
            json_encode(
                getMSPostDataToSend(
                    get_post($post),
                    [
                        "media" => $mediaLinksToDownload
                    ]
                )
            )
        );
        wp_die();
    }
    wp_die(json_encode(["success" => false, "error" => "Invalid details provided!"]));
}

/**
 * Action for publishing the post. Takes the story ID and gets the HTML for that
 */
function ms_publish_post_v2(){
    ms_protect_ajax_route();
    header("Content-Type: application/json");
    $options = ms_get_options();
    if((isset($_REQUEST["slug"]) || isset($_REQUEST["post_id"])) && isset($_REQUEST["story"])){
        $storyId = sanitize_text_field($_REQUEST["story"]);

        //Create post if post_id is not present
        //Get post permalink - to send while calling publishing
        //Call publishing URL and publish it
        //
        $slug = sanitize_text_field($_REQUEST["slug"]);
        if(isset($_REQUEST['post_id'])) {
            $post = get_post((int)sanitize_text_field($_REQUEST['post_id']));
            if ($post && $post->post_status != 'trash') {
                $post = $post->ID;
                if(isset($_REQUEST["slides"])){
                    $pages = sanitize_text_field($_REQUEST["slides"]);
                    update_post_meta($post,"pages", $pages);
                }
            } else {
                die(json_encode(["success" => false, "error" => "Post already deleted!"]));
            }
        }else{

            //This is for scheduled stories
            if (isset($_REQUEST["scheduleDate"])) {
                //Handle story scheduling later
                die(json_encode(["success" => false, "error" => "Scheduling not supported yet!"]));
            } else {
                $post = wp_insert_post([
                    "post_content" => '',
                    "post_name" => $slug,
                    "post_title" => '',
                    "post_status" => "publish",
                    "post_type" => MS_POST_TYPE,
                ]);
                //todo: Update title
            }
        }
        if(ms_is_categories_enabled() && !isset($_REQUEST['is_republish'])){
            $category = ms_get_default_category();
            if(isset($_REQUEST['category']) && !empty($_REQUEST['category'])){
                $category = sanitize_text_field($_REQUEST['category']);
            }
            wp_set_post_terms($post, $category, MS_TAXONOMY);
        }
        $link = get_post_permalink($post);
        wp_update_post([
//            "post_content" => str_ireplace(MS_WORDPRESS_CANONICAL_SUBSTITUTION_PLACEHOLDER, $link, $html),
            "post_content" => '',
            "ID" => $post,
            "post_name" => $slug,
        ]);
        update_post_meta( $post, "story_id", $storyId);
        update_post_meta( $post, "version", 2);

        $base = get_site_url() . "/" . ms_get_slug() . "/";
        $cleanedSlug = untrailingslashit(str_replace($base, "", $link));
        $response = wp_remote_post("http://apis-kub.makestories.io/publish/story",[
            "body" => json_encode([
                "storyId" => $storyId,
                "slug" => $cleanedSlug,
                "site_id" => $options['site_id'],
                "channelId" => "wp/".$options['site_id'],
                "uid" => sanitize_text_field($_REQUEST['userId']),
                "token" => sanitize_text_field($_REQUEST['token']),
                "workspace" => sanitize_text_field($_REQUEST['workspace']),
            ]),
            "headers" => [
                'Content-Type' => 'application/json',
            ],
            "timeout" => 120
        ]);
        if(is_wp_error($response)) {
            wp_die(json_encode(["success" => false, "error" => "Some error occurred while publishing the story.!"]));
        }
        $parsed = json_decode($response['body'], true);
        if($parsed && !$parsed['error'] && $parsed['url']){
            if(isset($parsed['publisherDetails']) && is_array($parsed['publisherDetails'])){
                update_post_meta( $post, "publisher_details", json_encode($parsed['publisherDetails']));
                wp_update_post([
                    "ID" => $post,
                    "post_title" => $parsed['publisherDetails']['title'],
                ]);

            }
            print_r(
                json_encode(
                    getMSPostDataToSend(
                        get_post($post),
                        [
                            "media" => [],
                        ]
                    )
                )
            );
        } else {
            wp_die(json_encode([
                "success" => false,
                "error" => $parsed["message"] ? $parsed["message"] : 'Some error occurred while publishing! Please try again or contact support if issue persists.'
            ]));
        }
        wp_die();
    }
    wp_die(json_encode(["success" => false, "error" => "Invalid details provided!"]));
}

add_action("wp_ajax_ms_upload_image_to_media_library", "ms_upload_image_to_media_library");
add_action("wp_ajax_nopriv_ms_upload_image_to_media_library", "ms_upload_image_to_media_library");

function ms_upload_image_to_media_library(){
    ms_protect_ajax_route();
    header("Content-Type: application/json");
    echo json_encode([
        "replaced" => true,
    ]);
    wp_die();
}

add_action("wp_ajax_ms_get_published_posts", "ms_get_published_posts");


function ms_get_published_posts(){
    ms_protect_ajax_route();
    header("Content-Type: application/json");
    $args = [
        "post_type" => MS_POST_TYPE,
        "numberposts" => -1,
    ];
    if(!current_user_can("manage_options")){
        $args["author"] = get_current_user_id();
    }
    $posts = get_posts($args);
    $toSend = [
        "posts" => [],
    ];
    foreach ($posts as $post){
        $storyId = get_post_meta($post->ID, "story_id", true);
        $category = [];
        $terms = wp_get_post_terms($post->ID, MS_TAXONOMY);
        foreach ($terms as $term){
            $category[] = $term->name;
        }
        $title = $post->post_name;
        $meta = get_post_meta($post->ID, "publisher_details", true);
        $poster = "";
        if($meta && is_string($meta) && strlen($meta)){
            try{
                $parsed = json_decode($meta, true);
                if($parsed && is_array($parsed)){
                    if(isset($parsed['title'])){
                        $title = $parsed['title'];
                    }
                    if(isset($parsed['poster-portrait-src'])){
                        $poster = $parsed['poster-portrait-src'];
                    }
                }
            }catch (Exception $e){
                //Do nothing - Just for safety
            }
        }
        $toSend['posts'][$storyId] = [
            "link" => get_post_permalink($post->ID),
            "title" => $title,
            "poster" => $poster,
            "updatedAt" => strtotime($post->post_modified) * 1000,
            "post_id" => $post->ID,
            "category" => $category,
            "site_id" => $site_id,
        ];
    }
    die(json_encode($toSend));
}


add_action("wp_ajax_ms_get_published_posts_all", "ms_get_published_posts_all");


function ms_get_published_posts_all(){
    ms_protect_ajax_route();
    header("Content-Type: application/json");
    $args = [
        "post_type" => MS_POST_TYPE,
        "numberposts" => -1,
    ];
    $posts = get_posts($args);
    $toSend = [
        "posts" => [],
    ];
    foreach ($posts as $post){
        $storyId = get_post_meta($post->ID, "story_id", true);
        $category = [];
        $terms = wp_get_post_terms($post->ID, MS_TAXONOMY);
        foreach ($terms as $term){
            $category[] = $term->name;
        }
        $title = $post->post_name;
        $meta = get_post_meta($post->ID, "publisher_details", true);
        $poster = "";
        if($meta && is_string($meta) && strlen($meta)){
            try{
                $parsed = json_decode($meta, true);
                if($parsed && is_array($parsed)){
                    if(isset($parsed['title'])){
                        $title = $parsed['title'];
                    }
                    if(isset($parsed['poster-portrait-src'])){
                        $poster = $parsed['poster-portrait-src'];
                    }
                }
            }catch (Exception $e){
                //Do nothing - Just for safety
            }
        }
        $toSend['posts'][$post->ID] = [
            "link" => get_post_permalink($post->ID),
            "title" => $title,
            "poster" => $poster,
            "updatedAt" => strtotime($post->post_modified) * 1000,
            "post_id" => $post->ID,
            "story_id" => $storyId,
            "category" => $category,
        ];
    }
    die(json_encode($toSend));
}

add_action("wp_ajax_ms_get_published_post", "ms_get_published_post");

function ms_get_published_post(){
    ms_protect_ajax_route();
    $toSend = [
        "isPublished" => false,
    ];
    $token = sanitize_text_field($_REQUEST['token']);
    ms_verify_site_id($token);
    if(isset($_REQUEST['story'])){
        $storyId = sanitize_text_field($_REQUEST['story']);
        $postType = sanitize_text_field($_REQUEST['post_type']);
        $postKey = sanitize_text_field($_REQUEST['post_key']);
        $args = [
            "post_type" => $postType,
            "numberposts" => 1,
            "meta_query" => [
                [
                    "key" => $postKey,
                    "value" => $storyId,
                    "compare" => "="
                ]
            ]
        ];
        $posts = get_posts($args);
        if(count($posts)){
            $toSend = getMSPostDataToSend($posts[0]);
            $toSend["isPublished"] = true;
        }
    }
    header("Content-Type: application/json");
    print_r(json_encode($toSend));
    die();
}

add_action("wp_ajax_ms_delete_post", "ms_delete_post");

function ms_delete_post(){
    ms_protect_ajax_route();
    $toSend = [
        "deleted" => false,
    ];
    if(isset($_REQUEST['story']) && isset($_REQUEST['post_id'])){
        $storyId = sanitize_text_field($_REQUEST['story']);
        $postId = sanitize_text_field($_REQUEST['post_id']);
        $args = [
            "post_type" => MS_POST_TYPE,
            "numberposts" => 1,
            "meta_query" => [
                [
                    "key" => "story_id",
                    "value" => $storyId,
                    "compare" => "="
                ]
            ]
        ];
        //Verify if post is already there before sending delete command
        $posts = get_posts($args);
        if(count($posts) && $postId == $posts[0]->ID){
            //Verified, now deleting
            $toSend["deleted"] = (bool) wp_trash_post($postId);
        }
    }
    header("Content-Type: application/json");
    print_r(json_encode($toSend));
    die();
}

add_action("wp_ajax_ms_change_story_slug", "ms_change_story_slug");

function ms_verify_media_in_story(){
//    ms_protect_ajax_route();
    $media = [];
    if(isset($_REQUEST['post_id'])){
        $postId = sanitize_text_field($_REQUEST['post_id']);
        $post = get_post($postId);
        $content = $post->post_content;
        $doc = new DOMDocument();
        $doc->loadHTML($content);
        $mediaInStory = [];

        //Gather all Image element sources
        $images = $doc->getElementsByTagName("amp-img");
        foreach ($images as $image){
            $mediaInStory[] = $image->getAttribute("src");
        }

        //Gather all Video element sources
        $videos = $doc->getElementsByTagName("amp-video");
        foreach ($videos as $video){
            $mediaInStory[] = $video->getAttribute("poster");
            $sources = $video->getElementsByTagName("source");
            foreach ($sources as $source){
                $mediaInStory[] = $source->getAttribute("src");
            }
        }

        //Gather all link elements
        $links = $doc->getElementsByTagName("link");
        foreach ($links as $link){
            $rel = $link->getAttribute("rel");
            if(strpos($rel, "icon") !== false){
                $mediaInStory[] = $link->getAttribute("href");
            }
        }

        //Gather all meta elements
        $metas = $doc->getElementsByTagName("meta");
        foreach ($metas as $meta){
            $name = $meta->getAttribute("property");
            if(empty($name)){
                $name = $meta->getAttribute("name");
            }
            if($name && strpos($name, ":image")){
                $mediaInStory[] = $meta->getAttribute("content");
            }
        }

        //Add images from JsonLd
        $scripts = $doc->getElementsByTagName("script");
        if(count($scripts)){
            foreach ($scripts as $script){
                $type = $script->getAttribute("type");
                if($type === "application/ld+json"){
                    try{
                        $json = json_decode($script->nodeValue, true);
                        if($json && is_array($json)){
                            if(isset($json['image']) && is_array($json['image'])){
                                foreach ($json['image'] as $url){
                                    $mediaInStory[] = $url;
                                }
                            }
                            if(
                                isset($json['publisher']) &&
                                is_array($json['publisher']) &&
                                isset($json['publisher']['logo']) &&
                                is_array($json['publisher']['logo']) &&
                                isset($json['publisher']['logo']["@type"]) &&
                                isset($json['publisher']['logo']["url"]) &&
                                $json['publisher']['logo']["@type"] === "ImageObject"
                            ){
                                $mediaInStory[] = $json['publisher']['logo']["url"];
                            }
                        }
                    }catch(Exception $e){
                        //Do Nothing
                    }
                }
            }
        }

        //Posters and other details
        $ampStory = $doc->getElementsByTagName("amp-story");
        if(count($ampStory)){
            $ampStory = $ampStory->item(0);
            $propertiesToAdd = [
                "publisher-logo-src",
                "poster-portrait-src",
                "poster-landscape-src",
                "poster-square-src",
            ];
            foreach ($propertiesToAdd as $prop){
                $mediaInStory[] = $ampStory->getAttribute($prop);
            }
        }

        //CHeck in post meta data
        $meta = get_post_meta($postId, "publisher_details", true);
        try{
            $meta = json_decode($meta, true);
            $propertiesToAdd = [
                "publisher-logo-src",
                "poster-portrait-src",
                "poster-landscape-src",
                "poster-square-src",
            ];
            foreach ($propertiesToAdd as $prop){
                if(isset($meta[$prop])){
                    $mediaInStory[] = $meta[$prop];
                }
            }
        }catch(Exception $e){
            //Do Nothing
        }



        //Sanitize all the media urls to take only the MS hosted ones
        foreach ($mediaInStory as $imageUrl){
            foreach (MS_DOMAINS as $domain){
                if(strpos($imageUrl, $domain) !== false){
                    $media[] = ms_replace_domains($imageUrl);
                    break;
                }
            }
        }
    }
    header("Content-Type: application/json");
    print_r(json_encode(array_unique($media)));
    die();
}

function ms_replace_domains($url){
    $url = str_replace("storage.googleapis.com/makestories-202705.appspot.com",MS_CDN_LINK, $url);
    $url = str_replace("storage.googleapis.com/cdn-storyasset-link",MS_CDN_LINK, $url);
    return $url;
}

add_action("wp_ajax_ms_verify_media_in_story", "ms_verify_media_in_story");

function ms_change_story_slug(){
    ms_protect_ajax_route();
    if($_REQUEST['post'] && $_REQUEST['slug']){
        header("Content-Type: application/json");
        $postId = sanitize_text_field($_REQUEST['post']);
        $newTitle = sanitize_text_field($_REQUEST['slug']);
        $post = get_post($postId);
        if($post){
            wp_update_post([
                "post_name" => $newTitle,
                "ID" => $postId,
            ]);
            print_r(json_encode(getMSPostDataToSend($post)));
        }else{
            print_r(json_encode([
                "message"=> "Post not found!",
                "error" => true,
            ]));
        }
    }else{
        print_r(json_encode([
            "message"=> "Invalid arguments provided!",
            "error" => true,
        ]));
    }
    die();
}

function getMSPostDataToSend($post, $toReturn = []){
    if(!is_array($toReturn)){
        $toReturn = [];
    }
    $category = [];
    $terms = wp_get_post_terms($post->ID, MS_TAXONOMY);
    foreach ($terms as $term){
        $category[] = $term->name;
    }
	return array_merge([
        "id" => $post->ID,
        "lastUpdated" => strtotime($post->post_modified),
        "permalink" => get_post_permalink($post->ID),
        "name" => $post->post_name,
        "category" => $category,
        "success" => true,
    ], $toReturn);
}

/********** ms get all published posts for story player **********/

add_action( 'rest_api_init', function () {
	register_rest_route( 'widget', '/stories/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'storyPlayer',
	) );
} );


function storyPlayer($data){

  header("Content-Type: application/json");
  $widgetID = $data->get_param( 'widgetId' );
  
  $output = getStoryPlayer($widgetID);
  print_r(json_encode([$output]));
  wp_die();
}

function getStoryPlayer($id) {
    if (isset($id) ) {
        $widgetArgs = [
            "post_type" => MS_POST_WIDGET_TYPE,
            "numberposts" => -1,
        ];

        $widgetPosts = get_posts($widgetArgs);

        $widgetId = "";
        $name = "";
        $type = "";
        $design = "";
        $categories = "";
        foreach($widgetPosts as $post) {
            $postMeta = get_post_meta($post->ID);

            if ($postMeta['widget_id'][0] == $id) {
                $widgetId = $id;
                $name = get_the_title($post->ID);
                $type = $postMeta['type'][0];
                $design = $postMeta['design'][0];
                $categories = $postMeta['categories'][0];
                break;
            }
        }

        if ($type == "category" && isset($categories)) {
            $cat = explode(',',$categories);

            $args = [
                "post_type" => MS_POST_TYPE,
                "numberposts" => -1,
                'tax_query' => array(
					array(
					'taxonomy' => MS_TAXONOMY,
					'field' => "name",
					'terms' => $cat,
					),
				),
            ];
        } else {
            $args = [
                "post_type" => MS_POST_TYPE,
                "numberposts" => -1,
            ];
        }

        $posts = get_posts($args);
        $count = wp_count_posts(MS_POST_TYPE);
        $publish = $count->publish;
        $toSend['count'] = $publish;
        $toSend['design'] = $design;
        $toSend['id'] = $widgetId;
        $toSend['name'] = $name;
        $toSend['stories'] = [];

        foreach ($posts as $post){
            $storyId = get_post_meta($post->ID, "story_id", true);
            $category = [];
            $terms = wp_get_post_terms($post->ID, MS_TAXONOMY);
            foreach ($terms as $term){
                $category[] = $term->name;
            }
            $title = $post->post_name;
            $meta = get_post_meta($post->ID, "publisher_details", true);
            $metaData = get_post_meta($post->ID);
            $poster = "";
            if($meta && is_string($meta) && strlen($meta)){
                try{
                    $parsed = json_decode($meta, true);
                    if($parsed && is_array($parsed)){
                        if(isset($parsed['poster-portrait-src'])){
                            $poster = $parsed['poster-portrait-src'];
                        }
                    }
                }catch (Exception $e){
                    //Do nothing - Just for safety
                }
            }
            $storyObject = [
                "id" => $storyId,
                "name" => $title,
                "posterPortrait" => $poster,
                "posterLandscape" => $metaData['poster-landscape-src'],
                "posterSquare" => $metaData['poster-square-src'],
                "publishedDate" => get_the_date("Y/m/d H:i:s",$post->ID),
                "publishDateTs" => strtotime(get_the_date("Y/m/d H:i:s",$post->ID)),
                "url" => get_post_permalink($post->ID),
                "pages" => $metaData["pages"][0],
            ];
            array_push($toSend['stories'], $storyObject);
        }
        $toSend['style'] =  [ "border" => "rgba(10,10,10,1)"];
        $toSend['type'] = $type;
       
        print_r(json_encode($toSend));
        die();
    } else {
        print_r(json_encode(['1','2','3']));
        die();
    }
}

/**
 * Action for publishing the Scheduling post. Takes the post ID and change the post status to publish
 */
add_action("wp_ajax_ms_schedule_publish_post", "ms_schedule_publish_post");
function ms_schedule_publish_post(){
    if(isset($_REQUEST['post_id']) && isset($_REQUEST['scheduleId'])) {
        $post = get_post((int)$_REQUEST['post_id']);
        $scheduleId = get_post_meta( $post->ID, 'scheduleId');
        if ($post && isset($scheduleId) && $post->post_status != 'trash' && $post->post_status == "draft" && $_REQUEST['scheduleId'] == $scheduleId) {
            $post = $post->ID;
            wp_update_post(array(
                'ID'    =>  $post,
                'post_status'   =>  'publish'
            ));
        } else {
            die(json_encode(["success" => false, "error" => "Post already deleted!"]));
        }
    }
}