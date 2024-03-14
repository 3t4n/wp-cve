<?php 
namespace Enteraddons\Widgets\Profile_Card\Traits;
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

trait Template_2 {
    
    public static function markup_style_2() {

        $settings   = self::getSettings(); 

        ?>
        <div class="ea-sb-about-me profile-card-wrap">
          <div class="widget-main-content">
            <div class="sb-profile-avatar-wrap">
              <?php
                if( !empty( $settings['profile_avatar'] ) ) {
                  echo '<div class="sb-profile-avatar profile-avatar-img">';
                  self::thumbnail( $settings['profile_avatar'] );
                  echo '</div>';
                }
                //
                if( !empty( $settings['profile_name'] ) ) {
                  echo self::linkOpen().'<h3>';  
                  self::text( $settings['profile_name'] ); 
                  echo self::linkClose().'</h3>';
                }
                //
                if( !empty( $settings['profile_username'] ) ) {
                  echo '<h6>';
                  self::text( $settings['profile_username'] );
                  echo '</h6>';
                }
              ?>
            </div>
            <?php
            if( !empty( $settings['profile_description'] ) ) {
              echo '<p>';
                self::text( $settings['profile_description'] );
              echo '</p>';
            }
            //
            if ( !empty( $settings['signature_image'] ) ) {
              echo '<div class="signature">';
                self::thumbnail( $settings['signature_image'] );
              echo '</div>';
            }
            //
            if( !empty( $settings['profile_social_media_enable'] ) ):
            ?>
            <div class="about-socials">
              <?php
              if( !empty( $settings['subscription_text'] ) ){
                echo '<h6>';
                  self::text( $settings['subscription_text'] );
                echo '</h6>';
              }
              //  
              if( !empty( $settings['icon_list'] ) ) {
                echo '<div class="about-social-icons">';
                foreach( $settings['icon_list'] as $item ) {
                  self::socialIcon( $item ); 
                }
                echo '</div>';
              }
              ?>
            </div>
            <?php 
            endif;
            ?>
          </div>
        </div>
        <?php
    }


}