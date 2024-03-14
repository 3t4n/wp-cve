const { registerPaymentMethod } = window.wc.wcBlocksRegistry;
const description = wc.wcSettings.getSetting('paygreen_payment_data').description;

const options = {
    name: wc.wcSettings.getSetting('paygreen_payment_data').name,
    label: <strong>{wc.wcSettings.getSetting('paygreen_payment_data').title}</strong>,
    ariaLabel: wc.wcSettings.getSetting('paygreen_payment_data').title,
    content: !description ? <></> : <div>{description}</div>,
    edit: <></>,
    canMakePayment: () => true,
    paymentMethodId: wc.wcSettings.getSetting('paygreen_payment_data').name,
    supports: {
        features: wc.wcSettings.getSetting('paygreen_payment_data').supports,
    },
};

registerPaymentMethod(options);