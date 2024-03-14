<div class="row">		
<style>

<?php echo esc_attr($flip_custom_css); ?>

.fb-d5-stel-pack-flip-box-layer-title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d5-stel-pack-flip-box-layer-description
{
	<?php if($flip_desc_font!="0") { ?>
	font-family:'<?php echo esc_attr($flip_desc_font); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_desc_font_size); ?>px !important;
	color:<?php echo esc_attr($flipdesccolor); ?> ;	
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
	
}
.fb-d5-stel-pack-flip-box-button
{
	
	color:<?php echo esc_attr($flipbuttoncolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackccolor); ?> ;
	border:solid 2px <?php echo esc_attr($flipbuttonborderccolor); ?> ;
}
.fb-d5-stel-pack-flip-box-button:hover
{
	
	color:<?php echo esc_attr($flipbuttonhcolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackhcolor); ?> ;
	border:solid 2px <?php echo esc_attr($flipbuttonhbordercolor); ?> ;
}


.fb-d5-stel-pack-flip-box-layer-inner
{
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d5-parent-icon
{
	
	color:<?php echo esc_attr($flipiconcolor); ?> ;
	background:<?php echo esc_attr($flipbackcolor); ?> ;
	
					
}
.fb-d5-parent-530 .fb-d5-ele-element.fb-d5-ele-element-68c7cdf .fb-d5-stel-pack-flip-box-effect-flip .fb-d5-stel-pack-flip-box-front {
    background-color: <?php echo esc_attr($flipfrontcolor); ?>  ;
   
}
.fb-d5-parent-530 .fb-d5-ele-element.fb-d5-ele-element-68c7cdf .fb-d5-stel-pack-flip-box-effect-flip .fb-d5-stel-pack-flip-box-back {
   background-color:<?php echo esc_attr($flipbackgcolor); ?> ;
}
.fb-d5-ele-widget-stel-flip-box-minimal.fb-d5-stel-pack-flip-box-effect-flip.stel-pack-flip-box-direction-right .fb-d5-stel-pack-flip-box-back{
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
.fb-d5-stel-pack-flip-box-effect-flip.stel-pack-flip-box-direction-right .fb-d5-stel-pack-flip-box-effect-flip:hover .fb-d5-stel-pack-flip-box-front{
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
.fb-d5-parent-530 .fb-d5-ele-element.fb-d5-ele-element-68c7cdf .fb-d5-stel-pack-flip-box-effect-flip .fb-d5-stel-pack-flip-box-front .fb-d5-parent-icon {
	font-size: <?php echo esc_attr($flip_icon_size);  ?>px !important;
	
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
<div class="col-md-<?php echo esc_attr($flip_itemperrow)?> col-sm-6 flipbox-col-sm-4 fb-design-5-col">
<div class="fb-d5-parent fb-d5-parent-530">
	<section class="fb-d5-ele-element fb-d5-ele-element-2bb8c8d fb-d5-parent-section-boxed fb-d5-parent-section-height-default fb-d5-parent-section-height-default fb-d5-parent-section fb-d5-parent-top-section">
		<div class="fb-d5-parent-container fb-d5-parent-column-gap-default">
			<div class="fb-d5-parent-row">
				<div class="fb-d5-ele-element fb-d5-ele-element-f7e48b8 fb-d5-parent-column fb-d5-parent-col-33 fb-d5-parent-top-column">
					<div class="fb-d5-parent-column-wrap  fb-d5-ele-element-populated">
						<div class="fb-d5-parent-widget-wrap">
							<div class="fb-d5-ele-element fb-d5-ele-element-68c7cdf stel-pack-flip-box-direction-right fb-d5-stel-pack-flip-box-3d fb-d5-stel-pack-flip-box-effect-flip fb-d5-parent-widget fb-d5-stel-transition-disabled fb-d5-ele-widget-stel-flip-box-minimal">
								<div class="fb-d5-parent-widget-container">
									<div id="stel-pro-widget-flip-box-minimal-68c7cdf" class="fb-d5-stel-pack-flip-box-effect-flip">
										<div class="fb-d5-stel-pack-flip-box-layer fb-d5-stel-pack-flip-box-front">
											<div class="fb-d5-stel-pack-flip-box-layer-overlay">
												<div class="fb-d5-stel-pack-flip-box-layer-inner">
													<div class="fb-d5-parent-icon-wrapper fb-d5-parent-view-framed fb-d5-parent-shape-circle"><span class="fb-d5-parent-icon"><i aria-hidden="true" class="<?php echo esc_attr($front_icon); ?>"></i></span></div>
													<h3 class="fb-d5-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,17)); ?></h3>
													<div class="fb-d5-stel-pack-flip-box-layer-description">
														<?php echo esc_html(substr($back_description,0,40)); ?>
													</div>
												</div>
											</div>
										</div>
										<div class="fb-d5-stel-pack-flip-box-layer fb-d5-stel-pack-flip-box-back">
											<div class="fb-d5-stel-pack-flip-box-layer-overlay">
												<div class="fb-d5-stel-pack-flip-box-layer-inner">
													<h3 class="fb-d5-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,17)); ?></h3>
													<div class="fb-d5-stel-pack-flip-box-layer-description">
														<?php echo esc_html(substr($back_description,0,80)); ?>
													</div>
													<a class="fb-d5-stel-pack-flip-box-button fb-d5-parent-button fb-d5-parent-size-sm fb-d5-parent-animation-wobble-skew" href="<?php echo esc_url($back_link); ?>" <?php if($flip_linkopen=="New Tab"){  ?>target="_blank"<?php } ?> style="display:<?php if($back_link=="" || $back_link=="#") { echo esc_attr("none"); } ?>">
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

	
				

    
    

