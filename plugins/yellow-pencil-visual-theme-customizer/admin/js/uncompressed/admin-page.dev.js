;(function($) {

    "use strict";

    window.define = window.define || ace.define;
    ace.config.set("basePath",window.aceEditorBase);
    ace.require("ace/ext/language_tools");

    // global checked all
    $("#global-choose-all").click(function(){

        if($(this).prop('checked')){
            $(".wyp-global-group ul li .wyp-checkbox input").prop('checked', true);
        }else{
            $(".wyp-global-group ul li .wyp-checkbox input").prop('checked', false);
        }

    });

    // template checked all
    $("#template-choose-all").click(function(){

        if($(this).prop('checked')){
            $(".wyp-template-group ul li .wyp-checkbox input").prop('checked', true);
        }else{
            $(".wyp-template-group ul li .wyp-checkbox input").prop('checked', false);
        }

    });

    // single checked all
    $("#single-choose-all").click(function(){

        if($(this).prop('checked')){
            $(".wyp-single-group ul li .wyp-checkbox input").prop('checked', true);
        }else{
            $(".wyp-single-group ul li .wyp-checkbox input").prop('checked', false);
        }

    });

    // animation checked all
    $("#animation-choose-all").click(function(){

        if($(this).prop('checked')){
            $(".wyp-animations-code-group > ul li .wyp-checkbox input").prop('checked', true);
        }else{
            $(".wyp-animations-code-group > ul li .wyp-checkbox input").prop('checked', false);
        }

    });


    $(".wyp-code-group li").click(function(e){

        if($(e.target)[0].tagName == "LI" || $(e.target).hasClass("wyp-edited-page-title")){

            var tab = $(this);

            // Can't open anymore
            if(tab.hasClass("wyp-data-deleted")){
                return false;
            }

            var editorDiv = tab.next(".wyp-inline-css");
            tab.toggleClass("active");
            editorDiv.toggleClass("active");
            editorDiv.next("li").toggleClass("wyp-border-top");

            // Set ace editor on first open
            if(editorDiv.hasClass("ace_editor") == false){

                // Set editor
                var editor = ace.edit(editorDiv.attr("id"));
                editor.setTheme("ace/theme/dawn");
                editor.getSession().setMode("ace/mode/css");
                editor.getSession().setUseWorker(false);

                // enable autocompletion and snippets
                editor.setOptions({
                    enableBasicAutocompletion: true,
                    enableSnippets: false,
                    enableLiveAutocompletion: true
                });

                // Set font size to editor
                editor.setOptions({
                    fontSize: "14px"
                });

                // Options
                editor.$blockScrolling = Infinity;
                editor.setShowPrintMargin(false);

                // No editable.
                editor.setReadOnly(true);

            }

        }

    });
    
    
    $(".wyp-button-reset").click(function(){

        var el = $(this);

        if(confirm("Are you sure to want to reset defined CSS comments and all plugin options? (This action does not delete any customizations)")){

            $.post(ajaxurl, {
                action: "wyp_delete_stylesheet_live",
                wyp_reset_options: true,
                _wpnonce: window.wyp_live_styles_delete_nonce
            }).complete(function(){
                $("#wyp-reset-form").submit();
            });

        }

    });


    $(".wyp-delete-page-edits").click(function(){

        var text;
        var value;

        var li = $(this).parent("li");

        var deleteValue = $(this).parent("li").attr("data-delete-value");
        
        // Animation
        if(deleteValue.indexOf("|") == -1){
            text = "Are you sure you want to delete this animation?";
            var value = deleteValue;
        }else{
            text = "Are you sure you want to delete the customization?";
            var value = deleteValue.split("|")[1];
        }

        // Specific single types
        if(value == "lostpassword" || value == "register" || value == "login" || value == "home"){
            deleteValue = "TYPE|"+value;
        }

        // confirm delete
        if(confirm(text)){

            li.addClass("wyp-data-deleted");

            // global delete
            if(deleteValue.indexOf("GLOBAL|") != -1){

                $.post(ajaxurl, {
                    action: "wyp_delete_stylesheet_live",
                    wyp_reset_global: value,
                    _wpnonce: window.wyp_live_styles_delete_nonce
                }).complete(function(){

                    if($(".wyp-global-group li:not(.wyp-data-deleted)").length == 0){
                        $(".wyp-global-group").fadeOut("slow");
                    }

                    li.css("background", "#ff7474").css("border-color", "#ff7474").fadeOut("slow");
                    li.next().css("background", "#ff7474").css("border-color", "#ff7474").fadeOut("slow");

                    setTimeout(function(){li.next().remove();li.remove();}, 600);

                });

            // type delete
            }else if(deleteValue.indexOf("TYPE|") != -1){

                $.post(ajaxurl, {
                    action: "wyp_delete_stylesheet_live",
                    wyp_reset_type: value,
                    _wpnonce: window.wyp_live_styles_delete_nonce
                }).complete(function(){

                    if($(".wyp-template-group li:not(.wyp-data-deleted)").length == 0){
                        $(".wyp-template-group").fadeOut("slow");
                    }

                    li.css("background", "#ff7474").css("border-color", "#ff7474").fadeOut("slow");
                    li.next().css("background", "#ff7474").css("border-color", "#ff7474").fadeOut("slow");

                    setTimeout(function(){li.next().remove();li.remove();}, 600);

                });

            // ID delete
            }else if(deleteValue.indexOf("ID|") != -1){

                $.post(ajaxurl, {
                    action: "wyp_delete_stylesheet_live",
                    wyp_reset_id: value,
                    _wpnonce: window.wyp_live_styles_delete_nonce
                }).complete(function(){

                    if($(".wyp-single-group li:not(.wyp-data-deleted)").length == 0){
                        $(".wyp-single-group").fadeOut("slow");
                    }

                    li.css("background", "#ff7474").css("border-color", "#ff7474").fadeOut("slow");
                    li.next().css("background", "#ff7474").css("border-color", "#ff7474").fadeOut("slow");

                    setTimeout(function(){li.next().remove();li.remove();}, 600);

                });

            // Custom Animate Delete
            }else{

                $.post(ajaxurl, {
                    action: "wyp_delete_stylesheet_live",
                    wyp_delete_animate: value,
                    _wpnonce: window.wyp_live_styles_delete_nonce
                }).complete(function(){
                    li.css("background", "#ff7474").css("border-color", "#ff7474").fadeOut("slow");
                    li.next().css("background", "#ff7474").css("border-color", "#ff7474").fadeOut("slow");

                    setTimeout(function(){li.next().remove();li.remove();}, 600);
                });

            }


            // hide all
            if($(".wyp-code-group ul li:not(.wyp-data-deleted)").length == 0){
                $(".wyp-tab-section").hide();
                $(".wyp-no-code,.wyp-no-animation").fadeIn(300);
            }

        }

    });
    
    // deactivate
    $(".wyp-disable-license").click(function(){

        // Variables
        var el = $(this), href = el.attr("data-href"), site = el.attr("data-site"), code = el.attr("data-code");

        // deactivating
        el.addClass("wyp-deactivating");

        // loading.
        el.text('Processing' + '...');

        // ajax
        $.get("https://waspthemes.com/yellow-pencil/auto-update/", {
            action: "deactivate",
            site: site,
            purchase_code: code
        }).fail(function(){
            alert("Something went wrong. Please try again.");
        }).success(function(){
            window.location.href = href;
        });

    });

}(jQuery));