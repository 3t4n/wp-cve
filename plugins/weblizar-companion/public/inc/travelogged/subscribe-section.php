<?php

defined( 'ABSPATH' ) or die();

class wl_companion_subscribe_travel {
    
    public static function wl_companion_subscribe_travel_html() {
    ?>
        <!--Subscribe Section-->
        <section class="subscribe space">
            <div class="container">
                <div class="subscribe-cont">
                    <h3> <?php  esc_html_e( get_theme_mod( 'travelogged_subscribe_title','Subscribe') ,WL_COMPANION_DOMAIN); ?> </h3>
                    <h2> <?php  esc_html_e( get_theme_mod( 'travelogged_subscribe_title1','FOR NEWSLETTER'),WL_COMPANION_DOMAIN ); ?></h2>
                    <p> <?php  esc_html_e( get_theme_mod( 'travelogged_subscribe_desc' ),WL_COMPANION_DOMAIN ); ?></p>
                </div>
                <div class="input-group row mt-4">
                    <input type="email" class="form-control" id="subscribe_mail" placeholder="Enter your email">
                    <button type="button" id="subscribe_home_btn" class="btn mb-2">
                        <?php  esc_html_e( get_theme_mod( 'travelogged_subscribe_btntext','Subscribe'),WL_COMPANION_DOMAIN ); ?>
                    </button>
                </div>
            </div>
        </section>
    <?php 
    }
}

?>