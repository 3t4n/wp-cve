<?php 
/**
 * displaying home page call to action section
 * 
 *
 * @package Industri WordPress Theme
 */
?>
<?php 
add_action( 'Industri_Homepage_Sections', 'industri_c2a_section', 14 );
function industri_c2a_section(){  
$default                        = amigo_industri_default_settings();
$display_c2a_section        = get_theme_mod( 'display_c2a_section', $default['display_c2a_section'] );
if(! $display_c2a_section){ return; }

$c2a_title                  = get_theme_mod( 'c2a_title', $default['c2a_title'] );
$c2a_text               = get_theme_mod( 'c2a_text', $default['c2a_text'] );
$c2a_button_text                   = get_theme_mod( 'c2a_button_text', $default['c2a_button_text'] );
$c2a_button_link            = get_theme_mod( 'c2a_button_link', $default['c2a_button_link'] );
?>
<section class="callout-section">
    <div class="container">
        <div class="row">
            <div class="section-title wow bounceInUp text-white mb-0 col-lg-6 col-md-12 wow fadeInLeft">
                 <?php if( !empty( $c2a_title ) ){ ?>
                <h3><?php echo esc_html( $c2a_title ) ?></h3>
                <?php } ?>

                 <?php if( !empty( $c2a_text ) ){ ?>
                <p><?php echo esc_html( $c2a_text ) ?></p>
                <?php } ?>
            </div>

             <?php if( !empty( $c2a_button_text ) ){ ?>
            <div class="col-md-6 callout-button wow fadeInRight">
                <a href="<?php echo esc_url( $c2a_button_link ) ?>" class="btn btn-theme btn-lg"><?php echo esc_html( $c2a_button_text ) ?><i class="fa fa-long-arrow-right faa-passing animated"></i></a>
            </div>
            <?php } ?>
        </div>
    </div>
    <div id="stars"></div>
    <div id="stars2"></div>
    <div id="stars3"></div>
</section>
<?php } ?>