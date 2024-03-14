<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;

class mbSocialNetworks
{
	protected $network = "";
	protected $network_data = array();
	protected $network_name = '';

	protected $networks = array();
	protected $network_settings;


	private $custom_api_url = 'https://maxbuttons.com/getsocialnetworks/';

	public function __construct()
	{
		$this->setNetworkClasses();
		$this->getNetworkSettings();


		if (is_admin())
				add_filter( 'http_request_host_is_external', array($this, 'allow_custom_api_host'), 10, 3 );
		//$this->setNetWorks();

	}

	public function allow_custom_api_host($allow, $host, $url)
	{
		$api = parse_url($this->custom_api_url);
		if ($api['host'] == $host)
			return true;

	}

	public function get($name = null)
	{
		if (! is_null($name))
		{
			foreach($this->networks as $class => $network)
			{
				if ($name == $class || $name == $network->get('network') )
				{
					return $network;
				}
			}
			return false; // not found

		}
		else
		{
			return $this->networks;
		}
	}


	//public function

	/** Get all available subclasses of main mbNetwork class **/
	protected function setNetworkClasses()
	{

		foreach (get_declared_classes() as $class) {

		    if (is_subclass_of($class, 'MBSocial\mbNetwork'))
		    {
						if ($class != 'MBSocial\customNetwork')
						{
							$newObj = new $class;
							$name = $newObj->get('network');
							$newObj->load_settings( $this->getNetworkSettings($name) );
	        		$this->networks[$class] = $newObj;

						}
		    }
		}

		// network settings that are imported
		$custom = $this->getCustomNetworkSettings();
		if ($custom)
		{
			foreach($custom as $network)
			{
				$name = $network['name'];
				$network['is_limitedpro'] = true;
				$new = new CustomNetwork();
				$new->load_settings($network);

				$this->networks[$name] = $new;
			}
		}

		if (! Install::isPRO() )
		{
			foreach($this->networks as $index => $class)
			{
				if ($class->get('is_limitedpro') === true)
				{
					unset($this->networks[$index]);
				}
			}
		}
	}

	public function getNetworkSettings($name = false)
	{
			if (is_null($this->network_settings))
				$this->network_settings = get_option('mbsocial_network_settings', array());

			if ($name)
			{
				if (isset($this->network_settings[$name]))
				{
					return $this->network_settings[$name];
				}
				else {
					return array();
				}
			}
			else {
				return $this->network_settings;
			}
	}

	// orginal - take only the imported settings, not the network editor ( used in network editor )
	public function getCustomNetworkSettings($original = false)
	{
		$settings = $this->getNetworkSettings();

		if (is_array($settings))
		{
			$custom = isset($settings['custom']) ? $settings['custom'] : false;

			if (! $custom || ! is_array($custom))
				return false; // no settings.

				foreach($custom as $name => $network)
				{
					// merge settings from network editor if any
					$custom_settings = isset( $settings[$name] ) ? $settings[$name] : false;
					if ( (! $original) && $custom_settings)
					{
							$custom[$name] = array_merge($network, $custom_settings);
					}
				}
				return $custom;

		}
		return false;
	}

