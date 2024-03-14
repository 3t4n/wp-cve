<?php

if(!defined('WPINC')){ die; }

if(!class_exists('THFAQF_Admin_Settings')):

abstract class THFAQF_Admin_Settings{
	public $settings_fields = array();
	public $cell_props_L = array();
	public $cell_props_R = array();
	public $cell_props_CB = array();
	public $cell_props_CBS = array();
	public $cell_props_CBL = array();
	public $cell_props_CPL = array();
	public $cell_props_CPR = array();

	public function __construct() {
		$this->init_constants();
	}

	public function init_constants(){
		$this->settings_fields = THFAQF_Utils::get_settings_fields();

		$this->cell_props_L = array( 
			'label_cell_props' => 'width="20%"', 
			'input_cell_props' => 'width="27%"', 
			'input_width' => '250px',  
		);
		
		$this->cell_props_R = array( 
			'label_cell_props' => 'width="20%"', 
			'input_cell_props' => 'width="27%"', 
			'input_width' => '250px', 
		);
		
		$this->cell_props_CB = array( 
			'label_props' => 'style="margin-right: 40px;"', 
		);
		$this->cell_props_CBS = array( 
			'label_props' => 'style="margin-right: 15px;"', 
		);
		$this->cell_props_CBL = array( 
			'label_props' => 'style="margin-right: 52px;"', 
		);
		
		$this->cell_props_CPL = array(
			'label_cell_props' => 'width="20%"', 
			'input_cell_props' => 'width="27%"', 
		);
		$this->cell_props_CPR = array(
			'label_cell_props' => 'width="15%"', 
			'input_cell_props' => 'width="32%"', 
		);
	}

