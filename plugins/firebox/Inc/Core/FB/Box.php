<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\FB;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FireBox\Core\Helpers\BoxHelper;
use FPFramework\Libs\Registry;
use FPFramework\Helpers\Fields\DimensionsHelper;
use FPFramework\Helpers\CSS;

class Box
{
	/**
	 * Send useful JS snippet once in first box
	 * 
	 * @var  boolean
	 */
	static $loadedLocalizedScript = false;

	/**
	 * The box.
	 * 
	 * @param   object
	 */
	private $box = null;

	/**
	 * Factory
	 * 
	 * @var  Factory
	 */
	private $factory = null;

	/**
	 * FireBox settings.
	 * 
	 * @var  object
	 */
	private $params = null;

	/**
	 * Popup CSS.
	 * 
	 * @var  CSS
	 */
	public $css = null;

	/**
	 * Constructor.
	 * 
	 * @param   object  $box
	 * @param   object  $factory
	 * 
	 * @return  void
	 */
	public function __construct($box = null, $factory = null)
	{
		if ($box)
		{
			$this->box = $this->prepareConstructorBox($box);
		}

		if (!$factory)
		{
			$factory = new \FPFramework\Base\Factory();
		}
		$this->factory = $factory;

		$this->params = new Registry(BoxHelper::getParams());
	}

	/**
	 * Allow to set either a box ID or box object
	 * and we then set the box object.
	 * 
	 * @param   mixed   $box
	 * 
	 * @return  object
	 */
	private function prepareConstructorBox($box)
	{
		if (!is_object($box))
		{
			$box = $this->get($box);
		}
		
		return $box;
	}

	/**
	 * Get a box.
	 * 
	 * @param   int     $id
	 * @param   string  $status
	 * 
	 * @return  object
	 */
	public function get($id = null, $status = null)
	{
		if (!$id)
		{
			return;
		}

		$payload = [
			'where' => [
				'ID' => ' = ' . esc_sql(intval($id)),
				'post_type' => " = 'firebox'"
			]
		];

		// apply status if given
		if ($status)
		{
			$payload['where']['post_status'] = ' = \'' . esc_sql($status) . '\'';
		}
		
		if (!$box = firebox()->tables->box->getResults($payload))
		{
			return [];
		}

		if (!isset($box[0]))
		{
			return [];
		}
		
		$this->box = $box[0];

		// get meta options for box
		$meta = \FireBox\Core\Helpers\BoxHelper::getMeta($id);
		$this->box->params = new Registry($meta);

		return $this->box;
	}

	/**
	 * Renders the box.
	 * 
	 * @return  void
	 */
	public function render()
	{
		// Check Publishing Assignments
        if (!$this->pass())
        {
			return;
		}

		$fbox = $this->box;
		
		add_action('wp_enqueue_scripts', function() use ($fbox) {
			// Loads all media files.
			$this->loadBoxMedia($fbox);
		});

		/**
		 * Runs before rendering the box.
		 */
		do_action('firebox/box/before_render', $this->box);

		$this->prepare();
		
		$css = $this->getCustomCSS();

		add_action('wp_enqueue_scripts', function() use ($fbox, $css) {
			// Load CSS
			if ($css)
			{
				wp_add_inline_style('firebox', $css);
			}

			
		});
		
		// Allow to manipulate the box before rendering
		$this->box = apply_filters('firebox/box/edit', $this->box);

		// payload
		$payload = [
			'box' => $this->box,
			'params' => $this->params,
		];
		
		// return box template
		add_action('wp_footer', function() use ($payload) {
			$html = firebox()->renderer->public->render('box', $payload, true);

			/**
			 * Runs after rendering the box.
			 */
			$html = apply_filters('firebox/box/after_render', $html, $payload['box']);

			echo $html;
		});
	}

	/**
	 * Gets the Custom CSS of the popup.
	 * 
	 * @return  string
	 */
	private function getCustomCSS()
	{
		return $this->box->params->get('customcss', '');
	}

	/**
	 * Send a helpful object to JavaScript files
	 *
	 * @return  void
	 */
	public static function setJSObject()
	{
		if (self::$loadedLocalizedScript)
		{
			return;
		}
		self::$loadedLocalizedScript = true;
		
		$data = array(
			'ajax_url'			 => admin_url('admin-ajax.php'),
			'nonce'				 => wp_create_nonce('fbox_js_nonce'),
			'site_url'			 => site_url('/'),
			'referrer'			 => isset($_SERVER['HTTP_REFERER']) ? sanitize_text_field($_SERVER['HTTP_REFERER']) : ''
		);

		wp_localize_script('firebox', 'fbox_js_object', $data);
	}

