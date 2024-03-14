<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header(); ?>
<section id="main-container" class="site-main container" role="main">
    <?php if ( have_posts() ) : ?>
        <div class="single-portfolios-container">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php echo Opalportfolio_Template_Loader::get_template_part( 'content-single-portfolio' ); ?>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <?php echo Opalportfolio_Template_Loader::get_template_part( 'content-data-none' ); ?>
    <?php endif; ?>

</section><!-- .content-area -->

<?php get_footer(); ?>
