jQuery( document ).ready( function($){
    'use strict';
    
    recalculate__special_grid_lists();
    //fetch__agent_filters_status();
    
    // Plugins section on Filter Edit screen, multi-select
    $('.block-plugin-wrapper .special_grid_list').selectable({
        filter: '.single_plugin',
        selected: function( event, ui ) {
            
            ui.selected.click();
        }
    });

    // Edit  screen - Hover a tooltip - Start
    $('#the-list').on('mouseenter', '.has_tooltip > .tooltip_trigger[data-tooltip-list]', function(){
        
        if( $(this).parents(".has_tooltip").hasClass("tooltip_is_active") ){
            
            return;
        }
        
        $(this).parents(".has_tooltip").addClass("tooltip_is_active");
        
        $('#the-list .active_tooltip').remove();


        let list = $(this).data("tooltip-list");
        
        if( ! list || list.length == 0 ){
            
            return;
        }
        
        // console.log( "list: ", list );
        // list = list.concat( list, list, list, list, list, list );
        // console.log( "list: ", list );
        
        
        
        let $tooltip = $('<div class="active_tooltip"><div class="active_tooltip_inner"></div></div>');
        let $tooltip_inner = $tooltip.find('.active_tooltip_inner');
        
        
        
        $tooltip.css("opacity", 0 );
        
        let html = '';
        
        $.each( list, function( index, value ){
            html += '<div>' + value + '</div>';
        });
        
        $tooltip_inner.html( html );
        
        $(this).after( $tooltip );
        
        
        let tooltip_inner__width = $tooltip_inner.outerWidth();
        $tooltip.css("width", tooltip_inner__width + 40 );
        // console.log( "tooltip_inner__width: ",  tooltip_inner__width  );
        
        let tooltip_height      = $tooltip.outerHeight();
        let tooltip_width       = $tooltip.outerWidth();
        let trigger_position    = $(this).position();
        let trigger_height      = $(this).outerHeight();
        
        // console.log( "trigger_position_left: " + trigger_position.left + ", trigger_position_top: " + trigger_position.top );
        // console.log( "tooltip_height: ", tooltip_height );
        // console.log( "tooltip_width: ",  tooltip_width  );
        
        $tooltip.css("left", trigger_position.left - tooltip_width - 20 );
        $tooltip.css("top", trigger_position.top + ( trigger_height / 2 ) - ( tooltip_height / 2 ) );
        
        $tooltip.hide();
        $tooltip.css("opacity", 1 );
        
        // https://github.com/Grsmto/simplebar/tree/master/packages/simplebar
        let simplebar = new SimpleBar( $tooltip[0], {
            autoHide: false
        });
        
        $tooltip.show();
    });

    // Edit  screen - Hover a tooltip - End
    $('#the-list').on('mouseleave', '.has_tooltip', function(){
        
        $(this).removeClass("tooltip_is_active");
        $(this).parent().find(".active_tooltip").remove();

        // console.log( "Over and out" );
    });

    // Edit Filter screen - fetch post types
    /*if( $('#set_filter_type').length >= 1 ){
        
        $.post( po_object.ajax_url, { action  : 'po_get_post_types' }, function( response ) {
            
            // console.log( "post_types: ", response.data.post_types );
            
            if( response.data.post_types ){
                
                $.each( response.data.post_types, function( key, name ){
                    
                    // console.log( key, name );
                    
                    $('#select_post_types').append(`<option value="${key}">${name}</option>`)
                    
                });
                
            }
            
            let selected = $('#set_filter_type').data("selected");
            
            $('#loading_post_types').hide();
            
            // TODO It's possible that this 'selected' post_type doesn't exist anymore, so we should:
            // a) switch to default, or
            // b) notify the user that there might be an error
            // it's not necessary an error if the plugin that creates that post type is temporary disabled
            $('#set_filter_type').val( selected ).change().slideDown();
            
        }, "json");
    }*/
    
    // Edit Filter screen, Edit Group screen - Clicking on a plugin
    $('#edit_filter, #edit_group').on('click', '.block-plugin-wrapper .single_plugin', function(){

        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
    });

    // Edit Filter screen - Clicking on a group
    $('#edit_filter').on('click', '.block-group-plugin-wrapper .single_group', function(){
        
        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
        
        toggle_plugins_by_group( $(this) );
    });
    
    // Toggles plugins for a group if it is being selected for blocking
    function toggle_plugins_by_group( $group_element, only_labels = false ){
        
        let plugins_to_block = $group_element.data("plugins");
        let group_name       = $group_element.children("span").text();
        
        if( $group_element.hasClass('blocked') ){
            
            // console.log( "Name: ", group_name );
            // console.log( "Plugins: ", plugins_to_block );
            
            $.each( plugins_to_block, function( index, plugin_name ){
                
                // console.log( "Block: ", plugin_name );
                
                if( ! only_labels ){
                
                    $(`.single_plugin[data-name="${plugin_name}"]`).addClass("blocked");
                    $(`.single_plugin[data-name="${plugin_name}"] input[type="checkbox"]`).prop("checked", true);
                
                }
                
                $(`.single_plugin[data-name="${plugin_name}"] span.group_name`).append(`<span data-name="${group_name}">${group_name}</span>`);
                
            });
            
        } else {
            
            $(`.single_plugin span.group_name span[data-name="${group_name}"]`).remove();
        }
    }
    
    // Edit Filter screen - Select a category for a new filter, does nothing but marks the selected category
    $('#edit_filter').on('click', '.category-wrapper .single_category:not(.dictionary_view)', function(){

        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
    });
    
    // Edit Filter screen - Dictionary view - toggle the plugins list
    $('#edit_filter').on('click', '.header.attribute-plugin.dictionary_view', function(){

        $(this).next('.dictionary_view_plugins_list').slideToggle();
    });
    
    // Edit Filter screen - Create new category, show input field
    $('#edit_filter').on('click', '#add_category.before_add', function(){

        $('#add_category').removeClass('before_add');
        $('#add_category').addClass('during_add');
        $('#add_category input').focus();
    });
    
    // Edit Filter screen - Create new category, Cancel
    $('#edit_filter').on('click', '#add_category.during_add .cancel', function(){

        $('#add_category').removeClass('during_add');
        $('#add_category').addClass('before_add');
    });
    
    // Edit Filter screen - Create new category, OK
    $('#edit_filter').on('click', '#add_category.during_add .ok', function(){

        let category_name = $('#add_category input').val();
        
        if( ! category_name ){
            return;
        }
        
        $.post( po_object.ajax_url, { action  : 'po_create_category', category_name : category_name }, function( response ) {
            console.log( "po_create_category: ", response );
            
            if( response.data.message ){
                alert( response.data.message );
            } else {
                
                $('#add_category').removeClass('during_add');
                $('#add_category').addClass('before_add');
                
                $('#add_category input').val('');
                
                $('#add_category').before(`
					<div class="single_category content blocked">
                        <input class="noeyes" type="checkbox" name="SOSPO_filter_data[categories][${response.data.category_id}]" value="${category_name}" checked="checked"/>
						<span value="${response.data.category_id}">${category_name}</span>
                    </div>
                `);
                
                // sort categories alphabetically
                let $categories_list = $('#add_category').parent();
                
                $categories_list.children('.single_category').sort( function( a, b ){
                    
                    let a_title = $(a).children('span').text().trim().toLowerCase();
                    let b_title = $(b).children('span').text().trim().toLowerCase();
                    
                    return a_title.localeCompare( b_title );
                    
                }).prependTo( $categories_list );
                
                // recalculate rows and columns
                recalculate__special_grid_lists();
                
            }
            
        }, "json");
    });
    
    // Edit Filter screen - Toggle plugins for already selected groups on page load
    $('#edit_filter .block-group-plugin-wrapper .single_group.blocked').each(function(){
        
        toggle_plugins_by_group( $(this), true );
    });
    
    // Edit Filter screen - Change filter type
    $('#edit_filter').on('change', '#set_filter_type', function(){
        
        let type = $(this).val();
        
        if( type == "_endpoint" || type == '_ajax'){
            $('#edit_filter #endpoints_wrapper').slideDown();
            $('#post_type_options').hide();
        } else {
            $('#edit_filter #endpoints_wrapper').slideUp();
            $('#post_type_options').show();
        }
    }).change();
    
    // Edit Filter screen - Save filter
    $('#edit_filter').on('click', '#save_filter', function(e){
        
        let filter_data = $('#edit_filter').find('select, textarea, input').serialize();
        

        if( $('#frontend_check').prop('checked')){
            let re = confirm("You have checked the 'Customer Facing Only' checkbox. This filter will work across all customer facing pages that use the post type "+$('#set_filter_type').val()+'. Review your filters to make sure you don\'t have unintended conficts.');
            if( !re ){
                return;
            }
            filter_data += '&frontend=true';
        } else {
            filter_data += '&frontend=false';
        }

        $.post( po_object.ajax_url, { action  : 'po_save_filter', data : filter_data }, function( response ) {
            // console.log( "po_save_filter: ", response );


            if( !response.success ){
              alert(response.data.message);
              return;
            }
            
            if( response.data.id ){
                
              const url = new URL( window.location.href );
              url.searchParams.set( 'filter_id', response.data.id );
              window.location.href=url;
            //     $('input[name="SOSPO_filter_data[ID]"]').val( response.data.id );
                
            //     const url = new URL( window.location.href );
            //     url.searchParams.set( 'filter_id', response.data.id );
            //     window.history.replaceState( null, null, url );
                
            //     $('#name_page').html( 'Editing filter: ' + $('#set_title').val() );
                
            //     window.scroll({
            //         top: 0,
            //         left: 0,
            //         behavior: 'smooth'
            //     });
            }
            
        }, "json");
    });
    
    // Edit Group screen - Save Group
    $('#edit_group').on('click', '#save_group', function(){
        
        let group_data = $('#edit_group').find('select, textarea, input').serialize();
        
        $.post( po_object.ajax_url, { action  : 'po_save_group', data : group_data }, function( response ) {
            // console.log( "po_save_group: ", response );
            
            alert( response.data.message );
            
            if( response.data.id ){
                
                $('input[name="SOSPO_filter_data[ID]"]').val( response.data.id );
                
                const url = new URL( window.location.href );
                url.searchParams.set( 'group_id', response.data.id );
                window.history.replaceState( null, null, url );
                
                $('#name_page').html( 'Editing group: ' + $('#set_title').val() );
                
                window.scroll({
                    top: 0,
                    left: 0,
                    behavior: 'smooth'
                });
            }
            
        }, "json");
    });
    
    // Edit Category screen - Save Category
    $('#edit_category').on('click', '#save_category', function(){
        
        let category_data = $('#edit_category').find('select, textarea, input').serialize();
        // console.log( "category_data: ", category_data );
        
        $.post( po_object.ajax_url, { action  : 'po_save_category', data : category_data }, function( response ) {
            // console.log( "po_save_category: ", response );
            
            alert( response.data.message );
            
            if( response.data.id ){
                
                $('input[name="SOSPO_filter_data[ID]"]').val( response.data.id );
                
                const url = new URL( window.location.href );
                url.searchParams.set( 'cat_id', response.data.id );
                window.history.replaceState( null, null, url );
                
                $('#name_page').html( 'Editing category: ' + $('#set_title').val() );
                
                window.scroll({
                    top: 0,
                    left: 0,
                    behavior: 'smooth'
                });
            }
            
        }, "json");
    });
    
    // On a new Filter or new Group pages: disable/enable all in a section
    $('body').on('click', '.all-check', function(){
        
        if( $(this).text() === 'Disable All'){
            
            $(this).text('Enable All');
            
            if( $(this).hasClass("toggle_plugins") ){
                
                $(this).parents('.row.block-plugin-wrapper').find('.single_plugin').addClass('blocked');
                $(this).parents('.row.block-plugin-wrapper').find('.single_plugin input[type="checkbox"]').prop( "checked", true );
                
            } else if( $(this).hasClass("toggle_groups") ){
                
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group').addClass('blocked');
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group input[type="checkbox"]').prop( "checked", true );
                
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group').each(function(){
                    
                    toggle_plugins_by_group( $(this) );
                });
                
            }
            
        } else {
            
            $(this).text('Disable All');
            
            if( $(this).hasClass("toggle_plugins") ){
                
                $(this).parents('.row.block-plugin-wrapper').find('.single_plugin').removeClass('blocked');
                $(this).parents('.row.block-plugin-wrapper').find('.single_plugin input[type="checkbox"]').prop( "checked", false );
                
            } else if( $(this).hasClass("toggle_groups") ){
                
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group').removeClass('blocked');
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group input[type="checkbox"]').prop( "checked", false );
                
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group').each(function(){
                    
                    toggle_plugins_by_group( $(this) );
                });
                
            }
            
        }
    });
    
    // On the Edit Filter screen, the button #add_endpoint is used to add a new endpoint to the filter
    $('body').on('click', '#add_endpoint', function(){
        
        $('#endpoints_wrapper > div:eq(-1)').after(`
			<div class="additional_endpoint_wrapper">
                <input class="additional_endpoint" type="text" name="SOSPO_filter_data[endpoints][]" placeholder="Put your URL here" value=""/>
                <div class="remove_additional_endpoint circle_button remove_something">-</div>
			</div>
        `);
        
        $('#endpoints_wrapper > div:eq(-1) input.additional_endpoint').focus();
    });

    // On the Edit Filter screen, the button #add_endpoint is used to add new endpoint to the filter
    $('body').on('click', '.remove_additional_endpoint', function(){
        
        $(this).parent().remove();
    });

    // On the Edit Filter screen, we need to force to local domain, can't filter plugins for other domains
    $('body').on('input', '.additional_endpoint_wrapper input', function(ev){
                
        var entered_val = $(this).val();
        var edited_val  = '/'+entered_val.replace(po_object.home_url+'/','');
        var edited_val  = edited_val.replace('//','/');

        $(this).val( edited_val );
        
        $(this).parent().removeClass("error__empty_input");
    });

    // On the Edit Filter screen, we need to force to local domain, can't filter plugins for other domains
    $('body').on('focusout', '.additional_endpoint_wrapper input', function(ev){
        
        if( ! $(this).val() ){
            
            $(this).parent().addClass("error__empty_input");
        }
    });

    // On the Edit Filter screen, #first_endpoint is the input field where you put the initial permalink/endpoint for the filter
    $('body').on('keypress', '#first_endpoint', function(e){
        
        if (e.keyCode == 13) {
            $('#add_endpoint').click();
        }
    });

    // Show toggle columns
    $('body').on('click', '#show_toggle_columns', function(){
        
        $('#show_toggle_columns').hide();
        $('#hide_toggle_columns').show();
        
        $('.toggle_columns_options').slideDown(200);
        
        $('#hide_filter_options').hide();
        $('#show_filter_options').show();
        
        $('.toggle_filter_options').hide();
    });
    
    // Hide toggle columns
    $('body').on('click', '#hide_toggle_columns', function(){
        
        $('#hide_toggle_columns').hide();
        $('#show_toggle_columns').show();
        
        $('.toggle_columns_options').slideUp(200);
    });
    
    // actually show/hide columns
    $('body').on('change', '#full_columns_list .single_column_state input', function(){
        
        let displayed = $(this).prop('checked');
        let column_id = $(this).data('id');
        
        $('table.po_table [data-label="' + column_id + '"]').toggle( displayed );
    });
    
    $('#full_columns_list .single_column_state input').change();
    
    // show all columns
    $('body').on('click', '#show_all_columns', function(){
        
        $('#full_columns_list .single_column_state input').prop('checked', true ).change();
    });
    
    // hide all columns
    $('body').on('click', '#hide_all_columns', function(){
        
        $('#full_columns_list .single_column_state input').prop('checked', false ).change();
    });
    
    // Show filtering options
    $('body').on('click', '#show_filter_options', function(){
        
        $('#show_filter_options').hide();
        $('#hide_filter_options').show();
        
        $('.toggle_filter_options').slideDown(200);
        
        $('#hide_toggle_columns').hide();
        $('#show_toggle_columns').show();
        
        $('.toggle_columns_options').hide();
    });
    
    // Hide filtering options
    $('body').on('click', '#hide_filter_options', function(){
        
        $('#hide_filter_options').hide();
        $('#show_filter_options').show();
        
        $('.toggle_filter_options').slideUp(200);
    });
    
    // Reset filtering options
    $('#clear_filter_options').on('click', function(){
        
        $('.filter_select').val('default').change();
        $('#search_boxes .search_filter').val('').change();
    });
    
    // Show only the published items
    $('body').on('click', '#all_elements:not(.filtered)', function(){
        
        $('#all_elements').addClass("filtered");
        $('#trash_elements').removeClass("filtered");
        
        $('#the-list').addClass("filter_on__status_publish");
        $('#the-list').removeClass("filter_on__status_trash");
        
        $('#the-list .toggle_filter label').css('visibility', 'initial' );
        
        $('select#check_all_elements option[value="default"]').after('<option value="turn_off">Turn Off</option>');
        $('select#check_all_elements option[value="default"]').after('<option value="turn_on">Turn On</option>');
        $('select#check_all_elements option[value="restore"]').remove();
        $('select#check_all_elements option[value="delete"]').html('Trash');
        
        $('#check_all, #the-list input.main_selector').prop('checked', false );
    });
    
    // Show only the trashed items
    $('body').on('click', '#trash_elements:not(.filtered)', function(){
        
        $('#trash_elements').addClass("filtered");
        $('#all_elements').removeClass("filtered");
        
        $('#the-list').addClass("filter_on__status_trash");
        $('#the-list').removeClass("filter_on__status_publish");
        
        $('#the-list .toggle_filter label').css('visibility', 'hidden' );
        
        $('select#check_all_elements option[value="turn_on"]').remove();
        $('select#check_all_elements option[value="turn_off"]').remove();
        $('select#check_all_elements option[value="delete"]').before('<option value="restore">Restore</option>');
        $('select#check_all_elements option[value="delete"]').html('Permanently Delete');
        
        $('#check_all, #the-list input.main_selector').prop('checked', false );
    });
        
    // Filter by date
    $('body').on('change', '#filter_by_date', function(){
        
        let filter = $('#filter_by_date').val();
        
        $('#the-list > *').removeClass("filtered_out__date");
        
        if( filter != "default" ){
            
            $(`#the-list > *:not([data-date="${filter}"])`).addClass("filtered_out__date");
        }
        
        $('#check_all, #the-list input.main_selector').prop('checked', false );
    });
    
    // Filter by type (filters only)
    $('body').on('change', '#filter_by_type', function(){
        
        let filter = $('#filter_by_type').val();
        
        $('#the-list > *').removeClass("filtered_out__type");
        
        if( filter != "default" ){
            
            $('#the-list > tr').each(function(){
                
                let is_premium = ( $(this).find('.filter_is_premium').length >= 1 );
                
                if( ( is_premium && filter == 'free' ) || ( ! is_premium && filter == 'premium' ) ){
                    
                    $(this).addClass("filtered_out__type");
                }
                
            });
            
        }
        
        $('#check_all, #the-list input.main_selector').prop('checked', false );
    });
    
    // Filter by trigger (filters only)
    $('body').on('change', '#filter_by_trigger', function(){
        
        let filter = $('#filter_by_trigger').val();
        
        $('#the-list > *').removeClass("filtered_out__trigger");
        
        if( filter != "default" ){
            
            $(`#the-list > *:not([data-type="${filter}"])`).addClass("filtered_out__trigger");
        }
        
        $('#check_all, #the-list input.main_selector').prop('checked', false );
    });
    
    // Filter by state (filters only)
    $('body').on('change', '#filter_by_state', function(){
        
        let filter = $('#filter_by_state').val();
        
        $('#the-list > *').removeClass("filtered_out__state");
        
        if( filter == "turned_on" ){
            
            $('#the-list .turn_off_filter:not(:checked)').parents('.block_info').addClass("filtered_out__state");
        }
        
        if( filter == "turned_off" ){
            
            $('#the-list .turn_off_filter:checked').parents('.block_info').addClass("filtered_out__state");
        }
        
        $('#check_all, #the-list input.main_selector').prop('checked', false );
    });
    
    // Filter by search box
    $('body').on('keyup paste change', '.search_filter', function(){
        
        let search = $(this).val();
        let type   = $(this).parent().data('label');
        
        // console.log( "search: ", search );
        // console.log( "type: ", type );
        
        $('#the-list > *').removeClass("filtered_out__search_" + type );
        
        if( search ){
            
            let low_search = search.toLowerCase();
            
            $(`#the-list > tr > [data-label="${type}"]`).each(function(){
                
                let text = type == 'plugins_tooltip' ? $(this).children('span').attr('data-tooltip-list') : $(this).text();
                
                if( ! text || ! text.toLowerCase().includes( low_search ) ){
                    
                    $(this).parent().addClass("filtered_out__search_" + type );
                }
                
                // console.log( $(this).text() );
                
            });
        }
        
        $('#check_all, #the-list input.main_selector').prop('checked', false );
    });
    
    // Sort by list header items
    $('body').on('click', '.sort_able', function(){
        
        let sort = $(this).data('label');
        
        let was_active   = $(this).hasClass('sort_active');
        let was_reversed = $(this).hasClass('sort_reversed');
        
        $('.sort_able').removeClass('sort_reversed').removeClass('sort_active');
        
        $(this).addClass('sort_active').toggleClass('sort_reversed', was_active && ! was_reversed );
        
        let is_active   = $(this).hasClass('sort_active');
        let is_reversed = $(this).hasClass('sort_reversed');
        
        console.log( "Sorting by: ", sort, ", ", ( is_reversed ? "Reversed" : "Normal" ) );
        
        // sort
        let $container = $('#the-list');
        
        $container.children().sort( function( a, b ){
            
            let a_title = $(a).children('[data-label="' + sort + '"]').text().trim().toLowerCase();
            let b_title = $(b).children('[data-label="' + sort + '"]').text().trim().toLowerCase();
            
            let compared = a_title.localeCompare( b_title, undefined, {
                numeric: true,
                sensitivity: 'base'
            });
            
            return is_reversed ? 0 - compared : compared;
            
        }).prependTo( $container );
    });
        
    // Change appearance checkbox all elements
    $('body').on('change', '#the-list input.main_selector', function(){
        
        if($('#check_all').is( ":checked" )){
            $('#check_all').prop('checked', false);
        }
        if($('#the-list .block_info:not([class*="filtered_out__"]) input.main_selector').length === $('#the-list .block_info:not([class*="filtered_out__"]) input.main_selector:checked').length){
            $('#check_all').prop('checked', true);
        }
    });
    
    // Select all elements
    $('body').on('change', '#check_all', function(){
        
        if( $(this).is(":checked") ){
            
            $('#the-list .block_info:not([class*="filtered_out__"]) input.main_selector').prop('checked', true );
        } else {
            $('#the-list .block_info input.main_selector').prop('checked', false );
        }
    });
    
    // Bulk actions button (usually delete or restore element)
    $('body').on('click', '#btn_apply', function(){
        
        if( $('#check_all_elements').val() === 'default' ){
            
            alert("Select an action!");
            return;
        }

        let name_post_type;
        let data = false;
        
        if( $('#name_page').attr("class") === 'worklist' ){
            name_post_type = 'plgnoptmzr_work';
        } else if( $('#name_page').attr("class") === 'filters' ){
            name_post_type = 'plgnoptmzr_filter';
        } else if( $('#name_page').attr("class") === 'groups' ){
            name_post_type = 'plgnoptmzr_group';
        } else if( $('#name_page').attr("class") === 'categories' ){
            name_post_type = 'cat';
        }
        
        let selected_ids = $('#the-list input:checked').toArray().map( item => item.id ).filter( id => id );
        
        if( selected_ids.length < 1 ){
            
            alert("Select some items!");
            return;
        }

        if( $('#check_all_elements').val() === 'delete' ){
            
            data = {
                action          : 'po_delete_elements',
                'name_post_type': name_post_type,
                'type_elements' : ( $('#all_elements').hasClass('filtered') ? 'all' : 'trash' ),
                'id_elements'   : selected_ids,
            };
            
        } else if( $('#check_all_elements').val() === 'restore' ){
        
            data = {
                action          : 'po_publish_elements',
                'name_post_type': name_post_type,
                'id_elements'   : selected_ids,
            };
            
        } else if( $('#check_all_elements').val() === 'turn_on' ){
        
            data = {
                action          : 'po_turn_filter_on',
                'name_post_type': name_post_type,
                'id_elements'   : selected_ids,
            };
            
        } else if( $('#check_all_elements').val() === 'turn_off' ){
        
            data = {
                action          : 'po_turn_filter_off',
                'name_post_type': name_post_type,
                'id_elements'   : selected_ids,
            };
            
        }
        
        // console.log( "Bulk: ", data );
        // console.log( "selected_ids: ", selected_ids );
        
        if( data ){
            
            $.ajax({
                url     : po_object.ajax_url,
                type    : 'POST',
                data    : data,
                success : function (response) {
                    if( response.success ){

                        // reset the filters
                        $('#bulk_actions select').val('default');
                        $('#bulk_actions button:not(#btn_apply)').click();
                        // TODO reset the latest filters
                        
                        
                        $('#check_all').prop('checked', false);
                        
                        let publish     = ( data.action == 'po_publish_elements' );
                        let trash       = ( data.action == 'po_delete_elements' && data.type_elements == 'all' );
                        let remove      = ( data.action == 'po_delete_elements' && data.type_elements == 'trash' );
                        let turned_on   = ( data.action == 'po_turn_filter_on' );
                        let turned_off  = ( data.action == 'po_turn_filter_off' );
                            
                        $.each( selected_ids, function( index, id ){
                            
                            $('input#' + id ).parents('.block_info').children('td:first-of-type').children('input:checked').prop('checked', false );
                            
                            if( publish || trash ){
                                $('input#' + id ).parents('.block_info').attr("data-status", ( publish ? "publish" : "trash" ) );
                            } else if( remove ){
                                $('input#' + id ).parents('.block_info').remove();
                            } else if( turned_on ){
                                $('input#' + id ).parents('.block_info').find('input.turn_off_filter').prop('checked', true );
                            } else if( turned_off ){
                                $('input#' + id ).parents('.block_info').find('input.turn_off_filter').prop('checked', false );
                            }
                            
                        });
                    }
                    
                    alert( response.data.message );
                    
                    $('#count_all_elements').html( $('#the-list > [data-status="publish"]').length );
                    $('#count_trash_elements').html( $('#the-list > [data-status="trash"]').length );
                    
                }
                
            });
            
        }
    });

    // Overview page - toggle free bootcamp
    $('body.po_page_overview').on('click', '.bootcamp_header .toggler', function(){
        
        if( $(this).parent().hasClass("closed") ){
            
            $(this).parent().removeClass("closed").addClass("opened");
            $(this).parents('.bootcamp').find('.bootcamp_content').slideDown();
            
        } else {
            
            $(this).parent().addClass("closed").removeClass("opened");
            $(this).parents('.bootcamp').find('.bootcamp_content').slideUp();
            
        }
    });
    
    // Overview page - toggle different sections
    $('body').on('click', '.tab_header', function(){
        
        if( $(this).next('.hidden-info_overview').hasClass("closed") ){
            
            $(this).next('.hidden-info_overview').slideDown().removeClass("closed").addClass("opened");
            $(this).children('.trigger').removeClass('trigger_closed').addClass('trigger_opened');
            
        } else {
            
            $(this).next('.hidden-info_overview').slideUp().addClass("closed").removeClass("opened");
            $(this).children('.trigger').addClass('trigger_closed').removeClass('trigger_opened');
            
        }
    });
    
    // switch between tabs menu pages
    $('body').on('click', '#window_filters, #window_categories, #window_groups, #window_worklist, #window_settings, #window_premium', function(){
        
        $('#main_tab_navigation > div.tabs').removeClass('current');
        
        $(this).addClass('current');

        const selfId = $(this).attr('id');

        switch (selfId) {
            case 'window_filters':
            
        // TODO SWITCH

                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_filters';
                // location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer';
                break;
            case 'window_categories':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_filters_categories';
                break;
            case 'window_groups':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_groups';
                break;
            case 'window_worklist':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_worklist';
                break;
            case 'window_settings':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_settings';
                break;
            case 'window_premium':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_premium';
                break;
        }
    });
    
    // Overview: mark tab complete
    $('body').on('click', '.mark_tab_complete', function(){
        
        let tab_id = $(this).parents('.hidden-info_overview').data('id').replace('tab_', '');
        
        // console.log( "Tab ID: ", tab_id );
        
        $(this).parents('.hidden-info_overview').slideUp( 400, function(){
            
            $('#tab_' + tab_id + ' .info-passage').addClass('done');
            $('#tab_' + tab_id + ' .trigger').removeClass('trigger_opened').addClass('trigger_closed');
            $(this).removeClass("opened").addClass("closed").attr("style", "");
            
        });
        
        $(this).remove();
        
        $.post( po_object.ajax_url, { action : 'po_mark_tab_complete', tab_id : tab_id, user_id : po_object.user_id }, function( response ) {
            // console.log( "po_mark_tab_complete: ", response );
            
            if( response.data.message ){
                
                
            }
            
        }, "json");
    });
    
    // Overview: mark tab complete
    $('body').on('change', '#should_alphabetize_menu', function(){
        
        $('body').addClass("po_is_recreating_menu").append('<div id="po_please_wait"><div id="po_please_wait_message">Please wait...</div></div>');
        
        let should = $(this).prop('checked');
        
        // console.log( "should: ", should );
        
        $.post( po_object.ajax_url, { action  : 'po_save_option_alphabetize_menu', should_alphabetize : should }, function( response ) {
            // console.log( "po_save_option_alphabetize_menu: ", response );
            
            if( response.data.message ){
                
                window.location.href = $('#wp-admin-bar-plugin_optimizer_recreate_the_menu a').attr("href");
            }
            
        }, "json");
    });
    
    // Filters List screen: turn filter on/off
    $('body').on('change', '#the-list .turn_off_filter', function(){
        
        let el = $(this);
        let turned_off = ! $(this).prop('checked');
        let post_id    = $(this).data('id');
        
        // console.log( "post_id: ", post_id );
        // console.log( "turned_off: ", turned_off );
        
        $.post( po_object.ajax_url, { action  : 'po_turn_off_filter', turned_off : turned_off, post_id : post_id }, function( response ) {
            // console.log( "po_save_option_alphabetize_menu: ", response );
            
            if( !turned_off && !response.success){

                el.prop('checked',false);
                alert(response.data.message);
            }

            if( response.data.message ){
                
                
            }
            
        }, "json");
    });
    
    // Filters List screen: Save columns state
    $('body').on('click', '#save_columns_state', function(){
        
        let columns_state = [];
        
        columns_state = columns_state.concat(
            $('#full_columns_list input[type=checkbox]:not(:checked)').map(function(){
                return this.name;
            }).get()
        );
        
        console.log( "save_columns_state-columns_state: ", columns_state );
        
        $.post( po_object.ajax_url, { action  : 'po_save_columns_state', data : columns_state }, function( response ) {
            console.log( "po_save_columns_state: ", response );
            
            alert( response.data.message );
            
            if( response.success ){
                
                $('#hide_toggle_columns').hide();
                $('#show_toggle_columns').show();
                
                $('.toggle_columns_options').slideUp(200);
                
            }
            
        }, "json");
    });

    // This is approve button in the filter table row
    if( $('.inline-approval-button').length ){

      $(document).on('click','.inline-approval-button', function(){

          var r = confirm("Are you sure you want to approve this filter?");

          if (r == true) {

            var el = this;
            $.ajax({
              url : po_object.ajax_url,
              type: 'POST',
              dataType: 'json',
              data: {
                action: 'PO_send_approval',
                filter_id: $(el).data('filter_id')
              },
              success: function(d){
                if( d.status == 'success' ){
                  var text = $(el).closest('tr').find('td[data-label="status"]').text()
                  var amended = text.replace('Pending', 'Approved<br />');

                  $(el).closest('tr').find('td[data-label="status"]').html(amended)
                  $(el).addClass('inline-pending-button');
                  $(el).removeClass('inline-approval-button');
                  $(el).text('Make Pending');
                }
              },
              complete: function(){

              }
            });
          }

      });
    }
    
    // this is the pending button in the same row
    if( $('.inline-pending-button').length ){

      $(document).on('click','.inline-pending-button', function(){

          var r = confirm("Are you sure you want to mark this filter as pending?");

          if (r == true) {

            var el = this;
            $.ajax({
              url : po_object.ajax_url,
              type: 'POST',
              dataType: 'json',
              data: {
                action: 'PO_send_pending',
                filter_id: $(el).data('filter_id')
              },
              success: function(d){
                if( d.status == 'success' ){
                  var text = $(el).closest('tr').find('td[data-label="status"]').text()
                  var amended = text.replace('Approved', 'Pending<br />');

                  $(el).closest('tr').find('td[data-label="status"]').html(amended);
                  $(el).addClass('inline-approval-button');
                  $(el).removeClass('inline-pending-button');
                  $(el).text('Approve');
                }
              },
              complete: function(){

              }
            });
          }
      });
    }

    // SYNC FILTERS
    // ----------------------------------------------
    if( $( "#sync-form" ).length > 0 ){
          
      if( typeof window.po === 'undefined' ){
        
        window.po = [];
      
        window.po.show_loading = function(el){
            // $(el).append('<img id="loadingif" style="width: 15px; margin-left: 5px;" src="<?php echo plugin_dir_url( __DIR__ )?>partials/loading.gif" />');
            console.log(el);
            $(el).text();
            $(el).text('Loading...');
        }

        window.po.hide_loading = function(){
            // $('img#loadingif').remove();
            $('#loading_text').remove();
        }

        window.po.retrieve_filters = function(el){

            var belongsTo = $('#belongsTo').val();

            $.ajax({
                url: po_object.ajax_url,
                type: 'GET',
                data: {
                    action: 'PO_retrieve_filters',
                    belongsTo: belongsTo
                },
                dataType: 'json',
                beforeSend: function(){
                    window.po.show_loading(el);
                    $('.PO-loading-container').show();
                },
                success: function(d){
                    // console.log( 'PO_retrieve_filters: ', d );
                    if( d.status == 'success' ){
                        window.po.success_message('All premium filters are successfully retrieved.');
                        location.reload();
                    } else {
                        window.po.success_message(d.message);
                    }
                },
                complete: function(){
                    window.po.hide_loading();
                    $('.PO-loading-container').hide();
                }
            });
        }

        window.po.success_message = function(message){

            alert(message);
        }

        // Get the modal
        var modal = document.getElementById("myModal");

        if( $('#filters_list__sync_now').length ){

          // Get the button that opens the modal
          var btn = document.getElementById("filters_list__sync_now");

          // When the user clicks on the button, open the modal
          btn.onclick = function() {
            modal.style.display = "block";
          }
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("scan-modal-close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        } 

        $('button.scan-modal-scan').on('click', function(){
            modal.style.display = "none";
            var el = $('#filters_list__sync_now');
            window.po.retrieve_filters(el[0]);
        });
      }

    } else {

      // If the user has Premium (and) activated, he can pull Premium Filters
      $('#filters_list__sync_now').on('click', function(){
          var el = this;
          window.po.retrieve_filters(el);
      });
      
    }    

    // OPERATIONS
    // ----------------------------------------------
    if( $('#rollback-filter').length ){

      function rollback_filter(el){ 

        $.ajax({
          url: po_object.ajax_url,
          data: {
            action: 'PO_rollback_filter',
            post_id : getParameterByName('filter_id')
          },
          type: 'POST',
          dataType: 'json',
          beforeSend: function(){
            $(el).text('Retrieving...')
          },
          success: function(data){
            $(el).text('Rollback Filter');
            if( data.status != 'success' ){
              alert(data.message);
            } else {
              //window.location.reload();
            }
          }
        })
      }

      $('#rollback-filter').on('click', function(){
          var el = this;

          var r = confirm("Are you sure you want to rollback this filter?");
          if( r == true ){
            rollback_filter(el);
          }
      });
    }
    
    // HELPERS
    // ----------------------------------------------
    function getParameterByName(name, url = window.location.href) {
      name = name.replace(/[\[\]]/g, '\\$&');
      var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
          results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function success_message(action){

        alert('All premium filters are successfully '+action);
    }    
    
    // check if all plugins from groups have been manually enabled
    function check_group_plugins_state(){
        
        // TODO Once a group is selected, you can manually re-enable each plugin and if all are enabled, we should deselect the group
    }
    
    // we need to calculate the rows and columns of .special_grid_list
    function recalculate__special_grid_lists( columns = 3 ){
        
      $('.special_grid_list').each(function( index ){
          
          let $grid_list = $(this);
          
          let items_no   = $grid_list.children().length;
          
          let rows       = Math.ceil( items_no / columns );
                    
          $grid_list.css("grid-template-columns", "repeat(" + columns + ", 1fr  )" );
          $grid_list.css("grid-template-rows",    "repeat(" + rows    + ", auto )" );
          
      });
    }
    
    $('.trashbutton').on('click', function(){
      var table_row = $(this).closest('tr');
      var id = $(table_row).attr('id').split('-');
      var id = id[1];

      // need to send delete request to server
      var yes = confirm('Are you sure you want to delete this filter? Wordpress will place in trash, but will delete from PO Dictionary');

      if( yes ){

        $.ajax({
          url: po_object.ajax_url,
          data: {
            action: 'PO_delete_filter',
            post_id: id
          },
          type: 'POST',
          dataType: 'json',
          success: function(d){

            // on success, need to delete on wordpress
            if( d.status == 'success' ){

              delete_filter_from_wp(id, table_row);
            } else {

              alert(d.message);              
              delete_filter_from_wp(id, table_row);
            }
          }
        });
      }

    });

    function delete_filter_from_wp(post_id, table_row){

      $.ajax({
        url : po_object.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
          action: 'po_delete_elements',
          name_post_type: 'plgnoptmzr_filter',
          type_elements: 'all',
          id_elements: [post_id],
        },
        success: function(d){
          if( d.success ){
            if( typeof table_row !== 'undefined' ){
              $(table_row).remove();
            } else {
              window.location.reload();
            }
          }
        }
      });
    }

    $('tr').on('click', 'a.duplicate', function(){

      if( confirm("Are you sure you want to duplicate this filter?") ){

        var the_row = $(this).closest('tr');
        var the_row_clone = $(the_row).clone();

        $.ajax({
          url: po_object.ajax_url,
          type: 'POST',
          dataType: 'json',
          data: {
            action: 'po_duplicate_filter',
            filter: $(the_row).attr('id').substr(7)
          },
          success: function(d){

            if( d.status == 'success' ){
              $(the_row_clone).find('a.edit_item').attr('href',po_object.admin_url+'admin.php?page=plugin_optimizer_add_filters&filter='+d.filter_id);
              var copy_text = $(the_row_clone).find(".filter_title").text() + ' Copy';
              $(the_row_clone).find(".filter_title").text(copy_text);
              $(the_row_clone).find(".endpoint-column").text('');
              $(the_row_clone).insertAfter(the_row);

              alert("Filter duplicated successfully! Note: You must define a unique trigger for this filter");
            }
            // 
          }
        });
        }

    });

    $("#po_update_database_button").on("click", function(){
      var el = this;
      if( confirm("This will update your database for the newest Plugin Optimizer version. Continue?") ){

        $.ajax({
          url : po_object.ajax_url,
          type: "POST",
          dataType: "json",
          data: {
            action : "po_update_database"
          },
          success: function(d){
            alert("Your database has been updated successfully");

            $(el).closest(".notice").find("button.notice-dismiss").click();
          }
        });

      }
    });
    // DEPRECATED: Leave for parts;
    // On agent sites, we need to get the premium filters status from the dictionary
    /*function fetch__agent_filters_status(){
        
        if( ! ( $('body').hasClass('plugin-optimizer_page_plugin_optimizer_filters') && $('#the-list td[data-label="status"]').length >= 1 ) ){
            
            return;
        }
        
        console.log( "Agent plugin found - fetching filters status." );
        
        let dictionary_ids = [];
        
        $('#the-list td[data-label="status"]').each(function( index, el ){
            
            let dictionary_id = $(el).data("id");
            
            if( ! dictionary_id ){
                
                $(el).html('');
            } else {
                
                dictionary_ids.push( dictionary_id );
            }
            
        });
        
        if( dictionary_ids.length == 0 ){
            
            return;
        }
        
        // console.log( "Dictionary IDs: ", dictionary_ids );
        
        $.post( po_object.ajax_url, { action : 'po_get_premium_filters_status', dictionary_ids : dictionary_ids }, function( response ) {
            // console.log( "po_mark_tab_complete: ", response );
            
            if( response.data.message ){
                
                $('#the-list td[data-label="status"][data-id]').html('!<br/>&nbsp;');
                
                // console.log( "#1293175193: ", response.data );
                
                $.each( response.data.statuses, function( index, item ){
                    
                    // console.log( "#873563456 Item: ", item );
                    
                    let status = item.status == "approved" ? "Approved" : "Pending";
                    
                    $('#the-list td[data-label="status"][data-id="' + item.id + '"]').html( status + '<br/>&nbsp;');
                    
                });
            }
            
        }, "json");
    }*/
    
});