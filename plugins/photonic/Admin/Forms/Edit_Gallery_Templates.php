<?php

namespace Photonic_Plugin\Admin\Forms;

class Edit_Gallery_Templates {
	private $fields;
	private $providers;
	private static $instance = null;

	private function __construct() {
		require_once PHOTONIC_PATH . '/Admin/Forms/Vanilla_Form.php';
		$form = Vanilla_Form::get_instance();
		$this->fields = $form->get_fields();

		$this->providers = ['default', 'flickr', 'google', 'smugmug', 'zenfolio', 'instagram'];
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new Edit_Gallery_Templates();
		}
		return self::$instance;
	}

	public function render() {
		foreach ($this->providers as $provider) {
			?>
			<script type="text/html" id="tmpl-photonic-editor-<?php echo esc_attr($provider); ?>">
				<?php
				$field_list = $this->fields[$provider]['fields'];
				echo "<div class='photonic-form'>\n";
				echo "<h2>Photonic " . wp_kses_post('default' === $provider ? 'WP' : $provider) . " Gallery Settings</h2>\n";
				foreach ($field_list as $field) {
					echo "\t<label class='setting'>\n";
					echo "\t\t<span class='label'>" . wp_kses_post($field['name'] . (isset($field['req']) && $field['req'] ? '(*)' : '')) . " </span>\n";
					switch ($field['type']) {
						case 'text':
							echo "\t\t<input type='text' name='" . esc_attr($field['id']) . "' value='" . esc_attr($field['std'] ?? '') . "' />\n";
							break;

						case 'select':
							echo "\t\t<select name='" . esc_attr($field['id']) . "'>\n";
							$default = $field['std'] ?? '';
							foreach ($field['options'] as $option_name => $option_value) {
								if ($option_name === $default) {
									$selected = 'selected';
								}
								else {
									$selected = '';
								}
								echo "\t\t\t<option value='" . esc_attr($option_name) . "' " . esc_attr($selected) . ">" . esc_attr($option_value) . "</option>\n";
							}
							echo "\t\t</select>\n";
							break;

						case 'raw':
							echo "\t\t" . wp_kses($field['std'], ['select' => ['name'], 'option' => ['value', 'selected']]) . "\n";
							break;
					}
					echo "\t\t<span class='hint'>" . wp_kses_post($field['hint'] ?? '') . "</span>\n";
					echo "\t</label>\n";
				}
				echo "</div>\n";
				?>
			</script>
			<?php
		}
	}
}

$photonic_edit_gallery = Edit_Gallery_Templates::get_instance();
$photonic_edit_gallery->render();
