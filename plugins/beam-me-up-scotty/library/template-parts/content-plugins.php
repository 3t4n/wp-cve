<?php
update_option( 'otb_new_plugin', false );
?>

<h2><?php echo __( 'Check out our other plugins!', 'beam-me-up-scotty' ); ?></h2>

<p>
	<?php
	echo __( 'If you like this plugin you might like our other plugins!', 'beam-me-up-scotty' );
	?>
<p>

<ul class="vanilla plugins">

<?php 
foreach ($this->plugins as $plugin) {
	$plugin = (object) $plugin; 
	$new = true === $plugin->new;

	if ($new && !get_option( 'otb_new_plugin_' .$plugin->slug. '_viewed' ) ) {
		update_option( 'otb_new_plugin_' .$plugin->slug. '_viewed', true );
	}
?>

	<li>
	
		<a href="https://www.outtheboxthemes.com/go/plugin-beam-me-up-scotty-<?php echo $plugin->slug; ?>" target="_blank"><?php echo '<img src="'. $plugin->thumbnail .'" title="' .$plugin->title. ' WordPress plugin" />'; ?></a>
	
		<div class="details">
			<h2>
			<?php 
				echo '<a href="https://www.outtheboxthemes.com/go/plugin-beam-me-up-scotty-' .$plugin->slug. '" target="_blank" title="' .$plugin->title. ' WordPress plugin">' . $plugin->title . '</a>';
				if ( $new ) {
					echo '<span class="new">NEW!</span>';
				}
			?>
			</h2>
			<h3 class="tagline"><?php echo $plugin->tagline; ?></h3>

			<?php
			if ( $plugin->coming_soon == false ) {
			} else {
			?>
			<span class="color-text"><?php echo __( 'Coming soon!', 'beam-me-up-scotty' ); ?></span>
			<?php 
			}
			?>
		</div>

	</li>
	
<?php
}
?>

</ul>