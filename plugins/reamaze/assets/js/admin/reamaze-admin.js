var _support = _support || { 'ui': {}, 'user': {} };
_support['account'] = 'reamaze';
_support['custom_fields'] = {
  current_page: {
    type: 'hidden',
    value: window.location.href
  },
  app: {
    type: 'hidden',
    value: 'wordpress'
  }
};

(function($) {
  $(function() {
    $('body').on('click', 'a[data-reamaze-path]', function(e) {
      // is reamaze link?
      e.preventDefault();
      e.stopPropagation();

      window.location.href = reamaze_context.dashboard_url + '&path=' + $(this).data('reamaze-path');

      return false;
    });

    $('body').on('click', '.reamaze-create-conversation', function() {
      var commentID = $(this).data('id');
      $.colorbox({
        href: ajaxurl + '?action=reamaze_convert_to_conversation&comment_id=' + commentID,
        onComplete: function(a,b,c) {
          $(window).trigger('reamaze:markitup', [$('#create-reamaze-conversation-form')]);
          $.colorbox.resize();

          var $form = $('#create-reamaze-conversation-form');

          $('#create-reamaze-conversation-form input[data-toggle]').on('change', function() {
            var $el = $(this);
            var selector = $el.data('toggle');
            $(selector).toggle($el.is(':checked'));
            $.colorbox.resize();
          });

          $form.on('submit', function(e) {
            $form.find('.button').prop('disabled', true);
            $form.find('.error-message').hide();

            var params = {
              action: 'reamaze_convert_to_conversation',
              comment_id: commentID,
              category: $form.find('#create-conversation-category').val()
            };

            if ($form.find('input[name=include_reply]').is(':checked')) {
              params['include_reply'] = 1;
              params['reply_message'] = $('#create-conversation-reply-message').val();
              if ($form.find('input[name=add_wp_reply]').is(':checked')) {
                params['add_wp_reply'] = 1;
              }
            }

            if ($form.find('input[name=add_note]').is(':checked')) {
              params['add_note'] = 1;
              params['note_message'] = $('#create-conversation-add-note').val();
            }

            $.ajax({
              type: 'POST',
              url: ajaxurl,
              data: params,
              dataType: 'json',
              success: function(o) {
                $('#create-reamaze-conversation-content-wrapper .conversation-admin-link').attr('data-reamaze-path', o['admin_path']);
                $('#create-reamaze-conversation-content-wrapper .conversation-admin-link').attr('href', o['admin_url']);
                $('#create-reamaze-conversation-content-wrapper .create-reamaze-conversation-content').hide();
                $('#create-reamaze-conversation-content-wrapper .success-message').show();
                $.colorbox.resize();
              },
              error: function (o) {
                $form.find('.button').prop('disabled', false);
                $form.find('.error-message').show();
                $.colorbox.resize();
              }
            });

            return false;
          });
        }
      });
    });
  });
})(jQuery);
