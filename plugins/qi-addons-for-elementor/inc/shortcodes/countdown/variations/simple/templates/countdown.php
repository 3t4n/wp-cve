<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attrs( $data_attrs ); ?>>
	<span class="qodef-m-date">
		<?php if ( 'mon' !== $format ) { ?>
			<span class="qodef-digit-wrapper qodef-months">
				<span class="qodef-digit">00</span>
				<span class="qodef-label"><?php esc_html__( 'Months', 'qi-addons-for-elementor' ); ?></span>
			</span>
		<?php } ?>
		<span class="qodef-digit-wrapper qodef-days">
			<span class="qodef-digit">00</span>
			<span class="qodef-label"><?php esc_html__( 'Days', 'qi-addons-for-elementor' ); ?></span>
		</span>
		<span class="qodef-digit-wrapper qodef-hours">
			<span class="qodef-digit">00</span>
			<span class="qodef-label"><?php esc_html__( 'Hours', 'qi-addons-for-elementor' ); ?></span>
		</span>
		<?php if ( 'minsec' !== $format ) { ?>
			<span class="qodef-digit-wrapper qodef-minutes">
				<span class="qodef-digit">00</span>
				<span class="qodef-label"><?php esc_html__( 'Minutes', 'qi-addons-for-elementor' ); ?></span>
			</span>
			<?php if ( 'sec' !== $format ) { ?>
				<span class="qodef-digit-wrapper qodef-seconds">
					<span class="qodef-digit">00</span>
					<span class="qodef-label"><?php esc_html__( 'Seconds', 'qi-addons-for-elementor' ); ?></span>
				</span>
			<?php } ?>
		<?php } ?>
	</span>
</div>
