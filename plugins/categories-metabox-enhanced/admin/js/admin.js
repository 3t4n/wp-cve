window._wpLoadBlockEditor.then(function () {
    var taxes = JSON.parse(of_cme.supported_taxonomies);
    for (var i = 0; i < taxes.length; i++) {
        wp.data.dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-' + taxes[i]);
    }
});
