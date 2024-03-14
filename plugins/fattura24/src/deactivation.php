<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com> 
 *
 * Descrizione: Tab "Attenzione" della schermata di impostazioni
 * 
 */

 namespace fattura24;

 if (!defined('ABSPATH')) {
     exit;
 }
 
$filesToInclude = [
    'methods/met_deactivation.php'
];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}

 function fatt_24_deactivation_form() {
    $reasons = fatt_24_get_reasons();
    ?>
    <div id="fattura24_deactivation_form" class="fattura24-f24-modal-mask">
            <div class="fattura24-modal">
                <div class="f24-modal-container">
                    <div class="f24-modal-content">
                        <div class="f24-modal-body">
                            <div class="f24-model-header">
                                <div style="display: flex; flex-direction: row;">
                                    <div><?php echo fatt_24_img(fatt_24_attr('src', fatt_24_png('../assets/logo_orange')), array()); ?></div>
                                    <div style="margin-top: 20px; margin-left: 20px;"><span style="font-size: large; font-weight: bold;"><?php echo __('Quick feedback', 'fattura24'); ?></span></div>
                                </div>
                            </div>
                            <main class="f24-form-container main-full">
                                <p class="fattura24-title-text"><?php echo __('If you have a moment, please let us know why you want to deactivate this plugin', 'fattura24'); ?></p>
                                <ul class="f24-deactivation-reason" data-nonce="<?php echo wp_create_nonce('fatt24_deactivation_nonce') ?>">
                                <?php
                                if($reasons) {
                                    foreach($reasons as $key => $reason) {
                                        $reason_type = isset($reason['reason_type']) ? $reason['reason_type'] : '';
                                        $reason_placeholder = isset($reason['reason_placeholder']) ? $reason['reason_placeholder'] : '';
                                        ?>
                                           <li data-type="<?php echo esc_attr($reason_type); ?>" data-placeholder="<?php echo esc_attr($reason_placeholder); ?> ">
                                                <label>
                                                    <input type="radio" name="selected-reason" value="<?php echo esc_attr($key); ?>">
                                                    <span><?php echo esc_html($reason['radio_label']); ?></span>
                                                </label>
                                                <div class="f24-reason-input">
                                                <?php
                                                    if ($reason_type == 'text') {
                                                        echo fatt_24_input(array('id' => $key, 'class' => 'reason-input-text', 'type' => 'text', 'placeholder' => $reason_placeholder, 'hidden'));   
                                                    } else {
                                                        echo '<textarea id="' . $key . '"' . ' row="5" placeholder="' . $reason_placeholder . '"' . 'hidden></textarea>';
                                                    }
                                                ?>    
                                                <div>    
          
                                            </li>
                                            <?php
                                        }
                                }
                                ?>
                            </ul>
                                <p class="fattura24-privacy-cnt"><?php echo __('This form is only for getting your valuable feedback. In this form we collect an username and an email address used to send a reply. To know more read our ', 'fattura24'); ?> <a class="fattura24-privacy-link" target="_blank" href="<?php echo esc_url('https://www.fattura24.com/documentazione-legale/informativa-privacy/');?>"><?php echo __('Privacy Policy', 'fattura24'); ?></a></p>
                            </main>
                            <footer class="f24-modal-footer">
                                <div class="fattura24-left">
                                    <a class="fattura24-link fattura24-left-link fattura24-deactivate" href="#"><?php echo __('Skip & Deactivate', 'fattura24'); ?></a>
                                </div>
                                <div class="fattura24-right">
                                    <a class="fattura24-link fattura24-right-link fattura24-active fattura24-get-support" target="_blank" href="https://www.fattura24.com/woocommerce/introduzione"><?php echo __('Docs', 'fattura24'); ?></a>
                                    <a class="fattura24-link fattura24-right-link fattura24-active fattura24-submit-deactivate" href="#"><?php echo __('Submit and Deactivate', 'fattura24'); ?></a>
                                    <a class="fattura24-link fattura24-right-link fattura24-close" href="#"><?php echo __('Cancel', 'fattura24'); ?></a>
                                </div>
                            </footer>
                    </div>
                </div>
            </div>
        </div> 
        <?php   
}