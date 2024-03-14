<?php 
namespace Enteraddons\Widgets\Post_Grid\Traits;
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
use \Enteraddons\Classes\Helper;
trait Templates_Components {
	
    // Set Settings options
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    // POst title
    public static function title() {
        $settings = self::getSettings();
        echo '<'.esc_attr( $settings['title_tag'] ).' class="entry-title">';
            echo self::anchorOpen('enteraddons-entry-thumb');
                the_title();
            echo self::anchorEnd();
        echo '</'.esc_attr( $settings['title_tag'] ).'>';
    }

    // Anchor open
    public static function anchorOpen( $class = '' ) {
        return '<a class="'.esc_attr( $class ).'" href="'.esc_url( get_the_permalink() ).'">';
    }
    // Anchor end
    public static function anchorEnd() {
        return '</a>';
    }

    // Thumbnail Image
    public static function thumbImage() {
        the_post_thumbnail();
    }

    // Post Excerpt
    public static function excerpt() {
        $settings = self::getSettings();
        echo '<div class="entry-summery">';
        echo wp_trim_words( get_the_excerpt(), esc_html( $settings['excerpt_count']['size'] ) );
        echo '</div>';
    }
    // Post Excerpt
    public static function postMeta() {
        $settings = self::getSettings();
        if( !empty( $settings['show_meta']  ) ) {
        echo '<div class="post-meta-list">';
            //
            if( !empty( $settings['show_author']  ) ) {

                $icon = Helper::getElementorIcon( $settings['meta_author_icon'] );
                $getIcon = esc_html__( 'By ', 'enteraddons' );
                if( !empty( $icon ) ) {
                    $getIcon = $icon;
                }
                
                echo '<span class="meta-author">'.Helper::allowFormattingTagHtml($getIcon).self::author().'</span>';
            }
            //
            if( !empty( $settings['show_post_date']  ) ) {
                $icon = Helper::getElementorIcon( $settings['meta_date_icon'] );
                echo '<span class="posted-on">'.Helper::allowFormattingTagHtml($icon).self::postDate().'</span>';
            }
            //
            if( !empty( $settings['show_comments'] ) ) {
                $icon = Helper::getElementorIcon( $settings['meta_comments_icon'] );
                echo '<span class="post-comment">'.Helper::allowFormattingTagHtml($icon).self::comments().'</span>';
            }
        echo '</div>';
        }
    }
    // Post author
    public static function author() {
        return '<a href="'.esc_url( get_author_posts_url( get_the_author_meta('ID') ) ).'">'.esc_html( get_the_author() ).'</a>';
    }
    // Post categories
    public static function categories() {
        
    }
    // Post date
    public static function postDate() {
        $year  = get_the_time('Y');
        $month_link = get_the_time('m');
        $day   = get_the_time('d');
        $link = get_day_link( $year, $month_link, $day);
        return '<a href="'.esc_url( $link ).'">'.esc_html( get_the_date() ).'</a>';
    }
    // Post comments
    public static function comments() {

        $html = '';
        $html .= '<a href="'.esc_url( get_comments_link() ).'">';
            $comments_number = get_comments_number();
            if( $comments_number > 0 ) {
                if( $comments_number > 1 ) {
                    $html .= sprintf( esc_html__( '%s Comments', 'enteraddons' ) , $comments_number );
                } else {
                    $html .= sprintf( esc_html__( '%s Comment', 'enteraddons' ) , $comments_number );
                }
            } else {
                $html .= esc_html__( 'No Comment', 'enteraddons' );
            }


        $html .= '</a>';

        return $html;

    }

    protected static function button() {

        $settings = self::getSettings();
        
        if( $settings['show_more_btn'] != 'yes' ) {
            return;
        }
        
        // button icon position
        $iconLeft   = '';
        $iconRight  = '';

        if( $settings['icon_position'] == 'left' ) {
            $iconLeft = self::button_icon().' ';
        } else {
            $iconRight = ' '.self::button_icon();
        }

        echo self::anchorOpen('post-grid-btn').$iconLeft.esc_html( $settings['btn_text'] ).$iconRight.self::anchorEnd();
    }

    protected static function button_icon() {
        $settings = self::getSettings();
        return Helper::getElementorIcon( $settings['button_icon'] );
    }

}