	public function ajax_networksettings($post, $echo = true)
	{
		$network_name = sanitize_text_field($post['param']);

		$network = $this->get($network_name);

		if (! $network)
		{
			exit('network not found - ' . $network_name);
		}

		$network_settings = $this->getNetworkSettings();

		$admin = MBSocial()->admin();
		maxBlocks::initBlocks();

		$default_options = $network->get_all_defaults(); // network should also load from WP
		if (isset($network_settings[$network_name]))
		{
				$options = $network_settings[$network_name];
				// per manual
				$options = $options + $default_options;
		}
		else
		{
			$options = $default_options;
		}

		$icon_types = array(
			  'fas' => __('Font Awesome Solid (fas)', 'mbsocial'),
				'fab' => __("Font Awesome Brand (fab)", 'mbsocial'),
				'far' => __('Font Awesome Regular (far)', 'mbsocial'),
				'nucleo' => __('Nucleo', 'mbsocial'),
				'image' => __('Image', 'mbsocial'),
	);

		$network_field = new maxField('hidden');
		$network_field->name = 'network';
		$network_field->id = $network_field->name;
		$network_field->value = $network_name;

		$admin->addField($network_field);

		$active = new maxField('switch');
	  $active->id = 'active';
	  $active->name = $active->id;
	  $active->label = __('Active', 'mbsocial');
		$active->value = 1;
		$active->checked = checked($options['active'], 1, false);

	  $admin->addField($active, 'start','end');

	  $label = new maxField('text');
	  $label->id = 'label';
	  $label->name = $label->id;
	  $label->value = $options['label'];
	  $label->label = __('Label', 'mbsocial');

	  $admin->addField($label, 'start', 'end');

	  $shareurl = new maxField('text');
	  $shareurl->id = 'share_url';
	  $shareurl->name = $shareurl->id;
	  $shareurl->value = $options['share_url'];
	//	$shareurl->disabled = true;
		$shareurl->help = __("{url} - will insert the relevant URL <br /> {title} - Will insert the post title ", 'mbsocial');
	  $shareurl->label = __('Share URL', 'mbsocial');

	  $admin->addField($shareurl, 'start', 'end');

	  $profileurl = new maxField('text');
	  $profileurl->id = 'profile_url';
	  $profileurl->name = $profileurl->id;
	  $profileurl->value = $options['profile_url'];
		//$profileurl->disabled = true;
		$profileurl->help = __('{profile} - Will insert the profile from settings into the URL', 'mbsocial');
	  $profileurl->label = __('Profile URL', 'mbsocial');

	  $admin->addField($profileurl,'start', 'end');

		$popup = new maxField('switch');
		$popup->id = 'popup';
		$popup->name = $popup->id;
		$popup->checked = checked($options['popup'], 1, false);
		$popup->value = 1;
		$popup->label = __('Open in Popup','mbsocial');

		$admin->addField($popup, 'start', 'end');

	  $popupwidth = new maxField('text');
	  $popupwidth->id = 'popup_width';
	  $popupwidth->name = $popupwidth->id;
	  $popupwidth->value = $options['popup_width'];
	  $popupwidth->inputclass ='small';
		$popupwidth->after_input = 'px';
	  $popupwidth->label = __('Popup Width', 'mbsocial');

	  $admin->addField($popupwidth, 'start');

	  $popupheight = new maxField('text');
	  $popupheight->id = 'popup_height';
	  $popupheight->name = $popupheight->id;
	  $popupheight->value = $options['popup_height'];
	  $popupheight->inputclass = 'tiny';
		$popupheight->after_input = 'px';
	  $popupheight->label = __('Popup Height', 'mbsocial');

	  $admin->addField($popupheight, '', 'end');

		$icon_type = new maxField('option_select');
		$icon_type->id = 'icon_type';
		$icon_type->name = $icon_type->id;
		$icon_type->label = __('Icon Type', 'mbsocial');
		$icon_type->options = $icon_types;
		$icon_type->selected = $options['icon_type'];

		$admin->addField($icon_type, 'start', 'end');

		$icon = new maxField('text');
		$icon->id = 'icon';
		$icon->name = $icon->id;
		$icon->value= $options['icon'];
		$icon->inputclass = 'large';
		$icon->label = __('Icon', 'mbsocial');
		$icon->start_conditional = htmlentities(json_encode(array('target' => $icon_type->id, 'values' => array('fas','far','fab', 'nucleo') )));

		$admin->addField($icon, 'start', 'end');

/*		$icon_svg = new maxField('textarea');
		$icon_svg->id = 'icon_svg';
		$icon_svg->name = $icon_svg->id;
		$icon_svg->value = $options['icon_svg'];
		$icon_svg->label = __('SVG', 'mbsocial');
		$icon_svg->start_conditional = htmlentities(json_encode(array('target' => $icon_type->id, 'values' => array('svg') )));

		$admin->addField($icon_svg, 'start', 'end');
*/
		Utils::imageUploader(array(
			'id' => 'icon_image',
			'show_hover_link' => false,
			'show_image_data' => false,
			'label' => __('Icon Image', 'mbsocial'),
			'data' => $options,
			'admin' => $admin,
			'conditional' => htmlentities(json_encode(array('target' => $icon_type->id, 'values' => array('image') ))),
		));

		$color = new maxField('color');
		$color->id = 'color';
		$color->name  = $color->id;
		$color->value = $options['color'];
		$color->label = __("Network Color", 'mbsocial');

		$admin->addField($color, 'start', 'end');

		$show_mobile = new maxField('switch');
		$show_mobile->id = 'displayMobile';
		$show_mobile->name = $show_mobile->id;
		$show_mobile->value = 1;
		$show_mobile->checked = checked($options['displayMobile'], 1, false);
		$show_mobile->label = __('Show on Mobile', 'mbsocial');

		$admin->addField($show_mobile, 'start', 'end');

		$show_desktop = new maxField('switch');
		$show_desktop->id = 'displayDesktop';
		$show_desktop->name = $show_desktop->id;
		$show_desktop->value = 1;
		$show_desktop->checked = checked($options['displayDesktop'], 1, false);
		$show_desktop->label = __('Show on Desktop', 'mbsocial');

		$admin->addField($show_desktop, 'start', 'end');

		$saveB = new maxField('button');
		$saveB->id = 'save_network';
		$saveB->name = $saveB->id;
		$saveB->button_label = __('Save Network', 'mbsocial');
		$saveB->dataaction = 'save-network';
		$saveB->inputclass = 'mb-ajax-submit button-primary';

		$admin->addField($saveB, 'start', '');

		if (! $network->get('is_native'))
		{
			$label = __('Remove Imported Network', 'mbsocial');
		}
		else
			$label = __('Return to defaults', 'mbsocial');

		$default = new maxField('generic');
		$default->name = 'return_default';
		$default->id = $default->name;
		$default->content = '<a class="mb-ajax-submit" data-action="remove-networksettings">' . $label . '</a>';

		$admin->addField($default, '', 'end');

	  $fields = $admin->display_fields(true, true);

		$output = array('status' => 'success',
										'output' => $fields,
										'title' => sprintf(__('Settings - %s', 'mbsocial'), $network->get_nice_name() ),
									);

		if ($echo)
		{
			echo json_encode($output);
			exit();
		}
		else {
			return $output;
		}
	}

