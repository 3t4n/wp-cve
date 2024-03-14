<div class="row">	
		
<style>

<?php echo esc_attr($flip_custom_css); ?>

.fb-d7-parent-530 .fb-d7-parent-element.fb-d7-parent-element-c598e67 .fb-d7-stel-pro-widget-flip-box-cube-wrapper .fb-d7-stel-pack-flip-box-front {
    background-color: <?php echo esc_attr($flipfrontcolor); ?>  ;
}
.fb-d7-parent-530 .fb-d7-parent-element.fb-d7-parent-element-c598e67 .fb-d7-stel-pro-widget-flip-box-cube-wrapper .fb-d7-stel-pack-flip-box-back {
   background-color:<?php echo esc_attr($flipbackgcolor); ?> ;
}
.fb-d7-stel-pack-flip-box-button
{
	
	color:<?php echo esc_attr($flipbuttoncolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackccolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonborderccolor); ?> ;
}
.fb-d7-stel-pack-flip-box-layer-inner
{
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d7-stel-pack-flip-box-button:hover
{
	
	color:<?php echo esc_attr($flipbuttonhcolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackhcolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonhbordercolor); ?> ;
}
.fb-d7-stel-pack-flip-box-layer-title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d7-stel-pack-flip-box-layer-description
{
	<?php if($flip_desc_font!="0") { ?>
	font-family:'<?php echo esc_attr($flip_desc_font); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_desc_font_size); ?>px !important;
	color:<?php echo esc_attr($flipdesccolor); ?> ;	
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
	
}



.fb-d7-parent-widget-stel-flip-box-cube.fb-d7-stel-pack-flip-box-effect-cubetilt.fb-d7-stel-pack-flip-box-direction-w .fb-d7-stel-pack-flip-box-cube
{
	<?php if($flip_fliptype=="Right to Left"){ ?>
	-webkit-transform:rotateY(-90deg) translateX(-50%) rotateY(90deg);transform:rotateY(-90deg) translateX(-50%) rotateY(90deg)
	<?php } ?>
	<?php if($flip_fliptype=="Top to Bottom"){ ?>
	-webkit-transform:rotateX(-90deg) translateY(50%) rotateX(90deg);transform:rotateX(-90deg) translateY(50%) rotateX(90deg)
	<?php } ?>	
	<?php if($flip_fliptype=="Left to Right"){ ?>
	-webkit-transform:rotateY(90deg) translateX(50%) rotateY(-90deg);transform:rotateY(90deg) translateX(50%) rotateY(-90deg)
	<?php } ?>
	<?php if($flip_fliptype=="Bottom to Top"){ ?>
	-webkit-transform:rotateX(90deg) translateY(-50%) rotateX(-90deg);transform:rotateX(90deg) translateY(-50%) rotateX(-90deg)
	<?php } ?>
}
.fb-d7-parent-widget-stel-flip-box-cube.fb-d7-stel-pack-flip-box-effect-cubetilt.fb-d7-stel-pack-flip-box-direction-w .fb-d7-stel-pack-flip-box-front
{
	<?php if($flip_fliptype=="Right to Left"){ ?>
	-webkit-transform:rotateY(90deg) translateX(-50%) rotateY(-90deg);transform:rotateY(90deg) translateX(-50%) rotateY(-90deg)
	<?php } ?>
	<?php if($flip_fliptype=="Top to Bottom"){ ?>
	-webkit-transform:rotateX(90deg) translateY(50%) rotateX(-90deg);transform:rotateX(90deg) translateY(50%) rotateX(-90deg)
	<?php } ?>	
	<?php if($flip_fliptype=="Left to Right"){ ?>
	-webkit-transform:rotateY(90deg) translateX(-50%) rotateY(-90deg);transform:rotateY(90deg) translateX(-50%) rotateY(-90deg)
	<?php } ?>
	<?php if($flip_fliptype=="Bottom to Top"){ ?>
	-webkit-transform:rotateX(-90deg) translateY(-50%) rotateX(90deg);transform:rotateX(-90deg) translateY(-50%) rotateX(90deg)
	<?php } ?>
		
}
.fb-d7-parent-widget-stel-flip-box-cube.fb-d7-stel-pack-flip-box-effect-cubetilt.fb-d7-stel-pack-flip-box-direction-w .fb-d7-stel-pack-flip-box-back
{
	<?php if($flip_fliptype=="Right to Left"){ ?>
	-webkit-transform:translateX(50%) rotateY(90deg);transform:translateX(50%) rotateY(90deg)
	<?php } ?>
	<?php if($flip_fliptype=="Top to Bottom"){ ?>
	-webkit-transform:translateY(-50%) rotateX(90deg);transform:translateY(-50%) rotateX(90deg)
	<?php } ?>	
	<?php if($flip_fliptype=="Left to Right"){ ?>
	-webkit-transform:translateX(-50%) rotateY(-90deg);transform:translateX(-50%) rotateY(-90deg)
	<?php } ?>
	<?php if($flip_fliptype=="Bottom to Top"){ ?>
	-webkit-transform:translateY(50%) rotateX(-90deg);transform:translateY(50%) rotateX(-90deg)
	<?php } ?>
	
}
.fb-d7-parent-widget-stel-flip-box-cube.fb-d7-stel-pack-flip-box-effect-cubetilt.fb-d7-stel-pack-flip-box-direction-w:hover .fb-d7-stel-pack-flip-box-cube
{
	<?php if($flip_fliptype=="Right to Left"){ ?>
	-webkit-transform:rotateY(-90deg) translateX(-50%);transform:rotateY(-90deg) translateX(-50%)
	<?php } ?>
	<?php if($flip_fliptype=="Top to Bottom"){ ?>
	-webkit-transform:rotateX(-90deg) translateY(50%);transform:rotateX(-90deg) translateY(50%)
	<?php } ?>	
	<?php if($flip_fliptype=="Left to Right"){ ?>
	-webkit-transform:rotateY(90deg) translateX(50%);transform:rotateY(90deg) translateX(50%)
	<?php } ?>
	<?php if($flip_fliptype=="Bottom to Top"){ ?>
	-webkit-transform:rotateX(90deg) translateY(-50%);transform:rotateX(90deg) translateY(-50%)
	<?php } ?>	
}

.fb-d7-parent-icon
{
	
	color:<?php echo esc_attr($flipiconcolor); ?> ;
	background:<?php echo esc_attr($flipbackcolor); ?> ;
	
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
<div class="col-md-<?php echo esc_attr($flip_itemperrow)?> col-sm-6 flipbox-col-sm-4 fb-design-7-col">
<div class="fb-d7-parent fb-d7-parent-530">
	<section class="fb-d7-parent-element fb-d7-parent-element-f975199 fb-d7-ele-section-boxed fb-d7-ele-section-height-default fb-d7-ele-section-height-default fb-d7-ele-section fb-d7-parent-top-section">
		<div class="fb-d7-parent-container fb-d7-parent-column-gap-default">
			<div class="fb-d7-parent-row">
				<div class="fb-d7-parent-element fb-d7-parent-element-badb994 fb-d7-parent-column fb-d7-parent-col-25 fb-d7-parent-top-column">
					<div class="fb-d7-parent-column-wrap  fb-d7-parent-element-populated">
						<div class="fb-d7-parent-widget-wrap">
							<div class="fb-d7-parent-element fb-d7-parent-element-c598e67 fb-d7-stel-pack-flip-box-effect-cubetilt fb-d7-stel-pack-flip-box-direction-w fb-d7-parent-widget fb-d7-parent-widget-stel-flip-box-cube fb-d7-stel-transition-disabled">
								<div class="fb-d7-parent-widget-container">
									<div class="fb-d7-stel-pro-widget-flip-box-cube-wrapper">
										<div class="fb-d7-stel-pack-flip-box-cube">
											<div class="fb-d7-stel-pack-flip-box-cube-inner">
												<div class="fb-d7-stel-pack-flip-box-layer fb-d7-stel-pack-flip-box-front">
													<div class="fb-d7-stel-pack-flip-box-layer-overlay">
														<div class="fb-d7-stel-pack-flip-box-layer-inner">
															<div class="fb-d7-parent-icon-wrapper fb-d7-parent-view-default"><span class="fb-d7-parent-icon"><i aria-hidden="true" class="<?php echo esc_attr($front_icon); ?>"></i></span></div>
															<h3 class="fb-d7-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,17)); ?></h3>
															<div class="fb-d7-stel-pack-flip-box-layer-description">
																<?php echo esc_html(substr($back_description,0,40)); ?>
															</div>
														</div>
													</div>
												</div>
												<div class="fb-d7-stel-pack-flip-box-layer fb-d7-stel-pack-flip-box-back">
													<div class="fb-d7-stel-pack-flip-box-layer-overlay">
														<div class="fb-d7-stel-pack-flip-box-layer-inner">
															<h3 class="fb-d7-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,17)); ?></h3>
															<div class="fb-d7-stel-pack-flip-box-layer-description">
																<?php echo esc_html(substr($back_description,0,80)); ?>
															</div>
															<a class="fb-d7-stel-pack-flip-box-button fb-d7-parent-button fb-d7-parent-size-sm fb-d7-parent-animation-" href="<?php echo esc_url($back_link); ?>" <?php if($flip_linkopen=="New Tab"){ ?>target="_blank"<?php } ?> style="display:<?php if($back_link=="" || $back_link=="#") { echo esc_attr("none"); } ?>">
																<?php echo esc_html($btntext); ?>
															</a>
														</div>
													</div>
												</div>
												<div class="fb-d7-stel-pack-flip-box-yflank"></div>
												<div class="fb-d7-stel-pack-flip-box-xflank"></div>
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
	
					
		

    
    
