/**********************************************************************************
 * The global ajax_ordernumber object should have the following entries:
 *   - Translations: 
 *     ORDERNUMBER_JS_NOT_AUTHORIZED, ORDERNUMBER_JS_INVALID_COUNTERVALUE, ORDERNUMBER_JS_JSONERROR
 *     ORDERNUMBER_JS_NEWCOUNTER, ORDERNUMBER_JS_EDITCOUNTER, ORDERNUMBER_JS_DELETECOUNTER
 *     ORDERNUMBER_JS_ADD_FAILED, ORDERNUMBER_JS_MODIFY_FAILED, ORDERNUMBER_JS_DELETE_FAILED
 *   - ajax_url: The URL for all AJAX calls

 * Optional entries (callback functions) are:
 *  - updateMessages(messages, cssidentifier)
 *  - parseAjaxResponse(response) => return json
 *  - modifyAjaxArgs(ajaxargs)    => return ajaxargs with modified arguments for jquery.ajax calls
 */
 
/**********************************************************************************
 * 
 *  Javascript for the counter modification table
 * 
 **********************************************************************************/
String.Format = function() {
  var s = arguments[0];
  for (var i = 0; i < arguments.length - 1; i++) {       
    var reg = new RegExp("\\{" + i + "\\}", "gm");             
    s = s.replace(reg, arguments[i + 1]);
  }
  return s;
}

