<?php 
/**
 * displaying home page about section
 * 
 *
 * @package Industri WordPress Theme
 */
?>
<?php 
add_action( 'Industri_Homepage_Sections', 'industri_about_section', 12 );
function industri_about_section(){ 
$default = amigo_industri_default_settings();
$display_about_section = get_theme_mod( 'display_about_section', $default['display_about_section'] );
if(! $display_about_section){ return; }

$about_title = get_theme_mod( 'about_title', $default['about_title'] );
$about_subtitle = get_theme_mod( 'about_subtitle', $default['about_subtitle'] );
$about_text = get_theme_mod( 'about_text', $default['about_text'] );
$about_button_text = get_theme_mod( 'about_button_text', $default['about_button_text'] );
$about_button_link = get_theme_mod( 'about_button_link', $default['about_button_link'] );
$display_about_overlay = get_theme_mod( 'display_about_overlay', $default['display_about_overlay'] );
$about_overlay_title = get_theme_mod( 'about_overlay_title', $default['about_overlay_title'] );
$about_overlay_subtitle = get_theme_mod( 'about_overlay_subtitle', $default['about_overlay_subtitle'] );
$about_overlay_video_text = get_theme_mod( 'about_overlay_video_text', $default['about_overlay_video_text'] );
$about_overlay_video_link = get_theme_mod( 'about_overlay_video_link', $default['about_overlay_video_link'] );
$about_image_first = get_theme_mod( 'about_image_first', $default['about_image_first'] );


$about_items = get_theme_mod( 'about_items', amigo_industri_default_about_items() );
if ( empty( $about_items ) ) { return; }
$about_items = json_decode( $about_items );       
?>

<section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 wow fadeInLeft">
                <div class="about-content">
                    <div class="section-title text-left wow bounceInUp">
                        <?php if( !empty( $about_title ) ){ ?>
                            <h5>
                                <?php echo esc_html( $about_title ) ?>
                                <div class="rainbow"></div>
                            </h5>
                        <?php } ?>
                        <?php if( !empty( $about_subtitle ) ){ ?>
                            <h3><?php echo esc_html( $about_subtitle ) ?></h3>
                        <?php } ?>
                        <?php if( !empty( $about_text ) ){ ?>
                            <p><?php echo esc_html( $about_text ) ?></p>
                        <?php } ?>
                    </div>
                    <ul class="nav flex-column about-list">
                        <?php foreach ( $about_items as $about ) { ?>
                            <li class="nav-item mt-0">
                                <span class="icon-box"> <i class="fa <?php echo esc_html( $about->icon_value ) ?>"> </i> </span>
                                <div class="about-list-content">
                                    <h4><?php echo esc_html( $about->title ) ?></h4>
                                    <p><?php echo esc_html( $about->text ) ?></p>
                                </div>
                            </li>                        
                        <?php } ?>
                    </ul>
                    <?php if(!empty($about_button_text)){ ?>
                        <a href="<?php echo esc_url($about_button_link) ?>" class="btn btn-theme about-btn"><?php echo esc_html($about_button_text) ?><i class="fa fa-angle-double-right"> </i></a>
                    <?php } ?>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 wow fadeInRight">
                <div class="about-img">
                    <img src="<?php echo esc_url($about_image_first) ?>" class="img-fluid your-element" data-tilt data-tilt-max="10" data-tilt-speed="1000" data-tilt-perspective="1200" alt="<?php echo esc_html__( 'About Section Image','amigo-extensions' ) ?>" />
                    <?php if(!empty($display_about_overlay)){ ?>
                        <div class="overlay your-element" data-tilt data-tilt-max="20" data-tilt-speed="1000" data-tilt-perspective="1200">
                            <?php if(!empty($about_overlay_title)){ ?>
                                <span class="large-text"> <?php echo esc_html($about_overlay_title) ?> </span>
                            <?php } ?>

                            <?php if(!empty($about_overlay_subtitle)){ ?>
                                <h4><?php echo esc_html($about_overlay_subtitle) ?></h4>
                            <?php } ?>

                            <?php if(!empty($about_overlay_video_text)){ ?>
                                <a href="<?php echo esc_url($about_overlay_video_link) ?>" class="btn btn-theme btn-lg btn-play">
                                    <span class="animation-ripple"> <i class="fa fa-play"></i> </span> <?php echo esc_html($about_overlay_video_text) ?>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>