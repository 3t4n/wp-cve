( function( jQuery ) {
jQuery(document).ready(function (jQuery) {
  var nonce = jQuery('meta[name="zoho_crm_forms_csrf_token"]').attr('content');
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': nonce
		}
	});
    var jQuery=jQuery;
    jQuery('select').select2();
   jQuery('select').on('select2:open', function () {
        if (jQuery(this).attr('multiple') == 'multiple') {
            var values = jQuery(this).val();
            var pop_up_selection = jQuery('.select2-results__options');

            if (values != null) {
                pop_up_selection.find("li[aria-selected=true]").hide();

            } else {
                pop_up_selection.find("li[aria-selected=true]").show();
            }

        }

    });
});
} )( jQuery );

var hideTimeout = '';
function fieldSetting() {
    jQuery("#field-setting-popup").show();
    jQuery("#field-setting-popup").css('overflow', 'visible');
    jQuery('.freezelayer').show();
    jQuery('html').css('overflow', 'hidden');
}
function cancelFormSettings() {
    jQuery("#field-setting-popup").hide();
    jQuery("#field-setting-popup").css('overflow', 'hidden');
    jQuery('.freezelayer').hide();
    jQuery('html').css('overflow', 'auto');
}

function zcfupdateState() {

    var selectedValues = new Array;
    jQuery("#select-fields :selected").each(function () {
        selectedValues.push(parseInt(jQuery(this).val()));
    });
    var resultarray = JSON.stringify(selectedValues);
    jQuery('#loading-image').show();
    jQuery('.freezelayer').show();
    var sorttabletbodytr = jQuery('#sort_table tbody tr').length;
    var data =  {
        action: 'zcfnewlead_form',
        Action: 'zcfupdateState',
        formfieldIds: resultarray,
        formfieldsLength: sorttabletbodytr,
        shortcodename: jQuery('#shortcode_id').val(),
    };
    // Add nonce
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data:data,
        success: function (data) {
          var data = data.replace("</div>\n</div>\n</div>\n", '');
          data = JSON.parse(data);
            jQuery('#loading-image').hide();
            jQuery('.freezelayer').hide();
            var success = "'" + jQuery('#form-name').val() + "'' updated successfully.";

            cancelNewFormPopup()
            setTimeout(function () {
                location.reload();
            }, 1000);
            showMsgBand('success', success, 3000);

        },
        error: function (errorThrown) {
        }
    });
}
function zcfdeleteFieldsState(id) {
    var selectedID = parseInt(id);
    jQuery('#loading-image').show();
    jQuery('.freezelayer').show();
    var data = {
          action: 'zcfnewlead_form',
          Action: 'zcfdeleteFieldsState',
          formfieldIds: selectedID,
      };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
          var data = data.replace("</div>\n</div>\n</div>\n", '');
          data = JSON.parse(data);
            jQuery('#loading-image').hide();
            jQuery('.freezelayer').hide();
            var success = "'" + jQuery('#form-name').val() + "'' updated successfully.";

            cancelNewFormPopup()
            setTimeout(function () {
                location.reload();
            }, 1000);
            showMsgBand('success', success, 3000);

        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }

    });
}




//Show mapped configuration
function mapping_config(map_module, map_form_title, form_id, mapped_tp_plugin, tp_roundrobin, layoutname) {

    jQuery("#clear_contents").show();
    jQuery("#mapping-modalbox").show();
    var data = {
        action: 'zcf_send_mapped_config',
        form_id: form_id,
        form_title: map_form_title,
        third_plugin: mapped_tp_plugin,
        third_module: map_module,
        third_roundrobin: tp_roundrobin,
        layoutname: layoutname
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            jQuery("#show_form_list").empty();
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            var data_array = JSON.parse(data);
            jQuery("#mapping_options").html(data_array.map_options);
            jQuery("#CRM_field_mapping").html(data_array.fields_html);

        },
        error: function (errorThrown) {
            console.log(data);
        }

    });
}

function delete_mappping_config(third_plugin, tp_form_id, formName) {
    jQuery.confirm({
        title: 'Delete Form -<span class="form-name-confirm">' + formName + "</span>",
        content: "<div class='mb20'>Your webform will be deleted permanently and will no longer be available in your website</div> Are you sure you want to delete the form?",
        type: 'red',
        typeAnimated: true,
        buttons: {
            Yes: {
                text: 'Yes',
                btnClass: 'redbtn ml10',
                text: 'Yes. Delete the form',
                action: function () {
                    jQuery('#loading-image').show();
                    jQuery('.freezelayer').show();
                    var data = {
                        action: 'zcf_delete_mapped_config',
                        form_id: tp_form_id,
                        third_plugin: third_plugin,
                    };
                    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: data,
                        success: function (data) {
                            var data = data.replace("</div>\n</div>\n</div>\n", '');
                            jQuery('#loading-image').hide();
                            jQuery('.freezelayer').hide();
                            showMsgBand('success', 'Form deletion successfull!', 10000);
                            window.location.reload();
                        },
                        error: function (errorThrown) {
                            console.log(data);
                        }

                    });
                }
            },
            Cancel: function () {
            }
        },
        keyboardEnabled: true,
    })



}

