<div class="better-blog style-3">
    <div class="container">
        <div class="row">
            <?php 
            $args = [
                'post_type' => 'post',
                'posts_per_page' => $settings['posts_number'],
                'order' => $settings['order'],
            ];

            if ( !empty($settings['cat']) && $settings['cat'] != 'all' ) {
                $args['category_name'] = $settings['cat'];
            }

            $blog_post = new WP_Query($args);

            while ($blog_post->have_posts()) : $blog_post->the_post();
            ?>
            <div class="col-lg-4">
                <div class="item list md-mb50 wow fadeInUp" data-wow-delay=".3s">
                    <div class="img">
                        <?php if (has_post_thumbnail()) { the_post_thumbnail(); } ?>
                    </div>
                    <div class="cont">
                        <a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" class="date custom-font">
                            <span><i><?php echo esc_html(get_the_date('d')); ?></i> <?php echo esc_html(get_the_date('M')); ?></span>
                        </a>
                        <div class="info custom-font">
                            <div class="author">
                                <span>by / <?php the_author_posts_link(); ?></span>
                            </div>
                            <div class="tag">
                                <span><?php the_category(' - '); ?></span>
                            </div>
                        </div>
                        <h6>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h6>
                        <div class="btn-more custom-font">
                            <a href="<?php the_permalink(); ?>" class="better-simple-btn">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>
