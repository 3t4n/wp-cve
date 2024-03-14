function RedNaoCreateInvoiceDropDown(selector) {
    var $select = jQuery('<select id="invoiceTemplateId_' + selector + '" name="rnTemplateId"></select>');
    var option=jQuery('<option></option>');
    option.text(bulkManagerVar.selectAPDFTemplate);
    $select.append(option);
    var first = true;
    for (var _i = 0, _a = bulkManagerVar.invoices; _i < _a.length; _i++) {
        var invoice = _a[_i];
        var option = jQuery('<option></option>');
        if (first)
            option.attr('selected', 'selected');
        first = false;
        option.text(invoice.Name);
        option.val(invoice.InvoiceID);
        $select[0].appendChild(option[0]);
    }
    $select.insertAfter(jQuery('#' + selector));
}
jQuery(function () {
    if (typeof bulkManagerVar != "undefined" && bulkManagerVar.invoices.length > 1) {
        RedNaoCreateInvoiceDropDown('bulk-action-selector-top');
        //RedNaoCreateInvoiceDropDown('bulk-action-selector-bottom');
    }
});
//# sourceMappingURL=BulkManager.js.map