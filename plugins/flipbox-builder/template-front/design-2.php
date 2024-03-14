<div class="row">

<style>

<?php echo esc_attr($flip_custom_css); ?>				
.fb-d2-ele-flip-box__button
{
	color:<?php echo esc_attr($flipbuttoncolor); ?> !important;
	background:<?php echo esc_attr($flipbuttonbackccolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonborderccolor); ?> ;
}
.fb-d2-ele-flip-box__button:hover
{
	color:<?php echo esc_attr($flipbuttonhcolor); ?> !important;
	background:<?php echo esc_attr($flipbuttonbackhcolor); ?> ;
	border:2px solid <?php echo esc_attr($flipbuttonhbordercolor); ?> ;
}		
				
.fb-d2-eael-elements-flip-box-heading
{
	<?php if($flip_title_fontfamily!="0") { ?>
	font-family:'<?php echo esc_attr($flip_title_fontfamily); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_title_font); ?>px !important ;
	color:<?php echo esc_attr($fliptitlecolor); ?>  !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
		
}
.fb-d2-eael-elements-flip-box-content
{
	<?php if($flip_desc_font!="0") { ?>
	  font-family:'<?php echo esc_attr($flip_desc_font); ?>' !important ;
	<?php } ?>
	font-size:<?php echo esc_attr($flip_desc_font_size); ?>px !important;
	color:<?php echo esc_attr($flipdesccolor); ?> !important;
	text-align:<?php echo esc_attr($flip_textalign); ?> ;
		
		
}

.fb-d2-eael-elements-flip-box-icon-image
{
	
	color:<?php echo esc_attr($flipiconcolor); ?> ;
	background:<?php echo esc_attr($flipbackcolor); ?> ;
	
	
	
}
.fb-d2-eael-elements-flip-box-vertical-align .fb-d2-eael-elements-flip-box-padding {
text-align:<?php echo esc_attr($flip_textalign); ?> ;
}
.fb-d2-eael-elements-flip-box-front-container
{
	background:<?php echo esc_attr($flipfrontcolor); ?> ;
}
.fb-d2-eael-elements-flip-box-rear-container
{
	background:<?php echo esc_attr($flipbackgcolor); ?> ;
}	
		
.fb-d2-eael-animate-flip.fb-d2-eael-animate-right.fb-d2-eael-elements-flip-box-container:hover .fb-d2-eael-elements-flip-box-flip-card, .fb-d2-eael-animate-flip.fb-d2-eael-animate-right .fb-d2-eael-elements-flip-box-rear-container {
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
	-webkit-transform:rotateX(0) rotateY(-180deg) ;transform:rotateX(0) rotateY(-180deg) ;
	<?php } ?>
}
		
.fb-d2-ele-flip-box__button{
	<?php if ($flip_textalign=="Left"){?>
	float:left ;
	<?php } else if($flip_textalign=="Right"){?>
	float:right ;
	<?php }?>
}
.fb-d2-eael-elements-flip-box-icon-image i {
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
<div class="col-md-<?php echo esc_attr($flip_itemperrow)?> col-sm-6 flipbox-col-sm-4 fb-design-2-col ">
<div class="fb-d2-parent fb-d2-parent-1519">
	<section class="fb-d2-ele-section fb-d2-parent-inner-section fb-d2-parent-element fb-d2-parent-element-2cddf982 fb-d2-ele-section-boxed fb-d2-ele-section-height-default fb-d2-ele-section-height-default">
		<div class="fb-d2-parent-container fb-d2-parent-column-gap-extended">
			<div class="fb-d2-parent-row">
				<div class="fb-d2-parent-column fb-d2-parent-col-33 fb-d2-parent-inner-column fb-d2-parent-element fb-d2-parent-element-5ac15e10">
					<div class="fb-d2-parent-column-wrap fb-d2-parent-element-populated">
						<div class="fb-d2-parent-widget-wrap">
							<div class="fb-d2-parent-element fb-d2-parent-element-7b240a6c fb-d2-eael-elements-flip-box-vertical-align eael-flipbox-img-default fb-d2-parent-widget fb-d2-parent-widget-eael-flip-box" data-id="7b240a6c">
								<div class="fb-d2-parent-widget-container">
									<div class="fb-d2-eael-elements-flip-box-container fb-d2-eael-animate-flip fb-d2-eael-animate-right eael-content">
										<div class="fb-d2-eael-elements-flip-box-flip-card">
											<div class="fb-d2-eael-elements-flip-box-front-container">
												<div class="fb-d2-eael-elements-slider-display-table">
													<div class="fb-d2-eael-elements-flip-box-vertical-align">
														<div class="fb-d2-eael-elements-flip-box-padding">
															<div class="fb-d2-eael-elements-flip-box-icon-image"> <i class=" <?php echo esc_attr($front_icon); ?>" aria-hidden="true"></i> </div>
															<h2 class="fb-d2-eael-elements-flip-box-heading"><?php echo esc_html(substr($front_title,0,17)); ?></h2>
															<div class="fb-d2-eael-elements-flip-box-content">
																<p></p>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="fb-d2-eael-elements-flip-box-rear-container">
												<div class="fb-d2-eael-elements-slider-display-table">
													<div class="fb-d2-eael-elements-flip-box-vertical-align">
														<div class="fb-d2-eael-elements-flip-box-padding">
															<h2 class="fb-d2-eael-elements-flip-box-heading"><?php echo esc_html(substr($front_title,0,17)); ?></h2>
															<div class="fb-d2-eael-elements-flip-box-content">
																<p>
																	<?php if($flip_itemperrow==3){ echo esc_html(substr($back_description,0,40)); } else {
				echo esc_html(substr($back_description,0,80));
	} ?>
																</p>
															</div>
															<a class="fb-d2-ele-flip-box__button fb-d2-parent-button fb-d2-parent-size-sm" href="<?php echo esc_url($back_link); ?>" <?php if($flip_linkopen=="New Tab"){ ?>target="_blank"<?php } ?> style="display:<?php if($back_link=="" || $back_link=="#") { echo esc_attr("none"); } ?>">
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
