jQuery(document).on('change', 'form.wpcf7-form :input', function(evt, attr){
    var $ = jQuery;
    if(
        typeof attr != 'undefined' &&
        attr == 'cf7-ds-fill' &&
        typeof cf7msm_posted_data != 'undefined'
    )
    {
        var t = evt.target,
            n = t.name,
            m = n.replace(/[\[\]]/g, '');

        if(m in cf7msm_posted_data && $(t).data('ms_ini') == undefined)
        {
            $('[name="'+n+'"]').val(cf7msm_posted_data[m])
                .data('ms_ini', 1)
                .change();
        }
    }
});