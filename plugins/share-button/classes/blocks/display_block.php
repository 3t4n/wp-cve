<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;

$collectionBlock["display"] = array('class' => "displayBlock",
									'order' => 25);

class displayBlock extends block
{
	protected $blockname = "display";
	protected $fields = array(
						'display_page' => array('default' => 'after'),
						'display_page_options' => array('default' => array()),
						'display_post' => array('default' => 'after'),
						'display_post_options' => array('default' => array()),
						'display_homepage' => array('default' => 'hidden'),
						'display_homepage_options' => array('default' => array()),
						'display_archive' => array('default' => 'after'),
						'display_archive_options' => array('default' => array()),
						'show_desktop' => array('default' => 1),
						'show_mobile' => array('default' => 1),
						);

		/*protected $meta_fields = array(
					'display_here' => array('default' => 1 )
				);
*/

/*
	function __construct()
	{

	}
*/

	function save_fields($data, $post)
	{
		$data = parent::save_fields($data, $post);
		$blockdata = $data[$this->blockname];

		$post_types = $this->get_post_types();

		$save_global = array('display_page', 'display_post', 'display_homepage', 'display_archive');
		$save_options = array('display_page_options', 'display_post_options', 'display_homepage_options', 'display_archive_options');

		// check google block realsave as well. Implement this on the main form? Should be there.
		if ( Install::isPRO() ) {

			if (! isset($post['show_desktop']) && isset($post['form_post']))
				$blockdata['show_desktop'] = 0;

			if (! isset($post['show_mobile']) && isset($post['form_post']))
				$blockdata['show_mobile'] = 0;
		}

		foreach($post_types as $post_type)
		{
			if (isset($post['display_' . $post_type]))
			{
				$field_name = 'display_' . $post_type;

				$display = sanitize_text_field($post[$field_name]);
				$options = isset($post[$field_name . '_options']) ? $post['display_' . $post_type . '_options'] : array();

				$blockdata[$field_name] = $display;
				$blockdata[$field_name . '_options'] = $options;

				if (in_array($field_name, $save_global) === false)	{
					$save_global[] = $field_name;
				}
				if ( in_array($field_name . '_options', $save_options)  === false)
				{
					$save_options[] = $field_name . '_options';
				}

			}

		}

		foreach($save_global as $field_name)
		{

			$data[$field_name] = $blockdata[$field_name];
			unset($blockdata[$field_name]);
		}

		foreach ($save_options as $option)
		{

			if ( is_string($blockdata[$option]) && strlen($blockdata[$option]) > 0)
			{
				$blockdata[$option] = json_decode($blockdata[$option], true);
			}
		}

		$data[$this->blockname] = $blockdata;



		return $data;

	}

	/** Get usable, front-end,  custom post types  **/
	public function get_post_types()
	{
		return \get_post_types(
							array('public' => true,
							  '_builtin' => false,
							 )
				);

	}

