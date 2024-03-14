<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');


$collectionBlock["email"] = array('class' => "emailBlock",
									'order' => 110);

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxUtils as maxUtils;

class emailBlock extends block
{

	protected $blockname = "email";

	protected $fields = array(
			'email_subject' => array('default' => ''),
			'email_content' => array('default' => ''),
	);

	public function __construct()
	{
		$w = MBSocial()->whistle();

		$w->listen('display/vars/email_subject', array($this, 'email_subject'), 'ask');
		$w->listen('display/vars/email_content', array($this, 'email_content'), 'ask');
	}

	public function email_subject()
	{
		$blockdata = $this->data[$this->blockname];
		$w = MBSocial()->whistle();
		$admin = MBSocial()->admin();

		if (isset($blockdata['email_subject']))
		{
			// applyVars to check nested vars inside the declaration.
			return $admin->applyVars($blockdata['email_subject']);
		}
		else {
			return  $w->ask('display/vars/title'); ;
		}
	}

	public function email_content()
	{
		$blockdata = $this->data[$this->blockname];
		$w = MBSocial()->whistle();
		$admin = MBSocial()->admin();

		if (isset($blockdata['email_content']))
		{
			return $admin->applyVars($blockdata['email_content']);
		}
		else {
			return $w->ask('display/vars/url'); // if no setting, ask the default.
		}
	}

	public function admin()
	{
		if (! Install::isPro() )
			return;

		$admin = mbSocial()->admin();
		$blockdata = $this->data[$this->blockname];


	?>
		<div class='options option-container layout' id='twitterBlock'
		data-has='{"target":"network_item_active[]","values":["twitter"]}' >
			<div class='title'><?php _e('Email Content Options', 'mbsocial' ); ?>  </div>
			<div class='inside'>

			<?php

			$subject = new maxField('text');
			$subject->id = 'email_subject';
			$subject->name = $subject->id;
			$subject->label = __('Subject', 'mbsocial');
			$subject->help = __('Type subject for Email Sharing. Use {url} to include sharing URL, use {title} to include page/post title', 'mbsocial');
			$subject->placeholder = __('Your friend wants to let your know about {title}');
			$subject->value = $this->getValue('email_subject');

			$admin->addField($subject, 'start','end', false);

			$content = new maxField('textarea');
			$content->id = 'email_content';
			$content->name = $content->id;
			$content->label = __('Content', 'mbsocial');
			$content->help = __('Type subject for Email Content. Use {url} to include sharing URL, use {title} to include page/post title', 'mbsocial');
			$content->placeholder = __('I found this really nice page. Read everything about {title} . Check it out : {url}');
			$content->value = $this->getValue('email_content');


			$admin->addField($content, 'start', 'end', false);

						$admin->display_fields();
			?>

				</div>
		   </div>

	<?php
	}

} // class
