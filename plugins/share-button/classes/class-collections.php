<?php
namespace MBSocial;
use MaxButtons\maxUtils as maxUtils;
use MaxButtons\maxBlocks as maxBlocks;

defined('ABSPATH') or die('No direct access permitted');



class collections
{
	static $init = false;
	//static $collectionClass = array();
	static $collectionBlocks = array();

	static $hooks = array();

	protected static $transientChecked = false;

	static $collectionButtons = null; // all button ID's in a collection.

	static function init()
	{

		self::$init = true;

	}

	public static function shortcode($atts, $content = null)
	{
			$display_args = shortcode_atts(array(
					 	'id' => 0,
						"echo" => false,
						"mode" => "normal",
						"nocache" => false,
						"style" => 'footer',
					),

			$atts);

			$collection_id = $display_args['id'];

			if($collection_id == 0)
					return ''; // nothing.

			$collection = new collection($collection_id);
			$collection->setHook('shortcode');


			//$this->set($collection_id);
			$display_args["compile"] = $display_args["nocache"];
			$display_args['load_type'] = $display_args['style'];
			unset($display_args['style']);
			unset($display_args["nocache"]);

			$output = $collection->display($display_args);

			return $output;
	}

	/*
	Check our custom transients for expiration. This should be done only once per run or less.
	*/
	static function checkExpireTrans()
	{
		if (! self::$transientChecked)
		{
			maxUtils::removeExpiredTrans();  // instead of on each button to reduce load.
			self::$transientChecked = true;
		}
	}
	/*
	Function to hook into WordPress for automatic display of collections.
	*/
	static function setupHooks()
	{
		// check for admin side, we need not hooks nor queries for this.
		if (is_admin())
			return;

		global $pagenow;
		if  ( in_array($pagenow, array('wp-login.php', 'wp-register.php')) )
			return;

		global $wpdb;
		$table = maxUtils::get_collection_table_name();
		$sql =  "select collection_id, collection_key, collection_value from $table where
				 collection_key like 'display_%' and collection_id IN (select collection_id from $table where collection_key = 'collection_type'
				 and collection_value  = 'social_share' ) ";
		$results = $wpdb->get_results($sql, ARRAY_A);

		$hook_array = array();

		foreach($results as $result)
		{
			$id = $result["collection_id"];
			$key = $result["collection_key"];
			$placement = $result["collection_value"];

			// override - if needed you can specify a different placement for each position
			$placement = apply_filters("mb-collection-$id-$key-placement", $placement);

			switch($placement)
			{
				case "after":
				case "before":
				case "after-before":
					$show_type = "post"; // show on the post loop
				break;
				case "static";
					$show_type = "once"; // show once - otherwise for overviews they will be many static buttons.
				break;
				case "hidden":
				default:
					$show_type = 'none';
				break;
			}

 				if ($show_type != 'none')
					$hook_array[$show_type][$key][] = array("collection_id" => $id, "placement" => $placement);
		}

		self::$hooks = $hook_array;

		if (isset($hook_array["post"]) && count($hook_array["post"]) > 0)
		{
			add_filter("the_content", array(MBSocial()->admin()->namespaceit('collections'), "doContentHooks"));
		}
		if (isset($hook_array["once"]) && count($hook_array["once"]) > 0)
		{
			//self::doFooterHooks(); // the stuff that goes once.
			add_action('wp_head', array(MBSocial()->admin()->namespaceit('collections'), 'doFooterHooks'));
		}

		if (count($hook_array) > 0)
			return true; // yes, bind action and check
		else
			return false; // no binds, don't check.
	}

	// When MB PRO is configured to output CSS via file.
	static function outputFileCSS()
	{
		 $collection_id = intval($_GET['id']);
		 $doc_id = intval($_GET['doc']);
		 $collection = new collection($collection_id);
		 $css = $collection->getDisplayCSS($doc_id);
		 header( "Content-type: text/css; charset: UTF-8" );

		 if ($css)
		 {
		 	header( "Content-type: text/css; charset: UTF-8" );
		 	echo $css;
	 	 }
		 exit();

	}

