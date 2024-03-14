(function($){
        
       
        $( ".esign_ninja_ratting_widget_yes_button" ).click(function(e) {
                e.preventDefault();
                 var ratting_url = $("#ninja-rating-url").val();
 
                 $(".esign_ninja_ratting_widget_info").html("That's awesome! Could you please do me a BIG favor and give it a 5-star rating on Wordpress to help us spread the word and boost our motivation?");     
                 $(".esign_ninja_ratting_widget_yes").removeClass("col-sm-1");
                 $(".esign_ninja_ratting_widget_yes").addClass("col-sm-3");
                 $(".esign_ninja_ratting_widget_yes").html("<a onclick='ninja_hide_permanent()' href='"+ratting_url+"' class='button-primary'>OK, you deserve it!</a>"); 
                 $(".esign_ninja_ratting_widget_no").removeClass("col-sm-4");
                 $(".esign_ninja_ratting_widget_no").addClass("col-sm-3");
                 $(".esign_ninja_ratting_widget_no").attr("id","ninja_rating_widget_hide");
                 $(".esign_ninja_ratting_widget_no").html("<a onclick='ninja_hide_permanent()' href='#'>No Thanks</a>"); 
        
                 $(".esign_monster_icon").removeClass("col-sm-7");
                 $(".esign_monster_icon").addClass("col-sm-6");

                });

     
    
        $( ".esign_ninja_ratting_widget_no_button" ).click(function(e) {
                e.preventDefault();

                var pluginName = $("#ninja-plugin-name").val();
 
                 $(".esign_ninja_ratting_widget_info").html("We're sorry to hear you aren't enjoying our WP E-Signature and "+pluginName+"s integration. We would love a chance to improve. Could you take a minute and let us know what we can do better?");     
                 $(".esign_ninja_ratting_widget_yes").removeClass("col-sm-1");
                 $(".esign_ninja_ratting_widget_yes").addClass("col-sm-2");
                 $(".esign_ninja_ratting_widget_yes").html('<input type="submit" id="esig-ninja-action-ratting-widget" class="button action" onclick="ninjaGiveFeedback()" value="Give Feedback">'); 
                 $(".esign_ninja_ratting_widget_no").removeClass("col-sm-4");
                 $(".esign_ninja_ratting_widget_no").addClass("col-sm-3");
                 $(".esign_ninja_ratting_widget_no").attr("id","ninja_rating_widget_hide");                                 
                 $(".esign_ninja_ratting_widget_no").html("<a onclick='ninja_hide_permanent()' href='#'>No Thanks</a>"); 

        }); 

     
	
})(jQuery);


    

function ninjaGiveFeedback() {
        var pluginName = document.getElementById('ninja-plugin-name').value;
        ninja_hide_permanent();

        var feedback = document.getElementById('ninja-feedback-url').value;
        window.location.replace(feedback);
} 

function ninja_hide_permanent() {

        var esignRatting = document.getElementById('esign-ninja-ratting');
        esignRatting.parentNode.removeChild(esignRatting);
        
        var pluginName = document.getElementById('ninja-plugin-name').value;
        esigRemoteRequest("esig_ninja_ratting_widget_remove", "POST", function(pluginName){
               
        });
} 
