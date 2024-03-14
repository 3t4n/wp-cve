/****************************************Fast Flow Addons page ****************************************************************/
/**
 * jQuery to process AJAX request for on-page Addons installation and activation
 */

jQuery.noConflict();

var checkBtn = "install";

(function( $ ) {
    jQuery( document ).ready(function( $ ) {
        jQuery(document).on('click', 'button.btnclk-install', function() {
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
        jQuery(document).on('click','button.btnclk-activate', function() {
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

        jQuery(document).on('click', 'button.rbtnclk-install', function() {
            var btnId = '#' + jQuery(this).attr('id');
            if (jQuery(btnId).hasClass("rbtnclk-install")) {
                jQuery(this).addClass("updating-message");
                var nonceData = jQuery(this).attr('data-wpnonce');
                var loc = jQuery(location).attr('href') + '&_wpnonce=' + nonceData;
                var id = jQuery(this).attr('data-slug');
                jQuery.post(loc, { fastflow_action:"wp-repository-plugin-install", wp_repository_slug: id })
                .done(function( data ) {
                    //console.log( data );
                    if ( data.endsWith("WpRepositoryPlugInInstalled") === true ) {
                        jQuery(btnId).html("Activate");
                        jQuery(btnId).removeClass("rbtnclk-install updating-message");
                        jQuery.post(loc, { fastflow_action:"wp-repository-plugin-activate", wp_repository_slug: id })
                        .done(function( data ) {
                            //console.log( data );
                            if ( data.endsWith("WpRepositoryPluginActivated") === true ) {
                                jQuery(btnId).html("Active");
                                jQuery(btnId).attr('disabled', 'disabled');
                                jQuery(btnId).removeClass("updating-message").addClass("disabled-btn");
                            } else if (data.length !== 0) {
                                jQuery(btnId).html("Failed");
                                jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                                setTimeout(function(){
                                    jQuery(btnId).html("Install Now");
                                    jQuery(btnId).removeAttr('disabled');
                                    jQuery(btnId).removeClass("failed disabled-btn")
                                }, 3000);
                            }
                        });
                    } else if (data.length !== 0) {
                        jQuery(btnId).html("Failed");
                        jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                        setTimeout(function(){
                            jQuery(btnId).html("Install Now");
                            jQuery(btnId).removeAttr('disabled');
                            jQuery(btnId).removeClass("failed disabled-btn")
                        }, 3000);
                    }
                });
            }
        });

        jQuery(document).on('click', 'button.rbtnclk-activate', function() {
            var btnId = '#' + jQuery(this).attr('id');
            jQuery(this).addClass("updating-message");
            var nonceData = jQuery(this).attr('data-wpnonce');
            var loc = jQuery(location).attr('href') + '&_wpnonce=' + nonceData;
            var id = jQuery(this).attr('data-slug');
            //console.log( '<br />' + loc + '<br />' + id + '<br />' );
            jQuery.post(loc, { fastflow_action:"wp-repository-plugin-activate", wp_repository_slug: id })
            .done(function( data ) {
                //console.log( data );
                if ( data.endsWith("WpRepositoryPluginActivated") === true ) {
                    jQuery(btnId).html("Active");
                    jQuery(btnId).attr('disabled', 'disabled');
                    jQuery(btnId).removeClass("updating-message").addClass("disabled-btn");
                } else if (data.length !== 0) {
                    jQuery(btnId).html("Failed");
                    jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                    setTimeout(function(){
                        jQuery(btnId).html("Install Now");
                        jQuery(btnId).removeAttr('disabled');
                        jQuery(btnId).removeClass("failed disabled-btn")
                    }, 3000);
                }
            });
        });

        jQuery(document).on('click','button.fbtnclk-install',function() {
            var btnId = '#' + jQuery(this).attr('id');
            if (jQuery(btnId).hasClass("fbtnclk-install")) {
                jQuery(this).addClass("updating-message");
                var nonceData = jQuery(this).attr('data-wpnonce');
                var loc = jQuery(location).attr('href') + '&_wpnonce=' + nonceData;
                var id = jQuery(this).attr('data-slug');
                jQuery.post(loc, { fastflow_action:"fastflow-repository-plugin-install", fastflow_repository_slug: id })
                .done(function( data ) {
                    //console.log( data );
                    if ( data.endsWith("FastflowRepositoryPlugInInstalled") === true ) {
                        jQuery(btnId).html("Activate");
                        jQuery(btnId).removeClass("fbtnclk-install updating-message");
                        jQuery.post(loc, { fastflow_action:"fastflow-repository-plugin-activate", fastflow_repository_slug: id })
                        .done(function( data ) {
                            //console.log( data );
                            if ( data.endsWith("FastflowRepositoryPluginActivated") === true ) {
                                jQuery(btnId).html("Active");
                                jQuery(btnId).attr('disabled', 'disabled');
                                jQuery(btnId).removeClass("updating-message").addClass("disabled-btn");
                            } else if (data.length !== 0) {
                                jQuery(btnId).html("Failed");
                                jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                                setTimeout(function(){
                                    jQuery(btnId).html("Install Now");
                                    jQuery(btnId).removeAttr('disabled');
                                    jQuery(btnId).removeClass("failed disabled-btn")
                                }, 3000);
                            }
                        });
                    } else if (data.length !== 0) {
                        jQuery(btnId).html("Failed");
                        jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                        setTimeout(function(){
                            jQuery(btnId).html("Install Now");
                            jQuery(btnId).removeAttr('disabled');
                            jQuery(btnId).removeClass("failed disabled-btn")
                        }, 3000);
                    }
                });
            }
        });

        jQuery(document).on('click','button.fbtnclk-activate', function() {
            var btnId = '#' + jQuery(this).attr('id');
            jQuery(this).addClass("updating-message");
            var nonceData = jQuery(this).attr('data-wpnonce');
            var loc = jQuery(location).attr('href') + '&_wpnonce=' + nonceData;
            var id = jQuery(this).attr('data-slug');
            //console.log( '<br />' + loc + '<br />' + id + '<br />' );
            jQuery.post(loc, { fastflow_action:"fastflow-repository-plugin-activate", fastflow_repository_slug: id })
            .done(function( data ) {
                //console.log( data );
                if ( data.endsWith("FastflowRepositoryPluginActivated") === true ) {
                    jQuery(btnId).html("Active");
                    jQuery(btnId).attr('disabled', 'disabled');
                    jQuery(btnId).removeClass("updating-message").addClass("disabled-btn");
                } else if (data.length !== 0) {
                    jQuery(btnId).html("Failed");
                    jQuery(btnId).removeClass("updating-message").addClass("failed disabled-btn");
                    setTimeout(function(){
                        jQuery(btnId).html("Install Now");
                        jQuery(btnId).removeAttr('disabled');
                        jQuery(btnId).removeClass("failed disabled-btn")
                    }, 3000);
                }
            });
        });
        nav_tab_data(jQuery('#addons a.nav-tab.nav-tab-active'));
        jQuery(document).on('click', '#addons a.nav-tab', function(){
          nav_tab_data(jQuery(this));
        })
    });
})(jQuery);

var nav_tab_data = function(source){
  if(!jQuery('#'+source.attr('title')+' .fastflow-addon').length){
    jQuery('#'+source.attr('title')+' #fastflow-addons-cont').html('<p class="tab_loader updating-message"></p>')
    jQuery.ajax({
      type:"POST",
      url:myajax.ajaxurl,
      dataType: 'html',
      data:{action : 'fm_get_tab_data', value: source.attr('title')},
      success:function(result){
        jQuery('#'+source.attr('title')+' #fastflow-addons-cont').html(result);
      },
      error:function(error){
        console.log(error);
      }
    });
  }
}
/****************************************Fast Flow Settings page ****************************************************************/
jQuery(document).ready(function($){
/*Accordion Settings on settings page*/
	var icons = {
		header: 'ui-icon-plus',
		activeHeader: 'ui-icon-minus'
	};
	$( '#accordion' ).accordion({
		collapsible: true,
		active: false,
		icons: icons,
		heightStyle: 'content',
	});

  jQuery('#addons a.nav-tab').click(function (e) {
    jQuery('.nav-tab-active').removeClass('nav-tab-active');
    jQuery(this).addClass('nav-tab-active');
    e.preventDefault();
    jQuery('.tabcontent').slideUp();
    var content_show = jQuery(this).attr('title');
    jQuery('#'+content_show).slideDown();
  });

  var mediaUploader;
	jQuery(document).on('click','.dashboard-logo-btn',function(e) {
		e.preventDefault();
		if( mediaUploader ){
			mediaUploader.open();
			return;
		}

		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Select a Picture',
			button: {
				text: 'Select Picture'
			},
			multiple: false
		});

		mediaUploader.on('select', function(){
			attachment = mediaUploader.state().get('selection').first().toJSON();
			jQuery('input[name="dashboard_logo"]').val(attachment.id);
			jQuery('.dashboard-logo-preview').attr('src', attachment.url);
		});

		mediaUploader.open();

	});

});
