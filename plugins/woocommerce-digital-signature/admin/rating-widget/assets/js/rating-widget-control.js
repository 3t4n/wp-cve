(function($){
        

       
        $( ".esign_woocommerce_ratting_widget_yes_button" ).click(function(e) {
                e.preventDefault();
                 var ratting_url = $("#woocommerce-rating-url").val();
                
                 
              
 
                 $(".esign_woocommerce_ratting_widget_info").html("That's awesome! Could you please do me a BIG favor and give it a 5-star rating on Wordpress to help us spread the word and boost our motivation?");     
                 $(".esign_woocommerce_ratting_widget_yes").removeClass("col-sm-1");
                 $(".esign_woocommerce_ratting_widget_yes").addClass("col-sm-3");
                 $(".esign_woocommerce_ratting_widget_yes").html("<a onclick='woocommerce_hide_permanent()' href='"+ratting_url+"' class='button-primary'>OK, you deserve it!</a>"); 
                 $(".esign_woocommerce_ratting_widget_no").removeClass("col-sm-4");
                 $(".esign_woocommerce_ratting_widget_no").addClass("col-sm-3");
                 $(".esign_woocommerce_ratting_widget_no").attr("id","woocommerce_rating_widget_hide");
                 $(".esign_woocommerce_ratting_widget_no").html("<a onclick='woocommerce_hide_permanent()' href='#'>No Thanks</a>"); 
                 $(".esign_monster_icon").removeClass("col-sm-7");
                 $(".esign_monster_icon").addClass("col-sm-6");
     
               
     
     
                });



        
        
    
        $( ".esign_woocommerce_ratting_widget_no_button" ).click(function(e) {
                e.preventDefault();

                var pluginName = $("#woocommerce-plugin-name").val();
 
                 $(".esign_woocommerce_ratting_widget_info").html("We're sorry to hear you aren't enjoying our WP E-Signature and "+pluginName+" integration. We would love a chance to improve. Could you take a minute and let us know what we can do better?");     
                 $(".esign_woocommerce_ratting_widget_yes").removeClass("col-sm-1");
                 $(".esign_woocommerce_ratting_widget_yes").addClass("col-sm-2");
                 $(".esign_woocommerce_ratting_widget_yes").html('<input type="submit" id="esig-woocommerce-action-ratting-widget" class="button action" onclick="woocommerceGiveFeedback()" value="Give Feedback">'); 
                 $(".esign_woocommerce_ratting_widget_no").removeClass("col-sm-4");
                 $(".esign_woocommerce_ratting_widget_no").addClass("col-sm-3");
                 $(".esign_woocommerce_ratting_widget_no").attr("id","woocommerce_rating_widget_hide");                                 
                 $(".esign_woocommerce_ratting_widget_no").html("<a onclick='woocommerce_hide_permanent()' href='#'>No Thanks</a>"); 
                 
        }); 


        
        
        
        
	
})(jQuery);


    

function woocommerceGiveFeedback() {
        var pluginName = document.getElementById('woocommerce-plugin-name').value;
        woocommerce_hide_permanent();

        var feedback = document.getElementById('woocommerce-feedback-url').value;
        window.location.replace(feedback);
} 

function woocommerce_hide_permanent() {

        var esignRatting = document.getElementById('esign-woocommerce-ratting');
        esignRatting.parentNode.removeChild(esignRatting);

        var pluginName = document.getElementById('woocommerce-plugin-name').value;
        esigRemoteRequest("esig_woocommerce_ratting_widget_remove", "POST", function(pluginName){
        });
} 
