<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * metodi utilizzati da hooks.php correlati alla gestione
 * e all'aggiunta dei campi fiscali - lato utente, lato checkout, lato admin
 *
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

function fatt_24_f24_added_vat_field() {
    return __('Added by Fattura24', 'fattura24') == get_option('fatt-24-add-vat-field');
}

function fatt_24_show_billing_checkbox()
{
    if ('2' == get_option('fatt-24-inv-create') && fatt_24_get_flag(FATT_24_INV_DISABLE_RECEIPTS)) {
        return false;
    }
    //fatt_24_trace('opzione toggle-billing-fields :', get_option('fatt-24-toggle-billing-fields'));

    $showCheckbox = get_option('fatt-24-toggle-billing-fields') == '';
    $piRequired = fatt_24_get_flag(FATT_24_ABK_VATCODE_REQ) == 1;
    $result = true;

    if ($piRequired || !$showCheckbox) {
        $result = false;
    }

    return $result;
}

function fatt_24_delete_user($user_id)
{
    $f24UserMetaArray = ['billing_fiscalcode', 'billing_vatcode', 'billing_pecaddress', 'billing_recipientcode'];
    foreach ($f24UserMetaArray as $metakey) {
        delete_user_meta($user_id, $metakey);
    }
}

/**
 * Campi aggiunti al checkout
 */
function fatt_24_billing_checkout_fields($fields)
{
    $showCheckbox = fatt_24_show_billing_checkbox();
    $addedF24PIVA = fatt_24_f24_added_vat_field(); 

    if ($showCheckbox) {
        $fields['billing']['billing_checkbox'] = array(
            'type'     => 'checkbox',
            'label'    => __('I would like to receive an invoice', 'fattura24'),
            'class'    => array('form-row-wide', 'tablecell', 'fatt_24_billing_cb'),
            'priority' => 25,
            'clear'    => true
        );
    }
    
    /**
    * Tolgo la classe css fattura24 dal campo P. IVA
    * nel caso del ticket DT 21383 poteva crearsi una visualizzazione sfalsata
    * tra etichetta e campo (tema grafico: Astra)
    */
    if ($addedF24PIVA) {
        $fields['billing']['billing_vatcode'] = array(
                'type'       => 'text',
                'placeholder' => __('Partita Iva / VAT', 'fattura24'),
                'label'      => __('Partita Iva / VAT', 'fattura24'),
                'class'      => array('form-row-wide'),
                'priority'   => 30,
                'clear'      => true,
            );
    }    

    $fields['billing']['billing_fiscalcode'] = array(
        'type'       => 'text',
        'placeholder' => __('Codice fiscale', 'fattura24'),
        'label'      => __('Codice fiscale', 'fattura24'),
        'class'      => array('form-row-wide', 'fattura24', 'inv_create_elecinvoice'), // lo aggiungo ai campi nascosti se il paese != IT
        'priority'   => 31,
        'clear'      => true
    );

    $fields['billing']['billing_recipientcode'] = array(
            'type' => 'text',
            'placeholder' => __('Codice Destinatario', 'fattura24'),
            'label' => __('Codice Destinatario', 'fattura24'),
            'maxlength' => 7, // definisco la lunghezza massima dell'input
            'class' => array('form-row-wide', 'fattura24', 'inv_create_elecinvoice'),
            'priority' => 32, // cambio l'ordine dei campi nel layout
            'clear' => true
        );

    $fields['billing']['billing_pecaddress'] = array(
             'type' => 'email',
             'placeholder' => __('Indirizzo PEC', 'fattura24'),
             'label' => __('Indirizzo PEC', 'fattura24'),
             'validate' => array('email'),
             'class' => array('form-row-wide', 'fattura24', 'inv_create_elecinvoice'),
             'priority' => 33,
             'clear' => true
        );

    //fatt_24_trace('campi aggiuntivi :', $fields);    

    return $fields;
}

