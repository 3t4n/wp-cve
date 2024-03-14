<?php global $wp_query;?>
<li class="item">
    <?php $has_thumb = get_the_post_thumbnail(); if($has_thumb){ ?>
    <div class="item-thumb">
        <a class="item-img" href="<?php the_permalink();?>" title="<?php echo esc_attr(get_the_title());?>">
            <?php the_post_thumbnail(); ?>
        </a>
        <?php
        $category = get_the_category();
        $cat = $category?$category[0]:'';
        if($cat){
        ?>
        <a class="item-category" href="<?php echo get_category_link($cat->cat_ID);?>"><?php echo $cat->name;?></a>
        <?php } ?>
    </div>
    <?php } ?>
    <div class="item-content"<?php echo ($has_thumb?'':' style="margin-left: 0;"');?>>
        <h2 class="item-title">
            <a href="<?php the_permalink();?>" title="<?php echo esc_attr(get_the_title());?>">
                <?php if( is_home() && $wp_query->is_main_query() && is_sticky()){ ?><span class="sticky-post"><?php _e('STICKY', 'wpcom');?></span><?php } ?> <?php the_title();?>
            </a>
        </h2>
        <div class="item-meta">
            <span class="item-meta-li date"><?php the_time(get_option('date_format'));?></span>
            <?php
            if(function_exists('the_views')) {
                $views = intval(get_post_meta($post->ID, 'views', true));
            ?>
            <span class="item-meta-li views"><i class="fa fa-eye"></i> <span class="data"><?php echo $views; ?></span></span>
            <?php } ?>
            <span class="item-meta-li comments"><i class="fa fa-message-square"></i> <span class="data"><?php echo get_comments_number();?></span></span>
        </div>
    </div>
</li>