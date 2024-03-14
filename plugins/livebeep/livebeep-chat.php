<?php

/*
Plugin Name: LiveBeep Chat
Plugin URI: http://www.livebeep.com
Description: LiveBeep.com Chat brings chat, offline email, and help desk features to your WordPress website.
Version: 2.0.1
Author: LiveBeep.com
Author URI: http://www.livebeep.com/
*/

define('LIVEBEEP_BASE_URL',     'https://www.livebeep.com/');
define('LIVEBEEP_TEXTDOMAIN', 	'livebeep');
define('LIVEBEEP_ICON', 		'https://www.livebeep.com/img/smallicon.png');

add_action('init', 'livebeep_init');
add_action('wp_head', 'livebeep_script');

function livebeep_init() {
	load_plugin_textdomain( LIVEBEEP_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages' );
	if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'livebeep_create_menu');
    } 
}

function livebeep_create_menu() {
	add_menu_page('Account Configuration', 'LiveBeep Chat', 'administrator', 'livebeep_setup', 'livebeep_setup', LIVEBEEP_ICON );
}

function livebeep_setup() {
	
	$str = '<br><br>
	<img src="https://www.livebeep.com/img/logo.png" alt="">
	<h2><strong>¡Gracias por instalar nuestro Plugin!</strong></h2>
	<h4>Si todavía no te has registrado, crea tu cuenta y empieza a usar Livebeep:<br>
	<a href="https://www.livebeep.com/prueba-gratuita/?source=wordpress" target="_blank">Crear cuenta</a>
	<br>
	<br>Si ya tienes una cuenta de Livebeep, inicia sesión con tu usuario y contraseña:<br>
	<a href="https://www.livebeep.com/login/?lang=es">Iniciar sesión</a>
	</h4>
	<h4>Al ingresar por primera vez, Livebeep se activará y la invitación de contacto ya se mostrará en tu sitio web.<br>
	Si tienes problemas, consulta nuestro soporte técnico:<br>
	<a href="https://www.livebeep.com/guia-asistencia/?lang=es">Soporte Técnico Livebeep</a>
	</h4>
	<br> 
	';

    echo $str;
}

function livebeep_script() {
    

	$str =  "<!--Start of LiveBeep Script-->"."\n".
		   	"<script type=\"text/javascript\">"."\n".
		   		"(function(d,s,id){"."\n".
		   			"if(d.getElementById(id)){return;}"."\n".
			   		"var u='//www.livebeep.com/'+d.domain+'/eye.js';"."\n".
			   		"if((h=d.location.href.split(/#ev!/)[1])) u += '?_e=' +h;"."\n".
			   		"else if((r=/.*\_evV=(\w+)\b.*/).test(c=d.cookie) ) u += '?_v='+c.replace(r,'$1');"."\n".
			   		"var js = d.createElement(s);"."\n".
		   			"js.src = u;js.id = id;"."\n".
		   			"var fjs = d.getElementsByTagName(s)[0];"."\n".
		   			"fjs.parentNode.insertBefore(js, fjs);"."\n".
			   	"})(document,'script','livebeep-script');"."\n".
		   	"</script>"."\n".
		   	"<!--End of LiveBeep Script-->"."\n";

	echo $str;
}


?>