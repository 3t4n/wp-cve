<?php
/**
 * Class for generic Settings pages .

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'Eos_Fdp_Plugins_Manager_Page' ) ) {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/abstracts/class-eos-fdp-plugins-manager-page.php';
}

class FDP_Settings_Page extends Eos_Fdp_Plugins_Manager_Page {

	public $page_slug;
	public $title;
	public $autoload;
	public $description;
	public $section_id;
	public $button_class;
	public $parent_menu_slug;
	public $capability;
	public $args;
	public $save_button;

	public function __construct( $page_slug, $args, $parent_menu_slug, $title, $description, $autoload, $capability, $dashicon = false, $save_button = true ) {
		if ( ! $capability ) {
			$capability = current_user_can( 'fdp_plugins_viewer' ) ? 'read' : 'activate_plugins';
			$capability = apply_filters( 'eos_dp_settings_capability', $capability );
		}
		if ( ! current_user_can( $capability ) && ! defined( 'FDP_EMERGENCY_LOG_ADMIN' ) ) {
			?>
	  <h2><?php esc_html_e( 'Sorry, you have not the right for this page', 'freesoul-deactivate-plugins' ); ?></h2>
			<?php
			return;
		}
		$this->title       = $title && ! empty( $title ) ? sanitize_text_field( $title ) : false;
		$this->description = $description;
		if ( $dashicon ) {
			$this->dashicon = $dashicon;
		}
		$page_slug          = sanitize_key( $page_slug );
		$this->page_slug    = $page_slug;
		$this->autoload     = $autoload ? 'true' : 'false';
		$this->section_id   = str_replace( '_', '-', $page_slug ) . '-section';
		$this->button_class = 'fdp-' . str_replace( '_', '-', $page_slug );
		$this->args         = $args;
		$this->save_button  = $save_button;
		$this->header();
		$this->before_section( $page_slug );
		$this->section( $page_slug );
		$this->footer( $this->button_class );
	}

	public function before_section( $page_slug ) {
		if ( $this->save_button ) {
			wp_enqueue_script( 'fdp-pro-settings', EOS_DP_SETTINGS_JS_URL, array( 'eos-dp-backend' ), true );
			wp_localize_script(
				'fdp-pro-settings',
				'fdp_setts_js',
				array(
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'action'   => 'eos_dp_save_addon_settings',
					'opts_key' => sanitize_key( $this->page_slug ),
					'autoload' => $this->autoload,
				)
			);
		}
		if ( $this->title ) {
			?>
	<h2><?php echo esc_html( $this->title ); ?></h2>
	  <?php } ?>
		<div class="eos-dp-explanation" style="margin-bottom:48px">
			<p>
			<?php
			echo wp_kses(
				$this->description,
				array(
					'h2'     => array(),
					'strong' => array(),
					'p'      => array(),
					'a'      => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			);
			?>
				</p>
		</div>
		<?php
	}
	public function section( $page_slug ) {
		$nonces = $this->get_nonces_map();
		if ( isset( $nonces[ $page_slug ] ) ) {
			wp_nonce_field( $page_slug, $nonces[ $page_slug ] );
		}
		$opts = eos_dp_get_option( $page_slug );
		?>
	<style id="fdp-settings-css">
	@media screen and (min-width:600px){
	.fdp-form-table input[type=text],.fdp-form-table input[type=email],.fdp-form-table textarea{min-width:400px}
	}
	</style>
	<section id="<?php echo esc_attr( $this->section_id ); ?>" class="
							<?php
							echo esc_attr( apply_filters( 'fdp_section_class_name', 'eos-dp-section' ) );
							echo ' ' . esc_attr( $this->section_common_class );
							?>
	" data-page_slug="<?php echo esc_attr( $page_slug ); ?>">
		<?php do_action( 'eos_dp_before_wrapper' ); ?>
	  <div id="eos-dp-wrp">
		<table class="fdp-form-table form-table">
		  <tbody>
		<?php
		foreach ( $this->args as $key => $arr ) {
			$value     = '';
			$attribute = isset( $arr['attribute'] ) && '' !== $arr['attribute'] ? ' ' . $arr['attribute'] : '';
			if ( isset( $arr['value'] ) ) {
				$value = $arr['value'];
			} elseif ( isset( $opts[ 'fdp-opt-' . $key ] ) && ! empty( $opts[ 'fdp-opt-' . $key ] ) && is_string( $opts[ 'fdp-opt-' . $key ] ) ) {
				$valueArr = json_decode( stripslashes( $opts[ 'fdp-opt-' . $key ] ), true );
				if ( $valueArr && ! empty( $valueArr ) && isset( $valueArr['value'] ) ) {
					$value = $valueArr['value'];
				}
			}
			?>
	  <tr valign="top"
			<?php
			echo isset( $arr['wrapper_id'] ) && '' !== $arr['wrapper_id'] ? ' id="' . esc_attr( $arr['wrapper_id'] ) . '"' : '';
			echo isset( $arr['wrapper_class'] ) && '' !== $arr['wrapper_class'] ? ' class="' . esc_attr( $arr['wrapper_class'] ) . '"' : '';
			?>
		>
		<th>
			<?php echo isset( $arr['title'] ) ? '<h2>' . esc_html( $arr['title'] ) . '</h2>' : ''; ?>
		  <label for="fdp-opt-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $arr['description'] ); ?></label>
		</th>
		<td><input id="fdp-opt-<?php echo esc_attr( $key ); ?>" class="fdp-opt<?php echo isset( $arr['class'] ) && '' !== $arr['class'] ? ' ' . esc_attr( $arr['class'] ) : ''; ?>" type="<?php echo esc_attr( $arr['type'] ); ?>" value="<?php echo esc_attr( $value ); ?>"<?php echo esc_attr( $attribute ); ?> /></td>
	  </tr>
			<?php
		}
		?>
		  </tbody>
		</table>
	  </div>
	</section>
		<?php
	}
	public function tableBody( $page_slug ) {
		return;
	}

	public function get_nonces_map() {
		$arr                                     = array();
		$arr[ sanitize_key( $this->page_slug ) ] = 'fdp_setts_nonce';
		return $arr;
	}

	public function footer( $button_class ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-footer.php';
		if ( $this->save_button ) {
			eos_dp_save_button();
		}
	}
}
