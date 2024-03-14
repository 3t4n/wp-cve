declare let CodeMirror:any;
declare let toastr:any;
declare let rednaoCustomFieldParam:any;
class CustomFieldBuilder{
    private codeMirror:any;
    private lSave;
    constructor(){
        jQuery(()=>{
            jQuery('.rednaoName').val(rednaoCustomFieldParam.name);
            jQuery('.rednaoCode').val(rednaoCustomFieldParam.code);
            jQuery('.rednaoType').val(rednaoCustomFieldParam.type);
            jQuery('.save').click(()=>{
                let name:any=jQuery('.rednaoName').val();
                let code='';
                if(this.codeMirror!=null)
                    code=this.codeMirror.getValue();

                if(name.trim()=='')
                {
                    toastr.error('Name is required');
                    return;
                }

                if(code.trim()=='')
                {
                    toastr.error('Code is required');
                    return;
                }

                let type=jQuery('.rednaoType').val();

                if(code.indexOf('return ')<0)
                    code='return '+code;



                this.lSave.start();
                jQuery.post(ajaxurl,{action:'rednao_wcpdfinv_save_custom_field',data:JSON.stringify({type:type, name:name,code:code,id:rednaoCustomFieldParam.Id})},(result)=>{
                    result=JSON.parse(result);
                    if(result.success) {
                        rednaoCustomFieldParam.Id=result.result.row_id;
                        toastr.success('Custom field saved successfully');
                    }else{
                        toastr.error(result.errorMessage);
                    }
                    this.lSave.stop();
                });




            });
            this.lSave=Ladda.create( document.querySelector( '.save' )) ;
            this.codeMirror = CodeMirror.fromTextArea(jQuery('.rednaoCode')[0], {
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                mode: "text/x-php",
                gutters: ["CodeMirror-lint-markers"],
                lint:true});
            this.codeMirror.on('changes', (instance, change) => {

            });
        });

    }
}

new CustomFieldBuilder();