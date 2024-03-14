jQuery(document).ready(function() {
	fullScreenModeBGone();
});

function fullScreenModeBGone() {
	if (wp.data) {
		wp.data.select( "core/edit-post" ).isFeatureActive( "fullscreenMode" ) && wp.data.dispatch( "core/edit-post" ).toggleFeature( "fullscreenMode" );
	}
}
