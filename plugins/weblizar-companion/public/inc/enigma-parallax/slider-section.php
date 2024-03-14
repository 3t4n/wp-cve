<?php

defined( 'ABSPATH' ) or die();

class wl_companion_sliders_enigma_parallax
{

    public static function wl_companion_sliders_enigma_parallax_html() {
    ?>
        <?php
        $slider_anim = get_theme_mod('slider_anim');
        if ($slider_anim == 'fadeIn') {
            $slider_class = 'fadein';
        } else {
            $slider_class = 'slide';
        }
        ?>
        <?php
        if ( get_theme_mod( 'slider_choise', '1' ) == '1' ) {   ?>
                <div  id="slider"></div>
                <?php if ( ! empty ( get_theme_mod('enigma_slider_data' ) ) ) { ?>
                <div id="myCarousel" class="carousel  <?php esc_attr_e($slider_class,WL_COMPANION_DOMAIN); ?>" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php

                        $j = 1;
                        $name_arr = unserialize(get_theme_mod( 'enigma_slider_data'));
                        foreach ( $name_arr as $key => $value ) {


                            if ( ! empty ( $value['slider_image']) ) { ?>
                                <div class="carousel-item <?php if ($j == 1) echo esc_attr("active"); ?>">
                                    <img src="<?php echo esc_url($value['slider_image']); ?>"
                                         class="img-responsive"
                                         alt="<?php if ( ! empty ( $value['slider_name'] ) ) { esc_attr_e( $value['slider_name'],WL_COMPANION_DOMAIN ); } ?>">
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <div class="carousel-text">
                                                <?php
                                                $animate_type_title = get_theme_mod('animate_type_title');
                                                if ( ! empty ( $value['slider_name'] ) ) { ?>
                                                    <h1 class="animated <?php if (!empty ($animate_type_title)) {
                                                         esc_attr_e(get_theme_mod('animate_type_title'),WL_COMPANION_DOMAIN);
                                                    } else esc_attr_e('bounceInRight',WL_COMPANION_DOMAIN); ?>"><?php esc_html_e( $value['slider_name'] ,WL_COMPANION_DOMAIN); ?>
                                                    </h1>
                                                <?php }
                                                $animate_type_desc = get_theme_mod('animate_type_desc');
                                                if ( ! empty ( $value['slider_desc'] ) ) { ?>
                                                    <ul class="list-unstyled carousel-list">
                                                        <li class="animated <?php if (!empty ($animate_type_desc)) {
                                                             esc_attr_e(get_theme_mod('animate_type_desc'),WL_COMPANION_DOMAIN);
                                                        } else esc_attr('bounceInLeft'); ?>">
                                                        <?php  esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?>
                                                        </li>
                                                    </ul>
                                                <?php }
                                                if ( ! empty ( $value['slider_link'] ) ) { ?>
                                                    <a class="enigma_blog_read_btn animated bounceInUp"
                                                       href="<?php echo esc_url($value['slider_link']);  ?>"
                                                       role="button">
                                                       <?php if ( ! empty ( $value['slider_text'] ) ) {  esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN); } ?> </a>
                                                <?php } ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php $j++;
                            }
                        } ?>
                    </div>
                    <ol class="carousel-indicators">
                        <?php for ($i = 0; $i<$j-1; $i++) { ?>
                            <li data-target="#myCarousel" data-slide-to="<?php esc_attr_e($i,WL_COMPANION_DOMAIN); ?>" <?php if ($i==0) {
                             echo esc_attr('class="active"');
                            } ?> ></li>
                        <?php } ?>
                    </ol>
                    <a class="carousel-control-prev" href="#myCarousel" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                    <a class="carousel-control-next" href="#myCarousel" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                    <div class="enigma_slider_shadow"></div>
                </div>
                <!-- /.carousel -->
            <?php }else{ ?>

                <!-- Carousel-->
                <div  id="slider"></div>
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                      <?php
                       $j = 1;
                        for( $i=1; $i<=3; $i++ ) {
                        if ( ! empty ( get_theme_mod( 'slide_image_'.$i) ) ) {  ?>
                        <div class="carousel-item <?php if ( $j == 1 ) echo esc_attr("active"); ?>">
                            <img src="<?php echo esc_url( get_theme_mod( 'slide_image_'.$i ) ); ?>" class="img-responsive" alt="<?php  esc_attr_e( get_theme_mod( 'slide_title_'.$i, 'Contrary to popular' ),WL_COMPANION_DOMAIN ); ?>">
                            <div class="container">
                                <div class="carousel-caption">
                                    <?php if ( ! empty ( get_theme_mod( 'slide_title_'.$i, 'Contrary to popular' ) ) ) {  ?>
                                    <div class="carousel-text">
                                        <h1 class="animated <?php if ( ! empty ( get_theme_mod( 'animate_type_title' ) ) ) {  esc_attr_e( get_theme_mod( 'animate_type_title' ),WL_COMPANION_DOMAIN ); } else  esc_attr_e( 'bounceInRight',WL_COMPANION_DOMAIN ); ?> head_<?php  esc_attr_e( $i ,WL_COMPANION_DOMAIN) ?>"><?php esc_html_e( get_theme_mod( 'slide_title_'.$i, 'Contrary to popular' ),WL_COMPANION_DOMAIN ); ?>
                                        </h1>
                                        <?php } if ( ! empty ( get_theme_mod( 'slide_desc_'.$i, 'Lorem Ipsum is simply dummy text of the printing' ) ) ) {  ?>
                                        <ul class="list-unstyled carousel-list">
                                            <li class="animated <?php if ( ! empty ( get_theme_mod( 'animate_type_desc' ) ) ) {  esc_attr_e( get_theme_mod( 'animate_type_desc' ) ,WL_COMPANION_DOMAIN); } else esc_attr( 'bounceInLeft' ); ?> desc_<?php esc_attr_e( $i,WL_COMPANION_DOMAIN ) ?>"><?php echo wp_kses_post( get_theme_mod( 'slide_desc_'.$i, 'Lorem Ipsum is simply dummy text of the printing' ) ); ?></li>
                                        </ul>
                                        <?php } if ( ! empty ( get_theme_mod( 'slide_btn_text_'.$i, 'Read More' ) ) ) { ?>
                                            <a class="enigma_blog_read_btn animated bounceInUp rdm_<?php esc_attr_e( $i,WL_COMPANION_DOMAIN ) ?>" href="<?php if ( ! empty ( get_theme_mod( 'slide_btn_link_'.$i, '#' ) ) ) { echo esc_url( get_theme_mod( 'slide_btn_link_'.$i, '#' ) ); } ?>" role="button"><?php esc_html_e( get_theme_mod( 'slide_btn_text_'.$i, 'Read More' ),WL_COMPANION_DOMAIN ); ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $j++; }  } ?>
                    </div>
                    <ol class="carousel-indicators">
                        <?php for( $i=0; $i<$j-1; $i++ ) { ?>
                            <li data-target="#myCarousel" data-slide-to="<?php esc_attr_e( $i ,WL_COMPANION_DOMAIN); ?>" <?php if( $i == 0 ) { echo esc_attr('class="active"'); } ?> ></li>
                        <?php } ?>
                    </ol>
                    <a class="carousel-control-prev" href="#myCarousel" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                    <a class="carousel-control-next" href="#myCarousel" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                    <div class="enigma_slider_shadow"></div>
                </div><!-- /.carousel -->
            <?php  }  } elseif ( get_theme_mod( 'slider_choise', '1' ) == '2' ) { ?>
                <!-- Swiper -->
                <div class="swiper-container swiper-container-slider2">
                    <?php if ( ! empty ( get_theme_mod('enigma_slider_data' ) ) ) { ?>
                  <div class="swiper-wrapper">
                    <?php
                    $name_arr = unserialize(get_theme_mod( 'enigma_slider_data'));
                    foreach ( $name_arr as $key => $value ) { ?>
                        <div class="swiper-slide">
                        <img src="<?php echo esc_url($value['slider_image']); ?>"/>
                        <div class="container">
                            <div class="carousel-caption">
                              <?php if ( ! empty ( $value['slider_name'] ) ) {  ?>
                              <div class="carousel-text">
                                <h1 class="animated animation animated-item-2 head_<?php esc_attr_e( $i,WL_COMPANION_DOMAIN ) ?>"><?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></h1>
                                <?php if ( ! empty ( $value['slider_desc'] ) ) {  ?>
                                <ul class="list-unstyled carousel-list">
                                  <li class="animated animation animated-item-3 desc_<?php esc_attr_e( $i ,WL_COMPANION_DOMAIN) ?>"><?php esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?>
                                </ul>
                                <?php } if ( ! empty ( $value['slider_link'] ) )  { ?>
                                  <a class="enigma_blog_read_btn  animation animated-item-3" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php if ( ! empty ( $value['slider_text'] ) ) {  esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN); } ?></a>
                                <?php } ?>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                    <?php } ?>
                  </div>
                  <?php } else { ?>

                    <!-- Swiper -->
                      <div class="swiper-wrapper">
                        <?php for ( $i=1; $i<=3; $i++ ) { ?>
                        <div class="swiper-slide">
                        <img src="<?php echo esc_url( get_theme_mod( 'slide_image_'.$i ) ); ?>"/>
                        <div class="container">
                            <div class="carousel-caption">
                              <?php if ( ! empty ( get_theme_mod( 'slide_title_'.$i, 'Contrary to popular' ) ) ) {  ?>
                              <div class="carousel-text">
                                <h1 class="animated animation animated-item-2 head_<?php esc_attr_e( $i ,WL_COMPANION_DOMAIN) ?>"><?php esc_html_e( get_theme_mod( 'slide_title_'.$i, 'Contrary to popular' ),WL_COMPANION_DOMAIN ); ?></h1>
                                <?php if ( ! empty ( get_theme_mod( 'slide_desc_'.$i, 'Lorem Ipsum is simply dummy text of the printing' ) ) ) {  ?>
                                <ul class="list-unstyled carousel-list">
                                  <li class="animated animation animated-item-3 desc_<?php esc_attr_e( $i ,WL_COMPANION_DOMAIN) ?>"><?php echo wp_kses_post( get_theme_mod( 'slide_desc_'.$i, 'Lorem Ipsum is simply dummy text of the printing' ) ); ?></li>
                                </ul>
                                <?php } if ( ! empty ( get_theme_mod( 'slide_btn_text_'.$i, 'Read More' ) ) ) { ?>
                                  <a class="enigma_blog_read_btn  animation animated-item-3 rdm_<?php esc_attr_e( $i ,WL_COMPANION_DOMAIN) ?>" href="<?php if ( ! empty ( get_theme_mod( 'slide_btn_link_'.$i, '#' ) ) ) { echo esc_url( get_theme_mod( 'slide_btn_link_'.$i, '#' ) ); } ?>" role="button"><?php  esc_html_e( get_theme_mod( 'slide_btn_text_'.$i, 'Read More' ),WL_COMPANION_DOMAIN ); ?></a>
                                <?php } ?>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                  <?php  }?>
                  <!-- Add Pagination -->
                    <div class="swiper-pagination swiper'"></div>
                    <div class="swiper-button-next swiper' swiper-button-next-cont swiper-button-white"></div>
                    <div class="swiper-button-prev swiper' swiper-button-prev-cont swiper-button-white"></div>
                </div>
    <?php }

    }
} ?>