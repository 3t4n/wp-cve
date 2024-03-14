<?php 
namespace Enteraddons\Widgets\Team\Traits;
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

    // Name
    public static function name() {

        $settings = self::getSettings();

        if( !empty( $settings['name'] ) ) {
            echo '<h5 class="team-title">'.esc_html( $settings['name'] ).'</h5>';
        }
    }

    // Designation
    public static function designation() {
        $settings = self::getSettings();

        if( !empty( $settings['designation'] ) ) {
            echo '<p class="team-designation">'.esc_html( $settings['designation'] ).'</p>';
        }
    }

    // Experience
    public static function experience() {
        $settings = self::getSettings();

        if( !empty( $settings['experience'] ) ) {
            echo '<span class="enteradd--experience">'.esc_html( $settings['experience'] ).'</span>';
        }

    }

    // Descriptions
    public static function descriptions() {

        $settings = self::getSettings();
        if( !empty( $settings['descriptions'] ) ) {
            echo '<p class="enteradd--descriptions descriptions">'.esc_html( $settings['descriptions'] ).'</p>';
        }
    }

    // Link
    public static function link() {

        $settings = self::getSettings();
        $label     = !empty( $settings['link_label'] ) ?  $settings['link_label'] : esc_html__( 'VIEW DETAILS', 'enteraddons' );
        echo \Enteraddons\Classes\Helper::getElementorLinkHandler( $settings['more_link'], $label, 'readme-more-link');
    }

    // Image
    public static function image() {
        $settings = self::getSettings();

        if( !empty( $settings['image']['url'] ) ) {
            echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
        }
    }

    // Social Icon
    public static function socialIcons() {
        $settings = self::getSettings();
        
        if( !empty( $settings['social'] ) ):
        ?>
        <div class="enteraddons-team-social-icon">
            <?php 
            foreach( $settings['social'] as $social ) {
                $icon = \Enteraddons\Classes\Helper::getElementorIcon( $social['social_icon'] );
                echo \Enteraddons\Classes\Helper::getElementorLinkHandler( $social['social_url'], $icon );
            }
            ?>
        </div>
        <?php
        endif;
    }

    // Template 2
    // Name
    public static function name2() {

        $settings = self::getSettings();

        if( !empty( $settings['name'] ) ) {
            echo '<h5 class="ea-team-title">'.esc_html( $settings['name'] ).'</h5>';
        }
    }

    // Designation
    public static function designation2() {
        $settings = self::getSettings();

        if( !empty( $settings['designation'] ) ) {
            echo '<span class="ea-hcard-subtitle">'.esc_html( $settings['designation'] ).'</span>';
        }
    }
    //Hover Title
    public static function hover_title() {

        $settings = self::getSettings();

        if( !empty( $settings['hover_title'] ) ) {
            echo '<h5 class="ea-team-hover-title">'.esc_html( $settings['hover_title'] ).'</h5>';
        }
    }

    // Hover Sub Title
    public static function hover_subtitle() {
        $settings = self::getSettings();

        if( !empty( $settings['hover_subtitle'] ) ) {
            echo '<span class="ea-hover-subtitle">'.esc_html( $settings['hover_subtitle'] ).'</span>';
        }
    }

}