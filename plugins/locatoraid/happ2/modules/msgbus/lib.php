<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Msgbus_Lib_HC_MVC extends _HC_MVC
{
	protected $msg = array();

	public function single_instance()
	{
	}

	public function add( $type, $text, $key = NULL, $group = FALSE )
	{
		if( ! isset($this->msg[$type]) ){
			$this->msg[$type] = array();
		}
		if( $key === NULL ){
			$this->msg[$type][] = $text;
		}
		else {
			if( $group ){
				if( ! isset($this->msg[$type][$key]) ){
					$this->msg[$type][$key] = $text;
					$this->msg[$type]['_msg_' . $key] = $text;
					$this->msg[$type]['_count_' . $key] = 1;
				}
				else {
					$this->msg[$type]['_count_' . $key ]++;
					$this->msg[$type][$key] = $this->msg[$type]['_msg_' . $key] . ' (' . $this->msg[$type]['_count_' . $key ] . ')';
				}
			}
			else {
				$this->msg[$type][$key] = $text;
			}
		}
	}

	public function get( $type = NULL )
	{
		$return = NULL;

		if( $type !== NULL ){
			if( isset($this->msg[$type]) ){
				$return = array();
				foreach( $this->msg[$type] as $k => $v ){
					if( substr($k, 0, 1) == '_' ){
						continue;
					}
					$return[$k] = $v;
				}
			}
			return $return;
		}
		else {
			return $this->msg;
		}
	}
}