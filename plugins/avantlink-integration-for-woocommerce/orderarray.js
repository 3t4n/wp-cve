if (order.order_id) {
    var avmArray = document.createElement('script'); avmArray.type = 'text/javascript';

    _AvantMetrics = "[";
    _AvantMetrics = _AvantMetrics + `['order', {amount: '${order.order_subtotal}', order_id: '${order.order_id}', country: '${order.order_country}', state: '${order.order_state}', ecc: '${order.coupons}', currency: '${order.order_currency}', new_customer: '${order.new_customer}'}]`;

    var items = JSON.parse(order.order_items);

    items.forEach(item => {
        _AvantMetrics = _AvantMetrics + `, ['item', {order_id: '${order.order_id}', parent_sku: '${item.parent_sku}', variant_sku: '${item.variant_sku}', price: '${item.total}', qty: '${item.quantity}'}]`;
    });

    _AvantMetrics = _AvantMetrics + ']';

    avmArray.innerText = "var _AvantMetrics = " + _AvantMetrics;

    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(avmArray, s);
}

var avm = document.createElement('script'); avm.type = 'text/javascript'; avm.async = true;
avm.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + `cdn.avmws.com/10${order.merchant_id}/`;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(avm, s);