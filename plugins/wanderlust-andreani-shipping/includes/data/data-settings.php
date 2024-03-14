<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Array of settings
 */
return array(
	'enabled'           => array(
		'title'           => __( 'Activar Andreani', 'woocommerce-shipping-andreani' ),
		'type'            => 'checkbox',
		'label'           => __( 'Activar este método de envió', 'woocommerce-shipping-andreani' ),
		'default'         => 'no'
	),

	'debug'      				=> array(
		'title'           => __( 'Modo Depuración', 'woocommerce-shipping-andreani' ),
		'label'           => __( 'Activar modo depuración', 'woocommerce-shipping-andreani' ),
		'type'            => 'checkbox',
		'default'         => 'no',
		'desc_tip'    => true,
		'description'     => __( 'Activar el modo de depuración para mostrar información de depuración en la compra/pago y envío.', 'woocommerce-shipping-andreani' )
	),

	'title'             => array(
		'title'           => __( 'Título', 'woocommerce-shipping-andreani' ),
		'type'            => 'text',
		'description'     => __( 'Controla el título que el usuario ve durante el pago.', 'woocommerce-shipping-andreani' ),
		'default'         => __( 'Andreani', 'woocommerce-shipping-andreani' ),
		'desc_tip'        => true
	),
	
   'origen'           => array(
		'title'           => __( 'Detalles de Envío', 'woocommerce-shipping-andreani' ),
		'type'            => 'title',
		'description'     => __( 'Dirección de envío / sucursal origen', 'woocommerce-shipping-andreani' ),
    ),
	 
	'origin'            => array(
		'title'           => __( 'Código Postal', 'woocommerce-shipping-andreani' ),
		'type'            => 'text',
		'description'     => __( 'Ingrese el código postal del <strong> remitente </ strong>.', 'woocommerce-shipping-andreani' ),
		'default'         => '',
		'desc_tip'        => true
    ),
	
 
 
   'api'              => array(
		'title'           => __( 'Configuración de la API', 'woocommerce-shipping-andreani' ),
		'type'            => 'title',
		'description'     => __( '', 'woocommerce-shipping-andreani' ),
    ),
	
   'api_key'          => array(
		'title'           => __( 'Wanderlust API Key', 'woocommerce-shipping-andreani' ),
		'type'            => 'text',
		'description'     => __( 'Wanderlust API Key', 'woocommerce-shipping-andreani' ),
		'default'         => __( 'iQ2DxXjPL0SbceFY5', 'woocommerce-shipping-andreani' ),
    'placeholder' => __( 'iQ2DxXjPL0SbceFY5', 'meta-box' ),
    ),
	
   'api_user'         => array(
		'title'           => __( 'User', 'woocommerce-shipping-andreani' ),
		'type'            => 'text',
		'description'     => __( 'Username', 'woocommerce-shipping-andreani' ),
		'default'         => __( '', 'woocommerce-shipping-andreani' ),
    'placeholder' => __( '', 'meta-box' ),
    ),
	
   'api_password'     => array(
		'title'           => __( 'Password', 'woocommerce-shipping-andreani' ),
		'type'            => 'text',
		'description'     => __( 'Password', 'woocommerce-shipping-andreani' ),
		'default'         => __( '', 'woocommerce-shipping-andreani' ),
    'placeholder' => __( '', 'meta-box' ),
    ),
	
   'api_nrocuenta'     => array(
		'title'           => __( 'Código de cliente', 'woocommerce-shipping-andreani' ),
		'type'            => 'text',
		'description'     => __( 'CL0003750', 'woocommerce-shipping-andreani' ),
		'default'         => __( '', 'woocommerce-shipping-andreani' ),
    'placeholder' => __( '', 'meta-box' ),
    ),	
	
   'api_confirmarretiro' => array(
		'title'           => __( 'Entorno', 'woocommerce-shipping-andreani' ),
		'type'            => 'select',
		'description'     => __( 'Pendiente: el envío quedará alojado en el Carrito de Envíos de e-Pak a la espera de la confirmación del mismo. Directo: la confirmación será instantánea.', 'woocommerce-shipping-andreani' ),
		'default'         => '',
		'class'           => 'packing_method',
		'options'         => array(
			'prod'       => __( 'Producción', 'woocommerce-shipping-andreani' ),
			'test'       => __( 'Testeo', 'woocommerce-shipping-andreani' ),
		),		
		'desc_tip'        => true
    ),
	
   'ajuste_precio'    => array(
		'title'           => __( 'Ajustar Costos %', 'woocommerce-shipping-andreani' ),
		'type'            => 'text',
		'description'     => __( 'Agregar costo extra al precio. Ingresar valor numérico.', 'woocommerce-shipping-andreani' ),
		'default'         => __( '', 'woocommerce-shipping-andreani' ),
    'placeholder' => __( '1', 'meta-box' ),		
    ),	

		'mercado_pago'      => array(
				'title'           => __( 'Modo Mercado Pago', 'woocommerce-shipping-andreani' ),
				'label'           => __( 'No agregar el costo de envio en el Total.', 'woocommerce-shipping-andreani' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'desc_tip'    => true,
				'description'     => __( 'Activar el modo de Mercado pago para no agregar costo de envio en el Total.', 'woocommerce-shipping-andreani' )
		),	
	
 		'redondear_total'      => array(
				'title'           => __( 'Ajustar Totales', 'woocommerce-shipping-andreani' ),
				'label'           => __( 'Mostrar costos totales sin decimales.', 'woocommerce-shipping-andreani' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'desc_tip'    => true,
				'description'     => __( 'Mostrar costos totales sin decimales. Ej: $56.96 a $57', 'woocommerce-shipping-andreani' )
		),	

    'packing'           => array(
		'title'           => __( 'Contratos', 'woocommerce-shipping-andreani' ),
		'type'            => 'title',
		'description'     => __( 'Los siguientes ajustes determinan cómo los artículos se embalan antes de ser enviado a Andreani.', 'woocommerce-shipping-andreani' ),
    ),

	'packing_method'   => array(
		'title'           => __( 'Método Embalaje', 'woocommerce-shipping-andreani' ),
		'type'            => 'select',
		'default'         => '',
		'class'           => 'packing_method',
		'options'         => array(
			'per_item'       => __( 'Por defecto: artículos individuales', 'woocommerce-shipping-andreani' ),
		),
	),

 	'services'  => array(
		'type'            => 'service'
	),

);