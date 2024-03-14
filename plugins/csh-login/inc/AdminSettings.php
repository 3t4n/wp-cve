<?php
namespace NtqAdminSetting;

// AdminSettings Class.
class AdminSettings {
	// NamTQ 18/07/2017-----------Goodluck, Have fun-----------//
	protected $optionName = ''; // remember to get data.
	protected $menuData = array(); // menu data.
	protected $fields = array(); // attribute of all fields.
	protected $sections = array(); // attribute of all sections.

	public function __construct() {
		$this->set_hooks();
	}


	protected function set_hooks() {
		add_filter( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}


	public function register_setting() {
		register_setting(
		'AdminSettingGroup', //group of setting.
		$this->optionName //name of setting.
		);
	}


	public function set_option_name($option_name) {
		$this->optionName = $option_name;
	}


	public function set_menu_data($page_title, $menu_title, $capability, $menu_slug, $icon_url = '', $position = null) {
		$this->menuData = array('page_title'	=>	$page_title,
								'menu_title'	=>	$menu_title,
								'capability'	=>	$capability,
								'menu_slug'		=>	$menu_slug,
								'icon_url'		=>	$icon_url,
								'position'		=>	$position
							);
	}


	public function add_menu_page() {
		$callback = array( $this, 'render_fields_and_submit_button');
		// Ad menu in Dasboard.
		add_menu_page(	$this->menuData['page_title'],
						$this->menuData['menu_title'],
						$this->menuData['capability'],
						$this->menuData['menu_slug'],
						$callback,
						$this->menuData['icon_url'],
						$this->menuData['position']
						);
	}


	public function admin_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_media();
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style('wp-color-picker');
	}


	public function add_section($sectionId, $title) {
		$input = array('sectionId'  =>	$sectionId,
					   	'title'     =>	$title);
		array_push($this->sections, $input);
	}


	public function add_field_of_section($sectionId, $fieldId, $title, $typeInput, $xData = array()) {
		$input = array('sectionId'	=>	$sectionId,
					   'fieldId'	=>	$fieldId,
					   'title'		=>	$title,
					   'typeInput'	=>	$typeInput,
					   'xData'		=>	$xData);
		array_push($this->fields, $input);
	}


	public function render_fields_and_submit_button() {
		foreach ($this->sections as $key => $value) {
			add_settings_section(
			$this->sections[$key]['sectionId'], // ID
			$this->sections[$key]['title'], // Title
			'', // Section can no need callback function.
			$this->sections[$key]['sectionId'] // Let page same sectionId to unique.
			);
		}

		// Render fields loop.
		foreach ($this->fields as $key => $value) {
			$callback = array($this, 'fields_callback');
			add_settings_field(
			$this->fields[$key]['fieldId'], // ID
			$this->fields[$key]['title'], // Title 
			$callback, // Callback
			$this->fields[$key]['sectionId'], // Same Page
			$this->fields[$key]['sectionId'], // Belong to Section id
			array ('fieldId'   => $this->fields[$key]['fieldId'],
				 'typeInput' => $this->fields[$key]['typeInput'],
				 'xData'     => $this->fields[$key]['xData']
				)         
			);
		}

		// Render Form.
		do_action('ntqadmin_header');

		?>
		<form method="post" action="options.php">
			<?php
			  settings_fields( 'AdminSettingGroup' );
			  foreach ($this->sections as $key => $value) {
				do_settings_sections( $this->sections[$key]['sectionId'] ); // Do for all sections.
			  }
			  submit_button();
			?>
		</form>
		<?php 
		
		do_action('ntqadmin_footer');
	}


