<?php
	$libs = $this->getLibs();
?>
<div class="wrap">
	<?php settings_errors(); ?>

	<h1><?php echo esc_html( 'Manage Libraries', 'sktbuilder' ); ?></h1>
	<form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
		<div id="sktbuilder-libs-container">
			<table class="wp-list-table widefat striped fix">
				<thead>
					<tr>
						<th><?php _e( 'URL', 'sktbuilder' ); ?></th>
						<th><?php _e( 'Name', 'sktbuilder' ); ?></th>
						<th><?php _e( 'Action', 'sktbuilder' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($libs as $lib) { ?>
						<tr>
							<td><?php echo $lib['url']; ?></td>
							<td><?php echo (isset($lib['name']) ? $lib['name'] : ''); ?></td>
							<td>
								<?php if (isset($lib['external'])) : ?>
									<a href="<?php menu_page_url( 'sktbuilder-manage-libs' ); ?>&action=remove&lib_url=<?php echo urlencode_deep( $lib['url'] ); ?>" title="<?php __( 'Delete', 'sktbuilder' ) ?>"><span class="dashicons dashicons-trash"></span></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</form>

	<h2><?php echo esc_html( 'Choose how to add a SKT Builder library', 'sktbuilder' ); ?></h2>

	<form enctype="multipart/form-data" id="sktbuilder-filters" method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
		<p><label><input type="radio" name="radio" checked="checked" value="url" /> <?php _e( 'Add a SKT Builder library', 'sktbuilder' ); ?></label></p>
		<ul id="sktbuilder-url" class="sktbuilder-filters">
			<li>
				<input type="url" class="regular-text" name="lib_url" placeholder="<?php echo esc_html( 'Enter JSON URL here', 'sktbuilder' ); ?>" value="" />
			</li>
		</ul>

		<p><label><input type="radio" name="radio" value="file" /> <?php _e( 'Upload a SKT Builder library', 'sktbuilder' ); ?></label></p>
		<ul id="sktbuilder-file" class="sktbuilder-filters">
			<li>
				<input name="lib_file" type="file" />
			</li>
		</ul>
		<input type="hidden" name="action" value="sktbuilder_add_library">
		<input type="hidden" name="_wp_http_referer" value="<?php echo admin_url( 'admin.php?page=sktbuilder-manage-libs' ) ?>" />
		<?php 
			wp_nonce_field( 'sktbuilder_add_lib', '_wpnonce', false );
			submit_button(esc_html( 'Add Library', 'sktbuilder' ));
		?>
	</form>

</div><!-- .wrap -->

