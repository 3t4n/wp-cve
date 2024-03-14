declare let Ladda:any;
declare var rnErrorResolver;
class ErrorResolver{
    public analyzeButton;
   constructor(){
       this.analyzeButton=Ladda.create( document.querySelector( '#analyze' )) ;
        jQuery('.issueType').change((e)=>{
            if(jQuery(e.currentTarget).val()=='order')
            {
                jQuery('#orderDetail').slideDown(200,()=>{jQuery('#orderNumber').focus();});
                this.CheckOrderNumber();
            }else{
                jQuery('#orderDetail').slideUp();
                jQuery('#analyze').slideDown();
            }

        });

        jQuery('#orderNumber').on('input',()=>{
           this.CheckOrderNumber();
        });

        jQuery('#analyze').click(()=>{this.Analyze();})
   }

    private CheckOrderNumber() {
        if( jQuery('#orderNumber').val()=='')
        {
            jQuery('#analyze').slideUp();
        }else
            jQuery('#analyze').slideDown();
    }

    private Analyze() {
        this.analyzeButton.start();
        jQuery.post(ajaxurl,{action:'rednao_wcpdfinv_diagnose_error',nonce:rnErrorResolver.nonce,invoiceId:jQuery('#templateName').val(),testType:jQuery('.issueType:checked').val(),orderNumber:jQuery('#orderNumber').val()},(response)=>{
            alert('Sorry the error couuldn\'t be captured, please contact support to resolve this issue');
            this.analyzeButton.stop();
        }).fail(()=>{
            jQuery.post(ajaxurl,{action:'rednao_wcpdfinv_get_latest_error'},(response)=>{
                if(response=='')
                {
                    alert('Sorry the error couuldn\'t be captured, please contact support to resolve this issue');
                    this.analyzeButton.stop();
                }
                try{
                    let error=JSON.parse(response);
                    this.PrintError(error);
                    this.analyzeButton.stop();
                }catch(exception)
                {
                    alert('Sorry the error couuldn\'t be captured, please contact support to resolve this issue');
                    this.analyzeButton.stop();
                }
            }).fail(()=>{
                alert('Sorry the error couuldn\'t be captured, please contact support to resolve this issue');
                this.analyzeButton.stop();
            });


        });


    }

    private PrintError(error: any) {
        jQuery('#edErrorMessage').text(error.ErrorMessage);
        jQuery('#edErrorNumber').text(error.ErrorNumber);
        jQuery('#edErrorFile').text(error.ErrorFile);
        jQuery('#edErrorLine').text(error.ErrorLine);
        jQuery('#edErrorContext').text(JSON.stringify(error.ErrorContext));
        jQuery('#edErrorDetail').html(this.GetStringFromArray(error.Detail));
        jQuery('#ErrorDetail').css('display','block');
    }
    EscapeHtml(string) {

        var entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        };
        return string.replace(/[&<>"'`=\/]/g, function (s) {
            return entityMap[s];
        });
    };

    private GetStringFromArray(Detail: any) {
        try{
            let parsedDetail=JSON.parse(Detail);
            let text='';
            for(let element of parsedDetail)
            {
                for(let property in element)
                {
                    text+='<strong>'+this.EscapeHtml(property.toString())+':</strong>'+this.EscapeHtml(element[property].toString())+'<br/>';
                }
                text+='<br/><br/><br/>';
            }
            return text;

        }catch(Exception)
        {
            return Detail;
        }
    }
}

jQuery(()=>{
    new ErrorResolver();
});

declare let ajaxurl:any;