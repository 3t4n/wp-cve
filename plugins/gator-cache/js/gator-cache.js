jQuery(document).ready(function($){
    if(0 === $("#gc_settings").length){
        return;
    }
    if('undefined' !== typeof($.ui.tabs)){
        $("#gc_load").hide();
        $("#gci_tabs").show();
        $("#gci_tabs").tabs({
            create: function( event, ui ) {
                if('#tab-debug' === location.hash){
                    $('html, body').animate({
                        scrollTop: $('#gci_del').offset().top - 50
                    }, 500);
                }
            }
        });
        $("#gci_roles,#post_types").chosen({no_results_text: "Oops, nothing found!"});
        //$("#ex_cust").bind("mousedown", function(e) {e.metaKey = true;}).selectable({
        $("#ex_cust").selectable({
            cancel: "i.fa-times-circle"
        });
    }
});
