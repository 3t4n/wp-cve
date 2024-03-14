<?php 
    $architec_paged = $settings['paged_on'] != 'yes' ? (get_query_var('paged') ? get_query_var('paged') : 1) : '';
    $args = [
        'posts_per_page' => $settings['blog_post'],
        'paged' => $architec_paged,
        'post_type' => 'post',
    ];

    if ($settings['sort_cat'] == 'yes' && !empty($settings['blog_cat'])) {
        $args['cat'] = $settings['blog_cat'];
    }

    $query = new WP_Query($args);
?>

<div class="better-blog style-9">
    <div class="row">
        <?php while ($query->have_posts()): $query->the_post(); ?>
            <div class="<?php 
                echo esc_attr(
                    $settings['blog_column'] == 'one' ? 'col-md-12' :
                    ($settings['blog_column'] == 'two' ? 'col-md-6' :
                    ($settings['blog_column'] == 'three' ? 'col-md-4' :
                    ($settings['blog_column'] == 'four' ? 'col-md-3' : '')))
                );
            ?>">
                <div class="item md-mb50 wow fadeInUp" data-wow-delay=".3s">
                    <div class="img">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    </div>
                    <div class="cont">
                        <div>
                            <?php if ($settings['meta_show'] == 'yes') : ?>
                                <div class="info">
                                    <a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" class="date">
                                        <span><i><?php echo esc_html(get_the_date('d')); ?></i> <?php echo esc_html(get_the_date('F')); ?></span>
                                    </a>
                                    <span>/</span>
                                    <?php if ($settings['cat_show'] == 'yes') : ?>
                                        <div class="tag">
                                            <?php the_category(' - '); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <h5>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h5>
                            <?php if ($settings['button_show'] == 'yes') : ?>
                                <div class="btn-more">
                                    <a href="<?php the_permalink(); ?>" class="simple-btn"><?php echo esc_html($settings['button']); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</div>
