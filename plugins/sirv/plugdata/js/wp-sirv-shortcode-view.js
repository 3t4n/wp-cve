tinymce.PluginManager.add('sirvgallery', function( editor ) {

    let jq;
    let placehodler_grey = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAAAAAA6fptVAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAKSURBVAgdY3gPAADxAPAXl1qaAAAAAElFTkSuQmCC";
    let placeholder_grey_params = '?q=1&w=10&colorize.color=efefef';
    let cachedShData = {};

    function replaceGalleryShortcodes( content ) {
        return content.replace( /\[sirv-gallery id=(\d*)\]/g, function( match, id ) {
            return html( match, id );
        });
    }


    function replaceResponsiveImages( content ){
        return content.replace( /<img\s*?class=\"Sirv.*?\".*?data-src=\"(.*?)\".*?\/>/g, function( match, dataSrc ) {
            return responsiveHTML(match, dataSrc);
        });
    }


    function responsiveHTML(imgHtml, dataSrc){
        let pattern = /\ssrc=".*?"/ig;
        if(pattern.test(imgHtml)){
            return imgHtml.replace(pattern, ' src="' + dataSrc + '"');
        }else{
            return imgHtml.replace('/>', ' src="' + dataSrc + '" />');
        }
    }


    function restoreResponsiveHTML( content ){
        return content.replace( /<img\s*?class=\"Sirv.*?\".*?\s(src=\"(.*?)\").*?\/>/g, function( match, srcTag, srcUrl){
            let url = srcUrl.replace(/\?.*/ig, '');
            url = !!url ? url + placeholder_grey_params : placehodler_grey;
            return match.replace(srcTag, 'src="' + url + '"');
        });
    }

    //TODO: make async func
    function html(sc, id ) {
        var html = '';

        if(isId(id)){
            const cache = getcachedShData(id);
            if(!!cache && !window.sirvIsChangedShortcode){
                html = renderGallery(id, cache);
            }else{
                const data = {
                    action: 'sirv_get_row_by_id',
                    row_id: id,
                };

                jq.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    async: false,
                    dataType: 'json'
                }).done(function(response){
                    //debug
                    //console.log(response);

                    cachedShData[id] = response;
                    window.sirvIsChangedShortcode = false;

                    html = renderGallery(id, response);
                });
            }
        }else{
            return sc;
        }

        return html;
    }


    function isId(id){
        return (!!id && !isNaN(id));
    }


    function restoreMediaShortcodes( content ) {
        function getAttr( str, name ) {
            name = new RegExp( name + '=\"([^\"]+)\"' ).exec( str );
            return name ? name[1] : '';
        }

        return content.replace( /(<div class="sirv-sc-view.*?" .*?>)<div.*?>.*?<\/div>.*?<\/div>/g, function( match, div ) {
            const id = getAttr( div, 'data-id' );

            if (isId(id)) {
                return `[sirv-gallery id=${id}]`;
            }

            return match;
        });
    }

    function getcachedShData(id){
        if(isId(id)){
            if(!!cachedShData[id]){
                return cachedShData[id];
            }
        }

        return null;
    }


    function renderGallery(id, data){
        var img_data = data['images'];
        var profile = data.profile == '' ? '' : 'profile=' + data.profile +'&';
        var images = '';
        var count = img_data.length > 4 ? 4 : img_data.length;
        const ending = img_data.length > 1 ? '(s)' : '';

        for(var i = 0; i < count; i++){
            let url = img_data[i]['type'] == 'model' ? sirv_ajax_object.assets_path + '/model-plhldr.svg' : img_data[i]['url'] +'?'+ profile +'thumbnail=120&image';
            images += '<img src="'+ url +'" alt="'+ img_data[i]['caption'] +'" />'
        }
        return `<div class="sirv-sc-view data-id-${id}" data-id="${id}" contenteditable=false >
                    <div class="sirv-overlay" data-id="${id}">
                        <span class="sirv-overlay-text">Sirv gallery: ${img_data.length} image${ending}</span>
                        <a href="#" title="Delete gallery" class="sirv-delete-sc-view sc-view-button sc-buttons-hide dashicons dashicons-no" data-id="${id}">Delete Gallery</a>
                        <a href="#" data-id="${id}" title="Edit gallery" class="sirv-edit-sc-view sc-view-button sc-buttons-hide dashicons dashicons-admin-generic">Edit Gallery</a>
                    </div>
                    ${images}
                </div>`;
    }


    editor.on( 'mouseup', function( event ) {
        var dom = editor.dom,
            node = event.target;

        function selectView(){
            dom.addClass( dom.select( 'div.sirv-sc-view' ), 'selected' );
            dom.removeClass( dom.select( '.sirv-edit-sc-view' ), 'sc-buttons-hide' );
            dom.removeClass( dom.select( '.sirv-delete-sc-view' ), 'sc-buttons-hide' );
        }

        function unselect() {
            dom.removeClass( dom.select( 'div.sirv-sc-view' ), 'selected' );
            dom.addClass( dom.select( '.sirv-edit-sc-view' ), 'sc-buttons-hide' );
            dom.addClass( dom.select( '.sirv-delete-sc-view' ), 'sc-buttons-hide' );
        }

        if ( dom.hasClass( node, 'sirv-overlay') || dom.hasClass( node, 'sc-view-button') ) {
            // Don't trigger on right-click
            if ( event.button !== 2 ) {
                selectView();
            } else {
                unselect();
            }
        }else{
            unselect();
        }
    });

    function deleteView(event){

    }

    // Display sirv-gallery, instead of div in the element path
    editor.on( 'ResolveName', function( event ) {
        var dom = editor.dom,
            node = event.target;

        if ( node.nodeName === 'DIV' && dom.hasClass( node, 'sirv-sc-view' ) ) {
            event.name = 'sirv-gallery';
        }
    });

    //editor.onClick.add(function(editor, e) {
    editor.on('click', function(e) {
        if(e.target.className == 'sirv-edit-sc-view sc-view-button dashicons dashicons-admin-generic'){
            var id = editor.dom.getAttrib(e.target, 'data-id');

            window['bPopup'] = jq('.sirv-modal').bPopup({
                            position: ['auto', 'auto'],
                            loadUrl: modal_object.media_add_url,
                            loadCallback: function(){
                                jq('.insert').addClass('edit-gallery');
                                jq('.insert').attr('data-shortcode-id', id);
                                sirvEditGallery(id);
                            }
                        });

        }else if(e.target.className == 'sirv-delete-sc-view sc-view-button dashicons dashicons-no'){
            var id = jq(e.target).attr('data-id');
            var content = editor.getContent({format : 'raw'});
            var re = new RegExp('(<div class=\"sirv-sc-view data-id-'+ id +'.*?\".*?>)<div class=\"sirv-overlay\".*?>.*?<\/div>.*?<\/div>', 'g');
            content = content.replace(re, '');
            editor.setContent(content);
        }
        e.preventDefault();
        return false;
    });



    editor.on( 'BeforeSetContent', function( event ) {
        event.content = replaceGalleryShortcodes( event.content );
        event.content = replaceResponsiveImages( event.content );
    });


    editor.on( 'PostProcess', function( event ) {
        if ( event.get ) {
            event.content = restoreMediaShortcodes( event.content );
            event.content = restoreResponsiveHTML( event.content );
        }
    });


    editor.on('preInit', function() {
        jq = editor.getWin().parent.jQuery;
    });

});