	/**
	 * Load Box Media
	 * 
	 * @return  void
	 */
	public function loadBoxMedia($box)
	{
		$box = new Registry($box);

		// Add polyfills for Internet Explorer
		$browser = $this->factory->getBrowser();
		if ($browser && is_array($browser) && array_key_exists('name', $browser) && $browser['name'] === 'ie')
		{
			wp_enqueue_script(
				'firebox-ie11-polyfill',
				'https://polyfill.io/v3/polyfill.min.js?features=NodeList.prototype.forEach%2CElement.prototype.closest%2CArray.prototype.forEach%2CArray.prototype.find%2CIntersectionObserver%2CIntersectionObserverEntry',
				[],
				FBOX_VERSION,
				true
			);
		}

		/**
		 * Velocity
		 */
		if ($this->params->get('loadVelocity', true))
		{
			wp_enqueue_script(
				'firebox-velocity',
				FBOX_MEDIA_PUBLIC_URL . 'js/vendor/velocity.js',
				[],
				FBOX_VERSION,
				true
			);
			wp_enqueue_script(
				'firebox-velocity-ui',
				FBOX_MEDIA_PUBLIC_URL . 'js/vendor/velocity.ui.js',
				[],
				FBOX_VERSION,
				true
			);

			/**
			 * Animations
			 */
			if (strpos($box->get('params.data.animationin'), 'firebox') !== false || strpos($box->get('params.data.animationout'), 'firebox') !== false)
			{
				wp_enqueue_script(
					'firebox-animations',
					FBOX_MEDIA_PUBLIC_URL . 'js/animations.js',
					[],
					FBOX_VERSION,
					true
				);
			}
		}

		/**
		 * FireBox JS
		 */
		wp_enqueue_script(
			'firebox',
			FBOX_MEDIA_PUBLIC_URL . 'js/firebox.js',
			[],
			FBOX_VERSION,
			true
		);

		// run above the main JS script to run only once
        self::setJSObject();
		
		/**
		 * FireBox CSS
		 */
		if ($this->params->get('loadCSS', true))
		{
			wp_enqueue_style(
				'firebox',
				FBOX_MEDIA_PUBLIC_URL . 'css/firebox.css',
				[],
				FBOX_VERSION
			);
		}

		/**
		 * Page Slide mode JS
		 */
		if ($box->get('params.data.mode') == 'pageslide')
		{
			wp_enqueue_script(
				'firebox-pageslide-mode',
				FBOX_MEDIA_PUBLIC_URL . 'js/pageslide_mode.js',
				[],
				FBOX_VERSION,
				true
			);
		}

		

		$this->loadThemeCSSOverrides();
	}

	/**
	 * Some themes require overrides to preserve as much as we can the styling of the popups.
	 * 
	 * @return  void
	 */
	private function loadThemeCSSOverrides()
	{
		$active_theme = wp_get_theme();
		$theme = $active_theme->template;

		/**
		 * This is the listed of the themes that we have created overrides
		 */
		$themes = [
			'twentytwentyone'
		];

		if (!in_array($theme, $themes))
		{
			return;
		}

		wp_enqueue_style(
			'firebox-theme-' . $theme . '-override',
			FBOX_MEDIA_PUBLIC_URL . 'css/themes/' . $theme . '.css',
			[],
			FBOX_VERSION
		);
	}

