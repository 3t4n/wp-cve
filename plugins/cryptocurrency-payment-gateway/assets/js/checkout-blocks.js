/**
 * CryptoWoo Checkout Blocks Javascript File
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage Checkout
 * @author     CryptoWoo AS
 */

const settings = window.wc.wcSettings.getSetting( 'cryptowoo_data', {} );
const label	   = window.wp.htmlEntities.decodeEntities( settings.title ) || window.wp.i18n.__( 'cryptowoo', 'cryptowoo' );

const htmlToElem = ( html ) => wp.element.RawHTML( { children: html } );

const Label = () => {
	return htmlToElem( settings.title + settings.icons );
};

const Content = () => {
	return htmlToElem( settings.payment_fields );
};

const CryptoWoo_Checkout_Block_Payment_Method = {
	name: 'cryptowoo',
	label: Object( window.wp.element.createElement )( Label, null ),
	content: Object( window.wp.element.createElement )( Content, null ),
	edit: Object( window.wp.element.createElement )( Content, null ),
	canMakePayment: () => true,
	ariaLabel: label,
	supports: {
		features: settings.supports,
	},
};

window.wc.wcBlocksRegistry.registerPaymentMethod( CryptoWoo_Checkout_Block_Payment_Method );

window.onload = function () {
	const currency_select = document.getElementById( 'cw_payment_currency' );
	if (currency_select) {
		currency_select.onchange = function (event) {
			cryptowoo_set_extension_data();
		}
		if ( 'please_choose' !== currency_select.value ) {
			cryptowoo_set_extension_data();
		}
	}

	const refund_address_input = document.getElementById( 'refund_address' );
	if (refund_address_input) {
		refund_address_input.onchange = function (event) {
			cryptowoo_set_extension_data();
		}
	}

	const order_btn_elements = document.getElementsByClassName( 'wc-block-components-checkout-place-order-button' );
	if (order_btn_elements) {
		const place_order_button   = order_btn_elements[0];
		place_order_button.onclick = function (event) {
			cryptowoo_set_extension_data();
		}
	}
};

function cryptowoo_set_extension_data() {
	const currency_select	   = document.getElementById( 'cw_payment_currency' );
	const refund_address_input = document.getElementById( 'refund_address' );

	let parameters = {};
	if ( currency_select ) {
		parameters['cw_payment_currency'] = currency_select.value;
	}
	if ( refund_address_input ) {
		parameters['refund_address'] = refund_address_input.value;
	}

	// Extend checkout block's request data with CryptoWoo payment options data.
	window.wp.data.dispatch( window.wc.wcBlocksData.CHECKOUT_STORE_KEY ).__internalSetExtensionData(
		'cryptowoo',
		parameters
	);
}