	public function render_form_field_element($field, $settings=array(), $atts=array(), $render_cell=true, $render_tooltip=true){
		if($field && is_array($field)){
			$ftype = isset($field['type']) ? $field['type'] : 'text';
			$name = isset($field['name']) ? $field['name'] : '';
			$class = isset($field['class']) ? $field['class'] : '';
			if(is_array($settings) && isset($settings[$name])){
				if($ftype === 'checkbox' || $ftype === 'switch'){
					$field['checked'] = $settings[$name];
				}else{
					$field['value'] = esc_attr($settings[$name]);
				}
			}
			
			if($ftype == 'checkbox'){
				$this->render_form_field_element_checkbox($name, $field, $atts, $render_cell);
				return true;
			}
		
			$args = shortcode_atts( array(
				'label_cell_props' => '',
				'input_cell_props' => '',
				'label_cell_th' => false,
				'input_width' => '',
				'input_name_prefix' => '',
				'input_name_suffix' => ''
			), $atts );

			$input_class = isset($field['class']) ? $field['class'] : array();
			
			if($ftype == 'multiselect'){
				$input_class[] = 'thpladmin-enhanced-multi-select';
				$args['input_name_suffix'] = $args['input_name_suffix'].'[]';

			}else if($ftype == 'colorpicker'){
				$input_class[] = 'thpladmin-colorpicker';
			}
			
			$fname  = $args['input_name_prefix'].$name.$args['input_name_suffix'];
			$flabel = isset($field['label']) ? __($field['label'], 'advanced-faq-manager') : '';
			$default_value = isset($field['default_value']) ? $field['default_value'] : 1;
			$fvalue = isset($field['value']) ? $field['value'] : $default_value;
			$fwrapper = isset($field['wrapper']) ? $field['wrapper'] : '';
			$cols = isset($field['cols']) ? $field['cols'] : '';
			$rows = isset($field['rows']) ? $field['rows'] : '';
            $id = $fname;
			$I_id = isset($field['ids']) ? $field['ids'] : array();			
			$input_width = $args['input_width'] ? 'width:'.$args['input_width'].';' : '';
			$fvalue = ($ftype === 'number' && !$fvalue) ? $default_value : $fvalue;
			$field_props = 'id="'. $id .'" name="'. $fname .'" value="'. $fvalue .'" style="'. $input_width .'"';
			$field_props = ($ftype === 'radio_btn' || $ftype === 'iconpicker') ? 'name="'. $fname .'" style="'. $input_width .'"' : $field_props;
			$field_props .=  ($ftype === 'textarea') ? ' spellcheck="false" cols="'. $cols .'" rows="'. $rows .'"' : '';
			$field_props .= isset($field['placeholder']) && !empty($field['placeholder']) ? ' placeholder="'.$field['placeholder'].'"' : '';
			$field_props .= is_array($input_class) && !empty($input_class) ? ' class="'.implode(" ", $input_class).'"' : '';
			$required_html = isset($field['required']) && $field['required'] ? '<abbr class="required" title="required">*</abbr>' : '';
			$field_html = '';
			
			if(isset($field['onchange']) && !empty($field['onchange'])){
				$field_props .= ' onchange="'.$field['onchange'].'"';
			}

			if(isset($field['onkeyup']) && !empty($field['onkeyup'])){
				$field_props .= ' onkeyup="'.$field['onkeyup'].'"';
			}
			
			if($ftype === 'radio_btn'){
	        	$F_rvalue = isset($field['rvalue']) ? $field['rvalue'] : array();
			    $F_icon_path = isset($field['icon_path']) ? $field['icon_path'] : array();
				$F_count = count($F_rvalue);
	        	$F_path = plugin_dir_url(__FILE__);
        	}
			
			if($ftype == 'text'){
				$field_html = '<input type="text" '. $field_props .' />';
				
			}else if($ftype == 'number'){
				$field_html = '<input type="number" '. $field_props .' min="0" />';

			}else if($ftype == 'textarea'){
				$field_html = '<textarea '. $field_props .' >'.$fvalue.'</textarea>';
				
			}else if($ftype == 'select'){
				$field_html = '<select '. $field_props .' >';
				foreach($field['options'] as $ovalue => $otext){
					$otext = __($otext, 'advanced-faq-manager');
					$selected = $ovalue === $fvalue ? 'selected' : '';
					$field_html .= '<option value="'. trim($ovalue) .'" '.$selected.'>'. $otext .'</option>';
				}
				$field_html .= '</select>';
				
			}else if($ftype == 'multiselect'){
				$fvalue_arr = $fvalue ? explode(',', $fvalue) : array();

				$field_html = '<div class="thfaq-share-wrapper"><select multiple="multiple" '. $field_props .' >';
				foreach($field['options'] as $ovalue => $otext){
					$otext = __($otext, 'advanced-faq-manager');
					$selected = in_array($ovalue, $fvalue_arr) ? 'selected' : '';
					$field_html .= '<option value="'. trim($ovalue) .'" '.$selected.'>'. $otext .'</option>';
				}
				$field_html .= '</select></div>';
				
			}else if($ftype == 'colorpicker'){
				$prev_style = $fvalue ? 'background-color:'.$fvalue.';' : '';
				$field_html  = '<span class="thpladmin-colorpicker-preview '.$name.'_preview" style="'.$prev_style.'"></span>';
                $field_html .= '<input type="text" autocomplete="off" '. $field_props .' />'; 

			}else if($ftype == 'switch'){
				$field_props .= isset($field['checked']) && $field['checked'] ? ' checked' : '';
				$field_html .= '<label class="'.$class.' thpladmin-switch">';
				$field_html .= '<input type="checkbox" '. $field_props .' />'; 
				$field_html .= '<span class="thpladmin-slider"></span>';
				$field_html .= '</label>';

			}elseif($ftype === 'radio_btn'){
			    for($i=0; $i<$F_count; $i++){
			    	$selected = $fvalue === $F_rvalue[$i] ? 'checked' : '';
	            	$F_root = $F_path.'icons/'.$F_icon_path[$i];
	            	$field_html .= '<input type="radio" id="'.$I_id[$i].'" '. $field_props .' value="'.$F_rvalue[$i].'" '.$selected.'/>';
	            	$field_html .= '<label for = "'.$I_id[$i].'">';
	            	$field_html .= '<img class ="thfaqf-icon-display" src="'.$F_root.'" alt="no image" />';
	            	$field_html .= '</label>';

				}
			}elseif($ftype === 'iconpicker'){
				$F_icons = THFAQF_Utils::get_font_awesome_icons();
			    $count = 0;
			    $field_html .= '<span class="thfaq-toggle-expndicon button button-large"><i class="thfaq-icon-panal '.$fvalue.'"></i></span>';
			    $field_html .= '<span class="thfaqf-icon-wrapper thfaq-hide-expndicon">';
                foreach ($F_icons as $class => $domine) {
                	$count ++;
                	$selected = $fvalue === $class ? 'checked' : '';
                	$field_html .= '<input type="radio" title="'.$class.'" data-th_icon="'.$class.'" id="thfaq-icon-'.$count.'" '. $field_props .' value="'.$class.'" '.$selected.' />';
	            	$field_html .= '<label for = "'.'thfaq-icon-'.$count.'">';
	            	$field_html .= '<i class="thfaqf-icon-display thfaqf-icon-style '. $class .'"></i>';
	            	$field_html .= '</label>';
                }
                $field_html .= '</span>';
			}

			if($render_cell){
				$label_cell_props = !empty($args['label_cell_props']) ? ' '.$args['label_cell_props'] : '';
				$input_cell_props = !empty($args['input_cell_props']) ? ' '.$args['input_cell_props'] : '';
				?>
	            
				<td <?php echo $label_cell_props ?> > <?php 
					echo $flabel; echo $required_html; 
					
					if(isset($field['sub_label']) && !empty($field['sub_label'])){
						?>
	                    <br /><span class="thpladmin-subtitle"><?php _e($field['sub_label'], 'advanced-faq-manager'); ?></span>
						<?php
					}
					?>
	            </td>
	            
	            <?php
	            if($render_tooltip){
					$tooltip = ( isset($field['hint_text']) && !empty($field['hint_text']) ) ? $field['hint_text'] : false;
					$this->render_form_element_tooltip($tooltip);
				}
				?>
	            
	            <td <?php echo $input_cell_props ?> ><?php echo $field_html; ?></td>
	            
	            <?php
			}else{
				echo $field_html;
			}	
		}
	}

