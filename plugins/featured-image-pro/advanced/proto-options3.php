<?php
/*
//Quick code to generate Admin Form Elements
*/
//require_once 'proto-snap-category-walker.php';
if ( !class_exists( 'proto_shortcode_fields3' ) ):
	/**
	 * proto_shortcode_fields3 class.
	 */
	class proto_shortcode_fields3 {
	private $values;   //Options
	private $optionsName; //String name of options
	private $defaults;
	/**
	 * __construct function.
	 * Initialize
	 *
	 * @access public
	 * @param array   $values      - widget field values
	 * @param string  $optionsname - setting name
	 * @param array   $defaults    (default: array())  default values
	 * @return void
	 */
	public function __construct( $values, $optionsname,  $defaults=array() ) {
		$this->optionvalues = $values;
		$this->optionsName = sanitize_text_field( $optionsname );
		$this->defaults = $defaults;
	}
	/**
	 * proto_checkbox function.
	 * Create a checkbox with data for shortcode generator
	 *
	 * @access public
	 * @param array   $args - checkbox arguments
	 * - @field - field name
	 * - @label - description label
	 * - @always - when true, always generate shortcode even when the shortcode is default
	 * - @help - tooltip help text
	 * @return checkbox element
	 */
	public function proto_checkbox( $args ) {
		$overidedefault =  ( isset ($args['default'] ) ) ?  proto_boolval( sanitize_text_field( $args['default'] ) ) : $overidedefault = null;
		$chkfield = trim( sanitize_text_field( $args['field'] ) ); // field
		$text = isset( $args['label'] ) ? sanitize_text_field( $args['label'] ) : ''; //label
		$always = isset( $args['always'] )? 'always' : ''; //always show in shortcode
		$name = "{$this->optionsName}[{$chkfield}]"; //Field Name
		$help = isset( $args['help'] ) ? sanitize_text_field( $args['help'] ) : ''; //any help text
		$class = isset( $args['class'] ) ? sanitize_text_field( $args['class'] ) : '';
		//get the  default value
		if ( isset( $overidedefault ) )
			$default = $overidedefault;
		else
			$default = isset( $this->defaults[$chkfield] ) ? proto_boolval($this->defaults[$chkfield]) : false;
		//check for an override
		if (  isset( $this->optionvalues[$chkfield] ) ) //See if there is a set value
			$checked = proto_boolval( $this->optionvalues[$chkfield] ) ? 'checked' : '';
		else
			$checked = $default ?  'checked' : '';

		$ret = "<div title='$help' class='proto_checkbox'><input type='checkbox' name='$name' id='$name' class='proto_shortcode_options $class'  value='$chkfield' data-default='$default' data-always='$always' data-name='$chkfield'  $checked>";
		if ( $text )
			$ret .= "<label for='$chkfield'>$text</label>";
		return $ret . '</div><!--proto_checkbox-->';
	}
	/**
	 * proto_number function.
	 *
	 * @access public
	 * @param mixed   $args
	 * @return void
	 */
	public function proto_number(  $args ) {
		$field = sanitize_text_field( $args['field'] ); //field
		$description = isset( $args['label'] ) ? sanitize_text_field( $args['label'] ) :''; //label
		$always = isset( $args['always'] )? "data-always='always'" : ''; //always show in shortcode
		$name = "{$this->optionsName}[{$field}]"; //field name
		$help = isset( $args['help'] ) ? sanitize_text_field( $args['help'] ) : '';
		$class = isset( $args['class'] ) ? sanitize_text_field( $args['class'] ) : '';
		$default = isset( $this->defaults[$field] ) && ( $this->defaults[$field] )  ? sanitize_text_field( $this->defaults[$field] ) : '0';  //default value
		$default = isset($args['default']) ? sanitize_text_field($args['default']) : $default;
		$value = isset($this->optionvalues[$field]) ? $this->optionvalues[$field] : $default;         //current field value
		$ret = '<div class="proto_number">';
		if ( $description )
			$ret .= "<div title='$help' class='proto-description'>$description</div><!--proto-description--> ";
		$data_always = "data-always='always";
		$ret .= "<input id='$name' type='number' name='$name' value='$value' data-default='$default' data-name='$field' $always class='proto_shortcode_options $class'/>
              </div><!--proto-number-->";
		return $ret;
	}
	/**
	 * proto_text function.
	 *
	 * @access public
	 * @param mixed   $args
	 * @return void
	 */
	public function proto_text(  $args ) {
		$field = sanitize_text_field( $args['field'] ); //field
		$description = isset( $args['label'] ) ? sanitize_text_field( $args['label'] ) : ''; //label
		$always = isset( $args['always'] )? 'always' : ''; //always show in shortcode
		$placeholder = isset( $args['placeholder'] ) ? sanitize_text_field( $args['placeholder'] ) : ''; //placeholder
		$name = "{$this->optionsName}[{$field}]";
		$class = isset( $args['class'] ) ? sanitize_text_field( $args['class'] ) : '';
		$default = isset( $this->defaults[$field] ) ? sanitize_text_field( $this->defaults[$field] ) : '';
		$default = isset($args['default']) ? sanitize_text_field($args['default']) : $default;
		$value = isset($this->optionvalues[$field]) ? $this->optionvalues[$field] : $default;         //current field value
		$hint = isset( $args['hint'] ) ? $args['hint'] : '';
		$help = isset( $args['help'] ) ? sanitize_text_field( $args['help'] )  : '';
		$ret = '<div class="proto_text">';
		if ( $description )
			$ret .= "<div title='$help' class='proto-description'>$description</div><!--proto-description--> ";
		$ret .= "<input id='$name' type='text' placeholder='$placeholder' name='$name' value='$value' data-default='$default' data-name='$field'  class='proto_shortcode_options $class' />";
		if ( $hint )
			$ret .= "<span class='proto-hint'>" . ' ' . $hint. "</span>";
		$ret .= "</div><!--proto_text-->";
		return $ret;
	}
	/**
	 * proto_select function.
	 *
	 * @access public
	 * @param mixed   $args
	 * @return void
	 */
	public function proto_select(  $args, $multiple = false, $taxonomy='' ) {
		$okey =  ( isset( $args['key'] ) ) ?  $args['key'] :  null;
		$assoc = isset( $args['field_assoc'] ) ? 'data-assoc="' . sanitize_text_field( $args['field_assoc'] ) . '"' : '';
		$class = isset( $args['class'] ) ? sanitize_text_field( $args['class'] ) : '';
		$data = isset( $args['taxonomy' ] ) ? " data-taxonomy='" . $args['taxonomy'] . "'" : ''; //fields for taxonomy drop boxes
		$data .= isset( $args['post_type'] ) ? " data-posttype='" . $args['post_type'] . "'" : ''; //fields for post type drop boxes
		$data .= isset( $args['meta_key'] ) ? " data-posttype='" . $args['meta_key'] . "'" : ''; //fields for meta key drop boxes

 		$field = sanitize_text_field( $args['field'] ); //the field name
		$description = isset( $args['label'] ) ? sanitize_text_field( $args['label'] ) : ''; //label
		$list = isset( $args['values'] ) ? $args['values'] : array(); //dropdown values
		$name = "{$this->optionsName}[{$field}]"; //the html name
		$id = isset( $args['id'] ) ? $args['id'] : "{$this->optionsName}_{$field}"; 			//the html id
		$hidden = isset($args['hidden']) ? "style='display:none'" : ''; 						//check for hidden field
		$help = isset( $args['help'] ) ? sanitize_text_field( $args['help'] ) : ''; 			//help text
		$always = isset( $args['always'] )? "data-always='always'" : ''; 						//always show in shortcode - used for debugging only

		$default = isset( $this->defaults[$field] ) ? sanitize_text_field( $this->defaults[$field] ) : NULL; //the default value
		$default = isset($args['default']) ? sanitize_text_field($args['default']) : $default;

		if ( isset($taxonomy)  && $taxonomy != '' ) {

			$sfield = $taxonomy . '_terms'; //the array key for a taxonomy field
			$valuefield = isset($this->optionvalues[$sfield]) ? $this->optionvalues[$sfield] : $default; //the selected values
		}
		else {
			$sfield = $field;
			$valuefield = isset($this->optionvalues[$sfield]) && $this->optionvalues[$sfield] != null ? $this->optionvalues[$sfield] : $default; //the selected values

			switch ( $sfield )
			{
				case 'meta_query_values':
					$xfield = isset( $this->optionvalues['meta_queries'] ) ? $this->optionvalues['meta_queries'] : '';
					$valuefield = $xfield != '' ? $this->optionvalues[$xfield . '_value'] : '';
					break;
				case 'post_query':

					foreach ( $list as $key=>$post_query_value )
					{
						if ( isset ( $this->optionvalues[$key] ) )
						{
							$valuefield = trim( $key );
							break;
						}
					}

					break;
				case 'post_title':

					$list2 = array('post__in', 'post__not_in', 'post_parent__in', 'post_parent__not_in');

					foreach ( $list2 as $key )
					{
						if ( isset ( $this->optionvalues[$key] ) )
						{
							$valuefield = $this->optionvalues[$key];
							break;
						}
					}
					break;
				case 'authors_list':
					if ( array_key_exists ( 'author__in' , $this->optionvalues ) )  //set the value to the author__in or author__not_in list
						$valuefield = $this->optionvalues['author__in'];
					else
						if ( array_key_exists( 'author__not_in', $this->optionvalues ) )
							$valuefield = $this->optionvalues['author__not_in'];
					break;

				case 'authors_type':												//set the key to author__in or author__not_in
					if ( array_key_exists ( 'author__in' , $this->optionvalues ) )
						$valuefield = 'author__in';
					else
						if ( array_key_exists( 'author__not_in', $this->optionvalues ) )
							$valuefield = 'author__not_in';
					break;
				default: //override

					$valuefield = isset( $args['value'] ) ? $args['value'] : $valuefield;


			}
		}

		$valuearray = explode(',', $valuefield);

		$none = isset( $args['none'] ) ? proto_boolval( $args['none'] ) : false;
		$generate = isset( $args['generate'] ) ? proto_boolval( $args['generate'] ) : true;
		$ret = '<div class="proto_select">';
		$index = isset($args['index']) ? " data-index='" . intval($args['index']) . "' " : '';
		$msstr = $multiple ? "multiple='multiple'" : '';
		$class .= $multiple ? " proto_masonry_select2": '';
		if ( $description )
			$ret .= "<div title='$help' class='proto-description'>$description</div><!--proto-description--> ";
		$ret .= "<select id='$id'  data-default='$default' data-name='$field' $hidden $assoc $data $index data-generate='$generate' $always $msstr  placeholder='placeholder' class='proto-select proto_shortcode_options $class' name='$name' >";
		if ( $none )
			$ret .= "<option value=''>--select--</option>";
		foreach ( $list as $key=>$item ) {
			$ikey = ($okey == 'value') ? $item : $key; //see if the dropdown index should be set to the array key or to the array value
			$ret .= "<option value='$ikey' id='$ikey'";
			if ( in_array( strval( $ikey ) , $valuearray ) ) {
					$ret .= 'selected';
			}
			$ret .= ">$item</option>";
		}
		$ret .= '</select>';

		$ret .='</div><!--proto_select-->';
		return $ret;
	}
	/**
	 * proto_category_multi_checkbox function.
	 *
	 * @access public
	 * @param mixed   $args
	 * @return void
	 */
	public function proto_category_multi_checkbox( $args ) {
		global $wp_version;
		$output = '';
		$taxonomy = isset( $args['taxonomy'] ) ? sanitize_text_field( $args['taxonomy'] ) : 'category';
		if ( $wp_version < 4.5 )
			$_cats      = get_terms( $taxonomy, array( 'hide_empty' => false, ) );
		else
			$_cats      = get_terms( array ('taxonomy' => $taxonomy, 'hide_empty' => false) );
		if ( !empty( $_cats ) ) {
			$cats = array();
			foreach ($_cats as $cat)
			{
				$cats[$cat->term_id] = $cat->name;
			}
			$args['values'] = $cats;
			 $output = $this->proto_select( $args, true, $taxonomy );
		}
		return $output;
	}
	public function proto_multi_checkbox( $args ) {
		$output = '';
		$terms = $args['values'];
		if ( !empty( $terms ) ) {
				$output = $this->proto_select( $args, true );
		}
			return $output;
	}
}
endif;