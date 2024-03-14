<?php

defined( 'ABSPATH' ) or die();

class wl_companion_about_guardian
{
    
    public static function wl_companion_about_guardian_html() {
    ?>
<div class="about-us-area">
        <div class="container">
            <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="part-img wow zoomIn  animated slow">
                    <a href="<?php the_permalink(); ?>">
					<?php if(get_theme_mod('About_Image')) { ?>
                    <img src="<?php echo esc_url(get_theme_mod('About_Image')); ?>">
					<?php } else { ?>
					<img src="<?php echo (has_post_thumbnail()) ? esc_url(the_post_thumbnail_url('medium')) : esc_url(get_template_directory_uri()) . '/images/about.jpg'; ?>">
					<?php } ?>
					</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="about-txt">
                        <div class="">
                            <h2><?php echo get_theme_mod( 'about_title' ,'We Are the Best Digital Agency' ); ?></h2>
                        </div>
                        <div class="txt">
							<?php if(get_theme_mod( 'about_txt' )) { ?>
                            <p><?php echo get_theme_mod( 'about_txt' ); ?></p>
							<?php } else { ?>
							<p><?php esc_html_e('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. Lorem Ipsum is simply dummy text of the printing and typesetting industry.It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. Lorem Ipsum is simply dummy text of the printing and typesetting industry.','guardian'); ?></p>
							<?php } ?>
                    </div>
                    <a href="<?php echo esc_url(get_theme_mod('about_link','#')); ?>" class="lfour about-btn">
			            <i class="fa fa-chevron-circle-right"></i>&nbsp; <?php esc_html_e('Read More','guardian'); ?>
					</a>
                    </div>
                </div>
              
            </div>
        </div>
    </div>
	<?php }
} ?>