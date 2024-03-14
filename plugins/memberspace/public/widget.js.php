<script>
	var MemberSpace = window.MemberSpace || {subdomain: "<?php echo esc_js( get_option( 'memberspace_subdomain' ) ); ?>"};
	(function(d){
		var s = d.createElement("script");
		s.src = "<?php echo esc_url( MemberSpace::WIDGET_ASSET_URI . '/scripts/widgets.js' ) ?>";
		var e = d.getElementsByTagName("script")[0];
		e.parentNode.insertBefore(s,e);
	}(document));
</script>
