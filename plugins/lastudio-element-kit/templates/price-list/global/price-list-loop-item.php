<?php
/**
 * Price list item template
 */

$item_title_attr = $this->get_item_inline_editing_attributes( 'item_title', 'price_list', $this->_processed_index, ['price-list__item-title', 'h4', 'theme-heading'] );
$item_price_attr = $this->get_item_inline_editing_attributes( 'item_price', 'price_list', $this->_processed_index, 'price-list__item-price' );
$item_desc_attr = $this->get_item_inline_editing_attributes( 'item_text', 'price_list', $this->_processed_index, 'price-list__item-desc' );

?>
<li class="price-list__item"><?php
	echo $this->_open_price_item_link( 'item_url' );
	echo '<div class="price-list__item-inner">';
	echo $this->get_price_image('<div class="price-list__item-img-wrap figure__object_fit">%s</div>', 'price-list__item-img');
	echo '<div class="price-list__item-content">';
		echo '<div class="price-list__item-title__wrapper">';
			echo $this->_loop_item( array( 'item_title' ), '<div ' . $item_title_attr . '>%s</div>' );
            if( 'yes' !== $this->get_settings_for_display('price_after_content') ) {
                echo '<div class="price-list__item-separator"></div>';
                echo $this->_loop_item(array('item_price'), '<div ' . $item_price_attr . '>%s</div>');
            }
		echo '</div>';
		echo $this->_loop_item( array( 'item_text' ), '<div ' . $item_desc_attr . '>%s</div>' );
	echo '</div>';
    if( 'yes' === $this->get_settings_for_display('price_after_content') ) {
        echo $this->_loop_item( array( 'item_price' ), '<div ' . $item_price_attr . '>%s</div>' );
    }
	echo '</div>';
	echo $this->_close_price_item_link( 'item_url' );
?></li>