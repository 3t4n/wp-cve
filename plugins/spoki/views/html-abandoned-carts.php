<?php
$account_info = $this->options['account_info'] ?? ['plan' => []];
$has_wc = spoki_has_woocommerce();
$is_current_tab = $GLOBALS['current_tab'] == 'abandoned-carts';
$has_abandoned_carts = isset($this->options['abandoned_carts']['enable_tracking']) && $this->options['abandoned_carts']['enable_tracking'] == 1;
$sections = ['settings', 'resend', 'perform-resend'];
$current_section = isset($_GET['section']) && in_array($_GET['section'], $sections) ? $_GET['section'] : $sections[0];
$resend_days = isset($_GET['resend']) ? intval($_GET['resend']) : null;

$statistics = [
	["days" => 60, "less_than" => null, "count" => 0, "revenue" => 0, "order" => 4],
	["days" => 30, "less_than" => 60, "count" => 0, "revenue" => 0, "order" => 3],
	["days" => 10, "less_than" => 30, "count" => 0, "revenue" => 0, "order" => 2],
	["days" => 5, "less_than" => 10, "count" => 0, "revenue" => 0, "order" => 1],
	["days" => 0, "less_than" => null, "count" => 0, "revenue" => 0, "order" => 0],
];

if ($is_current_tab) {
	$has_spoki_keys = (isset($this->options['secret']) && trim($this->options['secret']) != '') && (isset($this->options['delivery_url']) && trim($this->options['delivery_url']) != '');
	if ($has_wc && $has_spoki_keys && !isset($account_info['plan']['name'])) {
		$account_info = Spoki()->fetch_account_info();
	}
}
$is_pro = isset($account_info['plan']['is_pro']) && $account_info['plan']['is_pro'] == true;
$is_credit_based = isset($account_info['plan']['is_credit_based']) && $account_info['plan']['is_credit_based'] == true;
?>