	/**
	 * Prepares the box before rendering
	 * 
	 * @return  void
	 */
	public function prepare()
	{
		$this->css = new Styling\CSS($this->box);

		$cParam = BoxHelper::getParams();
		$cParam = new Registry($cParam);

		$this->box->post_content = apply_filters('the_content', $this->box->post_content);
		
		$position = $this->box->params->get('position', '');
		$position = !is_string($position) ? '' : $position;
		
        /* Classes */
        $css_class = [
            $this->box->ID,
            $position
		];
		
        $rtl = $this->box->params->get('rtl', '0');
        if ($rtl == '1')
        {
            $css_class[] = 'rtl';
		}

		self::prefixCSSClasses($css_class);
		
		// Class suffix
		$classSuffix = $this->box->params->get('classsuffix', '');
		$classSuffix = is_string($classSuffix) ? $classSuffix : '';
		
        $css_class[] = $classSuffix;
		
		$this->box->classes = $css_class;
		
		// Box shadow
		$boxshadow = (is_string($this->box->params->get('boxshadow', '1')) || is_int($this->box->params->get('boxshadow', '1'))) ? $this->box->params->get('boxshadow', '1') : '0';

        $dialog_css_classes = [
            $boxshadow != '0' ? 'shd' . $boxshadow : null
		];

		// Align Content
		$aligncontent = is_string($this->box->params->get('aligncontent')) ? explode(' ', $this->box->params->get('aligncontent')) : [];
        $dialog_css_classes = array_merge($dialog_css_classes, $aligncontent);
		
        self::prefixCSSClasses($dialog_css_classes);
		$this->box->dialog_classes = $dialog_css_classes;
		
        $trigger_point_methods = [
			'pageready'    => 'onPageReady',
            'pageload'     => 'onPageLoad',
            'onclick'      => 'onClick',
            'elementHover' => 'onHover',
            'ondemand'     => 'onDemand',
			
		];

		/* Other Settings */
		$scroll_depth = $this->box->params->get('scroll_depth', 'percentage');
		$scroll_depth = is_string($scroll_depth) ? $scroll_depth : '';

		$animation_duration = $this->box->params->get('duration') ? (float) $this->box->params->get('duration') : 0;

		$delay = in_array($this->box->params->get('triggermethod'), ['floatingbutton', 'onexternallink']) ? 0 : (int) $this->box->params->get('triggerdelay') * 1000;

		$trigger_method = (is_string($this->box->params->get('triggermethod'))) && array_key_exists($this->box->params->get('triggermethod'), $trigger_point_methods) ? $trigger_point_methods[$this->box->params->get('triggermethod')] : $this->box->params->get('triggermethod');

		$trigger_element = is_scalar($this->box->params->get('triggerelement', '')) ? $this->box->params->get('triggerelement', '') : '';

        // Use Namespaced classes for each trigger point and let them manipulate the settings dynamicaly.
        $this->box->settings = [
			'name'				   => $this->box->post_title,
            'trigger'              => $trigger_method,
            'trigger_selector'     => $trigger_method === 'onExternalLink' ? '' : rtrim($trigger_element, ','),
            'delay'                => $delay,
			
            'animation_open'       => $this->box->params->get('animationin'),
            'animation_close'      => $this->box->params->get('animationout'),
			'animation_duration'   => (float) $animation_duration * 1000,
			'prevent_default'      => (bool) $this->box->params->get('preventdefault', true),
            'backdrop'             => (bool) $this->box->params->get('overlay'),
            'backdrop_color'       => $this->box->params->get('overlay_color'),
            'backdrop_click'       => (bool) $this->box->params->get('overlayclick'),
            'disable_page_scroll'  => (bool) $this->box->params->get('preventpagescroll'),
            'test_mode'            => (bool) $this->box->params->get('testmode'),
            'debug'                => (bool) $cParam->get('debug', false),
			'auto_focus'		   => (bool) $this->box->params->get('autofocus', false)
		];

		// Apply Popup CSS
		$this->box->params->set('customcss', $this->box->params->get('customcss') . $this->css->getCSS());

		$this->replaceBoxSmartTags();
	}

	/**
	 * Replaces all box smart tags
	 * 
	 * @return  object
	 */
	public function replaceBoxSmartTags()
	{
		$tags = new \FPFramework\Base\SmartTags\SmartTags();

		// register FB Smart Tags
		$tags->register('\FireBox\Core\SmartTags', FBOX_BASE_FOLDER . '/Inc/Core/SmartTags', $this->box);

		$this->box = $tags->replace($this->box);
	}

