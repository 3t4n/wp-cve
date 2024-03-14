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

trait Template_1 {
    
    public static function markup_style_1() {
        $settings   = self::getSettings(); 
        
        ?>
        <div class="ea-profile-card profile-card-wrap">
            <?php
            if( !empty( $settings['profile_cover']['url'] ) ) {
                echo '<div class="profile-cover">';
                    self::thumbnail( $settings['profile_cover'] );
                echo '</div>';
            }
            //
            if( !empty( $settings['profile_avatar']['url'] ) ):
            ?>
            <div class="profile-avatar-wrap">
                <div class="profile-avatar profile-avatar-img">
                    <?php
                    // 
                    self::thumbnail( $settings['profile_avatar'] );
                    //
                    if( !empty( $settings['profile_status_enable'] ) ) {
                        echo '<span class="sb-status">';
                        \Elementor\Icons_Manager::render_icon( $settings['profile_status_icon'], [ 'aria-hidden' => 'true' ] );
                        echo '</span>';
                    }
                    ?>
                </div>
            </div>
            <?php 
            endif;
            ?>
            <div class="profile-content">
                <?php
                if( !empty( $settings['profile_name'] ) ) {
                echo '<div class="profile-title">';
                    echo self::linkOpen();
                        echo '<h3>';
                            self::text( $settings['profile_name'] ); 
                        echo '</h3>';
                    echo self::linkClose();
                echo '</div>';
                }
                //
                if( !empty( $settings['profile_username'] ) ) {
                    echo '<div class="profile-meta"><span class="sb-name">';
                        self::text( $settings['profile_username'] );
                    echo '</span></div>';
                }
                //
                if( !empty( $settings['show_profile_ratings'] ) ) {
                    echo '<div class="sb-star-rating">';
                        self::star();
                    echo '</div>';
                }
                ?>
                <div class="profile-info"> 
                    <?php 
                    if( !empty( $settings['followers_amount'] ) ){
                        echo '<span class="follower">';
                        self::text( $settings['followers_amount'] ); 
                        echo '</span>';
                    }
                    //
                    if( !empty( $settings['profile_link_text'] ) ){
                        echo self::linkOpen();
                        self::text( $settings['profile_link_text'] );
                        echo self::linkClose(); 
                    }
                     
                    ?>
                </div>
            </div>
        </div>
        <?php
    }


}