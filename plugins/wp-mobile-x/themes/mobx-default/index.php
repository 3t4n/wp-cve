<?php global $mobx_options; get_header();?>
<?php if(isset($mobx_options['slider_img']) && $mobx_options['slider_img'] && $mobx_options['slider_img'][0]){ ?>
<div class="slider">
    <ul><?php foreach($mobx_options['slider_img'] as $k => $img){ ?>
        <li>
            <?php if(isset($mobx_options['slider_url'][$k]) && $mobx_options['slider_url'][$k]){ ?>
                <a href="<?php echo esc_url($mobx_options['slider_url'][$k]); ?>">
                    <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($mobx_options['slider_title'][$k]); ?>">
                </a>
                <?php if(isset($mobx_options['slider_title'][$k]) && $mobx_options['slider_title'][$k]){ ?>
                    <h3 class="slider-title">
                        <a href="<?php echo esc_url($mobx_options['slider_url'][$k]); ?>">
                            <?php echo $mobx_options['slider_title'][$k];?>
                        </a>
                    </h3>
                <?php } ?>
            <?php } else { ?>
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($mobx_options['slider_title'][$k]); ?>">
                <?php if(isset($mobx_options['slider_title'][$k]) && $mobx_options['slider_title'][$k]){ ?>
                    <h3 class="slider-title">
                        <?php echo $mobx_options['slider_title'][$k];?>
                    </h3>
                <?php } ?>
            <?php } ?>
        </li>
    <?php } ?></ul>
    <div class="slider-nav">
        <?php $i=0; foreach($mobx_options['slider_img'] as $img){ ?>
        <span<?php echo $i==0?' class="active"':'';?>></span>
        <?php $i++;} ?>
    </div>
</div>
<?php } ?>

<div class="archive-list">
    <ul class="post-list">
        <?php while( have_posts() ) : the_post();?>
            <?php get_template_part( 'content' , 'list' ); ?>
        <?php endwhile; ?>
    </ul>
    <?php global $paged, $wp_query; if(!$paged){$paged = 1;}?>
    <a class="load-more j-load-more" href="<?php echo $wp_query->max_num_pages>$paged?get_pagenum_link($paged+1):'javascript:;';?>" data-type="index" data-page="<?php echo $paged;?>"><?php _e('Load more', 'wpcom');?></a>
</div>
<?php get_footer();?>