jQuery(document).ready(function() {
	jQuery('input[type=radio][name=mode]').change(function() {
		if (this.value === "category") {
			jQuery("#review_select").hide("slow");
			jQuery("#category_select").show("slow");

			jQuery("#star_select").show("slow");

		} else if (this.value === "manual_select") {
			jQuery("#category_select").hide("slow");
			jQuery("#review_select").show("slow");

			jQuery("#star_select").hide("slow");
			
		} else {
			jQuery("#review_select").hide("slow");
			jQuery("#category_select").hide("slow");

			jQuery("#star_select").show("slow");
		}
	});

	jQuery('#widget-name').on("input", function(){
		if (this.value)
		{
			jQuery('input[type=hidden][name=widget-name]').val(this.value);
		}
		else
		{
			jQuery('input[type=hidden][name=widget-name]').val(jQuery(this).data('default-name'));
		}
	});

	//jQuery('input[type=hidden][name=widget-name]').val(jQuery('#widget-name').val());

	let active_tab = document.getElementById('active-tab');
	if (active_tab)
	{
		active_tab.click();
	}
});

function openTab(evt, tabName) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(tabName).style.display = "block";
	evt.currentTarget.className += " active";
}

