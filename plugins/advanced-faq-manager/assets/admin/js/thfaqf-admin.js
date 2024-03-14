var thfaqf_settings = (function ($, window, document){
    'use strict';

    $(function (){
        var wrapper = $("#thfaqf_faq_form");
        var new_faq_from = $("#thfaqf_new_faq_form").html();

        $("body.post-type-faq #publish").click(function(event){
            var valid = validate_faq_post();
            return valid;
        });

        setup_sortable_meta_box();
        init_general_settings();
        init_faq_settings();
        display_expnd_icon();
        initialize_code_mrr();

    });

    function display_expnd_icon(){
        var icon_code = $("input[name='icon_picker']:checked").data( "th_icon");
        var display_btn = $('.thfaq-toggle-expndicon');
        var icon_panel = $('.thfaqf-icon-wrapper');

        $('.thfaq-input-hidden-field').change(function(){
            var icon_code = $(this).data( "th_icon");
            display_btn.find('.thfaq-icon-panal').addClass(icon_code);
        });
       
        display_btn.click(function(e){
            e.preventDefault();
            icon_panel.toggleClass('thfaq-hide-expndicon');
        });

        $(document).mouseup(function(e){
           if(!$(event.target).parents().addBack().is(display_btn)){
               icon_panel.addClass('thfaq-hide-expndicon');
           }
        });

        icon_panel.mouseup(function(e){
            event.stopPropagation();
        });

        $('input[name="enable_disable_title_icons"]').click(function(){
            if($(this).is(":checked")){
                $('.thfaq-icon-poss').removeClass('thfaq-hide-expndicon'); 
            }else{
                $('.thfaq-icon-poss').addClass('thfaq-hide-expndicon'); 
            }
        });
    }
     
    function setup_sortable_meta_box(){
        $(".meta-box-sortables")
        .sortable('option', 'cancel', '#thfaq_faq_list .hndle, :input, button')
        .sortable('refresh'); 
    }

    function sortable_faq(){
        $( "#thfaqf_faq_form" ).sortable();
        $( "#thfaqf_faq_form" ).disableSelection();
    }

    function wp_editor_load_initialy(){
       wp_tinymce_editor('thfaq_editor_tinymce_1');
    }

    function wp_tinymce_editor(uid){
        window.wp.editor.initialize(
            uid,
            {
                tinymce:{
                    wpautop: true,
                    menubar: true,
                    height: 300,
                    mediaButtons: false,
                    quicktags: true,
                    plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview lists link textcolor',
                    toolbar1: 'formatselect | bold italic | underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
                    toolbar2: 'alignjustify forecolor backcolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help',
                    templates: [
                        { title: 'Test template 1', content: 'Test 1' },
                        { title: 'Test template 2', content: 'Test 2' }
                    ],
                    mobile: { 
                        theme: 'mobile' 
                    },
                },
            }
        );
    }

    function wp_editor_settap(faq_item,uid){
        faq_item.hasClass("thfaqf-active") ? wp_tinymce_editor(uid) : tinymce.remove('#'+uid);
    }

    function init_general_settings(){
        var wrapper = $('#general_settings_form');

        setup_enhanced_multi_select(wrapper);
        setup_color_picker(wrapper);

        var enable_share_btn = wrapper.find('input[name="enable_share_button"]');
        enable_disble_social_share(enable_share_btn);
    }

    function initialize_code_mrr(){
        if(thfaqf_var.current_screen == 'faq_page_thfaq-settings'){
            wp.codeEditor.initialize($('#thfaq_custom_css'), thfaqf_var.cm_settings); 
        } 
    }

    function init_faq_settings(){
        var wrapper = $('#thfaqf_faq_form');
        var wrapper_settings = $('#thfaqf-override-settings-panel');
        setup_color_picker(wrapper_settings);
        enable_disable_override_global_settings('#override_global_settings');
        setup_realtime_title_display(); 
        sortable_faq();
        wp_editor_load_initialy();     
    }

    function edit_faq_item(elm){
        var faq_item = $(elm).closest('.thfaqf-single-form-wrapper'),
            other_faq_items = faq_item.siblings('.thfaqf-single-form-wrapper'),
            textarea = faq_item.find('.faq-input-content').attr("id");
        other_faq_items.removeClass("thfaqf-active");
        faq_item.toggleClass("thfaqf-active");
        wp_editor_settap(faq_item,textarea);
    }

    function clone_faq(elm){
        tinymce.remove();
        var wrapper = $("#thfaqf_faq_form"),
            faq_wrapper = $('.thfaqf-single-form-wrapper'),
            faq_item = $(elm).closest(faq_wrapper),
            textarea = faq_item.find('.faq-input-content'),
            random_editor_id = Math.floor(Math.random()*(10000-10+1)+10);

        wrapper.find('.thfaqf-single-form-wrapper').removeClass("thfaqf-active"); 
        var clone_data = faq_item.clone();

        clone_data.find('.thfaq-item-content').prop('id','thfaq_editor_tinymce_'+random_editor_id);
        clone_data.find('.random-editor-id').val(random_editor_id);
        clone_data.insertAfter(faq_item).find('.thfaqf-single-form-header').css('background-color','#cbc8c4');      
    }

    function delete_faq_item(elm){
        if(confirm('Are you sure you want to detete this FAQ?') == true){
            $(elm).closest('.thfaqf-single-form-wrapper').remove(); 
        }
    }

    function add_faq_item(elm){
        tinymce.remove();
        var wrapper = $("#thfaqf_faq_form"),
            faq_items = wrapper.find('.thfaqf-single-form-wrapper'),
            faq_item = $(elm).closest('#thfaq_faq_list').find('.thfaqf-single-form-wrapper'),
            random_editor_id = Math.floor(Math.random()*(10000-10+1)+10);

        faq_items.removeClass("thfaqf-active");
        var new_faq_from = $('#thfaqf_new_faq_form').html(),
            clone_data = $(new_faq_from).clone(),
            textarea = 'thfaq_editor_tinymce_'+random_editor_id;

        clone_data.find('.faq-input-content').prop('id',textarea);
        clone_data.find('.random-editor-id').val(random_editor_id);
        wrapper.append(clone_data); 
        wp_editor_settap(faq_item,textarea);
    }

    function insert_media(elm, event){
        var uid = $(elm).closest('.thfaqf-single-form-wrapper').find('.faq-input-content').prop('id');
        event.preventDefault();
        tinymce.remove();
        var container = $(elm).siblings('.faq-input-content'),
            attachments = [],
            img_html = '',
            frame = wp.media({
                title: 'Add Media',
                multiple: true, 
            });
        frame.on('select', function(){
            attachments = frame.state().get('selection').map(function( attachment ){
                attachment.toJSON();
                return attachment;
            });

            if(!$.isEmptyObject(attachments)) {
                $.each(attachments, function(key,value) {
                    var file_name = value.attributes.filename,
                        images = ['jpg','jpeg','png','gif', 'ico'],
                        extension = file_name.substr( (file_name.lastIndexOf('.') +1) );
                        if($.inArray(extension, images) != -1){
                            img_html += '<img src="'+ value.attributes.url +'">';
                        }else {
                            img_html += '<video width="320" height="240" controls><source src="'+value.attributes.url+'" ></video>';
                        }
                });
            }
            var content = container.val();
            content = content ? content+img_html : img_html;
            container.val(content);
            wp_tinymce_editor(uid);
        });

        frame.open();

        frame.on('close', function(){ 
            setTimeout(function() { wp_tinymce_editor(uid);},1); 
        });   
    }

    function setup_realtime_title_display(wrapper){
        $(document).on('keyup','.faq-input-title',function(evnt){
            var value = this.value;
            $(this).closest('.thfaqf-single-form-wrapper').find('.faq-title').text(value); 
        });
    }

    function setup_enhanced_multi_select(wrapper){
        wrapper.find('select.thpladmin-enhanced-multi-select').select2();
    }

    function setup_color_pick_preview(wrapper){
        wrapper.find('.thpladmin-colorpicker').each(function(){
            $(this).parent().find('.thpladmin-colorpicker-preview').css({ backgroundColor: this.value });
        });
    }

    function setup_color_picker(wrapper){
        wrapper.find('.thpladmin-colorpicker').iris({
            change: function( event, ui ) {
                $( this ).parent().find( '.thpladmin-colorpicker-preview' ).css({ backgroundColor: ui.color.toString() });
            },
            hide: true,
            border: true,
        }).click( function() {
            $('.iris-picker').hide();
            $(this ).closest('td').find('.iris-picker').show();
        });
    
        $('body').click( function(){
            $('.iris-picker').hide();
        });
    
        $('.thpladmin-colorpicker').click( function( event ) {
            event.stopPropagation();
        });
    }

    function enable_disble_social_share(elm){
        var wrapper = $('#general_settings_form');

        if($(elm).is(":checked")){
            wrapper.find('.social-share-field').removeClass('thfaq-disable-felement');
            wrapper.find('.thfaq-share-wrapper').removeClass('thfaq-disable-felement');
        }else{
            wrapper.find('.social-share-field').addClass('thfaq-disable-felement');
            wrapper.find('.thfaq-share-wrapper').addClass('thfaq-disable-felement');
        }
    }

    function enable_disable_override_global_settings(elm){
        var wrapper = $('#thfaqf-override-settings-panel');
        wrapper.removeClass('thfaqf-disabled-panel');

        if($(elm).is(":checked")){
            wrapper.find('.thfaqf-override-field').prop("readonly", false);
        }else{
            wrapper.find('.thfaqf-override-field').prop("readonly", true);
            wrapper.addClass('thfaqf-disabled-panel');
        }
    }

    function copy_text(elm){
        var copy_text  = $(elm).closest('.shortcode-copy-field').find('.copy_to_clipboard');
        copy_text.select();
        document.execCommand("copy");
    }

    function validate_faq_post(){
        var valid = true;
        var faq_titles = $("#post #thfaqf_faq_form .faq-input-title");

        if(!faq_titles.length){
            alert('FAQ Post cannot be saved without at least one FAQ thread.');
            return false;
        }

        faq_titles.each(function(){
            if(!this.value){
                valid = false;
                return false;
            }
        });

        if(!valid){
            alert('FAQ cannot be saved without Title. Please enter a title for FAQ');
        }
        return valid;
    }

    function additional_css_sections(elm) {
       if($(elm).is(":checked")){
            $('.thfaqf-additonal-css-wrapper').removeClass('thfaqf-disabled-panel'); 
        }else{
            $('.thfaqf-additonal-css-wrapper').addClass('thfaqf-disabled-panel'); 
        }
    }

    return{
        addFaqItem : add_faq_item,
        editFaqItem : edit_faq_item,
        deleteFaqItem : delete_faq_item,
        insertMedia : insert_media,
        copyText : copy_text,
        CloneFaq: clone_faq,
        enableDisableOverrideSettings : enable_disable_override_global_settings,
        enableDisbleSocialShare : enable_disble_social_share,
        EnableDisableCSS : additional_css_sections,
    }
}(window.jQuery, window, document));

function thfaqfAddFaqItem(elm){
    thfaqf_settings.addFaqItem(elm);
}

function thfaqfEditFaqItem(elm){
    thfaqf_settings.editFaqItem(elm);
}

function thfaqClone(elm){
    thfaqf_settings.CloneFaq(elm);
}

function thfaqfDeleteFaqItem(elm){
    thfaqf_settings.deleteFaqItem(elm);
}

function thfaqfInsertMedia(elm, event){
    thfaqf_settings.insertMedia(elm, event);
}

function thfaqfCopyText(elm){
    thfaqf_settings.copyText(elm);
}

function thfaqfEnableDisableOverrideSettings(elm){
    thfaqf_settings.enableDisableOverrideSettings(elm);
}

function thfaqfEnableDisableSocialShare(elm){
    thfaqf_settings.enableDisbleSocialShare(elm);
}

function thfaqfEnableDisableCustonCSS(elm){
    thfaqf_settings.EnableDisableCSS(elm);
}




 


