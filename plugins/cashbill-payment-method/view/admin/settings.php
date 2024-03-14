<div class="wrap">
<img id="cashbill-admin-logo" src="<?= plugins_url('cashbill-payment-method/img/logo.svg') ?>" />
    <h1><?= __('Konfiguracja płatności', 'cashbill-payment-method'); ?></h1>

    <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
	<table class="form-table">
            
                		<tbody><tr valign="top">
			<th scope="row" class="titledesc">
				<label for="cashbill_test"><?= __('Tryb testowy', 'cashbill-payment-method'); ?></label>
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?= __('Włączony', 'cashbill-payment-method'); ?></span></legend>
					<label for="cashbill_test">
					<input type="checkbox" name="cashbill_test" id="cashbill_test" value="1" <?= $cashbill_test == true ? "checked" : ""?>> Włącz</label>
                    <p class="description">
					<?= __('Włączenie tej opcji umożliwia pełne przetestowanie działania płatności od rozpoczęcia zamówienia do finalnej płatności i powiadomienia sklepu o nowej wpłacie.', 'cashbill-payment-method'); ?>
					<br/>
                    <strong><?= __('Zaznacz tą opcję jedynie kiedy testujesz działanie systemu.', 'cashbill-payment-method'); ?></strong>
                    </p>
				</fieldset>
			</td>
		</tr>
				<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="cashbill_id"><?= __('Identyfikator punktu płatności', 'cashbill-payment-method'); ?></label>
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?= __('Identyfikator punktu płatności', 'cashbill-payment-method'); ?></span></legend>
					<input class="input-text regular-input " type="text" name="cashbill_id" id="cashbill_id" value="<?= $cashbill_id ?>">
                    <p class="description"><?= __('Identyfikator punktu płatności znajduje się w panelu klienta CashBill.', 'cashbill-payment-method'); ?></p>
									</fieldset>
			</td>
		</tr>
				<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="cashbill_secret"><?= __('Klucz punktu płatności', 'cashbill-payment-method'); ?></label>
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?= __('Klucz punktu płatności', 'cashbill-payment-method'); ?></span></legend>
					<input class="input-text regular-input " type="text" name="cashbill_secret" id="cashbill_secret" value="<?= $cashbill_secret ?>">
                    <p class="description"><?= __('Klucz punktu płatności znajduje się w panelu klienta CashBill.', 'cashbill-payment-method'); ?></p>
				</fieldset>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="cashbill_secret"><?= __('Adres serwerowego potwierdzenia transakcji', 'cashbill-payment-method'); ?></label>
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?= __('Adres serwerowego potwierdzenia transakcji', 'cashbill-payment-method'); ?></span></legend>
					<p class="cashbill__callback"><span><?= add_query_arg('wc-api', 'cashbill_payment', home_url()."/") ?></span></p>
                    <p class="description"><?= __('Powyższy adres jest wykorzystywane do otrzymywania powiadomień o transakcji i zmianie jej statusu. Wklej go w konfiguracji swojego sklepu w panelu CashBill.', 'cashbill-payment-method'); ?>
					<br/>
                    <strong><?= __('Pamiętaj, że jeśli wymuszasz połączenie szyfrowane adres powinien zaczynać się od https://', 'cashbill-payment-method'); ?></strong>
					</p>
				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="cashbill_psc_mode"><?= __('Oddzielny punkt płatności dla transakcji PSC', 'cashbill-payment-method'); ?></label>
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?= __('Włączony', 'cashbill-payment-method'); ?></span></legend>
					<label for="cashbill_psc_mode">
					<input type="checkbox" name="cashbill_psc_mode" id="cashbill_psc_mode" value="1" <?= $cashbill_psc_mode == true ? "checked" : ""?>> Włącz</label>
                    <p class="description">
					<?= __('Włączenie tej opcji pozwala na podanie innego punktu płatności dla transakcji paysafecard.', 'cashbill-payment-method'); ?>
                    </p>
				</fieldset>
			</td>
		</tr>
				<tr class="only-if-psc" valign="top">
			<th scope="row" class="titledesc">
				<label for="cashbill_psc_id"><?= __('Identyfikator punktu płatności paysafecard', 'cashbill-payment-method'); ?></label>
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?= __('Identyfikator punktu płatności paysafecard', 'cashbill-payment-method'); ?></span></legend>
					<input class="input-text regular-input " type="text" name="cashbill_psc_id" id="cashbill_psc_id" value="<?= $cashbill_psc_id ?>">
                    <p class="description"><?= __('Identyfikator punktu płatności znajduje się w panelu klienta CashBill.', 'cashbill-payment-method'); ?></p>
									</fieldset>
			</td>
		</tr>
				<tr valign="top" class="only-if-psc">
			<th scope="row" class="titledesc">
				<label for="cashbill_psc_secret"><?= __('Klucz punktu płatności paysafecard', 'cashbill-payment-method'); ?></label>
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?= __('Klucz punktu płatności paysafecard', 'cashbill-payment-method'); ?></span></legend>
					<input class="input-text regular-input " type="text" name="cashbill_psc_secret" id="cashbill_psc_secret" value="<?= $cashbill_psc_secret ?>">
                    <p class="description"><?= __('Klucz punktu płatności znajduje się w panelu klienta CashBill.', 'cashbill-payment-method'); ?></p>
				</fieldset>
			</td>
		</tr>
		<tr valign="top" class="only-if-psc">
			<th scope="row" class="titledesc">
				<label for="cashbill_secret"><?= __('Adres serwerowego potwierdzenia transakcji', 'cashbill-payment-method'); ?></label>
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?= __('Adres serwerowego potwierdzenia transakcji', 'cashbill-payment-method'); ?></span></legend>
					<p class="cashbill__callback"><span><?= add_query_arg(array(
                        'wc-api' => 'cashbill_payment',
                        'cashbill-mode' => 'psc'
                    ), home_url()."/") ?></span></p>
                    <p class="description"><?= __('Powyższy adres jest wykorzystywane do otrzymywania powiadomień o transakcji i zmianie jej statusu. Wklej go w konfiguracji swojego sklepu w panelu CashBill.', 'cashbill-payment-method'); ?>
					<br/>
                    <strong><?= __('Jest to specjalny adres dla transakcji paysafecard. Nie zapomnij wkleić go w konfiguracji nowego punktu płatności.', 'cashbill-payment-method'); ?></strong>
					<br/>
                    <strong><?= __('Pamiętaj, że jeśli wymuszasz połączenie szyfrowane adres powinien zaczynać się od https://', 'cashbill-payment-method'); ?></strong>
					</p>
				</fieldset>
			</td>
		</tr>
		        </tbody></table>
            
        <?php
            wp_nonce_field('cashbill_settings_save', 'cashbill_settings_request');
            submit_button();
        ?>
 
    </form>
 <script>
 	var tooglePsc = function(){
		var el = document.querySelectorAll('.only-if-psc');
		var value = document.querySelector("input[name=cashbill_psc_mode]").checked;
		for(var i=0;i<el.length;i++){
			if(value){
				el[i].style.display = 'table-row';
			}else{
				el[i].style.display = 'none';
			}
		}
	}
	tooglePsc();

 	document.querySelector("input[name=cashbill_psc_mode]").addEventListener( 'change', tooglePsc);

	
 </script>
</div>