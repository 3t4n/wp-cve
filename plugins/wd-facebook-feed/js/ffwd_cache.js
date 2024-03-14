jQuery(window).load(function() {
  var feed_ids = {};
  jQuery(".ffwd_container1").each(function (key) {
    feed_ids[key] = jQuery(this).attr("data-feed_id");
  });
  if (Object.keys(feed_ids).length === 0) {
    feed_ids = 0;
  }
  var datas;
  /* Case when need update all feeds data */
  if(ffwd_cache.need_update === 'true') {
    if( ffwd_cache.update_data === '' ) {
      wdi_hide_loading();
      return;
    }
    datas = JSON.parse(ffwd_cache.update_data);
    jQuery.each( datas, function( key, value ) {
      var data = value;
      update_cache_data(data['id'], 0, '', data['page_access_token'], data['from'], data['content_type']);
    });
  }
  /* Case when request from admin part and no need to run cron */
  else if( ffwd_cache.need_update === 'false' && feed_ids === 0) { // need to remove
    wdi_hide_loading();
  }
  /* case frontend when media data count is 0 */
  else if( ffwd_cache.need_update === 'false' && feed_ids !== 0 ) {
    datas = JSON.parse(ffwd_cache.update_data);
    var zeroDataCount = 0;
    jQuery.each( datas, function( key, value ) {
      var data = value;
      if( parseInt(data['data_count']) === 0 && Object.values(feed_ids).includes(data['id']) ) {
        zeroDataCount++;
        set_cache_data(data['id'], 0, '', data['page_access_token'], data['from'], data['content_type']);
      }
    });
    if( zeroDataCount === 0 ) {
      wdi_hide_loading();
    }
  }

});

/* Using for frontend hide loading and show hidden container */
function wdi_hide_loading() {
  jQuery(".ffwd_container2").removeClass("ffwd-hidden"); // create function for this
  jQuery(".ffwd-loading-layout").remove();
}

function set_cache_data( fb_id, iter, fb_graph_url, page_access_token, user_id, content_type, non_public_share_count ) {
  var data = {
    'action' : 'set_cache_data',
    'fb_id' : fb_id,
    'user_id' : user_id,
    'content_type': content_type,
    'graph_url': fb_graph_url,
    'iter': iter,
    'page_access_token' : page_access_token,
    'non_public_share_count' : non_public_share_count,
  };
  jQuery.ajax({
    method: "POST",
    url: ffwd_cache.ajax_url,
    data: data,
    success: function (result) {
      result = JSON.parse(result);
      var return_json = '';
      if( result['status'] === 'success' && result['next_page'] !== '') {
        iter = parseInt(result['iter']) + 1;
        set_cache_data( fb_id, iter, result['next_page'], page_access_token, user_id, content_type, result['non_public_share_count'] );
      }
      else if( result['status'] === 'error' ) {
        return_json = {
          0 : result['status'],
          1 : result['msg'],
        };
        apply_save_ajax_message( return_json );
      } else {
        return_json = {
          0:'success',
          1: fb_id,
          2: result['non_public_share_count'],
        };
        apply_save_ajax_message( return_json );
        get_shortcode_html(fb_id);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(thrownError);
    }
  });
}

function get_shortcode_html( fb_id ) {
  var frontend = 0;
  jQuery(".ffwd_container1").each(function (key) {
    if(jQuery(this).attr("data-feed_id") === fb_id) {
      frontend = 1;
    }
  });
  if( frontend === 0 ) {
    return;
  }

  var data = {
    'action' : 'ffwd_ajax_front_end',
    'fb_id' : fb_id
  };
  jQuery.ajax({
    method: "POST",
    url: ffwd_cache.ajax_url,
    data: data,
   // dataType: 'html',
    success: function (result) {
      var html = jQuery(result).find(".ffwd_container1[data-feed_id='" + fb_id + "']").html();
      jQuery(document).find(".ffwd_container1[data-feed_id='" + fb_id + "']").html(html);
      jQuery(document).find(".ffwd_container1[data-feed_id='" + fb_id + "'] .ffwd-loading-layout").remove();
      jQuery(document).find(".ffwd_container1[data-feed_id='" + fb_id + "'] .ffwd_container2").removeClass('ffwd-hidden');

      /* Using to reset click action after content replace (problem in album click) */
      jQuery(".ffwd_container1").each(function () {
        var id = jQuery(this).attr("id");
        if( typeof id !== 'undefined' ) {
          var ind = id.replace('ffwd_container1_', '');
          window["ffwd_document_ready_" + ind]();
        }
      });
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(thrownError);
    }
  });
}

function update_cache_data( fb_id, iter, fb_graph_url, page_access_token, user_id, content_type, non_public_share_count ) {
  var data = {
    'action' : 'update_cache_data',
    'fb_id' : fb_id,
    'user_id' : user_id,
    'content_type': content_type,
    'graph_url': fb_graph_url,
    'iter': iter,
    'page_access_token' : page_access_token,
    'non_public_share_count' : non_public_share_count,
  };
  jQuery.ajax({
    method: "POST",
    url: ffwd_cache.ajax_url,
    data: data,
    success: function (result) {
      result = JSON.parse(result);
      var return_json = '';
      fb_id = result['fb_id'];
      if( result['status'] === 'success' && result['next_page'] !== '') {
        iter = parseInt(result['iter'])+1;
        update_cache_data( fb_id, iter, result['next_page'], page_access_token, user_id, content_type, result['non_public_share_count'] );
      }
      else if( result['status'] === 'error' ) {
        return_json = {
          0 : result['status'],
          1 : result['msg'],
        };
        apply_save_ajax_message( return_json );
      } else {
        return_json = {
          0:'success',
          1: fb_id,
          2: result['non_public_share_count'],
        };
        get_shortcode_html(fb_id);
        apply_save_ajax_message( return_json );
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(thrownError);
    }
  });
}
/* Save feed ajax message printing in success */
function apply_save_ajax_message( result ) {
  var task = jQuery("#task").val();
  jQuery(".ffwd_notice").html("");
  if (result[1] !== 0) {
    jQuery("#current_id").val(result[1]);
  }
  switch (task) {
    case "apply":
      var non_visible_post_msg = '';
      if( typeof result[2] !== "undefined" && 0 !== parseInt(result[2])) {
        non_visible_post_msg = "<p>"+parseInt(result[2])+" posts are not available, probably they are not public.</p>";
        non_visible_post_msg = "<p>The data of "+parseInt(result[2])+" posts will not be displayed on your site. This is because, most likely the owner of the post did not set the post to public and/or shared the post to a specific page, or has deleted the post.</p>";
      }
      jQuery("#task").val("");
      jQuery('#message_div').html("<strong><p>Items Succesfully Saved.</p>"+non_visible_post_msg+"</strong>");
      jQuery('#message_div').show();
      jQuery('#ffwd_page_url,#page_access_token,#name').removeAttr("style");
      break;
    case "save":
      if( typeof result[2] !== "undefined" && 0 !== parseInt(result[2])) {
        jQuery("#ffwd_info_form").append("<input type='hidden' name='non_public_posts_count' value='"+parseInt(result[2])+"'>")
      }

      jQuery("#ffwd_info_form").submit();
      break;
    default:
      jQuery("#task").val("");
      break;
  }
  jQuery('#opacity_div').hide();
  jQuery('#loading_div').hide();
}