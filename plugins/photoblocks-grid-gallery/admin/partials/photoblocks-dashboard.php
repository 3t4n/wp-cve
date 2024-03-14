<div class="wrap" id="photoblocks-dashboard">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Galleries', 'photoblocks' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->plugin_name . '-edit' ) ); ?> " class="page-title-action"><?php esc_html_e( 'Create a new gallery', 'photoblocks' ); ?></a>

	<hr class="wp-header-end">
	<br>
	<br>
	<br>
	
	<div class="panel">
				
		<table class="wp-list-table widefat fixed striped photoblocks">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Gallery name', 'photoblocks' ); ?></th>
					<th><?php esc_html_e( 'Shortcode', 'photoblocks' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if ( count( $_bag['galleries'] ) == 0 ) : ?>
				<tr>
					<td colspan="2"><?php esc_html_e( 'No items found.', 'photoblocks' ); ?></td>
				</tr>
			<?php endif ?>
			<?php foreach ( $_bag['galleries'] as $g ) : ?>
			<tr>
				<td>
					<a class="row-title" href="<?php echo esc_url( $this->generate_admin_action_link( $g->id ) ); ?>">
					<?php echo esc_html( $g->name ); ?></a>
					<div class="row-actions">
						<span class="edit"><a href="<?php echo esc_url( $this->generate_admin_action_link( $g->id ) ); ?>" aria-label="Edit">Edit</a></span>
						<?php if( current_user_can( 'edit_posts' ) ): ?>
						<span class="clone"> | <a href="<?php echo esc_url( $this->generate_admin_action_link( $g->id , 'clone' ) ); ?>" data-id="<?php echo esc_attr( $g->id ); ?>" aria-label="Clone">Clone</a></span>
						<span class="trash"> | <a href="<?php echo esc_url( $this->generate_admin_action_link( $g->id , 'delete' ) ); ?>" data-id="<?php echo esc_attr( $g->id ); ?>" class="submitdelete" aria-label="Delete">Delete</a></span>
						<?php endif;?>
					</div>
				</td>
				<td>
					<input type="text" readonly value="[photoblocks id=<?php echo esc_attr( $g->id ); ?>]"> 
					<a href="#" title="Click to copy shortcode" class="copy-photoblocks-shortcode button button-primary dashicons dashicons-format-gallery" style="width:40px;"></a><span style="margin-left:15px;"></span>
				</td>
			</tr>
			<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php esc_html_e( 'Gallery name', 'photoblocks' ); ?></th>
					<th><?php esc_html_e( 'Shortcode', 'photoblocks' ); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
