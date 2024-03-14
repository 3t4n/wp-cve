<?php

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

?>

<fieldset>
    <p>
        <?php esc_html_e( "1. Boleto (somente à vista).", 'appmax-woocommerce' ); ?><br/>
        <?php esc_html_e( "2. Pagamentos com Boleto Bancário levam até 3 dias úteis para serem compensados e então terem os produtos liberados.", 'appmax-woocommerce' ); ?><br/>
        <?php esc_html_e( "3. O Boleto será gerado por meio da plataforma APPMAX.", 'appmax-woocommerce' ); ?><br/>
        <?php esc_html_e( "4. Depois do pagamento, fique atento ao seu e-mail para acompanhar o envio do seu pedido (verifique também a caixa de SPAM).", 'appmax-woocommerce' ); ?>
    </p>

    <div class="clear"></div>

    <p class="form-row form-row-first">
        <label for="card_cpf_billet">
            <?php esc_html_e(  'CPF (Para emissão da Nota Fiscal)', 'appmax-woocommerce' ); ?>
            <span class="required">*</span>
        </label>
        <input type="text" autocomplete="off" maxlength="14" class="input-text" id="cpf_billet" name="cpf_billet" placeholder="<?php esc_html_e( 'CPF (Para emissão da Nota Fiscal)', 'appmax-woocommerce' ); ?>" style="font-size: 1.0em; padding: 8px;">
    </p>

    <div class="clear"></div>

</fieldset>

<?php echo $display_script_payment; ?>