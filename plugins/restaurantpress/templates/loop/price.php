<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/restaurantpress/loop/price.php.
 *
 * HOWEVER, on occasion RestaurantPress will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.wpeverest.com/docs/restaurantpress/template-structure/
 * @author  WPEverest
 * @package RestaurantPress/Templates
 * @version 1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $food;
?>

<?php if ( $price_html = $food->get_price_html() ) : ?>
	<span class="price"><?php echo $price_html; ?></span>
<?php endif; ?>
