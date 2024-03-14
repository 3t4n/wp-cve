jQuery(document).ready(function($){
    var ig_page_url  = new URL(window.location.href);

    if(ig_page_url.searchParams.get('tab')){
        $('.wrap.ig_settings .nav-tabs button.nav-link[aria-controls="'+ig_page_url.searchParams.get('tab')+'"]').click();
    }

    $('ul.ig_log_list input:checkbox').on('change', function(e){
        e.preventDefault();

        var all_checked = $('ul.ig_log_list input:checkbox:checked');

        var ig_action_bulk = $('ul.ig_bulk_action .ig_actions_selected');

        if(all_checked.length > 0){

            ig_action_bulk.css('visibility', 'visible');

        }else{
            ig_action_bulk.css('visibility', 'hidden');  
        }

    })

    $('ul.ig_bulk_action .ig_params input:checkbox').on('click', function(){

        var all_checkbox = $('ul.ig_log_list input:checkbox');

        all_checkbox.prop('checked', $(this).prop('checked'));

        all_checkbox.first().change();

    });

    function ig_update_action(this_actions, action_type){
        var this_type = this_actions.find('a[data-type="'+action_type+'"]');
        var other_type = action_type == 'whitelist' ? 'blacklist' : 'whitelist';
        var other_type_obj = this_actions.find('a[data-type="'+other_type+'"]');

        // console.log(action_type);
        // console.log(this_type);
        // console.log(other_type_obj);
        other_type_obj.removeClass('hide');
        this_type.addClass('hide');
        
    }

    $('.ig_bulk_action .ig_actions_selected a').on('click', function(e){
            e.preventDefault();
			
			if(!ig_obj.ig_super_admin){ alert(ig_obj.ig_super_admin_msg); return false; }

            var action_type = $(this).data('type');
            var parent_li = $(this).parents('li:first');
            ig_update_action(parent_li.find('.ig_actions_selected'), action_type);
            var all_checked = $('ul.ig_log_list input:checkbox:checked');

            if(all_checked.length > 0){
                var post_obj = {};
                $.each(all_checked, function(){

                    var this_li = $(this).parents('li:first');
                    var this_uri = $(this).data('uri');
                    var this_val = $(this).val();
                    var this_actions = this_li.find('div.ig_actions');

                    ig_update_action(this_actions, action_type);

                    if($.isArray(post_obj[this_uri])){
                        post_obj[this_uri].push(this_val);
                    }else{
                        post_obj[this_uri] = [];
                        post_obj[this_uri].push(this_val);
                    }

                });

                
                var data = {
                    action: 'ig_update_bulk_backlist',
                    ig_post_obj: post_obj,
                    ig_type: action_type,
                    ig_nonce: ig_obj.ig_nonce
                }
				$.blockUI({message:''});
                $.post(ajaxurl, data, function(resp, code){

                    if(code == 'success'){
                        
                    }
					setTimeout(function(){ document.location.reload(); }, 1000);
                });
            }






    });

    $('.wrap.ig_settings .nav-tabs button.nav-link').on('click', function(){
        var this_tab_name = $(this).attr('aria-controls');
        var this_url  = new URL(window.location.href);
        this_url.searchParams.set('tab', this_tab_name);
        window.history.replaceState('', '', this_url.href);

    })

});