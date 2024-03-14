/**
 * Created by user on 18.05.2017.
 */
jQuery(function($){

    $('.alids-menu-thumbler').on('click', function(e){
        e.preventDefault();

        var show = $(this).data('show'),
            hide = $(this).data('hide');
        $(this).removeClass('active').hide();
        $('.menu-'+show).addClass('active').show();

        $('#adminmenu .'+show).removeClass('alids-invisible').addClass('alids-visible');
        $('#adminmenu .'+hide).removeClass('alids-visible').addClass('alids-invisible');
    });
});