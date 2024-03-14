function cf7rl_closetabs(ids) {
	var x = ids;
	y = x.split(",");

	for(var i = 0; i < y.length; i++) {
	//console.log(y[i]);
	document.getElementById(y[i]).style.display = 'none';
	document.getElementById("id"+y[i]).classList.remove('nav-tab-active');
	}
}

function cf7rl_newtab(id) {
	var x = id;
	//console.log(x);
	document.getElementById(x).style.display = 'block';
	document.getElementById("id"+x).classList.add('nav-tab-active');
	document.getElementById('hidden_tab_value').value=x;
}




// tabs page - redirect type dropdown - on change
jQuery(document).ready(function() {
	jQuery('#cf7rl_redirect_type').on('change', function() {
		
		if (this.value == 'url') {
			jQuery('.cf7rl_redirect_option').hide();
			jQuery('.cf7rl_url').show();
		}
		
		if (this.value == 'thank') {
			jQuery('.cf7rl_redirect_option').hide();
			jQuery('.cf7rl_thank').show();
		}
		
		if (this.value == 'logic') {
			jQuery('.cf7rl_redirect_option').hide();
			jQuery('.cf7rl_logic').show();
		}
		
	});
	
	// tabs page - redirect type dropdown - onload
	var cf7rl_redirect_type = jQuery('#cf7rl_redirect_type').val();
	if (cf7rl_redirect_type == 'url') {
		jQuery('.cf7rl_redirect_option').hide();
		jQuery('.cf7rl_url').show();
	}
	if (cf7rl_redirect_type == 'thank') {
		jQuery('.cf7rl_redirect_option').hide();
		jQuery('.cf7rl_thank').show();
	}
	if (cf7rl_redirect_type == 'logic') {
		jQuery('.cf7rl_redirect_option').hide();
		jQuery('.cf7rl_logic').show();
	}
	
});