 	/* Try to find the current place we are at in the site ( front / blog / page etc ) .*/
 	protected static function getCurrentHook()
 	{
		$hook = '';
 		if (is_front_page() )	// check if home page ( home page > page )
 		{
 			$hook = "display_homepage";
 		}

 		elseif (is_page()) // check if page
  		{
			$hook = "display_page";
 		}
 		elseif (is_single()) // check if post
 		{
 			$hook = "display_post";
 		}
 		elseif (is_archive() || is_home() && ! is_front_page())  // second stat, is if post main page, in static mode.
 		{
 			$hook = "display_archive";
 		}

			$post_types = \get_post_types(
								array('public' => true,
								  '_builtin' => false,
								 )
					);

			$this_post_type = get_post_type( get_the_ID() );

			foreach($post_types as $post_type) // custom post type
			{
				if ($post_type == $this_post_type)
				{
					$hook = 'display_' . $this_post_type;
				}

			}


 		return $hook;
 	}

 	static function doContentHooks($content)
 	{
 		$hook_array = self::$hooks["post"];
  		$hook = self::getCurrentHook();

 		if ($hook == '')
 			return $content;  // nothing

 		if (! isset($hook_array[$hook]))  // nothing as well
 			return $content;

 		 $collections = $hook_array[$hook];

 		 // do all collections on hook -- check for placement as well.
 		 foreach($collections as $settings)
 		 {
 		 	$collection_id = $settings["collection_id"];
 		 	$placement = $settings["placement"];

 		 	//$col = self::getCollectionByID($collection_id);
		 	$collection = new collection($collection_id);
		 	$collection->set($collection_id);
		 	$collection->setHook($hook);
		 	$output = $collection->display(array("echo" => false)); // output default, no echo

 		 	switch($placement) // where to output, rather limited atm.
 		 	{
 		 		case "before":
 		 			$place = "before";
 		 		break;
 		 		case "after-before";
 		 			$place = "both";
 		 		break;
 		 		default:
 		 			$place = "after";
 		 		break;
 		 	}

 		 	if($place == 'before' || $place == 'both')
 		 	{
 		 		$content = $output . $content;
 		 	}
 		 	if($place == 'after' || $place == 'both')
 		 	{

 		 		$content = $content . $output;
 		 	}
 		 }

 		 return $content;
 	}

 	static function doFooterHooks()
 	{
 		$hook_array = self::$hooks["once"];
 		$hook = self::getCurrentHook();

 		if (! isset($hook_array[$hook]))  // nothing
 			return;

 		 $collections = $hook_array[$hook];

 		 foreach($collections as $settings)
 		 {
 		 	$collection_id = $settings["collection_id"];
 		 	$placement = $settings["placement"];

 		 	$collection = new collection($collection_id);
 		 	$collection->setHook($hook);

 		 	$output = $collection->display(array("echo" => false)); // output default, no echo

			do_action('mb-footer', 'collection-' . $collection_id, $output, 'collection_output');

 		 }
 	}

 	static function ajax_save()
 	{
 		$form = maybe_unserialize($_POST['form']);

 		$post_form = array();
 		parse_str($form, $post_form);

	 	$collection_id = intval($post_form["collection_id"]);

		$admin = MB()->getClass('admin');


		if (! Install::isPro() && $collection_id == 0)
		{
			$collections = self::getCollections();
			if (count($collections) > 0)
			{
				$result = array(
							 "error" => true,
				);
				$admin->endAjaxRequest($result);
			}
		}



	 	$close_text = __('Close', 'maxbuttons');

	 	$result = array(
	 			   "error" => false,
	 			   "body" => __("Saved", 'mbsocial'),
	 			   "result" => true,
	 			   "data" => array(),
	 			   "close_text" => $close_text,
	 			   "new_nonce" => 0,
	 			   "title" => __("Saved!","mbsocial"),
	 	);

		$collection = new collection();
		$collection->set($collection_id);

		// this can be a new id (!)
		$collection_id = $collection->save($post_form);

 		$result["data"]["id"] = $collection_id;

 	//	$result["data"]["new_nonce"] = wp_create_nonce($action . "-" . $collection_id);
 	//	$result["data"]["reload"] = apply_filters("collections_ajax_force_reload",$force_reload);

 	//	$result["title"] = $result_title["success"];

 		$admin->endAjaxRequest($result);
 	}

