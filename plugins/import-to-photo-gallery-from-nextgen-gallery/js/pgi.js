jQuery(document).ready(function() {
  jQuery(".select").change(function() {
    if (jQuery(this).attr("checked") != "checked") {
      jQuery(this).closest(".selectors").find(".select_all").attr("checked", false);
    }
    else {
      var selected = true;
      jQuery(this).parent().parent().find(".select").each(function() {
        _this = jQuery(this);
        if (_this.attr("checked") != "checked") {  
          selected = false;
          return;
        }
      });
      if (selected == true) {
        jQuery(this).closest(".selectors").find(".select_all").attr("checked", true);
      }
    }	
  });
});

function pgi_selectAll(obj) {
  if (jQuery(obj).attr("checked") == "checked") {
    jQuery(obj).closest(".selectors").find(".select").each(function() {
      jQuery(this).attr("checked", true);
    });
  }
  else {
    jQuery(obj).closest(".selectors").find(".select").each(function() {
	    jQuery(this).attr("checked", false);
    });
  }
}

function pgi_import_data(event) {
  if (jQuery(".select:checked") .length == 0) {
    alert(pgi_objectL10n.pgi_checkbox_required);
    return false;
  }
  spider_set_input_value('task', 'import');
  spider_form_submit(event, 'galleries_form');
}

// Set value by id.
function spider_set_input_value(input_id, input_value) {
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = input_value;
  }
}

// Submit form by id.
function spider_form_submit(event, form_id) {
  if (document.getElementById(form_id)) {
    document.getElementById(form_id).submit();
  }
  if (event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}