function create_crmform(Action, Module, shortcode, plugin, LayName) {

    var modulename = jQuery('#modulename').val();
    var layoutname = jQuery('#layoutname').val();
    var formTitle = jQuery('#form-name').val();
    var layoutId = jQuery('#layoutId').val();

    jQuery('#loading-image').show();
    jQuery('.freezelayer').show();
    var data = {
        action: 'zcfnewlead_form',
        Action: Action,
        Module: modulename,
        LayoutName: layoutname,
        shortcode: shortcode,
        plugin: plugin,
        layoutId: layoutId,
        formTitle: formTitle,
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
        var data = data.replace("</div>\n</div>\n</div>\n", '');
            var data_array = JSON.parse(data);
            jQuery('#loading-image').hide();
            jQuery('.freezelayer').hide();
            var shortcode = data_array.shortcode;
            var module = data_array.module;
            var crmtype = data_array.crmtype;
            var onAction = data_array.onAction;
            if (module == '') {
                module = modulename;

            }
            if (Action == 'zcfCreateShortcode') {

                window.location = "admin.php?page=create-leadform-builder&__module=ManageShortcodes&__action=zcfCrmManageFieldsLists&onAction=" + onAction + "&crmtype=" + crmtype + "&module=" + module + "&EditShortcode=" + shortcode + "&LayoutName=" + layoutname + "&formName=" + formTitle + "";
            } else {
                window.location = "admin.php?page=crmforms-builder";
            }

        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }

    });
}
function deleteforms(Action, Module, shortcode, plugin, LayName, formName) {

    jQuery.confirm({
        title: 'Delete Form -<span class="form-name-confirm">' + formName + "</span>",
        content: "<div class='mb20'>Your webform will be deleted permanently and will no longer be available in your website</div> Are you sure you want to delete the form?",
        type: 'red',
        typeAnimated: true,
        buttons: {

            Yes: {
                text: 'Yes. Delete the form',
                type: 'blue',
                btnClass: 'redbtn ml10',
                action: function () {
                    jQuery('#loading-image').show();
                    jQuery('.freezelayer').show();
                    var data = {
                        action: 'zcfnewlead_form',
                        Action: Action,
                        Module: Module,
                        LayoutName: LayName,
                        shortcode: shortcode,
                        plugin: plugin,
                    };
                    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: data,
                        success: function (data) {
                          var data = data.replace("</div>\n</div>\n</div>\n", '');
                            var data_array = JSON.parse(data);
                            jQuery('#loading-image').hide();
                            jQuery('.freezelayer').hide();
                            var shortcode = data_array.shortcode;
                            var module = data_array.module;
                            var crmtype = data_array.crmtype;
                            var onAction = data_array.onAction;
                            showMsgBand('success', 'Shortcode Deleted Successfully!', 10000);
                            window.location = "admin.php?page=crmforms-builder";
                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                }
            },
            Cancel: function () {

            }
        },
        keyboardEnabled: true,
    })
}
function edit_thirdpartyforms() {
    window.location = "create-leadform-builder&__module=ManageShortcodes&__action=zcfCrmManageFieldsLists&onAction=onEditShortCode&crmtype=crmformswpbuilder&module=Leads&EditShortcode=kS6DT&LayoutName=Standard&formName=Unititled";
}
function edit_forms(Action, Module, shortcode, plugin, LayName,formName) {
    var modulename = jQuery('#modulename').val();
    var layoutname = LayName;
    var formTitle = formName;
    var layoutId = jQuery('#layoutId').val();
    modulename = Module;
    layoutname = LayName;
    var create = "onCreate";
    setTimeout(function () {
    synceditFieldZohocrm(siteurl = '', 'crmformswpbuilder', modulename, leads_fields_tmp = '', create, '', contact_fields_tmp = '', 'leads', layoutname, shortcode);
   }, 100);
    jQuery('#loading-image').show();
    jQuery('.freezelayer').show();
    setTimeout(function () {
      var data = {
          action: 'zcfnewlead_form',
          Action: Action,
          Module: modulename,
          LayoutName: layoutname,
          shortcode: shortcode,
          plugin: plugin,
          layoutId: layoutId,
          formTitle: formTitle,
      };
      data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function (data) {
                var data = data.replace("</div>\n</div>\n</div>\n", '');
                var data_array = JSON.parse(data);
                jQuery('#loading-image').hide();
                jQuery('.freezelayer').hide();
                var shortcode = data_array.shortcode;
                var module = data_array.module;
                var crmtype = data_array.crmtype;
                var onAction = data_array.onAction;
                if (module == '') {
                    module = modulename;

                }
                window.location = "admin.php?page=create-leadform-builder&__module=ManageShortcodes&__action=zcfCrmManageFieldsLists&onAction=" + onAction + "&crmtype=" + crmtype + "&module=" + module + "&EditShortcode=" + shortcode + "&LayoutName=" + layoutname + "&formName=" + formTitle + "";


            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }

        });
    }, 3000)
}
function removemapppingcontents() {

    window.location = "admin.php?page=crmforms-builder";


}

//MAPPING CRM FIELDS

function getMappingConfiguration(thirdparty_form) {
    var thirdparty_module = jQuery("#map_thirdparty_module").val();
    if (thirdparty_module == "none") {
        alert("kindly choose module to map");
        return false;
    }
    var   data = {
          action: 'zcfsend_mapping_configuration',
          thirdparty_module: thirdparty_module,
          thirdparty_plugin: thirdparty_form,
      };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            if (data == 'false') {
                showMsgBand('success', 'Please choose any form type above!', 10000);
                jQuery("#display_form_lists").html('');
                jQuery('#map_fields').attr('disabled', true);
            } else {
                jQuery("#display_form_lists").html(data);
                jQuery('select').select2();
                jQuery('#map_fields').attr('disabled', false);
            }



        },
        error: function (errorThrown) {
            console.log(data);
        }

    });
}
function getThirdpartyTitle() {
    var layoutname = jQuery('#choose-thirdleads-layout option:selected').text();
    window.location = "admin.php?page=create-thirdpartyform-builder&__module=" + jQuery('#map_thirdparty_module').val() + "&form_title=" + jQuery('#thirdparty_form_title').val() + "&third_module=" + jQuery('#map_thirdparty_module').val() + "&third_plugin=contactform&layoutname=" + layoutname + "&layoutId=" + jQuery('#choose-thirdleads-layout').val() + "&third_module_pluginname=" + jQuery('#thirdparty_form_title option:selected').text();


}
function editThirdpartyFrom(form_ID, third_plugin, third_module, layoutname, layoutId, contact_form_title) {
    window.location = "admin.php?page=create-thirdpartyform-builder&__module=" + third_module + "&form_title=" + form_ID + "&third_module=" + third_module + "&third_plugin=contactform&layoutname=" + layoutname + "&layoutId=" + jQuery('#choose-thirdleads-layout').val() + "&third_module_pluginname=" + contact_form_title;
}



