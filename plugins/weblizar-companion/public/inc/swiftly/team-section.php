<?php

defined( 'ABSPATH' ) or die();

class wl_companion_teams_swiftly {
    
    public static function wl_companion_team_swiftly_html() { ?>
    
        <section class="container-fluid">
            <?php
            $enigma_team_title = get_theme_mod('enigma_team_title', 'Our Team');
            if ( !empty($enigma_team_title )) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-md-12 heading-section text-center ftco-animate">
                                <h2 class="mb-4"><?php echo get_theme_mod( 'enigma_team_title', 'Our Team' ); ?></h2>
                            </div>
                        </div>
                    </div>
                </div> <?php 
            } ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                            if ( ! empty ( get_theme_mod('enigma_team_data' ) ) ) :
                                $name_arr = unserialize(get_theme_mod( 'enigma_team_data'));

                                $team_name_array_number_have = count( $name_arr ); 
                                if( $team_name_array_number_have >= 3 ) : 

                                ?><div id="news-slider" class="owl-carousel"> <?php
                                    foreach( $name_arr as $key => $value  ) : ?>

                                        <div class="team-slide">
                                            <div class="post-img">
                                                <img src="<?php echo esc_url( $value['team_image'] ); ?>" alt="">
                                                <span class="over-layer"></span>
                                            </div>
                                            <div class="team-content">
                                                <h3 class="team-title">
                                                    <?php if ( ! empty ( $value['team_name'] ) ) {  esc_html_e( $value['team_name'] ,WL_COMPANION_DOMAIN); } ?>
                                                </h3>
                                                <p class="team-description">
                                                    <?php if ( ! empty ( $value['team_designation'] ) ) {  esc_html_e( $value['team_designation'],WL_COMPANION_DOMAIN ); } ?>
                                                </p>
                                                <div class="enigma_team_showcase_overlay">
                                                    <div class="enigma_team_showcase_overlay_inner ">
                                                        <div class="enigma_team_showcase_icons">
                                                            <a href="<?php echo esc_url( esc_attr( $value['fb_link'] ) ); ?>"><i class="fab fa-facebook"></i></a>
                                                            <a href="<?php echo esc_url( esc_attr( $value['twitter_link'] ) ); ?>"><i class="fab fa-twitter"></i></a>
                                                            <a href="<?php echo esc_url( esc_attr( $value['insta_link'] ) ); ?>"><i class="fab fa-instagram"></i></a>
                                                            <a href="<?php echo esc_url( esc_attr( $value['google_plus_link'] ) ); ?>"><i class="fab fa-google-plus"></i></a>
                                                        </div>
                                                    </div>
                                                </div>	
                                            </div>
                                        </div> <?php 
                                    endforeach;

                                    else:
                                        ?><div id="team-section-slider"><?php
                                            echo __( "Please, add Three Minimum Team Members Details To Show On Home Page in Our Team Section.", WL_COMPANION_DOMAIN );
                                        ?></div><?php
                                endif;
                                
                                else:
                                    ?><div id="team-section-slider"><?php
                                        echo __( "Please, add Three Minimum Team Members Details To Show On Home Page in Our Team Section.", WL_COMPANION_DOMAIN );
                                    ?></div><?php
                               ?></div><?php
                            endif; 
                        ?>
                    </div>
                </div>
            </div>
        </section>
        
    <?php 
    }
} ?>