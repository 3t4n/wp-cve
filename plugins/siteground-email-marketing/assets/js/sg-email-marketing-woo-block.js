(() => {
    "use strict";
    const e = window.wp.element,
        c = window.wp.blocks,
        t = window.wp.components,
        o = JSON.parse('{"apiVersion":2,"name":"sg-email-marketing/woo-block","version":"1.0.0","title":"SG Email Marketing Woo Block","category":"woocommerce","supports":{"html":false,"align":false,"multiple":false,"reusable":false},"parent":["woocommerce/checkout-contact-information-block"],"attributes":{"lock":{"type":"object","default":{"remove":true,"move":true}},"text":{"type":"string","default":""}},"textdomain":"sg-email-marketing"}');
    (0, c.registerBlockType)(o, {
        edit: () => {}
    });
})();