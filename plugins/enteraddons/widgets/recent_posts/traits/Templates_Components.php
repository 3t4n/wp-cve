<?php 
namespace Enteraddons\Widgets\Recent_Posts\Traits;
/**
 * Enteraddons template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Templates_Components {
	
    // Set Settings options
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    // Post title
    public static function title() {
        $settings = self::getSettings();
        echo '<'.esc_attr( $settings['title_tag'] ).' class="ea-repost-title">';
            echo self::anchorOpen('ea-repost-title-tag');
                the_title();
            echo self::anchorEnd();
        echo '</'.esc_attr( $settings['title_tag'] ).'>';
    }

    // Thumbnail Image
    public static function thumbImage() {
        the_post_thumbnail();
    }
   
    //  Post Date
    public static function date() {
        $settings = self::getSettings();
        if( !empty( $settings['show_post_date']  ) ) {
            echo '<span class="ea-posted-on">'.self::postDate().'</span>';
        }
        
    }

    // Post date
    public static function postDate() {
        $year  = get_the_time('Y');
        $month_link = get_the_time('m');
        $day   = get_the_time('d');
        $link = get_day_link( $year, $month_link, $day);
        return '<a class="ea-date-link" href="'.esc_url( $link ).'">'.esc_html( get_the_date() ).'</a>';
    }

     // Anchor open
     public static function anchorOpen( $class = '' ) {
        return '<a class="'.esc_attr( $class ).'" href="'.esc_url( get_the_permalink() ).'">';
    }
    // Anchor end
    public static function anchorEnd() {
        return '</a>';
    }

}