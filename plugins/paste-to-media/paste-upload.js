jQuery(document).ready(function($){
  document.onpaste = function(event){
    $('.spinner').addClass('is-active');
    if($(".media-modal, .media-frame").is(":visible")) {
      var items = (event.clipboardData || event.originalEvent.clipboardData).items;
      for (var i = 0 ; i < items.length ; i++) {
        var item = items[i];
        if (item.type.indexOf("image") != -1) {
          var file = item.getAsFile();
          setTimeout(upload_file_with_ajax(file, "file"), 100, file);
        } else if (item.type.indexOf("text/plain") != -1) {
          var clipboardData = event.clipboardData || window.clipboardData;
          var pastedData = clipboardData.getData('Text');
          setTimeout(upload_file_with_ajax(pastedData, "file"), 100, pastedData);
        }
      }
    }
  }
  function upload_file_with_ajax(file, type){
    var formData = new FormData();
    formData.append('action', "paste_save");
    formData.append('security', photo_upload.ajax_nonce);
    if(type == 'file'){
      formData.append('file', file);
      formData.append('param', "add_image");
    } else {
      formData.append('string', file);
      formData.append('param', "add_image_text");
    }

    $.ajax(photo_upload.ajaxurl , {
      type: 'POST',
      contentType: false,
      processData: false,
      data: formData,
      success: function(response) {
        if(wp.media.frame.content.get().collection!==undefined){
           wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});
           wp.media.frame.content.get().options.selection.reset();
        }else{
           wp.media.frame.library.props.set({ignore: (+ new Date())});
        }
        if($(".media-modal").is(":visible")) {
          setTimeout(function(){ 
            $(document).find('.attachments li.attachment:first-child').trigger('click');
          }, 100);
        }
      },
      done: function () {
        $('.spinner').removeClass('is-active');
      }
    });
  }
});