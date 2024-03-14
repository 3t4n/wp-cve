<?php
/**
 * @param $query WP_Query
 */

?>
<?php if ( $query->have_posts() ) { ?>
    <?php while( $query->have_posts() ){ ?>
        <?php $query->the_post(); ?>
            <div class="nav-post">
                <?php if ( has_post_thumbnail( ) ) { ?>
                <div class="megamenu-post-thumbnail">
                    <?php
                        $url = get_the_post_thumbnail_url( null, 'medium');
                    ?>
                    <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" style="<?php echo esc_attr( 'background-image:url("'.esc_url( $url ).'")' ); ?>"></a>
                </div>
                <?php } ?>
                <h2 class="post-title"><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </div>
    <?php } ?>
<?php }

