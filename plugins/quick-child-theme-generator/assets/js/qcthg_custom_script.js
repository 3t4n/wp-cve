var $qcthgJQuery = jQuery.noConflict();

$qcthgJQuery(document).ready(function() {
    $qcthgJQuery(function() {
        $qcthgJQuery("#tabs").tabs();
    });
    $qcthgJQuery('.qcthgErrNotice').insertAfter('.afterNoticesCls');
});