/**
 * I campi sono gli stessi già settati sopra,
 * la checkbox deve essere visibile solo nel checkout
 */
function fatt_24_billing_fields($fields)
{
    global $wp;
    $current_url = home_url(add_query_arg(array(), $wp->request));
    
    $isCheckout = is_checkout();
    $f24BillingFields = fatt_24_billing_checkout_fields($fields)['billing'];
    $showCheckbox = fatt_24_show_billing_checkbox();
    //fatt_24_trace('billing fields 1 :', $f24BillingFields);

    if ($showCheckbox && !$isCheckout) {
        //array_shift($f24BillingFields);
        unset($f24BillingFields['billing_checkbox']);
    }

    //fatt_24_trace('campi di fatturazione dopo il controllo:', $f24BillingFields);


    foreach ($f24BillingFields as $key => $field) {
        $fields[$key] = $field;
    }

    return $fields;
}

function fatt_24_checkout_fields_validation($data, $errors)
{
    global $woocommerce;

    $billing_vatcode = '';
    if (isset($data['billing_vatcode'])) {
        $billing_vatcode = trim($data['billing_vatcode']);
    } else if (isset($_POST['$billing_vatcode'])) {
        $billing_vatcode = trim($_POST['billing_vatcode']);
    }

    $billing_fiscalcode = isset($data['billing_fiscalcode']) ? trim($data['billing_fiscalcode']) : trim($_POST['billing_fiscalcode']);
   
    $customer = $woocommerce->customer ? $woocommerce->customer : new \WC_Customer(0, true);
    $country = method_exists($customer, 'get_billing_country') ? $customer->get_billing_country() : 
            $customer->get_country(); // deprecato da WooCommerce 3.0
    $customerRequiredInvoice = isset($_POST['billing_checkbox']) && (int) $_POST['billing_checkbox'] == 1;
        
    /** 
     * Non devo passare il controllo di validità sul campo p.iva  
     * se $selectedOption è fattura elettronica, $country NON è IT e il campo p. iva è vuoto
     * In quel caso infatti forzo la compilazione del campo con nome/cognome
     * oppure ragione sociale (cfr. api_call.php riga 248)
     * 
     * Davide Iandoli 11.01.2023 
     */
    $selectedOption = (string) get_option('fatt-24-inv-create'); 
    $forceVatCode = false;
    
    if ($selectedOption == '2' && $country !== 'IT' && empty($billing_vatcode)) {
        $forceVatCode = true;
    }
    if ($forceVatCode) {
        return;
    }

    $message = fatt_24_get_validation_message($billing_fiscalcode, $billing_vatcode, $country, $customerRequiredInvoice);
    
    if ($message) {
        $errors->add('validation', $message);
    }
}

