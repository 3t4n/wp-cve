<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_Element_HC_MVC extends _HC_MVC
{
	protected $tag = 'input';
	protected $attr = array();
	protected $children = array();

	public function __toString()
	{
		return '' . $this->render();
	}

	public function tag( $set )
	{
		$this->tag = $set;
		return $this;
	}

	public function add( $child ){
		$this->children[] = $child;
		return $this;
	}

	public function render()
	{
		$tag = $this->tag;

		$return = '';
		$add_newline = FALSE;

		if( $tag !== NULL ){
			$return .= '<' . $tag;

			if( in_array($tag, array('script', 'meta', 'link', 'head', 'body')) ){
				$add_newline = TRUE;
			}

			$attr = $this->attr();
			foreach( $attr as $key => $val ){
				$val = join(' ', $val);
				if( strlen($val) OR ( substr($key, 0, strlen('data-')) == 'data-') ){
					$return .= ' ' . esc_attr($key) . '="' . esc_attr($val) . '"';
				}
			}
		}

		if( in_array($tag, array('br', 'input', 'link', 'meta')) ){
			$return .= '/>';
		}
		else {
			if( $tag !== NULL ){
				$return .= '>';
			}

			if( $this->children ){
				$return .= $this->content();
			}

			if( $tag !== NULL ){
				$return .= '</' . $tag . '>';
			}
		}

		if( $add_newline ){
			$return .= "\n";
		}

		return $return;
	}

	public function content()
	{
		$return = NULL;
		if( $this->children ){
			$return = '';
			foreach( $this->children as $child ){
				if( is_object($child) && method_exists($child, 'render') ){
					$return .= $child->render();
				}
				else {
					$return .= '' . $child;
				}
			}
		}
		return $return;
	}

	// attribute related functions
	public function attr( $key = NULL )
	{
		if( $key === NULL ){
			$return = $this->attr;
		}
		elseif( isset($this->attr[$key]) ){
			$return = $this->attr[$key];
		}
		else {
			$return = array();
		}
		return $return;
	}

	public function reset_attr( $key )
	{
		unset( $this->attr[$key] );
		return $this;
	}

	public function add_attr( $key, $value )
	{
		if( is_array($value) ){
			foreach( $value as $v ){
				$this->add_attr( $key, $v );
			}
			return $this;
		}

		switch( $key ){
			case 'title':
				if( is_string($value) ){
					$value = strip_tags($value);
					$value = trim($value);
				}
				break;

			case 'id':
				if( isset($this->attr[$key]) ){
					unset($this->attr[$key]);
				}
				break;

			case 'class':
				if( isset($this->attr[$key]) && in_array($value, $this->attr[$key]) ){
					return $this;
				}

			// wordpress?
				if( defined('WPINC') ){
					switch( $value ){
						case 'hc-theme-btn-primary':
							$value = 'button-primary';
							break;

						case 'hc-theme-btn-secondary':
							if( is_admin() ){
								$value = 'page-title-action';
							}
							else {
								$value = NULL;
							}
							break;
					}
				}
				break;
		}

		if( $value === NULL ){
			return $this;
		}

		if( ! is_array($value) )
			$value = array( $value ); 

		if( in_array($key, array('alt', 'value', 'title')) ){
			for( $ii = 0; $ii < count($value); $ii++ ){
				$value[ $ii ] = HC_lib2::esc_attr( $value[ $ii ] );
			}
		}

		if( isset($this->attr[$key]) ){
			$this->attr[$key] = array_merge( $this->attr[$key], $value );
		}
		else {
			$this->attr[$key] = $value;
		}

		return $this;
	}
}