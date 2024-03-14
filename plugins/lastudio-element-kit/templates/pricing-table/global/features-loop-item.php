<?php
/**
 * Features list item template
 */

$classes  = 'lakit-pricing-feature-' . $this->_loop_item( array( '_id' ) );
$classes .= ' ' . $this->_loop_item( array( 'item_included' ) );

?>
<div class="lakit-pricing-feature <?php echo $classes; ?>">
	<div class="lakit-pricing-feature__inner"><?php
		echo $this->__pricing_feature_icon();
		printf( '<span class="lakit-pricing-feature__text">%s</span>', $this->_loop_item( array( 'item_text' ) ) );
	?></div>
</div>