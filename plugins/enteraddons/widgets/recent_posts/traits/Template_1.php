<?php 
namespace Enteraddons\Widgets\Recent_Posts\Traits;
/**
 * Enteraddons Recent Posts template class
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
		
        $args = [ 'post_type' => 'post', 'posts_per_page' => esc_attr( $settings['limit'] )];
        $query = new \WP_Query($args);
        if( $query->have_posts() ):
            echo '<div class="ea-recent-posts-wrap">';
            while( $query->have_posts() ):
                $query->the_post(); 
                echo '<div class="ea-recent-posts">';
                   if( !empty( $settings['show_post_image']  ) ) {
                        echo '<div class="ea-repost-image">';
                            echo self::anchorOpen('ea-repost-thumb');
                                self::thumbImage(); 
                            echo self::anchorEnd(); 
                        echo '</div>';   
                   } 
                    echo '<div class="ea-repost-content">';
                            self::date(); 
                            self::title(); 
                    echo '</div>';
                echo '</div>'; 
                
            endwhile;
            echo '</div>';
        endif;
	}

}