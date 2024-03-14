function RedNaoCreateInvoiceDropDown(selector) {

}
jQuery(function () {
    var $select = jQuery('<select class="rnTempalteId" name="rnTemplateId"></select>');
    var option=jQuery('<option></option>');
    option.text(bulkManagerVar.selectAPDFTemplate);
    $select.append(option);
    var first = true;
    for (var _i = 0, _a = bulkManagerVar.templates; _i < _a.length; _i++) {
        var invoice = _a[_i];
        var option = jQuery('<option></option>');
        if (first)
            option.attr('selected', 'selected');
        first = false;
        option.text(invoice.Name);
        option.val(invoice.Id);
        $select[0].appendChild(option[0]);
    }
    $select.insertAfter(jQuery('#bulk-action-selector-top'));
    $select.clone().insertAfter(jQuery('#bulk-action-selector-bottom'));

    jQuery('#doaction,#doaction2').click(function(e){
        let input=e.target;
        let form=input.closest('form');
        let action=form.querySelector('[name="action"]')

        if(action.value=='pdfbuilder_view_pdf'||action.value=='pdfbuilder_download_pdf'){
            e.preventDefault();

            let newForm=document.createElement('form');
            document.body.appendChild(newForm);

            newForm.target='_blank';
            newForm.action=ajaxurl;
            newForm.method='post';
            newForm.style.display='none';

            let input=document.createElement('input');
            input.name='_wpnonce';
            input.value=form.querySelector('#_wpnonce').value;
            newForm.appendChild(input);

            input=document.createElement('input');
            input.name='action';
            input.value='rednaopdfwpform_handle_bulk_action';
            newForm.appendChild(input);



            input=document.createElement('input');
            input.name='rnTempalteId';
            input.value=form.querySelector('.rnTempalteId').value;
            newForm.appendChild(input);


            input=document.createElement('input');
            input.name='rnActionType';
            input.value=form.querySelector('[name="action"]').value;
            newForm.appendChild(input);


            let ids=[];
            document.querySelectorAll('[name="entry_id[]"]:checked').forEach(x=>ids.push(x.value));


            input=document.createElement('input');
            input.name='entryId';
            input.value=ids.join(',');
            newForm.appendChild(input);


            newForm.submit();



        }
    });
});
//# sourceMappingURL=BulkManager.js.map