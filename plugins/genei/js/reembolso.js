jQuery(document).ready(function ($) {
    jQuery("#reembolso").on('click', function () {

    if (jQuery('#reembolso').prop('checked')) {
        jQuery('#div_cantidad_reembolso').show();
    } else {
        jQuery('#cantidad_reembolso').val(0);
        jQuery('#div_cantidad_reembolso').hide();
    } 
    actualizar_precio_envio();
});

 jQuery("#cantidad_reembolso").on('keyup', function () {
     jQuery(this).val($(this).val().replace(/,/g, '.'));
     if(parseFloat(jQuery("#cantidad_reembolso").val())>parseFloat(jQuery("#maxima_cantidad_reembolso").val())) {         
         jQuery("#cantidad_reembolso").val(parseFloat(jQuery("#maxima_cantidad_reembolso").val()).toFixed2(2));
     }
     actualizar_precio_envio();
    });
    
     jQuery("#cantidad_reembolso").on('blur', function () {          
         jQuery("#cantidad_reembolso").val(parseFloat(jQuery("#cantidad_reembolso").val()).toFixed2(2));
     
     actualizar_precio_envio();
    });

});