	public function render_form_field_element_checkbox($name, $field, $atts=array(), $render_cell=false){
		$args = shortcode_atts( array( 
			'cell_props'  => '', 
			'input_props' => '', 
			'label_props' => '', 
			'name_prefix' => 'i_', 
			'id_prefix' => 'a_f' 
		), $atts );
		
		$fid    = $args['id_prefix'].$name;
		$fname  = $args['name_prefix'].$name;
		$fvalue = isset($field['value']) ? $field['value'] : '';
		$flabel = __($field['label'], 'advanced-faq-manager');
		
		$field_props  = 'id="'. $fid .'" name="'. $fname .'"';
		$field_props .= !empty($fvalue) ? ' value="'. $fvalue .'"' : '';
		$field_props .= isset($field['checked']) && $field['checked'] ? ' checked' : '';
		$field_props .= $args['input_props'];
		$field_props .= isset($field['onchange']) && !empty($field['onchange']) ? ' onchange="'.$field['onchange'].'"' : '';
		
		$field_html  = '<input type="checkbox" '. $field_props .' />';
		$field_html .= '<label for="'. $fid .'" '. $args['label_props'] .' > '. $flabel .'</label>';
		
		if($render_cell){
		?>
			<td <?php echo $args['cell_props']; ?> ><?php echo $field_html; ?></td>
		<?php 
		}else{
		?>
			<?php echo $field_html; ?>
		<?php 
		}
	}

    public function render_form_section_separator($props, $atts=array()){
		?>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" style="height:10px;"></td></tr>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" class="thpladmin-form-section-title" ><?php echo $props['title']; ?></td></tr>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" style="height:0px;"></td></tr>
		<?php
	}

	public function render_form_section_subtitle($props, $atts=array()){
		?>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" class="thpladmin-form-section-subtitle" ><?php echo $props['title']; ?></td></tr>
		<?php
	}

    public function render_form_element_tooltip($tooltip){
		$tooltip_html = '';
		
		if($tooltip){
			$icon = '#';
			$tooltip_html = '<a href="javascript:void(0)" title="'. $tooltip .'" class="thpladmin_tooltip"><img src="'. $icon .'" alt="" title=""/></a>';
		}
	}

	public function render_form_element_empty_cell(){
		?>
		<td width="13%">&nbsp;</td>
        <?php $this->render_form_element_tooltip(false); ?>
        <td width="34%">&nbsp;</td>
        <?php
	}
}

endif;