	/** Save network settings */
	public function ajax_savenetwork($post)
	{

		if (isset($post['form']))
		{
			$form = array();
			parse_str($post['form'], $form);
		}

		$name = $form['network'];

		$network = $this->get($name);
		$network_settings = $this->getNetworkSettings(); // get all network settings

		if (! isset($network_settings[$name])) // create new entry if it doesn't exist.
		{

			$network_settings[$name] = array();
		}

		if ($network->get('is_native'))
		{
				$options = $network->get_all_defaults(); // get all options
		}
		else {
				$custom = $this->getCustomNetworkSettings(true);
				$options = $network->get_all_defaults(); // for all is_xx fields
				$custom = isset($custom[$name]) ? $custom[$name] : array();

				$options = array_merge($options, $custom);
		}

		$store_options = array(); // only save options that are not the default options of this network.

		$fields_replaced = 0;

		// ugly fix for checkboxes.
		$checkboxes = array('active', 'popup', 'displayDesktop', 'displayMobile');

		foreach($options as $item_name => $item_val)
		{
			if (isset($form[$item_name]) && $form[$item_name] != $item_val)
			{
					$value = sanitize_text_field($form[$item_name]);

					$store_options[$item_name] = $value;
					$fields_replaced++;
 			}
			elseif ( in_array($item_name, $checkboxes) && ! isset($form[$item_name])) // checkbox
			{
				 $store_options[$item_name] = 0;
				 $fields_replaced++;
			}

		}

		$network_settings[$name] = $store_options;

		update_option('mbsocial_network_settings', $network_settings, false);
		$this->network_settings = $network_settings;

		$param = array('param' => $name);
		$output = $this->ajax_networksettings($param, false);
		$output = $output['output'];

		echo json_encode(
				 array('success' => true,
							 'reload' => false,
							 'title' => __('Settings saved', 'mbsocial'),
							 'output' => $output,
							 'fields_replaced' => $fields_replaced,
		));
		exit('');
	}

