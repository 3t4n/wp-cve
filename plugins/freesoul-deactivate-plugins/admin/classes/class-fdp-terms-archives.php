<?php
/**
 * Class for the Terms Archives.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

class FDP_Terms_Archives_Page extends Eos_Fdp_Matrix_Page {
	public $default_languae;
	public $current_tax;
	public $tax;
	public $tax_slug;
	public $terms;
	public $permalink_structure;
	public $home_url;
	public $archiveSetts;
	public $option_size;
	public $skip_db;
	public $default_language;
	public $all_archives_name;


	public function init() {
		$opts                      = eos_dp_get_option( 'eos_dp_opts' );
		$this->skip_db             = ( isset( $opts['skip_db_for_archives'] ) && 'true' === $opts['skip_db_for_archives'] ) || ( defined( 'FDP_SKIP_DB_FOR_ARCHIVES' ) && FDP_SKIP_DB_FOR_ARCHIVES );
		$this->section_id          = 'eos-dp-by-archive-section';
		$this->default_language    = eos_dp_default_language();
		$this->current_tax         = isset( $_GET['eos_dp_tax'] ) ? sanitize_key( $_GET['eos_dp_tax'] ) : 'category';
		$this->tax                 = get_taxonomy( $this->current_tax );
		$this->terms               = get_terms(
			array(
				'taxonomy'   => $this->tax->name,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);
		$this->permalink_structure = eos_dp_get_option( 'permalink_structure' );
		$this->home_url            = get_option( 'home' );
		$this->archiveSetts        = eos_dp_get_option( 'eos_dp_archives' );
		$this->option_size         = eos_dp_get_option_size( 'eos_dp_archives' );
		if ( $this->option_size > 120 ) {
			add_action(
				'fdp_top_bar_notifications',
				function() {
					$msg  = '<p>' . esc_html__( 'The option Archives and Terms Archives is becoming too big and they may worsening the performance. Maybe better you move this option from the database to the filesystem.', 'freesoul-deactivate-plugins' ) . '</p>';
					$msg .= '<p>' . wp_kses_post( sprintf( __( 'If you want to do it go to %1$sExperiments%2$s', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( add_query_arg( 'page', 'eos_dp_experiments', admin_url( 'admin.php' ) ) ) . '" title="' . esc_attr__( 'Experiments', 'freesoul-deactivate-plugins' ) . '">', '</a>' ) ) . '</p>';
					eos_dp_display_admin_notice( 'eos_dp_archives', esc_html__( 'Option Archives and Terms Archives too big.', 'freesoul-deactivate-plugins' ), wp_kses_post( $msg ), 'warning' );
				}
			);
		}
	}

	public function before_section( $page_slug ) {
		wp_nonce_field( 'eos_dp_key', 'eos_dp_key' );
		wp_nonce_field( 'eos_dp_pro_gpsi_test', 'eos_dp_pro_gpsi_test' );
		wp_nonce_field( 'eos_dp_pro_gt_metrix_test', 'eos_dp_pro_gt_metrix_test' );
		if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ) {
			wp_nonce_field( 'eos_dp_pro_auto_settings', 'eos_dp_pro_auto_settings' );
		}
		if ( defined( 'ICL_SITEPRESS_VERSION' ) || function_exists( 'pll_languages_list' ) ) {
			echo wp_kses_post( eos_dp_wpml_switcher() );
		}
		?>
	<style id="fdp-archives-css">
	.fdp-all-archives-row .eos-dp-title{
	  text-transform:uppercase;
	  font-weight:bold
	}
	.fdp-all-archives-row{
	  position:relative
	}
	.fdp-all-archives-row:after {
	  content: " ";
	  position: absolute;
	  bottom: 0;
	  height: 3px;
	  background-color: #a28754;
	  left: 0;
	  right: 0;
	  z-index: 9;
	}
	</style>
	<p><?php printf( esc_html__( 'If you need to disable the same plugins on all archives, use the row All %s', 'freesoul-deactivate-plugins' ), esc_html( $this->tax->labels->name ) ); ?></p>
		<?php
		if ( false === strpos( $this->permalink_structure, '%category%' ) && '.' === eos_dp_get_option( 'category_base' ) ) {
			?>
	  <div id="eos-dp-plain-permalink-wrg" style="line-height:1;margin:20px 0;padding:10px;color:#23282d;background:#fff;border-<?php echo is_rtl() ? 'right' : 'left'; ?>:4px solid  #dc3232">
		  <div>
			  <h1><?php echo wp_kses_post( sprintf( __( "Issue detected. In the %1\$spermalinks settings%2\$s you have a full stop as base category, and you don't have %3\$s in your custom permalink structure.", 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '" target="_blank">', '</a>', '%category%' ) ); ?></h1>
			  <h1><?php esc_html_e( 'The plugins control will probably not work for term archives.', 'freesoul-deactivate-plugins' ); ?></h1>
			  <h1><?php echo wp_kses_post( sprintf( __( 'If you really want these permalink settings, you need %1$sthe custom URLs%2$s for the term archives.', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( admin_url( 'admin.php?page=eos_dp_url' ) ) . '" target="_blank">', '</a>' ) ); ?></h1>
		  </div>
		  <div>
			  <a class="button" target="_blank" href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>"><?php esc_html_e( 'Check Permalinks Structure', 'freesoul-deactivate-plugins' ); ?></a>
		  </div>
	  </div>
			<?php
		}
	}
	public function tableBody( $page_slug ) {
		$row = 1;
		if ( $this->tax && isset( $this->tax->labels ) ) {
			$tax_labels = $this->tax->labels;
			?>
	  <h2 id="eos-dp-terms-archives-title"><?php echo esc_html( $tax_labels->name ); ?></h2>
			<?php do_action( 'eos_dp_after_terms_archive_title' ); ?>
			<?php
		}
		if ( ! empty( $this->terms ) ) {
			$all_terms = $this->terms;
			if ( count( $all_terms ) > 30 ) {
				$letters = array();
				$filters = '<div id="fdp-filters-letters">';
				$get     = $_GET;
				if ( isset( $get['l'] ) ) {
					unset( $get['l'] );
				}
				$get      = array_map( 'sanitize_text_field', $get );
				$filters .= '<a class="fdp-filter-letter button" href="' . esc_url( add_query_arg( $get, admin_url( 'admin.php' ) ) ) . '">' . esc_attr__( 'All', 'freesoul-deactivate-plugins' ) . '</a>';
				foreach ( $all_terms as $term ) {
					$l = esc_attr( strtolower( mb_substr( $term->name, 0, 1 ) ) );
					if ( ! in_array( $l, $letters ) ) {
						$letters[] = $l;
						$filters  .= '<a class="fdp-filter-letter button" href="' . esc_url( add_query_arg( array_merge( array( 'l' => $l ), $get ), admin_url( 'admin.php' ) ) ) . '">' . esc_attr( ucfirst( $l ) ) . '</a>';
					}
				}
				$filters .= '<p style="margin:0"><small>' . esc_html__( 'Filter by letter', 'freesoul-deactivate-plugins' ) . '</small></p></div>';
				echo $filters; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied while building $filters.
			}
			$all_archives             = new stdClass();
			$all_archives->term_id    = 0;
			$all_archives->taxonomy   = $this->tax->name;
			$this->tax_slug                 = isset( $this->tax->rewrite ) && isset( $this->tax->rewrite['slug'] ) ? $this->tax->rewrite['slug'] : $this->tax->name;
			$all_archives->slug       = $this->is_missing_base() ? false : 'all_archives_' . $this->tax_slug;
			$this->all_archives_name  = sprintf( esc_attr__( 'All %s', 'freesoul-deactivate-plugins' ), $this->tax->labels->name );
			array_unshift( $all_terms, $all_archives );
			foreach ( $all_terms as $term ) {
				$active      = false;
				$labels_name = isset( $term->name ) ? $term->name : 'unkown';
				if ( isset( $_GET['l'] ) && strtolower( sanitize_text_field( $_GET['l'] ) ) !== strtolower( mb_substr( $labels_name, 0, 1 ) ) && 'all' !== strtolower( sanitize_text_field( $_GET['l'] ) ) ) {
					--$row;
					continue;
				}
				$archive_url = get_term_link( $term );
				$flag        = '';
				$loc         = false;
				if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
					$loc = apply_filters(
						'wpml_element_language_code',
						null,
						array(
							'element_id'   => $term->term_id,
							'element_type' => $term->taxonomy,
						)
					);
					if ( $loc && '' !== $loc ) {
						  $flag = defined( 'ICL_PLUGIN_URL' ) && defined( 'WPML_PLUGIN_PATH' ) && file_exists( WPML_PLUGIN_PATH . '/res/flags/' . $loc . '.png' ) ? ' <img src="' . esc_url( ICL_PLUGIN_URL . '/res/flags/' . $loc . '.png' ) . '" />' : esc_html( strtoupper( $loc ) );
					}
				}
				if ( function_exists( 'pll_get_term_language' ) ) {
					$loc = pll_get_term_language( $term->term_id );
					if ( $loc && '' !== $loc ) {
						$flag = defined( 'POLYLANG_FILE' ) && defined( 'POLYLANG_DIR' ) && file_exists( POLYLANG_DIR . '/flags/' . str_replace( 'en.png', 'england.png', $loc . '.png' ) ) ? ' <img src="' . esc_url( plugins_url( '/flags/' . str_replace( 'en.png', 'england.png', $loc . '.png' ), POLYLANG_FILE ) ) . '" />' : esc_html( strtoupper( $loc ) );
					}
				}
				if ( $loc && $loc === $this->default_language ) {
					$archive_urlA = explode( '?', $archive_url );
					$archive_url  = $archive_urlA[0];
				}
				if ( ! is_wp_error( $archive_url ) ) {
					$kArr = explode( '//', $archive_url );
					if ( isset( $kArr[1] ) ) {
						$key = $kArr[1];
					}
					$key                       = 1 === $row ? sanitize_key( str_replace( '/', '__', $all_archives->slug ) ) : sanitize_key( str_replace( '/', '__', rtrim( $key, '/' ) ) );
					
					$this->permalink_structure = eos_dp_get_option( 'permalink_structure' );
					if ( false !== strpos( $this->permalink_structure, '%category%' ) && '%postname%' === basename( $this->permalink_structure ) && '.' === eos_dp_get_option( 'category_base' ) ) {
						$key = str_replace( '__.__', '__', $key );

					}
					if ( ! $this->skip_db ) {
						$values = isset( $this->archiveSetts[ $key ] ) ? explode( ',', $this->archiveSetts[ $key ] ) : array_fill( 0, count( $this->active_plugins ), ',' );
					} else {
						$opts_by_path = eos_dp_get_opts_by_url( esc_attr( str_replace( $this->home_url, '', $archive_url ) ) );
						if ( isset( $opts_by_path['post_id'] ) && 'archive' === $opts_by_path['post_id'] ) {
							$values = isset( $opts_by_path['plugins'] ) ? explode( ',', $opts_by_path['plugins'] ) : array_fill( 0, count( $this->active_plugins ), ',' );
						}
					}
					if ( ! isset( $values ) ) {
						$values = array_fill( 0, count( $this->active_plugins ), ',' );
					}
					if( $row > 1 && isset( $_GET['only-all'] ) && '1' === sanitize_text_field( $_GET['only-all'] ) ) break;
					?>
		  <tr class="eos-dp-archive-row eos-dp-post-row<?php
					echo 1 === $row && ! $all_archives->slug ? ' fdp-all-archvies-missing-base' : '';
					echo 1 === $row && ( ! isset( $_GET['l'] ) || 'all' === $_GET['l'] ) ? ' fdp-all-archives-row' : '';
					echo 1 === $row && defined( 'FDP_SKIP_DB_FOR_ARCHIVES' ) && FDP_SKIP_DB_FOR_ARCHIVES && ( ! isset( $_GET['l'] ) || 'all' === $_GET['l'] ) ? ' eos-no-events' : '';
					?>" data-url="<?php echo esc_attr( str_replace( $this->home_url, '', $archive_url ) ); ?>" data-post-type="<?php echo isset( $term->name ) ? esc_attr( $term->name ) : 'unkown'; ?>" data-tax="<?php echo esc_attr( $this->tax->name ); ?>" data-href="<?php echo 1 === $row && ( ! isset( $_GET['l'] ) || 'all' === $_GET['l'] ) ? esc_attr( $all_archives->slug ) : esc_url( $archive_url ); ?>">
			<td class="eos-dp-post-name-wrp">
			  <span class="fdp-row-actions-ico dashicons dashicons-plus" title="<?php esc_attr__( 'Action buttons', 'freesoul-deactivate-plugins' ); ?>"></span>
			  <span class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate all plugins in %s', 'freesoul-deactivate-plugins' ), esc_attr( $labels_name ) ); ?>" data-row="<?php echo esc_attr( $row ); ?>" class="eos-dp-global-chk-row" type="checkbox" /></span>
			  <span class="eos-dp-title"><?php
					echo 1 === $row && ( ! isset( $_GET['l'] ) || 'all' === $_GET['l'] ) ? esc_html( $this->all_archives_name ) : sprintf( esc_html__( '%s Archive', 'freesoul-deactivate-plugins' ), esc_html( $labels_name ) );
					echo $flag; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied while building $flag. ?>
					</span>
			  <div class="eos-dp-actions">
					<?php
					eos_dp_debug_button( $archive_url );
					if ( isset( $this->tax->object_type ) ) {
						$post_types  = $this->tax->object_type;
						$singles_url = admin_url(
							add_query_arg(
								array(
									'page'             => 'eos_dp_menu',
									'tax_name'         => esc_attr( $this->tax->name ),
									'term_slug'        => esc_attr( $term->slug ),
									'eos_dp_post_type' => esc_attr( $post_types[0] ),
								),
								'admin.php'
							)
						);
						?>
				<a class="fdp-has-tooltip fdp-right-tooltip" href="<?php echo esc_url( $singles_url ); ?>">
				  <span class="dashicons dashicons-admin-post"></span>
				  <div class="fdp-tooltip"><?php esc_html_e( 'Go to Singles settings', 'freesoul-deactivate-plugins' ); ?></div>
				</a>
					<?php } ?>

					  <?php eos_dp_saved_preview_button( $archive_url, $key ); ?>
					  <?php
						$themes_list = eos_dp_active_themes_list();
						if ( $themes_list ) {
							?>
				<a class="eos-dp-theme-sel fdp-has-tooltip fdp-right-tooltip" style="border:1px solid #fff !important">
							<?php echo $themes_list; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied on the output of eos_dp_active_themes_list(). ?>
				  <div class="fdp-tooltip"><?php esc_html_e( 'Select a different Theme and then click on the lens icon to see the preview', 'freesoul-deactivate-plugins' ); ?></div>
				</a>
						<?php } ?>
				<a class="eos-dp-preview eos-dp-archive-preview fdp-has-tooltip" oncontextmenu="return false;" href="
					<?php
					echo esc_url(
						wp_nonce_url(
							add_query_arg(
								array(
									'test_id' => time(),
									'fdp_tax' => esc_attr( $this->tax->name ),
								),
								esc_url( $archive_url )
							),
							'eos_dp_preview',
							'eos_dp_preview'
						)
					);
					?>
					" target="_blank">
				  <span class="dashicons dashicons-search"></span>
				  <div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins according the settings you see now on this row and the selected theme', 'freesoul-deactivate-plugins' ); ?></div>
				</a>
				<a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="
					<?php
					echo esc_url(
						wp_nonce_url(
							add_query_arg(
								array_merge(
									array(
										'test_id'    => time(),
										'fdp_tax'    => esc_attr( $this->tax->name ),
										'show_files' => 'true',
									)
								),
								esc_url( $archive_url )
							),
							'eos_dp_preview',
							'eos_dp_preview'
						)
					);
					?>
					" target="_blank">
				  <span class="dashicons dashicons-search">
					<span class="dashicons dashicons-media-code"></span>
				  </span>
				  <div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins according the settings you see now on this row and the selected theme', 'freesoul-deactivate-plugins' ); ?></div>
				</a>
				<a class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="
					<?php
					echo esc_url(
						wp_nonce_url(
							add_query_arg(
								array(
									'js'      => 'off',
									'test_id' => time(),
									'fdp_tax' => esc_attr( $this->tax->name ),
								),
								esc_url( $archive_url )
							),
							'eos_dp_preview',
							'eos_dp_preview'
						)
					);
					?>
					" target="_blank">
				  <span class="dashicons dashicons-search">
					<span class="eos-dp-no-js">JS</span>
				  </span>
				  <div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins and the theme according the settings you see now on this row and disable JavaScript esecution', 'freesoul-deactivate-plugins' ); ?></div>
				</a>
				<a class="eos-dp-invert-selection fdp-has-tooltip" href="#"><span class="dashicons"><span style="display:inline-block"><span class="fdp-invert-up"></span><span class="fdp-invert-down"></span></span></span>
				  <div class="fdp-tooltip"><?php esc_html_e( 'Invert selection', 'freesoul-deactivate-plugins' ); ?></div>
				</a>
				<a class="eos-dp-copy fdp-has-tooltip" href="#"><span class="dashicons dashicons-admin-page"></span>
				  <div class="fdp-tooltip"><?php esc_html_e( 'Copy this row settings', 'freesoul-deactivate-plugins' ); ?></div>
				</a>
				<a class="eos-dp-paste fdp-has-tooltip" href="#"><span class="dashicons dashicons-category"></span>
				  <div class="fdp-tooltip"><?php esc_html_e( 'Paste last copied row settings', 'freesoul-deactivate-plugins' ); ?></div>
				</a>
					<?php do_action( 'eos_dp_action_buttons' ); ?>
					<?php do_action( 'eos_dp_archive_action_buttons' ); ?>
				<a title="<?php esc_attr_e( 'Close', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-close-actions" href="#"><span class="dashicons dashicons-no-alt"></span></a>
			  </div>
			</td>
					<?php
					$k = 0;
					foreach ( $this->active_plugins as $plugin ) {
						if ( in_array( $plugin, array_keys( $this->plugins_by_dirs ) ) ) {
							$active = ! isset( $values ) || ! $values || ! is_array( $values ) || ! in_array( $plugin, $values ) ? true : false;
							?>
			<td class="center<?php echo $active ? ' eos-dp-active' : ''; ?>" data-path="<?php echo esc_attr( $plugin ); ?>">
			  <div class="eos-dp-td-chk-wrp eos-dp-td-archive-chk-wrp">
				<input class="eos-dp-row-<?php echo esc_attr( $row ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ); echo isset( $term->name ) ? ' eos-dp-col-' . esc_attr( $k + 1 ) . '-' . esc_attr( $term->name ) : ''; ?>" data-checked="<?php echo $active ? 'not-checked' : 'checked'; ?>" type="checkbox"<?php echo ! $active ? ' checked' : ''; ?> />
			  </div>
			</td>
							<?php
							++$k; }
					}
					?>
		  </tr>
					<?php
					++$row;
				}
			}
		} else {
			?>
	  <p><?php printf( esc_html__( 'You have no %s', 'freesoul-deactivate-plugins' ), esc_html( $this->tax->label ) ); ?></p>
			<?php
		}
	}
	public function is_missing_base() {
		if( function_exists( 'fdp_is_plugin_globally_active' ) && fdp_is_plugin_globally_active( 'seo-by-rank-math/rank-math.php' ) ) {
			$rank_math_opts = eos_dp_get_option( 'rank-math-options-general' );
			$permalinks_slugs = array(
				'category' => 'strip_category_base',
				'product' => 'wc_remove_product_base',
				'product-category' => 'wc_remove_category_base',
				'product-tag',
			);
			if( in_array( $this->tax_slug, array_keys( $permalinks_slugs ) ) ) {
				$rank_math_opts = eos_dp_get_option( 'rank-math-options-general' );
				if( $rank_math_opts && is_array( $rank_math_opts ) ){
					$rank_key = $permalinks_slugs[$this->tax_slug];
					if( isset( $rank_math_opts[$rank_key] ) && 'on' === sanitize_text_field( $rank_math_opts[$rank_key] ) ) {
						add_action( 'eos_dp_after_footer', function() {
							echo '<style id="fdp-all-archives-disabled-css">' . sanitize_text_field( strip_tags( $this->missing_base_css() ) ) . '</style>';
						} );
						return true;
					}
				}
			}
		}
		return false;
	}

	public function missing_base_css() {
		$css = '';
		$css .= '.fdp-all-archvies-missing-base{position:relative}';
		$css .= '.fdp-all-archvies-missing-base td{background-image:none !important;background-color:transparent !important;border:none !important}';
		$css .= '.fdp-all-archvies-missing-base td:nth-child(2){opacity:1 !important}';
		$css .= '.fdp-all-archvies-missing-base td:nth-child(2):after{';
		$css .= 'content:"\f534  ' . sprintf( '%s disabled because %s removes the archive base from the URL', esc_html( $this->all_archives_name ), 'Rank Math' ) .'";';
		$css .= 'position:absolute;';
		$css .= 'height:20px;';
		$css .= 'top:0;';
		$css .= is_rtl() ?  'right' : 'left' . ':0;';
		$css .= 'color:red;';
		$css .= 'padding:5px;';
		$css .= 'font-family:dashicons;';
		$css .= 'text-transform:uppercase;';
		$css .= 'letter-spacing:0.5px;';
		$css .= 'padding:5px;';
		$css .= '}';
		$css .= '.fdp-all-archvies-missing-base td:not(.eos-dp-post-name-wrp){pointer-events:none;opacity:0.2}';
		return $css;
	}
}
