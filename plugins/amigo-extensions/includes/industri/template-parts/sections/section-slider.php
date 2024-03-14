<?php 
/**
 * displaying home page slider
 * 
 *
 * @package Aqwa WordPress Theme
 */
?>
<?php
add_action( 'Industri_Homepage_Sections', 'industri_slider_section', 10 );
function industri_slider_section(){ 
    $slider_items = get_theme_mod( 'slider_items', amigo_industri_slider_section_default() );
    if ( empty( $slider_items ) ) { return; }
    $slider_items = json_decode( $slider_items );       
    ?>
    <main>
        <div class="swiper-container">  

	<button type="button" class="btn swiper-button-prev swiper-button-white"></button>
      <button type="button" class="btn swiper-button-next swiper-button-white"></button>
    <div class="swiper-pagination"></div>		
            <div class="swiper-wrapper">
                <?php foreach ( $slider_items as $slide ) { ?>
                    <div class="swiper-slide slider-item">
                        <?php if( !empty( $slide->image_url ) ){ ?>
                            <img src="<?php echo esc_url( $slide->image_url ) ?>" class="img-fluid" alt="<?php echo esc_html( $slide->title ) ?>" />
                        <?php } ?>
                        <div class="slider-overlay">
                            <div class="container content-center">
                                <div class="slide-content">
                                    <?php if( !empty( $slide->title ) ){ ?>
                                        <h4><span class="animation-ripple"> <i class="fa <?php echo esc_html( $slide->icon_value ) ?>"> </i> </span> <?php echo esc_html( $slide->title ) ?>
                                    </h4>
                                <?php } ?>
                                <?php if( !empty( $slide->subtitle ) ){ ?>
                                    <h2 class="title"><?php echo esc_html( $slide->subtitle ) ?></h2>
                                <?php } ?>
                                <?php if( !empty( $slide->text) ){ ?>
                                    <p><?php echo esc_html( $slide->text ) ?></p>
                                <?php } ?>
                                <div class="slider-btn">
                                 <?php if( !empty( $slide->text2 ) ){ ?>
                                    <a href="<?php echo esc_url( $slide->link ) ?>" data-animation="fadeInUp" data-delay="800ms" class="btn btn-theme btn-lg"><?php echo esc_html( $slide->text2 ) ?>  <i class="fa fa-angle-double-right"></i> </a>
                                <?php } ?>

                                <?php if( !empty( $slide->button_second ) ){ ?>
                                    <a href="<?php echo esc_url( $slide->link2 ) ?>" class="btn btn-theme btn-lg btn-play glightbox">
                                        <span class="animation-ripple icon-video"> <i class="fa fa-play"></i> </span> <span class="video-btn-text"> <?php echo esc_html( $slide->button_second ) ?> </span>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
</main>
<?php } ?>