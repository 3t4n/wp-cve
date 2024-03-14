<?php
	/**
	 * Loop item template
	 */

	$img = $this->get_advanced_carousel_img( 'lakit-carousel__item-img' );

    $title  = $this->_loop_item( array( 'item_title' ) );

	$item_settings = $this->_processed_item;

	$content_type = ! empty( $item_settings['item_content_type'] ) ? $item_settings['item_content_type'] : 'default';

	$settings = $this->get_settings_for_display();

    $a_link_attribute = '';

    if($content_type !== 'template'){
        if ( $settings['item_link_type'] === 'link' ) {
            if(!empty($item_settings['item_link']['url'])){
                $this->_add_link_attributes( 'readmore_btn_' . $this->_processed_index, $item_settings['item_link'] );
                $this->add_render_attribute('readmore_btn_' . $this->_processed_index, 'title', wp_strip_all_tags($title));
                $a_link_attribute = $this->get_render_attribute_string('readmore_btn_' . $this->_processed_index);
            }
        }
        else{
            $a_link_attribute = sprintf('href="%1$s" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="%2$s"', $item_settings['item_image']['url'], $this->get_id());
        }
    }

    $btn_icon =  $this->_btn_icon('<span class="btn-icon">%s</span>');

?><div class="lakit-carousel__item swiper-slide<?php echo $content_type == 'template' ? ' lakit-carousel__item--template' : ''?>">
	<div class="lakit-carousel__item-inner">
        <?php if(empty($img) && $content_type == 'template') :?>
            <div class="lakit-template-wrapper"><?php echo $this->_loop_item_template_content();?></div>
        <?php else: ?>
        <?php
		if ( $img ) {
            printf('<a class="%1$s" %2$s>%3$s</a>', 'lakit-carousel__item-link', $a_link_attribute, $img);
		}
		echo '<div class="lakit-carousel__content">';
			switch ( $content_type ) {
				case 'default':

                    $loop_icon = $this->_loop_icon('%1$s');

                    if(!empty($loop_icon)){
                        if ( $link_title && $settings['item_link_type'] === 'link'  ) {
                            echo sprintf('<div class="lakit-carousel__item-icon"><a class="lakit-icon-inner" %2$s>%1$s</a></div>', $loop_icon, $a_link_attribute);
                        }
                        else{
                            echo sprintf('<div class="lakit-carousel__item-icon"><div class="lakit-icon-inner">%1$s</div></div>', $loop_icon);
                        }
                    }


					$text   = $this->_loop_item( array( 'item_text' ), '<div class="lakit-carousel__item-text">%s</div>' );
                    $button = '';
                    if(!empty($a_link_attribute) && ( !empty($item_settings['item_button_text']) || !empty($btn_icon) )){
                        $button = sprintf('<a class="elementor-button elementor-size-md lakit-carousel__item-button" %1$s><span>%2$s</span>%3$s</a>', $a_link_attribute, $item_settings['item_button_text'], $btn_icon);
                    }
					$title_format = '<%1$s class="lakit-carousel__item-title">%2$s</%1$s>';
					if ( $link_title && $settings['item_link_type'] === 'link'  ) {
						$title_format = '<%1$s class="lakit-carousel__item-title"><a %3$s>%2$s</a></%1$s>';
					}
					if ( $title ) {
						echo sprintf( $title_format, $title_tag, $title, $a_link_attribute );
					}
                    echo $text;
                    echo $button;
					break;
				case 'template':
					echo $this->_loop_item_template_content();
					break;
			}
		echo '</div>';?>
    <?php endif; ?>
    </div>
</div>
