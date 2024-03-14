// Data table integration
document.addEventListener('cf7-recordset', function (evt) {
    var $ = jQuery,
        recordset_id = evt.detail['recordset-id'],
        recordset_data = evt.detail['recordset-data'];

    $.fn.dataTableExt.sErrMode = "throw";

    $('[data-recordset="'+recordset_id+'"]').each(function(){
        try{
            var e = $(this),
                columns,
                settings = {
                    'autoWidth'     : e.data('autowidth') || false,
                    'lengthChange'  : e.data('lengthchange') || false,
                    'ordering'      : e.data('ordering') || false,
                    'paging'        : e.data('paging') || false,
                    'scrollX'       : e.data('scrollx') || false,
                    'scrollY'       : e.data('scrolly') || '',
                    'searching'     : e.data('searching') || '',
                    'createdRow'    : function(row,data,dataIndex){
                        $(row).attr('data-record', dataIndex);
                    }
                };

            settings['data'] = recordset_data;
            settings['columns'] = [];

            columns = e.data('columns');
            for(let i in columns)
            {
                settings['columns'].push(
                    {
                        'title' : columns[i][0],
                        'data'  : columns[i][1]
                    }
                );
            }

            if(e.data('language')) settings['language'] = {'url': e.data('language')};

            e.html('<table style="width:100%;"></table>');
            var html_table = e.find('table'),
                table_obj = html_table.DataTable(settings);
            html_table.data('table_obj', table_obj);

        } catch(err) {
            e.html('');
            console.log(err);
        }
    });
});

(function(){
    if(typeof jQuery != 'undefined')
    {
        var $ = jQuery;
        $(document).on('click', '.wpcf7-datatable tbody tr', function(){
            var e = $(this).closest('.wpcf7-datatable'),
                i = $(this).data('record'),
                table_obj = $(this).closest('table').data('table_obj');

            e.find('.highlight').removeClass('highlight');
            $(this).addClass('highlight');

            if(typeof table_obj != 'undefined')
            {
                $(e).trigger('cff-datatable-click', [i, table_obj.row( this ).data()]);
            }
        });
    }

})()