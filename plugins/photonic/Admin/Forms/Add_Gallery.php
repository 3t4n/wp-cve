<?php

namespace Photonic_Plugin\Admin\Forms;

use Photonic_Plugin\Core\Photonic;

if (!current_user_can('edit_posts')) {
	wp_die(esc_html__('You are not authorized to use this capability.', 'photonic'));
}

/**
 * Creates a form in the "Add Media" screen under the new "Photonic" tab. This form lets you insert the gallery shortcode with
 * the right arguments for native WP galleries, Flickr, Google Photos, SmugMug, Zenfolio and Instagram.
 */
class Add_Gallery {
	private static $instance = null;

	private function __construct() {
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new Add_Gallery();
		}
		return self::$instance;
	}

	public function build_form() {
		global $photonic_alternative_shortcode;
		$shortcode = empty($photonic_alternative_shortcode) ? 'gallery' : $photonic_alternative_shortcode;

		$selected_tab = sanitize_text_field($_GET['photonic-tab'] ?? 'default');
		if (!in_array($selected_tab, ['default', 'flickr', 'google', 'smugmug', 'zenfolio', 'instagram'], true)) {
			$selected_tab = 'default';
		}

		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				window.photonicAdminHtmlEncode = function photonicAdminHtmlEncode(value) {
					return $('<div/>').text(value).html();
				};

				$('#photonic-shortcode-form input[type="text"], #photonic-shortcode-form select').change(function () {
					var comboValues = $('#photonic-shortcode-form').serializeArray();
					var newValues = [];
					var len = comboValues.length;

					$(comboValues).each(function (i, obj) {
						var individual = this;
						if (individual['name'].trim() !== 'photonic-shortcode' && individual['name'].trim() !== 'photonic-submit' &&
							individual['name'].trim() !== 'photonic-cancel' && individual['value'].trim() !== '') {
							newValues.push(individual['name'] + "='" + photonicAdminHtmlEncode(decodeURIComponent(individual['value'].trim())) + "'");
						}
					});

					var shortcode = "[<?php echo esc_html($shortcode); ?> type='<?php echo esc_attr($selected_tab); ?>' ";
					$(newValues).each(function () {
						shortcode += this + ' ';
					});
					shortcode += ']';

					$('#photonic-preview').text(shortcode);
					$('#photonic-shortcode').val(shortcode);
				});
				$('#photonic-shortcode-form select').change();
			});
		</script>
		<?php
		require_once PHOTONIC_PATH . '/Admin/Forms/Vanilla_Form.php';
		$form = Vanilla_Form::get_instance();
		$fields = $form->get_fields();

		echo "<form id='photonic-shortcode-form' method='post' action=''>";
		$this->build_tabs($selected_tab, $fields);
	}

	private function build_tabs($selected_tab, $fields) {
		$tab_list = '';
		$field_list = [];
		$prelude = '';

		$user = get_current_user_id();
		if (0 === $user) {
			$user = wp_rand(1);
		}

		foreach ($fields as $tab => $field_group) {
			$tab_list .= "<li><a href='" . esc_url(add_query_arg(['photonic-tab' => $tab, 'nonce' => wp_create_nonce('photonic-vanilla-form-' . $user)])) . "' class='" . ($tab === $selected_tab ? 'current' : '') . "'>" . esc_attr($field_group['name']) . "</a> | </li>";
			if ($tab === $selected_tab) {
				$field_list = $field_group['fields'];
				$prelude = $field_group['prelude'] ?? '';
			}
		}

		echo "<ul class='subsubsub'>";
		if (strlen($tab_list) > 8) {
			$tab_list = substr($tab_list, 0, -8);
		}
		echo wp_kses_post($tab_list);
		echo "</ul>";

		if (!empty($prelude)) {
			echo "<p class='prelude'>";
			echo wp_kses_post($prelude);
			echo "</p>";
		}

		$this->build_table($field_list);
		$this->show_shortcode_preview();
	}

	private function build_table($field_list) {
		echo "<table class='photonic-form'>";
		foreach ($field_list as $field) {
			echo "<tr>";
			echo wp_kses_post("<th scope='row'>{$field['name']} " . (isset($field['req']) && $field['req'] ? '(*)' : '') . " </th>");
			switch ($field['type']) {
				case 'text':
					echo "<td><input type='text' name='" . esc_attr($field['id']) . "' value='" . esc_attr($field['std'] ?? '') . "' /></td>";
					break;

				case 'select':
					echo "<td><select name='" . esc_attr($field['id']) . "'>";
					$default = $field['std'] ?? '';
					foreach ($field['options'] as $option_name => $option_value) {
						if ($option_name === $default) {
							$selected = 'selected';
						}
						else {
							$selected = '';
						}
						echo "<option value='" . esc_attr($option_name) . "' " . esc_attr($selected) . ">" . esc_attr($option_value) . "</option>";
					}
					echo "</select></td>";
					break;

				case 'raw':
					echo "<td>" . wp_kses($field['std'], ['select' => ['name'], 'option' => ['value', 'selected']]) . "</td>";
					break;
			}
			echo "<td class='hint'>" . wp_kses_post($field['hint'] ?? '') . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	private function show_shortcode_preview() {
		echo "<div class='preview'>";
		echo "<script type='text/javascript'></script>";
		echo "<h4>" . esc_html__('Shortcode preview', 'photonic') . "</h4>";
		echo "<pre class='html' id='photonic-preview' name='photonic-preview'></pre>";
		echo "<input type='hidden' id='photonic-shortcode' name='photonic-shortcode' />";
		echo "</div>";

		echo "<div class='button-panel'>";
		echo wp_kses(get_submit_button(esc_html__('Insert into post', 'photonic'), 'primary', 'photonic-submit', false), Photonic::$safe_tags);
		echo wp_kses(get_submit_button(esc_html__('Cancel', 'photonic'), 'delete', 'photonic-cancel', false), Photonic::$safe_tags);
		echo "</div>";
	}
}

if (current_user_can('edit_posts') && isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'photonic-vanilla-form-' . get_current_user_id())) {
	if (isset($_POST['photonic-submit'])) {
		$photonic_user_shortcode = stripslashes(sanitize_text_field($_POST['photonic-shortcode']));
		media_send_to_editor($photonic_user_shortcode);
		return;
	}
	elseif (isset($_POST['photonic-cancel'])) {
		media_send_to_editor('');
		return;
	}
}

$photonic_add_gallery_form = Add_Gallery::get_instance();
$photonic_add_gallery_form->build_form();
