(function( $ ) {

    /**
     * Initiate the log viewer data table
     */
    $('#wa-logviewer').DataTable({
        order: [[0, 'desc']],
        columns: [
            {data: 'id', width: '5%'},
            {data: 'date', width: '9%'},
            {data: 'time', width: '6%'},
            {data: 'session', width: '8%'},
            {data: 'level', width: '8%'},
            {data: 'message'},
            {data: 'details'} ],
        ajax: {
            type: 'GET',
            url: ajaxurl,
            data: {
                action: 'wa_logdata',
                security: WunderAutoData.search_logdata_nonce,
            },
        }
    });

    /**
     * Initiate the queue data table
     */
    var queueTable = $('#wa-queueviewer').DataTable({
        processing: true,
        order: [[0, 'asc']],
        columns: [
            {data: 'id', width: '5%'},
            {data: 'created', width: '10%'},
            {data: 'workflow', width: '10%'},
            {data: 'objects', width: '20%'},
            {data: 'runsOn', width: '10%'},
            {data: 'actions', width: '10%'} ],
        ajax: {
            type: 'GET',
            url: ajaxurl,
            data: {
                action: 'wa_queuedata',
                security: WunderAutoData.search_queuedata_nonce,
            },
        }
    });

    /**
     * Clear log user confirmation
     */
    $('.form_clearlog').on('click', 'button.clearlog', function (e) {
        if (!window.confirm(WunderAutoData.clearlog_alertmsg)) {
            e.preventDefault();
        }
    });

    /**
     * Cancel queued trigger
     */
    $(document).on('click', '.wa_queue_cancel', function (e) {
        e.preventDefault();
        var msg = WunderAutoData.queue_cancel_alertmsg;
        var id = $(this).attr('data-id');
        msg = msg.replace('[id]', id.toString());
        if (window.confirm(msg)) {
            jQuery.ajax({
                url: ajaxurl,
                data: {
                    action: 'wa_cancelqueueditem',
                    id: id,
                    security: WunderAutoData.search_queuedata_nonce,
                },
                success: function (result) {
                    queueTable.ajax.reload();
                }
            });
        }
    });

    /**
     * Reschedule queued trigger to NOW()
     */
    $(document).on('click', '.wa_queue_runnow', function (e) {
        e.preventDefault();
        var msg = WunderAutoData.queue_runnow_alertmsg;
        var id = $(this).attr('data-id');
        msg = msg.replace('[id]', id.toString());
        if (window.confirm(msg)) {
            jQuery.ajax({
                url: ajaxurl,
                data: {
                    action: 'wa_runqueueditem',
                    id: id,
                    security: WunderAutoData.search_queuedata_nonce,
                },
                success: function (result) {
                    queueTable.ajax.reload();
                }
            });
        }
    });

    $('#wa_queue_refresh').click(function (e){
        queueTable.ajax.reload();
    });

})( jQuery );
