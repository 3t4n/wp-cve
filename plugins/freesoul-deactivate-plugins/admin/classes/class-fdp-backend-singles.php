<?php
/**
 * Class for the Singles settings.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Class FDP Backend Singles
 *
 * Implemented by backend singles templates.
 *
 * @version  1.0.0
 * @package  Freesoul Deactivate Plugins\Classes
 */
class FDP_Backend_Singles_Page extends Eos_Fdp_Matrix_Page {

	/**
	 * Section ID.
	 *
	 * @var string $section_id Section ID
	 * @since  1.9.0
	 */	
	public $section_id;

	/**
	 * Paged.
	 *
	 * @var bool $paged True if paged
	 * @since  1.9.0
	 */	
	public $paged;

	/**
	 * Paged step.
	 *
	 * @var int $paged_step Paged step
	 * @since  1.9.0
	 */	
	public $paged_step = 5;

	/**
	 * Array of labels.
	 *
	 * @var array $labels Labels
	 * @since  1.9.0
	 */	
	public $labels;

	/**
	 * Output before section.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */
	public function before_section( $page_slug ) {
		$this->section_id = 'eos-dp-by-admin-section';
		$this->paged_step = apply_filters( 'fdp_backend_singles_paged_step', $this->paged_step );
		global $fdp_admin_submenu,$fdp_admin_menu;
		$labels = array();
		foreach ( $fdp_admin_menu as $arr ) {
			$labels[ isset( $arr[5] ) ? $arr[5] : $arr[2] ] = $arr[0];
		}
		$this->labels = $labels;
		$this->paged  = 0;
		if ( $fdp_admin_submenu && is_array( $fdp_admin_submenu ) && ! empty( $fdp_admin_submenu ) ) {
			$n = count( array_keys( $fdp_admin_submenu ) );
			if ( $n > 10 ) {
				$this->paged = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
				$pages       = round( $n / $this->paged_step, 0 );
				?>
		<style id="fdp-backend-singles-css">
		#fdp-pagination{
		  margin-top: 16px;
		  display: inline-block;
		  position: absolute;
				<?php echo is_rtl() ? 'left' : 'right'; ?>: 15px;
		  top: 65px;
		}
		#fdp-pagination .button{position:relative}
		#fdp-pagination .fdp-singles-btn-description{display:none;position:absolute;top:15px;<?php echo is_rtl() ? 'left' : 'right'; ?>:0}
		#fdp-pagination .fdp-singles-btn-description li{color:#253042}
		#fdp-pagination .button:hover .fdp-singles-btn-description{display:block;border:1px solid;z-index:99999;background:#fff;padding:10px;width:300px;width:fit-content}
		</style>
		<div id="fdp-pagination" style="margin-top:16px;z-index:999999">
		<a class="button<?php echo 0 === $this->paged ? ' eos-active' : ''; ?>" href="
								   <?php
									echo esc_url(
										add_query_arg(
											array(
												'page'  => 'eos_dp_admin',
												'paged' => 0,
											),
											admin_url( 'admin.php' )
										)
									);
									?>
						"><?php esc_html_e( 'All', 'freesoul-deactivate-plugins' ); ?></a>
				<?php
				for ( $p = 1;$p <= $pages;++$p ) {
					?>
		<a class="button<?php echo $p === $this->paged ? ' eos-active' : ''; ?>" href="
								   <?php
									echo esc_url(
										add_query_arg(
											array(
												'page'  => 'eos_dp_admin',
												'paged' => $p,
											),
											admin_url( 'admin.php' )
										)
									);
									?>
						">
					  <?php echo esc_html( $p ); ?>
			<ul class="fdp-singles-btn-description">
					  <?php

						$g                        = 0;
						$fdp_admin_submenu_labels = $fdp_admin_submenu;
						$fdp_admin_submenu_labels = array_slice( $fdp_admin_submenu_labels, $this->paged_step * ( $p - 1 ), $this->paged_step );
						foreach ( $fdp_admin_submenu_labels as $fdp_admin_submenu_labels_item ) {
							  $fdp_admin_submenu_labels_item = array_values( $fdp_admin_submenu_labels_item );
							if ( empty( $fdp_admin_submenu_labels_item ) ) {
								continue;
							}
							$keyArr      = $fdp_admin_submenu_labels_item[0];
							$labels_name = preg_replace( '/[0-9]+/', '', isset( $labels[ $keyArr[2] ] ) ? $labels[ $keyArr[2] ] : $keyArr[0] );
							if ( '' !== $labels_name && false === strpos( $keyArr[2], 'eos_dp_' ) ) {
								$labels_name = sprintf( '<strong>%s</strong> (%s)', wp_strip_all_tags( explode( '<', $labels_name )[0] ), $keyArr[2] );
								echo '<li>' . wp_kses( $labels_name, array( 'strong' => array() ) ) . '</li>';
							}
						}
						?>
			</ul>
		  </a>
					<?php
				}
				?>
		</div>
				<?php
			}
		}
	}

	/**
	 * Table body.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */
	public function tableBody( $page_slug ) {
		$all_pages      = ! isset( $_GET['menu_group'] );
		$opts           = eos_dp_get_option( 'eos_dp_general_setts' );
		$active_plugins = eos_dp_active_plugins();
		global $fdp_admin_menu,$fdp_admin_submenu;
		$labels     = $this->labels;
		$adminSetts = eos_dp_get_option( 'eos_dp_admin_setts' );
		$adminTheme = eos_dp_get_option( 'eos_dp_admin_theme' );
		$n          = count( $this->active_plugins );
		wp_nonce_field( 'eos_dp_pro_auto_settings_admin', 'eos_dp_pro_auto_settings_admin' );
		$admin_pages     = array();
		$admin_pages_key = array();
		$printedUrls     = array();
		$groups          = array();
		$one_item        = isset( $_GET['item'] ) ? sanitize_text_field( urlencode( $_GET['item'] ) ) : false; //@codingStandardsIgnoreLine.
		// Sanitization applied after urlencode.
		$row = 1;
		$g   = 0;
		$G   = isset( $_GET['menu_group'] ) ? absint( $_GET['menu_group'] ) : 0;
		if ( $this->paged > 0 ) {
			$fdp_admin_submenu = array_slice( $fdp_admin_submenu, $this->paged_step * ( $this->paged - 1 ), $this->paged_step );
		}
		foreach ( $fdp_admin_submenu as $fdp_admin_submenu_item ) {
			$fdp_admin_submenu_item = array_values( $fdp_admin_submenu_item );
			if ( empty( $fdp_admin_submenu_item ) ) {
				continue;
			}
			$keyArr                  = $fdp_admin_submenu_item[0];
			$key                     = $keyArr[2];
			$admin_pages_key         = array();
			$labels_name             = isset( $labels[ $key ] ) ? preg_replace( '/[0-9]+/', '', wp_strip_all_tags( $labels[ $key ] ) ) : preg_replace( '/[0-9]+/', '', wp_strip_all_tags( $keyArr[0] ) );
			$fdp_admin_menu_item_url = false !== strpos( $keyArr[2], '.php' ) ? admin_url( $keyArr[2] ) : add_query_arg( 'page', $keyArr[2], admin_url( 'admin.php' ) );
			$groups[ $g ]            = $labels_name;
			if ( ( $g < 5 * $G || $g > ( 5 * ( $G + 1 ) - 1 ) ) && ! $all_pages ) {
				++$g;
				continue;
			}
			if (
			false !== strpos( $fdp_admin_menu_item_url, 'eos_dp_' )
			) {
				continue;
			}
			?>
	  <tr style="border-style:none" class="fdp-row-separator"<?php echo $one_item && $one_item === urlencode( $labels_name ) ? ' eos-hidden' : ''; ?>>
		<td style="border-style:none;box-shadow:none">
		  <a class="eos-dp-admin-main-menu-link" href="<?php echo esc_url( $fdp_admin_menu_item_url ); ?>" target="_blank">
			<h4 class="fdp-admin-menu-title"><?php echo esc_html( $labels_name ); ?></h4>
		  </a>
		</td>
		<td style="border-style:none;box-shadow:none" colspan="<?php echo count( $this->active_plugins ) - 1; ?>"></td>
	  </tr>
			<?php
			foreach ( $fdp_admin_submenu_item as $fdp_admin_menu_item ) {
				$active                 = $is_edit_post = false;
				$fdp_admin_menu_item[2] = str_replace( '&amp;', '&', $fdp_admin_menu_item[2] );
				if ( false === filter_var( $fdp_admin_menu_item[2], FILTER_VALIDATE_URL ) ) {
					$fdp_admin_menu_item_url = false !== strpos( $fdp_admin_menu_item[2], '.php' ) ? admin_url( $fdp_admin_menu_item[2] ) : add_query_arg( 'page', $fdp_admin_menu_item[2], admin_url( 'admin.php' ) );
				} else {
					$fdp_admin_menu_item_url = $fdp_admin_menu_item[2];
				}
				$values = isset( $adminSetts[ $fdp_admin_menu_item[2] ] ) ? explode( ',', $adminSetts[ $fdp_admin_menu_item[2] ] ) : array_fill( 0, count( $this->active_plugins ), ',' );
				if ( '' !== $labels_name && false === strpos( $fdp_admin_menu_item[2], 'eos_dp' ) ) {
					$title      = wp_strip_all_tags( reset( $fdp_admin_menu_item ) );
					$title      = preg_replace( '!\d+!', '', $title );
					$admin_page = array(
						'title' => $title,
						'page'  => $fdp_admin_menu_item[2],
						'url'   => $fdp_admin_menu_item_url,
					);
					if ( '' === $title ) {
						$tite = ucwords( str_replace( '-', ' ', $fdp_admin_menu_item[2] ) );
					}
					$admin_pages_key[] = $admin_page;
					$admin_page_key    = sanitize_key(
						str_replace(
							'.',
							'-',
							str_replace(
								'admin.php?page=',
								'eos_dp_tlp-',
								str_replace( admin_url(), '', $fdp_admin_menu_item_url )
							)
						)
					);
					$args              = array(
						'eos_dp_debug'   => 'no_errors',
						'admin_page_key' => $admin_page_key,
						'eos_dp_pro_id'  => md5( EOS_DP_PRO_TESTING_UNIQUE_ID ),
						'test_id'        => time(),
					);

					$url   = str_replace( '&amp;', '&', add_query_arg( $args, wp_nonce_url( $fdp_admin_menu_item_url, 'eos_dp_preview', 'eos_dp_preview' ) ) );
					$rowsN = 1;
					if ( false !== strpos( $fdp_admin_menu_item_url, 'edit.php' ) && ( false !== strpos( $fdp_admin_menu_item_url, 'post_type=' ) || $fdp_admin_menu_item_url === admin_url( 'edit.php' ) ) ) {
						$rowsN = 2;
					}
					for ( $nrows = 0;$nrows < $rowsN;++$nrows ) {
						if ( 1 === $nrows ) {
							$postTypeArr = explode( 'post_type=', $fdp_admin_menu_item_url );
							if ( isset( $postTypeArr[1] ) || $fdp_admin_menu_item_url === admin_url( 'edit.php' ) ) {
								if ( isset( $postTypeArr[1] ) ) {
									$postTypeArr = explode( '&', $postTypeArr[1] );
									$post_key    = $postTypeArr[0];
									$post_type   = get_post_type_object( sanitize_key( $post_key ) );
								} elseif ( $fdp_admin_menu_item_url === admin_url( 'edit.php' ) ) {
									$post_type = get_post_type_object( 'post' );
									$post_key  = 'post';
								}
								if ( $post_type && isset( $post_type->labels ) && isset( $post_type->labels->singular_name ) ) {
									$labels_name             = $title = sprintf( esc_attr__( 'Edit Single %s', 'freesoul-deactivate-plugins' ), $post_type->labels->singular_name );
									$fdp_admin_menu_item[2]  = $admin_page_key = 'single_' . sanitize_key( $post_key );
									$is_edit_post            = true;
									$fdp_admin_menu_item_url = false;
									$values                  = isset( $adminSetts[ $admin_page_key ] ) ? explode( ',', $adminSetts[ $admin_page_key ] ) : array_fill( 0, count( $this->active_plugins ), ',' );
								} else {
									continue;
								}
							} else {
								continue;
							}
						}
						if ( $title ) {
							?>
			<tr class="eos-dp-admin-row eos-dp-post-row
							<?php
							echo $is_edit_post ? ' fdp-edit-single' : '';
							$fdp_admin_menu_item_url && '' !== $fdp_admin_menu_item_url && in_array( $fdp_admin_menu_item_url, $printedUrls ) ? ' eos-dp-duplicated-url' : '';
							echo $one_item && $one_item !== urlencode( $labels_name ) ? ' eos-hidden' : '';
							echo false !== strpos( $fdp_admin_menu_item_url, 'plugins.php' ) ? ' eos-dp-not-active eos-dp-not-allowed' : '';
							?>
							" data-admin="<?php echo esc_attr( $fdp_admin_menu_item[2] ); ?>">
			  <td class="eos-dp-post-name-wrp">
				<span class="fdp-row-actions-ico dashicons dashicons-plus" title="<?php esc_attr__( 'Action buttons', 'freesoul-deactivate-plugins' ); ?>"></span>
				<span class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate all plugins in %s', 'freesoul-deactivate-plugins' ), esc_attr( $labels_name ) ); ?>" data-row="<?php echo esc_attr( $row ); ?>" class="eos-dp-global-chk-row" type="checkbox" /></span>
				<a class="eos-dp-title" href="<?php echo esc_url( $fdp_admin_menu_item_url ); ?>" target="_blank"><?php echo esc_html( $title ); ?></a>
				<div class="eos-dp-actions">
							<?php if ( $fdp_admin_menu_item_url ) { ?>
				  <a class="eos-dp-view fdp-has-tooltip" href="<?php echo esc_url( $fdp_admin_menu_item_url ); ?>" target="_blank">
					<span class="dashicons dashicons-visibility"></span>
					<div class="fdp-tooltip"><?php esc_html_e( 'View page loading plugins according the saved options', 'freesoul-deactivate-plugins' ); ?></div>
				  </a>
				  <a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( $url ); ?>" target="_blank">
					<span class="dashicons dashicons-search"></span>
					<div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins according the settings you see now on this row', 'freesoul-deactivate-plugins' ); ?></div>
				  </a>
				  <a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( add_query_arg( 'js', 'off', $url ) ); ?>" target="_blank">
					<span class="dashicons dashicons-search">
					  <span class="eos-dp-no-js">JS</span>
					</span>
					<div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins and the theme according the settings you see now on this row and disable JavaScript esecution', 'freesoul-deactivate-plugins' ); ?></div>
				  </a>
								<?php do_action( 'eos_dp_action_buttons' ); ?>
				  <a href="#" class="eos-dp-pro-autosettings fdp-has-tooltip">
					<span class="dashicons dashicons-plugins-checked"></span>
					<div class="fdp-tooltip"><?php esc_html_e( 'Suggest unused plugins', 'freesoul-deactivate-plugins' ); ?></div>
				  </a>
								<?php do_action( 'eos_dp_backend_actions' ); ?>
				  <?php } ?>
				  <a class="eos-dp-invert-selection fdp-has-tooltip" href="#"><span class="dashicons"><span style="display:inline-block"><span class="fdp-invert-up"></span><span class="fdp-invert-down"></span></span></span>
					<div class="fdp-tooltip"><?php esc_html_e( 'Invert selection', 'freesoul-deactivate-plugins' ); ?></div>
				  </a>
				  <a class="eos-dp-copy fdp-has-tooltip" href="#"><span class="dashicons dashicons-admin-page"></span>
					<div class="fdp-tooltip"><?php esc_html_e( 'Copy this row settings', 'freesoul-deactivate-plugins' ); ?></div>
				  </a>
				  <a class="eos-dp-paste fdp-has-tooltip" href="#"><span class="dashicons dashicons-category"></span>
					<div class="fdp-tooltip"><?php esc_html_e( 'Paste last copied row settings', 'freesoul-deactivate-plugins' ); ?></div>
				  </a>
							<?php do_action( 'eos_dp_archive_action_buttons' ); ?>
				</div>

			  </td>
							<?php
							$printedUrls[] = $fdp_admin_menu_item_url;
							$k             = 0;
							foreach ( $this->active_plugins as $plugin ) {
								if ( in_array( $plugin, array_keys( $this->plugins_by_dirs ) ) ) {
									$extra_class = $plugin === EOS_DP_PLUGIN_BASE_NAME ? ' eos-hidden' : '';
									?>
				  <td class="center
									<?php
									echo ! in_array( $plugin, $values ) ? ' eos-dp-active' : '';
									echo esc_attr( $extra_class );
									?>
	" data-path="<?php echo esc_attr( $plugin ); ?>">
					<div class="eos-dp-td-chk-wrp eos-dp-td-admin-chk-wrp">
					  <input class="eos-dp-row-<?php echo esc_attr( $row ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ) . '-' . esc_attr( $fdp_admin_menu_item[2] ); ?>" data-checked="<?php echo in_array( $plugin, $values ) ? 'checked' : 'not-checked'; ?>" type="checkbox"<?php echo in_array( $plugin, $values ) ? ' checked' : ''; ?> />
					</div>
				  </td>
									<?php
									++$k;
								}
							}
							?>
			  <td class="center<?php echo ! isset( $adminTheme[ $fdp_admin_menu_item[2] ] ) || $adminTheme[ $fdp_admin_menu_item[2] ] ? ' eos-dp-active' : ''; ?>">
				<div class="eos-dp-td-chk-wrp eos-dp-td-admin-chk-wrp">
				  <input class="eos-dp-row-theme eos-dp-col-<?php echo esc_attr( $k + 1 ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ) . '-' . esc_attr( $fdp_admin_menu_item[2] ); ?>" data-checked="checked" type="checkbox" checked />
				</div>
			  </td>
			</tr>
							<?php
						}
					}
					$admin_pages[ $key ] = array( 'title' => esc_attr( $labels_name ) );
				}
				++$row;
			}
			++$g;
		}
		wp_add_inline_script( 'eos-dp-backend', 'var eos_dp_admin_pages = ' . str_replace( '&quot;', '"', wp_json_encode( $admin_pages ) ), 'before' );
	}

	/**
	 * Output legend.
	 *
	 * @since  1.9.0
	 */
	public function legend() {
		if ( defined( 'FDP_PRO_ACTIVE' ) && FDP_PRO_ACTIVE ) {
			return;
		}
		?>
	  <div style="margin-top:64px">
		  <span id="eos-dp-stop-process" class="eos-dp-not-active button" title="<?php esc_attr_e( 'Stop auto-suggestion', 'freesoul-deactivate-plugins' ); ?>"><?php esc_html_e( 'Stop auto-suggestion', 'freesoul-deactivate-plugins' ); ?></span>
	  </div>
		<?php
	}
}
