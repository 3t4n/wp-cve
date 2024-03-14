const settings = window.wc.wcSettings.getSetting('payever_gateway_data', {});

settings.forEach(item => {
    const title = window.wp.htmlEntities.decodeEntities(item.title);
    const label = () => {
        let img = '';
        if (item.icon) {
            img = Object(window.wp.element.createElement)('img', {
                src: item.icon,
                alt: item.title,
                className: 'payever_icon'
            });
        }

        return Object(window.wp.element.createElement)('span', { className: 'payever-payment-item' }, title, img);
    }
    const content = () => {
        let content = null;
        if (item.description) {
            const desc = window.wp.htmlEntities.decodeEntities(item.description || '');
            content = Object(window.wp.element.createElement)('span', { className: 'payever-payment-description' }, desc)
        }

        return content;
    };

    const Block_Gateway = {
        name: item.id,
        label: Object(window.wp.element.createElement)(label, null),
        content: Object(window.wp.element.createElement)(content, null),
        edit: Object(window.wp.element.createElement)(content, null),
        canMakePayment: () => true,
        ariaLabel: title,
        supports: {
            features: item.supports,
        },
    };
    window.wc.wcBlocksRegistry.registerPaymentMethod(Block_Gateway);
});
