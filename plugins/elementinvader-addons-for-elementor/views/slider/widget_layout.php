<div class="widget-eli eli_slider" id="eli_<?php echo esc_html($this->get_id_int()); ?>">
    <div class="eli_container">
        <div class="ili_slider_box <?php echo esc_attr($settings['t_options_slider_animation_style']) . '_animation'; ?> <?php echo esc_attr(join(' ', [$settings['t_styles_dots_position_style'], $settings['t_styles_arrows_position_style'], $settings['t_styles_arrows_position'], $settings['t_styles_arrows_position_style']])); ?>">
            <div class="eli_slider_ini  <?php echo esc_attr($settings['t_styles_img_des_type']); ?> ">
                <?php foreach ($results as $key => $item) : ?>
                    <div class="eli_s_item elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                        <img src="<?php echo esc_url($item['thumbnail']); ?>" class="eli_s_item_thumbnail">
                        <div class="eli_slider_mask"></div>
                        <?php if(!empty($item['data']['position'])):?>
                            <div class="slider-content-el position_<?php echo esc_attr($item['data']['position']); ?>">
                                <?php if (!empty($item['title'])) : ?>
                                    <div class="eli_s_item_box_line">
                                        <div class="eli_s_item_box_title"> <?php echo wp_kses_post($item['title']); ?> </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item['description'])) : ?>
                                    <div class="eli_s_item_box_line">
                                        <div class="eli_s_item_box_content"> <?php echo wp_kses_post($item['description']); ?> </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item['link'])) : ?>
                                    <div class="eli_s_item_box_line"> <a class="eli_s_item_box_link" href="<?php echo esc_url(($item['link'])); ?>"> <?php echo esc_html($settings['t_content_basic_link_text']); ?> </a></div>
                                <?php endif; ?>
                            </div>
                        <?php else:?>
                            <?php if (!empty($item['title'])) : ?>
                                    <div class="eli_s_item_box_line">
                                        <div class="eli_s_item_box_title"> <?php echo wp_kses_post($item['title']); ?> </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item['description'])) : ?>
                                    <div class="eli_s_item_box_line">
                                        <div class="eli_s_item_box_content"> <?php echo wp_kses_post($item['description']); ?> </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item['link'])) : ?>
                                    <div class="eli_s_item_box_line"> <a class="eli_s_item_box_link" href="<?php echo esc_url($item['link']); ?>"> <?php echo wp_kses_post($settings['t_content_basic_link_text']); ?> </a></div>
                                <?php endif; ?>
                        <?php endif;?>

                    </div>
                <?php endforeach; ?>
                <?php if (false) : ?>
                    <div class="eli_s_item">
                        <img src="<?php echo ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_URL; ?>/assets/img/placeholder.jpg" class="eli_s_item_thumbnail">
                        <div class="eli_s_item_box_line">
                            <div class="eli_s_item_box_title"> Title </div>
                        </div>
                        <div class="eli_s_item_box_line">
                            <div class="eli_s_item_box_content"> Text Text </div>
                        </div>
                        <div class="eli_s_item_box_line"> <a class="eli_s_item_box_link" href="#"> Link </a></div>
                    </div>
                    <div class="eli_s_item">
                        <img src="<?php echo ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_URL; ?>/assets/img/placeholder.jpg" class="eli_s_item_thumbnail">
                        <div class="eli_s_item_box_line">
                            <div class="eli_s_item_box_title"> Title </div>
                        </div>
                        <div class="eli_s_item_box_line">
                            <div class="eli_s_item_box_content"> Text Text </div>
                        </div>
                        <div class="eli_s_item_box_line"> <a class="eli_s_item_box_link" href="#"> Link </a></div>
                    </div>
                    <div class="eli_s_item">
                        <img src="<?php echo ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_URL; ?>/assets/img/placeholder.jpg" class="eli_s_item_thumbnail">
                        <div class="eli_s_item_box_line">
                            <div class="eli_s_item_box_title"> Title </div>
                        </div>
                        <div class="eli_s_item_box_line">
                            <div class="eli_s_item_box_content"> Text Text </div>
                        </div>
                        <div class="eli_s_item_box_line"> <a class="eli_s_item_box_link" href="#"> Link </a></div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="eli_slider_arrows">
                <a class="eli_s_prev eli_slider_arrow">
                    <?php \Elementor\Icons_Manager::render_icon($settings['t_styles_arrows_icon_left'], ['aria-hidden' => 'true']); ?>
                </a>
                <a class="eli_s_next eli_slider_arrow">
                    <?php \Elementor\Icons_Manager::render_icon($settings['t_styles_arrows_icon_right'], ['aria-hidden' => 'true']); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $('#eli_<?php echo esc_html($this->get_id_int()); ?> .eli_slider_ini').slick({
            dots: true,
            arrows: true,
            <?php if ($settings['t_options_slider_center'] == 'yes') : ?>
                centerMode: true,
            <?php endif; ?>
            <?php if ($settings['t_options_slider_variableWidth'] == 'yes') : ?>
                variableWidth: true,
            <?php endif; ?>
            slidesToShow: <?php echo esc_attr($this->_ch($settings['layout_carousel_columns'], 1)); ?>,
            slidesToScroll: <?php echo esc_attr($this->_ch($settings['layout_carousel_columns'], 1)); ?>,
            infinite: <?php echo esc_attr($this->_ch($settings['t_options_slider_infinite'], 'false')); ?>,
            autoplay: <?php echo esc_attr($this->_ch($settings['t_options_slider_autoplay'], 'false')); ?>,
            speed: '<?php echo esc_attr($this->_ch($settings['t_options_slider_speed'], '0')); ?>',
            <?php if (in_array($settings['t_options_slider_animation_style'], ['fade', 'fade_in_in'])) : ?>
                fade: true,
            <?php endif; ?>
            cssEase: '<?php echo esc_attr($this->_ch($settings['t_options_slider_cssease'], 'linear')); ?>',
            nextArrow: $('#eli_<?php echo esc_html($this->get_id_int()); ?> .eli_slider_arrows .eli_s_next'),
            prevArrow: $('#eli_<?php echo esc_html($this->get_id_int()); ?> .eli_slider_arrows .eli_s_prev'),
            customPaging: function(slider, i) {
                // this example would render "tabs" with titles
                return '<span class="eli_dot"><?php \Elementor\Icons_Manager::render_icon($settings['t_styles_dots_icon'], ['aria-hidden' => 'true']); ?></span>';
            },
        });
    })
</script>