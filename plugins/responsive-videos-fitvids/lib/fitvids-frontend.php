<?php

/**
 * 
 * @package SGI\FitVids
 */

/**
 * 
 * Main plugin class
 * 
 * This class loads plugin options, enqueues scripts and loads up the selection sharer
 * 
 * @subpackage Frontend interfaces
 * @author Sibin Grasic
 * @since 1.0
 */
class SGI_FitVids_Frontend
{

	/**
     * @var array $opts {
     *         Plugin options
     *
     *         @type array $core {
     *               Array holding core plugin settings
     *               
     *               @type bool   $autoconfig       Autoconfig flag
     *               @type string $selector         CSS selector for the fitvids js
     *         }
     *         @type array $active {
     *               Array holding booleans for page type activation
     *
     *               @type bool $post         Flag for single post
     *               @type bool $page         Flag for single page
     *               @type bool $fp           Flag for front page
     *               @type bool $arch         Flag for archive pages
     *         }
     *
     * }
     * @since 1.0
     */
    private $opts;

	/**
	 * Class Constructor
	 * 
	 * Loads default options (if none are saved) and enqueues our scripts
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function __construct()
	{
		
		$fitvids_opts = get_option(
            'sgi_fitvids_opts',
            array(
                'core'     => array(
                    'autoconfig' => true,
                    'selector'   => '.entry-content'
                ),
                'selector' => '.entry-content',
                'active'   => array(
                    'post' => true,
                    'page' => true,
                    'fp'   => true,
                    'arch' => true,
                ),
            )
        );

        $this->opts = $fitvids_opts;

		add_action('wp_enqueue_scripts',array(&$this,'load_scripts'),75);
		add_action('embed_oembed_html',[&$this, 'embed_wrap'],20,4);
	}

	public function embed_wrap($cache, $url, $attr = '', $post_ID = '')
	{

		if ($this->opts['core']['autoconfig']) :

  			return '<div class="entry-content-asset">' . $cache . '</div>';

  		else :

  			return $cache;

  		endif;

	}

	/**
	 * Load our scripts when needed.
	 * 
	 * Since we only need selection share on pages where it's enabled, we're checking if we're viewing single post or page
	 *
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function load_scripts()
	{
		if ( (is_single() && $this->opts['active']['post']) || (is_page()  && $this->opts['active']['page']) || (is_home() && $this->opts['active']['fp']) || (is_archive()  && $this->opts['active']['arch']) ) : 

			
			wp_register_script( 'sgi-fitvids', plugins_url( "assets/js/jQuery.fitVids.js", SGI_FITVIDS_BASENAME ), array('jquery'), SGI_FITVIDS_VERSION, true);
			wp_enqueue_script('sgi-fitvids');


			add_action ('wp_footer',array (&$this,'footer_activation'),90);

		endif;
	}

	/**
	 * Add script activator to footer
	 * 
	 * Adds a small inline script to the bottom of the footer which targets our custom selector set in WP-admin
	 * 
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function footer_activation()
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