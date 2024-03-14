declare var acfwAdmin: any;

(function ($) {
  var noticeCountTemplate =
    '<span class="update-plugins count-{count}"><span class="notices-count">{count}</span></span>';

  // replace all instances of {count} with the actual count
  noticeCountTemplate = noticeCountTemplate.replace(/\{count\}/g, acfwAdmin.noticeCount);

  $('#toplevel_page_acfw-admin .wp-menu-name').append(' ' + noticeCountTemplate);
})(jQuery);
