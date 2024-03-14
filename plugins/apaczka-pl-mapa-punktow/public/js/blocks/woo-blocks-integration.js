!function () {
    "use strict";
    var e = window.wp.element, t = window.wp.blocks, o = window.wp.data, l = window.wp.components;
    const {ExperimentalOrderMeta: c} = wc.blocksCheckout, {getSetting: n} = wc.wcSettings;

    function r(t) {
        let {handleLockerChange: o, apaczkaPoint: l} = t;
        const [c, n] = (0, e.useState)(null);
        return (0, e.useEffect)((() => {
            const e = l, t = e;
            n(t)
        }), [l]), (0, e.createElement)("div", {className: "apaczka-delivery-point-wrap"}, (
            0, e.createElement)("input", {
            value: l,
            type: "text",
            id: "apaczka-point",
            onChange: e => {
                o(e)
            },
            placeholder: "apaczka point"
        })
        )
    }

    var a = JSON.parse('{"apiVersion":2,"name":"extended-checkout/apaczka-point-selector","textdomain":"apaczka-pl","title":"Punkt dostawy Apaczka","description":"Punkt dostawy Apaczka","supports":{"html":false,"align":false,"multiple":false,"reusable":false},"parent":["woocommerce/checkout-contact-information-block"],"attributes":{"lock":{"type":"object","default":{"remove":true,"move":true}},"text":{"type":"string","source":"html","selector":".wp-block-woocommerce-checkout-newsletter-subscription","default":""}},"editorScript":"file:./index.js","editorStyle":"file:../../../build/style-newsletter-block.css","style":"file:../../build/style-card-message-frontend.css"}');
    window.wp.blockEditor;
    const {registerCheckoutBlock: s} = wc.blocksCheckout, {name: i} = a;
    !!(0, o.select)("core/editor") && (0, t.registerBlockType)(i, {
        icon: {
            src: (0, e.createElement)(l.SVG, {
                xmlns: "http://www.w3.org/2000/svg",
                viewBox: "0 0 20 16"
            }, (0, e.createElement)("g", {
                fill: "none",
                fillRule: "evenodd"
            }, (0, e.createElement)("path", {
                stroke: "currentColor",
                strokeWidth: "1.5",
                d: "M2 .75h16c.69 0 1.25.56 1.25 1.25v12c0 .69-.56 1.25-1.25 1.25H2c-.69 0-1.25-.56-1.25-1.25V2C.75 1.31 1.31.75 2 .75z"
            }), (0, e.createElement)("path", {
                fill: "currentColor",
                d: "M7.667 7.667A2.34 2.34 0 0010 5.333 2.34 2.34 0 007.667 3a2.34 2.34 0 00-2.334 2.333 2.34 2.34 0 002.334 2.334zM11.556 3H17v3.889h-5.444V3zm2.722 2.916l1.944-1.36v-.779L14.278 5.14l-1.945-1.362v.778l1.945 1.361zm-5.834-.583a.78.78 0 00-.777-.777.78.78 0 00-.778.777c0 .428.35.778.778.778a.78.78 0 00.777-.778zm3.89 5.904c0-1.945-3.088-2.785-4.667-2.785-1.58 0-4.667.84-4.667 2.785v1.097h9.333v-1.097zM7.666 10c-1.012 0-2.163.389-2.738.778h5.475C9.821 10.38 8.678 10 7.667 10z"
            }))), foreground: "#874FB9"
        }, Edit: () => {
        }, Save: () => {
        }
    }), s({
        metadata: a, component: t => {
            let {checkoutExtensionData: o} = t;
            const [l, n] = (0, e.useState)(null), [a, s] = (0, e.useState)(""), {setExtensionData: i} = o;
            return (0, e.useEffect)((() => {
                i("apaczka", "apaczka-point", a)
            }), [i, a]), (0, e.createElement)(e.Fragment, null, (0, e.createElement)(c, null, (0, e.createElement)(r, {
                apaczkaPoint: a,
                handleLockerChange: e => {
                    const t = e.target.value;
                    n(e.target.value), s(e.target.value)
                }
            })))
        }
    })
}();