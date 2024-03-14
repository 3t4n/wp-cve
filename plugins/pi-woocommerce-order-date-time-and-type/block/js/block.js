const pisol_dtt_warning = ({ cart, extensions }) => {

    return React.createElement("div", {
        className: 'alert alert-warning pisol-dtt-block-warning'
    }, "Custom field of Order Date, Time and Pickup location cant be added in block based checkout page, you should make your checkout page using short code ", React.createElement('strong',null,'[woocommerce_checkout]'), " In order to collect delivery date, time and pickup location from customer. (This warning is only visible to administrator and not to your customers)");

};

const pisol_dtt_render = () => {
    return React.createElement(wc.blocksCheckout.ExperimentalOrderMeta, null, React.createElement(pisol_dtt_warning, null));
};

wp.plugins.registerPlugin('pisol-dtt', {
    render: pisol_dtt_render,
    scope: 'woocommerce-checkout',
});