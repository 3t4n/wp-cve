if (window.wc
    && window.wc.wcBlocksRegistry
    && window.React
) {
    /**
     * External dependencies
     */
    const {registerPaymentMethod} = wc.wcBlocksRegistry;
    const {createElement} = React;
    const {__} = wp.i18n;
    const {getSetting} = wc.wcSettings;
    const {decodeEntities} = wp.htmlEntities;

    /**
     * Internal dependencies
     */
    const PAYMENT_METHOD_NAME = 'ziina'

    const settings = getSetting('ziina_data', {});
    const defaultLabel = __(
        'Ziina Payment',
        'ziina'
    );
    const label = decodeEntities(settings.title) || defaultLabel;

    /**
     * Content component
     */
    const Content = createElement((props) => {
        return decodeEntities(settings.description || '');
    }, null);

    /**
     * Label component
     *
     * @param {*} props Props from payment API.
     */
    const Label = createElement(props => {
        const {PaymentMethodLabel} = props.components;
        return createElement(PaymentMethodLabel, {text: label})
    }, null)

    /**
     * Bank transfer (ZiinaPayment) payment method config object.
     */
    const bankTransferPaymentMethod = {
        name: PAYMENT_METHOD_NAME,
        label: Label,
        content: Content,
        edit: Content,
        canMakePayment: () => true,
        ariaLabel: label,
        supports: {
            features: settings?.supports ?? [],
        },
    };

    registerPaymentMethod(bankTransferPaymentMethod);
}
