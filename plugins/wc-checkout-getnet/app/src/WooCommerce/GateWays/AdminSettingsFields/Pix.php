<?php

namespace WcGetnet\WooCommerce\GateWays\AdminSettingsFields;

abstract class Pix {
	
	public static function getBasicFields() {
		return [
			'title' => [
				'title'       => __( 'Título' ),
				'type'        => 'text',
				'description' => __( 'Controla o título que o usuário vê durante o checkout.' ),
				'default'     => 'Getnet Pix',
				'desc_tip'    => true
			],
			'description' => [
				'title'       => __( 'Descrição' ),
				'type'        => 'text',
				'description' => __( 'Controla a descrição que o usuário vê durante o checkout.' ),
				'default'     => 'Pague com pix via Getnet.',
				'desc_tip'    => true
			],
			'order_prefix' => [
				'title'       => __( 'Prefixo do pedido' ),
				'type'        => 'text',
				'description' => __( 'Insira um prefixo para os números dos seus pedidos. Se você usar sua conta Getnet para várias lojas, certifique-se de que este prefixo seja único.' ),
				'default'     => 'WC-GETNET-',
				'desc_tip'    => true
			],
			'qrcode_expiration_time' => [
				'title'       =>  __( 'Tempo de expiração do QR Code' ),
				'type'        => 'number',
				'description' => __( 'Controla o tempo que o QR Code fica válido em segundos.' ),
				'default'     => '180',
				'desc_tip'    => true
			],
			'logs' => [
				'title'       =>  __( 'Logs' ),
				'label'       => __( 'Habilita os logs do pix.' ),
				'type'        => 'checkbox',
				'description' => __( 'Logs: getnet-pix-order ou getnet-pix-order-error. Para visualizar: WooCommerce > Status > Logs' ),
				'desc_tip'    => true,
				'default'     => 'no'
			]
		];
	}

	public static function getDiscountFields()
	{
		return [
			'discount_pix_name' => [
				'title'       => __( 'Nome do Desconto' ),
				'type'        => 'text',
				'description' => __( 'Digite o nome do desconto (Nome padrão: Desconto Getnet).' ),
				'default'     => 'Desconto Getnet',
				'desc_tip'    => true
			],
			'discount_pix_amount' => [
				'title'       => __( 'Valor do Desconto' ),
				'type'        => 'text',
				'description' => __( 'Digite o valor do desconto (Exemplo: 10,00%), desconto em porcentagem.' ),
				'placeholder' => '0,00',
				'desc_tip'    => true
			]
		];
	}

	public static function getHeaderFields() {
		return [
			'enabled' => [
				'title'       => '',
				'label'       => 'Habilitar',
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
				'css'         => 'header-component',
			]
		];
	}
}
