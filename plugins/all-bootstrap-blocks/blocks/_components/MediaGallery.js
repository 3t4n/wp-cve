const MediaGallery = ( areoi, attributes, onChange, label, types, key ) => {

    function MediaGallery() {
        var output = [];

        if ( attributes[key].length ) {
            attributes[key].forEach( ( item, index ) => {
                var newOutput = (
                    <div class="item areoi-gallery-item">
                        <div class="areoi-gallery-item-media">
                            {
                                item.type == 'image' &&
                                <img src={ item.url } />
                            }
                            {
                                item.type == 'video' &&
                                <video src={ item.url }></video>
                            }
                        </div>
                        <div class="areoi-galery-item-label">
                            <p>{ item.filename ? item.filename.substring( 0, 15 ) + '...' : '' }</p>
                            <a href="#" className="areoi-remove-link" onClick={ () => {

                                var images = [...attributes[key]];
                                images.splice( index, 1 );
                                
                                onChange( key, images )

                            } }>Remove Media</a>
                        </div>
                        <div class="areoi-galery-item-arrows">
                            <button onClick={ () => {

                                var images = [...attributes[key]];
                                var to = index;
                                var from = index-1;

                                if ( from < 0 ) {
                                    from = 0;
                                }
                                
                                images.splice(to, 0, images.splice(from, 1)[0]);

                                var newImages = [];
                                for (var i in images) {
                                    if ( typeof images[i] != 'undefined' ) {
                                        newImages.push(images[i]);
                                    }
                                }
                                
                                onChange( key, newImages )

                            } }>
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"></path></svg>
                            </button>

                            <button onClick={ () => {

                                var images = [...attributes[key]];
                                var to = index;
                                var from = index+1;

                                if ( from < 0 ) {
                                    from = 0;
                                }
                                
                                images.splice(to, 0, images.splice(from, 1)[0]);

                                var newImages = [];
                                for (var i in images) {
                                    if ( typeof images[i] != 'undefined' ) {
                                        newImages.push(images[i]);
                                    }
                                }
                                
                                onChange( key, newImages )

                            } }>
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>
                            </button>
                        </div>
                    </div>
                );

                output.push( newOutput );
            } )
        }

        return output;
    }

    return (
        <areoi.components.PanelRow>
            
            <areoi.editor.MediaUploadCheck>
                <areoi.editor.MediaUpload
                    label={ label }
                    multiple={ true }
                    onSelect={ ( items ) => {
                        onChange( key, attributes[key].concat( items ) )
                    } }
                    value={ '' }
                    allowedTypes={ types }
                    render={({ open }) => (
                        <div className="areoi-gallery-container">

                            <areoi.components.Button 
                                className={ 'editor-post-featured-image__toggle areoi-components-button-img' }
                                onClick={ open }
                            >
                                { areoi.__('Add Media to  ' + label ) }

                            </areoi.components.Button>

                            <div className="areoi-gallery">
                                { MediaGallery() }
                            </div>

                        </div>
                    )}
                />
            </areoi.editor.MediaUploadCheck>

        </areoi.components.PanelRow>
    );
}

export default MediaGallery;