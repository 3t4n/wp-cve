<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * VikAppointments view contents handler.
 * This class provides helpful tools to enhance the
 * <head> of the pages.
 *
 * @since  	1.6
 */
#[\AllowDynamicProperties]
class VAPViewContents
{
	/**
	 * A list of instances.
	 *
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * The view object.
	 *
	 * @var JView
	 */
	protected $page;

	/**
	 * A registry of params.
	 *
	 * @var JRegistry
	 */
	protected $params;

	/**
	 * The name of the current view (set in query string).
	 *
	 * @var string
	 */
	protected $currentView;

	/**
	 * The name of the active page (active menu item).
	 *
	 * @var string
	 */
	protected $activeView;

	/**
	 * Returns a new instance of this object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param 	mixed 	$page 	The view object.
	 *
	 * @return 	self 	A new instance of this object.
	 *
	 * @uses 	getViewHandler()
	 */
	public static function getInstance($page)
	{
		$sign = get_class($page);

		if (!isset(static::$instances[$sign]))
		{
			$obj = static::getViewHandler($sign, $page);

			if (!$obj)
			{
				$obj = new static($page);
			}

			static::$instances[$sign] = $obj;
		}

		return static::$instances[$sign];
	}

	/**
	 * Helper method used to detect if a sub-handler
	 * should be used.
	 *
	 * @param 	string 	$name 	The view class name.
	 * @param 	string 	$view 	The view object.
	 *
	 * @return 	mixed 	The handler object if exists, otherwise null.
	 */
	protected static function getViewHandler($name, $view)
	{
		if (!preg_match("/VikAppointmentsView(.+)$/i", $name, $match))
		{
			// the view is not part of VikAppointments
			return null;
		}

		$file 	= strtolower($match[1]);
		$class 	= 'VAPViewContents' . ucwords($match[1]);

		if (!VAPLoader::import('libraries.view.classes.' . $file))
		{
			// the view is not supported
			return null;
		}

		$handler = new $class($view, $file);

		if (!$handler instanceof VAPViewContents)
		{
			// the handler doesn't inherit the default class
			return null;
		}

		return $handler;
	}

	/**
	 * Class constructor.
	 *
	 * @param 	mixed 	$page 	The view object.
	 * @param 	string 	$type 	The view file/type. If not provided
	 * 							it will be calculated from the $page class name.
	 */
	public function __construct($page, $type = null)
	{
		$this->page = $page;

		$menu 	= JFactory::getApplication()->getMenu()->getActive();
		$params = null;

		/**
		 * Workaround for issue related to MenuItem object.
		 *
		 * @since 3.7.0
		 */
		if (isset($menu->params))
		{
			$this->params = $menu->params;
		}
		else if ($menu && method_exists($menu, 'getParams'))
		{
			$this->params = $menu->getParams();
		}
		else
		{
			$this->params = new JRegistry;
		}

		if ($type)
		{
			$this->currentView = $type;
		}
		else if (preg_match("/View(.+)$/i", get_class($page), $match))
		{
			$this->currentView = strtolower($match[1]);
		}

		if (isset($menu->query) && isset($menu->query['view']))
		{
			$this->activeView = $menu->query['view'];
		}
	}

	/**
	 * Magic method to access protected properties.
	 *
	 * @param 	string 	$name 	The property name.
	 *
	 * @return 	mixed 	The property value.
	 */
	public function __get($name)
	{
		// do not check if the property exists as it should
		// be raised a warning in case of missing property
		return $this->{$name};
	}

	/**
	 * Returns the HTML used to render the page heading.
	 *
	 * @param 	boolean  $echo 	True to display the HTML, false to return it.
	 *
	 * @return 	string 	 The heading HTML.
	 */
	public function getPageHeading($echo = false)
	{
		$html = '';

		if ((int) $this->params->get('show_page_heading') == 1 && $this->params->get('page_heading'))
		{
			$data = array(
				'suffix' => $this->params->get('pageclass_sfx', ''),
				'title'  => $this->params->get('page_heading'),
			);

			$html = JLayoutHelper::render('document.heading', $data);
		}

		if (!$echo)
		{
			return $html;
		}

		echo $html;	
	}