	static function ajax_remove_collection()
	{
		$admin = MB()->getClass('admin');

		$collection_id = intval($_POST['param']);

		$url = admin_url('?page=maxbuttons-social');

		$result = array(
			'error' => false,
			'redirect' => $url,
		);

		global $wpdb;

		$table = maxUtils::get_collection_table_name();
		$where = array("collection_id" => $collection_id);
		$where_format = array("%d");
		$wpdb->delete($table, $where, $where_format);


		$admin->endAjaxRequest($result);

	}

 	static function ajax_action_front()
 	{

 		// only for trivial front page actions!
 		self::ajax_action(array("ajax_nopriv" => true));
 	}

 	static function ajax_action($args = array())
 	{
 		ob_start();
 		$defaults = array("ajax_nopriv" => false);
 		$args = wp_parse_args($args, $defaults);


 	 	$admin = MB()->getClass('admin');

 		$nonce = isset($_POST["nonce"]) ? $_POST["nonce"] : false;
 		$block_name = sanitize_text_field($_POST["block_name"]);
 		$block_action  = sanitize_text_field($_POST["block_action"]);
 		$block_data = (isset($_POST["block_data"])) ? $_POST["block_data"] : '';
 		$action  = sanitize_text_field($_POST["action"]);

 		$collection_id = intval($_POST["collection_id"]);
	 //	$collection_type = sanitize_text_field($_POST["collection_type"]);

		if(! $args["ajax_nopriv"])
		{
	 		if (! wp_verify_nonce($nonce, $action . "-" . $collection_id))
	 		{
	 			$result["error"] = true;
				$result["body"] = __("Nonce not verified","maxbuttons");
				$result["result"] = false;
				$result["title"] = __("Security error","maxbuttons");
				$result["data"] = array("id" => $collection_id);

	 			$admin->endAjaxRequest($result);

	 		}
		}

	 	$result = array(
	 			   "error" => false,
	 			   "body" => '',
	 			   "result" => true,
	 			   "data" => array(),
	 			   "new_nonce" => 0,
	 	);

 		$collection = new collection($collection_id);

		$result = $collection->doBlockAjax($result, $block_name, $block_action, $block_data);

	 	//ob_end_clean();  // prevent PHP errors from breaking JSON response.

	 	$admin->endAjaxRequest($result);
	 			$results = $collection->get_meta($name, 'collection_name');
 	}


	public static function ajax_refreshblock()
	{
		$post = $_POST;
		$collection_id = intval($_POST['collection_id']);
		$target_class = sanitize_text_field($_POST['block_name']);
		//$source_block = sanitize_text_field($_POST['source_block']);

 		$postdata = array();
 		parse_str( $_POST['form'], $postdata );

		// invoke correct collection
		$collection = new collection($collection_id);


		$blocks = self::getBlocks();
		$data = array();
		foreach($blocks as $block_class)
		{
			$block = $collection->getBlock($block_class);
			$data = $block->save_fields($data, $postdata);
		}
		foreach($blocks as $block_class)
		{
			$block = $collection->getBlock($block_class);
			$block->set($data);
		}

		foreach($data as $blockname => $blockdata)
		{

			$collection->setData($blockname, $blockdata);
		}
		// get the requested block
		$target_block = $collection->getBlock($target_class);
		$target_block_name = $target_block->get_name();

		//foreach($blockdata as $source_block => $data)
		//{
		// something wrong here ( you're welcome )
		/*	$s_block = $collection->getBlock($source_block);
			$source_name = $s_block->get_name();

			$s_block->set(array($source_name => $data));
			maxBlocks::setData(array($source_name => $data ) );
		} */

		if ($target_block)
		{
			ob_start();
				$target_block->admin();
			$content = ob_get_contents();
			ob_clean();
		}

		$result = array(
			'status' => true,
			'content' => $content,
			'block' => $target_class,
			'block_name' => $target_block_name,
		);

		echo json_encode($result);

		exit();

	}

