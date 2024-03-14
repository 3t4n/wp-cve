var php_vars_maps_js = new Object();
grupoimpultec_destruir_ls('select_oficinas_destino');
grupoimpultec_destruir_ls('entrega_oficina_destino');
function inicializar_mapa(lat, lng, texto, div_mapa) {

    if (isNaN(lat) || isNaN(lng))
        return;
    var myLatLng = {lat: lat, lng: lng};
    var map = new google.maps.Map(document.getElementById(div_mapa), {
        center: new google.maps.LatLng(myLatLng),
        zoom: 15
    });
    var contentString = texto;
    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Oficina'
    });
    marker.addListener('click', function () {
        infowindow.open(map, marker);
    });
}

function popular_mapa(api_server, id_agencia, id_agencia_madre, codigo_postal_oficina, select, div, id_pais = 1) {
    datos = {
        'id_agencia': id_agencia,
        'codigo_postal_oficina': codigo_postal_oficina,
        'id_pais': id_pais
    };

    my_url = 'https://www.' + api_server + '/json_interface/localizar_oficina/?callback=?';
    jQuery.getJSON(my_url, datos, function (response) {
        valor_inicial = [];
        jQuery.each(response, function (i, val) {
            if (i == 1) {
                valor_inicial.nombre_oficina = val.nombre_oficina;
                valor_inicial.latitud = val.latitud;
                valor_inicial.longitud = val.longitud;
                valor_inicial.direccion = val.direccion;
            }

            jQuery('#' + select).append(jQuery('<option>', {
                value: val.id_oficina,
                text: val.id_oficina + ': ' + val.nombre_oficina + ', ' + val.direccion,
                latitud: val.latitud,
                longitud: val.longitud
            }));
        });
        inicializar_mapa(parseFloat(valor_inicial.latitud), parseFloat(valor_inicial.longitud), valor_inicial.nombre_oficina + ', ' + valor_inicial.direccion, div);
    });
    if (id_agencia_madre == 1) {
        jQuery(function () {
            jQuery("#entrega_oficina_destino").prop("checked", false);
        });
        jQuery(function () {
            jQuery("#div_activar_desactivar_mapa_entrega").show();
            grupoimpultec_almacenar_ls('select_oficinas_destino');
        });
        jQuery(function () {
            jQuery("#div_map_oficinas_destino").hide();
            grupoimpultec_destruir_ls('select_oficinas_destino');
            grupoimpultec_destruir_ls('entrega_oficina_destino');
        });
    } else {
        jQuery(function () {
            jQuery("#entrega_oficina_destino").prop("checked", true);
        });
        jQuery(function () {
            jQuery("#div_activar_desactivar_mapa_entrega").hide();
            grupoimpultec_destruir_ls('select_oficinas_destino');
        });
        jQuery(function () {
            jQuery("#div_map_oficinas_destino").show();

        });
    }
    grupoimpultec_almacenar_ls('select_oficinas_destino');
    grupoimpultec_almacenar_ls('entrega_oficina_destino');

}

jQuery(document).ready(function (jQuery) {
    jQuery("#select_oficinas_destino").on('change', function () {
        latitud = (jQuery(this).find(":selected").attr('latitud'));
        longitud = (jQuery(this).find(":selected").attr('longitud'));
        texto = (jQuery(this).find(":selected").val());
        inicializar_mapa(parseFloat(latitud), parseFloat(longitud), texto, 'map_oficinas_destino');
    });    
    jQuery('[name="calc_shipping"]').on('click', function () {
        jQuery(document).ajaxComplete(function () {
            popular_mapa(php_vars_maps_js.api_server,php_vars_maps_js.id_agencia, php_vars_maps_js.id_agencia_madre, php_vars_maps_js.codigo_postal_oficina, php_vars_maps_js.select_oficinas_destino, php_vars_maps_js.map_oficinas_destino);
        });
    });
    if (jQuery('#entrega_oficina_destino').length > 0) {
        jQuery('#entrega_oficina_destino').on('change', function () {
            if (jQuery('#entrega_oficina_destino').is(':checked')) {
                jQuery('#div_map_oficinas_destino').show();
                jQuery(document).ajaxComplete(function () {
                    popular_mapa(php_vars_maps_js.api_server,php_vars_maps_js.id_agencia, php_vars_maps_js.id_agencia_madre, php_vars_maps_js.codigo_postal_oficina, php_vars_maps_js.select_oficinas_destino, php_vars_maps_js.map_oficinas_destino);
                });
            } else {
                jQuery('#div_map_oficinas_destino').hide();
                grupoimpultec_destruir_ls('select_oficinas_destino');
                grupoimpultec_destruir_ls('entrega_oficina_destino');
            }
        });
    }
});
function establecer_oficina_seleccionada_cliente(oficina_seleccionada_cliente) {
    setTimeout(function () {
        jQuery('#select_oficinas_destino option[value="' + oficina_seleccionada_cliente + '"]').prop("selected", true);
    }, 1000);
}
function establecer_entrega_destino() {
    setTimeout(function () {
        jQuery('#no_recoger').attr('checked', true);
    }, 1000);
}

function grupoimpultec_almacenar_ls(nombre) {
    setTimeout(function () {
        var dato = jQuery('#' + nombre).val();
        localStorage.setItem(nombre, dato);
    }, 500);
}

function grupoimpultec_obtener_ls(nombre) {
    return localStorage.getItem(nombre);
}

function grupoimpultec_destruir_ls(nombre) {
    setTimeout(function () {
        localStorage.removeItem(nombre);
    }, 500);
}





