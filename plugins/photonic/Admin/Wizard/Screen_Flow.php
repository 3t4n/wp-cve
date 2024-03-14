<?php

namespace Photonic_Plugin\Admin\Wizard;

/**
 * Contains the flow layout skeleton. Cannot be overridden by a theme file
 *
 * Screen 1: Provider selection
 * Screen 2: Display Type selection; input: Provider
 * Screen 3: Gallery selection; input: Display Type
 * Screen 4: Layout selection; input: Gallery & Display Type
 *
 * @since 2.00
 */

if (!current_user_can('edit_posts')) {
	wp_die(esc_html__('You are not authorized to use this capability.', 'photonic'));
}

class Screen_Flow {
	private $editor_shortcode;
	private $input_shortcode;
	private $editor_shortcode_text;

	public function __construct() {
		if (wp_verify_nonce($_REQUEST['nonce'], 'photonic-wizard-' . get_current_user_id())) {
			if (isset($_REQUEST['shortcode'])) {
				$this->input_shortcode = sanitize_text_field($_REQUEST['shortcode']);
				$this->input_shortcode = base64_decode($this->input_shortcode);  // The in-flight shortcode is passed from screen to screen using the JS function `btoa`, in flow.js, which encodes it
				$this->input_shortcode = json_decode($this->input_shortcode);
			}

			$this->editor_shortcode = [
				'provider' => '',
			];

			if (!empty($this->input_shortcode) && !empty($this->input_shortcode->shortcode)) {
				$this->editor_shortcode_text = esc_attr($this->input_shortcode->content);
				$shortcode = $this->input_shortcode->shortcode;
				$attrs = $shortcode->attrs;
				$attrs = $attrs->named;
				if ((!empty($attrs->type) && in_array($attrs->type, ['wp', 'flickr', 'smugmug', 'picasa', 'google', 'zenfolio', 'instagram'], true)) ||
					(empty($attrs->type) && !empty($attrs->style)) && in_array($attrs->style, ['square', 'circle', 'random', 'masonry', 'mosaic', 'strip-above', 'strip-below', 'strip-right', 'no-strip'], true)) {
					$this->editor_shortcode['provider'] = !empty($attrs->type) ? $attrs->type : 'wp';
				}
			}
		}
	}

	public function render() {
		if (wp_verify_nonce($_REQUEST['nonce'], 'photonic-wizard-' . get_current_user_id())) {
			?>
		<div id="photonic-flow-wrapper" data-current-screen="1">
			<form id="photonic-flow" data-photonic-submission="" data-photonic-submission-pending="">
				<input type="hidden" name="post_id" value="<?php echo esc_attr($_REQUEST['post_id']); ?>"/>
				<input name="photonic-editor-shortcode" id="photonic-editor-shortcode" type="hidden"
					   value="<?php echo !empty($this->editor_shortcode_text) ? esc_attr($this->editor_shortcode_text) : ''; ?>"/>
				<input name="photonic-editor-shortcode-raw" id="photonic-editor-shortcode-raw" type="hidden"
					   value="<?php echo !empty($_REQUEST['shortcode']) ? esc_attr($_REQUEST['shortcode']) : ''; ?>"/>
				<input name="photonic-editor-json" id="photonic-editor-json" type="hidden" value=""/>
				<input name="photonic-gutenberg-active" id="photonic-gutenberg-active" type="hidden" value=""/>
				<div id="photonic-flow-provider" class="photonic-flow-screen photonic-gallery" data-screen="1">
					<!-- Provider selection -->
					<h1><?php esc_html_e('Choose Gallery Source', 'photonic'); ?></h1>
					<div class='photonic-flow-selector-container photonic-flow-provider'
						 data-photonic-flow-selector-mode='single-no-plus' data-photonic-flow-selector-for="provider">
						<input type="hidden" id="provider" name="provider"
							   value="<?php echo esc_attr($this->editor_shortcode['provider']); ?>"/>

						<?php
						$providers = [
							'wp'        => 'WordPress',
							'flickr'    => 'Flickr',
							'smugmug'   => 'SmugMug',
							'google'    => 'Google Photos',
							'zenfolio'  => 'Zenfolio',
							'instagram' => 'Instagram',
						];
						foreach ($providers as $provider => $desc) {
							?>
							<div class="photonic-flow-selector photonic-flow-provider-<?php echo esc_attr($provider); ?> <?php echo $provider === $this->editor_shortcode['provider'] ? 'selected' : ''; ?>"
								 title="<?php echo esc_attr($desc); ?>">
								<span class="photonic-flow-selector-inner photonic-provider"
									  data-photonic-selection-id="<?php echo esc_attr($provider); ?>">&nbsp;</span>
								<div class='photonic-flow-selector-info'><?php echo wp_kses_post($desc); ?></div>
							</div>
							<?php
						}
						?>

					</div>
					<div class="photonic-editor-info">
						<?php
						if (empty($this->editor_shortcode_text)) {
							echo '<div>' . sprintf(esc_html__('%1$sHint:%2$s To edit an existing shortcode select the shortcode before clicking on the "Add / Edit Photonic Gallery" button.', 'photonic'), '<strong>', '</strong>') . '</div>';
						}
						?>
					</div>
				</div>

				<!-- "Display Type" selection -->
				<div class="photonic-flow-screen" data-submitted="" data-screen="2">
				</div>

				<!-- Gallery Builder -->
				<div class="photonic-flow-screen" data-submitted="" data-screen="3">
				</div>

				<!-- Layout Selection -->
				<div class="photonic-flow-screen" data-submitted="" data-screen="4">
				</div>

				<!-- Layout Options -->
				<div class="photonic-flow-screen" data-submitted="" data-screen="5">
				</div>

				<!-- Final shortcode -->
				<div class="photonic-flow-screen" data-submitted="" data-screen="6">
				</div>

				<div id="photonic-flow-navigation" class="photonic-flow-navigation">
					<?php
					$user = get_current_user_id();
					if (0 === $user) {
						$user = wp_rand(1);
					}
					$nonce = wp_create_nonce('photonic-wizard-next-' . $user);
					?>
					<a href="#" id="photonic-nav-previous" class="previous disabled">Previous</a>
					<a href="#" id="photonic-nav-next" class="next" data-photonic-nonce="<?php echo esc_attr($nonce); ?>">Next</a>
				</div>

				<input type="hidden" id="selected_data" name="selected_data"/>
				<input type="hidden" id="selection_passworded" name="selection_passworded"/>
			</form>
		</div>
		<div class="photonic-waiting"></div>
			<?php
		}
	}
}

$photonic_screen_flow = new Screen_Flow();
$photonic_screen_flow->render();
