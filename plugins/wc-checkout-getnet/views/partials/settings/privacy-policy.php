<?php
/**
 * Privacy Policy partial
 *
 * @package WcGetnet
 */

?>
<div class="gnt-policy-privacy hide <?php echo ( get_option( '_policy_privacy_accept' ) == 1 ) ? 'hide' : ''; ?>">
    <div class="gnt-container">
        <div class="gnt-modal">
            <div class="gnt-modal-title">
                Aceito fornecer informações não sensíveis do meu cadastro para melhorar minha experiência.
            </div>
            <div class="gnt-modal-controls">
                <input type="hidden" name="activepost" id="activepost" value="<?php echo $_GET['page']; ?>" />
                <button class="accept-policy-privacy button button-primary">Sim, eu aceito!</button>
                <a href="/politicas" target="_blank">Ler politicas de privacidade</a>
            </div>
        </div>
    </div>
</div>
