 <?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wpsm_row">  
<style>

#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .wpsm_testimonial .wpsm_testimonial-title{
	font-size:<?php echo esc_attr($test_mb_name_font_size); ?>px !important;
	color:<?php echo esc_attr($test_mb_name_clr); ?> !important;
	font-family:'<?php  echo esc_attr($test_font_family); ?>';
}
#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .wpsm_testimonial > .wpsm_testimonial-review span{
	font-size:<?php echo esc_attr($test_mb_deg_font_size); ?>px !important;
	color:<?php echo esc_attr($test_mb_deg_clr); ?> !important;
	font-family:'<?php  echo esc_attr($test_font_family); ?>';
}
#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .wpsm_testimonial .wpsm_testi_links{
	font-size:<?php echo esc_attr($test_mb_web_link_font_size); ?>px !important;
	color:<?php echo esc_attr($test_mb_web_link_clr); ?> !important;
	font-family:'<?php  echo esc_attr($test_font_family); ?>';
}
#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .wpsm_testimonial .wpsm_testi_description{
	font-size:<?php echo esc_attr($test_mb_content_font_size); ?>px !important;
	color:<?php echo esc_attr($test_mb_content_clr); ?> !important;
	font-family:'<?php  echo esc_attr($test_font_family); ?>';
	padding: 10px !important;
    margin: 0px !important;
	font-weight: 400;
	display: block;
}
#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .wpsm_testimonial .wpsm_testi_content{
	background:<?php echo esc_attr($bgclr_style2); ?> !important;
}
#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .wpsm_testimonial .wpsm_testi_content:after{
	  border-top: 10px solid <?php echo esc_attr($bgclr_style2); ?> !important;
}
#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .wpsm_testimonial-pic > img{
	<?php  if($test_image_layout=="2") { ?>
		border-radius: 50% !important;
	<?php } else { ?>
		border-radius: 0% !important;
	<?php } ?>
}
#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .owl-dots{
	<?php if($enable_test_mb_dots=='no'){?>
		display: none;
	<?php } else {?>
		display: block;
	<?php } ?>
}
#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .owl-dots span
{
	background:<?php echo esc_attr($carousel_dot_bgclr);?>;
}

#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .owl-dot.active span{
		background:<?php echo esc_attr($carousel_dot_hover_bgclr);?>;
}

#wpsm_testi_b_row_<?php echo esc_attr($post_id); ?> .owl-dot:hover span{
		background:<?php echo esc_attr($carousel_dot_hover_bgclr);?>;
}


<?php echo esc_attr($custom_css); ?>
</style>
	<div id="wpsm-carousel-two-<?php echo esc_attr($post_id); ?>" class="wpsm-testi-owl-carousel">
		<?php

		 $i=1;
		switch($test_layout){
			case(12):
				$row=1;
			break;
			case(6):
				$row=2;
			break;
			case(4):
				$row=3;
			break;
		}

		foreach($test_data as $single_data)
		{
			$mb_photo = $single_data['mb_photo'];
			$mb_name = $single_data['mb_name'];
			$mb_deg = $single_data['mb_deg'];
			$mb_website = $single_data['mb_website'];
			$mb_desc = $single_data['mb_desc'];
			$mb_id = $single_data['mb_id'];
			$crop_size = wp_get_attachment_image_src($mb_id,'wpsm_testi_small');

			if($mb_id==0){
				$img_url = $mb_photo;
			}
			else{
				$img_url = $crop_size[0];
			}
		?>
			<div class="wpsm_testimonial">
				<?php if($mb_desc!=""){ ?>
				<div class="wpsm_testi_content">
					<p class="wpsm_testi_description">
						<?php echo esc_html($mb_desc); ?>
					 </p>
				</div>
				<?php } ?>
				<div class="wpsm_testimonial-pic">
					<img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($mb_name); ?>">
				</div>
				<div class="wpsm_testimonial-review">
					<?php if($mb_name!=""){ ?>
						<h3 class="wpsm_testimonial-title"><?php echo esc_html($mb_name); ?></h3>
					<?php } ?>
					<?php if($mb_deg!=""){ ?>
						<span><?php echo esc_html($mb_deg); ?></span>
					<?php } ?>
					<?php if($mb_website!=""){ ?>
						<a class="wpsm_testi_links" href="<?php echo esc_url($mb_website); ?>" target="_blank"><?php echo esc_html($test_mb_web_link_label); ?></a>
					<?php } ?>
				</div>
			</div>
			<?php
			
		} ?>
 	</div>
</div>
 <script>
jQuery(document).ready(function() {
	  var owl = jQuery('#wpsm-carousel-two-<?php echo esc_attr($post_id); ?>');
	  owl.owlCarousel({
		responsiveClass:true,
		
		loop: true,
		margin: 20,				
		autoplay: true,
		rewindNav : false,
		autoplayTimeout: 5000,
		
        autoplaySpeed: 500,				
		autoplayHoverPause: true,		
		responsive: {
		  0: {
			items: 1
		  },
		  500: {
			items: 2
		  },
		  767: {
			items: 2
		  },
		  992: {
			items: 3
		  },
		  1000: {
			items:<?php echo esc_attr($row);?>
		  }
		}
	  });
	  
})
</script>                      