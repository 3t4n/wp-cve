<?php
/**
 * Abstract Class for the matrix.
 *
 * @class       Eos_Fdp_Matrix_Page
 * @version     1.0.0
 * @package     Freesoul Deactivate Plugins\Abstracts
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Abstract FDP Matrix Page
 *
 * Implemented by classes using the same pattern.
 *
 * @version  1.0.0
 * @package  Freesoul Deactivate Plugins\Abstracts
 */
abstract class Eos_Fdp_Matrix_Page extends Eos_Fdp_Plugins_Manager_Page {

	/**
	 * Google Page Speed Insights URL.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $gpsi_url = 'https://developers.google.com/speed/pagespeed/insights/';

	/**
	 * Dataset.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $dataset  = '';

	/**
	 * Settings page header.
	 *
	 * @since  1.9.0
	 */
	public function header() {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-navigation.php';
		eos_dp_alert_plain_permalink();
		eos_dp_navigation();
	}

	/**
	 * Settings page foooter.
	 *
	 * @since  1.9.0
	 */
	public function footer( $button_class ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-footer.php';
		if ( current_user_can( 'activate_plugins' ) ) {
			eos_dp_save_button( $button_class );
		}
	}
	/**
	 * Output head of the matrix including the plugins and the slide control.
	 *
	 * @since  1.9.0
	 */
	public function tableHead() {
		$plugins                           = $this->get_plugins();
		$GLOBALS['eos_dp_plugins_by_dirs'] = $plugins;
		$active_plugins                    = $this->active_plugins;
		?>
	  <tr id="eos-dp-table-head">
		  <th class="fdp-legend" style="vertical-align:bottom;border-style:none;text-align:initial;padding-left:20px;margin-left:-20px">
			<?php do_action( 'fdp_table_head_first_col' ); ?>
			<?php if( isset( $_GET['page'] ) ) do_action( 'fdp_table_head_first_col_' . esc_attr( sanitize_text_field( $_GET['page'] ) ) ); ?>
			<?php $this->legend(); ?>
		<div>
		  <input class="fdp-resize-col" type="range" min="0" max="700" value="354">
		</div>
		  </th>
		<?php
		$n   = 0;
		$fdp = array();
		foreach ( $active_plugins as $p ) {
			if ( isset( $plugins[ $p ] ) ) {
				$plugin            = $plugins[ $p ];
				$plugin_name       = function_exists( 'eos_dp_get_plugin_name_by_slug' ) ? strtoupper( eos_dp_get_plugin_name_by_slug( $p ) ) : strtoupper( str_replace( '-',' ',dirname( $p ) ) );
				$plugin_name_short = substr( $plugin_name, 0, 28 );
				$plugin_name_short = $plugin_name === $plugin_name_short ? $plugin_name : $plugin_name_short . ' ...';
				$details_url       = add_query_arg(
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
				  <th class="eos-dp-name-th"<?php echo isset( $_GET['int_plugin'] ) && dirname( $p ) === $_GET['int_plugin'] ? ' style="display:none"' : ''; ?>>
					  <div>
						  <div id="eos-dp-plugin-name-<?php echo esc_attr( $n + 1 ); ?>" class="eos-dp-plugin-name" title="<?php echo esc_attr( $plugin_name ); ?>" data-path="<?php echo esc_attr( $p ); ?>">
							  <span><a title="<?php printf( esc_attr__( 'View details of %s', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ) ); ?>" href="<?php echo esc_url( $details_url ); ?>" target="_blank"><?php echo esc_html( $plugin_name_short ); ?></a></span>
						  </div>
						  <div class="eos-dp-global-chk-col-wrp">
							  <div class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate %s everywhere', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ) ); ?>" data-col="<?php echo esc_attr( $n + 1 ); ?>" class="eos-dp-global-chk-col" type="checkbox" /></div>
							<?php do_action( 'eos_dp_table_head_col_after' ); ?>
						  </div>
						  <div class="fdp-p-n"><?php echo esc_attr( $n + 1 ); ?></div>
					  </div>
				  </th>
				<?php
				++$n;
			}
		}
		do_action( 'eos_dp_after_table_head_columns' );
		?>
	  </tr>
		<?php
		$this->slider();
	}

	/**
	 * Output plugins slide control.
	 *
	 * @since  1.9.0
	 */
	public function slider( $class_name = '' ) {
		?>
	  <tr class="fdp-slide-row<?php echo '' !== $class_name ? ' ' . esc_attr( $class_name ) : ''; ?>" style="border:none;box-shadow:none">
	  <td style="border:none;box-shadow:none">
		<div class="fdp-plugins-slider-wrp">
			  <input class="fdp-plugins-slider hover" style="margin:10px 0 0 0" type="range" min="0" max="<?php echo esc_attr( $GLOBALS['fdp_plugins_count'] ); ?>" value="0">
		  </div>
	  </td>
	  <td style="border:none;box-shadow:none" colspan="<?php echo esc_attr( $GLOBALS['fdp_plugins_count'] ); ?>"></td>
	</tr>
		<?php
	}

	/**
	 * Output script to resizze the first column.
	 *
	 * @since  2.6.0
	 */
	public function resize_col() {
		?>
	<script id="fdp-resize-col-js">
	function eos_dp_resize_col(){
	  var c=document.getElementsByClassName('eos-dp-post-name-wrp'),
		w=localStorage.getItem("fdp_first_col_width"),
		i=document.getElementsByClassName('fdp-resize-col');
	  if(i && c && c.length>0 && w){
		c[0].style.width=w + 'px';
		c[0].style.maxWidth=w + 'px';
		i[0].value=w;
	  }
	}
	eos_dp_resize_col();
	</script>
		<?php
	}

	/**
	 * Returnn plugins table.
	 *
	 * @since  1.9.0
	 * @return string
	 */	
	public function get_plugins_table() {
		return apply_filters( 'eos_dp_plugins_table', eos_dp_plugins_table() );
	}

	/**
	 * Output row action buttons.
	 *
	 * @since  1.9.0
	 */	
	public function action_buttons( $page_slug ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-action-buttons.php';
	}

	/**
	 * Return array of nonces.
	 *
	 * @since  1.9.0
	 * @return array
	 */	
	public function get_nonces_map() {
		return array(
			'eos_dp_menu'            => 'eos_dp_setts',
			'eos_dp_by_post_type'    => 'eos_dp_pt_setts',
			'eos_dp_by_archive'      => 'eos_dp_arch_setts',
			'eos_dp_by_term_archive' => 'eos_dp_arch_setts',
			'eos_dp_url'             => 'eos_dp_url_setts',
			'eos_dp_admin'           => 'eos_dp_admin_setts',
			'eos_dp_admin_url'       => 'eos_dp_admin_url_setts',
			'eos_dp_post_requests'   => 'eos_dp_post_requests_setts',
		);
	}

	/**
	 * Classes can output the matrix legend.
	 *
	 * @since  1.9.0
	 */
	public function legend() {
		return;
	}

	/**
	 * Classes have to output the table body.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */
	abstract public function tableBody( $page_slug );
}
