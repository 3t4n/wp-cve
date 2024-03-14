<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_UpgradeOk_View_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$return = $this->app->make('/html/list')
			->set_gutter(2)
			;

		$header = $this->app->make('/html/element')->tag('h1')
			->add( __('Thank You', 'locatoraid') )
			;

		$return
			->add( $header )
			;

		$link = $this->app->make('/http/uri')
			->url()
			;

		$return->add(
			'Thank you for upgrading our software!'
			);
		$return->add(
			$this->app->make('/html/element')->tag('a')
				->add_attr('href', $link)
				->add('Please now proceed to the start page.')
				->add_attr('class', 'hc-theme-btn-submit')
				->add_attr('class', 'hc-theme-btn-primary')
			);
		// $return->add(
			// '<META http-equiv="refresh" content="5;URL=' . echo $link . '">'
			// );

		$input = $this->app->make('/input/lib');
		$localhost = ($input->server('SERVER_NAME') != 'localhost') ? FALSE : TRUE;
		$track_setup = isset($this->app->app_config['nts_track_setup']) ? $this->app->app_config['nts_track_setup'] : '';
		if( $track_setup ){
			list( $track_site_id, $track_goal_id ) = explode( ':', $track_setup );
			if( $localhost ){
				// $return->add(
					// 'TRACKING ' . $track_site_id . ':' . $track_goal_id
					// );
			}
			else {
				$js = <<<EOT

<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);

  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://www.greatdealsplaza.com/piwik/";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "$track_site_id"]);
	_paq.push(['trackGoal', $track_goal_id]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>

EOT;
				$return->add( $js );
			}
		}

		return $return;
	}
}