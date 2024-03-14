jQuery(document).ready(function(){
    jQuery('.clear-browser-cache').click(function(){
        var data = {
  			'action': 'purge_cache',
            'security': ajax_object.purge_cache_nonce,
  		};
  		jQuery.post(ajax_object.ajax_url, data, function() {
  			alert('Browser cache cleared Successfully!');
            location.reload();
  		});
    });
    jQuery('.aeh-dismiss-maybelater').click(function(){
        var data = {
                'action': 'hide_review_notice',
                'security': ajax_object.maybelater_nonce,
        };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajax_object.ajax_url, data, function() {
            alert('Thanks for your response!');
            location.reload();
        });
    });
    jQuery('.aeh-dismiss-alreadydid').click(function(){
        var data = {
                'action': 'hide_review_notice',
                'security': ajax_object.alreadydid_nonce,
        };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajax_object.ajax_url, data, function() {
            alert('Thanks for your response!');
            location.reload();
        });
    });
});