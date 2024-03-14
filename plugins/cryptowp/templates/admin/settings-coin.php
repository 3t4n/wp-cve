<div id="cryptowp_coin_<?php echo esc_attr( $id ); ?>" class="cryptowp-coin">
	<div class="crypto-coin-field cryptowp-coin-name">
		<label for="cryptowp_coins_<?php echo $id; ?>_url"><?php echo esc_attr( get_cryptowp( 'coins', $symbol, 'name' ) ); ?></label>
	</div>
	<div class="crypto-coin-field cryptowp-coin-icon">
		<img class="cryptowp-coin-icon-image" src="<?php echo esc_url( $option['coins'][$symbol]['icon'] ); ?>" />
		<input type="hidden" class="cryptowp-coin-icon-url cryptowp-coin-hidden regular-text" name="cryptowp[coins][<?php echo $symbol; ?>][icon]" id="cryptowp_coins_<?php echo $id; ?>_icon" value="<?php echo esc_attr( get_cryptowp( 'coins', $symbol, 'icon' ) ); ?>" />
	</div>
	<div class="crypto-coin-field cryptowp-coin-symbol cryptowp-coin-hidden">
		<label for="cryptowp_coins_<?php echo $id; ?>_symbol"><?php echo sprintf( $strings['coin_symbol'], '<small><a href="https://cryptowp.com/get-coin-id/" target="_blank">[?]</a></small>' ); ?></label>
		<input type="text" name="cryptowp[coins][<?php echo $symbol; ?>][symbol]" id="cryptowp_coins_<?php echo $id; ?>_symbol" value="<?php echo esc_attr( get_cryptowp( 'coins', $symbol, 'symbol' ) ); ?>" placeholder="<?php $strings['enter_ticker_symbol']; ?>" class="regular-text" />
	</div>
	<div class="crypto-coin-field cryptowp-coin-url">
		<label for="cryptowp_coins_<?php echo $id; ?>_url"><?php echo $strings['page_url']; ?></label>
		<input type="text" name="cryptowp[coins][<?php echo $symbol; ?>][url]" id="cryptowp_coins_<?php echo $id; ?>_url" value="<?php echo esc_attr( get_cryptowp( 'coins', $symbol, 'url' ) ); ?>" placeholder="http://" class="regular-text" />
	</div>
	<div class="cryptowp-coin-hidden">
		<?php foreach ( array( 'name', 'id', 'percent', 'percent_hour', 'price', 'value', 'value_hour', 'error', 'price_btc', 'supply', 'market_cap' ) as $hidden ) : ?>
			<input name="cryptowp[coins]<?php echo "[{$symbol}][{$hidden}]"; ?>" id="cryptowp_coins_<?php echo "{$id}_{$hidden}"; ?>" class="regular-text" value="<?php echo esc_attr( get_cryptowp( 'coins', $symbol, $hidden ) ); ?>" type="hidden" />
		<?php endforeach; ?>
	</div>
	<div class="cryptowp-coin-footer">
		<?php if ( empty( $option['coins'][$symbol]['error'] ) && ! empty( $option['coins'][$symbol]['price'] ) ) :
			$change = ! empty( $option['coins'][$symbol]['percent'] ) ? $option['coins'][$symbol]['percent'] : '';
			$value  = ! empty( $option['coins'][$symbol]['value'] ) ? $option['coins'][$symbol]['value'] : '';
			$sign   = ! empty( $option['currency_sign'] ) ? $option['currency_sign'] : '$';
		?>
			<p><b><?php echo esc_attr( $symbol ); ?></b> &nbsp; <span class="cryptowp-price"><?php echo esc_html( $sign ); ?><?php echo esc_html( $price ); ?></span> &nbsp; <span class="cryptowp-price-<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $change ); ?> <small>%</small></span>&nbsp; <small>24hr</small></p>
		<?php else : ?>
			<p><?php $strings['no_coin_data']; ?></p>
		<?php endif; ?>
	</div>
	<span class="cryptowp-coin-drag cryptowp-dashicons-drag"></span>
	<span class="cryptowp-coin-remove cryptowp-dashicons-close"></span>
</div>