(function( $, LaStudioKitSwatchesListConfig ) {
    'use strict';

    window.LaStudioKitSwatchesSettings = {
        templateInstance: null,
        init: function (){
            this.initTemplate();
            this.initEvents();
            this.setUpVariationGallery();
            this.setUpThreeSixtyVideoMediaAttach();
        },
        initEvents: function (){
            const self = this;
            $(document).on('reload', '#variable_product_options', () => {
                self.ajaxLoadData()
            } )
            $(document).on('woocommerce_variations_saved', '#woocommerce-product-data', () => {
                self.ajaxLoadData()
            } )
        },
        initTemplate: function (){
            const self = this;
            this.templateInstance = new Vue( {
                el: '#lastudiokit_swatches_list',
                template: '#lastudiokit-swatches-list',
                data: {
                    attributeList: [],
                    defaultData: LaStudioKitSwatchesListConfig.controlData
                },
                mounted: function() {
                    self.ajaxLoadData( res => {
                        this.attributeList = res
                    } );
                },
                methods: {
                    getTypeLabel: function ( val ){
                        for (var i = 0; i < this.defaultData.type.options.length; i++) {
                            if ( val === this.defaultData.type.options[ i ].value ) {
                                return this.defaultData.type.options[ i ].label;
                            }
                        }
                    },
                    getTypeOptions: function ( item, is_custom ){
                        return this.defaultData[item + (is_custom ? '_custom' : '')].options
                    },
                    updatePreview: function ( val, attr, term ){
                        term[attr] = val;
                    },
                    renderPreview: function ( opts ){
                        let _html = '<span>ola</span>';
                        if(opts.type == 'photo'){
                            _html = '<span class="attr-preview attr-preview-img">';
                            if(opts.photo){
                                const photo_attr = wp.media.attachment( opts.photo ).attributes;
                                if(photo_attr.url){
                                    _html += '<img src="'+ photo_attr.url +'"/>';
                                }
                                else if( opts.photo_url ){
                                    _html += '<img src="'+ opts.photo_url +'"/>';
                                }
                            }
                            _html += '</span>';
                        }
                        if(opts.type == 'color'){
                            _html = '<span class="attr-preview"><span class="attr-preview-color'+ (opts.color2 != '' ? ' has-gradient' : '') +'" style="--lakit-swatch--color-1:' + opts.color + ';--lakit-swatch--color-2:' + opts.color2 + ';"></span></span>';
                        }
                        return _html;
                    }
                }
            });
        },
        ajaxLoadData: function ( callback ){
            wp.ajax.post( 'lakit_ajax', {
                'action': 'lakit_ajax',
                '_nonce': LaStudioKitSwatchesListConfig.ajaxNonce,
                'actions': JSON.stringify({
                    'swatches_get_variation_attributes' : {
                        'action': 'swatches_get_variation_attributes',
                        'data': {
                            'product_id': LaStudioKitSwatchesListConfig.currentID,
                        }
                    }
                }),
            } ).done( res =>{
                if(callback !== undefined){
                    callback(res.responses.swatches_get_variation_attributes.data)
                }
                else{
                    this.templateInstance.$data.attributeList = res.responses.swatches_get_variation_attributes.data;
                }
            } )
        },
        setUpVariationGallery: function (){
            function event_input_change( $input ){
                $input
                    .closest( '.woocommerce_variation' )
                    .addClass( 'variation-needs-update' );

                $( 'button.cancel-variation-changes, button.save-variation-changes' ).removeAttr( 'disabled' );
                $( '#variable_product_options' ).trigger( 'woocommerce_variations_input_changed' );
            }
            // Update Selected Images
            function event_update_selected_images( $table_col ) {
                var $selectedImgs = [],
                    $gallery_field = $table_col.find('.lakit_variation_image_gallery');

                $table_col.find('.lakit_variation_thumbs .image').each(function(){
                    $selectedImgs.push($(this).attr('data-attachment_id'));
                });
                // Update hidden input with chosen images
                $gallery_field.val($selectedImgs.join(','));
                event_input_change( $gallery_field );
            }

            function trigger_get_gallery_data() {
                // Moving gallery after featured image row
                $('.woocommerce_variable_attributes .data > .lakit-gallery-for-variation').each(function () {
                    let $me = $(this);
                    $me.appendTo( $me.closest('.data').find('.form-row.upload_image') );
                    // Sort Images
                    $( '.lakit_variation_thumbs', $me ).sortable({
                        deactivate: function(en, ui) {
                            var $table_col = $(ui.item).closest('.lakit_variation_thumb');
                            event_update_selected_images($table_col);
                        },
                        placeholder: 'ui-state-highlight'
                    });
                })
            }

            // Setup Variation Image Manager
            function init(){

                trigger_get_gallery_data();

                let product_gallery_frame;
                $(document).on('click', '.lakit_swatches--manage_variation_thumbs', function(e){
                    e.preventDefault();
                    var $el = $(this),
                        $variation_thumbs = $el.siblings('.lakit_variation_thumbs'),
                        $image_gallery_ids = $el.siblings('.lakit_variation_image_gallery'),
                        attachment_ids = $image_gallery_ids.val();

                    // Create the media frame.
                    product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                        // Set the title of the modal.
                        title: 'Manage Variation Images',
                        button: {
                            text: 'Add to variation'
                        },
                        multiple: true
                    });

                    // When an image is selected, run a callback.
                    product_gallery_frame.on( 'select', function() {
                        var selection = product_gallery_frame.state().get('selection');
                        selection.map( function( attachment ) {
                            attachment = attachment.toJSON();
                            if ( attachment.id ) {
                                attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
                                $variation_thumbs.append('<li class="image" data-attachment_id="' + attachment.id + '"><a href="#" class="delete" title="Delete image"><span style="background-image: url('+attachment.url+')"></span></a></li>');
                            }
                        } );

                        $image_gallery_ids.val( attachment_ids );
                        event_input_change( $image_gallery_ids );
                    });
                    // Finally, open the modal.
                    product_gallery_frame.open();
                    return false;
                });

                // Delete Image
                $(document).on('click', '.lakit_variation_thumbs .delete', function(e){
                    e.preventDefault();
                    var $table_col = $(this).closest('.lakit_variation_thumb');
                    // Remove clicked image
                    $(this).closest('li').remove();
                    event_update_selected_images($table_col);
                });
                // after variations load
                $( '#woocommerce-product-data' ).on( 'woocommerce_variations_loaded', function(){
                    trigger_get_gallery_data();
                });
                // Once a new variation is added
                $('#variable_product_options').on('woocommerce_variations_added', function(){
                    trigger_get_gallery_data();
                });
            }

            $(function (){
                init();
            })

        },
        setUpThreeSixtyVideoMediaAttach: function (){

            $(document).on('change', '.lakit-admin__attach-media-wrap [data-control_id="type"] select', function ( e ){
                let $wrap = $(this).closest('.lakit-admin__attach-media-wrap'),
                    $videoControls = $('.frm-field--depends', $wrap);
                $videoControls.hide();
            });

            let mediaView;

            $(document).on('click', '.lakit-admin__attach-media-wrap button.button', function ( e ){
                e.preventDefault();
                let _type = $(this).data('type'),
                    $inputControl = $(this).siblings('input'),
                    $preview = $(this).siblings('.frm-field--preview');
                mediaView = wp.media({
                    multiple: false,
                    library : {
                        type : _type
                    },
                });
                mediaView.on( 'select', function() {
                    let [selection] = mediaView.state().get('selection').toJSON();
                    if( 'video' === _type ) {
                        $inputControl.val(selection.url).trigger('change');
                    }
                    else{
                        $preview.html('<img src="'+selection.url+'"/>');
                        $inputControl.val(selection.id).trigger('change');
                    }
                });
                mediaView.open();
                return false;
            });

            $(document).on('lastudiokit/admin/media/product-threesixty-video-init-hook', '.lakit-admin__attach-media-wrap', function (){
                const _this = $(this),
                    $selectType = $('[data-control_id="type"] select', _this);
                // $selectType.trigger('change');
            })
        }
    }

    LaStudioKitSwatchesSettings.init();

})( jQuery, window.LaStudioKitSwatchesListConfig );