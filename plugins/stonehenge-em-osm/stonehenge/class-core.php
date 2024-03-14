<?php
if( !defined('ABSPATH') ) exit;
include_once(ABSPATH.'wp-admin/includes/plugin.php');

if( !class_exists('Stonehenge_Core') ) :
Class Stonehenge_Core {


	#===============================================
	public function __construct($plugin) {
		$this->set_variables($plugin);
		$this->core_actions($plugin);
		$this->plugin_actions($plugin);
	}


	#===============================================
	public function core_actions( $plugin ) {
		$this->load_translations( $plugin );
		add_filter('plugin_action_links', array($this, 'add_settings_link'), 10, 2);
		add_filter('plugin_row_meta', array($this, 'add_plugin_links'), 10, 2);
		add_action('admin_enqueue_scripts', array($this, 'register_core_assets'), 20);
		add_action('wp_enqueue_scripts', array($this, 'register_core_assets'), 20);
		add_filter('stonehenge_content', 'do_shortcode');
		add_action('wp_ajax_stonehenge_mailer', array($this, 'mailer_actions'));
		add_action('wp_ajax_stonehenge_form', array($this, 'form_actions'));

		$this->init_updater($plugin);
		$this->check_for_licensed();
		$this->plugin_updated($plugin);
	}


	#===============================================
	public function plugin_actions( $plugin ) {
		if( !has_action('stonehenge_menu') ) {
			new Stonehenge_Creations();
		}
		add_action('stonehenge_menu', array($this, 'create_sub_menu'), $plugin['prio']);

		if( $this->check_dependency($plugin) ) {
			add_action('admin_init', array($this, 'register_options'));
			add_action('admin_enqueue_scripts', array($plugin['class'], 'register_assets'), 20);
			add_action('wp_enqueue_scripts', array($plugin['class'], 'register_assets'), 20);

			if( $this->is_valid ) {
				if( !is_array($plugin['options']) && is_backend() ) {
					$this->show_settings_notice($plugin, 'error');
				}
				do_action("{$plugin['base']}_loaded", $plugin);
			}
			// Maybe show settings errors.
			if( method_exists($plugin['class'], 'show_errors') ) {
				$plugin['class']::show_errors( $plugin );
			}
		}
	}


	#===============================================
	public function plugin_updated( $plugin ) {
		$saved_version = get_site_option("{$plugin['base']}_version");
		if( version_compare($saved_version, $this->version, '<') ) {
			$this->do_cleanup($plugin);
			if( method_exists($plugin['class'], 'plugin_updated') ) {
				$plugin['class']::plugin_updated( $plugin );
			}

			// Prevent loop.
			if( !add_site_option("{$plugin['base']}_version", $this->version, '', 'no') ) {
				update_site_option( "{$plugin['base']}_version", $this->version, '', 'no' );
			}
		}
	}


	#===============================================
	public function add_settings_link($links, $file) {
		if( $file != $this->file ) {
			return $links;
		}
		else {
			$settings_link = sprintf( '<a href="%s">%s</a>', $this->url, __wp('Settings') );
			array_unshift($links, $settings_link);
			return $links;
		}
	}


	#===============================================
	public function add_plugin_links($links, $file) {
		if( $file != $this->file ) {
			return $links;
		}
		$plugin 	= $this->plugin;
		$base 		= $plugin['base'];
		$style 		= 'style="color:red !important;"';
		$author		= 'DuisterDenHaag';
		$support 	= array('<a href="'. admin_url('admin.php?page=stonehenge_support') .'" '.$style.'>Premium Support</a>');
		$free 		= array('<a href="'. admin_url('admin.php?page=stonehenge_forums') .'">Limited Free Support</a>');
		$more 		= array('<a href="'. admin_url('admin.php?page=stonehenge-plugins') .'">More Goodies</a>');
		return $this->is_licensed ? array_merge($links, $support, $more) : array_merge($links, $more, $free);
	}


	#===============================================
	public function register_core_assets() {
		$version = $this->core_version;
		//Development.
//		wp_register_style('stonehenge-admin', plugins_url('assets/unminified/stonehenge-admin.less', __FILE__), '', $version, 'all');
//		wp_register_script('stonehenge-admin', plugins_url('assets/unminified/stonehenge-admin.js', __FILE__), array('jquery'), $version, true);
//		wp_register_style('stonehenge-public', plugins_url('assets/unminified/stonehenge-public.less', __FILE__), '', $version, 'all');
//		wp_register_script('stonehenge-public', plugins_url('assets/unminified/stonehenge-public.js', __FILE__), array('jquery'), $version, true);

		// Admin.
		wp_register_style('stonehenge-admin', plugins_url('assets/stonehenge-admin.min.css', __FILE__), '', $version, 'all');
		wp_register_script('stonehenge-admin', plugins_url('assets/stonehenge-admin.min.js', __FILE__), array('jquery'), $version, true);
		wp_localize_script('stonehenge-admin', 'CORE', $this->localize_core_assets());

		// Public.
		wp_register_style('stonehenge-public', plugins_url('assets/stonehenge-public.min.css', __FILE__), '', $version, 'all');
		wp_register_script('stonehenge-public', plugins_url('assets/stonehenge-public.min.js', __FILE__), array('jquery'), $version, true);

		// Parsley Validation.
		$locale = substr(get_bloginfo('language'), 0, 2);
		wp_register_script('parsley-validation', plugins_url('assets/parsley/parsley.min.js', __FILE__), array('jquery'), $version, true);
		wp_register_script('parsley-locale', plugins_url('assets/parsley/i18n/'. $locale .'.js', __FILE__), array('jquery'), $version, true);
		wp_register_script('parsley-locale-extra', plugins_url('assets/parsley/i18n/'. $locale .'.extra.js', __FILE__), array('jquery'), $version, true);

		$this->parsley = array('parsley-validation', 'parsley-locale', 'parsley-locale-extra');

		// Datepicker.
		wp_register_style('stonehenge-datepicker', plugins_url('assets/datepicker.min.css', __FILE__), '', $version, 'screen');

		// Timepicker.
		wp_register_style('stonehenge-timepicker', plugins_url('assets/timepicker.min.css', __FILE__), '', $version, 'all');
		wp_register_script('stonehenge-timepicker', plugins_url('assets/timepicker.min.js', __FILE__), array('jquery'), $version, true);
	}


	#===============================================
	public function load_admin_assets() {
		wp_enqueue_style('stonehenge-admin');
		wp_enqueue_script('stonehenge-admin');
		wp_enqueue_script( array('parsley-validation', 'parsley-locale', 'parsley-locale-extra') );
	}


	#===============================================
	public function load_public_assets() {
		wp_enqueue_style('stonehenge-public');
		wp_enqueue_script('stonehenge-public');
		wp_enqueue_script( array('parsley-validation', 'parsley-locale', 'parsley-locale-extra') );
	}


	#===============================================
	public function load_datepicker() {
		wp_enqueue_style('stonehenge-datepicker');
		wp_enqueue_script('jquery-ui-datepicker');
	}


	#===============================================
	public function load_timepicker() {
		wp_enqueue_style('stonehenge-timepicker');
		wp_enqueue_script('stonehenge-timepicker');
	}


	#===============================================
	public function localize_core_assets() {
		$text 		= $this->plugin['text'];
		$localize 	= array(
			'is_ready' 			=> is_array($this->plugin['options']) ? true : false,
			'add' 				=> __wp('Add'),
			'remove'			=> __wp('Remove'),
			'edit' 				=> __wp('Edit'),
			'clear' 			=> __wp('Clear'),
			'choose' 			=> __wp('Select file'),
			'showError' 		=> __('Not all required fields were filled out. Please check all sections and try again.', $text),
			'locale'			=> strtolower( substr(get_bloginfo('language'), 0, 2) ),
			'date_format'		=> $this->get_date_format(),
			'currency' 			=> get_option('dbem_bookings_currency', 'USD'),

			// Date Picker.
			'next'				=> __wp('Next'),
			'previous' 			=> __wp('Previous'),
			'dateFormat' 		=> stonehenge()->php_date_to_js(),
			'daysFull'			=> $this->localize_datepicker('weekdays_full', true, false),
			'daysShort'			=> $this->localize_datepicker('weekdays_short', false, true),
			'monthsFull'		=> $this->localize_datepicker('months_full', true, false),
			'monthsShort'		=> $this->localize_datepicker('months_short', true, false),
			'yearRange' 		=> "-1:+4",

			// Time Picker.
			'time_format'		=> $this->get_time_format(),
		);
		return $localize;
	}


	#===============================================
	public function load_translations( $plugin ) {
		load_default_textdomain(); 	// Fallback => Since WordPress 5.4 default translations load later.
		$text 	= $plugin['text'];
		$file 	= WP_PLUGIN_DIR .'/'. $text;
		$locale = apply_filters( 'plugin_locale', function_exists( 'determine_locale' ) ? determine_locale() : get_locale(), $text );
		$mofile = ( $file ) . '/languages/'. $text . '-' . $locale . '.mo';
		$loaded = load_textdomain( $text, $mofile );
		if( !$loaded ) { $loaded = load_plugin_textdomain( $text, false, '/languages/' ); }
		if( !$loaded ) { $loaded = load_muplugin_textdomain( $text, '/languages/' ); }
	}


	#===============================================
	public function create_sub_menu() {
		$plugin = $this->plugin;
		$page 	= add_submenu_page('stonehenge-creations', $plugin['name'], $plugin['short'], 'manage_options', $plugin['slug'], array($this, 'show_options_page') );
		add_action('admin_print_styles-'.$page, array($this, 'load_admin_assets'));
		add_action('admin_print_styles-'.$page, array($plugin['class'], 'load_admin_assets'));
	}


	#===============================================
	public function show_options_page() {
		$plugin 	= $this->plugin;
		$sections 	= $this->get_plugin_options($plugin);
		$count 		= count($sections);

		?>
		<div class="wrap">
			<h1><?php echo sprintf('%s %s – %s', $plugin['icon'], $plugin['name'], __wp('Settings') ); ?></h1>

			<?php settings_errors(); ?>
			<div class="listFieldError" style="display:none;"></div>
			<br style="clear:both;">
			<?php
			if( !$this->check_dependency($plugin) ) {
				$this->show_dependency($plugin);
				return;
			}

			do_action('stonehenge_before_form', $plugin);

			if( $this->is_licensed ) {
				$this->show_license($plugin);
			}

			if( $this->is_valid ) {
				if( $sections ) :
					?>
					<form method="post" action="options.php" id="stonehenge-options-form" autocomplete="off" data-parsley-validate="" novalidate="">
						<?php
						settings_fields($plugin['slug']);
						do_settings_sections($plugin['slug']);
						do_action('stonehenge_before_options', $plugin);

						foreach( $sections as $section_id => $section ) {
							$this->render_metabox( $section, $section_id, $count );
						}

						do_action('stonehenge_after_options', $plugin);

						?>
						<div class="listFieldError" style="display:none;"></div>
						<input type="submit" class="button-primary" value="<?php echo __wp('Save Changes'); ?>" onclick="setTimeout(showErrors, 100)">
					</form>
					<?php
				endif;
			}
			do_action('stonehenge_after_form', $plugin);
			Stonehenge_Creations_Plugins::show_new_plugin();
			?>
			<br style="clear:both;">
		</div>
		<?php
	}


	#===============================================
	public function get_plugin_options( $plugin, $sections = array() ) {
		$slug 		= $plugin['slug'];
		$sections 	= apply_filters("{$slug}_options", $sections);
		return $sections;
	}


	#===============================================
	public function register_options() {
		$slug = $this->plugin['slug'];
		register_setting( $slug, $slug, array($this, 'sanitize_options') );
	}


	#===============================================
	public function sanitize_options( $input, $sections = array() ) {
		$slug 		= $this->plugin['slug'];
		$sections 	= apply_filters("{$slug}_options", $sections);
		if( count( (array) $sections) > 1 ) {
			$clean = $this->sanitize_multiple( $input, $sections );
		} else {
			$clean = $this->sanitize_single( $input, $sections );
		}
		return $clean;
	}


	#===============================================
	public function sanitize_multiple($input, $sections) {
		$clean = array();
		foreach($sections as $section) {
			foreach($section['fields'] as $fields => $field) {
				$id 	= "{$section['id']}_{$field['id']}";
				$type 	= $field['type'];
				foreach($input as $tabs => $tab) {
					foreach($tab as $key => $value) {
						$tab_key = $tabs.'_'.$key;
						switch($type) {
							case 'text':
							case 'number':
							case 'tel':
							case 'phone':
							case 'password':
							case 'select':
							case 'dropdown':
							case 'color':
								if($id === $tab_key) { $clean[$tabs][$key] = sanitize_text_field(stripslashes($input[$tabs][$key])); }
							break;
							case 'time':
								if($id === $tab_key) { $clean[$tabs][$key] = sanitize_text_field(stripslashes(date("H:i:s",strtotime($input[$tabs][$key])))); }
							break;
							case 'date':
								if($id === $tab_key) { $clean[$tabs][$key] = sanitize_text_field(stripslashes(date("Y-m-d",strtotime($input[$tabs][$key])))); }
							break;
							case 'toggle':
							case 'switch':
							case 'radio':
								if($id === $tab_key) { $clean[$tabs][$key] = sanitize_text_field(wp_unslash($input[$tabs][$key])); }
							break;
							case 'email':
								if($id === $tab_key) { $clean[$tabs][$key] = strtolower(sanitize_email($input[$tabs][$key])); }
							break;
							case 'textarea':
							case 'editor':
							case 'tiny':
								if($id === $tab_key) { $clean[$tabs][$key] = wp_kses_allowed(stripslashes($input[$tabs][$key])); }
							break;
							case 'url':
							case 'media':
							case 'image':
							case 'file':
								if($id === $tab_key) { $clean[$tabs][$key] = esc_url_raw($input[$tabs][$key]); }
							break;
							case 'checkbox':
							case 'flip':
							case 'pages':
								if($id === $tab_key) { $clean[$tabs][$key] = sanitize_key($input[$tabs][$key]); }
							break;
							case 'checkboxes':
								if($id === $tab_key) { $clean[$tabs][$key] = @array_map('sanitize_text_field', wp_unslash($input[$tabs][$key])); }
							break;
							case 'multi':
							case 'select_multi':
								if($id === $tab_key) { $clean[$tabs][$key] = array_map('sanitize_text_field', $input[$tabs][$key]); }
							break;
							case 'feedback':
								if($id === $tab_key) { $clean[$tabs][$key] = wp_kses_some(stripslashes($input[$tabs][$key])); }
							break;
							case 'custom':
								if($tabs === 'items') { $clean[$tabs][$key] = @array_map('sanitize_text_field', wp_unslash($input[$tabs][$key])); }
							break;
						}
					}
				}
			}
		}
		return $clean;
	}


	#===============================================
	public function sanitize_single($input, $sections) {
		$clean = array();
		foreach( $sections[0]['fields'] as $fields => $field ) {
			$id = $field['id'];
			foreach( $input as $key => $value ) {
				switch($field['type']) {
					case 'text':
					case 'number':
					case 'tel':
					case 'phone':
					case 'password':
					case 'select':
					case 'dropdown':
					case 'color':
						if($id === $key) { $clean[$key] = sanitize_text_field(stripslashes($input[$key])); }
					break;
					case 'time':
						if($id === $key) { $clean[$key] = sanitize_text_field(stripslashes(date("H:i:s",strtotime($input[$key])))); }
					break;
					case 'date':
						if($id === $key) { $clean[$key] = sanitize_text_field(stripslashes(date("Y-m-d",strtotime($input[$key])))); }
					break;
					case 'toggle':
					case 'switch':
					case 'radio':
						if($id === $key) { $clean[$key] = sanitize_text_field(wp_unslash($input[$key])); }
					break;
					case 'email':
						if($id === $key) { $clean[$key] = strtolower(sanitize_email($input[$key])); }
					break;
					case 'textarea':
					case 'editor':
					case 'tiny':
						if($id === $key) { $clean[$key] = wp_kses_allowed(stripslashes($input[$key])); }
					break;
					case 'url':
					case 'media':
					case 'image':
					case 'file':
						if($id === $key) { $clean[$key] = esc_url_raw($input[$key]); }
					break;
					case 'checkbox':
					case 'pages':
					case 'flip':
						if($id === $key) { $clean[$key] = sanitize_key($input[$key]); }
					break;
					case 'checkboxes':
						if($id === $key) { $clean[$key] = @array_map('sanitize_text_field', wp_unslash($input[$key])); }
					break;
					case 'multi':
					case 'select_multi':
						if($id === $key) { $clean[$key] = array_map('sanitize_text_field', $input[$key]); }
					break;
					case 'feedback':
						if($id === $tab_key) { $clean[$key] = wp_kses_some(stripslashes($input[$key])); }
					break;
				}
			}
		}
		return $clean;
	}


	#===============================================
	public function render_fields( $fields, $section_id, $count = 1 ) {
		$slug 		= $this->plugin['slug'];
		$database 	= get_option( $slug );
		$saved 		= ($count > 1) ? ($database[$section_id] ?? null) : ($database ?? null);
		$section_id	= $section_id === 0 ? $slug : $section_id;
		$prefix 	= ($count > 1) ? "{$slug}[{$section_id}]" : $section_id;

		is_backend() ? $this->load_admin_assets() : $this->load_public_assets();

		foreach( $fields as $field ) {
			$type 		= $field['type'];
			$field_id	= ($count > 1) ? "{$section_id}_{$field['id']}" : $field['id'];
			$id 		= esc_attr( str_replace('-', '_', $field_id) );
			$name 		= esc_attr("{$prefix}[{$field['id']}]");
			$row 		= !empty($field['id']) ? str_replace('_', '-', "{$section_id}-{$field['id']}") : str_replace('_', '-', "{$section_id}");
			$class 		= isset($field['class']) && !empty($field['class']) ? "class='{$field['class']}'" : null;
			$min 		= esc_attr( $field['min'] ?? '1' );
			$max 		= esc_attr( $field['max'] ?? '9999' );
			$required 	= isset($field['required']) && $field['required'] === true ? " required='required'" : null;
			$indicator 	= $required ? " class='indicator'" : null;
			$before 	= isset($field['before']) && !empty($field['before']) ? "<span class='before'>{$field['before']}</span>&nbsp;&nbsp;" : null;
			$after 		= isset($field['after']) && !empty($field['after']) ? "&nbsp;&nbsp;<span class='after'>{$field['after']}</span>" : null;
			$helper 	= isset($field['helper']) && !empty($field['helper']) ? "<p class='description'>{$field['helper']}</p>" : null;
			$choices	= isset($field['choices']) && is_array($field['choices']) ? $field['choices'] : array( 'no' => __wp('No'), 'yes' => __wp('Yes') );
			$parsley 	= isset($field['parsley']) ? sprintf('data-parsley-%1$s="%2$s" data-parsley-%1$s-message="%3$s"',
				$field['parsley']['function'], $field['parsley']['field'], $field['parsley']['message']) : null;

			$default 	= $field['default'] ?? null;
			$value 		= $saved[$field['id']] ?? $default;

			$label 		= isset($field['label']) ? ucfirst($field['label']) : null;
			$label 		= "<th scope='row'><label for='{$id}'{$indicator}>{$label}</label></th><td>{$before}";

			if( $type === 'loader' ) {
				echo $default;
			} elseif( $type === 'hidden' ) {
				echo sprintf("<div class='hidden'><input type='hidden' name='%s' id='%s' value='%s' readonly></div>", $name, $id, $value);
			} else {
				echo "<tr class='{$row}'>";
				switch( $type ) {
					case 'text':
					case 'email':
					case 'password':
					case 'url':
						echo sprintf('%s<input type="%s" id="%s" name="%s" value="%s" %s %s %s>',
							$label, $type, $id, $name, esc_attr($value), $class, $required, $parsley);
						break;
					case 'phone':
					case 'tel':
						echo sprintf('%s<input type="tel" id="%s" name="%s" value="%s" %s %s %s data-parsley-type="digits">',
							$label, $id, $name, esc_attr($value), $class, $required, $parsley);
						break;
					case 'number':
						$step 	= $field['step'] ?? '1';
						$value 	= isset($field['decimals']) ? number_format($value, (int) $field['decimals']) : $value;
						echo sprintf('%s<input type="%s" id="%s" name="%s" value="%s" min="%s" max="%s" step="%s" %s %s>',
							$label, $type, $id, $name, esc_attr($value), $min, $max, $step, $required, $parsley);
						break;
					case 'date':
						$this->load_datepicker();
						echo sprintf('%s<input type="text" name="%s" value="%s" class="pickadate" autocomplete="off" size="20" %s>',
							$label, $name, esc_attr($value), $required);
						break;
					case 'time':
						$this->load_timepicker();
						$value = $this->localize_time( $value );
						echo sprintf('%s<input type="text" id="%s" name="%s" value="%s" class="pickatime" size="10" autocomplete="off">',
							$label, $id, $name, esc_attr($value) );
						break;
					case 'select':
					case 'dropdown':
						echo "{$label}<select name='{$name}' id='{$id}' {$required} {$class}>";
						echo "<option disabled selected>[ ". esc_attr( __wp('Select') )." ]</option>";
						foreach( $choices as $k => $v ) {
							if( is_array($v) ) {
								$show = array_values($v);
								$show = esc_attr($show[0]);
								$data = null;
								foreach( $v as $i => $j ) {
									$data .= " data-{$i}='$j' ";
								}
								$index 		= ($k - 1);
								$selected 	= ($value && $k == $value) ? esc_attr(" selected") : null;
								echo "<option value='{$k}'{$selected} {$data}>{$show}</option>";
							} else {
								$selected = ($value && $k == $value) ? esc_attr(" selected") : null;
								echo "<option value='{$k}'{$selected}>{$v}</option>";
							}
						}
						echo "</select>";
						break;
					case 'radio':
						echo "{$label}<div class='radios'>";
						$checked = count($choices) === 1 ? esc_attr("checked='checked'") : null;
						$c = 0;
						foreach( $choices as $k => $v ) {
							echo sprintf('<input type="radio" id="%s" name="%s" value="%s" %s %s> <label for="%s">%s</label>%s',
								"{$id}_{$k}", $name, $k, "data-parsley-group='status' {$required}","{$checked}", "{$id}_{$k}", $v, ($c < count($choices) - 1 ? '<br>' : ''));
							$c++;
						}
						echo '</div>';
						break;
					case 'checkbox':
						echo "<td colspan='2'><div class='checkboxes'>";
						echo 	"<input type='checkbox' name='{$name}' id='{$id}' value='yes' class='field'{$required}>&nbsp;";
						echo 	"<label for='{$id}' {$indicator}>{$field['label']}</label></div>";
						break;
					case 'checkboxes':
						echo "{$label}<div id='checkboxes' class='checkboxes'>";
						foreach( $choices as $key => $result) {
							$checked = in_array($key, (array) $value) ? esc_attr('checked=checked') : null;
							echo sprintf( '<input type="checkbox" id="%s" name="%s" value="%s" %s %s> <label for="%s">%s</label><br>',
								esc_attr($section_id.'_'.$key),
								$name.'[]',
								esc_attr($key),
								$checked,
								'data-parsley-group="status" '.$required,
								esc_attr($section_id.'_'.$key), esc_html($result)
							);
						}
						echo '</div>';
						break;
					case 'textarea':
						echo sprintf('%s<textarea id="%s" name="%s" rows="6" %s>%s</textarea>',
							$label, $id, $name, $required, wp_kses_allowed(stripslashes($value)) );
						break;
					case 'tiny':
						echo sprintf('%s<textarea id="%s" name="%s" rows="6" class="tiny-editor" data-type="textarea" data-parsley-errors-container="#errordiv" %s>%s</textarea>', $label, $id, $name, $required, wp_kses_allowed(stripslashes($value)) );
						break;
					case 'editor':
						$id = "editor_{$id}"; 	// Required for front-end. 'content' will place the buttons wrong.
						$required  = $field['required'] ?? false;
						echo "{$label}";
						if( $required ) { add_filter('the_editor', 'wp_editor_required', 10, 1); }
						$args = array(
						    'wpautop' => true,
						    'media_buttons' => false,
						    'textarea_name' => $name,
						    'textarea_rows' => 8,
						    'editor_css' => false,
						    'editor_class' => false,
						    'teeny' => false,
						    'dfw' => true,
						    'tinymce' => true, // <-----
						    'quicktags' => true
						);
						wp_editor( wp_kses_allowed(stripslashes($value)), $id, $args );
						if( $required ) { remove_filter('the_editor', 'wp_editor_required', 10, 1); }
						break;
					case 'file':
						wp_enqueue_script( 'jquery' );
						wp_enqueue_media();
						$file 	= esc_attr( __wp('Select file') );
						$remove = esc_attr( __wp('Clear') );
						$input 	= sprintf('<input type="url" id="%s" name="%s" value="%s" class="%s filename" readonly %s>',
							$id, $name, esc_url_raw($value), 'regular-text', $required);
						$select = sprintf('<button type="button" id="%s_button" class="button-secondary file-button" title="%s">%s</button>',
							$id, esc_attr($file), '&#x1F4CE; '. esc_html($file) );
						$clear 	= sprintf('<button type="button" class="button-secondary clear-file" title="%s">%s</button>',
							esc_attr($remove), '&#x2718;');
						echo sprintf('%s%s%s&nbsp;&nbsp;%s',
							$label, $input, $select, $clear
						);
						break;
					case 'color':
						wp_enqueue_style('farbtastic');
						wp_enqueue_script('farbtastic');
						echo sprintf('%s<div style="position:relative;"><input type="text" name="%s" id="%s" value="%s" %s><input type="button" class="pickcolor button-secondary" value="%s"><div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div></div>',
						$label, $name, $id, esc_attr($value), $required, __wp('Select Color') );
						break;
					case 'submit':
						echo "<td colspan='2'>";
						echo "<input type='submit' id='{$id}' class='button button-primary' value='{$field['label']}'";
						echo "</td>";
						break;
					case 'feedback':
						echo sprintf('%s<input type="%s" id="%s" name="%s" value="%s" %s>',
							$label, $type, $id, $name, $value, $required);
						break;
					case 'toggle':
						echo $label . '<div class="switch-toggle switch-holo" style="">';
						foreach ( $choices as $v => $l ) {
							$checked = $v === $value ? esc_attr('checked=checked') : '';
							echo sprintf( '<input type="radio" name="%s" id="%s" value="%s" %s %s><label for="%s">%s</label>',
								$name, esc_attr($id.'_'.$v), esc_attr($v), $checked, $required, esc_attr($id.'_'.$v), esc_html($l) );
						}
						echo '<a></a></div>';
					break;
					case 'flip':
						$values = array_keys($choices);
						$checked = (!empty($value) && $value != 'no') ? 'checked="checked"' : '';
						echo sprintf('%s<label class="flip"><input type="checkbox" id="%s" name="%s" value="%s" %s><span data-unchecked="%s" data-checked="%s"></span></label>',
							$label, $id, $name, $values[1], $checked, esc_attr( __wp('No')), esc_attr( __wp('Yes')) );
					break;
					case 'info':
						echo "<td colspan='2'>{$value}";
						break;
					case 'span':
						echo "{$label}{$value}";
						break;
					case 'protected':
						echo "{$label}<input type='hidden' name='{$name}' id='{$id}' value='{$value}' readonly><span class='$id'>{$value}</span>";
						break;
					case 'custom':
						$value = apply_filters('stonehenge_content', $value);
						echo "<td colspan='2'><span id='{$section_id}'>{$value}</span>";
						break;
					case 'block':
						$value = apply_filters('stonehenge_content', $value);
						echo "{$label}<div id='{$id}'><span>{$value}</span></div>";
						break;
					case 'pages':
						if( $required ) { add_filter('wp_dropdown_pages', 'wp_dropdown_pages_required', 10, 1); }
						$none = __wp('None');
						$args = array(
							'echo' 				=> 0,
							'selected'     		=> $value,
							'name' 				=> $name,
							'show_option_none' 	=> "[ {$none} ]",
						);
						$pages = wp_dropdown_pages($args);
						echo "{$label}<div id='{$id}'><span>{$pages}</span></div>";
						if( $required ) { remove_filter('wp_dropdown_pages', 'wp_dropdown_pages_required', 10, 1); }
						break;
					default:
						echo "<td colspan='2'>{$type} not defined yet.";
						break;
				}
				echo "{$after}{$helper}</td>";
				echo "</tr>";
			}
		}
	}

} // End class.
endif;