	public function parseCSS($css, $args)
	{
		$blockdata = $this->data[$this->blockname];
		//$button_spacing = $this->data["button_spacing"];

		$hook = $this->collection->getHook();

		$options = isset($blockdata[$hook . "_options"]) ? $blockdata[$hook . '_options'] : array();
		$display = isset($this->data[$hook]) ? $this->data[$hook] : 'hidden';

		//$position_x = isset($options['position_x']) ? $options['position_x'] : 'auto';
		//$position_y = isset($options['position_y']) ? $options['position_y'] : 'auto';

		$position = isset($options['position']) ? $options['position'] : false;

		$fixed = isset($options['fixed_position'] ) ? $options['fixed_position'] : false;

		$orientation = $this->collection->orientation;
		$is_static = $this->collection->is_static;
		$is_post = $this->collection->is_post;

	///	$css["mb-item"]["normal"]["float"]  = 'left';

		 // Decide which auto values go with the default settings of the other dimensions.

		 if ($position && $is_static)
		 {
			 	list($position_y, $position_x) = explode("_", $position);

		 }
		 elseif($position && $is_post)
		 {
			 	$position_x = $position;
				$position_y = false;
		 }
		 else {
			 	if ($is_static)
				{
						$position_x = 'left';
						$position_y = 'center';
				}
				else
				{
					 $position_x = 'center';
					 $position_y = false;
				}

		 }

		switch($orientation)
		{
 			case "horizontal":
				$css["mb-item:last-child"]["normal"]["margin-right"] = "0";
			break;

			case "vertical";
				$css["mb-item"]["normal"]["clear"] = "both";
				$css['maxsocial']['normal']['width'] = 'auto';
			break;

		}

		if ($args["preview"] == true)
			return $css; // don't process move than this in preview.

		if ($is_static) // static options
		{
			//	if ($orientation == 'vertical')
			  	$css['maxsocial']['normal']['width'] = 'auto';

			if ($fixed == 1)
				$css['maxsocial']['normal']['position'] = 'fixed';
			else
				$css['maxsocial']['normal']['position'] = 'absolute';


	 		switch($position_x)
	 		{
	 			case "left":
	 			case "right":
	 				// left or right
					$css["maxsocial"]["normal"][$position_x] = '0';
	 			break;

	 			case "center":
					$css['maxsocial']['normal']['left']  = '50%';
					$css['maxsocial']['normal']['transform'] = 'translateX(-50%)';
				break;
	 		}

			switch($position_y)
			{
				case "center":
					$css["maxsocial"]["normal"]["top"] = "50%";
					if ( isset($css['maxsocial']['normal']['transform'])) // set by x
					{
						$css['maxsocial']['normal']['transform'] = 'translate(-50%,-50%)';
					}
					else
						$css["maxsocial"]["normal"]["transform"] = "translateY(-50%)";
				break;
				case 'top':
				  $css['maxsocial']['normal']['top']  = 0;
				break;
				case "bottom":
					$css["maxsocial"]["normal"]["bottom"] = "0";
				break;
			}


	 	} // is_static
	/*	elseif ($is_post)
		{

		} */

		return $css;
	}

