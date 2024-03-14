<?php
/**
 * Class for the Custom Rows.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Class FDP Custom Rows
 *
 * Implemented by Custom Rows templates.
 *
 * @version  1.0.0
 * @package  Freesoul Deactivate Plugins\Classes
 */
class FDP_Custom_Rows_Page extends Eos_Fdp_Matrix_Page {

	/**
	 * URLs.
	 *
	 * @var array $urls URLs
	 * @since  1.9.0
	 */		
	public $urls;

	/**
	 * Homme URL.
	 *
	 * @var string $home_url Home URL
	 * @since  1.9.0
	 */		
	public $home_url;

	/**
	 * Section ID.
	 *
	 * @var string $section_id Section ID
	 * @since  1.9.0
	 */	
	public $section_id;

	/**
	 * Button class.
	 *
	 * @var string $button_class Button class
	 * @since  1.9.0
	 */	
	public $button_class = 'fdp-custom-rows-btn';

	/**
	 * Output before section.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */
	public function before_section( $page_slug ) {
		$this->section_id = 'eos-dp-by-url-section';
		$this->urls       = eos_dp_get_option( 'eos_dp_by_url' );
		if ( ! $this->urls || '' === $this->urls ) {
			$this->urls = array(
				array(
					'url'     => '',
					'plugins' => '',
				),
			);
		} else {
			$this->urls[] = array(
				'url'     => '',
				'plugins' => '',
			);
		}
		$this->home_url = get_home_url();
		?>
	<style id="fdp-custom-rows-css">
	.fdp-exact-filter{
	  margin-left: 15px;
	  margin-top: 3px;
	}
	.fdp-exact-filter-off{
	  opacity:0.6
	}
	</style>
	<h2><?php esc_html_e( 'Uncheck the plugins you want to disable depending on the URL', 'freesoul-deactivate-plugins' ); ?></h2>
		<h2><span class="dashicons dashicons-warning"></span><?php esc_html_e( 'It will work only for the FRONTEND', 'freesoul-deactivate-plugins' ); ?></h2>
		<div class="eos-dp-explanation" style="margin-bottom:48px">
			<p><?php esc_html_e( 'Use the star "*" as replacement of groups of characters.', 'freesoul-deactivate-plugins' ); ?></p>
			<p><?php echo wp_kses_post( printf( __( 'E.g. %1$s*example/ will match URLs as %2$s/an-example/, %3$s/another-example/...', 'freesoul-deactivate-plugins' ), esc_url( $this->home_url ), esc_url( $this->home_url ), esc_url( $this->home_url ) ) ); ?></p>
			<p><?php echo wp_kses_post( sprintf( __( 'You can use these options to disable plugins by URL query arguments. E.g. *?example-paramameter=true* will match URLS as %1$s?example-paramameter=true, %2$s/page-example/?example-paramameter=true...', 'freesoul-deactivate-plugins' ), esc_url( $this->home_url ), esc_url( $this->home_url ) ) ); ?></p>
			<p><?php printf( esc_html__( 'Use the pattern %s to match the homepage URL with whatever query arguments', 'freesoul-deactivate-plugins' ), '[home]?*' ); ?></p>
		</div>
		<?php
	}

