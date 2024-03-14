<div class="better-blog style-1 row clearfix blog-body">
    <?php while ($query->have_posts()): $query->the_post(); ?> 
    <div class="<?php 
        // Securely outputting the class based on settings
        echo esc_attr($settings['blog_column'] === 'one' ? 'col-md-12' : ($settings['blog_column'] === 'two' ? 'col-md-6' : ($settings['blog_column'] === 'three' ? 'col-md-4' : ($settings['blog_column'] === 'four' ? 'col-md-3' : '')))); 
    ?>">
        <div class="blog-col-inner">
            <?php if ($settings['image'] == 'yes') { ?>
            <div class="blog-link-img"> 
                <?php if (has_post_thumbnail()) {
                    the_post_thumbnail(); 
                } ?>
                        
                <?php if ($settings['cat_show'] == 'yes') { ?>
                        <div class="cat-post">
                           <i class="fa fa-clone"></i> <?php the_category(' | '); ?>
                        </div>
                <?php } ?>
            </div>
            <?php } ?>
            
            <div class="excerpt-box">
                <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                
                <?php if ($settings['meta_show'] == 'yes') { ?>
                <ul class="post-meta">
                    <li><i class="lnr lnr-user fw-600"></i> <?php the_author_posts_link(); ?></li>
                    <li><i class="lnr lnr-clock fw-600"></i> <?php echo get_the_date(); ?></li>
                </ul>
                <?php } ?>
                
                <?php if ($settings['show_excerpt'] == 'yes') { ?>
                <p class="excerpt">
                    <?php 
                    $excerpt = get_the_excerpt();
                    echo wp_kses_post(wp_trim_words($excerpt, $settings['excerpt'], esc_html($settings['excerpt_after'])));
                    ?>
                </p>
                <?php } ?>
                
                <?php if ($settings['show_excerpt'] == 'yes' && $settings['button_show'] != 'yes') { ?>
                <div class="spc-20 clearfix"></div>
                <?php } ?>
                
                <?php if ($settings['button_show'] == 'yes') { ?>
                    <a class="content-btn" href="<?php the_permalink(); ?>">
                    
                    <?php if (!empty($settings['icon'])) : ?>
                    <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
                        <i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
                    </span>
                    <?php endif; ?>
                    
                    <?php echo esc_html($settings['button']); ?>
                    </a>
                <?php } ?>
            </div>
            
        </div>
    </div>
    
    <?php endwhile; wp_reset_postdata(); ?>
</div>
