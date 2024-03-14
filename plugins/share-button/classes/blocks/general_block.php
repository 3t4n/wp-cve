<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;

$collectionBlock["general"] = array('class' => "generalBlock",
									'order' => 5);


class generalBlock extends block
{
	protected $blockname = 'general';
	protected $fields = array(
					   'name' => array('default' => ''),
					   'active' => array('default' => 1),
					   );

	function save_fields($data, $post)
	{
	 		$data = parent::save_fields($data, $post);

			if (! isset($post['active']) && ! is_null($post) && count($post) > 0 )
			{
					$data[$this->blockname]['active'] = 0;
			}

			return $data;
	}

	 public function do_meta_boxes($content, $post)
	 {

	 	$admin = mbSocial()->admin();

	 	$metadata = $this->get_block_meta_data($post->ID);

		$is_hidden = isset($metadata['hide']) ? $metadata['hide']: 0;

		$active = new maxField('switch');
		$active->label = __('Hide', 'mbsocial');
		$active->id = 'mbsocial_hide';
		$active->name = $active->id;
		$active->value = '1';
		$active->note = __('This option can be used to hide the social share only here', 'mb-social');
		$active->checked = checked( $is_hidden , 1, false);

		$admin->addField($active, 'start','end');

	 	$fields = $admin->display_fields(true, true, false);

	 	$content['display'] = array('title' => __('Display Options', 'mbsocial'),
	 								'icon' => 'display',
	 								'content' => $fields,
	 						);

	 	return $content;
	 }

public function save_meta_boxes($metadata, $post_id, $post)
{
	 $is_active = isset($post['mbsocial_hide']) ? intval($post['mbsocial_hide']) : 0;
	 $metadata[$this->blockname]['hide'] = $is_active;

	 return $metadata;


}

	public function admin()
	{
		$admin = MBSocial()->admin();

	?>
		<div class='option-container general' id='generalBlock' >
		<div class='title'><?php _e('General','mbsocial'); ?></div>
		<div class='inside'>
	<?php

		$field_name = new maxField() ;
		$field_name->label = __('Name', 'mbsocial');
	//	$field_name->note = __('Something that you can quickly identify the button with.', 'maxbuttons');
		$field_name->value = $this->getValue('name');
		$field_name->id = 'name';
		$field_name->name = $field_name->id;
		$field_name->placeholder = __("Name","mbsocial");
		$field_name->output('start','end');

		// until multi-collection done not in use
		//$admin->addField($field_name, 'start','end');

		$active = new maxField('switch');
		$active->label = __('Active', 'mbsocial');
		$active->id = 'active';
		$active->name = $active->id;
		$active->value = '1';
		$active->checked = checked( $this->getValue('active'), 1, false);

		$admin->addField($active, 'start','end', false);

		$notactive = new maxField('spacer');
		$notactive->id = 'general_notactive';
		$notactive->label = __('Warning:', 'mbsocial');
		$notactive->name = $notactive->id;
		$notactive->content = '<b class="red">'. __('Social Share is not active. It will not show up anywhere on the frontend!', 'mbsocial') . '</b>';
		$notactive->conditional = htmlentities(json_encode(array('target' => 'active', 'values' => 'unchecked')));

		$admin->addField($notactive, 'start', 'end', false);

		if ( Install::isPRO() ) {
			$deskhide = new maxField('spacer');
			$deskhide->id = 'deskhide';
			$deskhide->name = $deskhide->id;
			$deskhide->label = __('Desktop', 'mbsocial');
			$deskhide->content = 'Shown';
			$deskhide->conditional = htmlentities(json_encode(array('target' => 'show_desktop', 'values' => 'checked')));

			$deskshow = new maxField('spacer');
			$deskshow->id = 'deskshow';
			$deskshow->name = $deskshow->id;
			$deskshow->label = __('Desktop', 'mbsocial');
			$deskshow->content = '<b class="red">Hidden</b>';
			$deskshow->conditional = htmlentities(json_encode(array('target' => 'show_desktop', 'values' => 'unchecked')));

			$admin->addField($deskhide, 'start', '', false );
			$admin->addField($deskshow, '', 'end', false);

			$mobhide = new maxField('spacer');
			$mobhide->id = 'mobhide';
			$mobhide->name = $mobhide->id;
			$mobhide->label = __('Mobile', 'mbsocial');
			$mobhide->content = 'Shown';
			$mobhide->conditional = htmlentities(json_encode(array('target' => 'show_mobile', 'values' => 'checked')));

			$mobshow = new maxField('spacer');
			$mobshow->id = 'mobshow';
			$mobshow->name = $mobshow->id;
			$mobshow->label = __('Mobile', 'mbsocial');
			$mobshow->content = '<b class="red">Hidden</b>';
			$mobshow->conditional = htmlentities(json_encode(array('target' => 'show_mobile', 'values' => 'unchecked')));

			$admin->addField($mobhide, 'start', '', false);
			$admin->addField($mobshow, '', 'end', false);
		} // IsPro

		$admin->display_fields();
	?>
			</div>
		</div>
	<?php
	}


}
