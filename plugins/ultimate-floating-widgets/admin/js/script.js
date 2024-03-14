(function($){
$(document).ready(function(){
    
    var delete_ctext = 'Are you sure want to delete this widget box ?';
    
    var init = function(){
        
        if( $.fn.conditioner ){
            $('[data-conditioner]').conditioner();
        }
        
        if( $.fn.wpColorPicker ){
            $('.color_picker').wpColorPicker();
        }
        
        tabs();

        ufw_init_image_selects();

    }
    
    var remove_animate_class = function($ele){
        $ele.removeClass(function(index, className){
            return (className.match (/(^|\s)animate__\S+/g) || []).join(' ');
        });
    }

    var auto_close_preview = function(){
        clearTimeout(window.anim_preview);
        window.anim_preview = setTimeout(function(){
            $('.ufw_anim_prev').fadeOut();
            clearTimeout(window.anim_preview);
        }, 5000);
    }

    $(document).on('click', '.ufw_delete_list', function(e){
        
        e.preventDefault();
        
        var del_btn = $(this);
        var href = del_btn.attr( 'href' );
        var confirm_user = confirm( delete_ctext );
        
        if( confirm_user ){
            
            var ajax = $.get( href );
            del_btn.addClass( 'spin' );
            
            ajax.done(function( data ){
                if( data.search( 'DELETED' ) != -1 ){
                    del_btn.closest( 'tr' ).fadeOut( 'slow', function(){
                        $(this).remove();
                    });
                }else{
                    alert( 'Delete failed ! - ' + data );
                }
            });
            
            ajax.fail(function(){
                alert( 'Auth failed !' );
            });
            
        }
        
    });
    
    $(document).on('click', '.ufw_delete_ep', function(e){
        
        e.preventDefault();
        
        var $delete_btn = $(this);
        var href = $delete_btn.attr( 'href' );
        var confirm_user = confirm( delete_ctext );
        
        if( confirm_user ){
            
            var ajax = $.get( href );
            $delete_btn.addClass( 'spin' );
            
            ajax.done(function( data ){
                if( data.search( 'DELETED' ) != -1 ){
                    var back_href = $( '.ufw_back_btn' ).attr( 'href' );
                    window.location = back_href + '&msg=3';
                }else{
                    alert( 'Delete failed ! - ' + data );
                }
            });
            
            ajax.fail(function(){
                alert( 'Auth failed !' );
            });
            
            $delete_btn.removeClass( 'spin' );
            
        }
        
    });
    
    $(document).on('change input', '.ufw_search', function(){
        
        var search_term = $(this).val();
        var re = new RegExp(search_term, 'gi');
        
        $('.ufw_name').each(function(){
            var txt = $(this).text();
            if(txt.match(re) === null){
                $(this).closest('tr').hide();
            }else{
                $(this).closest('tr').show();
            }
        });
    });
    
    $(document).on('change click', '.ufw_do_anim_prev', function(){
        clearTimeout(window.anim_preview);
        $('.ufw_anim_prev').show();
        $('.ufw_anim_obj').addClass('animate__animated animate__' + $(this).val());
        auto_close_preview();
    });

    $('.ufw_anim_obj').on('animationend', function(){
        clearTimeout(window.anim_preview);
        remove_animate_class($(this));
        auto_close_preview();
    })

    $('.ufw_pro_feature').on('click', function(){
        $(this).closest('td').find('.description').fadeIn();
    });

    var tabs = function(){

        $('.ufw_tabs li:first-child').addClass('active');
        $('.ufw_sec_wrap > section:first-child').addClass('active');

        $('.ufw_tabs a').click(function(e){

            e.preventDefault();

            var id = $(this).attr('href').substr(1);

            var $ufw_tabs = $(this).closest('.ufw_tabs');
            var $ufw_sec_wrap = $ufw_tabs.next('.ufw_sec_wrap');

            $ufw_sec_wrap.children('section').removeClass('active');
            $tab = $ufw_sec_wrap.find('[data-tab="' + id + '"]');
            $tab.addClass('active');

            $ufw_tabs.find('li').removeClass('active');
            $(this).parent().addClass('active');

        });

    }

    init();
    
});
})( jQuery );

function ufw_init_image_selects(){
    jQuery( '.img_select_list li' ).each(function(){
        $li = jQuery(this);
        if( $li.attr( 'data-init' ) == 'false' ){
            $li.on( 'click', function(){
                $the_li = jQuery(this);
                $parent = $the_li.parent();
                $org = $parent.prev();
                $parent.find( 'li' ).removeClass( 'img_opt_selected' );
                $the_li.addClass( 'img_opt_selected' );
                $org.val( $the_li.attr( 'data-value' ) );
                $org.trigger( 'change' );
            });
            $li.attr( 'data-init', 'true' );
        }
    });
}