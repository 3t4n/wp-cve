<div class="xpro-business-hour-wrapper">

	<?php if ( 'yes' === $settings['show_title'] ) : ?>
		<div class="xpro-business-hour-header">
			<?php if ( $settings['title'] ) : ?>
				<!-- Title -->
				<span class="xpro-business-hour-title"><?php echo esc_html( $settings['title'] ); ?></span>
			<?php endif; ?>

			<?php if ( $settings['sub_title'] ) : ?>
				<!-- Sub Title -->
				<span class="xpro-business-hour-sub-title"><?php echo esc_html( $settings['sub_title'] ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<ul class="xpro-business-hour-inner">
		<?php foreach ( $settings['business_item'] as $i => $item ) : ?>
		<li class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> xpro-business-hour-item">
			<?php if ( $item['business_day'] ) : ?>
			<span class="xpro-business-hour-day"><?php echo esc_html( $item['business_day'] ); ?></span>
			<?php endif; ?>

			<span class="xpro-business-hour-separator-<?php echo esc_attr( $settings['separator_layout'] ); ?>">
				<span></span>
			</span>

			<?php if ( $item['business_time'] ) : ?>
			<span class="xpro-business-hour-time"><?php echo esc_html( $item['business_time'] ); ?></span>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
