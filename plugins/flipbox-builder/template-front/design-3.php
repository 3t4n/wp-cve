<div class="row">
<style>
@media screen and (max-width: 768px)
{
	.fb-d3-ele-flip-box__layer__title
	{
		font-size:14px !important;
	}
	.fb-d3-ele-flip-box__layer__description
	{
		font-size:11px !important;
	}
}
@media (min-width: 769px)
{
	.fb-d3-ele-flip-box__layer__title
	{
		font-size:<?php echo esc_attr($flip_title_font); ?>px !important;
	}
	.fb-d3-ele-flip-box__layer__description
	{
		font-size:<?php echo esc_attr($flip_desc_font_size); ?>px !important;
	}
}
<?php if($flip_itemperrow==6){ ?>
.fb-d3-ele-section.fb-d3-ele-section-stretched {
    position: relative;
    width: 100%;
}
<?php } else { ?>
.fb-d3-ele-section.fb-d3-ele-section-stretched {
	position: relative;
	width: 100%;
}
<?php } ?>
<?php echo esc_attr($flip_custom_css); ?>
.fb-d3-ele-flip-box__layer__description
{
	<?php if($flip_desc_font!="0") { ?>
	 font-family:'<?php echo esc_attr($flip_desc_font); ?>' !important ;
	<?php } ?>
	
	color:<?php echo esc_attr($flipdesccolor); ?> ;	
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d3-ele-flip-box__layer__title
{
	<?php if($flip_title_fontfamily!="0") { ?>
	font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	
	color:<?php echo esc_attr($fliptitlecolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d3-ele-flip-box__button
{	
	color:<?php echo esc_attr($flipbuttoncolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackccolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonborderccolor); ?> ;
}
.fb-d3-ele-flip-box__button:hover
{	
	color:<?php echo esc_attr($flipbuttonhcolor); ?> !important;
	background-color:<?php echo esc_attr($flipbuttonbackhcolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonhbordercolor); ?> ;
	
}


.fb-d3-parent-icon
{
	
	color:<?php echo esc_attr($flipiconcolor); ?> ;
	background:<?php echo esc_attr($flipbackcolor); ?> ;
	
}
.fb-d3-ele-flip-box__front {
    background:<?php echo esc_attr($flipfrontcolor); ?> ;
}
.fb-d3-ele-flip-box__back {
     background:<?php echo esc_attr($flipbackgcolor); ?> ;
    
}
.fb-d3-ele-flip-box__layer__overlay
{
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
}

.fb-d3-ele-flip-box--effect-flip.fb-d3-ele-flip-box--direction-up .fb-d3-ele-flip-box__back{
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
.fb-d3-ele-flip-box--effect-flip.fb-d3-ele-flip-box--direction-up .fb-d3-ele-flip-box:hover .fb-d3-ele-flip-box__front{
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
.fb-d3-parent-icon {	
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
<div class="col-md-<?php echo esc_attr($flip_itemperrow)?> col-sm-6 flipbox-col-sm-4 fb-design-3-col">
<div class="fb-d3-parent fb-d3-parent-493">
	<section class="fb-d3-ele-section fb-d3-parent-top-section fb-d3-parent-element fb-d3-parent-element-5dd4de56 fb-d3-ele-section-stretched fb-d3-ele-section-full_width fb-d3-ele-section-height-default fb-d3-ele-section-height-default">
		<div class="fb-d3-parent-container fb-d3-parent-column-gap-default">
			<div class="fb-d3-parent-row">
				<div class="fb-d3-parent-column fb-d3-parent-col-33 fb-d3-parent-top-column fb-d3-parent-element fb-d3-parent-element-1dfc5523">
					<div class="fb-d3-parent-column-wrap fb-d3-parent-element-populated">
						<div class="fb-d3-parent-widget-wrap">
							<div class="fb-d3-parent-element fb-d3-parent-element-69ba3be0 fb-d3-ele-flip-box--3d fb-d3-ele-flip-box--effect-flip fb-d3-ele-flip-box--direction-up fb-d3-parent-widget fb-d3-parent-widget-flip-box">
								<div class="fb-d3-parent-widget-container">
									<div class="fb-d3-ele-flip-box">
										<div class="fb-d3-ele-flip-box__layer fb-d3-ele-flip-box__front">
											<div class="fb-d3-ele-flip-box__layer__overlay">
												<div class="fb-d3-ele-flip-box__layer__inner">
													<div class="fb-d3-parent-icon-wrapper fb-d3-parent-view-default">
														<div class="fb-d3-parent-icon"> <i class="<?php echo esc_attr($front_icon); ?>"></i> </div>
													</div>
													<h3 class="fb-d3-ele-flip-box__layer__title">
								<?php echo esc_html(substr($front_title,0,17)); ?>					</h3>
													<div class="fb-d3-ele-flip-box__layer__description">
														<?php if($flip_itemperrow==3){ echo esc_html(substr($back_description,0,40)); } else {
				echo esc_html(substr($back_description,0,80));
	} ?>
													</div>
												</div>
											</div>
										</div>
										<div class="fb-d3-ele-flip-box__layer fb-d3-ele-flip-box__back">
											<div class="fb-d3-ele-flip-box__layer__overlay">
												<div class="fb-d3-ele-flip-box__layer__inner">
													<a class="fb-d3-ele-flip-box__button fb-d3-parent-button fb-d3-parent-size-sm" href="<?php echo esc_url($back_link); ?>" <?php if($flip_linkopen=="New Tab"){ ?>target="_blank"<?php } ?> style="display:<?php if($back_link=="" || $back_link=="#") { echo esc_attr("none"); } ?>">
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

			