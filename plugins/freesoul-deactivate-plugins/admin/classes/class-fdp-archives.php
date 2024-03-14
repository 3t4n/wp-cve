<?php
/**
 * Class for the Archives settings.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Class FDP Archives Page
 *
 * Implemented by archives templates.
 *
 * @version  1.0.0
 * @package  Freesoul Deactivate Plugins\Classes
 */
class FDP_Archives_Page extends Eos_Fdp_Matrix_Page {

	/**
	 * Array of settings.
	 *
	 * @var array $archiveSetts Archive Settings
	 * @since  1.9.0
	 */	
	public $archiveSetts;

	/**
	 * Home URL.
	 *
	 * @var string $home_url Home URL
	 * @since  1.9.0
	 */	
	public $home_url;

	/**
	 * Shop URL.
	 *
	 * @var string $shop_url Shop URL
	 * @since  1.9.0
	 */	
	public $shop_url;

	/**
	 * Filter Home.
	 *
	 * @var bool $filter_homme True if homepage
	 * @since  1.9.0
	 */	
	public $filter_home;

	/**
	 * Page for posts.
	 *
	 * @var int $page_for_posts Page for posts
	 * @since  1.9.0
	 */	
	public $page_for_posts;

	/**
	 * Show on front.
	 *
	 * @var string $show_on_front Show on front
	 * @since  1.9.0
	 */	
	public $show_on_front;

	/**
	 * Option size.
	 *
	 * @var int $option_size Option size
	 * @since  1.9.0
	 */	
	public $option_size;

	/**
	 * Skip DB.
	 *
	 * @var bool $skip_db Skip DB
	 * @since  1.9.0
	 */	
	public $skip_db;

