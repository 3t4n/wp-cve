
jQuery(document).ready(function($){
    let cfRequired = f24_checkout_vars['cfRequired'] === '1';
    let pIRequired = f24_checkout_vars['pIRequired'] === '1';
    let showCheckbox = f24_checkout_vars['showCheckBox'] !== '1';
      
    $('select#billing_country').change(function(){
          if( $(this).val() == 'IT'){
              if(cfRequired) {
                  $('#billing_fiscalcode_field').addClass("validate-required");
                  $('#billing_fiscalcode_field label > .optional').remove();
              }            
              $('.inv_create_elecinvoice').show();
          } else {
            if (!pIRequired || !showCheckbox) {
                $('#billing_vatcode_field').find('abbr').remove();
            } 
            $('.inv_create_elecinvoice').hide();
          }
      });
  
}); 