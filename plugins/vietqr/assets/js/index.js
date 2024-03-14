!function () {
    "use strict";
    var t = window.wp.element, e = window.wp.htmlEntities, a = window.wp.i18n, n = window.wc.wcBlocksRegistry, i = window.wc.wcSettings;
    const l = () => {
        const t = (0, i.getSetting)("vietqr_data", null); if (!t) throw new Error("VietQR initialization data is not available");
        return t
    };
    var o;
    const r = () => (0, e.decodeEntities)(l()?.description || "");
    const title = () => (0, e.decodeEntities)(l()?.title || "");
    const banks = l()?.vietqr_banks;
    const bank_description =
        banks.map((bank) => {
            if (bank['logo'] && bank['transferSupported'] > 0) {
                return (0, t.createElement)('div', { className: 'list-bank' },
                    (0, t.createElement)('div', { className: 'list-bank-box' },
                        (0, t.createElement)("img", { src: bank['logo'], alt: bank['shortName'] })
                    ),
                )
            }
        });
    (0, n.registerPaymentMethod)
        ({
            name: "vietqr",
            label: (0, t.createElement)('div', {},
                (0, t.createElement)(title, null),
                (0, t.createElement)("img", { src: l()?.logo_url, alt: l()?.title })
            ),
            ariaLabel: (0, a.__)("VietQR payment method", "woocommerce-gateway-vietqr"), canMakePayment: () => !0,
            content: (0, t.createElement)('div', {},
                (0, t.createElement)(r, null),
                (0, t.createElement)('div', { className: 'vietqr-row' }, bank_description),
            ),
            edit: (0, t.createElement)('div', {},
                (0, t.createElement)(r, null),
                (0, t.createElement)('div', { className: 'vietqr-row' }, bank_description),
            ),
            supports: { features: null !== (o = l()?.supports) && void 0 !== o ? o : [] }
        })
}();
