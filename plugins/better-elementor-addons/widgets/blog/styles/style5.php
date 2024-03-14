<div class="better-blog style-5">
    <div class="container">
        <div class="row">
            <?php 
            $args = [
                'post_type' => 'post',
                'posts_per_page' => $settings['posts_number'],
                'order' => $settings['order'],
            ];

            if (!empty($settings['cat']) && $settings['cat'] != 'all') {
                $args['category_name'] = $settings['cat'];
            }

            $blog_post = new WP_Query($args);

            while ($blog_post->have_posts()) : $blog_post->the_post(); ?>
            <div class="col-lg-4">
                <div class="item md-mb50">
                    <div class="img">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                        <div class="tag">
                            <?php the_category(' - '); ?>
                        </div>
                    </div>
                    <div class="cont">
                        <div class="info">
                            <h6>By <?php the_author_posts_link(); ?> <span><?php echo esc_html(get_the_date('d F')); ?></span></h6>
                        </div>
                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <a href="<?php the_permalink(); ?>" class="more">Read More</a>
                    </div>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</div>