	public function ajax_removesettings($post)
	{
			if (isset($post['form']))
			{
				$form = array();
				parse_str($post['form'], $form);
			}

			$network_name = $form['network'];

			$network = $this->get($network_name);
			$network_settings = $this->getNetworkSettings();

			$reload = false;
			$output = false;

			if (! $network->get('is_native') )
			{
					$custom = $this->getCustomNetworkSettings();
					if (isset($custom[$network_name])) // the custom definition
					{
						unset($custom[$network_name]);
						$network_settings['custom'] = $custom;
						update_option('mbsocial_network_settings', $network_settings, false);
					}
					if (isset($network_settings[$network_name])) // custom user settings
					{
						unset($network_settings[$network_name]);
						update_option('mbsocial_network_settings', $network_settings, false);
						$this->network_settings = $network_settings; // new ones.
					}
					$reload = true;
			}
			else
			{
					if (isset($network_settings[$network_name]))
					{
						unset($network_settings[$network_name]);
						update_option('mbsocial_network_settings', $network_settings, false);
						$this->network_settings = $network_settings; // new ones.
					}
						$param = array('param' => $network_name);
						$output = $this->ajax_networksettings($param, false);
						$output = $output['output'];
			}

			echo json_encode(
					 array('success' => true,
								 'reload' => $reload,
								 'output' => $output,
								 'title' => __('Settings removed', 'mbsocial'),
			));
			exit('');
	}

	public function ajax_showcustomnetworks()
	{
				$license = MB()->getClass('license');
				if (! $license)
					return false; // something wrong

				$key = $license->get_key();

			  $post_args = array(
						'url' => home_url(),
						'license_key' => $key,

				);

				$remote_args = array(
					'method' => 'POST',
					'body' => array( 'license' => $key ),
				);


				$response = wp_remote_post($this->custom_api_url, $remote_args);

				if ( is_wp_error($response))
				{
					// error
					$output = array('status' => 'error',
													'message' => __('Remote Error', 'mbsocial'),


										);
					echo json_encode($output);
					exit();
				}
				else {
						$result = wp_remote_retrieve_body($response);

						$result = json_decode($result, true);

						set_transient('mbsocial-social-networks', $result);

						if (isset($result['status']) && $result['status'] == 'error')
						{
							$output = array('status' => 'error',
															'message' => $result['message'],
												);
							echo json_encode($output);
							exit();
						}

						$output = $this->parseCustomResult($result);

						echo json_encode(
										array(
											'status' => 'success',
											'output' => $output,
											'title'  => __("Available additional networks", 'mbsocial'),
						));


						exit();
				}

				/*
				$output = array('status' => 'success',
												'output' => 'output');

				echo json_encode($output);
				*/
				exit();

	}

	public function parseCustomResult($result)
	{
		$output = '
		<div class= "customnetwork_select">
		<p class="cns-toolbar"><span class="selected">0</span> ' . __('Selected', 'mbsocial') . '
			 <input type="hidden" name="selected_custom" id="selected_custom" value="">
			 <button type="button" class="mb-ajax-action button-primary" data-action="import-customnetworks" disabled data-param-input="#selected_custom">
				' . __('Import', 'mbsocial') . '
			 </button>
		<ul>';

		$customs = $this->getCustomNetworkSettings();

		foreach($result as $item)
		{
			$name = ( isset($item['nice_name']) )  ? $item['nice_name'] : ucfirst($item['name']);
			$network_name = $item['name'];
			$icon =  isset($item['icon']) ? $item['icon'] : '';
			$icon_type = isset($item['icon_type']) ? $item['icon_type'] : '';

			$is_imported = isset($customs[$network_name]) ? true : false;

			$icon_args = array('icon' => $icon,
											'icon_type' => $icon_type,
											'title' => $name,
											'icon_size' => 20);
			if ($icon_type == 'image')
			{
				$icon_args['image_url'] = $item['icon_image'];
				$icon_args['icon_type'] = 'image-remote';
			}

			$the_icon = MBSocial()->admin()->renderIcon($icon_args
									);

				$output .= '<li class="the_network" data-network="' . $network_name . '">' . $the_icon . $name;
				if ($is_imported)
				{
					$output .= '<span class="is_imported">' . __('Already Imported.', 'mbsocial') . '</span>';
				}
				$output .= ' </li>';
		}

		$output .= '</ul>
		</div><p>' . sprintf(__('Network Missing? %s Email us %s','mbsocial'), '<a href="mailto:support@maxfoundry.com">', '</a>') . '</p>';

		return $output;

	}

