<?php
/**
 * Outputs the Gallery Settings Tabs and Config options.
 *
 * @since   1.5.0
 *
 * @var array $data Array of data to pass to the view.
 *
 * @package Envira_Gallery
 * @author  Envira Team
 */

?>
<!-- Tabs -->
<ul id="envira-tabs-nav" class="envira-tabs-nav" data-container="#envira-tabs" data-update-hashbang="1">
	<?php
	// Iterate through the available tabs, outputting them in a list.
	$i = 0;
	foreach ( $data['tabs'] as $id => $title ) {
		$class = ( 0 === $i ? ' envira-active' : '' );
		?>
		<li class="envira-<?php echo esc_attr( $id ); ?>">
			<a href="#envira-tab-<?php echo esc_attr( $id ); ?>" title="<?php echo esc_attr( $title ); ?>"<?php echo ( ! empty( esc_attr( $class ) ) ? ' class="' . esc_attr( $class ) . '"' : '' ); ?>>
				<?php echo esc_html( $title ); ?>
			</a>
		</li>
		<?php

		++$i;
	}
	?>
</ul>

<!-- Settings -->
<div id="envira-tabs" data-navigation="#envira-tabs-nav">
	<?php
	// Iterate through the registered tabs, outputting a panel and calling a tab-specific action,
	// which renders the settings view for that tab.
	$i = 0;
	foreach ( $data['tabs'] as $id => $title ) {
		$class = ( 0 === $i ? 'envira-active' : '' );
		?>
		<div id="envira-tab-<?php echo esc_attr( $id ); ?>" class="envira-tab envira-clear <?php echo esc_attr( $class ); ?>">
			<?php do_action( 'envira_gallery_tab_' . $id, $data['post'] ); ?>

		</div>
		<?php
		++$i;
	}
	?>
</div>

<div class="clear"></div>
