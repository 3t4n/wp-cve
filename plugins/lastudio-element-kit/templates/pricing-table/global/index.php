<?php
/**
 * Pricing table main template
 */
?>
<div class="lakit-pricing-table<?php $this->_html( 'featured', ' featured-table' ); ?>">
	<?php $this->_glob_inc_if( 'heading', array( 'icon', 'image', 'title', 'subtitle' ) ); ?>
	<?php include $this->_get_global_template( 'price' ); ?>
	<?php $this->_get_global_looped_template( 'features', 'features_list' ); ?>
	<?php $this->_glob_inc_if( 'action', array( 'button_before', 'button_url', 'button_text', 'button_after' ) ); ?>
	<?php $this->_glob_inc_if( 'badge', array( 'featured' ) ); ?>
</div>
