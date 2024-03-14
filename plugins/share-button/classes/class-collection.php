<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxUtils as maxUtils;
use \MaxButtons\maxCSSParser as maxCSSParser;
use \MaxButtons\simple_html_dom as simple_html_dom;


class collection
{
	protected $blocks = array(); // array of objects
	protected $buttons = array();
	protected $data;  // data of the collection
	protected $meta_data;  // data stored specific to posts / pages etc

	protected $css;

	protected $collection_id = 0;
	protected static $count = 0;
	protected $collection_type = 'social_share';
	protected $styleObj = null;

	// layout functions
	protected $cssParser;

	// save hook currently active
	protected $doingHook;

  public $is_showable = true; // keeps state of hidden options
	public $is_static = false;
	public $is_post = false; // embedded in a post situation
	public $is_mobile = false;
	public $is_tablet = false;
	public $is_desktop = false;

	public $orientation = 'horizontal';
	public $display_mode = 'hidden';
	public $post_id;


	/* Constructor
	   Sets the blocks used in this collection. These are mostly derived from subclasses of the specific collection types.
	*/
	public function __construct($collection_id = 0)
	{
		collections::checkExpireTrans();
		$w = MBSocial()->whistle();
		$w->tell('collection/new', $collection_id);
		self::$count++;

		$blocks = collections::getBlocks();

		$default_data = array();


		foreach($blocks as $block_name)
		{
			$block = collections::getBlock($block_name);
			if ($block)
			{
				$block->setCollection($this);
				$default_data = $block->save_fields($default_data, array() ); // force the fields to set a default pos.

				$this->blocks[$block_name] = $block;

			}
		}

		// then load the default as data
		$this->data = $default_data;

		foreach($this->blocks as $block)
		{
			if ($block)
			{
				$block->set($default_data);
			}

		}

		if ($collection_id > 0)
		{
			$this->set($collection_id); // set function loads the block values
		}
	}
	/* Get all buttons that are currently loaded */
	/*public function getLoadedButtons()
	{
		return $this->buttons;
	} */




	/* Get a certain block class by name */
	public function getBlock($blockname)
	{

		if (isset($this->blocks[$blockname]))
			return $this->blocks[$blockname];
		else
			return false;
	}

	/* Get the type of the collection */
	/*public function getType()
	{
		return $this->collection_type;
	} */

	/* Get the ID of the collection */
	public function getID()
	{
		return $this->collection_id;
	}

	/** Get the MaxCSSParser classes which handles SCSS. This function is derived from the one in Button class */
	public function getCSSParser()
	{
		if (! $this->cssParser)
		{	$this->cssParser = new maxCSSParser();
			$this->cssParser->anchor_class = '.mb-social';
		}
		return $this->cssParser;
	}

	/** Set the collection by ID
	* @param $collection_id ID of the collection stored in database
	*/
	public function set($collection_id)
	{
		$this->collection_id = $collection_id;


		// make every block set it's stuff.
		$data = $this->get_meta();
		if ( isset($data['style']['style'])  )
		{
			Install::convertToOne($this);
			$data = $this->get_meta();
		}

		foreach($this->blocks as $name => $block)
		{
			// run every blocks setter
			$block->set($data);
		}

		$this->data = $data;
	}

	public function setData($blockname, $data)
	{
		if (! isset($this->data[$blockname]))
			$this->data[$blockname] = array();

		if (! is_array($data) )
		{
			$this->data[$blockname] = $data; // happens on root display fields
		}
		else
		{
			foreach($data as $key => $field)
			{
				$this->data[$blockname][$key] = $field;
			}
		}
//sync
		foreach($this->blocks as $name => $block)
		{
			// run every blocks setter
			$block->set($this->data);
		}
	}

	public function setHook($hook)
	{
		$this->doingHook = $hook;
		$this->setEnv();
	}

	/** Change the internal counter. This is useful for ajax request, or other situations where there is already collection on screen */
	public static function setCount($count)
	{
		static::$count = $count;
	}

	// sets the style object to a collection. This is important for styling CSS
	public function setStyle($style)
	{
		if (! is_object($style))
			return false;

		$this->styleObj = $style;
		$style->setActive($this);
	}

	public function getStyle()
	{
		return $this->styleObj;

	}

	public function getHook()
	{
		if (is_null($this->doingHook))
			return false;
		else
			return $this->doingHook;
	}

