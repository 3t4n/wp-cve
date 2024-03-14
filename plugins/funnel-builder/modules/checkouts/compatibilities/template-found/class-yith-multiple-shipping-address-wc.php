<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YITH Multiple Shipping Addresses for WooCommerce
 * Plugin URI: https://yithemes.com/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Yith_Multiple_Shipping_Address_WC {

	public function __construct() {
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'add_action' ], 99 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'add_action' ], 99 );
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ], 99 );

	}

	public function add_action() {

		if ( class_exists( 'YITH_Multiple_Addresses_Shipping_Frontend' ) ) {
			$object = WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'YITH_Multiple_Addresses_Shipping_Frontend', 'manage_addresses_cb' );

			if ( $object instanceof YITH_Multiple_Addresses_Shipping_Frontend ) {
				add_action( 'woocommerce_before_checkout_form', [ $object, 'manage_addresses_cb' ], 999 );
			}
		}

		if ( function_exists( 'yith_wcmas_init' ) ) {
			add_filter( 'wfacp_item_quantity', function ( $item_quantity, $cart_item ) {
				if ( isset( $cart_item['quantity'] ) && ! empty( $cart_item['quantity'] ) ) {
					return $cart_item['quantity'];
				}

				return $item_quantity;
			}, 10, 2 );
		}
	}

	public function wfacp_internal_css() {
		if ( ! class_exists( 'YITH_Multiple_Addresses_Shipping_Frontend' ) ) {
			return;
		}
		if ( function_exists( 'wfacp_template' ) ) {
			$instance = wfacp_template();
		}


		?>

        <style>
            <?php

                if($instance->get_template_type()!=='pre_built'){
                        echo ".pp_pic_holder.pp_woocommerce{top: 50px !important;}";
                    }

            ?>
            .wfacp_mini_cart_start_h table.shop_table.wfacp_mini_cart_reviews tr td, .wfacp_mini_cart_start_h table.shop_table.wfacp_mini_cart_reviews tr th {
                padding: 8px 0;
            }

            .pp_pic_holder.pp_woocommerce {
                top: 50% !important;
            }

            a.ywcmas_shipping_address_button_edit {
                display: inline-block;
                margin-right: 5px !important;
            }

            select.ywcmas_addresses_manager_address_select {
                margin-bottom: 5px !important;
            }

            td.ywcmas_addresses_manager_table_foot span.ywcmas_increase_qty_alert {
                font-size: 8pt !important;
                float: right;
            }

            table.ywcmas_addresses_manager_table.shop_table_responsive tfoot tr td {
                padding-bottom: 10px !important;
                border-bottom: 1px solid #ddd !important;
            }

            table.ywcmas_addresses_manager_table.shop_table_responsive tbody tr td {
                padding-top: 10px !important;

            }

            .pp_pic_holder.pp_woocommerce {
                top: 50% !important;
            }


            /* --------------------------------Multi shipping address -------------------------------------------------- */

            body #wfacp-e-form .ywcmas_multiple_addresses_manager h3 {
                margin: 0;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_qty {
                display: block;
                width: 40px;
                float: left;
                margin-right: 10px;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_manage_addresses_cb_container input[type="checkbox"] {
                position: relative;
                left: auto;
                right: auto;
                top: auto;
                bottom: auto;
                margin-right: 7px;
            }


            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager input[type="text"],
            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager input[type="password"],
            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager input[type="email"],
            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager input[type="tel"],
            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager select,
            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager textarea,
            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager .select2-container .select2-selection--single .select2-selection__rendered {
                font-size: 14px;
                line-height: 1.5;
                width: 100%;
                background-color: #fff;
                border-radius: 4px;
                position: relative;
                color: #404040;
                display: block;
                min-height: 40px;
                margin-bottom: 0;
                padding: 10px;
                vertical-align: top;
                box-shadow: none;
                opacity: 1;
                border: 1px solid #bfbfbf;
                transition: all .4s ease-out;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager select {
                -webkit-appearance: menulist;
                -moz-appearance: menulist;

            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager label {
                color: #777777;
                font-size: 14px;
                position: relative;
                left: auto;
                right: auto;
                top: auto;
                bottom: auto;
                margin: 0;
            }


            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager input[type="number"] {
                width: 40px !important;
                height: 40px;
                display: inline-block;
                padding: 5px;
                background-color: #f2f2f2;
                color: #43454b;
                border: 0;
                -webkit-appearance: none;
                box-sizing: border-box;
                font-weight: 400;
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, .125);
                min-height: 1px;
            }


            body .wfacp_main_form.woocommerce .ywcmas_select {
                width: calc(100% - 50px);
                margin: 0;
            }


            body .wfacp_main_form.woocommerce select.ywcmas_addresses_manager_table_shipping_address_select {
                margin-bottom: 0;
            }

            div.ywcmas_addresses_manager_table_remove {
                float: none;
                width: 15px;
                height: 15px;
                position: absolute;
                right: 0;
                top: 50%;
                margin: 0;
                margin-top: -7px;
                background: transparent;
                padding: 0;
            }

            select.ywcmas_addresses_manager_table_shipping_address_select {
                margin-bottom: 0;
            }

            .ywcmas_addresses_manager_table_qty_container {
                position: relative;
            }


            .wfacp_main_form.woocommerce table.shop_table_responsive.ywcmas_addresses_manager_table:last-child {
                margin: 0;
            }

            .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table {
                margin: 0 0 15px;
            }

            .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table thead th.ywcmas_addresses_manager_table_product_th {
                width: 40%;
                padding-bottom: 10px
            }

            .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table thead th {

                border: none;
            }

            .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table {

                border: none;
                padding: 0;
                margin: 0;
                border-radius: 0;
            }

            .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table tr td.ywcmas_addresses_manager_table_qty_td {
                padding: 0;
                margin: 0;
                border: none;
                border-radius: 0;
            }


            body .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager tr td:not(.ywcmas_addresses_manager_table_foot):first-child {
                width: 50%;
                padding-right: 10px;
            }

            body .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager tr td:not(.ywcmas_addresses_manager_table_foot):last-child {
                width: 50%;
                padding-left: 10px;

            }


            td.ywcmas_addresses_manager_table_foot span.ywcmas_increase_qty_alert {

                padding-left: 0;
            }


            .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table thead tr th:empty {
                display: none;
            }

            body .wfacp_main_form .ywcmas_manage_addresses_tables_container table.shop_table_responsive tr td {
                vertical-align: middle;
                display: table-cell;
            }


            body #wfacp-e-form .wfacp_main_form span.ywcmas_addresses_manager_table_img {
                float: none;
                display: inline-block;
                vertical-align: middle;
            }

            span.ywcmas_addresses_manager_table_img img {
                max-width: 100%;
                height: auto;
            }

            body #wfacp-e-form .wfacp_main_form span.ywcmas_addresses_manager_table_img {
                float: none;
                display: inline-block;
                vertical-align: middle;
            }

            body #wfacp-e-form .wfacp_main_form span.ywcmas_addresses_manager_table_img {
                float: none;
                display: inline-block;
                vertical-align: middle;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager tr td {
                vertical-align: middle;
            }


            a.ywcmas_addresses_manager_table_update_qty_button {
                font-size: 11px !important;
            }

            body #wfacp-e-form .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table tr:not(first-child) select {
                margin: 0 0 10px !important;
            }


            body #wfacp-e-form .wfacp_main_form .ywcmas_addresses_manager_table tbody tr:first-child select {
                margin: 0 !important;
            }

            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager input[type=number]:hover::-webkit-outer-spin-button,
            body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager input[type=number]:hover::-webkit-inner-spin-button {
                opacity: 1;
            }


            body #wfacp-e-form .wfacp_main_form.woocommerce a.button.ywcmas_shipping_address_button_new {
                padding: 15px 15px;
                line-height: 1.5;
                display: inline-block !important;
                color: #fff !important;
                text-transform: capitalize;
                box-shadow: none;
                font-family: inherit;
                background-color: #999;
                font-size: 15px;
                width: auto;
                font-weight: 400;
                border: none;
                min-height: 48px;
                border-radius: 4px;
                margin: 0 !important;
            }


            body #wfacp-e-form .wfacp_main_form.woocommerce a.button.ywcmas_shipping_address_button_new:hover {
                background-color: #878484;
            }


            body .wfacp_main_form.woocommerce .ywcmas_manage_addresses_cb_container {
                margin: 20px 0 !important;
            }

            @media (max-width: 767px) {
                /* Multishipping address */
                body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager tr td:not(.ywcmas_addresses_manager_table_foot):first-child {
                    width: 40%;

                }

                body #wfacp-e-form .wfacp_main_form.woocommerce .ywcmas_multiple_addresses_manager tr td:not(.ywcmas_addresses_manager_table_foot):last-child {
                    width: 60%;
                }

                body #wfacp-e-form .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table tr.ywcmas_addresses_manager_table_shipping_selection_row span {
                    margin: 0;
                    text-align: left;
                    display: block;
                }

                body #wfacp-e-form .wfacp_main_form .ywcmas_manage_addresses_tables_container table.shop_table_responsive tr td[data-title]::before {
                    display: none;
                }

                body #wfacp-e-form .wfacp_main_form table.ywcmas_addresses_manager_table.shop_table_responsive thead {
                    display: table-header-group;
                }

                body #wfacp-e-form .wfacp_main_form table.shop_table_responsive.ywcmas_addresses_manager_table tr.ywcmas_addresses_manager_table_shipping_selection_row td.ywcmas_addresses_manager_table_product_name_td_empty {
                    display: block;
                }
            }

        </style>
        <script>

            window.addEventListener('load', function () {
                (function ($) {

                    $(document.body).on('click', '.wfacp_increase_item,.wfacp_decrease_item', function () {

                        var cart_key = $(this).parents('.cart_item').find("input[type=number]").attr('cart_key');
                        if (typeof cart_key == "undefined") {
                            cart_key = $(this).parents('.cart_item').attr('cart_key');
                        }
                        setTimeout(function () {
                            if (cart_key != '' && typeof cart_key !== "undefined") {

                                if ($('.ywcmas_addresses_manager_table').length > 0) {
                                    $('.ywcmas_addresses_manager_table').each(function () {
                                        var cartkey = $(this).find('tbody').find('.ywcmas_addresses_manager_table_item_cart_id').val();
                                        if (cart_key == cartkey) {
                                            $(this).find('tbody').find('.ywcmas_addresses_manager_table_shipping_address_select').trigger('change');
                                        }
                                    });
                                }
                            }
                        }, 500);


                    });

                })(jQuery);
            });
        </script>
		<?php
	}


}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Yith_Multiple_Shipping_Address_WC(), 'wfacp-ymsfw' );


