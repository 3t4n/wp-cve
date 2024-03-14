<div class="row">
<style>

<?php echo esc_attr($flip_custom_css); ?>
.fb-d4-stel-pack-flip-box-layer-title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}

.fb-d4-stel-pack-flip-box-layer-description
{
	<?php if($flip_desc_font!="0") { ?>
	 font-family:'<?php echo esc_attr($flip_desc_font); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_desc_font_size); ?>px !important;
	color:<?php echo esc_attr($flipdesccolor); ?> ;	
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
	
}
.fb-d4-stel-pack-flip-box-button
{
	
	color:<?php echo esc_attr($flipbuttoncolor); ?> !important ;
	background-color:<?php echo esc_attr($flipbuttonbackccolor); ?> ;
	border:solid 2px <?php echo esc_attr($flipbuttonborderccolor); ?> ;
}
.fb-d4-stel-pack-flip-box-button:hover
{
	color:<?php echo esc_attr($flipbuttonhcolor); ?> !important ;
	background-color:<?php echo esc_attr($flipbuttonbackhcolor); ?> ;
	border:solid 2px <?php echo esc_attr($flipbuttonhbordercolor); ?> ;
}


.fb-d4-stel-pack-flip-box-layer-inner
{
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d4-parent-icon
{
	
	color:<?php echo esc_attr($flipiconcolor); ?> ;
	background:<?php echo esc_attr($flipbackcolor); ?> ;
	line-height:<?php echo esc_attr($flip_icon_height); ?>px  ;
					
}

.fb-d4-parent-530 .fb-d4-parent-element.fb-d4-parent-element-1cc10c4 .fb-d4-fb-d4-stel-pro-widget-flip-box-minimal-wrapper .fb-d4-stel-pack-flip-box-front {
    background-color: <?php echo esc_attr($flipfrontcolor); ?>  ;
}

.fb-d4-parent-530 .fb-d4-parent-element.fb-d4-parent-element-1cc10c4 .fb-d4-fb-d4-stel-pro-widget-flip-box-minimal-wrapper .fb-d4-stel-pack-flip-box-back {
    background-color:<?php echo esc_attr($flipbackgcolor); ?> ;
}

.fb-d4-ele-widget-stel-flip-box-minimal.fb-d4-stel-pack-flip-box-effect-flip.fb-d4-stel-pack-flip-box-direction-left .fb-d4-stel-pack-flip-box-back
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
.fb-d4-stel-pack-flip-box-effect-flip.fb-d4-stel-pack-flip-box-direction-left .fb-d4-fb-d4-stel-pro-widget-flip-box-minimal-wrapper:hover .fb-d4-stel-pack-flip-box-front
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
.fb-d4-parent-530 .fb-d4-parent-element.fb-d4-parent-element-1cc10c4 .fb-d4-fb-d4-stel-pro-widget-flip-box-minimal-wrapper .fb-d4-stel-pack-flip-box-front .fb-d4-parent-icon {
	font-size: <?php  echo esc_attr($flip_icon_size);  ?>px !important;
	
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
<div class="col-md-<?php echo esc_attr($flip_itemperrow)?> col-sm-6 flipbox-col-sm-4 fb-design-4-col">
<div class="fb-d4-parent fb-d4-parent-530">
	<section class="fb-d4-parent-element fb-d4-parent-element-ba2f642 fb-d4-parent-section-boxed fb-d4-parent-section-height-default fb-d4-parent-section-height-default fb-d4-parent-section fb-d4-parent-top-section">
		<div class="fb-d4-parent-container fb-d4-parent-column-gap-default">
			<div class="fb-d4-parent-row">
				<div class="fb-d4-parent-element fb-d4-parent-element-3b5be30 fb-d4-parent-column fb-d4-parent-col-33 fb-d4-parent-top-column" d>
					<div class="fb-d4-parent-column-wrap  fb-d4-parent-element-populated">
						<div class="fb-d4-parent-widget-wrap">
							<div class="fb-d4-parent-element fb-d4-parent-element-1cc10c4 fb-d4-stel-pack-flip-box-direction-left fb-d4-stel-pack-flip-box-effect-flip fb-d4-parent-widget fb-d4-stel-transition-disabled fb-d4-ele-widget-stel-flip-box-minimal">
								<div class="fb-d4-parent-widget-container">
									<div id="fb-d4-stel-pro-widget-flip-box-minimal-1cc10c4" class="fb-d4-fb-d4-stel-pro-widget-flip-box-minimal-wrapper">
										<div class="fb-d4-stel-pack-flip-box-layer fb-d4-stel-pack-flip-box-front">
											<div class="fb-d4-stel-pack-flip-box-layer-overlay">
												<div class="fb-d4-stel-pack-flip-box-layer-inner">
													<div class="fb-d4-parent-icon-wrapper fb-d4-parent-view-framed fb-d4-parent-shape-circle"><span class="fb-d4-parent-icon"><i aria-hidden="true" class="<?php echo esc_attr($front_icon); ?>"></i></span></div>
													<h3 class="fb-d4-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,20)); ?></h3>
													<div class="fb-d4-stel-pack-flip-box-layer-description">
														<?php echo esc_html(substr($back_description,0,40)); ?>
													</div>
												</div>
											</div>
										</div>
										<div class="fb-d4-stel-pack-flip-box-layer fb-d4-stel-pack-flip-box-back">
											<div class="fb-d4-stel-pack-flip-box-layer-overlay">
												<div class="fb-d4-stel-pack-flip-box-layer-inner">
													<h3 class="fb-d4-stel-pack-flip-box-layer-title"><?php echo esc_html(substr($front_title,0,20)); ?></h3>
													<div class="fb-d4-stel-pack-flip-box-layer-description">
														<?php echo esc_html(substr($back_description,0,80)); ?>
													</div>
													<a class="fb-d4-stel-pack-flip-box-button fb-d4-ele-button fb-d4-parent-size-sm fb-d4-parent-animation-grow" href="<?php echo esc_url($back_link); ?>" <?php if($flip_linkopen=="New Tab"){ ?>target="_blank"<?php } ?> style="display:<?php if($back_link=="" || $back_link=="#") { echo esc_attr("none"); } ?>">
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