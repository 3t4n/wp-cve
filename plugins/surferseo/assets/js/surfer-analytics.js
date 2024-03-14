jQuery(function ($) {
  $('.surfer-analytics').on('click', function (e) {
    e.preventDefault()

    const enabling = $(this).data('tracking-enabling') || false
    if (!enabling && !surfer_analytics_lang.tracking_enabled) {
      window.location = $(this).attr('href')
    }

    const eventName = $(this).data('event-name')
    const eventData = $(this).data('event-data')

    const data = {
      force_push: enabling,
      event_name: eventName,
      event_data: eventData,
      _surfer_nonce: surfer_analytics_lang._surfer_nonce,
    }

    $.ajax({
      url: surfer_analytics_lang.ajaxurl + '?action=surfer_track_event',
      type: 'POST',
      data: JSON.stringify(data),
      dataType: 'json',
      contentType: 'application/json',
      async: true,
    })

    window.location = $(this).attr('href')
  })
})
