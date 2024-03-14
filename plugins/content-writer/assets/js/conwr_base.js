
jQuery(document).ready(function () {
    jQuery(".stcon-tabs-menu li span").click(function (event) {
        event.preventDefault();
        document.cookie = "selectedSCTab=" + (jQuery(this).parent().index() + 1);
        jQuery(this).parent().addClass("current");
        jQuery(this).parent().siblings().removeClass("current");

        var tab = jQuery(this).attr("id");
        jQuery(".stcon-tab-content").not("#div" + tab).css("display", "none");
        jQuery("#div" + tab).show();
    });

    var selectedSCTab = getSCCookie("selectedSCTab");
    if (selectedSCTab != "") {
        jQuery(".stcon-tabs-menu li:eq(" + (parseInt(selectedSCTab) - 1) + ") span").click();
    } else {
        jQuery(".stcon-tabs-menu li:eq(0) span").click();
        jQuery(".stcon-tabs-menu li:eq(0) span").parent().addClass("current");
    }
});

// jQuery(document).ready(function () {
//     InitEditorContent();
// });

function InitEditorContent() {
    if (jQuery("#content").length > 0 && jQuery("#content").css("display") != "none") {
        var Content = jQuery('#content').val(); // Get the editor content (html)

        // jQuery('#content').on('input load', function (e) {
        //     SetWriterInfoDetails(Content);
        // });

        // jQuery('#content').on('input keyup', function (e) {
        //     SetWriterInfoDetails(Content);
        // });

        // jQuery('#content').on('load', function (e) {
        //     SetWriterInfoDetails(Content, 1);
        // });

        // jQuery('#content').on('keyup', function (e) {
        //     SetWriterInfoDetails(Content, 2);
        // });
    }
}

function getSCCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function ShowHideWriterPopup(show, tabIndex) {
    if (show) {
        jQuery(".conwr-modal-background").show();
        jQuery(".conwr-writer-popup-wrapper").show();

        jQuery(".popup-tab a:eq(" + tabIndex + ")").click();
    }
    else {
        jQuery(".conwr-modal-background").hide();
        jQuery(".conwr-writer-popup-wrapper").hide();
    }
}

function OpenWriterPopupTab(tabButton, tabName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("popup-tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("popup-tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    //NOTE: we show only first tab because all tabs are the same
    //document.getElementById(tabName).style.display = "block";
    document.getElementById("Message").style.display = "block";

    tabButton.className += " active";
}

function SubmitWriterFeedback(PostID) {
    var SubmitType = jQuery("a.popup-tablinks.active")[0].innerHTML;
    var Comment = jQuery("#txtComments").val();

    if (Comment == undefined || Comment == "") {
        displayNote(true, "Please enter some comment.", true);
    }
    else {
        showLoader();

        setTimeout(function () {
            jQuery.ajax({
                method: 'GET',
                url: "https://api.steadycontent.com/Service.asmx/SubmitWriterFeedback?callback=SubmitWriterFeedbackCallback",
                data: { PostID: PostID, Comment: Comment, SubmitType: SubmitType },
                contentType: "application/json",
                dataType: 'jsonp'
            });
        }, 500);
    }
}

function SubmitWriterFeedbackCallback(data) {
    if (data && data.Status == "ok") {
        displayNote(true, "Feedback has been successfully sent.", false);
        jQuery("#txtComments").val("");

        var SubmitType = jQuery("a.popup-tablinks.active")[0].innerHTML;

        if (SubmitType == "Problem") {
            jQuery("#spanFlaggedAction")[0].innerHTML = '<span title="Writer is already flagged"><i class="material-icons md-20" style="color: #a0a0a0;">flag</i></span>';
        }
        else if (SubmitType == "Favorite" && data.Description == "true") {
            jQuery("#spanFavoriteAction")[0].innerHTML = '<span title="Writer is already favorited"><i class="fa fa-heart" style="color: #a0a0a0;"></i></span>';
        }

        setTimeout(function () {
            ShowHideWriterPopup(false, 0);
        }, 3000);
    }
    else if (data && data.Status == "error") {
        displayNote(true, data.Description, true);
        console.log(data);
    }
    else {
        displayNote(true, "Oops, something went wrong!", true);
        console.log(data);
    }
}

function displayNote(show, message, error) {
    if (error) {
        jQuery(".popup-error").css("background-color", "#ff0000");
        hideLoader();
    }
    else {
        jQuery(".popup-error").css("background-color", "#2ccb4e");
    }

    if (show) {
        jQuery(".popup-error").fadeIn("medium");
        jQuery(".popup-error").html(message);
        hideLoader();

        setTimeout(function () {
            jQuery(".popup-error").fadeOut("medium");
        }, 5000);
    }
    else {
        jQuery(".popup-error").fadeOut("medium");
        jQuery(".popup-error").html("");
    }
}

function showLoader() {
    jQuery(".popup-loader").show();
    jQuery(".submit-wf-button").hide();
    jQuery(".cancel-button").hide();
}

function hideLoader() {
    jQuery(".popup-loader").hide();
    jQuery(".submit-wf-button").show();
    jQuery(".cancel-button").show();
}