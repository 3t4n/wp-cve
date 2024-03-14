<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

abstract class block
{
	protected $data = array();
	protected $collection = null;
	protected $fields = array();
	protected $fielddata = array();
	protected $blockname;
	protected $is_preview = false;

	//protected static $eventInit = false;
	protected $network;


	public function __construct()
	{
				$w = MBSocial()->whistle();
				$w->listen('display/parse/network', array($this, 'set_network'), 'tell');
	}

	public function set_network($network)
	{
			$this->network = $network;
	}
	function setCollection($collection)
	{
		$this->collection = $collection;
	}


	public function set($data)
	{
 		$this->data = $data;
		if (! isset($this->data[$this->blockname]) )
			$this->data[$this->blockname] = array();

		return true;

	}

	// get the block data
	public function get()
	{
		return $this->data;

	}


	public function get_name()
	{
		return $this->blockname;
	}

	public function get_fields()
	{
		return $this->fields;
	}

	public function getValue($field)
{
	 $data = $this->data;

	if (isset($data[$this->blockname][$field]))
	{
		return $data[$this->blockname][$field];
	}

	if (isset($this->fields[$field]['default']))
		return $this->fields[$field]['default'];


	// if not found return default
	return false;
}

public function getColorValue($fieldname)
{
	$value = self::getValue($fieldname);
	if ($this->isrgba($value))
		return $value;

	if (! $value )
		return false;
	if (substr($value,0,1) !== '#')
	{
		$value = '#' . $value;
	}
	return $value;
}

private function isrgba($value)
{
			if (strpos($value, 'rgb') >= 0 )
				return true;
			else {
				return false;
			}
}



	public function setPreview($preview = true)
	{
		$this->is_preview = $preview;

	}

	/* Save fields on a per block data
	*
	*   Post data is sent unfiltered so sanitization must be done here!
	*	@param $data Array with all collection block data
	*	@param $post Array|null The post array or null. Null in case of loading defaults, not a post.
	*/
	public function save_fields($data, $post)
	{
		$blockdata = array();

		//if (isset($this->data[$this->blockname]))
		//	$blockdata = $this->data;
		//$blockdata = $data;

		foreach($this->fields as $field => $options)
		{

			if ($field == "multifields") // standardize multi fields
			{
				$mf_id = $options["id"];
				$mf_fields = $options["fields"];

				$multidata = array();

				if (isset($post[$mf_id])) // the collection with the id's.
				{
					$i = 0; // id's might be duplicate i.e. two similar buttons.
					foreach ($post[$mf_id] as $id) // id is button-id
					{

							foreach($mf_fields as $mf_field => $options)
							{
								$default = (isset($options["default"])) ? $options["default"] : '';
						// POST[ field_name - $id ]
					//
				$multidata[$i][$id][$mf_field] = isset($post[$mf_field . "-" . $id . "-" . $i ]) ? $post[$mf_field . "-" . $id . "-" . $i] : $default;
						 	}
						 $i++;
					}
				}
				$blockdata[$mf_id] = $multidata;
			}
			else
			{
				$default = (isset($options["default"])) ? $options["default"] : '';
				// stripslashes since the WP post var adds them.

				$blockdata[$field] = (isset($post[$field])) ? stripslashes(sanitize_text_field($post[$field])) : $default;
						
			}
		}

		$data[$this->blockname] = $blockdata;
		return $data;

	}

	public function parse($domObj, $args = array() )
	{

		return $domObj; // nothing to do by default
	}

	public function parseButtons($buttons)
	{
		return $buttons;
	}

	// Adepted from block class - maxbuttons
	public function parseCSS($css, $args)
	{
		$data = $this->data[$this->blockname];

 		// get all fields from this block
 		foreach($this->fields as $field => $field_data)
		{
			// get cssparts, can be comma-seperated value
			$csspart = (isset($field_data["csspart"])) ? explode(",",$field_data["csspart"]) : array('maxbutton');
			$csspseudo = (isset($field_data["csspseudo"])) ? explode(",", $field_data["csspseudo"]) : 'normal';

			// if this field has a css property
			if (isset($field_data["css"]))
			{


				// get the property value from the data
				$value = isset($data[$field]) ? $data[$field] : $field_data['default'];
				$value = str_replace(array(";"), '', $value);  //sanitize

				if ( strpos($field_data["default"],"px") && ! strpos($value,"px"))
				{
					if ($value == '') $value = 0; // pixel values, no empty but 0
					$value .= "px";
				}

 				if (isset($data[$field]))
 				{

	 				 foreach($csspart as $part)
	 				 {
		 					if (is_array($csspseudo))
		 					{
		 						foreach($csspseudo as $pseudo)
		 							$css[$part][$pseudo][$field_data["css"]] = $value ;
		 					}
		 					else
								$css[$part][$csspseudo][$field_data["css"]] = $value ;
					  }
				}
			}

		}

		return $css;
	}


	// default function
	public function parseJS($js)
	{
		return $js;
	}

  /** Get the post metadata of a certain block
	* Used in post specific settings
	* @param $post_id int Post id
	* @return Array | Boolean . Array of setting data, or false if not metadata present
	*/
	public function get_block_meta_data($post_id)
	{
		$meta = $this->collection->get_metadata($post_id);

		if ( isset($meta[$this->blockname]))
			return $meta[$this->blockname];
		else
			return false;

	}

	public function save_meta_boxes($metadata, $post_id, $post)
	{
		if (! isset($this->meta_fields))
			return $metadata;

		$blockmeta = array();

		foreach($this->meta_fields as $field => $options)
		{
			$default = (isset($options["default"])) ? $options["default"] : '';
			// stripslashes since the WP post var adds them.
			$blockmeta[$field] = (isset($post[$field])) ? stripslashes(sanitize_text_field($post[$field])) : $default;
		}

		$metadata[$this->blockname] = $blockmeta;

		return $metadata;
	}

	public function do_meta_boxes($array, $post)
	{
		return $array;
	}


	abstract function admin();

} // class
