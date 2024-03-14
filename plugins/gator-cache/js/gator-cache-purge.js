jQuery(document).ready(function($){
    if (0 == $('#wpadminbar').length) {
        return;
    }
    $('#wpadminbar').delegate('#wp-admin-bar-gc-purge-page > a,#wp-admin-bar-gc-purge-zap > a', 'click', function(e){
        e.preventDefault();
        if ('undefined' === typeof(gcData)) {
            return false;
        }
        gcData.page.type = 'page';
        var a = $(this);
        if ('wp-admin-bar-gc-purge-zap' === a.parent('li').attr('id')) {
            gcData.page.type = 'zap';
        }
        var spinner = $('<span style="display:inline-block;margin-left:15px">' + gcData.msg.loading + '...</span>').insertAfter(a);
        $.post(gcData.ajaxurl, gcData.page, function(data){
            spinner.text(gcData.msg[gcData.page.type]);
            setTimeout(function(){
                spinner.slideUp(function(){
                    $(this).remove();
                });
            }, 1500);
        },'json').fail(function(xhr, textStatus, errorThrown){
            spinner.text('Unknown xhr error');
            setTimeout(function(){
                spinner.slideUp(function(){
                    $(this).remove();
                });
            }, 1500);
        });
        
        return false;
    });
});
