<?php
/**
 * Mobile menu trigger template
 */
?>

<div class="main-color lakit-nav__mobile-trigger lakit-nav-mobile-trigger-align-<?php echo esc_attr( $trigger_align ); ?>">
	<?php $this->_icon( 'mobile_trigger_icon', '<span class="lakit-nav__mobile-trigger-open lakit-blocks-icon">%s</span>' ); ?>
	<?php $this->_icon( 'mobile_trigger_close_icon', '<span class="lakit-nav__mobile-trigger-close lakit-blocks-icon">%s</span>' ); ?>
</div>