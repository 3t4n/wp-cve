import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';
import { getSetting } from '@woocommerce/settings';
import { registerCheckoutBlock } from '@woocommerce/blocks-checkout';

// Global import
const htmlToElem = ( html ) => wp.element.RawHTML( { children: html } );

//Get settings from DB
const settings = getSetting( 'mypos_virtual_data');
const label = decodeEntities( settings.title ) || 'Card Payment - myPOS';

/**
 * Content component
 */
const Content = () => {
	let desc = decodeEntities( htmlToElem( "Pay via myPOS Checkout" +"<img src='"+settings.path+"/mypos-virtual-for-woocommerce/assets/images/card_schemes_ideal_no_bg.png' />"));
	if (settings?.description){
		desc = decodeEntities( htmlToElem( settings.description + "<img src='"+settings.path+"/mypos-virtual-for-woocommerce/assets/images/card_schemes_ideal_no_bg.png' />"));
	}
    return  desc;
};

/**
 * Label component
 *
 * @param {*} props Props from payment API.
 */
const Label = ( props ) => {
    const { PaymentMethodLabel } = props.components;
    return <PaymentMethodLabel text={ label } />;
};

const options = {
    name: 'mypos_virtual',
    title: settings.title,
    description: settings.description,
    category: 'woocommerce',
    parent: [ 'woocommerce/checkout-payment-methods-block' ],
    canMakePayment: () => true,
    supports: settings.supports,
    savedTokenComponent: '',
    label: <Label />,
    content: <Content />,
    ariaLabel: label,
    edit: <div/>,
};

/**
 * Payment method config object.
 */
const myPOS = {
    metadata: {...options},
    force: true,
    component: () => <div/>,
};

registerCheckoutBlock( myPOS);
registerPaymentMethod( options );