	public static function ajax_getpresets()
	{
		$post = $_POST;
		$admin = mbSocial()->admin();

		$collection_id = isset($post['collection_id']) ? intval($post['collection_id']) : false;
		//$current_style = isset($post['current_style']) ? sanitize_text_field($post['current_style']): false;

		//$collection = new collection($collection_id);

		$presets = new presets();
		$sets = $presets->get();
		$labels = $presets->getLabels();

		$json = array();

	    $args = array('preview' => true,
		 			   'load_type' => 'inline',
		 			   'echo' => false,
		 			   'compile' => true,
		 			  );

			collection::setCount(10);

			foreach($sets as $name => $set)
			{
				$set = json_decode($set, ARRAY_A);
				//$styleBlock = $collection->getBlock('styleBlock');
				//$styleBlock->add_preview_style = $style_class->class;
			//$collection->setStyle($style_class);

				$stylename = $set['style']['mbs-style'];
				$style = styles::getStyle($stylename);

				$collection = new collection($collection_id);

				$collection->setStyle($style);

				foreach($set as $blockname => $blockdata)
				{
					$collection->setData($blockname, $blockdata);
				}

				$networks = $admin->get_networks();
				$networks = $networks['unselected'];

				$set_active = array();
				foreach($networks as $nwObj)
				{
					$set_active[] = $nwObj->get('network');
				}

				$collection->setData('network', array('network_active' => $set_active) );

				$label = $labels[$name];

				// $collection->display($args);
			//	$countBlock = $collection->getBlock('countBlock');
			//	$countBlock->resetTotal();

				$json[] = array('name' => $name,
									  'collection' => $collection->display($args),
										'label' => $label,
								  );
			}


		echo json_encode($json);
		exit();
	}

	public static function ajax_setPreset()
	{
		 $collection_id = intval($_POST['collection_id']);
		 $preset = sanitize_text_field($_POST['preset']);

		 $collection = new collection($collection_id);

		$presets = new presets();
 		$sets = $presets->get();
 		$labels = $presets->getLabels();

		$do_set = json_decode($sets[$preset], ARRAY_A);

		foreach($do_set as $blockname => $blockdata)
		{
			$collection->setData($blockname, $blockdata);
		}

		$collection->saveData();

		$collection_id = $collection->getID();
		$url = admin_url('?page=maxbuttons-social');
		$url = add_query_arg('social_id', $collection_id, $url);

		echo json_encode( array(
				'done' => true,
				'redirect' => $url,
			) );
		exit();

	}

	static function getCollections()
	{
		if (! self::$init) self::init();

		global $wpdb;

		$table = maxUtils::get_collection_table_name();

		$sql = "SELECT distinct collection_id from $table WHERE collection_key = 'collection_type' and collection_value = 'social_share' order by collection_id ";

		$results = $wpdb->get_col($sql);
		return $results;
	}


	/* This will find an user defined collection from the database by ID -  */
	// This function is outdated and should be removed in time
/*static function getCollectionByID($id)
	{
		$collection = new collection($id);

		return $collection;
	/*	$results = $collection->get_meta($id, 'collection_type');


		if ( count($results) == 1)
		{

			$type = $results["collection_type"];

			$usecol = self::getCollection($type);
			$usecol->set($id);
			return $usecol;
		}

		return false;
	} */

	/* Find a collection from the database by name */
	public static function getCollectionbyName($name)
	{
		//$collection = new maxCollection();
		global $wpdb;
		$sql = "select collection_id from " . maxUtils::get_collection_table_name() . " where collection_key = 'collection_name' and collection_value = %s ";
		$sql = $wpdb->prepare($sql, $name);
		$result = $wpdb->get_row($sql, ARRAY_A); // find first


		if (count($result) > 0)
		{
			if (isset($result["collection_id"]))
			{

				//$usecol = self::getCollectionByID($result["collection_id"]);
				$collection = new collection($result['collection_id']);
				//return $usecol;
			}

		}
		return false;
	}


	public static function getBlock($name)
	{
		if (! self::$init) self::init();

		if ($index = array_search($name, self::$collectionBlocks))
		{
			$classname = __NAMESPACE__ . "\\" . self::$collectionBlocks[$index];
			return new $classname;
		}
		else return false;
	}

	public static function setBlocks($blocks)
	{
		$new_array = array();

		foreach($blocks as $block)
		{
			$order = $block['order'];
			if (isset($new_array[$order]))
				$new_array[$order][] = $block['class'];
			else
				$new_array[$order] = $block['class'];

		}
		ksort($new_array);
		self::$collectionBlocks = $new_array;

	}

	public static function getBlocks()
	{
		$blocks = array();
		foreach(self::$collectionBlocks as $index => $block)
		{
			$blocks[] = $block;
		}

		return $blocks;
	}



} // class
