<?php 
    $categories = wp_get_post_terms( get_the_ID(), PE_CAT);
    $color = !empty(get_post_meta(get_the_ID(), 'opal_portfolio_color' , true)) ? get_post_meta(get_the_ID(), 'opal_portfolio_color' , true) : '' ;
?>
<div class="gridblock-inner">
	<?php the_post_thumbnail('portfolio-medium'); ?>
	<span class="mask" style="background-color: <?php echo esc_html($color);?>;"><?php echo esc_html($color);?></span>
	<div class="gridblock-background-hover">
	    
        <div class="boxtitle-hover">
        	<?php if($show_category): ?>
		        <div class="portfolio-categories">
		        <?php foreach( $categories as $categorie ) :
		                $namect = $categorie->name.',';
		                if ($categorie === end($categories) || count($categories) == 1){
		                    $namect = $categorie->name;
		                }?>
		            <a href="<?php echo esc_url( get_term_link($categorie->term_id, PE_CAT) );?>" class="categories-link"><span><?php echo trim( $namect ); ?></span> </a>
		        <?php endforeach; ?>
		        </div>
		    <?php endif; ?>
            <div class="work-details">
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <?php if($show_description): ?>
                	<p class="entry-content work-description"><?php echo wp_trim_words(get_the_excerpt(), 10,  '...'); ?></p>
                <?php endif; ?>
            </div>
        </div>
	    
	</div>
</div>