	protected function expert_admin()
	{
		$admin = mbSocial()->admin();

		$data = $this->data;
		$blockdata = $this->data[$this->blockname];

/*
		$staticIcons = array(
						'auto' => 'icon_auto.png',
						'left' => 'icon_posleft.png',
						'right' => 'icon_posright.png',
						'center' => 'icon_poscenter.png',
						'top' => 'icon_postop.png',
						'bottom' => 'icon_posbottom.png',
						'horizontal' => 'icon_horizontal.png',
						'vertical' => 'icon_vertical.png',
		);
 */
		$icon_url = MBSocial()->get_plugin_url() . 'images/icons/';
		/*$option_icons = array(
			'hidden' => 'icon_manual.png',
			'before' => 'icon_before.png',
			'after' => 'icon_after.png',
			'after-before' => 'icon_both.png',
			'static' => 'icon_absolute.png'
		); */

		$options = array(
					 'hidden' => __('Manual / Do not show', 'mbsocial'),
					 'before' => __('Before post content','mbsocial'),
					 'after' => __('After post content', 'mbsocial'),
					 'after-before' => __('Before and after the post content', 'mbsocial'),
					 'static' => __('Absolute Position', 'mbsocial'),
					);

		$conditional_options = $options;
		unset($conditional_options['hidden']); // all minus hidden
		$conditional_options = array_keys($conditional_options);


		$option_button = new maxField('button');
		$option_button->modal = 'modal_options';
		$option_button->button_label = __("Options",'mbsocial');
		$option_button->name = 'options';
		$option_button->inputclass = 'maxmodal';

		$gen = new maxField('generic');
		$title_off = __('Off / Manual - Hide on this view. You can add it manually via the shortcode', 'mbsocial');
		$title_content = __('Display around the post content', 'mbsocial');
		$title_float = __('Display static / floating at the screen. Configure position via options', 'mbsocial');

		$gen->content = '<span title="' . $title_off . '">' . __('Off', 'mbsocial') . '</span>
										<span title="' . $title_content . '">' . __('Content','mbsocial') . '</span>
										<span title="' . $title_float . '">' . __('Float','mbsocial') . '</span>';

	  $gen->name = 'display_heading';
		$admin->addField($gen,'start','end');

		$genrow = new maxField('generic');
		$genrow->content = '<span>Top</span><span>Bottom</span><span>Both</span>';
		$genrow->name = 'display_rowhead';

		$admin->addField($genrow, 'start','end');

		// ** HOMEPAGE
		$fspacer = new maxField('spacer');
		$fspacer->label = __('Homepage', 'mbsocial');
		$fspacer->name = 'spacer';
		$fspacer->main_class = 'option display_group';

			$admin->addField($fspacer, 'start');

			$i =0;
				foreach($options as $option => $label)
				{
						$display = new maxField('radio');
						$display->id = 'radio_display_homepage_' . $option;
						$display->name = 'display_homepage';
						$display->title = $label;
						$display->value = $option;

						//$display->image = $icon_url . $option_icons[$option];
						$display->custom_icon = 'display_icon ' . $option;

						if (isset($data[$display->name]))
							$display->checked = checked ( $data[$display->name], $option, false);
						$display->inputclass = 'check_button icon';

						$start = ($i == 0) ? 'group_start' : '';
						$end =  ($i == count($options)-1 ) ? 'group_end' : '';
						$admin->addField($display, $start, $end);
						$i++;
				}

		$hidden = new maxField('hidden');
		$hidden->id = 'display_homepage_options';
		$hidden->name = $hidden->id;
		if (isset($blockdata['display_homepage_options']))
			$hidden->value = htmlentities(json_encode($blockdata['display_homepage_options']) );

		$admin->addField($hidden, '','');

		$homepage_button = clone $option_button;
		$homepage_button->id = 'option_homepage';
		$homepage_button->conditional = htmlentities(json_encode(array('target' => $display->name, 'values' => $conditional_options)));
		$admin->addField( $homepage_button, '', 'end');

					$fspacer = new maxField('spacer');
					$fspacer->name = 'spacer';
					$fspacer->main_class = 'option display_group';
					$fspacer->label = __('Pages', 'mbsocial');

					$admin->addField($fspacer, 'start', '');

					$i = 0;
					foreach($options as $option => $label)
					{
							$display = new maxField('radio');
							$display->id = 'radio_display_page_' . $option;
							$display->name = 'display_page';
							$display->title = $label;
							$display->value = $option;
						//	$display->label = ($i == 0) ? __('Pages') : '';


//							$display->image = $icon_url . $option_icons[$option];
							$display->custom_icon = 'display_icon ' . $option;

							if (isset($data[$display->name]))
								$display->checked = checked ( $data[$display->name], $option, false);

							$display->inputclass = 'check_button icon';

							$start = ($i == 0) ? 'group_start' : '';
							$end =  ($i == count($options)-1) ? 'group_end' : '';
							$admin->addField($display, $start, $end);
							$i++;
					}

	/*
					$display = new maxField('option_select');
					$display->publish = false;
					$display->label = __('Pages', 'mbsocial');
					$display->id = 'display_page';
					$display->name = $display->id;
					$display->selected = isset($data[$display->id]) ? $data[$display->id] : '';
					$display->options = $options;
					$display->main_class = 'option display_group';

					$admin->addField($display, 'start','');
					*/

					$pages_button = clone $option_button;
					$pages_button->id = 'option_page';
					$pages_button->conditional = htmlentities(json_encode(array('target' => $display->name, 'values' => $conditional_options)));

					$hidden = new maxField('hidden');
					$hidden->id = 'display_page_options';
					$hidden->name = $hidden->id;
					if ( isset($blockdata['display_page_options']))
						$hidden->value = htmlentities(json_encode($blockdata['display_page_options']) );
					$admin->addField( $hidden, '','');

					$admin->addField($pages_button, '','end');


		// ** POSTS
					$fspacer = new maxField('spacer');
					$fspacer->label = __('Posts', 'mbsocial');
					$fspacer->name = 'spacer';
					$fspacer->main_class = 'option display_group';

					$admin->addField($fspacer, 'start');

					$i = 0;
					foreach($options as $option => $label)
					{
							$display = new maxField('radio');
							$display->id = 'radio_display_post_' . $option;
							$display->name = 'display_post';
							$display->title = $label;
							$display->value = $option;

							//$display->image = $icon_url . $option_icons[$option];
							$display->custom_icon = 'display_icon ' . $option;

							if (isset($data[$display->name]))
								$display->checked = checked ( $data[$display->name], $option, false);
							$display->inputclass = 'check_button icon';

							$start = ($i == 0) ? 'group_start' : '';
							$end =  ($i == count($options)-1) ? 'group_end' : '';
							$admin->addField($display, $start, $end);
							$i++;
					}
		/*
					$display = new maxField('option_select');
					$display->publish = false;
					$display->label = __('Posts', 'mbsocial');
					$display->id = 'display_post';
					$display->name = $display->id;
					$display->selected = isset($data[$display->id]) ? $data[$display->id] : '';
					$display->options = $options;
					$display->main_class = 'option display_group';

					$admin->addField($display, 'start', '');
	*/
					$posts_button = clone $option_button;
					$posts_button->id = 'option_post';
					$posts_button->conditional = htmlentities(json_encode(array('target' => $display->name, 'values' => $conditional_options)));

					$hidden = new maxField('hidden');
					$hidden->id = 'display_post_options';
					$hidden->name = $hidden->id;
					if (isset($blockdata['display_post_options']))
						$hidden->value = htmlentities(json_encode($blockdata['display_post_options']) );
					$admin->addField($hidden, '','');

					$admin->addField( $posts_button, '','end');


				// ** ARCHIVES **
				$fspacer = new maxField('spacer');
				$fspacer->name = 'spacer';
				$fspacer->main_class = 'option display_group';
				$fspacer->label = __('Archive and Categories', 'mbsocial');
						$admin->addField($fspacer, 'start');

						$i = 0;
						foreach($options as $option => $label)
						{
								$display = new maxField('radio');
								$display->id = 'radio_display_archive_' . $option;
								$display->name = 'display_archive';
								$display->title = $label;
								$display->value = $option;

						//		$display->image = $icon_url . $option_icons[$option];
							$display->custom_icon = 'display_icon ' . $option;

								if (isset($data[$display->name]))
									$display->checked = checked ( $data[$display->name], $option, false);
								$display->inputclass = 'check_button icon';

								$start = ($i == 0) ? 'group_start' : '';
								$end =  ($i == count($options)-1 ) ? 'group_end' : '';
								$admin->addField($display, $start, $end);
								$i++;
						}
					/*$display = new maxField('option_select');
					$display->publish = false;
					$display->label = __('Archive and Categories', 'mbsocial');
					$display->id = 'display_archive';
					$display->name = $display->id;
					$display->selected = isset($data[$display->id]) ? $data[$display->id] : '';
					$display->options = $options;
					$display->main_class = 'option display_group';

					$admin->addField( $display, 'start', '');
	*/
					$archive_button = clone $option_button;
					$archive_button->id = 'option_archive';
					$archive_button->conditional = htmlentities(json_encode(array('target' => $display->name, 'values' => $conditional_options)));

					$hidden = new maxField('hidden');
					$hidden->id = 'display_archive_options';
					$hidden->name = $hidden->id;
					if (isset($blockdata['display_archive_options']))
						$hidden->value = htmlentities(json_encode($blockdata['display_archive_options']) );

					$admin->addField($hidden, '','');

					$admin->addField($archive_button, '', 'end');

					$post_types = $this->get_post_types();


					if (count($post_types) > 0)
					{
						$spacer = new maxField('spacer');
						$spacer->label = __('Custom Post Types', 'mbsocial');
						$spacer->id = 'spacer_custom';
						$spacer->name = $spacer->id;

						$admin->addField($spacer, 'start', 'end');

						if (! Install::isPRO() ) {
								$spacer_pro = new maxField('spacer');
								$spacer_pro->id = 'display_no_pro';
								$spacer_pro->label = $admin->getProMessage();
								$spacer_pro->name = $spacer_pro->id;

								$admin->addField($spacer_pro,'start', 'end');
					 }
					}

					// Custom Post Types
					foreach ($post_types as $post_type)
					{

						$fspacer = new maxField('spacer');
						$fspacer->label = ucfirst($post_type);
						$fspacer->name = 'spacer';
						$fspacer->main_class = 'option display_group';
						$admin->addField($fspacer, 'start');

						 $option_value = isset($data['display_' . $post_type]) ? $data['display_' . $post_type] : 'hidden';

						 $i = 0;
							  foreach($options as $option => $label)
								{

										$display = new maxField('radio');
										$display->id = 'radio_display_' . $post_type . '_' . $option;
										$display->name = 'display_' . $post_type;
										$display->title = $label;
										$display->value = $option;
								//		$display->image = $icon_url . $option_icons[$option];
										$display->custom_icon = 'display_icon ' . $option;

										$display->checked = checked ( $option_value, $option, false);
										$display->inputclass = 'check_button icon';

										if (! Install::isPRO() ) {
												$display->disabled = true;
												$display->inputclass = 'check_button icon disabled';


										}

										$start = ($i == 0) ? 'group_start' : '';
										$end =  ($i == count($options)-1 ) ? 'group_end' : '';
										$admin->addField($display, $start, $end);
										$i++;
								}

						/*
						$display = new maxField('option_select');
						$display->id = 'display_' . $post_type;
						$display->name = $display->id;
						$display->label = ucfirst($post_type);
						$display->selected = isset($data[$display->id]) ? $data[$display->id] : '';
						$display->options = $options;
						$display->main_class = 'option display_group ' . $display->id;
	*/

		//				$admin->addField($display, 'start', '');

						$display_button = clone $option_button;
						$display_button->id = 'option_' . $post_type;
						$display_button->name = $display_button->id;
						$display_button->conditional = htmlentities(json_encode(array('target' => $display->name, 'values' => $conditional_options)));

						$hidden = new maxField('hidden');
						$hidden->id = 'display_' . $post_type . '_options';
						$hidden->name = $hidden->id;
						if ( isset($blockdata['display_' . $post_type . '_options']))
							$hidden->value = htmlentities(json_encode($blockdata['display_' . $post_type . '_options']));

						$admin->addField( $hidden, '','');
						$admin->addField( $display_button, '', 'end');


					}

						$spacer = new maxField('spacer');
						$spacer->id = 'show_spacer';
						$spacer->name = $spacer->id;
						$spacer->label = '&nbsp';

						$admin->addField($spacer, 'start', 'end');

						if ( Install::isPRO() ) {
							$show_desktop = new maxField('switch');
							$show_desktop->id = 'show_desktop';
							$show_desktop->name = $show_desktop->id;
							$show_desktop->value = 1;
							$show_desktop->label = __('Show on Desktop', 'mbsocial');
							$show_desktop->checked = checked( $this->getValue('show_desktop'), 1, false);

							$admin->addField($show_desktop, 'start', 'end');

							$show_mobile = new maxField('switch');
							$show_mobile->id = 'show_mobile';
							$show_mobile->name = $show_mobile->id;
							$show_mobile->value = 1;
							$show_mobile->label = __('Show on Mobile', 'mbsocial');
							$show_mobile->checked = checked( $this->getValue('show_mobile'), 1, false);
							//$show_mobile->publish = ;

							$admin->addField($show_mobile, 'start', 'end');
					}
					$admin->display_fields();

	}

