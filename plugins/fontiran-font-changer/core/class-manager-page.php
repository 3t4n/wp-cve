<?php

/**
 * Class Fontiran_Manager_Page.
 */
class Fontiran_Manager_Page extends WP_Fontiran_Admin_Page {
	
	protected $options = array(), $elm = array();
	private $manager_report = array();

	public function on_load() {		
		
		if(get_option('fontiran_default_options')) {
			$this->set_options();
		} else {
			if(get_option('fontiran_new_option')) {
				$this->options = get_option('fontiran_new_option');
			} else {
				$this->set_options();
			}
		}
		$this->messages = array(
			'set_default' => 'کلاس های پیش فرض افزوده شدند.',
			'save_option' => 'کلاس ها به روز رسانی شدند.',
			'failed_file' => 'نتوانستیم تنظیمات را در فایل css ذخیره کنیم. لطفا یکبار دیگر تنظیمات را به روز رسانی کنید.',
			'not_classes' => 'به نظر می رسد دستوری برای اجرا نیست. ما فایل css را در صورت موجود پاک می کنیم.',
			'not_subject' => 'ما دستوراتی که کلاس یا آیدی برای آنها در نظر گرفته نشده است را پاک کرده ایم. اینکار برای عدم ذخیره داده های غیر ضروری است.'
			);


		$this->check_change_data();
		$this->send_notices();			
	}

	protected function render_inner_content() {
		$this->view( $this->slug . '-page');
	}
		
	
	private function set_options() {

		update_option('fontiran_new_option',$this->get_static_option());
		update_option('fontiran_default_options',null);
		
		$this->options = $this->get_static_option();

		
	}
	
	
	public function get_static_option() {
		
		// fields options
		$elements = array(
			array(
				'tab' => 'alltags',
				'label' => 'تمام بخش های قالب',
				'subject' => 'html,body,div,header,footer,h1,h1 a,h2,h2 a,h3,h3 a,h4,h4 a,h5,h5 a,h6,h6 a,p',
			),
			array(
				'tab' => 'bodytag',
				'label' => 'تگ بدنه سایت',
				'subject' => 'body'
			),
			array(
				'tab' => 'hstags',
				'label' => 'تگ های تیتر',
				'subject' => 'h1,h1 a,h2,h2 a,h3,h3 a,h4,h4 a,h5,h5 a,h6,h6 a'
			),
			array(
				'tab' => 'astags',
				'label' => 'تگ های لینک',
				'subject' => 'a'
			),
			array(
				'tab' => 'pstags',
				'label' => 'پاراگراف ها',
				'subject' => 'p'
			),
			array(
				'tab' => 'bquotetag',
				'label' => 'بازگویه (نقل قول)',
				'subject' => 'blockquote'
			),
			array(
				'tab' => 'codetag',
				'label' => 'تگ کدها',
				'subject' => 'code'
			)
		);
		
		return $elements = apply_filters('fontiran_setting_options', $elements);
		
	}
	
	
	function get_html_select($array, $selected = false) {
		
		$html = '';
			foreach($array as $key => $val) {
				
				
				($selected == $val) ? $sel = 'selected="selected"' : $sel = '';
				$html .= '<option value="'.$val.'" '.$sel.'>'.$val.'</option>';
			}
		echo $html;
			
	}

	
	public function check_change_data() {

		if (!isset($_POST['fi_ul_font'])) return;
		
		if (!isset($_POST['fiwp_nonce']) || !wp_verify_nonce($_POST['fiwp_nonce'], 'fiwp')) {
			return $this->set_notices( array('type'=>'error', 'ms'=> 'یک چیزی درست نیست!') );	
		}
		
			
		$change = $_POST['fi_ops'];
		
		function sanitize_text_or_array_field($change) {
			if( is_string($change) ){
				$change = sanitize_text_field($change);
			}elseif( is_array($change) ){
				foreach ( $change as $key => &$value ) {
					if ( is_array( $value ) ) {
						$value = sanitize_text_or_array_field($value);
					}
					else {
						$value = sanitize_text_field( $value );
					}
				}
			}
		
			return $change;
		}

		if( is_array($change) ) {
			$change = array_values($change);
			$change = $this->validate($change);
			$this->options = $change;
			update_option('fontiran_new_option',$change);	
			$this->render_options();
			$this->set_notices( array('type'=>'success', 'ms'=> $this->messages['save_option']) );
				
		} else {
			$this->set_options();
			return $this->set_notices( array('type'=>'success', 'ms'=> $this->messages['set_default']) );	
		}
		
		
	}
	
	
	private function render_options() {
		
		$css = '';
		
		foreach($this->options as $op) {
			if(!isset($op['stat'])) $op['stat'] = false;
			if($op['stat']) {
				
			  $subject = (isset($op['subject'])) ? $op['subject'] : null;
			  $name = (isset($op['font'])) ? 'font-family:'. $op['font'].' !important;' : null;
			  $st = (isset($op['size_type'])) ? $op['size_type'] : 'px' ;	
			  $size = (isset($op['size']) ) ? 'font-size:'. $op['size']. $st.  ' !important;' : null;
			  $weight = (isset($op['weight'])) ? 'font-weight:' . $op['weight']. ' !important;' : null;
			  $style = (isset($op['style'])) ? 'font-style:'. $op['style']. ' !important;' : null;
			  $color = (isset($op['color'])) ? 'color:'. $op['color'].' !important;' : null;	
			  
			  if(!$size && !$weight && !$style && !$color && !$name) {
				  // display error
			  } else {
				  $css .= "{$subject} { {$name}{$size}{$style}{$weight}{$color} }\n";
			  }
			  			
				
				
			}
		}
		
		
		if(trim($css) != '') {
			
			if(!file_put_contents(FIRAN_DATA . 'fontiran_front.css', $css)) {
				$this->set_notices( array('type'=>'error', 'ms'=> $this->messages['failed_file']) );	
			}
		} else {
			
			if(file_exists(FIRAN_DATA  . 'fontiran_front.css'))
				unlink(FIRAN_DATA . 'fontiran_front.css');
			
			$this->set_notices( array('type'=>'warning', 'ms'=> $this->messages['not_classes']) );
			
		}

		
	}
	
	
	
