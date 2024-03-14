<div class="xpro-progress-bar-wrapper xpro-progress-bar-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
	<?php if ( $settings['title'] && '15' !== $settings['layout'] ) : ?>
		<div class="xpro-progress-content">
			<span class="xpro-progress-title"><?php echo esc_html( $settings['title'] ); ?></span>
		</div>
	<?php endif; ?>
	<div class="xpro-progress-bar">
		<div class="xpro-progress-track">
			<?php if ( 'yes' === $settings['show_count'] && '15' !== $settings['layout'] ) : ?>
				<div class="xpro-progress-counter">
					<?php if ( '5' === $settings['layout'] ) : ?>
						<span class="xpro-progress-control"></span>
					<?php endif; ?>
					<?php if ( '6' === $settings['layout'] ) : ?>
						<span class="xpro-progress-count-less-wrapper">
				<span class="xpro-progress-count-less">90</span>%
			</span>
					<?php endif; ?>
					<span class="xpro-progress-count">90</span>%
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( '15' === $settings['layout'] && ( 'yes' === $settings['show_count'] || $settings['title'] ) ) : ?>
		<div class="xpro-progress-content">
			<?php if ( 'yes' === $settings['show_count'] ) : ?>
				<div class="xpro-progress-counter">
					<span class="xpro-progress-count">90</span>%
				</div>
			<?php endif; ?>
			<?php if ( $settings['title'] ) : ?>
				<span class="xpro-progress-title"><?php echo esc_html( $settings['title'] ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
