jQuery(document).ready(function () {
    jQuery(".uf_of_type_ckbox").each(function(){
        var ckCheck = false;
        var groupName = jQuery(this).attr("ckbox-group-name");
        if(jQuery("input."+groupName+":checked").length != 0){
            ckCheck = true;
        }
        if (ckCheck == true) {
            var el = document.getElementsByClassName(groupName);
            for (i = 0; i < el.length; i++) {
                jQuery(el[i]).attr("data-validation", "");
            }
        }
    });
    // Call block for all the #
    jQuery("body").delegate('a[href="#"]', "click", function (event) {
        event.preventDefault();
    });
    // Check boxess multi-selection
    jQuery('#selectall').click(function (event) {
        if (this.checked) {
            jQuery('.jsjobs-cb').each(function () {
                this.checked = true;
            });
        } else {
            jQuery('.jsjobs-cb').each(function () {
                this.checked = false;
            });
        }
    });
    //submit form with anchor
    jQuery("a.multioperation").click(function (e) {
        e.preventDefault();
        var total = jQuery('.jsjobs-cb:checked').size();
        if (total > 0) {
            var task = jQuery(this).attr('data-for');
            if (task.toLowerCase().indexOf("remove") >= 0) {
                if (confirmdelete(jQuery(this).attr('confirmmessage')) == true) {
                    jQuery("input#task").val(task);
                    jQuery("form#jsjobs-list-form").submit();
                }
            } else {
                var wpnoncecheck = jQuery(this).attr('data-for-wpnonce');
                jQuery("input#_wpnonce").val(wpnoncecheck);
                jQuery("input#task").val(task);
                jQuery("form#jsjobs-list-form").submit();
            }
        } else {
            var message = jQuery(this).attr('message');
            alert(message);
        }
    });
    jsjobsPopupLink();
});

function jsjobsPopupLink() {
    var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

    var target_ancher = "a.jsjobs-popup";
    if (null != themecall) {
        target_ancher = "a." + common.theme_chk_prefix + "-modal-credit-action-btn";
    }
    jQuery(target_ancher).click(function (e) {
        //      var link = jQuery(target_ancher).attr('href');

        //        e.preventDefault();

    });
}

function confirmdelete(message) {
    if (confirm(message) == true) {
        return true;
    } else {
        return false;
    }
}

function jsjobsClosePopup() {
    var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

    var popup_div = "";
    var bkpop_div = "";
    if (null != themecall) {
        popup_div = "div#" + common.theme_chk_prefix + "-popup";
        bkpop_div = "div#" + common.theme_chk_prefix + "-popup-background";
    } else {
        popup_div = "div#jsjobs-popup";
        bkpop_div = "div#jsjobs-popup-background";
    }
    jQuery(popup_div).slideUp();
    jQuery(bkpop_div).hide();
    setTimeout(function () {
        jQuery(popup_div).html(' ');
    }, 350);
}

function getApplyNowByJobid(jobid, pageid) {
    var themecall = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;


    if (null != themecall) {
        jQuery('div#' + common.theme_chk_prefix + '-popup-background').show();
    } else {
        jQuery("div#jsjob-popup-background").show();
    }

    var permalink = jQuery('div#jsjobs_permalink').html();
    jQuery.post(common.ajaxurl, { action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'getApplyNowByJobid', jobid: jobid, jobpermalink: permalink, jsjobs_pageid: pageid, themecall: themecall , wpnoncecheck:common.wp_jm_nonce}, function (data) {
        if (data) {
            var d = (data);
			if (null != themecall) {
			   jQuery("div#" + common.theme_chk_prefix + "-popup").html(decodeURIComponent(escape(d)));
			   jQuery("div#" + common.theme_chk_prefix + "-popup").slideDown("slow");
			} else {
			   jQuery("div#jsjobs-listpopup span.popup-title span.title").text('Apply Now');
			   jQuery("div#jsjobs-listpopup div.jsjob-contentarea").html(decodeURIComponent(escape(d)));
			   jQuery("div#jsjobs-listpopup").slideDown("slow");
			}
        }
    });
    return;
}

