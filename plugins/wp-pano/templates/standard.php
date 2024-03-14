<?php $id = get_the_ID();?>
<div id="wppano_post_wrapper">
	<div id="wppano_post_title"><h1><?php the_title();?></h1></div>
	<div id="wppano_post_content">
		<?php the_content(); ?>
	</div>
	<div class="wp-pano-close-icon" onclick="wppano_close_post();"></div>
</div>
