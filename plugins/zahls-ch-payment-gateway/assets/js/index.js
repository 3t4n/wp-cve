(() => {
    "use strict";
    const t = window.wp.htmlEntities,
        e = window.wp.i18n,
        n = window.wc.wcBlocksRegistry,
        a = window.wc.wcSettings;
    var i, l, o = function() {
            var t = (0, a.getSetting)("zahls_data", null);
            if (!t) throw new Error("Zahls initialization data is not available");
            return t
        },
        r = function() {
            var e;
            return (0, t.decodeEntities)((null === (e = o()) || void 0 === e ? void 0 : e.description) || "")
        },
        c = function() {
            var t = o();
            if (!t || !t.icons) return null;
            return React.createElement("div", {
                style: {
                    display: 'flex',
                    alignItems: 'center',
                    maxHeight: '24px' // Maximale Höhe und vertikale Zentrierung
                }
            }, [
                t.icons.map((icon, index) => 
                    React.createElement("img", {
                        key: icon.id || index, // Verwendung von icon.id, falls verfügbar, ansonsten index
                        src: icon.src,
                        alt: icon.alt,
                        style: { marginRight: '5px', height: '100%' } // Höhe auf 100% der Elternhöhe setzen
                    })
                ),
                React.createElement("span", {
                    className: "wc-block-components-payment-method-label zahls-label",
                    style: { marginLeft: '5px' }
                }, t.title)
            ]);
        };
    
    (0, n.registerPaymentMethod)({
        name: "zahls",
        label: React.createElement(c, null),
        ariaLabel: (0, e.__)("Zahls", "zahls"),
        canMakePayment: function() {
            return !0
        },
        content: React.createElement(r, null),
        edit: React.createElement(r, null),
        supports: {
            features: null !== (i = null === (l = o()) || void 0 === l ? void 0 : l.supports) && void 0 !== i ? i : []
        }
    })
})();
