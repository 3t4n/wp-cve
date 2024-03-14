
		
		
<div class="better-testimonial style-1 testi-slider">
    <?php foreach ( $settings['testi_list'] as $index => $item ) : 
    ?>
    <div>
    	
        <h3><?php echo esc_html($item['title']); ?></h3>
        
        <p class="testi-from"><?php echo esc_html($item['position']); ?></p>
        
        <div class="author-img">
			<img src="<?php echo esc_url ( $item['image']['url']); ?>" alt="img">
		</div>
        
        <p class="testi-text">
       	<?php echo  esc_html($item['text']); ?>
        </p>

        <?php
		    for($x=1;$x<=$item['rate'];$x++) {
		        echo '<i class="rating-icon fa fa-star" aria-hidden="true"></i>';
		    }
		    if (strpos($item['rate'],'.')) {
		        echo '<i class="rating-icon fas fa-star-half-alt" aria-hidden="true"></i>';
		        $x++;
		    }
		    while ($x<=5) {
		        echo '<i class="rating-icon far fa-star" aria-hidden="true"></i>';
		        $x++;
		    }
	    ?>
        
    </div>
    
    <?php endforeach; ?>
</div><!--/.testimonial-->
	


