<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

$collectionBlock["network"] = array('order' => 10,
									'class' => "networkBlock");

use \MaxButtons\simple_html_dom as simple_html_dom;
use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxInstall as maxInstall;

class networkBlock extends block
{
	protected $blockname = "network";
	protected $fields = array( 'network_active' => array('default' => array() )

);

	protected $meta_fields = array(
					'share_url' => array('default' => ''),
  				 );

	protected $share_data;

	protected $index_helper = -1;

	public function __construct()
	{
		parent::__construct();
		MBSocial()->whistle()->listen('display/parse/network', array($this, 'setShareData'), 'tell');
	}

	public function parse($domObj, $args = array() )
	{
		$w = MBSocial()->whistle();

		$output = '';
 		$blockdata = $this->data[$this->blockname];
 		$admin = MBSocial()->admin();

		$network = $w->ask('display/parse/network');
		$is_popup = $network->is_popup();

 		$itemObj = $domObj->find('a', 0);

		$url = $itemObj->href;

		// replace all {} variables with data
 		$url = $admin->applyVars($url);

		// remove empty query parts from the URL
		$parts  = parse_url($url);
		if(isset($parts['query']) > 0)
		{
			parse_str($parts['query'], $query);
			 foreach($query as $query_arg => $query_val )
			 {
				 if (strlen($query_val) == 0)
				 		$url = remove_query_arg($query_arg, $url);
			 }
		}

		$itemObj->href = $url;

 		if ($is_popup)
 		{
 			$tag = 'data-popup';
 			$dimensions = $network->get('popup_dimensions');
 			$dimensions = array('width' => $dimensions[0], 'height' => $dimensions[1]);
 			$itemObj->$tag = htmlentities(json_encode($dimensions));
 		}
		else {
			if (! $network->get('forcesamewindow'))
				$itemObj->target = "_blank";
		}

		$itemObj->rel = 'noopener nofollow';

		$icon = $this->renderIcon();
		if ($icon)
		{
			$obj = $domObj->find('.mb-social', 0);
			if (is_object($obj))
			{
					$obj->innertext .= "<span class='mb-icon-wrapper'>
											 " . $icon . " </span>";
	  			$this->collection->getStyle()->addDisplay('icon');
			}
		}

		return $domObj;

	}


	public function renderIcon($network = false)
	{

		 $w= MBSocial()->whistle();

		if (! $network)
			$network = $w->ask('display/parse/network');

		$icon = $network->get('icon');
		$icon_type = $network->get('icon_type');

		$network_name = $network->get_nice_name();

		$icon_size = false;
		if ($this->collection)
		{
			$layoutBlock = $this->collection->getBlock('layoutBlock');
			$icon_size = $layoutBlock->getValue('font_icon_size');
		}

		$args = array('icon' => $icon,
									'icon_type' => $icon_type,
									'title' => $network_name,
									'icon_size' => $icon_size
						);

		if ($icon_type == 'image')
		{
			$args['image_id'] = $network->get('icon_image_id');
			$args['image_size'] = $network->get('icon_image_size');
			$args['image_url'] = $network->get('icon_image_url');
		}

		return MBSocial()->admin()->renderIcon($args);

	}