	protected function set_notices($ms = array(), $row = null) {
		
		if(isset($row) && !empty($row)) {
			$i = $row;
		} else {
			$i = count($this->manager_report);
		}
		
		return $this->manager_report[$i] = $ms;
		
	}
	
	protected function send_notices() {		
		return $this->set_all_notices($this->manager_report);
	}
	
	
	
	private function validate($data) {		
		
		$ndata = array();
		foreach($data as $key=>$elm) {

			
			
			if(!isset($elm['label']) || empty($elm['label'])) 
				$elm['label'] = 'class';

			if(isset($elm['subject']) && !empty($elm['subject'])) {
				
				if(isset($elm['stat']))
					$ndata[$key]['stat'] = '1';
				
				
				$ndata[$key]['label'] = $elm['label'];
				$ndata[$key]['subject'] = $elm['subject'];

				if(isset($elm['font']) && $this->check_font($elm['font']))
					$ndata[$key]['font'] = $elm['font'];
					
				if(isset($elm['weight']) && $this->check_weight($elm['weight']))
					$ndata[$key]['weight'] = $elm['weight'];
				
				if(isset($elm['style']) && $this->check_style($elm['style']))
					$ndata[$key]['style'] = $elm['style'];
					
				if(isset($elm['size']) && $this->check_size($elm['size'])) {
					$ndata[$key]['size'] = $elm['size'];
					$ndata[$key]['size_type'] = (isset($elm['size_type'])) ? $elm['size_type'] : null;
					
				}
				if(isset($elm['color']) && !empty($elm['color']))
					$ndata[$key]['color'] = $elm['color'];

			} else {
				$this->set_notices( array('type'=>'warning', 'ms'=> $this->messages['not_subject']), 'not_subject' );
			}
				
			
		}
		
		return $ndata;
		
	}
	
	
	public function check_size($string) {
		
		if(!filter_var($string, FILTER_VALIDATE_INT) && $string != '0') {
			return false;			
		} else {
			return true;
		}
		
	}

	// check weight
	protected function check_weight($w = null) {
		
		$ex = array('normal','100','200','300','400','500','600','700','800','900','bold');
		if( in_array($w , $ex) )
			return true;
		
		return false;
		
	}
	
	// check style
	protected function check_style($s = null) {
		
		$ex = array('normal','italic','oblique');
		if( in_array($s , $ex) )
			return true;
		
		return false;
		
	}
	
	
	private function check_font($f) {		
		
		
		
		$fonts = fi_fonts_name();
		
		if(!is_array($fonts)) $fonts = array();
		
		if( in_array($f, $fonts) )
			return true;
			
		return false;
		
	}
	
	
}