jQuery(document).ready(function($) {
     //  added class to expired row
    $('#the-list tr td.column-status span.expired').parent().siblings().addClass('expired_inactive');
    $('#the-list tr td.column-status span.expired').parent().addClass('expired_inactive');
});
