<?php
/**
 * Prints the list of tabs and highlights the first one.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings/partials
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of required vars:
 *
 * @var array  $tabs the list of tabs.
 */

?>

<h2 class="nav-tab-wrapper">
<?php
foreach ( $tabs as $_tab ) { // phpcs:ignore
	printf(
		'<a id="nelio-content-%1$s" class="nav-tab" href="#">%2$s</a>',
		esc_attr( $_tab['name'] ),
		esc_html( $_tab['label'] )
	);
}//end foreach
?>
</h2>