//Map Existing Forms
function mapping_crmforms_fields() {
    var count = jQuery("#total_field_count").val();
    var tp_module = jQuery("#module").val();
    var tp_title = jQuery("#form_name").val();
    var tp_crm = jQuery("#active_crm").val();
    var tp_plugin = jQuery("#thirdparty_plugin").val();
    var tp_duplicate = jQuery("#duplicate_handling").val();
    var layoutname = jQuery('#layoutname').val();
    var layoutId = jQuery("#layoutId").val()

    var tp_assignedto = jQuery("#assignedto").val();
    var assignedto_name = jQuery("#assignedto option:selected").text();
    var crm_mandatory_fields = jQuery("#crm_mandatory_fields").val();
    var crm_man_fields = JSON.parse(crm_mandatory_fields);
    var flag, error = 0,
            errormessage = "";
    var Repeat_fields = false,
            mapping_fields = [],
            save_repeated_fields = [];

    for (var i = 0; i < crm_man_fields.length; i++) {
        flag = false;
        for (j = 1; j < count; j++) {
            var check_man_field = "#crm_fields_" + j;
            selected_val = jQuery(check_man_field).val();
            if (selected_val == crm_man_fields[i]) {
                flag = true;
            }
        }
        if (flag == false) {
            errormessage += crm_man_fields[i] + " cannot be empty. Please map it with Contact Form field.\n";
            error++;
        }
    }


    for (var i = 1; i < count; i++) {
        var crm_field_name = "#crm_fields_" + i;
        selected_val = jQuery(crm_field_name).val();
        if (mapping_fields.indexOf(selected_val) != -1 && selected_val != "" && selected_val != "None") {
            Repeat_fields = true;
            save_repeated_fields[i] = selected_val;
        }
        mapping_fields[i] = selected_val;

    }

    if (error > 0) {
        showMsgBand('warning', errormessage, 10000);
        return false;
    }


    if (Repeat_fields == true) {
        showMsgBand('warning', "Mapped Fields should not be repeated", 10000);
        return false;
    }

    var config_data = JSON.parse("" || "{}");
    var items = jQuery("form :input").map(function (index, elm) {
        return {
            name: elm.name,
            type: elm.type,
            value: jQuery(elm).val()
        };
    });

    jQuery.each(items, function (i, d) {
        if (d.value != '' && d.value != null)
            config_data[d.name] = d.value;
    });
    var data = {
        action: 'zcf_map_contactform_fields',
        post_data: config_data,
        form_title: tp_title,
        third_plugin: tp_plugin,
        third_module: tp_module,
        third_crm: tp_crm,
        third_duplicate: tp_duplicate,
        third_assigedto: tp_assignedto,
        assignedto_name: assignedto_name,
        layoutname: layoutname,
        layoutId: layoutId,
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            showMsgBand('success', 'Mapped Successfully', 3000);
            setTimeout(function () {
                window.location = "admin.php?page=crmforms-builder";

            }, 2000);


        },
        error: function (errorThrown) {
            console.log(data);
        }

    });
}

function showMsgBand(type, content, delay) {

    jQuery("#crm-msg").remove();
    clearTimeout(hideTimeout);
    var divEle = jQuery('<div>');
    var spanEle = jQuery('<span>');
    var spanEleT = jQuery('<span>');
    divEle.attr({'id': 'crm-msg', 'class': 'crm-msg fromTopCenter'});//No I18N
    spanEle.attr({'id': 'crm-msg-close', 'class': 'crm-msg-close'});//No I18N
    spanEleT.attr({'class': 'cm-ico'});//No I18N
    jQuery("body").append(divEle); // NO OUTPUTENCODING
    divEle.click(function (e) {
        sE(e);
    })

    divEle.html("<div class='crm-msg-cnt'>" + content + "</div>"); // NO OUTPUTENCODING
    if (type === "success") {
        jQuery("#crm-msg").addClass("crm-band-success");
    }
    if (type === "warn" || type === "warning" || type === "alert") {
        jQuery("#crm-msg").addClass("crm-band-warn");
    }
    if (type === "info") {
        jQuery("#crm-msg").addClass("crm-band-info");
    }
    if (type === "error") {
        jQuery("#crm-msg").addClass("crm-band-error");
    }
    jQuery("#crm-msg").show();
    if (delay != '' && delay != 'infinity') {
        hideMsgBand(delay);
    } else if (delay === 'infinity') {
        hideMsgBand('150000');
    } else {
        hideMsgBand('4000');
    }
    jQuery("#crm-msg").append(spanEle); // NO OUTPUTENCODING
    jQuery("#crm-msg").prepend(spanEleT); // NO OUTPUTENCODING
    jQuery("#crm-msg-close").on("click", function () {
        jQuery("#crm-msg").remove();
    });
}

function  hideMsgBand(delay) {
    hideTimeout = setTimeout(function () {
        jQuery("#crm-msg").fadeOut("", function () {
            jQuery("#crm-msg").remove();
        });
        clearTimeout(hideTimeout);
    }, delay);
}


function clicktocopyshortcode(obj) {


    var id = 'shortcode-id';
    jQuery(obj).attr('title', 'Click to copy shortcode').removeClass('shortcodeCopied');
    jQuery(obj).attr('title', 'Shortcode Copied').addClass('shortcodeCopied');
    setTimeout(function () {
        jQuery(obj).removeClass('shortcodeCopied').attr('title', 'Click to copy shortcode');
    }, 7000);
    let t = document.createElement('textarea')
    t.id = 't'
    t.style.height = 0
    document.body.appendChild(t)
    t.value = document.getElementById(id).innerText
    let selector = document.querySelector('#t')
    selector.select()
    document.execCommand('copy')
    document.body.removeChild(t)

}
function clicktocopyshortcodeList(obj) {


    var id = jQuery(obj).parent().attr('id');
    jQuery(obj).attr('title', 'Click to copy shortcode').removeClass('shortcodeCopied');
    jQuery(obj).attr('title', 'Shortcode Copied').addClass('shortcodeCopied');
    setTimeout(function () {
        jQuery(obj).removeClass('shortcodeCopied').attr('title', 'Click to copy shortcode');
    }, 7000);
    let t = document.createElement('textarea')
    t.id = 't'
    t.style.height = 0
    document.body.appendChild(t)
    t.value = jQuery(obj).parent().find('#copyshortcodeTxt').text();
    let selector = document.querySelector('#t')
    selector.select()
    document.execCommand('copy')
    document.body.removeChild(t)

}



