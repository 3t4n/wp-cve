<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class PL_Customizer_Control_Upgrade_Notice extends WP_Customize_Control {

	/**
	 * Customize control type.
	 *
	 * @var    string
	 */
	public $type = 'pluglab-upgrade';
	public $tname = 'PRO Version';
	public $upgradeinfo = 'https://unibirdtech.com/themes/';

	/**
	 * Render content.
	 */
	protected function render_content() {
//		$upgrade_link = 'https://unibirdtech.com/themes/shapro-pro/';
		?>

		<div class="pluglab-upgrade-pro-message" style="display:none;">
			<h4 class="customize-control-title"><?php echo wp_kses_post( 'Upgrade to <a href="' . esc_url( $this->upgradeinfo ) . '" target="_blank" > '.$this->tname .' </a> for unlimited', 'pluglab' ); ?>  <?php esc_html_e( 'and get more premium features.', 'pluglab' ); ?></h4>
		</div>

		<?php
	}

}
