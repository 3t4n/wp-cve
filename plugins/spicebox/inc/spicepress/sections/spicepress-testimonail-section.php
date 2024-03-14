<?php
/**
 * Portfolio section for the homepage.
 */
if ( ! function_exists( 'spiceb_spicepress_testimonial' ) ) :

	function spiceb_spicepress_testimonial() {

$testimonial_section_enable = get_theme_mod('testimonial_section_enable','on');

                    $testimonial_section_design= 'default';
                if (wp_get_theme()->name == 'Stacy' && version_compare(wp_get_theme()->get('Version'), '1.3.3') > 0){
                    if (get_theme_mod('testimonial_design', false)) {
                        if (get_theme_mod('testimonial_design', 'center-effect') == 'center-effect') {
                            $testimonial_section_design= 'center-effect';
                        }
                    } else {
                        if (get_option('stacy_user', false) == 'new') {
                            $testimonial_section_design= 'center-effect';
                        }
                    }
            }
if (wp_get_theme()->name == 'SpicePress Dark'){
	 $testimonial_section_design= 'spicepress-dark';
}
$testimonial_section_design = ($testimonial_section_design) ? $testimonial_section_design : 'default';

if($testimonial_section_enable !='off' && $testimonial_section_design == 'default' )
{
$testimonial_callout_background = get_theme_mod('testimonial_callout_background',SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testimonial-bg.jpg');	
if($testimonial_callout_background != '') { ?>
<section class="testimonial-section" style="background-image:url('<?php echo esc_url($testimonial_callout_background);?>'); background-repeat: no-repeat; background-position: top left;">
	<?php } else { ?>
<section class="testimonial-section">
<?php } 
$testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color','rgba(0,0,0,0.6)');
$testimonial_image_overlay = get_theme_mod('testimonial_image_overlay',true);
?>
	<div class="overlay"<?php if($testimonial_image_overlay != false) { ?>style="background-color:<?php echo $testimonial_overlay_section_color; } ?>">
	<div class="container">
		<?php
		$home_testimonial_section_title = get_theme_mod('home_testimonial_section_title',__('Lorem ipsum dolor','spicebox'));
		$home_testimonial_section_discription = get_theme_mod('home_testimonial_section_discription',__('Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.','spicebox'));
		$home_testimonial_title = get_theme_mod('home_testimonial_title',__('Ipsum dolor','spicebox'));
		$home_testimonial_designation= get_theme_mod('home_testimonial_designation',__('Ligula Eget','spicebox'));
		$home_testimonial_thumb = get_theme_mod('home_testimonial_thumb',SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testi1.jpg');
		$home_testimonial_desc = get_theme_mod('home_testimonial_desc',__('Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.','spicebox'));
		?>
		
		
		<?php if( $home_testimonial_section_title != '' || $home_testimonial_section_discription != '') { ?>		
			<!-- Section Title -->
			<div class="row">
				<div class="col-md-12">
					<div class="section-header">
						<h1 class="white wow fadeInUp animated" data-wow-delay="0ms" data-wow-duration="500ms">
						<?php echo esc_attr($home_testimonial_section_title); ?>
						</h1>
						<div class="widget-separator"><span></span></div>
						<p class="white wow fadeInDown animated">
						<?php echo esc_attr($home_testimonial_section_discription); ?>
						</p>
					</div>
				</div>
			</div>
			<?php } ?>
			<!-- /Section Title -->
			<!-- Testimonial -->
			<div class="row">
				 <div class="item">
						<div class="media testmonial-area">
							<?php if($home_testimonial_thumb !=''){ ?>
							<div class="author-box">
								<img src="<?php echo $home_testimonial_thumb; ?>" class="img-circle" alt="img">
							</div>
							<?php } ?>
							<div class="media-body">
								<div class="description-box">
									<div class="author-description">
									<p><?php echo $home_testimonial_desc;  ?>
									</p>
									</div>									
								</div>
								<?php if($home_testimonial_title != '' || $home_testimonial_designation !='' ){?>
								<h4><?php echo $home_testimonial_title; ?> <?php if($home_testimonial_designation !=''){ ?> - <?php } ?><span class="designation"><?php echo $home_testimonial_designation; ?></span>
								</h4>
								<?php } ?>
							</div>
							
								
						</div>	
					</div>
			</div>
			<!-- /Testimonial -->	
			
		</div>	
	</div>	
</section>
<!-- /Testimonial Section -->
<div class="clearfix"></div>
<?php } 



if($testimonial_section_enable !='off' && $testimonial_section_design == 'center-effect' )
{
$testimonial_callout_background = get_theme_mod('testimonial_callout_background',SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testimonial-bg.jpg');	
if($testimonial_callout_background != '') { ?>
<section class="testimonial-section" style="background-image:url('<?php echo esc_url($testimonial_callout_background);?>'); background-repeat: no-repeat; background-position: top left;">
	<?php } else { ?>
<section class="testimonial-section">
<?php } 
$testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color','rgba(0,0,0,0.6)');
$testimonial_image_overlay = get_theme_mod('testimonial_image_overlay',true);
?>
	<div class="overlay"<?php if($testimonial_image_overlay != false) { ?>style="background-color:<?php echo $testimonial_overlay_section_color; } ?>">
	<div class="container">
		<?php
		$home_testimonial_section_title = get_theme_mod('home_testimonial_section_title',__('Lorem ipsum dolor','spicebox'));
		$home_testimonial_section_discription = get_theme_mod('home_testimonial_section_discription',__('Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.','spicebox'));
		$home_testimonial_title = get_theme_mod('home_testimonial_title',__('Ipsum dolor','spicebox'));
		$home_testimonial_designation= get_theme_mod('home_testimonial_designation',__('Ligula Eget','spicebox'));
		$home_testimonial_thumb = get_theme_mod('home_testimonial_thumb',SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testi1.jpg');
		$home_testimonial_desc = get_theme_mod('home_testimonial_desc',__('Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.','spicebox'));
		?>
		
		
		<?php if( $home_testimonial_section_title != '' || $home_testimonial_section_discription != '') { ?>		
			<!-- Section Title -->
			<div class="row">
				<div class="col-md-12">
					<div class="section-header">
						<h1 class="white wow fadeInUp animated" data-wow-delay="0ms" data-wow-duration="500ms">
						<?php echo esc_attr($home_testimonial_section_title); ?>
						</h1>
						<div class="widget-separator"><span></span></div>
						<p class="white wow fadeInDown animated">
						<?php echo esc_attr($home_testimonial_section_discription); ?>
						</p>
					</div>
				</div>
			</div>
			<?php } ?>
			<!-- /Section Title -->
			<!-- Testimonial -->
			<div class="row">
                            <div class="home col-md-12">
				 <div class="item">
                                     <article class="testmonial-block text-center">
							<?php if($home_testimonial_thumb !=''){ ?>
							<figure class="avatar">
								<img src="<?php echo $home_testimonial_thumb; ?>" class="img-responsive img-circle" alt="img">
							</figure>
							<?php }  ?>
                                         
                                                            <?php if($home_testimonial_title != '' || $home_testimonial_designation !='' ){?>
								<h4><?php echo $home_testimonial_title; ?> <?php if($home_testimonial_designation !=''){ ?> - <?php } ?><span class="designation"><?php echo $home_testimonial_designation; ?></span>
								</h4>
								<?php } ?>
                                         
                                         <?php if($home_testimonial_desc !=''){ ?>
                                                <div class="entry-content">
                                                    <p class="text-white"><?php echo $home_testimonial_desc;  ?></p>
						</div>	
                                         <?php } ?>
								
						</article>
					</div>
					</div>
			</div>
			<!-- /Testimonial -->	
			
		</div>	
	</div>	
</section>
<!-- /Testimonial Section -->
<div class="clearfix"></div>
<?php } 

if($testimonial_section_enable !='off' && $testimonial_section_design == 'spicepress-dark' )
{
$testimonial_callout_background = get_theme_mod('testimonial_callout_background',SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testimonial-bg.jpg');	
if($testimonial_callout_background != '') { ?>
<section class="testimonial-section testimonial5" style="background-image:url('<?php echo esc_url($testimonial_callout_background);?>'); background-repeat: no-repeat; background-position: top left;">
	<?php } else { ?>
<section class="testimonial-section testimonial5">
<?php } 
$testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color','rgba(0,0,0,0.6)');
$testimonial_image_overlay = get_theme_mod('testimonial_image_overlay',true);
?>
	<div class="overlay"<?php if($testimonial_image_overlay != false) { ?>style="background-color:<?php echo $testimonial_overlay_section_color; } ?>">
	<div class="container">
		<?php
		$home_testimonial_section_title = get_theme_mod('home_testimonial_section_title',__('Lorem ipsum dolor','spicebox'));
		$home_testimonial_section_discription = get_theme_mod('home_testimonial_section_discription',__('Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.','spicebox'));
		$home_testimonial_title = get_theme_mod('home_testimonial_title',__('Ipsum dolor','spicebox'));
		$home_testimonial_designation= get_theme_mod('home_testimonial_designation',__('Ligula Eget','spicebox'));
		$home_testimonial_thumb = get_theme_mod('home_testimonial_thumb',SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testi-2.jpg');
		$home_testimonial_desc = get_theme_mod('home_testimonial_desc',__('Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.','spicebox'));
		?>
		
		
		<?php if( $home_testimonial_section_title != '' || $home_testimonial_section_discription != '') { ?>		
			<!-- Section Title -->
			<div class="row">
				<div class="col-md-12">
					<div class="section-header">
						<h1 class="white wow fadeInUp animated" data-wow-delay="0ms" data-wow-duration="500ms">
						<?php echo esc_attr($home_testimonial_section_title); ?>
						</h1>
						<div class="widget-separator"><span></span></div>
						<p class="white wow fadeInDown animated">
						<?php echo esc_attr($home_testimonial_section_discription); ?>
						</p>
					</div>
				</div>
			</div>
			<?php } ?>
			<!-- /Section Title -->

			<!-- Testimonial -->
			<div class="row">
                <div class="col-md-12">
                    <article class="testmonial-block">
                    	<div class="col-lg-4">
							<?php if($home_testimonial_thumb !=''){ ?>
							<figure class="avatar">
								<img src="<?php echo $home_testimonial_thumb; ?>" class="img-responsive img-circle" alt="img">
							</figure>
							<?php }  ?>
						</div>
						<div class="col-lg-8 testimonial-text">
						<?php 
						if($home_testimonial_desc !=''){ ?>
	                        <div class="entry-content">
	                            <p class="text-white"><?php echo $home_testimonial_desc;  ?></p>
							</div>	
                        <?php }
                        if($home_testimonial_title != '' || $home_testimonial_designation !='' ){?>
							<h4><?php echo $home_testimonial_title; ?> <?php if($home_testimonial_designation !=''){ ?> - <?php } ?><span class="designation"><?php echo $home_testimonial_designation; ?></span>
							</h4>
						<?php }?>
						</div>		
					</article>
				</div>
			</div>
		</div>
		<!-- /Testimonial -->			
	</div>	
</section>
<!-- /Testimonial Section -->
<div class="clearfix"></div>
<?php } ?>

		<?php
}

endif;

		if ( function_exists( 'spiceb_spicepress_testimonial' ) ) {
		$section_priority = apply_filters( 'spicepress_section_priority', 13, 'spiceb_spicepress_testimonial' );
		add_action( 'spiceb_spicepress_sections', 'spiceb_spicepress_testimonial', absint( $section_priority ) );
}