<?php

/**
 * Dojo for WooCommerce Template
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/templates
 * @author     Dojo
 * @link       http://dojo.tech/
 */

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
	exit();
}
?>

<ul class="woocommerce-error">
	<li><?php echo esc_html($message); ?></li>
</ul>
<?php if (isset($cancel_url)) { ?>
	<p>
		<a class="button cancel" href="<?php echo esc_url($cancel_url); ?>"><?php esc_html_e('Cancel order & restore cart', 'woocommerce-dojo'); ?></a>
	</p>
<?php
}
