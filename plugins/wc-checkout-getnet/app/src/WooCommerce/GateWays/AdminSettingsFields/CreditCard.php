<?php

namespace WcGetnet\WooCommerce\GateWays\AdminSettingsFields;

abstract class CreditCard {
	
	public static function getBasicFields() {
		return [
			'title' => [
				'title'       => 'Título',
				'type'  	  => 'text',
				'description' => 'Controla o título que o usuário vê durante o checkout.',
				'default'     => 'Getnet Cartão Crédito',
				'desc_tip'    => true
			],
			'description' => [
				'title'       => 'Descrição no Checkout',
				'type'        => 'text',
				'description' => 'Controla a descrição que o usuário vê durante o checkout.',
				'default'     => 'Pague com cartão de crédito via Getnet.',
				'desc_tip'    => true
			],
			'order_prefix' => [
				'title'       => 'Prefixo do pedido',
				'type'        => 'text',
				'description' => 'Insira um prefixo para os números dos seus pedidos. Se você usar sua conta Getnet para várias lojas, certifique-se de que este prefixo seja único.',
				'default'     => 'WC-GETNET-',
				'desc_tip'    => true
			],
			'soft_descriptor' => [
				'title'       => __( 'Descrição na fatura do comprador', 'wc_getnet' ),
				'type'        => 'text',
				'description' => 'Texto exibido na fatura do cartão do comprador.',
				'default'     => '',
				'desc_tip'    => true,
				'custom_attributes' => [
					'maxlength'   => 22
				]
			],
			'creditcard_image' => [
				'title'       =>  __( 'Imagem de cartão de crédito no checkout' ),
				'label'       => __( 'Habilita imagem de pré visualização do cartão de crédito no checkout.' ),
				'type'        => 'checkbox',
				'description' => __( 'Habilita imagem de pré visualização do cartão de crédito no checkout.' ),
				'desc_tip'    => true,
				'default'     => 'no'
			],
			'logs' => [
				'title'       =>  __( 'Logs' ),
				'label'       => __( 'Habilita os logs do cartão de crédito.' ),
				'type'        => 'checkbox',
				'description' => __( 'Logs: getnet-creditcard-order ou getnet-creditcard-order-error. Para visualizar: WooCommerce > Status > Logs' ),
				'desc_tip'    => true,
				'default'     => 'no'
			]
		];
	}

	public static function getInstallmensFields() {
		return [
			'min_value_from_installments' => [
				'title'             => __( 'Valor mínimo para parcelar a compra', 'wc_getnet' ),
				'type'              => 'text',
				'description'       => __( 'Valor mínimo para compras parceladas.', 'wc_getnet' ),
				'desc_tip'          => true,
				'placeholder'       => '0,00',
				'custom_attributes' => [
					'data-field'        => 'min-value-from-installments',
					'data-mask'         => '##0,00',
					'data-mask-reverse' => 'true',
				],
			],
			'installments' => [
				'title'             => __( 'Quantidade máxima de parcelas', 'wc_getnet' ),
				'type'              => 'select',
				'description'       => __( 'Seleciona a quantidade máxima de parcelas para o pagamento.', 'wc_getnet' ),
				'desc_tip'          => true,
				'default'           => 12,
				'options'           => array_combine( range( 1, 12 ), range( 1, 12 ) ),
				'custom_attributes' => [
					'data-field' => 'installments-maximum',
				],
			],
			'installments_interest' => [
				'title'             => __( 'Juros a partir da parcela', 'wc_getnet' ),
				'type'              => 'select',
				'description'       => __( 'Define a partir de qual parcela será aplicado o juros.', 'wc_getnet' ),
				'desc_tip'          => true,
				'default'           => 2,
				'options'           => array_combine( range( 1, 12 ), range( 1, 12 ) ),
				'custom_attributes' => [
					'data-field' => 'installments-interest',
				],
			],
			'installments_initial_interest' => [
				'title'             => __( 'Valor inicial dos juros', 'wc_getnet' ),
				'type'              => 'text',
				'description'       => __( 'Valor percentual dos juros a serem aplicados na parcela.', 'wc_getnet' ),
				'desc_tip'          => true,
				'placeholder'       => '0,00',
				'custom_attributes' => [
					'data-field'        => 'installments-initial-interest',
					'data-mask'         => '##0,00',
					'data-mask-reverse' => 'true',
				],
			],
			'installments_increase_interest' => [
				'title'             => __( 'Incremento nos juros', 'wc_getnet' ),
				'type'              => 'text',
				'description'       => __( 'Valor percentual dos juros incrementados em cada parcela.', 'wc_getnet' ),
				'desc_tip'          => true,
				'placeholder'       => '0,00',
				'custom_attributes' => [
					'data-field'        => 'installments-increase-interest',
					'data-mask'         => '##0,00',
					'data-mask-reverse' => 'true',
				],
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

	public static function getInstallmentsByFlagFields() {
		return [
			'installments_by_flag' => [
				'title' => __( 'Settings by flag', 'wc_getnet' ),
				'type'  => 'installments_by_flag',
			],
		];
	}
}