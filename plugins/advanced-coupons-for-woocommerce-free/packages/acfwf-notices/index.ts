import './index.scss';

declare var ajaxurl: any;

jQuery(document).ready(function ($) {
  var $adminNotices = $('.acfw-admin-notice');

  $adminNotices.on(
    'click',
    'button.notice-dismiss,.acfw-notice-dismiss,.review-actions .snooze,.review-actions .dismissed,.action-button.with-response',
    function (e) {
      e.preventDefault();
      var $notice = $(this).closest('.acfw-admin-notice');
      var response = $(this).data('response');
      $notice.fadeOut('fast');
      $.post(ajaxurl, {
        action: 'acfw_dismiss_admin_notice',
        notice: $notice.data('notice'),
        response: response ? response : 'dismissed',
        nonce: $notice.data('nonce'),
      });
    }
  );
});
