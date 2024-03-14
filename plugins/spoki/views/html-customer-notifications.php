<?php
$has_wc = spoki_has_woocommerce();
$is_current_tab = $GLOBALS['current_tab'] == 'customer-notifications';
$has_abandoned_carts = isset($this->options['abandoned_carts']['enable_tracking']) && $this->options['abandoned_carts']['enable_tracking'] == 1;
if (!$has_wc) : ?>
    <div class="notice notice-error">
        <p>
			<?php _e("Install and activate the <strong>WooCommerce</strong> plugin to enable the Spoki features for WooCommerce.", "spoki") ?>
        </p>
    </div>
<?php endif ?>


    <div <?php if (!$is_current_tab) echo 'style="display:none"' ?>>
        <div id="notifications-success" class="notifications-status-message" style="display: none">
            <p><?php _e("🎉 Congratulations! WhatsApp Notifications to customers enabled.", "spoki") ?></p>
        </div>
        <div id="notifications-warning" class="notifications-status-message bg-danger" style="display: none">
            <p>⚠️ <?php _e("There are not active notifications.", "spoki") ?></p>
            <a><?php _e("Enable all", "spoki") ?></a>
        </div>
        <h2>
			<?php _e('Customer Notifications', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=customer-notifications-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p>
			<?php _e('Send <b>order notifications via WhatsApp</b> to your customer.', "spoki") ?><br/>
			<?php _e('The custom fields (eg: <code>[FIRST_NAME]</code>) present within the templates, are fields that Spoki will automatically fill with information relating to the store.', "spoki") ?>
        </p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/order-status.png' ?>"/>

        <fieldset <?php if (!$has_wc) : ?>disabled<?php endif ?>>
			<?php if ($is_current_tab): ?>
                <table class="form-table">
                    <tr>
                        <th>
                            <label for="single_product_button_position">
                                <b><?php _e('Notifications language', "spoki") ?></b>
                            </label>
                        </th>
                        <td>
							<?php
							$language = Spoki()->shop['language'];
							$is_spanish = spoki_starts_with($language, 'es');
							$is_italian = spoki_starts_with($language, 'it');
							$is_portoguese = spoki_starts_with($language, 'pt');
							$is_french = spoki_starts_with($language, 'fr');
							$is_english = !$is_italian && !$is_spanish && !$is_portoguese && !$is_french;
							?>
                            <select name="<?php echo SPOKI_OPTIONS ?>[language]" id="language" class="regular-text">
                                <option value="en-EN" <?php echo $is_english ? 'selected' : '' ?>><?php echo __('English', "spoki") ?></option>
                                <option value="it-IT" <?php echo $is_italian ? 'selected' : '' ?>><?php echo __('Italian', "spoki") ?></option>
                                <option value="es-ES" <?php echo $is_spanish ? 'selected' : '' ?>><?php echo __('Spanish', "spoki") ?></option>
                                <option value="pt-PT" <?php echo $is_portoguese ? 'selected' : '' ?>><?php echo __('Portuguese', "spoki") ?></option>
                                <option value="fr-FR" <?php echo $is_french ? 'selected' : '' ?>><?php echo __('French', "spoki") ?></option>
                            </select>
                            <a id="test-notifications-btn" href="https://app.spoki.it/wd/0f03408d-c637-44ce-975a-c919b6d844c8/" target="_blank">
                                <button class="button button-secondary" type="button"><?php _e("Test notifications", "spoki") ?></button>
                            </a>
                            <p class="description">
								<?php _e('Your language is not in list?', "spoki") ?> <a target="_blank" href="<?php echo SPOKI_SUGGEST_TEMPLATES_URL ?>"><?php _e('Suggest messages', "spoki") ?></a>
                            </p>
                        </td>
                    </tr>
                </table>
			<?php endif; ?>
            <br/>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="leave_review" style="align-items: center;display: inline-flex;">
                            <span><b><?php _e('Require a review', "spoki") ?></b></span>
                        </label>
                    </th>
                    <td>
                        <input class="notification-check" type="checkbox" id="leave_review"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][leave_review]"
                               value="1" <?php if (isset($this->options['woocommerce']['leave_review'])) echo checked(1, $this->options['woocommerce']['leave_review'], false) ?>>
                        <label for="leave_review">
							<?php _e('Ask a public review to customer', "spoki") ?>
                        </label>
                        <p class="description">
							<?php _e("The customer will receive a notification to leave a <b>review on the website you prefer</b>.", "spoki") ?>
                        </p>
                        <br/>
                        <label><?php _e('Send notification', "spoki") ?></label>
                        <?php $currentDays = (isset($this->options['woocommerce']['leave_review_days']) && $this->options['woocommerce']['leave_review_days'] !== "") ? $this->options['woocommerce']['leave_review_days'] : 5; ?>
                        <select name="<?php echo SPOKI_OPTIONS ?>[woocommerce][leave_review_days]" id="leave_review_days" class="regular-text" style="width: 120px">
                            <option value="0" <?php echo ($currentDays == 0) ? 'selected' : '' ?>>
                                <?php _e('immediatly', "spoki") ?>
                            </option>
                            <option value="1" <?php echo ($currentDays == 1) ? 'selected' : '' ?>>
								1 <?php _e('day', "spoki") ?>
                            </option>
                            <?php foreach ([2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30] as $day): ?>
                                <option value="<?php echo $day ?>" <?php echo ($currentDays == $day) ? 'selected' : '' ?>>
                                    <?php echo $day . ' ' . __('days', "spoki") ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                        <label><?php _e('after the completion of the order.', "spoki") ?></label>
                        <br/>
                        <br/>
                        <label><?php _e('Insert the <b>Review Link</b>', "spoki") ?>:</label>
                        <p class="description">
							<?php _e("The review link is the website where the customer can leave you a public review.", "spoki") ?>
                        </p>
                        <input type="text" class="regular-text" id="review_link_input"
                               style="margin-top:8px;"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][leave_review_link]"
                               value="<?php if (isset($this->options['woocommerce']['leave_review_link']) && trim($this->options['woocommerce']['leave_review_link']) != '') echo $this->options['woocommerce']['leave_review_link'] ?>"
                               placeholder="<?php _e('https://google.com/my-website/review', "spoki") ?>"
                        />
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e("If you leave it empty the customer will send the review to your WhatsApp telephone", "spoki") ?>
                        </p>
                        <br/>
                        <label><?php _e('Notification preview', "spoki") ?>:</label>
                        <blockquote>
                            <code id="leave_review_template">
								<?php _e("Hi [FIRST_NAME]! Thank you for purchasing from [SHOP_NAME]! How satisfied are you with your purchase? Leave us a review here [REVIEW_LINK]", "spoki") ?>
                            </code>
                        </blockquote>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label style="align-items: center;display: inline-flex;">
                            <span><b><?php _e('Abandoned carts', "spoki") ?></b></span>
                        </label>
                    </th>
                    <td>
                        <label>
							<?php if (!$has_abandoned_carts): ?>
								<?php _e('Enable from', "spoki") ?> <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
									<?php _e('Abandoned Carts', "spoki") ?>
                                </a>
							<?php else: ?>
                                <span class="color-spoki">✓ <?php _e('Enabled', "spoki") ?></span>
							<?php endif ?>
                        </label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e("Send abandoned cart WhatsApp messages to customers and reduce dropout rates.", "spoki") ?>
                        </p>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="check_order_created">
                            <b><?php _e('Order created', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input class="notification-check" type="checkbox" id="check_order_created"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][order_created]"
                               value="1" <?php if (isset($this->options['woocommerce']['order_created'])) echo checked(1, $this->options['woocommerce']['order_created'], false) ?>>
                        <label for="check_order_created"><?php _e('Enable notification', "spoki") ?></label>
                        <blockquote>
                            <code id="order_created_template">
								<?php _e("👋 Hi [FIRST_NAME], your order n° \"[ORDER_NUMBER]\" 🛍 for the store [SHOP_NAME] has been created! 👍 Need support? ➡️➡️➡️ [CONTACT_LINK]", "spoki") ?>
                            </code>
                        </blockquote>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="check_order_updated">
                            <b><?php _e('Order updated', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input class="notification-check" type="checkbox" id="check_order_updated"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][order_updated]"
                               value="1" <?php if (isset($this->options['woocommerce']['order_updated'])) echo checked(1, $this->options['woocommerce']['order_updated'], false) ?>>
                        <label for="check_order_updated"><?php _e('Enable notification', "spoki") ?></label>
                        <blockquote>
                            <code id="order_updated_template">
								<?php _e("👋 Hi [FIRST_NAME], your order n° \"[ORDER_NUMBER]\" 🛍 for the store [SHOP_NAME] has been updated and its status is \"[ORDER_STATUS]\". Need support? ➡️➡️➡️ [CONTACT_LINK]", "spoki") ?>
                            </code>
                        </blockquote>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="check_order_deleted">
                            <b><?php _e('Order deleted', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input class="notification-check" type="checkbox" id="check_order_deleted"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][order_deleted]"
                               value="1" <?php if (isset($this->options['woocommerce']['order_deleted'])) echo checked(1, $this->options['woocommerce']['order_deleted'], false) ?>>
                        <label for="check_order_deleted"><?php _e('Enable notification', "spoki") ?></label>
                        <blockquote>
                            <code id="order_deleted_template">
								<?php _e("👋 Hi [FIRST_NAME], your order n° \"[ORDER_NUMBER]\" 🛍 for the store [SHOP_NAME] has been updated and its status is CANCELED. Need support? ➡️➡️➡️ [CONTACT_LINK]", "spoki") ?>
                            </code>
                        </blockquote>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="check_order_note_added">
                            <b><?php _e('Tracking number added', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input class="notification-check" type="checkbox" id="check_order_note_added"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][order_note_added]"
                               value="1" <?php if (isset($this->options['woocommerce']['order_note_added'])) echo checked(1, $this->options['woocommerce']['order_note_added'], false) ?>>
                        <label for="check_order_note_added"><?php _e('Enable notification', "spoki") ?></label>
                        <blockquote>
                            <code id="tracking_template">
								<?php _e("👋 Hi [FIRST_NAME], your order n° \"[ORDER_NUMBER]\" 🛍 for the store [SHOP_NAME] has been shipped! 🚀 Here the shipping info: [SHIPPING_INFO] Need support? ➡️➡️➡️ [CONTACT_LINK]", "spoki") ?>
                            </code>
                        </blockquote>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e("All the fields relating to the Tracking Number are automatic except for the [SHIPPING_INFO] field that you will have to fill in by inserting in the note: “[tracking] followed by the tracking number” that you want to insert in the note. GLS integrates automatically!", "spoki") ?>
                        </p>
                        <img class="example" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/tracking_example.png' ?>"/>
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
                "order_created": {
                    "en": "👋 Hi [FIRST_NAME], your order n° [ORDER_NUMBER] 🛍 for the store [SHOP_NAME] has been created! 👍 Do you need support? ➡️➡️➡️ [CONTACT_LINK] <i>do not reply to this message</i>",
                    "it": "👋 Ciao [FIRST_NAME], il tuo ordine n°[ORDER_NUMBER] 🛍 del negozio [SHOP_NAME] è stato creato! 👍 Hai bisogno di aiuto? ➡️➡️➡️ [CONTACT_LINK] <i>non rispondere a questo messaggio</i>",
                    "es": "👋 Hola [FIRST_NAME], tu número de pedido [ORDER_NUMBER] 🛍 de la tienda [SHOP_NAME] se ha creado 👍 ¿Necesitas ayuda? ➡️➡️➡️ [CONTACT_LINK] <i>no contestes a este mensaje</i>",
                    "pt": "👋 Olá [FIRST_NAME], o  número do pedido [ORDER_NUMBER] 🛍 da loja [SHOP_NAME] foi criado 👍 Precisa de ajuda? ➡️➡️➡️ [CONTACT_LINK] <i>não responda a essa mensagem</i>",
                    "fr": "👋 Salut [FIRST_NAME], votre commande n°[ORDER_NUMBER] 🛍 de votre compte [SHOP_NAME] a été créée!👍  Vous avez besoin d’aide? ➡️➡️➡️ [CONTACT_LINK] <i>ne pas répondre</i>",
                },
                "order_updated": {
                    "en": "👋 Hi [FIRST_NAME], your order n° [ORDER_NUMBER] 🛍 for the store [SHOP_NAME] has been updated and its status is [ORDER_STATUS]! 👍 Do you need support? ➡️➡️➡️ [CONTACT_LINK] <i>do not reply to this message</i>",
                    "it": "👋 Ciao [FIRST_NAME], il tuo ordine n°[ORDER_NUMBER] 🛍 del negozio [SHOP_NAME] è stato aggiornato e il suo stato è [ORDER_STATUS]. Hai bisogno di aiuto? ➡️➡️➡️ [CONTACT_LINK] <i>non rispondere a questo messaggio</i>",
                    "es": "👋 Hola [FIRST_NAME], tu número de pedido [ORDER_NUMBER] 🛍 de la tienda [SHOP_NAME] ha sido actualizado y su estado es [ORDER_STATUS]. 👍 ¿Necesitas ayuda? ➡️➡️➡️ [CONTACT_LINK] <i>no contestes a este mensaje</i>",
                    "pt": "👋 Olá [FIRST_NAME], o número do pedido [ORDER_NUMBER] 🛍 da loja [SHOP_NAME] foi atualizado e seu status é [ORDER_STATUS].👍 Precisa de ajuda? ➡️➡️➡️ [CONTACT_LINK] <i>não responda a essa mensagem</i>",
                    "fr": "👋 Salut [FIRST_NAME], votre commande n°[ORDER_NUMBER] 🛍 de votre compte [SHOP_NAME] a été mise à jour et son état est [ORDER_STATUS]. 👍 Vous avez besoin d’aide? ➡️➡️➡️ [CONTACT_LINK] <i>ne pas répondre</i>",
                },
                "order_deleted": {
                    "en": "👋 Hi [FIRST_NAME], your order n° [ORDER_NUMBER] 🛍 for the store [SHOP_NAME] has been updated and its status is Cancelled! 👍 Do you need support? ➡️➡️➡️ [CONTACT_LINK] <i>do not reply to this message</i>",
                    "it": "👋 Ciao [FIRST_NAME], il tuo ordine n°[ORDER_NUMBER] 🛍 del negozio [SHOP_NAME] è stato aggiornato e il suo stato è Annullato. Hai bisogno di aiuto? ➡️➡️➡️ [CONTACT_LINK] <i>non rispondere a questo messaggio</i>",
                    "es": "👋 Hola [FIRST_NAME], tu número de pedido [ORDER_NUMBER] 🛍 de la tienda [SHOP_NAME] ha sido actualizado y su estado es Cancelado. 👍 ¿Necesitas ayuda? ➡️➡️➡️ [CONTACT_LINK] <i>no contestes a este mensaje</i>",
                    "pt": "👋 Olá [FIRST_NAME], o número do pedido [ORDER_NUMBER] 🛍 da loja [SHOP_NAME] foi atualizado e seu status é Cancelled.👍 Precisa de ajuda? ➡️➡️➡️ [CONTACT_LINK] <i>não responda a essa mensagem</i>",
                    "fr": "👋 Salut [FIRST_NAME], votre commande n°[ORDER_NUMBER] 🛍 de votre compte [SHOP_NAME] a été mise à jour et son état est Annulé. 👍 Vous avez besoin d’aide? ➡️➡️➡️ [CONTACT_LINK] <i>ne pas répondre</i>",
                },
                "leave_review": {
                    "en": "Hi [FIRST_NAME], thank you for purchasing from [SHOP_NAME]! How satisfied are you with your purchase? Leave us a review here [REVIEW_LINK] <i>do not reply to this message</i>",
                    "it": "Ciao [FIRST_NAME], grazie di aver acquistato da [SHOP_NAME]! Quanto sei soddisfatto del tuo acquisto? Lasciaci una recensione qui [REVIEW_LINK] <i>non rispondere a questo messaggio</i>",
                    "es": "Hola [FIRST_NAME], ¡gracias por haber comprado en [SHOP_NAME]! ¿Cuánto estás satisfecho de tu compra? Déjanos una reseña aquí [REVIEW_LINK] <i>no contestes a este mensaje</i>",
                    "pt": "Olá [FIRST_NAME], obrigado por comprar em [SHOP_NAME]! Quão satisfeito você está com sua compra? Deixe-nos um comentário aqui [REVIEW_LINK] <i>não responda a esta mensagem</i>",
                    "fr": "Salut [FIRST_NAME], merci d'avoir acheté chez [SHOP_NAME]! Comment étiez-vous satisfait de votre achat? Laissez un commentaire ici [REVIEW_LINK] <i>ne pas répondre</i>",
                },
                "tracking": {
                    "en": "👋 Hi [FIRST_NAME], we have some news about your order n° [ORDER_NUMBER] 🛍 for the store [SHOP_NAME]! Here the shipping info: [SHIPPING_INFO]👍 Do you need support? ➡️➡️➡️ [CONTACT_LINK] <i>do not reply to this message</i>",
                    "it": "👋 Ciao [FIRST_NAME],abbiamo delle novità sul tracciamento del tuo ordine n°[ORDER_NUMBER] 🛍 del negozio [SHOP_NAME] 🚀 Ecco le informazioni sulla spedizione: [SHIPPING_INFO]. Hai bisogno di aiuto? ➡️➡️➡️ [CONTACT_LINK]  <i>non rispondere a questo messaggio</i>",
                    "es": "👋 Hola [FIRST_NAME], tu número de pedido [ORDER_NUMBER] 🛍 de la tienda [SHOP_NAME] ha sido enviado 🚀  Aquí está la información de envío: [SHIPPING_INFO]. 👍 ¿Necesitas ayuda? ➡️➡️➡️ [CONTACT_LINK] <i>no contestes a este mensaje</i>",
                    "pt": "👋 Olá [FIRST_NAME], temos notícias sobre o rastreamento de seu pedido nº [ORDER_NUMBER] 🛍 da loja [SHOP_NAME] 🚀 Aqui estão as informações de envio: [SHIPPING_INFO]. Precisa de ajuda? ➡️➡️➡️ [CONTACT_LINK] <i>não responda a essa mensagem</i>",
                    "fr": "👋 Salut [FIRST_NAME], il y a des nouvelles des données de poursuite de votre commande n°[ORDER_NUMBER] 🛍 de votre compte [SHOP_NAME] 🚀  Voici les informations: [SHIPPING_INFO]. Vous avez besoin d’aide? ➡️➡️➡️ [CONTACT_LINK] <i>ne pas répondre</i>",
                },
            };

            const test_links = {
                "en": "https://app.spoki.it/wd/0f03408d-c637-44ce-975a-c919b6d844c8/",
                "it": "https://app.spoki.it/wd/2d2bdf73-f868-4b98-8075-fa987b92a1b0/",
                "es": "https://app.spoki.it/wd/74b0985c-fa11-408a-b6f5-378b1313aa95/",
                "pt": "https://app.spoki.it/wd/1f4b05ce-e53b-49fa-a0cf-36612cdc0ffb/",
                "fr": "https://app.spoki.it/wd/0e5c1623-81b0-402d-8e86-8f73d2c69b64/",
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

            function setTestLink(lan) {
                const language = lan.substr(0, 2);
                Object.keys(test_links).forEach(key => {
                    document.getElementById('test-notifications-btn').href = test_links[language] || test_links['en'];
                });
            }

            setTemplates("<?php echo Spoki()->shop['language'] ?>");
            setTestLink("<?php echo Spoki()->shop['language'] ?>");
            document.getElementById('language').addEventListener('change', e => {
                setTemplates(e.target.value);
                setTestLink(e.target.value);
            });

            // Check notifications status
            const hasActiveNotification = !!Array.from(document.querySelectorAll('.notification-check[checked]')).length;
            document.getElementById(`notifications-${hasActiveNotification ? 'success' : 'warning'}`).style = '';

            function onCheckTemplates(check) {
                Array.from(document.querySelectorAll('.notification-check')).forEach(el => {
                    if (check) {
                        el.setAttribute('checked', 'checked');
                    } else {
                        el.removeAttribute('checked');
                    }
                });
                document.getElementsByName('submit-templates')[0].click();
            }

            const notificationsSuccess = document.querySelector('#notifications-success a');
            const notificationsWarning = document.querySelector('#notifications-warning a');
            const reviewLinkInput = document.getElementById('review_link_input');
            notificationsSuccess && notificationsSuccess.addEventListener('click', () => onCheckTemplates(false));
            notificationsWarning && notificationsWarning.addEventListener('click', () => onCheckTemplates(true));
            reviewLinkInput && reviewLinkInput.addEventListener('keyup', () => setTemplates());
        });
    </script>
<?php endif; ?>