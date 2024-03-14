<?php
/**
 * Ticker block default - php render 
 * 
 */
?>
<div class="cvmm-ticker-wrapper">
    <?php
        if( !empty( $tickerCaption ) ) {
            echo '<span class="cvmm-ticker-caption">'.esc_html( $tickerCaption ).'</span>';
        }

        $marqueeAttr = ' data-duration='.esc_html( $marqueeDuration ).' data-direction='.esc_attr( $marqueeDirection ).' data-start='.esc_attr( $marqueeStart );

    ?>
        <div class="cvmm-ticker-content" <?php echo esc_attr( $marqueeAttr ); ?>>
        <?php
            if( $contentType === 'custom' ) {
                foreach( $tickerRepeater as $ticker ) {
                    echo '<div class="cvmm-ticker-single-title">'.esc_html( $ticker['ticker_title'] ).'</div>';
                }
            } else {
                $post_query = new WP_Query( array(
                    'status'    => 'publish',
                    'post_type' => 'post',
                    'cat'       => esc_attr( $postCategory ),
                    'posts_per_page' => absint( $postCount )
                ));
                if( $post_query->have_posts() ) :
                    while( $post_query->have_posts() ) : $post_query->the_post();
                        echo '<div class="cvmm-ticker-single-title"><a href="'.get_the_permalink().'" target="'.esc_html( $permalinkTarget ).'">'.get_the_title().'</a></div>';
                    endwhile;
                else :
                    echo esc_html__( 'No posts found', 'wp-magazine-modules-lite' );
                endif;
            }
        ?>
        </div><!-- .cvmm-ticker-content -->
</div>