	public function setEnv() // load orientations and specifics about the current collections. After data has loaded and all that.
	{
		$w = MBSocial()->whistle();

		$data = $this->data;

		$hook = $this->doingHook;

		$display = isset($this->data[$hook]) ? $this->data[$hook] : 'hidden';
		if ($this->doingHook == 'shortcode')
			$display = 'shortcode'; // exception

		$this->display_mode = $display;


		if (! is_admin() && is_singular() )
		{
			global $post;
			$this->post_id = $post->ID;
		}
		else
			$this->post_id = 0;


		if ($display == 'static')
		{
			$this->is_static = true;
		}
		elseif ($display == 'after' || $display == 'before' || $display == 'after-before' || $display == 'shortcode')
			$this->is_post = true;

		// check if on some mobile
		$mobileDetect = new Mobile_Detect;
		$this->is_mobile = $mobileDetect->isMobile();
		$this->is_tablet = $mobileDetect->isTablet();
		$this->is_desktop = ($this->is_mobile) ? false : true;

		$options = isset($data['display'][$hook . '_options']) ? $data['display'][$hook . '_options'] : array();

		$orientation = isset($options['orientation']) ? $options['orientation'] : 'auto';
		$static = isset($options['static']) ? $options['static'] : 'auto';

		if ($orientation == 'auto' && $display == 'static')
			{
				switch($static) // auto align on basis of placement on screen
				{
					 case "left":  // vertical items
					 case "right":
					 case "auto":
					 	$orientation = "vertical";
					break;
					case "top":  // horizontal
					case "bottom":
					default:
						$orientation = "horizontal";
					break;
			}
		}
		elseif ($orientation == 'auto')
		{
			$orientation = 'horizontal';
		}

		$this->orientation = $orientation;

		// Check if any hidden variables are given.
		$is_active = isset($this->data['general']['active']) ? $this->data['general']['active'] : true;

		if ($is_active === 0)
		{
			$this->is_showable = false;
		}

		if ($this->post_id > 0)
		{
			$meta = $this->get_metadata($this->post_id);
			$is_old_active = (isset($meta['general']['active'])) ? $meta['general']['active'] : true;

			$is_hidden = isset($meta['general']['hide']) ? $meta['general']['hide'] : 0;
			if ($is_hidden == 1)
			{
				$this->is_showable = false;
			}
			elseif (isset($meta['general']['active']) && $is_old_active === 0) // backward for 0.9.5 . Can go at some point
				$this->is_showable = false;
		}


		if ( Install::isPRO() ) {
			$show_mobile = (isset($data['display']['show_mobile']) && $data['display']['show_mobile'] == 0) ? false: true;
			$show_desktop = (isset($data['display']['show_desktop']) && $data['display']['show_desktop'] == 0) ? false: true;

			if ($this->is_mobile &&  ! $show_mobile)
			{
					$this->is_showable = false;
			}
			if ($this->is_desktop && ! $show_desktop)
			{
				 $this->is_showable = false;
			}
		}

		// new method of getting data around
		$w->tell('display/env/hook', $hook);
		$w->tell('display/env/post_id', $this->post_id);
		$w->tell('display/env/is_static', $this->is_static );
		$w->tell('display/env/is_post',$this->is_post);
		$w->tell('display/env/is_mobile', $this->is_mobile);
		$w->tell('display/env/is_tablet', $this->is_tablet);
		$w->tell('display/env/is_active', $this->is_showable);

	}

	/** Interface for handling AJAX requests to communicate with the seperate blocks.
	*	@param $result The initial (default) result to JSON back
	*	@param $block_name The name of the block in question
	*	@param $action The name of the block function to call
	*	@param $data Data specified by JS to use for this function
	*	@return $result - Standardized result block including error, body
	*/
	public function doBlockAJAX($result, $block_name, $action, $data)
	{
		// core class is being called.
		if ($block_name == 'collection')
		{
			$result = $this->$action($result, $data);
			return $result;
		}

		if (isset($this->blocks[$block_name]))
		{
			$block= $this->blocks[$block_name];
			$result = $block->$action($result, $data);
			return $result;

		}

		$result["error"] = true;
		$result["title"] = __("Error : Block was not there","maxbuttons");
		return $result;
	}

	/** Save the collection post data to the database. This will invoke the save functions of the blocks itself to gather, verify and manipulate data */
	public function save($post)
	{
		$data = array();

		// run this by each block and collect data
	 	foreach($this->blocks as $block_name => $block)
	 	{

	 		$data = $block->save_fields($data, $post);
	 	}

		$this->data = $data;
		return $this->saveData();

	}

