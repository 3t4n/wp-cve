jQuery(document).ready(function() {
    'use strict';

    // script for the toggle sidebar
    var span_full = jQuery('.toggleSidebar .dashicons');
    var show_sidebar = localStorage.getItem('mmqw-sidebar-display');
    if( ( null !== show_sidebar || undefined !== show_sidebar ) && ( 'hide' === show_sidebar ) ) {
        jQuery('.all-pad').addClass('hide-sidebar');
        span_full.removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
    } else {
        jQuery('.all-pad').removeClass('hide-sidebar');
        span_full.removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
    }
    jQuery(document).on( 'click', '.toggleSidebar', function(){
        jQuery('.all-pad').toggleClass('hide-sidebar');
        if( jQuery('.all-pad').hasClass('hide-sidebar') ){
            localStorage.setItem('mmqw-sidebar-display', 'hide');
            span_full.removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
            jQuery('.all-pad .mmqw-section-right').css({'-webkit-transition': '.3s ease-in width', '-o-transition': '.3s ease-in width',  'transition': '.3s ease-in width'});
            jQuery('.all-pad .mmqw-section-left').css({'-webkit-transition': '.3s ease-in width', '-o-transition': '.3s ease-in width',  'transition': '.3s ease-in width'});
            setTimeout(function() {
                jQuery('#dotsstoremain .dotstore_plugin_sidebar').css('display', 'none');
            }, 300);
        } else {
            localStorage.setItem('mmqw-sidebar-display', 'show');
            span_full.removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
            jQuery('.all-pad .mmqw-section-right').css({'-webkit-transition': '.3s ease-out width', '-o-transition': '.3s ease-out width',  'transition': '.3s ease-out width'});
            jQuery('.all-pad .mmqw-section-left').css({'-webkit-transition': '.3s ease-out width', '-o-transition': '.3s ease-out width',  'transition': '.3s ease-out width'});
            jQuery('#dotsstoremain .dotstore_plugin_sidebar').css('display', 'block');
        }
    });
        
    jQuery('#no_post_add').keyup(function() {
        var value = jQuery(this).val();
        value = value.replace(/^(0*)/, '');
        jQuery(this).val(value);
    });
    // End Subscribe Functionality
    jQuery(document).ready(function() {
        jQuery('#type').change(function() {
            var type = jQuery('#type').val();
            if (type === 'page') {
                jQuery('.parent_page_id_tr').show();
                jQuery('.template_name_tr').show();
            } else if(type === 'e-landing-page') {
                jQuery('.template_name_tr').show();
                jQuery('.parent_page_id_tr').hide();
            } else {
                jQuery('.parent_page_id_tr').hide();
                jQuery('.template_name_tr').hide();
            }

        });

        // script for plugin rating
        jQuery(document).on('click', '.dotstore-sidebar-section .content_box .et-star-rating label', function(e){
            e.stopImmediatePropagation();
            var rurl = jQuery('#et-review-url').val();
            window.open( rurl, '_blank' );
        });
    });

    // add currunt menu class in main manu
    jQuery(window).load(function () {
        jQuery('a[href="admin.php?page=mass-pages-posts-creator"]').parent().addClass('current');
        jQuery('a[href="admin.php?page=mass-pages-posts-creator"]').addClass('current');
    });

    function pages_content_getContent(editor_id, textarea_id) {
        if (typeof editor_id === 'undefined') {
            editor_id = wpActiveEditor;
        }
        if (typeof textarea_id === 'undefined') {
            textarea_id = editor_id;
        }
        if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
            return tinyMCE.get(editor_id).getContent();
        } else {
            return jQuery('#' + textarea_id).val();
        }
    }

    jQuery('#btn_submit').click(function() {
        var prefix_word = jQuery('#page_prefix').val();
        var pages_list = jQuery('#pages_list').val();
        var pages_content = pages_content_getContent('pages_content');
        var parent_page_id = jQuery('#page-filter').val();
        var template_name = jQuery('#template_name').val();
        var type = jQuery('#type').val();
        var postfix_word = jQuery('#page_postfix').val();
        var comment_status = jQuery('#comment_status').val();

        var page_status = jQuery('#page_status').val();
        var authors = jQuery('#authors').val();
        var excerpt_content = jQuery('#excerpt_content').val();
        var no_post_add = jQuery('#no_post_add').val();
        var mass_pages_posts_creator = jQuery('#mass_pages_posts_creator').val();

        if (pages_list.length === 0 || pages_list === '') {
            alert('Please enter list of Pages..');
            event.preventDefault();
            return false;
        }

        if (type === 'none') {
            alert('Please select the type..');
            event.preventDefault();
            return false;
        }
        jQuery.ajax({
            type: 'POST',
            data: {
                action: 'mpc_ajax_action',
                prefix_word: prefix_word,
                postfix_word: postfix_word,
                pages_list: pages_list,
                pages_content: pages_content,
                parent_page_id: parent_page_id,
                template_name: template_name,
                type: type,
                page_status: page_status,
                authors: authors,
                excerpt_content: excerpt_content,
                no_post_add: no_post_add,
                comment_status: comment_status,
                security: mass_pages_posts_creator
            },
            url: adminajax.ajaxurl,
            dataType: 'json',
            success: function(response) {
                if (response) {
                    jQuery('#createForm').css('display', 'none');
                    jQuery('#message').addClass('view');
                    jQuery('html,body').animate({scrollTop: 0}, 'slow');
                    jQuery('#message').html('Pages/Posts Succesfully Created.. ');
                    responseTable(jQuery('#result').get(0),response); 
                } else {
                    jQuery('#message').addClass('view');
                    jQuery('#message').html('Something goes wrong..');
                }
            }
        });

    });

    jQuery( '#page-filter' ).select2({
        ajax: {
            url: adminajax.ajaxurl,
            dataType: 'json',
            delay: 250,
            data: function( params ) {
                return {
                    value: params.term,
                    action: 'page_finder_ajax',
                    security: jQuery('#mass_pages_posts_creator').val(),
                };
            },
            processResults: function( data ) {
                var options = [];
                if ( data ) {
                    jQuery.each( data, function( index, text ) {
                        options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
                    });

                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });
});


function createtag(element,tag,attributes){
    var createElement=document.createElement(tag);
    setAllAttributes(createElement,attributes);
    element.appendChild(createElement);
    return document.getElementById(attributes.id);    
}

function responseTable(element,response){
    var table=createtag(element,'table',{'id': 'datatable'});
    var thead=createtag(table,'thead',{'id': 'datahead'});
    var headtitles=['Page/Post Id','Page/Post Name','Page/Post Status', 'URL'];
    createCustomRow(thead,'th',headtitles,{'id':'datath'});
    var tbody=createtag(table,'tbody',{'id': 'databody'});
    for(var i=0; i<response.length;i++){
        data=Object.values(response[i]);
        createCustomRow(tbody,'td',data,{'id' : 'datatd-'+i});
    }
}
function createCustomRow(element,celltype,data,attributes){
    var tr=createtag(element,'tr',attributes);
    for(var i=0;i<data.length;i++){
        var cell=createtag(tr,celltype,{'id': attributes.id+'-'+celltype+'-'+i});
        var text = document.createTextNode(data[i]);
        cell.appendChild(text);
        tr.appendChild(cell);
    }
}
function setAllAttributes(element,attributes){
    Object.keys(attributes).forEach(function (key) {
        element.setAttribute(key, attributes[key]);
        // use val
    });
    return element;
}
function allowSpeicalCharacter(str){
    return str.replace('&#8211;','–').replace('&gt;','>').replace('&lt;','<').replace('&#197;','Å');    
}
