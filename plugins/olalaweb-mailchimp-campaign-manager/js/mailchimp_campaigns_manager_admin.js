/**
 * Import Mailchimp campaigns.
 */
jQuery(document).ready(function ($) {
  var btn = $('#mailchimp_campaigns_manager_import'),
    placeholder = $('#mailchimp_campaigns_manager_placeholder'),
    dots = '<span class="dots"><span>.</span><span>.</span><span>.</span></span>';
  btn.on('click', function (e) {
    e.preventDefault();
    btn.toggleClass('active');
    placeholder.addClass('notice');
    placeholder.html(
      '<span class="mailchimp_campaigns_manager_ajax_loading">' +
      '<span class="dashicons dashicons-format-status"></span>' + ' Talking to the chimp' + dots +
      '<br/>' +
      'Please wait until all campaigns are imported.' +
      '<br/>' +
      'This may take a while if you have many campaigns in your Mailchimp account.' +
      '</span>'
    );
    var data = {
      'action': 'mailchimp_campaigns_manager_import'
    };
    $.post(ajaxurl, data, function (response) {
      btn.toggleClass('active');
      placeholder.removeClass('notice');
      placeholder.html(response);
    });
  });
});

/**
 * Recalculate Mailchimp campaigns total.
 */
jQuery(document).ready(function ($) {
  var btn = $('#mailchimp_campaigns_manager_recalculate'),
    placeholder = $('#mailchimp_campaigns_manager_placeholder'),
    dots = '<span class="dots"><span>.</span><span>.</span><span>.</span></span>';
  btn.on('click', function (e) {
    e.preventDefault();
    btn.toggleClass('active');
    placeholder.addClass('notice');
    placeholder.html(
      '<span class="mailchimp_campaigns_manager_ajax_loading">' +
      '<span class="dashicons dashicons-format-status"></span>' + ' Talking to the chimp' + dots +
      '</span>'
    );
    var data = {
      'action': 'mailchimp_campaigns_manager_recalculate'
    };
    $.post(ajaxurl, data, function (response) {
      btn.toggleClass('active');
      placeholder.removeClass('notice');
      placeholder.html(response);
    })
      .done(function () {
        placeholder.append('<p class="description">Successfully import all your campaigns.</p>');
      })
      .fail(function (error) {
        var error_message = $.parseJSON(error.responseJSON);
        placeholder.append('<p class="description">' + error_message + '</p>');
      })
      .always(function () {
        // Refresh page.
        placeholder.append('<p class="description">You should refresh this page now.</p>');
      });
  });
});

/**
 * Try to connect to distant app.
 */
jQuery(document).ready(function ($) {
  var btn = $('#mailchimp_campaigns_manager_connect_app'),
    placeholder = $('#mailchimp_campaigns_manager_placeholder'),
    dots = '<span class="dots"><span>.</span><span>.</span><span>.</span></span>';
  btn.on('click', function (e) {
    e.preventDefault();
    btn.toggleClass('active');
    placeholder.addClass('notice');
    placeholder.html(
      '<span class="mailchimp_campaigns_manager_ajax_loading">' +
      '<span class="dashicons dashicons-format-status"></span>' + ' Talking to remote server ' + dots +
      '</span>'
    );
    var data = {
      'action': 'mailchimp_campaigns_manager_connect_app'
    };
    $.post(ajaxurl, data, function (response) {
      btn.toggleClass('active');
      placeholder.removeClass('notice');
      placeholder.html(response);
    })
      .always(function () {
        // Refresh page.
        placeholder.append('<p class="description">You should refresh this page now.</p>');
      });
  });
});

/**
 * Try to disconnect from distant app.
 */
jQuery(document).ready(function ($) {
  var btn = $('#mailchimp_campaigns_manager_disconnect_app'),
    placeholder = $('#mailchimp_campaigns_manager_placeholder'),
    dots = '<span class="dots"><span>.</span><span>.</span><span>.</span></span>';
  btn.on('click', function (e) {
    e.preventDefault();
    btn.toggleClass('active');
    placeholder.addClass('notice');
    placeholder.html(
      '<span class="mailchimp_campaigns_manager_ajax_loading">' +
      '<span class="dashicons dashicons-format-status"></span>' + ' Talking to remote server ' + dots +
      '</span>'
    );
    var data = {
      'action': 'mailchimp_campaigns_manager_disconnect_app'
    };
    $.post(ajaxurl, data, function (response) {
      btn.toggleClass('active');
      placeholder.removeClass('notice');
      placeholder.html(response);
    })
      .always(function () {
        // Refresh page.
        placeholder.append('<p class="description">You should refresh this page now.</p>');
      });
  });
});

/**
 * Try to update distant app informationI.
 */
jQuery(document).ready(function ($) {
  var btn = $('#mailchimp_campaigns_manager_update_app'),
    placeholder = $('#mailchimp_campaigns_manager_placeholder'),
    dots = '<span class="dots"><span>.</span><span>.</span><span>.</span></span>';
  btn.on('click', function (e) {
    e.preventDefault();
    btn.toggleClass('active');
    placeholder.addClass('notice');
    placeholder.html(
      '<span class="mailchimp_campaigns_manager_ajax_loading">' +
      '<span class="dashicons dashicons-format-status"></span>' + ' Talking to remote server ' + dots +
      '</span>'
    );
    var data = {
      'action': 'mailchimp_campaigns_manager_update_app'
    };
    $.post(ajaxurl, data, function (response) {
      btn.toggleClass('active');
      placeholder.removeClass('notice');
      placeholder.html(response);
    })
      .always(function () {
        // Refresh page.
        placeholder.append('<p class="description">You should refresh this page now.</p>');
      });
  });
});
