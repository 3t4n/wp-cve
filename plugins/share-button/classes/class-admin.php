<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

// Helper for admin pages / fields / saving etc.

class admin
{
	protected $fields = array();
	protected $defined_fields = array();
	protected $defined_updatable = array();
	protected static $namespace;

	public function __construct()
	{
		self::$namespace = __NAMESPACE__ . '\\';  // PHP 5.3

	}

	// add a maxfield to be displayed on the admin.
	public function addfield( $field, $start = '', $end = '', $trigger_update = true)
	{
		$field_id = isset($field->id) ? $field->id : $field->template . \rand(0,1000);
		if ($trigger_update)
		{
			$this->addUpdate($field); // make updating standard
		}
		$field->publish = false;  // Output fields via class - never output.

				$this->fields[$field_id] = array('field' => $field,
								   'start' => $start,
								   'end' => $end);
		$this->fields = apply_filters('mbsocial/editor/addfield', $this->fields, $field);

		$this->defined_fields[] = $field_id;

	}

	public function addUpdate($field)
	{
		$field_id = $field->id;
		if (is_null($field_id) || $field_id == '')
			return;  // no bad names

		if (! isset($this->defined_updatable[$field_id])) // no double sets
			$this->defined_updatable[] = $field_id;
	}


	public function namespaceit($var)
	{
		return self::$namespace . $var;
	}

	public function display_fields($clean = true, $return = false)
	{
		$fields = apply_filters('mbsocial/display_fields', $this->fields);
		$output = '';

		foreach($fields as $id => $item)
		{
			$field = $item['field'];
 			$output .= $field->output($item['start'], $item['end']);
		}

		// auto-update system
		$updatable = $this->defined_updatable;
		foreach($updatable as $index => $field)
		{
			$updatable[$index] = '#' . $field;
		}

		$output .= ' <span class="updatables hidden">' . implode(',', $updatable) . '</span>';

		if ($clean)
		{
			$this->fields = array();
			$this->defined_updatable = array();
		}

		if (! $return)
			echo $output;
		else
			return $output;

	}

	public function output_defined_fields()
	{
		$fields = $this->defined_fields;
		$fields = implode(',', $fields);

		echo '<input type="hidden" name="defined_fields" value="' . $fields . '">';
	}

	/** @param $active_networks - Networks that were saved by the users. This should be loaded instead of the default ones **/
	public function get_default_networks($active_networks = array() )
	{
		if (! is_array($active_networks))
		{
			$active_networks = array();
		}
		$networks = mbSocial()->networks()->get();
		$selected = array();
		$unselected = array();
		$readmore = array();

		// set active networks. Support doubles.
		foreach($active_networks as $index => $network_name)
		{
			$network = mbSocial()->networks()->get($network_name);
			if (! is_object($network)) // could be removed network.
				continue;
			if ($network->get('is_active'))
				$selected[$index] = $network;
		}

		foreach($networks as $index => $network)
		{
			$priority = $network->get('priority');
			if (! $network->get('is_active'))
			{ continue; }
			$is_active = array_search($network->get('network'), $active_networks); // retrieve the array index

			if ($is_active !== false)
			{
				$priority = 'selected';
			}

			if ($priority == 'unselected')
				$unselected[] = $network;
			if ($priority == 'readmore')
				$readmore[] = $network;

				if ($network->get('network') == 'maxbutton' && $priority != 'readmore') // exceptions
				{

						$readmore[] = $network;
				}


		} // foreach networks

		// Sort networks by order saved.
		ksort($selected);
		return array('selected' => $selected, 'unselected' => $unselected, 'readmore' => $readmore);
	}



	/* Load saved networks, check which ones are selected. Load others as default settings if no save */
	public function get_networks($active_networks = array() )
	{
		$networks_array = $this->get_default_networks($active_networks);
		return $networks_array;
	}

