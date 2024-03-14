<?php
/**
 * Testimonial section for the homepage.
 */
if (!function_exists('spiceb_cloudpress_testimonial')) :

    function spiceb_cloudpress_testimonial() {
		$home_testimonial_title = get_theme_mod('home_testimonial_title',__('Cras Vitae','spicebox'));
		$home_testimonial_desc = get_theme_mod('home_testimonial_desc',__('Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.','spicebox'));
		$test_link = get_theme_mod('home_testimonial_link',__('#','spicebox'));
		$open_new_tab = get_theme_mod('home_testimonial_open_tab',false);
		$designation = get_theme_mod('home_testimonial_designation',__('Eu Suscipit','spicebox'));
		$home_testimonial_thumb = get_theme_mod('home_testimonial_thumb', SPICEB_PLUGIN_URL .'inc/cloudpress/images/testimonial/user-1.jpg');

		$home_testimonial_section_title = get_theme_mod('home_testimonial_section_title',__('Proin Egestas','spicebox'));
		$home_testimonial_section_discription = get_theme_mod('home_testimonial_section_discription',__('Nam Viverra Iaculis Finibus','spicebox'));
		$testimonial_callout_background = get_theme_mod('testimonial_callout_background', SPICEB_PLUGIN_URL .'inc/cloudpress/images/testimonial/bg03.jpg');
		if(get_theme_mod('testimonial_section_enable','on')=='on'):
      if (get_theme_mod('testimonial_section_enable') != 'off') {
          $theme = wp_get_theme();
          if ($theme->name == 'CloudPress Business') {
              $testimonial_section_class = "testimonial testimonial3";
              $testimonial_blockquote_class = "testmonial-block";
              $testimonial_blockquote_media_class = "media";
          }
          if ($theme->name == 'CloudPress Agency' || $theme->name == 'CloudPress Dark') {
              $testimonial_section_class = "testimonial";
              $testimonial_blockquote_class = "testmonial-block text-center";
          }
      }
		?>
		<section class="testimonial-wrapper" id="testimonial" style="background:url('<?php echo esc_url($testimonial_callout_background);?>') center center no-repeat;">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="section-header text-left cloudpress-testimonial">
							<?php if(!empty($home_testimonial_section_title)):?>
							<h2 class="section-title  text-white"><?php echo esc_html($home_testimonial_section_title); ?></h2>
						<?php endif; if(!empty($home_testimonial_section_discription)):?>
							<h5 class="section-subtitle  text-white"><?php echo esc_html($home_testimonial_section_discription); ?></h5><?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="<?php echo esc_attr($testimonial_section_class); ?>">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<blockquote class="<?php echo esc_attr($testimonial_blockquote_class);?> <?php echo esc_attr($testimonial_blockquote_media_class); ?>">
							<?php $default_arg =array('class' => "img-circle"); ?>
								<?php if($home_testimonial_thumb != ''): ?>
							<figure class="avatar">
								<img class="img-circle" src="<?php echo esc_url($home_testimonial_thumb); ?>">
							</figure>
							<?php endif;

              if ($theme->name == 'CloudPress Business') { echo '<div class="media-body">'; }
  							if(!empty($home_testimonial_desc)):?>
    							<div class="description">
    								<p><?php echo wp_kses_post( $home_testimonial_desc ); ?></p>
    							</div>
  							<?php endif;
  							if( $home_testimonial_title != '' || $designation != ''): ?>
  								<figcaption>
  									<?php if(!empty($home_testimonial_title)):?>
  									<cite class="name"><a href="<?php if(empty($test_link)) { echo '#';} else { echo $test_link;} ?>" <?php if($open_new_tab==true) { ?> target="_blank"<?php } ?>><?php echo $home_testimonial_title; ?></a><?php if(!empty($designation)):?><span class="designation"><?php echo $designation; ?></span><?php endif;?></cite>
  									<?php endif;?>
  								</figcaption>
  							<?php endif;
              if ($theme->name == 'CloudPress Business') { echo '</div>'; } ?>
						</blockquote>
					</div>
				</div>
			</div>
		</section>
	<?php endif;?>
  <style type="text/css">
  <?php
  		if(get_theme_mod('testimonial_image_overlay',true)==true):?>
    	.testimonial-wrapper:before
    	{
    		background-color: <?php echo get_theme_mod('testimonial_overlay_section_color','rgba(1, 7, 12, 0.65)');?>
    	}
  	   <?php endif;?>
  </style>
	<?php }
endif;
if (function_exists('spiceb_cloudpress_testimonial')) {
    $section_priority = apply_filters('cloudpress_section_priority', 4, 'spiceb_cloudpress_testimonial');
    add_action('spiceb_cloudpress_sections', 'spiceb_cloudpress_testimonial', absint($section_priority));
}
?>
