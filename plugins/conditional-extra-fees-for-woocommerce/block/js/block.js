
const OptionalFeesComponent = ({ cart, extensions }) => {

    const handleCheckboxChange = (id, event) => {
        if (event.target.checked) {
            var data = { id: id, checked: true };
        } else {
            var data = { id: id, checked: false };
        }
        wc.blocksCheckout.extensionCartUpdate({
            namespace: 'pisol_cefw_fees',
            data: data
        });
    };

    if (typeof extensions.pisol_cefw_fees == 'undefined') return [];

    if (extensions.pisol_cefw_fees.options.length == 0) return [];

    return React.createElement("div", {
        className: 'pisol-fees-container'
    }, React.createElement("strong", {
        className: 'pisol-fees-container-label'
    }, extensions.pisol_cefw_fees.label), extensions.pisol_cefw_fees.options.map(item => React.createElement("div", {
        key: item.id,
        className: 'pisol-fees-parent'
    }, React.createElement("label", {
        htmlFor: item.id,
    }, React.createElement("input", {
        type: "checkbox",
        id: item.id,
        onChange: (e) => handleCheckboxChange(item.id, e),
        checked: item.checked
    }), item.title))));

};

const render = () => {
    return React.createElement(wc.blocksCheckout.ExperimentalOrderMeta, null, React.createElement(OptionalFeesComponent, null));
};

wp.plugins.registerPlugin('pisol-cefw-fees', {
    render,
    scope: 'woocommerce-checkout',
});