	/**
	 * Checks if a box passes assignments
	 * 
	 * @return  boolean
	 */
	public function pass()
    {
        if (!$this->box || !is_object($this->box))
        {
            return false;
		}

        // Check first local assignments
        if (!$this->passLocalAssignments())
        {
            return false;
        }

        $displayConditionsType = $this->box->params->get('display_conditions_type', '');

        // If empty, display popup sitewide
        if (empty($displayConditionsType) || $displayConditionsType === 'all')
        {
            return true;
        }
		
        // Mirror Display Conditions of another popup.
        if ($displayConditionsType == 'mirror' && $mirror_box_id = $this->box->params->get('mirror_box'))
        {
            $this->box->params->merge(self::getAssignmentsForMirroring($mirror_box_id));
        }

		// Get a recursive array of all rules
        $rules = json_decode(wp_json_encode($this->box->params->get('rules', [])), true);

		// If testmode is enabled disable the User Groups assignment
        if ($this->box->params->get('testmode'))
        {
            foreach ($rules as $key => &$group)
            {
                foreach ($group['rules'] as $_key => &$rule)
                {
                    if (!isset($rule['name']) || empty($rule['name']))
                    {
                        continue;
                    }

                    if ($rule['name'] === 'WP\UserGroup')
                    {
                        unset($group['rules'][$_key]);
                    }
                }
            }
        }

        // Check framework based assignments
        return \FPFramework\Base\Conditions\ConditionBuilder::pass($rules, $this->factory);
	}

    /**
     * Check if a box passes local assignments
     *
     * @return  boolean
     */
    private function passLocalAssignments()
    {
        $localAssignments = new \FireBox\Core\FB\Assignments($this, $this->factory);
        return $localAssignments->passAll();
    }
	
	/**
	 * Gets assignments of mirrored box
	 * 
	 * @param   int  $box_id
	 * 
	 * @return  object
	 */
	private function getAssignmentsForMirroring($box_id)
    {   
		$payload = [
			'where' => [
				'ID' => ' = ' . intval($box_id),
				'post_status' => " = 'publish'",
				'post_type' => " = 'firebox'"
			]
		];
		
        // Load box
		if (!$box = firebox()->tables->box->getResults($payload))
		{
            return;
		}
		
		$box = $box[0];
		
		// get meta options for box
		$meta = get_post_meta($box_id, 'fpframework_meta_settings', true);
		$box->params = new Registry($meta);

        return new Registry(['rules' => $box->params->get('rules')]);
    }

	/**
	 * Prefixes the CSS classes
	 * 
	 * @param   array   $classes
	 * @param   string  $prefix
	 * 
	 * @return  void
	 */
    private static function prefixCSSClasses(&$classes, $prefix = 'fb-')
    {
		$classes = array_filter($classes);
		
		if (empty($classes))
		{
			return;
		}

        foreach ($classes as &$class)
        {
            $class = $prefix . $class;
        }
    }

	/**
	 * Track box open
	 * 
	 * @param   integer  $box_id
	 * @param   string   $page
	 * @param   string   $referrer
	 * 
	 * @return  void
	 */
    public function logOpenEvent($box_id, $page = null, $referrer = null)
    {
        $box = $this->get($box_id);

        // Do not track if statistics option is disabled
		$track_open_event = (bool) (is_null($box->params->get('stats', null)) ? BoxHelper::getParams()->get('stats', 1) : $box->params->get('stats'));
        if (!$track_open_event)
        {
            return;
        }

        return firebox()->log->track($box_id, 1, null, $page, $referrer);
    }

	/**
	 * Track box close
	 * 
	 * @param   integer  $box_id
	 * @param   integer  $box_log_id
	 * 
	 * @return  void
	 */
    public function logCloseEvent($box_id, $box_log_id)
    {
        $box = $this->get($box_id);

        // Do not track if statistics option is disabled
		$track_open_event = (bool) (is_null($box->params->get('stats', null)) ? BoxHelper::getParams()->get('stats', 1) : $box->params->get('stats'));
        if (!$track_open_event)
        {
            return null;
        }

        firebox()->log->track($box_id, 2, $box_log_id);
	}

	/**
	 * Get total box impressions
	 * 
	 * @param   array  $payload
	 * 
	 * @return  array
	 */
	public function getTotalImpressions($payload)
	{
		$impressions = firebox()->tables->boxlog->getResults($payload);
		
		return count($impressions);
	}

	/**
	 * Returns the cookie instance.
	 * 
	 * @return  mixed
	 */
	public function getCookie()
	{
		if (!$this->box)
		{
			return;
		}
		
		return new Cookie($this->box->id);
	}

	/**
	 * Returns the box.
	 * 
	 * @return  object
	 */
	public function getBox()
	{
		return $this->box;
	}

	/**
	 * Sets the box.
	 * 
	 * @param   object  $box
	 * 
	 * @return  Box
	 */
	public function setBox($box)
	{
		$this->box = $box;

		return $this;
	}
}