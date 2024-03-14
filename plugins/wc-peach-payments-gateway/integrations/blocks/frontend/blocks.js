(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        l = window.wc.wcSettings,
        a = window.wp.i18n,
        i = window.wp.htmlEntities,
		c = (0, l.getSetting)("peach-payments_data", {}),
        s = (0, a.__)(c.title, "woocommerce-gateway-peach-payments"),
        r = ({ title: e }) => (0, i.decodeEntities)(e) || s,
        o = ({ description: e }) => (0, i.decodeEntities)(e || ""),
        d = r({ title: c.title }),
		z = 'div',
        w = {
            name: "peach-payments",
            label: (0, e.createElement)((a) => {
                const { PaymentMethodLabel: t } = a.components;
                return (0, e.createElement)(t, { text: s });
            }, null),
            //content: (0, e.createElement)(o, { description: c.whatever }),
			content: (0, e.createElement)(z, { dangerouslySetInnerHTML: { __html: c.whatever } }),
            edit: (0, e.createElement)(o, { description: c.description }),
            canMakePayment: () => !0,
            ariaLabel: d,
            supports: { features: c.supports  },
        };
		console.log(c);
    (0, t.registerPaymentMethod)(w);
})();