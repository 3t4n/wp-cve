jQuery(document).ready(function ($) {
    // console.log(jQuery(window).width());
    $('ul.hsb_tabs li:first').addClass('active');
    $('.block article').hide();
    $('.block article:first').show();
    $('ul.hsb_tabs li').on('click', function () {
        //   alert('click');
        $('ul.hsb_tabs li').removeClass('active');
        $(this).addClass('active')
        $('.block article').hide();
        var activeTab = $(this).find('a').attr('href');
        $(activeTab).show();
        return false;
    });

    var empty = false;
    $('#hsb_opt_access_token').each(function () {
        if ($(this).val().length == 0) {
            empty = true;
        }
    });
    //afi api key not enetered disable "Post Now"
    if (empty) {
        $('#submitNow').attr('disabled', 'disabled');

    } else {
        $('#submitNow').removeAttr('disabled');
        hsb_setBufferIds();
    }
});

function hsb_setBufferIds() {
    var acntids = document.getElementById("acntids").value;
    if (acntids) {
        var arracntids = acntids.split(",");
        var  arracntids_size =  arracntids.length;

        if (arracntids_size < 2)
        {
            for (var i = 0; i < arracntids_size; i++) {
                document.getElementById(arracntids[i]).checked = true;
            }
        }
        else{
            for (var i = 0; i < 2; i++) {
                document.getElementById(arracntids[i]).checked = true;
            }
        }
    }
}

function hsb_manageacntid(ctrl, id) {
    var acntids = document.getElementById("acntids").value;
    if (ctrl.checked) {
        acntids = hsb_addId(acntids, id);
    }
    else {
        acntids = hsb_removeId(acntids, id);
    }
    document.getElementById("acntids").value = acntids;
}

function hsb_accnt_checked() {
    if (jQuery(".porofile_check:checked").length == 0) {
        return false;
    }
    else {
        return true;
    }
}


//For checking if a string is empty, null or undefined I use:
function hsb_isEmpty(str) {
    return (!str || 0 === str.length);
}

//For checking if a string is blank, null or undefined I use:
function isBlank(str) {
    return (!str || /^\s*$/.test(str));
}

//For checking if a string is blank or contains only white-space:
String.prototype.hsb_isEmpty = function () {
    return (this.length === 0 || !this.hsb_trim());
};

function hsb_validate() {

    if (hsb_trim(document.getElementById("hsb_opt_interval").value) != "" && !hsb_isNumber(hsb_trim(document.getElementById("hsb_opt_interval").value))) {
        alert("Enter only numeric in Minimum interval between post");
        document.getElementById("hsb_opt_interval").focus();
        return false;
    }

    if (hsb_trim(document.getElementById("hsb_opt_no_of_post").value) != "" && !hsb_isNumber(hsb_trim(document.getElementById("hsb_opt_no_of_post").value))) {
        alert("Enter only numeric in Number Of Posts To Post");
        document.getElementById("hsb_opt_no_of_post").focus();
        return false;
    }

    if (hsb_trim(document.getElementById("hsb_opt_age_limit").value) != "" && !hsb_isNumber(hsb_trim(document.getElementById("hsb_opt_age_limit").value))) {
        alert("Enter only numeric in Minimum age of post");
        document.getElementById("hsb_opt_age_limit").focus();
        return false;
    }
    if (hsb_trim(document.getElementById("hsb_opt_max_age_limit").value) != "" && !hsb_isNumber(hsb_trim(document.getElementById("hsb_opt_max_age_limit").value))) {
        alert("Enter only numeric in Maximum age of post");
        document.getElementById("hsb_opt_max_age_limit").focus();
        return false;
    }
    if (hsb_trim(document.getElementById("hsb_opt_max_age_limit").value) != "" && hsb_trim(document.getElementById("hsb_opt_max_age_limit").value) != 0) {
        if (eval(document.getElementById("hsb_opt_age_limit").value) > eval(document.getElementById("hsb_opt_max_age_limit").value)) {
            alert("Post max age limit cannot be less than Post min age iimit");
            document.getElementById("hsb_opt_age_limit").focus();
            return false;
        }
    }
}

function hsb_trim(stringToTrim) {
    return stringToTrim.replace(/^\s+|\s+$/g, "");
}


function hsb_isNumber(val) {
    if (isNaN(val)) {
        return false;
    }
    else {
        return true;
    }
}

function hsb_setFormAction() {
    var loc = location.href;
    if (location.href.indexOf("&") > 0) {
        location.href.substring(0, location.href.lastIndexOf("&"));
    }
    document.getElementById("hsb_opt").action = loc;
}

function hsb_resetSettings() {
    var re = confirm("This will reset all the setting, including your account, omitted categories, and your excluded posts. Are you sure you want to reset all the settings?");
    if (re == true) {
        document.getElementById("hsb_opt").action = location.href;
        return true;
    }
    else {
        return false;
    }
}

function hsb_removeId(list, value) {
    list = list.split(",");
    if (list.indexOf(value) != -1)
        list.splice(list.indexOf(value), 1);
    var newlist = list.join(",");

    if (newlist.substring(0, 1) == ",")
        newlist = newlist.substring(1, newlist.length);

    if (newlist.substring(newlist.length - 1, 1) == ",")
        newlist = newlist.substring(0, newlist.length - 1);

    return newlist;
}

function hsb_addId(list, value) {
    list = list.split(",");
    if (list.indexOf(value) == -1)
        list.push(value);
    newlist = list.join(",");
    if (newlist.substring(0, 1) == ",")
        newlist = newlist.substring(1, newlist.length);

    if (newlist.substring(newlist.length - 1, 1) == ",")
        newlist = newlist.substring(0, newlist.length - 1);

    return newlist;
}


function hsb_showURLOptions() {
    if (document.getElementById("hsb_opt_include_link").value == "true") {
        document.getElementById("urloptions").style.display = "block";
    }
    else {
        document.getElementById("urloptions").style.display = "none";
    }
}

function showCustomField() {
    if (document.getElementById("hsb_opt_custom_url_option").checked) {
        document.getElementById("customurl").style.display = "block";
    }
    else {
        document.getElementById("customurl").style.display = "none";
    }
}

hsb_setFormAction();

