
(() => {
    "use strict";

    const React = window.React;
    const wcBlocksRegistry = window.wc.wcBlocksRegistry;
    const i18n = window.wp.i18n;
    const wcSettings = window.wc.wcSettings;
    const htmlEntities = window.wp.htmlEntities;

    const settings = wcSettings.getSetting( 'icwoorok2_sofort_data', {} );
    const label = htmlEntities.decodeEntities( settings.title ) || window.wp.i18n.__( 'Sofort', 'ic-woo-rabo-omnikassa-2' );
    
    const decodeDescription = () => htmlEntities.decodeEntities(settings.description || "");

    const paymentMethod = {
        name: 'icwoorok2_sofort',
        label: React.createElement("div", {
            style: {
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
                width: '95%',
            }
        }, [
            React.createElement("span", {}, label),
            React.createElement("img", {
                src: htmlEntities.decodeEntities(settings.icon),
                alt: label
            }),
        ]),
        placeOrderButtonLabel: i18n.__("Proceed to Sofort", "ic-woo-rabo-omnikassa-2"),
        content: React.createElement(decodeDescription, null),
        edit: React.createElement(decodeDescription, null),
        canMakePayment: () => true,
        ariaLabel: label,
        supports: {
            features: settings.supports,
        },
    };
    
    wcBlocksRegistry.registerPaymentMethod( paymentMethod );
})();