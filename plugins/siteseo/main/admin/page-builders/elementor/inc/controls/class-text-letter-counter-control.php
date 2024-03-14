<?php
namespace SiteSeoElementorAddon\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Text_Letter_Counter_Control extends \Elementor\Base_Data_Control {
	public function get_type() {
		return 'siteseotextlettercounter';
	}

	public function enqueue() {
		wp_enqueue_style(
			'siteseo-el-text-letter-counter-style',
			SITESEO_ELEMENTOR_ADDON_URL . 'assets/css/text-letter-counter.css'
		);

		wp_enqueue_script(
			'siteseo-el-text-letter-counter-script',
			SITESEO_ELEMENTOR_ADDON_URL . 'assets/js/text-letter-counter.js',
			array('jquery'),
			11,
			true
		);
	}

	protected function get_default_settings() {
		return [
			'field_type' => 'text',
			'description' => '',
			'rows' => 7
		];
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field siteseo-text-letter-counter">
			<?php do_action('siteseo_elementor_seo_titles_before'); ?>

			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<# if ( data.field_type === 'text' ) { #>
					<input type="text" id="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-tag-area" data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}" />
				<# } else { #>
					<textarea id="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-tag-area" rows="{{ data.rows }}" data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}"></textarea>
				<# } #>
			<div>
			<div class="siteseo-progress">
				<div class="siteseo_counters_progress siteseo-progress-bar" role="progressbar" style="width: 2%;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">1%</div>
			</div>
			<div class="wrap-siteseo-counters">
				<div class="siteseo_pixel"></div>
				<strong>
					<# if ( data.field_type === 'text' ) { #>
						<?php esc_html_e(' / 568 pixels - ','siteseo'); ?>
					<# } else { #>
						<?php esc_html_e(' / 940 pixels - ','siteseo'); ?>
					<# } #>
				</strong>
				<div class="siteseo_counters"></div>
				<?php esc_html_e(' (maximum recommended limit)','siteseo'); ?>
			</div>

			<div class="wrap-tags">
				<# if ( data.field_type === 'text' ) { #>
					<span class="siteseo-tag-single-title tag-title" data-tag="%%post_title%%" ><span class="dashicons dashicons-insert"></span><?php esc_html_e( 'Post Title','siteseo' ); ?></span>
					<span class="siteseo-tag-single-sep tag-title" data-tag="%%sep%%"><span class="dashicons dashicons-insert"></span><?php esc_html_e( 'Separator','siteseo' ); ?></span>
					<span class="siteseo-tag-single-site-title tag-title" data-tag="%%sitetitle%%"><span class="dashicons dashicons-insert"></span><?php esc_html_e( 'Site Title','siteseo' ); ?></span>
				<# } else { #>
					<span class="siteseo-tag-single-excerpt tag-title" data-tag="%%post_excerpt%%"><span class="dashicons dashicons-insert"></span><?php esc_html_e( 'Post Excerpt', 'siteseo' ); ?></span>
				<# } #>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
