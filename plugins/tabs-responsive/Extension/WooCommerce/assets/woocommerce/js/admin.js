jQuery.noConflict();
(function ($) {
    $(document).ready(function () {
        function NEWRegExp(par = '') {
            return new RegExp(par, "g");
        }

        $(document.body).on("click", ".responsive-woo-header", function (e) {
            e.preventDefault();
            $(this).parent().toggleClass("responsive-hidden");
        });
        $(document.body).on("click", ".responsive-woo-header .responsive-delete-button", function (e) {
            e.preventDefault();
            $(this).parents(".woo-responsive-tabs-admin-tabs").remove();
        });
        $(document.body).on("keyup", ".responsive_tabs_woo_layouts_title_field", function (e) {
            $input = $(this);
            $input.parents('.woo-responsive-content').siblings(".responsive-woo-header").children('.responsive-woo-header-text').html($input.val());
        });
        $(".woo-responsive-tabs-admin-body").sortable({
            axis: 'y',
            handle: ".responsive-woo-header-text"
        });

        $(document.body).on("click", ".responsive-woo-tabs-add-rows-button", function (e) {
            e.preventDefault();
            var newid = check_content_id($(".woo-responsive-tabs-admin-body").children().length);
            $('.responsive-woo-tabs-add-rows-store .woo-responsive-tabs-admin-tabs').clone().appendTo(".woo-responsive-tabs-admin-body");
            $Current = $(".woo-responsive-tabs-admin-body .woo-responsive-tabs-admin-tabs:last-child");

            $Current.find('input').each(function () {

                $This = jQuery(this);
                if ($This.is('input[name="_responsive_tabs_woo_store_tab_title_[]"]')) {
                    var clss = 'responsive_tabs_woo_layouts_title_field',
                            name = '_responsive_tabs_woo_layouts_tab_title_[]',
                            id = '_responsive_tabs_woo_layouts_tab_title_[]';
                    $This.attr('name', name).attr('id', id).removeClass('responsive_tabs_woo_store_title_field').addClass(clss)
                            .parents('p').removeClass('_responsive_tabs_woo_layouts_tab_title_[]_field').addClass(id + '_field')
                            .find('label').removeAttr('for').attr('for', id + '_field');
                }
                if ($This.is('input[name="_responsive_tabs_woo_store_tab_priority_[]"]')) {
                    var clss = 'responsive_tabs_woo_layouts_priority_field',
                            name = '_responsive_tabs_woo_layouts_tab_priority_[]',
                            id = '_responsive_tabs_woo_layouts_tab_priority_[]';
                    $This.attr('name', name).attr('id', id).removeClass('responsive_tabs_woo_store_priority_field').addClass(clss)
                            .parents('p').removeClass('_responsive_tabs_woo_store_tab_priority_[]_field').addClass(id + '_field')
                            .find('label').removeAttr('for').attr('for', id + '_field');
                }
                if ($This.is('input[name="_responsive_tabs_woo_store_tab_callback_[]"]')) {
                    var clss = 'responsive_tabs_woo_layouts_callback_field',
                            name = '_responsive_tabs_woo_layouts_tab_callback_[]',
                            id = '_responsive_tabs_woo_layouts_tab_callback_[]';
                    $This.attr('name', name).attr('id', id).removeClass('responsive_tabs_store_layouts_callback_field').addClass(clss)
                            .parents('p').removeClass('_responsive_tabs_woo_store_tab_callback_[]_field').addClass(id + '_field')
                            .find('label').removeAttr('for').attr('for', id + '_field');
                }

            });
            $Current.find('textarea').each(function () {
                $This = jQuery(this);
                if (jQuery(this).is('textarea[name="_responsive_tabs_woo_store_tab_content_"]')) {
                    var name = '_responsive_tabs_woo_layouts_tab_content_[]',
                            id = '_responsive_tabs_woo_layouts_tab_content_' + newid;
                    jQuery(this).attr('name', name).attr('id', id).removeClass('_responsive_tabs_woo_store_tab_content_')
                            .parents('p').addClass('_responsive_tabs_woo_layouts_tab_content_field').removeClass('_responsive_tabs_woo_store_tab_content_field');
                }
            });
            textarea_id = $Current.find('textarea:last-child').attr('id');
            tab_content = $Current.find('textarea:last-child').val();
            get_wp_editor(textarea_id, true, tab_content, newid);
        });
        function check_content_id($count) {
            for ($i = $count; $i < 1000; $i++) {
                var id = '_responsive_tabs_woo_layouts_tab_content_' + $i;
                if ($("#" + id).length === 0) {
                    return $i;
                }
            }

        }
        function get_wp_editor(textarea_id, product_page, tab_content, newid) {

            if (!wp || !wp.editor || !wp.editor.initialize) {
                return false;
            }
            var settings = {
                tinymce: {
                    branding: false,
                    theme: 'modern',
                    skin: 'lightgray',
                    language: 'en',
                    formats: {
                        alignleft: [
                            {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'left'}},
                            {selector: 'img,table,dl.wp-caption', classes: 'alignleft'}
                        ],
                        aligncenter: [
                            {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'center'}},
                            {selector: 'img,table,dl.wp-caption', classes: 'aligncenter'}
                        ],
                        alignright: [
                            {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'right'}},
                            {selector: 'img,table,dl.wp-caption', classes: 'alignright'}
                        ],
                        strikethrough: {inline: 'del'}
                    },
                    relative_urls: false,
                    remove_script_host: false,
                    convert_urls: false,
                    browser_spellcheck: true,
                    fix_list_elements: true,
                    entities: '38,amp,60,lt,62,gt',
                    entity_encoding: 'raw',
                    keep_styles: false,
                    paste_webkit_styles: 'font-weight font-style color',
                    preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
                    end_container_on_empty_block: true,
                    wpeditimage_disable_captions: false,
                    wpeditimage_html5_captions: true,
                    plugins: 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview',
                    menubar: false,
                    wpautop: true,
                    indent: false,
                    resize: true,
                    theme_advanced_resizing: true,
                    theme_advanced_resize_horizontal: false,
                    statusbar: true,
                    toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_adv',
                    toolbar2: 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                    toolbar3: '',
                    toolbar4: '',
                    tabfocus_elements: ':prev,:next',
                    setup: function (editor) {
                        editor.on('init', function () {
                            this.getBody().style.fontFamily = 'Georgia, "Times New Roman", "Bitstream Charter", Times, serif';
                            this.getBody().style.fontSize = '16px';
                            this.getBody().style.color = '#333';
                            if (tab_content.length > 0) {
                                this.setContent(tab_content);
                            }
                        });
                    },
                },
                quicktags: {
                    buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
                }
            }

            wp.editor.initialize(textarea_id, settings);



            var add_media_button = '<div id="wp-' + textarea_id + '-media-buttons" class="wp-media-buttons"> <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="' + textarea_id + '"><span class="wp-media-buttons-icon"></span> Add Media</button></div>';
            jQuery('#wp-_responsive_tabs_woo_layouts_tab_content_'+newid+'-wrap .wp-editor-tools').prepend(add_media_button);
            return true;
        }

    });
})(jQuery)