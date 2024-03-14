/**
 * jQuery to process AJAX request
 * for on-page Addons installation 
 * and activation
 * 
 */

jQuery.noConflict();

var checkBtn = "install";

(function( $ ) {
    jQuery( document ).ready(function( $ ) {
        jQuery("button.btnclk-install").click(function() {
            var btnId = '#' + jQuery(this).attr('id');
            if (jQuery(btnId).hasClass("btnclk-install")) {
                jQuery(this).html("Installing...");
                jQuery(this).addClass("updating-message");
                var nonceData = jQuery(this).attr('data-wpnonce');
                var loc = jQuery(location).attr('href') + '&_wpnonce=' + nonceData;
                var id = jQuery(this).attr('data-slug');
                //console.log( '<br />' + loc + '<br />' + id + '<br />' + checkBtn + '<br />' );
                //setTimeout(function(){}, 50);
            
                jQuery.post(loc, { fastflow_action:"fastflow-plugin-install", fastflow_addons_slug: id })
                .done(function( data ) {
                    //console.log( data );
                    if ( data.endsWith("FastFlowAddOnPlugInInstalled") === true ) {
                        jQuery(btnId).html("Activate");
                        jQuery(btnId).removeClass("btnclk-install updating-message").click(function() {

                                checkBtn = "Activate";

                                jQuery(btnId).html("Activating...");
                                jQuery(btnId).addClass("updating-message");

                                /*var nonceData = jQuery(this).attr('data-wpnonce');
                                var loc = jQuery(location).attr('href') + '&_wpnonce=' + nonceData;
                                var id = jQuery(this).attr('data-slug');*/
                                //console.log( '<br />' + loc + '<br />' + id + '<br />' );
                                jQuery.post(loc, { fastflow_action:"fastflow-plugin-activate", fastflow_addons_slug: id })
                                .done(function( data ) {
                                    //console.log( data );
                                    if ( data.endsWith("FastFlowPluginActivated") === true ) {
                                        jQuery(btnId).html("Active");
                                        jQuery(btnId).attr('disabled', 'disabled');
                                        jQuery(btnId).removeClass("updating-message").addClass("disabled-btn");
                                    } else if (data.length !== 0) {
                                        jQuery(btnId).html("Failed");
                                        jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                                        jQuery(btnId).attr('disabled', 'disabled');
                                        var secLoc = loc + "&fastflow_action=fastflow-plugin-activate&fastflow_addons_slug=" + id;
                                        setTimeout(function(){
                                            jQuery(btnId).removeAttr('disabled');
                                            jQuery(btnId).html("<a style='text-decoration: none; color: inherit;' href='" + secLoc + "'>Activate Again</a>");
                                            jQuery(btnId).removeClass("failed disabled-btn");
                                        }, 3000);
                                    }
                                });
                        });
                    } else if (data.length !== 0) {
                        jQuery(btnId).html("Failed");
                        jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                        jQuery(btnId).attr('disabled', 'disabled');
                        var secLoc = loc + "&fastflow_action=fastflow-plugin-install&fastflow_addons_slug=" + id;
                        setTimeout(function(){
                            jQuery(btnId).removeAttr('disabled');
                            jQuery(btnId).html("<a style='text-decoration: none; color: inherit;' href='" + secLoc + "'>Try Install Again</a>");
                            jQuery(btnId).removeClass("btnclk-install failed disabled-btn");
                        }, 3000);
                    }
                });
            }   
        });
        jQuery("button.btnclk-activate").click(function() {
            var btnId = '#' + jQuery(this).attr('id');
            jQuery(this).html("Activating...");
            jQuery(this).addClass("updating-message");
            var nonceData = jQuery(this).attr('data-wpnonce');
            var loc = jQuery(location).attr('href') + '&_wpnonce=' + nonceData;
            var id = jQuery(this).attr('data-slug');
            //console.log( '<br />' + loc + '<br />' + id + '<br />' );
            jQuery.post(loc, { fastflow_action:"fastflow-plugin-activate", fastflow_addons_slug: id })
            .done(function( data ) {
                //console.log( data );
                if ( data.endsWith("FastFlowPluginActivated") === true ) {
                    jQuery(btnId).html("Active");
                    jQuery(btnId).attr('disabled', 'disabled');
                    jQuery(btnId).removeClass("updating-message").addClass("disabled-btn").removeClass("btnclk-activate");
                } else if (data.length !== 0) {
                    jQuery(btnId).html("Failed");
                    jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                    jQuery(btnId).attr('disabled', 'disabled');
                    var secLoc = loc + "&fastflow_action=fastflow-plugin-activate&fastflow_addons_slug=" + id;
                    setTimeout(function(){
                        jQuery(btnId).removeAttr('disabled');
                        jQuery(btnId).html("<a style='text-decoration: none; color: inherit;' href='" + secLoc + "'>Activate Again</a>");
                        jQuery(btnId).removeClass("failed disabled-btn").removeClass("btnclk-activate");
                    }, 3000);
                }
            });
        });
    });
})(jQuery);