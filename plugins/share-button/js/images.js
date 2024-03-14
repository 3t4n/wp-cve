// uploader for images
"use strict";

jQuery(document).ready(function($) {

    $(document).on('mbsocial-editor-settings-loaded', function(e)
    {
      $('.media_upload').each(function()
      {
        var id = $(this).data('upload-id');
        window.maxFoundry.maxIcons.attachMediaUploader(id);
      });

    });
});