	/**
	 * Sets the browser page title.
	 *
	 * @return 	boolean  True if set, otherwise false.
	 *
	 * @since 	1.6.1
	 */
	public function setPageTitle()
	{
		// do nothing on base class

		return false;
	}

	/**
	 * Sets the meta description according to the settings of the page.
	 *
	 * @return 	boolean  True if set, otherwise false.
	 */
	public function setMetaDescription()
	{
		$desc = $this->params->get('menu-meta_description');
		
		if ($desc)
		{
			$this->page->document->setDescription($desc);

			return true;
		}

		return false;
	}

	/**
	 * Sets the meta keywords according to the settings of the page.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @return 	boolean  True if set, otherwise false.
	 */
	public function setMetaKeywords()
	{
		return $this->setMetaData('keywords', 'menu-meta_keywords');
	}

	/**
	 * Sets the meta robots according to the settings of the page.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @return 	boolean  True if set, otherwise false.
	 */
	public function setMetaRobots()
	{
		if ($this->setMetaData('robots', 'robots'))
		{
			return true;
		}

		$app = JFactory::getApplication();

		// fallback to include global robots
		if ($robot = $app->get('robots'))
		{
			$this->page->document->setMetaData('robots', $app->get('robots'));

			return true;
		}

		return false;
	}

	/**
	 * Creates the OPEN GRAPH protocol according to the 
	 * entity of the current page.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function buildOpenGraph()
	{
		$doc = $this->page->document;

		// generic metadata
		$locale = str_replace('-', '_', JFactory::getLanguage()->getTag());
		$doc->setMetaData('og:locale', $locale);

		foreach (VikAppointments::getKnownLanguages() as $tag)
		{
			$tag = str_replace('-', '_', $tag);

			if ($tag != $locale)
			{
				$doc->setMetaData('og:locale:alternate', $tag);
			}
		}

		$doc->setMetaData('og:site_name', JFactory::getApplication()->get('sitename'));

		/**
		 * Trigger event to allow the plugins to add meta data according to
		 * OPEN GRAPH protocol.
		 *
		 * @param 	JDocument 	 	 $doc   The application document object.
		 * @param 	VAPViewContents	 $this  The view content handler. 
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 */
		VAPFactory::getEventDispatcher()->trigger('onBuildOpenGraph', array($doc, $this));

		return $this;
	}

	/**
	 * Builds mircodata information using the application/ld+json format.
	 *
	 * @return 	void
	 *
	 * @uses 	getMicrodataObject()
	 */
	public function buildMicrodata()
	{
		// init empty object
		$json = new stdClass;

		// fill microdata object
		$res = $this->getMicrodataObject($json);

		/**
		 * Trigger event to allow the plugins to manipulate the JSON object
		 * that will be used by search engines.
		 *
		 * @param 	object 	 		 &$json    The JSON object.
		 * @param 	boolean 	 	 &$res     True whether the JSON object should be included into the document.
		 * @param 	VAPViewContents	 $handler  The view content handler. 
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 */
		VAPFactory::getEventDispatcher()->trigger('onBeforeAddMicrodata', array(&$json, &$res, $this));

		if ($res)
		{
			// attach only in case of successful response
			$this->page->document->addScriptDeclaration(json_encode($json), 'application/ld+json');
		}
	}

	/**
	 * Returns the microdata object to include within the head of the page.
	 * Inherits this method to dispatch the attachment of the properties to 
	 * children classes.
	 *
	 * @param 	object 	 &$json  The root object used to attach microdata.
	 *
	 * @return 	boolean  True in case the object should be attached, otherwise false.
	 */
	protected function getMicrodataObject(&$json)
	{
		return false;
	}

	/**
	 * Sets a generic meta data according to the settings of the page.
	 *
	 * @param 	string 	 $key 	 The meta key.
	 * @param 	string 	 $param  The parameter name.
	 *
	 * @return 	boolean  True if set, otherwise false.
	 */
	protected function setMetaData($key, $param)
	{
		$value = $this->params->get($param);
		
		if ($value)
		{
			$this->page->document->setMetaData($key, $value);

			return true;
		}

		return false;
	}
}
