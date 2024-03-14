<!-- This file can be modified and placed in your theme directory, The plugin will search for a file with this name there first and use it if it exists -->
<div class="B24-Property">
	<section class="post-content clearfix">
		<div class="twelvecol first  simple">
		<h2 class="B24-H1Property"><?php echo $post->post_title; ?></h2>
			<div class="B24-ProppriceButton">
				<div class="B24-Propprice">
				€ <?php echo $bestprice ?>
				</div>
				<div class="B24-Bookbutton">
				<a class="fancybox fancybox.iframe" href="<?php echo esc_url($bookurl); ?>">Book Now</a>
				</div>
			</div>
			<div class="clearboth"></div>
            <div class="fourcol first">
            <?php the_content(); ?>   
            </div> 
            <div class="eightcol last">
            	<div class=""> <?php echo get_post_meta($post->ID, 'desc', true) ?> </div>   
            </div>
			<div class="twelvecol first">
				<div class=""> <?php echo get_post_meta($post->ID, 'more', true) ?> </div>   
            </div>
		</div> 
	</section> <!-- end article section -->
	
</div> 
