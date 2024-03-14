(function (blocks, i18n, element, components) {
    var SelectControl = components.SelectControl;
    var el = element.createElement; // The wp.element.createElement() function to create elements.

    blocks.registerBlockType('uxgallery/gallery', {
        title: 'UXGallery Gallery',
        icon: 'format-gallery',
        category: 'uxgallery',
        attributes: {
            gallery_id: {type: 'string'}
        },
        edit: function (props) {
            var focus = props.focus;
            props.attributes.gallery_id =  props.attributes.gallery_id &&  props.attributes.gallery_id != '0' ?  props.attributes.gallery_id : false;
            return [
                !focus && el(
                    SelectControl,
                    {
                        label: 'Select Gallery',
                        value: props.attributes.gallery_id ? parseInt(props.attributes.gallery_id) : 0,
                        instanceId: 'uxgallery-gallery-selector',
                        onChange: function (value) {
                            props.setAttributes({gallery_id: value});
                        },
                        options: uxgalleryBlockI10n.galleries,
                    }
                ),
                el('div',{}, props.attributes.gallery_id ? 'Gallery: ' + uxgalleryBlockI10n.galleryMetas[props.attributes.gallery_id] : 'Select Gallery')
            ];
        },
        save: function (props) {
            if(typeof props.attributes.gallery_id != 'undefined' && props.attributes.gallery_id != 0){
                return el('p', {}, '[uxgallery id="'+props.attributes.gallery_id+'"]');
            } else {
                return el('p', {}, 'Gallery not selected');
            }

        },
    });

    blocks.registerBlockType('uxgallery/album', {
        title: 'UXGallery Album',
        icon: 'images-alt2',
        category: 'uxgallery',
        attributes: {
            album_id: {type: 'string'}
        },
        edit: function (props) {
            var focus = props.focus;
            props.attributes.album_id =  props.attributes.album_id &&  props.attributes.album_id != '0' ?  props.attributes.album_id : false;
            return [
                !focus && el(
                    SelectControl,
                    {
                        label: 'Select Album',
                        value: props.attributes.album_id ? parseInt(props.attributes.album_id) : 0,
                        instanceId: 'uxgallery-album-selector',
                        onChange: function (value) {
                            props.setAttributes({album_id: value});
                        },
                        options: uxgalleryBlockI10n.albums,
                    }
                ),
                el('div',{}, props.attributes.album_id ? 'Album: ' + uxgalleryBlockI10n.albumMetas[props.attributes.album_id] : 'Select Album')
            ];
        },
        save: function (props) {
            if(typeof props.attributes.album_id != 'undefined' && props.attributes.album_id != 0){
                return el('p', {}, '[uxgallery_album id="'+props.attributes.gallery_id+'"]');
            } else {
                return el('p', {}, 'Album not selected');
            }

        },
    });
})(
    window.wp.blocks,
    window.wp.i18n,
    window.wp.element,
    window.wp.components
);