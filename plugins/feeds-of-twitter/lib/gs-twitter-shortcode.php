<?php 

function gs_twittershortcode_getoption( $option, $section, $default = '' ) {
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

add_filter( 'gstweets_api_url', 'gstweets_api_url' );
function gstweets_api_url( $url ) {
    $extended_mode = gs_twittershortcode_getoption( 'gs_tweet_full_text', 'gs_Twitter_Feed_settings', 'off' );
    if ( $extended_mode == 'on' ) $url = add_query_arg( 'tweet_mode', 'extended', $url );
    return $url;
}

add_shortcode('gs_tweet','gs_plugin_twitter_tweets');

function gs_plugin_twitter_tweets($atts) {

    $gs_username = gs_twittershortcode_getoption( 'gstfw_user_timeline', 'gs_Twitter_Feed_settings', 'gsplugins' );
    $gs_tweets_number = gs_twittershortcode_getoption( 'gstfw_twettes_count', 'gs_Twitter_Feed_settings', '3' );
    $gs_tweets_theme = gs_twittershortcode_getoption( 'gstfw_theme', 'gs_Twitter_Feed_settings', '' );
    $gs_tweets_date_formet = gs_twittershortcode_getoption( 'gstfw_date_formet', 'gs_Twitter_Feed_settings', 'full_date' );
    $gs_tweets_display_action = gs_twittershortcode_getoption( 'gs_tweet_action_button', 'gs_Twitter_Feed_settings', '' );
    $gs_tweets_display_follow = gs_twittershortcode_getoption( 'gs_tweet_follow_button', 'gs_Twitter_Feed_settings', '' );
    $gs_twitter_link = gs_twittershortcode_getoption( 'gs_twitter_link_tar', 'gs_Twitter_Feed_settings', '_blank' );
   
    extract(shortcode_atts(
        array(
            'username'          => $gs_username,
            'tweet_number'      => $gs_tweets_number,
            'theme'             => $gs_tweets_theme,
            'link'              => $gs_twitter_link,
            //'follow_button'   => $gs_tweets_display_follow 
        ),$atts
    ));

    if ( isset($username) && isset($tweet_number) ) {
    	$gs_tweets = get_twitter_tweets( $username, $tweet_number );
    }

    $output = '';
        $output = '<div class="wrap gs_tw_feeds_area '.$theme.'">';
            
            if ( $theme == 'gstf_theme1' ) {
                include GSTWF_PLUGIN_DIR .'lib/templates/gs_tw_feed_theme1light.php';
            } elseif( $theme == 'gstf_theme2' ) {
                include GSTWF_PLUGIN_DIR .'lib/templates/gs_tw_feed_theme2dark.php';
            } else {
                $output = '<h4 style="text-align: center;">Select correct Theme or Upgrade to <a href="https://www.gsplugins.com/product/wordpress-twitter-feeds" target="_blank">Pro version</a><br>For more Options <a href="https://twitter.gsplugins.com" target="_blank">Chcek available demos</a></h4>';
            }
        $output .= '</div>'; // end wrap

        $news = '<div class="notice notice-error"><p>Due to changes with <b><i>Twitter’s API</b></i> the plugin will not update feeds.<br> 
        <b>GS Plugins</b> is working on a solution to see updated tweets in feeds again. </p></div>';
    return $news;
}

add_shortcode('gs_tweet_tag','gs_plugin_twitter_tweets_tag');

function gs_plugin_twitter_tweets_tag($atts) {

    $gs_hashtag = gs_twittershortcode_getoption('gstfw_hash_tag','gs_Twitter_Feed_settings',' ');
    $gs_tweets_number = gs_twittershortcode_getoption('gstfw_twettes_count','gs_Twitter_Feed_settings','3');
    $gs_tweets_theme = gs_twittershortcode_getoption('gstfw_theme','gs_Twitter_Feed_settings',' ');
    $gs_tweets_date_formet = gs_twittershortcode_getoption('gstfw_date_formet','gs_Twitter_Feed_settings','full_date');
    $gs_tweets_display_action = gs_twittershortcode_getoption('gs_tweet_action_button','gs_Twitter_Feed_settings','');
    $gs_tweets_display_follow = gs_twittershortcode_getoption('gs_tweet_follow_button','gs_Twitter_Feed_settings','');
    $gs_twitter_link = gs_twittershortcode_getoption('gs_twitter_link_tar','gs_Twitter_Feed_settings','_blank');
   
    extract(shortcode_atts(
        array(
            'hashtag'           => $gs_hashtag,
            'tweet_number'      => $gs_tweets_number,
            'theme'             => $gs_tweets_theme,
            'link'              => $gs_twitter_link,
            //'follow_button'   =>$gs_tweets_display_follow 
        ), $atts
    ));

    if(!empty($hashtag) && isset($tweet_number)) {
        $gs_tweets = get_twitter_hasttag_tweets($hashtag, $tweet_number);
        $gs_array_tweets=(array)$gs_tweets ;
        $gs_tweets=$gs_array_tweets['statuses'];
    }

    $output = '';
        $output = '<div class="wrap gs_tw_feeds_area '.$theme.'">';
            
            if ( $theme == 'gstf_theme1' ) {
                include GSTWF_PLUGIN_DIR .'lib/templates/gs_tw_feed_theme1light.php';
            } elseif( $theme == 'gstf_theme2' ) {
                include GSTWF_PLUGIN_DIR .'lib/templates/gs_tw_feed_theme2dark.php';
            } else {
                $output = '<h4 style="text-align: center;">Select correct Theme or Upgrade to <a href="https://www.gsplugins.com/product/wordpress-twitter-feeds" target="_blank">Pro version</a><br>For more Options <a href="https://twitter.gsplugins.com" target="_blank">Chcek available demos</a></h4>';
            }

        $output .= '</div>'; // end wrap
    return $output;
}




// add shortcode for widget

add_shortcode('gs_tweet_widget','gs_twitter_tweets_widget');

function gs_twitter_tweets_widget($atts){

    $gs_username =gs_twittershortcode_getoption('gstfw_user_timeline','gs_Twitter_Feed_settings','gsplugins');
    $gs_hashtag =gs_twittershortcode_getoption('gstfw_hash_tag','gs_Twitter_Feed_settings',' ');
    $gs_tweets_number =gs_twittershortcode_getoption('gstfw_twettes_count','gs_Twitter_Feed_settings','3');
    $gs_tweets_date_formet =gs_twittershortcode_getoption('gstfw_date_formet','gs_Twitter_Feed_settings','full_date');
    $gs_tweets_display_follow =gs_twittershortcode_getoption('gs_tweet_follow_button','gs_Twitter_Feed_settings','');
    


    extract(shortcode_atts(
        array(
            'username'      => $gs_username,
            'hashtag'       => $gs_hashtag ,
            'tweet_number'  => $gs_tweets_number,
            'follow_button' => $gs_tweets_display_follow
        ),$atts
    ));

    if(isset($username) && isset($tweet_number)) {
        $gs_tweets = get_twitter_tweets($username, $tweet_number);
    }

    if(!empty($hashtag) && isset($tweet_number)){
        $gs_tweets = get_twitter_hasttag_tweets($hashtag, $tweet_number);
        $gs_array_tweets = (array)$gs_tweets ;
        $gs_tweets = $gs_array_tweets['statuses'];
    }

    $output = '';
        $output = '<div class="wrap gs_tw_feeds_area">';

        if (is_array($gs_tweets)) {

            $output .= '<ul class="tweet-widget">';
    
                foreach ($gs_tweets as $tweets) {

                    if ( !empty($tweets->full_text) ) $tweets->text = $tweets->full_text;

                    if ($tweets->text) {
                        $gs_tweet = ' '.$tweets->text . ' '; //adding an extra space to convert hast tag into links
                        $gs_tweet = gs_makeClickableLinks($gs_tweet);

                            if (is_array($tweets->entities->user_mentions)) {
                                foreach ($tweets->entities->user_mentions as $key => $user_mention) {
                                    $gs_tweet = preg_replace( '/@' . $user_mention->screen_name . '/i', '<a href="https://www.twitter.com/' . $user_mention->screen_name . '" target="_blank">@' . $user_mention->screen_name . '</a>', $gs_tweet);
                                }
                            }

                            if (is_array($tweets->entities->hashtags)) {
                                foreach ($tweets->entities->hashtags as $hashtag) {
                                    $gs_tweet = str_replace(' #' . $hashtag->text . ' ', ' <a href="https://twitter.com/search?q=%23' . $hashtag->text . '&src=hash" target="_blank">#' . $hashtag->text . '</a> ', $gs_tweet);
                                }
                            }
                    
                        $output .= '<li><div class="item-info">';
                            $output .='<div class="tweet-description">';
                                                                
                                $output .= '<i class="fa fa-twitter"></i> '.$gs_tweet;
                                $output .= '</br> - <small>';
                                    $output .= gstwitter_date_format($tweets->created_at, $gs_tweets_date_formet);
                                $output .='</small>';
                            $output .='</div>';
                        $output .= '</div></li>'; 
                    } // end if  
                } // end foreach

            $output .= '</ul>'; 
        } // end foreach

        $output .= '</div>'; // gs_tw_feeds_area
    return $output;
}