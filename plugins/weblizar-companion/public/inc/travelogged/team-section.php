<?php

defined( 'ABSPATH' ) or die();

class wl_companion_team_travel {
    
    public static function wl_companion_team_travel_html() {
    ?>
       <!--Team Section-->
        <section class="team_mbr space">
            <div class="container ">
                <!--section-title-->
                <div class="section-heading  text-center">
                <?php if ( ! empty ( get_theme_mod( 'travel_team_title' ) ) ) { ?>
                    <h2 class="section-title"><span><?php echo get_theme_mod( 'travel_team_title', 'Meet Our Team' ); ?></span></h2>
                <?php } if ( ! empty ( get_theme_mod( 'travel_team_desc' ) ) ) { ?>
                    <p> <?php echo get_theme_mod( 'travel_team_desc' ); ?> </p>
                <?php } ?>
                </div>
                <div class="row">
                <?php if ( ! empty ( get_theme_mod('travelogged_team_data' ) ) ) {
                    $name_arr = unserialize(get_theme_mod( 'travelogged_team_data'));
                    foreach ( $name_arr as $key => $value ) {
                ?>
                    <!--column-->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                    <?php if ( ! empty ( $value['team_image'] ) ) { ?>
                        <div class="mbr_img">
                            <img src="<?php echo esc_url( $value['team_image'] ); ?>" class="img-fluid" alt="<?php if ( ! empty ( $value['team_name'] ) ) {  esc_attr_e( $value['team_name'] ,WL_COMPANION_DOMAIN); } ?>"/>
                            <ul class="team_social justify-content-center">
                            <?php if ( ! empty ( $value['fb_link'] ) ) { ?>
                                <li>
                                    <a href="<?php echo esc_url( esc_attr( $value['fb_link'] ) ); ?>"> <i class="fab fa-facebook-f"></i> </a>
                                </li>
                            <?php } if ( ! empty ( $value['twitter_link'] ) ) { ?>
                                <li>
                                    <a href="<?php echo esc_url( esc_attr( $value['twitter_link'] ) ); ?>"> <i class="fab fa-twitter"></i> </a>
                                </li>
                            <?php } if ( ! empty ( $value['insta_link'] ) ) { ?>
                                <li>
                                    <a href="<?php echo esc_url( esc_attr( $value['insta_link'] ) ); ?>"> <i class="fab fa-instagram"></i> </a>
                                </li>
                            <?php } if ( ! empty ( $value['google_plus_link'] ) ) { ?>
                                <li>
                                    <a href="<?php echo esc_url( esc_attr( $value['google_plus_link'] ) ); ?>"> <i class="fab fa-google-plus-g"></i> </a>
                                </li>
                            <?php } if ( ! empty ( $value['youtube_link'] ) ) { ?>
                                <li>
                                    <a href="<?php echo esc_url( esc_attr( $value['youtube_link'] ) ); ?>"> <i class="fab fa-youtube"></i> </a>
                                </li>
                            <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                        <div class="mbr_name">
                            <h3><span><?php if ( ! empty ( $value['team_name'] ) ) {  esc_html_e( $value['team_name'] ,WL_COMPANION_DOMAIN); } ?></span></h3>
                            <p><?php if ( ! empty ( $value['team_designation'] ) ) {  esc_html_e( $value['team_designation'],WL_COMPANION_DOMAIN ); } ?></p>
                        </div>
                    </div>
                    <!--column-->
                    <?php } } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php 
    }
}

?>