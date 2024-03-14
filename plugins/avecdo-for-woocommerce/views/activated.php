<?php
if (isset($mesages) && count($mesages) > 0):
    foreach ($mesages as $type => $mesage):
        avecdoEchoNotice(implode('<br>', $mesage), $type, true);
    endforeach;
endif;
$nonce = wp_create_nonce('avecdo_activation_form');
?>
<script>
    // A json map where you input language and it outputs available currencies for the language.
    let langToCurrency = <?php echo json_encode($currencies); ?>;

    // Rebuild the available currencies list, when the language is changed.

    function languageChanged(sender) {
        let value = sender.value;
        let currencySelect = document.getElementById("currenciesSelector");
        let html = "";
        
        if (langToCurrency[value] != null) {
            for (let i = 0; i < langToCurrency[value].length; i++) {
                let currencyCode = langToCurrency[value][i];
                html += "<option name='" + currencyCode + "'>" + currencyCode + "</option>";
            }
        }

        currencySelect.innerHTML = html;
    }
</script>
<?php
// end of multi currency

?>
<div class="avecdo-wrapper">
    <div class="avecdo-content">
        <div class="avecdo-spacer-l"></div>
        <?php include 'version-selector.php' ?>
        <div class="avecdo-box-notop">
            <div class="avecdo-align-left">
                <h2 class="avecdo-shop-connected"><?php echo __('Your shop is connected.', 'avecdo-for-woocommerce'); ?></h2>
            </div>
            <div class="avecdo-align-right">
                <img class="avecdo-logo" src="<?php echo plugins_url('assets/images/avecdo-logo.png', dirname(__FILE__)); ?>" alt="avecdo logo"/>
            </div>

            <div class="avecdo-inner-container">
                <form method="post" action="<?php echo admin_url('admin.php?page=avecdo&activation=true'); ?>">
                    <input type="hidden" name="avecdo_submit_reset" value="1" />
                    <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
                    <h4 class="avecdo-subheader"><?php echo __('Activation key', 'avecdo-for-woocommerce'); ?></h4>
                    <table class="avecdo-table">
                        <tr>
                            <td>
                                <input type="text" name="avecdo_activation_key" value="<?php echo $activationKey; ?>" class="avecdo-activation-key" spellcheck="false" autocomplete="off" disabled="disabled">
                            </td>
                            <td>
                                <button type="submit" class="avecdo-btn avecdo-btn-primary avecdo-s-btn"><?php echo __('Reset', 'avecdo-for-woocommerce'); ?></button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="avecdo-spacer-s"></div>
        <div class="avecdo-nav-container">
            <div class="avecdo-nav-inner">
                <div class="avecdo-nav-nobutton">
                    Settings
                </div>
            </div>
        </div>
        <div class="avecdo-box-notop">
            <div class="avecdo-spacer-s"></div>
            <div class="avecdo-inner-container">
                <form method="post" action="<?php echo admin_url('admin.php?page=avecdo&update_settings=1'); ?>">
                    <input type="hidden" name="avecdo_submit_reset" value="0" />
                    <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />

                    <?php if ($multiCurrencyEnabled) : ?>
                        <div>
                            <h4 class="avecdo-subheader">
                                Select language
                            </h4>
                            <select name="AVECDO_LANGUAGE_ID" id="languagesSelector" onchange="languageChanged(this)" class="form-control">
                                <?php foreach ($languages as $lang) : ?>
                                    <option value='<?= $lang['code'] ?>' <?php if ($avecdo_language == $lang['code']) { echo 'selected'; } ?>><?php echo $lang['native_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <h4 class="avecdo-subheader">
                                Select currency
                            </h4>
                            <select name="AVECDO_CURRENCY_ID" id="currenciesSelector" class="form-control">
                                <?php foreach ($currencies[$avecdo_language] as $currency) : ?>
                                    <option value='<?= $currency ?>' <?php if ($avecdo_currency === $currency) { echo 'selected'; } ?>><?php echo $currency; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="avecdo-panel-body">
                                Only products in chosen language will be sent to avecdo. Prices will be converted to chosen currency, if they are not set.
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="avecdo-panel">
                        <div class="avecdo-panel-head">
                            <h4 class="avecdo-subheader"><?php echo __('Select description type', 'avecdo-for-woocommerce'); ?></h4>
                        </div>
                        <div class="avecdo-panel-body">
                            <label>
                                <input type="radio" name="use_description" value="any"<?php echo ($useDescription == 'any' ? ' checked':'') ?>><?php echo __('Use first non-empty value.', 'avecdo-for-woocommerce'); ?>
                            </label>
                            <br>
                            <label>
                                <input type="radio" name="use_description" value="short"<?php echo ($useDescription == 'short' ? ' checked':'') ?>><?php echo __('Use short product description.', 'avecdo-for-woocommerce'); ?>
                            </label>
                            <br>
                            <label>
                                <input type="radio" name="use_description" value="long"<?php echo ($useDescription == 'long' ? ' checked':'') ?>><?php echo __('Use long product description.', 'avecdo-for-woocommerce'); ?>
                            </label>
                        </div>
                        <div class="avecdo-spacer-s"></div>
                        <button type="submit" class="avecdo-btn avecdo-btn-primary avecdo-btn-block"><?php echo __('Save Settings', 'avecdo-for-woocommerce'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>