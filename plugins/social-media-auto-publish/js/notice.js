	jQuery(document).ready(function() {
		jQuery('#system_notice_area').animate({
			opacity : 'show',
			height : 'show'
		}, 500);

		jQuery('#system_notice_area_dismiss').click(function() {
			jQuery('#system_notice_area').animate({
				opacity : 'hide',
				height : 'hide'
			}, 500);

		});

	});
	function XyzSmapToggleRadio(value,buttonId)
	{
		if (value == '1') {
	    	jQuery("#"+buttonId+"_no").removeClass( "xyz_smap_toggle_on" ).addClass( "xyz_smap_toggle_off" );
	    	jQuery("#"+buttonId+"_yes").removeClass( "xyz_smap_toggle_off" ).addClass( "xyz_smap_toggle_on" );
	        }
	    else if (value == '0') {
	    	jQuery("#"+buttonId+"_yes").removeClass( "xyz_smap_toggle_on" ).addClass( "xyz_smap_toggle_off" );
	    	jQuery("#"+buttonId+"_no").removeClass( "xyz_smap_toggle_off" ).addClass( "xyz_smap_toggle_on" );
	    }
	}
	function xyz_smap_open_tab(evt, xyz_smap_form_div_id) {
	    var i, xyz_smap_tabcontent, xyz_smap_tablinks;
	    tabcontent = document.getElementsByClassName("xyz_smap_tabcontent");
	    for (i = 0; i < tabcontent.length; i++) {
	        tabcontent[i].style.display = "none";
	    }
	    tablinks = document.getElementsByClassName("xyz_smap_tablinks");
	    for (i = 0; i < tablinks.length; i++) {
	        tablinks[i].className = tablinks[i].className.replace(" active", "");
	    }
	    document.getElementById(xyz_smap_form_div_id).style.display = "block";
	    evt.currentTarget.className += " active";
	}