declare var bulkManagerVar;
function RedNaoCreateInvoiceDropDown(selector)
{

    let $select=jQuery('<select id="invoiceTemplateId_'+selector+'" name="rnTemplateId"><option>Select a pdf template</option></select>');

    let first=true;
    for(let invoice of bulkManagerVar.invoices)
    {

        let option=jQuery('<option></option>');
        if(first)
            option.attr('selected','selected');
        first=false;
        option.text(invoice.Name);
        option.val(invoice.InvoiceID);
        $select[0].appendChild(option[0]);

    }
    $select.insertAfter(jQuery('#'+selector));
}


jQuery(function(){
    if(typeof bulkManagerVar!="undefined"&&bulkManagerVar.invoices.length>1)
    {
        RedNaoCreateInvoiceDropDown('bulk-action-selector-top');
        //RedNaoCreateInvoiceDropDown('bulk-action-selector-bottom');
    }
});


