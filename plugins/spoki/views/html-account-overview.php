<?php
$is_current_tab = $GLOBALS['current_tab'] == 'welcome';
$has_wc = spoki_has_woocommerce();
$has_elementor = spoki_has_elementor();

/** Spoki Account Overview */
if ($has_wc && $is_current_tab) :
	$has_spoki_keys = (isset($this->options['secret']) && trim($this->options['secret']) != '') && (isset($this->options['delivery_url']) && trim($this->options['delivery_url']) != '');
	$account_info = $this->options['account_info'] ?? ['plan' => []];
	if ($has_spoki_keys && !isset($account_info['plan']['name'])) {
		$account_info = Spoki()->fetch_account_info();
	}
	$is_free = (!isset($this->options['account_info']['plan']['slug']) || $this->options['account_info']['plan']['slug'] == 'flex-10');
	$plan_start = isset($this->options['account_info']['plan']['start_datetime']) ? (new DateTime($account_info['plan']['start_datetime']))->format('d/m/Y') : null;
	$plan_end = isset($account_info['plan']['end_datetime']) ? (new DateTime($account_info['plan']['end_datetime']))->format('d/m/Y') : null;
	$available_coins = $account_info['available_coins'] ?? 0;
	$coins_status = $available_coins < 3 ? 'expired' : ($available_coins < 6 ? 'expiring' : 'ok');
	$upgrade_url = Spoki()->get_pro_plan_link();
	$is_pro = isset($account_info['plan']['is_pro']) && $account_info['plan']['is_pro'] == true;
	$is_credit_based = isset($account_info['plan']['is_credit_based']) && $account_info['plan']['is_credit_based'] == true;

	if (!$is_credit_based && $available_coins && $coins_status != 'ok') { ?>
        <div class="notice notice-<?php echo $coins_status == 'expiring' ? 'warning' : 'error' ?>">
            <p>
				<?php $available_coins > 0 ?
					_e('<b>Your available contacts are expiring!</b>', "spoki") :
					_e('<b>Your available contacts are expired!</b>', "spoki")
				?>
				<?php if (!$is_pro): ?>
                    <a href="<?php print($upgrade_url) ?>" target="_blank">
                        <button type="button" class="button button-primary bg-spoki" style="border: none;">
                            ⇡ <?php _e('Upgrade', "spoki") ?>
                        </button>
                    </a>
				<?php endif; ?>
            </p>
        </div>
	<?php }

	if (isset($account_info)) :
		$has_notifications = Spoki()->shop['has_notifications'];
		$has_order_created_to_seller_notification = Spoki()->shop['has_order_created_to_seller_notification'];
		$has_leave_review_notification = Spoki()->shop['has_leave_review_notification'];
		$has_order_note_added_notification = Spoki()->shop['has_order_note_added_notification'];
		$has_order_status_notifications = Spoki()->shop['has_order_created_notification'] || Spoki()->shop['has_order_updated_notification'] || Spoki()->shop['has_order_deleted_notification'];
		$has_abandoned_carts = Spoki()->shop['has_abandoned_carts'];
		$has_fixed_support_button = Spoki()->shop['has_fixed_support_button'];
		$has_product_item_listing_button = Spoki()->shop['has_product_item_listing_button'];
		$has_single_product_button = Spoki()->shop['has_single_product_button'];
		$has_cart_button = Spoki()->shop['has_cart_button'];
		$has_buttons = $has_fixed_support_button || $has_product_item_listing_button || $has_single_product_button || $has_cart_button;
		?>
        <div class="spoki-dashboard">
            <div class="spoki-card" style="order: <?php echo $is_free ? 0 : 10 ?>">
                <div class="spoki-card-header">
                    <div class="d-flex align-items-center">
                        <h2 class="title">
							<?php _e("Earn with Spoki!", "spoki") ?>
                        </h2>
                    </div>
                    <div>
                        <button type="button" id="why-spoki-expand" class="button button-secondary" style="<?php if ($is_free) echo "display:none;" ?> border: none">＋</button>
                        <button type="button" id="why-spoki-collapse" class="button button-secondary" style="<?php if (!$is_free) echo "display:none;" ?> border: none">－</button>
                    </div>
                </div>
                <div id="why-spoki-body" class="spoki-card-body" <?php if (!$is_free) echo "style='display:none;'" ?>>
                    <p style="font-size: 0.9rem; margin-top: 0">
						<?php _e('<b>Everyone reads WhatsApp messages!</b><br/><br/>Spoki is a project that solves a big need: <b>to increase sales</b>!
<br/><br/>One problem we continually encounter is that <b>no one reads emails</b>! This is why Spoki was born: <b>it increases the reading rate from 10% to 99%</b> simply by moving the conversation from traditional emails to WhatsApp.
<br/><br/>Spoki allows you to <b>earn in 3 different ways</b>:', 'spoki') ?>
                        <br/><br/>
                        <span id="why-spoki-read-more" class="button-link">
							<?php _e('Keep reading', "spoki") ?>
                        </span>
                    </p>
                    <div id="why-spoki-extra-content" style="display: none">
                        <h3>1. <?php _e('Earn Orders', 'spoki') ?></h3>
                        <p style="font-size: 0.9rem">
							<?php _e('How cool would it be to <b>acquire lost customers</b> in the checkout phase? It is <b>the dream of every ecommerce</b>!
<br/><br/>This feature is so in demand and <b>powerful</b> that it can cost up to &dollar; 600 / year and uses email alone as a communication (open rate of only 10&percnt;).
<br/><br/>How nice would it be to hook up all the <b>very high quality carts</b> (with a mobile number already entered) and remind them <b>on WhatsApp</b> that they have left something in the cart?
<br/><br/>The brand new Spoki feature is already available for FREE with an <b>open rate 5 times higher</b>!', 'spoki') ?>
                            <br/>
							<?php if (!$has_abandoned_carts): ?>
                                <br/>
                                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                                    <button type="button" class="button button-primary">
										<?php _e('Enable Abandoned Carts', "spoki") ?>
                                    </button>
                                </a>
							<?php else: ?>
                                <br/>
                                <span class="color-spoki">✓ <?php _e('Abandoned Carts Enabled', "spoki") ?></span>
							<?php endif; ?>
                        </p>
                        <br/>
                        <h3>2. <?php _e('Save Time', 'spoki') ?></h3>
						<?php _e(' <p style="font-size: 0.9rem">How many times has it happened to you that the customer has <b>asked you for the status of his order</b> despite the automatic WooCommerce email?
<br/><br/>How challenging is it to have to <b>search</b> and send parcel <b>tracking information</b> every time that unfortunately WooCommerce does not manage?
<br/><br/>How many <b>hours</b> do you or one of your collaborators <b>waste</b> each month forced to <b>stop what you were doing</b> to dedicate it to this type of support?
<br/><br/>Here is Spoki\'s second earning opportunity: <b>Automatic Notifications on WhatsApp</b>!</p><ul>
<li>• Everyone reads WhatsApp messages =></li>
<li>• Everyone will read your order notifications =></li>
<li>• Only customers who encounter problems will contact you =></li>
<li>• You will reduce the time dedicated to this activity by 90% =></li>
<li>• Devote this saved time to your most profitable activities =></li>
<li>• Get a positive review wherever you like with the <b>review notification</b>!</li>
</ul><p style="font-size: 0.9rem"><br/>You have gained time and effort that until now you waste in this boring and repetitive activity.
<br/><br/>You have increased the possibility of receiving a positive review and increasing the image of your e-commerce.
<br/><br/>Enable all Free WooCommerce notifications and <b>stop wasting your time</b>!</p>', 'spoki') ?>
						<?php if (!$has_notifications): ?>
                            <br/>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=customer-notifications">
                                <button type="button" class="button button-primary">
									<?php _e('Enable Order Status Notifications', "spoki") ?>
                                </button>
                            </a>
						<?php else: ?>
                            <p style="font-size: 0.9rem" class="color-spoki">✓ <?php _e('Order Status Notifications Enabled', "spoki") ?></p>
						<?php endif; ?>
                        <br/>
                        <h3>3. <?php _e('Earn Customers', 'spoki') ?></h3>
						<?php _e(' <p style="font-size: 0.9rem">The last great earning opportunity, totally free and always available, are the <b>WhatsApp buttons</b>.
<br/><br/>Spoki allows you to show all kinds of WhatsApp buttons you want on your site, from the Fixed button to the product button, to the checkout button, which allow the customer to <b>write you directly on your WhatsApp number</b>!
<br/><br/>Respond quickly and comfortably to your potential customers and take advantage of the WhatsApp communication channel to offer them promotions and <b>push them to purchase</b>!</p>', 'spoki') ?>
						<?php if (!$has_buttons): ?>
                            <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons">
                                <button type="button" class="button button-primary">
									<?php _e('Customize Buttons', "spoki") ?>
                                </button>
                            </a>
						<?php else: ?>
                            <p style="font-size: 0.9rem" class="color-spoki">✓ <?php _e('Buttons Enabled', "spoki") ?></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <script>
                document.getElementById('why-spoki-read-more').addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById('why-spoki-extra-content').style.display = '';
                    document.getElementById('why-spoki-read-more').style.display = 'none';
                });

                document.getElementById('why-spoki-expand').addEventListener('click', function (e) {
                    document.getElementById('why-spoki-body').style.display = '';
                    document.getElementById('why-spoki-collapse').style.display = '';
                    document.getElementById('why-spoki-expand').style.display = 'none';
                });

                document.getElementById('why-spoki-collapse').addEventListener('click', function (e) {
                    document.getElementById('why-spoki-body').style.display = 'none';
                    document.getElementById('why-spoki-collapse').style.display = 'none';
                    document.getElementById('why-spoki-expand').style.display = '';
                });
            </script>


			<?php if (!$is_credit_based): ?>
                <!-- Contacts Overview -->
                <div class="spoki-account-overview spoki-card">
                    <div class="spoki-card-header header">
                        <div class="d-flex align-items-center">

                            <h2 class="title">
								<?php _e("Contacts overview", "spoki") ?>
                            </h2>
                        </div>
						<?php if (!$is_pro): ?>
                            <a href="<?php print($upgrade_url) ?>" target="_blank">
                                <button type="button" class="button button-primary bg-spoki" style="border: none">
                                    ⇡ <?php _e('Upgrade', "spoki") ?>
                                </button>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="body spoki-card-body">
                        <div class="spoki-statistic">
                        <span class="coins <?php echo $coins_status ?>">
                            <?php echo isset($available_coins) ? $available_coins : '--' ?>
                        </span>
                            <div>
                                <h3 class="label"><?php _e("Available contacts", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("New contacts to whom you can send unlimited messages until", "spoki") ?>
										<?php echo (isset($plan_end)) ? $plan_end : __("the current plan expiration date", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (isset($available_coins) && $coins_status != 'ok') : ?>
                                    <p>
										<?php echo $available_coins > 0 ?
											_e('<b>Your available contacts are expiring!</b>', "spoki") :
											_e('<b>Your available contacts are expired!</b>', "spoki")
										?>
										<?php if (!$is_pro): ?>
                                            <a href="<?php print($upgrade_url) ?>" target="_blank">
                                                <button type="button" class="button button-primary bg-spoki" style="border: none;">
                                                    ⇡ <?php _e('Upgrade', "spoki") ?>
                                                </button>
                                            </a>
										<?php endif; ?>
                                    </p>
								<?php endif ?>
                            </div>
                        </div>
                        <div class="spoki-statistic">
                        <span class="coins">
                            <?php echo $account_info['active_contacts'] ?>
                        </span>
                            <div>
                                <h3 class="label"><?php _e("Active contacts", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Contacts you have sent at least one message since", "spoki") ?>
										<?php echo (isset($plan_start)) ? $plan_start : __("the activation date", "spoki") ?>.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="footer spoki-card-footer">
                    <span>
                        <?php
						if (isset($account_info['plan'])) {
							$price = ($account_info['plan']['standard_amount'] != $account_info['plan']['amount']) ?
								'<del aria-hidden="true">' . wc_price($account_info['plan']['standard_amount'] / 100, ['currency' => 'EUR']) . '</del> <ins>' . wc_price($account_info['plan']['amount'] / 100, ['currency' => 'EUR']) . '</ins>' :
								wc_price($account_info['plan']['amount'] / 100, ['currency' => 'EUR']);
							/* translators: %1$s: Plan name. */
							/* translators: %2$s: Renew date. */
							/* translators: %3$s: Plane price. */
							printf(__('Your plan <i>"%1$s"</i> will automatically renew the <i>%2$s</i> for the price of <b>%3$s</b>.', "spoki"), $account_info['plan']['name'], $plan_end ? $plan_end : '--', $price);
						} ?>
                    </span>
                        <p class="note">
                            <small>
                                <b><?php _e("Note", "spoki") ?></b>: <?php _e("The contact is a person with whom you can send unlimited notifications during the monthly subscription period.", "spoki") ?>
                            </small>
                        </p>
                    </div>
                </div>
			<?php endif; ?>

            <!-- Abandoned Carts Overview -->
			<?php
			$conversion_rate = 0;
			$abandoned_report = ["no_of_orders" => 0, "revenue" => 0];
			$recovered_report = ["no_of_orders" => 0, "revenue" => 0];
			$recontacted_recovered_report = ["no_of_orders" => 0, "revenue" => 0];
			if ($has_abandoned_carts) {
				$spoki_abandoned_carts = Spoki_Abandoned_Carts::instance();
				$abandoned_report = $spoki_abandoned_carts->get_report_by_type(SPOKI_CART_ABANDONED_ORDER);
				$recovered_report = $spoki_abandoned_carts->get_report_by_type(SPOKI_CART_COMPLETED_ORDER);
				$recontacted_recovered_report = $spoki_abandoned_carts->get_recontacted_recovered_report();
				$conversion_rate = 0;
				$total_orders = ($recovered_report['no_of_orders'] + $abandoned_report['no_of_orders']);
				if ($total_orders) {
					$conversion_rate = ($recovered_report['no_of_orders'] / $total_orders) * 100;
				}
				global $woocommerce;
			}
			$conversion_rate = number_format_i18n($conversion_rate, 2);
			?>
            <div class="spoki-card">
                <div class="spoki-card-header">
                    <div class="d-flex align-items-center">
                        <h2 class="title">
							<?php _e("Abandoned Carts", "spoki") ?>
                            <a href="#TB_inline?&width=300&inlineId=abandoned-carts-info-dialog" class="thickbox button-info">ℹ</a>
                        </h2>
                    </div>
					<?php if (!$has_abandoned_carts): ?>
                        <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                            <button type="button" class="button button-primary">
								<?php _e('Enable tracking', "spoki") ?>
                            </button>
                        </a>
					<?php else: ?>
                        <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                            <button type="button" class="button">
								<?php _e('Settings', "spoki") ?>
                            </button>
                        </a>
					<?php endif; ?>
                </div>
                <div class="spoki-card-body d-flex flex-direction-xs-column">
                    <div class="spoki-statistic <?php if (!$has_abandoned_carts) echo 'disabled' ?>">
                        <span class="coins">
                            <?php echo $abandoned_report['no_of_orders'] ?>
                        </span>
                        <div>
                            <h3 class="label"><?php _e("Recoverable Orders", "spoki") ?></h3>
                            <p class="subtitle">
                                <small>
									<?php _e("Total recoverable orders", "spoki") ?>.
                                </small>
                            </p>
							<?php if (!$has_abandoned_carts): ?>
                                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                                    <button type="button" class="button button-link">
										<?php _e('Enable tracking', "spoki") ?>
                                    </button>
                                </a>
							<?php else: ?>
                                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts&section=resend">
                                    <button type="button" class="button button-link mt-1">
										<?php _e('Resend Notifications', "spoki") ?>
                                    </button>
                                </a>
							<?php endif; ?>
                        </div>
                    </div>
                    <div class="spoki-statistic <?php if (!$has_abandoned_carts) echo 'disabled' ?>">
                        <span class="coins">
                            <?php echo $recovered_report['no_of_orders'] ?>
                        </span>
                        <div>
                            <h3 class="label"><?php _e("Recovered Orders", "spoki") ?></h3>
                            <p class="subtitle">
                                <small>
									<?php _e("Total recovered orders with spoki", "spoki") ?>.
                                </small>
                            </p>
							<?php if (!$has_abandoned_carts): ?>
                                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                                    <button type="button" class="button button-link">
										<?php _e('Enable tracking', "spoki") ?>
                                    </button>
                                </a>
							<?php endif; ?>
                        </div>
                    </div>
                    <div class="spoki-statistic <?php if (!$has_abandoned_carts) echo 'disabled' ?>">
                        <span class="coins">
                            <?php echo $recontacted_recovered_report['no_of_orders'] ?>
                        </span>
                        <div>
                            <h3 class="label"><?php _e("Recontacted and recovered", "spoki") ?></h3>
                            <p class="subtitle">
                                <small>
									<?php _e("Total recontacted and recovered carts", "spoki") ?>.
                                </small>
                            </p>
							<?php if (!$has_abandoned_carts): ?>
                                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                                    <button type="button" class="button button-link">
										<?php _e('Enable tracking', "spoki") ?>
                                    </button>
                                </a>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="spoki-card-body d-flex flex-direction-xs-column">
                    <div class="spoki-statistic <?php if (!$has_abandoned_carts) echo 'disabled' ?>">
                        <span class="coins small">
                            &euro;<?php echo esc_attr(number_format_i18n($abandoned_report['revenue'], 2)); ?>
                        </span>
                        <div>
                            <h3 class="label"><?php _e("Recoverable Revenue", "spoki") ?></h3>
                            <p class="subtitle">
                                <small>
									<?php _e("Total recoverable revenue", "spoki") ?>.
                                </small>
                            </p>
							<?php if (!$has_abandoned_carts): ?>
                                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                                    <button type="button" class="button button-link">
										<?php _e('Enable tracking', "spoki") ?>
                                    </button>
                                </a>
							<?php endif; ?>
                        </div>
                    </div>
                    <div class="spoki-statistic <?php if (!$has_abandoned_carts) echo 'disabled' ?>">
                        <span class="coins small">
                            &euro;<?php echo esc_attr(number_format_i18n($recovered_report['revenue'], 2)); ?>
                        </span>
                        <div>
                            <h3 class="label"><?php _e("Recovered Revenue", "spoki") ?></h3>
                            <p class="subtitle">
                                <small>
									<?php _e("Total recovered revenue", "spoki") ?>.
                                </small>
                            </p>
							<?php if (!$has_abandoned_carts): ?>
                                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                                    <button type="button" class="button button-link">
										<?php _e('Enable tracking', "spoki") ?>
                                    </button>
                                </a>
							<?php endif; ?>
                        </div>
                    </div>
                    <div class="spoki-statistic <?php if (!$has_abandoned_carts) echo 'disabled' ?>">
                        <span class="coins small">
                            &euro;<?php echo esc_attr(number_format_i18n($recontacted_recovered_report['revenue'], 2)); ?>
                        </span>
                        <div>
                            <h3 class="label"><?php _e("Recontacted and recovered Revenue", "spoki") ?></h3>
                            <p class="subtitle">
                                <small>
									<?php _e("Total recontacted and recovered revenue", "spoki") ?>.
                                </small>
                            </p>
							<?php if (!$has_abandoned_carts): ?>
                                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts">
                                    <button type="button" class="button button-link">
										<?php _e('Enable tracking', "spoki") ?>
                                    </button>
                                </a>
							<?php endif; ?>
                        </div>
                    </div>
                    <!--                    <div class="spoki-statistic --><?php //if (!$has_abandoned_carts) echo 'disabled'
					?><!--">-->
                    <!--                        <span class="coins small">-->
                    <!--                            --><?php //echo esc_attr($conversion_rate) . '%';
					?>
                    <!--                        </span>-->
                    <!--                        <div>-->
                    <!--                            <h3 class="label">--><?php //_e("Recovery Rate", "spoki")
					?><!--</h3>-->
                    <!--                            <p class="subtitle">-->
                    <!--                                <small>-->
                    <!--									--><?php //_e("Total recovery rate", "spoki")
					?><!--.-->
                    <!--                                </small>-->
                    <!--                            </p>-->
                    <!--							--><?php //if (!$has_abandoned_carts):
					?>
                    <!--                                <a href="?page=--><?php //echo urlencode(SPOKI_PLUGIN_NAME)
					?><!--&tab=abandoned-carts">-->
                    <!--                                    <button type="button" class="button button-link">-->
                    <!--										--><?php //_e('Enable tracking', "spoki")
					?>
                    <!--                                    </button>-->
                    <!--                                </a>-->
                    <!--							--><?php //endif;
					?>
                    <!--                        </div>-->
                    <!--                    </div>-->
                </div>
            </div>

			<?php if (isset($account_info['statistics']['notifications'])) : ?>
                <!-- Customer Notifications Overview -->
                <div class="spoki-card">
                    <div class="spoki-card-header">
                        <div class="d-flex align-items-center">
                            <h2 class="title">
								<?php _e("Customer Notifications", "spoki") ?>
                            </h2>
                        </div>
						<?php if (!$has_notifications): ?>
                            <input id="enable_notifications_input" type="hidden" name="<?php echo SPOKI_OPTIONS ?>[enable_notifications]">
                            <button type="button" class="button button-primary" onclick="document.getElementById('enable_notifications_input').value = '1';document.getElementsByName('submit-statistics')[0].click()">
								<?php _e('Enable notifications', "spoki") ?>
                            </button>
						<?php else: ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=customer-notifications">
                                <button type="button" class="button">
									<?php _e('Settings', "spoki") ?>
                                </button>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="spoki-card-body d-flex flex-direction-xs-column">
                        <div class="spoki-statistic <?php if (!$has_leave_review_notification) echo 'disabled' ?>">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['notifications']['leave_review']) ? $account_info['statistics']['notifications']['leave_review'] : '0' ?>
                            </span>
                            <div>
                                <h3 class="label">
									<?php _e("Require a review", "spoki") ?>
                                </h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total review request notifications sent", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (!$has_leave_review_notification): ?>
                                    <input id="enable_leave_review_notification_input" type="hidden" name="<?php echo SPOKI_OPTIONS ?>[enable_leave_review_notification]">
                                    <button type="button" class="button button-link" onclick="document.getElementById('enable_leave_review_notification_input').value = '1';document.getElementsByName('submit-statistics')[0].click()">
										<?php _e('Enable', "spoki") ?>
                                    </button>
								<?php endif; ?>
                            </div>
                        </div>
                        <div class="spoki-statistic <?php if (!$has_order_status_notifications) echo 'disabled' ?>">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['notifications']['order_status']) ? $account_info['statistics']['notifications']['order_status'] : '0' ?>
                            </span>
                            <div>
                                <h3 class="label"><?php _e("Order status", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total order status notifications sent (created, updated, deleted)", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (!$has_order_status_notifications): ?>
                                    <input id="enable_order_status_notifications_input" type="hidden" name="<?php echo SPOKI_OPTIONS ?>[enable_order_status_notifications]">
                                    <button type="button" class="button button-link" onclick="document.getElementById('enable_order_status_notifications_input').value = '1';document.getElementsByName('submit-statistics')[0].click()">
										<?php _e('Enable', "spoki") ?>
                                    </button>
								<?php endif; ?>
                            </div>
                        </div>
                        <div class="spoki-statistic <?php if (!$has_order_note_added_notification) echo 'disabled' ?>">
                                <span class="coins">
                                    <?php echo isset($account_info['statistics']['notifications']['tracking_info']) ? $account_info['statistics']['notifications']['tracking_info'] : '0' ?>
                                </span>
                            <div>
                                <h3 class="label"><?php _e("Tracking number added", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total tracking info notifications sent", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (!$has_order_note_added_notification): ?>
                                    <input id="enable_order_note_added_notification_input" type="hidden" name="<?php echo SPOKI_OPTIONS ?>[enable_order_note_added_notification]">
                                    <button type="button" class="button button-link" onclick="document.getElementById('enable_order_note_added_notification_input').value = '1';document.getElementsByName('submit-statistics')[0].click()">
										<?php _e('Enable', "spoki") ?>
                                    </button>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seller Notifications Overview -->
                <div class="spoki-card">
                    <div class="spoki-card-header">
                        <div class="d-flex align-items-center">
                            <h2 class="title">
								<?php _e("Seller Notifications", "spoki") ?>
                            </h2>
                        </div>
						<?php if (!$has_order_created_to_seller_notification): ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=seller-notifications">
                                <button type="button" class="button button-primary">
									<?php _e('Enable notifications', "spoki") ?>
                                </button>
                            </a>
						<?php else: ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=seller-notifications">
                                <button type="button" class="button">
									<?php _e('Settings', "spoki") ?>
                                </button>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="spoki-card-body d-flex flex-direction-xs-column">
                        <div class="spoki-statistic <?php if (!$has_order_created_to_seller_notification) echo 'disabled' ?>">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['notifications']['order_created_to_seller']) ? $account_info['statistics']['notifications']['order_created_to_seller'] : '0' ?>
                            </span>
                            <div>
                                <h3 class="label">
									<?php _e("Order created", "spoki") ?>
                                </h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total order created notifications sent", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (!$has_order_created_to_seller_notification): ?>
                                    <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=seller-notifications">
                                        <button type="button" class="button button-link">
											<?php _e('Enable', "spoki") ?>
                                        </button>
                                    </a>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

            <!-- Buttons Overview -->
            <div class="d-flex flex-direction-xs-column">
                <div class="spoki-card w-50 w-xs-100">
                    <div class="spoki-card-header">
                        <div class="d-flex align-items-center">
                            <h2 class="title">
								<?php _e("Floating Button", "spoki") ?>
                                <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
                            </h2>
                        </div>
						<?php if (!$has_fixed_support_button): ?>
                            <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons">
                                <button type="button" class="button button-primary">
									<?php _e('Customize', "spoki") ?>
                                </button>
                            </a>
						<?php else: ?>
                            <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons">
                                <button type="button" class="button">
									<?php _e('Settings', "spoki") ?>
                                </button>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="spoki-card-body">
                        <div class="spoki-statistic <?php if (!$has_fixed_support_button) echo 'disabled' ?>">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['widget_fixed']) ? $account_info['statistics']['widget_fixed'] : '0' ?>
                            </span>
                            <div>
                                <h3 class="label"><?php _e("Clicks", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total clicks since the activation date", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (!$has_fixed_support_button): ?>
                                    <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons">
                                        <button type="button" class="button button-link">
											<?php _e('Customize', "spoki") ?>
                                        </button>
                                    </a>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php if ($has_elementor): ?>
                    <div class="spoki-card w-50 w-xs-100">
                        <div class="spoki-card-header">
                            <div class="d-flex align-items-center">
                                <h2 class="title">
									<?php _e("Elementor Buttons", "spoki") ?>
                                    <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
                                </h2>
                            </div>
                            <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=elementor">
                                <button type="button" class="button">
									<?php _e('Settings', "spoki") ?>
                                </button>
                            </a>
                        </div>
                        <div class="spoki-card-body">
                            <div class="spoki-statistic">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['widget_elementor']) ? $account_info['statistics']['widget_elementor'] : '0' ?>
                            </span>
                                <div>
                                    <h3 class="label"><?php _e("Clicks", "spoki") ?></h3>
                                    <p class="subtitle">
                                        <small>
											<?php _e("Total clicks since the activation date", "spoki") ?>.
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
                <div class="spoki-card w-50 w-xs-100">
                    <div class="spoki-card-header">
                        <div class="d-flex align-items-center">
                            <h2 class="title">
								<?php _e("Shortcode Buttons", "spoki") ?>
                                <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
                            </h2>
                        </div>
                        <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=other_buttons">
                            <button type="button" class="button">
								<?php _e('Settings', "spoki") ?>
                            </button>
                        </a>
                    </div>
                    <div class="spoki-card-body">
                        <div class="spoki-statistic">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['widget_shortcode']) ? $account_info['statistics']['widget_shortcode'] : '0' ?>
                            </span>
                            <div>
                                <h3 class="label"><?php _e("Clicks", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total clicks since the activation date", "spoki") ?>.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-direction-xs-column">
                <div class="spoki-card w-50 w-xs-100">
                    <div class="spoki-card-header">
                        <div class="d-flex align-items-center">
                            <h2 class="title">
								<?php _e("Shop Page Button", "spoki") ?>
                                <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
                            </h2>
                        </div>
						<?php if (!$has_product_item_listing_button): ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=product_item">
                                <button type="button" class="button button-primary">
									<?php _e('Customize', "spoki") ?>
                                </button>
                            </a>
						<?php else: ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=product_item">
                                <button type="button" class="button">
									<?php _e('Settings', "spoki") ?>
                                </button>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="spoki-card-body">
                        <div class="spoki-statistic <?php if (!$has_product_item_listing_button) echo 'disabled' ?>">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['widget_woo_shop']) ? $account_info['statistics']['widget_woo_shop'] : '0' ?>
                            </span>
                            <div>
                                <h3 class="label"><?php _e("Clicks", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total clicks since the activation date", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (!$has_product_item_listing_button): ?>
                                    <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=product_item">
                                        <button type="button" class="button button-link">
											<?php _e('Customize', "spoki") ?>
                                        </button>
                                    </a>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="spoki-card w-50 w-xs-100">
                    <div class="spoki-card-header">
                        <div class="d-flex align-items-center">
                            <h2 class="title">
								<?php _e("Product Page Button", "spoki") ?>
                                <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
                            </h2>
                        </div>
						<?php if (!$has_single_product_button): ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=single_product">
                                <button type="button" class="button button-primary">
									<?php _e('Customize', "spoki") ?>
                                </button>
                            </a>
						<?php else: ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=single_product">
                                <button type="button" class="button">
									<?php _e('Settings', "spoki") ?>
                                </button>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="spoki-card-body">
                        <div class="spoki-statistic <?php if (!$has_single_product_button) echo 'disabled' ?>">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['widget_woo_item']) ? $account_info['statistics']['widget_woo_item'] : '0' ?>
                            </span>
                            <div>
                                <h3 class="label"><?php _e("Clicks", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total clicks since the activation date", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (!$has_single_product_button): ?>
                                    <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=single_product">
                                        <button type="button" class="button button-link">
											<?php _e('Customize', "spoki") ?>
                                        </button>
                                    </a>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="spoki-card w-50 w-xs-100">
                    <div class="spoki-card-header">
                        <div class="d-flex align-items-center">
                            <h2 class="title">
								<?php _e("Cart Page Button", "spoki") ?>
                                <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
                            </h2>
                        </div>
						<?php if (!$has_cart_button): ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=cart">
                                <button type="button" class="button button-primary">
									<?php _e('Customize', "spoki") ?>
                                </button>
                            </a>
						<?php else: ?>
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=cart">
                                <button type="button" class="button">
									<?php _e('Settings', "spoki") ?>
                                </button>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="spoki-card-body">
                        <div class="spoki-statistic <?php if (!$has_cart_button) echo 'disabled' ?>">
                            <span class="coins">
                                <?php echo isset($account_info['statistics']['widget_woo_cart']) ? $account_info['statistics']['widget_woo_cart'] : '0' ?>
                            </span>
                            <div>
                                <h3 class="label"><?php _e("Clicks", "spoki") ?></h3>
                                <p class="subtitle">
                                    <small>
										<?php _e("Total clicks since the activation date", "spoki") ?>.
                                    </small>
                                </p>
								<?php if (!$has_cart_button): ?>
                                    <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons&section=cart">
                                        <button type="button" class="button button-link">
											<?php _e('Customize', "spoki") ?>
                                        </button>
                                    </a>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none">
			<?php submit_button(null, 'primary', 'submit-statistics') ?>
        </div>
	<?php
	endif;
endif;
?>
