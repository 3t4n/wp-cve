<?php 
/**
 * displaying home page info section
 * 
 *
 * @package Industri WordPress Theme
 */
?>
<?php 

add_action( 'Industri_Homepage_Sections', 'industri_info_section', 11 );
function industri_info_section(){ 
    $default = amigo_industri_default_settings();
    $display_info_section = get_theme_mod( 'display_info_section', $default['display_info_section'] );
    $display_info_clm = get_theme_mod( 'display_info_clm', $default['display_info_clm'] );
    $info_clm_icon = get_theme_mod( 'info_clm_icon', $default['info_clm_icon'] );
    $info_clm_title = get_theme_mod( 'info_clm_title', $default['info_clm_title'] );
    $info_clm_subtitle = get_theme_mod( 'info_clm_subtitle', $default['info_clm_subtitle'] );
    $info_clm_text = get_theme_mod( 'info_clm_text', $default['info_clm_text'] );

    if(! $display_info_section){ return; }
    $info_items = get_theme_mod( 'info_items', amigo_industri_default_info_items() );
    if ( empty( $info_items ) ) { return; }
    $info_items = json_decode( $info_items );       
    ?>
    <section class="flow-section info-section p-0">
        <div class="container mr-0">
            <div class="row">
                <?php foreach ( $info_items as $info ) { ?>
                   <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp">
                    <figure class="flow-cover">
                       <?php if( !empty( $info->icon_value ) ){ ?>
                        <div class="flow_icon">
                            <i class="fa <?php echo esc_html( $info->icon_value ) ?>"> </i>
                        </div>
                    <?php } ?>
                    <figcaption>
                        <?php if( !empty( $info->title ) ){ ?>
                            <h5 class="flow-title"> <?php echo esc_html( $info->title ) ?></h5>
                        <?php } ?>
                        <?php if( !empty( $info->text ) ){ ?>
                            <p class="flow-dec"><?php echo esc_html( $info->text ) ?></p>
                        <?php } ?>
                    </figcaption>
                    <?php if( !empty( $info->subtitle ) ){ ?>
                        <span class="title-number"> <?php echo esc_html( $info->subtitle ) ?> </span>
                    <?php } ?>
                </figure>
            </div>       
        <?php } ?>

        <?php if($display_info_clm){ ?>
         <div class="col-lg-4 col-md-12 wow fadeInUp">
            <figure class="flow-cover flow-cover-timing">
                <div class="flow_icon animation-ripple">
                 <?php if( !empty( $info_clm_icon ) ){ ?>
                    <i class="fa <?php echo esc_html( $info_clm_icon ) ?>"> </i>
                <?php } ?>
            </div>
            <figcaption>
                <?php if( !empty( $info_clm_title ) ){ ?>
                    <p class="flow-dec"><?php echo esc_html( $info_clm_title ) ?></p>
                <?php } ?>

                <?php if( !empty( $info_clm_subtitle ) ){ ?>
                    <h5 class="flow-contact"><?php echo esc_html( $info_clm_subtitle ) ?></h5>
                <?php } ?>

                <?php if( !empty( $info_clm_text ) ){ ?>
                    <p class="flow-timing"><?php echo wp_kses_post( $info_clm_text ) ?></p>
                <?php } ?>
            </figcaption>
        </figure>
    </div>
<?php } ?>
</div>
</div>
</section>
<?php } ?>