	public function ajax_importcustomnetworks($post)
	{
			$network_settings = $this->getNetworkSettings();
			$custom_networks = get_transient('mbsocial-social-networks');

			$to_import = isset($post['param']) ? $post['param'] : false;

			if (! $to_import)
				exit();  //error

			$custom_active = isset($network_settings['custom']) ? $network_settings['custom'] : array();

			$import_count = 0;
			$to_import = explode(',', $to_import);

			foreach($to_import as $item_name)
			{
				$network_name = trim($item_name);
				$new_network = isset($custom_networks[$network_name]) ? $custom_networks[$network_name] : false;
				//if ($new_network)
				//{
					$new_network = $this->checkRemoteImage($new_network);

					$custom_active[$network_name]  = $new_network;

					$import_count++;
				//}
			}

			if ($import_count > 0)
			{
				$network_settings['custom'] = $custom_active;
				update_option('mbsocial_network_settings',$network_settings );
			}

			echo json_encode(
						array('status' => 'success',
									'import_count' => $import_count,
									'title' => sprintf(__('%s Network(s) Imported','mbsocial'), $import_count),
						)
			);
			exit();

	}

	public function checkPopup()
	{
		if (isset($this->network_data["popup"]) && $this->network_data["popup"] )
			return true;
		else
			return false;
	}

	public function getPopupDimensions()
	{
		if (isset($this->network_data["popup_dimensions"]))
			return $this->network_data["popup_dimensions"];
		else
			return array();
	}


 protected function checkRemoteImage($data)
 {
	 if (isset($data['icon_image']) && strlen($data['icon_image']) > 0)
	 {
 	 	 require_once ABSPATH . 'wp-admin/includes/file.php';
		 $image_url = $data['icon_image'];
		 $temp_file = download_url($image_url, 10);
		 if (is_wp_error($temp_file))
		 {		var_dump($temp_file); var_dump($image_url); exit('temp file, WP error'); }

		 		$mime_type = mime_content_type($temp_file);
				$file = array(
						'name'     => basename($image_url), // ex: wp-header-logo.png
						'type'     => $mime_type,
						'tmp_name' => $temp_file,
						'error'    => 0,
						'size'     => filesize($temp_file),
					);

					$overrides = array(
						'test_form' => false,
						'test_size' => true,
					);

					$results = wp_handle_sideload( $file, $overrides );

					if ( !empty( $results['error'] ) ) {
						exit('sideload failed');
					} else {

						$filename  = $results['file']; // Full path to the file
						$local_url = $results['url'];  // URL to the file in the uploads dir
						$type      = $results['type']; // MIME type of the file

						$wp_upload_dir = wp_upload_dir();
						// Prepare an array of post data for the attachment.
						$attachment_data = array(
							'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
							'post_mime_type' => $type,
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
							'post_content'   => '',
							'post_status'    => 'inherit',
						);
						$attachment_id = wp_insert_attachment( $attachment_data, $filename );
						$attach_data = wp_generate_attachment_metadata($attachment_id, $filename);
					 	wp_update_attachment_metadata( $attachment_id,  $attach_data );

						unset($data['icon_image']);
						$data['icon_type'] = 'image';
						$data['icon_image_id'] = $attachment_id;
						$data['icon_image_url'] = $local_url;
						$data['icon_image_size'] = '';
						return $data;
					}
	 }

	 return $data;
 }



}  // class
