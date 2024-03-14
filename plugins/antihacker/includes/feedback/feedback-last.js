	jQuery(document).ready(function($) {
	   
  	$('#imagewaitfbl').hide(); 
    $deactivateSearch = $(".active");
    $deactivateSearch.click(function (evt) {
        
    billtempclass = evt.target.parentNode.className;
    // billclass = $(event.target).parent().prop("class");
    if( billtempclass != "deactivate") 
          { return; }
          
   

    billstring = evt.target.href;
    $deactivateLink = '';    
    if(billstring.includes('antihacker'))
      { 
        $deactivateLink = billstring; 
        product = 'antihacker';
        prodclass = 'anti_hacker';
      }
    else if (billstring.includes('boatdealer'))
      { 
        return;
      } 
      
      
      
   
    if($deactivateLink == '')
         { return; }  
         
    if(prodclass != 'boat_dealer_plugin')
       {$('.boat_dealer_plugin-wrap-deactivate').slideUp();}
       
    if(prodclass != 'anti_hacker')
      {$('.anti_hacker-wrap-deactivate').slideUp();}
      
    if(prodclass != 'report_attacks')
      {$('.report_attacks-wrap-deactivate').slideUp();}
      
    if(prodclass != 'stop_bad_bots')
      {$('.stop_bad_bots-wrap-deactivate').slideUp();} 
	
     evt.preventDefault(billstring);
        
     $billmodal = $('.'+prodclass+'-wrap-deactivate');
     
     $billmodal.prependTo($('#wpcontent')).slideDown();

     $('.'+prodclass+'-wrap-deactivate').prependTo($('#wpcontent')).slideDown();
     
     $('html, body').scrollTop(0);
        
     $( "."+prodclass+"-deactivate" ).click(function() {
             $('#imagewaitfbl').show();
             if( !$(this).hasClass('disabled')) 
                {  
                    $( "."+prodclass+"-close-submit" ).addClass('disabled');
                    $( "."+prodclass+"-close-dialog" ).addClass('disabled');
                    $( "."+prodclass+"-deactivate" ).addClass('disabled');
                    window.location.href = $deactivateLink; 
                }
          });

     $( "."+prodclass+"-close-submit" ).click(function() {
                     window.location.href = $deactivateLink;
         }); // end clicked button share ..


         $( "."+prodclass+"-close-dialog" ).click(function(evt) {
            if( ! $(this).hasClass('disabled')) 
            {
               $('#imagewaitfbl').hide();
               $billmodal = $('.'+prodclass+'-wrap-deactivate');
               $billmodal.slideUp();
            }
         });                       
   	}); // end clicked Deactivated ...
});  // end jQuery  