<?php
/**
 * Action box template
 */
?>
<div class="lakit-pricing-table__action">
	<?php $this->_html( 'button_before', '<div class="lakit-pricing-table__action-before">%s</div>' ); ?>
	<?php $this->_glob_inc_if( 'button', array( 'button_url', 'button_text' ) ); ?>
	<?php $this->_html( 'button_after', '<div class="lakit-pricing-table__action-after">%s</div>' ); ?>
</div>