<div id="<?php echo "{$id}-coins"; ?>" class="cryptowp-coins <?php echo esc_attr( $coins_classes ); ?>">

	<?php foreach ( $coins as $coin ) :
		$coin = get_cryptowp_coin_by_id( $coin );
		$coin_error = get_cryptowp( 'coins', $coin, 'error' );

		if ( ! empty( $coin_error ) || ! in_array( $coin, get_cryptowp( 'ids' ) ) )
			continue;

		$coin_id = get_cryptowp( 'coins', $coin, 'id' );
		$coin_url = get_cryptowp( 'coins', $coin, 'url' );
		$coin_name = get_cryptowp( 'coins', $coin, 'name' );
		$coin_symbol = get_cryptowp( 'coins', $coin, 'symbol' );
		$coin_price = get_cryptowp( 'coins', $coin, 'price' );
		$coin_percent = get_cryptowp( 'coins', $coin, 'percent' );
		$coin_icon = get_cryptowp( 'coins', $coin, 'icon' );
		$coin_value = get_cryptowp( 'coins', $coin, 'value' );

		$coin_classes = "$coin_id cryptowp-coin-" . $coin_value;

		$html = ! empty( $coin_url ) ? 'a href="' . esc_url( $coin_url ) . '"' : 'div';
		$html_c = ! empty( $coin_url ) ? 'a' : 'div';
	?>

		<<?php echo $html; ?> id="<?php echo "{$id}-{$coin_id}"; ?>" class="cryptowp-coin <?php echo esc_attr( $coin_classes ); ?>"<?php echo $columns_style; ?>>

			<div class="cryptowp-coin-inner">

				<?php if ( ! empty( $coin_icon ) && empty( $hide_icon ) ) : ?>
					<div class="cryptowp-coin-icon">
						<img src="<?php echo esc_url( $coin_icon ); ?>" alt="<?php echo esc_attr( $coin_name ); ?>" width="70" height="70" />
					</div>
				<?php endif; ?>

				<div class="cryptowp-coin-stats">

					<p class="cryptowp-coin-name"><?php echo esc_html( $coin_name ); ?></p>

					<?php if ( ! empty( $coin_price ) ) : ?>
						<p class="cryptowp-coin-price">
							<span class="cryptowp-coin-price-sign"><?php echo esc_html( $currency_sign ); ?></span><span class="cryptowp-coin-price-total"><?php echo esc_html( $coin_price ); ?></span>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $coin_percent ) && empty( $hide_percent ) ) : ?>
						<p class="cryptowp-coin-meta">
							<span class="cryptowp-coin-symbol"><?php echo esc_html( $coin_symbol ); ?></span>
<span class="cryptowp-coin-percent"><?php echo esc_html( $coin_percent ); ?><span class="cryptowp-coin-percent-symbol">%</span></span>
						</p>
					<?php endif; ?>

				</div>

			</div>

		</<?php echo $html_c; ?>>

	<?php $c++; endforeach; ?>

</div>