<div class="better-blog style-4">
    <?php $count = 0;
    while ($query->have_posts()): $query->the_post(); 
    $count++; ?>
        <div class="<?php 
        echo esc_attr($settings['blog_column'] == 'one' ? 'col-md-12' : 
              ($settings['blog_column'] == 'two' ? 'col-md-6' : 
              ($settings['blog_column'] == 'three' ? 'col-md-4' : 
              ($settings['blog_column'] == 'four' ? 'col-md-3' : '')))); 
        ?>">
            <div class="item">
                <div class="img better-bg-img" data-background="<?php echo esc_url(get_the_post_thumbnail_url()); ?>"></div>
                <div class="cont">
                    <?php if ($settings['meta_show'] == 'yes') { ?>
                    <div class="info">
                        <div class="author">
                            <span><?php echo esc_html(get_the_author_meta('nickname')); ?></span>
                        </div>
                        <div class="date">
                            <span><?php echo esc_html(get_the_date('j M Y')); ?></span>
                        </div>
                        <div class="coments">
                            <span><?php comments_number(); ?></span>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="text">
                        <h4><?php the_title(); ?></h4>
                        <?php if ($settings['show_excerpt'] == 'yes') { ?>
                        <p><?php echo wp_trim_words(get_the_excerpt(), $settings['excerpt'], esc_html($settings['excerpt_after'])); ?></p>
                        <?php } ?>
                    </div>
                    <?php if ($settings['button_show'] == 'yes') { ?>
                    <div class="more">
                        <h6>
                            <a href="<?php the_permalink(); ?>"><?php echo esc_html($settings['button']); ?></a>
                        </h6>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php endwhile; wp_reset_postdata(); ?>
</div>
