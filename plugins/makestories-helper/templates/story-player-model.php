<?php

$playerDetails = array();

$playerDetails['root'] = "#d-one";
$playerDetails['design'] = "d3";
$playerDetails['style'] = [];
$playerDetails['widgetId'] = "575e";

$postArgs = [
    'post_type' => MS_POST_TYPE,
    'numberposts' => $postsPerPage,
    'post_status' => 'publish',
];
$postsDataList = get_posts($postArgs);

foreach ($postsDataList as $post) {
    $postMeta = get_post_meta($post->ID);
    $publisherData = json_decode($postMeta['publisher_details'][0], true);

    $posterImage = "https://ss.makestories.io/get?story=".$postMeta['story_id'][0];
    $posterPortrait = isset($publisherData['poster-portrait-src']) ? $publisherData['poster-portrait-src'] : $posterImage;
    $posterLandscape = isset($publisherData['poster-landscape-src']) ? $publisherData['poster-landscape-src'] : $posterImage;
    $posterSquare = isset($publisherData['poster-square-src']) ? $publisherData['poster-square-src'] : $posterImage;
    $playerDetails['stories'][] = [
        "id" => $postMeta['story_id'][0],
        "url" => get_post_permalink($post->ID),
        "name" => get_the_title( $post->ID ),
        "logo" => $publisherData['publisher-logo-src'],
        "posterPortrait" => $posterPortrait,
        "posterSquare" =>  $posterSquare,
        "posterLandscape" => $posterLandscape
    ];
}

?>
<script>
    let players;
    if(typeof loadStoryPlayer !== "function"){
        function loadStoryPlayer(e, elm, url = false) {
            e.preventDefault();
            if(players && typeof players.openStory === "function"){
                players.openStory(url);
            }else{
                window.open(url)
            }
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        if(window.msPlayer && typeof window.msPlayer === "function"){
            players = new msPlayer( <?php echo stripslashes(json_encode($playerDetails)); ?> );
        }
    });

</script>