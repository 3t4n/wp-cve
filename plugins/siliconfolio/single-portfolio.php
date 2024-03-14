<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly?>
<?php get_header(); ?>
<div class="this_page oi_page_holder">
	<div class="oi_sinle_portfolio_holder">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="oi_portfolio_page_holder">
			<?php do_shortcode(the_content()); if (function_exists('si_after_content')) {si_after_content();};?>
        </div>
        
		<?php endwhile; endif;?>
    </div>
</div>
<?php get_footer(); ?>