<?php

class Youzify_Profile_Instagram_Widget {

    /**
     * Constructor
     */
    function __construct() {

        // Actions.
        add_filter( 'youzify_is_widget_visible', array( $this, 'is_widget_visible' ), 10, 2 );
        add_filter( 'youzify_profile_widget_visibility', array( $this, 'display_widget' ), 10, 2 );

    }

    /**
     * Fields.
     */
    function fields( $fields ) {

        $fields['instagram'] = array(
            'id' => 'youzify_wg_instagram_account_token',
            'title' => __( 'User Instagram', 'youzify' )
        );

        return apply_filters( 'youzify_instagram_widget_fields', $fields );
    }

    /**
     * Display Widget.
     */
    function is_widget_visible( $visibility, $widget_name ) {

        if ( 'instagram' != $widget_name ) {
            return $visibility;
        }

        // Get Instagram Account.
        $app_id = youzify_option( 'youzify_wg_instagram_app_id' );
        $app_secret = youzify_option( 'youzify_wg_instagram_app_secret' );

        if ( empty( $app_id ) || empty( $app_secret ) ) {
            return false;
        }

        return true;

    }

    /**
     * Display Widget.
     */
    function display_widget( $visibility, $widget_name ) {

        if ( 'instagram' != $widget_name ) {
            return $visibility;
        }

        if ( ! $this->is_widget_visible( false, 'instagram' ) ) {
            return false;
        }

        // Get Instagram Account.
        $instagram = get_the_author_meta( 'youzify_wg_instagram_account_token', bp_displayed_user_id() );

        if ( empty( $instagram ) ) {
            return false;
        }

        return true;

    }

    /**
     * Content.
     */
    function widget() {

        // Get User Data
        $user_id = bp_displayed_user_id();
        $photos_number = youzify_option( 'youzify_wg_max_instagram_items', 9 );

        // Get Instagram Photos
        $instagram_medias = $this->get_instagram_photos( $user_id, $photos_number );

        if ( empty( $instagram_medias ) ) {
            return;
        }

        ?>

        <ul class="youzify-portfolio-content youzify-instagram-photos">

        <?php foreach ( $instagram_medias as $media ) : ?>

        <li>
            <figure class="youzify-project-item youzify-portfolio-<?php echo $media['media_type']; ?>">
                <?php if ( $media['media_type'] == 'image' ) : ?>
                    <div class="youzify-projet-img"><img loading="lazy" <?php echo youzify_get_image_attributes_by_link( $media['media_url'] ); ?> alt=""></div>
                <?php else: ?>
                    <video width="100%" <?php echo apply_filters( 'youzify_instagram_widget_video_attributes', 'controls controlsList="nodownload"' ); ?> preload="metadata" type="video/quicktime"><source src="<?php echo $media['media_url']; ?>#t=0.001" type="video/mp4"></video>
                <?php endif; ?>

                <figcaption class="youzify-pf-buttons">
                    <a class="youzify-pf-url" rel="nofollow noopener" href="<?php echo $media['link']; ?>" target="_blank" >
                        <i class="fas fa-link"></i>
                    </a>
                    <?php if ( $media['media_type'] == 'image' ) : ?>
                    <a class="youzify-pf-zoom"><i class="fas fa-search"></i></a>
                    <a class="youzify-lightbox-img" rel="nofollow noopener" href="<?php echo $media['media_url']; ?>" data-youzify-lightbox="youzify-instagram" <?php if ( ! empty( $media['caption'] ) ) { echo "data-title='" . esc_attr( $media['caption'] ) . "'"; } ?>></a>
                     <?php endif; ?>
                </figcaption>
            </figure>
        </li>

        <?php endforeach; ?>

        </ul>

        <?php
    }

    /**
     * Get Instagram Photos By Username
     */
    function get_instagram_photos( $user_id, $limit = 6 ) {

        // Init Vars.
        $medias = array();

        // Get Data
        $instagram_data = $this->get_data( $user_id, $limit );

        // if data is empty return false.
        if ( empty( $instagram_data['data'] ) ) {
            return false;
        }

        foreach ( $instagram_data['data'] as $data ) {

            // Get Image Data.
            $medias[] = array(
                'media_type' => strtolower( $data['media_type'] ),
                'media_url' => $data['media_url'],
                'caption' => isset( $data['caption'] ) ? $data['caption'] : '',
                'id' => $data['id'],
                'link' => $data['permalink']
            );

        }

        return $medias;
    }

    /**
     * Check if account is working.
     */
    function get_data( $user_id = null, $limit = 6 ) {

        // Get Feed.
        $feed = apply_filters( 'youzify_instagram_widget_get_transient', get_transient( 'youzify_instagram_feed_' . $user_id ) );

        if ( empty( $feed ) ) {

            // Get Access Token
            $token = get_the_author_meta( 'youzify_wg_instagram_account_token', $user_id );

            if ( empty( $token ) ) {
                return false;
            }

            if ( ! is_array( $token ) ) {
                $token = array( 'token' => $token );
            }

            // Get User Images Feed
            $profile_url = 'https://graph.instagram.com/me/media/?fields=id,media_type,like_count,media_url,permalink,caption&access_token=' . $token['token'] . '&limit=' . $limit;

            $remote = wp_remote_get( $profile_url );

            if ( ! is_wp_error( $remote ) ) {
                // certain ways of representing the html for double quotes causes errors so replaced here.
                $feed = json_decode( str_replace( '%22', '&rdquo;', $remote['body'] ), true );
            }

            if ( isset( $token['expires'] ) && new DateTime() > new DateTime ( $token['expires'] ) ) {
                $response = wp_remote_get( 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $token['token'] , array( 'timeout' => 60, 'sslverify' => false ) );
                if ( ! is_wp_error( $response ) ) {

                    // certain ways of representing the html for double quotes causes errors so replaced here.
                    $response = json_decode( str_replace( '%22', '&rdquo;', $response['body'] ), true );

                    // Get Current Time.
                    $date = new DateTime();

                    // Set Expiration Date After 30 Days.
                    $date->modify( '+30 days' );

                    update_user_meta( $user_id, 'youzify_wg_instagram_account_token', array( 'token' => $response['token'], 'expires' => $date->format( 'Y/m/d' ) ) );

                }
            }

            // Set Cache.
            set_transient( 'youzify_instagram_feed_' . $user_id, $feed, HOUR_IN_SECONDS );

        }

        return $feed;
    }

}