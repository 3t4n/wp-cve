<?php echo wp_kses_post($args['before_widget']); ?>
<?php if (!empty($title))
echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
?>
<div class="directorypress-content-wrap directorypress-widget directorypress-social-widget">
	<ul class="directorypress-social clearfix">
		<?php if ($instance['is_facebook']): ?>
		<li>
			<a target="_blank" href="<?php echo esc_url($instance['facebook']); ?>"><i class="fab fa-facebook-f"></i></a>
		</li>
		<?php endif; ?>

		<?php if ($instance['is_twitter']): ?>
		<li>
			<a target="_blank" href="<?php echo esc_url($instance['twitter']); ?>"><i class="fab fa-twitter"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if ($instance['is_linkedin']): ?>
		<li>
			<a target="_blank" href="<?php echo esc_url($instance['linkedin']); ?>"><i class="fab fa-linkedin-in"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if ($instance['is_youtube']): ?>
		<li>
			<a target="_blank" href="<?php echo esc_url($instance['youtube']); ?>"><i class="fab fa-youtube"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if ($instance['is_rss']): ?>
		<li>
			<a target="_blank" href="<?php echo esc_url($instance['rss']); ?>"><i class="fas fa-rss"></i></a>
		</li>
		<?php endif; ?>
	</ul>
</div>
<?php echo wp_kses_post($args['after_widget']); ?>