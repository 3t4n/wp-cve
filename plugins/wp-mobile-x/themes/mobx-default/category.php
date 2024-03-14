<?php get_header();?>
<div class="archive-list">
    <ul class="post-list">
        <?php while( have_posts() ) : the_post();?>
            <?php get_template_part( 'content' , 'list' ); ?>
        <?php endwhile; ?>
    </ul>
    <?php global $paged, $wp_query; if(!$paged){$paged = 1;}?>
    <a class="load-more j-load-more" href="<?php echo $wp_query->max_num_pages>$paged?get_pagenum_link($paged+1):'javascript:;';?>" data-type="cat" data-id="<?php echo get_queried_object_id();?>" data-page="<?php echo $paged;?>"><?php _e('Load more', 'wpcom');?></a>
</div>
<?php get_footer();?>