<?php

function gs_twitter_getoption( $option, $section, $default = '' ) {
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

 /**
 * New Functions
 **/
 function get_oauth_connection($cons_key, $cons_secret, $oauth_token, $oauth_token_secret){
    $ai_connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
    return $ai_connection;
}
$username =gs_twitter_getoption('gstfw_user_timeline','gs_Twitter_Feed_settings', 'gsplugins');
$gs_hashtag =gs_twitter_getoption('gstfw_hash_tag','gs_Twitter_Feed_settings',' ');
$gs_hashtag =gs_twitter_getoption('gstfw_hash_tag','gs_Twitter_Feed_settings',' ');
$gs_cache_period =gs_twitter_getoption('gstfw_cache_expire','gs_Twitter_Feed_settings',' 30');
$gs_disable_cache =gs_twitter_getoption('gs_tweet_dis_cache','gs_Twitter_Feed_settings',' off');


function get_twitter_tweets( $username, $tweets_number ) {
    $consumer_key=gs_twitter_getoption('gstfw_consumer_key','gs_Twitter_Feed_settings',' ');
    $consumer_secret=gs_twitter_getoption('gstfw_consumer_secret','gs_Twitter_Feed_settings',' ');
    $access_token =gs_twitter_getoption('gstfw_access_token','gs_Twitter_Feed_settings',' ');
    $access_token_secret =gs_twitter_getoption('gstfw_access_token_secret','gs_Twitter_Feed_settings',' ');
    $gs_cache_period =gs_twitter_getoption('gstfw_cache_expire','gs_Twitter_Feed_settings','30');
    $oauth_connection = get_oauth_connection($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    $api_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$tweets_number.'&exclude_replies=true';
    $tweets = $oauth_connection->get(apply_filters('gstweets_api_url', $api_url, $username, $tweets_number ));
    return $tweets;
}

function get_twitter_hasttag_tweets( $gs_hashtag, $tweets_number ) {
    $consumer_key=gs_twitter_getoption('gstfw_consumer_key','gs_Twitter_Feed_settings',' ');
    $consumer_secret=gs_twitter_getoption('gstfw_consumer_secret','gs_Twitter_Feed_settings',' ');
    $access_token =gs_twitter_getoption('gstfw_access_token','gs_Twitter_Feed_settings',' ');
    $access_token_secret =gs_twitter_getoption('gstfw_access_token_secret','gs_Twitter_Feed_settings',' ');
    $gs_cache_period =gs_twitter_getoption('gstfw_cache_expire','gs_Twitter_Feed_settings','30');
    $oauth_connection = get_oauth_connection($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    $api_url="https://api.twitter.com/1.1/search/tweets.json?q=%23".$gs_hashtag."&result_type=recent&count=".$tweets_number;
    $tweets = $oauth_connection->get(apply_filters('gstweets_api_url',$api_url,$gs_hashtag,$tweets_number));
    return $tweets;    
}

function get_twitter_card_tweets( $username, $tweets_number ) {
    $tweets='';
    $consumer_key=gs_twitter_getoption('gstfw_consumer_key','gs_Twitter_Feed_settings',' ');
    $consumer_secret=gs_twitter_getoption('gstfw_consumer_secret','gs_Twitter_Feed_settings',' ');
    $access_token =gs_twitter_getoption('gstfw_access_token','gs_Twitter_Feed_settings',' ');
    $access_token_secret =gs_twitter_getoption('gstfw_access_token_secret','gs_Twitter_Feed_settings',' ');
    $gs_hashtag =gs_twitter_getoption('gstfw_hash_tag','gs_Twitter_Feed_settings',' ');
    
    $oauth_connection = get_oauth_connection($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    $api_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$tweets_number.'&exclude_replies=true';
    $tweets = $oauth_connection->get(apply_filters('gstweets_api_url',$api_url,$username,$tweets_number));
     
    return $tweets;
}

function gstwitter_date_format($date, $format) {
    $unformatted_date = $date;
    switch ( $format ) {
        case 'full_date':
            $date = strtotime($date);
            $date = date('F j, Y, g:i a',$date);
        break;
        case 'date_only':
            $date = strtotime($date);
            $date = date('F j, Y',$date);
        break;
        case 'elapsed_time':
        $current_date = strtotime(date('h:i A M d Y'));
        $tweet_date = strtotime($date);
        $total_seconds = $current_date - $tweet_date;
    
        $seconds = $total_seconds % 60;
        $total_minutes = $total_seconds / 60;
        $minutes = $total_minutes % 60;
        $total_hours = $total_minutes / 60;
        $hours = $total_hours % 24;
        $total_days = $total_hours / 24;
        $days = $total_days % 365;
        $years = $total_days / 365;

        if ( $years >= 1 ) {
            if ( $years == 1 ) {
                $date = $years . __(' year ago', 'gstfw');
            } else {
                $date = $years . __(' year ago', 'gstfw');    
            }
            
        } elseif ($days >= 1) {
            if ( $days == 1 ) {
                $date = $days . __(' day ago', 'gstfw');    
            } else {
                $date = $days . __(' days ago', 'gstfw');
            }
            
        } elseif ($hours >= 1) {
            if ( $hours == 1 ) {
                $date = $hours . __(' hour ago', 'gstfw');    
            } else {
                $date = $hours . __(' hours ago', 'gstfw');
            }
            
        } elseif ($minutes > 1) {
            $date = $minutes . __(' minutes ago', 'gstfw');
        } else {
            $date = __("1 minute ago", 'gstfw');
        }
        break;
        default:
        break;
    }

    $date = apply_filters('gstf_date_value',$date,$unformatted_date); 
    return $date;
}

function gs_makeClickableLinks($s) {
    return preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.-]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
}