
var wpzDialogHelper = {
    needsPreview: false,
    setUpButtons: function () {
        var a = this;
        jQuery( "#wpz-btn-cancel").click(function () {
            a.closeDialog()
        });
        jQuery( "#wpz-btn-insert").click(function () {
            a.insertAction()
        });
        jQuery( "#wpz-btn-preview").click(function () {
            a.previewAction()
        })
    },

    setUpColourPicker: function () {

        var startingColour = '000000';

        jQuery( '.wpz-marker-colourpicker-control div.colorSelector').each ( function () {

            var colourPicker = jQuery(this).ColorPicker({

            color: startingColour,
            onShow: function (colpkr) {
                jQuery(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr) {
                jQuery(colpkr).fadeOut(500);

                wpzDialogHelper.previewAction();

                return false;
            },
            onChange: function (hsb, hex, rgb) {
                jQuery(colourPicker).children( 'div').css( 'backgroundColor', '#' + hex);
                jQuery(colourPicker).next( 'input').attr( 'value','#' + hex);
            }

            });

            // jQuery(colourPicker).children( 'div').css( 'backgroundColor', '#' + startingColour);
            // jQuery(colourPicker).next( 'input').attr( 'value','#' + startingColour);


        });

        jQuery( '.colorpicker').css( 'position', 'absolute').css( 'z-index', '9999' );

    },

    loadShortcodeDetails: function () {
        if (wpzSelectedShortcodeType) {

            var a = this;
            jQuery.getScript(shortcode_generator_url + "shortcodes/" + wpzSelectedShortcodeType + ".js", function () {
                a.initializeDialog();

                // Set the default content to the highlighted text, for certain shortcode types.
                switch ( wpzSelectedShortcodeType ) {

                    case 'box':
                    case 'ilink':
                    case 'quote':
                    case 'button':
                    case 'abbr':
                    case 'unordered_list':
                    case 'ordered_list':
                    case 'typography':

                        jQuery( 'input#wpz-value-content').val( selectedText );

                    case 'toggle':

                        jQuery( 'textarea#wpz-value-content').val( selectedText );

                    break;

                } // End SWITCH Statement

                // Automatic preview generation on load.
                a.previewAction();
            })

        }

    },
    initializeDialog: function () {

        if (typeof wpzShortcodeMeta == "undefined") {
            jQuery( "#wpz-options").append( "<p>Error loading details for shortcode: " + wpzSelectedShortcodeType + "</p>" );
        } else {
            if (wpzShortcodeMeta.disablePreview) {
                jQuery( "#wpz-preview").remove();
                jQuery( "#wpz-btn-preview").remove()
            }
            var a = wpzShortcodeMeta.attributes,
                b = jQuery( "#wpz-options-table" );

            for (var c in a) {
                var f = "wpz-value-" + a[c].id,
                    d = a[c].isRequired ? "wpz-required" : "",
                    g = jQuery( '<th valign="top" scope="row"></th>' );

                var requiredSpan = '<span class="optional"></span>';

                if (a[c].isRequired) {

                    requiredSpan = '<span class="required">*</span>';

                } // End IF Statement
                jQuery( "<label/>").attr( "for", f).attr( "class", d).html(a[c].label).append(requiredSpan).appendTo(g);
                f = jQuery( "<td/>" );

                d = (d = a[c].controlType) ? d : "text-control";

                switch (d) {

                case "column-control":

                    this.createColumnControl(a[c], f, c == 0);

                    break;

                case "tab-control":

                    this.createTabControl(a[c], f, c == 0);

                    break;

                case "awsm-icon-control":
                    this.createIconControl(a[c], f, c == 0);

                    break;

                case "icon-control":
                case "color-control":
                case "link-control":
                case "text-control":

                    this.createTextControl(a[c], f, c == 0);

                    break;

                case "textarea-control":

                    this.createTextAreaControl(a[c], f, c == 0);

                    break;

                case "select-control":

                    this.createSelectControl(a[c], f, c == 0);

                    break;

                case "font-control":

                    this.createFontControl(a[c], f, c == 0);

                    break;

                 case "range-control":

                    this.createRangeControl(a[c], f, c == 0);

                    break;

                 case "colourpicker-control":

                     this.createColourPickerControl(a[c], f, c == 0);

                     break;

                }

                jQuery( "<tr/>").append(g).append(f).appendTo(b)
            }
            jQuery( ".wpz-focus-here:first").focus()

            // Add additional wrappers, etc, to each select box.

            jQuery( '#wpz-options select').wrap( '<div class="select_wrapper"></div>' ).before( '<span></span>' );

            jQuery( '#wpz-options select option:selected').each( function () {

                jQuery(this).parents( '.select_wrapper').find( 'span').text( jQuery(this).text() );

            });

            // Setup the colourpicker.
            this.setUpColourPicker();

        } // End IF Statement
    },

    /* Column Generator Element */

    createColumnControl: function (a, b, c) {
        new wpzColumnMaker(b, 6, c ? "wpz-focus-here" : null);
        b.addClass( "wpz-marker-column-control")
    },

     /* Tab Generator Element */

    createTabControl: function (a, b, c) {
        new wpzTabMaker(b, 10, c ? "wpz-focus-here" : null);
        b.addClass( "wpz-marker-tab-control")
    },

    /* Colour Picker Element */

    createColourPickerControl: function (a, b, c) {

        var f = a.validateLink ? "wpz-validation-marker" : "",
            d = a.isRequired ? "wpz-required" : "",
            g = "wpz-value-" + a.id;

        b.attr( 'id', 'wpz-marker-colourpicker-control').addClass( "wpz-marker-colourpicker-control" );

        jQuery( '<div class="colorSelector"><div></div></div>').appendTo(b);

        jQuery( '<input type="text">').attr( "id", g).attr( "name", g).addClass(f).addClass(d).addClass( 'txt input-text input-colourpicker').addClass(c ? "wpz-focus-here" : "").appendTo(b);

        if (a = a.help) {
            jQuery( "<br/>").appendTo(b);
            jQuery( "<span/>").addClass( "wpz-input-help").html(a).appendTo(b)
        }

        var h = this;
        b.find( "#" + g).bind( "keydown focusout", function (e) {
            if (e.type == "keydown" && e.which != 13 && e.which != 9 && !e.shiftKey) h.needsPreview = true;
            else if (h.needsPreview && (e.type == "focusout" || e.which == 13)) {
                h.previewAction(e.target);
                h.needsPreview = false
            }
        })

    },

    /* Icon Chooser Element */
    createIconControl: function (a, b, c) {
        var g = "wpz-value-" + a.id;
        var icons = [
            'glass', 'music', 'search', 'envelope-o', 'heart', 'star', 'star-o', 'user', 'film', 'th-large', 'th',
            'th-list', 'check', 'times', 'search-plus', 'search-minus', 'power-off', 'signal', 'gear', 'cog', 'trash-o',
            'home', 'file-o', 'clock-o', 'road', 'download', 'arrow-circle-o-down', 'arrow-circle-o-up', 'inbox',
            'play-circle-o', 'rotate-right', 'repeat', 'refresh', 'list-alt', 'lock', 'flag', 'headphones',
            'volume-off', 'volume-down', 'volume-up', 'qrcode', 'barcode', 'tag', 'tags', 'book', 'bookmark', 'print',
            'camera', 'font', 'bold', 'italic', 'text-height', 'text-width', 'align-left', 'align-center',
            'align-right', 'align-justify', 'list', 'dedent', 'outdent', 'indent', 'video-camera', 'picture-o',
            'pencil', 'map-marker', 'adjust', 'tint', 'edit', 'pencil-square-o', 'share-square-o', 'check-square-o',
            'arrows', 'step-backward', 'fast-backward', 'backward', 'play', 'pause', 'stop', 'forward', 'fast-forward',
            'step-forward', 'eject', 'chevron-left', 'chevron-right', 'plus-circle', 'minus-circle', 'times-circle',
            'check-circle', 'question-circle', 'info-circle', 'crosshairs', 'times-circle-o', 'check-circle-o', 'ban',
            'arrow-left', 'arrow-right', 'arrow-up', 'arrow-down', 'mail-forward', 'share', 'expand', 'compress',
            'plus', 'minus', 'asterisk', 'exclamation-circle', 'gift', 'leaf', 'fire', 'eye', 'eye-slash', 'warning',
            'exclamation-triangle', 'plane', 'calendar', 'random', 'comment', 'magnet', 'chevron-up', 'chevron-down',
            'retweet', 'shopping-cart', 'folder', 'folder-open', 'arrows-v', 'arrows-h', 'bar-chart-o',
            'twitter-square', 'facebook-square', 'camera-retro', 'key', 'gears', 'cogs', 'comments', 'thumbs-o-up',
            'thumbs-o-down', 'star-half', 'heart-o', 'sign-out', 'linkedin-square', 'thumb-tack', 'external-link',
            'sign-in', 'trophy', 'github-square', 'upload', 'lemon-o', 'phone', 'square-o', 'bookmark-o',
            'phone-square', 'twitter', 'facebook', 'github', 'unlock', 'credit-card', 'rss', 'hdd-o', 'bullhorn',
            'bell', 'certificate', 'hand-o-right', 'hand-o-left', 'hand-o-up', 'hand-o-down', 'arrow-circle-left',
            'arrow-circle-right', 'arrow-circle-up', 'arrow-circle-down', 'globe', 'wrench', 'tasks', 'filter',
            'briefcase', 'arrows-alt', 'group', 'users', 'chain', 'link', 'cloud', 'flask', 'cut', 'scissors', 'copy',
            'files-o', 'paperclip', 'save', 'floppy-o', 'square', 'bars', 'list-ul', 'list-ol', 'strikethrough',
            'underline', 'table', 'magic', 'truck', 'pinterest', 'pinterest-square', 'google-plus-square',
            'google-plus', 'money', 'caret-down', 'caret-up', 'caret-left', 'caret-right', 'columns', 'unsorted',
            'sort', 'sort-down', 'sort-asc', 'sort-up', 'sort-desc', 'envelope', 'linkedin', 'rotate-left', 'undo',
            'legal', 'gavel', 'dashboard', 'tachometer', 'comment-o', 'comments-o', 'flash', 'bolt', 'sitemap',
            'umbrella', 'paste', 'clipboard', 'lightbulb-o', 'exchange', 'cloud-download', 'cloud-upload', 'user-md',
            'stethoscope', 'suitcase', 'bell-o', 'coffee', 'cutlery', 'file-text-o', 'building-o', 'hospital-o',
            'ambulance', 'medkit', 'fighter-jet', 'beer', 'h-square', 'plus-square', 'angle-double-left',
            'angle-double-right', 'angle-double-up', 'angle-double-down', 'angle-left', 'angle-right', 'angle-up',
            'angle-down', 'desktop', 'laptop', 'tablet', 'mobile-phone', 'mobile', 'circle-o', 'quote-left',
            'quote-right', 'spinner', 'circle', 'mail-reply', 'reply', 'github-alt', 'folder-o', 'folder-open-o',
            'smile-o', 'frown-o', 'meh-o', 'gamepad', 'keyboard-o', 'flag-o', 'flag-checkered', 'terminal', 'code',
            'reply-all', 'mail-reply-all', 'star-half-empty', 'star-half-full', 'star-half-o', 'location-arrow', 'crop',
            'code-fork', 'unlink', 'chain-broken', 'question', 'info', 'exclamation', 'superscript', 'subscript',
            'eraser', 'puzzle-piece', 'microphone', 'microphone-slash', 'shield', 'calendar-o', 'fire-extinguisher',
            'rocket', 'maxcdn', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up',
            'chevron-circle-down', 'html5', 'css3', 'anchor', 'unlock-alt', 'bullseye', 'ellipsis-h', 'ellipsis-v',
            'rss-square', 'play-circle', 'ticket', 'minus-square', 'minus-square-o', 'level-up', 'level-down',
            'check-square', 'pencil-square', 'external-link-square', 'share-square', 'compass', 'toggle-down',
            'caret-square-o-down', 'toggle-up', 'caret-square-o-up', 'toggle-right', 'caret-square-o-right', 'euro',
            'eur', 'gbp', 'dollar', 'usd', 'rupee', 'inr', 'cny', 'rmb', 'yen', 'jpy', 'ruble', 'rouble', 'rub', 'won',
            'krw', 'bitcoin', 'btc', 'file', 'file-text', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc',
            'sort-amount-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'thumbs-up', 'thumbs-down', 'youtube-square',
            'youtube', 'xing', 'xing-square', 'youtube-play', 'dropbox', 'stack-overflow', 'instagram', 'flickr', 'adn',
            'bitbucket', 'bitbucket-square', 'tumblr', 'tumblr-square', 'long-arrow-down', 'long-arrow-up',
            'long-arrow-left', 'long-arrow-right', 'apple', 'windows', 'android', 'linux', 'dribbble', 'skype',
            'foursquare', 'trello', 'female', 'male', 'gittip', 'sun-o', 'moon-o', 'archive', 'bug', 'vk', 'weibo',
            'renren', 'pagelines', 'stack-exchange', 'arrow-circle-o-right', 'arrow-circle-o-left', 'toggle-left',
            'caret-square-o-left', 'dot-circle-o', 'wheelchair', 'vimeo-square', 'turkish-lira', 'try', 'plus-square-o'
            ];
        var $container = jQuery('<div>').css({
            'overflow' : 'scroll',
            'height'   : '88px'
        }).appendTo(b);
        var $value_input = jQuery('<input type="hidden">').attr('id', g).attr('name', g).appendTo(b);
        var h = this;
        jQuery(icons).each(function() {
            var $icon_wrapper = jQuery('<a>').css({
                'color' : '#666',
                'padding' : '0',
                'margin'  : '0',
                'display' : 'inline-block',
                'line-height' : '22px',
                'height' : '22px',
                'width' : '22px',
                'text-decoration' : 'none'
            });

            $icon_wrapper.attr('href', '#');
            $icon_wrapper.on('click', function(event) {
                event.preventDefault();
                $value_input.val( jQuery(this).find('i').data('icon') );
                h.previewAction();
                $container.find('a').css('color', '#666');
                jQuery(this).css('color', '#222');
            });
            $icon_wrapper.appendTo($container);
            $icon_wrapper.append(
                jQuery('<i>')
                    .attr('class', 'fa fa-' + this)
                    .data('icon', this)
                    .css({
                        'font-size' : '14px'
                    })
            );
        });
    },

    /* Generic Text Element */

    createTextControl: function (a, b, c) {

        var f = a.validateLink ? "wpz-validation-marker" : "",
            d = a.isRequired ? "wpz-required" : "",
            g = "wpz-value-" + a.id;
             defaultValue = a.defaultValue ? a.defaultValue : "";

        jQuery( '<input type="text">').attr( "id", g).attr( "name", g).addClass(f).addClass(d).addClass( 'txt input-text').addClass(c ? "wpz-focus-here" : "").appendTo(b);

        if (a = a.help) {
            jQuery( "<br/>").appendTo(b);
            jQuery( "<span/>").addClass( "wpz-input-help").html(a).appendTo(b)
        }

        var h = this;
        b.find( "#" + g).bind( "keydown focusout", function (e) {
            if (e.type == "keydown" && e.which != 13 && e.which != 9 && !e.shiftKey) h.needsPreview = true;
            else if (h.needsPreview && (e.type == "focusout" || e.which == 13)) {
                h.previewAction(e.target);
                h.needsPreview = false
            }
        })

    },

    /* Generic TextArea Element */

    createTextAreaControl: function (a, b, c) {

        var f = a.validateLink ? "wpz-validation-marker" : "",
            d = a.isRequired ? "wpz-required" : "",
            g = "wpz-value-" + a.id;

        jQuery( '<textarea>').attr( "id", g).attr( "name", g).attr( "rows", 10).attr( "cols", 30).addClass(f).addClass(d).addClass( 'txt input-textarea').addClass(c ? "wpz-focus-here" : "").appendTo(b);
        b.addClass( "wpz-marker-textarea-control" );

        if (a = a.help) {
            jQuery( "<br/>").appendTo(b);
            jQuery( "<span/>").addClass( "wpz-input-help").html(a).appendTo(b)
        }

        var h = this;
        b.find( "#" + g).bind( "keydown focusout", function (e) {
            if (e.type == "keydown" && e.which != 13 && e.which != 9 && !e.shiftKey) h.needsPreview = true;
            else if (h.needsPreview && (e.type == "focusout" || e.which == 13)) {
                h.previewAction(e.target);
                h.needsPreview = false
            }
        })

    },

    /* Select Box Element */

    createSelectControl: function (a, b, c) {

        var f = a.validateLink ? "wpz-validation-marker" : "",
            d = a.isRequired ? "wpz-required" : "",
            g = "wpz-value-" + a.id;

        var selectNode = jQuery( '<select>').attr( "id", g).attr( "name", g).addClass(f).addClass(d).addClass( 'select input-select').addClass(c ? "wpz-focus-here" : "" );

        b.addClass( 'wpz-marker-select-control' );

        var selectBoxValues = a.selectValues;

        var labelValues = a.selectValues;

        for (v in selectBoxValues) {

            var value = selectBoxValues[v];
            var label = labelValues[v];
            var selected = '';

            if (value == '') {

                if (a.defaultValue == value) {

                    label = a.defaultText;

                } // End IF Statement
            } else {

                if (value == a.defaultValue) {
                    label = a.defaultText;
                } // End IF Statement
            } // End IF Statement
            if (value == a.defaultValue) {
                selected = ' selected="selected"';
            } // End IF Statement

            selectNode.append( '<option value="' + value + '"' + selected + '>' + label + '</option>' );

        } // End FOREACH Loop

        selectNode.appendTo(b);

        if (a = a.help) {
            jQuery( "<br/>").appendTo(b);
            jQuery( "<span/>").addClass( "wpz-input-help").html(a).appendTo(b)
        }

        var h = this;

        b.find( "#" + g).bind( "change", function (e) {

            if ((e.type == "change" || e.type == "focusout") || e.which == 9) {

                h.needsPreview = true;

            }

            if (h.needsPreview) {

                h.previewAction(e.target);

                h.needsPreview = false
            }

            // Update the text in the appropriate span tag.
            var newText = jQuery(this).children( 'option:selected').text();

            jQuery(this).parents( '.select_wrapper').find( 'span').text( newText );
        })

    },

    /* Range Select Box Element */

    createRangeControl: function (a, b, c) {

        var f = a.validateLink ? "wpz-validation-marker" : "",
            d = a.isRequired ? "wpz-required" : "",
            g = "wpz-value-" + a.id;

        var selectNode = jQuery( '<select>').attr( "id", g).attr( "name", g).addClass(f).addClass(d).addClass( 'select input-select input-select-range').addClass(c ? "wpz-focus-here" : "" );

        b.addClass( 'wpz-marker-select-control' );

        // var selectBoxValues = a.selectValues;

        var rangeStart = a.rangeValues[0];
        var rangeEnd = a.rangeValues[1];
        var defaultValue = 0;
        if ( a.defaultValue ) {

            defaultValue = a.defaultValue;

        } // End IF Statement

        for ( var i = rangeStart; i <= rangeEnd; i++ ) {

            var selected = '';

            if ( i == defaultValue ) { selected = ' selected="selected"'; } // End IF Statement

            selectNode.append( '<option value="' + i + '"' + selected + '>' + i + '</option>' );

        } // End FOR Loop

        selectNode.appendTo(b);

        if (a = a.help) {
            jQuery( "<br/>").appendTo(b);
            jQuery( "<span/>").addClass( "wpz-input-help").html(a).appendTo(b)
        }

        var h = this;

        b.find( "#" + g).bind( "change", function (e) {

            if ((e.type == "change" || e.type == "focusout") || e.which == 9) {

                h.needsPreview = true;

            }

            if (h.needsPreview) {

                h.previewAction(e.target);

                h.needsPreview = false
            }

            // Update the text in the appropriate span tag.
            var newText = jQuery(this).children( 'option:selected').text();

            jQuery(this).parents( '.select_wrapper').find( 'span').text( newText );
        })

    },

    /* Fonts Select Box Element */

    createFontControl: function (a, b, c) {

        var f = a.validateLink ? "wpz-validation-marker" : "",
            d = a.isRequired ? "wpz-required" : "",
            g = "wpz-value-" + a.id;

        var selectNode = jQuery( '<select>').attr( "id", g).attr( "name", g).addClass(f).addClass(d).addClass( 'select input-select input-select-font').addClass(c ? "wpz-focus-here" : "" );

        b.addClass( 'wpz-marker-select-control').addClass( 'wpz-marker-font-control' );

        var selectBoxValues = '';
        selectBoxValues = selectBoxValues.split( '|' );

        for (v in selectBoxValues) {

            var value = selectBoxValues[v];
            var label = selectBoxValues[v];
            var selected = '';

            if (value == '') {

                if (a.defaultValue == value) {

                    label = a.defaultText;

                } // End IF Statement
            } else {

                if (value == a.defaultValue) {
                    label = a.defaultText;
                } // End IF Statement
            } // End IF Statement
            if (value == a.defaultValue) {
                selected = ' selected="selected"';
            } // End IF Statement

            selectNode.append( '<option value=\'' + value + '\'' + selected + '>' + label + '</option>' );

        } // End FOREACH Loop

        selectNode.appendTo(b);

        if (a = a.help) {
            jQuery( "<br/>").appendTo(b);
            jQuery( "<span/>").addClass( "wpz-input-help").html(a).appendTo(b)
        }

        var h = this;

        b.find( "#" + g).bind( "change", function (e) {

            if ((e.type == "change" || e.type == "focusout") || e.which == 9) {

                h.needsPreview = true;

            }

            if (h.needsPreview) {

                h.previewAction(e.target);

                h.needsPreview = false
            }

            // Update the text in the appropriate span tag.
            var newText = jQuery(this).children( 'option:selected').text();

            jQuery(this).parents( '.select_wrapper').find( 'span').text( newText );
        })

    },

   getTextKeyValue: function (a) {
        var b = a.find( "input" );
        if (!b.length) return null;
        a = 'text-input-id';
        if ( b.attr( 'id' ) != undefined ) {
            a = b.attr( "id" ).substring(10);
        }
        b = b.val();
        return {
            key: a,
            value: b
        }
    },

    getTextAreaKeyValue: function (a) {
        var b = a.find( "textarea" );
        if (!b.length) return null;
        a = b.attr( "id").substring(10);
        b = b.val();
        b = b.replace(/\n\r?/g, '<br />');
        return {
            key: a,
            value: b
        }
    },

    getColumnKeyValue: function (a) {
        var b = a.find( "#wpz-column-text").text();
        if (a = Number(a.find( "select option:selected").val())) return {
            key: "data",
            value: {
                content: b,
                numColumns: a
            }
        }
    },

    getTabKeyValue: function (a) {
        var b = a.find( "#wpz-tab-text").text();
        if (a = Number(a.find( "select option:selected").val())) return {
            key: "data",
            value: {
                content: b,
                numTabs: a
            }
        }
    },

    makeShortcode: function () {

        var a = {},
            b = this;

        jQuery( "#wpz-options-table td").each(function () {

            var h = jQuery(this),
                e = null;

            if (e = h.hasClass( "wpz-marker-column-control") ? b.getColumnKeyValue(h) : b.getTextKeyValue(h)) a[e.key] = e.value
            if (e = h.hasClass( "wpz-marker-select-control") ? b.getSelectKeyValue(h) : b.getTextKeyValue(h)) a[e.key] = e.value
            if (e = h.hasClass( "wpz-marker-tab-control") ? b.getTabKeyValue(h) : b.getTextKeyValue(h)) a[e.key] = e.value
            if (e = h.hasClass( "wpz-marker-textarea-control") ? b.getTextAreaKeyValue(h) : b.getTextKeyValue(h)) a[e.key] = e.value

        });

        if (wpzShortcodeMeta.customMakeShortcode) return wpzShortcodeMeta.customMakeShortcode(a);
        var c = a.content ? HtmlSanitizer.SanitizeHtml( a.content ) : wpzShortcodeMeta.defaultContent,
            f = "";
        for (var d in a) {
            var g = a[d];
            if (g && d != "content") f += " " + d + '="' + g + '"'
        }

        // Customise the shortcode output for various shortcode types.

        switch ( wpzShortcodeMeta.shortcodeType ) {

            case 'text-replace':

                var shortcode = "[" + wpzShortcodeMeta.shortcode + f + "]" + (c ? c + "[/" + wpzShortcodeMeta.shortcode + "]" : " ")

            break;

            default:

                var shortcode = "[" + wpzShortcodeMeta.shortcode + f + "]" + (c ? c + "[/" + wpzShortcodeMeta.shortcode + "] " : " ")

            break;

        } // End SWITCH Statement

        return shortcode;
    },

    getSelectKeyValue: function (a) {
        var b = a.find( "select" );
        if (!b.length) return null;
        a = b.attr( "id").substring(10);
        b = b.val();
        return {
            key: a,
            value: b
        }
    },

    insertAction: function () {
        if (typeof wpzShortcodeMeta != "undefined") {
            var a = this.makeShortcode();
            tinyMCE.activeEditor.execCommand( "mceInsertContent", false, a);
            this.closeDialog()
        }
    },

    closeDialog: function () {
        this.needsPreview = false;
        tb_remove();
        jQuery( "#wpz-dialog").remove()
    },

    previewAction: function (a) {

        var fontValue = '';

        jQuery( '#wpz-options-table').find( 'select.input-select-font').each ( function () {

            fontValue = jQuery(this).val();

        });

        jQuery(a).hasClass( "wpz-validation-marker") && this.validateLinkFor(a);
        jQuery( "#wpz-preview h3:first").addClass( "wpz-loading" );
        jQuery( "#wpz-preview-iframe").attr( "src", ajaxurl + '?action=zoom_shortcodes_ajax_preview&shortcode=' + encodeURIComponent(this.makeShortcode()) + "&font=" + fontValue )
    },

    validateLinkFor: function (a) {
        var b = jQuery(a);
        b.removeClass( "wpz-validation-error" );
        b.removeClass( "wpz-validated" );
        if (a = b.val()) {
            b.addClass( "wpz-validating" );
            jQuery.ajax({
                url: ajaxurl,
                dataType: "json",
                data: {
                    action: "wpz_check_url_action",
                    url: a
                },
                error: function () {
                    b.removeClass( "wpz-validating")
                },
                success: function (c) {
                    b.removeClass( "wpz-validating" );
                    c.error || b.addClass(c.exists ? "wpz-validated" : "wpz-validation-error")
                }
            })
        }
    }

};

wpzDialogHelper.setUpButtons();
wpzDialogHelper.loadShortcodeDetails();