<div <?php if (!$is_current_tab) echo 'style="display:none"' ?>>
    <ul class="subsubsub">
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=settings" <?php if ($current_section == 'settings') echo "class='current'" ?>><?php _e('Settings', "spoki") ?></a> |</li>
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=resend" <?php if ($current_section == 'resend') echo "class='current'" ?>><?php _e('Resend Notification', "spoki") ?></a></li>
    </ul>
    <br/>
    <br/>

    <!-- Settings Section -->
    <div <?php if ($current_section != 'settings') echo "style='display:none'" ?>>
        <h2 style="display: flex; align-items: center">
			<?php _e('Abandoned Carts', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=abandoned-carts-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p>
			<?php _e('<b>Send abandoned cart WhatsApp messages</b> to customers and reduce dropout rates.', "spoki") ?>
        </p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/abandoned-carts.png' ?>"/>

        <fieldset <?php if ($is_current_tab && !$has_wc) : ?>disabled<?php endif ?>>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][enable_tracking]">
							<?php _e('Enable Tracking', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="enable_tracking"
                               name="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][enable_tracking]"
                               value="1" <?php if (isset($this->options['abandoned_carts']['enable_tracking'])) echo checked(1, $this->options['abandoned_carts']['enable_tracking'], false) ?>>

                        <label for="enable_tracking"><?php _e('Start capturing abandoned carts', "spoki") ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][waiting_minutes]">
							<?php _e('Trigger After', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <select id="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][waiting_minutes]" name="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][waiting_minutes]" class="regular-text">
							<?php $current = isset($this->options['abandoned_carts']['waiting_minutes']) ? $this->options['abandoned_carts']['waiting_minutes'] : 15;
							foreach ([5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60] as $minutes): ?>
                                <option value="<?php _e($minutes) ?>" <?php echo ($current == $minutes) ? 'selected' : '' ?>>
									<?php _e($minutes) ?><?php _e('minutes', "spoki") ?>
                                </option>
							<?php endforeach;
							foreach ([2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 12, 16, 20, 24] as $hours): ?>
                                <option value="<?php _e($hours * 60) ?>" <?php echo ($current == $hours * 60) ? 'selected' : '' ?>>
									<?php _e($hours) ?><?php _e('hours', "spoki") ?>
                                </option>
							<?php endforeach;
							foreach ([2, 3, 4, 5] as $day): ?>
                            <option value="<?php _e($day * 24 * 60) ?>" <?php echo ($current == $day * 24 * 60) ? 'selected' : '' ?>>
								<?php _e($day) ?><?php _e('days', "spoki") ?>
								<?php endforeach; ?>
                        </select>
                        <label for="enable_tracking"></label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('The cart will be considered abandoned if the order is not completed within the selected time.', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][notify_to_admin]">
							<?php _e('Notify recovery to admin', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="notify_to_admin"
                               name="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][notify_to_admin]"
                               value="1" <?php if (isset($this->options['abandoned_carts']['notify_to_admin'])) echo checked(1, $this->options['abandoned_carts']['notify_to_admin'], false) ?>>

                        <label for="notify_to_admin">
							<?php
							/* translators: %1$s: Telephone */
							printf(__('Send a <b>cart recovered notification</b> to your WhatsApp Telephone <small>(%1$s)</small>', "spoki"), Spoki()->shop['telephone'])
							?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[custom_checkout_url]">
							<?php _e('Custom Checkout URL', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" class="regular-text"
                               name="<?php echo SPOKI_OPTIONS ?>[custom_checkout_url]"
                               placeholder="<?php if ($has_wc) echo wc_get_page_permalink('checkout') ?>"
                               value="<?php if (isset($this->options['custom_checkout_url']) && trim($this->options['custom_checkout_url']) != '') echo $this->options['custom_checkout_url'] ?>"
                        />
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Change only if needed (ex. if you have a custom checkout flow).', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[custom_checkout_session_id_param]">
							<?php _e('Custom Checkout Session ID Param', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" class="regular-text"
                               name="<?php echo SPOKI_OPTIONS ?>[custom_checkout_session_id_param]"
                               placeholder="session_id"
                               value="<?php if (isset($this->options['custom_checkout_session_id_param']) && trim($this->options['custom_checkout_session_id_param']) != '') echo $this->options['custom_checkout_session_id_param'] ?>"
                        />
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Change only if needed (ex. if you have a custom checkout flow).', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <div style="display: flex; align-items: center">
                            <label for="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][trigger_webhook]" style="white-space: nowrap">
								<?php _e('Enable Webhook', "spoki") ?>
                            </label>
							<?php if (!$is_pro): ?>
                                <a href="<?php print(Spoki()->get_pro_plan_link()) ?>" target="_blank" style="text-decoration: none">
                                    <div class="spoki-badge bg-spoki-secondary"><?php _e('Spoki PRO', "spoki") ?></div>
                                </a>
							<?php endif ?>
                        </div>
                    </th>
                    <td>
                        <input type="checkbox" id="trigger_webhook" disabled
                               name="<?php echo SPOKI_OPTIONS ?>[abandoned_carts][trigger_webhook]"
                               value="1" <?php if (!$is_pro && isset($this->options['abandoned_carts']['trigger_webhook'])) echo checked(1, $this->options['abandoned_carts']['trigger_webhook'], false) ?>>

                        <label for="trigger_webhook">
							<?php _e(' Allows you to trigger webhook automatically upon cart abandonment and recovery', "spoki") ?>
                        </label>
                    </td>
                </tr>
            </table>
			<?php if ($has_wc) : submit_button(null, 'primary', 'submit-templates'); else : ?>
                <p>
					<?php _e("Install and activate the <strong>WooCommerce</strong> plugin to enable the Spoki features for WooCommerce.", "spoki") ?>
                </p>
			<?php endif ?>
        </fieldset>
    </div>

    <!-- Resend Section -->
	<?php if ($is_current_tab && $current_section == 'resend'): ?>
        <h2 style="display: flex; align-items: center">
			<?php _e('Resend Abandoned Cart Notification', "spoki") ?>
        </h2>
        <p>
			<?php _e('<b>Resend</b> the abandoned cart WhatsApp message <b>to those who have not converted</b> their cart yet.', "spoki") ?>
        </p>
		<?php if ($has_abandoned_carts): ?>
            <h3 style="font-weight: normal">
				<?php _e('⚠️ <b>Attention!</b> You can resend the abandoned cart notification <i>only once</i> to preserve the quality of your WhatsApp conversations and avoid massive SPAM.', "spoki") ?>
            </h3>
			<?php
			$spoki_abandoned_carts = Spoki_Abandoned_Carts::instance();
			$recontactable_carts = $spoki_abandoned_carts->get_recontactable_carts();
			$total_revenue = 0;
			$available_coins = $account_info['available_coins'] ?? 0;
			$upgrade_url = Spoki()->get_pro_plan_link();

			foreach ($recontactable_carts as $cart) {
				$checkout = $spoki_abandoned_carts->get_checkout_details($cart->session_id);
				$diff = date_diff(date_create($checkout->time), date_create());
				if ($diff->d >= $statistics[0]["days"]) {
					$statistics[0]["count"] += 1;
					$statistics[0]["revenue"] += $checkout->cart_total;
				} elseif ($diff->d >= $statistics[1]["days"]) {
					$statistics[1]["count"] += 1;
					$statistics[1]["revenue"] += $checkout->cart_total;
				} elseif ($diff->d >= $statistics[2]["days"]) {
					$statistics[2]["count"] += 1;
					$statistics[2]["revenue"] += $checkout->cart_total;
				} else {
					$statistics[3]["count"] += 1;
					$statistics[3]["revenue"] += $checkout->cart_total;
				}
				$total_revenue += $checkout->cart_total;
			}
			?>
            <div class="spoki-card">
                <div class="spoki-card-header">
                    <div class="d-flex align-items-center">
                        <h2 class="title">
							<?php _e("All Recontactable Users", "spoki") ?>
                        </h2>
                    </div>
                </div>
                <div class="spoki-card-body d-flex flex-direction-xs-column">
                    <div class="spoki-statistic">
                            <span class="coins">
                                <?php echo count($recontactable_carts) ?>
                            </span>
                        <div>
                            <h3 class="label"><?php _e("Total Count", "spoki") ?></h3>
                            <p class="subtitle">
                                <small>
									<?php _e("People you can send the abandoned cart message to for the second time", "spoki") ?>.
                                </small>
                            </p>
                        </div>
                    </div>
                    <div class="spoki-statistic">
                            <span class="coins small">
                                &euro;<?php echo esc_attr(number_format_i18n($total_revenue, 2)); ?>
                            </span>
                        <div>
                            <h3 class="label"><?php _e("Total Revenue", "spoki") ?></h3>
                            <p class="subtitle">
                                <small>
									<?php _e("Total revenue of abandoned carts that you can recontact", "spoki") ?>.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-direction-column">
				<?php foreach ($statistics as $stat): ?>
                    <div class="spoki-card" style="order: <?php _e($stat["order"]) ?>">
                        <div class="spoki-card-header">
                            <div class="d-flex align-items-baseline">
                                <h2 class="title">
									<?php
									if ($stat["days"] == 0) {
										_e("Less then 5 days ago", "spoki");
									} else {
										/* translators: %1$s: Count. */
										printf(__('%1$s+ days ago', "spoki"), $stat["days"]);
									}
									?>
                                </h2>

								<?php
								if ($stat["less_than"]) {
									echo "<small style='margin-left: 5px'>(";
									/* translators: %1$s: LessThan. */
									printf(__('less than %1$s days ago', "spoki"), $stat["less_than"]);
									echo ")</small>";
								}
								?>
                            </div>
                            <span class="d-flex align-items-center">
                            <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=perform-resend&resend=<?php _e($stat["days"]) ?>">
                                <button type="button" class="button button-primary" <?php if ($stat["count"] == 0 || (!$is_credit_based && $available_coins < $stat["count"])) echo "disabled" ?>>
                                    <?php _e('Resend Abandoned Cart Notification', "spoki") ?>
                                </button>
                            </a>
                            <?php if (!$is_credit_based && $available_coins < $stat["count"]): ?>
                                <p style="margin: 0 1rem; color: red"><?php _e('You don\'t have enough available contacts.', "spoki") ?></p>
                                <?php if (!$is_pro): ?>
                                    <a href="<?php print($upgrade_url) ?>" target="_blank">
                                        <button type="button" class="button button-primary bg-spoki" style="border: none;">
                                            ⇡ <?php _e('Upgrade', "spoki") ?>
                                        </button>
                                    </a>
								<?php endif; ?>
							<?php endif; ?>
                        </span>
                        </div>
                        <div class="spoki-card-body d-flex flex-direction-xs-column">
                            <div class="spoki-statistic">
                                <span class="coins">
                                    <?php echo $stat["count"] ?>
                                </span>
                                <div>
                                    <h3 class="label"><?php _e("Count", "spoki") ?></h3>
                                    <p class="subtitle">
                                        <small>
											<?php
											/* translators: %1$s: Count. */
											printf(__('Count of carts abandoned for more than %1$s days that you can contact again.', "spoki"), $stat["days"])
											?>
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="spoki-statistic">
                                <span class="coins small">
                                    &euro;<?php echo esc_attr(number_format_i18n($stat['revenue'], 2)); ?>
                                </span>
                                <div>
                                    <h3 class="label"><?php _e("Revenue", "spoki") ?></h3>
                                    <p class="subtitle">
                                        <small>
											<?php
											if ($stat["days"] == 0) {
												_e("Revenue of carts abandoned for less than 5 days that you can recontact.", "spoki");
											} else {
												/* translators: %1$s: Count. */
												printf(__('Revenue of carts abandoned for more than %1$s days that you can recontact.', "spoki"), $stat["days"]);
											}
											?>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php else: ?>
            <h2>
				<?php _e('Enable the Abandoned Cart Tracking before continue.', "spoki") ?>
                <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=settings">
					<?php _e('Abandoned Carts', "spoki") ?>
                </a>
            </h2>
		<?php endif; ?>
	<?php endif; ?>

    <!-- Perform Resend Section -->
	<?php if ($is_current_tab && $current_section == 'perform-resend'): ?>
		<?php
		$available_coins = $account_info['available_coins'] ?? 0;
		$upgrade_url = Spoki()->get_pro_plan_link();
		$spoki_abandoned_carts = Spoki_Abandoned_Carts::instance();
		$recontactable_carts = $spoki_abandoned_carts->get_recontactable_carts();
		$session_ids = [];

		foreach ($recontactable_carts as $cart) {
			$diff = date_diff(date_create($cart->time), date_create());
			if ($diff->d >= $statistics[0]["days"]) {
				if ($statistics[0]["days"] == $resend_days) {
					array_push($session_ids, $cart->session_id);
				}
			} elseif ($diff->d >= $statistics[1]["days"]) {
				if ($statistics[1]["days"] == $resend_days) {
					array_push($session_ids, $cart->session_id);
				}
			} elseif ($diff->d >= $statistics[2]["days"]) {
				if ($statistics[2]["days"] == $resend_days) {
					array_push($session_ids, $cart->session_id);
				}
			} else {
				if ($statistics[3]["days"] == $resend_days) {
					array_push($session_ids, $cart->session_id);
				}
			}
		}
		?>
        <h2>
			<?php _e('Resending Abandoned Cart Notifications...', "spoki") ?>
        </h2>
        <p>
			<?php
			/* translators: %1$s: Count */
			printf(__('Total users to contact: %1$s', "spoki"), count($session_ids));
			?>
        </p>
		<?php if (!$is_credit_based && $available_coins < count($session_ids)): ?>
            <div class="d-flex">
                <p style="margin: 0 1rem; color: red"><?php _e('You don\'t have enough available contacts.', "spoki") ?></p>
				<?php if (!$is_pro): ?>
                    <a href="<?php print($upgrade_url) ?>" target="_blank">
                        <button type="button" class="button button-primary bg-spoki" style="border: none;">
                            ⇡ <?php _e('Upgrade', "spoki") ?>
                        </button>
                    </a>
				<?php endif; ?>
            </div>
		<?php else: ?>
			<?php if (count($session_ids) > 0): ?>
                <h4>
					<?php _e("Please don't close the page before the process finish.", "spoki") ?>
                </h4>
                <table class="form-table">
                    <tr>
                        <th>#</th>
                        <th><?php _e("Recontanted Status") ?></th>
                    </tr>
					<?php
					$i = 1;
					foreach ($session_ids as $session_id):
						?>
                        <tr>
                            <td>#<?php _e($i) ?></td>
                            <td><?php echo $spoki_abandoned_carts->spoki_resend_ca_notification($session_id) ? '✅' : '❌' ?></td>
                        </tr>
						<?php
						$i++;
					endforeach;
					?>
                </table>
			<?php endif; ?>
		<?php endif; ?>
        <a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=resend">
            <button type="button" class="button button-primary">
				<?php _e("Finish") ?>
            </button>
        </a>
	<?php endif; ?>
</div>

