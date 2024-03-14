<?php
/**
 * Timeline block one - php render 
 * 
 */
?>
<div class="cvmm-timeline-wrapper">
    <?php
        if( $contentType === 'custom' ) {
            foreach( $timelineRepeater as $key => $timeline ) {
        ?>
                <div class="cvmm-timeline-single-item">
                    <?php if( $thumbOption ) { ?>
                        <div class="cvmm-timeline-count">
                            <?php
                                if( isset( $timeline["timeline_image"] ) ) {
                            ?>
                                <figure class="cvmm-post-thumb">
                                    <img src=<?php echo esc_url( $timeline["timeline_image"] ); ?> } />
                                </figure>
                            <?php
                                }
                            ?>
                        </div>
                    <?php } ?>
                    <div class="cvmm-timeline-allcontent-wrap">
                        <?php if( $titleOption ) { ?>
                            <div class="cvmm-timeline-date">
                                <?php echo esc_attr( wp_date( "F d, Y", strtotime( $timeline["timeline_date"] ) ) ); ?>
                            </div>
                        <?php } ?>
                        <div class="cvmm-timeline-content-wrap">
                            <?php if( $titleOption ) { ?>
                                <div class="cvmm-timeline-title">
                                    <?php echo esc_html( $timeline["timeline_title"] ); ?>
                                </div>
                            <?php } ?>
                            <?php if( $contentOption ) { ?>
                                <div class="cvmm-timeline-desc">
                                    <?php echo esc_html( $timeline["timeline_desc"] ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div><!-- .cvmm-timeline-allcontent-wrap -->
                </div>
        <?php
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
                    ?>
                        <div class="cvmm-timeline-single-item">
                            <?php if( $thumbOption ) { ?>
                                <div class="cvmm-timeline-count">
                                    <?php
                                        if( has_post_thumbnail() ) {
                                    ?>
                                            <figure class="cvmm-post-thumb">
                                                <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" />
                                            </figure>
                                    <?php
                                        }
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="cvmm-timeline-allcontent-wrap">
                                <div class="cvmm-timeline-date">
                                    <?php echo get_the_date(); ?>
                                </div>
                                <div class="cvmm-timeline-content-wrap">
                                    <?php if( $titleOption ) { ?>
                                        <div class="cvmm-timeline-title">
                                            <a href="<?php the_permalink(); ?>" target="<?php echo esc_html( $permalinkTarget ); ?>" ><?php the_title() ?></a>
                                        </div>
                                    <?php } ?>
                                    <?php if( $contentOption ) { ?>
                                        <div class="cvmm-timeline-desc">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div><!-- .cvmm-timeline-allcontent-wrap -->
                        </div>
                    <?php
                endwhile;
            else :
                echo esc_html__( 'No posts found', 'wp-magazine-modules-lite' );
            endif;
        }
    ?>
</div>