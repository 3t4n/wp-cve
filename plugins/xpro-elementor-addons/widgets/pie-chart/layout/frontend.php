<div class="xpro-pie-chart" data-percent="0">
	<div class="xpro-pie-chart-media">

		<?php

		use Elementor\Icons_Manager;

		if ( 'percentage' === $settings['chart_media'] ) : ?>
			<span class="xpro-pie-chart-count"></span>
		<?php endif; ?>

		<?php
		if ( 'icon' === $settings['chart_media'] && $settings['icon'] ) {
			Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );
		}
		?>

		<?php if ( 'inside' === $settings['content_position'] ) { ?>
			<?php if ( $settings['title'] ) : ?>
				<h3 class="xpro-pie-chart-title"><?php echo esc_html( $settings['title'] ); ?></h3>
			<?php endif; ?>
			<?php if ( $settings['description'] ) : ?>
				<p class="xpro-pie-chart-desc"><?php echo wp_kses_post( $settings['description'] ); ?></p>
				<?php
			endif;
		}
		?>
	</div>
</div>
<?php if ( 'outside' === $settings['content_position'] ) { ?>
	<?php if ( $settings['title'] ) : ?>
		<h3 class="xpro-pie-chart-title"><?php echo esc_html( $settings['title'] ); ?></h3>
	<?php endif; ?>
	<?php if ( $settings['description'] ) : ?>
		<p class="xpro-pie-chart-desc"><?php echo wp_kses_post( $settings['description'] ); ?></p>
	<?php endif;
} ?>
