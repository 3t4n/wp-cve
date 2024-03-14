<?php
namespace MBSocial;

use MaxButtons\maxField as maxField;
use MaxButtons\maxBlocks as maxBlocks;

class twitterNetwork extends mbNetwork
{
	protected $network = 'twitter';
	protected $icon = 'fa-twitter';
	protected $color = '#00aced';


	public function __construct()
	{
		// https://dev.twitter.com/web/tweet-button/web-intent
		$this->label = __('Tweet','mbsocial');
	 $this->share_url = 'https://twitter.com/intent/tweet/?url={url}&text={title}&via={twitter_handle}&hashtags={twitter_hashtags}';
		$this->profile_url = 'https://twitter.com/{profile}';

		$this->popup_dimensions = array(550, 320);

		parent::__construct();

	}


}
