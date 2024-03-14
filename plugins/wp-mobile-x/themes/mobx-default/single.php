<?php get_header();?>
<?php while( have_posts() ) : the_post();?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry">
            <div class="entry-head">
                <h1 class="entry-title"><?php the_title();?></h1>
                <div class="entry-meta">
                    <span class="entry-time"><?php the_time(get_option('date_format'));?></span>
                    <?php the_category( ', ', '', false ); ?>
                    <?php if(function_exists('the_views')) {
                        $views = intval(get_post_meta($post->ID, 'views', true));
                        ?>
                        <span class="entry-views"><?php printf(__('%s views', 'wpcom'), $views); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="entry-content clearfix">
                <?php the_content();?>
            </div>
        </div>
    </article>
    <div class="archive-list">
        <?php mobx_related_post('content-list', 5, __('Related Posts', 'wpcom'), 'post-list');?>
    </div>
    <?php if ( comments_open() ) { ?><?php comments_template(); ?><?php } ?>
<?php endwhile; ?>
<?php get_footer();?>