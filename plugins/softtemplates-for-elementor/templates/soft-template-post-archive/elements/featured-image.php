<?php if( 'no' !== $settings['show_media'] ) { ?>
    <div class="qodef-e-media">
        <?php  
        if ( has_post_thumbnail() ) { ?>
            <div class="qodef-e-media-image">
                <a itemprop="url" href="<?php the_permalink(); ?>">
                    <?php 
                        $post_thumbnail = get_post_thumbnail_id(get_the_ID());
                        //var_dump($post_thumbnail);

                        echo Soft_template_Core_Utils::get_featured_image_html($settings, 'masonry_images_proportion', $post_thumbnail);
                        //echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'masonry_images_proportion', $post_thumbnail );
                    ?>
                </a>
            </div>
        <?php } ?>
    </div>
<?php } ?>