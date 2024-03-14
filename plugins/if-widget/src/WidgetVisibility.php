<?php
namespace Layered\IfWidget;

class WidgetVisibility {

	public static function start() {
		return new static;
	}

	protected function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'assets']);
		add_action('in_widget_form', [$this, 'form'], 100, 3);
		add_action('widget_update_callback', [$this, 'update'], 10, 2);
		add_action('widget_display_callback', [$this, 'checkWidgetVisibility'], 10, 2);
	}

	public function assets() {
		global $pagenow;

		if ($pagenow === 'widgets.php' || $pagenow === 'customize.php') {
			wp_enqueue_script('vuejs', 'https://cdn.jsdelivr.net/npm/vue@2.6', [], '2.6');
			wp_enqueue_script('v-runtime-template', plugins_url('assets/v-runtime-template.min.js', dirname(__FILE__)), ['vuejs'], '1.10.0');
			wp_enqueue_script('sprintf', plugins_url('assets/sprintf.min.js', dirname(__FILE__)), [], '1.1.2');
			wp_enqueue_script('if-widget', plugins_url('assets/if-widget.js', dirname(__FILE__)), ['vuejs', 'sprintf', 'jquery-ui-dialog'], '0.5');
			wp_localize_script('if-widget', 'ifWidget', [
				'rules'		=>	apply_filters('if_visibility_rules', []),
				'texts'		=>	[
					'is'			=>	__('is', 'if-widget'),
					'is not'		=>	__('is not', 'if-widget'),
					'etc'			=>	__('etc', 'if-widget'),
					'equals'		=>	__('equals', 'if-widget'),
					'starts with'	=>	__('starts with', 'if-widget'),
					'contains'		=>	__('contains', 'if-widget'),
					'nocontains'	=>	__('doesn\'t contain', 'if-widget'),
					'equals'		=>	__('equals', 'if-widget'),
					'not equal'		=>	__('not equal', 'if-widget'),
					'select'		=>	__('select', 'if-widget')
				]
			]);

			wp_enqueue_style('if-widget', plugins_url('assets/if-widget.css', dirname(__FILE__)), ['wp-jquery-ui-dialog'], '0.5');
		}
	}

	public function form(\WP_Widget $widget, $return, array $instance) {
		$visibility = isset($instance['if-widget']) && $instance['if-widget'];
		$visibilityRules = $visibility ? json_decode($instance['if-widget'], true) : [[
			'type'		=>	'rule',
			'rule'		=>	'user-logged-in',
			'values'	=>	[1]
		]];
		?>

		<hr class="if-widget-line">

		<div class="if-widget-visibility-rules" id="if-widget-visibility-rules-<?php echo $widget->id ?>" v-cloak>
			<p>
				<a href="<?php echo admin_url('themes.php?page=if-widget') ?>" class="if-widget-help if-widget-float-right" data-tooltip="<?php esc_attr_e('Visibility rule examples', 'if-widget') ?>" title="<?php esc_attr_e('Visibility rule examples', 'if-widget') ?>"><span class="dashicons dashicons-editor-help"></span></a>
				<label><input type="checkbox" name="<?php echo esc_attr($widget->get_field_name('if-widget-enabled')) ?>" class="if-widget-is-enabled" <?php checked($visibility) ?> v-model="enabled"> <?php _e('Show widget only if', 'if-widget') ?> »</label>
			</p>

			<div v-if="enabled">
				<div v-for="(v, index) in visibility">
					<div v-if="v.type === 'rule'" class="if-widget-visibility-rule" :class="{'is-open': v.isOpen}">
						<span class="change" @click="v.isOpen = !v.isOpen"><span class="dashicons dashicons-arrow-down-alt2"></span></span>
						<span class="remove" v-show="visibility.length > 1" @click="visibility.splice(Math.max(index - 1, 0), 2)">-</span>
						<v-runtime-template :template="formatRule(v.rule, index)"></v-runtime-template>
						<ul class="options">
							<li v-for="(rule, ruleId) in rules" :class="{selected: v.rule == rule, promo: rule.callback === '__return_true'}" v-html="formatName(rule)" @click="setRule(v, rule)"></li>
						</ul>
					</div>

					<div v-if="v.type === 'op'" class="if-widget-visibility-rule-op">
						<span :class="{selected: v.op == 'and'}" @click="v.op = 'and'"><?php _e('and', 'if-widget') ?></span>
						<span :class="{selected: v.op == 'or'}" @click="v.op = 'or'"><?php _e('or', 'if-widget') ?></span>
					</div>
				</div>

				<div class="if-widget-visibility-rule-op">
					<span @click="addOp()">+</span>
				</div>
			</div>

			<input type="hidden" name="<?php echo esc_attr($widget->get_field_name('if-widget')) ?>" class="if-widget-the-rules" value="<?php echo esc_attr(json_encode($visibilityRules)) ?>" v-model="vis">
		</div>

		<?php
	}

	public function update(array $instance, array $newInstance) {

		if (isset($newInstance['if-widget-enabled'])) {
			$instance['if-widget'] = $newInstance['if-widget'];
		} else {
			unset($instance['if-widget']);
		}

		return $instance;
	}

	public function checkWidgetVisibility(array $instance, \WP_Widget $widget) {

		if (isset($instance['if-widget'])) {
			$visibilityRules = json_decode($instance['if-widget'], true);
			$rules = apply_filters('if_visibility_rules', []);

			$visibilityRules = array_map(function($visibilityRule) use($rules) {
				if ($visibilityRule['type'] === 'rule') {
					$rule = $rules[$visibilityRule['rule']];

					if ($rule['type'] === 'bool') {
						$visibilityRule['result'] = call_user_func($rule['callback']);
						$visibilityRule['result'] = $visibilityRule['values'][0] ? $visibilityRule['result'] : !$visibilityRule['result'];
						$visibilityRule = $visibilityRule['result'] ? 1 : 0;
					} elseif ($rule['type'] === 'select') {
						$visibilityRule['result'] = call_user_func($rule['callback'], $visibilityRule['values'][1]);
						$visibilityRule['result'] = $visibilityRule['values'][0] ? $visibilityRule['result'] : !$visibilityRule['result'];
						$visibilityRule = $visibilityRule['result'] ? 1 : 0;
					} elseif ($rule['type'] === 'multiple') {
						$visibilityRule['result'] = call_user_func($rule['callback'], $visibilityRule['values'][1]);
						$visibilityRule['result'] = $visibilityRule['values'][0] ? $visibilityRule['result'] : !$visibilityRule['result'];
						$visibilityRule = $visibilityRule['result'] ? 1 : 0;
					} elseif ($rule['type'] === 'text') {
						$text = call_user_func($rule['callback']);

						if ($visibilityRule['values'][0] == 'starts') {
							$visibilityRule['result'] = substr($text, 0, strlen($visibilityRule['values'][1])) === $visibilityRule['values'][1];
						} elseif ($visibilityRule['values'][0] == 'ends') {
							$visibilityRule['result'] = substr($text, -strlen($visibilityRule['values'][1])) === $visibilityRule['values'][1];
						} elseif ($visibilityRule['values'][0] == 'contains') {
							$visibilityRule['result'] = strpos($text, $visibilityRule['values'][1]) !== false;
						} elseif ($visibilityRule['values'][0] == 'nocontains') {
							$visibilityRule['result'] = strpos($text, $visibilityRule['values'][1]) === false;
						} elseif ($visibilityRule['values'][0] == 1) {
							$visibilityRule['result'] = $text == $visibilityRule['values'][1];
						} elseif ($visibilityRule['values'][0] == 0) {
							$visibilityRule['result'] = $text != $visibilityRule['values'][1];
						}

						$visibilityRule = $visibilityRule['result'] ? 1 : 0;
					}
				} else {
					$visibilityRule = $visibilityRule['op'];
				}

				return $visibilityRule;
			}, $visibilityRules);

			if ((count($visibilityRules) === 1 && $visibilityRules[0] == 0) || (count($visibilityRules) > 1 && !eval('return ' . implode(' ', $visibilityRules) . ';'))) {
				$instance = false;
			}
		}

		return $instance;
	}

}