 /*protected function getDisplayRules()
 {
	 $data = $this->data;
	 $blockdata = $this->data[$this->blockname];

	 return array();
 } */

	protected function addRule($args)
	{
		$rule = array(
				'hook' => '',
				'display' => 'hidden',
				'orientation' => '',
				'position' => '',
				'fixed' => '',
				'share_setting' => 'current-post',
				'custom_url' => '',
			);

			$rule = wp_parse_args($args, $rule);
			return $rule;
	}


	/** Just simple for whom? **/
	protected function simple_admin()
	{

		$admin = mbSocial()->admin();

		$data = $this->data;
		$blockdata = $this->data[$this->blockname];

		$rules = $this->getDisplayRules();

		if(count($rules) == 0)
		{
				$rule = $this->addRule(array(
								'hook' => 'all',
								'display' => 'static',
								'orientation' => 'vertical',
								'fixed' => true,

						));
				$rules[] = $rule;
		}


		$label = new maxField('spacer');
		$label->label = __('Label!', 'mbsocial');
		$label->id = 'label';

		$admin->addField($label, 'start');

		foreach ($rules as $rule)
		{
				$display = $rule['display'];
				$hook = $rule['hook'];

				$output = sprintf('On %s this will show the button %s ', $hook, $display);


		}

		$rfield = new maxField('generic');
		$rfield->id = 'rule_1';
		$rfield->content = $output;

		$admin->addField($rfield,'','end');

		$admin->display_fields();
?>
<p><h4>

On the <strong>Homepage</strong> this will show <strong>Before the content</strong>

<i>Remove</i>

	<p>

<p>	<h3> <a>Add a display Rule</a> </h3>
</p>

<p>I want this to show at the <select>
			<option>Homepage</option>
			<option>Pages</option>
			<option>Posts</option>
			<option>Custom Post Type</option>
		</select>
		section. Show it
			<select><option>Before the content</option>
					<option>After the content</option>
					<option>Both before and after the cotnent</option>
					<option class='selected'>Separate from the content</option>
			</select>

	</p>
	<p>The position of the separated content will be <strong>Left </strong>
		<strong>Top</strong>
	</p>

	<p> The shared URL will be the one of the page / something else
			<input type='text'>
			<input type="button" value="Do it" class="button-primary">
		</p>

<?php


	}

