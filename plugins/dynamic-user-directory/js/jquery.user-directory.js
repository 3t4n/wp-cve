(function( $ ) {
    $(function() {
         
        // Add Color Picker to all inputs that have 'color-field' class
        $( '.cpa-color-picker' ).wpColorPicker();
         
    });
    	
	 $(document).ready(function() {
				
    	if($('#ud_display_listings').val() == 'horizontally') {    	 	
    		$("#ud-horizontal-settings-1").show();  
			$("#ud-horizontal-settings-2").show();  	
			$("#ud-horizontal-settings-3").show();  
			$("#ud-horizontal-settings-4").show(); 
			$("#horizontal-width-settings-1").show(); 
			$("#horizontal-width-settings-2").show(); 
			$("#horizontal-width-settings-3").show(); 
			$("#horizontal-width-settings-4").show(); 
			$("#ud_avatar_size_and_padding").show();
			$("#avatar_padding").hide();
			
			
			$("#user_directory_border option[value='surrounding_border']").remove();
			
		}
    	else
    	{
    		$("#ud-horizontal-settings-1").hide();
			$("#ud-horizontal-settings-2").hide();
			$("#ud-horizontal-settings-3").hide();  
			$("#ud-horizontal-settings-4").hide(); 
			$("#horizontal-width-settings-1").hide(); 	
            $("#horizontal-width-settings-2").hide();
			$("#horizontal-width-settings-3").hide();
			$("#horizontal-width-settings-4").hide(); 
			$("#ud_avatar_size_and_padding").show();
			$("#avatar_padding").show();
			
			var optionExists = ($("#user_directory_border option[value='surrounding_border']").length > 0);

			if(!optionExists)
			{
				$('#user_directory_border').append("<option value='surrounding_border'>Surrounding Border</option>");
			}

    	} 
    }); 	
    
    $(function() {
   	$('#ud_display_listings').change(function() {
       	if($('#ud_display_listings').val() == 'horizontally') { 

			if ($("#ud_show_heading_labels").is(':checked'))
			{
				$("#user_name_label").show();
				$("#email_label").show();
				$("#website_label").show();
				$("#address_label").show();
				$("#social_label").show();
				$("#date_registered_label").show();
				$("#roles_label").show();
				
				for(var i=1; i<11; i++)
				{	
					if($('#user_directory_num_meta_flds').val() >= i) 
						$("#col_meta_label_" + i).show();					
				}
			}
			else
			{
				$("#user_name_label").hide();
				$("#email_label").hide();
				$("#website_label").hide();
				$("#address_label").hide();
				$("#social_label").hide();
				$("#date_registered_label").hide();
				$("#roles_label").hide();
				
				for(var i=1; i<11; i++)
				{	
					if($('#user_directory_num_meta_flds').val() >= i) 
						$("#col_meta_label_" + i).hide();					
				}
			}
			
			for(var i=1; i<11; i++)
			{	
				if($('#user_directory_num_meta_flds').val() >= i) 
					$("#meta_label_" + i).hide();					
			}
				
    		$("#ud-horizontal-settings-1").show();  
			$("#ud-horizontal-settings-2").show();  	
			$("#ud-horizontal-settings-3").show();  
			$("#ud-horizontal-settings-4").show(); 
			$("#horizontal-width-settings-1").show();
			$("#horizontal-width-settings-2").show(); 
			$("#horizontal-width-settings-3").show(); 
			$("#horizontal-width-settings-4").show(); 
			$("#ud_avatar_size_and_padding").show();
			$("#avatar_padding").hide();
			$("#horizontal-width-settings-2").show(); 	
			
			$("#user_directory_border option[value='surrounding_border']").remove();
		}
    	else
    	{
			$("#user_name_label").hide();
			$("#email_label").hide();
			$("#website_label").hide();
			$("#address_label").hide();
			$("#social_label").hide();
			$("#date_registered_label").hide();
			$("#roles_label").hide();
			
			for(var i=1; i<11; i++)
			{	
				if($('#user_directory_num_meta_flds').val() >= i)  
				{					
					$("#meta_label_" + i).show();
					$("#col_meta_label_" + i).hide();
				}					
			}
			
    		$("#ud-horizontal-settings-1").hide();
			$("#ud-horizontal-settings-2").hide();
			$("#ud-horizontal-settings-3").hide();  
			$("#ud-horizontal-settings-4").hide(); 
            $("#horizontal-width-settings-1").hide(); 	
            $("#horizontal-width-settings-2").hide(); 	
			$("#horizontal-width-settings-3").hide(); 
			$("#horizontal-width-settings-4").hide(); 			
			$("#ud_avatar_size_and_padding").show();
			$("#avatar_padding").show();

			var optionExists = ($("#user_directory_border option[value='surrounding_border']").length > 0);

			if(!optionExists)
			{
				$('#user_directory_border').append("<option value='surrounding_border'>Surrounding Border</option>");
			}
    	} 
   	});
    });
		    
    
    
    $(document).ready(function() {	
    	if ($("#user_directory_show_avatars").is(':checked'))
		{
    		$("#user_directory_avatar_style").show();
			$("#avatar_padding").show();
			$("#custom_avatar").show();
			
			try 
			{
				if($('#ud_display_listings').val() == 'horizontally') 
					$("#avatar_padding").hide();
			}
			catch(err){;}
		}
    	else
		{
    		$("#user_directory_avatar_style").hide();
			
			try {
				$("#ud_avatar_size_and_padding").hide();
				$("#avatar_padding").hide();
				$("#custom_avatar").hide();
			}
			catch(err){;}
		}
		
    });
    
    $(document).ready(function() {	
    	if ($("#ud_author_page").is(':checked')) {	
    		$("#ud_target_window").show();
			$("#open_linked_page").show();
			$('#ud_auth_or_bp').show();
			
			if($('#ud_auth_or_bp').length)
			{
				if($('#ud_auth_or_bp').val() === 'auth')
					$("#show-auth-pg-lnk").show();
				else 
					$("#show-auth-pg-lnk").hide();
			}
			else
				$("#show-auth-pg-lnk").show();
		}
    	else {			
		    $("#show-auth-pg-lnk").hide();
    		$("#ud_target_window").hide();	
			$("#open_linked_page").hide();
			$('#ud_auth_or_bp').hide();
		}
    });
    
    $(function() {
   	$('#ud_author_page').change(function() {
			$("#open_linked_page").toggle(this.checked);
       		$("#ud_target_window").toggle(this.checked);
			$('#ud_auth_or_bp').toggle(this.checked);
			
			if($('#ud_auth_or_bp').length)
			{
				if($('#ud_auth_or_bp').val() === 'auth' && $('#ud_author_page').is(':checked'))
					$("#show-auth-pg-lnk").show();
				else 
					$("#show-auth-pg-lnk").hide();
			}
			else
			{
				$("#show-auth-pg-lnk").toggle(this.checked);
			}
   	});
    });
	
	$(function() {
   	$('#ud_auth_or_bp').change(function() {
       					
			if($('#ud_auth_or_bp').val() === 'bp')
			{
				$("#show-auth-pg-lnk").hide();
			}
			else 
			{
				$("#show-auth-pg-lnk").show();
			}
   	});
    });
    
    $(document).ready(function() {
    	if($('#user_directory_border').val() == 'no_border') {    	 	
    		$("#border-settings").hide(); 
			$("#border-settings-2").hide();
   	}
    	else
    	{
    		$("#border-settings").show();
			$("#border-settings-2").show();
    	} 
    }); 	
    
    $(function() {
   	$('#user_directory_border').change(function() {
       		if($('#user_directory_border').val() == 'no_border') {    	 	
    			$("#border-settings").hide(); 
				$("#border-settings-2").hide(); 
   		}
    		else
    		{
    			$("#border-settings").show();
				$("#border-settings-2").show();
    		} 
   	});
    });
    
    $(function() {
   	$('#user_directory_show_avatars').change(function() {
       		$("#user_directory_avatar_style").toggle(this.checked);
				
			if($('#user_directory_show_avatars').is(':checked'))
			{
				$("#ud_avatar_size_and_padding").show();
				$("#avatar_padding").show();
				$("#custom_avatar").show();
				
				try 
				{
					if($('#ud_display_listings').val() == 'horizontally') 
						$("#avatar_padding").hide();
				}
				catch(err){;}
			}
			else
			{
				$("#ud_avatar_size_and_padding").hide();
				$("#avatar_padding").hide();
				$("#custom_avatar").hide();
			}
   	});
    });
    
    $(function() {
   	$('#ud_show_srch').change(function() 
	{
       	$("#ud_srch_style").toggle(this.checked);
			
		if ($("#ud_show_srch").is(':checked'))
		{			
			var dd_srch_exists = false;
			
			$('#ud_sort_show_categories_as > option').each(function(){ 
					if (this.value == 'dd-srch') 
						dd_srch_exists = true;	
			});
			
			if(!dd_srch_exists) 
				$('#ud_sort_show_categories_as').append('<option value="dd-srch">Dropdown (Search Field)</option>');
		}
    	else
		{
			$("#ud_sort_show_categories_as option[value='dd-srch']").remove();
		}
   	});
    });
    
    $(document).ready(function() {
    	if($('#ud_directory_type').val() == 'all-users') 
		{   
    		$("#one-page-dir-type-a").show();   	 	
    		$("#letter-link-dir-type").hide();
			$("#show_srch_results").hide();			
    		
    		if($('#ud_letter_divider').val() !== 'nld')
			{
				$("#one-page-dir-type-b").show();  
				
				if($('#ud_letter_divider').val() == 'ld-bb' || $('#ud_letter_divider').val() == 'ld-tb')
				{
					$("#letter-divider-border-settings").show();
					$("#letter-divider-border-settings-2").show();
					$("#divider-fill-color").hide();
					$("#divider-font-size").show();
				}
				else if($('#ud_letter_divider').val() == 'ld-lo')
				{
					$("#letter-divider-border-settings").hide();
					$("#letter-divider-border-settings-2").hide();
					$("#divider-fill-color").hide();
					$("#divider-font-size").show();
				}
				else
				{
					$("#letter-divider-border-settings").hide();
					$("#letter-divider-border-settings-2").hide();
					$("#divider-fill-color").show();
					$("#divider-font-size").hide();
				}
    		     
			}
    		else 
			{		
    		     $("#one-page-dir-type-b").hide(); 
				 $("#letter-divider-border-settings").hide();
				 $("#letter-divider-border-settings-2").hide();
			}
			
			//Custom Sort Add-on
			
			//Removals
			$("#ud_sort_show_categories_as option[value='dd']").remove();
			
			if( $('#alpha_links_scroll_active').val() !== "1" || $('#ud_sort_cat_header').val() === "nch") 
					$("#ud_sort_show_categories_as option[value='links']").remove();
							
			if( $('#alpha_links_scroll_active').val() !== "1" || $('#meta_flds_srch_active').val() !== "1" || $('#ud_sort_cat_header').val() === "nch") 
					$("#ud_sort_show_categories_as option[value='dd-links']").remove();
			
			if( $('#meta_flds_srch_active').val() !== "1" ) 
			{
					$("#ud_sort_show_categories_as option[value='no-show']").remove();
			}
			
			//Appends
			var no_show_exists = false;
			
			$('#ud_sort_show_categories_as > option').each(function(){
					if (this.value == 'no-show') 
						no_show_exists = true;	
			});
			
			if(!no_show_exists && $('#meta_flds_srch_active').val() === "1") 
				$('#ud_sort_show_categories_as').append('<option value="no-show">Don\'t Show Categories</option>');
			
			//Hide appropriate fields
			var length = $('#ud_sort_show_categories_as').children('option').length;
			
			if(length == 1 && $('#ud_sort_show_categories_as').val() === "links" && $('#alpha_links_scroll_active').val() !== "1" 
					&& ( $('#ud_directory_type').val() == 'all-users' || $('#ud_show_srch_results').val() == 'single-page') )
			{				
				$('#show-cats-as').hide();
				
				$("#category-dd-1").hide();
				
				if($('#ud_sort_show_categories_as').val() === "links")
					$("#category-dd-2").hide();
				
				$("#category-dd-3").hide();
				
				$("#category-links-1").hide();
				$("#category-links-2").hide();
				$("#category-links-3").hide();
			}
			else if(length < 1 || $('#ud_sort_cat_header').val() === "nch")
			{
				if(length < 1)
				{
					$('#show-cats-as').hide();
					$("#category-dd-1").hide();
					$("#category-dd-2").hide();
					$("#category-dd-3").hide();
					$("#category-links-1").hide();
					$("#category-links-2").hide();
					$("#category-links-3").hide();
				}	
				else 
				{	
					if($('#ud_sort_show_categories_as').val() === "links" || $('#meta_flds_srch_active').val() !== "1")
					{
						$("#category-dd-1").hide();

						if($('#ud_sort_show_categories_as').val() === "links" )
						{
							$("#category-dd-2").hide();
							$("#category-dd-3").hide();
						}
					}
					
					if( $('#ud_sort_show_categories_as').val() !== "links" && $('#ud_sort_show_categories_as').val() !== "dd-links")
					{
						$("#category-links-1").hide();
						$("#category-links-2").hide();
						$("#category-links-3").hide();
					}
				}
			}
			else
			{
				if($('#ud_sort_show_categories_as').val() === "links" || $('#ud_sort_show_categories_as').val() === "dd-links" ) 
				{
					$("#category-links-1").show();
					$("#category-links-2").show();
					$("#category-links-3").show();
					
					if($('#ud_sort_show_categories_as').val() !== "dd-links")
					{
						$("#category-dd-1").hide();
						$("#category-dd-2").hide();
						$("#category-dd-3").hide();
					}
					else
					{
						$("#category-dd-1").show();
						$("#category-dd-2").show();
						$("#category-dd-3").show();
					}	
				}
				else if( $('#ud_sort_show_categories_as').val() === "dd-srch") 
				{
					$("#category-links-1").hide();
					$("#category-links-2").hide();
					$("#category-links-3").hide();
					
				}
			}
		}
    	else
    	{ 
			//Custom Sort Add-on ******************************************/
			$('#show-cats-as').show();

			var dd_exists = false;
			var dd_links_exists = false;
			var links_exists = false;
			
			$('#ud_sort_show_categories_as > option').each(function(){
				if (this.value == 'dd') 
					dd_exists = true;
				else if(this.value == 'links')
					links_exists = true;
				else if(this.value == 'dd-links')
					dd_links_exists = true;
			});
			
			//Appends
			if(!links_exists) 
					$('#ud_sort_show_categories_as').append('<option value="links">Links</option>');
				
			if(!dd_exists && $('#meta_flds_srch_active').val() !== "1")
				$('#ud_sort_show_categories_as').append('<option value="dd">Dropdown (Auto-Refresh)</option>');
							
			if(!dd_links_exists && $('#meta_flds_srch_active').val() === "1")
				$('#ud_sort_show_categories_as').append('<option value="dd-links">Links + Dropdown Search Field</option>');
			
			//Removals
			$("#ud_sort_show_categories_as option[value='no-show']").remove();
			
			if($('#meta_flds_srch_active').val() !== "1")
				$("#ud_sort_show_categories_as option[value='dd-links']").remove();
			else 
				$("#ud_sort_show_categories_as option[value='dd']").remove();
			
			//Hide appropriate fields
			if( $('#ud_sort_show_categories_as').val() === "links" || $('#ud_sort_show_categories_as').val() === "dd-links" )
			{
				$("#category-links-1").show();
				$("#category-links-2").show();
				$("#category-links-3").show();
				
				if($('#ud_sort_show_categories_as').val() !== "dd-links")
				{
					$("#category-dd-1").hide();
					$("#category-dd-2").hide();
					$("#category-dd-3").hide();
				}
			}
			if( $('#ud_sort_show_categories_as').val() === "dd" || $('#ud_sort_show_categories_as').val() === "dd-srch" 
				|| $('#ud_sort_show_categories_as').val() === "dd-links" )
			{
				if($('#ud_sort_show_categories_as').val() === "dd-srch" || $('#ud_sort_show_categories_as').val() === "dd-links" )
				{
					$("#category-dd-1").show();
				}	
				
				$("#category-dd-2").show();
				$("#category-dd-3").show();
				
				$("#category-links-1").hide();
				$("#category-links-2").hide();
				$("#category-links-3").hide();
			}
			//end Custom Sort Add-on *************************************/
						
			$("#letter-divider-border-settings").hide();
			$("#letter-divider-border-settings-2").hide();
    		$("#one-page-dir-type-a").hide();  
    		$("#one-page-dir-type-b").hide();  	 	
    		$("#letter-link-dir-type").show(); 
			$("#show_srch_results").show();
    	} 
    }); 	
    		
    $(function() {
   	$('#ud_letter_divider').change(function() {
		    
		if($('#ud_directory_type').val() == 'all-users') 
		{   
    		$("#one-page-dir-type-a").show();   	 	
    		$("#letter-link-dir-type").hide();
			$("#show_srch_results").hide();			
    		
    		if($('#ud_letter_divider').val() !== 'nld')
			{
				$("#one-page-dir-type-b").show();  
				
				if($('#ud_letter_divider').val() == 'ld-bb' || $('#ud_letter_divider').val() == 'ld-tb')
				{
					$("#letter-divider-border-settings").show();
					$("#letter-divider-border-settings-2").show();
					$("#divider-fill-color").hide();
					$("#divider-font-size").show();
				}
				else if($('#ud_letter_divider').val() == 'ld-lo')
				{
					$("#letter-divider-border-settings").hide();
					$("#letter-divider-border-settings-2").hide();
					$("#divider-fill-color").hide();
					$("#divider-font-size").show();
				}
				else
				{
					$("#letter-divider-border-settings").hide();
					$("#letter-divider-border-settings-2").hide();
					$("#divider-fill-color").show();
					$("#divider-font-size").hide();
				}
    		     
			}
    		else 
			{		
    		     $("#one-page-dir-type-b").hide(); 
				 $("#letter-divider-border-settings").hide();
				 $("#letter-divider-border-settings-2").hide();
			}
		}
    	else
    	{  
			$("#letter-divider-border-settings").hide();
			$("#letter-divider-border-settings-2").hide();
    		$("#one-page-dir-type-a").hide();  
    		$("#one-page-dir-type-b").hide();  	 	
    		$("#letter-link-dir-type").show(); 
			$("#show_srch_results").show();
    	} 
   	});
    });
			
	$(function() {
		$('#ud_custom_sort').change(function() {
							
			if ($("#ud_custom_sort").is(':checked'))
			{
				$("#custom-sort-sub-sort-fld").show();
				$("#ud-sort-fld").hide();
				$("#custom-sort-sub-sort-fld-desc").show();
				$("#ud-sort-fld-desc").hide();
				$("#dud-custom-sort-field-settings").show();  
				$("#dud-custom-sort-field-settings-header").show();  
			}
			else
			{
				$('#ud_sort_fld_key').val("");
				$("#custom-sort-sub-sort-fld").hide();
				$("#ud-sort-fld").show();
				$("#custom-sort-sub-sort-fld-desc").hide();
				$("#ud-sort-fld-desc").show();
				$("#dud-custom-sort-field-settings-header").hide();  
				$("#dud-custom-sort-field-settings").hide();  
			}
		});
    });
	
	$(document).ready(function() {
		if ($("#ud_custom_sort").is(':checked'))
			{
				$("#custom-sort-sub-sort-fld").show();
				$("#ud-sort-fld").hide();
				$("#custom-sort-sub-sort-fld-desc").show();
				$("#ud-sort-fld-desc").hide();
				$("#dud-custom-sort-field-settings").show();  
				$("#dud-custom-sort-field-settings-header").show();  
			}
			else
			{
				$("#custom-sort-sub-sort-fld").hide();
				$("#ud-sort-fld").show();
				$("#custom-sort-sub-sort-fld-desc").hide();
				$("#ud-sort-fld-desc").show();
				$("#dud-custom-sort-field-settings-header").hide();  
				$("#dud-custom-sort-field-settings").hide();  
			}
    });
 	
	$(function() {
   	$('#ud_sort_cat_header').change(function() {
		        		
		if($('#ud_sort_cat_header').val() !== 'nch')
		{
			 $("#category-header-border-settings-3").show(); 
			 $("#category-header-border-settings-4").show(); 
			
			if($('#ud_sort_cat_header').val() == 'ch-bb' || $('#ud_sort_cat_header').val() == 'ch-tb')
			{
				$("#category-header-border-settings").show();
				$("#category-header-border-settings-2").show();
				$("#sort-cat-fill-color").hide();
				$("#sort-cat-font-size").show();
			}
			else if($('#ud_sort_cat_header').val() == 'ch-lo')
			{
				$("#category-header-border-settings").hide();
				$("#category-header-border-settings-2").hide();
				$("#sort-cat-fill-color").hide();
				$("#sort-cat-font-size").show();
			}
			else
			{
				$("#category-header-border-settings").hide();
				$("#category-header-border-settings-2").hide();
				$("#sort-cat-fill-color").show();
				$("#sort-cat-font-size").show();
			} 
			
			if( !( $("#alpha_links_scroll_active").val() !== "1" && $('#ud_directory_type').val() == 'all-users') )
				$('#show-cats-as').show();
			
			if( $("#alpha_links_scroll_active").val() === "1" && $('#ud_directory_type').val() == 'all-users')
			{
				//Appends
				var dd_links_exists = false;
				var links_exists = false;
				
				$('#ud_sort_show_categories_as > option').each(function(){
					if (this.value == 'dd-links') 
						dd_links_exists = true;
					else if (this.value == 'links') 
						links_exists = true;
				});
				
				if(!links_exists) 
					$('#ud_sort_show_categories_as').append('<option value="links">Links</option>');
				if(!dd_links_exists) 
					$('#ud_sort_show_categories_as').append('<option value="dd-links">Links + Dropdown Search Field</option>');
			}
		}
		else 
		{
			 $("#category-header-border-settings-4").hide(); 
			 $("#category-header-border-settings-3").hide(); 
			 $("#category-header-border-settings-2").hide();
			 $("#category-header-border-settings").hide();
			 
			 if($('#ud_directory_type').val() == 'all-users') 
			 {
				
				$("#ud_sort_show_categories_as option[value='links']").remove();
				$("#ud_sort_show_categories_as option[value='dd-links']").remove();
							
				if( $('#meta_flds_srch_active').val() !== "1" ) 
				{
					$("#ud_sort_show_categories_as option[value='dd-srch']").remove();
					$("#category-dd-1").hide();
				}
				
				var length = $('#ud_sort_show_categories_as').children('option').length;
				
				if(length < 1)
				{
					$("#show-cats-as").hide();
					$("#category-dd-2").hide();
					$("#category-dd-3").hide();
				}
				
				$("#category-links-1").hide();
				$("#category-links-2").hide();
				$("#category-links-3").hide();
			 }				
		}
   	});
    });
	
	$(document).ready(function() {
	
		if($('#ud_sort_show_categories_as').is(":hidden") ) return;
		
		if($('#ud_sort_show_categories_as').val() === "no-show")
		{
			$("#category-links-1").hide();
			$("#category-links-2").hide();
			$("#category-links-3").hide();
			$("#category-dd-1").hide();
			$("#category-dd-2").hide();
			$("#category-dd-3").hide();
			$("#show-cats-dd-hide-dir-before-srch").hide();
			return;
		}
		
		if( ($('#ud_sort_show_categories_as').val() === "dd" || $('#ud_sort_show_categories_as').val() === "dd-srch" || $('#ud_sort_show_categories_as').val() === "dd-links") && $('#hide_dir_before_srch_active').val() == "1")
		{
			$("#show-cats-dd-hide-dir-before-srch").show();	
		}
		else
		{
			$("#show-cats-dd-hide-dir-before-srch").hide();	
		}
		
		if($('#ud_sort_show_categories_as').val() === "dd" || $('#ud_sort_show_categories_as').val() === "dd-srch")
		{
			$("#category-dd-2").show();
			
			if( ($('#meta_flds_srch_active').val() == "1" 
				&& ($('#ud_directory_type').val() == 'all-users' || $('#ud_show_srch_results').val() == 'single-page')) 
				|| ( $('#ud_sort_show_categories_as').val() === "dd-srch" && $("#ud_show_srch").is(':checked')) )
					$("#category-dd-1").show();
			else
					$("#category-dd-1").hide();
			
			$("#category-dd-3").show();
			
			$("#category-links-1").hide();
			$("#category-links-2").hide();
			$("#category-links-3").hide();
		}
		else if($('#ud_sort_show_categories_as').val() === "links")
		{
			$("#category-links-1").show();
			$("#category-links-2").show();
			$("#category-links-3").show();
			
			$("#category-dd-1").hide();
			$("#category-dd-2").hide();
			$("#category-dd-3").hide();
		}		
		else
		{
			$("#category-links-1").show();
			$("#category-links-2").show();
			$("#category-links-3").show();
			
			$("#category-dd-2").show();
			
			if($('#meta_flds_srch_active').val() == "1" 
				&& ($('#ud_directory_type').val() == 'all-users' || $('#ud_show_srch_results').val() == 'single-page'))
					$("#category-dd-1").show();
			else
				$("#category-dd-1").hide();
			
			$("#category-dd-3").show();
		}

		var length = $('#ud_sort_show_categories_as').children('option').length;
		
		if(length == 1 && $('#ud_sort_show_categories_as').val() === "links" && $('#alpha_links_scroll_active').val() !== "1" 
			&& ($('#ud_directory_type').val() == 'all-users' || $('#ud_show_srch_results').val() == 'single-page'))
		{
			$('#show-cats-as').hide();
			
			$("#category-dd-1").hide();
			$("#category-dd-2").hide();
			$("#category-dd-3").hide();
			
			$("#category-links-1").hide();
			$("#category-links-2").hide();
			$("#category-links-3").hide();
		}
	});	
	
	$(function() {
   	$('#ud_sort_show_categories_as').change(function() {
		
		if($('#ud_sort_show_categories_as').val() === "no-show")
		{
			$("#category-links-1").hide();
			$("#category-links-2").hide();
			$("#category-links-3").hide();
			$("#category-dd-1").hide();
			$("#category-dd-2").hide();
			$("#category-dd-3").hide();
			$("#show-cats-dd-hide-dir-before-srch").hide();
			
			return;
		}
		
		if( ($('#ud_sort_show_categories_as').val() === "dd" || $('#ud_sort_show_categories_as').val() === "dd-srch" || $('#ud_sort_show_categories_as').val() === "dd-links") && $('#hide_dir_before_srch_active').val() == "1")
		{
			$("#show-cats-dd-hide-dir-before-srch").show();	
		}
		else
		{
			$("#show-cats-dd-hide-dir-before-srch").hide();	
		}
		
		if($('#ud_sort_show_categories_as').val() === "dd" || $('#ud_sort_show_categories_as').val() === "dd-srch")
		{			
			$("#category-dd-2").show();
			
			if( ($('#meta_flds_srch_active').val() == "1" 
				&& ($('#ud_directory_type').val() == 'all-users' || $('#ud_show_srch_results').val() == 'single-page')) 
				|| ( $('#ud_sort_show_categories_as').val() === "dd-srch" && $("#ud_show_srch").is(':checked')) )
					$("#category-dd-1").show();
			else
					$("#category-dd-1").hide();
			
			$("#category-dd-3").show();
			
			$("#category-links-1").hide();
			$("#category-links-2").hide();
			$("#category-links-3").hide();
		}
		else if($('#ud_sort_show_categories_as').val() === "links")
		{
			$("#category-links-1").show();
			$("#category-links-2").show();
			$("#category-links-3").show();
			
			$("#category-dd-1").hide();
			$("#category-dd-2").hide();
			$("#category-dd-3").hide();
		}		
		else
		{
			$("#category-links-1").show();
			$("#category-links-2").show();
			$("#category-links-3").show();
			
			$("#category-dd-2").show();
			
			if($('#meta_flds_srch_active').val() == "1")
					$("#category-dd-1").show();
			else
					$("#category-dd-1").hide();
			
			$("#category-dd-3").show();
		}		
   	});
    });
	
	$(document).ready(function() {
		
    	if($('#ud_sort_cat_header').val() !== 'nch')
		{
			$("#category-header-border-settings-3").show(); 
            $("#category-header-border-settings-4").show(); 			
			
			if($('#ud_sort_cat_header').val() == 'ch-bb' || $('#ud_sort_cat_header').val() == 'ch-tb')
			{
				$("#category-header-border-settings").show();
				$("#category-header-border-settings-2").show();
				$("#sort-cat-fill-color").hide();
				$("#sort-cat-font-size").show();
			}
			else if($('#ud_sort_cat_header').val() == 'ch-lo')
			{
				$("#category-header-border-settings").hide();
				$("#category-header-border-settings-2").hide();
				$("#sort-cat-fill-color").hide();
				$("#sort-cat-font-size").show();
			}
			else
			{
				$("#category-header-border-settings").hide();
				$("#category-header-border-settings-2").hide();
				$("#sort-cat-fill-color").show();
				$("#sort-cat-font-size").show();
			}  
		}
		else 
		{
			 $("#category-header-border-settings-4").hide(); 
			 $("#category-header-border-settings-3").hide(); 
			 $("#category-header-border-settings-2").hide();
			 $("#category-header-border-settings").hide();
		}
    }); 	
      
	 $(function() {
   	$('#ud_directory_type').change(function() {
		
		if($('#ud_directory_type').val() == 'all-users') 
		{   
    		$("#one-page-dir-type-a").show();   	 	
    		$("#letter-link-dir-type").hide();
			$("#show_srch_results").hide();			
    		
    		if($('#ud_letter_divider').val() !== 'nld')
			{
				$("#one-page-dir-type-b").show();  
				
				if($('#ud_letter_divider').val() == 'ld-bb' || $('#ud_letter_divider').val() == 'ld-tb')
				{
					$("#letter-divider-border-settings").show();
					$("#letter-divider-border-settings-2").show();
					$("#divider-fill-color").hide();
					$("#divider-font-size").show();
				}
				else if($('#ud_letter_divider').val() == 'ld-lo')
				{
					$("#letter-divider-border-settings").hide();
					$("#letter-divider-border-settings-2").hide();
					$("#divider-fill-color").hide();
					$("#divider-font-size").show();
				}
				else
				{
					$("#letter-divider-border-settings").hide();
					$("#letter-divider-border-settings-2").hide();
					$("#divider-fill-color").show();
					$("#divider-font-size").hide();
				}
    		     
			}
    		else 
			{		
    		     $("#one-page-dir-type-b").hide(); 
				 $("#letter-divider-border-settings").hide();
				 $("#letter-divider-border-settings-2").hide();
			}
			
			//Custom Sort Add-on
			
			//Removals
			$("#ud_sort_show_categories_as option[value='dd']").remove();
			
			if( $('#alpha_links_scroll_active').val() !== "1" || $('#ud_sort_cat_header').val() === "nch") 
					$("#ud_sort_show_categories_as option[value='links']").remove();
							
			if( $('#alpha_links_scroll_active').val() !== "1" || $('#meta_flds_srch_active').val() !== "1" || $('#ud_sort_cat_header').val() === "nch") 
					$("#ud_sort_show_categories_as option[value='dd-links']").remove();
			
			if( $('#meta_flds_srch_active').val() !== "1" ) 
			{
					$("#ud_sort_show_categories_as option[value='no-show']").remove();
			}
			
			//Appends
			var no_show_exists = false;
			
			$('#ud_sort_show_categories_as > option').each(function(){
					if (this.value == 'no-show') 
						no_show_exists = true;
				
			});
			
	
			if(!no_show_exists && $('#meta_flds_srch_active').val() === "1") 
				$('#ud_sort_show_categories_as').append('<option value="no-show">Don\'t Show Categories</option>');
			
			//Hide appropriate fields
			var length = $('#ud_sort_show_categories_as').children('option').length;
			
			if(length == 1 && $('#ud_sort_show_categories_as').val() === "links" && $('#alpha_links_scroll_active').val() !== "1" 
					&& ( $('#ud_directory_type').val() == 'all-users' || $('#ud_show_srch_results').val() == 'single-page') )
			{				
				$('#show-cats-as').hide();
				
				$("#category-dd-1").hide();
				
				if($('#ud_sort_show_categories_as').val() === "links")
					$("#category-dd-2").hide();
				
				$("#category-dd-3").hide();
				
				$("#category-links-1").hide();
				$("#category-links-2").hide();
				$("#category-links-3").hide();
			}
			else if(length < 1 || $('#ud_sort_cat_header').val() === "nch")
			{
				if(length < 1)
				{
					$('#show-cats-as').hide();
					$("#category-dd-1").hide();
					$("#category-dd-2").hide();
					$("#category-dd-3").hide();
					$("#category-links-1").hide();
					$("#category-links-2").hide();
					$("#category-links-3").hide();
				}	
				else 
				{	
					if($('#ud_sort_show_categories_as').val() === "links" || $('#meta_flds_srch_active').val() !== "1")
					{
						$("#category-dd-1").hide();

						if($('#ud_sort_show_categories_as').val() === "links" )
						{
							$("#category-dd-2").hide();
							$("#category-dd-3").hide();
						}
					}
					
					if( $('#ud_sort_show_categories_as').val() !== "links" && $('#ud_sort_show_categories_as').val() !== "dd-links")
					{
						$("#category-links-1").hide();
						$("#category-links-2").hide();
						$("#category-links-3").hide();
					}
				}
			}
			else
			{
				if($('#ud_sort_show_categories_as').val() === "links" || $('#ud_sort_show_categories_as').val() === "dd-links" ) 
				{
					$("#category-links-1").show();
					$("#category-links-2").show();
					$("#category-links-3").show();
					
					if($('#ud_sort_show_categories_as').val() !== "dd-links")
					{
						$("#category-dd-1").hide();
						$("#category-dd-2").hide();
						$("#category-dd-3").hide();
					}
					else
					{
						$("#category-dd-1").show();
						$("#category-dd-2").show();
						$("#category-dd-3").show();
					}	
				}
				else if( $('#ud_sort_show_categories_as').val() === "dd-srch") 
				{
					$("#category-links-1").hide();
					$("#category-links-2").hide();
					$("#category-links-3").hide();
					
				}
			}
		}
    	else
    	{
			//Custom Sort Add-on ******************************************/
			$('#show-cats-as').show();

			var dd_exists = false;
			var dd_links_exists = false;
			var links_exists = false;
			
			$('#ud_sort_show_categories_as > option').each(function(){
				if (this.value == 'dd') 
					dd_exists = true;
				else if(this.value == 'links')
					links_exists = true;
				else if(this.value == 'dd-links')
					dd_links_exists = true;
			});
			
			//Appends 
			if(!links_exists) 
					$('#ud_sort_show_categories_as').append('<option value="links">Links</option>');
				
			if(!dd_exists && $('#meta_flds_srch_active').val() !== "1")
				$('#ud_sort_show_categories_as').append('<option value="dd">Dropdown (Auto-Refresh)</option>');
			
			if(!dd_links_exists && $('#meta_flds_srch_active').val() === "1") 
				$('#ud_sort_show_categories_as').append('<option value="dd-links">Links + Dropdown Search Field</option>');
			
			//Removals
			$("#ud_sort_show_categories_as option[value='no-show']").remove();
			
			if($('#meta_flds_srch_active').val() !== "1")
				$("#ud_sort_show_categories_as option[value='dd-links']").remove();
			else 
				$("#ud_sort_show_categories_as option[value='dd']").remove();
			
			//Hide appropriate fields
			if( $('#ud_sort_show_categories_as').val() === "links" || $('#ud_sort_show_categories_as').val() === "dd-links" )
			{
				$("#category-links-1").show();
				$("#category-links-2").show();
				$("#category-links-3").show();
				
				if($('#ud_sort_show_categories_as').val() !== "dd-links")
				{
					$("#category-dd-1").hide();
					$("#category-dd-2").hide();
				}
				
				$("#category-dd-3").hide();
			}
			
			if( $('#ud_sort_show_categories_as').val() === "dd" || $('#ud_sort_show_categories_as').val() === "dd-srch" || $('#ud_sort_show_categories_as').val() === "dd-links" )
			{
				$("#category-dd-2").show();
				$("#category-dd-3").show();
				
				$("#category-links-1").hide();
				$("#category-links-2").hide();
				$("#category-links-3").hide();
			}
			//end Custom Sort Add-on *************************************/
			
			$("#letter-divider-border-settings").hide();
    		$("#one-page-dir-type-a").hide();  
    		$("#one-page-dir-type-b").hide();  	 	
    		$("#letter-link-dir-type").show(); 
			$("#show_srch_results").show();
    	} 
   	});
    });
	
    $(document).ready(function() {	
		
    	if ($("#user_directory_website").is(':checked'))
		{
			try {
				if ($("#ud_display_listings").val() == 'horizontally')
				{
					$("#col_width_website").show();
					if ($("#ud_show_heading_labels").is(':checked'))
					{
						$("#website_label").show();
					}
					else
					{
						$("#website_label").hide();
					}
					
					$("#website_lbl_row").hide();
					$("#website_format_row").show();
				}
				else
				{
					$("#website_lbl_row").show();
					$("#website_format_row").show();
				}
			}
			catch(err){;}
			
    		$("#Website").show();
		}
    	else
		{
			try {
				$("#col_width_website").hide();
				$("#website_label").hide();	
				$("#website_lbl_row").hide();
				$("#website_format_row").hide();
			}
			catch(err){;}
			
    		$("#Website").hide(); 
		}			
    });  
    
    $(function() {
   	$('#user_directory_website').change(function() {
		if ($("#user_directory_website").is(':checked'))
		{
			try {
				if ($("#ud_display_listings").val() == 'horizontally')
				{
					$("#col_width_website").show();
					if ($("#ud_show_heading_labels").is(':checked'))
						$("#website_label").show();
					else
						$("#website_label").hide();
					
					$("#website_lbl_row").hide();
					$("#website_format_row").show();
				}
				else
				{
					$("#website_lbl_row").show();
					$("#website_format_row").show();
				}
			}
			catch(err){;}
			
			$("#Website").show();
		}
   		else
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_website").hide();
						$("#website_label").hide();
						
					}
					else
					{
						$("#website_lbl_row").hide();
					}
			}
			catch(err){;}
				
   			$("#Website").hide(); 
    	}		
    		var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order);
    	   });
    });
      

	$(document).ready(function() {	
		
    	if ($("#ud_date_registered").is(':checked'))
		{
			try {
				if ($("#ud_display_listings").val() == 'horizontally')
				{
					$("#col_width_date").show();
					if ($("#ud_show_heading_labels").is(':checked'))
					{
						$("#date_registered_label").show();
					}
					else
					{
						$("#date_registered_label").hide();
					}
					
					$("#date_lbl_row").hide();
				}
				else
				{
					$("#date_lbl_row").show();
				}
			}
			catch(err){;}
			
    		$("#DateRegistered").show();
		}
    	else
		{
			try {
				$("#col_width_date").hide();
				$("#date_registered_label").hide();	
				$("#date_lbl_row").hide();
			}
			catch(err){;}
			
    		$("#DateRegistered").hide(); 
		}			
    });  
	   
	$(function() {
   	$('#ud_date_registered').change(function() {
		
		if ($("#ud_date_registered").is(':checked'))
		{
			try {
				if ($("#ud_display_listings").val() == 'horizontally')
				{
					$("#col_width_date").show();
					if ($("#ud_show_heading_labels").is(':checked'))
						$("#date_registered_label").show();
					else
						$("#date_registered_label").hide();
				}
				else
				{
					$("#date_lbl_row").show();
				}
			}
			catch(err){;}
			
			$("#DateRegistered").show();
		}
   		else
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_date").hide();
						$("#date_registered_label").hide();	
					}
					else
					{
						$("#date_lbl_row").hide();
					}
			}
			catch(err){;}
				
   			$("#DateRegistered").hide(); 
    	}		
    		var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order);
    	   });
    });   
	    
     $(document).ready(function() {	
    	if ($("#ud_display_listings").val() == 'horizontally' && $("#ud_show_heading_labels").is(':checked'))
		{
			try 
			{	
				$("#user_name_label").show();
				$("#email_label").show();
				$("#website_label").show();
				$("#address_label").show();
				$("#social_label").show();
				$("#date_registered_label").show();
				$("#roles_label").show();
				
				for(var i=1; i<11; i++)
				{	
					if($('#user_directory_num_meta_flds').val() >= i) 
					{						
						$("#meta_label_" + i).hide();
						$("#col_meta_label_" + i).show();						
					}
				}	
			}
			catch(err){;}
		}
    	else
		{
			try 
			{
				$("#user_name_label").hide();
				$("#email_label").hide();
				$("#website_label").hide();
				$("#address_label").hide();
				$("#social_label").hide();
				$("#date_registered_label").hide();
				$("#roles_label").hide();
				
				for(var i=1; i<11; i++)
				{	
					if($('#user_directory_num_meta_flds').val() >= i)
					{	
						if ($("#ud_display_listings").val() == 'horizontally')
							$("#meta_label_" + i).hide();
						else
							$("#meta_label_" + i).show();
						
						$("#col_meta_label_" + i).hide();
					}
				}		
			}
			catch(err){;}
		}			
    });  
    
     $(function() {
   	 $('#ud_show_heading_labels').change(function() {
       	if ($("#ud_display_listings").val() == 'horizontally' && $("#ud_show_heading_labels").is(':checked'))
		{
			$("#user_name_label").show();
			$("#email_label").show();
			$("#website_label").show();
			$("#address_label").show();
			$("#social_label").show();
			$("#date_registered_label").show();
			$("#roles_label").show();
			
			for(var i=1; i<11; i++)
			{	
				if($('#user_directory_num_meta_flds').val() >= i) 
				{					
					$("#meta_label_" + i).hide();
					$("#col_meta_label_" + i).show();
				}					
			}
		}
    	else
		{
			$("#user_name_label").hide();
			$("#email_label").hide();
			$("#website_label").hide();
			$("#address_label").hide();
			$("#social_label").hide();
			$("#date_registered_label").hide();
			$("#roles_label").hide();
			
			for(var i=1; i<11; i++)
			{	
				if($('#user_directory_num_meta_flds').val() >= i)  
				{					
					if ($("#ud_display_listings").val() == 'horizontally')
						$("#meta_label_" + i).hide();
					else
						$("#meta_label_" + i).show();
					
					$("#col_meta_label_" + i).hide();
				}					
			}
		}

		});
    });

   
    $(document).ready(function() {	
    	if ($("#user_directory_email").is(':checked'))
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_email").show();
						if ($("#ud_show_heading_labels").is(':checked'))
							$("#email_label").show();
						else
							$("#email_label").hide();
							
						$("#email_lbl_row").hide();
						$("#email_format_row").show();
					}
					else
					{
						$("#email_lbl_row").show();
						$("#email_format_row").show();
					}
			}
			catch(err){;}
			
    		$("#Email").show();
		}
    	else
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_email").hide();
						$("#email_label").hide();
						$("#email_lbl_row").hide();
						$("#email_format_row").hide();
					}
					else
					{
						$("#email_lbl_row").hide();
						$("#email_format_row").hide();
					}
			}
			catch(err){;}
			
    		$("#Email").hide(); 
		}

		if ($("#ud_show_user_roles").is(':checked'))
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_user_roles").show();
						if ($("#ud_show_heading_labels").is(':checked'))
							$("#roles_label").show();
						else
							$("#roles_label").hide();
							
						$("#roles_lbl_row").hide();
						$("#roles_format_row").show();
					}
					else
					{
						$("#roles_lbl_row").show();
						$("#roles_format_row").show();
					}
			}
			catch(err){;}
			
    		$("#UserRoles").show();
		}
    	else
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_user_roles").hide();
						$("#roles_label").hide();
						$("#roles_lbl_row").hide();
						$("#roles_format_row").hide();
					}
					else
					{
						$("#roles_lbl_row").hide();
						$("#roles_format_row").hide();
					}
			}
			catch(err){;}
			
    		$("#UserRoles").hide(); 
		}

		
    });  
    
	$(function() {
   	$('#ud_hide_before_srch').change(function() {
				
       		if ( ! $("#ud_hide_before_srch").is(':checked'))
				$("#show-cats-dd-hide-dir-before-srch").hide();	
			
			else if(! $('#ud_sort_show_categories_as').is(":hidden") && ( $('#ud_sort_show_categories_as').val() === "dd" 
					|| $('#ud_sort_show_categories_as').val() === "dd-srch" || $('#ud_sort_show_categories_as').val() === "dd-links"))
						$("#show-cats-dd-hide-dir-before-srch").show();	
    			
    	   });
    });
	
    $(function() {
   	$('#user_directory_email').change(function() {
       		if ($("#user_directory_email").is(':checked'))
			{
				try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_email").show();
						if ($("#ud_show_heading_labels").is(':checked'))
							$("#email_label").show();
						else
							$("#email_label").hide();
						
						$("#email_format_row").show();
					}
					else
					{
						$("#email_lbl_row").show();
						$("#email_format_row").show();
					}
			}
			catch(err){;}
			
    			$("#Email").show();
			}
   		else
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_email").hide();
						$("#email_label").hide();
						$("#email_format_row").hide();
					}
					else
					{
						$("#email_lbl_row").hide();
						$("#email_format_row").hide();
					}
			}
			catch(err){;}
			
   			$("#Email").hide(); 
		}
    			
    		var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order);
    	   });
    });
	
	$(function() {
   	$('#ud_show_user_roles').change(function() {
		
		if ($("#ud_show_user_roles").is(':checked'))
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_user_roles").show();
						if ($("#ud_show_heading_labels").is(':checked'))
							$("#roles_label").show();
						else
							$("#roles_label").hide();
							
						$("#roles_lbl_row").hide();
						$("#roles_format_row").show();
					}
					else
					{
						$("#roles_lbl_row").show();
						$("#roles_format_row").show();
					}
			}
			catch(err){;}
			
    		$("#UserRoles").show();
		}
    	else
		{
			try {
					if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#col_width_user_roles").hide();
						$("#roles_label").hide();
						$("#roles_lbl_row").hide();
						$("#roles_format_row").hide();
					}
					else
					{
						$("#roles_lbl_row").hide();
						$("#roles_format_row").hide();
					}
			}
			catch(err){;}
			
    		$("#UserRoles").hide(); 
		}

    	var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order);
    	   });
    });
	
    $(document).ready(function() {	
    	if ($("#user_directory_address").val() == "1")
    	{
    		$("#street1").hide();
    		$("#street2").hide();
    		$("#city").hide();
    		$("#state").hide();
    		$("#zip").hide();
			$("#country").hide();
			
			$("#user_directory_addr_1").val("");
			$("#user_directory_addr_2").val("");
			$("#user_directory_city").val("");
			$("#user_directory_state").val("");
			$("#user_directory_zip").val("");
			$("#user_directory_country").val("");
    		
    		$("#Address").hide(); 
			
			try
			{
				$("#ud_line_break_address").val("");
				$("#col_width_address").hide();
			}
			catch(err) {;}
			
			$("#address-down-arrow").show();
			$("#address-up-arrow").hide();
			
    	}
    	else
    	{
    		$("#street1").show();
    		$("#street2").show();
    		$("#city").show();
    		$("#state").show();
    		$("#zip").show();
			$("#country").show();
						
			$("#Address").show(); 
			
			try
			{
				$("#col_width_address").show();
				
				if ($("#ud_show_heading_labels").is(':checked'))
					$("#addr_label").show();
				else
					$("#addr_label").hide();
			}
			catch(err) {;}
			
			$("#address-up-arrow").show();
			$("#address-down-arrow").hide();
			
    	}
		
		
		if ($("#ud_exclude_user_filter_mp").val() == "")
		{
			$("#mp_active_membership").hide();
			$("#mp_one_time_txn").hide();
			$("#mp_hide_status").hide();
			
			$("#mp-down-arrow").hide();
			$("#mp-up-arrow").show();
		}
		else
		{
			$("#mp_active_membership").show();
			$("#mp_one_time_txn").show();
			$("#mp_hide_status").show();
			
			$("#mp-down-arrow").show();
			$("#mp-up-arrow").hide();
		}
		
		if ($("#ud_exclude_user_filter_bp").val() == "")
		{
			$("#bp_last_activity_1").hide();
			$("#bp_last_activity_2").hide();
			$("#bp_inactive").hide();
			$("#bp_no_last_activity").hide();
			$("#bp_spammer").hide();
			
			$("#bp-down-arrow").hide();
			$("#bp-up-arrow").show();
		}
		else
		{
			$("#bp_last_activity_1").show();
			$("#bp_last_activity_2").show();
			$("#bp_inactive").show();
			$("#bp_no_last_activity").show();
			$("#bp_spammer").show();
			
			$("#bp-down-arrow").show();
			$("#bp-up-arrow").hide();
		}
		
		if ($("#ud_exclude_user_filter_wc").val() == "")
		{
			$("#wc_active_plan").hide();
			
			$("#wc-down-arrow").hide();
			$("#wc-up-arrow").show();
		}
		else
		{
			$("#wc_active_plan").show();
			
			$("#wc-down-arrow").show();
			$("#wc-up-arrow").hide();
		}
    });
	
	 $(document).ready(function() {	
    	if ($("#ud_social").val() == "1")
    	{
    		$("#facebook").hide();
    		$("#twitter").hide();
    		$("#linkedin").hide();
    		$("#google").hide();
    		$("#instagram").hide();
			$("#pintrest").hide();
			$("#youtube").hide();
			$("#tiktok").hide();
			$("#podcast").hide();
			
			$("#icon_size").hide();
			$("#icon_color").hide();
			$("#icon_style").hide();
			
			$("#ud_facebook").val("");
    		$("#ud_twitter").val("");
    		$("#ud_linkedin").val("");
    		$("#ud_google").val("");
    		$("#ud_instagram").val("");
			$("#ud_pinterest").val("");
			$("#ud_youtube").val("");
			$("#ud_tiktok").val("");
			$("#ud_podcast").val("");
			
			$("#Social").hide(); 
			
			try
			{
				$("#col_width_social").hide();
				
				if ($("#ud_show_heading_labels").is(':checked'))
					$("#social_label").show();
				else
					$("#social_label").hide();
			}
			catch(err) {;}
			
			$("#social-down-arrow").show();
			$("#social-up-arrow").hide();
			
    	}
    	else
    	{
			$("#facebook").show();
    		$("#twitter").show();
    		$("#linkedin").show();
    		$("#google").show();
    		$("#instagram").show();
			$("#pintrest").show();
			$("#youtube").show();
			$("#tiktok").show();
			$("#podcast").show();
			
			
			$("#icon_size").show();
			$("#icon_color").show();
			$("#icon_style").show();
			
			$("#Social").show();
 			
			try
			{
				$("#col_width_social").show();
				
				if ($("#ud_show_heading_labels").is(':checked'))
					$("#social_label").show();
				else 
					$("#social_label").hide();
				
			}
			catch(err) {;}
			
			$("#social-up-arrow").show();
			$("#social-down-arrow").hide();
			
    	}
    });
    
	$( "#address-down-arrow" ).click(function() {
		
		    $("#street1").show();
    		$("#street2").show();
    		$("#city").show();
    		$("#state").show();
    		$("#zip").show();
			$("#country").show();
			
		    $("#address-up-arrow").show();
			$("#address-down-arrow").hide();
			
			$("#Address").show(); 
			
			try
			{
				$("#col_width_address").show();
				
				if ($("#ud_show_heading_labels").is(':checked'))
					$("#addr_label").show();
				else
					$("#addr_label").hide();
			}
			catch(err) {;}
			
			$("#user_directory_address").val("");
			
			var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order);
	});
	
	$( "#social-down-arrow" ).click(function() {
		
		    $("#facebook").show();
    		$("#twitter").show();
    		$("#linkedin").show();
    		$("#google").show();
    		$("#instagram").show();
			$("#pintrest").show();
			$("#youtube").show();
			$("#tiktok").show();
			$("#podcast").show();
			
			
			$("#icon_size").show();
			$("#icon_color").show();
			$("#icon_style").show();
			
		    $("#social-up-arrow").show();
			$("#social-down-arrow").hide();
			
			$("#Social").show(); 
			
			try
			{
				$("#col_width_social").show();
				if ($("#ud_show_heading_labels").is(':checked'))
					$("#social_label").show();
				else
					$("#social_label").hide();
			}
			catch(err) {;}
			
			$("#ud_social").val("");
			
			var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order);
	});
		
	$( "#address-up-arrow" ).click(function() {
		
		    $("#street1").hide();
    		$("#street2").hide();
    		$("#city").hide();
    		$("#state").hide();
    		$("#zip").hide();
			$("#country").hide();
			
			$("#user_directory_addr_1").val("");
			$("#user_directory_addr_2").val("");
			$("#user_directory_city").val("");
			$("#user_directory_state").val("");
			$("#user_directory_zip").val("");
			$("#user_directory_country").val("");
			
			$("#Address").hide();

			try
			{
				$("#ud_line_break_address").val("");
				$("#col_width_address").hide();
				$("#addr_label").hide();
			}
			catch(err) {;}
    		
			$("#address-down-arrow").show();
			$("#address-up-arrow").hide();
			
			$("#user_directory_address").val("1");
			
			var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order);
	});
	
	$( "#social-up-arrow" ).click(function() {
		
		    $("#facebook").hide();
    		$("#twitter").hide();
    		$("#linkedin").hide();
    		$("#google").hide();
    		$("#instagram").hide();
			$("#pintrest").hide();
			$("#youtube").hide();
			$("#tiktok").hide();
			$("#podcast").hide();
			
			$("#icon_size").hide();
			$("#icon_color").hide();
			$("#icon_style").hide();
			
			$("#ud_facebook").val("");
    		$("#ud_twitter").val("");
    		$("#ud_linkedin").val("");
    		$("#ud_google").val("");
    		$("#ud_instagram").val("");
			$("#ud_pinterest").val("");
			$("#ud_youtube").val("");
			$("#ud_tiktok").val("");
			$("#ud_podcast").val("");
			
			$("#Social").hide();

			try
			{
				$("#col_width_social").hide();
				$("#social_label").hide();
			}
			catch(err) {;}
    		
			$("#social-down-arrow").show();
			$("#social-up-arrow").hide();
			
			$("#ud_social").val("1");
			
			var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order);
	});
    
    $( "#wc-down-arrow" ).click(function() {
		
		 $("#wc_active_plan").hide();
		 
		 $("#ud_exclude_user_filter_wc").val("");
		 
		 $("#wc-down-arrow").hide();
		 $("#wc-up-arrow").show();
	});
	
	$( "#bp-down-arrow" ).click(function() {
		
	    $("#bp_last_activity_1").hide();
		$("#bp_last_activity_2").hide();
		$("#bp_inactive").hide();
		$("#bp_no_last_activity").hide();
		$("#bp_spammer").hide();
		
		$("#ud_exclude_user_filter_bp").val("");
		
		 $("#bp-down-arrow").hide();
		 $("#bp-up-arrow").show();
	});
	
	$( "#mp-down-arrow" ).click(function() {
		
	    $("#mp_active_membership").hide();
		$("#mp_one_time_txn").hide();
		$("#mp_hide_status").hide();
		
		$("#ud_exclude_user_filter_mp").val("");
		
		 $("#mp-down-arrow").hide();
		 $("#mp-up-arrow").show();
	});
	
	$( "#wc-up-arrow" ).click(function() {
		
		 $("#wc_active_plan").show();
		 
		 $("#ud_exclude_user_filter_wc").val("1");
		 
		 $("#wc-down-arrow").show();
		 $("#wc-up-arrow").hide();
	});
	
	$( "#bp-up-arrow" ).click(function() {
		
	    $("#bp_last_activity_1").show();
		$("#bp_last_activity_2").show();
		$("#bp_inactive").show();
		$("#bp_no_last_activity").show();
		$("#bp_spammer").show();
		
		$("#ud_exclude_user_filter_bp").val("1");
		
		$("#bp-down-arrow").show();
		$("#bp-up-arrow").hide();
	});
	
	$( "#mp-up-arrow" ).click(function() {
		
	    $("#mp_active_membership").show();
		$("#mp_one_time_txn").show();
		$("#mp_hide_status").show();
		
		$("#ud_exclude_user_filter_mp").val("1");
		
		$("#mp-down-arrow").show();
		$("#mp-up-arrow").hide();
		 
	});
	
    $(document).ready(function() {
		for(var i=1; i<11; i++)
		{	
			if($('#user_directory_num_meta_flds').val() >= i)     	 	
			{
				$("#meta_fld_" + i).show(); 
				$("#MetaKey" + i).show();   
			    
				try {
					$("#col_width_" + i).show();  
					if ($("#ud_display_listings").val() == 'horizontally' && $("#ud_show_heading_labels").is(':checked'))
					{
						$("#meta_label_" + i).hide();
						$("#col_meta_label_" + i).show();
					}
					else if ($("#ud_display_listings").val() == 'horizontally')
					{
						$("#meta_label_" + i).hide();
						$("#col_meta_label_" + i).hide();						
					}
					
				}
				catch(err){;}
			}
			else
			{
				$("#meta_fld_" + i).hide(); 
				$("#MetaKey" + i).hide(); 
			
				try {
					$("#col_width_" + i).hide(); 
					$("#meta_label_" + i).hide();
					$("#col_meta_label_" + i).hide();
				}
				catch(err){;}
			}      
		}
		
		for(var iSrch=1; iSrch<16; iSrch++)
		{
           		
			if($('#user_directory_num_meta_srch_flds').val() >= iSrch)     	 	
			{
				$("#meta_srch_fld_" + iSrch).show(); 
			}
			else
			{
				$("#meta_srch_fld_" + iSrch).hide(); 
			}      
		}
    }); 
    
    
    $(function() {
   	$('#user_directory_num_meta_flds').change(function() {
		
       		for(var i=1; i<11; i++)
				{	
					if($('#user_directory_num_meta_flds').val() >= i)     	 	
					{
						$("#meta_fld_" + i).show(); 
						$("#MetaKey" + i).show(); 
						
						try {
							
							$("#col_width_" + i).show();  
							if ($("#ud_display_listings").val() == 'horizontally' && $("#ud_show_heading_labels").is(':checked'))
							{
								$("#meta_label_" + i).hide(); 
								$("#col_meta_label_" + i).show();
							}
							else if ($("#ud_display_listings").val() == 'horizontally')
							{
								$("#meta_label_" + i).hide(); 
								$("#col_meta_label_" + i).hide();
							}
							else 
							{
								$("#meta_label_" + i).show(); 	
							}
						}
						catch(err){;}

					}

					else
					{

						$("#meta_fld_" + i).hide(); 
						$("#MetaKey" + i).hide();
												
						try {
							
							$("#col_width_" + i).hide(); 
							$("#meta_label_" + i).hide();
							$("#col_meta_label_" + i).hide();
						}
						catch(err){;}

						$("#user_directory_meta_field_" + i).val("");
						$("#user_directory_meta_label_" + i).val("");
						$("#ud_line_break_" + i).val("");

					}      
				}	
    		
    		var Order = $("#sortable").sortable('toArray').toString();
                $('#user_directory_sort_order').val(Order); 
   	});
    });
    
	$(function() {
   	$('#user_directory_num_meta_srch_flds').change(function() {
		
       		for(var i=1; i<16; i++)
				{	
					if($('#user_directory_num_meta_srch_flds').val() >= i)     	 	
					{

						$("#meta_srch_fld_" + i).show();  		 

					}

					else
					{

						$("#meta_srch_fld_" + i).hide(); 

						$("#user_directory_meta_srch_field_" + i).val("");

						$("#user_directory_meta_srch_label_" + i).val("");

					}      
				}	
   	});
    });
   
    $(function() {
    	$( "#sortable" ).sortable();
    	$( "#sortable" ).disableSelection();
    }); 
    
    $('#Email').hover(function() {
        $(this).css('cursor','pointer');
    });
    $('#Website').hover(function() {
        $(this).css('cursor','pointer');
    });
    $('#Address').hover(function() {
        $(this).css('cursor','pointer');
    });
	$('#DateRegistered').hover(function() {
        $(this).css('cursor','pointer');
    });
	$('#UserRoles').hover(function() {
        $(this).css('cursor','pointer');
    });
    $('#MetaKey1').hover(function() {
        $(this).css('cursor','pointer');
    });
    $('#MetaKey2').hover(function() {
        $(this).css('cursor','pointer');
    });
    $('#MetaKey3').hover(function() {
        $(this).css('cursor','pointer');
    });
    $('#MetaKey4').hover(function() {
        $(this).css('cursor','pointer');
    });
    $('#MetaKey5').hover(function() {
        $(this).css('cursor','pointer');
    });
    
    $(function() {
        $( "#sortable" ).sortable({
            placeholder: "ui-state-highlight",
            cursor: 'crosshair',
            update: function(event, ui) {
               var Order = $("#sortable").sortable('toArray').toString();
               $('#user_directory_sort_order').val(Order);
              
             }
         });
         
     });

	/* For Multipl Dirs Add-on */ 
	$( "#delete" ).click(function() {
		
    if ( confirm( "Delete the selected directory instance(s)?" ) )
       document.LoadInstance.submit();
    else
        return false;
	});
	
	$( "#add" ).click(function() {
		
    if ( $('#dud_new_instance_name').val() ) 
       document.AddInstance.submit();
    else
	{
        alert("Please enter a new directory name.");
		return false;
	}
	});
	
	$(document).ready(function() {	
    	try 
		{
			if ($("#ud_show_pagination_top_bottom").val() == 'top' || $("#ud_show_pagination_top_bottom").val() == 'both')
				$("#pagination_above_below").show();
			else
				$("#pagination_above_below").hide();
		}
		catch(err){;}
    });
    
    $(function() {
   	$('#ud_show_pagination_top_bottom').change(function() {
			if ($("#ud_show_pagination_top_bottom").val() == 'top' || $("#ud_show_pagination_top_bottom").val() == 'both')
				$("#pagination_above_below").show();
			else
				$("#pagination_above_below").hide();
   	});
    });
	
	$(document).ready(function() {	
    	if ($("#ud_show_srch").is(':checked'))
		{
    		$("#ud_srch_style").show();
			
			var dd_srch_exists = false;
			
			$('#ud_sort_show_categories_as > option').each(function(){ 
					if (this.value == 'dd-srch') 
						dd_srch_exists = true;	
			});
			
			if(!dd_srch_exists) 
				$('#ud_sort_show_categories_as').append('<option value="dd-srch">Dropdown (Search Field)</option>');
		}
    	else
		{
			$("#ud_srch_style").hide();
			$("#ud_sort_show_categories_as option[value='dd-srch']").remove();
		}
    });
	
	$(document).ready(function() {
				
		//If General Search checked show placeholder text fld
		if ($("#ud_general_srch").is(':checked'))
		{
			$("#gen_srch_placeholder_txt").show();
			$("#ud_show_srch_results").val("single-page");
			
			for(var iSrch=1; iSrch<16; iSrch++)
			{
				$("#dd_option_values_" + iSrch).hide(); 
				$("#dd_option_labels_" + iSrch).hide(); 
				$("#search_type_" + iSrch).hide(); 
				$("#meta_srch_lbl_" + iSrch).hide();
					
				$("#user_directory_meta_srch_dd_values_" + iSrch).val("");
				$("#user_directory_meta_srch_dd_labels_" + iSrch).val("");	
			}
		}
		else
		{
			$("#gen_srch_placeholder_txt").hide();	
			
			for(var iSrch=1; iSrch<16; iSrch++)
			{
				$("#dd_option_values_" + iSrch).show(); 
				$("#dd_option_labels_" + iSrch).show(); 
				$("#search_type_" + iSrch).show(); 
				$("#meta_srch_lbl_" + iSrch).show();	
			}
		}
    }); 

	$(function() {
	$('#ud_general_srch').change(function() {
	
		if ($("#ud_general_srch").is(':checked'))
		{
			$("#gen_srch_placeholder_txt").show();
			$("#ud_show_srch_results").val("single-page");
			
			for(var iSrch=1; iSrch<16; iSrch++)
			{
				$("#dd_option_values_" + iSrch).hide(); 
				$("#dd_option_labels_" + iSrch).hide(); 
				$("#search_type_" + iSrch).hide();
				$("#meta_srch_lbl_" + iSrch).hide();

				$("#user_directory_meta_srch_dd_values_" + iSrch).val("");
				$("#user_directory_meta_srch_dd_labels_" + iSrch).val("");	
			}
		}
		else
		{
			for(var iSrch=1; iSrch<16; iSrch++)
			{
				$("#dd_option_values_" + iSrch).show(); 
				$("#dd_option_labels_" + iSrch).show(); 
				$("#search_type_" + iSrch).show(); 
				$("#meta_srch_lbl_" + iSrch).show();	
			}
				
			$("#gen_srch_placeholder_txt").hide();
		}
	});
	});
			
	$(function() {
	$('#ud_alpha_links_scroll').change(function() {
	
		if ($("#ud_alpha_links_scroll").val() == "on")
			$("#ud_users_per_page").val("");
		
	});
	});
	
	$(function() {
	$('#ud_users_per_page').change(function() {
	
		if ($("#ud_users_per_page").val() !== "")
		{
			$( "#ud_alpha_links_scroll" ).val("off");
		}
		
	});
	});
	
	$(function() {
   	$('#ud_meta_srch_legacy_style').change(function() {
			
				if($('#ud_meta_srch_legacy_style').val() === 'new')
				{
					$("#srch_container_width").show();
					$("#srch_container_width_2").show();
					$("#srch_container_width_3").show();
				}
				else 
				{
					$("#srch_container_width").hide();
					$("#srch_container_width_2").hide();
					$("#srch_container_width_3").hide();
				}
			
   	});
    });
	
	$(document).ready(function() {
				
		if($('#ud_meta_srch_legacy_style').val() === 'new')
		{
			$("#srch_container_width").show();
			$("#srch_container_width_2").show();
			$("#srch_container_width_3").show();
		}
		else 
		{
			$("#srch_container_width").hide();
			$("#srch_container_width_2").hide();
			$("#srch_container_width_3").hide();
		}
    }); 
	
	
})( jQuery );