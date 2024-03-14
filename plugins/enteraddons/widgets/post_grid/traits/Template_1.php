<?php 
namespace Enteraddons\Widgets\Post_Grid\Traits;
/**
 * Enteraddons team template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Template_1 {
	
	public static function markup_style_1() {
        $settings = self::getSettings();
		?>

        <div class="enteraddons-post-grid-wrapper enteraddons-grid-col-<?php echo esc_attr( $settings['column'] ); ?>">
            <?php
            $args = [ 'post_type' => 'post', 'posts_per_page' => esc_attr( $settings['limit'] ), 'order' => esc_attr($settings['order']) ];

            if( !empty( $settings['post_offset'] ) ) {
                $args['offset'] = esc_html( $settings['post_offset'] );
            }

            $query = new \WP_Query($args);
            if( $query->have_posts() ):
                while( $query->have_posts() ):
                    $query->the_post();
            ?>
            <!-- Single Blog -->
            <div class="enteraddons-single-blog blog-style--six">
                <?php 
                // Blog Header
                if( !empty( $settings['show_thumbnail'] ) ) {

                    echo '<div class="enteraddons-entry-header position-relative '.esc_attr( $settings['img_hover_animation'] ).'">';
                        echo self::anchorOpen('enteraddons-entry-thumb');
                        self::thumbImage();
                        echo self::anchorEnd();
                        // Meta On thumbnail
                        if( $settings['meta_position'] == 'on_thumbnail' ) {
                            self::postMeta();
                        }
                    echo '</div>';
                }
                //
                ?>
                <div class="enteraddons-entry-body">                  
                    <?php
                    // Post meta before title
                    if( $settings['meta_position'] == 'before_title' ) {
                    self::postMeta();
                    }
                    // Post Title
                    self::title();
                    // Post meta After title
                    if( $settings['meta_position'] == 'after_title' ) {
                    self::postMeta();
                    }
                    // Post Excerpt
                    if( !empty( $settings['show_desc'] ) ) {
                        self::excerpt();
                    }
                    // Read more button
                    self::button();

                    ?>

                </div>
                <!-- End Blog Body -->
            </div>
                        
            <!-- End Single Blog -->
            <?php 
            endwhile;
            else:
                echo '<p>'.esc_html__( 'No post found.', 'enteraddons' ).'</p>';
            endif;
            wp_reset_postdata();
            ?>
        </div>
		<?php
	}

}