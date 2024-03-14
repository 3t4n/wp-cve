<?php 

// Theme-1

if (is_array($gs_tweets)) {
    $output .= '<div class="container">';
        $output .= '<div class="row clearfix gs_tweet">';
            
            foreach ($gs_tweets as $tweets) {

                if ( !empty($tweets->full_text) ) $tweets->text = $tweets->full_text;

                if ($tweets->text) {

                    $gs_tweet = ' '.$tweets->text . ' '; //adding an extra space to convert hast tag into links
                    $gs_tweet = gs_makeClickableLinks($gs_tweet);

                        if (is_array($tweets->entities->user_mentions)) {
                            foreach ($tweets->entities->user_mentions as $key => $user_mention) {
                                $gs_tweet = preg_replace( '/@' . $user_mention->screen_name . '/i', '<a href="http://www.twitter.com/' . $user_mention->screen_name . '" target="_blank">@' . $user_mention->screen_name . '</a>', $gs_tweet);
                            }
                        }

                        if (is_array($tweets->entities->hashtags)) {
                            foreach ($tweets->entities->hashtags as $hashtag) {
                                $gs_tweet = str_replace(' #' . $hashtag->text . ' ', ' <a href="https://twitter.com/search?q=%23' . $hashtag->text . '&src=hash" target="_blank">#' . $hashtag->text . '</a> ', $gs_tweet);
                            }
                        }
                    
                    $output .= '<div class="col-md-12 single-tweet">';
                       
                        $output .='<a href="http://twitter.com/'.$tweets->user->screen_name.'" class="gs-tweet-name" target="_blank"> '.$tweets->user->screen_name.'</a> ';
                            $output .= '<span class="gs-name-separator">-</span> <a href="https://twitter.com/'.$tweets->user->screen_name.'/status/'.$tweets->id_str.'" target="_blank">';
                            $output .= gstwitter_date_format($tweets->created_at, $gs_tweets_date_formet);
                        $output .='</a></br>';
                        
                        $output .= $gs_tweet;

                    $output .= '</div>';
                }   
            }

            if($gs_tweets_display_follow == 'on' && !empty($username)) {

                $output  .= include GSTWF_PLUGIN_DIR .'lib/templates/gs-twitter-follow-buttons.php';;                    
            }

        $output .= '</div>'; // end row
    $output .= '</div>'; // end container
    return $output;
}