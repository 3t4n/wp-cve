<?php include __DIR__ . '/includes/menu.php'; ?>

<div class="page-header">
	<h1><?= __('Settings', 'axima-payment-gateway') ?></h1>
</div>

<form class="form-horizontal" method="post">
	<div class="col-sm-12">
		<div class="form-group">
			<label for="form-merchant-id" class="col-sm-2 control-label"><?= __('MerchantID', 'axima-payment-gateway') ?></label>
			<div class="col-sm-10">
				<input type="text" name="merchant-id" id="form-merchant-id" class="form-control" value="<?= isset($settings['merchantId']) ? $settings['merchantId'] : '' ?>">
                <p class="help-block">MerchantID najdete v <a href="https://www.pays.cz/customers/ShopSettings.asp" target="_blank">administraci</a> Pays v menu Nastavení, sekce Obecné informace.</p>
			</div>
		</div>
		<div class="form-group">
			<label for="form-shop-id" class="col-sm-2 control-label">ShopID</label>
			<div class="col-sm-10">
				<input type="text" name="shop-id" id="form-shop-id" class="form-control" value="<?= isset($settings['shopId']) ? $settings['shopId'] : '' ?>">
                <p class="help-block">ShopId najdete v <a href="https://www.pays.cz/customers/ShopSettings.asp" target="_blank">administraci</a> Pays v menu Nastavení, sekce Obecné informace.</p>
			</div>
		</div>
		<div class="form-group">
			<label for="form-hash-password" class="col-sm-2 control-label"><?= __('API password', 'axima-payment-gateway') ?></label>
			<div class="col-sm-10">
				<input type="password" name="hash-password" id="form-hash-password" class="form-control" autocomplete="off" value="<?= isset($settings['hashPassword']) ? $settings['hashPassword'] : '' ?>">
                <p class="help-block">Heslo najdete v <a href="https://www.pays.cz/customers/ShopSettings.asp" target="_blank">administraci</a> Pays v menu Nastavení, sekce Komunikační adresy.</p>
			</div>
		</div>

        <div class="form-group">
            <h3>Adresy notifikačních stránek</h3>
            <div class="col-sm-10">
                <p>
                    Je doporučeno použít formát adres pro WooCommerce, které přesměrují na děkovné a chybové stránky WooCommerce. Stále můžete použít starší způsob nastavením pomocí vlastních článků.
                    <strong>Informujte Pays.cz o jakékoliv změně URL stránek</strong> e-mailem nebo odešlete tlačítkem níže přímo z administrace pluginu.
                </p>
                <div>
                    <input type="radio" id="url-type-1" name="url-type" value="<?php echo \Pays\PaymentGate\Plugin::URL_TYPE_WOOCOMMERCE ?>" <?php if($settings['url-type'] == \Pays\PaymentGate\Plugin::URL_TYPE_WOOCOMMERCE): ?>checked<?php endif; ?>>
                    <label for="url-type-1">WooCommerce stránky (doporučeno)</label>
                </div>
                <div>
                    <input type="radio" id="url-type-2" name="url-type" value="<?php echo \Pays\PaymentGate\Plugin::URL_TYPE_OWN_PAGE ?>" <?php if($settings['url-type'] == \Pays\PaymentGate\Plugin::URL_TYPE_OWN_PAGE): ?>checked<?php endif; ?>>
                    <label for="url-type-2">Vlastní stránky</label>
                </div>

                <hr>
            </div>
        </div>

		<div class="form-group">
			<label for="form-confirm-url" class="col-sm-2 control-label"><?= __('Confirmation URL', 'axima-payment-gateway') ?></label>
			<div class="col-sm-10">
				<input type="text" name="confirm-url" id="form-confirm-url" class="form-control" readonly value="<?= get_site_url(NULL, 'wp-admin/admin-ajax.php?action=pays-confirmation') ?>">
			</div>
		</div>

        <div id="tab-1" <?php if($settings['url-type'] != \Pays\PaymentGate\Plugin::URL_TYPE_WOOCOMMERCE): ?>style="display:none;"<?php endif; ?>>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?= __('Success payment page', 'axima-payment-gateway') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" readonly value="<?= get_site_url(NULL, 'wp-admin/admin-ajax.php?action=pays-success') ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?= __('Failed payment page', 'axima-payment-gateway') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" readonly value="<?= get_site_url(NULL, 'wp-admin/admin-ajax.php?action=pays-error') ?>">
                </div>
            </div>
        </div>

        <div id="tab-2" <?php if($settings['url-type'] != \Pays\PaymentGate\Plugin::URL_TYPE_OWN_PAGE): ?>style="display:none;"<?php endif; ?>>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?= __('Success payment page', 'axima-payment-gateway') ?></label>
                <div class="col-sm-5">
                    <select name="success-url" data-change="#ok-url" class="form-control">
                        <?php foreach ($pages as $url => $page): ?>
                            <option value="<?= $url ?>"<?php if (isset($settings['success-url']) && $url === $settings['success-url']): ?> selected<?php endif; ?>><?= $page ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-5">
                    <input type="text" id="ok-url" name="success-url-preview" class="form-control" readonly value="<?= isset($settings['success-url']) ? $settings['success-url'] : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?= __('Failed payment page', 'axima-payment-gateway') ?></label>
                <div class="col-sm-5">
                    <select name="error-url" data-change="#error-url" class="form-control">
                        <?php foreach ($pages as $url => $page): ?>
                            <option value="<?= $url ?>"<?php if (isset($settings['error-url']) && $url === $settings['error-url']): ?> selected<?php endif; ?>><?= $page ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-5">
                    <input type="text" id="error-url" name="error-url-preview" class="form-control" readonly value="<?= isset($settings['error-url']) ? $settings['error-url'] : '' ?>">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <button type="submit" name="_submit" class="btn btn-primary"><?= __('Save', 'axima-payment-gateway') ?></button>
                <a href="?page=<?= $domain ?>" class="btn btn-default"><?= __('Cancel', 'axima-payment-gateway') ?></a>

                <?php if(!empty($settings['merchantId']) && !empty($settings['shopId'])): ?>
                    <a href="#" id="mailbutton" type="button" class="btn btn-info">Odeslat nastavení do Pays.cz</a>
                <?php endif; ?>
            </div>
        </div>
	</div>
