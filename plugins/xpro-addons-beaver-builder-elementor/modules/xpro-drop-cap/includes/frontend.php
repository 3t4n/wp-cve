<?php if ( $settings->dropcap_description ) :
	global $wp_embed;
	?>
<div class="xpro-dropcap-wrapper">
	<?php echo wpautop( $wp_embed->autoembed( $settings->dropcap_description ) ); ?>
</div>
<?php endif; ?>
