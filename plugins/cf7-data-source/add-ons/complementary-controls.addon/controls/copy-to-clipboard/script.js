jQuery(function(){
    var $ = jQuery,
        copied = (typeof cf7_ds_clopy_to_clipboard != 'undefined') ? cf7_ds_clopy_to_clipboard['copied'] : 'copied';

    $('textarea.clipboard,input.clipboard').after('<i class="cf7-ds-clipboard-icon"></i>');
    $('body').append('<span class="cf7-ds-clipboard-tooltip">'+copied+'</span>');

    // Show/Hide icon
    $('textarea.clipboard,input.clipboard').on('mouseover', function(){
        var e = $(this),
            i = e.next('.cf7-ds-clipboard-icon'),
            setPosition = function(){
                var o = e.offset(),
                    t = o.top+5,
                    l = o.left + e.outerWidth() - (i.width()+5);
                i.offset({top:t, left:l});
            };

        setTimeout(setPosition, 5);
        setPosition();

    });

    // Show/Hide tooltip
    $('.cf7-ds-clipboard-icon').on('click', function(){
        var e = $(this),
            i = $('.cf7-ds-clipboard-tooltip'),
            setPosition = function(){
                var o = e.offset(),
                    t = o.top - (i.outerHeight()+10),
                    l = o.left;
                i.offset({top:t, left:l});
            };

         setTimeout(setPosition, 5);
         setPosition();
    });

    $('.cf7-ds-clipboard-icon').on('mouseout', function(){
        $('.cf7-ds-clipboard-tooltip').hide();
    });

    // Copy the content
    $('.cf7-ds-clipboard-icon').on('click', function(){
        var me = $(this),
            tt = $('.cf7-ds-clipboard-tooltip'),
            el = me.prev('input,textarea')[0],
            o = me.offset(),
            t = o.top - (tt.height()+10),
            l = o.left;

        tt.offset({top:t, left:l}).show();

        /* Select the text field */
        el.select();
        el.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(el.value);

        me.siblings('.cf7-ds-clipboard-tooltip').css('display', 'inline-block');

    });


});