jQuery(document).ready(function ($) {
    jQuery("#switch_iva").on('click', function () {
        var importe_agencia;
        if (iva_exento != 1) {
            if (jQuery('#switch_iva').prop('checked')) {
                jQuery(".importe_envio_listado").each(function () {
                    importe_agencia = parseFloat(jQuery(this).html());
                    importe_agencia = importe_agencia / (1 + (iva / 100));
                    importe_agencia = importe_agencia.toFixed2(2);
                    jQuery(this).html(importe_agencia);
                });

            } else {
                jQuery(".importe_envio_listado").each(function () {
                    importe_agencia = parseFloat(jQuery(this).html());
                    importe_agencia = importe_agencia * (1 + (iva / 100));
                    importe_agencia = importe_agencia.toFixed2(2);
                    jQuery(this).html(importe_agencia);
                });

            }
        }
    });
});