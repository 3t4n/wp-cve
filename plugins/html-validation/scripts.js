// JavaScript Document

jQuery(function ($) { // start jquery


    /**************************************
    // change settings save button state
    *************************************/
    $("#html_validation_options :input").change(function () {
        if ($(this).attr('id') !== 'html_validation_pro_license_key')
            $("#html_validation_settings_save").css("border", "4px solid red");
    });


    /**************************************
    // hide notices
    *************************************/
    $(document).on("click", '.htmlvalidationhidenotices', function () {
        $('.html_validation_report_messages').hide();
    });


    /*****************************************    
    // toggle ignore options
    ******************************************/
    $("body").on("click", ".html-validation-ignore-options-click", function () {
        $(this).next("span").show().css("display", "inline-block");
        return false;
    });

    /******************************************    
    // recheck url
    ******************************************/
    $(document).on("click", 'a.html_validation_recheck', function () {
        var linkid = $(this).data('linkid');

        $('.html_validation_report_messages').html(htmlvalidateVariables['wait']);

        var seperator = '&';
        var resturl = htmlvalidateVariables['resturl'];

        if (resturl.search('/wp-json/') > 0) seperator = '?';
        event.preventDefault();
        $.ajax({
            url: htmlvalidateVariables['resturl'] + 'html_validation/v1/recheck/' + seperator + '_wpnonce=' + htmlvalidateVariables['nonce'] + '&linkid=' + linkid,
            dataType: 'html',
            async: true,
            error: function (e) {
                console.log('failed');
                return true;
            },
            success: function (data) {

                html_validation_refresh_report(htmlvalidateVariables['recheck']);
            }
        });
    });


    /******************************************    
    // ignore error
    ******************************************/
    $(document).on("click", 'a.html_validation_ignore', function () {
        var errorid = $(this).data('errorid');
        var state = $(this).data('state');
        $('.html_validation_report_messages').html(htmlvalidateVariables['wait']);
        var seperator = '&';
        var resturl = htmlvalidateVariables['resturl'];

        if (resturl.search('/wp-json/') > 0) seperator = '?';
        event.preventDefault();
        $.ajax({
            url: htmlvalidateVariables['resturl'] + 'html_validation/v1/ignoreError/' + seperator + '_wpnonce=' + htmlvalidateVariables['nonce'] + '&errorid=' + errorid + '&state=' + state,
            dataType: 'html',
            async: true,
            error: function (e) {
                console.log('failed');
                return true;
            },
            success: function (data) {

                if (state === 1) {
                    html_validation_refresh_report(htmlvalidateVariables['ignore']);
                }
                else {
                    html_validation_refresh_report(htmlvalidateVariables['ignoreX']);
                }

            }
        });
    });


    /******************************************    
    // ignore link
    ******************************************/
    $(document).on("click", '.html_validation_ignore_link', function () {
        var linkid = $(this).data('linkid');
        var state = $(this).data('state');
        $('.html_validation_report_messages').html(htmlvalidateVariables['wait']);
        var seperator = '&';
        var resturl = htmlvalidateVariables['resturl'];

        if (resturl.search('/wp-json/') > 0) seperator = '?';
        if ($(this).is("a")) {
            event.preventDefault();
        }
        $.ajax({
            url: htmlvalidateVariables['resturl'] + 'html_validation/v1/ignoreLink/' + seperator + '_wpnonce=' + htmlvalidateVariables['nonce'] + '&linkid=' + linkid + '&state=' + state,
            dataType: 'html',
            async: true,
            error: function (e) {
                console.log('failed');
                return true;
            },
            success: function (data) {

                html_validation_refresh_report(htmlvalidateVariables['ignoreLink']);

            }
        });
    });



    /******************************************    
    // ignore duplicate error
    ******************************************/
    $(document).on("click", 'a.html_validation_ignore_duplicates', function () {
        var errorid = $(this).data('errorid');
        var state = $(this).data('state');
        $('.html_validation_report_messages').html(htmlvalidateVariables['wait']);
        var seperator = '&';
        var resturl = htmlvalidateVariables['resturl'];

        if (resturl.search('/wp-json/') > 0) seperator = '?';
        event.preventDefault();
        $.ajax({
            url: htmlvalidateVariables['resturl'] + 'html_validation/v1/ignoreDuplicates/' + seperator + '_wpnonce=' + htmlvalidateVariables['nonce'] + '&errorid=' + errorid + '&state=' + state,
            dataType: 'html',
            async: true,
            error: function (e) {
                console.log('failed');
                return true;
            },
            success: function (data) {
                if (state === 1) {
                    html_validation_refresh_report(htmlvalidateVariables['ignoreDups']);
                }
                else {
                    html_validation_refresh_report(htmlvalidateVariables['ignoreDupsX']);
                }

            }
        });
    });


    /******************************************    
    // refresh report
    ******************************************/
    function html_validation_refresh_report(message) {

        var seperator = '&';
        message = '<button aria-label="hide notices" class="htmlvalidationhidenotices"><i class="fas fa-times" aria-hidden="true"></i></button><i class="fas fa-info-circle" aria-hidden="true"></i>' + message;
        var resturl = htmlvalidateVariables['resturl'];
        var view = html_validation_getParameterByName('view');
        var errortype = html_validation_getParameterByName('errortype');
        var cpage = html_validation_getParameterByName('cpage');
        var type = html_validation_getParameterByName('type');
        var excludedups = html_validation_getParameterByName('excludedups');
        var validate = html_validation_getParameterByName('validate');
        var linkid = html_validation_getParameterByName('linkid');
        var adamarker = html_validation_getParameterByName('adamarker');
        if (resturl.search('/wp-json/') > 0) seperator = '?';
        $.ajax({
            url: htmlvalidateVariables['resturl'] + 'html_validation/v1/refresh/' + seperator + '_wpnonce=' + htmlvalidateVariables['nonce'] + '&view=' + view + '&errortype=' + errortype + '&cpage=' + cpage + '&type=' + type + '&excludedups=' + excludedups + '&validate=' + validate + '&linkid=' + linkid + '&adamarker=' + adamarker,
            dataType: 'html',
            async: true,
            error: function (e) {
                return true;
            },
            success: function (data) {
                data = data.replace("null", "");
                $('.html_validation_report').html(data);
                $('.html_validation_report_messages').html(message);
            }
        });
    };


    /*************************************************
     // get url params                         
    **************************************************/
    function html_validation_getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);

        if (name == 'cpage' && !results) return 1;
        if (!results) return '';
        if (!results[2]) return '';

        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }


    /*************************************************
    settings page tab functions                             
    **************************************************/
    // settings tabs 							 					 
    $(function () {
        if (document.getElementById("abb-tabs") !== null) {
            $('#abb-tabs').tabs();

            if (html_validation_getCookie('html_validationsettingslastTab') != 'undefined') {
                $('.ui-tabs-nav a[href="' + html_validation_getCookie('html_validationsettingslastTab') + '"]').trigger('click');
            }
            $("body").on("click", ".ui-tabs-anchor", function () {
                console.log('link clicked' + $(this).attr("href"));
                var addressValue = $(this).attr("href");
                document.cookie = 'html_validationsettingslastTab=' + addressValue;
                $('#abb-tabs li').attr('tabindex', '0');
            });

            $("body").on("keydown", "#abb-tabs li", function (event) {
                $('#abb-tabs li').attr('tabindex', '0');
            });

            $('#abb-tabs li').attr('tabindex', '0');
        }
    });


    /*************************************************
    cookies                            
    **************************************************/
    function html_validation_getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
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




}); // end jquery ready