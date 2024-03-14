(function($){
        

        // next step click from sif pop
        $( "#esig-ninja-create" ).click(function() {
 
                   var form_id= $('select[name="esig_ninja_form_id"]').val();
                   $("#esig-ninja-form-first-step").hide();
                   // jquery ajax to get form field . 
                   jQuery.post(esigAjax.ajaxurl,{ action:"esig_ninja_form_fields",form_id:form_id},function( data ){ 
				      $("#esig-nf-field-option").html(data);
				},"html");
                   
                   $("#esig-nf-second-step").show();                        
  
        });
 
        // ninja add to document button clicked 
        $( "#esig-ninja-insert" ).click(function() {
 
                   var form_id= $('input[name="esig_nf_form_id"]').val() ;
                   var field_id =$('select[name="esig_nf_field_id"]').val();
                   var displayType =$('select[name="esig_ninja_value_display_type"]').val();
                   // 

                if (field_id == "all") {
                        //$("#esig_formidableform_field_id").each(function () {
                        $('select#esig_nf_field_id').find('option').each(function () {

                                // Add $(this).val() to your list
                                let allField = $(this).val();
                                if (allField == "all") return true;
                                var return_text = '<p> [esigninja formid="' + form_id + '" field_id="' + allField + '" display="' + displayType + '" ] </p>';
                                esig_sif_admin_controls.insertContent(return_text);
                        });
                }
                else {
                        var return_text = ' [esigninja formid="' + form_id + '" field_id="' + field_id + '" display="' + displayType + '" ] ';
                        esig_sif_admin_controls.insertContent(return_text);
                }   
            
             tb_remove();
                     
                   
        });
        
        
        //if overflow
        $('#select-ninja-form-list').click(function(){
            
            
          
            $(".chosen-drop").show(0, function () { 
				$(this).parents("div").css("overflow", "visible");
				});
            
            
            
        });
	
})(jQuery);



