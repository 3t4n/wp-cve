const settings = window.wc.wcSettings.getSetting( 'moneris_data', {} );
const label = window.wp.htmlEntities.decodeEntities( settings.title ) || window.wp.i18n.__( 'Moneris', 'wpheka-gateway-moneris' );
const Content = () => {
	return window.wp.htmlEntities.decodeEntities( settings.description || '' );
};
const Wpheka_Moneris_Gateway = {
	name: 'moneris',
	label: label,
	content: Object( window.wp.element.createElement )( Content, null ),
	edit: Object( window.wp.element.createElement )( Content, null ),
	canMakePayment: () => true,
	ariaLabel: label,
	supports: {
		features: settings.supports,
	},
};
window.wc.wcBlocksRegistry.registerPaymentMethod( Wpheka_Moneris_Gateway );