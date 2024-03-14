Dropzone.options.importForm = {
  dictDefaultMessage: seoAutomatedLinkBuildingImport.importHint,
  error: function(file, response) {
    console.error(response);
  },
  success: function(file, response) {
    console.log(file, response);
    window.location.href = seoAutomatedLinkBuildingImport.redirectUrl;
  }
};

jQuery(function($) {
  $('.export').on('click', function() {
    const ext = $('input[name=ext]:checked').val();
    const mimetype = ext === 'csv' ? 'text/csv' : 'application/json';
    $.post(seoAutomatedLinkBuildingImport.adminAjax, {action: 'seo_automated_link_building_export_links', ext: ext}, function(data) {
      var blob = new Blob([
          new Uint8Array([0xEF, 0xBB, 0xBF]), // UTF-8 BOM
          data,
        ],
        { type: mimetype + ";charset=utf-8" }
      );
      download(blob, 'internal-link-manager.' + ext, mimetype);
    });
  });
});
