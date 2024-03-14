jQuery(function(){
    var $ = jQuery;
    if('select2' in $.fn)
    {
        $('[data-cf7-ds-select2]').each(function(){
            var e = $(this);
            e.parent().addClass('cf7-ds-container');
            e.select2({'dropdownParent': e.closest('.cf7-ds-container'), 'width': 'resolve'});
        });
        $('[data-cf7-ds-select2]').css('visibility', 'hidden');
        $(document).on('change', '[data-cf7-ds-select2]', function(evt, param){
            if(typeof param != 'undefined' && param == 'cf7-ds-fill')
            {
                var e = $(this);
                e.select2({'dropdownParent': e.closest('.cf7-ds-container'), 'width': 'resolve'});
            }
        });
    }
});