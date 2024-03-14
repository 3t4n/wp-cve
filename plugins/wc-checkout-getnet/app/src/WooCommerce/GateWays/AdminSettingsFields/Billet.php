<?php

namespace WcGetnet\WooCommerce\GateWays\AdminSettingsFields;

abstract class Billet {
	
	public static function getBasicFields() {
		return [
			'title' => [
				'title'       => __( 'Título' ),
				'type'        => 'text',
				'description' => __( 'Controla o título que o usuário vê durante o checkout.' ),
				'default'     => 'Getnet Boleto',
				'desc_tip'    => true
			],
			'description' => [
				'title'       => __( 'Descrição' ),
				'type'        => 'text',
				'description' => __( 'Controla a descrição que o usuário vê durante o checkout.' ),
				'default'     => 'Pague com boleto via Getnet.',
				'desc_tip'    => true
			],
			'order_prefix' => [
				'title'       => 'Prefixo do pedido',
				'type'        => 'text',
				'description' => 'Insira um prefixo para os números dos seus pedidos. Se você usar sua conta Getnet para várias lojas, certifique-se de que este prefixo seja único.',
				'default'     => 'WC-GETNET-',
				'desc_tip'    => true
			],
			'expiration_date' => [
				'title'       => __( 'Número de dias para vencimento' ),
				'type'        => 'text',
				'description' => __( 'Dias de vencimento do boleto após impresso.' ),
				'default'     => 5,
				'desc_tip'    => true
			],
			'instructions' => [
				'title'       => __( 'Instruções do boleto' ),
				'type'        => 'textarea',
				'description' => __( 'Instruções para aparecer no boleto.' ),
				'css'         => 'width: 400px;',
				'desc_tip'    => true
			],
			'logs' => [
				'title'       =>  __( 'Logs' ),
				'label'       => __( 'Habilita os logs do boleto.' ),
				'type'        => 'checkbox',
				'description' => __( 'Logs: getnet-billet-order ou getnet-billet-order-error. Para visualizar: WooCommerce > Status > Logs' ),
				'desc_tip'    => true,
				'default'     => 'no'
			]
		];
			
	}

	public static function getDiscountFields()
	{
		return [
			'discount_name' => [
				'title'       => __( 'Nome do Desconto' ),
				'type'        => 'text',
				'description' => __( 'Digite o nome do desconto (Nome padrão: Desconto Getnet).' ),
				'default'     => 'Desconto Getnet',
				'desc_tip'    => true
			],
			'discount_amount' => [
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
