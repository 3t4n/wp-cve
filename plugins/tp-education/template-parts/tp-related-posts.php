<?php
/**
 * Template part for displaying related posts for custom post type posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package TP Education
 * @since 1.0
 */

function tp_education_related_posts_content(){
?>
<div id="related-posts" class="two-columns">

    <?php
    $id = get_the_id();
    $post_type = get_query_var( 'post_type' );
    $taxonomy = get_post_taxonomies( $id );
    $terms = wp_get_post_terms( $id, $taxonomy, array( 'fields' => 'ids' ) );
    $args = array(
            'post_type'       => $post_type,  
            'posts_per_page'  => 2,
            'terms'           => $terms,
            'post__not_in'    => array( $id )
        );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) :
    ?>
        <h2 class="related-post-title"><?php _e( 'Related posts', 'tp-education' ); ?></h2>
        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <article id="post-3" class="column-wrapper blog-item has-post-thumbnail hentry">
                <div class="blog-post-wrap">
                    <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
                        <?php  
                        if ( has_post_thumbnail() ) { 
                            the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) );
                        } else {
                            echo '<img src="' . esc_url( TP_EDUCATION_URL_PATH ) . '/assets/images/demo-300x200.jpg" alt="' . the_title_attribute( array( 'echo' => false ) ) . '">';
                        }
                        ?>
                    </a><!-- .post-thumbnail -->

                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h2>

                        <?php
                        $post_type = get_query_var( 'post_type' );
                        switch ( $post_type ) {
                            case 'tp-class': ?>
                                <p class="tp-education-meta entry-meta">
                                    <?php  
                                    // class age group
                                    tp_class_age_group();

                                    // class size
                                    tp_class_size();

                                    if ( get_post_meta( get_the_id(), 'tp_class_cost_value', true ) != '' ) : 
                         
                                        // class cost
                                        tp_class_cost();    

                                        // class period
                                        tp_class_period();
                                    
                                    endif; ?>
                                </p><!-- .tp-education-meta -->
                            <?php break;

                            case 'tp-course': ?>
                                <p class="tp-education-meta entry-meta">
                                    <?php  
                                    // course type
                                    tp_course_type();

                                    // course duration
                                    tp_course_duration();
                                    ?>
                                </p><!-- .tp-education-meta -->
                            <?php break;

                            case 'tp-event': ?>
                                <p class="tp-education-meta entry-meta">
                                    <?php  
                                    // event date
                                    tp_event_date();

                                    // event start time
                                    tp_event_start_time();

                                    // event end time
                                    tp_event_end_time();

                                    // event location
                                    tp_event_location();
                                    ?>
                                </p><!-- .tp-education-meta -->
                            <?php break;

                            case 'tp-excursion': ?>
                                <p class="tp-education-meta entry-meta">
                                    <?php  
                                    // excursion start date
                                    tp_excursion_start_date();

                                    // excursion end date
                                    tp_excursion_end_date();

                                    // event end time
                                    tp_event_end_time();

                                    // excursion location
                                    tp_excursion_location();
                                    ?>
                                </p><!-- .tp-education-meta -->
                            <?php break;

                            case 'tp-team': ?>
                                <p class="tp-education-meta entry-meta">
                                    <?php  
                                    // team designation
                                    tp_team_designation();
                                    ?>
                                </p><!-- .tp-education-meta -->
                            <?php break;

                            case 'tp-affiliation': ?>
                                <p class="tp-education-meta entry-meta">
                                    <?php  
                                    // affiliation link
                                    tp_affiliation_link();
                                    ?>
                                </p><!-- .tp-education-meta -->
                            <?php break;
                            
                            default:
                            break;
                        }

                        ?>

                    </header><!-- .entry-header -->

                </div><!-- .blog-post-wrap -->
            </article><!-- #post-1 -->
        <?php endwhile; 
        wp_reset_postdata();
    endif; ?>
</div><!-- .two-columns -->
<?php }
add_action( 'tp_education_related_posts_content_action', 'tp_education_related_posts_content', 10 );