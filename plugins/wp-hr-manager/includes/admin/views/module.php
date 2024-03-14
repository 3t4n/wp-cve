<?php

$tab                = isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : false;
$modules            = wphr()->modules->get_query_modules( $tab );
$all_active_modules = wphr()->modules->get_active_modules();
$count_all          = count( wphr()->modules->get_modules() );
$count_active       = count( $all_active_modules );
$count_inactive     = count( wphr()->modules->get_inactive_modules() );

$all_url            = admin_url( 'admin.php/?page=wphr-modules' );
$active_url         = admin_url( 'admin.php/?page=wphr-modules&tab=active' );
$inactive_url       = admin_url( 'admin.php/?page=wphr-modules&tab=inactive' );

$current_tab        = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : ''
?>
<div class="wrap wphr-settings">
	<h2><?php _e( 'Modules', 'wphr' ); ?></h2>
	<ul class="wphr-subsubsub">
		<li><a class="wphr-nav-tab<?php echo $current_tab == '' ? ' wphr-nav-tab-active' : ''; ?>" href="<?php echo $all_url; ?>"><?php printf( __( 'All (%s) |', 'wphr' ), $count_all ); ?></a></li>
		<li><a class="wphr-nav-tab<?php echo $current_tab == 'active' ? ' wphr-nav-tab-active' : ''; ?>" href="<?php echo $active_url; ?>"><?php printf( __( 'Active (%s) |', 'wphr' ), $count_active ); ?></a></li>
		<li><a class="wphr-nav-tab<?php echo $current_tab == 'inactive' ? ' wphr-nav-tab-active' : ''; ?>" href="<?php echo $inactive_url; ?>"><?php printf( __( 'Inactive (%s)', 'wphr' ), $count_inactive  ); ?></a></li>
	</ul>


	<form method="post">
	<table class="widefat fixed plugins" cellspacing="0">
		<thead>
			<tr>
				<td scope="col" id="cb" class="manage-column column-cb check-column">&nbsp;</td>
				<th scope="col" id="name" class="manage-column column-name" style="width: 190px;"><?php _e( 'Title', 'wphr' ); ?></th>
				<th scope="col" id="description" class="manage-column column-description"><?php _e( 'Description', 'wphr' ); ?></th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td scope="col" class="manage-column column-cb check-column">&nbsp;</td>
				<th scope="col" class="manage-column column-name" style="width: 190px;"><?php _e( 'Title', 'wphr' ); ?></th>
				<th scope="col" class="manage-column column-description"><?php _e( 'Description', 'wphr' ); ?></th>
			</tr>
		</tfoot>

		<tbody id="the-list">

		<?php

			foreach ( $modules as $slug => $module ) {
				$checked = array_key_exists( $slug, $all_active_modules ) ? $slug : '';
				?>
				<tr class="active">
					<th scope="row">
						<input type="checkbox" name="modules[]" id="wphr_module_<?php echo $slug; ?>" value="<?php echo $slug; ?>" <?php checked( $slug, $checked ); ?>>
					</th>
					<td class="plugin-title" style="width: 190px;">
						<label for="wphr_module_<?php echo $slug; ?>">
							<strong><?php echo isset( $module['title'] ) ? $module['title'] : ''; ?></strong>
						</label>
					</td>
					<td class="column-description desc">
						<div class="plugin-description">
							<p><?php echo isset( $module['description'] ) ? $module['description'] : ''; ?></p>
						</div>
					</td>
				</tr>
				<?php
			}

			if ( ! $modules  ) {
				?>
				<tr class="active">
					<td colspan="3" class="column-description desc">
						<?php _e( 'No modules found!', 'wphr' ); ?>
					</td>
				</tr>
				<?php
			}
		?>

		</tbody>
	</table>
	<p class="submit clear">
		<?php wp_nonce_field( 'wphr_nonce', 'wphr_settings' ); ?>
		<input class="button-primary" type="submit" name="wphr_module_status"  value="Save Settings">
	</p>
	</form>

</div>
