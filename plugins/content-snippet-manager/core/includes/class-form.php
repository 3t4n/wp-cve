<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( !class_exists( 'csm_form' ) ) {

	class csm_form {

		/**
		 * ELEMENT START
		 */

		public function elementStart($attribute, $id, $class ) {
			
			$html = '<';
			$html .= $attribute !== FALSE ? $attribute : '' ;
			$html .= $id !== FALSE ? ' id="' . $id . '" ' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '" ' : '' ;
			$html .= '>';

			return $html;

		}
	
		/**
		 * ELEMENT END
		 */

		public function elementEnd($attribute) {
			
			$html = '</';
			$html .= $attribute !== FALSE ? $attribute : '' ;
			$html .= '>';

			return $html;

		}

		/**
		 * ELEMENT
		 */

		public function element($attribute, $id, $class, $content) {
			
			$html = '<';
			$html .= $attribute !== FALSE ? $attribute : '' ;
			$html .= $id !== FALSE ? ' id="' . $id . '" ' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '" ' : '' ;
			$html .= '>';
			$html .= $content !== FALSE ? $content : '' ;
			$html .= '</';
			$html .= $attribute !== FALSE ? $attribute : '' ;
			$html .= '>';

			return $html;

		}

		/**
		 * IMAGE
		 */

		public function img($src, $id = FALSE, $class = FALSE) {
			
			$html = '<img ';
			$html .= $src !== FALSE ? ' src="' . $src . '" ' : '' ;
			$html .= $id !== FALSE ? ' id="' . $id . '" ' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '" ' : '' ;
			$html .= '>';
			
			return $html;

		}
		
		/**
		 * LINK
		 */ 
		 
		public function link($url, $id, $class, $target, $rel, $content) {
			
			$html = '<a ';
			$html .= $url !== FALSE ? ' href="' . esc_url($url) . '" ' : '' ;
			$html .= $id !== FALSE ? ' id="' . $id . '" ' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '" ' : '' ;
			$html .= $target !== FALSE ? ' target="' . $target . '" ' : '' ;
			$html .= $rel !== FALSE ? ' rel="' . $rel . '" ' : '' ;
			$html .= '>';
			$html .= $content !== FALSE ? $content : '' ;
			$html .= '</a>';
			
			return $html;

		}
		
		/**
		 * LIST
		 */

		public function htmlList( $type, $id, $class, $values, $current ) {

			$html  = '<' . $type;
			$html .= $id !== FALSE ? ' id="' . $id . '"' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '"' : '' ;
			$html .= '>';

			foreach ($values as $k => $v ) {
			
				$html .= '<li';
				$html .= (str_replace(' ', '', $k) === $current) ? ' class="ui-state-active"' : '' ;
				$html .= ' value="' . $k . '"';
				$html .= '>';
				
				if (0 === strpos($k, 'http')) {
					$html .= $this->link($k, FALSE, FALSE, '_BLANK', FALSE, $v);
				} else {
					$html .= $this->link(esc_url('admin.php?page=csm_panel&tab=' . str_replace(' ', '', $k)), FALSE, FALSE, '_SELF', FALSE, $v);
				}
				
				$html .= '</li>';
			}
			
			$html .= '<li class="clear"></li>';
			$html .= '</' . $type . '>';

			return $html;

		}
		
		/**
		 * FORM START
		 */

		public function formStart($method, $action ) {
			
			$html = '<form enctype="multipart/form-data"';
			$html .= $method !== FALSE ? ' method="' . $method . '" ' : '' ;
			$html .= $action !== FALSE ? ' action="' . $action . '" ' : '' ;
			$html .= '>';

			return $html;

		}

		/**
		 * FORM END
		 */

		public function formEnd() {
			
			return '</form>';

		}

		/**
		 * LABEL
		 */

		public function label($id, $text) {

			$html  = '<label';
			$html .= $id !== FALSE ? ' for="' . $id . '"' : '' ;
			$html .= '>';
			$html .= $text !== FALSE ? $text : '' ;
			$html .= '</label>';

			return $html;

		}
	
		/**
		 * INPUT
		 */

		public function input($name, $id, $class, $type, $value, $limit = FALSE ) {

			$html  = '<input ';
			$html .= $name !== FALSE ? ' name="' . $name . '"' : '' ;
			$html .= $id !== FALSE ? ' id="' . $id . '"' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '"' : '' ;
			$html .= $type !== FALSE ? ' type="' . $type . '"' : '' ;
			$html .= $limit !== FALSE ? ' min="' . $limit . '"' : '' ;
			$html .= $value !== FALSE ? ' value="' . $value . '"' : '' ;
			$html .= ' >';

			return $html;

		}
	
		/**
		 * COLOR
		 */

		public function color($name, $id, $class, $type, $value ) {

			$html = str_replace ('<input ', '<input data-default-color="' . $value .'" ' , $this->input($name, $id, $class, $type, $value));  
			return $html;

		}
	
		/**
		 * TEXTAREA
		 */

		public function textarea($name, $id, $class, $value, $disabled) {

			$html  = '<textarea ';
			$html .= $name !== FALSE ? ' name="' . $name . '"' : '' ;
			$html .= $id !== FALSE ? ' id="' . $id . '"' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '"' : '' ;
			$html .= $disabled === TRUE ? ' disabled' : '' ;
			$html .= '>';
			$html .= $value !== FALSE ? stripslashes($value) : '' ;
			$html .= '</textarea>';

			return $html;

		}

		/**
		 * SELECT
		 */

		public function select($name, $id, $class, $values, $current, $dataType ) {

			$html  = '<select ';
			$html .= $name !== FALSE ? ' name="' . $name . '"' : '' ;
			$html .= $id !== FALSE ? ' id="' . $id . '"' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '"' : '' ;
			$html .= $dataType !== FALSE ? $dataType : '' ;
			$html .= '>';

			foreach ($values as $k => $v ) {
			
				$html .= '<option';
				$html .= $k === $current ? ' selected="selected"' : '' ;
				$html .= ' value="' . $k . '"';
				$html .= '>';
				$html .= $v;
				$html .= '</option>';
			}
				
			$html .= '</select>';

			return $html;

		}

		/**
		 * AJAXSELECT
		 */

		public function ajaxSelect($name, $id, $class, $values, $dataType ) {

			$html  = '<select multiple ';
			$html .= $name !== FALSE ? ' name="' . $name . '[]"' : '' ;
			$html .= $id !== FALSE ? ' id="' . $id . '"' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '"' : '' ;
			$html .= $dataType !== FALSE ? ' data-type="' . $dataType . '"' : '' ;
			$html .= '>';

			switch ( $class ) {
						
				case 'csmAjaxSelect2':
				
					if ( is_array($values) ) :
		
						foreach ($values as $k => $v ) {
			
							if ( $v == '-1' ) {
								
								$html .= '<option selected="selected" value="-1">[All]</option>';
							
							} elseif ( get_the_title($v) ) {
							
								$html .= '<option selected="selected" value="' . $v . '">' . get_the_title($v) . '</option>';
							
							}
			
						}
						
					endif;
				
				break;
				
				case 'csmAjaxSelect2Tax':
				
					if ( is_array($values) ) :
					
						foreach ($values as $k => $v ) {
							
							$term = get_term( $v, $dataType );
			
							if (isset($term->name)) {
								
								$html .= '<option selected="selected" value="' . $v . '">' . $term->name . '</option>';
							
							} elseif ( $v == '-1' ) {
							
								$html .= '<option selected="selected" value="-1">[All]</option>';
							
							}
			
						}
						
					endif;
				
				break;
				
			}

			$html .= '</select>';

			return $html;

		}

		/**
		 * CHECKBOX
		 */

		public function checkbox($name, $values, $default ) {
			
			$html = '';

			foreach ($values as $k => $v ) {
				
				$check = '';

				if ( $default != false ) {
					
					foreach ( $default as $current ) {
						
						if ( $current == $k ) 
							$check = ' checked="checked"';
					
					}
				
				}

				$html .= '<p><input name="' . $name . '[]" type="checkbox" value="' . $k . '" ' . $check . '/>' . $v . '</p>';
			
			}
				
			return $html;

		}

		/**
		 * TABLE START
		 */

		public function tableStart($id, $class, $cellspacing, $cellpadding) {

			$html  = '<table ';
			$html .= $id !== FALSE ? ' id="' . $id . '"' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '"' : '' ;
			$html .= $cellspacing !== FALSE ? ' cellspacing="' . $cellspacing . '"' : '' ;
			$html .= $cellpadding !== FALSE ? ' cellpadding="' . $cellpadding . '"' : '' ;
			$html .= '>';

			return $html;

		}

		/**
		 * TABLE END
		 */

		public function tableEnd() {

			return '</table>';

		}

		/**
		 * TABLE ELEMENT
		 */

		public function tableElement($name, $id, $class) {

			$html  = '<'.$name.' ';
			$html .= $id !== FALSE ? ' id="' . $id . '"' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '"' : '' ;
			$html .= '>';

			$html .= '</'.$name.'>';

			return $html;

		}

		/**
		 * TABLE ELEMENT START
		 */

		public function tableElementStart($name, $id, $class) {

			$html  = '<'.$name.' ';
			$html .= $id !== FALSE ? ' id="' . $id . '"' : '' ;
			$html .= $class !== FALSE ? ' class="' . $class . '"' : '' ;
			$html .= '>';

			return $html;

		}

		/**
		 * TABLE ELEMENT END
		 */

		public function tableElementEnd($name) {

			return '</'.$name.'>';

		}

	}

}

?>