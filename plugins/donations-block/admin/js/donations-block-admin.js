function copy_text_fun() {
    var copyText = document.getElementById("copy_txt");
    var input = document.createElement("textarea");
    input.value = copyText.textContent;
    document.body.appendChild(input);
    input.select();
    document.execCommand("Copy");
    jQuery('.copy-msg').show();
    jQuery('.copy-msg').fadeOut(2000)
    input.remove();
}
jQuery('.pdb_form_data').on('keyup change', function (){
    let pdb_mode = jQuery('#pdb_mode').val();
    let pdb_email = jQuery('#pdb_email').val();
    let pdb_amount = jQuery('#pdb_amount').val();
    let pdb_currency = jQuery('#pdb_currency').val();
    let pdb_size = jQuery('#pdb_size').val();
    let pdb_purpose = jQuery('#pdb_purpose').val();
    let pdb_SuggestionAmount = jQuery('#pdb_SuggestionAmount').val();

    jQuery('#copy_txt').html("[paypal_donation_block email='"+pdb_email+"' amount='"+pdb_amount+"' currency='"+pdb_currency+"' size='"+pdb_size+"' purpose='"+pdb_purpose+"' mode='"+pdb_mode+"' suggestion='"+pdb_SuggestionAmount+"']")
})

//Copy to clipboard
function copy_text_fun() {
    var copyText = document.getElementById("copy_txt");
    var input = document.createElement("textarea");
    input.value = copyText.textContent;
    document.body.appendChild(input);
    input.select();
    document.execCommand("Copy");
    jQuery('.copy-msg').show();
    jQuery('.copy-msg').fadeOut(2000)
    input.remove();
}
jQuery('.pdb_form_data').on('keyup change', function (){
    let pdb_mode = jQuery('#pdb_mode').val();
    let pdb_email = jQuery('#pdb_email').val();
    let pdb_amount = jQuery('#pdb_amount').val();
    let pdb_currency = jQuery('#pdb_currency').val();
    let pdb_size = jQuery('#pdb_size').val();
    let pdb_purpose = jQuery('#pdb_purpose').val();
    let pdb_SuggestionAmount = jQuery('#pdb_SuggestionAmount').val();

    jQuery('#copy_txt').html("[paypal_donation_block email='"+pdb_email+"' amount='"+pdb_amount+"' currency='"+pdb_currency+"' size='"+pdb_size+"' purpose='"+pdb_purpose+"' mode='"+pdb_mode+"' suggestion='"+pdb_SuggestionAmount+"']")
})

jQuery(document).ready(function (){
    if (jQuery('#pdb_list').length) {
        jQuery('#pdb_list').DataTable();
    }

})