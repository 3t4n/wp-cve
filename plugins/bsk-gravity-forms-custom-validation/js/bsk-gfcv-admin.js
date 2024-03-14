jQuery(document).ready( function($) {
	
	$("#bsk_gfcv_list_edit_form_id").keypress(function(e) {
		var key = e.charCode || e.keyCode || 0;     
		if (key == 13) {
			e.preventDefault();
		}
    });
	
	$("#bsk_gfcv_blacklist_list_save_ID").click(function(){
		var list_name = $("#bsk_gfcv_list_name_ID").val();
        
		list_name = $.trim(list_name);
		if( list_name == "" ){
			alert( "List name cannot be empty" );
			$("#bsk_gfcv_list_name_ID").focus();
			
			return false;
		}
		
		$("#bsk_gfcv_list_edit_form_id").submit();
	});
	
    /* Custom Validation */
    $(".bsk-gfcv-add-cv-rule").change(function(){
        
        var form_obj = $(this).parents("form");
        var error_message_obj = form_obj.find(".bsk-gfcv-rule-settings-error-message");
        var settings_obj = form_obj.find(".bsk-gfcv-rule-settings-container");
        var ajax_loader_obj = form_obj.find(".bsk-gfcv-rule-select-ajax-loader");
        var rule_slug = $(this).val();
        
        error_message_obj.html( "" );
        error_message_obj.css( "display", "none" );
        settings_obj.html( "" );
        form_obj.find(".bsk-gfcv-rule-save-conatiner").css( "display", "none" );
        
        if( rule_slug == '' ){
            return;
        }
        
        
        //get rule details
        var nonce_val = form_obj.find(".bsk-gfcv-rule-ajax-nonce").val();
        var data = { 
                        action: 'bsk_gfcv_get_rule_html_settings_by_slug',
                        nonce: nonce_val, 
                        slug: rule_slug
                   };
        ajax_loader_obj.css( "display", "inline-block" );
        $.post( ajaxurl, data, function( response ) {
            ajax_loader_obj.css( "display", "none" );
            var return_obj = $.parseJSON( response );
            if( !return_obj.status ){
                error_message_obj.html( return_obj.msg );
                error_message_obj.css( "display", "block" );
                
                return;
            }
            settings_obj.html( return_obj.html );
            if( !return_obj.only_pro ){
                form_obj.find(".bsk-gfcv-rule-save-conatiner").css( "display", "block" );
            }
        });
        
    });
    
    $("#bsk_gfcv_rules_form_id").on("click", ".bsk-gfcv-rule-save", function(){
        var settings_container = $("#bsk_gfcv_rules_form_id").find(".bsk-gfcv-rule-settings-container");
        var error_message_obj = $("#bsk_gfcv_rules_form_id").find(".bsk-gfcv-rule-settings-error-message");
        
        error_message_obj.html("");
        error_message_obj.css( "display", "none" );
        
        if( settings_container.find(".bsk-gfcv-BSK_CV_MIN").length ){
            var value = settings_container.find(".bsk-gfcv-BSK_CV_MIN").val();
            if( value == '' ){
                error_message_obj.html("The min value cannot be empty");
                error_message_obj.css( "display", "block" );
                settings_container.find(".bsk-gfcv-BSK_CV_MIN").focus();
                
                return;
            }
        }
        if( settings_container.find(".bsk-gfcv-BSK_CV_MAX").length ){
            var value = settings_container.find(".bsk-gfcv-BSK_CV_MAX").val();
            if( value == '' ){
                error_message_obj.html("The max value cannot be empty");
                error_message_obj.css( "display", "block" );
                settings_container.find(".bsk-gfcv-BSK_CV_MAX").focus();
                
                return;
            }
        }
        
        if( settings_container.find(".bsk-gfcv-BSK_CV_MIN").length &&
            settings_container.find(".bsk-gfcv-BSK_CV_MAX").length ){
            var min_value = settings_container.find(".bsk-gfcv-BSK_CV_MIN").val();
            var max_value = settings_container.find(".bsk-gfcv-BSK_CV_MAX").val();
            
            min_value = parseFloat( min_value );
            max_value = parseFloat( max_value );
            if( min_value > max_value ){
                error_message_obj.html("The min(" + min_value + ") value must small than max(" + max_value + ") value");
                error_message_obj.css( "display", "block" );
                settings_container.find(".bsk-gfcv-BSK_CV_MIN").focus();
                
                return;
            }
        }
        
        if( settings_container.find(".bsk-gfcv-BSK_CV_TEXT").length ){
            var value = settings_container.find(".bsk-gfcv-BSK_CV_TEXT").val();
            if( value == '' ){
                error_message_obj.html("The value cannot be empty");
                error_message_obj.css( "display", "block" );
                settings_container.find(".bsk-gfcv-BSK_CV_TEXT").focus();
                
                return;
            }
        }
        if( settings_container.find(".bsk-gfcv-BSK_CV_NUMBER").length ){
            var value = settings_container.find(".bsk-gfcv-BSK_CV_NUMBER").val();
            if( value == '' ){
                error_message_obj.html("The value cannot be empty");
                error_message_obj.css( "display", "block" );
                settings_container.find(".bsk-gfcv-BSK_CV_NUMBER").focus();
                
                return;
            }
        }
        
        $("#bsk_gfcv_action_ID").val( 'save_rule' );
        $("#bsk_gfcv_rules_form_id").submit();
    });
    
    $(".bsk-gfcv-admin-delete-cv-list").click(function(){
        $(this).parent().find( ".bsk-gfcv-delete-confirm-span" ).css( "display", "inline-block" );
        $(this).css( "display", 'none' );
    })
    
    $(".bsk-gfcv-admin-delete-cv-list-cancel").click(function(){
        $(this).parents('td').find( ".bsk-gfcv-admin-delete-cv-list" ).css( "display", "inline-block" );
        $(this).parent().css( "display", 'none' );
    })
    
    $(".bsk-gfcv-admin-delete-cv-list-yes").click(function(){
        var list_id = $(this).attr("rel");
		var count = $(this).attr("count");
		
		if( parseInt(list_id) < 1 ){
			$(this).parents('td').find( ".bsk-gfcv-admin-delete-cv-list" ).css( "display", "inline-block" );
            $(this).parent().css( "display", 'none' );
            
			return false;
		}

		$("#bsk_gfcv_cv_list_id_to_be_processed_ID").val( list_id );
		$("#bsk_gfcv_action_ID").val( "delete_cv_list_by_id" );
		$("#bsk_gfcv_cv_lists_form_id").submit();
    })
    
    $(".bsk-gfcv-rule-delete-anchor").click(function(){
        var item_id = $(this).attr('rel');
		
		if( parseInt(item_id) < 1 ){
			alert( "Invalid opearation" );
		}
		
		$("#bsk_gfcv_rule_id_ID").val( item_id );
		$("#bsk_gfcv_action_ID").val( "delete_rule" );
		
		$("#bsk_gfcv_rules_form_id").submit();
    });

    //validation message
    $("#bsk_gfcv_rules_form_id").on("click", ".bsk-gfcv-rule-message", function(){
        var pro_tips_container = $( this ).parents( ".bsk-gfcv-rule-validation-message-container" ).find( ".bsk-gfcv-tips-box" );
        pro_tips_container.css( "display", "inline-block" );
    });
    
    /*
     * blocked entries
     *
     */
    $("#bsk_gfcv_form_select_to_list_entries_ID").change( function(){
        var slected_form = $(this).val();
        
        $(this).parents( 'form' ).submit();
    });
    
    $( ".bsk-gfcv-notify-bloked-enable-radio" ).click( function(){
        var notify_blocked_enable = $("input[name='bsk_gfcv_notify_blocked_enable']:checked").val();
        var details_container = $(this).parents( ".bsk-gfcv-notify-administrtor-settings" ).find( ".bsk-gfcv-administrator-mails-details-container" );
        
        if( notify_blocked_enable == 'NO' ){
            details_container.css( "display", "none" );
            return;
        }
        
        details_container.css( "display", "block" );
    });
    
    /*
     * form settings
     */
    /* settings tab switch */
	$("#bsk_gfcv_setings_wrap_ID .nav-tab-wrapper a").click(function(){
		//alert( $(this).index() );
		$('#bsk_gfcv_setings_wrap_ID section').hide();
		$('#bsk_gfcv_setings_wrap_ID section').eq($(this).index()).show();
		
		$(".nav-tab").removeClass( "nav-tab-active" );
		$(this).addClass( "nav-tab-active" );
		
		return false;
	});
    
	//settings target tab
	if( $("#bsk_gfcv_settings_target_tab_ID").length > 0 ){
		var target = $("#bsk_gfcv_settings_target_tab_ID").val();
		if( target ){
			$("#bsk_gfcv_setings_tab-" + target).click();
		}
	}
    
    $( ".bsk-gfcv-form-settings-enable-raido" ).change(function () {

        var enable = $("input[type='radio'][name='bsk_gfcv_form_settings_enable']:checked").val();
        var form_settings_container = $(this).parents( '.bsk-gfcv-form-settings-container' );
        
        if( enable == 'DISABLE' ){
            form_settings_container.find( ".bsk-gfcv-form-settings-actions-container" ).css( "display", "none" );
            form_settings_container.find( ".bsk-gfcv-form-settings-blocked-data-container" ).css( "display", "none" );
            
            return;
        }
        
        form_settings_container.find( ".bsk-gfcv-form-settings-actions-container" ).css( "display", "table-row" );
        form_settings_container.find( ".bsk-gfcv-form-settings-blocked-data-container" ).css( "display", "block" );
        
        var notify_administrator = $("input[type='radio'][name='bsk_gfcv_notify_administrators']:checked").val();
        if( notify_administrator == 'YES' ){
            form_settings_container.find( ".bsk-gfcv-form-settings-notify-send-to" ).css( 'display', 'table-row' );
        }else{
            form_settings_container.find( ".bsk-gfcv-form-settings-notify-send-to" ).css( 'display', 'none' );
        }
    });
    
   
    $( ".bsk-gfcv-form-settings-action-block-chk" ).click( function( event ){
        event.preventDefault();
    });
    
    $(".bsk-gfcv-notifiy-administrators-raido").change( function(){
        var notify_administrator = $("input[type='radio'][name='bsk_gfcv_notify_administrators']:checked").val();
        var form_settings_container = $(this).parents( '.bsk-gfcv-form-settings-container' );
        
        if( notify_administrator == 'YES' ){
            form_settings_container.find( ".bsk-gfcv-form-settings-notify-send-to" ).css( 'display', 'table-row' );
        }else{
            form_settings_container.find( ".bsk-gfcv-form-settings-notify-send-to" ).css( 'display', 'none' );
        }
    })
    
    /*
     * formidable forms form field
     */
    $( ".bsk-gfcv-ff-form-field-apply-list-chk" ).click( function() {
        var checked = $(this).is(":checked");
        var type = $(this).data( 'list-type' );
        
        //uncheck or checkbox
        $(this).parents( 'ul' ).find( '.bsk-gfcv-ff-form-field-apply-list-chk' ).prop( 'checked', false );
        $(this).parents( 'ul' ).find( 'select' ).val( '');
        $(this).parents( 'ul' ).find( 'select' ).slideUp();
        
        //check the current
        if ( checked ) {
            $(this).prop( 'checked', true );
            //show select
            $(this).parent().find( 'select' ).slideDown();
        }
        
    });
    
});
