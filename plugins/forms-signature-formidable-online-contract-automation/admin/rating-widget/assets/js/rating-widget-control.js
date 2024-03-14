(function($){
        

       
        $( ".esign_formidable_ratting_widget_yes_button" ).click(function(e) {
                e.preventDefault();
                 var ratting_url = $("#rating-formidable-url").val();
                
                 
              
 
                 $(".esign_formidable_ratting_widget_info").html("That's awesome! Could you please do me a BIG favor and give it a 5-star rating on Wordpress to help us spread the word and boost our motivation?");     
                 $(".esign_formidable_ratting_widget_yes").removeClass("col-sm-1");
                 $(".esign_formidable_ratting_widget_yes").addClass("col-sm-3");
                 $(".esign_formidable_ratting_widget_yes").html("<a onclick='formidable_hide_permanent()' href='"+ratting_url+"' class='button-primary'>OK, you deserve it!</a>"); 
                 $(".esign_formidable_ratting_widget_no").removeClass("col-sm-4");
                 $(".esign_formidable_ratting_widget_no").addClass("col-sm-3");
                 $(".esign_formidable_ratting_widget_no").attr("id","formidable_rating_widget_hide");
                 $(".esign_formidable_ratting_widget_no").html("<a onclick='formidable_hide_permanent()' href='#'>No Thanks</a>"); 
     
                 $(".esign_monster_icon").removeClass("col-sm-7");
                 $(".esign_monster_icon").addClass("col-sm-6");
               
     
     
                });



        
        
    
        $( ".esign_formidable_ratting_widget_no_button" ).click(function(e) {
                e.preventDefault();

                var pluginName = $("#formidable-plugin-name").val();
 
                 $(".esign_formidable_ratting_widget_info").html("We're sorry to hear you aren't enjoying our WP E-Signature and "+pluginName+"s integration. We would love a chance to improve. Could you take a minute and let us know what we can do better?");     
                 $(".esign_formidable_ratting_widget_yes").removeClass("col-sm-1");
                 $(".esign_formidable_ratting_widget_yes").addClass("col-sm-2");
                 $(".esign_formidable_ratting_widget_yes").html('<input type="submit" id="esig-formidable-action-ratting-widget" class="button action" onclick="formidableGiveFeedback()" value="Give Feedback">'); 
                 $(".esign_formidable_ratting_widget_no").removeClass("col-sm-4");
                 $(".esign_formidable_ratting_widget_no").addClass("col-sm-3");
                 $(".esign_formidable_ratting_widget_no").attr("id","formidable_rating_widget_hide");                                 
                 $(".esign_formidable_ratting_widget_no").html("<a onclick='formidable_hide_permanent()' href='#'>No Thanks</a>"); 

        }); 


        
        
        
        
	
})(jQuery);


    

function formidableGiveFeedback() {
        var pluginName = document.getElementById('formidable-plugin-name').value;
        formidable_hide_permanent();

        var feedback = document.getElementById('formidable-feedback-url').value;
        window.location.replace(feedback);
} 

function formidable_hide_permanent() {

        var esignRatting = document.getElementById('formidable-esign-ratting');
        esignRatting.parentNode.removeChild(esignRatting);

        var pluginName = document.getElementById('formidable-plugin-name').value;
        esigRemoteRequest("esig_formidable_ratting_widget_remove", "POST", function(pluginName){
        });
} 