function jobApply(jobid) {
    var themecall = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

    task = "jobapply";
    if (null != themecall) {
        jQuery('div#' + common.theme_chk_prefix + '-popup').prepend('<div class="' + common.theme_chk_prefix + '-loading"></div>');
        task = "jobapplyjobmanager";
    } else {
        jQuery('div.jsjob-contentarea').find('div.quickviewrow').prepend('<div class="transparentbg loading"></div>');
    }
    var cvid = jQuery('select#cvid').val();
    var coverletterid = jQuery('select#coverletterid').val();
    jQuery.post(common.ajaxurl, { action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: task, jobid: jobid, cvid: cvid, coverletterid: coverletterid, themecall: themecall , wpnoncecheck:common.wp_jm_nonce}, function (data) {
        if (data) {
            if (null != themecall) {
                jQuery('div#' + common.theme_chk_prefix + '-popup').find("div." + common.theme_chk_prefix + "-loading").remove();
                //jQuery("div."+common.theme_chk_prefix+"-modal-wrp").find("div."+common.theme_chk_prefix+"-modal-data-wrp").append(data);
                jQuery("div." + common.theme_chk_prefix + "-modal-wrp").append(data);
            } else {
                jQuery("div.quickviewbutton").html(data); //retuen value
                jQuery("div.transparentbg").removeClass('loading');
            }
        }
    });
}

function getDataForDepandantFieldResume(parentf, childf, type) {
    var section = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    var sectionid = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
    var themecall = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;

    var val;
    if (type == 1) {
        if (1 != section) {
            val = jQuery("select#" + parentf + sectionid).val();
        } else if (1 == section) {
            val = jQuery("select#" + parentf).val();
        }
    } else if (type == 2) {
        if (1 != section) {
            val = jQuery("input[name=sec_" + section + "\\[" + parentf + "\\]\\[" + sectionid + "\\]]:checked").val();
        } else if (1 == section) {
            val = jQuery("input[name=sec_" + section + "\\[" + parentf + "\\]]:checked").val();
        }
    }
    jQuery.post(common.ajaxurl, { action: 'jsjobs_ajax', jsjobsme: 'fieldordering', task: 'DataForDepandantFieldResume', fvalue: val, child: childf, section: section, sectionid: sectionid, type: type, themecall: themecall , wpnoncecheck:common.wp_jm_nonce}, function (data) {
        if (data) {

            var d = (data);
            /*console.log(d);
            console.log(section);*/
            if (1 != section) {
                //console.log(childf+sectionid);
                jQuery("select#" + childf + sectionid).replaceWith(d);
            } else {
                jQuery("select#" + childf).replaceWith(d);
            }
        }
    });
}

function getDataForDepandantField(parentf, childf, type) {
    var section = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    var sectionid = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
    var themecall = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;

    if (type == 1) {
        var val = jQuery("select#" + parentf).val();
    } else if (type == 2) {
        if (section == 1) {
            var val = jQuery("input[name=sec_" + section + "\\[" + parentf + "\\]]:checked").val();
        } else {
            var val = jQuery("input[name=" + parentf + "]:checked").val();
        }
    }
    jQuery.post(common.ajaxurl, { action: 'jsjobs_ajax', jsjobsme: 'fieldordering', task: 'DataForDepandantField', fvalue: val, child: childf, themecall: themecall , wpnoncecheck:common.wp_jm_nonce}, function (data) {
        if (data) {

            var d = (data);
            jQuery("select#" + childf).replaceWith(d);
        }
    });
}
function draw() {
    var objects = document.getElementsByClassName('goldjob');
    for (var i = 0; i < objects.length; i++) {
        var canvas = objects[i];
        if (canvas.getContext) {
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = "#FFFFFF";
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(10, 10);
            ctx.lineTo(0, 20);
            ctx.fill();
        }
    }
}

window.onload = function () {
    draw();
};

function fillSpaces(string) {
    string = string.replace(" ", "%20");
    return string;
}

function showloginpopupjobmanager() {
    jQuery("a." + common.theme_chk_prefix + "-tp-link").click();
    return;
}

function showloginpopupjobhub() {
    jQuery("a." + common.theme_chk_prefix + "-tp-link").click();
    return;
}

function deRequireUfCheckbox(elClass) {
    var el = document.getElementsByClassName(elClass);
    var atLeastOneChecked = false; //at least one cb is checked
    for (i = 0; i < el.length; i++) {
        if (el[i].checked === true) {
            atLeastOneChecked = true;
        }
    }

    if (atLeastOneChecked === true) {
        for (i = 0; i < el.length; i++) {
            jQuery(el[i]).attr("data-validation", "");
        }
    } else {
        for (i = 0; i < el.length; i++) {
            jQuery(el[i]).attr("data-validation", "required");
        }
    }
}
function JSJOBSDecodeHTML(html) {
    var txt = document.createElement('textarea');
    txt.innerHTML = html;
    return txt.value;
}
