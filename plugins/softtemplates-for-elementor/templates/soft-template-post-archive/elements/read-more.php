<?php if ( 'no' !== $settings['show_button'] ) { 
    // Readmore Button
    $read_more_text = isset( $settings['read_more_text'] ) && ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : esc_html__('Read More', 'soft-template-core');

    $button_classes = array();
    $button_classes[] = 'qodef-qi-button';
    $button_classes[] = 'qodef-html--link';

    $button_classes[] = ! empty( $settings['button_layout'] ) ? 'qodef-layout--' . $settings['button_layout'] : '';

    $button_classes[] = ! empty( $settings['button_layout_type'] ) ? 'qodef-type--' . $settings['button_layout_type'] : '';

    $button_classes[] = ! empty( $settings['button_size'] ) ? 'qodef-size--' . $settings['button_size'] : ''; 
    
    $button_classes[] = ! empty( $settings['button_size'] ) ? 'qodef-size--' . $settings['button_size'] : '';

    $button_classes[] = 'yes' === $settings['button_text_underline'] ? 'qodef-text-underline' : '';

    $button_classes[] = ! empty( $settings['image_hover'] ) ? 'qodef-image--hover-' . $settings['image_hover'] : '';

    
    // qodef-hover--icon-move-horizontal-short qodef-text-underline qodef-underline--left 
    // qodef-type--icon-boxed   qodef-icon--right qodef-hover--icon-move-horizontal-short 

    ?>


    <div class="qodef-e-info qodef-info--bottom">
        <?php if ( ! post_password_required() ) { ?>
            <a class="qodef-shortcode <?php echo implode(' ', $button_classes ); ?>" href="<?php echo get_the_permalink() ?>" target="_self">
                <span class="qodef-m-text"><?php echo sprintf("%s",$read_more_text); ?></span>
                <span class="qodef-m-icon">
                    <span class="qodef-m-icon-inner">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['readmore_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                </span>
                <?php 
                if( $settings['button_layout_type'] === 'inner-border' ) { ?>
                    <div class="qodef-m-inner-border">					
                        <span class="qodef-m-border-top"></span>			
                        <span class="qodef-m-border-right"></span>			
                        <span class="qodef-m-border-bottom"></span>			
                        <span class="qodef-m-border-left"></span>				
                    </div>
                <?php } ?>
            </a>
        <?php } ?>
    </div>
<?php } ?>