<?php
/**
 * Class for the One Column pages.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'Eos_Fdp_Plugins_Manager_Page' ) ) {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/abstracts/class-eos-fdp-plugins-manager-page.php';
}

class Eos_Fdp_One_Column_Page extends Eos_Fdp_Plugins_Manager_Page {

	public $title;
	public $page_slug;
	public $active_plugins  = array();
	public $plugins_by_dirs = array();
	public $section_id;
	public $settings;
	public $active;
	public $dashicon = false;

	public function custom_before_section() {

	}

	public function before_section( $page_slug, $active = false ) {
		$this->custom_before_section();
		$this->style();
		$this->settings = eos_dp_get_option( $page_slug );
		?>
		<h2>
			<?php
			if ( $this->dashicon ) {
				echo '<span class="dashicons dashicons-' . esc_attr( $this->dashicon ) . '"></span>';
			}
			echo esc_html( $this->title );
			?>
		</h2>
		<?php 
		do_action( 'fdp_one_column_after_title' );
		do_action( 'fdp_addon_description', sanitize_key( $page_slug ) );
		$this->after_title(); 
		$this->section_legend(); 
		$this->bulk_selection();
		do_action( 'fdp_one_column_before_section' );
		$this->before_section_end();
	}

	public function after_title() {

	}

	public function before_section_end() {

	}

	public function section( $page_slug ) {
		wp_nonce_field( $page_slug . '_setts', $page_slug . '_setts' );
		?>
		<section id="<?php echo esc_attr( $this->section_id ); ?>" class="fdp-one-col-sec <?php echo esc_attr( apply_filters( 'fdp_section_class_name', 'eos-dp-section' ) ); ?>" data-section="<?php echo esc_attr( $page_slug ); ?>">
		<div id="eos-dp-wrp">
			<table id="eos-dp-setts"<?php echo $this->active ? '' : 'class="fdp-opposite-activation"'; ?>>
			<?php $this->tableBody( $page_slug ); ?>
			</table>
		</div>
		</section>
		<?php
	}

	public function section_legend() {
		?>
		<div style="margin-bottom:12px;margin-top:32px">
			<span class="eos-dp-active-wrp eos-dp-icon-wrp"><input style="width:20px;height:20px;margin:0" type="checkbox"></span>
			<span class="eos-dp-legend-txt"><?php esc_html_e( 'Plugin active', 'freesoul-deactivate-plugins' ); ?></span>
			<span class="eos-dp-not-active-wrp eos-dp-icon-wrp"><input style="width:20px;height:20px;margin:0" type="checkbox" checked=""></span>
			<span class="eos-dp-legend-txt"><?php esc_html_e( 'Plugin not active', 'freesoul-deactivate-plugins' ); ?></span>
		</div>
		<?php
	}

	public function bulk_selection() {
		?>
		<div style="margin:32px 0 16px 0">
			<span id="fdp-select-all-single-post" class="button"><?php esc_html_e( 'Enable All', 'freesoul-deactivate-plugins' ); ?></span>
			<span id="fdp-unselect-all-single-post" class="button"><?php esc_html_e( 'Disable All', 'freesoul-deactivate-plugins' ); ?></span>
		</div>
		<?php
	}

	public function style() {
		?>
		<style id="fdp-one-col-css">
		.fdp-more-than-25-plugins .fdp-one-col-sec table#eos-dp-setts {
			column-count: 3;
			max-width: calc(100vw - 180px);
			border-collapse: separate;
		}
		.fdp-more-than-25-plugins.folded .fdp-one-col-sec table#eos-dp-setts {
			max-width: calc(100vw - 50px);
		}
		.fdp-more-than-25-plugins .fdp-one-col-sec table#eos-dp-setts td,
		.fdp-more-than-25-plugins .fdp-one-col-sec table#eos-dp-setts th{
			font-size: 0.7rem;
		}
		td.eos-dp-name-td a{
    		max-width: 380px;
			overflow:hidden;
			white-space:nowrap;
			text-overflow:ellipsis;
			display:inline-block
		}
		@media screen and (max-width:1350px){
			.fdp-more-than-25-plugins .fdp-one-col-sec table#eos-dp-setts {
				column-count: 2;
			}
		}
		@media screen and (max-width:900px){
			.fdp-more-than-25-plugins .fdp-one-col-sec table#eos-dp-setts {
				column-count: 1;
			}
		}
		</style>	
	<?php
	}
	// Settings page header.
	public function header() {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-navigation.php';
		eos_dp_alert_plain_permalink();
		eos_dp_navigation();
	}

	public function tableBody( $page_slug ) {
		$n              = 0;
		$plugins        = $this->get_plugins();
		$page_slug_dash = str_replace( '_', '-', $page_slug );
		foreach ( $this->active_plugins as $p ) {
			if ( isset( $plugins[ $p ] ) ) {
				$plugin_name = eos_dp_get_plugin_name_by_slug( $p );
				$checked     = $this->settings && in_array( $p, $this->settings ) ? '' : ' checked';
				$details_url = add_query_arg(
					array(
						'tab'         => 'plugin-information',
						'plugin'      => dirname( $p ),
						'TB_iframe'   => true,
						'eos_dp'      => $p,
						'eos_dp_info' => 'true',
					),
					admin_url( 'plugin-install.php' )
				);
				?>
		<tr>
		  <td class="<?php echo esc_attr( $page_slug_dash ); ?>-chk-col fdp-one-col-td <?php echo '' !== $checked ? 'eos-dp-active' : 'd'; ?>">
			<div class="eos-dp-td-chk-wrp">
			  <input id="<?php echo esc_attr( $page_slug_dash . '-' . ( $n + 1 ) ); ?>" class="fdp-one-col-i <?php echo esc_attr( $page_slug_dash ); ?>" title="<?php printf( esc_attr__( 'Activate/deactivate %s everywhere', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ) ); ?>" data-path="<?php echo esc_attr( $p ); ?>" type="checkbox"<?php echo $checked; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied on $checked. ?> />
			</div>
		  </td>
		  <td class="eos-dp-name-td"><span class="dashicons dashicons-admin-plugins"></span><a title="<?php esc_attr_e( 'View details', 'freesoul-deactivate-plugins' ); ?>" target="_blank" class="eos-dp-no-decoration" href="<?php echo esc_url( $details_url ); ?>"><?php echo esc_html( $plugin_name ); ?></a></td>
		</tr>
				<?php
				++$n;
			}
		}
	}

	// Settings page footer.
	public function footer( $button_class ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-footer.php';
		if ( current_user_can( 'activate_plugins' ) ) {
			eos_dp_save_button();
		}
	}

	public function get_nonces_map() {
		return array(
			'eos_dp_mobile'  => 'eos_dp_mobile_setts',
			'eos_dp_desktop' => 'eos_dp_desktop_setts',
			'eos_dp_search'  => 'eos_dp_search_setts',
		);
	}
}
