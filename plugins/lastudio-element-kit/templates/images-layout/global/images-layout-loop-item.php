<?php
/**
 * Images list item template
 */
$settings = $this->get_settings_for_display();

$col_class = ['lakit-images-layout__item'];
$col_class[] = $this->_loop_item( array( 'item_css_class' ), '%s' );

$enable_carousel    = filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN);

if($enable_carousel){
    $col_class[] = 'swiper-slide';
}

if ( 'grid' == $settings['layout_type'] || 'masonry' == $settings['layout_type'] ) {
	$col_class[] = lastudio_kit_helper()->col_new_classes('columns', $this->get_settings_for_display());
}

$link_instance = 'link-instance-' . $this->item_counter;

$link_type = $this->_loop_item( array( 'item_link_type' ), '%s' );

$this->add_render_attribute( $link_instance, 'class', array(
	'lakit-images-layout__link'
) );

$link_tag = 'a';

if ( 'lightbox' === $link_type ) {
	$this->add_render_attribute( $link_instance, 'href', $this->_loop_item( array( 'item_image', 'url' ), '%s' ) );
	$this->add_render_attribute( $link_instance, 'data-elementor-open-lightbox', 'yes' );
	$this->add_render_attribute( $link_instance, 'data-elementor-lightbox-slideshow', $this->get_id()  );
    $this->add_render_attribute( $link_instance, 'data-elementor-lightbox-title', wp_strip_all_tags($this->_loop_item( array( 'item_title' ), '%s' )) );
}
else if ('external' === $link_type){
    $target = $this->_loop_item( array( 'item_target' ), '%s' );
    $target = ! empty( $target ) ? $target : '_self';

    $this->add_render_attribute( $link_instance, 'href', $this->_loop_item( array( 'item_url' ), '%s' ) );
    $this->add_render_attribute( $link_instance, 'target', $target );
}
else {
    $link_tag = 'div';
}

$item_instance = 'item-instance-' . $this->item_counter;


$this->add_render_attribute( $item_instance, 'class', $col_class );

$this->item_counter++;

$btn_icon =  $this->_btn_icon('<span class="btn-icon">%s</span>');

?>
<div <?php echo $this->get_render_attribute_string( $item_instance ); ?>>
	<div class="lakit-images-layout__inner">
		<<?php echo $link_tag; ?> <?php echo $this->get_render_attribute_string( $link_instance ); ?>>
			<div class="lakit-images-layout__image"><?php
                echo $this->get_loop_image_item();
				?>
			</div>
			<div class="lakit-images-layout__content"><?php
                echo $this->_loop_icon( '<div class="lakit-images-layout__icon"><div class="lakit-images-layout-icon-inner">%s</div></div>' );
                $title_tag = $this->_get_html( 'title_html_tag', '%s' );
                echo $this->_loop_item( array( 'item_title' ), '<' . $title_tag . ' class="lakit-images-layout__title">%s</' . $title_tag . '>' );
                echo $this->_loop_item( array( 'item_desc' ), '<div class="lakit-images-layout__desc">%s</div>' );
                if('external' === $link_type){
                    $item_link_text = $this->_loop_item( array( 'item_link_text' ), '<span>%s</span>');
                    if(!empty($item_link_text) || !empty($btn_icon)){
                        echo sprintf('<button class="elementor-button lakit-images-layout__button button">%1$s%2$s</button>', $item_link_text, $btn_icon);
                    }
                }
            ?></div>
		</<?php echo $link_tag; ?>>
	</div>
</div>