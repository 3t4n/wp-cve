<?php

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

?>

<fieldset>
    <p class="form-row form-row-first">
        <label for="card_number">
            <?php esc_html_e(  'Número do Cartão', 'appmax-woocommerce' ); ?>
            <span class="required">*</span>
        </label>
        <input type="tel" autocomplete="off" maxlength="19" class="input-text" id="card_number" name="card_number" placeholder="<?php esc_html_e( 'Número do Cartão', 'appmax-woocommerce' ); ?>" style="font-size: 1.0em; padding: 8px;">
    </p>

    <p class="form-row form-row-last">
        <label for="card_name">
            <?php esc_html_e(  'Nome do Titular', 'appmax-woocommerce' ); ?>
            <span class="required">*</span>
        </label>
        <input type="text" autocomplete="off" class="input-text" id="card_name" name="card_name" placeholder="<?php esc_html_e( 'Nome do Titular', 'appmax-woocommerce' ); ?>" style="font-size: 1.0em; padding: 8px;">
    </p>

    <div class="clear"></div>

    <p class="form-row form-row-first">
        <label for="card_cpf_credit_card">
            <?php esc_html_e(  'CPF do Titular', 'appmax-woocommerce' ); ?>
            <span class="required">*</span>
        </label>
        <input type="tel" autocomplete="off" maxlength="14" class="input-text" id="card_cpf" name="card_cpf" placeholder="<?php esc_html_e( 'CPF do Titular', 'appmax-woocommerce' ); ?>" style="font-size: 1.0em; padding: 8px;">
    </p>

    <p class="form-row form-row-last">
        <label for="card_security_code">
            <?php esc_html_e(  'Cód de Segurança', 'appmax-woocommerce' ); ?>
            <span class="required">*</span>
        </label>
        <input type="text" autocomplete="off" class="input-text" id="card_security_code" name="card_security_code" maxlength="4" placeholder="<?php esc_html_e( '3 ou 4 dígitos', 'appmax-woocommerce' ); ?>" style="font-size: 1.0em; padding: 8px;">
    </p>

    <div class="clear"></div>

    <p class="form-row form-row-first">
        <label for="month_expiration_date">
            <?php esc_html_e( 'Mês de Expiração', 'appmax-woocommerce' ); ?>
            <span class="required">*</span>
        </label>
        <select id="card_month" name="card_month" style="font-size: 1.0em; padding: 8px; width: 100%;">
            <?php echo $display_options_card_expiration_month; ?>
        </select>
    </p>

    <p class="form-row form-row-last">
        <label for="year_expiration_date">
            <?php esc_html_e( 'Ano de Expiração', 'appmax-woocommerce' ); ?>
            <span class="required">*</span>
        </label>
        <select id="card_year" name="card_year" style="font-size: 1.0em; padding: 8px; width: 100%;">
            <?php echo $display_options_card_expiration_year; ?>
        </select>
    </p>

    <div class="clear"></div>

    <p class="form-row form-row-wide">
        <label for="installments">
            <?php esc_html_e(  'Parcelamento', 'appmax-woocommerce' ); ?>
            <span class="required">*</span>
        </label>
        <select id="installments" name="installments" style="font-size: 1.0em; padding: 8px; width: 100%;">
            <?php echo $display_installments; ?>
        </select>
    </p>

    <div class="clear"></div>

</fieldset>

<?php echo $display_script_payment; ?>