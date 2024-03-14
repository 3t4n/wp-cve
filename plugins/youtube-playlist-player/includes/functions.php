<?php
/*
 * Add default plugin options
 */
function ytpp_install() {
    add_option( 'ytpp_rel', 0 );
    add_option( 'ytpp_info', 0 );
    add_option( 'ytpp_controls', 1 );
    add_option( 'ytpp_privacy', 0 );
    add_option( 'ytpp_iframe_fix', 1 );

    add_option( 'ytppYouTubeApi', '' );
}

/*
 * Remove default plugin options on uninstall
 */
function ytpp_uninstall() {
    delete_option( 'ytpp_rel' );
    delete_option( 'ytpp_info' );
    delete_option( 'ytpp_controls' );
    delete_option( 'ytpp_privacy' );
    delete_option( 'ytpp_iframe_fix' );

    delete_option( 'ytppYouTubeApi' );
}

/*
 * Add plugin options page
 */
function ytpp_admin() {
    add_options_page( __( 'YouTube Playlist Player', 'youtube-playlist-player' ), __( 'YouTube Playlist Player', 'youtube-playlist-player' ), 'manage_options', 'ytpp', 'ytpp_settings' );
}

/*
 * Show static player/playlist
 *
 * @return string
 */
function ytpp_player_show( $atts ) {
    wp_enqueue_style( 'ytpp' );

    wp_enqueue_script( 'ytpp' );

    if ( (int) get_option( 'ytpp_iframe_fix' ) === 1 ) {
        wp_enqueue_script( 'ytpp-fluid-vids' );
    }

    $atts = shortcode_atts(
        [
            'mainid' => '',
            'vdid'   => '',
        ],
        $atts
    );

    $ytpp_height = (int) get_option( 'ytpp_height' );
    $main_id     = esc_attr( sanitize_text_field( $atts['mainid'] ) );
    $vd_id       = esc_attr( sanitize_text_field( $atts['vdid'] ) );

    $ytpp_rel         = (int) get_option( 'ytpp_rel' );
    $ytpp_info        = (int) get_option( 'ytpp_info' );
    $ytpp_controls    = (int) get_option( 'ytpp_controls' );
    $ytpp_privacy     = (int) get_option( 'ytpp_privacy' );
    $ytpp_youtube_uri = 'https://www.youtube.com';

    if ( (int) $ytpp_privacy === 1 ) {
        $ytpp_youtube_uri = 'https://www.youtube-nocookie.com';
    }

    $out = '<div id="yt-container" class="ytpp-main">
        <a name="ytplayer" class="f"><iframe name="ytpl-frame" id="ytpl-frame" type="text/html" rel="' . $main_id . '" src="' . $ytpp_youtube_uri . '/embed/' . $main_id . '?rel=' . $ytpp_rel . '&hd=1&version=3&iv_load_policy=3&showinfo=' . $ytpp_info . '&controls=' . $ytpp_controls . '&origin=' . home_url() . '" width="560" height="315" loading="lazy"></iframe></a>
        <div id="ytpp-playlist-container" class="ytpp-playlist-container" data-playlist="' . $vd_id . '"><div id="ytplayer_div2"></div></div>
    </div>';

    // There are no filters to be applied for this shortcode
    // Also fixes an issue with the "Rate my post" plugin
    // $out = apply_filters( 'the_content', $out );

    return $out;
}

/*
 * Show dynamic player/playlist
 *
 * Uses YouTube Data API v3.
 *
 * @return string
 */
