<?php 
/**
 * displaying home page service section
 * 
 *
 * @package Industri WordPress Theme
 */
?>
<?php

add_action( 'Industri_Homepage_Sections', 'industri_service_section', 13 );
function industri_service_section(){  
$default                        = amigo_industri_default_settings();
$display_service_section        = get_theme_mod( 'display_service_section', $default['display_service_section'] );
if(! $display_service_section){ return; }

$service_title                  = get_theme_mod( 'service_title', $default['service_title'] );
$service_subtitle               = get_theme_mod( 'service_subtitle', $default['service_subtitle'] );
$service_text                   = get_theme_mod( 'service_text', $default['service_text'] );
$service_button_more            = get_theme_mod( 'service_button_more', $default['service_button_more'] );
$service_button_link            = get_theme_mod( 'service_button_link', $default['service_button_link'] );
$clipart_1 = get_theme_mod('clipart_image_1',$default['clipart_image_1']);
$clipart_2 = get_theme_mod('clipart_image_2',$default['clipart_image_2']);
$clipart_3 = get_theme_mod('clipart_image_3',$default['clipart_image_3']);
$clipart_4 = get_theme_mod('clipart_image_4',$default['clipart_image_4']);

$service_items                     = get_theme_mod( 'service_items', amigo_industri_default_service_items() );
if ( empty( $service_items ) ) { return; }
$service_items = json_decode( $service_items );       
?>

<section class="service-section bg-gray">
    <div class="container">
        <ul class="element-css your-element" data-tilt data-tilt-max="5" data-tilt-speed="2000" data-tilt-perspective="2200">
         <?php if(!empty($clipart_1)){ ?>
            <li class="size_1"><img src="<?php echo esc_url($clipart_1) ?>" alt="clip art image" /></li>
        <?php } ?>

        <?php if(!empty($clipart_2)){ ?>
            <li class="size_2"><img src="<?php echo esc_url( $clipart_2 ) ?>" alt="clip art image" /></li>
        <?php } ?>

        <?php if(!empty($clipart_3)){ ?>
            <li class="size_3"><img src="<?php echo esc_url( $clipart_3 ) ?>" alt="clip art image" /></li>
        <?php } ?>

        <?php if(!empty($clipart_4)){ ?>
            <li class="size_4"><img src="<?php echo esc_url( $clipart_4 ) ?>" alt="clip art image" /></li>
        <?php } ?>
    </ul>
    <div class="section-title text-center wow bounceInUp">
        <?php if( !empty( $service_title ) ){ ?>
            <h5>
                <?php echo esc_html( $service_title ) ?>
                <div class="rainbow"></div>
            </h5>
        <?php } ?>
        <?php if( !empty( $service_subtitle ) ){ ?>
            <h3><?php echo esc_html( $service_subtitle ) ?></h3>
        <?php } ?>
        <?php if( !empty( $service_text ) ){ ?>
            <p><?php echo esc_html( $service_text ) ?></p>
        <?php } ?>
    </div>
    <div class="row service-items">
        <?php foreach ( $service_items as $item ) { ?>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s">
                <figure class="service-col">
                    <figcaption class="row m-0">
                        <?php if( !empty( $item->icon_value ) ){ ?>
                            <div class="service-icon">
                                <i class="fa <?php echo esc_html( $item->icon_value ) ?>"> </i>
                            </div>
                        <?php } ?>
                        <div class="service-content col">
                            <?php if( !empty( $item->title ) ){ ?>
                                <h4 class="service-title"><?php echo esc_html( $item->title ) ?></h4>
                            <?php } ?>

                            <?php if( !empty( $item->text ) ){ ?>
                                <p class="service-dec"><?php echo esc_html( $item->text ) ?></p>
                            <?php } ?>

                            <?php if( !empty( $item->text2 ) ){ ?>
                                <a href="<?php echo esc_url( $item->link ) ?>" class="btn btn-theme btn-v2 btn-md"> <i class="fa fa-long-arrow-right  "></i> <span><?php echo esc_html( $item->text2 ) ?> </span></a>
                            <?php } ?>
                        </div>
                    </figcaption>
                </figure>
            </div>
        <?php } ?>
    </div>
    <?php if( !empty( $service_button_more ) ){ ?>
     <div class="container text-center">
        <a href="<?php echo esc_url($default['service_button_link']) ?>" class="btn btn-theme service-btn"><?php echo esc_html( $service_button_more ) ?><i class=" fa fa-rotate-right "> </i></a>
    </div>
<?php } ?>
</div>
</section>
<?php } ?>