<?php 
global $portfolio; 
$portfolio 		= new Opalportfolio_Portfolios( get_the_ID() );
$client 		= $portfolio->getClient();
$budgets 		= $portfolio->getBudgets();
$completed 		= $portfolio->getCompleted();
$location 		= $portfolio->getLocation();
$categories 	= $portfolio->getCategoryTax();
$link 			= $portfolio->getLink();
$desc 			= !empty(get_post_meta(get_the_ID(), 'opal_portfolio_desc' , true)) ? get_post_meta(get_the_ID(), 'opal_portfolio_desc' , true) : '' ;
?>
<div class="entry-content">
	<div class="service-image">
		<?php the_post_thumbnail('full'); ?>
	</div>
	<div class="content-top">
		<div class="about-left">
			<h3 class="heading"><?php echo esc_html('About the project', 'opalportfolios'); ?></h3>
			<div class="portfolio-details">
				<ul class="portfolio-details-list">

					<?php if(!empty($client)) : ?>
						<li>
							<label><?php echo esc_html( 'Client', 'opalportfolios' ); ?></label>
							<span><?php echo esc_attr($client); ?></span>
						</li>
					<?php endif; ?>
					<?php if(!empty($categories)) : ?>
						<li>
							<label><?php echo esc_html( 'Categories', 'opalportfolios' ); ?></label>
							<span>
								<?php 
								foreach($categories as $categorie){
									$namect = $categorie->name.',';
									if ($categorie === end($categories) || count($categories) == 1){
				                        $namect = $categorie->name;
				                    }
									echo esc_attr( $namect );
								} ?>	
							 </span>
						</li>
					<?php endif; ?>
					<?php if(!empty($completed)) : ?>
						<li>
							<label><?php echo esc_html( 'Completed', 'opalportfolios' ); ?></label>
							<span><?php echo trim($completed); ?></span>
						</li>
					<?php endif; ?>
					<?php if(!empty($budgets)) : ?>
						<li>
							<label><?php echo esc_html( 'Budgets', 'opalportfolios' ); ?></label>
							<span><?php echo trim($budgets); ?></span>
						</li>
					<?php endif; ?>
					<?php if(!empty($location)) : ?>
						<li>
							<label><?php echo esc_html( 'Location', 'opalportfolios' ); ?></label>
							<span><?php echo trim($location); ?></span>
						</li>
					<?php endif; ?>
					<?php if(!empty($link)) : ?>
						<li>
							<label><?php echo esc_html( 'Project url', 'opalportfolios' ); ?></label>
							<span><a href="<?php echo esc_url($link); ?>"><?php echo trim($link); ?></a></span>
						</li>
					<?php endif; ?>
				</ul>
			</div>
			<?php opalportfolio_single_share(); ?>
		</div>
		<div class="about-right">
			<div class="description"><?php echo trim($desc); ?></div>
		</div>
	</div>
	
	<div class="content-bottom">
		<?php the_content(); ?>
	</div>
</div><!-- .entry-content -->