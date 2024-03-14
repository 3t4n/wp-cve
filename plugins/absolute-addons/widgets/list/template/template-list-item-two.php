<?php
/**
 * Template Style Two for List
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

?>

<li class="absp-list-widget-item">
	<?php if ( 'icon' === $item['icon_type'] ) {
		$this->list_icon( $item );
	} elseif ( 'number' === $item['icon_type'] ) {
		$this->list_number( $item );
	} else {
		$this->list_image( $item );
	}
	$this->list_title( $item );
	?>
</li>

