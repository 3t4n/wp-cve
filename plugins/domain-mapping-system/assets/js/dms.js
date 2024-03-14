(function ($, dms_fs) {
    var collector = {
            init: function () {
                var check,
                    saveButtons = $(".dms-submit"),
                    disabledDelay = $(saveButtons[0]).data('disabled_delay');
                // Set onclick to all of them
                saveButtons.on("click", function (e) {
                    check = confirm(collector.translate('Are you sure to change these settings?'));
                    if (check === false) {
                        e.preventDefault();
                    }
                });
                if (disabledDelay) {
                    var disabledNote = $('.dms-disabled-delay-note');
                    var disableSetInterval = setInterval(function () {
                        disabledDelay--;
                        if (disabledDelay <= 0) {
                            // Remove main delay note
                            disabledNote.remove();
                            // Stops the setInterval
                            clearInterval(disableSetInterval);
                            // Enable all buttons
                            saveButtons.removeAttr('disabled');
                            var otherButtons = $('button[data-disabled_delay]');
                            if (otherButtons.length) {
                                otherButtons.removeAttr('disabled');
                            }
                        } else {
                            disabledNote.find('b.timer').html(disabledDelay);
                        }
                    }, 1000);
                }
            },
            translate: function ($string) {
                return dms_fs.translations[$string] ? dms_fs.translations[$string] : $string;
            },
            is_premium: function () {
                return dms_fs.is_premium === '0' ? false : dms_fs.is_premium === '1'
            }
        },
        controls = {
            init: function () {
                var that = this,
                    body = $('body');
                /**
                 * Delete mapping row
                 */
                body.on('click', '.dms-n-config-table-delete', function (e) {
                    e.preventDefault();
                    var toBeRemovedEl = $('#dms-domains-to-remove'),
                        toBeRemovedElVal = toBeRemovedEl.val(),
                        toBeRemovedElValArr = toBeRemovedElVal ? toBeRemovedElVal.split(',') : [],
                        id = $($(this).parent().find('.dms-map-id')[0]).val();
                    that.removeRow($(this));
                    // Fill the hidden input value to collect remove domains.
                    toBeRemovedElValArr.push(id);
                    toBeRemovedEl.val(toBeRemovedElValArr.join(','));
                    // Trigger global mapping select change
                    $('tr.dms-single-mapping .dms-mapping-host').trigger('change');
                });

                /**
                 * Add mapping row
                 */
                body.on('click', '.dms-add-row', function (e) {
                    e.preventDefault();
                    that.addRow(false);
                });

                /**
                 * Check/uncheck post types
                 */
                body.on('click', '.dms-n-post-types-checkbox', function(e){
                    $(this).parent().toggleClass('checked');
                });

                /**
                 * Show more/less
                 */
                body.on('click', '.dms-n-config-table-dropdown', function(e){
                    e.preventDefault()
                    $(this).toggleClass('opened')
                    $(this).parent().find('.dms-n-config-table-row').last().toggleClass('closed')
                    that.shrunkSet();
                });

                /**
                 * Update main domain select on each mapping input change
                 */
                body.on('change', 'tr.dms-single-mapping .dms-mapping-host,tr.dms-single-mapping .dms-mapping-path', function (e) {
                    if (!collector.is_premium()) {
                        return;
                    }
                    e.preventDefault();
                    var existingSelect = $('.dms-main-domain'),
                        hostEls = $('.dms-mapping-host');

                    if (existingSelect.length && hostEls.length) {
                        controls.addMainDomainSelect();
                    }
                });

                /**
                 * Hide configs bar
                 */
                body.on('click', '#show-settings-link', function (e) {
                    e.preventDefault()
                    $('#screen-options-wrap').toggleClass('dms-hide-configuration-bar')
                })

                // Check WPCS main button existence existence
                if ($('.dms-n-config-table-wpcs-set-main-domain').length) {
                    /*
                    * Set main domain for WPCS Tenant platform
                    * */
                    body.on('click', '.dms-n-config-table-wpcs-set-main-domain', function (e) {
                        e.preventDefault();
                        var check = confirm(collector.translate('Warning! You will be logged out, and you will need to login again using the new domain. Be sure you know your login details. It may take up to 3 minutes for the change to process.'));
                        if (check === false) {
                            return;
                        }
                        $('#dms_platform_wpcs_domain_map_id_value').val($(this).data('map_id'));
                        $('#dms_platform_wpcs_set_tenant_main_domain_form').submit();
                    });
                }

                /**
                 * Remove favicon
                 */
                body.on('click', '.dms-delete-img', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    $this.parent().find('.favicon').remove();
                    $this.parent().find('.dms-attachment-id')[0].value = 0;
                    $this.hide();
                });

                /**
                 * MDM related import
                 */
                body.on('click', '#dms-mdm-import a.yes, #dms-mdm-import a.no', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    if($this.hasClass('no')) {
                        // Remove bar, hide notification
                        if(confirm(collector.translate('Are you sure you would like to avoid importing mappings form Multiple Domain Mapping?'))) {
                            $.post( 
                                dms_fs.ajax_url, 
                                {
                                    action: 'dms_hide_mdm_note',
                                    nonce: dms_fs.nonce
                                }
                            ).done(function( data ) {
                                if(data && data.status) {
                                    $this.parent().parent().remove();
                                }
                            });
                        }
                    } else {
                        if($this.hasClass('yes')) {
                            if(confirm(collector.translate('Are you sure you would like to import mappings from Multiple Domain Mapping? Warning: Ensure you have a backup available, as errors may occur.'))) {
                                // Yes proceed import, hide notification bar
                                $('#dms-mdm-import-form').submit();
                            }
                        }
                    }
                });

                /**
                 * Expand and collapse additional options
                 */
                body.on('click', '.dms-n-additional-accordion-header', function (){
                    $(this).parent().toggleClass('opened');
                })
            },
            removeRow: function (btn) {
                $(btn).closest(".dms-n-config-table").remove();
            },
            
            /**
             * Saving a current shrinked field position in the cookie
             */
            shrunkSet: function () {
                var shrink_btn_array = $(".dms-n-config-table-dropdown"),
                    shrinked_all_filed_array = $(".dms-n-config-table-row"),
                    shrinked_filed_array = [],
                    will_shrink = 0,
                    // If you need a save some data in cookie you can push it in dms_cookie, which is associative array
                    dms_cookie = {'shrinked': []},
                    // Those vars are, for settings a cookies empires time
                    date = new Date(),
                    days = 365;
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                for (let i = 0; i < shrink_btn_array.length; i++) {
                    shrink_btn_array[i] === this ? will_shrink = i : ''
                }
                // This is generated a new array only with shrunked fields
                for (let i = 1 ; i < shrinked_all_filed_array.length; i += 2) {
                    shrinked_filed_array.push(shrinked_all_filed_array[i]);
                }
                for (let i = 0; i < shrinked_filed_array.length; i++) {
                    shrinked_filed_array[i].classList.length === 1 ? dms_cookie['shrinked'][i] = "opened" : dms_cookie['shrinked'][i] = "closed";
                }
                document.cookie = `dms_cookie=${JSON.stringify(dms_cookie)};expires=${date.toUTCString()}`;
            },

            /**
             * Collapse a field, based on the cookies
             */
            shrunkCheck: function () {
                let cookie_array = (!getCookie("dms_cookie")) ? null : JSON.parse(getCookie("dms_cookie")),
                    shrinked_all_filed_array = $(".dms-n-config-table-row"),
                    shrink_btn = $(".dms-n-config-table-dropdown");
                function getCookie(cname) {
                    var name = cname + "=",
                        all_cookie_array = document.cookie.split(';');
                    for (let i = 0; i < all_cookie_array.length; i++) {
                        let c = all_cookie_array[i];
                        while (c.charAt(0) == ' ') {
                            c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                            return c.substring(name.length, c.length);
                        }
                    }
                    return "";
                }
                for (let i = 1, j = 0; i < shrinked_all_filed_array.length; i += 2, j++) {
                    if ( cookie_array["shrinked"] ) {
                        if (cookie_array["shrinked"][j] === "closed") {
                            shrinked_all_filed_array[i].classList.add("closed");
                            shrink_btn[j].classList.remove("opened");
                        } else if (cookie_array["shrinked"][j] === "opened") {
                            shrinked_all_filed_array[i].classList.remove("closed");
                            shrink_btn[j].classList.add("opened");
                        }
                    }
                    else{
                        // this part for showing a last field opened , and other closed, if cookie not exist
                        if(i == (shrinked_all_filed_array.length - 1)){
                            shrinked_all_filed_array[i].classList.remove("closed");
                            shrink_btn[j].classList.add("opened");
                        }
                        else {
                            shrinked_all_filed_array[i].classList.add("closed");
                            shrink_btn[j].classList.remove("opened");
                        }
                    }
                }
            },

            addRow: function (flag) {
                // Define variables
                // Get all rows,
                // Find the last index and create new index , then apply to new row ( both to row and select )
                // Get default select options, create select and apply options to it
                var map = $("#dms-map"),
                    mappings = map.find('.dms.dms-n-config-table-select'),
                    multiple = collector.is_premium() ? 'multiple ' : '',
                    index = mappings && mappings.length ? (mappings.last().data('index') + 1) : 0,
                    options = $($('#dms-default-select').find('select')[0]).html(),
                    tr = '<div class=\'dms-n-config-table\'>\n' +
                        '<button class=\'dms-n-config-table-dropdown opened\'>\n' +
                        '<i></i>\n' +
                        '</button>\n' +
                        '<div class=\'dms-n-config-table-in\'>\n' +
                        '<div class=\'dms-n-config-table-row first\'>\n' +
                        '<div class=\'dms-n-config-table-column domain\'>\n' +
                        '<div class=\'dms-n-config-table-header\'>\n' +
                        '<p>\n' +
                        '<span>' + collector.translate('Enter Mapped Domain') +'</span>\n' +
                        '</p>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-body\'>\n' +
                        '<span  class="dms-n-config-table-body-scheme">'+dms_fs.scheme+'://</span>\n' +
                        '<input type=\'text\'\n' +
                        'name=\'dms_map[domains]['+ index +'][host]\'\n' +
                        'class=\'dms-n-config-table-input\'\n' +
                        'placeholder=\'example.com\'>\n' +
                        '<span class="slash">/</span>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-column subdirectory\'>\n' +
                        '<div class=\'dms-n-config-table-header\'>\n' +
                        '<p>\n' +
                        '<span>' + collector.translate('Enter Subdirectory (optional)') + '</span>\n' +
                        (! collector.is_premium() ? 
                        '<a href="'+dms_fs.upgrade_url+'">' +
                        collector.translate('Upgrade') + '&#8594' +
                        '</a>' : '') +
                        '</p>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-body\'>\n' +
                        '<input type=\'text\'\n' +
                        'name=\'dms_map[domains]['+ index +'][path]\'\n' +
                        (! collector.is_premium() ? 'disabled' : '') + '\n' +
                        'class=\'dms-n-config-table-input\'\n' +
                        'placeholder=\'' + collector.translate('Sub Directory') + '\'>\n' +
                        '<span class="slash">/</span>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-column content\'>\n' +
                        '<div class=\'dms-n-config-table-header\'>\n' +
                        '<p>\n' +
                        '<span>' + collector.translate('Select the Published Content to Map for this Domain.') + '</span>\n' +
                        (! collector.is_premium() ? 
                        '<a href="'+dms_fs.upgrade_url+'">' +
                        collector.translate('Upgrade') + '&#8594' +
                        '</a>' : '') +
                        '</p>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-body\'>\n' +
                        '<select class="dms dms-n-config-table-select"\n' +
                        'name="dms_map[domains][' + index + '][mappings][values][]"\n' +
                        'data-index="' + index + '"\n' +
                        'data-placeholder="' + collector.translate('The choice is yours.') + '"\n' + multiple +
                        'value="">\n' +
                        options +
                        '</select>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-row\'>\n' +
                        '<div class=\'dms-n-config-table-column code\'>\n' +
                        '<div class=\'dms-n-config-table-header\'>\n' +
                        '<p>\n' +
                        '<span>' + collector.translate('Custom HTML Code') + '</span>\n' +
                        (! collector.is_premium() ?
                        '<a href="'+dms_fs.upgrade_url+'">' +
                        collector.translate('Upgrade') + '&#8594' +
                        '</a>' : '') +
                        '</p>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-body\'>\n' +
                        '<input type=\'text\'\n' +
                        'name=\'dms_map[domains]['+ index +'][custom_html]\'\n' +
                        'class=\'dms-n-config-table-input-code\'\n' +
                        'placeholder=\'</' + collector.translate('Code here') + '>\'' +
                        '' + (! collector.is_premium() ? 'disabled' : '') + '/>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-column favicon\'>\n' +
                        '<div class=\'dms-n-config-table-header\'>\n' +
                        '<p>\n' +
                        '<span>' + collector.translate('Favicon per Domain') + '</span>\n' +
                        (! collector.is_premium() ? 
                        '<a href="'+dms_fs.upgrade_url+'">' +
                        collector.translate('Upgrade') + '&#8594' +
                        '</a>' : '') +
                        '</p>\n' +
                        '</div>\n' +
                        '<div class=\'dms-n-config-table-body\'>\n' +
                        '<div class=\'dms-n-config-table-favicon\'>\n' +
                        '<input type="button" name="upload-btn"\n' +
                        'class="' + (! collector.is_premium() ? 'disabled' : '') + ' upload upload-btn"\n' +
                        'value="'+collector.translate('Upload Image')+'" \n' +
                        'id="'+ index +'" >\n' +
                        (collector.is_premium() ?
                        '<input class="dms-attachment-id"  type="hidden"\n' +
                        'name="dms_map[domains]['+index+'][attachment_id]"\n' +
                        'value="">\n' +
                        '<button class="dms-delete-img"\n' +
                        'title="delete"\n' +
                        'style="display: none"\n' +
                        '>&times;\n' +
                        '</button>\n' : '') +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        (!flag ?
                        '<button class="dms-n-config-table-delete">' : "")+
                        '<i></i>' +
                        '</button>' +
                        '</div>',
                    args = {
                        placeholder: collector.translate('The choice is yours.')
                    };
                // Insert in the table
                $(".dms-n-row-footer").before($(tr));
                // Initiate new select2
                if (collector.is_premium()) {
                    args.templateSelection = select2.select2Template;
                }
                // var select2El = $($('#dms-map select.dms[data-index="' + index + '"]')[0]);
                var select2El = $('#dms-map select.dms[data-index="' + index + '"]');
                select2El.select2(args);
                // Initialize on change
                if (collector.is_premium()) {
                    // Set up unselect event to remove domain from some options connected
                    select2.eventsConfiguration(select2El);
                }
            },
            addMainDomainSelect: function () {
                if (!collector.is_premium()) {
                    return;
                }
                var hostEls = $('.dms-mapping-host'),
                    existingSelect = $('.dms-main-domain'),
                    selectedInitial = existingSelect.length ? existingSelect.find('option:selected').val() : null,
                    container = $('.dms-main-domain-container'),
                    dmsMainDomains = '',
                    options = '';
                for (var i = 0; i < hostEls.length; i++) {
                    var hostEl = $(hostEls[i]),
                        path = hostEl.next().next().children(0).val(),
                        value = hostEl.val() && path ? hostEl.val() + '/' + path : (hostEl.val() ? hostEl.val() : ''),
                        selected = selectedInitial === value ? 'selected' : '';
                    if (value.trim() === '') {
                        continue;
                    }
                    options += '<option value="' + value + '" ' + selected + '>' + value + '</option>'
                }
                dmsMainDomains = collector.translate('Select the domain [+path] to serve for all unmapped pages:') +
                    '<select name="dms_main_domain" class="dms-main-domain">' +
                    '<option value="0">' + collector.translate('Select domain') + '</option>' +
                    options +
                    '</select>';
                if (existingSelect.length) {
                    existingSelect.remove();
                }
                container.html(dmsMainDomains);
            }
        },
        select2 = {
            init: function () {
                // Initialize select2 on all mapping values selects
                var selects = $("select.dms"),
                    args = {
                        placeholder: collector.translate('The choice is yours.')
                    };
                if (collector.is_premium()) {
                    args.templateSelection = this.select2Template;
                }
                selects.select2(args);
                // Set up unselect event to remove domain from some options connected
                this.eventsConfiguration(selects);
            },
            eventsConfiguration: function (selects) {
                // console.log(selects.length);
                // Set up events connected with options selecting/unselecting
                selects.each(function () {
                    $(this).on('select2:unselect', function (e) {
                        // Some events could listed here
                    });
                    $(this).on('select2:select', function (e) {
                        // Some events could listed here
                    });
                });
            },
            select2Template: function (state, container) {
                if (!state.id) {
                    return state.text;
                }
                var stateElement = $(state.element),
                    selectElement = $(state.element).parent().parent(),
                    selectedElements = selectElement.find("option:selected"),
                    isPrimary = stateElement.data('primary') ? true : (selectedElements && selectedElements.length === 1 ? stateElement.data('primary', 1) : 0),
                    index = selectElement.data('index'),
                    checked = isPrimary ? 'checked' : '',
                    $state = $(
                        '<span>' +
                        '<span class="dms-mapped-page-selected"></span>' +
                        '<span>' +
                        '<input style="margin-left: 5px;" ' +
                        'name="dms_map[domains][' + index + '][mappings][primary]" ' +
                        'value="' + state.id + '" ' +
                        'type="radio" ' + checked + '  />' +
                        '</span>' +
                        '</span>'
                    );

                $($state.find("span")[0]).text(state.text);
                $($state.find('input[type="radio"]')[0]).on('click', function (e) {
                    e.stopPropagation();
                }).on('change', function (e) {
                    // Remove data primary from all elements first
                    $(selectedElements).each(function (index) {
                        $(selectedElements[index].element).data('primary', 0);
                    });
                    // Add to exact one
                    if ($(this).is(':checked')) {
                        $(state.element).data('primary', 1);
                    } else {
                        $(state.element).data('primary', 0);
                    }
                });
                return $state;
            }
        },
        tabs = {
            init: function () {
                var nav_tabs = $('.dms.nav-tab');
                if (nav_tabs.length) {
                    nav_tabs.on('click', function (e) {
                        e.preventDefault();
                        $('.dms.nav-tab').removeClass('nav-tab-active');
                        $('.dms-tab-container').hide();
                        $(this).addClass('nav-tab-active');
                        $($(this).attr('href')).show();
                    });
                    $('.dms.nav-tab.nav-tab-active').trigger('click');
                }
            }
        },
        favicon = {
            getImageSelectOptions: function (attachment, controller) {
                var realWidth = attachment.get('width'),
                    realHeight = attachment.get('height');
                return {
                    handles: true,
                    keys: true,
                    instance: true,
                    persistent: true,
                    imageWidth: realWidth,
                    imageHeight: realHeight,
                    minWidth: attachment.get('width') < 512 ? attachment.get('width') : 512,
                    minHeight: attachment.get('height') < 512 ? attachment.get('height') : 512,
                    x1: 0,
                    y1: 0,
                    x2: realWidth,
                    y2: realHeight
                };
            },
            init: function () {
                var body = $('body');
                /**
                 * Creates media uploader for favicons
                 */
                body.on('click', '.upload-btn', function (event) {
                    event.preventDefault();
                    if (!collector.is_premium()) {
                        return;
                    }
                    var mediaUploader,
                        cropControl = {
                            id: "control-id",
                            params: {
                                flex_width: false,  // set to true if the width of the cropped image can be different to the width defined here
                                flex_height: false, // set to true if the height of the cropped image can be different to the height defined here
                                width: 512,  // set the desired width of the destination image here
                                height: 512, // set the desired height of the destination image here
                            },
                        };
                    mediaUploader = wp.media({
                        button: {
                            text: 'Select', // l10n.selectAndCrop,
                            close: false
                        },
                        states: [
                            new wp.media.controller.Library({
                                title: collector.translate('Select and Crop'), // l10n.chooseImage,
                                library: wp.media.query({type: 'image'}),
                                multiple: false,
                                date: false,
                                priority: 20,
                                suggestedWidth: 512,
                                suggestedHeight: 512
                            }),
                            new wp.media.controller.CustomizeImageCropper({
                                imgSelectOptions: favicon.getImageSelectOptions,
                                control: cropControl
                            })
                        ]
                    });

                    mediaUploader.on('cropped', function (croppedImage) {
                        // let index = event.target.id;
                        if(!event.target.parentNode.querySelector('.favicon')){
                            let favicon = document.createElement('img');
                            favicon.classList.add('favicon');
                            favicon.src = croppedImage.url;
                            event.target.parentNode.prepend(favicon);
                        } else {
                            event.target.parentNode.querySelector('.favicon').src = croppedImage.url;
                        }
                        event.target.parentNode.querySelector(".dms-attachment-id").value = croppedImage.id;
                        event.target.parentNode.querySelector('.dms-delete-img').style.display = 'block';
                    });

                    mediaUploader.on("select", function () {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        if (cropControl.params.width === attachment.width
                            && cropControl.params.height === attachment.height
                            && !cropControl.params.flex_width
                            && !cropControl.params.flex_height) {
                                if(!event.target.parentNode.querySelector('.favicon')){
                                    let favicon = document.createElement('img');
                                    favicon.classList.add('favicon');
                                    favicon.src = attachment.url;
                                    event.target.parentNode.prepend(favicon);
                                } else {
                                    event.target.parentNode.querySelector('.favicon').src = attachment.url;
                                }
                                event.target.parentNode.querySelector(".dms-attachment-id").value = attachment.id;
                                event.target.parentNode.querySelector('.dms-delete-img').style.display = 'block';
                                mediaUploader.close();
                        } else {
                            mediaUploader.setState('cropper');
                        }

                    });

                    mediaUploader.open();
                });
            }
        }
    // Document ready event
    $(document).ready(function () {
        // Initializations
        collector.init();
        controls.init();
        select2.init();
        tabs.init();
        favicon.init();
        // Add empty line
        controls.addRow(true);
        controls.shrunkCheck();
    });
})(jQuery, dms_fs);
