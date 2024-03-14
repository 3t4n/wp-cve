(function($){
$(document).ready(function(){
    
    var init = function(){
        
        window.ufw_wrap_auto = {};
        
        $('.ufw_wrap').each(function(){
            
            var id = $(this).attr('id');
            var auto_trigger = $(this).attr('data-auto-trigger');
            var auto_close = $(this).attr('data-auto-close');
            var auto_close_time = $(this).attr('data-auto-close-time');
            var btn_reveal = $(this).attr('data-btn-reveal');
            var devices = $(this).attr('data-devices');
            var save_state = $(this).attr('data-save');

            window.ufw_wrap_auto[id] = {
                'status': $(this).hasClass('ufw_wb_opened') ? 'opened' : 'closed',
                'save_state': (typeof save_state !== 'undefined') ? save_state : false,
                'open_at': (typeof auto_trigger !== 'undefined') ? auto_trigger : false,
                'close_at': (typeof auto_close !== 'undefined') ? auto_close : false,
                'close_time': (typeof auto_close_time !== 'undefined') ? auto_close_time : false,
                'close_timeout': false,
                'btn_reveal': (typeof btn_reveal !== 'undefined') ? btn_reveal : false,
                'btn_status': 'visible'
            };

            var hidden = init_display($(this), auto_trigger, devices);

            if(hidden){
                return;
            }else{
                $(this).removeClass('ufw_wb_hidden');
            }

            var $btn = $(this).find('.ufw_btn');
            show_hide_btn($btn, 'show');

            if($(this).hasClass('ufw_wb_opened')){
                adjust_size($(this));
                $(this).find('.ufw_wb').css('display', 'block');
                do_auto_close_time($(this), 'start');
            }

            if(window.ufw_wrap_auto[id]['close_time']){
                $(this).on('mouseenter', function(){
                    do_auto_close_time($(this), 'stop')
                });
                $(this).on('mouseleave', function(){
                    do_auto_close_time($(this), 'start')
                });
            }

        });
        
        position_popups();

        on_scroll(0, 0);

        window.UFW = api();

    }
    
    var api = function(){
        
        return {
            'open': function(id){
                var $wrap = $('#ufw_' + id);
                if($wrap.length == 0){
                    return false;
                }else{
                    open_close_wb($wrap, 'open', 'manual');
                    return $wrap;
                }
            },
            'close': function(id){
                var $wrap = $('#ufw_' + id);
                if($wrap.length == 0){
                    return false;
                }else{
                    open_close_wb($wrap, 'close', 'manual');
                    return $wrap;
                }
            },
            'toggle': function(id){
                var $wrap = $('#ufw_' + id);
                if($wrap.length == 0){
                    return false;
                }else{
                    open_close_wb($wrap, 'toggle', 'manual');
                    return $wrap;
                }
            }
        };
    }

    var open_close_wb = function($wrap, action='toggle', by='auto'){
        
        var id = $wrap.attr('id');
        var $wb = $wrap.find('.ufw_wb');
        var open_anim = 'animate__animated animate__' + $wrap.attr('data-open-anim');
        var close_anim = 'animate__animated animate__' + $wrap.attr('data-close-anim');
        
        var open_class = 'ufw_wb_opened';
        var close_class = 'ufw_wb_closed';
        
        var $close_btn = $wrap.find('.ufw_close_btn');

        if($wrap.hasClass(open_class) && (action == 'close' || action == 'toggle')){
            
            if(close_anim.includes('none')){
                $wb.hide(0, function(){
                    after_open_close($wb, true);
                });
            }else{
                $wb.removeClass(open_anim);
                $wb.addClass(close_anim);
            }
            
            $wrap.addClass(close_class);
            $wrap.removeClass(open_class);
            
            $close_btn.fadeOut();

            window.ufw_wrap_auto[id]['status'] = 'closed';

            if(by == 'manual'){
                $wrap.data('closed_manually', true);
                if(window.ufw_wrap_auto[id]['save_state'] !== false){
                    set_cookie('ufw_status_' + id, 'closed', window.ufw_wrap_auto[id]['save_state']);
                }
            }

        }else if($wrap.hasClass(close_class) && (action == 'open' || action == 'toggle')){
            
            if(by == 'auto' && $wrap.data('closed_manually')){
                return;
            }

            $wb.show();

            do_auto_close_time($wrap, 'start');

            if(!open_anim.includes('none')){
                $wb.removeClass(close_anim);
                $wb.addClass(open_anim);
            }
            
            adjust_size($wrap);
            
            $wrap.removeClass(close_class);
            $wrap.addClass(open_class);
            
            $close_btn.fadeIn();

            window.ufw_wrap_auto[id]['status'] = 'opened';

            if(by == 'manual'){
                if(window.ufw_wrap_auto[id]['save_state'] !== false){
                    set_cookie('ufw_status_' + id, 'opened', window.ufw_wrap_auto[id]['save_state']);
                }
            }

        }
        
    }
    
    var show_hide_btn = function($btn, action='show'){
        
        if($btn.length == 0){
            return;
        }

        var id = $btn.closest('.ufw_wrap').attr('id');

        if(action == 'show'){
            $btn.fadeIn();
            window.ufw_wrap_auto[id]['btn_status'] = 'visible';

        }else if(action == 'hide'){
            $btn.fadeOut();
            window.ufw_wrap_auto[id]['btn_status'] = 'hidden';
        }
        
    }
    
    var on_scroll = function(at, scrolled){
        
        for (var id in window.ufw_wrap_auto) {
            if (window.ufw_wrap_auto.hasOwnProperty(id)){
                
                var open_at = window.ufw_wrap_auto[id]['open_at'];
                var close_at = window.ufw_wrap_auto[id]['close_at'];
                var cur_status = window.ufw_wrap_auto[id]['status'];
                var btn_reveal = window.ufw_wrap_auto[id]['btn_reveal'];
                var btn_status = window.ufw_wrap_auto[id]['btn_status'];
                
                var $wrap = $('#'+id);
                var $btn = $wrap.find('.ufw_btn');
                
                if(open_at !== false){
                    if(at > open_at && cur_status == 'closed'){
                        if(close_at === false || (close_at !== false && at < close_at)){
                            open_close_wb($wrap, 'open');
                        }
                    }
                    else if(at < open_at && cur_status == 'opened'){
                        open_close_wb($wrap, 'close');
                    }
                }
                
                if(close_at !== false){
                    if(at > close_at){
                        open_close_wb($wrap, 'close');
                    }
                }

                if(btn_reveal !== false){
                    if(scrolled < btn_reveal && btn_status == 'visible' && cur_status != 'opened'){
                        show_hide_btn($btn, 'hide');
                        open_close_wb($wrap, 'close');
                    }else if(scrolled > btn_reveal && btn_status == 'hidden'){
                        show_hide_btn($btn, 'show');
                    }
                }
                
            }
        }
        
    }

    var position_popups = function(){
        
        postion_popup('.ufw_pp.ufw_p_br', 'right');
        postion_popup('.ufw_pp.ufw_p_bl', 'left');
        postion_popup('.ufw_pp.ufw_p_tr', 'right');
        postion_popup('.ufw_pp.ufw_p_tl', 'left');
        
    }
    
    var postion_popup = function(wb, position){
        
        var btn_offset = 16;
        
        $(wb).each(function(){
            
            if(typeof $(this).attr('data-hidden') !== 'undefined'){
                return;
            }

            $(this).css(position, btn_offset + 'px');
            
            var $btn = $(this).find('.ufw_btn');
            var btn_width = $btn.outerWidth();
            
            btn_offset += btn_width + btn_offset;
            
        });
    }
    
    var adjust_size = function($wrap){

        var $wb = $wrap.find('.ufw_wb');
        var is_popup = $wrap.hasClass('ufw_pp');
        var size = $wrap.attr('data-size').split('*');

        $wrap.outerWidth(size[0]);

        if(typeof $wb.data('orig-width') === 'undefined'){
            $wb.data('orig-width', $wb.outerWidth());
        }

        if(typeof $wb.data('orig-height') === 'undefined'){
            $wb.data('orig-height', $wb.outerHeight());
        }

        var orig_width = $wb.data('orig-width');
        var orig_height = $wb.data('orig-height');
        var width_pad = is_popup ? 50 : 0;

        // Set width (both popup and flyout)
        if(orig_width + width_pad > document.body.clientWidth){
            $wrap.outerWidth(document.body.clientWidth - width_pad);
        }else{
            $wrap.outerWidth(orig_width);
        }

        // Set height (only popup)
        if(is_popup){
            if( orig_height + 100 > window.innerHeight ){
                $wb.outerHeight(window.innerHeight - 100);
            }else{
                $wb.outerHeight(orig_height);
            }
        }

    }

    var remove_animate_class = function($ele){
        $ele.removeClass(function(index, className){
            return (className.match (/(^|\s)animate__\S+/g) || []).join(' ');
        });
    }

    var after_open_close = function($wb, no_anim=false){
        var $wrap = $wb.closest('.ufw_wrap');
        if($wrap.hasClass('ufw_wb_closed') || no_anim){
            $wb.hide();
            $wrap.width('auto');
        }
        remove_animate_class($wb);
    }

    var do_auto_close_time = function($wrap, action){

        var id = $wrap.attr('id');
        var auto_close_time = window.ufw_wrap_auto[id]['close_time'] * 1000;

        if(!auto_close_time){
            return;
        }

        if(action == 'stop'){
            if(window.ufw_wrap_auto[id]['close_timeout']){
                clearTimeout(window.ufw_wrap_auto[id]['close_timeout']);
                window.ufw_wrap_auto[id]['close_timeout'] = false;
            }
        }

        if(action == 'start' && window.ufw_wrap_auto[id]['status'] == 'opened'){
            window.ufw_wrap_auto[id]['close_timeout'] = setTimeout(function(){
                open_close_wb($wrap, 'close', 'auto');
                clearTimeout(window.ufw_wrap_auto[id]['close_timeout']);
                window.ufw_wrap_auto[id]['close_timeout'] = false;
            }, auto_close_time);
        }

    }

    var is_mobile = function(){
        return /Mobi|Android/i.test(navigator.userAgent);
    }

    var set_cookie = function(name, value, days){

        var expires = '';

        if (days != 0) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = '; expires=' + date.toUTCString();
        }

        document.cookie = name + '=' + (value || '') + expires + '; path=/';

    }

    var get_cookie = function(name){

        var name_eq = name + '=';
        var ca = document.cookie.split(';');

        for(var i=0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(name_eq) == 0) return c.substring(name_eq.length, c.length);
        }

        return null;

    }

    var init_display = function($ele, auto_trigger, devices){

        if(devices == 'mobile_only' && !is_mobile()){
            $ele.hide();
            return true; // Stay hidden
        }

        if(devices == 'desktop_only' && is_mobile()){
            $ele.hide();
            return true;
        }

        if(typeof auto_trigger !== 'undefined'){ // Auto trigger is set
            return false;
        }

        var id = $ele.attr('id');
        var todo = false;

        if( ( is_mobile() && $ele.attr( 'data-init-m' ) == 'opened' )
        || ( !is_mobile() && $ele.attr( 'data-init-d' ) == 'opened' ) ){
            todo = 'open';
        }

        if(window.ufw_wrap_auto[id]['save_state'] !== false){
            var cookie_status = get_cookie('ufw_status_' + id);

            if(cookie_status == 'opened'){
                todo = 'open';
            }
    
            if(cookie_status == 'closed'){
                todo = 'close';
            }
        }

        if(todo){
            if(todo == 'open'){
                setTimeout(function(){
                    open_close_wb($ele, 'open', 'auto');
                }, 500);
            }else{
                $ele.removeClass( 'ufw_wb_opened' );
                $ele.addClass( 'ufw_wb_closed' );
                window.ufw_wrap_auto[id]['status'] = 'closed';
            }
        }

        return false;

    }

    $('.ufw_wb').on( 'transitionend animationend', function(){
        after_open_close($(this));
    });
    
    $('.ufw_btn').on( 'click', function(e){
        e.preventDefault();
        $wrap = $(this).closest('.ufw_wrap');
        open_close_wb($wrap, 'toggle', 'manual');
    });
    
    $('.ufw_btn').on( 'animationend', function(){
        remove_animate_class($(this));
    });

    $('.ufw_close_btn').on('click', function(e){
        e.preventDefault();
        $wrap = $(this).closest('.ufw_wrap');
        open_close_wb($wrap, 'toggle', 'manual');
    });

    $(window).scroll(function(){
        
        var at = (($(window).scrollTop() + $(window).height())/$(document).height())*100;
        var scrolled = $(window).scrollTop();
        
        on_scroll(at, scrolled);
        
    });
    
    $(document).keyup(function(e){
        if (e.keyCode === 27){
            $('.ufw_wrap').each(function(){
                open_close_wb($(this), 'close');
            });
        }
    });
    
    init();
    
});
})( jQuery );