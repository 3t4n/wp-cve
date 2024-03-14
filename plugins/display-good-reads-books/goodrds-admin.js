jQuery(document).ready(function() {
    var wrapper         = jQuery(".exceptions"); 
    var add_button      = jQuery(".add_field_button");
    
    var x = 1; // count
    jQuery(add_button).click(function(e){
        e.preventDefault();
        x++;
        jQuery('<tr class="exceptions"><td></td><td>#<input class="code" type="text" name="goodrds_options[exceptions][ids][]" placeholder="Book ID"></td><td><input class="code" type="text" name="goodrds_options[exceptions][urls][]" placeholder="http://"> <a href="#">[x]</a></td></tr>').insertBefore('tr#last');
        goodrds_remove_listener();
    });
    
    function goodrds_remove_listener() {
	    jQuery('#goodrds tr.exceptions td a').each(function(){
		    jQuery(this).click(function(e){
		        e.preventDefault(); 
		        jQuery(this).parent().parent('tr').remove();
		        x--;
		    })	
	    });
    }
    goodrds_remove_listener(); 		    
});