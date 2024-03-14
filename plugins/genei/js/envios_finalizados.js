    function update_link_tarifas(numero_pedido_wp) {    
        direccion_remitente = jQuery("#direccion_remitente").val();
        var href_pedido = '?page=grupoimpultec&action=tarifas&pedido=' + numero_pedido_wp + '&dr=' + direccion_remitente;
        var check_bultos_defecto = 0;        
        var peso_bultos_defecto = 0;
        var alto_bultos_defecto = 0;
        var ancho_bultos_defecto = 0;
        var largo_bultos_defecto = 0;
        var direccion_remitente = 1;
        console.log('antes del ccheck');
        if (jQuery("#chkdf_" + numero_pedido_wp).is(':checked')) {
            check_bultos_defecto = 1;
            contador = 1;
            cadena_bultos='';
            console.log('antes del each');
           jQuery('.divs_bulto_defecto').each(function(){ 
               console.log('iteracion each '+contador);
            peso_bulto_defecto = jQuery("#peso_bulto_defecto_" + contador).val();
            alto_bulto_defecto = jQuery("#alto_bulto_defecto_" + contador).val();
            ancho_bulto_defecto = jQuery("#ancho_bulto_defecto_" + contador).val();
            largo_bulto_defecto = jQuery("#largo_bulto_defecto_" + contador).val();            
            cadena_bultos+='&peso_bulto_defecto_'+contador+'='+ peso_bulto_defecto +
                    '&ancho_bulto_defecto_'+contador+'='+ ancho_bulto_defecto +
                    '&alto_bulto_defecto_'+contador+'='+ alto_bulto_defecto +
                    '&largo_bulto_defecto_'+contador+'='+ largo_bulto_defecto; 
           contador++; 
           });
           
           direccion_remitente = jQuery("#direccion_remitente").val();
           jQuery("#href_tarifas_" + numero_pedido_wp).attr('href', href_pedido +
                    '&check_bultos_defecto=' + check_bultos_defecto +
                    '&numero_bultos_defecto=' + (contador-1) + cadena_bultos +
                    '&dr=' + direccion_remitente);
           
        } else {
            check_bultos_defecto = 0;
            jQuery("#href_tarifas_" + numero_pedido_wp).attr('href', href_pedido);
        }

    }
    function crear_botones() {                
        var html = '<div class="row divs_bulto_defecto_botones">\n\
<div class="col-12">\n\
<input type="button" class="form-control button add_bulto_defecto" onclick="add_fila_defecto()" value="Añadir">\n\
<input type="button" class="form-control button borrar_bulto_defecto" onclick="borrar_fila_defecto()" value="Borrar">\n\
</div>\n\
</div>';
        return html;
    }
    function crear_bultos_defecto() {
        var bulto_defecto = jQuery('.divs_bulto_defecto').length;
        var html = '<div class="row divs_bulto_defecto" id="div_bulto_defecto_' + (bulto_defecto+1) + '">\n\
<div class="col-6" style="font-weight:bold">Bulto ' + (bulto_defecto+1) + '</div>\n\
<div class="row">\n\
<div class="col-1">\n\
</div>\n\
<div class="col-12 col-sm-2">\n\
<label for="peso_bulto_defecto_' + (bulto_defecto+1) + '">P.Kg.</label>\n\
<input type="number" max="2000" min="0.1" class="form-control" id="peso_bulto_defecto_' + (bulto_defecto+1) + '" value="1" onchange="actualizar_datos_bulto_defecto()" name="peso_bulto_defecto_' + (bulto_defecto+1) + '" style="width:80px;">\n\
</div>\n\
<div class="col-12 col-sm-2">\n\
<label for="alto_bultos_defecto_' + (bulto_defecto+1) + '">Al.cm.</label>\n\
<input type="number" max="2000" min="0.1" class="form-control" id="alto_bulto_defecto_' + (bulto_defecto+1) + '" value="1" onchange="actualizar_datos_bulto_defecto()" name="alto_bulto_defecto_' + (bulto_defecto+1) + '" style="width:80px;">\n\
</div>\n\
<div class="col-12 col-sm-2">\n\
<label for="ancho_bultos_defecto_' + (bulto_defecto+1) + '">An.cm.</label>\n\
<input type="number" max="2000" min="0.1" class="form-control" id="ancho_bulto_defecto_' + (bulto_defecto+1) + '" value="1" onchange="actualizar_datos_bulto_defecto()" name="ancho_bulto_defecto_' + (bulto_defecto+1) + '" style="width:80px;">\n\
</div>\n\
<div class="col-12 col-sm-2">\n\
<label for="largo_bultos_defecto_' + (bulto_defecto+1) + '">La.cm.</label>\n\
<input type="number" max="2000" min="0.1" class="form-control" id="largo_bulto_defecto_' + (bulto_defecto+1) + '" value="1" onchange="actualizar_datos_bulto_defecto()" name="largo_bulto_defecto_' + (bulto_defecto+1) + '" style="width:80px;">\n\
</div>\n\
<div class="col-12 col-sm-2">\n\
</div>\n\
</div>';
        return html;
    }

    
    function actualizar_datos_bulto_defecto() {        
        var numero_pedido_wp = jQuery('.divs_bulto_defecto').parent('.bultos_defecto').attr('id').split("_")[2];
        update_link_tarifas(numero_pedido_wp);
    
    }
    function add_fila_defecto() {
        var numero_bultos = jQuery('.divs_bulto_defecto').length;
        if(numero_bultos < 30) {
        jQuery('.divs_bulto_defecto').parent('.bultos_defecto').append(crear_bultos_defecto());
        var numero_pedido_wp = jQuery('.divs_bulto_defecto').parent('.bultos_defecto').attr('id').split("_")[2];
        update_link_tarifas(numero_pedido_wp);
    }
    }
    function borrar_fila_defecto() {
        var numero_bultos = jQuery('.divs_bulto_defecto').length;
        if(numero_bultos > 1) {        
        var numero_pedido_wp = jQuery('.divs_bulto_defecto').parent('.bultos_defecto').attr('id').split("_")[2];
        jQuery('.divs_bulto_defecto').last().remove();
        update_link_tarifas(numero_pedido_wp);
    }
    
    }

    jQuery(document).ready(function ($) {        
        jQuery(".href_tarifas").each(function() {
            href_actual = jQuery(this).attr('href');
            jQuery(this).attr('href',href_actual+'&dr='+jQuery("#direccion_remitente").val());
        });
               
        
        
        jQuery("#direccion_remitente").on('change', function () {        
        jQuery(".href_tarifas").each(function() {
            pos_dir_ret = jQuery(this).attr('href').indexOf('&dr=');
            href_actual_sin_remitente = jQuery(this).attr('href').substring(0,pos_dir_ret);            
            jQuery(this).attr('href',href_actual+'&dr='+jQuery("#direccion_remitente").val());
        });
        
        });
        
        jQuery(".check_bultos_defecto").on('click', function () {
            bulto_defecto = 1;
            if (jQuery(this).is(':checked')) {                
                jQuery(this).parents('.caja_bultos_defecto').find('.bultos_defecto').append(crear_botones(jQuery(this)));
                jQuery(this).parents('.caja_bultos_defecto').find('.bultos_defecto').append(crear_bultos_defecto());                
                 jQuery(".check_bultos_defecto").not(this).prop('checked',false);
                 jQuery('.bultos_defecto').not(jQuery(this).parents('.caja_bultos_defecto').find('.bultos_defecto')).html('');
                 update_link_tarifas(jQuery(this).attr('id').split("_")[1]);
                
            } else {                
                jQuery('.bultos_defecto').html('');
            }
        });
    });