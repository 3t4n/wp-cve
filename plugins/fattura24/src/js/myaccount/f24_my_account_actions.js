window.addEventListener('load', () => {
    let orderElementsCollection = document.getElementsByClassName('orderPdfView');
    let invoiceElementsCollection = document.getElementsByClassName('invoicePdfView');
    let orderElements = [...orderElementsCollection];
    let invoiceElements = [...invoiceElementsCollection];

    orderElements.forEach(order => {
        order.setAttribute('target', '_blank');
    });

    invoiceElements.forEach(invoice => {
        invoice.setAttribute('target', '_blank');
    })
});    