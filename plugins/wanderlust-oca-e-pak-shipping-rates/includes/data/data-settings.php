<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Array of settings
 */
return array(
	'enabled'           => array(
		'title'           => __( 'Activar OCA', 'woocommerce-shipping-oca' ),
		'type'            => 'checkbox',
		'label'           => __( 'Activar este método de envió', 'woocommerce-shipping-oca' ),
		'default'         => 'no'
	),

	'debug'      				=> array(
		'title'           => __( 'Modo Depuración', 'woocommerce-shipping-oca' ),
		'label'           => __( 'Activar modo depuración', 'woocommerce-shipping-oca' ),
		'type'            => 'checkbox',
		'default'         => 'no',
		'desc_tip'    => true,
		'description'     => __( 'Activar el modo de depuración para mostrar información de depuración en la compra/pago y envío.', 'woocommerce-shipping-oca' )
	),

	'title'             => array(
		'title'           => __( 'Título', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Controla el título que el usuario ve durante el pago.', 'woocommerce-shipping-oca' ),
		'default'         => __( 'OCA', 'woocommerce-shipping-oca' ),
		'desc_tip'        => true
	),
	
   'origen'           => array(
		'title'           => __( 'Detalles de Origen', 'woocommerce-shipping-oca' ),
		'type'            => 'title',
		'description'     => __( 'Dirección de retiro / Sucursal origen - Todos los campos son obligatorios.', 'woocommerce-shipping-oca' ),
    ),
	
	'origin_contacto' 	=> array(
		'title'           => __( 'Nombre y Apellido', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Datos de Contacto', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),

	'origin_email' 	=> array(
		'title'           => __( 'Email', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( '', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),	
	
	'origin_calle'      => array(
		'title'           => __( 'Calle de Origen', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( '', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),
	
	'origin_numero'     => array(
		'title'           => __( 'Número de Calle', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( '', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),
	
	'origin_piso'       => array(
		'title'           => __( 'Piso', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( '', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),
	
	'origin_depto'      => array(
		'title'           => __( 'Depto', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( '', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),
	
	'origin'            => array(
		'title'           => __( 'Código Postal', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Ingrese el código postal del <strong> remitente </ strong>.', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),
	
	'origin_localidad' 	=> array(
		'title'           => __( 'Localidad', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( '', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),		
	
	'origin_provincia' 	=> array(
		'title'           => __( 'Provincia', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( '', 'woocommerce-shipping-oca' ),
		'default'         => '',
		'desc_tip'        => true
    ),			
	
   'api'              => array(
		'title'           => __( 'Configuración de la API', 'woocommerce-shipping-oca' ),
		'type'            => 'title',
		'description'     => __( '', 'woocommerce-shipping-oca' ),
    ),
	
   'api_key'          => array(
		'title'           => __( 'Wanderlust API Key', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Wanderlust API Key', 'woocommerce-shipping-oca' ),
		'default'         => __( 'wEWW5yPc2zEpsXOjD', 'woocommerce-shipping-oca' ),
    	'placeholder' => __( 'wEWW5yPc2zEpsXOjD', 'meta-box' ),
    ),
	
   'api_user'         => array(
		'title'           => __( 'Usuario en OCA', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Cuenta de mail con la que ingresa a OCA.', 'woocommerce-shipping-oca' ),
		'default'         => __( '', 'woocommerce-shipping-oca' ),
    'placeholder' => __( '', 'meta-box' ),
    ),
	
   'api_password'     => array(
		'title'           => __( 'Password de OCA', 'woocommerce-shipping-oca' ),
		'type'            => 'password',
		'description'     => __( 'Password con el que ingresa a OCA.', 'woocommerce-shipping-oca' ),
		'default'         => __( '', 'woocommerce-shipping-oca' ),
    'placeholder' => __( '', 'meta-box' ),
    ),
	
   'api_nrocuenta'     => array(
		'title'           => __( 'OCA Nro Cuenta', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Nro Cuenta en OCA - Ej: 111111/000', 'woocommerce-shipping-oca' ),
		'default'         => __( '', 'woocommerce-shipping-oca' ),
    'placeholder' => __( '', 'meta-box' ),
    ),	
	
   'cuit_number'      => array(
		'title'           => __( 'Número de CUIT', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Ingrese su CUIT de esta forma: 00-00000000-0', 'woocommerce-shipping-oca' ),
		'default'         => __( '', 'woocommerce-shipping-oca' ),
    'placeholder' => __( '00-00000000-0', 'meta-box' ),
    ),
 	
   'ajuste_precio'    => array(
		'title'           => __( 'Ajustar costos de envío % (porcentual)', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Agregar costo extra al precio. Ingresar valor numérico mayor a 0.', 'woocommerce-shipping-oca' ),
		'default'         => __( '', 'woocommerce-shipping-oca' ),
    'placeholder' => __( '1', 'meta-box' ),		
    ),	
	
	 'envio_gratis'    => array(
		'title'           => __( 'Envio gratis', 'woocommerce-shipping-oca' ),
		'type'            => 'text',
		'description'     => __( 'Envio gratis para pedidos mayores a', 'woocommerce-shipping-oca' ),
		'default'         => __( '9999999999999', 'woocommerce-shipping-oca' ),
    'placeholder' => __( '1', 'meta-box' ),		
    ),	
	
	

		'mercado_pago'      => array(
				'title'           => __( 'No cobrar el costo de envío', 'woocommerce-shipping-oca' ),
				'label'           => __( 'No agregar el costo de envío en el Total (carrito/checkout).', 'woocommerce-shipping-oca' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'desc_tip'    => true,
				'description'     => __( 'Al activar este modulo, no se agregara el costo de envío en el Total (carrito/checkout).', 'woocommerce-shipping-oca' )
		),	
	
 		'redondear_total'      => array(
				'title'           => __( 'Ajustar Totales', 'woocommerce-shipping-oca' ),
				'label'           => __( 'Mostrar costos totales sin decimales. Ej: $56.96 a $57', 'woocommerce-shipping-oca' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'desc_tip'    => true,
				'description'     => __( 'Mostrar costos totales sin decimales. Ej: $56.96 a $57', 'woocommerce-shipping-oca' )
		),	

    'packing'           => array(
		'title'           => __( 'Paquetes y Operativas', 'woocommerce-shipping-oca' ),
		'type'            => 'title',
		'description'     => __( 'Los siguientes ajustes determinan cómo los artículos se embalan antes de ser enviado a la OCA. <a href="https://shop.wanderlust-webdesign.com/que-son-las-operativas-de-oca/" target="_blank">¿De donde saco las operativas? </a>', 'woocommerce-shipping-oca' ),
    ),

	'packing_method'   => array(
		'title'           => __( 'Método Embalaje', 'woocommerce-shipping-oca' ),
		'type'            => 'select',
		'default'         => '',
		'class'           => 'packing_method',
		'options'         => array(
			'per_item'       => __( 'Por defecto: artículos individuales.', 'woocommerce-shipping-oca' ),
		),
	),

 	'services'  => array(
		'type'            => 'service'
	),

);