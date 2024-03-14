jQuery(document).ready(function ($) {
    jQuery("#seguro").on('click', function () {

    if (jQuery('#seguro').prop('checked')) {
        jQuery('#div_cantidad_seguro').show();
    } else {
        jQuery('#cantidad_seguro').val(0);
        jQuery('#div_cantidad_seguro').hide();
    }
    actualizar_precio_envio();
});

 jQuery("#cantidad_seguro").on('keyup', function () {
     jQuery(this).val($(this).val().replace(/,/g, '.'));
     if(parseFloat(jQuery("#cantidad_seguro").val())>parseFloat(jQuery("#maxima_cantidad_seguro").val())) {
         jQuery("#cantidad_seguro").val(parseFloat(jQuery("#maxima_cantidad_seguro").val()).toFixed2(2));
     }
     actualizar_precio_envio();
    });
    
    jQuery("#cantidad_seguro").on('blur', function () {          
         jQuery("#cantidad_seguro").val(parseFloat(jQuery("#cantidad_seguro").val()).toFixed2(2));
     
     actualizar_precio_envio();
    });
    
});