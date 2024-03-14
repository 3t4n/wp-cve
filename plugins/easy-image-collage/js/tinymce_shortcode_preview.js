(function() {
    tinymce.PluginManager.add('easyimagecollage', function( editor, url ) {
        function replaceShortcodes( content ) {
            return content.replace( /\[easy-image-collage([^\]]*)\]/g, function( match ) {
                return html( match );
            });
        }

        function html( data ) {
            var id = data.match(/id="?'?(\d+)/i);
            data = window.encodeURIComponent( data );

            var ajax_data = {
                action: 'image_collage_preview',
                security: eic_admin.nonce,
                grid_id: id[1]
            };

            jQuery.post(eic_admin.ajaxurl, ajax_data, function(preview) {
                var content = editor.getContent({format: 'raw'});
                content = content.replace('>Loading Easy Image Collage ' + id[1] + '<', '>' + preview +'<');
                editor.setContent(content);
            }, 'html');

            return '<div class="eic-shortcode" style="display: block; cursor: pointer; margin: 5px; padding: 10px; border: 1px solid #999;" contentEditable="false" ' +
                'data-eic-grid="' + id[1] + '" data-eic-shortcode="' + data + '" data-mce-resize="false" data-mce-placeholder="1">Loading Easy Image Collage ' + id[1] + '</div><span class="eic-placeholder" contentEditable="false">&nbsp;</span>';
        }

        function restoreShortcodes( content ) {
            function getAttr( str, name ) {
                name = new RegExp( name + '=\"([^\"]+)\"' ).exec( str );
                return name ? window.decodeURIComponent( name[1] ) : '';
            }

            content = content.replace( /<p><span class="eic-(?=(.*?span>))\1\s*<\/p>/g, '' );
            content = content.replace( /<span class="eic-.*?span>/g, '' );

            return content.replace( /(?:<p(?: [^>]+)?>)*(<div [^>]+>.*?<\/div>)(?:<\/p>)*/g, function( match, div ) {
                var data = getAttr( div, 'data-eic-shortcode' );

                if ( data ) {
                    return '<p>' + data + '</p>';
                }

                return match;
            });
        }

        editor.on( 'mouseup', function( event ) {
            var dom = editor.dom,
                node = event.target;

            if ( event.button !== 2 ) {
                if ( dom.getAttrib( node, 'data-eic-grid' ) ) {
                    var id = dom.getAttrib( node, 'data-eic-grid' );
                    EasyImageCollage.btnEditGrid(id, false);
                } else if ( dom.getAttrib( node, 'data-eic-grid-remove' ) ) {
                    editor.dom.remove(node.parentNode);
                }
            }
        });

        editor.on( 'BeforeSetContent', function( event ) {
            event.content = event.content.replace( /^(\s*<p>)(\s*\[easy-image-collage)/, '$1<span class="eic-placeholder" contentEditable="false">&nbsp;</span>$2' );
            event.content = replaceShortcodes( event.content );
        });

        editor.on( 'PostProcess', function( event ) {
            if ( event.get ) {
                event.content = restoreShortcodes( event.content );
            }
        });
    });
})();