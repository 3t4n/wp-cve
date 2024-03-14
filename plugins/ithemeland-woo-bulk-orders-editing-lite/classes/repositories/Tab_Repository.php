<?php

namespace wobel\classes\repositories;

use wobel\classes\helpers\Generator;
use wobel\classes\helpers\Operator;
use wobel\classes\helpers\Meta_Field as Meta_Field_Helper;


class Tab_Repository
{
    private $product_repository;
    private $meta_field_repository;
    private $meta_fields;

    public function __construct()
    {
        $this->meta_field_repository = new Meta_Field();
        $this->meta_fields = $this->meta_field_repository->get();

        $this->product_repository = new Product();
    }

    public function get_bulk_edit_form_tabs_title()
    {
        return [
            'general' => esc_html__("General", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'billing' => esc_html__("Billing", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'shipping' => esc_html__("Shipping", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'pricing' => esc_html__("Pricing", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'other_fields' => esc_html__("Other Fields", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'custom_fields' => esc_html__("Custom Fields", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public function get_bulk_edit_form_tabs_content()
    {
        $order_repository = Order::get_instance();
        $order_statuses = $order_repository->get_order_statuses();
        $payment_methods = $order_repository->get_payment_methods();
        $payment_methods['other'] = __('Other', 'ithemeland-woocommerce-bulk-orders-editing-lite');

        $custom_fields = $this->get_bulk_edit_custom_fields();

        return [
            'general' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'selected wobel-tab-content-item',
                    'data-content' => 'general'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'date_created' => [
                        'wrap_attributes' => 'data-name="date_created" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-created-date'], esc_html__('Date', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::input_field([
                                'class' => 'wobel-datetimepicker',
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-created-date',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Date ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'date_paid' => [
                        'wrap_attributes' => 'data-name="date_paid" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-paid-date'], esc_html__('Paid Date', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::input_field([
                                'class' => 'wobel-datetimepicker',
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-paid-date',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Paid Date ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'order_status' => [
                        'wrap_attributes' => 'data-name="order_status" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-status'], esc_html__('Status', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'id' => 'wobel-bulk-edit-form-order-status',
                                'data-field' => 'value',
                                'title' => esc_html__('Select Status ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], $order_statuses, true),
                        ]
                    ],
                    'customer_note' => [
                        'wrap_attributes' => 'data-name="customer_note" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-customer-note'], esc_html__('Customer Note', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::textarea_field([
                                'class' => 'wobel-input-md',
                                'id' => 'wobel-bulk-edit-form-customer-note',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Note ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'order_notes' => [
                        'wrap_attributes' => 'data-name="order_notes" data-type="order_notes"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-notes'], esc_html__('Order Notes', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'id' => 'wobel-bulk-edit-form-order-notes-operator',
                                'data-field' => 'operator',
                            ], [
                                'private' => esc_html__('Private note', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                                'customer' => esc_html__('Note to customer', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            ]),
                            Generator::textarea_field([
                                'class' => 'wobel-input-md',
                                'id' => 'wobel-bulk-edit-form-order-notes',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Note ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                ]
            ],
            'billing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'billing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'billing_first_name' => [
                        'wrap_attributes' => 'data-name="billing_first_name" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-billing-first-name'], esc_html__('Billing First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'id' => 'wobel-bulk-edit-form-order-billing-first-name-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-billing-first-name',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Billing First Name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'billing_last_name' => [
                        'wrap_attributes' => 'data-name="billing_last_name" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-billing-last-name'], esc_html__('Billing Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'id' => 'wobel-bulk-edit-form-order-billing-last-name-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-billing-last-name',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Billing Last Name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'billing_address_1' => [
                        'wrap_attributes' => 'data-name="billing_address_1" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-billing-address-1'], esc_html__('Billing Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'id' => 'wobel-bulk-edit-form-order-billing-address-1-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-billing-address-1',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Billing Address 1 ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'billing_address_2' => [
                        'wrap_attributes' => 'data-name="billing_address_2" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-billing-address-2'], esc_html__('Billing Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'id' => 'wobel-bulk-edit-form-order-billing-address-2-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-billing-address-2',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Billing Address 2 ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    '_billing_city' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Billing City', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Billing City ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_billing_company' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Billing Company', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Billing Company ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_billing_country' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Billing Country', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                            ], [], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_billing_state' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Billing State', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                            ], [], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_billing_email' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Billing Email', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Billing Email ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_billing_phone' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Billing Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Billing Phone ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_billing_postcode' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Billing Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Billing Postcode ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                ]
            ],
            'shipping' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'shipping'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'shipping_first_name' => [
                        'wrap_attributes' => 'data-name="shipping_first_name" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-shipping-first-name'], esc_html__('Shipping First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'id' => 'wobel-bulk-edit-form-order-shipping-first-name-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-shipping-first-name',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Shipping First Name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'shipping_last_name' => [
                        'wrap_attributes' => 'data-name="shipping_last_name" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-shipping-last-name'], esc_html__('Shipping Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'id' => 'wobel-bulk-edit-form-order-shipping-last-name-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-shipping-last-name',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Shipping Last Name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'shipping_address_1' => [
                        'wrap_attributes' => 'data-name="shipping_address_1" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-shipping-address-1'], esc_html__('Shipping Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'id' => 'wobel-bulk-edit-form-order-shipping-address-1-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-shipping-address-1',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Shipping Address 1 ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    'shipping_address_2' => [
                        'wrap_attributes' => 'data-name="shipping_address_2" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-shipping-address-2'], esc_html__('Shipping Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'id' => 'wobel-bulk-edit-form-order-shipping-address-2-operator',
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'id' => 'wobel-bulk-edit-form-order-shipping-address-2',
                                'data-field' => 'value',
                                'placeholder' => esc_html__('Shipping Address 2 ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                        ]
                    ],
                    '_shipping_city' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Shipping City', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Shipping City ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_shipping_company' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Shipping Company', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Shipping Company ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_shipping_country' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Shipping Country', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md wobel-order-country',
                                'disabled' => 'disabled',
                                'title' => esc_html__('Shipping Country ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], [], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_shipping_state' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Shipping State', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                            ], [], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_shipping_postcode' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Shipping Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_text()),
                            Generator::input_field([
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Shipping Postcode ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                ]
            ],
            'pricing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'pricing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_order_currency' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Order Currency', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Order Currency ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], [], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_order_discount' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Cart Discount', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_number()),
                            Generator::input_field([
                                'type' => 'number',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Cart Discount ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_order_discount_tax' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Cart Discount Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_number()),
                            Generator::input_field([
                                'type' => 'number',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Cart Discount Tax ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_order_total' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Order Total', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'disabled' => 'disabled',
                            ], Operator::edit_number()),
                            Generator::input_field([
                                'type' => 'number',
                                'disabled' => 'disabled',
                                'placeholder' => esc_html__('Order Total ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ]),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                ]
            ],
            'other_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'other_fields'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'created_via' => [
                        'wrap_attributes' => 'data-name="created_via" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-create-via'], esc_html__('Create Via', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'id' => 'wobel-bulk-edit-form-order-create-via',
                                'data-field' => 'value',
                                'title' => esc_html__('Create Via ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], [
                                'checkout' => esc_html__('Checkout', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                                'admin' => esc_html__('Admin', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            ], true),
                        ]
                    ],
                    'payment_method' => [
                        'wrap_attributes' => 'data-name="payment_method" data-type="woocommerce_field"',
                        'html' => [
                            Generator::label_field(['for' => 'wobel-bulk-edit-form-order-payment-method'], esc_html__('Payment Method', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'id' => 'wobel-bulk-edit-form-order-payment-method',
                                'data-field' => 'value',
                                'title' => esc_html__('Payment Method ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], $payment_methods, true),
                        ]
                    ],
                    '_shipping_tax' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Shipping Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                                'title' => esc_html__('Shipping Tax ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], [
                                '1' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                                '0' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_order_shipping' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Order Shipping', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                                'title' => esc_html__('Order Shipping ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], [
                                '1' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                                '0' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_recorded-coupon_usage_counts' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Coupon Usage Counts', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                                'title' => esc_html__('Recorder Coupon Usage Counts ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                                'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_order_stock_reduced' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Order Stock Reduced', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                                'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_prices_include_tax' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Prices Index Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                                'title' => esc_html__('Prices Index Tax ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], [
                                '1' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                                '0' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                    '_recorded_sales' => [
                        'wrap_attributes' => '',
                        'html' => [
                            Generator::label_field([], esc_html__('Recorded Sales', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                            Generator::select_field([
                                'class' => 'wobel-input-md',
                                'disabled' => 'disabled',
                                'title' => esc_html__('Recorded Sales ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], [
                                'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                                'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            ], true),
                            Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                                'class' => 'wobel-short-description'
                            ])
                        ]
                    ],
                ]
            ],
            'custom_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'custom_fields'
                ]),
                'fields_top' => (!empty($custom_fields['top_alert'])) ? $custom_fields['top_alert'] : '',
                'wrapper_end' => Generator::div_field_end(),
                'fields' => (!empty($custom_fields['fields'])) ? $custom_fields['fields'] : []
            ],
        ];
    }

    public function get_filter_form_tabs_title()
    {
        return [
            'filter_general' => esc_html__("General", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'filter_billing' => esc_html__("Billing", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'filter_shipping' => esc_html__("Shipping", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'filter_pricing' => esc_html__("Pricing", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'filter_items' => esc_html__("Items", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'filter_other_fields' => esc_html__("Other Fields", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'filter_custom_fields' => esc_html__("Custom Fields", 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public function get_filter_form_tabs_content()
    {
        $order_repository = Order::get_instance();
        $order_statuses = $order_repository->get_order_statuses();
        $shipping_countries = $order_repository->get_shipping_countries();
        $currencies = $order_repository->get_currencies();
        $payment_methods = $order_repository->get_payment_methods();
        $payment_methods['other'] = esc_html__('Other', 'woocommerce');

        $custom_fields = $this->get_filter_custom_fields();

        return [
            'filter_general' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'selected wobel-tab-content-item',
                    'data-content' => 'filter_general'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'order_ids' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-ids'], esc_html__('Order ID(s)', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-ids-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [
                            'exact' => esc_html__('Exact', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-ids',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('for example: 1,2,3 or 1-10 or 1,2,3|10-20', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                    ],
                    'post_date' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-created-date-from'], esc_html__('Date', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::input_field([
                            'class' => 'wobel-datetimepicker wobel-input-ft wobel-date-from',
                            'data-to-id' => 'wobel-filter-form-order-created-date-to',
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-created-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Date From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::input_field([
                            'class' => 'wobel-datetimepicker wobel-input-ft',
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-created-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Date To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                    ],
                    'post_modified' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-modified-date-from'], esc_html__('Modified Date', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::input_field([
                            'class' => 'wobel-datetimepicker wobel-input-ft wobel-date-from',
                            'data-to-id' => 'wobel-filter-form-order-modified-date-to',
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-modified-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Modified Date From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::input_field([
                            'class' => 'wobel-datetimepicker wobel-input-ft',
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-modified-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Modified Date To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                    ],
                    '_paid_date' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-paid-date-from'], esc_html__('Paid Date', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::input_field([
                            'class' => 'wobel-datetimepicker wobel-input-ft wobel-date-from',
                            'data-to-id' => 'wobel-filter-form-order-paid-date-to',
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-paid-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Paid Date From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::input_field([
                            'class' => 'wobel-datetimepicker wobel-input-ft',
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-paid-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Paid Date To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                    ],
                    '_customer_ip_address' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-customer-ip-address'], esc_html__('Customer IP Address', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-customer-ip-address-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-customer-ip-address',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Customer IP Address ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    'post_status' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-status'], esc_html__('Status', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'multiple' => 'true',
                            'class' => 'wobel-input-md wobel-select2',
                            'id' => 'wobel-filter-form-order-status',
                            'data-field' => 'value',
                            'title' => esc_html__('Select Status ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], $order_statuses, false)
                    ],
                ]
            ],
            'filter_billing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'filter_billing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_billing_first_name' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-first-name'], esc_html__('Billing First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-first-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-first-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing First Name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_last_name' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-last-name'], esc_html__('Billing Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-last-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-last-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Last Name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_address_1' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-address-1'], esc_html__('Billing Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-address-1-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-address-1',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Address 1 ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_address_2' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-address-2'], esc_html__('Billing Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-address-2-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-address-2',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Address 2 ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_city' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-city'], esc_html__('Billing City', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-city-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-city',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing City ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_company' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-company'], esc_html__('Billing Company', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-company-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-company',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Company ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_country' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-country'], esc_html__('Billing Country', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md wobel-order-country',
                            'data-state-target' => '.wobel-filter-form-order-billing-state',
                            'id' => 'wobel-filter-form-order-billing-country',
                            'data-field' => 'value',
                            'title' => esc_html__('Billing Country ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], $shipping_countries, true)
                    ],
                    '_billing_state' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-state'], esc_html__('Billing State', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md wobel-filter-form-order-billing-state',
                            'data-field' => 'value',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Billing State ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [], true),
                        Generator::input_field([
                            'class' => 'wobel-input-md wobel-filter-form-order-billing-state wobel-h43',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('State ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'title' => esc_html__('Billing State ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_email' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-email'], esc_html__('Billing Email', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-email-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-email',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Email ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_phone' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-phone'], esc_html__('Billing Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-phone-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-phone',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Phone ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_billing_postcode' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-billing-postcode'], esc_html__('Billing Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-billing-postcode-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-billing-postcode',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Postcode ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                ]
            ],
            'filter_shipping' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'filter_shipping'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_shipping_first_name' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-first-name'], esc_html__('Shipping First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-shipping-first-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-shipping-first-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping First Name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_shipping_last_name' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-last-name'], esc_html__('Shipping Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-shipping-last-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-shipping-last-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Last Name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_shipping_address_1' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-address-1'], esc_html__('Shipping Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-shipping-address-1-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-shipping-address-1',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Address 1 ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_shipping_address_2' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-address-2'], esc_html__('Shipping Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-shipping-address-2-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-shipping-address-2',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Address 2 ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_shipping_city' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-city'], esc_html__('Shipping City', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-shipping-city-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-shipping-city',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping City ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_shipping_company' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-company'], esc_html__('Shipping Company', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-shipping-company-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-shipping-company',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Company ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_shipping_country' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-country'], esc_html__('Shipping Country', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md wobel-order-country',
                            'data-state-target' => '.wobel-filter-form-order-shipping-state',
                            'id' => 'wobel-filter-form-order-shipping-country',
                            'data-field' => 'value',
                            'title' => esc_html__('Shipping Country ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], $shipping_countries, true)
                    ],
                    '_shipping_state' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-state'], esc_html__('Shipping State', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md wobel-filter-form-order-shipping-state',
                            'data-field' => 'value',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Shipping State ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [], true),
                        Generator::input_field([
                            'class' => 'wobel-input-md wobel-filter-form-order-shipping-state wobel-h43',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('State ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'title' => esc_html__('Shipping State ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                    '_shipping_postcode' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-postcode'], esc_html__('Shipping Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-shipping-postcode-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobel-filter-form-order-shipping-postcode',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Postcode ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ])
                    ],
                ]
            ],
            'filter_pricing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'filter_pricing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_order_currency' => [
                        Generator::label_field([], esc_html__('Order Currency', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'disabled' => 'disabled',
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                            'class' => 'wobel-short-description'
                        ])
                    ],
                    '_order_discount' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-discount-from'], esc_html__('Cart Discount', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::input_field([
                            'class' => 'wobel-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'id' => 'wobel-filter-form-order-discount-from',
                            'placeholder' => esc_html__('Cart Discount From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::input_field([
                            'class' => 'wobel-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'id' => 'wobel-filter-form-order-discount-to',
                            'placeholder' => esc_html__('Cart Discount To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                            'class' => 'wobel-short-description'
                        ])
                    ],
                    '_order_discount_tax' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-discount-tax-from'], esc_html__('Cart Discount Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::input_field([
                            'class' => 'wobel-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'id' => 'wobel-filter-form-order-discount-tax-from',
                            'placeholder' => esc_html__('Cart Discount Tax From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::input_field([
                            'class' => 'wobel-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'id' => 'wobel-filter-form-order-discount-tax-to',
                            'placeholder' => esc_html__('Cart Discount Tax To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                            'class' => 'wobel-short-description'
                        ])
                    ],
                    '_order_total' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-total-from'], esc_html__('Order Total', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::input_field([
                            'class' => 'wobel-input-ft',
                            'type' => 'number',
                            'id' => 'wobel-filter-form-order-total-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Order Total From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                        Generator::input_field([
                            'class' => 'wobel-input-ft',
                            'type' => 'number',
                            'id' => 'wobel-filter-form-order-total-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Order Total To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ]),
                    ],
                ]
            ],
            'filter_items' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'filter_items'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'products' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-products'], esc_html__('Products', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-products-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'id' => 'wobel-filter-form-order-products',
                            'data-field' => 'value',
                            'multiple' => 'true',
                            'class' => 'wobel-select2-products',
                            'data-placeholder' => esc_html__('Select Product', 'ithemeland-woocommerce-bulk-orders-editing-lite') . ' ...',
                        ], []),
                    ],
                    'categories' => [
                        Generator::label_field([], esc_html__('Categories', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'disabled' => 'disabled',
                            'class' => 'wobel-select2-categories',
                            'data-placeholder' => esc_html__('Select Category', 'ithemeland-woocommerce-bulk-orders-editing-lite') . ' ...',
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                            'class' => 'wobel-short-description'
                        ])
                    ],
                    'tags' => [
                        Generator::label_field([], esc_html__('Tags', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'disabled' => 'disabled',
                            'class' => 'wobel-select2-tags',
                            'data-placeholder' => esc_html__('Select Tag', 'ithemeland-woocommerce-bulk-orders-editing-lite') . ' ...',
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                            'class' => 'wobel-short-description'
                        ])
                    ],
                    'taxonomies' => [
                        Generator::label_field([], esc_html__('Taxonomies', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'disabled' => 'disabled',
                            'class' => 'wobel-select2-taxonomies',
                            'data-placeholder' => esc_html__('Select Taxonomy', 'ithemeland-woocommerce-bulk-orders-editing-lite') . ' ...',
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", 'ithemeland-woocommerce-bulk-orders-editing-lite'), [
                            'class' => 'wobel-short-description'
                        ])
                    ],
                ]
            ],
            'filter_other_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'filter_other_fields'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_created_via' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-create-via'], esc_html__('Create Via', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'id' => 'wobel-filter-form-order-create-via',
                            'data-field' => 'value',
                            'title' => esc_html__('Create Via ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [
                            'checkout' => esc_html__('Checkout', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'admin' => esc_html__('Admin', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        ], true)
                    ],
                    '_payment_method' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-payment-method'], esc_html__('Payment Method', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'id' => 'wobel-filter-form-order-payment-method',
                            'data-field' => 'value',
                            'title' => esc_html__('Payment Method ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], $payment_methods, true)
                    ],
                    '_order_shipping_tax' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping-tax'], esc_html__('Shipping Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'id' => 'wobel-filter-form-order-shipping-tax',
                            'data-field' => 'value',
                            'title' => esc_html__('Shipping Tax ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        ], true)
                    ],
                    '_order_shipping' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-shipping'], esc_html__('Order Shipping', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'id' => 'wobel-filter-form-order-shipping',
                            'data-field' => 'value',
                            'title' => esc_html__('Order Shipping ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        ], true)
                    ],
                    '_recorded_coupon_usage_counts' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-recorder-coupon-usage-counts'], esc_html__('Coupon Usage Counts', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'id' => 'wobel-filter-form-order-recorder-coupon-usage-counts',
                            'data-field' => 'value',
                            'title' => esc_html__('Recorder Coupon Usage Counts ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        ], true)
                    ],
                    '_order_stock_reduced' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-stock-reduced'], esc_html__('Order Stock Reduced', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'id' => 'wobel-filter-form-order-stock-reduced',
                            'data-field' => 'value',
                            'title' => esc_html__('Order Stock Reduced ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        ], true)
                    ],
                    '_prices_include_tax' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-prices-index-tax'], esc_html__('Prices Index Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'id' => 'wobel-filter-form-order-prices-index-tax',
                            'data-field' => 'value',
                            'title' => esc_html__('Prices Index Tax ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        ], true)
                    ],
                    '_recorded_sales' => [
                        Generator::label_field(['for' => 'wobel-filter-form-order-recorded-sales'], esc_html__('Recorded Sales', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                        Generator::select_field([
                            'class' => 'wobel-input-md',
                            'id' => 'wobel-filter-form-order-recorded-sales',
                            'data-field' => 'value',
                            'title' => esc_html__('Recorded Sales ...', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                        ], [
                            'yes' => esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                            'no' => esc_html__('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        ], true)
                    ],
                ]
            ],
            'filter_custom_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobel-tab-content-item',
                    'data-content' => 'filter_custom_fields'
                ]),
                'fields_top' => (!empty($custom_fields['top_alert'])) ? $custom_fields['top_alert'] : '',
                'wrapper_end' => Generator::div_field_end(),
                'fields' => (!empty($custom_fields['fields'])) ? $custom_fields['fields'] : []
            ],
        ];
    }

    private function get_bulk_edit_custom_fields()
    {
        $output['top_alert'] = [];
        $output['fields'] = [];

        if (!empty($this->meta_fields) && is_array($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                $field_id = 'wobel-bulk-edit-form-custom-field-' . $meta_field['key'];
                $output['fields'][$meta_field['key']]['wrap_attributes'] = "data-name='{$meta_field['key']}' data-type='meta_field'";
                $output['fields'][$meta_field['key']]['html'][] = Generator::label_field(['for' => $field_id], $meta_field['title']);
                if (in_array($meta_field['main_type'], $this->meta_field_repository::get_fields_name_have_operator()) || ($meta_field['main_type'] == $this->meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $this->meta_field_repository::STRING_TYPE)) {
                    $class = ($meta_field['main_type'] == $this->meta_field_repository::CALENDAR) ? 'wobel-datepicker' : '';
                    $output['fields'][$meta_field['key']]['html'][] = Generator::select_field([
                        'data-field' => 'operator',
                        'id' => $field_id . '-operator'
                    ], Operator::edit_text());
                    $output['fields'][$meta_field['key']]['html'][] = Generator::input_field([
                        'type' => 'text',
                        'data-field' => 'value',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                        'class' => $class
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $this->meta_field_repository::NUMBER) {
                    $output['fields'][$meta_field['key']]['html'][] = Generator::select_field([
                        'data-field' => 'operator',
                        'for' => $field_id
                    ], Operator::edit_number());
                    $output['fields'][$meta_field['key']]['html'][] = Generator::input_field([
                        'type' => 'number',
                        'data-field' => 'value',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::CHECKBOX) {
                    $output['fields'][$meta_field['key']]['html'][] = Generator::select_field([
                        'id' => $field_id,
                        'data-field' => 'value',
                    ], [
                        'yes' => esc_html_e('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        'no' => esc_html_e('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ], true);
                } elseif (in_array($meta_field['main_type'], [$this->meta_field_repository::SELECT, $this->meta_field_repository::ARRAY_TYPE]) && !empty($meta_field['key_value'])) {
                    $options = Meta_Field_Helper::key_value_field_to_array($meta_field['key_value']);
                    $output['fields'][$meta_field['key']]['html'][] = Generator::select_field([
                        'id' => $field_id,
                        'class' => 'wobel-input-md',
                        'data-field' => 'value',
                    ], $options, true);
                } elseif (in_array($meta_field['main_type'], [$this->meta_field_repository::CALENDAR, $this->meta_field_repository::DATE])) {
                    $output['fields'][$meta_field['key']]['html'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-datepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::DATE_TIME) {
                    $output['fields'][$meta_field['key']]['html'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-datetimepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::TIME) {
                    $output['fields'][$meta_field['key']]['html'][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-timepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                }
            }
        } else {
            $output['top_alert'] = [
                Generator::div_field_start([
                    'class' => 'wobel-alert wobel-alert-warning',
                ]),
                Generator::span_field(esc_html__('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                Generator::div_field_end()
            ];
        }

        return $output;
    }

    private function get_filter_taxonomies_fields()
    {
        $items_grouped_fields = [];
        $product_taxonomies = $this->product_repository->get_grouped_taxonomies();
        if (!empty($product_taxonomies) && is_array($product_taxonomies)) {
            foreach ($product_taxonomies as $group => $taxonomies) {
                if (!empty($taxonomies)) {
                    foreach ($taxonomies as $taxonomy) {
                        $taxonomy_terms = [];
                        $terms = get_terms([
                            'taxonomy' => $taxonomy->name,
                            'hide_empty' => false,
                        ]);
                        if (!empty($terms) && is_array($terms)) {
                            foreach ($terms as $term) {
                                if ($term instanceof \WP_Term) {
                                    $taxonomy_terms[$term->term_id] = $term->name;
                                }
                            }
                        }
                        $items_grouped_fields[$group][$taxonomy->name] = [
                            Generator::label_field(['for' => "wobel-filter-form-order-{$taxonomy->name}"], $taxonomy->label),
                            Generator::select_field([
                                'id' => "wobel-filter-form-order-{$taxonomy->name}-operator",
                                'data-field' => 'operator',
                                'title' => esc_html__('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite')
                            ], Operator::filter_multi_select()),
                            Generator::select_field([
                                'id' => "wobel-filter-form-order-{$taxonomy->name}",
                                'data-type' => 'taxonomy',
                                'data-taxonomy' => $taxonomy->name,
                                'data-field' => 'value',
                                'multiple' => 'true',
                                'class' => 'wobel-select2',
                                'data-placeholder' => esc_html__('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite') . ' ...',
                            ], $taxonomy_terms),
                        ];
                    }
                }
            }
        }

        return $items_grouped_fields;
    }

    private function get_filter_custom_fields()
    {
        $output['top_alert'] = [];
        $output['fields'] = [];

        if (!empty($this->meta_fields) && is_array($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                $field_id = 'wobel-filter-form-custom-field-' . $meta_field['key'];
                $output['fields'][$meta_field['key']][] = Generator::label_field(['for' => $field_id], $meta_field['title']);
                if (in_array($meta_field['main_type'], $this->meta_field_repository::get_fields_name_have_operator()) || ($meta_field['main_type'] == $this->meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $this->meta_field_repository::STRING_TYPE)) {
                    $class = ($meta_field['main_type'] == $this->meta_field_repository::CALENDAR) ? 'wobel-datepicker' : '';
                    $output['fields'][$meta_field['key']][] = Generator::select_field([
                        'data-field' => 'operator',
                        'id' => $field_id . '-operator'
                    ], Operator::filter_text());
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'data-field' => 'value',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                        'class' => $class
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $this->meta_field_repository::NUMBER) {
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'number',
                        'class' => 'wobel-input-ft',
                        'data-field' => 'from',
                        'data-field-type' => 'number',
                        'id' => $field_id . '-from',
                        'placeholder' => esc_html__('From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ]);
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'number',
                        'class' => 'wobel-input-ft',
                        'data-field' => 'to',
                        'data-field-type' => 'number',
                        'id' => $field_id . '-to',
                        'placeholder' => esc_html__('To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::CHECKBOX) {
                    $output['fields'][$meta_field['key']][] = Generator::select_field([
                        'id' => $field_id,
                        'data-field' => 'value',
                    ], [
                        'yes' => esc_html_e('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                        'no' => esc_html_e('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ], true);
                } elseif (in_array($meta_field['main_type'], [$this->meta_field_repository::SELECT, $this->meta_field_repository::ARRAY_TYPE]) && !empty($meta_field['key_value'])) {
                    $options = Meta_Field_Helper::key_value_field_to_array($meta_field['key_value']);
                    $output['fields'][$meta_field['key']][] = Generator::select_field([
                        'id' => $field_id,
                        'class' => 'wobel-input-md',
                        'data-field' => 'value',
                    ], $options, true);
                } elseif (in_array($meta_field['main_type'], [$this->meta_field_repository::CALENDAR, $this->meta_field_repository::DATE])) {
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-datepicker wobel-date-from',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => esc_html__('From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ]);
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-datepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => esc_html__('To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::DATE_TIME) {
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-datetimepicker wobel-date-from',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => esc_html__('From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ]);
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-datetimepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => esc_html__('To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::TIME) {
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-timepicker',
                        'data-field' => 'from',
                        'data-field-type' => 'time',
                        'id' => $field_id . '-from',
                        'placeholder' => esc_html__('From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ]);
                    $output['fields'][$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobel-input-md wobel-timepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'time',
                        'id' => $field_id . '-to',
                        'placeholder' => esc_html__('To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    ]);
                }
            }
        } else {
            $output['top_alert'] = [
                Generator::div_field_start([
                    'class' => 'wobel-alert wobel-alert-warning',
                ]),
                Generator::span_field(esc_html__('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', 'ithemeland-woocommerce-bulk-orders-editing-lite')),
                Generator::div_field_end()
            ];
        }

        return $output;
    }
}