	/**
	 * Initialization.
	 *
	 * @since  1.9.0
	 */	
	public function init() {
		$opts                 = eos_dp_get_option( 'eos_dp_opts' );
		$this->skip_db        = ( isset( $opts['skip_db_for_archives'] ) && 'true' === $opts['skip_db_for_archives'] ) || ( defined( 'FDP_SKIP_DB_FOR_ARCHIVES' ) && FDP_SKIP_DB_FOR_ARCHIVES );
		$this->archiveSetts   = eos_dp_get_option( 'eos_dp_archives' );
		$this->option_size    = eos_dp_get_option_size( 'eos_dp_archives' );
		$this->home_url       = get_option( 'home' );
		$this->shop_url       = false;
		$this->filter_home    = false;
		$this->page_for_posts = eos_dp_get_option( 'page_for_posts' );
		$this->show_on_front  = eos_dp_get_option( 'show_on_front' );
		if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] && 'posts' === $this->show_on_front ) {
			$this->filter_home = true;
		}
		if ( $this->option_size > 120 ) {
			add_action(
				'fdp_top_bar_notifications',
				function() {
					$msg  = '<p>' . esc_html__( 'The option Archives and Terms Archives is becoming too big and they may worsening the performance. Maybe better you move this option from the database to the filesystem.', 'freesoul-deactivate-plugins' ) . '</p>';
					$msg .= '<p>' . wp_kses_post( sprintf( __( 'If you want to do it go to %1$sExperiments%2$s', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( add_query_arg( 'page', 'eos_dp_experiments', admin_url( 'admin.php' ) ) ) . '" title="' . esc_attr__( 'Experiments', 'freesoul-deactivate-plugins' ) . '">', '</a>' ) ) . '</p>';
					eos_dp_display_admin_notice( 'eos_dp_archives', esc_html__( 'Option Archives and Terms Archives too big.', 'freesoul-deactivate-plugins' ), $msg, 'warning' );
				}
			);
		}
	}

	/**
	 * Output before section.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */
	public function before_section( $page_slug ) {
		?>
	<h2><?php esc_html_e( 'Archives', 'freesoul-deactivate-plugins' ); ?></h2>
		<?php do_action( 'eos_dp_after_archive_title' ); ?>
	<p><?php esc_html_e( 'If you don’t find here what you are looking for, maybe it’s a static page that behaves like an archive. In that case, have also a look in Singles => Pages', 'freesoul-deactivate-plugins' ); ?></p>
		<?php
	}

	/**
	 * Table body.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */
	public function tableBody( $page_slug ) {
		$plugins_table = apply_filters( 'eos_dp_plugins_table', eos_dp_plugins_table() );
		$row           = 1;
		$rowN          = 0;
		$key           = '';
		foreach ( $plugins_table as $post_type => $plugins ) {
			if ( ! $this->filter_home || ( $this->filter_home && 'post' === $post_type ) ) {
				$args        = array(
					'test_id'       => time(),
					'fdp_post_type' => $post_type,
				);
				$active      = false;
				$postTypeObj = get_post_type_object( $post_type );
				if ( ! is_object( $postTypeObj ) ) {
					continue;
				}
				$labels        = get_post_type_labels( get_post_type_object( $post_type ) );
				$labels_name   = isset( $labels->name ) ? $labels->name : $post_type;
				$post_type_obj = get_post_type_object( $post_type );
				$archive_url   = get_post_type_archive_link( $post_type );
				$skip          = false;
				if ( is_object( $post_type_obj ) && isset( $post_type_obj->rewrite ) ) {
					$rewrite = $post_type_obj->rewrite;
					$slug    = isset( $rewrite['slug'] ) ? $rewrite['slug'] : false;
					$page    = get_page_by_path( esc_attr( $slug ) );
					if ( is_object( $page ) ) {
						if ( get_permalink( $page->ID ) === $archive_url ) {
							$skip = true;
						}
					}
				}
				if ( ! $skip && $this->shop_url !== $archive_url && $post_type !== 'page' && $archive_url && ( $post_type !== 'post' || ( ! $this->page_for_posts && 'posts' === $this->show_on_front ) ) ) :
					$archive_url = remove_query_arg( 'lang', $archive_url );
					$kArr        = explode( '//', $archive_url );
					if ( isset( $kArr[1] ) ) {
						$key = $kArr[1];
					}
					$GLOBALS['fdp_action_archive_url'] = $archive_url;
					$key                               = sanitize_key( str_replace( '/', '__', rtrim( $key, '/' ) ) );
					if ( ! $this->skip_db ) {
						$values = isset( $this->archiveSetts[ $key ] ) ? explode( ',', $this->archiveSetts[ $key ] ) : array_fill( 0, count( $this->active_plugins ), ',' );
					} else {
						$opts_by_path = eos_dp_get_opts_by_url( esc_attr( str_replace( $this->home_url, '', $archive_url ) ) );
						if ( isset( $opts_by_path['post_id'] ) && 'archive' === $opts_by_path['post_id'] ) {
							$values = isset( $opts_by_path['plugins'] ) ? explode( ',', $opts_by_path['plugins'] ) : array_fill( 0, count( $this->active_plugins ), ',' );
						}
					}
					?>
		<tr class="eos-dp-archive-row eos-dp-post-row<?php echo 0 === $rowN ? ' fdp-row-1' : ''; ?>" data-url="<?php echo esc_attr( str_replace( $this->home_url, '', $archive_url ) ); ?>" data-post-type="<?php echo esc_attr( $post_type ); ?>" data-href="<?php echo esc_url( $archive_url ); ?>" >
		  <td class="eos-dp-post-name-wrp">
			<span class="fdp-row-actions-ico dashicons dashicons-plus" title="<?php esc_attr__( 'Action buttons', 'freesoul-deactivate-plugins' ); ?>"></span>
			<span class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate all plugins in %s', 'freesoul-deactivate-plugins' ), esc_attr( $labels_name ) ); ?>" data-row="<?php echo esc_attr( $row ); ?>" class="eos-dp-global-chk-row" type="checkbox" /></span>
			<span class="eos-dp-title"><?php printf( esc_html__( '%s Archive', 'freesoul-deactivate-plugins' ), esc_html( $labels_name ) ); ?></span>
			<div class="eos-dp-actions">
					<?php eos_dp_debug_button( $archive_url ); ?>
					<?php eos_dp_saved_preview_button( $archive_url, 'arch-' . esc_attr( $post_type ) ); ?>
					<?php
					$themes_list = eos_dp_active_themes_list();
					if ( $themes_list ) {
						?>
			  <a class="eos-dp-theme-sel fdp-has-tooltip fdp-right-tooltip" style="border:1px solid #fff !important">
						<?php echo $themes_list; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied on the output of eos_dp_active_themes_list(). ?>
				<div class="fdp-tooltip"><?php esc_html_e( 'Select a different Theme and then click on the lens icon to see the preview', 'freesoul-deactivate-plugins' ); ?></div>
			  </a>
				<?php } ?>
			  <a class="eos-dp-preview eos-dp-archive-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( $args, esc_url( $archive_url ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank">
				<span class="dashicons dashicons-search"></span>
				<div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins according the settings you see now on this row and the selected theme', 'freesoul-deactivate-plugins' ); ?></div>
			  </a>
			  <a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array_merge( $args, array( 'show_files' => 'true' ) ), esc_url( $archive_url ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank">
				<span class="dashicons dashicons-search">
				  <span class="dashicons dashicons-media-code"></span>
				</span>
				<div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins according the settings you see now on this row and show the files that are called', 'freesoul-deactivate-plugins' ); ?></div>
			  </a>
			  <a class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array_merge( $args, array( 'js' => 'off' ) ), esc_url( $archive_url ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank">
				<span class="dashicons dashicons-search">
				  <span class="eos-dp-no-js">JS</span>
				</span>
				<div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins and the theme according the settings you see now on this row and disable JavaScript esecution', 'freesoul-deactivate-plugins' ); ?></div>
			  </a>
					  <?php
						$args['eos_dp_preview'] = 1000 * absint( time() / 1000 );
						$psi_url                = add_query_arg(
							array(
								'url' => urlencode(
									add_query_arg(
										array_merge( array( 'display_usage' => 'false' ), $args ),
										esc_url( $archive_url )
									)
								),
							),
							$this->gpsi_url
						);
						?>
			  <a data-page_speed_insights="true" data-encode_url="true" class="eos-dp-preview eos-dp-psi-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( $psi_url ); ?>" target="_blank" rel="noopener">
				<span class="dashicons dashicons-search">
				  <img width="20" height="20" src="<?php echo esc_url( EOS_DP_PLUGIN_URL . '/admin/assets/img/pagespeed.png' ); ?>" />
				</span>
				<div class="fdp-tooltip"><?php esc_html_e( 'Check the page with Google PageSpeed Insights loading plugins and the theme according the settings you see now on this row', 'freesoul-deactivate-plugins' ); ?></div>
			  </a>
					<?php
					if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ) {
						require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-home-actions.php';
					}
					?>
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
			  <a title="<?php esc_html_e( 'Close', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-close-actions" href="#"><span class="dashicons dashicons-no-alt"></span></a>
			</div>
		  </td>
					<?php
					$k = 0;
					foreach ( $this->active_plugins as $plugin ) {
						$active = ! isset( $values ) || ! $values || ! is_array( $values ) || ! in_array( $plugin, $values ) ? true : false;
						if ( in_array( $plugin, array_keys( $this->plugins_by_dirs ) ) ) {
							?>
			<td class="center<?php echo $active ? ' eos-dp-active' : ''; ?>" data-path="<?php echo esc_attr( $plugin ); ?>">
			  <div class="eos-dp-td-chk-wrp eos-dp-td-archive-chk-wrp">
				<input class="eos-dp-row-<?php echo esc_attr( $row ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ) . '-' . esc_attr( $post_type ); ?>" data-checked="<?php echo isset( $values ) && $active ? 'not-checked' : 'checked'; ?>" type="checkbox"<?php echo $active ? '' : ' checked'; ?> />
			  </div>
			</td>
							<?php
							++$k;
						}
					}
					?>
		</tr>
					<?php
					++$rowN;
			  endif;
				++$row;
			}
		}
	}

}