	/** Get data from the page to use in sharing. Like page title, url and image

	 **/
 	public function setShareData()
 	{
 		if (! is_null($this->share_data))
 			return $this->share_data;

		$w = MBSocial()->whistle();

 		$url = '';
 		$title = '';
 		$img = '';

 		$hook = $w->ask('display/env/hook'); // $this->collection->getHook();

		$post_id = $w->ask('display/env/post_id');

 		$share_setting = 'auto';

 		if ( isset($this->data['display'][$hook . '_options']))
 		{
 			$options = $this->data['display'][$hook . '_options'];

 			$share_setting = isset($options['share_setting']) ? $options['share_setting'] : 'auto';
 			$custom = isset($options['share_custom_url']) ? $options['share_custom_url'] : '';
			$custom_title = isset($options['share_custom_title']) ? $options['share_custom_title'] : '';
 		}
 		switch($share_setting)
 		{
 			case "home":
 				$url = get_home_url();
 				$title = get_bloginfo('name');
 				$img = $this->getImage(null, true);
 			break;
 			case "custom-url";
 				$url = $custom;
				$title = $custom_title;
 				$img = $this->getImage();
 			break;
 			case "current-post":
				$url = get_permalink();
				$title = get_the_title();
				$img = $this->getImage();
			break;
 			case "auto":
 			default:
	 				/* After / before being placed on the post data, therefore by default (auto) always use the POST URL
	 				Static is being placed once(1) on the page, so get the greated to-share url ( of the whole section ) */
				if ($this->collection->is_post)
	 			{
	 					 	$url = get_permalink();
 							$title = get_the_title();
 							$img = $this->getImage();

	 			}
	 			if ($this->collection->is_static)
				{

				 	if (is_front_page())
					{
					 	$url = get_home_url();
						$title = get_bloginfo('name');
		 				$img = $this->getImage(null, true);
					}
					elseif (is_category() )
					{
						$obj = get_queried_object();
						$category_id = $obj->cat_ID;
						$url = get_category_link($category_id);
						$title = $obj->category_nicename;

					}
					else // are there any other cases? Page should give back their main url like this.
					{
						$url = get_permalink();
						$title = get_the_title();
					 	$img = $this->getImage();
					}
	 			}

 			break;
 		}

		// Check for custom URL settings
		if ($post_id > 0)
		{
				$metadata = $this->get_block_meta_data($post_id);
				if (isset($metadata['share_url']) && strlen( trim($metadata['share_url']) ) > 0 )
				{
					$url = $metadata['share_url'];
				}
		}

 		$data = array("url" => $url,
 			"title" => $title,
 			"img" => $img);

		$w->tell('display/vars/url', $url);
		$w->tell('display/vars/title', $title);
		$w->tell('display/vars/img', $img);

 		$this->share_data = $data;
 		return $data;

 	}

 	protected function getImage($post_id = null, $home = false)
 	{
		if (is_admin())
			return ''; // in admin all this is not set.

 		if ($home && get_option('show_on_front') === 'page')
 		{
 			$post_id = get_option('page_on_front');

 		}

 		$thumb_id = get_post_thumbnail_id($post_id); // First try to find thumbnail on the content

 		$image = false;
		if (is_numeric($thumb_id))
		{
			$image = wp_get_attachment_url($thumb_id);
		}

		if ($image !== false)
			return $image;

		// In case of no thumbnails, tries to find first image from current content.

//		if (! is_null($post_id))
//		{
			$post = get_post($post_id);
			$content = '';
			if (! is_null($post))
				$content = $post->post_content;
/*		}
		else
		{

			$content = get_the_content();
		} */
		$domObj = new simple_html_dom();
		$domObj->load($content);

		$img = $domObj->find('img', 0);
 		if ($img)
 		{
 			$image = $img->src;
 			return $image;
 		}
 		else
 			return '';
 	}



	function save_fields($data, $post)
	{
		$data = parent::save_fields($data, $post);

		$selection = array();

		$button = MB()->getClass("button");
		$network_active = isset($post['network_item_active']) ? $post['network_item_active'] : array();

		// Dummy is to ensure conditionals work on network_active.
		$index = array_search('dummy', $network_active);
		if ($index !== false)
			 unset($network_active[$index]);


		//mbcustom
		$mbcustom = isset($post['mbcustom_id']) ? $post['mbcustom_id'] : array();
		$data[$this->blockname]['mbcustom'] = array();

		if (is_array($mbcustom))
		{
			$i = 0;
			foreach($mbcustom as $index => $button_id)
			{
						if ( intval($button_id) > 0)
						{
							$usenetwork = isset($post['mbcustom_usenetwork'][$index]) ? intval($post['mbcustom_usenetwork'][$index]) : 0;
							$network = isset($post['mbcustom_network'][$index]) ? sanitize_text_field($post['mbcustom_network'][$index]) : '';
							$url = isset($post['mbcustom_url'][$index]) ? sanitize_text_field($post['mbcustom_url'][$index]) : '';
							$text = isset($post['mbcustom_text'][$index]) ? sanitize_text_field($post['mbcustom_text'][$index]) : '';

							$data[$this->blockname]['mbcustom'][$i] = array(
																								'button_id' => $button_id,
																								'use_network' => $usenetwork,
																								'network' => $network,
																								'url' => $url,
																								'text' => $text,
																							);
								$i++; // this is not index. Start saving from index 0.
						}


			}
		}

		// new user, give default networks
		$collection_id = $this->collection->getID();


		if (count($network_active) == 0 && $collection_id == 0)
	 		$network_active = array('facebook', 'twitter'); // default options



		$data[$this->blockname]["network_active"] = $network_active;
		return $data;
	}


