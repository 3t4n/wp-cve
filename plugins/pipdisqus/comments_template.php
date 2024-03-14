<?php
if (!defined('ABSPATH')) die;

if (post_password_required()) {
	return;
}

// Don't show comments for Beaver Builder plugin or previews
if (isset($_GET['fl_builder']) || isset($_GET['preview'])) {
	return;
}

// array of post statuses which should NOT display comments
$no_comments = apply_filters('pipdisqus_post_statuses', array('draft', 'trash', 'future'));

if ( in_array(get_post_status(), $no_comments) ) {
	if (is_super_admin()) { // admins see notice only
	?>
		<div id="comments" class="comments-area">
			<p>Disqus comments are not displayed until the post is published.</p>
		</div>
	<?php
	}
	return;
}

$disqus_shortname = '';
$options = get_option('pipdisqus_settings');
$disqus_embed = 'https://'.sanitize_text_field($options['disqus_shortname']).'.disqus.com/embed.js';

?>
<div id="comments" class="comments-area">
	<div id="disqus_thread"></div>
	<!-- pipDisqus -->
	<script defer>
	var disqus_config = function () {
		this.page.url = "<?php the_permalink(); ?>";
		this.page.identifier = "<?php echo $post->ID.' '.$post->guid; ?>";
		this.page.title = "<?php the_title_attribute(); ?>";
	};
	(function() {
		var d = document, s = d.createElement('script');
		s.src = '<?php echo $disqus_embed; ?>';
		s.defer = 'defer';
		s.setAttribute('data-timestamp', +new Date());
		(d.head || d.body).appendChild(s);
	})();
	</script>
	<noscript>Please enable JavaScript to view comments powered by Disqus.</noscript>
</div>