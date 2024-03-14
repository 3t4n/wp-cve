<?php
/**
 * Template for displaying the sign-up form
 *
 * This template can be overridden by copying it to yourtheme/fdsus/sign-up-form.php
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.2 (plugin version)
 * @version     1.1.0 (template file version)
 */

/** @var array $args */
/** @var FDSUS\Model\Sheet $sheet */
/** @var string $signup_titles_str */
/** @var int $task_id */
/** @var array $initial */
/** @var string $submit_button_text */
/** @var string $go_back_url */
extract($args);

dlssus_get_template_part((fdsus_is_pro() ? 'pro/' : '') . 'fdsus/sign-up-form-header', null, true, $args);
?>

<form id="fdsus-signup-form" name="dls-sus-signup-form" method="post" action="<?php echo esc_url(fdsus_current_url()); ?>" class="dls-sus-signup-form">
    <p>
        <label for="signup_firstname" class="signup_firstname">
            <?php esc_html_e('First Name', 'fdsus'); ?>
            <span class="dls-sus-required-icon">*</span>
        </label>
        <input type="text" id="signup_firstname" class="signup_firstname" name="signup_firstname"
               maxlength="100" required aria-required="true" autocomplete="given-name"
               value="<?php echo esc_attr($initial['firstname']); ?>"/>
        <?php echo $args['multi_tag']; ?>
    </p>

    <p>
        <label for="signup_lastname" class="signup_lastname">
            <?php esc_html_e('Last Name', 'fdsus'); ?>
            <span class="dls-sus-required-icon">*</span>
        </label>
        <input type="text" id="signup_lastname" class="signup_lastname" name="signup_lastname"
               maxlength="100" required aria-required="true" autocomplete="family-name"
               value="<?php echo esc_attr($initial['lastname']); ?>"/>
    </p>

    <?php if ($sheet->showEmail()): ?>
        <p>
            <label for="signup_email" class="signup_email">
                <?php esc_html_e('E-mail', 'fdsus'); ?>
                <span class="dls-sus-required-icon">*</span>
            </label>
            <input type="email" id="signup_email" class="signup_email" name="signup_email"
                   maxlength="100" required aria-required="true" autocomplete="email"
                   value="<?php echo esc_attr($initial['email']); ?>"/>
        </p>

        <?php if (get_option('dls_sus_deactivate_email_validation') !== 'true') : ?>
            <div id="dls-sus-mailcheck-suggestion"></div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($sheet->showPhone()): ?>
        <p>
            <label for="signup_phone" class="signup_phone">
                <?php esc_html_e('Phone', 'fdsus'); ?>
                <?php if ($sheet->isPhoneRequired()): ?>
                    <span class="dls-sus-required-icon">*</span>
                <?php endif; ?>
            </label>
            <input type="text" id="signup_phone" class="signup_phone" name="signup_phone"
                   maxlength="50" <?php if ($sheet->isPhoneRequired()) echo 'required aria-required="true"'; ?>
                   autocomplete="tel-national"
                   value="<?php echo esc_attr($initial['phone']); ?>"/>
        </p>
    <?php endif; ?>

    <?php if ($sheet->showAddress()): ?>
        <p>
            <label for="signup_address" class="signup_address">
                <?php esc_html_e('Address', 'fdsus'); ?>
                <?php if ($sheet->isAddressRequired()): ?>
                    <span class="dls-sus-required-icon">*</span>
                <?php endif; ?>
            </label>
            <input type="text" id="signup_address" class="signup_address" name="signup_address"
                   maxlength="200" <?php if ($sheet->isAddressRequired()) echo 'required aria-required="true"'; ?>
                   autocomplete="street-address"
                   value="<?php echo esc_attr($initial['address']); ?>"/>
        </p>
        <p class="dls-sus-city">
            <label for="signup_city" class="signup_city">
                <?php esc_html_e('City', 'fdsus'); ?>
                <?php if ($sheet->isAddressRequired()): ?>
                    <span class="dls-sus-required-icon">*</span>
                <?php endif; ?>
            </label>
            <input type="text" id="signup_city" class="signup_city" name="signup_city"
                   maxlength="50" <?php if ($sheet->isAddressRequired()) echo 'required aria-required="true"'; ?>
                   autocomplete="address-level2"
                   value="<?php echo esc_attr($initial['city']); ?>"/>
        </p>
        <p class="dls-sus-state">
            <label for="signup_state" class="signup_state">
                <?php esc_html_e('State', 'fdsus'); ?>
                <?php if ($sheet->isAddressRequired()): ?>
                    <span class="dls-sus-required-icon">*</span>
                <?php endif; ?>
            </label>
            <select id="signup_state" class="signup_state" name="signup_state" <?php if ($sheet->isAddressRequired()) echo 'required aria-required="true"'; ?> autocomplete="address-level1">
                <option value=""></option>
                <?php
                foreach ($args['states'] as $abbr => $name) {
                    $selected = ($initial['state'] == $abbr) ? ' selected="selected"' : null;
                    echo sprintf( '<option value="%s"%s>%s</option>', $abbr, $selected, $abbr);
                }
                ?>
            </select>
        </p>
        <p class="dls-sus-zip">
            <label for="signup_zip" class="signup_zip">
                <?php esc_html_e('Zip', 'fdsus'); ?>
                <?php if ($sheet->isAddressRequired()): ?>
                    <span class="dls-sus-required-icon">*</span>
                <?php endif; ?>
            </label>
            <input type="text" id="signup_zip" class="signup_zip"
                   name="signup_zip" maxlength="10" autocomplete="postal-code"
                   value="<?php echo esc_attr($initial['zip']) ?>"/>
        </p>
    <?php endif; ?>

    <?php
    fdsus_the_signup_form_additional_fields($sheet, $initial);

    fdsus_captcha();

    fdsus_the_signup_form_last_fields($sheet, $args);
    ?>

    <p class="submit">
        <?php if (!fdsus_is_honeypot_disabled()): ?>
            <input type="hidden" name="website" id="dlssus-website" value="" />
        <?php endif; ?>
        <input type="hidden" name="dlssus_submitted" value="<?php echo esc_attr($task_id); ?>" />
        <input type="submit" name="Submit" <?php echo fdsus_signup_form_button_attributes(); ?>
               value="<?php echo esc_html($submit_button_text); ?>"/>
        <?php if (!is_admin() && !empty($go_back_url)): ?>
            <?php esc_html_e('or', 'fdsus'); ?>
            <a href="<?php echo esc_url($go_back_url); ?>" class="dls-sus-backlink-from-task">
                <?php esc_html_e('&laquo; go back to the Sign-Up Sheet', 'fdsus'); ?>
            </a>
        <?php endif; ?>
    </p>

    <p><span class="dls-sus-required-icon">*</span> = <?php esc_html_e('required', 'fdsus'); ?></p>
<?php wp_nonce_field('fdsus_signup_submit', 'signup_nonce') ?>
</form><!-- .dls-sus-signup-form -->