var getCounterData = function (btn) {
    var row = jQuery(btn).closest("tr.counter_row");
    return { row: row };
}
var handleJSONResponse = function (json, counter) {
	if ('updateMessages' in ajax_ordernumber) { 
		ajax_ordernumber.updateMessages(json['messages'], "ordernumber");
	}
	if (!json.authorized && !json.success) {
		alert(ajax_ordernumber.ORDERNUMBER_JS_NOT_AUTHORIZED);
	} else if (json.error) {
		alert(json.error);
	} else {
		// TODO: Which other error checks can we do?
	}
}
var ajaxEditCounter = function (btn, nrtype, ctr, value) {
    var counter = getCounterData(btn);
    counter.type=nrtype;
    counter.counter=ctr;
    counter.value=value;
    var value = NaN;
    var msgprefix = "";
    while (isNaN(value) && (value != null)) {
        value = prompt (String.Format(ajax_ordernumber.ORDERNUMBER_JS_EDITCOUNTER, msgprefix, counter.counter, counter.value), counter.value);
        if (value != null)
            value = parseInt(value);
        if (isNaN(value)) 
            msgprefix = ajax_ordernumber.ORDERNUMBER_JS_INVALID_COUNTERVALUE;
    }
    if (value != null) {
        var loading = jQuery("img.ordernumber-loading").first().clone().insertAfter(btn).show();
        var ajaxargs = {
            type: "POST",
            url: ajax_ordernumber.ajax_url,
            data: { 
				action: 'setCounter',
				nrtype: counter.type, 
				counter: counter.counter, 
				value: value 
			},
			success: function ( json ) {
                try {
					if ('parseAjaxResponse' in ajax_ordernumber) { 
						json = ajax_ordernumber.parseAjaxResponse(json);
					}
                    handleJSONResponse(json, counter);
                } catch (e) {
                    alert(ajax_ordernumber.ORDERNUMBER_JS_JSONERROR+"\n"+e);
                    return;
                }
                if (json.success>0) {
					// replace the whole row with the html returned by the AJAX call:
					jQuery(counter.row).replaceWith(json.row);
//                     jQuery(counter.row).find(".counter_value").text(value);
                } else {
                    alert (String.Format(ajax_ordernumber.ORDERNUMBER_JS_MODIFY_FAILED, counter.counter));
                }
            },
            error: function() { alert (String.Format(ajax_ordernumber.ORDERNUMBER_JS_MODIFY_FAILED, counter.counter)); },
            complete: function() { jQuery(loading).remove(); },
        };
		if ('modifyAjaxArgs' in ajax_ordernumber) { 
			ajaxargs = ajax_ordernumber.modifyAjaxArgs(ajaxargs);
		}
		jQuery.ajax(ajaxargs);
    }
}
var ajaxDeleteCounter = function (btn, nrtype, ctr, value) {
    var counter = getCounterData(btn);
    counter.type=nrtype;
    counter.counter=ctr;
    counter.value=value;
    var proceed = confirm (String.Format(ajax_ordernumber.ORDERNUMBER_JS_DELETECOUNTER, counter.counter, counter.value));
    if (proceed == true) {
        var loading = jQuery("img.ordernumber-loading").first().clone().insertAfter(btn).show();
        var ajaxargs = {
            type: "POST",
			dataType: "json",
            url: ajax_ordernumber.ajax_url,
            data: { 
				action: 'deleteCounter',
				nrtype: counter.type, 
				counter: counter.counter 
			},
			success: function ( json ) {
                try {
					if ('parseAjaxResponse' in ajax_ordernumber) { 
						json = ajax_ordernumber.parseAjaxResponse(json);
					}
                    handleJSONResponse(json, counter);
                } catch (e) {
                    alert(ajax_ordernumber.ORDERNUMBER_JS_JSONERROR+"\n"+e);
                    return;
                }
                if (json.success>0) {
                    jQuery(counter.row).fadeOut(1500, function() { jQuery(counter.row).remove(); });
                } else {
                    alert (String.Format(ajax_ordernumber.ORDERNUMBER_JS_DELETE_FAILED, counter.counter));
                }
            },
            error: function() { alert (String.Format(ajax_ordernumber.ORDERNUMBER_JS_DELETE_FAILED, counter.counter)); },
            complete: function() { jQuery(loading).remove(); },
        };
		if ('modifyAjaxArgs' in ajax_ordernumber) { 
			ajaxargs = ajax_ordernumber.modifyAjaxArgs(ajaxargs);
		}
		jQuery.ajax(ajaxargs);
    }
}
var ajaxAddCounter = function (btn, nrtype) {
    var row = jQuery(btn).parents("tr.addcounter_row");
    var countername = prompt (ajax_ordernumber.ORDERNUMBER_JS_NEWCOUNTER);
    if (countername != null) {
        var loading = jQuery("img.ordernumber-loading").first().clone().insertAfter(jQuery(btn).find("img.ordernumber-counter-addbtn")).show();
        var ajaxargs = {
            type: "POST",
			dataType: "json",
            url: ajax_ordernumber.ajax_url,
            data: { 
				action: "addCounter",
				nrtype: nrtype, 
				counter: countername 
			},
			
			success: function ( json ) {
                try {
					if ('parseAjaxResponse' in ajax_ordernumber) { 
						json = ajax_ordernumber.parseAjaxResponse(json);
					}
                    handleJSONResponse(json, null);
                } catch (e) {
                    alert(ajax_ordernumber.ORDERNUMBER_JS_JSONERROR+"\n"+e);
                    return;
                }
                if (json.success>0) {
                    if (json.row) {
                        jQuery(row).before(jQuery(json.row));
                    }
                } else {
                    alert (String.Format(ajax_ordernumber.ORDERNUMBER_JS_ADD_FAILED, countername));
                }
            },
            error: function() { alert (String.Format(ajax_ordernumber.ORDERNUMBER_JS_ADD_FAILED, countername)); },
            complete: function() { jQuery(loading).remove(); },
        };
		if ('modifyAjaxArgs' in ajax_ordernumber) { 
			ajaxargs = ajax_ordernumber.modifyAjaxArgs(ajaxargs);
		}
		jQuery.ajax(ajaxargs);
    }
}




/**********************************************************************************
 * 
 *  Javascript for the Custom Variables table
 * 
 **********************************************************************************/

var ordernumberVariablesAddRow = function (template, element) {
	var cl = jQuery("#" + template + " tr").clone(true);
	// Enable all form controls
	jQuery(cl).find('input,select,button,img').removeAttr('disabled');

	if (jQuery.fn.chosen) {
		// select boxes handled by the chosen juery plugin cannot simply be cloned,
		// instead we need to re-initialize chosen!
		jQuery(cl).find('select').removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();
		jQuery(cl).find('select').chosen({width: "50px"});
	}
	// Now insert this new row into the table
	jQuery(cl).appendTo("table#" + element + " tbody");
	jQuery("tr#ordernumber-replacements-empty-row")
		.addClass("rowhidden")
		.find('input')
		.attr('disabled', 'disabled');
}

jQuery(document).ready (function () {
	jQuery('img.ordernumber-replacement-deletebtn').click(
		function () {
			var count = jQuery(this).closest('table').find('tbody tr').length;
			if (count<=1) {
				jQuery("tr#ordernumber-replacements-empty-row")
					.removeClass("rowhidden")
					.find('input,select,button,img')
					.removeAttr('disabled');
			}
			jQuery(this).closest('tr').remove();
		}
	);

	jQuery("#ordernumber_variables tbody").sortable();
});
