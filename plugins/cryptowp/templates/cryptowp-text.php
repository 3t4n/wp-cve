<?php if ( $show == 'price' ) : ?>
<span id="cryptowp_shortcode_<?php echo esc_attr( $i ); ?>" class="cryptowp-text cryptowp-text-price">
	<?php if ( ! $calc ) : ?><span class="cryptowp-text-price-symbol"><?php echo esc_html( $currency_sign ); ?></span><?php endif; ?><span class="cryptowp-text-price-amount"><?php echo esc_html( $price ); ?></span></span><?php elseif ( $show == 'percent' ) : ?>
<span id="cryptowp_shortcode_<?php echo esc_attr( $i ); ?>" class="cryptowp-text cryptowp-text-percent cryptowp-coin-<?php echo esc_attr( $value ); ?>">
	<span class="cryptowp-coin-percent cryptowp-text-percent-number">
		<span class="cryptowp-text-percent-number"><?php echo esc_html( $percent ); ?></span><span class="cryptowp-text-percent-symbol">%</span></span></span></span><?php elseif ( $show == 'percent_hour' ) : ?>
<span id="cryptowp_shortcode_<?php echo esc_attr( $i ); ?>" class="cryptowp-text cryptowp-text-percent cryptowp-coin-<?php echo esc_attr( $value_hour ); ?>">
	<span class="cryptowp-coin-percent cryptowp-text-percent-number">
		<span class="cryptowp-text-percent-number"><?php echo esc_html( $percent_hour ); ?></span><span class="cryptowp-text-percent-symbol">%</span></span></span></span><?php elseif ( $show == 'market_cap' ) : ?>
<span id="cryptowp_shortcode_<?php echo esc_attr( $i ); ?>" class="cryptowp-text cryptowp-text-market-cap>">
	<span class="cryptowp-text-market-cap-symbol">$</span><span class="cryptowp-text-market-cap-amount"><?php echo esc_html( $market_cap ); ?></span></span><?php elseif ( $show == 'price_btc' ) : ?>
<span id="cryptowp_shortcode_<?php echo esc_attr( $i ); ?>" class="cryptowp-text cryptowp-text-price-btc>"><?php echo esc_html( $price_btc ); ?></span><?php elseif ( $show == 'supply' ) : ?>
<span id="cryptowp_shortcode_<?php echo esc_attr( $i ); ?>" class="cryptowp-text cryptowp-text-supply>"><?php echo esc_html( $supply ); ?></span><?php endif; ?>