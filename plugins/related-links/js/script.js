jQuery(document).ready(function($) {
		
	// add a link to the list
	jQuery("#related-links-list a").live("click", function(event) {
		var id = jQuery(this).attr("href").substr(1);
		var title = jQuery(this).text();
		var type = jQuery(this).parent().find("span").text();

		jQuery(this).addClass("selected");
				
		if (jQuery("#related-links-selected-" + id).length == 0) {
			jQuery("#related-links-selected ul").append('<li class="related-links-selected menu-item-handle" id="related-links-selected-' + id + '"><input type="hidden" name="related_links[posts][]" value="' + id + '" /><span class="selected-title">' + title + '</span><span class="selected-right"><span class="selected-type">' + type + '</span><a href="#" class="selected-delete">Delete</a></span></li>');
		}
		
		return false;
	});
	
	// add a custom link to the list
	jQuery("#related-links-custom-submit").click(function(event) {
		var id = "custom_" + (new Date() - 0);
		var title = jQuery("#related-links-custom-label").val();
		var url = jQuery("#related-links-custom-url").val();
		var type = "Custom";

		if(!url) {
			url = "";
			
			var bColor = jQuery("#related-links-custom-url").css("borderTopColor");
			var bgColor = jQuery("#related-links-custom-url").css("backgroundColor");
			var tColor = jQuery("#related-links-custom-url").css("color");

			jQuery("#related-links-custom-url").animate({
				borderTopColor: "#A82F00", 
				borderLeftColor: "#A82F00", 
				borderRightColor: "#A82F00", 
				borderBottomColor: "#A82F00", 
				backgroundColor: "#F8ECE8",
				color: "#A82F00"
			}, 1).delay(300).animate({
				borderTopColor: bColor, 
				borderLeftColor: bColor, 
				borderRightColor: bColor, 
				borderBottomColor: bColor, 
				backgroundColor: bgColor,
				color: tColor
			}, 500);
			
			return false;
		}
		
		if(!title) {
			title = url;
		}
		
		jQuery("#related-links-selected ul").append('<li class="related-links-selected menu-item-handle" id="related-links-selected-' + id + '"><input type="hidden" name="related_links[posts][]" value="' + id + '" /><input type="hidden" name="related_links[custom][' + id + '][]" value="' + title + '" /><input type="hidden" name="related_links[custom][' + id + '][]" value="' + url + '"/><span class="selected-title">' + title + '</span><span class="selected-right"><span class="selected-type">' + type + '</span><a href="#" class="selected-delete">Delete</a></span></li>');
		jQuery("#related-links-custom-label").val("").focus();
		jQuery("#related-links-custom-url").val("").blur();
		
		return false;
	});
	
	// remove a link from the list
	jQuery("#related-links-selected .selected-delete").live("click", function(event) {
		jQuery(this).parent().parent().css({
			"border": "1px solid #A82F00", 
			"background-color": "#F8ECE8", 
			"background-image": "none"
		});

		jQuery(this).parent().parent().fadeOut(400, function() {
			var id = jQuery("input", this).attr("value");
			
			jQuery("#in-related-links-" + id).removeClass("selected");
			jQuery(this).remove();
		});
		
		return false;
	});
	
	// open or close custom link box
	jQuery("#related-links-custom-addurl").click(function() {
		if($("#related-links-custom-content:visible").length > 0) {
			$("#related-links-custom-content").hide();
		} else {
			$("#related-links-custom-content").show();
		}
		
		return false;
	});
	
	// enable sorting
	jQuery("#related-links-selected ul").sortable();
			
	// list content display
	var numPosts = 20;
	var offsetCounter = 0;
	var complete = false;
	var loader = jQuery("#related-links-list .status");
	var xhr = null;
	
	// initialize
	resetListContent();
	loadListContent();
	
	// load the posts links list
	function loadListContent() {
		if(complete == false) {
			var data = {
				action: "load_links_list",
				post_id: jQuery('#post_ID').val(),
				nonce: jQuery("#related_links_nonce").val(),
				search: jQuery("#related-links-searchfield").val(),
				posts_per_page: numPosts,
				posts_offset: offsetCounter * numPosts
			};
			
			jQuery("#related-links-list .status").show();
			xhr = jQuery.post(ajaxurl, data, function(data, textStatus, jqXHR) {
				if(data) {
					jQuery("#related-links-list .status").before(data);
				} else {
					complete = true;
					jQuery("#related-links-list .status").addClass('end');
				}
				jQuery("#related-links-list .status").hide();
			});
			
			offsetCounter++;
		}
	}
	
	// reset the list content
	function resetListContent() {
		offsetCounter = 0;
		complete = false;
		loader.removeClass('end');
		
		if(xhr) {
			xhr.abort();
			xhr = null;
		}
		
		jQuery("#related-links-list").empty().append(loader);
	}
	
	// load the posts on scroll
	jQuery("#related-links-content").scroll(function(event) {
		if($(this).scrollTop() + $(this).height() == $(this)[0].scrollHeight) {
			loadListContent();
		}
	});
	
	// search for a title
	jQuery("#related-links-searchfield").keyup(function() { 
		resetListContent();
		loadListContent();
	});
	
	/*
	// get the search value from the field
	function getSearchVal() {
		var field = jQuery("#related-links-searchfield");
		if(field.val() != field.attr('title')) {
			return field.val();
		} else {
			return '';
		}
	}
	*/
	
});