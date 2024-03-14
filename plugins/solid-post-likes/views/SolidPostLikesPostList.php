<?php
namespace OACS\SolidPostLikes\Views;

/** This class manages the Likes output for the WordPress frontend posts list via shortcode [oacsspllist] */
if ( ! defined( 'WPINC' ) ) { die; }
class SolidPostLikesPostList
{
    public function oacs_spl_show_user_likes_post_list( $user ) {

    ?>
<ul>
    <?php
            $types = get_post_types(array( 'public' => true ));
            $args = array(
                  'numberposts'   => -1,
                  'post_type'     => $types,
                  'meta_query' => array(
                    array(
                        'key'     => '_oacs_spl_user_liked',
                        'value'   => $user,
                        'compare' => 'LIKE'
                    )
                ) );
            $sep = '';

            $wp_query = new \WP_Query($args);

    if ($wp_query->have_posts()) : ?>
    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
    <li>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
        <?php
    endwhile; ?>
    </li>
    <?php else : ?>
    <p><?php esc_html_e('You do not like anything yet.', 'oaspl'); ?></p>
    <?php
    endif;
    wp_reset_postdata(); ?>
</ul>
<?php
    }
}