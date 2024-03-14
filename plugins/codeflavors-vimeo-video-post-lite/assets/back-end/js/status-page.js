(function($){
    $(document).ready( function(){
        var clicked = false;

        $('#vimeotheque-status-copy').on('click', function(e){
            e.preventDefault();
            if( clicked ){
                return;
            }
            
            clicked = true;
            
            var titles = $('#status-display').find('h2');

            $.each( titles, function(i, title){
                var table = $(title).next('table'),
                    sectionTitle = $(title).data('export-label'),
                    before = 0 == i ? '' : "\n";
                
                writeReport( before + '### ' + sectionTitle + ' ###' + "\n\n" );

                var labels = $(table).find( 'th' );
                $.each( labels, function( i, label ){
                    var labelText = $(label).data('export-label'),
                        value = $(label).next('td').data('value');

                    writeReport( labelText + ': ' + value + "\n" );
                })

            })

            $('.status-report .export').show(100);
            $('#vimeotheque-report').on('click', function(){
                $(this).select();
            })

            $('#vimeotheque-report').trigger('click');
            $('.command').html(
                $('<span></span>', {
                    html: $('.command').data('alt-text')
                })
            );

        })
    
        var writeReport = function( message ){
            var exporter = $('#vimeotheque-report'); 
            $(exporter).val( $(exporter).val() + message );
        }

    })


})(jQuery);