function saveFormSettings(shortcodename) {
    var formtype = jQuery("input[name=formtype]").val();
    var duplicate_handling = jQuery("input[type=radio][name=check_duplicate]:checked").val();
    var assignedto = jQuery("select[name=assignedto]").val();
    var assignemail = jQuery("select[name=assignedto] option:selected").text();
    var errormessage = jQuery("input[name=errormessage]").val();
    var successmessage = jQuery("input[name=successmessage]").val();
    var enableurlredirection = jQuery("input[type=checkbox][name=enableurlredirection]").is(':checked');
    var redirecturl = jQuery("input[name=redirecturl]").val();
    var enablecaptcha = jQuery("input[type=checkbox][name=enablecaptcha]").is(':checked');
    var customthirdpartyplugin = jQuery("input[type=checkbox][name=customthirdpartyplugin]").is(':checked');

    //var savedetails = '<br>FormType &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' + formtype + '<br>' + 'Shortcode &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  ' + shortcodename + '<br>' + 'Assignee&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' + assignemail   ;

    jQuery("select[name=assignedto]").change(function () {
        var option_selected = jQuery(this).find("option:selected").text();
    });

    var redirect = jQuery("#redirecturl").val();
    var thirdparty_form_type = 'contactform';
    var thirdparty_title = jQuery("#thirdparty_form_title").val();
    var thirdparty_option_available = jQuery("#thirdparty_option_available").val();
    var assignmentrule_ID = jQuery("#assignmentRule_ID").val();
    var assignmentrule_enable = jQuery("input[type=radio][name=check_assigenduser]:checked").val();

    if (redirect.length > 0) {
        var redir_postid = '<br>Redir Post-id &nbsp;:' + redirect;
    }

    jQuery('#loading-image').show();
    jQuery('.freezelayer').show();

    var data = {
        'action': 'zcfmainFormsActions',
        'operation': 'NoFieldOperation',
        'doaction': 'SaveFormSettings',
        'shortcode': shortcodename,
        'formtype': formtype,
        'duplicate_handling': duplicate_handling,
        'assignedto': assignedto,
        'errormessage': errormessage,
        'successmessage': successmessage,
        'enableurlredirection': enableurlredirection,
        'redirecturl': redirecturl,
        'enablecaptcha': enablecaptcha,
        'thirdparty_title': thirdparty_title,
        'thirdparty_form_type': thirdparty_form_type,
        'assignmentrule_ID': assignmentrule_ID,
        'assignmentrule_enable': assignmentrule_enable,
        'customthirdpartyplugin': customthirdpartyplugin,
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
              var data = data.replace("</div>\n</div>\n</div>\n", '');

            jQuery("#url_post_id").css('display', 'block');
            jQuery("#url_post_id").html(redir_postid);
            jQuery("#url_post_id").css('display', 'inline').fadeOut(3800);
            var success = "The settings of the form updated successfully.";
            showMsgBand('success', success, 10000);
            jQuery('#loading-image').hide();
            jQuery('.freezelayer').hide();
            if (jQuery('#customthirdpartyplugin').is(':checked')== true) {
                jQuery('#generate_forms').click();

            }
            cancelFormSettings();
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}

















function updatecaptchakey() {
    var emailcondition = jQuery("#emailcondition").val();
    var email = jQuery("#email").val();

    if (jQuery('#crmforms_recaptcha_no').is(":checked")) {
        var crmforms_recaptcha = "no";
    } else {
        crmforms_recaptcha = "yes";
    }
    var recaptcha_public_key = jQuery("#crmforms_public_key").val();
    var recaptcha_private_key = jQuery("#crmforms_private_key").val();
    var data = {
          'action': 'zcfcaptcha_info',
          'emailcondition': emailcondition,
          'email': email,
          'crmforms_recaptcha': crmforms_recaptcha,
          'recaptcha_public_key': recaptcha_public_key,
          'recaptcha_private_key': recaptcha_private_key,
      };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            showMsgBand('success', 'The settings of the plugin Zoho CRM Forms  updated successfully', 3000);
            setTimeout(function () {
                location.reload(true);
            }, 3000)

        },

        error: function (errorThrown) {
            console.log(errorThrown);
        }

    });

}

function toggleRecaptcha(option) {
    if (option == "no") {
        jQuery("#recaptcha_public_key").css("display", 'none');
        jQuery("#recaptcha_private_key").css("display", 'none');
    } else {
        jQuery("#recaptcha_public_key").css("display", 'block');
        jQuery("#recaptcha_private_key").css("display", 'block');
    }
}






function saveConfig(id) {
    jQuery('.circle-loader').removeClass('enableloading').removeClass('load-complete');
    jQuery('.checkmark').css('display', 'none');
    jQuery('#loading-image').show();
    jQuery('.freezelayer').show();
    var siteurl = jQuery("#site_url").val();
    var active_plugin = jQuery("#active_plugin").val();
    var leads_fields_tmp = jQuery("#leads_fields_tmp").val();
    var contact_fields_tmp = jQuery("#contact_fields_tmp").val();
    var leads = "Leads";
    var contact = "Contacts";
    var create = "onCreate";
    var config_data = JSON.parse("" || "{}");
    var items = jQuery("form :input").map(function (index, elm) {
        return {
            name: elm.name,
            type: elm.type,
            value: jQuery(elm).val()
        };
    });

    jQuery.each(items, function (i, d) {
        if (d.value != '' && d.value != null)
            config_data[d.name] = d.value;
    });
    jQuery('.lead-module-update-status').removeClass('dN');
    syncFields(siteurl, 'crmformswpbuilder', leads, leads_fields_tmp, create, contact, contact_fields_tmp, 'leads');
    window.location = siteurl + '/wp-admin/admin.php?page=crmforms-builder';


}




