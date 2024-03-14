<?php
defined('ABSPATH') or die();
?>
<div class="wrap">
	<?php foreach ( $notices as $notice) { ?>
		<div class="notice notice-<?php echo $notice['type']; ?> is-dismissible">
			<p><?php echo $notice['message']; ?></p>
		</div>
	<?php } ?>
	<h2 class="nav-tab-wrapper">
		<a href="?page=legal-page-generator" class="nav-tab nav-tab-active"><?php _e( 'Main', 'legal-page-generator' ); ?></a>
		<a href="?page=legal-page-generator-pages" class="nav-tab"><?php _e( 'Manage Pages', 'legal-page-generator' ); ?></a>
		<a href="?page=legal-page-generator-cr" class="nav-tab"><?php _e( 'Custom Request', 'legal-page-generator' ); ?></a>
	</h2>
	<h1><?php _e( 'Legal Pages Generator - Main', 'legal-page-generator' ); ?></h1>
	<form method="post" action="options.php">
		<?php settings_fields( 'legal_page_generator_optsgroup_2' ); ?>
		<?php do_settings_sections( 'legal_page_generator_optsgroup_2' ); ?>
		<input type="hidden" name="lpg_saving_settings" value="<?php echo time(); ?>" />
		<table class="form-table">
			<?php foreach ( $this->website_data as $field_name => $field_label ) { ?>
			<tr valign="top">
				<th scope="row"><?php _e( $field_label, 'legal-page-generator' ); ?></th>
				<td>
					<?php $field_v = $this->website_data_validation[$field_name]; ?>
					<input type="<?php echo $field_v['type']; ?>" name="lpg_<?php echo $field_name; ?>" value="<?php echo esc_attr( get_option( 'lpg_' . $field_name, '-' ) ); ?>" placeholder="<?php _e( $field_label, 'legal-page-generator' ); ?>" <?php echo ! isset( $field_v['pattern'] ) ? '' : 'pattern="' . $field_v['pattern'] . '"'; ?> <?php echo ! isset( $field_v['maxlength'] ) ? '' : 'maxlength="' . $field_v['maxlength'] . '"'; ?> <?php echo ! isset( $field_v['length'] ) ? '' : 'length="' . $field_v['length'] . '"'; ?> title="<?php echo $field_v['message']; ?>" required />
				</td>
			</tr>
			<?php } ?>
		</table>
		<?php submit_button( 'Save info & Generate Pages' ); ?>
	</form>
	<p><?php _e( 'Note: If you have previously generated legal pages on this website using this tool, the content of these pages will be updated using your company info and the default page templates.' ); ?></p>
</div>