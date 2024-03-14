<?php get_header();?>
<div class="archive-list">
    <?php if( have_posts() ) : ?>
    <ul class="post-list">
        <?php while( have_posts() ) : the_post();?>
            <?php get_template_part( 'content' , 'list' ); ?>
        <?php endwhile; ?>
    </ul>
    <?php global $paged, $wp_query; if(!$paged){$paged = 1;}?>
    <a class="load-more j-load-more" href="<?php echo $wp_query->max_num_pages>$paged?get_pagenum_link($paged+1):'javascript:;';?>" data-type="search" data-id="<?php echo get_search_query();?>" data-page="<?php echo $paged;?>"><?php _e('Load more', 'wpcom');?></a>
    <?php else : ?>
        <p style="padding: 5rem 1.5rem;margin-bottom: 3rem;font-size: 1.4rem;line-height:1.6;color:#666;text-align: center;"><?php _e("Sorry, but nothing matched your search terms. Please try again with some different keywords.", 'wpcom');?></p>
    <?php endif; ?>
</div>
<?php get_footer();?>