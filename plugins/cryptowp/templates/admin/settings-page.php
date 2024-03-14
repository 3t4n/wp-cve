<div class="cryptowp wrap">
	<form id="cryptowp" method="post" action="options.php">
		<?php settings_fields( 'cryptowp' ); ?>
		<div class="cryptowp-head">
			<h1 class="cryptowp-title"><?php echo $strings['cryptocurrency']; ?></h1>
			<span class="cryptowp-button cryptowp-action" data-cryptowp-action="import"><?php echo $strings['import_coins']; ?> <i class="cryptowp-tick"></i></span>
			<span class="cryptowp-button cryptowp-action" data-cryptowp-action="currency"><?php echo $strings['currency']; ?> <i class="cryptowp-tick"></i></span>
			<span id="cryptowp_refresh" class="cryptowp-button cryptowp-process cryptowp-button-icon" data-cryptowp-process="refresh"><?php echo $strings['refresh_coins']; ?> <i class="dashicons dashicons-update-alt"></i></span>
		</div>
		<div class="cryptowp-panels cryptowp-spacer">
			<div id="cryptowp_panel_import" class="cryptowp-panel<?php echo ! get_cryptowp( 'coins' ) ? ' cryptowp-active' : ''; ?>">
				<div class="cryptowp-panel-inner">
					<h3><?php echo $strings['import_coins']; ?></h3>
					<p class="cryptowp-importer">
						<input type="text" name="cryptowp[import]" id="cryptowp_import" placeholder="<?php echo $strings['enter_import_coins']; ?>" class="regular-text" />
						<span class="button cryptowp-process" data-cryptowp-process="import"><?php echo $strings['import_coins']; ?></span>
						<i class="cryptowp-import-spinner dashicons dashicons-update-alt"></i>
					</p>
					<p class="description"><?php echo $strings['import_description']; ?></p>
				</div>
			</div>
			<div id="cryptowp_panel_currency" class="cryptowp-panel">
				<div class="cryptowp-panel-inner">
					<h3><?php echo $strings['select_currency']; ?></h3>
					<div class="cryptowp-clear">
						<select name="cryptowp[currency]" id="cryptowp_currency">
							<option value=""><?php echo $strings['usd_default']; ?></option>
							<?php foreach ( $currencies as $curr ) : ?>
								<option value="<?php echo esc_attr( $curr ); ?>"<?php echo selected( $currency, $curr, false ); ?>><?php esc_html_e( $curr ); ?></option>
							<?php endforeach; ?>
						</select>
						<input name="cryptowp[currency_sign]" id="cryptowp_currency_sign" value="<?php echo esc_attr( $currency_sign ); ?>" placeholder="$" size="4" type="text" />
					</div>
				</div>
			</div>
		</div>
		<?php $this->coins(); ?>
		<input type="hidden" name="cryptowp[version]" value="<?php echo esc_attr( CRYPTOWP_VERSION ); ?>" />
		<div class="cryptowp-save">
			<?php submit_button( $strings['save_changes'], 'primary', 'save', false ); ?>
		</div>
		<hr />
		<p class="cryptowp-admin-footer-text">
			<?php echo sprintf( $strings['coin_data_by'], '<a href="https://www.cryptocompare.com/" target="_blank">CryptoCompare</a>' ); ?>
			&nbsp;|&nbsp; <?php echo sprintf( $strings['plugin_by'], '<a href="https://cryptowp.com/" target="_blank">CryptoWP</a>' ); ?>
			&nbsp;&middot;&nbsp; <?php echo '<a href="https://cryptowp.com/preorder/" target="_blank"><b>' . $strings['preorder'] . '</b></a>'; ?>
			&nbsp;&middot;&nbsp; <?php echo '<a href="https://cryptowp.com/kb/" target="_blank">' . $strings['tutorials'] . '</a>'; ?>
		</p>
	</form>
</div>