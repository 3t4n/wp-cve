<div class="better-blog style-6">
    <div class="container">
        <div class="row">
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

            while ($blog_post->have_posts()) : $blog_post->the_post(); ?>
                <div class="better-full-width">
                    <div class="img">
                        <!-- Ensuring the thumbnail URL is properly escaped -->
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    </div>
                    <div class="sm-post">
                        <!-- Escape the URL and title for secure output -->
                        <p><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></p>  
                        <span class="date gr-text">
                            <?php 
                            // Properly escaped date output
                            echo esc_html(get_the_date(__('d M Y'))); 
                            ?>
                        </span>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</div>
