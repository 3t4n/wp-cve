<?php
$permalink = isset($post) ? get_the_permalink($post->ID) :get_the_permalink();
$id = isset($post) ? $post->ID : get_the_id();
$storyId = get_post_meta($id, "story_id", true);
$posterImage = "https://ss.makestories.io/get?story=".$storyId;
$publisherDetails = get_post_meta($id, "publisher_details", true);
$posterPortrait = '';
$posterLandscape = '';
$posterSquare = '';
$logo = '';
$title = isset($post) ? get_the_title($post->ID) : get_the_title();
$publishDate = isset($post) ? get_the_date("M j Y",$post->ID) : get_the_date();
try{
    if($publisherDetails && $parsedDetails = json_decode($publisherDetails, true)){
        if($posterPortrait && !empty($posterPortrait)){
            $posterImage = $posterPortrait;
        }
        $posterPortrait = isset($parsedDetails['poster-portrait-src']) ? $parsedDetails['poster-portrait-src'] :  $posterImage;
        $posterLandscape = isset($parsedDetails['poster-landscape-src']) ? $parsedDetails['poster-landscape-src'] : $posterImage;
        $posterSquare = isset($parsedDetails['poster-square-src']) ? $parsedDetails['poster-square-src'] : $posterImage;
        $logo = isset($parsedDetails['poster-square-src']) ? $parsedDetails['poster-square-src'] : '';
    }
}catch (ErrorException $e){}
$posterImage = apply_filters("ms_story_poster", $posterPortrait ? $posterPortrait : $posterImage, [
    "portrait" => $posterPortrait,
    "landscape" => $posterLandscape,
    "square" => $posterSquare,
    "logo" => $logo,
]);