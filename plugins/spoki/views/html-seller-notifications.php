<?php
$has_wc = spoki_has_woocommerce();
$is_current_tab = $GLOBALS['current_tab'] == 'seller-notifications';
$has_abandoned_carts_notify_to_admin = isset($this->options['abandoned_carts']['notify_to_admin']) && $this->options['abandoned_carts']['notify_to_admin'] == 1;

if (!$has_wc) : ?>
    <div class="notice notice-error">
        <p>
			<?php _e("Install and activate the <strong>WooCommerce</strong> plugin to enable the Spoki features for WooCommerce.", "spoki") ?>
        </p>
    </div>
<?php endif ?>


    <div <?php if (!$is_current_tab) echo 'style="display:none"' ?>>
        <h2><?php _e('Seller notifications', "spoki") ?></h2>
        <p>
			<?php
			/* translators: %1$s: WhatsApp Telephone. */
			printf(__('Send order notifications via WhatsApp <b>to your WhatsApp Telephone</b> (%1$s).', "spoki"), Spoki()->shop['telephone']) ?><br/>
        </p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/spoki-seller-notification.png' ?>"/>

        <fieldset <?php if ($is_current_tab && !$has_wc) : ?>disabled<?php endif ?>>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="check_order_created_to_seller">
                            <b><?php _e('Order created', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input class="notification-check" type="checkbox" id="check_order_created_to_seller"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][order_created_to_seller]"
                               value="1" <?php if (isset($this->options['woocommerce']['order_created_to_seller'])) echo checked(1, $this->options['woocommerce']['order_created_to_seller'], false) ?>>
                        <label for="check_order_created_to_seller"><?php _e('Get notified via WhatsApp on order creation', "spoki") ?></label>
                        <blockquote>
                            <code id="order_created_to_seller_template">
								<?php _e("Good news! You have just received an order amounting to [PRICE] on the [SHOP_NAME] store 🥳 Look at the detail [ORDER_LINK]", "spoki") ?>
                            </code>
                        </blockquote>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e("The message will be sent to your WhatsApp telephone.", "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label style="align-items: center;display: inline-flex;">
                            <span><b><?php _e('Cart recovery', "spoki") ?></b></span>
                        </label>
                    </th>
                    <td>
                        <label>
							<?php if (!$has_abandoned_carts_notify_to_admin): ?>
								<?php _e('Enable from', "spoki") ?> <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
									<?php _e('Abandoned Carts', "spoki") ?>
                                </a>
							<?php else: ?>
                                <span class="color-spoki">✓ <?php _e('Enabled', "spoki") ?></span>
							<?php endif ?>
                        </label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Send cart recovered notification to your WhatsApp Telephone', "spoki") ?>
                        </p>
                        <br/>
                    </td>
                </tr>
            </table>
        </fieldset>

		<?php if ($has_wc) : submit_button(null, 'primary', 'submit-templates'); else : ?>
            <p>
				<?php _e("Install and activate the <strong>WooCommerce</strong> plugin to enable the Spoki features for WooCommerce.", "spoki") ?>
            </p>
		<?php endif ?>
    </div>

<?php if ($is_current_tab): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var currentLanguage = "<?php echo Spoki()->shop['language'] ?>";
            // Handle templates
            const templates = {
                "order_created_to_seller": {
                    "en": "Good news! You have just received an order amounting to [PRICE] on the [SHOP_NAME] store 🥳 Look at the detail [ORDER_LINK]",
                    "it": "Buone notizie! Hai appena ricevuto un ordine di [PRICE] sullo shop [SHOP_NAME] 🥳 Vai al dettaglio dell'ordine [ORDER_LINK]",
                    "es": "¡Buenas noticias! Acabas de recibir un pedido de [PRICE] de la tienda [SHOP_NAME] 🥳 Mire el detalle [ORDER_LINK]",
                    "pt": "Boas notícias! Você acabou de receber um pedido de [PRICE] da loja [SHOP_NAME] 🥳 Veja os detalhes [ORDER_LINK]",
                    "fr": "Bonnes nouvelles! Vous venez de recevoir une commande d'un montant de [PRICE] sur la boutique [SHOP_NAME] 🥳 Regardez le détail [ORDER_LINK]",
                },
            };

            function setTemplates(lan) {
                if (!lan) {
                    lan = currentLanguage;
                } else {
                    currentLanguage = lan;
                }
                const language = lan.substr(0, 2);
                Object.keys(templates).forEach(key => {
                    let text = templates[key][language] || templates[key]['en'];
                    if (key === 'leave_review') {
                        text = text.replace('[REVIEW_LINK]', document.querySelector('#review_link_input').value || '[REVIEW_LINK]')
                    }
                    document.getElementById(`${key}_template`).innerHTML = text;
                });
            }

            setTemplates("<?php echo Spoki()->shop['language'] ?>");
        });
    </script>
<?php endif; ?>