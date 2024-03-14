<?php
/**
 * Portfolio Entry
 *
 * @var        $attributes - attributes
 * @var        $options    - options
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    Sight
 */

?>
<article <?php $portfolio_entry->item_class(); ?>>
	<div <?php $portfolio_entry->item_outer_class(); ?>>
		<?php
			$portfolio_entry->item_attachment();
			$portfolio_entry->item_content();
		?>
	</div>
</article>
