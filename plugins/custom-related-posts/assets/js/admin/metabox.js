import '../../css/admin/metabox.scss';
import '../../css/admin/spinner.scss';

jQuery(document).ready(function($) {
    if($('#crp_meta_box').length > 0) {
        Metabox.initMetaBox();
    }
});

let Metabox = {
    post_id: undefined,
    search_term_field: undefined,
    search_button: undefined,
    initMetaBox: function() {
        Metabox.post_id = jQuery('#crp_post').val();
        Metabox.search_post_type = jQuery('#crp_search_post_type');
        Metabox.search_term_field = jQuery('#crp_search_term');
        Metabox.search_button = jQuery('#crp_search_button');
    
        // Trigger search for any if these actions.
        Metabox.search_term_field.on('keydown', function(e) {
            if(e.keyCode == 13) {
                e.preventDefault();
                e.stopPropagation();
                Metabox.searchPosts();
            }
        });
        Metabox.search_post_type.on('change', function(e) {
            var search_term = Metabox.search_term_field.val();
    
            if(search_term.length > 0) {
                Metabox.searchPosts();
            }
        });
        Metabox.search_button.on('click', function() { Metabox.searchPosts() } );
    
        jQuery('#crp_relations').on('click', '.crp_remove_relation', function() {
            Metabox.removeRelation(jQuery(this));
        });
    
        jQuery('#crp_search_results_table').on('click', 'button', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    },
    removeRelation: function(btn) {
        var both = btn.hasClass('crp_remove_relation_both'),
            to = btn.hasClass('crp_remove_relation_to'),
            from = btn.hasClass('crp_remove_relation_from'),
            post_id = btn.parent('div').data('post');
    
        if(post_id == undefined) {
            post_id = btn.parents('tr').data('post');
    
            if(to) {
                var title = btn.parents('tr').find('td').first().text();
                var element = '<div id="crp_related_post_' + post_id +'" data-post="' + post_id + '">' + crp_admin.remove_image_from + title + '</div>';
                jQuery('#crp_relations_single_from').prepend(element);
            } else if(from) {
                var title = btn.parents('tr').find('td').first().text();
                var element = '<div id="crp_related_post_' + post_id +'" data-post="' + post_id + '">' + title + crp_admin.remove_image_to + '</div>';
                jQuery('#crp_relations_single_to').prepend(element);
            }
        }
        jQuery('#crp_related_post_' + post_id).remove();
    
        var data = {
            action: 'crp_remove_relation',
            security: crp_admin.nonce,
            base: Metabox.post_id,
            target: post_id,
            from: both || from,
            to: both || to
        };
    
        jQuery.post(crp_admin.ajax_url, data);
    },
    searchPosts: function() {
        jQuery('.crp_search_results').slideUp(250);
        Metabox.search_button.addClass('crp_spinner');
    
        var search_term = Metabox.search_term_field.val();
    
        if(search_term.length == 0) {
            Metabox.search_button.removeClass('crp_spinner');
            jQuery('#crp_search_results_input').slideDown(500);
            Metabox.search_term_field.focus();
        } else {
            var data = {
                action: 'crp_search_posts',
                security: crp_admin.nonce,
                post_type: Metabox.search_post_type.val(),
                term: search_term,
                base: Metabox.post_id
            };
    
            jQuery.post(crp_admin.ajax_url, data, function(posts) {
                Metabox.search_button.removeClass('crp_spinner');
    
                if(posts.length == 0) {
                    jQuery('#crp_search_results_none').slideDown(500);
                    Metabox.search_term_field.focus();
                } else {
                    jQuery('#crp_search_results_table')
                        .slideDown(500)
                        .find('tbody').html(posts);
                }
            }, 'html');
        }
    },
    linkTo: function(post_id) {
        Metabox.addLink(post_id, false, true);
        Metabox.disableActions(post_id, false);
    
        var title = jQuery('#crp_post_' + post_id + '_title').text();
        var element = '<div id="crp_related_post_' + post_id +'" data-post="' + post_id + '">' + title + crp_admin.remove_image_to + '</div>';
        jQuery('#crp_relations_single_to').append(element);
    },
    linkFrom: function(post_id) {
        Metabox.addLink(post_id, true, false);
        Metabox.disableActions(post_id, false);
    
        var title = jQuery('#crp_post_' + post_id + '_title').text();
        var element = '<div id="crp_related_post_' + post_id +'" data-post="' + post_id + '">' + crp_admin.remove_image_from + title +'</div>';
        jQuery('#crp_relations_single_from').append(element);
    },
    linkBoth: function(post_id) {
        Metabox.addLink(post_id, true, true);
        Metabox.disableActions(post_id, true);
    
        // Remove any links that might already exists
        jQuery('#crp_related_post_' + post_id).remove();
    
        var title = jQuery('#crp_post_' + post_id + '_title').text();
        var element = '<tr id="crp_related_post_' + post_id +'" data-post="' + post_id + '"><td>' + title + crp_admin.remove_image_to + '</td><td><div class="crp_link">' + crp_admin.remove_image_both + '</div></td><td>' + crp_admin.remove_image_from + title + '</td></tr>';
        jQuery('#crp_relations_both').append(element);
    },
    disableActions: function(post_id, both) {
        jQuery('#crp_post_' + post_id + '_actions').find('button').each(function(index, elem) {
            if(both || index != 1) {
                elem.disabled = true;
            }
        });
    },
    addLink: function(post_id, from, to) {
        var data = {
            action: 'crp_link_posts',
            security: crp_admin.nonce,
            base: Metabox.post_id,
            target: post_id,
            from: from,
            to: to
        };
    
        jQuery.post(crp_admin.ajax_url, data);
    },
}
export default Metabox;