	/* The actual save function. Save data to the database */
	public function saveData()
	{
		// Collection id needed to save meta data and others.
		if ($this->collection_id == 0) // assume new
		{
			$collection_id = $this->getNewCollectionID();

			$this->collection_id = $collection_id;
		}

		// clean what was not set
	 	$this->clean_meta($this->data);

	 	// write data as a per block per key value into the database
	 	foreach($this->data as $blockname => $blockdata)
	 	{
	 		// seems dangerous
	 		$meta_id = $this->update_meta($this->collection_id, $blockname, $blockdata);
		}

		// insert collection type
		$this->update_meta($this->collection_id, 'collection_type', 'social_share') ;

	 	return $this->collection_id;
	}

	// remove post meta not in data post.
	public function clean_meta($data)
	{
		$data_blocks = array_keys($data);

		$meta = $this->get_meta();

		foreach($meta as $meta_key => $values)
		{
			if (! in_array($meta_key, $data_blocks))
			{
				$this->delete_meta($this->collection_id, $meta_key);
			}
		}

	}

	public function update_meta($collection_id, $collection_key, $collection_value)
	{
		global $wpdb;
		$table = maxUtils::get_collection_table_name();

		if ($collection_value == '') return; // no data no database entry
		if (is_array($collection_value) && count($collection_value) == 0) return; // same for empty arrays

		if (is_array($collection_value))
			$collection_value = json_encode($collection_value);

		if ($collection_id == 0) // assume new
		{
			$collection_id = $this->getNewCollectionID();

			$this->collection_id = $collection_id;
		}

		$sql = "SELECT meta_id from $table where collection_id = %d and collection_key = %s ";
		$sql = $wpdb->prepare($sql, $collection_id, $collection_key);

		$results = $wpdb->get_results($sql, ARRAY_A);

		if (count($results) == 1)
		{
			$meta_id = $results[0]["meta_id"];
			$data = array("collection_value" => $collection_value);  // what's being updated
			$where = array("meta_id" => $meta_id);

			$wpdb->update($table, $data, $where);
			return $meta_id;
		}
		if (count($results) == 0)
		{
			$data = array("collection_value" => $collection_value,
						"collection_key" => $collection_key,
						"collection_id" => $collection_id,
					);

			$meta_id = $wpdb->insert($table, $data);

			return $meta_id;
		}
		else
		{
			MB()->add_notice("error", __("Update Collection meta has multiple rows, this should not be possible.","maxbuttons"));

		}
		return false;
	}


	/** Determine the next ID for collection */
	protected function getNewCollectionID()
	{
		global $wpdb;
		$table = maxUtils::get_collection_table_name();

		$sql = "SELECT max(collection_id) as max from $table";

		$max = intval($wpdb->get_var($sql));

		$max = $max + 1;
		return $max;
	}

	protected function delete_meta($collection_id, $collection_key)
	{
		if (intval($collection_id) > 0 && $collection_key != "")
		{
			global $wpdb;
		//	delete_post_meta($collection_id, $collection_key);
			$table = maxUtils::get_collection_table_name();
			$where = array("collection_id" => $collection_id,
						   "collection_key" => $collection_key
						  );
			$where_format = array("%d", "%s");
			$wpdb->delete($table, $where, $where_format);

		}
	}


	/** Delete the collection. This is done via AJAX request */
	/*public function delete($result, $data)
	{
		global $wpdb;
		if(! $this->collection_id > 0)
			return false;

		$picker = $this->getBlock("picker");
		$picker_data = $picker->get();
		$buttons = $picker_data["selection"];
		$button = MB()->getClass("button");

		$buttons_removed = 0;

		foreach($buttons as $button_id)
		{
			$deleted = $this->maybe_delete_button($button_id);
			if ($deleted)
				$buttons_removed++;

		}

		$table = maxUtils::get_collection_table_name();
		$where = array("collection_id" => $this->collection_id);
		$where_format = array("%d");
		$wpdb->delete($table, $where, $where_format);

 		$result["data"]["body"] = __("The collection is removed","maxbuttons");
 		$result["data"]["title"] = __("Removed","maxbuttons");
 		$result["data"]["buttons_removed"] = $buttons_removed;
 		$result["data"]["collection_id"] = $this->collection_id;
 		return $result;


	} */

