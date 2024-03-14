<a class="cdc_shop_loop_box" href="<?php echo the_permalink(); ?>">
	<div class="cdc_thetip" >
		<strong class="cdc_thetitle"><?php the_title();?></strong>
		<div class="cdc_thecontent">
			<!-- short description -->
			<?= get_the_excerpt(); ?>
			<!-- end short description -->
		</div>
	</div>
</a>