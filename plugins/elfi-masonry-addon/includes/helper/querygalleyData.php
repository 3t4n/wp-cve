<?php
use \Elementor\Icons_Manager;

	?>

	<div  class=" grid-item <?php echo esc_attr($on_draught) . ' ' . $layout_class; ?>" style="width:<?php echo esc_attr($grid_layout) ?>%">
	   <div id="rerepopup-<?php echo esc_attr($x . $rand_type); ?>" class="image-popup-child white-popup mfp-hide">
	      <img src="<?php echo esc_url($image_link); ?>" alt="<?php echo esc_attr($image_alt); ?>">

	  	<?php  	if($enable_link  == 'yes'){ 
	      echo '<h2 class="elfig-mfg-title"><a href="'.esc_url($gallery_link).'" class="elfig_poptilie_link_">'.esc_html($title).'</a></h2>';
	  }else{
	  	echo '<h2 class="elfig-mfg-title">'.$title.'</h2>';
	  } ?>
	   </div>
	   <?php if ($grid_style_class == 'portfolio_wrap_free')
	   { 

	   	?>

	   	<div class="elfi-free-item elfi-free-effect__item <?php echo esc_attr($grid_effetcs); ?>">

	   	  <img class="elfi-free-item__image" src="<?php echo esc_url($image_link); ?>"  alt="<?php echo esc_attr($image_alt); ?>" >

	   	        <div class="portfolio_content">

	   	  <div class="elfi-free-item__info">
		<?php  	if($enable_link  == 'yes'){ echo '<a href="'.$gallery_link.'">
	   	    <h2 class="elfi-free-item__header">'.esc_html($title).'</h2></a>';
	    }else{ echo '<h2 class="elfi-free-item__header">'.esc_html($title).'</h2>';} ?>
	   	    <div class="elfi-free-item__links">
			<?php  	if($enable_link  == 'yes'){ ?>
	   	      <div class="elfi-free-item__link-block">

	   	      	<a href="<?php echo esc_url($gallery_link); ?>" class="elfi_port_link "><?php Icons_Manager::render_icon($linkicon, ['aria-hidden' => 'true']) ?></a>
	   	      </div>
			<?php } ?>
	   	      <div class="elfi-free-item__link-block">

					<?php include ELFI_PRO_PATH .'includes/extra/elfigalleypopup.php'; ?>


	   	      </div>

	   	    </div>

	   	  </div>

	   	</div>
	   </div>

<?php
	   }  

	?>