	/** On deletion of a collection check for each button if this is an auto-generated button just made for this collection * and if
	* no changes where made to this button. If both conditions are true, remove the button */
	/*function maybe_delete_button($button_id)
	{
			$button = MB()->getClass("button");

			$button->set($button_id);
			$button_data = $button->get();

			$collection_id= $this->collection_id;

			// remove unedited buttons created for this collection - use with care.
			if (isset($button_data["meta"]["user_edited"]))
			{
				$created_source = (isset($button_data["meta"]["created_source"])) ? $button_data["meta"]["created_source"] : '';
				if ($button_data["meta"]["user_edited"] === false && $created_source == 'collection')
				{
					$in_collections = $button_data["meta"]["in_collections"];

					$key = array_search($collection_id, $in_collections);

					if ($key !== false)
					{
						unset($button_data["meta"]["in_collections"][$key]);

						if (count($button_data["meta"]["in_collections"]) == 0)
						{	$button->delete($button_id);
							return true;
						}
						else
						{
							if ($button_id > 0) // safety.
							$button->update($button_data);
						}
					}
				}


			}
		return false;
	} */

	function get_meta ($collection_key = '')
	{
		global $wpdb;
		$table = maxUtils::get_collection_table_name();

		$prepare = array($this->collection_id);

		$sql = "SELECT * from $table where collection_id = %d ";
		if ($collection_key != '')
		{
			$sql .= " and collection_key = %s ";
			array_push($prepare, $collection_key);

		}

		$sql = $wpdb->prepare($sql, $prepare);

		$results = $wpdb->get_results($sql, ARRAY_A);

		$result_array = array(); // format array by field name = values to feed blocks and others.
		if (! is_null($results))
		{

			$nr = array();
			foreach($results as $row)
			{
				$key = $row["collection_key"];
				/* A field can be either plain text or JSON */
				if(json_decode($row["collection_value"]))
					$value = json_decode($row["collection_value"], true);
				else
					$value = $row["collection_value"];

				$result_array[$key] = $value;

				//$row["collection_value"] = unserialize($row["collection_value"]);
			}

			return $result_array;
		}
		else
		{
			return false;
		}
	}

	function display($args = array())
	{
		if (! $this->is_showable)
			return;

	/* 1.8 Deprecated. Not included in MB() anymore, using svg method.
		if (method_exists(MB(), 'load_library'))
		{
			MB()->load_library('fontawesome');  // always load lib since change is about 100% this includes FA.
		}
	*/
		maxUtils::startTime('collection-display-' . self::$count);

		$w = MBSocial()->whistle();

		wp_enqueue_style('mbsocial-buttons'); // load the general button style
		wp_enqueue_script('mbsocial_front');


		$defaults = array(
				"preview" => false,
				"echo" => true,
				"style_tag" => false,
				"compile" => false,
				"js_tag" => true,
				"load_type" => "footer",
		);

		$args = wp_parse_args($args, $defaults);

		$w->tell('display/env/is_preview', $args['preview']);


		//$cache = MaxCollections::checkCachedCollection($this->collection_id);
		$cache = false;

		if (! $cache)
		{
			//$cssParser = $this->getCSSParser();
			$domObj = $this->parse($args);

			//$css 	= $this->parseCSS($args);

			$js = $this->parseJS($args);

			$output = $domObj->save();

			unset($domObj);


		// CSS & JS output control
		$output .= $this->displayCSS($this->css, $args);
		$output .= $this->displayJS($js, $args);
		}
		else
			$output = $cache;

		//collections::addCachedCollection($this->collection_id, $output);

		maxUtils::endTime('collection-display-' . self::$count);

		if ($args["echo"])
		{
			echo $output;
		}
		else
		{
			return $output;
		}

	}

	public function getDisplayCSS($doc_id = false) // For file output
	{
		$key = '_parsed_css';

		if ($doc_id)
			$key .= '_' . $doc_id;

		$meta = $this->get_meta($key);
		if (is_array($meta))
			return array_shift($meta);
		else
			return false;

	}