function syncFields(siteurl, crmtype, module, option, onAction, contactmodule, contact_fields_tmp, call_back) {
    //Clear CSS
    var shortcode = '';
    if (onAction == 'onEditShortCode') {
        shortcode = jQuery('#shortcode').val();
    }
    jQuery('#' + module + '-module').addClass('enableloading');
    var data = {
        'action': 'zcfmainFormsActions',
        'doaction': 'FetchCrmFields',
        'siteurl': siteurl,
        'module': module,
        'crmtype': crmtype,
        'option': option,
        'onAction': onAction,
        'shortcode': shortcode,
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery('#loading-image').hide();
            jQuery('.freezelayer').hide();
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}

function updateStatus(obj, siteurl, module, option, shortcode, onAction) {
    var crmtype = document.getElementById("lead_crmtype").value;

    var bulkaction = jQuery(obj).attr('data-label');
    var form = document.getElementById('field-form');
    var chkall = form.elements['selectall'];
    var chkBx_count = form.elements['no_of_rows'].value;
    var chkArray = '';
    var labelArray = new Array;
    var orderArray = new Array;
    var returnflag = true;
    var a = 0;
    var i;
    var className = '';
    var successMsg = "Form updated successfully!";
    if (bulkaction == 'Enable Field') {
        jQuery(obj).attr('data-label', 'Disable Field');
        jQuery(obj).parents('tr').find(".mandatoryField,.hiddenFields,.defaultvaluesField").removeClass('disabled');
        jQuery(obj).parents('tr').find('.field_label_display').attr('disabled', false).removeClass('disabled');
    } else if (bulkaction == 'Disable Field') {
        jQuery(obj).attr('data-label', 'Enable Field');
        jQuery(obj).parents('tr').find(".mandatoryField,.hiddenFields,.defaultvaluesField").addClass('disabled');
        jQuery(obj).parents('tr').find('.field_label_display').attr('disabled', true).addClass('disabled');
    } else if (bulkaction == 'Enable Mandatory') {
        jQuery(obj).attr('data-label', 'Disable Mandatory');
        jQuery(obj).parents('tr').find(".hiddenFields,.defaultvaluesField").addClass('disabledHidden');
				jQuery(obj).parents('tr').find(".hiddenfieldChk,.defaultvaluesField").attr('data-label', 'Hidden Enable Field');
				jQuery(obj).parents('tr').find(".hiddenfieldChk,.defaultvaluesField").find('.form-control').removeClass('dB');
				jQuery(obj).parents('tr').find(".hiddenfieldChk").prop("checked", false);


    } else if (bulkaction == 'Disable Mandatory') {
        jQuery(obj).attr('data-label', 'Enable Mandatory');
        jQuery(obj).parents('tr').find(".hiddenFields,.defaultvaluesField").removeClass('disabledHidden');
				jQuery(obj).parents('tr').find(".hiddenfieldChk,.defaultvaluesField").attr('data-label', 'Hidden Enable Field');
				jQuery(obj).parents('tr').find(".hiddenfieldChk").prop("checked", false);
				jQuery(obj).parents('tr').find(".hiddenfieldChk").find('.form-control').addClass('dB');


    } else if (bulkaction == 'Hidden Enable Field') {
        jQuery(obj).attr('data-label', 'Hidden Disable Field');
        jQuery(obj).parents('td').next('td').find('.form-control').addClass('dB')

    } else if (bulkaction == 'Hidden Disable Field') {
        jQuery(obj).attr('data-label', 'Hidden Enable Field');
        jQuery(obj).parents('td').next('td').find('.form-control').removeClass('dB');
    } else if (bulkaction == 'Default value') {
        jQuery(obj).parents('td').next('td').find('.form-control').removeClass('dB');
        var curData = jQuery(obj).val();
        if (curData == jQuery(obj).attr('data-value')) {
            returnflag = false;
        } else {
            jQuery(obj).attr('data-value', curData);
            returnflag = true;
        }

    } else {
        var labelDisplay = jQuery(obj).val();
        var attrDataValue = jQuery(obj).attr('data-value');
        returnflag = true;
        if (labelDisplay != undefined) {
            if (attrDataValue == labelDisplay) {
                returnflag = false;
            } else {
                jQuery(obj).attr('data-value', labelDisplay);
                returnflag = true;
            }
        } else {
            returnflag = true;
        }



    }



    chkArray = jQuery(obj).parents('tr').index();
    var fielsId = jQuery(obj).attr('data-id');
    for (i = 0; i < chkBx_count; i++) {
        var Label = document.getElementById('field_label_display_' + i).value;
        labelArray.push(Label);
    }
    jQuery("#sort_table tbody").find('tr').each(function (i, el) {
        var tds = jQuery(this).find('.orderPos');
        var idx = tds.attr('data-id');
        var changed_pos = parseInt(idx);
        orderArray.push(changed_pos);

    });
    var labelarray = JSON.stringify(labelArray);
    var orderarray = JSON.stringify(orderArray);
    var inputtype = jQuery(obj).prop("type");
    var defaultvalue = jQuery(obj).val();
    var data = {
        'action': 'zcfmainFormsActions',
        'doaction': 'CheckformExits',
        'siteurl': siteurl,
        'module': module,
        'crmtype': crmtype,
        'option': option,
        'onAction': onAction,
        'shortcode': shortcode,
        'bulkaction': bulkaction,
        'chkarray': fielsId,
        'labelarray': labelDisplay,
        'orderarray': orderarray,
        'defaultvalue': defaultvalue,
        'inputtype': inputtype,

    };
      data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    var flag = true;
    if (returnflag == true) {
        jQuery('#loading-image').show();
        jQuery('.freezelayer').show();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function (data) {
                var data = data.replace("</div>\n</div>\n</div>\n", '');
                jQuery('#loading-image').hide();
                jQuery('.freezelayer').hide();


                showMsgBand('success', successMsg, 3000);
                jQuery(obj).parents('tr').removeClass('active');
                var counteditfield = jQuery('#sort_table').find('tr.active').length;
                jQuery('.editupdatecount').text(counteditfield);
                if (counteditfield == 0) {
                    jQuery('.editupdatecount').hide();
                }


            },
            error: function (errorThrown) {}
        });
    }
    return flag;
}

