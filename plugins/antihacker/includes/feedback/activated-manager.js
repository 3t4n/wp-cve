jQuery(document).ready(function($){
   var billclass = $('#billclass').val();
   $bill_feedback_modal = $('.'+billclass);
   
   $bill_feedback_modal.prependTo($('body')).slideDown();
   $('#wpwrap').css('opacity', '.1');
   $bill_feedback_modal.css('display', 'block');
   
   
   $( "."+billclass+"-close-dialog" ).click(function() {
     $bill_feedback_modal.slideUp();                          
     $('#wpwrap').css('opacity', '1');
     // Cookie
     var d = new Date();
     d.setTime(d.getTime() + (1*60*60*1000));
     var expires = "expires="+ d.toUTCString();
     cvalue = "0-"+document.domain;
     document.cookie = billclass + "=" + cvalue + ";" + expires + ";path=/";
  });
  $( "."+billclass+"-close-submit" ).click(function() {
                     var username = $('#username').val();
                     var version = $("#version").val();
                     var email = $('#email').val();
                     var produto = $('#produto').val();
                     var wpversion = $('#wpversion').val();
                     var dom = document.domain;
                     var limit = $('#limit').val();
                     var wplimit = $('#wplimit').val();
                     var usage = $('#usage').val();

                     $('#imagewait').show(); 
                     $( "."+billclass+"-close-dialog" ).addClass('disabled');
                     $( "."+billclass+"-close-submit" ).addClass('disabled');
                     $.ajax({
                	    url       : 'https://billminozzi.com/httpapi/httpapi.php',
                        withCredentials: true,
                        timeout: 15000,
                		method    : 'POST',
                        crossDomain: true,
                		data      : {
                		    email: email,
                            name: username,
                            dom: dom,
                            version: version,
                            produto: produto,
                            wpversion: wpversion,
                            wplimit: wplimit,
                            usage: usage,
                            limit: limit,
                            status: 'activated'
                			},
                		complete  : function () {
                             $bill_feedback_modal.slideUp();                          
                             $('#wpwrap').css('opacity', '1');
                             // Cookie
                             var d = new Date();
                             d.setTime(d.getTime() + (1*60*60*1000));
                             var expires = "expires="+ d.toUTCString();
                             cvalue = "1-"+document.domain;
                             document.cookie = billclass + "=" + cvalue + ";" + expires + ";path=/";
                        }
                	 }); // end ajax  
  });
});