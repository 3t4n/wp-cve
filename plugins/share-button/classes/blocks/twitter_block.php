<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

$collectionBlock["twitter"] = array('class' => "twitterBlock",
								  'order' => 100);

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxUtils as maxUtils;


class twitterBlock extends block
{

	protected $blockname = "twitter";
	protected $fields = array( 'twitter_handle' => array('default' => ''),
							   'twitter_hash' => array('default' => ''),
						);

	protected $meta_fields = array(
							'twitter_hash' => array('default' => ''),
							'twitter_handle' => array('default' => ''),

							);

	static $hooked = null;

	public function __construct()
	{
		$w = MBSocial()->whistle();

		if (self::$hooked == null) // listen just once.
		{
			$w->listen('display/vars/twitter_handle', array($this, 'twitter_handle'), 'ask');
			$w->listen('display/vars/twitter_hashtags', array($this, 'twitter_hashtags'), 'ask');
			self::$hooked = true;
		}
	}

	public function twitter_handle()
	{

		$post_id = $this->collection->post_id;
		$metadata = $this->get_block_meta_data($post_id);
		$blockdata = $this->data[$this->blockname];

		$handle = ( isset($metadata['twitter_handle']) ) ? $metadata['twitter_handle'] : false;
		if (! $handle)
			$handle = ( isset($blockdata['twitter_handle']) ) ? $blockdata['twitter_handle'] : false;

		$handle = str_replace('@', '', $handle);

		return $handle;

	}

	public function twitter_hashtags()
	{
		$post_id = $this->collection->post_id;
		$metadata = $this->get_block_meta_data($post_id);
		$blockdata = $this->data[$this->blockname];

		$hash = ( isset($metadata['twitter_hash']) ) ? $metadata['twitter_hash'] : false;
		if (! $hash)
			$hash = ( isset($blockdata['twitter_hash'] )) ? $blockdata['twitter_hash'] : false;

		if ($hash && strlen($hash) > 0)
		{

				$hashes = array_filter(explode(',', trim($hash)) );
				$hashes = array_map('trim', $hashes);
				$hash = implode(',', $hashes);
		}
		return $hash;

	}

	public function do_meta_boxes($content, $post)
	{
		$admin = mbSocial()->admin();

		$metadata = $this->get_block_meta_data($post->ID);

		if (! Install::isPro() )
		{
			$gopro = new maxField('generic');
			$gopro->id = 'pro';
			$gopro->content = "<div class='forpro overlay'><div>" . $admin->getProMessage() . "</div></div>";
			$admin->addField($gopro, false,false);
		}

		$hash = new maxField();
		$hash->id = 'twitter_hash';
		$hash->name = 'twitter_hash';
		$hash->label = __('Twitter Hash Tag(s)', 'mbsocial');
		$hash->placeholder = '#';
		$hash->note = __('Seperate multiple tags with a comma', 'mbsocial');
		$hash->value = isset($metadata['twitter_hash']) ? $metadata['twitter_hash'] : '';

		$admin->addField($hash, 'start', 'end');

		$handle = new maxField();
		$handle->id = 'twitter_handle';
		$handle->name = $handle->id;
		$handle->label = __('Twitter Handle', 'mbsocial');
		$handle->note = __('Leave empty for global default','mbsocial');
		$handle->value = isset($metadata['twitter_handle']) ? $metadata['twitter_handle'] : '';

		$admin->addField($handle, 'start', 'end');

		$fields = $admin->display_fields(true, true);


		$content['twitter'] = array('title' => __('Twitter', 'mbsocial'),
									'icon' => 'twitter',
									'content' => $fields,
							);

		return $content;

	}



	public function admin()
	{
		$admin = mbSocial()->admin();
		$blockdata = $this->data[$this->blockname];


	?>
		<div class='options option-container layout' id='twitterBlock'
		data-has='{"target":"network_item_active[]","values":["twitter"]}' >
			<div class='title'><?php _e('Default Twitter Options', 'mbsocial' ); ?>  </div>
			<div class='inside'>

		<?php
			$handle = new maxField();
			$handle->id = 'twitter_handle';
			$handle->name = $handle->id;
			$handle->label = __('Twitter Handle', 'mbsocial');
			$handle->value = $this->getValue('twitter_handle') ;
			$handle->placeholder = '@example';
			$handle->inputclass = 'medium';
			$handle->help = __('Adding a handle will include the handle in the proposed message (via)', 'mb-social');
			$admin->addField($handle, 'start', 'end', false);



			$hash = new maxField();
			$hash->id = 'twitter_hash';
			$hash->name = 'twitter_hash';
			$hash->label = __('Twitter Default Hash Tag(s)', 'mbsocial');
			$hash->value = $this->getValue('twitter_hash');
			$hash->inputclass = 'medium';
			$hash->help = __('Separate multiple hashtags with a comma (first,second)', 'mb-social');
			$hash->placeholder = '#';

			$admin->addField($hash, 'start', 'end', false);

			$admin->display_fields();
		?>

				</div> <!-- inside -->
		   </div>
		<?php


	}

}
