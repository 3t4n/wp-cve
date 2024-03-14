<div class="row">
		
<style>

<?php echo esc_attr($flip_custom_css); ?>

.fb-d10-stel-pack-flip-box-back .fb-d10-stel-pack-flip-box-layer-title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	 font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}

.fb-d10-stel-pack-flip-box-front .fb-d10-stel-pack-flip-box-layer-title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	 font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}

.fb-d10-stel-pack-flip-box-layer-description
{
	<?php if($flip_desc_font!="0") { ?>
	  font-family:'<?php echo esc_attr($flip_desc_font); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_desc_font_size); ?>px !important;
	color:<?php echo esc_attr($flipdesccolor); ?> ;	
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
	
}
.fb-d10 .fb-d10-element.fb-d10-element-951fd28 .fb-d10-stel-pro-widget-flip-box-minimal-wrapper .fb-d10-stel-pack-flip-box-back {
   background-color:<?php echo esc_attr($flipbackgcolor); ?> ;
}
.fb-d10 .fb-d10-element.fb-d10-element-951fd28 .fb-d10-stel-pro-widget-flip-box-minimal-wrapper .fb-d10-stel-pack-flip-box-front {
   background-color: <?php echo esc_attr($flipfrontcolor); ?>  ;
}
.fb-d10-stel-pack-flip-box-layer-inner
{
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d10-stel-pack-flip-box-button
{
	font-family:'Open Sans',sans-serif ;
	color:<?php echo esc_attr($flipbuttoncolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackccolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonborderccolor); ?> ;
}
.fb-d10-stel-pack-flip-box-button:hover
{
	
	color:<?php echo esc_attr($flipbuttonhcolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackhcolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonhbordercolor); ?> ;
}


.fb-d10-ele-parent-image-wrapper img
{
	<?php if($flip_textalign=="Left"){ ?>
	float:left ;
	<?php } ?>
	<?php if($flip_textalign=="Right"){ ?>
	float:right ;
	<?php } if($flip_textalign=="Center"){ ?>
	margin:0 auto;
	<?php } ?>
}
.fb-d10-ele-parent-widget-stel-flip-box-minimal.fb-d10-stel-pack-flip-box-effect-flip.fb-d10-stel-pack-flip-box-direction-left .fb-d10-stel-pack-flip-box-back{
	<?php if($flip_fliptype=="Top to Bottom"){ ?>
	-webkit-transform:rotateX(180deg) rotateY(0) ;transform:rotateX(180deg) rotateY(0) ;
	<?php } ?>
	<?php if($flip_fliptype=="Bottom to Top"){ ?>
	-webkit-transform:rotateX(-180deg) rotateY(0) ;transform:rotateX(-180deg) rotateY(0) ;
	<?php } ?>
	<?php if($flip_fliptype=="Left to Right"){ ?>
	-webkit-transform:rotateX(0) rotateY(-180deg) ;transform:rotateX(0) rotateY(-180deg) ;
	<?php } ?>
	<?php if($flip_fliptype=="Right to Left"){ ?>
	-webkit-transform:rotateX(0) rotateY(180deg) ;transform:rotateX(0) rotateY(180deg) ;
	<?php } ?>
	
}
.fb-d10-stel-pack-flip-box-effect-flip.fb-d10-stel-pack-flip-box-direction-left .fb-d10-stel-pro-widget-flip-box-minimal-wrapper:hover .fb-d10-stel-pack-flip-box-front{
	<?php if($flip_fliptype=="Top to Bottom"){ ?>
	-webkit-transform:rotateX(-180deg) rotateY(0) ;transform:rotateX(-180deg) rotateY(0) ;
	<?php } ?>
	<?php if($flip_fliptype=="Bottom to Top"){ ?>
	-webkit-transform:rotateX(180deg) rotateY(0) ;transform:rotateX(180deg) rotateY(0) ;
	<?php } ?>
	<?php if($flip_fliptype=="Left to Right"){ ?>
	-webkit-transform:rotateX(0) rotateY(180deg) ;transform:rotateX(0) rotateY(180deg) ;
	<?php } ?>
	<?php if($flip_fliptype=="Right to Left"){ ?>
	-webkit-transform:rotateX(0) rotateY(-180deg);transform:rotateX(0) rotateY(-180deg) ;
	<?php } ?>
}

</style>
<?php 
	$i = 1;
	$j = 1;
	if ($TotalCount != - 1)
	{
		switch($flip_itemperrow){
			case(6):
				$row=2;
			break;
			case(4):
				$row=3;
			break;
			case(3):
				$row=4;
			break;
		}
		foreach ($All_data as $single_data)
		{
			
			$front_title = $single_data['front_title'];
			$front_icon = $single_data['front_icon'];
			$back_description = $single_data['back_description'];
			$btntext = $single_data['btntext'];
			$back_link = $single_data['back_link'];
			$flip_image_field = $single_data['flip_image_field']; 
			$flip_image_id = $single_data['flip_image_id']; 
			$url = wp_get_attachment_image_src(((int)$flip_image_id), 'flipbox_image_size', true
			);
			// echo "<pre>";			
			// print_r($url);
			

?>
<div class="col-md-<?php echo esc_attr($flip_itemperrow)?> col-sm-6 flipbox-col-sm-4 fb-design-10-col">
<div class="fb-d10-ele-parent fb-d10">
	<section class="fb-d10-element fb-d10-element-49b78de fb-d10-ele-section-boxed fb-d10-ele-section-height-default fb-d10-ele-section-height-default fb-d10-ele-section fb-d10-ele-parent-top-section">
		<div class="fb-d10-ele-parent-container fb-d10-ele-parent-column-gap-default">
			<div class="fb-d10-ele-parent-row">
				<div class="fb-d10-element fb-d10-element-2ab1831 fb-d10-ele-parent-column fb-d10-ele-parent-col-25 fb-d10-ele-parent-top-column">
					<div class="fb-d10-ele-parent-column-wrap  fb-d10-element-populated">
						<div class="fb-d10-ele-parent-widget-wrap">
							<div class="fb-d10-element fb-d10-element-951fd28 fb-d10-stel-pack-flip-box-direction-left fb-d10-stel-pack-flip-box-3d fb-d10-stel-pack-flip-box-effect-flip fb-d10-ele-parent-widget stel-transition-disabled fb-d10-ele-parent-widget-stel-flip-box-minimal">
								<div class="fb-d10-ele-parent-widget-container">
									<div class="fb-d10-stel-pro-widget-flip-box-minimal-wrapper">
										<div class="fb-d10-stel-pack-flip-box-layer fb-d10-stel-pack-flip-box-front">
											<div class="fb-d10-fb-d10-stel-pack-flip-box-layer-overlay">
												<div class="fb-d10-stel-pack-flip-box-layer-inner">
													<div class="fb-d10-ele-parent-image-wrapper"><img src="<?php if($flip_image_id==null || $flip_image_id=='' || $flip_image_id==0){ echo esc_url($flip_image_field); } else{  echo esc_url($url['0']); } ?>"
													alt="Front Image"/></div>
													<h3 class="fb-d10-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,20)); ?></h3>
													<div class="fb-d10-stel-pack-flip-box-layer-description"></div>
												</div>
											</div>
										</div>
										<div class="fb-d10-stel-pack-flip-box-layer fb-d10-stel-pack-flip-box-back">
											<div class="fb-d10-fb-d10-stel-pack-flip-box-layer-overlay">
												<div class="fb-d10-stel-pack-flip-box-layer-inner">
													<h3 class="fb-d10-stel-pack-flip-box-layer-title"><?php esc_html_e('About ','flipbox-builder-text-domain'); ?><?php echo esc_html(substr($front_title,0,17)); ?></h3>
													<div class="fb-d10-stel-pack-flip-box-layer-description">
														<?php echo esc_html(substr($back_description,0,80)); ?>
													</div>
													<a class="fb-d10-stel-pack-flip-box-button fb-d10-ele-parent-button fb-d10-ele-parent-size-sm fb-d10-ele-parent-animation-" href="<?php echo esc_url($back_link); ?>" <?php if($flip_linkopen=="New Tab"){ ?>target="_blank"<?php } ?> style="display:<?php if($back_link=="" || $back_link=="#") { echo esc_attr("none"); } ?>">
														<?php echo esc_html($btntext); ?>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
</div>
			<?php
			$i++;
			$j++;			
			
		}

	}
?>
</div>