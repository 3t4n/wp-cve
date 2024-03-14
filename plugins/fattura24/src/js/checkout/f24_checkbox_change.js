jQuery(document).ready(function($){
  let alwaysCreateFE = f24_checkout_vars['alwaysCreateFE'] == '1';
  let cfRequired = f24_checkout_vars['cfRequired'] === '1';
  let pIRequired = f24_checkout_vars['pIRequired'] === '1';
  let showCheckbox = f24_checkout_vars['showCheckbox'] === '1';
  let title = f24_checkout_vars['error_message'];
  let checkCF = f24_checkout_vars['checkCF'];
  let checkPI = f24_checkout_vars['checkPI'];
  let required = '<abbr class="required" title="required">*</abbr>'; 
  let oneRequired = '<abbr class="required" title="' + title + '">°</abbr>';
  let cfAppended = false;
  let piAppended = false;

  function f24AppendCheck() {
    $('#billing_fiscalcode_field label').append(checkCF);
    $('#billing_vatcode_field label').append(checkPI);
  }

  function f24AlwaysShowFields()
  {
    $('#billing_company').show();
    $('#billing_company_field label').show();
    $('#billing_fiscalcode').show();
    $('#billing_fiscalcode_field label').show();
    $('#billing_vatcode').show();
    $('#billing_vatcode_field label').show();
    $('#billing_recipientcode').show();
    $('#billing_recipientcode_field label').show();
    $('#billing_pecaddress').show();
    $('#billing_pecaddress_field label').show();
  }

  function f24PivaFieldRequired()
  {
    if(pIRequired || alwaysCreateFE) {
      $('#billing_vatcode_field label > .optional').remove();
      /*if (!cfRequired) {
        $('#billing_vatcode_field label > .optional').remove();
      }*/
      if (pIRequired) {
        $('#billing_vatcode_field').addClass("validate-required");
        if (!piAppended) {
          $('#billing_vatcode_field label').append(required);
          piAppended = true;
        }
      } else if (alwaysCreateFE && !cfRequired) {
        $('#billing_vatcode_field label').append(oneRequired);
      }
    }
  }

  function f24CfFieldRequired()
  {
    if (cfRequired || alwaysCreateFE) {
      $('#billing_fiscalcode_field label > .optional').remove();
      /*if (pIRequired) {
        $('#billing_fiscalcode_field label > .optional').remove();
      }*/
      if (cfRequired) {
        $('#billing_fiscalcode_field').addClass("validate-required");
        if (!cfAppended) {
          $('#billing_fiscalcode_field label').append(required);
          cfAppended = true;
        }
      } else if (alwaysCreateFE && !pIRequired) {
        $('#billing_fiscalcode_field label').append(oneRequired);
      }
    }
  }

  function f24HideFields()
  {
    if (!cfRequired) {
      $('#billing_fiscalcode').hide();
      $('#billing_fiscalcode_field label').hide();
    } 
    
    if (!pIRequired) { 
      $('#billing_company').hide();
      $('#billing_company_field label').hide();
      $('#billing_vatcode').hide();
      $('#billing_vatcode_field label').hide();
      $('#billing_recipientcode').hide();
      $('#billing_recipientcode_field label').hide();
      $('#billing_pecaddress').hide();
      $('#billing_pecaddress_field label').hide(); 
    }  
  }
  
  function f24ShowHideFields() {
    $(':checkbox#billing_checkbox').change(function (){
      //console.log('cf appended :', cfAppended);
        if ($(this).is(':checked')) {
          $('#billing_company').show();
          $('#billing_company_field label').show();
          $('#billing_fiscalcode').show();
          $('#billing_fiscalcode_field label').show();
          $('#billing_fiscalcode_field label > .optional').remove();
          if (!cfRequired && !cfAppended) {
            $('#billing_fiscalcode_field label').append(oneRequired);
            cfAppended = true;
          }
          $('#billing_vatcode').show();
          $('#billing_vatcode_field label').show();
          $('#billing_vatcode_field label > .optional').remove();
          if (!pIRequired && !piAppended) {
            $('#billing_vatcode_field label').append(oneRequired);
            piAppended = true;
          }
          $('#billing_recipientcode').show();
          $('#billing_recipientcode_field label').show();
          $('#billing_pecaddress').show();
          $('#billing_pecaddress_field label').show();
      } else {
          $('#billing_company').hide();
          $('#billing_company_field label').hide();
          //$('#billing_company_field').removeClass('form-row-wide');
          if(!cfRequired) {
              $('#billing_fiscalcode').hide();
              $('#billing_fiscalcode_field label').hide();
          }
         
          $('#billing_vatcode').hide();
          $('#billing_vatcode_field label').hide();
          $('#billing_vatcode_field').removeClass('validate-required');
          $('#billing_vatcode_field').removeClass('woocommerce-validated');
          $('#billing_vatcode_field').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
          $('#billing_recipientcode').hide();
          $('#billing_recipientcode_field label').hide();
          $('#billing_pecaddress').hide();
          $('#billing_pecaddress_field label').hide();
      }
    });
  }

 /**
  * Tolgo i link per la verifica del CF e della P.IVA
  * nel caso del ticket DT 21383 poteva crearsi una visualizzazione sfalsata
  * tra etichetta e campo (tema grafico: Astra)
  */
  //f24AppendCheck();

  if (showCheckbox) {
    f24PivaFieldRequired();
    f24CfFieldRequired();
    f24HideFields();
    f24ShowHideFields();
  } else {
    f24PivaFieldRequired();
    f24CfFieldRequired();
    f24AlwaysShowFields();
  }
});  
