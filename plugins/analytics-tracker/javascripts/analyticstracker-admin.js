/*
 * Custom scripts
 *
 */
;(function ($) {
  //"use strict";

  var analyticstracker_ga_admin = {

    // Start Functions
    startSwp: function () {
      analyticstracker_ga_admin.ShowProduct();
    },

    //Init
    ShowProduct: function () {

        $.ajax( {
          url: 'https://dailydesigncafe.com/wp-json/wp/v2/post_product-api',
          data: {
			  per_page: 1,
			  filter: {
			  'orderby': 'rand'
		  	},
          },
          dataType: 'json',
          type: 'GET',
          success: function ( data ) {
            var $html = ''
            $.each(data, function(i) {
              $html += '<div class="dailydesign"><a target="_blank" title="'+data[i].title['rendered']+'" href="'+data[i].product_meta['ktnl_affiliate_url']+'?ref=stylishwp"><img src="'+data[i].ktnl_featured_image['media_details']['sizes']['medium']['source_url']+'" alt="'+data[i].title['rendered']+'"></a></div><div class="dailydesign_by">Premium WordPress Theme created by: <a target="_blank" title="'+data[i].product_meta['ktnl_tf_author_username']+'" href="'+data[i].product_meta['ktnl_tf_author_url']+'?ref=stylishwp"> '+data[i].product_meta['ktnl_tf_author_username']+'</a></div>';
            });
            $( '.dailydesign_container' ).html( $html );
          },
          cache: true
        } );

    },

  };

  jQuery(document).ready(function () {
    analyticstracker_ga_admin.startSwp();
  });

})(jQuery);
