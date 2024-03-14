<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

class diggNetwork extends mbNetwork
{
	protected $network = 'digg';
	protected $icon = 'fa-digg';
	protected $priority = 'readmore';
	protected $color = '#000';

	public function __construct()
	{
		parent::__construct();
		$this->label = __('Share', 'mbsocial');
		$this->share_url = 'http://digg.com/submit?url={url}&title={title}';
		$this->popup_dimensions = array(600,600);

	}
}

class emailNetwork extends mbNetwork
{
	protected $network = 'email';
	protected $icon = 'fa-envelope';
	protected $icon_type = 'fas';
	protected $color = '#5e5e5e';
	protected $forcesamewindow = true;
	protected $is_popup = false;

	public function __construct()
	{
		$this->label = __('Email', 'mbsocial');
		if (Install::isPro() )
			$this->share_url = 'mailto:?subject={email_subject}&body={email_content}';
		else {
			$this->share_url = 'mailto:?subject={title}&body={url}';
		}

		parent::__construct();

	}
}

class facebookNetwork extends mbNetwork
{
	protected $network = 'facebook';

	protected $icon = 'fa-facebook-f';
	protected $color = '#3b5998';

	public function __construct()
	{
		$this->label = __('Share', 'mbsocial');
		$this->share_url = 'https://www.facebook.com/sharer.php?u={url}';
		$this->profile_url = 'https://www.facebook.com/{profile}';
		$this->countable = true;
		$this->count_api = 'https://graph.facebook.com/{url}';
		$this->return_var = 'share|share_count';
		$this->popup_dimensions = array(550,320);

		parent::__construct();
	}

}

class linkedinNetwork extends mbNetwork
{
	protected $network = 'linkedin';
	protected $icon = 'fa-linkedin-in';
	protected $color = '#007bb6';
	protected $priority = 'unselected';


	public function __construct()
	{
		$this->label = __('Share','mbsocial');
		$this->share_url = 'https://www.linkedin.com/shareArticle?mini=true&url={url}';
		$this->alternate_url = 'https://www.linkedin.com/company/{profile}';
		$this->alternate_label = __('Company Profile');
		$this->profile_url = 'https://www.linkedin.com/in/{profile}';
		// Linkedin counts where killed
		//$this->count_api = 'https://www.linkedin.com/countserv/count/share?url={url}&format=json';
		//$this->countable = true;
		//$this->return_var = 'count';

		$this->popup_dimensions = array(700,500);

		parent::__construct();

	}

}


class printNetwork extends mbNetwork
{
	protected $network = 'print';
	protected $icon = 'fa-print';
	protected $icon_type = 'fas';
	protected $color = '#5e5e5e';
	protected $forcesamewindow = true;
	protected $is_popup = false;

	public function __construct()
	{
		$this->label = __('Print', 'mbsocial');
		$this->share_url = 'javascript:window.print()';

		parent::__construct();

	}
}


class stumbleuponNetwork extends mbNetwork
{
	protected $network = 'stumbleupon';
	protected $priority = 'readmore';
	protected $icon = 'fa-stumbleupon';
	protected $color = '#eb4924';

	public function __construct()
	{
		$this->label = __('Share','mbsocial');
		$this->share_url = 'https://www.stumbleupon.com/submit?url={url}&title={title}';
		$this->count_api = 'https://www.stumbleupon.com/services/1.01/badge.getinfo?url={url}';
		$this->countable = true;
		$this->return_var = 'result|views';

		parent::__construct();
	}
}

class redditNetwork extends mbNetwork
{
	protected $network = 'reddit';
	protected $priority = 'unselected';
	protected $icon = 'fa-reddit';
	protected $color = '#ff4500';


	public function __construct()
	{
		$this->label = __('Share', 'mbsocial');
		$this->share_url = 'https://reddit.com/submit?url={url}&title={title}';
		$this->count_api = 'https://buttons.reddit.com/button_info.json?url={url}';
		$this->countable = true;
		$this->profile_url = 'https://www.reddit.com/user/{profile}';
		$this->return_var = 'data|children|0|data|score';
		$this->popup_dimensions = array(800,500);

		parent::__construct();

	}

}


