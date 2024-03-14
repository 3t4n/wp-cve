<?php
namespace OACS\SolidPostLikes\Views;

/** This class manages the Likes output for the WordPress backend admin user profile */
if ( ! defined( 'WPINC' ) ) { die; }
class SolidPostLikesUserProfile
{

  public function oacs_spl_show_user_likes( $user )

  {

		$oacs_spl_show_likes_setting = carbon_get_theme_option('oacs_spl_show_user_profile_likes');

        if ($oacs_spl_show_likes_setting) {
            ?>
<table class="form-table">
    <tr>
        <th><label for="user_likes"><?php esc_html_e('You Like:', 'oaspl'); ?></label></th>
        <td>
            <?php
                $types = get_post_types(array( 'public' => true ));
            $args = array(
                  'numberposts'   => -1,
                  'post_type'     => $types,
                  'meta_query'    => array(
                    array(
                      'key'       => '_oacs_spl_user_liked',
                      'value'     => $user->ID,
                      'compare'   => 'LIKE'
                    )
                  ) );
            $sep = '';

            $wp_query = new \WP_Query($args);

            if ($wp_query->have_posts()) : ?>
            <p>
                <?php while ($wp_query->have_posts()) : $wp_query->the_post();
            echo esc_html($sep); ?><a href="<?php the_permalink(); ?>"
                    title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                <?php
                $sep = ' &middot; ';
            endwhile; ?>
            </p>
            <?php else : ?>
            <p><?php esc_html_e('You do not like anything yet.', 'oaspl'); ?></p>
            <?php
                endif;
            wp_reset_postdata(); ?>
        </td>
    </tr>
</table>
<?php
        } else {
			// ...
       }
    }
}