	public function tableBody( $page_slug ) {
		$row   = 0;
		$urlsN = count( $this->urls );
		do_action( 'fdp_before_table_rows' );
		do_action( 'fdp_before_table_rows_' . sanitize_key( $page_slug ) );
		$h_pattern = isset( $_GET['pattern'] ) ? sanitize_text_field( urldecode( $_GET['pattern'] ) ) : false; //@codingStandardsIgnoreLine.
		// Sanitization applied after urldecode.
		foreach ( $this->urls as $urlA ) {
			?>
	  <tr class="eos-dp-url eos-dp-post-row
			<?php
			echo isset( $urlA['url'] ) && $h_pattern === $urlA['url'] ? ' fdp-actions-on' : '';
			echo $row + 1 === $urlsN ? ' eos-hidden' : '';
			echo isset( $urlA['needs_url'] ) && absint( $urlA['needs_url'] ) > 0 ? ' eos-dp-need-from-singe' : '';
			?>
		">
		<td class="eos-dp-post-name-wrp">
		  <span class="eos-dp-not-active-wrp"><input title="<?php esc_attr_e( 'Activate/deactivate all plugins for this URL', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-global-chk-row" type="checkbox" /></span>
		  <span class="dashicons dashicons-move" title="<?php esc_attr_e( 'Move it up to assign higher priority', 'freesoul-deactivate-plugins' ); ?>"></span>
			  <?php if ( defined( 'FDP_PRO_ACTIVE' ) && FDP_PRO_ACTIVE ) { ?>
		  <span class="hover fdp-exact-filter<?php echo isset( $urlA['f'] ) && '1' === $urlA['f'] ? '' : ' fdp-exact-filter-off'; ?> dashicons dashicons-filter" title="<?php esc_attr_e( 'Disable exactly the plugins of this row/filter the plugins of this row and take into account also other settings.', 'freesoul-deactivate-plugins' ); ?>"></span>
		  <?php } ?>
		  <input type="text" class="eos-dp-url-input" title="<?php echo isset( $urlA['url'] ) ? esc_attr( $urlA['url'] ) : ''; ?>" placeholder="<?php echo wp_kses_post( sprintf( __( 'Write here the URL', 'freesoul-deactivate-plugins' ), esc_url( $this->home_url ) ) ); ?>" value="<?php echo isset( $urlA['url'] ) ? esc_attr( $urlA['url'] ) : ''; ?>" />
			  <?php if ( isset( $urlA['needs_url'] ) && absint( $urlA['needs_url'] ) > 0 ) { ?>
		  <span class="eos-dp-ncu-wrn dashicons dashicons-warning" title="<?php echo wp_kses_post( sprintf( __( 'This URL covers the post ID %s. It was not possibe to manage it with the Singles settings.', 'freesoul-deactivate-plugins' ), esc_html( $urlA['needs_url'] ) ) ); ?>"></span>
		  <?php } ?>
		  <span class="eos-dp-delete-url dashicons dashicons-trash hover" title="<?php esc_attr_e( 'Delete', 'freesoul-deactivate-plugins' ); ?>"></span>
		  &nbsp;&nbsp;<a class="eos-dp-copy" href="#"><span class="dashicons dashicons-admin-page" style="font-size:30px"></span></a>
		  &nbsp;&nbsp;<a class="eos-dp-paste" href="#"><span class="dashicons dashicons-category" style="font-size:30px"></span></a>
		  <span class="eos-dp-x-space"></span>
		</td>
			  <?php
				$k = 0;
				foreach ( $this->active_plugins as $plugin ) {
					if ( in_array( $plugin, array_keys( $this->plugins_by_dirs ) ) ) {
						if ( ! isset( $urlA['plugins'] ) ) {
								  $active = true;
						} else {
							$active = ! in_array( $plugin, explode( ',', $urlA['plugins'] ) ) ? true : false;
						}
						?>
		  <td class="center<?php echo $active ? ' eos-dp-active' : ''; ?>" data-path="<?php echo esc_attr( $plugin ); ?>">
			<div class="eos-dp-td-chk-wrp eos-dp-td-url-chk-wrp">
			  <input class="eos-dp-row-<?php echo esc_attr( $row ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ); ?>" type="checkbox"<?php echo $active ? ' checked' : ''; ?> />
			</div>
		  </td>
						<?php
						++$k;
					}
				}
				do_action( 'fdp_after_last_plugin', $urlA['url'], $k );
				?>
	  </tr>
			<?php
			++$row;
		}
		?>
	<tr>
	  <td colspan="<?php echo count( $this->active_plugins ) + 2; ?>" id="eos-dp-url-actions" style="border:none;padding:0">
		<button id="eos-dp-add-url" style="margin-top:16px"><?php esc_html_e( 'Add URL', 'freesoul-deactivate-plugins' ); ?></button>
	  </td>
	</tr>
		<?php
		do_action( 'fdp_after_table_rows' );
		do_action( 'fdp_after_table_rows_' . sanitize_key( $page_slug ) );
	}
}
