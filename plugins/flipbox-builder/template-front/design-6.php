<div class="row">			
<style>
<?php echo esc_attr($flip_custom_css); ?>
.fb-d6-ele-icon
{	
	color:<?php echo esc_attr($flipiconcolor); ?> ;
	background:<?php echo esc_attr($flipbackcolor); ?> ;
					
}
.fb-d6-stel-pack-flip-box-front .fb-d6-stel-pack-flip-box-layer-title
{
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	<?php if($flip_title_fontfamily!="0") { ?>
	font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d6-stel-pack-flip-box-back .fb-d6-stel-pack-flip-box-layer-title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d6-stel-pack-flip-box-button
{
	
	color:<?php echo esc_attr($flipbuttoncolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackccolor); ?> ;
	border:solid 2px <?php echo esc_attr($flipbuttonborderccolor); ?> ;
}
.fb-d6-stel-pack-flip-box-button:hover
{
	
	color:<?php echo esc_attr($flipbuttonhcolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackhcolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonhbordercolor); ?> ;
}
.fb-d6-stel-pack-flip-box-layer-description
{
	<?php if($flip_desc_font!="0") { ?>
	font-family:'<?php echo esc_attr($flip_desc_font); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_desc_font_size); ?>px !important;
	color:<?php echo esc_attr($flipdesccolor); ?> ;	
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
	
}

.fb-d6-stel-pack-flip-box-layer-inner
{
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d6-ele-530 .fb-d6-ele-element.fb-d6-ele-element-4120c35 .fb-d6-stel-pro-widget-flip-box-minimal-wrapper .fb-d6-stel-pack-flip-box-front 
{
	 background-color: <?php echo esc_attr($flipfrontcolor); ?>  ;
}
.fb-d6-ele-530 .fb-d6-ele-element.fb-d6-ele-element-4120c35 .fb-d6-stel-pro-widget-flip-box-minimal-wrapper .fb-d6-stel-pack-flip-box-back
{
	background-color:<?php echo esc_attr($flipbackgcolor); ?> ;
}
.fb-d6-ele-widget-stel-flip-box-minimal.fb-d6-stel-pack-flip-box-effect-flip.fb-d6-stel-pack-flip-box-direction-right .fb-d6-stel-pack-flip-box-back{
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
.fb-d6-stel-pack-flip-box-effect-flip.fb-d6-stel-pack-flip-box-direction-right .fb-d6-stel-pro-widget-flip-box-minimal-wrapper:hover .fb-d6-stel-pack-flip-box-front{
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
.fb-d6-ele-530 .fb-d6-ele-element.fb-d6-ele-element-4120c35 .fb-d6-stel-pro-widget-flip-box-minimal-wrapper .fb-d6-stel-pack-flip-box-front .fb-d6-ele-icon {
	font-size: <?php echo esc_attr($flip_icon_size);?>px !important;
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
?>
<div class="col-md-<?php echo esc_attr($flip_itemperrow)?> col-sm-6 flipbox-col-sm-4 fb-design-6-col">
<div class="fb-d6-ele fb-d6-ele-530">
	<section class="fb-d6-ele-element fb-d6-ele-element-fbc7b4e fb-d6-ele-section-boxed fb-d6-ele-section-height-default fb-d6-ele-section-height-default fb-d6-ele-section fb-d6-ele-top-section">
		<div class="fb-d6-ele-container fb-d6-ele-column-gap-default">
			<div class="fb-d6-ele-row">
				<div class="fb-d6-ele-element fb-d6-ele-element-287c65d fb-d6-ele-column fb-d6-ele-col-33 fb-d6-ele-top-column">
					<div class="fb-d6-ele-column-wrap  fb-d6-ele-element-populated">
						<div class="fb-d6-ele-widget-wrap">
							<div class="fb-d6-ele-element fb-d6-ele-element-4120c35 fb-d6-stel-pack-flip-box-direction-right fb-d6-stel-pack-flip-box-3d fb-d6-stel-pack-flip-box-effect-flip fb-d6-ele-widget fb-d6-stel-transition-disabled fb-d6-ele-widget-stel-flip-box-minimal">
								<div class="fb-d6-ele-widget-container">
									<div class="fb-d6-stel-pro-widget-flip-box-minimal-wrapper">
										<div class="fb-d6-stel-pack-flip-box-layer fb-d6-stel-pack-flip-box-front">
											<div class="fb-d6-stel-pack-flip-box-layer-overlay">
												<div class="fb-d6-stel-pack-flip-box-layer-inner">
													<div class="fb-d6-ele-icon-wrapper fb-d6-ele-view-stacked fb-d6-ele-shape-circle"><span class="fb-d6-ele-icon"><i aria-hidden="true" class="<?php echo esc_attr($front_icon); ?>"></i></span></div>
													<h3 class="fb-d6-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,20)); ?></h3></div>
											</div>
										</div>
										<div class="fb-d6-stel-pack-flip-box-layer fb-d6-stel-pack-flip-box-back">
											<div class="fb-d6-stel-pack-flip-box-layer-overlay">
												<div class="fb-d6-stel-pack-flip-box-layer-inner">
													<h3 class="fb-d6-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,17)); ?></h3>
													<div class="fb-d6-stel-pack-flip-box-layer-description">
														<?php echo esc_html(substr($back_description,0,80)); ?>
													</div>
													<a class="fb-d6-stel-pack-flip-box-button fb-d6-ele-button fb-d6-ele-size-sm fb-d6-ele-animation-wobble-skew" href="<?php echo esc_url($back_link); ?>" <?php if($flip_linkopen=="New Tab"){ ?>target="_blank"<?php } ?> style="display:<?php if($back_link=="" || $back_link=="#") { echo esc_attr("none"); } ?>">
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