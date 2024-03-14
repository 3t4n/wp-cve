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

$this->add_render_attribute( $link_instance, 'href', $this->_loop_item( array( 'item_image', 'url' ), '%s' ) );
$this->add_render_attribute( $link_instance, 'data-elementor-open-lightbox', 'yes' );
$this->add_render_attribute( $link_instance, 'data-elementor-lightbox-slideshow', $this->get_id()  );

$item_instance = 'item-instance-' . $this->item_counter;


$this->add_render_attribute( $item_instance, 'class', $col_class );

$this->item_counter++;

?>
<div <?php echo $this->get_render_attribute_string( $item_instance ); ?>>
	<div class="lakit-images-layout__inner">
		<<?php echo $link_tag; ?> <?php echo $this->get_render_attribute_string( $link_instance ); ?>>
			<div class="lakit-images-layout__image"><?php
                echo $this->get_loop_image_item();
				?>
			</div>
		</<?php echo $link_tag; ?>>
	</div>
</div>