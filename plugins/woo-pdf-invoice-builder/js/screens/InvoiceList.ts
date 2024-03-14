class InvoiceList{
    constructor(){
        jQuery(()=>{
           jQuery('.createInvoice').click(()=>{
               if(rednaoPDFInvoiceParamsList.IsPR!="1"&&rednaoPDFInvoiceParamsList.TemplateCount>0)
                   alert('Sorry, the free version support only one invoice template, please edit the existing template or get the Pro Version');
               else
                   window.location.href=rednaoPDFInvoiceParamsList.AddNewURL;
           });
            jQuery('#fileToImport').change(function(){
                if((jQuery('#fileToImport')[0] as any).files.length>0){
                    jQuery('#formImporter').submit();
                }

            });

           jQuery('#invoiceImport').click(function(e){
                e.preventDefault();
                jQuery('#fileToImport').click();
           });
        });


    }
}

new InvoiceList();
declare let rednaoPDFInvoiceParamsList:any;