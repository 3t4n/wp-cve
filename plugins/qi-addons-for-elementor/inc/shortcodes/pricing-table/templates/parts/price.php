<?php if ( '' !== $price ) { ?>
	<div class="qodef-m-price">
		<div class="qodef-m-price-wrapper qodef-h1">
			<?php if ( ! empty( $currency ) ) { ?>
				<span class="qodef-m-price-currency"><?php echo esc_html( $currency ); ?></span>
			<?php } ?>
			<span class="qodef-m-price-value"><?php echo esc_html( $price ); ?></span>
		</div>
		<?php if ( ! empty( $period ) ) { ?>
			<span class="qodef-m-price-period"><?php echo esc_html( $period ); ?></span>
		<?php } ?>
	</div>
<?php } ?>