</form>

<script>
	(function ($) {
		$(function () {
			$('[data-change]').on('change', function () {
				$($(this).data('change')).val($(this).val());
			});

			$('input[name="url-type"]').on('change', function () {
                let urlType = $(this).val();

                if ($('a#mailbutton').length > 0) {
                    $('a#mailbutton').hide();
                }

                if (urlType == 1) {
                    $('div#tab-2').hide();
                    $('div#tab-1').show();
                } else {
                    $('div#tab-2').show();
                    $('div#tab-1').hide();
                }
            });
		});

        <?php if(!empty($settings['merchantId']) && !empty($settings['shopId'])): ?>
            <?php
                $body = "Zasíláme komunikační adresy:\n" .
                        "URL pro potvrzení platby: " . get_site_url(NULL, 'wp-admin/admin-ajax.php?action=pays-confirmation') . "\n";

                if ($settings['url-type'] == \Pays\PaymentGate\Plugin::URL_TYPE_OWN_PAGE) {
                    $body .= "Stránka pro úspěšnou platbu: " . $settings['success-url'] . "\n" .
                    "Stránka pro chybnou platbu: " . $settings['error-url'];
                } else {
                    $body .=  "Stránka pro úspěšnou platbu: " . get_site_url(NULL, 'wp-admin/admin-ajax.php?action=pays-success') . "\n" .
                    "Stránka pro chybnou platbu: " . get_site_url(NULL, 'wp-admin/admin-ajax.php?action=pays-error');
                }


                $subject = "Nastavení eshopu: " . $settings['merchantId'] . ", " . $settings['shopId'];

                $body = rawurlencode(htmlspecialchars_decode($body));
                $subject = rawurlencode(htmlspecialchars_decode($subject));
            ?>

            $('#mailbutton').click(function(event) {
                window.location = "mailto:podpora@pays.cz?subject=<?=$subject;?>&body=<?=$body; ?>";
            });
        <?php endif; ?>
	})(jQuery);
</script>
