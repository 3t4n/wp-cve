<div class="better-blog style-8">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="posts">
                    <?php 
                    $args = [
                        'post_type' => 'post',
                        'posts_per_page' => $settings['posts_number'],
                        'order' => $settings['order'],
                    ];
                    
                    if (!empty($settings['cat']) && $settings['cat'] !== 'all') {
                        $args['category_name'] = $settings['cat'];
                    }

                    $blog_post = new WP_Query($args);

                    while($blog_post->have_posts()) : $blog_post->the_post(); ?>
                        <div class="item mb-80">
                            <div class="img">
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                </a>
                            </div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-10">
                                        <a href="#0" class="date">
                                            <span class="num"><?php echo esc_html(get_the_date('d')); ?></span>
                                            <span><?php echo esc_html(get_the_date('F')); ?></span>
                                        </a>
                                        <div class="tags">
                                            <?php the_category(' - '); ?>
                                        </div>
                                        <h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <p>
                                            <?php 
                                            echo wp_kses_post(wp_trim_words(get_the_excerpt(), $settings['excerpt'], esc_html($settings['excerpt_after'])));
                                            ?>
                                        </p>
                                        <a href="<?php the_permalink(); ?>" class="better-simple-btn mt-30">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
