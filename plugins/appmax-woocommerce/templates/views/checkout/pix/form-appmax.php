<?php

if (!defined("ABSPATH")) {
    exit;
}

?>

<fieldset>
    <p>
        <?php esc_html_e( "1. Clique no botão abaixo para gerar o Qr-Code", 'appmax-woocommerce' ); ?><br/>
        <?php esc_html_e( "2. Abra o aplicativo do seu banco ou instituição financeira e entre na opção Pix", 'appmax-woocommerce' ); ?><br/>
        <?php esc_html_e( "3. Na opção Pix Copia e Cola, insira o código copiado no passo anterior", 'appmax-woocommerce' ); ?><br/>
    </p>
    <div class="clear"></div>

    <p class="form-row form-row-first">
        <br>
        <label for="card_cpf_pix">
            <?php esc_html_e('CPF', 'appmax-woocommerce'); ?>
            <span class="required">*</span>
        </label>
        <input type="text" autocomplete="off" maxlength="14" class="input-text" id="cpf_pix" name="cpf_pix" placeholder="<?php esc_html_e('CPF', 'appmax-woocommerce'); ?>" style="font-size: 1.0em; padding: 8px;">
        <br><br>
    </p>

    <div class="clear"></div>

</fieldset>

<?php echo $display_script_payment; ?>