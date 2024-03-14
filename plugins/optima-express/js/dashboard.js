jQuery(document).on("ready", function() {
	var $formImage = null;
	var $formField = null;
	var send_editor = function() {
		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html) {
			if($formImage !== null && $formField !== null) {
				var fileUrl = jQuery("img", html).attr("src");
				$formField.attr("value", fileUrl);
				$formImage.attr("src", fileUrl);
				$formImage.show();
				tb_remove();
			}
			window.send_to_editor = window.original_send_to_editor;
			$formImage = null;
			$formField = null;
		};
	}
	jQuery("#ihf_upload_agent_photo_button").on("click", function() {
		$formImage = jQuery("#ihf_upload_agent_photo_image");
		$formField = jQuery("#ihf_upload_agent_photo" );
		tb_show("", "media-upload.php?type=image&TB_iframe=true");
		send_editor();
		return false;
	});
	jQuery("#ihf_upload_email_logo_button").on("click", function() {
		$formImage = jQuery("#ihf_upload_email_logo_image");
		$formField = jQuery("#ihf_upload_email_logo");
		tb_show("", "media-upload.php?type=image&TB_iframe=true");
		send_editor();
		return false;
	});
});

var ihfSelectAllCheckboxesReset = function(selectAllCheckbox, checkBoxesContainer) {
	// Check if all checkboxes are checked. If yes, then make sure "Select All" is checked.
	var allItemsChecked = true;
	jQuery("#" + checkBoxesContainer).find("input").each(function() {
		if(!jQuery(this).attr("checked")) {
			allItemsChecked = false;
			// break out of the loop
			return false;
		}
	});
	if(allItemsChecked) {
		jQuery("#" + selectAllCheckbox).attr("checked", "checked");
	} else if(jQuery("#" + selectAllCheckbox).attr("checked")) {
		jQuery("#" + selectAllCheckbox).removeAttr("checked");
	}
}

var ihfSelectAllCheckboxes = function(selectAllCheckbox, checkBoxesContainer) {
	if(jQuery("#" + selectAllCheckbox).attr("checked")) {
		jQuery("#" + checkBoxesContainer).find("input").each(function() {
			jQuery(this).attr("checked", "checked")
		});
	} else {
		jQuery("#" + checkBoxesContainer).find("input").each(function() {
			jQuery(this).removeAttr("checked")
		});
	}
}

var ihfVariablesAutocomplete = function(fieldId, variables, prefix, suffix) {
	var $field = jQuery("#" + fieldId);
	$field.autocomplete({
		source: function(request, response) {
			var search = request.term;
			var position = $field.textrange("get", "position");
			var character = search.charAt(position - 1);
			if(character === prefix) {
				var results = [];
				for(var index in variables) {
					var variable = variables[index];
					results.push({
						label: variable.description,
						value: variable.name
					});
				}
				response(results);
			} else {
				response(null);
				$field.autocomplete("close");
			}
		},
		select: function(event, ui) {
			var position = $field.textrange("get", "position");
			$field
				.textrange("set", position -1, 1) //select the curly brace
				.textrange("replace", ui.item.value) //replace it wit the variable
				.textrange("set", $field.textrange("get", "end"), 0) //set the cursor position 
			;
			return false;
		},
		focus: function (event, ui) {
			event.preventDefault();
		}
	});
}
