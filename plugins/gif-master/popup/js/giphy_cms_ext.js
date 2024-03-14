var GiphyCMSExt = {
  doTinyMCEEmbed: function() {
    var embedId = jQuery('img#gif-detail-gif').attr('data-id');
    var width = jQuery('img#gif-detail-gif').attr('data-embed-width');
    var height = jQuery('img#gif-detail-gif').attr('data-embed-height');
    var username = jQuery('img#gif-detail-gif').attr('data-username');
    var profile_url = jQuery('img#gif-detail-gif').attr('data-profile-url');
    var shortlink = jQuery('img#gif-detail-gif').attr('data-shortlink');
    var attribution_string;

    var uri = '<iframe src="//giphy.com/embed/' + embedId + '" width="' + width + '" height="' + height + '" frameBorder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'

    parent.tinyMCE.activeEditor.execCommand("mceInsertRawHTML", false, uri);
    parent.tinyMCE.activeEditor.selection.select(parent.tinyMCE.activeEditor.getBody(), true); // ed is the editor instance
    parent.tinyMCE.activeEditor.selection.collapse(false);
    parent.tinyMCE.activeEditor.windowManager.close(window);
  }
};
