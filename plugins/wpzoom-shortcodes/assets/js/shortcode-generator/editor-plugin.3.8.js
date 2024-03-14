/**
 * Hook shortcodes to TineMCE
 *
 * @return void
 */
(function() {
    var icon_url = zoomFramework.assetsUri + '/images/shortcodes/icon.png';

    tinymce.create(
        "tinymce.plugins.wpzoomShortcodes",
        {
            init: function(d, e) {
                d.addCommand("wpzOpenDialog", function(a, c) {
                    // Grab the selected text from the content editor.
                    selectedText = '';

                    if (d.selection.getContent().length > 0) {
                        selectedText = d.selection.getContent();
                    }

                    wpzSelectedShortcodeType = c.identifier;
                    wpzSelectedShortcodeTitle = c.title;

                    jQuery.get(ajaxurl + '?action=zoom_shortcodes_ajax_dialog', function(b) {
                        jQuery( '#wpz-options').addClass('shortcode-' + wpzSelectedShortcodeType);
                        jQuery( '#wpz-preview').addClass('shortcode-' + wpzSelectedShortcodeType);

                        // Skip the popup on certain shortcodes.
                        var a;
                        switch (wpzSelectedShortcodeType) {
                            // Highlight
                            case 'highlight':
                                a = '[highlight]' + selectedText + '[/highlight]';
                                tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
                                break;

                            // Dropcap
                            case 'dropcap':
                                a = '[dropcap]' + selectedText + '[/dropcap]';
                                tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
                                break;

                            default:
                                var width  = jQuery(window).width()
                                  , height = jQuery(window).height() - 84;

                                width  = ((720 < width) ? 720 : width) - 80;

                                jQuery("#wpz-dialog").remove();
                                jQuery("body").append(b);
                                jQuery("#wpz-dialog").hide();

                                tb_show("Insert " + wpzSelectedShortcodeTitle + " Shortcode", "#TB_inline?width=" + width + "&height=" + height + "&inlineId=wpz-dialog");
                                jQuery("#wpz-options h3:first").text("Customize the " + c.title + " Shortcode");
                                break;
                        }

                    });

                });

                // d.onNodeChange.add(function(a,c){ c.setDisabled( "wpzoom_shortcodes_button",a.selection.getContent().length>0 ) } ) // Disables the button if text is highlighted in the editor.
            },

            createControl:function(d, e) {
                if (d == "wpzoom_shortcodes_button") {
                    d = e.createMenuButton("wpzoom_shortcodes_button", {
                        'title' : "Insert Shortcode",
                        'image' : icon_url,
                        'icons' : false
                    });

                    var that = this;
                    d.onRenderMenu.add(function(c, b) {
                        that.addWithDialog(b, "Button", "button");
                        that.addWithDialog(b, "Icon Link", "ilink");
                        b.addSeparator();

                        that.addWithDialog(b, "Info Box", "box");
                        b.addSeparator();

                        that.addWithDialog(b, "Column Layout", "column");
                        that.addWithDialog(b, "Tabbed Layout", "tab");
                        b.addSeparator();

                        c = b.addMenu({ 'title' : "List Generator" });
                        that.addWithDialog(c, "Unordered List", "unordered_list");
                        that.addWithDialog(c, "Ordered List", "ordered_list");

                    });

                    return d;
                }

                return null;
            },

            addImmediate: function(d, e, a) {
                d.add({
                    'title'   : e,
                    'onclick' : function() {
                        tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
                    }
                });
            },

            addWithDialog: function(d, e, a) {
                d.add({
                    'title'   : e,
                    'onclick' : function() {
                        tinyMCE.activeEditor.execCommand("wpzOpenDialog", false, { 'title' : e, 'identifier' : a});
                    }
                });
            },

            getInfo: function() {
                return {
                    'longname'  : "WPZOOM Shortcode Generator",
                    'author'    : "WPZOOM",
                    'authorurl' : "https://wpzoom.com/",
                    'infourl'   : "https://www.wpzoom.com/framework-tour/",
                    'version'   : "1.0"
                };
            }
        }
    );

    tinymce.PluginManager.add("wpzoomShortcodes", tinymce.plugins.wpzoomShortcodes);
})();
