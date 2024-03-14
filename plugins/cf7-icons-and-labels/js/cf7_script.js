/* Contact forms 7 using Font Awesome and dynamic labels
   USAGE->
   add class="cf7-fa-icon-fa-icon-name" or "cf7-label-The_label_name" for which icon or label to be set
   here fa-icon-name is the name of the fontawesome icon and The_label_name is the name of the label to set
   cf7-fa-icon- must be used for icon and cf7-label- must be for label in class name
   use underscore for spaces in label names
*/

jQuery(document).ready(function(){
	
	// Icons
	var checkIcon = "cf7-fa-icon-";         
    jQuery('[class^="cf7-fa-icon-"], [class*=" cf7-fa-icon-"]').each(function () {    
        // Get array of class names   
        var cls = jQuery(this).attr('class').split(' ');       
        for (var i = 0; i < cls.length; i++) {
            // Iterate over the class and use it if it matches
            if (cls[i].indexOf(checkIcon) > -1) {        
                var iconName = cls[i].slice(checkIcon.length, cls[i].length);
				if(iconName!='')
				{
					jQuery(this).after("<i class='fa " +iconName+" cf7-form-icons'></i>");
					jQuery(this).addClass("cf7-icon-field");
				} 
            }       
        }    
    });
	
	
	// Labels
	var checkLabel = "cf7-label-";         
    jQuery('[class^="cf7-label-"], [class*=" cf7-label-"]').each(function () {    
        // Get array of class names   
        var cls = jQuery(this).attr('class').split(' ');       
        for (var i = 0; i < cls.length; i++) {
            // Iterate over the class and use it if it matches
            if (cls[i].indexOf(checkLabel) > -1) {        
                var labelName = cls[i].slice(checkLabel.length, cls[i].length);
				if(labelName!='')
				{
					var label = labelName.replace(/_/g," ");
					jQuery(this).after("<label class='label-"+labelName+" cf7-form-labels'>" +label+"</label>");
					jQuery(this).addClass("cf7-label-field");
				} 
            }       
        }    
    });

	// Check if Both Icons and labels are exists
	jQuery('.wpcf7-form-control').each(function () {		
		if(jQuery(this).hasClass('cf7-label-field') && jQuery(this).hasClass('cf7-icon-field'))
		{
			jQuery(this).addClass("cf7-common-field");
			jQuery(this).removeClass("cf7-icon-field");
			jQuery(this).removeClass("cf7-label-field");
		}
	});
});

// add class after focus change
jQuery(document).on('focusout','.wpcf7 .wpcf7-form-control',function(){
   if(jQuery(this).val() !== ''){
      jQuery(this).closest('.wpcf7-form-control-wrap').find('.wpcf7-form-control, .cf7-form-labels, .cf7-form-icons').addClass('field-has-value');
   }else{
      jQuery(this).closest('.wpcf7-form-control-wrap').find('.wpcf7-form-control, .cf7-form-labels, .cf7-form-icons').removeClass('field-has-value');
   }
});

// add class after form submission
jQuery( document ).ajaxComplete(function( event, xhr, settings ) {	

  if ( xhr.responseText ) {
	  jQuery('.wpcf7-form-control').each(function () {	  
	  if(jQuery(this).val() !== ''){
			  jQuery(this).closest('.wpcf7-form-control-wrap').find('.wpcf7-form-control, .cf7-form-labels, .cf7-form-icons').addClass('field-has-value');
		   }else{
			  jQuery(this).closest('.wpcf7-form-control-wrap').find('.wpcf7-form-control, .cf7-form-labels, .cf7-form-icons').removeClass('field-has-value');
		   }
      });	   
  }
});


jQuery(document).ready(function(){
	// Submit Button wraper
	jQuery( ".cf7-submit" ).wrap( "<div class='cf7-submit-btn'></div>" );
	
	// Submit-btn-icon
	var submit_icon = "submit-icon-";         
    jQuery('[class^="submit-icon-"], [class*=" submit-icon-"]').each(function () {    
        // Get array of class names   
        var cls = jQuery(this).attr('class').split(' ');       
        for (var i = 0; i < cls.length; i++) {
            // Iterate over the class and use it if it matches
            if (cls[i].indexOf(submit_icon) > -1) {        
                var submitIcon = cls[i].slice(submit_icon.length, cls[i].length);
				if(submitIcon!='')
				{
					jQuery('.cf7-submit-btn').addClass(submitIcon);
				} 
            }       
        }    
    });
	
});