	public function do_meta_boxes($content, $post)
	{
		$admin = mbSocial()->admin();

		$metadata = $this->get_block_meta_data($post->ID);

		$url = new maxField();
		$url->id = 'share_url';
		$url->name = $url->id;
		$url->label = __('URL to Share', 'mbsocial');
		$url->note = __('By default the URL of the post is being shared. Leave empty for default', 'mbsocial');
		$url->value = isset($metadata['share_url']) ? $metadata['share_url'] : '';
		$url->placeholder = 'https://';

		$admin->addField($url, 'start', 'end');

		$fields = $admin->display_fields(true, true, false);


		$content['share'] = array('title' => __('Share Options', 'mbsocial'),
									'icon' => 'alt-share',
									'content' => $fields,
							);

		return $content;

	}

	public function admin()
	{
		$w = mbSocial()->whistle();

		$admin = mbSocial()->admin();
		$blockdata = $this->data[$this->blockname];

		$active_networks = isset($blockdata['network_active']) ? $blockdata['network_active'] : array();
		$networks = $admin->get_networks($active_networks);
		$styleObj = $this->collection->getStyle();

/*
	No clue what this is doing here.
foreach ($networks as $type => $data)
		{
			echo $type; var_dump($data);
			foreach ($data as $network_id => $network)
			{
				$label = $network->get_nice_name();
				$icon = $network->get('icon');
				$icon_type = $network->get('icon_type');
				$color = $network->get('color');

				   $field = new maxField('switch');
					 $field->id = $network->get('name');
					 $field->label = $label;

					$admin->addField($field, 'start', 'end');
			}
		} */
	?>
		<div class='help-side'>
			<h3><?php _e('Social Networks','mbsocial'); ?></h3>
			<p><?php printf(__('%s Drag and drop %s the networks you want to use into the \'active\' box. You can find additional networks under the \'more networks\' tab.', 'mbsocial'), '<strong>', '</strong>'); ?></p>
			<p><?php __('Hold the mouse pointer over the network icon to see the network\'s name', 'mbsocial'); ?></p>
			<p>The <strong>Share Page</strong> networks will share your website's URL to other networks. </p>
			<p>The <strong>Share Profile</strong> networks will link from the website to your social accounts</p>
		</div>
		<div class='options networks option-container' id='networkBlock' data-refresh='previewBlock'>
		<?php
			$field_obj = new maxField('hidden');
			$field_obj->id = 'network_trigger_change';
			$field_obj->name = $field_obj->id;
			$field_obj->value = '';
			$field_obj->placeholder = '';

			$admin->addField($field_obj);
			$admin->addUpdate($field_obj);
			$admin->display_fields();


		?>
			<div class='title'><?php _e('Social Networks','mbsocial') ?> </div>
			<div class='inside'>
				<div class='option active_network'>
					<label><?php _e('Active','mbsocial'); ?></label>
					<div class='drag-area input' data-area='active'>
						<span class="updatables hidden">input[name="network_active"], input[name="network_item_active"]</span>

						<input type="hidden" name="network_item_active[]" value="dummy">

						<?php if (isset($networks['selected'])): foreach($networks['selected'] as $index => $network): ?>

						<?php
							$args = array();
							$args['input'] = 'network_item_active[]';
							$args['network'] = $network;

							$this->printNetwork($index, $network, $args );

						?>
						<?php endforeach;
					 			else: ?>
						<?php
					 endif; ?>
					</div>
				</div>


				<div class='option inactive_network'>
					<label><?php _e('Inactive', 'mbsocial'); ?></label>
					<div class='drag-area input' data-area='inactive'>
						<?php if (isset($networks['unselected'])): foreach($networks['unselected'] as $index => $network): ?>

								<?php $this->printNetwork($index, $network); ?>

						<?php endforeach; endif; ?>
					</div>
				</div>

				<div class='option'>
					<label>&nbsp;</label>
					<div class='see-more toggle input'><span class='title'><?php _e('more networks', 'mbsocial'); ?></span>
						<span class='see-more-wrap option toggle-target'>

							<div class='drag-area'>
								<?php if (isset($networks['readmore'])): foreach($networks['readmore'] as $index => $network): ?>

								<?php $this->printNetwork($index, $network); ?>

								<?php endforeach; endif; ?>
							</div>

							<?php if (Install::IsPro()): ?>
							<p><?php printf(__("Need another network? Check out the %s Network settings %s","mbsocial"),
							 		'<a href="' . admin_url('admin.php') . '?page=maxbuttons-social-settings" target="_blank">', '</a>');
									?>

							 </p>
						 <?php else : ?>
							 <p>
							 	<?php printf(__("More Networks or Customization needed? %s Check out our PRO offer %s ", 'mbsocial'),
									'<a href="https://maxbuttons.com/wordpress-share-buttons/#compare" target="_blank">', '</a>'); ?>
							</p>
						 <?php endif; ?>
						</span>

					</div>
				</div> <!-- option -->

				<?php
				// find caps of Networks
				$networks = mbSocial()->networks()->get();

				$nw_share = array();
				$nw_profile = array();
				foreach($networks as $nw)
				{
						if ($nw->is_share_icon())
						{
							$nw_profile[] = $nw->get_nice_name();
						}
						if ($nw->is_social_share())
						{
							$nw_share[] = $nw->get_nice_name();
						}
				}
				sort($nw_share);
				sort($nw_profile);
				?>

				<div class='option network-help'>
						<label>&nbsp;</label>

	<!--					<p>
							 <div><span class='item'><span class="legend-circle share-icon"></span> <?php _e('Share Page', 'mbsocial'); ?> </span></div>
							 <div><span class='item'><span class="legend-circle social-icon"></span> <?php _e('Share Profile','mbsocial'); ?></span></div>
							 <div><span class='item'><span class="legend-circle share-icon"></span><span class="legend-circle social-icon"></span> <?php _e('Can be used as both','mbsocial'); ?></span></div>
						</p> -->

						<p><?php _e(sprintf("The Share Profile networks will link from the website to your social accounts: %s networks.", implode(', ', $nw_profile)), 'mbsocial'
					); ?> </p>

						<p><?php _e(
								sprintf("The Share Page networks will share your websites URL to other networks: These include %s networks.", implode(', ', $nw_share)), 'mbsocial'
							); ?>
						</p>


						<?php if (Install::isPro()): ?>
						<p><?php _e('The MaxButtons option lets you add a MaxButton and configure it.','mbsocial') ?></p>
					<?php endif; ?>
				</div>

				<div class='hidden mbcustom-helper'>

						<?php
						$this->printMBCustomButton();
						$this->printMBCustomOptions(array('index' => -1)); ?>
				</div>

			</div> <!-- inside -->
		</div> <!-- option-container -->
	<?php
	}

