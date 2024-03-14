<?php
$has_wc = spoki_has_woocommerce();
$has_phone = isset($this->options['telephone']) && $this->options['telephone'] != '';
$has_spoki_keys = (isset($this->options['secret']) && trim($this->options['secret']) != '') && (isset($this->options['delivery_url']) && trim($this->options['delivery_url']) != '');

/** Spoki Without WooCommerce Onboarding */
if (!$has_wc && !$has_phone) : ?>
    <div class="spoki-onboarding">
        <h2><?php _e("Welcome on Spoki!", "spoki") ?></h2>
        <p><?php _e("Insert your WhatsApp phone number and start using <b>Free WhatsApp buttons</b> on your website!", "spoki") ?></p>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[onboarding][telephone]">
						<?php _e('Your WhatsApp Telephone', "spoki") ?>
                    </label>
                </th>
                <td>
                    <div class="spoki-phone-container">
                        <input type="text" class="regular-text spoki-phone-prefix"
                               name="<?php echo SPOKI_OPTIONS ?>[onboarding][prefix]"
                               required
                               value="<?php echo (isset($this->options['onboarding']['prefix']) && trim($this->options['onboarding']['prefix']) != '') ? $this->options['onboarding']['prefix'] : (isset($this->options['prefix']) ? $this->options['prefix'] : '') ?>"
                               placeholder="+39"
                        />
                        <input type="tel" class="regular-text" required
                               name="<?php echo SPOKI_OPTIONS ?>[onboarding][telephone]"
                               placeholder="3331234567"
                               value="<?php echo (isset($this->options['onboarding']['telephone']) && $this->options['onboarding']['telephone'] != '') ? ($this->options['onboarding']['telephone']) : (isset($this->options['telephone']) ? $this->options['telephone'] : '') ?>"
                        />
                    </div>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e("Your customers will send messages to this WhatsApp number.", "spoki") ?>
                    </p>
                </td>
            </tr>
        </table>
        <input type="hidden" name="<?php echo SPOKI_OPTIONS ?>[onboarding][without_wc]" value="1">
		<?php submit_button() ?>
    </div>
<?php endif;

/** Spoki WooCommerce Onboarding */
if ($has_wc && !$has_spoki_keys) : ?>
    <div class="spoki-onboarding">
        <h2><?php _e("Welcome on Spoki!", "spoki") ?></h2>
        <p><?php _e("Confirm your shop info and start sending <b>FREE WhatsApp Notifications</b> to your customers about Orders and Abandoned Carts!", "spoki") ?></p>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[onboarding][email]">
						<?php _e('Email Spoki', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="email" class="regular-text" required
                           name="<?php echo SPOKI_OPTIONS ?>[onboarding][email]"
                           placeholder="<?php echo Spoki()->shop['email'] ?>"
                           value="<?php if (isset($this->options['onboarding']['email']) && $this->options['onboarding']['email'] !== '') {
							   echo esc_html($this->options['onboarding']['email']);
						   } else {
							   echo Spoki()->shop['email'];
						   } ?>"
                    />
                    <p class="description">
                        <b><?php _e("Don't you have a Spoki account?", "spoki") ?></b><br/><a href='https://spoki.app/register' target='_blank'><?php _e("Try the Sandbox", "spoki") ?></a> <?php _e("or just insert your best email to enable Spoki Free (10 free contacts / month).", "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[onboarding][telephone]">
						<?php _e('Your WhatsApp Telephone', "spoki") ?>
                    </label>
                </th>
                <td>
                    <div class="spoki-phone-container">
                        <input type="text" class="regular-text spoki-phone-prefix"
                               name="<?php echo SPOKI_OPTIONS ?>[onboarding][prefix]"
                               required
                               value="<?php echo (isset($this->options['onboarding']['prefix']) && trim($this->options['onboarding']['prefix']) != '') ? $this->options['onboarding']['prefix'] : (isset($this->options['prefix']) ? $this->options['prefix'] : '') ?>"
                               placeholder="+39"
                        />
                        <input type="tel" class="regular-text" required
                               name="<?php echo SPOKI_OPTIONS ?>[onboarding][telephone]"
                               placeholder="3331234567"
                               value="<?php echo (isset($this->options['onboarding']['telephone']) && $this->options['onboarding']['telephone'] != '') ? ($this->options['onboarding']['telephone']) : (isset($this->options['telephone']) ? $this->options['telephone'] : '') ?>"
                        />
                    </div>

                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e("Your customers will send messages to this WhatsApp number.", "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[onboarding][shop_name]">
						<?php _e('Shop Name', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="text" class="regular-text" required
                           name="<?php echo SPOKI_OPTIONS ?>[onboarding][shop_name]"
                           placeholder="<?php echo Spoki()->shop['name'] ?>"
                           value="<?php if (isset($this->options['onboarding']['shop_name']) && $this->options['onboarding']['shop_name'] !== '') {
							   echo esc_html($this->options['onboarding']['shop_name']);
						   } else {
							   echo Spoki()->shop['name'];
						   } ?>"
                    />
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e("Insert the name of your shop.", "spoki") ?>
                    </p>
                </td>
            </tr>
        </table>
        <br/>
        <div>
            <input type="checkbox" id="terms_check" required
                   name="<?php echo SPOKI_OPTIONS ?>[onboarding][terms_check]"
                   value="1" <?php if (isset($this->options['onboarding']['terms_check'])) echo checked(1, $this->options['onboarding']['terms_check'], false) ?>>
            <label for="terms_check">
				<?php _e("I accept", "spoki") ?>
                <a href="https://app.spoki.it/static/terms_conditions_spoki_flex.pdf" target="_blank"><?php _e('general terms and conditions', "spoki") ?></a>
				<?php _e("for using the Spoki service and the", "spoki") ?>
                <a href="https://app.spoki.it/static/privacy.pdf" target="_blank"><?php _e('privacy policy', "spoki") ?></a>.
            </label>
        </div>
        <input type="hidden" name="<?php echo SPOKI_OPTIONS ?>[onboarding][with_wc]" value="1">
        <input type="hidden" name="spoki_enable_url" value="<?php echo Spoki()->api_enable_flex ?>">
        <input type="hidden" name="spoki_plan_url" value="<?php echo Spoki()->api_plan ?>">
        <input type="hidden" name="<?php echo SPOKI_OPTIONS ?>[onboarding][spoki_onboarding_delivery_url]" value="">
        <input type="hidden" name="<?php echo SPOKI_OPTIONS ?>[onboarding][spoki_onboarding_secret]" value="">
        <input type="hidden" name="<?php echo SPOKI_OPTIONS ?>[account_info][response_json]" value="">
        <div class="enable-spoki-section">
			<?php submit_button(__("Enable Spoki", "spoki"), 'primary', 'submit', true, 'enable-spoki-btn="1"') ?>
            <!--            <a href="--><?php //echo Spoki()->get_pro_plan_link() ?><!--" target="_blank">-->
            <!--				--><?php //_e("Discover the free features of Spoki Free!", "spoki") ?>
            <!--            </a>-->
        </div>
        <!--        <p class="already-have-spoki">-->
        <!--			--><?php //_e("Do you already have Spoki?", "spoki") ?>
        <!--            <a href="?page=--><?php //echo urlencode(SPOKI_PLUGIN_NAME) ?><!--&tab=settings">--><?php //_e("Insert your Spoki keys", "spoki") ?><!--</a>.-->
        <!--        </p>-->
    </div>
<?php endif;
