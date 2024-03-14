<?php
$eli_helper_button_class = '';
$eli_helper_button_class .= ' '.$this->get_align_class($settings['button_align']);
$eli_helper_button_class .= ' '.$this->get_align_class($settings['button_align_tablet'],'tablet_');
$eli_helper_button_class .= ' '.$this->get_align_class($settings['button_align_mobile'],'phone_');
?>
<div class="widget-eli eli-blog-search contact-form" id="eli_<?php echo esc_html($this->get_id_int());?>">
    <div class="eli-container">
        <form class="eli_f" id="searchform" method="get" action="<?php echo esc_attr((!empty($settings['special_result_page'])) ? $settings['special_result_page'] : bloginfo('url')); ?>">
            <div class="eli_f_container">
                <div class="eli_f_group eli_f_group_el_s ">
                    <label for="s" <?php echo wp_kses_post($this->get_render_attribute_string( 'field_search_label_text' )); ?>><?php echo esc_html($settings['field_search_label_text']); ?></label>
                    <input type="text" class="eli_f_field" name="<?php echo esc_attr((!empty($settings['special_result_page'])) ? 'search' : 's'); ?>" id="s" placeholder="<?php echo esc_attr($settings['field_search_placeholder_text']); ?>" value="<?php echo esc_attr((isset($_GET['search'])) ? sanitize_text_field($_GET['search']) : get_search_query()); ?>" />
                </div>
                <div class="eli_f_group eli_f_group_el_button <?php echo esc_html($eli_helper_button_class);?>" <?php echo wp_kses_post($this->get_render_attribute_string( 'submit-group' )); ?>>
                    <button type="submit" <?php echo wp_kses_post($this->get_render_attribute_string( 'button' )); ?>>
                        <span <?php echo wp_kses_post($this->get_render_attribute_string( 'content-wrapper' )); ?>>
                            <?php if ( ! empty( $settings['selected_button_icon'] ) ) : ?>
                                <span <?php echo wp_kses_post($this->get_render_attribute_string( 'icon-align' )); ?>>
                                    <?php $this->el_icon_with_fallback( $settings ); ?>
                                    <?php if ( empty( $settings['button_text'] ) ) : ?>
                                        <span class="elementor-screen-only"><?php _e( 'Submit', 'elementinvader-addons-for-elementor' ); ?></span>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['button_text'] ) ) : ?>
                                    <span class="elementor-button-text"><?php echo esc_html($settings['button_text']); ?></span>
                            <?php endif; ?>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>