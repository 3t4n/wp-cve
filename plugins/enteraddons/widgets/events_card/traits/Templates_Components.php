<?php 
namespace Enteraddons\Widgets\Events_Card\Traits;
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

    protected static function title() {
        $settings = self::getSettings();
        if( !empty( $settings['title'] ) ) {
            $tag = $settings['title_tag'];
            echo '<'.esc_attr( $tag ).' class="el-event-title">'.esc_html( $settings['title'] ).'</'.esc_attr( $tag ).'>';
        }
    }

    protected static function image() {
        $settings = self::getSettings();
        if( !empty( $settings['img']['url'] ) ) {
            echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'img' );
        }
    }
    protected static function eventType() {
        $settings = self::getSettings();        
        if( !empty( $settings['event_type'] ) ) {
            echo '<span class="el-event-cat">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['event_type_icon'] ).esc_html( $settings['event_type'] ).'</span>';
        }
    }
    protected static function shortDescription() {
        $settings = self::getSettings();
        if( !empty( $settings['event_description'] ) ) {
            echo '<div class="el-short-des">'.wp_kses( $settings['event_description'], 'post' ).'</div>';
        }
    }
    protected static function eventPlace() {
        $settings = self::getSettings();
        
        if( !empty( $settings['event_place'] ) ) {
            echo '<span class="el-event-place">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['event_place_icon'] ).esc_html( $settings['event_place'] ).'</span>';
        }
        
    }
    protected static function eventDate() {
        $settings = self::getSettings();
        if( !empty( $settings['event_date'] ) ) {
            echo '<span class="enteraddons-date el-event-date '.esc_attr( $settings['date_position'] ).'">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['event_date_icon'] ).\Enteraddons\Classes\Helper::allowFormattingTagHtml( $settings['event_date'] ).'</span>';
        }
        
    }
    protected static function eventTime() {
        $settings = self::getSettings();
        if( !empty( $settings['event_time'] ) ) {
            echo '<span class="el-event-time">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['event_time_icon'] ).esc_html( $settings['event_time'] ).'</span>';
        }
    }
    protected static function eventPrice() {
        $settings = self::getSettings();
        if( !empty( $settings['event_ticket_price'] ) ) {
            echo '<span class="el-event-price">'.\Enteraddons\Classes\Helper::allowFormattingTagHtml( $settings['event_ticket_price'] ).'</span>';
        }        
    }
    protected static function button() {
        $settings = self::getSettings();
        $label     = !empty( $settings['btn_label'] ) ?  $settings['btn_label'] : esc_html__( 'DETAILS', 'enteraddons' );
        echo \Enteraddons\Classes\Helper::getElementorLinkHandler( $settings['btn_link'], $label, 'enteraddons-btn');
    }

}