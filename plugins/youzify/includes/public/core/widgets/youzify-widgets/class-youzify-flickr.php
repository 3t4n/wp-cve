<?php

class Youzify_Profile_Flickr_Widget {

    /**
     * Content.
     */
    function widget() {

        // Get User Data
        $flickr_id = get_the_author_meta( 'youzify_wg_flickr_account_id', bp_displayed_user_id() );

        if ( empty( $flickr_id ) ) {
            return;
        }

        $photos_number = youzify_option( 'youzify_wg_max_flickr_items', 6 );

        // Get Flickr Photos.
        $flickr_photos = $this->get_flickr_photos( $flickr_id, $photos_number );

        if ( ! $flickr_photos ) {
            return;
        }

        echo '<ul id="youzify-flickr-wg" class="youzify-photos-content youzify-flickr-photos">';

        foreach ( $flickr_photos as $photo ) : ?>

        <li>
            <figure class="youzify-project-item">
                <div class="youzify-projet-img"><img loading="lazy" <?php echo youzify_get_image_attributes_by_link( $photo['thumbnail'] ); ?> alt=""></div>
                <figcaption>
                    <a class="youzify-flickr-zoom" rel="nofollow noopener"><i class="fas fa-search"></i></a>
                    <a class="youzify-lightbox-img" rel="nofollow noopener" href="<?php echo $photo['full']; ?>" data-youzify-lightbox="youzify-flickr"></a>
                </figcaption>
            </figure>
        </li>

        <?php endforeach;

        echo '</ul>';

    }

    /**
     * Get Flickr Photos By User Id.
     */
    function get_flickr_photos( $user_id = false, $limit = 100 ) {

        // Get Feed.
        $feed = apply_filters( 'youzify_flickr_widget_get_transient', get_transient( 'youzify_flickr_feed_' . $user_id ) );

        if ( false === $feed ) {

            // Init Vars.
            $apiKey  = 'ed5819327dbcf671ce68e550e2b0d4d0';
            $method  = 'flickr.people.getPublicPhotos';

            // Get Data Link.
            $feed_url = "https://api.flickr.com/services/rest/?method=$method&api_key=$apiKey&user_id=$user_id&format=json&per_page=$limit&nojsoncallback=1";

            // Init Vars.;
            $feed = array();
            $remote = wp_remote_get( $feed_url );

            // Check if Url is Working.
            if ( is_wp_error( $remote ) ) {
                return false;
            }

            // Check If Url Is working.
            if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
               return false;
            }

            // GET User Data.
            $response = wp_remote_retrieve_body( $remote );

            if ( $response === false ) {
                return false;
            }

            // Decode Data.
            $data = json_decode( $response, true );

            if ( ! isset( $data['photos'] ) ) {
                return false;
            }

            foreach ( $data['photos']['photo'] as $photo ) :

                $photo_url = 'https://farm' . $photo['farm'] . '.static.flickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'];

                // Get Image Data.
                $image = array(
                    'full'      => $photo_url .'_z.jpg',
                    'thumbnail' => $photo_url .'_q.jpg',
                );

                // Fill Images with the new image item.
                array_push( $feed, $image );

            endforeach;

            // Set Cache.
            set_transient( 'youzify_flickr_feed_' . $user_id, $feed, HOUR_IN_SECONDS );
        }

        return $feed;
    }

}