function fatt_24_add_fieldset_to_my_account()
{
    if (fatt_24_customer_use_cf() || fatt_24_customer_use_vat()) {
        $user_id = get_current_user_id();
        $user = get_userdata($user_id); ?>
    
            <fieldset>
                <legend>
                    <?php _e('Dati fiscali', 'fattura24') ?> <!-- edited label Davide Iandoli 29.01.2019 -->
                </legend>
                <?php if (fatt_24_customer_use_cf()) { ?>
                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide"> <!-- edited style -->
                        <label for="billing_fiscalcode">
                            <?php __('Codice Fiscale', 'fattura24') ?>
                        </label>
    
                        <input type="text" class="woocommerce-Input input-text"
                               name="billing_fiscalcode" id="billing_fiscalcode"
                               value="<?php echo esc_attr(strtoupper(fatt_24_user_fiscalcode($user_id))) ?>" />
    
                        <span class="description">
                            <?php _e('inserisci un codice fiscale valido', 'fattura24') ?> <!-- edited label Davide Iandoli 29.01.2019 -->
                        </span> 
                    </p>
                <?php } ?>
         
                <?php if (fatt_24_customer_use_vat()) { ?>
                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <label for="billing_vatcode">
                            <?php _e('Partita Iva', 'fattura24')?>
                        </label>
    
                        <input type="text" class="woocommerce-Input input-text"
                               name="billing_vatcode" id="billing_vatcode"
                               value="<?php echo esc_attr(fatt_24_user_vatcode($user_id)) ?>" />
    
                        <span class="description">
                            <?php _e('inserisci una Partita Iva valida', 'fattura24') ?> <!-- edited label Davide Iandoli 29.01.2019 -->
                        </span> 
                    </p>
                  <?php } ?> 
                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <label for="billing_recipientcode">
                            <?php _e('Codice Destinatario', 'fattura24')?>
                        </label>
    
                        <input type="text" class="woocommerce-Input input-text"
                               name="billing_recipientcode" id="billing_recipientcode"
                               maxlength="7"
                               value="<?php echo esc_attr(fatt_24_user_recipientcode($user_id)) ?>" />
    
                        <span class="description">
                            <?php _e('inserire un codice destinatario valido') ?> 
                            <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        
                        <label for="billing_pec">
                            <?php _e('Indirizzo PEC', 'fattura24')?>
                        </label>
            
                        <input type="email" class="woocommerce-Input input-text"
                               name="billing_pecaddress" id="billing_pecaddress"
                               value="<?php echo esc_attr(fatt_24_user_pecaddress($user_id)) ?>" />
               
                        <span class="description">
                            <?php _e('inserire un indirizzo PEC valido', 'fattura24'); ?> 
            </fieldset> 
    
        <div class="clear"></div>
    <?php
    }
}

function fatt_24_customer_meta_fields($fields)
{
    if (fatt_24_customer_use_cf() || fatt_24_customer_use_vat()) {
        if (!isset($fields['fatt_24'])) {
            $fields['fatt_24'] = array(
                'title' => __('Invoicing required fields', 'fattura24'),
                'fields' => array()
            );
        }
        if (fatt_24_customer_use_cf()) {
            $fields['fatt_24']['fields']['billing_fiscalcode'] = array(
                'label' => __('Fiscal Code', 'fattura24'),
                'description' => __('a valid Fiscal Code', 'fattura24'),
            );
        }
        if (fatt_24_customer_use_vat()) {
            $fields['fatt_24']['fields']['billing_vatcode'] = array(
                'label' => __('Vat code', 'fattura24'),
                'description' => __('a valid Vat Code', 'fattura24'),
            );
        }
    }

    if (fatt_24_customer_use_recipientcode()) {
        $fields['fatt_24']['fields']['billing_recipientcode'] = array(
            'label' => __('Recipient Code', 'fattura24'),
            'description' => __('a valid Recipient Code', 'fattura24'),
        );

        $fields['fatt_24']['fields']['billing_pecaddress'] = array(
            'label' => __('Indirizzo PEC', 'fattura24'),
            'description' => __('a valid PEC address', 'fattura24'),
        );
    }
    return $fields;
}