	public function displayCSS($css, $args = array() ) // $echo = true, $style_tag = true)
	{

		$default = array(
			"echo" => true,
			"style_tag" => true,
			"load_type" => "footer",
		);
		$args = wp_parse_args($args, $default);
		if ($args['load_type'] == 'inline')
			$args['style_tag'] =true;

		$output = '';
		$css = apply_filters('mbsocial/displaycss/', $css);

		if ($args["style_tag"])
			$output .=  "<style type='text/css'>";

			$output .= $css;

		if ($args["style_tag"])
			$output .= "</style>";


 		if ($args["load_type"] == 'footer')
 		{
			$load_id = 'collection-' . $this->collection_id; // must be unique for unique items, otherwise css won't be output

			$use_file = get_option('maxbuttons_usecssfile', false);
			if ($use_file)
			{

				 wp_enqueue_style('maxbuttons-social-front-' . self::$count, admin_url('admin-ajax.php').'?action=maxbuttons_social_css&id=' . $this->collection_id . '&doc=' . self::$count) ;
				 $this->update_meta($this->collection_id, '_parsed_css_' . self::$count, $css);
			}

			if (self::$count > 1)
			{
					$load_id .= "-" . self::$count;
			}


 			do_action('mb-footer',$load_id, $output);
 		}
 		elseif ($args["load_type"] == 'inline')
 		{
 			if ($args["echo"]) echo $output;
 			else return $output;

 		}

	}

	public function displayJS($js, $args = array() ) // $echo = true, $style_tag = true)
	{
		$default = array(
			"echo" => true,
			"js_tag" => true,
			"load_type" => "footer",
			"preview" => false,
		);


		$args = wp_parse_args($args, $default);

		if ($args["preview"])
			return; // no js on previews.

		$output = '';

		if (count($js) == 0)
			return; // no output, holiday

		if ($args["js_tag"])
		{
			$output .= "<script type='text/javascript'> ";

		}

		foreach($js as $index => $code)
		{
			$output .= $code;
		}

		if ($args["js_tag"])
		{
			$output .= " // }
					//	}
			//	window.onload = MBcollection" . $this->collection_id . "();
				</script>		";
		}


 		if ($args["load_type"] == 'footer')
 		{

 			do_action('mb-footer','collection-' . $this->collection_id, $output, "js");
 		}
 		elseif ($args["load_type"] == 'inline')
 		{
 			if ($args["echo"]) echo $output;
 			else return $output;

 		}
	}

	/* Parses the collection, via the blocks */
	function parse($args)
	{
		$admin = mbSocial()->admin();
		$w = MBSocial()->whistle();
		$cssParser = $this->getCSSParser();
		maxUtils::startTime('collection-parse-'. self::$count);

		//$this->setEnv(); // sets the relevant data for the block (orientation etc)
		$this->buttons = array(); // reset the buttons - needed for preview

		$is_preview = isset($args["preview"]) ? $args["preview"] : false;

		$collectionObj = new simple_html_dom();
		$collection_id = $this->collection_id;

		$styleObj = $this->styleObj;
		$style_class = '';  //$styleObj->class;
		$style_class =  $this->orientation;

		// create container
		if (self::$count > 1)
		{
			$style_class .= ' doc-' . self::$count;
		}
		$node = "<div class='maxsocial maxsocial-" . $collection_id . " $style_class ' data-collection='" . $collection_id . "'>
					   </div>";
		$node = apply_filters("maxbuttons/social/basic-container",$node);

		$collectionObj->load($node);

		$active = ( isset($this->data['network']['network_active']) && is_array($this->data['network']['network_active']) ) ? $this->data['network']['network_active'] : array();

		if (count($active) == 0 && $args['preview'] == true)
		{
			$networks = $admin->get_default_networks();
			$use_networks = $networks['unselected'];
		}
		elseif (count($active) == 0)
			return $collectionObj; // no networks no collection
		else
		{
			$networks = $admin->get_networks($active);
			$use_networks = $networks['selected'];
		}

		$index = 0; // used as unique button_id

	maxUtils::startTime('collection-parse-network-' . self::$count);
		foreach($use_networks as $network)
		{
			if (! is_object($network))
			{
				continue;
			}
			if (! $is_preview)
			{
				if ( ($this->is_mobile && ! $network->forMobile() ) || $this->is_desktop && ! $network->forDesktop() )
				{
					continue;
				}
			}
			$w->tell('display/parse/network', $network);

			$button_args = array('link' => $network->get_url(),
						  'preview' => $is_preview,
							'index' => $index,
							'name' => $network->get('network'),
							'data' => $this->data,
							'collection_count' => self::$count,
					);

			$button_args = apply_filters('mbsocial/parsebutton/args', $button_args);
			$button = $network->createButton($button_args);

			// empty response == skip button
			if (! $button || ! is_object($button))
				continue;


			foreach($this->blocks as $block)
			{
				/** Here some reset to block and to style, to denote new button creation. */
				$block->setPreview($is_preview);
				$button = $block->parse($button, $button_args);
			}

			if ($button)
			{

				// save DOM structure to parser
				$cssParser->loadDom($button);
				$css = '';

				$css = $this->parseCSS( array('preview' => $is_preview) );

				$this->buttons[] = array('item_class' => $button_args['name'],
										 'obj' => $button,
										 'css' => $css,
									 );

			}

			$index++;

		} // network loop.
		maxUtils::endTime('collection-parse-network-' . self::$count);

		// general parsing
	 	$button_output = '';
		$css = '';

	 	foreach($this->buttons as $button_item)
	 	{
	 		$buttonDom = $button_item['obj'];
	 		$button_output .=  $buttonDom->save() ;
			$css .= $button_item['css'];
	 	}

		$count_block = $this->blocks['countBlock'];
		$count_block->createTotal($collectionObj);

		$cssParser->loadDom($collectionObj);
		$css .= $this->parseCSS( array('preview' => $is_preview, 'skip_blocks' => true) );

		$this->css = $css;

	 	$collectionObj->find('.maxsocial', 0)->innertext  .= $button_output;
	 	$collectionObj->load($collectionObj->save());

		maxUtils::endTime('collection-parse-'. self::$count);
		return $collectionObj;
	}


