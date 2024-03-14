<?php
class WC_Esto_Pay_Later_Payment extends WC_Esto_Payment {

	function __construct() {

		$this->id            = 'pay_later';
		$this->method_title  = __( 'Pay Later', 'woo-esto' );
		$this->schedule_type = 'PAY_LATER';

		parent::__construct();

		$this->admin_page_title = __( 'ESTO Pay Later payment gateway', 'woo-esto' );
		$this->min_amount       = $this->get_option( 'min_amount', 0.1 );
		$this->max_amount       = $this->get_option( 'max_amount', 10000 );
	}

	function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields = [
			'enabled' => [
				'title'   => __( 'Enable/Disable', 'woo-esto' ),
				'type'    => 'checkbox',
				'label'   => __( 'Pay Later is a campaign of ESTO. Contact ESTO support for additional information.', 'woo-esto' ),
				'default' => 'no',
			],
			'title' => [
				'title'       => __( 'Title', 'woo-esto' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-esto' ),
				'default'     => __( 'Buy now, pay later!', 'woo-esto' ),
			],
			'description' => [
                'title'         => __( 'Description', 'woo-esto' ),
                'type'          => 'textarea',
                'description'   => __( 'This controls the description which the user sees during checkout.', 'woo-esto' ),
                'default'       => __( 'Pay Later gives you an extra 30 days to pay for your order. Get the service or goods instantly and pay whenever you like within the next 30 days, with no additional fees!', 'woo-esto' ),
            ],
		]
		+ $this->description_logos
		+ [
			'show_logo'  => $this->form_fields['show_logo'],
			'logo'       => $this->form_fields['logo'],
			'min_amount' => $this->form_fields['min_amount'],
			'max_amount' => $this->form_fields['max_amount'],
		];

		$this->form_fields['min_amount']['default'] = 0.1;
		$this->form_fields['max_amount']['default'] = 10000;
	}
}
