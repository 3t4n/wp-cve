/**
 * In questo file js abilito e disabilito campi di impostazione
 * sulla base delle opzioni scelte per 'Crea ordine' e 'Crea fattura'
 * usato in settings.php
 * Davide Iandoli 09.02.2022
 */
 jQuery(function() {
    /** eseguo gli script solo nella schermata principale */
    let url = window.location.href;
    let page = url.substring(url.indexOf('page=') + 5);
    if (page !== 'fatt-24-settings') {
        return;
    }
    
    let $ = jQuery;
    // dichiarazioni
    let SAVE_CUST = document.getElementById('fatt-24-abk-save-cust-data');
    let ORD_CREATE = document.getElementById('fatt-24-ord-enable-create');
    let ORD_TOT_ZERO_ENABLED = document.getElementById('fatt-24-ord-zero-tot-enable');   
    let ORD_SEND = document.getElementById('fatt-24-ord-send-pdf');
    let ORD_TEMPLATE = document.getElementById('fatt-24-ord-template');
    let ORD_TEMPLATE_DEST = document.getElementById('fatt-24-ord-template-dest');
    let INV_CREATE = document.getElementById('fatt-24-inv-create');
    let INV_TOT_ZERO_ENABLED = document.getElementById('fatt-24-inv-zero-tot-enable');
    let INV_SEND = document.getElementById('fatt-24-inv-send-pdf');
    let INV_WHEN_PAID = document.getElementById('fatt-24-inv-create-when-paid');
    let INV_DISABLE_RECEIPTS = document.getElementById('fatt-24-inv-disable-receipts');
    let INV_TEMPLATE = document.getElementById('fatt-24-inv-template');
    let INV_TEMPLATE_DEST = document.getElementById('fatt-24-inv-template-dest');
    let INV_PDC = document.getElementById('fatt-24-inv-pdc');
    let INV_SEZIONALE_RICEVUTA = document.getElementById('fatt-24-inv-sezionale-ricevuta');
    let INV_SEZIONALE_FATTURA = document.getElementById('fatt-24-inv-sezionale-fattura');
    let INV_SEZIONALE_FATTURA_ELETTRONICA = document.getElementById('fatt-24-inv-sezionale-fattura-elettronica');
    let BOLLO_VIRTUALE_FE = document.getElementById('fatt-24-bollo-virtuale-fe');
    let DISPLAY_F24_FIELDS = document.getElementById('fatt-24-toggle-billing-fields');
    let ORD_ENABLE_PDF = document.getElementById('fatt-24-ord-enable-pdf-download');
    let INV_ENABLE_PDF = document.getElementById('fatt-24-inv-enable-pdf-download');
   
     // controllo opzioni in base a 'Crea ordine' (funzione)
     function order_controlled(main, controlled) {
        let enabled = main.value !== '0';
        if (enabled) {
            controlled.forEach(item => {
                item.disabled = false;
            });
        } else {
            controlled.forEach(item => {
                item.disabled = true;
                item.setAttribute('checked', 'checked');
            });
        }
    }
    
    // controllo opzioni in base a 'Crea fattura' (funzione)
    function invoice_controlled(main, controlled) {
        let selectedVal = main.value;
        //let group = controlled.join(',');
        //let FE = ['2', '3', '4', '6', '7'].includes(selectedVal);
        
        $('table tr.electronic_invoice_row').hide();
                
        if (selectedVal !== '0') {
            controlled.forEach(item => {
                item.disabled = false;
            });
            //$(group).prop('disabled', false);
        } else {
            controlled.forEach(item => {
                item.disabled = true;
                item.setAttribute('checked', '');
            })
        }
        $('table tr.electronic_invoice_row').show();
    }

    // abilita / disabilita 'Salva cliente'
    function enable_when_both_off() {
        let c1 = INV_CREATE.value !== '0'  ? true : false;
        let c2 = ORD_CREATE.value !== '0' ? true : false;
        //var c3 = SAVE_CUST.value === '1' ? 'checked' : ''; // controllo il valore è salvo
        if(c1 || c2) {
           SAVE_CUST.setAttribute('checked', 'checked');
           SAVE_CUST.disabled = true;
        } else  {
           SAVE_CUST.disabled = false; 
           //SAVE_CUST.setAttribute('checked', c3); // predefinito = true, così lo cambio in false 
       }	
   }

   function toggle_display_fields() {
    let invOption = INV_CREATE.value;
    let disabledReceipts = INV_DISABLE_RECEIPTS.checked;
       if (invOption === '2' && disabledReceipts) {
          DISPLAY_F24_FIELDS.checked = 'checked';
          DISPLAY_F24_FIELDS.disabled = true;
       } else {
          DISPLAY_F24_FIELDS.disabled = false;
          DISPLAY_F24_FIELDS.checked = false;
       }
   }

    /**
    * Abilito la checkbox solo se seleziono
    * Fattura NON elettronica o Fattura elettronica
	*/
    function toggle_receipt_checkbox() {
        let invOption = INV_CREATE.value;
        let enableCheckbox = invOption === '1' || invOption === '2';
        if (!enableCheckbox) {
            INV_DISABLE_RECEIPTS.disabled = true;
        }
    }
    /**
    * Disabilito la casella invia Email
    */
    function disable_send_invoice() {
        if (INV_CREATE.value === '0')
            INV_SEND.disabled = true;
        else
            INV_SEND.disabled = false;
    }	

    $(ORD_CREATE).change(function() {
       order_controlled(ORD_CREATE, [ORD_TOT_ZERO_ENABLED, ORD_SEND, ORD_TEMPLATE, ORD_TEMPLATE_DEST, DISPLAY_F24_FIELDS, ORD_ENABLE_PDF])
       enable_when_both_off();
       });

    $(INV_CREATE).change(function(){
        invoice_controlled(INV_CREATE, [INV_SEND, INV_TOT_ZERO_ENABLED, INV_WHEN_PAID, INV_DISABLE_RECEIPTS,
            INV_TEMPLATE, INV_TEMPLATE_DEST, INV_PDC, INV_SEZIONALE_RICEVUTA, INV_SEZIONALE_FATTURA, INV_SEZIONALE_FATTURA_ELETTRONICA, BOLLO_VIRTUALE_FE, INV_ENABLE_PDF]);
        enable_when_both_off();
        toggle_display_fields();
        toggle_receipt_checkbox();
        disable_send_invoice();
    });
    
    order_controlled(ORD_CREATE, [ORD_TOT_ZERO_ENABLED, ORD_SEND, ORD_TEMPLATE, ORD_TEMPLATE_DEST, DISPLAY_F24_FIELDS, ORD_ENABLE_PDF]);
    invoice_controlled(INV_CREATE, [INV_SEND, INV_TOT_ZERO_ENABLED, INV_WHEN_PAID, INV_DISABLE_RECEIPTS, 
        INV_TEMPLATE, INV_TEMPLATE_DEST, INV_PDC, INV_SEZIONALE_RICEVUTA, INV_SEZIONALE_FATTURA, INV_SEZIONALE_FATTURA_ELETTRONICA, BOLLO_VIRTUALE_FE, INV_ENABLE_PDF]);
    toggle_display_fields();
    toggle_receipt_checkbox();
    disable_send_invoice();
    enable_when_both_off();
});
