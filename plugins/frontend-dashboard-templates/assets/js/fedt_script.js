jQuery(document).ready(function ($) {

  var b = $('body')

  b.on('click', '.fed_profile_image_edit', function (e) {
    var custom_uploader
    var button_click = $(this)
    e.preventDefault()
    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: 'Upload Profile Picture',
      button: {
        text: 'Upload Profile Picture'
      },
      multiple: false
    })
    custom_uploader.on('select', function () {
      // var regex_image_type = /(image)/g;
      attachment = custom_uploader.state().get('selection').first().toJSON()
      button_click.closest('.menu_image').find('.fedt_profile_image img').attr('src', attachment.url)
      $.ajax({
        type: 'POST',
        url: button_click.closest('.menu_image').data('url'),
        data: { 'profile_image_url': attachment.url },
        success: function (results) {
          if ( ! results.success) {
            swal(
              {
                title: results.data.message,
                type: 'error',
              }
            )
          }
        }
      })
    })
    //Open the uploader dialog
    custom_uploader.open()
    e.preventDefault()
  })
})