	/** Admin() **/
	public function admin()
	{
		$admin = mbSocial()->admin();

		$data = $this->data;
		$blockdata = $this->data[$this->blockname];

		$orientation_options = array('auto' => __('Auto','mbsocial'),
									 'horizontal' => __('Horizontal','mbsocial'),
									 'vertical' => __('Vertical', 'mbsocial'),
									);

		$static_options = array(
		//						'auto' => __('Auto', 'mbsocial'),
								'left' => __('Left', 'mbsocial'),
								'center' => __('Center', 'mbsocial'),
								'right' => __('Right', 'mbsocial'),

					//		 	'bottom' => __('Bottom', 'mbsocial'),
								);

		$postpos_options = $static_options;

		$staticpos_options = array(
			//		'auto' => __('Auto', 'mbsocial'),
					'top' => __('Top', 'mbsocial'),
					'center' => __('Center', 'mbsocial'),
					'bottom' => __('Bottom', 'mbsocial'),
						);

		$collection_id  = $this->collection->getID();

		if ($collection_id > 0)
			$display_id = $collection_id;
		else
			$display_id = '*id*';

	?>
 		<div class='help-side'>
 			<h3><?php _e('Display Options', 'mbsocial') ?></h3>
 			<p>You can control where share buttons will appear by section of the site, like posts, pages, categories and custom post types</p>

 			<p><b>Manual / Off:</b> Use [maxsocial id="<?php echo $display_id ?>"] in your post content where you would like social sharing to appear</p>

 			<p><b>Content :</b> The buttons will display before or after your main post content, or both. </p>

 			<p><b>Float: </b> The buttons will display detached from the movement of content on the screen. This will allow you to position them on a fixed position. </p>

 			<p>Some settings have options which are revealed after clicking that option's button</p>
 		</div>

		<div id='displayBlock' class='options display option-container'>
		<div class='title'><?php _e('Display Options', 'mbsocial'); ?></div>
		<div class='inside'>
			<?php // [Simple] [Expert] ?>
			<?php
				// ** PAGES **
				$i = 0;

				$interface_option = 'simple';

				// if ($interface_option == 'expert')
					$this->expert_admin();

				/*else {
					$this->simple_admin();

				} */
			?>

		</div> <!-- inside -->
	</div> <!-- option-container -->

	<div id='modal_options'  class='maxmodal-data' data-load='window.maxFoundry.maxSocial.displayOptions'>
		<div class='title'><?php _e('Options', 'mbsocial'); ?></div>
		<div class='content '>
			<div class='display_modal wrapper'>

				<div class='post_options'>

				<?php
				/* 07/01/2021 - Seems unused
				$staticIcons = array(
								'auto' => 'icon_auto.png',
								'left' => 'icon_posleft.png',
								'right' => 'icon_posright.png',
								'center' => 'icon_poscenter.png',
								'top' => 'icon_postop.png',
								'bottom' => 'icon_posbottom.png',
								'horizontal' => 'icon_horizontal.png',
								'vertical' => 'icon_vertical.png',
				);
				*/
				$icon_url = MBSocial()->get_plugin_url() . 'images/icons/';

				$orsp = new maxField('spacer');
				$orsp->name = 'or_spacer';
				$orsp->label = __('Orientation of the buttons');

				$admin->addField($orsp, 'start');


				$or = new maxField('radio');
				$or->id = 'orientation_horizontal';
				$or->name = 'orientation';
				$or->title = __('Horizontal', 'mbsocial');
				$or->value = 'horizontal';
			//	$or->image = $icon_url . $staticIcons['horizontal'];
				$or->custom_icon = 'display_icon horizontal';


			//	$or->checked =
				$or->inputclass = 'check_button icon';


				$admin->addField($or, '', '');

				$orv = clone $or;
				$orv->id = 'orientation_vertical';
				$orv->title = __('Vertical', 'mbsocial');
				$or->value = 'vertical';
		//		$or->image = $icon_url . $staticIcons['vertical'];
			$or->custom_icon = 'display_icon vertical';

				$admin->addField($orv, '', 'end');

				$admin->display_fields();



		?>
			</div>
			<div class='content_options'>
		<?php

/* See : https://bitbucket.org/MF_Team/maxbuttons-social-share/issues/38/inline-share-buttons-cant-be-aligned
		$spacer = new maxField('spacer');
		$spacer->name = 'pos_spacer';
		$spacer->label = __('Alignment', 'mbsocial');
	//	$spacer->main_class = 'pos_options';
		$admin->addField($spacer, 'start','');

		$i = 0;

		foreach($postpos_options as $option => $label)
		{

			$posopt = new maxField('radio');
			$posopt->id = 'position_' . $option;
			$posopt->name = 'position';
			$posopt->title = $label;
			$posopt->value = $option;
			//$posopt->image = $icon_url . 'icon_' . $y_option . $x_option . '.png';
			$posopt->custom_icon = 'display_icon center' . $option; // center comes from lookup on icon image

			$posopt->checked = checked ( $option, 'auto', false);
			$posopt->inputclass = 'check_button icon';

			//$start = ($i == 0) ? 'start' : '';
			$end = ($i == 2) ? 'end' : '';
			$admin->addField($posopt, '', $end);
			$i++;
		}

		$admin->display_fields();
*/
				?>

				</div>

				<div class='static_options'>

					<?php
/*
						$spacer = new maxField('spacer');
						$spacer->name = 'spacer';
						$spacer->main_class = 'pos_options';
						$admin->addField($spacer, 'start','');

						$i = 0;
					foreach($static_options as $option => $label)
						{
								$posopt = new maxField('radio');
								$posopt->id = 'position_x_' . $option;
								$posopt->name = 'position_x';
								$posopt->title = $label;
								$posopt->value = $option;
								$posopt->image = $icon_url . $staticIcons[$option];
								//default
								$posopt->checked = checked ( $option, 'auto', false);
								$posopt->inputclass = 'check_button icon';
								$end = ($i == count($static_options)-1) ? 'end' : '';

								$admin->addField($posopt, '',$end);
								$i++;
						}
*/
						$spacer = new maxField('spacer');
						$spacer->name = 'pos_spacer';
						$spacer->label = __('Position', 'mbsocial');
					//	$spacer->main_class = 'pos_options';
						$admin->addField($spacer, 'start','');

						$i = 1;
						$end = null;

						foreach($staticpos_options as $y_option => $y_label)
						{
							foreach($static_options as $x_option => $x_label)
							{
								$posopt = new maxField('radio');
								$posopt->id = 'position_' . $y_option . '_' . $x_option;
								$posopt->name = 'position';
								$posopt->title = $y_label . '-' . $x_label;
								$posopt->value = $y_option . '_' . $x_option;
								//$posopt->image = $icon_url . 'icon_' . $y_option . $x_option . '.png';
								$posopt->custom_icon = 'display_icon ' . $y_option . $x_option;

								$posopt->checked = checked ( $x_option, 'auto', false);
								$posopt->inputclass = 'check_button icon';

								$start = ( $end == 'end') ? 'start' : ''; // from prev run
								$end = ($i % 3 === 0 && $i > 0) ? 'end' : '';

								$admin->addField($posopt, $start ,$end);
								$i++;
							}

						}

				/*	$static= new maxField('option_select');
					$static->id = 'static';
					$static->name = $static->id;
					$static->options = $static_options;
					$static->label = __('Location on screen', 'mbsocial');
					$static->note  = __('This will define where on the screen your buttons will show up', 'mbsocial');

					$admin->addField($static, 'start', 'end');
 				*/
				/*	$static_pos = new maxField('option_select');
					$static_pos->id = 'staticpos';
					$static_pos->name = $static_pos->id;
					$static_pos->options = $staticpos_options;
					$static_pos->label = __('Position on screen', 'mbsocial');
					$static_pos->note = __('This will define on what level the buttons will show up' , 'mbsocial');

					$admin->addField($static_pos, 'start', 'end');
					*/

					$fixed = new maxField('switch');
					$fixed->id = 'fixed_position';
					$fixed->name = $fixed->id;
					$fixed->label = __('Position fixed', 'mbsocial');
					$fixed->value = 1;

					$admin->addField($fixed, 'start', 'end');



					$admin->display_fields();
				?>
				</div>





				<div class='share_options'>
					<?php
					$share_options = array(
						'auto' => __('Auto', 'mbsocial'),
						'current-post' => __('Current Post or Page', 'mbsocial'),
						'home' => __('Homepage', 'mbsocial'),
						'custom-url' => __('Custom URL link', 'mbsocial'),
					);


					$share_setting = new maxField('option_select');
					$share_setting->id = 'share_setting';
					$share_setting->name = $share_setting->id;
					$share_setting->label = __('Share URL setting', 'mbsocial');
					$share_setting->options = $share_options;

					//$custom_conditional = htmlentities(json_encode(array('target' => $share_setting->name, 'values' => 'custom-url')));

					$share_custom = new maxField();
					$share_custom->id = 'share_custom_url';
					$share_custom->note = __("Settings below only apply when Share URL is set to Custom URL", 'mbsocial');
					$share_custom->name = $share_custom->id;
					$share_custom->label = __('Custom URL','mbsocial');
					$share_custom->inputclass = 'medium';
				//	$share_custom->start_conditional = $custom_conditional;

					$custom_title = new maxField('text');
					$custom_title->id = 'share_custom_title';
					$custom_title->name = $custom_title->id;
					$custom_title->label = __('Custom Title', 'mbsocial');
					$custom_title->inputclass = 'medium';
				//	$custom_title->start_conditional = $custom_conditional; //htmlentities(json_encode(array('target' => $share_setting->name, 'values' => 'custom-url')));

					$admin->addField($share_setting, 'start', 'end');

					$admin->addField($share_custom, 'start', 'end');
					$admin->addField($custom_title, 'start', 'end');



					$admin->display_fields();
					?>


				</div>



			</div> <!-- content -->

		</div> <!-- wrapper -->
		<div class='controls'>
			<button class='button-primary modal_close'>Done</button>
		</div>
	</div> <!-- modal -->



	<?php

	}
}
