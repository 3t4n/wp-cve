<?php

namespace SGI\Fitvids\Frontend;

use const SGI\Fitvids\BASENAME;
use const SGI\FITVIDS\VERSION;

class Engine
{

    /**
     * Plugin options
     * 
     * @since 3.0
     */
    private $opts;

    public function __construct()
    {
        
        if (is_admin()) return;

        $this->opts = RSFitvids()->getOpts();

        add_action('wp_enqueue_scripts',[&$this,'loadScripts'], 75);
		add_action('embed_oembed_html',[&$this, 'embedWrap'], 20, 4);

    }

    /**
     * @param string|false $cache   The cached HTML result, stored in post meta.
     * @param string       $url     The attempted embed URL.
     * @param array        $attr    An array of shortcode attributes.
     * @param int          $post_ID Post ID.
     *
     
     */
    public function embedWrap($cache, $url, $attr = '', $post_ID = '')
	{

        if (!$this->opts['core']['autoconfig']) :
            return $cache;
        endif;

        return '<div class="entry-content-asset">' . $cache . '</div>';

	}

    public function loadScripts()
    {

        wp_register_script( 'sgi-fitvids', plugins_url( "assets/js/jQuery.fitVids.js", BASENAME ), array('jquery'), VERSION, true);
        wp_enqueue_script('sgi-fitvids');


        add_action ('wp_footer', [&$this,'footerActivation'], 90);
        
    }

    public function footerActivation()
	{
		
		$selector = ($this->opts['core']['autoconfig']) ? '.entry-content-asset' : $this->opts['core']['selector'];

		echo 
		"
		<script type=\"text/javascript\">
		jQuery(document).ready(function(){
			jQuery('${selector}').fitVids();
		});
		</script>
		";
	}

}