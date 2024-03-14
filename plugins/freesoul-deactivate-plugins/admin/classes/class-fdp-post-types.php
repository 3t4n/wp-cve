<?php
/**
 * Class for the Post Types.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

class FDP_Post_Types_Page extends Eos_Fdp_Matrix_Page {

	private $old_method = false;

	public function before_section( $page_slug ) {

	}

	public function tableBody( $page_slug ) {
		$plugins_table    = apply_filters( 'eos_dp_plugins_table', eos_dp_plugins_table() );
		$this->old_method = apply_filters( 'fdp_old_method_padlock', false );
		foreach ( $plugins_table as $pt => $arr ) {
			if ( ! isset( $arr[0] ) || 1 !== $arr[0] ) {
				$obj = get_post_type_object( $pt );
				if ( 'page' === $pt || ( 'attachment' !== $pt && isset( $obj->labels ) && isset( $obj->labels->name ) && isset( $obj->publicly_queryable ) && $obj->publicly_queryable ) ) {
					$this->old_method = true;
					break;
				}
			}
		}
		$n             = count( $this->active_plugins );
		$values_string = '';
		$values        = explode( ',', $values_string );
		$row           = 0;
		foreach ( $plugins_table as $post_type => $plugins ) {
			if ( ! in_array( $post_type, array( 'attachment' ) ) ) {
				$active  = false;
				$labsObj = get_post_type_object( $post_type );
				if ( 'page' === $post_type || ( isset( $labsObj->labels ) && isset( $labsObj->publicly_queryable ) && $labsObj->publicly_queryable ) ) {
					$labs      = $labsObj->labels;
					$labs_name = isset( $labs->name ) ? $labs->name : false;
					$singles   = add_query_arg( 'eos_dp_post_type', $post_type, admin_url( 'admin.php?page=eos_dp_menu' ) );
					?>
		<tr class="eos-dp-post-type eos-dp-post-row
					<?php
					echo isset( $_GET['eos_dp_post_type'] ) && $post_type === $_GET['eos_dp_post_type'] ? ' fdp-from-single-page' : '';
					echo 0 === $row ? ' fdp-row-1' : '';
					?>
					" data-post-type="<?php echo esc_attr( $post_type ); ?>" data-row_id="<?php echo esc_attr( $post_type ); ?>">
		  <td class="eos-dp-post-name-wrp">
			<span class="fdp-row-actions-ico dashicons dashicons-plus" title="<?php esc_attr__( 'Action buttons', 'freesoul-deactivate-plugins' ); ?>"></span>
			<span class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate all plugins in %s', 'freesoul-deactivate-plugins' ), esc_attr( $labs_name ) ); ?>" class="eos-dp-global-chk-row" type="checkbox" /></span>
			<span class="eos-dp-not-active-wrp<?php echo ! isset( $plugins[0] ) || $plugins[0] === 1 || false === $this->old_method ? ' eos-dp-priority-active' : ''; ?> eos-dp-priority-post-type-wrp"<?php echo ! $this->old_method ? 'style="display:none !important"' : ''; ?>><input title="<?php printf( esc_attr__( 'If activated the Single %s Settings will be ignored.', 'freesoul-deactivate-plugins' ), esc_attr( $labs_name ) ); ?>" class="eos-dp-priority-post-type" type="checkbox" /></span>
			<span class="eos-dp-title"><a style="color:inherit;text-decoration:none" href="<?php echo esc_url( $singles ); ?>"><?php echo esc_html( $labs_name ); ?></a></span>
			<span class="<?php echo isset( $plugins[2] ) && $plugins[2] == '1' ? 'eos-dp-default-active' : ''; ?> eos-dp-default-post-type-wrp">
			  <span class="eos-dp-default-chk-wrp">
				<input title="<?php printf( esc_attr__( 'If activated the Single %s Settings will have this row settings as default.', 'freesoul-deactivate-plugins' ), esc_attr( $labs_name ) ); ?>" class="eos-dp-default-post-type" type="checkbox"<?php echo isset( $plugins[2] ) && $plugins[2] == '1' ? ' checked' : ''; ?>/>
				<span></span>
			  </span>
			</span>
			<span class="eos-dp-x-space"></span>
			<div class="eos-dp-actions">
			  <a class="eos-dp-to-singles fdp-has-tooltip" style="padding:0 10px;border:1px solid #fff !important" href="<?php echo esc_url( add_query_arg( 'eos_dp_post_type', $post_type, admin_url( 'admin.php?page=eos_dp_menu' ) ) ); ?>"><span class="dashicons dashicons-admin-generic"></span><span style="position:relative;top:6px"><?php esc_html_e( 'Singles', 'freesoul-deactivate-plugins' ); ?></span>
				<div class="fdp-tooltip"><?php printf( esc_html__( 'Go to singles settings of %s', 'freesoul-deactivate-plugins' ), esc_html( $labs_name ) ); ?></div>
			  </a>
			  <a class="eos-dp-invert-selection fdp-has-tooltip" href="#"><span class="dashicons"><span style="display:inline-block"><span class="fdp-invert-up"></span><span class="fdp-invert-down"></span></span></span>
				<div class="fdp-tooltip"><?php esc_html_e( 'Invert selection', 'freesoul-deactivate-plugins' ); ?></div>
			  </a>
			  <a class="eos-dp-copy fdp-has-tooltip" href="#"><span class="dashicons dashicons-admin-page"></span>
				<div class="fdp-tooltip"><?php esc_html_e( 'Copy this row settings', 'freesoul-deactivate-plugins' ); ?></div>
				<div class="fdp-action-msg fdp-msg-success" style="opacity:0;position:absolute;top:34px;background:#fff;padding:10px;transition:opacity 0.5s linear"><?php esc_html_e( 'Row settings copied', 'freesoul-deactivate-plugins' ); ?></div>
				<div class="fdp-action-msg fdp-msg-error" style="opacity:0;display:none;position:absolute;top:34px;background:#fff;padding:10px;transition:opacity 0.5s linear"><?php esc_html_e( 'It was not possible to copy the row settings', 'freesoul-deactivate-plugins' ); ?></div>
			  </a>
			  <a class="eos-dp-paste fdp-has-tooltip" href="#"><span class="dashicons dashicons-category"></span>
				<div class="fdp-tooltip"><?php esc_html_e( 'Paste last copied row settings', 'freesoul-deactivate-plugins' ); ?></div>
			  </a>
					  <?php do_action( 'eos_dp_action_buttons' ); ?>
			</div>
		  </td>
					  <?php
						$k = 0;
						foreach ( $this->active_plugins as $plugin ) {
								$active = ! isset( $plugins[1] ) || ! in_array( $plugin, explode( ',', $plugins[1] ) ) ? true : false;
							if ( in_array( $plugin, array_keys( $this->plugins_by_dirs ) ) ) {
								?>
		  <td class="center<?php echo $active ? ' eos-dp-active' : ''; ?>" data-path="<?php echo esc_attr( $plugin ); ?>" >
			<div class="eos-dp-td-chk-wrp eos-dp-td-post-type-chk-wrp">
			  <input class="eos-dp-row-<?php echo esc_attr( $row ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ) . '-' . esc_attr( $post_type ); ?>" type="checkbox"<?php echo $active ? ' checked' : ''; ?> />
			</div>
		  </td>
								<?php
								++$k;
							}
						}
						?>
		</tr>
					<?php
				}
			}
			++$row;
		}
	}

	public function action_buttons( $page_slug ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-action-buttons.php';
	}

	public function legend() {
		?>
	<div id="eos-dp-priority-legend">
		<?php if ( $this->old_method ) { ?>
		<span class="eos-dp-priority-legend-wrp eos-dp-priority-active">
			<input class="eos-dp-priority-post-type" type="checkbox" />
		</span>
		<a class="eos-dp-no-decoration fdp-has-tooltip" href="#">
			<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
			<p class="fdp-tooltip" style="width:max-content">
			<?php
			esc_html_e( 'Overrides inactive rows in the Singles Settings', 'freesoul-deactivate-plugins' );
			?>
 </p>
		</a>
		<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<span class="eos-dp-priority-legend-wrp">
			<input style="pointer-events:none" class="eos-dp-priority-post-type" type="checkbox" />
		</span>
		<a class="eos-dp-no-decoration fdp-has-tooltip" href="#">
			<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
			<p class="fdp-tooltip" style="width:max-content">
			<?php
			esc_html_e( 'Singles Settings will override the Post Types Settings', 'freesoul-deactivate-plugins' );
			?>
 </p>
		</a>
		<div style="height:32px"></div>
		<?php } ?>
		<span class="eos-dp-default-legend-wrp eos-dp-default-active">
			<span class="eos-dp-default-active eos-dp-default-post-type-wrp">
				<span class="eos-dp-default-chk-wrp">
					<input style="pointer-events:none" checked title="<?php esc_html_e( 'Set as default on new posts.', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-default-post-type-checked eos-dp-default-post-type" type="checkbox" />
					<span></span>
				</span>
		</span>
		<a class="eos-dp-no-decoration fdp-has-tooltip" href="#">
			<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
			<p class="fdp-tooltip" style="width:max-content"><?php esc_html_e( 'Set as default on new posts.', 'freesoul-deactivate-plugins' ); ?></p>
		</a>
		<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<span class="eos-dp-default-legend-wrp">
				<span class="eos-dp-default-active eos-dp-default-post-type-wrp">
					<span class="eos-dp-default-chk-wrp">
						<input style="pointer-events:none" title="<?php esc_html_e( 'Do not set as default on new posts.', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-default-post-type" type="checkbox" />
						<span></span>
					</span>
				</span>
			</span>
			<a class="eos-dp-no-decoration fdp-has-tooltip" href="#">
				<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
				<p class="fdp-tooltip" style="width:max-content"><?php esc_html_e( 'Do not set as default on new posts.', 'freesoul-deactivate-plugins' ); ?></p>
			</a>
	</div>
		<?php
	}
}