	protected function printNetwork($index, $network, $args = array() )
	{
		if (! is_object($network))
		{
			return false;
		}
		$defaults = array(
						'input' => 'network_active',
						'content' => '',
						'network' => null);

		$args = wp_parse_args($args, $defaults);

		$w = mbSocial()->whistle();
			//$w->tell('display/parse/network', $network);
			$network_name = $network->get('network');
			$label = $network->get_nice_name();

			$icon = $network->get('icon');
			$icon_type = $network->get('icon_type');
			$color = $network->get('color');

			$icon_args = array('icon' => $icon, 'icon_type' => $icon_type);

			if ($icon_type == 'image')
			{
				$icon_args['image_id'] = $network->get('icon_image_id');
				$icon_args['image_size'] = $network->get('icon_image_size');
				$icon_args['image_url'] = $network->get('icon_image_url');
			}

			$icon_output = MBSocial()->admin()->renderIcon($icon_args);
			$is_share_icon = $network->is_share_icon();
			$is_social_share = $network->is_social_share();

			$input = $args['input'];

			$classes = $network_name;
		?>


		<span style='background-color: <?php echo $color ?>' class="item <?php echo $classes ?> " title="<?php echo $label ?>">
		<?php echo $icon_output ?>

		<?php
		/*	if ($is_share_icon)
					echo '<span class="legend-circle share-icon"></span>';
			if ($is_social_share)
				 echo '<span class="legend-circle social-icon"></span>';
	 	*/
		?>
		<input type='hidden' name='<?php echo $input ?>' value='<?php echo $network_name ?>'>

		<?php echo $args['content']; ?>
		<?php
		if (! is_null($args['network']))
		{
			$network = $args['network'];
			if ($network->get('network') == 'maxbutton')
			{
					$this->printMBCustomButton();
					$this->printMBCustomOptions(array('index' => $index));
			}
		}
		?>
		</span>
		<?php
	}


