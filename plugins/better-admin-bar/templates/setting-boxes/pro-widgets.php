<?php
/**
 * Pro widgets template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox sidebar-heatbox pro-widgets-box">

	<h2><?php _e( 'Better Admin Bar PRO', 'better-admin-bar' ); ?></h2>

	<div class="heatbox-content">

		<p><?php _e( 'Get <strong>Better Admin Bar PRO</strong> & provide your customers with the user experience they deserve.', 'better-admin-bar' ); ?></p>

		<ul id="pro-items" class="widget-items pro-items">

			<?php
			// Build locked widget items output.
			foreach ( $locked_widgets as $widget_key ) :
				$widget_settings = $locked_settings[ $widget_key ];
				?>
				<li class="widget-item is-locked" data-widget-key="<?php echo esc_attr( $widget_key ); ?>">
					<div class="heatbox-cols widget-default-area">
						<div class="widget-item-col drag-wrapper">
							<span class="locked-handle fas fa-check-circle"></span>
						</div>
						<div class="widget-item-col text-wrapper">
							<p><?php echo esc_html( $widget_settings['text'] ); ?></p>
							<p class="description"><?php echo esc_html( $widget_settings['description'] ); ?></p>
						</div>
					</div>
				</li>
			<?php endforeach; ?>

		</ul>

		<a href="https://betteradminbar.com/pricing/" target="_blank" class="button button-primary button-larger button-full">Learn more</a>

	</div>

</div>
