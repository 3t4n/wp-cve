<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add or edit to the snippet
 */
$action      = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : '';
$snippet_id  = isset( $_GET['form_id'] ) ? esc_attr( $_GET['form_id'] ) : '';
$snippetData = ecsnippets_get_snippet( $snippet_id );
$title	     = isset( $snippetData['title'] ) ? esc_html( $snippetData['title'] ) : '';
$code	     = isset( $snippetData['code'] ) ? html_entity_decode( htmlentities( $snippetData['code'] ) ) : '';
$position	 = isset( $snippetData['position'] ) ? esc_attr( $snippetData['position'] ) : ''; ?>
<div class="wrap ecsnippets-addedit-snippet">
	<h1 class="wp-heading-inline">
		<?php
		if( $action == 'add-new-snippet' ) {
			esc_html_e( 'Add New Snippet', 'ecsnippets' );
		} else if( $action == 'edit-snippet' ) {
			esc_html_e( 'Edit Snippet', 'ecsnippets' );
		} ?>
	</h1>
	<a href="<?php echo add_query_arg( 'page', 'ecsnippets-snippets', admin_url('admin.php') ); ?>" class="page-title-action"><?php esc_html_e( 'Back' ); ?></a>
	<hr class="wp-header-end">
	<?php settings_errors(); ?>
	<form method="post" id="ecsnippets-save-snippet" action="" enctype="multipart/form-data">
		<div class="metabox-holder">
			<div class="postbox">
				<div class="handlediv" title="<?php _e( 'Click to toggle', 'ecsnippets' ); ?>"><br /></div>
				<?php
				if( $action == 'add-new-snippet' ) {
					?>
					<h3 class="hndle"><span style='vertical-align: top;'><?php _e( 'Add New Snippet', 'ecsnippets' ); ?></span></h3>
					<?php
				} else if( $action == 'edit-snippet' ) {
					?>
					<h3 class="hndle"><span style='vertical-align: top;'><?php _e( 'Edit Snippet', 'ecsnippets' ); ?></span></h3>
					<?php
				} ?>
				<div class="inside">
					<input type="hidden" value="<?php echo esc_attr( $snippet_id ); ?>" name="snippet_id" />
					<table class="form-table"><tbody>
						<tr>
							<th>
								<label for="title"><?php esc_html_e( 'Title', 'ecsnippets' ); ?></label>
							</th>
							<td>
								<input type="text" name="title" id="title" class="large-text" value="<?php echo esc_attr( $title ); ?>"  />
							</td>
						</tr>
						<tr>
							<th>
								<label for="code"><?php esc_html_e( 'Code Snippet', 'ecsnippets' ); ?></label>
							</th>
							<td>
								<textarea name="code" id="code" class="ecsnippets-html-editor"><?php echo wp_unslash( $code ); ?></textarea><br/>
								<p class="description"><?php esc_html_e( 'Please add CSS or JS code in above editor, please wrap the JS code with <script></script> tags and CSS code with <style></style> tags.', 'ecsnippets' ); ?></p>
							</td>
						</tr>
						<tr>
							<th>
								<label for="position"><?php esc_html_e( 'Position', 'ecsnippets' ); ?></label>
							</th>
							<td>
								<select name="position" id="position">
									<option value="header" <?php selected( $position, 'header' ); ?>><?php esc_html_e( 'Header', 'ecsnippets' ); ?></option>
									<option value="footer" <?php selected( $position, 'footer' ); ?>><?php esc_html_e( 'Footer', 'ecsnippets' ); ?></option>
								</select>
							</td>
						</tr>
					</tbody></table>
				</div>
			</div>
			<?php
			if( empty( $GLOBALS['hide_save_button'] ) ) :
				submit_button( __( 'Save Changes', 'ecsnippets' ), 'primary', 'ecsnippets-snippet-save' );
			endif; ?>
		</div>
	</form>	
</div>