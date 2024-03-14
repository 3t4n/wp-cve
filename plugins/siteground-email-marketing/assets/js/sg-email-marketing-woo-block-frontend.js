(() => {
    "use strict";
    const e = window.wp.element,
        t = JSON.parse(
            '{"apiVersion":2,"name":"sg-email-marketing/woo-block","version":"1.0.0","title":"SG Email Marketing Woo Block","category":"woocommerce","supports":{"html":false,"align":false,"multiple":false,"reusable":false},"parent":["woocommerce/checkout-contact-information-block"],"attributes":{"lock":{"type":"object","default":{"remove":true,"move":true}},"text":{"type":"string","default":""}},"textdomain":"siteground-email-marketing"}'
        ),
        o = window.wc.blocksComponents,
        a = window.wp.i18n,
        { registerCheckoutBlock: n } = wc.blocksCheckout;
    n({
        metadata: t,
        component: ({ checkoutExtensionData: t }) => {
            const [n, c] = (0, e.useState)(!1),
                { setExtensionData: i } = t,
                l = sgEmailMarketingWooBlockFrontend.checkboxLabel || (0, a.__)("Sign me up for the newsletter!", "siteground-email-marketing");
            (0, e.useEffect)(() => {
                i("sg-email-marketing", "sg-email-marketing-woo-checkbox", n);
            }, [n, i]);
            const s = (0, e.useCallback)(
                (e) => {
                    c(e), i("sg-email-marketing", "sg-email-marketing-woo-checkbox", e);
                },
                [c, i]
            );
            return (0, e.createElement)("div", { className: "sg-email-marketing-woo-checkbox" }, (0, e.createElement)(o.CheckboxControl, { label: l, checked: n, onChange: s }));
        },
    });
})();