	protected function printMBCustomOptions($args = array() )
	{
			$index = isset($args['index']) ? intval($args['index']) : 0;

			$admin = mbSocial()->admin();
			$networks = mbSocial()->networks()->get();

			$nw_options = array();
			foreach($networks as $network) // list all networks, for option.
			{
				$network_name = $network->get('network');
				$label = $network->get_nice_name();

				if ($network_name == 'maxbutton')
					continue;

				$nw_options[$network_name] = $label;
			}

			$data = $this->data[$this->blockname];


			if (isset($data['mbcustom']))
			{
					$customs = $data['mbcustom'];

					$this->index_helper++;
					$settings = isset($customs[$this->index_helper]) ? $customs[$this->index_helper] : false;
					$button_id = isset($settings['button_id']) ? $settings['button_id'] : false;

					$use_value = isset($settings['use_network']) ? $settings['use_network'] : false;
					$network_value = isset($settings['network']) ? $settings['network'] : false;
					$url_value = isset($settings['url']) ? $settings['url'] : false;
					$text_value = isset($settings['text']) ? $settings['text'] : false;
			}
			else {
					$button_id = 0;
					$use_value = 0;
					$network_value = '';
					$url_value = '';
					$text_value = '';
			}

			?>
			<div class='mbcustom-options hidden'>

						<?php
						$id = new maxField('hidden');
						$id->id = 'mbcustom_id_' . $index;
						$id->name = 'mbcustom_id[' . $index . ']';
						$id->value = $button_id;

						$findex = new maxField('hidden');
						$findex->id = 'mbcustom_index[]';
						$findex->name = 'mbcustom_index[]';
						$findex->value = $index;

						$admin->addField($id, false, false, false);
						$admin->addField($findex, false, false, false);

						$switch = new MaxField('switch');
						$switch->id = 'mbcustom_usenetwork_' . $index;
						$switch->name = 'mbcustom_usenetwork[' . $index. ']';
						$switch->label = __('Use Selected Network Settings', 'mbsocial');
						$switch->note = __('This will replace the button text and URL settings', 'mbsocial');
						$switch->value = 1;
						$switch->checked = checked($switch->value, $use_value, false);

						$admin->addField($switch, 'start', 'end', false);

						$custom = new MaxField('option_select');
						$custom->id= 'mbcustom_network_' . $index;
						$custom->name = 'mbcustom_network[' . $index. ']';
						$custom->label = __('Handle as network');
						$custom->options = $nw_options;
						$custom->selected = $network_value;

						$admin->addField($custom, 'start', 'end', false);

						$url = new maxField('hidden');
						$url->id = 'mbcustom_url_' . $index;
						$url->name = 'mbcustom_url[' . $index . ']';
						$url->value = $url_value;

						$admin->addField($url, 'start', 'end', false);

						$text = new maxField('hidden');
						$text->id = 'mbcustom_text_' . $index;
						$text->name = 'mbcustom_text[' . $index . ']';
						$text->value = $text_value;

						$admin->addField($text, 'start', 'end', false);

						$admin->display_fields();

?>

			</div>

			<?php

	}

	protected function printMBCustomButton()
	{
	?>
		<span class='config_button'><button type='button' class='button-primary'   ><?php _e('Config', 'mbsocial'); ?></button></span>
	<?php
	}

}
