jQuery(document).ready(function($) {
    tinymce.create('tinymce.plugins.ccpuz_wpse72394_plugin', {
        init : function(ed, url) {
                // Register command for when button is clicked
                ed.addCommand('ccpuz_wpse72394_insert_shortcode', function() {
				    
                    $.ajax({
                        url: ccpuz_wpse72394_button_ajax_url,
                        type: 'POST',
                        dataType: 'HTML',
                        data: {
                            action: 'ccpuz_get_crossword_mce_from',
                            post_id: ccpuz_post_id
                        }
                    }).done(function(response){
                        var e = $(response);
                        vex.dialog.open({
                            message: '',
                            input: [
                                e.html()
                            ].join(''),
                            afterOpen: function () {
                                var e = $('.vex-dialog-input');
                                e.find('#crossword_method').on('change', function(){
                                    if( $(this).val() == 'url' ){
                                        e.find('.ccpuz_file_class').hide();
                                        e.find('.ccpuz_url_class').show();
                                    }
                                    if( $(this).val() == 'local' ){
                                        e.find('.ccpuz_url_class').hide();
                                        e.find('.ccpuz_file_class').show();
                                    }
                                });
                                e.find('#crossword_method').change();
                            },
                            onSubmit: function(event) {
                                var dialog = this;
                                event.preventDefault();
                                var e = $('.vex-dialog-input');
                                $('.vex-dialog-button').attr('disabled', 'disabled');

                                var formData = new FormData();
                                formData.append('crossword_method', e.find('#crossword_method').val());
                                formData.append('action', 'ccpuz_save_crossword_mce_from');
                                formData.append('post_id', ccpuz_post_id);
                                if( e.find('#crossword_method').val() == 'url' ) {
                                    formData.append( 'ccpuz_url_upload_field', e.find('#ccpuz_url_upload_field').val() )
                                } else if ( e.find('#crossword_method').val() == 'local' ) {
                                    formData.append('ccpuz_html_file', e.find('#ccpuz_html_file')[0].files[0]); 
                                    formData.append('ccpuz_js_file', e.find('#ccpuz_js_file')[0].files[0]); 
                                }
                                $.ajax({
                                    url: ccpuz_wpse72394_button_ajax_url,
                                    data: formData,
                                    type: 'POST',
                                    dataType: 'html',
                                    contentType: false,
                                    processData: false
                                }).done(function(response){
                                    if( response == 1 ) {
                                        added_crossword = true;
                                        var content = '[crossword]';
                                        tinymce.execCommand( 'mceInsertContent', false, content );
                                    } else {
                                        alert(response);
                                    }
                                    dialog.close();
                                });
                                return false;
                            }
                        });
                    });
			
                });

            // Register buttons - trigger above command when clicked
            ed.addButton('ccpuz_wpse72394_button', {title : 'Insert puzzle', cmd : 'ccpuz_wpse72394_insert_shortcode', image: url + '/images/logo.png' });
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('ccpuz_wpse72394_button', tinymce.plugins.ccpuz_wpse72394_plugin);
});
