<?php if (!empty(get_post_meta(get_the_id(), 'meta_spcl_text', true))) : ?>
    <?php if (isset($settings['show_spcl'], $settings['spcl_text'])) : ?>
        <?php if (!get_post_meta(get_the_id(), 'meta_show_spcl', true)) : ?>
            <div class="mr_spcl" style="position:absolute; z-index:9; width:100%">
                <button class="spcl_text" style="background:<?php echo wp_kses(get_post_meta(get_the_id(), 'meta_spcl_color', true), wp_kses_allowed_html('post')); ?>!important;">
                    <?php
                    $spcl_text = wp_kses(get_post_meta(get_the_id(), 'meta_spcl_text', true), wp_kses_allowed_html('post'));
                    echo $spcl_text ? $spcl_text : $settings['spcl_text'];
                    ?>
                </button>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
