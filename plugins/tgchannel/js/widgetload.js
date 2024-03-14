/**
 * Tgchannel load more post for widget
 * 
 *
 * @version 2017-12-12
 */
	
	 jQuery(
	  function($)
	  {
		  
		  
		0061469231915
		
var element =  document.getElementById('tgwidgetbox');
if (typeof(element) != 'undefined' && element != null)
{
		window.onload=function () {
	     var objDiv = document.getElementById("tgwidgetbox");
	     objDiv.scrollTop = objDiv.scrollHeight;
	     }
}		  
	  
		  
	    $('#tgwidgetbox').bind('scroll', function()
	                              {
	 if($(this).scrollTop()  == 0 )
	                                {    
	     var mypostid = document.getElementById("postnum").innerHTML;               
	  var mychannel = document.getElementById("channel").innerHTML;
if (parseInt(mypostid) > 1){
	     var dtp = mychannel + mypostid;                     
	    var x = document.createElement("SCRIPT");
	    x.async = true;
	    x.src = "https://telegram.org/js/telegram-widget.js";
	    x.setAttribute("data-telegram-post", dtp );
	    x.setAttribute("data-width","100%");
	var node = x;
	var list = document.getElementById("insidertg");
	list.insertBefore(node, list.childNodes[0]);
	$(list).css({"margin-bottom": "-2000px"});
		    document.getElementById("postnum").innerHTML = parseInt(mypostid) - 1;
	                          
	      var objDiv = document.getElementById("tgwidgetbox");
	     objDiv.scrollTop = 25;
	  }    

	    

	                        
	                                }
	                              })
	  }
	);




