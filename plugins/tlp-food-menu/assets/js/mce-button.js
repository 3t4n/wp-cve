(function() {
    tinymce.PluginManager.add('fmp', function( editor, url ) {
        var tlpsc_tag = 'foodmenu';


        //add popup
        editor.addCommand('fmp_popup', function(ui, v) {
            //setup defaults

            editor.windowManager.open( {
                title: 'Food Menu ShortCode',
                width: jQuery( window ).width() * 0.3,
                height: (jQuery( window ).height() - 36 - 50) * 0.1,
                id: 'fmp-insert-dialog',
                body: [
                    {
                        type   : 'container',
                        html   : '<span class="rt-loading">Loading...</span>'
                    },
                ],
                onsubmit: function( e ) {

                    var shortcode_str;
                    var id = jQuery("#scid").val();
                    var title = jQuery( "#scid option:selected" ).text();
                    if(id && id != 'undefined'){
                        shortcode_str = '[' + tlpsc_tag;
                            shortcode_str += ' id="'+id+'" title="'+ title +'"';
                        shortcode_str += ']';
                    }
                    if(shortcode_str) {
                        editor.insertContent(shortcode_str);
                    }else{
                        alert('No short code selected');
                    }
                }
            });

            putScList();
        });

        //add button
        editor.addButton('fmp', {
            icon: 'fmp',
            tooltip: 'Food menu pro',
            cmd: 'fmp_popup',
        });

        function putScList(){
                var dialogBody = jQuery( '#fmp-insert-dialog-body' )
                jQuery.post( ajaxurl, {
                    action: 'fmShortCodeList'
                }, function( response ) {

                    dialogBody.html(response);
                    //console.log(response);
                });

        }

    });
})();