function ChooseFields(siteurl, module, option, onAction) {
    var crmtype;
    var module;
    module = document.getElementById("module").value;
    crmtype = document.getElementById("crmtype").value;
    var shortcode = '';
    if (onAction == 'onEditShortCode') {
        shortcode = jQuery('#shortcode').val();
    }
    if (module != "--Select--" && crmtype != "--Select--") {
        jQuery('#loading-image').show();
        jQuery('.freezelayer').show();
        var data = {
            'action': 'zcfmainFormsActions',
            'doaction': 'GetTemporaryFields',
            'siteurl': siteurl,
            'module': module,
            'crmtype': crmtype,
            'option': option,
            'onAction': onAction,
            'shortcode': shortcode,
        };
        data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function (data) {
                var data = data.replace("</div>\n</div>\n</div>\n", '');
                jQuery("#fieldtable").html(data);
                assigToUser(module, crmtype, siteurl, option, onAction, shortcode);
                jQuery('#loading-image').hide();
                jQuery('.freezelayer').show();
                //location.reload();
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    }
}



function assigToUser(module, crmtype, siteurl, option, onAction, shortcode) {
  var data = {
      'action': 'zcfmainFormsActions',
      'doaction': 'GetAssignedToUser',
      'siteurl': siteurl,
      'module': module,
      'crmtype': crmtype,
      'option': option,
      'onAction': onAction,
      'shortcode': shortcode,
  };
  data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery("#assignedto_td").html(data);
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}


function toggleredirecturl(id) {
    if (document.getElementById("enableurlredirection").checked == true) {
        jQuery('#redirecturl').removeClass('vH');
    } else {
        jQuery('#redirecturl').addClass('vH');
    }
}
function toggleCustomPlugin(id) {
    if (document.getElementById("customthirdpartyplugin").checked == true) {
        jQuery('.customthirdparty,.update-thirdparty_title').removeClass('vH');
    } else {
        jQuery('.customthirdparty,.update-thirdparty_title').addClass('vH');
    }
}




function syncCrmformsfields(siteurl, crmtype, module, option, onAction, contactmodule, contact_fields_tmp, call_back) {
    var shortcode = '';
    if (onAction == 'onEditShortCode') {
        shortcode = jQuery('#shortcode').val();
    }
    jQuery('#' + module + '-module').addClass('enableloading');
    var data = {
        'action': 'zcfmainFormsActions',
        'doaction': 'FetchCrmFields',
        'siteurl': siteurl,
        'module': module,
        'crmtype': crmtype,
        'option': option,
        'onAction': onAction,
        'shortcode': shortcode,
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery('.smaill-loading-image').hide();
            syncModuleFiels(siteurl, module);
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}
function synceditFieldZohocrm(siteurl, crmtype, module, option, onAction, contactmodule, contact_fields_tmp, call_back, layoutname, shortcode) {
    jQuery('#' + module + '-module').addClass('enableloading');
    var data = {
        'action': 'zcfmainFormsActions',
        'doaction': 'FetcheditCrmFields',
        'siteurl': siteurl,
        'module': module,
        'crmtype': crmtype,
        'option': option,
        'onAction': onAction,
        'shortcode': shortcode,
        'layoutname': layoutname,
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery('.smaill-loading-image').hide();
            syncModuleFiels(siteurl, module);
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}
function syncfields(siteurl, crmtype, module, option, onAction, contactmodule, contact_fields_tmp, call_back, layoutname, shortcode) {
     jQuery('#loading-image').show();
      jQuery(this).css('pointer-events','none');
    jQuery('#' + module + '-module').addClass('enableloading');
    var data = {
        'action': 'zcfmainFormsActions',
        'doaction': 'FetcheditCrmFields',
        'siteurl': siteurl,
        'module': module,
        'crmtype': crmtype,
        'option': option,
        'onAction': onAction,
        'shortcode': shortcode,
        'layoutname': layoutname
    };
      data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery('#loading-image').hide();
            syncModuleFiels(siteurl, module);
             setTimeout(function () {
                location.reload();
            }, 100);
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}


function syncthirdZohocrm(siteurl, crmtype, module, option, onAction, contactmodule, contact_fields_tmp, call_back) {
    var shortcode = '';
    if (onAction == 'onEditShortCode') {
        shortcode = jQuery('#shortcode').val();
    }
    jQuery('#' + module + '-module').addClass('enableloading');
    var data = {
        'action': 'zcfmainFormsActions',
        'doaction': 'FetchCrmFields',
        'siteurl': siteurl,
        'module': module,
        'crmtype': crmtype,
        'option': option,
        'onAction': onAction,
        'shortcode': shortcode,
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery('.smaill-loading-image').hide();

            syncythirdModuleFiels(siteurl, module);

        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}
function createNewFormPopup() {
    jQuery('#create-new-form-popup').show();
    jQuery("#reate-new-form-popup").css('overflow', 'visible');
    jQuery('.freezelayer').show();
    jQuery('html').css('overflow', 'hidden');
}
function createNewTPFormPopup() {
    jQuery('#thirdparity-field-mapping-popup').show();
    jQuery("#create-new-form-popup").css('overflow', 'visible');
    jQuery('.freezelayer').show();
    jQuery('html').css('overflow', 'hidden');
}
function cancelNewTPFormPopup() {
    jQuery('#thirdparity-field-mapping-popup').hide();
    jQuery('#thirdparity-field-mapping-popup').css('overflow', 'hidden');
    jQuery('.freezelayer').hide();
    jQuery('html').css('overflow', 'auto');
}
function cancelNewFormPopup() {
    jQuery('.newPopup').hide();
    jQuery('.newPopup').css('overflow', 'hidden');
    jQuery('.freezelayer').hide();
    jQuery('html').css('overflow', 'auto');
}
function fieldlistpopup() {
    jQuery('#create-fields-list-popup').show();
    jQuery("#create-fields-list-popup").css('overflow', 'visible');
    jQuery('.freezelayer').show();
    jQuery('html').css('overflow', 'hidden');
}
function selectModule(obj, siteurl) {
    jQuery('.smaill-loading-image').css('display', 'inline-block');
    var moduleName = jQuery(obj).val();
    if (moduleName != '') {
        var siteurl = jQuery("#site_url").val();
        var active_plugin = jQuery("#active_plugin").val();
        var leads_fields_tmp = jQuery("#leads_fields_tmp").val();
        var contact_fields_tmp = jQuery("#contact_fields_tmp").val();
        var module = moduleName;
        var create = "onCreate";
        jQuery('#modulename').val(jQuery(obj).val());
        syncCrmformsfields(siteurl, 'crmformswpbuilder', moduleName, leads_fields_tmp, create, '', contact_fields_tmp, 'leads');
    } else {
        jQuery('#modulename').val(jQuery(obj).val())
        jQuery('#choose-leads-layout').addClass('dN');
    }

}
function selectThirdModule(obj, siteurl, id) {
    jQuery('.smaill-loading-image').css('display', 'inline-block');
    var moduleName = jQuery(obj).val();
    if (moduleName != '') {
        var siteurl = jQuery("#site_url").val();
        var active_plugin = jQuery("#active_plugin").val();
        var leads_fields_tmp = jQuery("#leads_fields_tmp").val();
        var contact_fields_tmp = jQuery("#contact_fields_tmp").val();
        var module = moduleName;
        var create = "onCreate";
        jQuery('#modulename').val(jQuery(obj).val());
        syncthirdZohocrm(siteurl, 'crmformswpbuilder', moduleName, leads_fields_tmp, create, '', contact_fields_tmp, 'leads');
    } else {
        jQuery('#modulename').val(jQuery(obj).val())
        jQuery('#choose-leads-layout').addClass('dN');
    }
}
function syncythirdModuleFiels(siteurl, moduleName) {
    var data = {
        'action': 'zcf_getModuleLayoutlist',
        'siteurl': siteurl,
        'module': moduleName,
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery('#layout-third-module').css('display','flex');
            jQuery('#choose-thirdleads-layout select').select2('destroy');
            jQuery('#choose-thirdleads-layout').html("<select>" + data + "</select>");
            jQuery('#choose-thirdleads-layout select').select2();
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}
function selectThirdlayout(obj) {
    jQuery('#layoutname').val(jQuery('#choose-thirdleads-layout option:selected').text());
    jQuery('#layoutId').val(jQuery(obj).val());
    if (jQuery(obj).val() != '') {
        jQuery('#thirdparty-plugin-list').show();
        jQuery('#form-tbsubmit-module').prop('disabled', false);

    } else {
        jQuery('#thirdparty-plugin-list').hide();
        jQuery('#form-tbsubmit-module').prop('disabled', true);
    }

}
function syncrmModules() {
    jQuery('#loading-image').show();
    jQuery('.freezelayer').show();
    var siteurl = jQuery("#site_url").val();
    var active_plugin = jQuery("#active_plugin").val();
    var leads_fields_tmp = jQuery("#leads_fields_tmp").val();
    var contact_fields_tmp = jQuery("#contact_fields_tmp").val();
    var data = {
        'action': 'zcfmainFormsActions',
        'doaction': 'FetchcrmModules',
        'siteurl': siteurl,
        'module': 'Leads',
        'crmtype': 'crmformswpbuilder',
        'onAction': 'create',
    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery('#loading-image').hide();
            jQuery('.freezelayer').hide();
            showMsgBand('success', 'Latest module updated Successfully', 4000);
            setTimeout(function () {
                window.location = jQuery('#currentpageUrl').val();
            }, 1000);
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });

}

function formTitleupdate(obj, dataVal, siteurl, shortcode) {
    var curVal = jQuery(obj).val();
    var attrVal = jQuery(obj).attr('data-value');

    var data ={
        'action': 'zcf_updateTitles',
        'siteurl': siteurl,
        'shortcode': shortcode,
        'formvalue': curVal,
    }
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    if (attrVal != curVal) {
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function (data) {
                var data = data.replace("</div>\n</div>\n</div>\n", '');
                if (jQuery.trim(data) == 'success') {
                    jQuery(obj).attr('data-value', curVal)
                    showMsgBand('success', 'Form name updated Successfully', 10000);

                } else {
                    showMsgBand('warning', 'Oops....', 10000);

                }

            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    }

}
function syncModuleFiels(siteurl, moduleName) {
  var data = {
      'action': 'zcf_getModuleLayoutlist',
      'siteurl': siteurl,
      'module': moduleName,
  };
  data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            jQuery('.lead-module-update-status').removeClass('dN');
            jQuery('#choose-leads-layout').removeClass('dN');
            jQuery('#choose-leads-layout select').select2('destroy');
            jQuery('#choose-leads-layout select').html(data);
            jQuery('#choose-leads-layout select').select2();
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}
function thirdParty() {
    jQuery('#CRM_field_mapping').empty();
    jQuery("#mapping-modalbox").show();
    jQuery("#clear_contents").show();
}
function selectLayout(obj) {
    if (jQuery(obj).val() != '') {
        jQuery('#form-submit-module').prop('disabled', false);
        jQuery('#layoutname').val(jQuery('#select-layout option:selected').text());
        jQuery('#layoutId').val(jQuery(obj).val());

    } else {
        jQuery('#form-submit-module').prop('disabled', true);
        jQuery('#layoutname').val(jQuery('#select-layout option:selected').text());
        jQuery('#layoutId').val(jQuery(obj).val());
    }
}
function assignedUser(obj) {
    var curObj = jQuery(obj);
    var curVal = jQuery(obj).val();
    if (curVal === 'updaterule') {
        jQuery('#assignmentRule').show();
        jQuery('#assignedto_td').hide();
    } else {
        jQuery('#assignedto_td').show();
        jQuery('#assignmentRule').hide();
    }
}
function thirdparty_form_title_change(obj) {
    if (jQuery(obj).val() != 'None') {
        jQuery('.crmmodule-container').css('display','flex');

    } else {
        jQuery('.crmmodule-container').hide();
    }
}

jQuery(function () {
    jQuery('#crmforms_recaptcha_yes').on('ifChecked', function () {
        jQuery('.leads-captcha,#recaptcha_private_key,#recaptcha_public_key').slideDown('slow').css('display', 'block');
    });
    jQuery('#crmforms_recaptcha_yes').on('ifUnchecked', function () {
        jQuery('.leads-captcha').slideUp('slow');
    });

});
function selectaccount(obj){
    var curlVal = jQuery(obj).val();
    if(curlVal == 'com'){
        jQuery('#zohocomaccount').show();
        jQuery('#zohoeuaccount').hide();
				jQuery('#zohocomauaccount').hide();
				jQuery('#zohoinaccount').hide();
        jQuery('#zohocomjpaccount').hide();
        jQuery('#zcrm_integ_authorization_uri').val("https://extensions.zoho.com/plugin/wordpress/callback")

    }else if(curlVal == 'com.au'){
        jQuery('#zohocomaccount').hide();
				jQuery('#zohoeuaccount').show();
        jQuery('#zohocomauaccount').show();
				jQuery('#zohoinaccount').hide();
        jQuery('#zohocomjpaccount').hide();
        jQuery('#zcrm_integ_authorization_uri').val("https://extensions.zoho.com.au/plugin/wordpress/callback")
    }else if(curlVal == 'in'){
			jQuery('#zohoinaccount').show();
        jQuery('#zohocomaccount').hide();
				jQuery('#zohoeuaccount').hide();
        jQuery('#zohocomauaccount').hide();
        jQuery('#zohocomjpaccount').hide();
        jQuery('#zcrm_integ_authorization_uri').val("https://extensions.zoho.in/plugin/wordpress/callback")
    }else if(curlVal == 'jp'){
		  	jQuery('#zohoinaccount').hide();
        jQuery('#zohocomaccount').hide();
				jQuery('#zohoeuaccount').hide();
        jQuery('#zohocomauaccount').hide();
        jQuery('#zohocomjpaccount').show();
        jQuery('#zcrm_integ_authorization_uri').val("https://extensions.zoho.jp/plugin/wordpress/callback")
    }else{
			jQuery('#zohocomaccount').hide();
			jQuery('#zohoinaccount').hide();
			jQuery('#zohocomauaccount').hide();
			jQuery('#zohoeuaccount').show();
      jQuery('#zohocomjpaccount').hide();
			jQuery('#zcrm_integ_authorization_uri').val("https://extensions.zoho.eu/plugin/wordpress/callback")
		}
}
function authToken(siteurl){
    var domain             = jQuery('#zcrm_integ_domain_name').val();
    var clientID           = jQuery('#zcrm_integ_client_id').val();
    var clientSecKey       = jQuery('#zcrm_integ_client_secret').val();
    var domainName = "https://extensions.zoho.com/plugin/wordpress/callback";
    var accountUrl = "https://accounts.zoho.com/developerconsole";
    if(domain =='eu'){
        domainName = "https://extensions.zoho.eu/plugin/wordpress/callback";
        accountUrl = "https://accounts.zoho.eu/developerconsole";
    }else if(domain =='com.au'){
			domainName = "https://extensions.zoho.com.au/plugin/wordpress/callback";
			accountUrl = "https://accounts.zoho.com.au/developerconsole";
		}else if(domain =='in'){
			domainName = "https://extensions.zoho.in/plugin/wordpress/callback";
			accountUrl = "https://accounts.zoho.in/developerconsole";
		}else if(domain =='jp'){
			domainName = "https://extensions.zoho.jp/plugin/wordpress/callback";
			accountUrl = "https://accounts.zoho.jp/developerconsole";
		}else{
        domainName = "https://extensions.zoho.com/plugin/wordpress/callback";
        accountUrl = "https://accounts.zoho.com/developerconsole";
    }
    jQuery('#zcrm_integ_authorization_uri').val(domainName);
    jQuery('#zohocomaccount a').attr('href',accountUrl);
    var authorization_uri  = jQuery('#zcrm_integ_authorization_uri').val();
    var stateurl = jQuery('#stateurl').val();
    var data = {
        'action': 'zcf_updateTitles1',
        'clientID': clientID,
        'clientSecKey':clientSecKey,
        'domain':domain,
        'authorization_uri':authorization_uri,

    };
    data.nonce = ajax_object.zoho_crm_forms_nonce[data.action];
    if(clientID !='' && clientSecKey !='' && domain !=''){
        jQuery.ajax({
        type: 'POST',
        'siteurl': siteurl,
        url: ajaxurl,
        data:data,
        success: function (data) {
            var data = data.replace("</div>\n</div>\n</div>\n", '');
            var url = "https://accounts.zoho."+domain+"/oauth/v2/auth?response_type=code&client_id="+clientID+"&scope=ZohoCRM.modules.ALL,ZohoCRM.settings.ALL,ZohoCRM.users.ALL&redirect_uri="+authorization_uri+"&access_type=offline&prompt=consent&state="+stateurl;
            jQuery('#zcrm_integ_submit').attr('href',url);
            jQuery('#zcrm_integ_submit').attr('disabled',false);

        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
    }
}
