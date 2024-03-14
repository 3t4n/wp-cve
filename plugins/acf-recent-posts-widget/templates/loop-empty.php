<?php
/*
 * @author WP doin
 * @package acf-recent-posts-widget/templates
 * @version 5.4.3
 * 
 */
global $acf_rpw_instance, $acf_rpw_args, $acf_rpw_title;
extract( $acf_rpw_instance );
?>
<?php if( isset( $no_posts ) ): ?>
	<div class="acf-rpw-no-posts-found">
		<?php echo htmlspecialchars_decode( $no_posts ); ?>
	</div>
<?php endif; ?>