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
 * VikAppointments Service Search view contents handler.
 * This class provides helpful tools to enhance the
 * <head> of the servicesearch pages.
 *
 * @since  	1.6
 */
class VAPViewContentsServiceSearch extends VAPViewContents
{
	/**
	 * A registry containing the service information.
	 *
	 * @var JRegistry
	 */
	protected $service;

	/**
	 * The service description.
	 *
	 * @var string
	 */
	protected $description = null;

	/**
	 * The maximum number of characters to display
	 * for the service description.
	 *
	 * @var integer
	 */
	protected $descLength = 512;

	/**
	 * Class constructor.
	 *
	 * @param 	mixed 	$page 	The view object.
	 * @param 	string 	$type 	The view file/type. If not provided
	 * 							it will be calculated from the $page class name.
	 */
	public function __construct($page, $type = null)
	{
		parent::__construct($page, $type);

		if (isset($page->service))
		{
			$tmp = $page->service;
		}
		else
		{
			$tmp = array();
		}

		$this->service = new JRegistry($tmp);

		/**
		 * Create service metadata registry.
		 *
		 * @since 1.6.1
		 */
		$meta = (array) json_decode($this->service->get('metadata', ''), true);

		$this->service->set('metadata', new JRegistry($meta));
	}

	/**
	 * @override
	 * Sets the meta description according to the settings of the page.
	 *
	 * @return 	boolean  True if set, otherwise false.
	 */
	public function setMetaDescription()
	{
		/**
		 * If provided, use the service meta description.
		 *
		 * @since 1.6.1
		 */
		if ($desc = $this->service->get('metadata')->get('description'))
		{
			$this->page->document->setDescription($desc);

			return true;
		}

		// before all, set the description of the current menu item
		$set = parent::setMetaDescription();

		// if the description was empty or the current menu item is
		// not equals to the view set in the request, try to use
		// the description related to the service
		if (!$set || $this->activeView != $this->currentView)
		{
			// get service description
			$desc = strip_tags($this->service->get('description', ''));

			if ($desc)
			{
				$this->page->document->setDescription($desc);

				$set = true;
			}
		}

		return $set;
	}

	/**
	 * @override
	 * Sets the meta keywords according to the settings of the page.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @return 	boolean  True if set, otherwise false.
	 *
	 * @since 	1.6.1
	 */
	public function setMetaKeywords()
	{
		// if provided, use the service meta keywords
		if ($keys = $this->service->get('metadata')->get('keywords'))
		{
			$this->page->document->setMetaData('keywords', $keys);
			
			return true;
		}

		// otherwise use default method
		return parent::setMetaKeywords();
	}

	/**
	 * @override
	 * Sets the browser page title.
	 *
	 * @return 	boolean  True if set, otherwise false.
	 *
	 * @since 	1.6.1
	 */
	public function setPageTitle()
	{
		// if provided, use the service meta title
		if ($title = $this->service->get('metadata')->get('title'))
		{
			$this->page->document->setTitle($title);
			
			return true;
		}

		// if the current menu item is not equals to the view set 
		// in the request, try to use the title related to the service
		if ($this->activeView != $this->currentView)
		{
			$title = $this->page->document->getTitle() . ' - ' . $this->service->get('name');

			$this->page->document->setTitle($title);

			return true;
		}

		return false;
	}

	/**
	 * Returns the service description.
	 *
	 * @return 	string 	A short version of the description.
	 */
	protected function getServiceDescription()
	{
		if ($this->description === null)
		{
			// get service description
			$desc = $this->service->get('description', '');

			if ($desc)
			{
				// render HTML description to grab plugin contents and short description
				VikAppointments::renderHtmlDescription($desc, 'microdata');

				// strip HTML tags from description
				$desc = strip_tags($desc);

				// check if the description length exceeds the limit
				if (strlen($desc) > $this->descLength)
				{
					// use the first N characters of the service description
					$desc = mb_substr(strip_tags($desc), 0, $this->descLength, 'UTF-8') . '...';
				}
			}

			// cache description
			$this->description = $desc;
		}

		return $this->description;
	}

	/**
	 * @override
	 * Creates the OPEN GRAPH protocol according to the 
	 * entity of the current page.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @uses 	getServiceDescription()
	 */
	public function buildOpenGraph()
	{
		$doc = $this->page->document;

		// basic metadata
		$doc->setMetaData('og:title'      , $this->service->get('name'));
		$doc->setMetaData('og:description', $this->getServiceDescription());
		$doc->setMetaData('og:type'       , 'website');

		if ($this->service->get('image'))
		{
			$doc->setMetaData('og:image', VAPMEDIA_URI . $this->service->get('image'));
		}

		// invoke parent to include generic metadata
		return parent::buildOpenGraph();
	}

	/**
	 * @override
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
		/**
		 * Service schema type.
		 *
		 * @link https://schema.org/Service
		 */

		$config = VAPFactory::getConfig(); 

		// define schema type
		$json->{"@context"} = 'http://schema.org';
		$json->{"@type"}	= 'Service';

		// add service information
		$json->name        = $this->service->get('name');
		$json->description = $this->getServiceDescription();
		$json->url         = JUri::root();

		if ($this->service->get('image'))
		{
			$json->image = VAPMEDIA_URI . $this->service->get('image');
		}

		if ($config->get('companylogo'))
		{
			$json->logo = VAPMEDIA_URI . $config->get('companylogo');
		}

		// get reviews
		$reviews = $this->service->get('reviews');

		// add reviews
		if ($reviews && $reviews->size > 0)
		{
			/**
			 * AggregateRating schema type.
			 *
			 * @link https://schema.org/AggregateRating
			 */
			$json->aggregateRating = new stdClass;
			$json->aggregateRating->{"@type"}   = 'AggregateRating';
			$json->aggregateRating->ratingValue = $this->service->get('rating');
			$json->aggregateRating->reviewCount = $reviews->size;

			// add latest 2 reviews
			$json->review = array();

			// number of reviews to show
			$lim = min(array((int) $reviews->size, 2));

			for ($i = 0; $i < $lim; $i++)
			{
				$tmp = $reviews->rows[$i];

				/**
				 * Review schema type.
				 *
				 * @link https://schema.org/Review
				 */
				$review = new stdClass;
				$review->{"@type"}     = 'Review';
				$review->author        = $tmp->name;
				$review->datePublished = JDate::getInstance($tmp->timestamp)->format('Y-m-d');
				$review->description   = $tmp->comment;
				$review->name          = $tmp->title;

				/**
				 * Rating schema type.
				 *
				 * @link https://schema.org/Rating
				 */
				$review->reviewRating 	= new stdClass;
				$review->reviewRating->{"@type"}   = 'Rating';
				$review->reviewRating->bestRating  = 5;
				$review->reviewRating->ratingValue = $tmp->rating;
				$review->reviewRating->worstRating = 1;

				$json->review[] = $review;
			}
		}

		// add offer
		if ($this->service->get('price', 0) > 0)
		{
			/**
			 * Offer schema type.
			 *
			 * @link https://schema.org/Offer
			 */
			$json->offers = new stdClass;
			$json->offers->{"@type"} 	 = 'Offer';
			$json->offers->price 	 	 = $this->service->get('price');
			$json->offers->priceCurrency = $config->get('currencyname');
		}

		return true;
	}
}
