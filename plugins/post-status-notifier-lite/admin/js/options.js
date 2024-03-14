jQuery(document).ready( function($) {

    if (window.location.hash != '') {
        $('#psn-options-page ul.nav-tabs a[href="'+ window.location.hash +'"]').tab('show');
        psn_options_update_referer();
        window.scrollTo(0, 0);
    } else {
        $('#psn-options-page ul.nav-tabs a:first').tab('show');
    }

    function psn_options_update_referer() {
        var href = $('div#psn-options-page ul.nav li.active a').attr('href');
        var referer = $('input[name="_wp_http_referer"]').val();
        $('input[name="_wp_http_referer"]').val(referer.replace(/page=.*/, 'page='+ getParameterByName('page') +'&controller=options&appaction=index' + href));
    }

    $('#psn-options-page ul.nav-tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
        window.location.hash = $(this).attr('href');
        window.scrollTo(0, 0);
        psn_options_update_referer();
    });

    // tabs
    $('#psn-options-page ul.nav-pills a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

    $('#psn-options-page ul.nav-pills a:first').tab('show');


    $('.form-table input[type=hidden]').closest('table').hide();

    // ace
    var textareaFilters = $('textarea#psn_option_placeholders_filters');
    textareaFilters.hide();
    var filters_val = textareaFilters.val();
    textareaFilters.closest('td').prepend('<div id="placeholders_filters_editor"></div>');

    var editorFilters = ace.edit("placeholders_filters_editor");

    editorFilters.setTheme("ace/theme/github");
    editorFilters.getSession().setMode("ace/mode/twig");
    editorFilters.getSession().setValue(filters_val);

    editorFilters.getSession().on('change', function() {
        textareaFilters.val(editorFilters.getSession().getValue());
    });

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    $('form.ifw-wp-options').on('submit', function (e) {
        HoldOn.open();
    });
});