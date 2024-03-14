<?php 
	$cap_link = ( isset($caption_url) && $caption_url != '' ) ? $caption_url : 'javascript:void(0)' ;
 ?>
<div class="ih-item square effect2" style="width:<?php echo $thumb_width; ?>;height: <?php echo $thumb_height; ?>;border: <?php echo $thumb_border_width; ?> solid <?php echo $thumb_border_color; ?>">
	<a class="<?php echo $caption_touch; ?>" href="<?php echo $cap_link; ?>" target="<?php echo $caption_url_target; ?>">
	  	<div class="img" style="width:100%;height:100%;">
	      <img src="<?php echo $image_url; ?>" alt="img">
	    	<?php if (isset( $caption_static_title ) && $caption_static_title == 'yes'): ?>
				<h3 class="title-over-image" style="color:<?php echo $caption_heading_color; ?>;font-size:<?php echo $caption_heading_size; ?>;background: <?php echo $caption_heading_bg; ?>;top:<?php echo $title_postion; ?>">
		  	  		<?php echo $caption_heading; ?>		
		  	  	</h3>
			<?php endif ?>
	   	</div>
	  	<?php if ( $caption_bg_type == 'color' ) { ?>
  				<div class="info" style="background:<?php echo $caption_bg_color; ?>;">
  					<?php if (isset( $caption_static_title ) && $caption_static_title == 'no'): ?>
  		  	  			<h3 style="color:<?php echo $caption_heading_color; ?>;font-size:<?php echo $caption_heading_size; ?>;background: <?php echo $caption_heading_bg; ?>">
  		  	  				<?php echo $caption_heading; ?>
  		  	  			</h3>
  		  	  		<?php endif ?>
  		  			<p style="color:<?php echo $caption_desc_color; ?>;font-size:<?php echo $caption_description_size; ?>;<?php echo $title_enabled_style; ?>">
  		  				<?php echo $content; ?>
  		  					
  		  			</p>
  				</div>
	  	<?php } else { ?>
	  		<div class="info" style="background:<?php echo 'url('.$bg_image_url.')'; ?>; background-size: contain;">
	  		</div>
	  	<?php } ?>
	</a>
</div>
