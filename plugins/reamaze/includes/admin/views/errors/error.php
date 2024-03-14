<?php
/**
 * Generic Error partial
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<div style="text-align: center; padding: 20px;">
  <h2><?php echo __( "Something went wrong :(", 'reamaze'); ?><h2>
  <p><?php echo __( 'Please try again or <a href="javascript:;" data-reamaze-lightbox="kb">contact us</a>.', 'reamaze' ); ?></p>
</div>
<?php
