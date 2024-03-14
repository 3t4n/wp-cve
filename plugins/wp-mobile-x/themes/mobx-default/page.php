<?php get_header();?>
<?php while( have_posts() ) : the_post();?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry">
            <div class="entry-head">
                <h1 class="page-title"><?php the_title();?></h1>
            </div>
            <div class="entry-content clearfix">
                <?php the_content();?>
            </div>
        </div>
    </article>
<?php endwhile; ?>
<?php get_footer();?>