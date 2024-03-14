<fieldset>
    <input id="pix-order-id" type="hidden" value="<?php echo $order->get_meta('_appmax_woocommerce_transaction_data')["post_payment"]["order_id"]; ?>">
    <input id="wp_order_id" type="hidden" value="<?php echo $order->get_meta('_appmax_woocommerce_transaction_id'); ?>">
    <input id="expiration_date" type="hidden" value="<?php echo $order->get_meta('_appmax_woocommerce_transaction_data')["post_payment"]["pix_expiration_date"] ?>">
    <div id="wrapper">
        <div class="text-header">
            <div>
                <h2>Seu pedido foi reservado!</h2>
                <h2>Efetue o pagamento dentro de <span id="countdown"></span>
                </h2>
            </div>
        </div>

        <div class="col-md-6">
            <p>1 - Clique no botão abaixo para copiar o código</p>
            <p>2 - Abra o aplicativo do seu banco ou instituição financeira e entre na opção Pix</p>
            <p>3 - Na opção Pix Copia e Cola, insira o código copiado no passo anterior</p>
        </div>

        <div style="display: grid; place-items: center;">
            <div>
                <img src='data:image/png;base64,
                <?= $order->get_meta("_appmax_woocommerce_transaction_data")["post_payment"]["pix_qrcode"] ?>' width="240">
                <p id="demo"></p>
            </div>
            <div class="text-center">
                <input type="text" id="pix_emv" style="display: none;" value="<?= $order->get_meta("_appmax_woocommerce_transaction_data")["post_payment"]["pix_emv"] ?>">
                <button id="get-qrcode">Copiar código</button>
            </div>
        </div>
    </div>

</fieldset>
<script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>