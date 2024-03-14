var wizard = {
    
    features : new Array("cURL", "oAuth", "php", "Wordpress", "Flattr"),
    
    ready : function () {
        wizard.returncheck();
    },
    
    toggleToActive : function () {
        jQuery(".flattr-wrap span.inactive").toggleClass("inactive active");
        jQuery(".flattr-wrap span.active").html('<a href="#" id="start-flattr-wizard">Start Wizard</a>');
        jQuery("#start-flattr-wizard").live("click", function(e) {
            e.preventDefault();
            wizard.start();
        });
    },
    
    toggleToInctive : function () {
        jQuery(".flattr-wrap span.active").toggleClass("active inactive");
        jQuery(".flattr-wrap span.inactive").html('Start Wizard');
    },
    
    start : function () {
        jQuery(".flattr-wrap div#dialog").dialog({
            modal: true,
            height: 250,
            resizable: false,
            buttons: [
                {
                    text: "Check",
                    click: function() {
                                       wizard.runChecks();
                                   },
                    id : "check-button"
                },
                {
                    text: "Cancel",
                    click: function () {
                        wizard.toggleToInctive();
                        jQuery(this).dialog("close");
                    }
                }
            ]
        });
        jQuery(".flattr-wrap div#dialog").dialog("open");
    },
    
    runChecks : function () {
        wizard.clearDialog();
        var text = 'Running server capabilities check<ul id="checklist">';
        for (x in wizard.features) {
            var feature = wizard.features[x];
            text += '<li>checking '+feature+' <span id="check-'+feature+'" class="flattr-server-check">...</span></li>';
        }
        text += '</ul>';
        
        wizard.write(text);
        
        for (x in wizard.features) {
            feature = wizard.features[x];
            wizard.check(feature);
        }
    },
    
    check : function (feature) {
        wizard.disableButton();
        
        jQuery.get("admin.php", {q : feature, flattrJAX : true , page : "flattr/flattr.php"}, function(data) {
            var testclass = data.result;
            jQuery("#check-"+data.feature).addClass(testclass);
            jQuery("#check-"+data.feature).html(data.text);
            
            var enable = true;
            jQuery(".flattr-server-check").each(function () {
                if (jQuery(this).hasClass("failed")) {
                    enable = false;
                }
            });
            if (enable) {
                wizard.enableButton();
                wizard.step2();
            }
        }, 'json');
    },
    
    step2 : function () {
        jQuery("#check-button").html("Continue");
        jQuery("#check-button").unbind("click");
        jQuery("#check-button").click(function(){
            wizard.disableButton();
            wizard.clearDialog();
            jQuery(".ui-dialog").animate({
                left: '-=125',
                width: '+=250',
                top: '-=25',
                height: '+=85'
            }, "fast", function() {
                    jQuery("div#dialog").css("height", 240);
                    wizard.enableButton();
                }
            );
            wizard.write('<ol><li>Login to your Flattr Account at <a href="https://flattr.com/" target="_blank">flattr.com</a></li>'+
                         '<li>To get your personal Flattr APP Key and APP Secret you need to <a href="https://flattr.com/apps/new" target="_blank">register your blog</a> as Flattr app.</li>'+
                         "<li>Choose reasonable values for <em>Application name</em>, <em>Application website</em> and <em>Application description</em></li>"+
                         "<li>It is mandatory to <strong>select BROWSER application type!</strong> This plugin will not work if CLIENT is selected.</li>"+
                         '<li>Your callback domain must be the URL to this site.<br><input readonly="readonly" size="70" value="'+ window.location +'"></li>'+
                         "<li>Click Continue.</li>"+
                         "</ol>"
            );
            jQuery("input").each(function() {
                if (jQuery(this).attr("readonly")=="readonly")
                    jQuery(this).click(function(){
                        jQuery(this).select();
                    });
            });
            jQuery("#check-button").unbind("click");
            jQuery("#check-button").click(function(){
                wizard.disableButton();
                jQuery("#check-button").unbind("click");
                wizard.clearDialog();
                wizard.write("<p>Copy 'n Paste your APP Key and APP Secret in the corresponding fields below.</p>"+
                             '<table class="form-table">' +
                             '<tbody><tr valign="top">' +
                             '<th scope="row">APP_KEY</th>' + 
                             '<td><input size="70" name="flattrss_api_key" id="flattrss_api_key" value="" autocomplete="off"></td>' +
                             '</tr>' +
                             '<tr valign="top">' +
                             '<th scope="row">APP_SECRET</th>' +
                             '<td><input size="70" name="flattrss_api_secret" id="flattrss_api_secret" value="" autocomplete="off"></td>' +
                             '</tr>' +
                             '</tbody></table>'
                );
                jQuery("div#dialog").find("input").change(function() {
                    wizard.step2b();
                });
                jQuery("div#dialog").find("input").keyup(function() {
                    wizard.step2b();
                });
                jQuery("div#dialog").find("input").select(function() {
                    wizard.step2b();
                });
                jQuery("div#dialog").find("input").mouseup(function() {
                    wizard.step2b();
                });
            });
        });
    },
    
    step2b : function () {
        var step3 = true;
        jQuery("div#dialog").find("input").each(function() {
            if (jQuery(this).attr("value") == "") {
                step3 = false;
            }
        })
        
        if (step3) {
            jQuery("#check-button").unbind("click");
            wizard.enableButton();
            jQuery("#check-button").click(function() {
                wizard.step3();
            });
        } else {
            wizard.disableButton();
        }
    },
    
    step3 : function () {
        wizard.disableButton();
        jQuery("#check-button").html("validating...");
        
        jQuery.get("admin.php", {flattrss_api_key : jQuery("#flattrss_api_key").attr("value"),
                                  flattrss_api_secret : jQuery("#flattrss_api_secret").attr("value"),
                                  flattrJAX : true , page : "flattr/flattr.php"}, function(data) {
            if (data.result == 0) {
                window.location = data.result_text;
            } else {
                jQuery("#check-button").html("Continue");
                wizard.enableButton();
            }
        }, 'json');
    },
    
    clearDialog : function () {
        wizard.write("");
    },
    
    write : function (String) {
        jQuery("div#dialog").html(String);
    },
    
    append : function (String) {
        wizard.write(jQuery("div#dialog").html()+String)
    },
    
    disableButton : function () {
        jQuery("#check-button").attr("disabled", "disabled");
    },
    
    enableButton : function () {
        jQuery("#check-button").removeAttr("disabled");
    },
    
    returncheck : function (callback) {
        var query = window.location.search.substring(1);
        var nvPairs = query.split("&");
        var param = new Array();
        
        for (i = 0; i < nvPairs.length; i++) {
             var kv = nvPairs[i].split("=");
             param[kv[0]] = kv[1];
        }
        if (typeof(param["error"])!="undefined" && typeof(param["error_description"])!="undefined") {
            jQuery(".flattr-wrap div#dialog").dialog({
                modal: true,
                height: 250,
                buttons: [
                    {
                        text: "Cancel",
                        click: function () {
                            jQuery(this).dialog("close");
                            jQuery(this).unbind("dialog");
                        }
                    }
                ]
            });
            jQuery(".flattr-wrap div#dialog").dialog("open");
            wizard.write('<h3>'+wizard.ucwords(param["error"].replace(/\_/g, " "))+"</h3>"+
                         '<p>'+param["error_description"].replace(/\+/g, " ")+'</p>'
            );
        } else if (typeof(param["code"])!="undefined") {
            jQuery(".flattr-wrap div#dialog").dialog({
                modal: true,
                height: 320,
                buttons: [
                    {
                        text: "Ok",
                        click: function () {
                            jQuery(this).dialog("close");
                            jQuery(this).unbind("dialog");
                        }
                    }
                ]
            });
            jQuery(".flattr-wrap div#dialog").dialog("open");
            wizard.disableButton();
            wizard.clearDialog();
            jQuery.get("admin.php", {code : param["code"], flattrJAX : true , page : "flattr/flattr.php"}, function(data) {
                wizard.enableButton();
                wizard.write(data.result_text);
            }, 'json');
            wizard.toggleToActive();
        } else {
            wizard.toggleToActive();
        }
    },
    
    ucwords : function  (str) {
        return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
            return $1.toUpperCase();
        });
    }
};

jQuery(document).ready(function() {
    wizard.ready();
});