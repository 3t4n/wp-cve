<div class="row">
		
<style>

<?php echo esc_attr($flip_custom_css); ?>
.fb-d9-parent-widget-stel-flip-box-minimal.fb-d9-stel-pack-flip-box-effect-flip.fb-d9-stel-pack-flip-box-direction-down .fb-d9-stel-pack-flip-box-back
{
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

.fb-d9-stel-pack-flip-box-effect-flip.fb-d9-stel-pack-flip-box-direction-down .fb-d9-stel-pro-widget-flip-box-minimal-wrapper:hover .fb-d9-stel-pack-flip-box-front
{
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

.fb-d9-parent-530 .fb-d9-ele-element.fb-d9-ele-element-d96e1c1 .fb-d9-stel-pro-widget-flip-box-minimal-wrapper .fb-d9-stel-pack-flip-box-front {
   background-color: <?php echo esc_attr($flipfrontcolor); ?>  ;
}
.fb-d9-parent-530 .fb-d9-ele-element.fb-d9-ele-element-d96e1c1 .fb-d9-stel-pro-widget-flip-box-minimal-wrapper .fb-d9-stel-pack-flip-box-back{
	background-color:<?php echo esc_attr($flipbackgcolor); ?> ;
}
.fb-d9-parent-icon
{	
	color:<?php echo esc_attr($flipiconcolor); ?> ;
	background:<?php echo esc_attr($flipbackcolor); ?> ;	
					
}

.fb-d9-stel-pack-flip-box-front .fb-d9-stel-pack-flip-box-layer-title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	 font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}

.fb-d9-stel-pack-flip-box-back .fb-d9-stel-pack-flip-box-layer-title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	 font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}

.fb-d9-stel-pack-flip-box-layer-description
{
	<?php if($flip_desc_font!="0") { ?>
	font-family:'<?php echo esc_attr($flip_desc_font); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_desc_font_size); ?>px !important;
	color:<?php echo esc_attr($flipdesccolor); ?> ;	
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
	
}
.fb-d9-stel-pack-flip-box-button
{
	
	color:<?php echo esc_attr($flipbuttoncolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackccolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonborderccolor); ?> ;
}
.fb-d9-stel-pack-flip-box-layer-inner
{
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d9-stel-pack-flip-box-button:hover
{
	
	color:<?php echo esc_attr($flipbuttonhcolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackhcolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonhbordercolor); ?> ;
}
.fb-d9-parent-530 .fb-d9-ele-element.fb-d9-ele-element-d96e1c1 .fb-d9-stel-pro-widget-flip-box-minimal-wrapper .fb-d9-stel-pack-flip-box-front .fb-d9-parent-icon {
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
<div class="col-md-<?php echo esc_attr($flip_itemperrow)?> col-sm-6 flipbox-col-sm-4 fb-design-9-col">
<div class="fb-d9-parent fb-d9-parent-530">
	<section class="fb-d9-ele-element fb-d9-ele-element-970f61f fb-d9-parent-section-boxed fb-d9-parent-section-height-default fb-d9-parent-section-height-default fb-d9-parent-section fb-d9-parent-top-section">
		<div class="fb-d9-parent-container fb-d9-parent-column-gap-default">
			<div class="fb-d9-parent-row">
				<div class="fb-d9-ele-element fb-d9-ele-element-ce64973 fb-d9-parent-column fb-d9-parent-col-25 fb-d9-parent-top-column">
					<div class="fb-d9-parent-column-wrap  fb-d9-ele-element-populated">
						<div class="fb-d9-parent-widget-wrap">
							<div class="fb-d9-ele-element fb-d9-ele-element-d96e1c1 fb-d9-stel-pack-flip-box-direction-down fb-d9-stel-pack-flip-box-3d fb-d9-stel-pack-flip-box-effect-flip fb-d9-parent-widget fb-d9-stel-transition-disabled fb-d9-parent-widget-stel-flip-box-minimal">
								<div class="fb-d9-parent-widget-container">
									<div class="fb-d9-stel-pro-widget-flip-box-minimal-wrapper">
										<div class="fb-d9-stel-pack-flip-box-layer fb-d9-stel-pack-flip-box-front">
											<div class="fb-d9-stel-pack-flip-box-layer-overlay">
												<div class="fb-d9-stel-pack-flip-box-layer-inner">
													<div class="fb-d9-parent-icon-wrapper fb-d9-parent-view-stacked fb-d9-parent-shape-square"><span class="fb-d9-parent-icon"><i aria-hidden="true" class="<?php echo esc_attr($front_icon); ?>"></i></span></div>
													<h3 class="fb-d9-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,20)); ?></h3></div>
											</div>
										</div>
										<div class="fb-d9-stel-pack-flip-box-layer fb-d9-stel-pack-flip-box-back">
											<div class="fb-d9-stel-pack-flip-box-layer-overlay">
												<div class="fb-d9-stel-pack-flip-box-layer-inner">
													<h3 class="fb-d9-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,17)); ?></h3>
													<div class="fb-d9-stel-pack-flip-box-layer-description">
														<?php echo esc_html(substr($back_description,0,80)); ?>
													</div>
													<a class="fb-d9-stel-pack-flip-box-button fb-d9-parent-button fb-d9-parent-size-sm fb-d9-parent-animation-buzz-out" href="<?php echo esc_url($back_link); ?>" <?php if($flip_linkopen=="New Tab"){ ?>target="_blank"<?php } ?> style="display:<?php if($back_link=="" || $back_link=="#") { echo esc_attr("none"); } ?>">
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

					
		

    
    
