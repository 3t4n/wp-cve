jQuery(window).on('popstate', function(event) {
    console.log(event);
    if ( event.originalEvent.state != null && event.originalEvent.state.BeRocket == 'Compare' ) {
        location.reload();
    }
});
(function ($){
    $(document).ready( function () {
        $(document).on( 'click', '.br_compare_button .fa', function ( event ) {
            $button = $(this).parent();
            event.preventDefault();
            event.stopPropagation();
            var id = $button.data('id');
            toggle_products_compare(id);
            load_selected_list();
        });
        $(document).on( 'click', '.br_compare_button', function ( event ) {
            if ( add_products_compare($(this).data('id')) ) {
                event.preventDefault();
                load_selected_list();
            } else {
                if( $(this).is('.berocket_product_smart_compare') && $('.berocket_compare_box').length == 0 ) {
                    event.preventDefault();
                    load_smart_compare_table();
                }
            }
        });
        $(document).on( 'click', '.br_remove_compare_product', function ( event ) {
            event.preventDefault();
            var $parent = $(this).parents('.berocket_compare_widget');
            remove_products_compare($(this).data('id'));
            $('.br_widget_compare_product_'+$(this).data('id')).remove();
            has_compared_products( $parent.find('ul li').length );
            if( typeof(br_compare_page) != 'undefined' ) {
                location.replace(br_compare_page);
            }
        });
        function set_page_to_history() {
            if( typeof(br_compare_page) != 'undefined' && typeof(history) != 'undefined' ) {
                var stateParameters = {BeRocket: 'Compare'};
                history.replaceState(stateParameters, "BeRocket Rules");
                history.pushState(stateParameters, "BeRocket Rules", br_compare_page);
                history.pathname = br_compare_page;
            }
        }
        $(document).on( 'click', '.br_remove_compare_product_reload', function ( event ) {
            event.preventDefault();
            remove_products_compare($(this).data('id'));
            if($(this).parents('#br_popup').length > 0) {
                var $popup_object = $(this).parents('#br_popup').data('br_popup_main');
                jQuery($popup_object).br_popup().hide_popup();
                load_selected_list();
                load_smart_compare_table($popup_object);
                set_page_to_history();
            } else {
                location.replace(br_compare_page);
            }
        });
        $(document).on('click', '.br_remove_all_compare', function(event) {
            event.preventDefault();
            $(this).parents('.br_new_compare_block').first().find('.br_remove_compare_product_reload').each(function() {
                remove_products_compare($(this).data('id'));
            });
            if($(this).parents('#br_popup').length > 0) {
                var $popup_object = $(this).parents('#br_popup').data('br_popup_main');
                jQuery($popup_object).br_popup().hide_popup();
                load_selected_list();
                load_smart_compare_table($popup_object);
                set_page_to_history();
            } else {
                location.replace(br_compare_page);
            }
        });
        function load_selected_list() {
            if ( $('.berocket_compare_widget').length > 0 ) {
                compare_products_execute_func( the_compare_products_data.user_func.before_load );
                jQuery(document).trigger('berocket_compare-before_load');
                var data = {action: 'br_get_compare_products'};
                $.post( the_compare_products_data.ajax_url, data, function ( data ) {
                    var $data = $(data).find('.berocket_compare_widget');
                    var $data_text = $(data).find('.berocket_compare_widget');
                    $data_text.find('img').remove();
                    $('.berocket_compare_widget').each( function ( i, o ) {
                        if ( $(o).data('type') == 'text' ) {
                            $(o).html($data_text.html());
                        } else {
                            $(o).html($data.html());
                        }
                        if( $(o).data('fast_compare') ) {
                            $(o).find('.berocket_open_compare').addClass('berocket_open_smart_compare');
                        }
                    });
                    has_compared_products( $data.find('ul li').length );
                    compare_products_execute_func( the_compare_products_data.user_func.after_load );
                    jQuery(document).trigger('berocket_compare-after_load');
                });
            }
        }
        function has_compared_products( count ) {
            if ( count > 0 ) {
                $('.berocket_show_compare_toolbar').show();
            } else {
                $('.berocket_compare_widget_toolbar').hide();
                $('.berocket_show_compare_toolbar').hide();
                $('.berocket_open_compare').remove();
            }
            BeRocket_hide_compare_empty_widget();
        }
        $(document).on( 'click', '.berocket_show_compare_toolbar', function ( event ) {
            event.preventDefault();
            $('.berocket_compare_widget_toolbar').show();
        });
        $(document).on( 'click', '.berocket_hide_compare_toolbar', function ( event ) {
            event.preventDefault();
            $('.berocket_compare_widget_toolbar').hide();
        });
        $(document).on( 'click', '.berocket_back_full_screen, .berocket_compare_box .berocket_normal_size', function ( event ) {
            event.preventDefault();
            $('.berocket_compare_box').removeClass('berocket_full_screen_box');
            $('.berocket_back_full_screen').remove();
            $('.berocket_compare_box .berocket_normal_size').remove();
            setTimeout( berocket_load_compare_table, 100 );
        });
        $(document).on('click', '.berocket_open_smart_compare', function(event) {
            event.preventDefault();
            load_smart_compare_table();
        });
        $('body').append($('.berocket_compare_widget_toolbar_block'));
        function BeRocket_hide_compare_empty_widget() {
            $('.berocket_compare_widget_start').each(function() {
                if( $(this).find('.berocket_compare_widget ul li').length ) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        BeRocket_hide_compare_empty_widget();
        //FROM COMPARE TABLE FILE
        if( the_compare_products_data.hide_same_default ) {
            jQuery(".br_same_attr").hide();
        }
        jQuery(document).on('click', '.br_show_compare_dif', function(event) {
            event.preventDefault();
            var $parent = $(this).parents('.br_new_compare_block').first();
            if( $(this).is('.br_hidden_same') ) {
                $parent.find('.br_same_attr').show();
                $(this).removeClass('br_hidden_same').text(the_compare_products_data.hide_same);
            } else {
                $parent.find('.br_same_attr').hide();
                $(this).addClass('br_hidden_same').text(the_compare_products_data.show_same);
            }
        });
        $(window).scroll( function () {
            $('.br_new_compare_block_wrap').each(function() {
                if(! $(this).find('.br_new_compare_block').is('.br_full_size_popup') && $(this).find('.br_new_compare_block').length > 0 ) {
                    if( jQuery(window).width() > 767 ) {
                        var top = jQuery(window).scrollTop() - $(this).find('.br_new_compare_block .br_top_table').offset().top + parseInt(the_compare_products_data.toppadding);
                        if( jQuery('#wpadminbar').length ) {
                            top += jQuery('#wpadminbar').height();
                        }
                        var max_top = $(this).find('.br_new_compare_block').height() - $(this).find('.br_new_compare_block .br_main_top').height() - parseInt(the_compare_products_data.toppadding);
                        if ( top < 0 ) {
                            top = 0;
                        } else if ( top > max_top ) {
                            top = max_top;
                        }
                    } else {
                        var top = 0;
                    }
                    $(this).find('.br_new_compare_block .br_main_top').css('top', top );
                }
            });
        });
        berocket_popup_compare_scroll_fix();
    });
})(jQuery);
var load_smart_compare_table_interval;
function load_smart_compare_table($popup_object) {
    if( typeof($popup_object) == 'undefined' ) {
        $popup_object = jQuery(the_compare_products_data.compare_selector);
    }
    jQuery('.br_compare_popup').remove();
    jQuery.post(the_compare_products_data.ajax_url, {action:'br_get_compare_list'}, function(data) {
        clearInterval(load_smart_compare_table_interval);
        if( data && jQuery(data).find('.br_compare_popup_block').length ) {
            jQuery('.br_compare_popup_block, '+the_compare_products_data.compare_selector).html(jQuery(data).find('.br_compare_popup_block').html());
            if( the_compare_products_data.hide_same_default ) {
                jQuery('.br_compare_popup_block, '+the_compare_products_data.compare_selector).find(".br_same_attr").hide();
            }
            load_smart_compare_table_interval = setInterval(function() {
                if( ! $popup_object.data('br_popup_data') || ! $popup_object.data('br_popup_data').opened ) {
                    $popup_object.br_popup().open_popup();
                    clearInterval(load_smart_compare_table_interval);
                }
            }, 100);
        } else {
            jQuery('.br_compare_popup_block, '+the_compare_products_data.compare_selector).html("");
            jQuery('.br_new_compare_block_wrap').hide();
        }
    });
}
function toggle_products_compare( product ) {
    var products = set_get_products_compare_cookie();
    if ( ! products || products.search( new RegExp("(^" + product + "$)|(^" + product + ",)|(," + product + "$)|(," + product + ",)") ) == -1 ) {
        add_products_compare( product );
        return true;
    } else {
        remove_products_compare( product );
        return false;
    }
}
function add_products_compare( product ) {
    var products = set_get_products_compare_cookie();
    jQuery('.br_product_' + product).addClass('br_compare_added');
    jQuery('.br_product_' + product).find('span.br_compare_button_text').text(jQuery('.br_product_' + product).find('span.br_compare_button_text').data('added'));
    if ( ! products || products.search( new RegExp("(^" + product + "$)|(^" + product + ",)|(," + product + "$)|(," + product + ",)") ) == -1 ) {
        if ( products !== false && products ) {
            products = products + ',' + product;
        } else {
            products = '' + product;
        }
        set_get_products_compare_cookie( products );
        return true;
    } else {
        set_get_products_compare_cookie( products );
        return false;
    }
}
function remove_products_compare( product ) {
    compare_products_execute_func( the_compare_products_data.user_func.before_remove );
    jQuery(document).trigger('berocket_compare-before_remove');
    var products = set_get_products_compare_cookie();
    if ( products !== false && products ) {
        products = products.replace( new RegExp("(^" + product + "$)|(^" + product + ",)|(," + product + "$)"), '' );
        products = products.replace( new RegExp("," + product + ","), ',' );
    }
    jQuery('.br_product_' + product).removeClass('br_compare_added');
    jQuery('.br_product_' + product).find('span.br_compare_button_text').text(jQuery('.br_product_' + product).find('span.br_compare_button_text').data('not_added'));
    set_get_products_compare_cookie(products);
    compare_products_execute_func( the_compare_products_data.user_func.after_remove );
    jQuery(document).trigger('berocket_compare-after_remove');
    return products;
}
function set_get_products_compare_cookie ( value ) {
    if ( typeof value === "undefined" ) {
        value = false;
    }
    if ( value === false ) {
        if ( jQuery.cookie( 'br_products_compare' ) ) {
            return jQuery.cookie( 'br_products_compare' );
        } else {
            return false;
        }
    } else {
        /*var path = the_compare_products_data.home_url;
        path = path.split(document.domain);
        path = path[1];
        if ( path == '' ) {
            path == '/';
        }*/
        jQuery.cookie( 'br_products_compare', value, { path: '/', domain: document.domain } );
    }
}
function compare_products_execute_func ( func ) {
    if( the_compare_products_data.user_func != 'undefined'
        && the_compare_products_data.user_func != null
        && typeof func != 'undefined' 
        && func.length > 0 ) {
        try{
            eval( func );
        } catch(err){
            alert('You have some incorrect JavaScript code (Product Compare)');
        }
    }
}
function berocket_popup_compare_scroll_fix() {
    jQuery('.br_popup_inner:not(.br_compare_scroll_ready), .br_new_compare_block:not(.br_compare_scroll_ready)').each(function() {
        if( jQuery(this).is('.br_popup_inner') ) {
            jQuery(this).scroll(function() {
                var $parent = jQuery(this);
                if( jQuery(window).width() > 767 ) {
                    var top = jQuery(this).position().top - $parent.find('.br_top_table').position().top;
                    if ( top < 0 ) {
                        top = 0;
                    }
                } else {
                    var top = 0;
                }
                $parent.find('.br_main_top').css('top', top );
            });
        }
        jQuery(this).find('.br_main_top').mousewheel( function( event ) {
            var $parent = jQuery(this).parents('.br_popup_inner, .br_new_compare_block').first();
            event.preventDefault();
            event.stopPropagation();
            var scroll = $parent.find('.br_main_top').scrollLeft() - ( ( event.deltaY + event.deltaX ) * event.deltaFactor );
            $parent.find('.br_main_top').scrollLeft(scroll);
            $parent.find('.br_main_top').scroll();
        });
        jQuery(this).find('.br_main_top, .br_right_table').scroll( function() {
            var $parent = jQuery(this).parents('.br_popup_inner, .br_new_compare_block').first();
            var table_hidden_scroll = $parent.find('.br_new_compare_block').data('table_hidden_scroll');
            var table_scroll = $parent.find('.br_new_compare_block').data('table_scroll');
            var left_initial = jQuery(this).scrollLeft();
            var set_size = 0;
            if( jQuery(this).is('.br_main_top') ) {
                var check_scroll_table = table_hidden_scroll;
                set_size = jQuery(this).parents('.br_new_compare_block').find('.br_right_table table').width();
            } else {
                var check_scroll_table = table_scroll;
                set_size = jQuery(this).parents('.br_new_compare_block').find('.br_main_top table').width();
            }
            if( check_scroll_table != left_initial ) {
                var this_size = jQuery(this).find('table').width();
                left = set_size / this_size * left_initial;
                if( left < 0 ) {
                    left = left - 0.5;
                } else {
                    left = left + 0.5;
                }
                left = parseInt(left);
                if( jQuery(this).is('.br_main_top') ) {
                    table_hidden_scroll = left_initial;
                    table_scroll = left;
                    jQuery(this).parents('.br_new_compare_block').find('.br_right_table').scrollLeft(left); 
                } else {
                    table_hidden_scroll = left;
                    table_scroll = left_initial;
                    jQuery(this).parents('.br_new_compare_block').find('.br_main_top').scrollLeft(left); 
                }
            }
            $parent.find('.br_new_compare_block').data('table_hidden_scroll', table_hidden_scroll);
            $parent.find('.br_new_compare_block').data('table_scroll', table_scroll);
        });
        jQuery(this).addClass('br_compare_scroll_ready');
    });
}
