jQuery(document).ready(function($) {
	$('.categorychecklist li, .category-checklist li').each(function() {
		// add empty space to each of li
		$(this).prepend("<a href=\"#\" class=\"icc-cat-toggle\"></a>")
		
		// check if have children category
		if ($(this).children("ul").length > 0) {
			// hide children ul
			$(this).find("ul").hide();
			// add unfold class
			$(this).children(".icc-cat-toggle").addClass('icc-expand');
		}
	});
	icc_category_scan();
	
	// quick edit
	$(".ptitle").focus(function() {
		icc_category_scan();
	});
	
	$(".icc-cat-toggle").click(function() {
		if ($(this).hasClass("icc-expand")) {
			$(this).parent("li").children("ul").show();
			$(this).removeClass("icc-expand");
			$(this).addClass("icc-contract");
		} else if ($(this).hasClass("icc-contract")) {
			$(this).parent("li").children("ul").hide();
			$(this).removeClass("icc-contract");
			$(this).addClass("icc-expand");
		}
		return false;
	});
	
	function icc_category_scan() {
		$('.categorychecklist li, .category-checklist li').each(function() {
			// check if children checked
			if ($("> label > input[type=\"checkbox\"]", this).is(":checked")) {
				$(this).parents("ul").show();
				$(this).parents("li").each(function() {
					$(this).children(".icc-cat-toggle").removeClass('icc-expand');
					$(this).children(".icc-cat-toggle").addClass('icc-contract');
				});
			}
		});
	}
});