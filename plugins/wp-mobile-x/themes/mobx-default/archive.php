<?php get_header();?>
<div class="archive-list">
    <ul class="post-list">
        <?php while( have_posts() ) : the_post();?>
            <?php get_template_part( 'content' , 'list' ); ?>
        <?php endwhile; ?>
    </ul>
    <?php global $paged, $wp_query; if(!$paged){$paged = 1;} ?>
    <div class="load-more-wrap">
        <?php if($paged>1) {?>
        <a class="load-more<?php echo $wp_query->max_num_pages>$paged?' load-more-half':''?>" href="<?php echo get_pagenum_link($paged-1);?>"><?php _e('&laquo; Previous', 'wpcom');?></a>
        <?php } ?>
        <?php if($wp_query->max_num_pages>$paged) {?>
        <a class="load-more<?php echo $paged>1?' load-more-half':''?>" href="<?php echo get_pagenum_link($paged+1);?>"><?php _e('Next &raquo;', 'wpcom');?></a>
        <?php } ?>
    </div>
</div>
<?php get_footer();?>