	public function fields_callback( $args) {
		$arrGlobalData = get_option($this->optionName);
		switch ($args['typeInput']) {
			case 'radio':
				$name = $this->optionName.'['.$args['fieldId'].']';

				foreach ($args['xData']['options'] as $key => $value) {
					$checked_default = '';
					$enable_checked = '';
					// check default.
					if ($args['xData']['default'][$key] == '1') {
						$checked_default = 'checked';
						$enable_checked = $checked_default;
					}else{
						$checked_default = '';
						$enable_checked = $checked_default;
					}

					//not default.
					if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
						$value_data = esc_attr( $arrGlobalData[$args['fieldId']] );
						if ($value == $value_data) {
							$enable_checked = 'checked';
						}else{
							$enable_checked = '';
						}
					}

					?>
					<div style="margin-bottom: 10px;">

					  <input
					  type="radio" 
					  name="<?php echo esc_attr($name); ?>"
					  value="<?php echo esc_attr($value); ?>"<?php echo $enable_checked ?>> <?php echo $value;?>

					</div>
					<?php
				}
				break;
			case 'range':
				$name = $this->optionName.'['.$args['fieldId'].']';
				$value = $args['xData']['default'];
				$min = $args['xData']['min'];
				$max = $args['xData']['max'];
				$step = $args['xData']['step'];
				$spanId = $args['fieldId'].'_span';

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				?>
				<input style="width:20%;" type="range" 
				min="<?php echo $min ?>" max="<?php echo $max ?>" step="<?php echo $step ?>" 
				name="<?php echo esc_attr($name); ?>" 
				value="<?php echo $value ?>" 
				oninput='show_value("<?php echo $spanId; ?>",this.value);'>
				<span id="<?php echo $spanId; ?>"><?php echo $value; ?></span>

				<script type="text/javascript">
					function show_value(y,x){
						document.getElementById(y).innerHTML=x;
					}
				</script>
				<?php
				break;
			case 'select':
				$name = $this->optionName.'['.$args['fieldId'].']';
				$selected = '';
				$desc = $args['xData']['desc'];
				?>
				<select name="<?php echo esc_attr($name); ?>">
				<?php
				foreach ($args['xData']['options'] as $key => $value) {
					if ( ( isset($arrGlobalData[$args['fieldId']]) && $arrGlobalData[$args['fieldId']] !='') ){
						$value_data = esc_attr( $arrGlobalData[$args['fieldId']] );
						if ($value == $value_data) {
							$selected = 'selected';
						}else{
							$selected = '';
						}
					}

					?>
					<option value="<?php echo $value ?>" <?php echo $selected ?>><?php echo $value; ?> </option>
					<?php
				}
				?>
				</select>
				<p><?php echo $desc; ?></p>
				<?php
				break;
			case 'text':
				$name = $this->optionName.'['.$args['fieldId'].']';
				$value = "";

				if (isset($args['xData']['default'])) {
					$value = $args['xData']['default'];
				}

				$desc = "";
				if (isset($args['xData']['desc'])) {
					$desc = $args['xData']['desc'];
				}

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				?>
				<input
				type="text" 
				class="regular-text" 
				id="<?php echo $args['fieldId']; ?>" 
				name="<?php echo esc_attr($name); ?>" 
				value="<?php echo $value ?>" />
				<p><?php echo $desc; ?></p>
				<?php
				break;
			case 'color':
				$value = $args['xData']['default'];
				$name = $this->optionName.'['.$args['fieldId'].']';
				$desc = $args['xData']['desc'];

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				?>
				<input
				name="<?php echo esc_attr($name); ?>" 
				type="text" 
				value="<?php echo $value ?>" 
				class="csh_color_picker" />
				<p> <?php echo $desc; ?></p>

				<script type="text/javascript">
					jQuery(document).ready(function($){
						$('.csh_color_picker').wpColorPicker();
					});
				</script>
				<?php
				break;
			case 'upload':
				$name = $this->optionName.'['.$args['fieldId'].']';
				$value = $args['xData']['default'];

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				$inputId = $args['fieldId'].'_input';
				$buttonId = $args['fieldId'].'_button';
				global $j_input_var;
				global $j_button_var;
				$j_input_var ='#'.$inputId;
				$j_button_var ='#'.$buttonId;
				?>
				<div>

					<input type="text" 
					name="<?php echo esc_attr($name); ?>" 
					id="<?php echo $inputId ?>" class="regular-text" 
					value="<?php echo $value ?>">
					<input type="button" name="upload-btn" id="<?php echo $buttonId ?>" 
					class="button-secondary" value="Upload Image">

				</div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						var j_button = "<?php echo $j_button_var; ?>";
						$(j_button).click(function(e) {
							e.preventDefault();
							var image = wp.media({
									title: 'Upload Image',
									// mutiple: true if you want to upload multiple files at once
									multiple: false
								}).open()
								.on('select', function(e) {
									// This will return the selected image from the Media Uploader, the result is an object
									var uploaded_image = image.state().get('selection').first();
									// We convert uploaded_image to a JSON object to make accessing it easier
									// Output to the console uploaded_image
									console.log(uploaded_image);
									var image_url = uploaded_image.toJSON().url;
									// Let's assign the url value to the input field
									var j_input = "<?php echo $j_input_var; ?>";
									$(j_input).val(image_url);
								});
						});
					});
				</script>
				<?php
				echo $args['xData']['desc'];
				break;
			case 'number':
				$name = $this->optionName.'['.$args['fieldId'].']';
				$value = $args['xData']['default'];

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				?>
				<input 
				style="width: 8%;"
				type="number"  
				id="<?php echo $args['fieldId']; ?>" 
				name="<?php echo esc_attr($name); ?>" 
				value="<?php echo $value ?>" />
				<?php
				echo $args['xData']['desc'];
				break;
			case 'description':
				echo htmlspecialchars($args['xData']['desc']);
				break;
			case 'checkbox':
				$name = $this->optionName.'['.$args['fieldId'].']';
				$checked = '';
				$desc = $args['xData']['desc'];

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] !='')){
					$checked = 'checked';
				}else{
					$checked = '';
				}

				?>
				<input type="checkbox" 
				name="<?php echo esc_attr($name); ?>"<?php echo $checked ?>> <?php echo $desc; ?>
				<?php
				break;
			case 'textarea':
				$name = $this->optionName.'['.$args['fieldId'].']';
				$value = "";

				$label = "";
				if (isset($args['xData']['label'])) {
					$label = $args['xData']['label'];
				}

				$width = "";
				if (isset($args['xData']['width'])) {
					$width = $args['xData']['width'];
				}

				$height = "";
				if (isset($args['xData']['height'])) {
					$height = $args['xData']['height'];
				}

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}
				?>
				<p><?php echo $label;?></p>
				<textarea style="width: <?php echo $width ?>; height: <?php echo $height ?>;"
				id="<?php echo $args['fieldId']; ?>" 
				name="<?php echo esc_attr($name); ?>"/><?php echo $value ?></textarea>
				<?php
				break;
			default:
		}
	}// end of call back.

}// End of AdminSettings.

?>