if (!WOOSVIADM) {
    var WOOSVIADM = {}
} else {
    if (WOOSVIADM && typeof WOOSVIADM !== "object") {
        throw new Error("WOOSVIADM is not an Object type")
    }
}

WOOSVIADM.isLoaded = false;
WOOSVIADM.STARTS = function($) {
    var $video_tag = '<input class="svipro-product_video_gallery" name="sviproduct_video_gallery[{{slug}}][{{attachment_id}}]" value="{{video_url}}" type="hidden">';
    var $video_tag_add = '<span class="dashicons dashicons-video-alt3 svi-{{video_action}} svi-videomanager" data-tip="{{vidTxt}}" data-attachment_id="{{attachment_id}}" data-svi_type="{{type}}" data-svi_gal="{{svi_gal}}"></span>';
    let $video_data = false;
    var $ajax_run = false;
    var $selectedReload = false;
    return {
        NAME: "Application initialize module",
        VERSION: 1.0,
        init: function() {
            if ($('body').attr('class').indexOf('woocommerce_page_woosvi-options-settings') > 0) {
                // this.loadInits();
                this.control_subfields();
            } else if ($('body').hasClass('post-type-product')) {
                this.loadProductInits();
                this.notices();
                this.initMedia();
                this.goMediaGallery();
                this.initMediaGal();
                this.prepMediaView();
            }
        },
        loadInits: function() {
            setTimeout(() => {
                $('input#columns').prop('type', 'number').attr('min', 1).attr('max', 10);
                $('input#lens_size').prop('type', 'number').attr('min', 100).attr('max', 300);
                $('input#slider_spaceBetweenNavigation,input#slider_spaceBetween,#lightbox_thumbnails_thumbWidth,input#lens_zIndex').prop('type', 'number');

                $('input#lightbox_width').prop('type', 'number').attr('min', 10).attr('max', 90);
                $('input#lightbox_height').prop('type', 'number').attr('min', 100).attr('max', 1000);

                $('input#hide_thumbs').change(function() {
                    if ($(this).val() == 1) {
                        $('#woosvi_options-variation_swap label.cb-disable').trigger('click');
                        $('#woosvi_options-keep_thumbnails label.cb-disable').trigger('click')
                    }
                });

                $('input#keep_thumbnails').change(function() {
                    if ($(this).val() == 1) {
                        $('#woosvi_options-swselect label.cb-disable').trigger('click');
                        $('#woosvi_options-hide_thumbs label.cb-disable').trigger('click');
                    }
                });

                // add a 'Settings' tab via JS
                const navTabWrapper = $('.fs-section .nav-tab-wrapper');
                const currentTabs = $('.fs-section .nav-tab-wrapper a');
                let activeTab = '';
                if (!currentTabs.hasClass('nav-tab-active')) {
                    activeTab = ' nav-tab-active';
                }
                navTabWrapper.prepend('<a href="/wp-admin/admin.php?page=woocommerce_svi" class="nav-tab fs-tab svg-flags-lite home' + activeTab + '">Settings</a>');

            }, 500)

            if ($('#wpwrap').find('#wpfooter')) {
                jQuery("#wpfooter").detach().appendTo('#wpwrap')
            }
        },
        control_subfields: function() {
            // If show if, hide by default.
            $('.svisubfield').each(function(index) {
                var element = $(this);
                element.closest('tr').addClass('wpsfsvi-subfield');
            });
        },
        notices: function() {
            jQuery(document).on('click', '.woosvi-notice-dismissed .notice-dismiss', function() {
                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'woosvi_dismiss_notice'
                    }
                });
            });
        },
        loadProductInits: function() {
            $('#woocommerce-product-data').on('woocommerce_variations_loaded', function() {
                WOOSVIADM.STARTS.loadGuide();
            });

            $('#variable_product_options').on('woocommerce_variations_added', function() {
                WOOSVIADM.STARTS.loadGuide();
            });

            wp.media.view.Modal.prototype.on('all', function(e) {
                if (e == 'close') {
                    setTimeout(() => {
                        WOOSVIADM.STARTS.prepMediaView();
                    }, 200);
                }
            });
        },
        loadGuide: function() {
            $('#variable_product_options').on('click', 'a.svi-add-additional-images', function(e) {
                e.preventDefault();
                $('#sviprobulk').val(null).trigger('change');

                $selectedReload = true;
                $('.svi_variations_options>a:not(.active)').trigger('click');
                WOOSVIADM.STARTS.block();

                let $slug = $(this).closest('.woocommerce_variation.wc-metabox').find('h3 select');
                let $slug_name = $(this).closest('.woocommerce_variation.wc-metabox').find('h3 select').find('option:selected');

                var $data = $.map($slug, (item, v) => {
                    if (item.value != '')
                        return item.value
                });
                var $data_txt = $slug_name.toArray().map((item, v) => {
                    if (item.value != '')
                        return item.text;
                }).filter(Boolean).join(" + ");

                let promise = WOOSVIADM.STARTS.reloadSelectPromise();
                var wrapper = $('div#sviselect_container');

                promise.then((response) => {
                        wrapper.empty().append(response);

                        $('select#sviprobulk').select2({
                            placeholder: "Select a Attribute",
                        });

                        if ($data.length > 0) {
                            $('#sviprobulk').val($data);
                        } else {
                            $('#sviprobulk').val(['sviproglobal']);
                            $data = ['sviproglobal'];
                            $data_txt = 'SVI GLobal';
                        }
                        $('#sviprobulk').trigger('change');

                        WOOSVIADM.STARTS.buidlSVIgal($data, $data_txt);

                        $selectedReload = false;
                        $ajax_run = false;
                    })
                    .catch((error) => {
                        //console.log(error)
                    });

            });
        },
        /**
         * Block edit screen
         */
        block: function() {
            $('#svi-images_tab_data').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },
        /**
         * Unblock edit screen
         */
        unblock: function() {
            $('#svi-images_tab_data').unblock();
        },
        initMedia: function() {
            $('.svi_variations_options>a:not(.active)').on('click', function() {
                if (!$selectedReload)
                    WOOSVIADM.STARTS.reloadSelect();
            });
        },
        reloadSelect: function() {
            WOOSVIADM.STARTS.block();
            var wrapper = $('div#sviselect_container');

            if ($ajax_run)
                return;

            $ajax_run = true;

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'html',
                data: {
                    action: 'woosvi_reloadselect',
                    data: $('#post_ID').val()
                },
                success: function(response) {
                    $ajax_run = false;
                    wrapper.empty().append(response);

                    // $('div[id^=__wp-uploader]').remove();
                    // WOOSVIADM.STARTS.initMediaGal();
                    //WOOSVIADM.STARTS.goMediaGallery();
                    WOOSVIADM.STARTS.unblock();
                    $('select#sviprobulk').select2({
                        placeholder: "Select a Attribute",
                    });

                    WOOSVIADM.STARTS.tag();
                }
            });
        },
        reloadSelectPromise: function() {
            if ($ajax_run)
                return new Promise((resolve, reject) => { reject('woosvi_reloadselect already running') });

            $ajax_run = true;
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        action: 'woosvi_reloadselect',
                        data: $('#post_ID').val()
                    },
                    success: function(data) {
                        resolve(data)
                    },
                    error: function(error) {
                        reject(error)
                    }
                });
            })
        },
        tag: function() {
            var $tag = $("select#sviprobulk");

            $(document).on("click", ".select2-results__group", function() {
                var groupName = $(this).html()
                var options = $('#sviprobulk option');
                $.each(options, function(key, value) {
                    if ('label' in $(value)[0].parentElement) {
                        if ($(value)[0].parentElement.label.indexOf(groupName) >= 0) {
                            $(value).prop("selected", "selected");
                        }
                    }
                });

                $("#sviprobulk").trigger("change");
                $("#sviprobulk").select2('close');

            });

            $tag.on('select2:selecting', function(e) {
                var sibling_elements = e.params.args.data.element.parentElement.children
                var sibParent = e.params.args.data.element.parentElement.parentElement.children;
                for (var i = 0; i < sibling_elements.length; i++) {
                    if (sibParent[0].value == 'svidefault')
                        sibParent[0].selected = false;
                    if (sibParent[1].value == 'sviproglobal')
                        sibParent[1].selected = false;
                    /*var sibParentChildren = sibling_elements[i].children;
                    if (sibParentChildren.length > 0) {
                        for (var i2 = 0; i2 < sibParentChildren.length; i2++) {
                            sibParentChildren[i2].selected = false
                        }
                    }
                    sibling_elements[i].selected = false*/
                }
            });
        },
        goMediaGallery: function() {

            $('#addsviprovariation').on('click', function(e) {
                e.preventDefault();
                WOOSVIADM.STARTS.block();
                WOOSVIADM.STARTS.buidlSVIgal();

            });

        },
        buidlSVIgal: function($data = false, $data_txt = false) {

            let $slug = $('#sviprobulk');
            let $slug_name = $('#sviprobulk option:selected');

            if (!$data)
                $data = $slug.val();
            if (!$data_txt)
                $data_txt = $slug_name.toArray();

            if ($data.length > 0) {
                $data = new Object();
                $data_txt = [];
                $.each($('#sviprobulk').find('option:selected'), (i, v) => {
                    var label = $(v).parents('optgroup').data('svilabel');
                    if (!label)
                        label = 'sviglobals';
                    var nVal = $(v).attr('value');
                    var nValText = $(v).text();
                    if (!(label in $data)) {
                        $data[label] = [];
                    }
                    $data[label].push(nVal);
                    $data_txt.push({ value: nVal, txt: nValText });
                });

                WOOSVIADM.STARTS.iterateGalCreation(WOOSVIADM.STARTS.allCombinations($data, $data_txt), 0);

            } else {

                WOOSVIADM.STARTS.runGalCreate($data, $data_txt);
            }

        },
        iterateGalCreation: function($combos, idx) {
            if (idx < $combos.combo.length) {

                var promise = WOOSVIADM.STARTS.runGalCreate($combos.combo[idx], $combos.txt[idx], true);

                promise.then(() => {

                    idx++;
                    WOOSVIADM.STARTS.iterateGalCreation($combos, idx);
                })
            }

        },

        runGalCreate: function($data, $data_txt, iterate = false) {
            return new Promise((resolve, reject) => {
                var $clone = $('div#svipro_clone').clone();
                $data_txt = $data_txt.join(" + ");
                var $where;
                var textshow;
                var promise = WOOSVIADM.STARTS.esc_html($data);

                promise.then(($slug) => {
                    $slug = $slug.replace(/^\s+/g, '');

                    $svikey = $('div[id^=svipro_]').size() - 1;

                    if (jQuery.inArray("sviproglobal", $data) >= 0 && $data.length > 1)
                        return;

                    if ($slug !== '' && $('div[data-svigal="' + $slug + '"]').length < 1) {
                        if (iterate)
                            WOOSVIADM.STARTS.block();

                        $svikey = $slug != 'svidefault' && $slug != 'sviproglobal' ? $svikey : $slug;

                        if ('Default Gallery' == $data_txt || 'SVI Global' == $data_txt)
                            textshow = "<span class='svititle svibadge svibadge-light'>" + $data_txt + '</span>';
                        else
                            textshow = $data_txt + ' Gallery</span>';

                        $($clone)
                            .attr('id', 'svipro_' + $svikey)
                            .removeClass('hidden')
                            .attr('data-svigal', $slug)
                            .attr('data-svikey', $svikey)
                            .find('h2 span.svititle').html(textshow);

                        if ($slug != 'svidefault' && $slug != 'sviproglobal')
                            $($clone).find('input.svipro-product_image_gallery_hidden').attr('name', 'sviproduct_image_gallery_hidden[' + $slug + ']');
                        else
                            $($clone).find('span.sviHiddenLoop').remove();

                        $($clone).find('input.svipro-product_image_gallery').attr('name', 'sviproduct_image_gallery[' + $slug + ']');
                        $($clone).hide();

                        switch ($svikey) {
                            case 'svidefault':
                                if ($('#svipro_x').length > 0)
                                    $where = '#svipro_x';
                                break;
                            case 'sviproglobal':
                                if ($('#svipro_svidefault').length > 0)
                                    $where = '#svipro_svidefault';
                                else if ($('#svipro_x').length > 0)
                                    $where = '#svipro_x';
                                break;
                            default:
                                if ($('#svipro_sviproglobal').length > 0)
                                    $where = '#svipro_sviproglobal';
                                else if ($('#svipro_svidefault').length > 0)
                                    $where = '#svipro_svidefault';
                                else if ($('#svipro_x').length > 0)
                                    $where = '#svipro_x';
                        }

                        if ($where)
                            $($clone).insertAfter($where);
                        else
                            $('#svigallery').prepend($clone);

                        $($clone).fadeIn(1500);
                        //$('html, body').animate({
                        //    scrollTop: $('#svipro_' + $svikey).offset().top - 100
                        //}, 500);

                        WOOSVIADM.STARTS.buildMediaGal($slug, $svikey);
                        WOOSVIADM.STARTS.removeMediaGallery($slug, $svikey);
                        WOOSVIADM.STARTS.removeElementMediaGallery($slug, $svikey);
                        WOOSVIADM.STARTS.sortableGallery();
                    }
                    WOOSVIADM.STARTS.unblock();
                    resolve(true);
                }).catch((error) => {
                    //console.log(error)
                    reject(error);
                });
            });
        },
        allCombinations: function(obj, $data_txt) {
            let combos = [{}];
            Object.entries(obj).forEach(([key, values]) => {
                let all = [];
                if (key !== 'sviglobals') {
                    values.forEach((value) => {
                        combos.forEach((combo) => {
                            all.push({...combo, [key]: value });
                        });
                    });
                    combos = all;
                }
            });

            $return = {
                combo: [],
                txt: []
            };
            if ('sviglobals' in obj) {
                obj.sviglobals.forEach((i, v) => {
                    $return.combo.push([i]);
                    if (i == 'svidefault')
                        $return.txt.push(['Default Gallery']);
                    else
                        $return.txt.push(['SVI Global']);

                });
            }
            combos.forEach((i, v) => {
                let objV = Object.values(i);
                $return.combo.push(objV);

                let $txt = $.map(objV, (variation, idx) => {
                    return $.map($data_txt, (txtObj, i2) => {
                        if (txtObj.value == variation)
                            return txtObj.txt.trim();
                    });
                });

                $return.txt.push($txt);

            });
            return $return;
        },
        initMediaGal: function() {
            $('div[id^=svipro_]').each(function(i, v) {
                WOOSVIADM.STARTS.buildMediaGal($(this).data('svigal'), $(this).data('svikey'));
                WOOSVIADM.STARTS.removeMediaGallery($(this).data('svigal'), $(this).data('svikey'));
                WOOSVIADM.STARTS.removeElementMediaGallery($(this).data('svigal'), $(this).data('svikey'));
                WOOSVIADM.STARTS.sortableGallery();
            });

            $("#product_images_container .product_images").sortable({
                connectWith: ".product_galsort",
                connectToSortable: '#destination',
                helper: function(e, li) {
                    //$copyHelper = li.clone().insertAfter(li);

                    $copyHelper = li.clone();

                    $(this).data('copied', false);

                    return li.clone();
                },
                //helper: "clone"
                remove: function(ev, li) {
                    setTimeout(() => {
                        if ($(ev.target).find('li.image[data-attachment_id="' + li.item.data('attachment_id') + '"]').length < 1) {
                            $(this).sortable('cancel');
                            //li.item.clone().insertAfter(ev.item);

                            var attachment_ids = [];
                            $("#product_images_container").find('li.image').css('cursor', 'default').each(function() {
                                var attachment_id = $(this).attr('data-attachment_id');
                                attachment_ids.push(attachment_id);
                            });
                            var unique = WOOSVIADM.STARTS.onlyUnique(attachment_ids);
                            $("#product_images_container").find('input#product_image_gallery').val(unique.join(','));

                        }

                    }, 200)
                },
            }).disableSelection();
        },
        sortableGallery: function() {
            $('#svigallery').sortable({
                items: '.postbox:not(#svipro_x,#svipro_svidefault,#svipro_sviproglobal)',
                cursor: 'move',
                scrollSensitivity: 40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'wc-metabox-sortable-placeholder',
                start: function(event, ui) {
                    ui.item.css('background-color', '#f6f6f6');
                },
                stop: function(event, ui) {
                    ui.item.removeAttr('style');
                },
                receive: function(ev, ui) {},
                update: function(current, data) {
                    var i = 0;

                    $('#svigallery .postbox').filter(function(index, v) {
                        if ($(this).attr('id') != 'svipro_x' && $(this).attr('id') != 'svipro_svidefault')
                            $(this).attr('id', 'svipro_' + i);
                        i++;
                    });
                }
            }).disableSelection();
        },
        startSortable: function($product_images, $input) {
            $product_images.sortable({
                items: 'li.image:not(.ui-state-disabled)',
                connectWith: ".product_galsort",
                cursor: 'move',
                scrollSensitivity: 40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'wc-metabox-sortable-placeholder',
                start: function(event, ui) {
                    ui.item.css('background-color', '#f6f6f6');
                },
                stop: function(event, ui) {
                    ui.item.removeAttr('style');
                },
                receive: function(ev, ui) {

                    if ($(ev.target).find('li.image[data-attachment_id="' + ui.item.data('attachment_id') + '"]').length > 1) {
                        ui.sender.sortable("cancel");
                    } else {

                        var $existing = $('div#svigallery').find('ul li.image[data-attachment_id="' + ui.item.data('attachment_id') + '"]').length - 1;


                        if ($(ui.sender).parent('#product_images_container').length > 0) {
                            ui.item.clone().appendTo(ev.target);
                            WOOSVIADM.STARTS.updateUnasignGal(ui.item.data('attachment_id'));
                        }

                        var slug = $(ui.sender).closest('.postbox').data('svigal');
                        var target_slug = $(ev.target).closest('.postbox').data('svigal');

                        if (target_slug == 'unsigned_svi') {
                            if ($existing > 0) {
                                alert('Notice: This image is still assigned to ' + $existing + ' other Galleries. It will only be unasigned from: ' + $(ui.sender).closest('.postbox').find('h2>span').html());
                                $(ui.item).fadeOut(2000).remove();
                            }
                        }


                    }
                },
                update: function(current, data) {

                    WOOSVIADM.STARTS.updateGal($input, $product_images); //Updates current 
                    WOOSVIADM.STARTS.removeElementMediaGallery($product_images.closest('.postbox').data('svigal'), $product_images.closest('.postbox').data('svikey'));

                }
            }).disableSelection();
        },
        buildMediaGal: function($slug, $svikey) {
            var product_gallery_frame;
            var $input = $('#svipro_' + $svikey).find('input.svipro-product_image_gallery');
            var $product_images = $('#svipro_' + $svikey).find('ul.product_images');
            var $product_images_woo = $('#product_images_container').find('ul.product_images');

            WOOSVIADM.STARTS.startSortable($product_images, $input);

            $('#svipro_' + $svikey).find('.add_product_images_svipro').on('click', 'a', function(event) {
                var $el = $(this);
                //var $input = $(this).closest('.postbox').find('input.svipro-product_image_gallery');
                //var $product_images = $(this).closest('.postbox').find('ul.product_images');

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (product_gallery_frame) {
                    product_gallery_frame.open();
                    return;
                }

                // Create the media frame.
                product_gallery_frame = wp.media.frames.product_gallery = wp.media({
                    // Set the title of the modal.
                    title: $el.data('choose'),
                    button: {
                        text: $el.data('update')
                    },
                    states: [
                        new wp.media.controller.Library({
                            title: $el.data('choose'),
                            filterable: 'all',
                            multiple: true
                        })
                    ]
                });



                // When an image is selected, run a callback.
                product_gallery_frame.on('select', function() {
                    var selection = product_gallery_frame.state().get('selection');
                    selection.map(function(attachment) {
                        attachment = attachment.toJSON();

                        if (attachment.id && $product_images.find('li.image[data-attachment_id="' + attachment.id + '"]').length < 1) {
                            if ($svikey != 'x')
                                $('#svipro_x').find('ul li.image[data-attachment_id="' + attachment.id + '"]').remove();

                            var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                            var $elm = $('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#/" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');
                            $elm.insertBefore($product_images.find('li').last());


                            //Add new image to WC product gallery
                            if ($('#product_images_container').find('ul li.image[data-attachment_id="' + attachment.id + '"]').length < 1) {

                                $product_images_woo.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#/" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');
                            }
                        }
                    });

                    WOOSVIADM.STARTS.updateGal($input, $product_images); //Updates current gallery
                    WOOSVIADM.STARTS.updateGal($('#svipro_x').find('input.svipro-product_image_gallery'), $('#svipro_x')); //UPDATE NULL SVI GALLERY
                    WOOSVIADM.STARTS.updateGal($('input#product_image_gallery'), $('#product_images_container')); //UPDATE MAIN GALLERY
                    WOOSVIADM.STARTS.removeElementMediaGallery($slug, $svikey); //Detect new element for delete
                    WOOSVIADM.STARTS.prepMediaView();
                    WOOSVIADM.STARTS.tiptipInit();

                });


                // Finally, open the modal.
                product_gallery_frame.open();
            });

        },
        removeMediaGallery: function($slug, $svikey) {
            $('#svipro_' + $svikey).on('click', 'a.sviprobulk_remove', function(event) {
                let txtDel = '';
                if ($(this).closest('.postbox').find('h2>span>span').length > 0)
                    txtDel = $(this).closest('.postbox').find('h2>span>span').html();
                else
                    txtDel = $(this).closest('.postbox').find('h2>span').html();

                if (confirm("Delete " + txtDel + "?")) {
                    event.preventDefault();
                    var $input = $(this).closest('div.svi-woocommerce-product-images').find('input.svipro-product_image_gallery').val().split(',');

                    $(this).closest('div.svi-woocommerce-product-images').remove();

                    $.each($input, function(i, v) {
                        if ($('#svigallery').find('ul li.image[data-attachment_id="' + v + '"]').length < 1)
                            $('#product_images_container').find('ul li.image[data-attachment_id="' + v + '"]').remove();
                    });

                    WOOSVIADM.STARTS.updateGal($('#product_image_gallery'), $('#product_images_container')); //UPDATE MAIN GALLERY
                }
                return false;
            });

        },
        removeElementMediaGallery: function($slug, $svikey) {
            // Remove images.
            $('#svipro_' + $svikey).find('li.image').on('click', 'a.delete', function() {
                var att_id = $(this).closest('li.image').attr('data-attachment_id');
                var $product_images = $(this).closest('ul.product_images');
                var $input = $(this).closest('div.svipro-product_images_container').find('input.svipro-product_image_gallery');
                var attachment_ids = [];


                $(this).closest('li.image').remove(); // Removes the thumbnail image from gallery

                //START If image not found in SVI Gallery we can remove it from the WC Product Gallery
                if ($('#svigallery').find('ul li.image[data-attachment_id="' + att_id + '"]').length < 1) {
                    $('#product_images_container').find('ul li.image[data-attachment_id="' + att_id + '"]').remove();
                }
                //END

                WOOSVIADM.STARTS.updateGal($('input#product_image_gallery'), $('div#product_images_container')); //Updates main gallery
                WOOSVIADM.STARTS.updateGal($input, $product_images); //Updates current gallery

                // Remove any lingering tooltips.
                $('#tiptip_holder').removeAttr('style');
                $('#tiptip_arrow').removeAttr('style');

                WOOSVIADM.STARTS.prepMediaView();

                return false;
            });

            // Remove images.
            $('#product_images_container').on('click', 'a.delete', function() {
                var attachment_id = $(this).closest('li.image').attr('data-attachment_id');

                var $thumb = $('div#svigallery').find('ul li.image[data-attachment_id="' + attachment_id + '"]');
                $.each($thumb, function() {
                    var $sviblock = $(this).closest('div.svipro-product_images_container');
                    var $input = $sviblock.find('input.svipro-product_image_gallery');
                    $(this).remove();

                    WOOSVIADM.STARTS.updateGal($input, $sviblock); //Updates current gallery
                })
                return false;
            });

        },
        updateUnasignGal: function(attachment_id) {
            var $thumb = $('div#svipro_x').find('ul li.image[data-attachment_id="' + attachment_id + '"]');
            var $sviblock = $thumb.closest('div.svipro-product_images_container');
            var $input = $sviblock.find('input.svipro-product_image_gallery');
            $thumb.remove();

            WOOSVIADM.STARTS.updateGal($input, $sviblock); //Updates current gallery
        },
        updateGal: function($image_gallery_ids_svi, $product_images) {
            var attachment_ids = [];

            $product_images.find('li.image').each(function() {
                attachment_ids.push($(this).attr('data-attachment_id'));
            });
            var unique = WOOSVIADM.STARTS.onlyUnique(attachment_ids);
            $image_gallery_ids_svi.val(unique.join(','));
        },
        esc_html: function(handleData) {
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'woosvi_esc_html',
                        data: handleData
                    },
                    success: function(data) {
                        resolve(data)
                    },
                    error: function(error) {
                        reject(error)
                    }
                });
            });
        },
        onlyUnique: function(ids) {
            var uniqueIds = [];
            $.each(ids, function(i, el) {
                if ($.inArray(el, uniqueIds) === -1) uniqueIds.push(el);
            });
            return uniqueIds;
        },
        prepMediaView: function() {
            $('.svi-videomanager').remove();

            let $inputvideo;
            let mainID = $('input#_thumbnail_id').val();

            videoVal = WOOSVIADM.STARTS.findVideoVal(mainID, '[wc_svimainvideo]');

            if (mainID && videoVal) {
                $inputvideo = WOOSVIADM.STARTS.videoAppend('editvideo', 'Edit/Remove Video', mainID, 'main', 'wc_svimainvideo');
            } else {
                $inputvideo = WOOSVIADM.STARTS.videoAppend('addvideo', 'Add Video', mainID, 'main', 'wc_svimainvideo');
            }

            $('#set-post-thumbnail').parent().append($inputvideo);

            if ($('#product-type').val() != 'variable')
                WOOSVIADM.STARTS.prepVideoAppend('#product_images_container', '')
            else
                WOOSVIADM.STARTS.prepVideoAppend('.svipro-product_images_container', 'variable')

        },
        prepVideoAppend: function($target, $type) {

            $($target).find('ul li.image').each(function(i, v) {
                let videoVal, $inputvideo;
                let attachment_id = $(this).attr('data-attachment_id');

                let $svi_gal = $(this).closest('.svi-woocommerce-product-images').length > 0 ? $(this).closest('.svi-woocommerce-product-images').data('svigal') : $type == 'variable' ? $type : attachment_id;

                videoVal = WOOSVIADM.STARTS.findVideoVal(attachment_id, '[' + $svi_gal + ']');

                if (videoVal) {
                    $inputvideo = WOOSVIADM.STARTS.videoAppend('editvideo', 'Edit/Remove Video', attachment_id, $type, $svi_gal);
                } else {
                    $inputvideo = WOOSVIADM.STARTS.videoAppend('addvideo', 'Add Video', attachment_id, $type, $svi_gal);
                }

                $(this).append($inputvideo)

            })

            WOOSVIADM.STARTS.tiptipInit();

            WOOSVIADM.STARTS.detectVideoAddClick();

        },
        detectVideoAddClick: function() {
            var dialog = $('#svipro_dialogvideo').dialog({
                title: 'Video',
                dialogClass: 'wp-dialog svipro-dialog',
                autoOpen: false,
                width: 'auto',
                modal: true,
                resizable: false,
                closeOnEscape: true,
                position: {
                    my: "center",
                    at: "center",
                    of: window
                },
                buttons: [{
                    text: "Close",
                    click: function() {
                        $(this).dialog("close");
                    }
                }, {
                    text: "Remove",
                    click: function() {
                        WOOSVIADM.STARTS.handleVideoData('delete');
                        $(this).dialog("close");
                    }
                }, {
                    text: "Save Video",
                    click: function() {
                        WOOSVIADM.STARTS.handleVideoData('addedit');
                        $(this).dialog("close");
                    }
                }],
                close: function() {
                    $("#svipro_video_input").val('');
                },
                open: function() {
                    // close dialog by clicking the overlay behind it
                    $('.ui-widget-overlay').bind('click', function() {
                        $('#svipro_dialogvideo').dialog('close');
                    })
                },
            });

            $('.svi-videomanager').on('click', function(e) {
                e.preventDefault();
                $video_data = $(this).data();

                let videoVal = WOOSVIADM.STARTS.findVideoVal($video_data.attachment_id, '[' + $video_data.svi_gal + ']');

                if (videoVal)
                    $("#svipro_video_input").val(videoVal);

                dialog.dialog('open');
            })
        },
        tiptipInit: function() {
            $('.svi-addvideo,.svi-editvideo').tipTip({
                'attribute': 'data-tip',
                'fadeIn': 50,
                'fadeOut': 50,
                'delay': 200
            });
        },
        handleVideoData($action) {
            let vidInput = $("#svipro_video_input");

            let addNewVideoData;
            let el = $('#svipro_video').find('input[name="sviproduct_video_gallery[' + $video_data.svi_gal + '][' + $video_data.attachment_id + ']"]');


            switch ($action) {
                case 'addedit':
                    if (vidInput.val() != '') {
                        if (el.length > 0)
                            el.val(vidInput.val());
                        else {
                            addNewVideoData = $video_tag
                                .replace("{{slug}}", $video_data.svi_gal)
                                .replace("{{attachment_id}}", $video_data.attachment_id)
                                .replace("{{video_url}}", vidInput.val());

                            $('#svipro_video').append(addNewVideoData);
                        }
                    }
                    break;
                case 'delete':
                    if (el.length > 0)
                        el.remove();
                    break;
            }

            WOOSVIADM.STARTS.prepMediaView();

        },
        findVideoVal(id, $svi_gal = '') {
            let el = $('#svipro_video').find('input[name="sviproduct_video_gallery' + $svi_gal + '[' + id + ']"]');
            return el.length > 0 ? el.val() : false;
        },
        videoAppend(video_action, vidTxt, attachment_id, type, svi_gal) {
            return $video_tag_add
                .replace("{{video_action}}", video_action)
                .replace("{{vidTxt}}", vidTxt)
                .replace("{{attachment_id}}", attachment_id)
                .replace("{{type}}", type)
                .replace("{{svi_gal}}", svi_gal);
        }
    }
}(jQuery);
jQuery(document).ready(function() {
    WOOSVIADM.STARTS.init();
});