function fatt_24_created_customer($customer_id)
{
    $f24billingFields = ['billing_fiscalcode', 'billing_vatcode', 'billing_pecaddress', 'billing_recipientcode'];

    foreach ($f24billingFields as $field) {
        if (isset($_POST[$field])) {
            update_user_meta($customer_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}

function fatt_24_save_account_details($user_id)
{
    if (fatt_24_customer_use_cf()) {
        update_user_meta($user_id, 'billing_fiscalcode', htmlentities(sanitize_text_field(strtoupper($_POST['billing_fiscalcode']))));
    }

    if (fatt_24_customer_use_vat()) {
        update_user_meta($user_id, 'billing_vatcode', htmlentities(sanitize_text_field($_POST['billing_vatcode'])));
    }

    if (fatt_24_customer_use_recipientcode()) {
        update_user_meta($user_id, 'billing_recipientcode', htmlentities(sanitize_text_field($_POST['billing_recipientcode'])));
        update_user_meta($user_id, 'billing_pecaddress', htmlentities(sanitize_text_field($_POST['billing_pecaddress'])));
    }
}

function fatt_24_checkout_meta($order_id, $data) {
    /**
     * Con questo blocco di codice forzo l'assegnazione di un id all'ordine
     * nel caso peggiore (nuovo ambiente) sarà 1, altrimenti sarà pari al numero più grande incrementato di uno
     * in assenza di questo workaround l'id dell'ordine (con HPOS) sarà zero e non sarà possibile finalizzare il checkout
     * Davide Iandoli 15.11.2023
     */

    $order = new \WC_Order($order_id);

    if (0 === $order_id) {
        fatt_24_trace('Potenziale errore: l\'ordine potrebbe non essere stato salvato perché l\'id è zero !');
    }

    $order->add_meta_data(FATT_24_ORDER_INVOICE_STATUS, '');
    $f24billingFields = ['billing_checkbox', 'billing_fiscalcode', 'billing_pecaddress', 'billing_recipientcode'];
    $addedF24PIVA = fatt_24_f24_added_vat_field() == true;
    
    if ($addedF24PIVA) {
        array_push($f24billingFields, 'billing_vatcode');
    }

    foreach ($f24billingFields as $field) {
        if (!empty($data[$field])) {
            $order->add_meta_data("_$field", $data[$field]);
        }
    } 
}


function fatt_24_add_fields_to_admin_order($order)
{
    /**
     * Added link to check cf and vat code on AdE services
     * Davide Iandoli 15.11.2021
     */
  
    $cfValue = strtoupper(fatt_24_order_c_fis($order));
    $checkCf = !empty($cfValue) ?
    '<a href="https://telematici.agenziaentrate.gov.it/VerificaCF/VerificaCf.do?cf=' . $cfValue . '"target="_blank">' . __('(Check fiscal code)', 'fattura24') . '</a>' :
    '' ;
    $cfLabel = '<p><strong>' . __('Fiscal Code', 'fattura24') . ':</strong>' . $cfValue . ' ' . $checkCf . '</p>';

    $piValue = strtoupper(fatt_24_order_p_iva($order));
    $checkPiva = !empty($piValue) ?
   '<a href="https://telematici.agenziaentrate.gov.it/VerificaPIVA/VerificaPiva.do?piva=' . $piValue . '"target="_blank">'. __('(Check vat code)', 'fattura24') . '</a>' :
   '';
    $piLabel = '<p><strong>' . __('Vat Code', 'fattura24') . ':</strong>' . $piValue . ' ' . $checkPiva . '</p>'; 
    
    $pec = strtolower(fatt_24_order_pec_address($order)); // la pec tutta in minuscolo
    $sdi_code = strtoupper(fatt_24_order_recipientcode($order));
    ?>

  
    
    
    <div class="address">
		<?php
        if (fatt_24_customer_use_cf()) {
            echo $cfLabel;
        }
    if (fatt_24_customer_use_vat()) {
        echo $piLabel;
    }
    if (fatt_24_customer_use_recipientcode()) {
        echo '<p><strong>' . __('Recipient Code', 'fattura24') . ':</strong>' . $sdi_code .'</p>';
        echo '<p><strong>' . __('PEC address', 'fattura24') . ':</strong>'  . $pec . '</p>';
    } ?>
	</div>
    <div class="edit_address">
    <?php
        
        $fatt_24_billing_fields_values = [
            'CF' => $cfValue,
            'PI' => $piValue,
            'PEC' => $pec,
            'SDI' => $sdi_code
        ];
        // con questo codice li rendo editabili nell'ordine lato admin usando il metodo woocommerce_wp_input
        if (fatt_24_customer_use_cf()) {
            woocommerce_wp_text_input(array( 'id' => '_billing_fiscalcode', 'label' =>__('Fiscal Code', 'fattura24'), 'value' => $fatt_24_billing_fields_values['CF'], 'wrapper_class' => '_billing_company_field' ));
        }
    if (fatt_24_customer_use_vat()) {
        woocommerce_wp_text_input(array( 'id' => '_billing_vatcode', 'label' => __('Vat Code', 'fattura24'), 'value' => $fatt_24_billing_fields_values['PI'], 'wrapper_class' => '_billing_company_field' ));
    }
    if (fatt_24_customer_use_recipientcode()) {
        woocommerce_wp_text_input(array( 'id' => '_billing_recipientcode', 'label' => __('Recipient Code', 'fattura24'), 'value' => $fatt_24_billing_fields_values['PEC'], 'wrapper_class' => '_billing_company_field' ));
        woocommerce_wp_text_input(array( 'id' => '_billing_pecaddress', 'label' => __('PEC Address', 'fattura24'), 'value' => $fatt_24_billing_fields_values['SDI'], 'wrapper_class' => '_billing_company_field' ));
    } ?>
    </div>
    <?php
}
/** Qui aggiorno i campi aggiunti nel dettaglio dell'ordine;  */
function fatt_24_process_order_meta($post_id, $post)
{
    $order = wc_get_order($post_id);    
    $f24billingFields = ['billing_fiscalcode', 'billing_vatcode', 'billing_pecaddress', 'billing_recipientcode'];
    foreach ($f24billingFields as $field) {

        $order->update_meta_data("_$field", wc_clean(sanitize_text_field($_POST[ "_$field" ])));
    }
    $order->save_meta_data();
}

/**
 * Metodo con cui mostro o nascondo i campi aggiuntivi in base ad alcune opzioni
 * oppure in base al paese di fatturazione selezionato
 * edit 03.03.2022 : ora richiamo js esterni a cui passo i dati necessari
 */
function fatt_24_manage_checkout()
{
    $title = __('Fill in at least one of these two fields', 'fattura24');
    $checkLabel = __('click here to verify', 'fattura24');
    $f24CheckoutJs = ['f24_checkbox_change', 'f24_select_change'];

    $data = [
             'alwaysCreateFE' => get_option('fatt-24-inv-create') == '2' && fatt_24_get_flag(FATT_24_INV_DISABLE_RECEIPTS),
             'showCheckbox' => fatt_24_show_billing_checkbox(),
             'cfRequired' => fatt_24_CF_flag() == 1,
             'pIRequired' => fatt_24_get_flag(FATT_24_ABK_VATCODE_REQ) == 1,
             'error_message' => $title,
             'checkCF' => '<a style="float:right;" href="https://telematici.agenziaentrate.gov.it/VerificaCF/Scegli.do?parameter=verificaCf" target="_blank">' . $checkLabel . '</a>' ,
             'checkPI' => '<a style="float:right;" href="https://telematici.agenziaentrate.gov.it/VerificaPIVA/Scegli.do?parameter=verificaPiva" target="_blank">'. $checkLabel . '</a>'
            ];

    foreach ($f24CheckoutJs as $addJs) {
        wp_enqueue_script($addJs, fatt_24_url('/js/checkout/'. $addJs . '.js'), array('jquery'));
        wp_localize_script($addJs, 'f24_checkout_vars', $data);
    }
}

/** 
* Davide Iandoli 10.01.2023
* Per la logica di convalida checkout devo controllare codice fiscale e/o partita iva
* lista controlli:
*
* C.F. :
* - se $country == 'IT';
* - se il campo è obbligatorio (admin o frontend) e vuoto
* - se la lunghezza della stringa è 11 oppure 16;
*
* P. IVA : 
* - se il campo è aggiunto e gestito da F24;
* - se il campo è obbligatorio (admin o frontend);
* - lunghezza uguale a 11 solo se il paese è IT (altrimenti niente controllo sulla lunghezza)
* ATTENZIONE: se l'opzione selezionata in Crea doc fiscale è 2 e il paese !== IT forzo la compilazione del campo p.iva con 
* nome / ragione sociale
*/

function fatt_24_get_validation_message($billing_fiscalcode, $billing_vatcode, $country, $customerRequiredInvoice)
{
    $checkCF = false;
    $checkPI = false;
    $checkPILen = false;
    $addedF24PIVA = fatt_24_f24_added_vat_field() == true;
    $feRequired = fatt_24_get_resulting_doc_type($country) == FATT_24_DT_FATTURA_ELETTRONICA;
    $adminPIRequired = fatt_24_get_flag(FATT_24_ABK_VATCODE_REQ) == 1;
    $cfRequired = fatt_24_CF_flag();
    $message = ''; // nessun errore
    
    /*
    * fatt_24_trace('cf required :', $cfRequired);
    * fatt_24_trace('fe required :', $feRequired);
    * fatt_24_trace('added F24 PIVA :', $addedF24PIVA);
    * fatt_24_trace('admin PI required :', $adminPIRequired);
    * fatt_24_trace('customer required invoice :', $customerRequiredInvoice);
    * fatt_24_trace('documento risultante :', fatt_24_get_resulting_doc_type($country));
    */
    
    /**  
     * Qui decido se fare i controlli sul CF
     * se il paese di fatt. è IT e il campo P.IVA è vuoto
     */ 
    if ($country == 'IT' && empty($billing_vatcode)) {
        if ($cfRequired) {
            $checkCF = true;
        }
        if ($customerRequiredInvoice && $addedF24PIVA) {
            $checkCF = true;
        }
        if ($feRequired) {
            $checkCF = true;
        }
    }
   
    /** 
     * Qui decido se fare i controlli su PIVA
     * se il campo è aggiunto da F24 e il campo C.F. è vuoto
     */
    if ($addedF24PIVA && empty($billing_fiscalcode)) {
        if ($adminPIRequired) {
            $checkPI = true;
            $checkPILen = $country == 'IT' ? true : false;
        }
        if ($feRequired) {
            $checkPI = true;
            $checkPILen = $country == 'IT' ? true : false;
        }
        if ($customerRequiredInvoice) {
            $checkPI = true;
            $checkPILen = $country == 'IT' ? true : false;
        }
    }

    /* 
    * Devo controllare entrambi se il tipo di doc creato è FE e il paese è IT
    * in caso di paese diverso al massimo devo controllare solo la p.iva perciò passo solo da $checkPI
    * Nota: solo se queste condizioni sono verificate e i campi sono vuoti il messaggio viene visualizzato
    * ed esco dalla funzione; altrimenti proseguo con i controlli successivi
    */
    $bothCheck = $checkCF && $checkPI;
    
    if ($bothCheck) {
        if (empty($billing_fiscalcode) && empty($billing_vatcode)) {
            $message =  __('You should fill either Fiscal code or Vat code field', 'fattura24');
        }
    }

    if (!empty($message)) {
        return $message;
    }
    
    if ($checkCF) {
        if (empty($billing_fiscalcode)) {
            $message = __('Codice fiscale obbligatorio', 'fattura24');
        }

        $cfLen = strlen($billing_fiscalcode);
        $isCFMinLen = $cfLen == 11;
        $isCFMaxLen = $cfLen == 16;
        $validCf = $isCFMaxLen || $isCFMinLen;
             
        if (!$validCf) {
            $message = __('Fiscal code should be long 11 or 16 characters', 'fattura24');
        }
    }

    if ($checkPI) {
        if (empty($billing_vatcode)) {
            $message = __('Vat code required', 'fattura24');
            $checkPILen = false;
        }

        if ($checkPILen) {
            if (strlen($billing_vatcode) !== 11) {
                $message = __('Vat code should be long 11 characters', 'fattura24');
            }
        }
    }
       
    return $message;
}