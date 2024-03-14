jQuery(document).ready(function($) {
   var billvote = $('#billclassvote').val();
    $bill_vote_modal = $('.'+billvote+'-wrap-vote');
    $bill_vote_modal.prependTo($('#wpcontent')).slideDown();
   
    $('html, body').scrollTop(0);
	$('.bill-vote-action').on('click', function(e) {
      	var $this = $(this);
   		url= $this.attr('href');
        
	   e.preventDefault();
   
       vote = '';
       if(url.includes('vote=yes'))
         { vote = 'yes'; }
         
       if(url.includes('vote=no'))
         { vote = 'no'; }
   
       if(url.includes('vote=later'))
         { vote = 'later'; }
                    
		$.ajax({
			type: 'get',
            timeout: 15000,
			url: url, // url,
            data: {
              action : 'vote', 
              vote : vote
              },
      /*
			beforeSend: function() {
        	   // if (typeof $this.data('action') !== 'undefined') 
               if(url.includes('action'))
               {
                 // window.open(url);
               }
			},
      */
	    	//	success: function(data) {}
 		    complete  : function () {
			   $($bill_vote_modal).slideUp();
               urlsite = $this.data("action");
               if(vote = 'yes')
                {
                   if(urlsite.includes('http'))
                   { window.open(urlsite); }
                }
            } 
		});
	});
    
});