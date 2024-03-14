<?php 
global $apg_video_sitemap; 

$tab            = 1;
$configuracion  = get_option( 'xml_video_sitemap' );
?>
<div class="wrap">
	<h2>
		<?php _e( 'Google Video Sitemap Feed Options.', 'google-video-sitemap-feed-with-multisite-support' ); ?>
	</h2>
	<h3><a href="<?php echo $apg_video_sitemap[ 'plugin_url' ]; ?>" title="Art Project Group"><?php echo $apg_video_sitemap[ 'plugin' ]; ?></a> </h3>
	<p>
		<?php _e( 'Dynamically generates a Google Video Sitemap and automatically submit updates to Google and Bing.', 'google-video-sitemap-feed-with-multisite-support' ); ?>
	</p>
	<?php include( 'cuadro-informacion.php' ); ?>
	<form method="post" action="options.php">
		<?php settings_fields( 'apg_video_sitemap_settings_group' ); ?>
		<div class="cabecera"> <a href="<?php echo $apg_video_sitemap[ 'plugin_url' ]; ?>" title="<?php echo $apg_video_sitemap[ 'plugin' ]; ?>" target="_blank"><img src="<?php echo plugins_url( 'assets/images/cabecera.jpg', DIRECCION_apg_video_sitemap ); ?>" class="imagen" alt="<?php echo $apg_video_sitemap[ 'plugin' ]; ?>" /></a> </div>
		<table class="form-table apg-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'email:', 'google-video-sitemap-feed-with-multisite-support' ); ?>
					</th>
					<td><input id="xml_video_sitemap[correo]" name="xml_video_sitemap[correo]" type="checkbox" value="1" <?php echo ( isset( $configuracion[ 'correo' ] ) && $configuracion[ 'correo' ] == "1" ? "checked":  "" ); ?> tabindex="<?php echo $tab++; ?>" />
						<label for="xml_video_sitemap[correo]">
							<?php _e( 'Send video error notifications by email.', 'google-video-sitemap-feed-with-multisite-support' ); ?>
						</label></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<?php
			//Guarda las URLs procesadas
			if ( !empty( $configuracion ) ) {
			    foreach ( $configuracion as $nombre => $opcion ) {
			        if ( $nombre != 'correo' ) {
			            echo '<input type="hidden" id="xml_video_sitemap[' . $nombre . ']" name="xml_video_sitemap[' . $nombre . ']" value="' . $opcion . '">';
			        }
			    }
			}
			?>
			<input class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'google-video-sitemap-feed-with-multisite-support' ); ?>"  name="submit" id="submit" tabindex="<?php echo $tab++; ?>" />
		</p>
	</form>
</div>