	// render icons with input from different icon libraries (/future)
	public function renderIcon($args)
	{
		$defaults = array('icon' => '',
									'icon_type' => '',
									'icon_size' => 20,
									'title' => '',
		);

		$args = wp_parse_args($args, $defaults);

		$icon_type = $args['icon_type'];
		$icon = $args['icon'];
		$icon_size = $args['icon_size'];
		$title = $args['title'];

				$faUtils = FAUtils::getInstance();

				switch($icon_type)
				{
					case 'fa': // FA5
					case 'fab':
					case 'fas':
					case 'far':
						  $args = array('icon' => trim($icon_type) . ' ' . trim($icon), 'title' => $title);
							if ($icon_size)
							{
								$args['size'] = $icon_size;
							}

							$svg = $faUtils->getFASVG($args);
							$output = "<span class='mb-icon'>$svg</span>";
					break;
					case 'nucleo':
						$output = "<span class='mb-icon'><i class='$icon_type $icon' title='$title'  > </i></span>";
					break;
					case 'svg':
					case 'png': // via network settings, or whatever.
						$output = "<span class='mb-icon " . $icon_type . "'><img  src='" . $icon . "' alt='" . $title . "'></span>";
					break;
					case 'image':  // via media uploader
					case 'image-remote':
							if ($icon_type == 'image-remote') // when loading a custom network, before import
							{
									$url = $args['image_url'];
									$output = '<img src="' . $url . '" border="0" />';
							}
							else {
								$image_id = isset($args['image_id']) ? $args['image_id'] : false;
								$image_size = isset($args['image_size']) ? $args['image_size'] : false;

								$img_data = wp_get_attachment_image_src($image_id, $image_size);
								$output = '';
								
								if ($img_data)
								{
									$width = $img_data[1];
									$height = $img_data[2];
									$url = $img_data[0];
									$output = '<span class="mb-icon ' . $icon_type . '"><img src="' . $url . '" width="' . $width . '" height="' . $height . '" border="0" /></span>';
								}

							}
					break;
					default:
						$output = '';
					break;
				}

				return apply_filters('display/render/icon', $output, $args);
	}

	public function applyVars($string)
	{
		//$vars = $this->getShareData();
		$w = MBSocial()->whistle();

		preg_match_all('/\{(.*?)\}/im', $string, $matches);
		//$vars = (isset($vars[0]) && count($vars[0]) > 0) ? $vars[0] : array();

		if (count($matches) == 0)
			return $string;

			$vars = $matches[0]; // i.e. {url}
			$clean_vars = $matches[1];  // i.e. url

		for($i = 0; $i < count($vars); $i++)
		{
				$var = $vars[$i];
				$clean_var = $clean_vars[$i];

				if (strpos($string, $var) !== false)
				{
						$val = $w->ask('display/vars/' . $clean_var);
						$string = str_replace($var, $val, $string);
				}

		}

		return $string;
	}

	public function header( $args = array() )
	{
		$defaults = array(
			'title' => '',
		);

		$args = array_merge($defaults, $args);
		extract($args);

		include_once( mbSocial()->get_plugin_path() . '/admin/header.php');
	}

	public function footer()
	{
		include_once( mbSocial()->get_plugin_path() . '/admin/footer.php');
	}

	public function getProMessage()
	{
		return __( sprintf('You can only change these settings in the PRO version.  Click %shere%s for upgrading to MaxButtons PRO', '
							<a href="https://maxbuttons.com" target="_blank">', '</a>') );
	}


	public function do_review_notice () {

			$current_user_id = get_current_user_id();
			$version = MBSOCIAL_VERSION_NUM;

			$review = get_user_meta( $current_user_id, 'mbsocial_review_notice' , true );

				if ($review == '')
				{
				//$created = get_option("MBFREE_CREATED");
				$show = time() + (7* DAY_IN_SECONDS);
				update_user_meta($current_user_id, 'mbsocial_review_notice', $show);
				return;
				}

				$display_review = false;

				if ($review == 'off')
				{	return; // no show

			}
				elseif (is_numeric($review))
				{
					$now = time();

					if ($now > $review)
					{
					$display_review = true;

					}
				}

				// load style / script. It's seperated since it should show everywhere in admin.
				if ($display_review)
				{
						add_action( 'admin_notices', array( $this, 'mbsocial_review_notice'));
						// registered in admin scripts
						if (method_exists(MB(), 'load_library'))
							MB()->load_library('review_notice');
				}

		}

		public function mbsocial_review_notice()
		{
			include_once( mbSocial()->get_plugin_path() . '/admin/review_notice.php');
		}

		public function save_review_notice()
		{
				$status = isset($_POST["status"]) ? sanitize_text_field($_POST["status"]) : '';
			$current_user_id = get_current_user_id();

			$updated = false;

			if ($status == 'off')
			{
				$updated = true;
				update_user_meta($current_user_id, 'mbsocial_review_notice', 'off');

			}
			if ($status == 'later')
			{
				$updated = true;
				$later = time() + (14 * DAY_IN_SECONDS );

				update_user_meta($current_user_id, 'mbsocial_review_notice', $later);
			}
			if ($status == 'reviewoffer-dismiss') // different ad!
			{
				$updated = true;
				update_user_meta($current_user_id, 'mbsocial_review_notice', 'off');

			}

			echo json_encode(array("updated" => $updated)) ;

			exit();
		}

}