	protected function parseCSS($args)
	{
		maxUtils::startTime('collection-parseCSS-'. self::$count);
		$defaults = array(
						'preview' => false,
						'skip_blocks' => false
					);
		$args = wp_parse_args($args, $defaults);


		$css = array();
		if (! is_null($this->styleObj))
		{
			$prep_args = array(
					'orientation' => $this->orientation,
					'is_static' => $this->is_static,
					'is_mobile' => $this->is_mobile,
					'is_tablet' => $this->is_tablet,
					'is_post' => $this->is_post);

		   $this->styleObj->prepareCSS($prep_args);
			 $css = $this->styleObj->getCSS();
		}

		foreach($this->blocks as $block)
		{
			// This needs changes to accomodate to parse the defined SCSS stylesheet instead of using CSSParser which will not work here.
			// It will not work because CSSparse expects a very precise class definition and not a general broad definition of the items.
			$css = $block->parseCSS($css, $args);

		}

		// before the parse let have style have one more go
		$css = $this->styleObj->preParse($css);
		$css = apply_filters('mbsocial/parsecss/', $css); // anybody else

		$css = $this->getCSSParser()->parse($css);

		maxUtils::endTime('collection-parseCSS-'. self::$count);
		return $css;
	}

	protected function parseJS($args)
	{
		$js = array();

		$defaults = array("preview" => false,
		);

		$args = wp_parse_args($args, $defaults);

		if ($args["preview"])
			return false; // no js on previews

		foreach($this->blocks as $block)
		{
			$js = $block->parseJS($js, $args);

		}

		return $js;
	}




	/** Metadata functions **/

	public function get_metadata($post_id)
	{
		if ( ! is_array($this->meta_data) || count ($this->meta_data) == 0)
		{
			$data = get_post_meta($post_id, 'mbsocial_data', true);
			$this->meta_data = $data;
		}

		return $this->meta_data;
	}

	public function do_meta_boxes($post_type, $post)
	{
		$content_blocks = array();

		foreach($this->blocks as $block)
		{
			$content_blocks = $block->do_meta_boxes($content_blocks, $post);
		}

		return $content_blocks;
	}

	/** @param $post_id Saving post_id
	*		@param $post  Post Array of submitted values
	**/
	public function save_meta_boxes($post_id, $post)
	{
		$post_meta = array();

		foreach($this->blocks as $block)
		{
			$post_meta = $block->save_meta_boxes($post_meta, $post_id, $post);

		}

		if (count($post_meta) > 0)
			update_post_meta($post_id, 'mbsocial_data', $post_meta);
	}

	function showBlocks()
	{
		foreach($this->blocks as $block)
		{
			echo $block->admin();
		}

		$active = isset($this->data['network']['network_active']) ? $this->data['network']['network_active'] : array();

		if ( is_array($active) && count($active) > 0)
		{
			foreach($active as $network_name)
			{
				$network = MbSocial()->networks()->get($network_name);
				if ( is_object($network))
					echo $network->admin($this->data);
			}
		}
	}
} //class
