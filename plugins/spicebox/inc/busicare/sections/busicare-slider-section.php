<?php
/**
 * Slider section for the homepage.
 */
add_action('spiceb_busicare_slider_action','spiceb_busicare_slider_section');

function spiceb_busicare_slider_section()
{	
	$theme = wp_get_theme();
	if( $theme->name=='BusiCare Dark')
	{
	$home_slider_image = get_theme_mod('home_slider_image',SPICEB_PLUGIN_URL .'inc/busicare/images/slider/slider-dark.jpg');	
	}
	else
	{
	$home_slider_image = get_theme_mod('home_slider_image',SPICEB_PLUGIN_URL .'inc/busicare/images/slider/slider.jpg');	
	}
	
	$home_slider_subtitle = get_theme_mod('home_slider_subtitle',__('Nulla nec dolor sit amet lacus molestie','spicebox'));
	$home_slider_title = get_theme_mod('home_slider_title',__('Nulla nec dolor sit amet lacus molestie','spicebox'));		
	$home_slider_discription = get_theme_mod('home_slider_discription',__('Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim <br> mei ludus  efficiendi ei sea summo mazim ex.','spicebox'));
	$home_slider_btn_txt = get_theme_mod('home_slider_btn_txt',__('Nec Sem','spicebox'));
	$home_slider_btn_link = get_theme_mod('home_slider_btn_link',__(esc_url('#'),'spicebox'));
	$home_slider_btn_target = get_theme_mod('home_slider_btn_target',false);

	$home_slider_btn_txt2 = get_theme_mod('home_slider_btn_txt2',__('Cras Vitae','spicebox'));
	$home_slider_btn_link2 = get_theme_mod('home_slider_btn_link2',__(esc_url('#'),'spicebox'));
	$home_slider_btn_target2 = get_theme_mod('home_slider_btn_target2',false);
	$slider_align_split = get_theme_mod('slider_content_alignment','center');

	if(get_theme_mod('home_page_slider_enabled',true)==true) {
	$video_upload = get_theme_mod('slide_video_upload');
	$video_upload = wp_get_attachment_url( $video_upload);
	$video_youtub = get_theme_mod('slide_video_url');	
	// Below Script will run for only video slide		
	if((!empty($video_upload) || !empty($video_youtub) ) && (get_theme_mod('slide_variation','slide')=='video')){ ?>
		<section class="video-slider home-section home-full-height bcslider-section back-img" id="totop" data-background="assets/images/section-5.jpg">
			<?php if(!empty($video_youtub)){?>
				<div class="video-player" data-property="{videoURL:'<?php echo esc_url($video_youtub);?>', containment:'.home-section', mute:false, autoPlay:true, loop:true, opacity:1, showControls:false, showYTLogo:false, vol:25}"></div>
			<?php } 
			else if(!empty($video_upload)){?>
			<video autoplay="" muted="" loop="" id="video_slider">
	            <source src="<?php echo esc_url($video_upload); ?>" type="video/mp4">
	         </video>
	     	<?php }?>
	     	<div class="container slider-caption">
				<div class="caption-content <?php echo 'text-'.esc_attr($slider_align_split);?>">
                    <?php if($home_slider_subtitle!=''){ ?>
                    	<p class="heading"><?php echo esc_html($home_slider_subtitle); ?></p> 
					<?php }
					if($home_slider_title!=''){ ?>
						<h2 class="title"><?php echo  esc_html($home_slider_title); ?></h2>
					<?php } 
					if($home_slider_discription!=''){ ?>
						<p class="description"><?php echo  wp_kses_post($home_slider_discription); ?></p>
					<?php } ?>
					<?php if(($home_slider_btn_txt !=null) || ($home_slider_btn_txt2 !=null)) { ?>
					<div class="btn-combo mt-5">
						<?php if($home_slider_btn_txt !=null): ?>
							<a href="<?php echo esc_url($home_slider_btn_link); ?>" <?php if($home_slider_btn_target) { ?> target="_blank" <?php } ?> class="btn-small btn-default"> <?php echo  esc_html($home_slider_btn_txt); ?> </a>
						<?php endif; ?>											
						<?php if($home_slider_btn_txt2 !=null): ?>
								<a href="<?php echo esc_url($home_slider_btn_link2); ?>" <?php if($home_slider_btn_target2) { ?> target="_blank" <?php } ?> class="btn-small btn-light"><?php echo  esc_html($home_slider_btn_txt2); ?></a>
						<?php endif;?>
					</div>
					<?php } ?>						
				</div>
			</div>
			<?php $slider_image_overlay = get_theme_mod('slider_image_overlay',true);
						$slider_overlay_section_color = get_theme_mod('slider_overlay_section_color','rgba(0,0,0,0.6)');
					if($slider_image_overlay != false) { ?>
						<div class="overlay" style="background-color:<?php echo esc_attr($slider_overlay_section_color);?>"></div>
					<?php } ?>					
	</section>
	<?php }
	else{ ?>
	<!-- Slider Section -->	
	<section class="bcslider-section">
		<div class="home-section back-img" <?php if($home_slider_image!='') { ?>style="background-image:url( <?php echo esc_url($home_slider_image); ?> );" <?php } ?>>
			<div class="container slider-caption">
				<div class="caption-content <?php echo 'text-'.esc_attr($slider_align_split);?>">
                    <?php if($home_slider_subtitle!=''){ ?>
                    	<p class="heading"><?php echo esc_html($home_slider_subtitle); ?></p> 
					<?php }
					if($home_slider_title!=''){ ?>
						<h2 class="title"><?php echo  esc_html($home_slider_title); ?></h2>
					<?php } 
					if($home_slider_discription!=''){ ?>
						<p class="description"><?php echo  wp_kses_post($home_slider_discription); ?></p>
					<?php } ?>
					<?php if(($home_slider_btn_txt !=null) || ($home_slider_btn_txt2 !=null)) { ?>
					<div class="btn-combo mt-5">
						<?php if($home_slider_btn_txt !=null): ?>
							<a href="<?php echo esc_url($home_slider_btn_link); ?>" <?php if($home_slider_btn_target) { ?> target="_blank" <?php } ?> class="btn-small btn-default"> <?php echo  esc_html($home_slider_btn_txt); ?> </a>
						<?php endif; ?>											
						<?php if($home_slider_btn_txt2 !=null): ?>
								<a href="<?php echo esc_url($home_slider_btn_link2); ?>" <?php if($home_slider_btn_target2) { ?> target="_blank" <?php } ?> class="btn-small btn-light"><?php echo  esc_html($home_slider_btn_txt2); ?></a>
						<?php endif;?>
					</div>
					<?php } ?>						
				</div>
			</div>
			<?php $slider_image_overlay = get_theme_mod('slider_image_overlay',true);
				$slider_overlay_section_color = get_theme_mod('slider_overlay_section_color','rgba(0,0,0,0.6)');
			if($slider_image_overlay != false) { ?>
				<div class="overlay" style="background-color:<?php echo esc_attr($slider_overlay_section_color);?>"></div>
			<?php } ?>
    	</div>						
	</section>
	<?php } ?>
	<div class="clearfix"></div>
<?php 
}
} ?>