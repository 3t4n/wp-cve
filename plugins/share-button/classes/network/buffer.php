<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');


class bufferNetwork extends mbNetwork
{
	protected $network = 'buffer';
	protected $icon_type = 'nucleo';
	protected $icon = 'nucleo-logo-buffer';
	protected $priority = 'readmore';
	protected $color = '#242424';

	public function __construct()
	{
		$this->label = __('Share', 'mbsocial');
		$this->share_url = 'https://buffer.com/add?url={url}&text={title}';
		$this->popup_dimensions = array(600,600);

		$this->countable = true;
		$this->count_api = 'https://api.bufferapp.com/1/links/shares.json?url={url}';
		$this->return_var = 'shares';

		parent::__construct();

	}
}