function ytpp_apiplayer_show( $atts ) {
    wp_enqueue_style( 'ytpp' );

    $atts = shortcode_atts(
        [
            'mainid' => '',
            'vdid'   => '',
        ],
        $atts
    );

    $ytpp_youtube_api = sanitize_text_field( get_option( 'ytppYouTubeApi' ) );
    $main_id          = esc_attr( str_replace( ' ', '', sanitize_text_field( $atts['mainid'] ) ) );
    $vd_id            = esc_attr( str_replace( ' ', '', sanitize_text_field( $atts['vdid'] ) ) );

    $ytpp_rel         = (int) get_option( 'ytpp_rel' );
    $ytpp_info        = (int) get_option( 'ytpp_info' );
    $ytpp_controls    = (int) get_option( 'ytpp_controls' );
    $ytpp_privacy     = (int) get_option( 'ytpp_privacy' );
    $ytpp_youtube_uri = 'https://www.youtube.com';

    if ( (int) $ytpp_privacy === 1 ) {
        $ytpp_youtube_uri = 'https://www.youtube-nocookie.com';
    }

    return '<div class="yt-api-container ytpp-main" data-mainid="' . $main_id . '" data-vdid="' . $vd_id . '" data-apikey="' . $ytpp_youtube_api . '">
        <iframe id="vid_frame" src="' . $ytpp_youtube_uri . '/embed/' . $main_id . '?rel=' . $ytpp_rel . '&showinfo=' . $ytpp_info . '&autohide=1&controls=' . $ytpp_controls . '" width="560" height="315" loading="lazy"></iframe>

        <div class="yt-api-video-list"></div>
    </div>';
}

function ytpp_get_channel_videos( $channel_ids, $max_results = 100 ) {
    $ytpp_youtube_api = sanitize_text_field( get_option( 'ytppYouTubeApi' ) );

    $videos = [];

    foreach ( $channel_ids as $channel_id ) {
        $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&channelId=$channel_id&maxResults=$max_results&order=date&key=$ytpp_youtube_api";

        $response = file_get_contents( $url );
        $data     = json_decode( $response );

        foreach ( $data->items as $item ) {
            if ( $item->id->kind === 'youtube#video' ) {
                $video_id  = $item->id->videoId;
                $title     = $item->snippet->title;
                $thumbnail = '';

                if ( isset( $item->snippet->thumbnails->maxres ) ) {
                    $thumbnail = $item->snippet->thumbnails->maxres->url;
                } elseif ( isset( $item->snippet->thumbnails->standard ) ) {
                    $thumbnail = $item->snippet->thumbnails->standard->url;
                //} elseif ( isset( $item->snippet->thumbnails->high ) ) {
                //    $thumbnail = $item->snippet->thumbnails->high->url;
                } elseif ( isset( $item->snippet->thumbnails->medium ) ) {
                    $thumbnail = $item->snippet->thumbnails->medium->url;
                }

                $videos[] = [
                    'videoId'   => $video_id,
                    'title'     => $title,
                    'thumbnail' => $thumbnail,
                ];
            }
        }
    }

    return $videos;
}


function ytpp_feed_youtube( $atts ) {
    wp_enqueue_style( 'ytpp' );
    wp_enqueue_script( 'ytpp' );

    $atts = shortcode_atts(
        [
            'channels' => '',
            'results'  => 100,
        ],
        $atts
    );

    $channel_ids = explode( ',', $atts['channels'] );

    $out = '<div id="ytpp-lightbox-container">
        <div id="ytpp-lightbox-video"></div>
        <button id="ytpp-lightbox-close-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path fill="#ffffff" d="M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" opacity=".75"/><path fill="#000000" d="M8.97 8.97c.3-.3.77-.3 1.06 0L12 10.94l1.97-1.97a.75.75 0 1 1 1.06 1.06L13.06 12l1.97 1.97a.75.75 0 0 1-1.06 1.06L12 13.06l-1.97 1.97a.75.75 0 0 1-1.06-1.06L10.94 12l-1.97-1.97a.75.75 0 0 1 0-1.06Z"/></svg>
        </button>
    </div>
    <div class="ytpp-feed--wrapper">';

    $videos = ytpp_get_channel_videos( $channel_ids, $atts['results'] );

    foreach ( $videos as $video ) {
        $video_id  = $video['videoId'];
        $title     = $video['title'];
        $thumbnail = $video['thumbnail'];

        $out .= "<a href='#' class='ytpp-video-thumbnail' data-video-id='$video_id'>
            <img src='$thumbnail' alt='$title'>
        </a>";
    }

    $out .= '</div>';

    return $out;
}