class whatappNetwork extends mbNetwork
{
	protected $network = 'whatsapp';
	protected $priority = 'readmore';
	protected $icon = 'fa-whatsapp';
	protected $color = '#4dc247';

	protected $displayDesktop = false;

	public function __construct()
	{
		$this->label = __('Send', 'mbsocial');
		$this->share_url = 'whatsapp://send?text={url} {title}';

		parent::__construct();

	}

}

class instagramNetwork extends mbNetwork
{
	protected $network = 'instagram';
	protected $priority = 'unselected';
	protected $icon = 'fa-instagram';
	protected $color = '#517fa4';

	public function __construct()
	{

			$this->label = __('Follow', 'mbsocial');
			$this->profile_url = 'https://instagram.com/{profile}';

			parent::__construct();

	}

}


class youtubeNetwork extends mbNetwork
{
		protected $network = 'youtube';
		protected $priority = 'readmore';
		protected $icon = 'fa-youtube';
		protected $color = '#bb0000';
		protected $is_popup = false;

		public function __construct()
		{
			$this->label = __('View', 'mbsocial');
			$this->profile_url = 'https://youtube.com/{profile}';

			parent::__construct();
		}
}

class snapchatNetwork extends mbNetwork
{
	 protected $network = 'snapchat';
	 protected $priority = 'readmore';
	 protected $icon = 'fa-snapchat';
	// protected $color = '#fffc00';
	 protected $color = '#000';
	 protected $displayDesktop = false;

	 public function __construct()
	 {
			$this->label = __('Follow', 'mbsocial');
			$this->profile_url = 'https://snapchat.com/{profile}';

			parent::__construct();

	 }

}

class rssNetwork extends mbNetwork
{
			protected $network = 'rss';
			protected $priority = 'readmore';
			protected $icon = 'fa-rss';
			protected $icon_type = 'fas';
			protected $color = '#F99000';

			protected $share_profile = false;
			protected $is_popup = false;
	 //	protected $profile_placeholder = '';

			public function __construct()
			{
				$this->nice_name = __('RSS', 'mbsocial');
				$this->profile_placeholder = __('Feed URL', 'mbsocial');
				$this->label = __('Follow', 'mbsocial');
				$this->profile_url = '{profile}';
				parent::__construct();

			}
/*
			public function get_url()
			{
					$url = parent::get_url();

					if (strlen(trim($url)) == 0)
					{
						return bloginfo('rss_url');
					}
			} */

}

class vimeoNetwork extends mbNetwork
{
	protected $network = 'vimeo';
	protected $priority = 'readmore';
	protected $icon = 'fa-vimeo-v';
	protected $color = '#1ab7ea';

	public function __construct()
	{
		 $this->label = __('View', 'mbsocial');
		 $this->profile_url = 'https://vimeo.com/{profile}';

		 parent::__construct();

	}
}

class tiktokNetwork extends mbNetwork
{

	protected $network = 'tiktok';
	protected $priority = 'readmore';
	protected $icon = 'fa-tiktok';
	protected $icon_type = 'fab';
	protected $color = '#010101';

	public function __construct()
	{
		 $this->nice_name = __('TikTok', 'mbsocial');
		 $this->label = __('Follow', 'mbsocial');
		 $this->profile_url = 'https://tiktok.com/{profile}';

		 parent::__construct();

	}
}


class phoneNetwork extends mbNetwork
{
	protected $network = 'phone';
	protected $priority = 'readmore';
	protected $icon = 'fa-phone';
	protected $icon_type = 'fas';
	protected $color = '#00932c';
	protected $is_popup = false;

	public function __construct()
	{
		 $this->nice_name = __('Phone Number', 'mbsocial');
		 $this->label = __('Call', 'mbsocial');
		 $this->profile_url = 'tel:{profile}';
		 $this->profile_placeholder = __('Phone Number', 'mbsocial');
		 $this->displayDesktop = false;

		 parent::__construct();
	}


}
