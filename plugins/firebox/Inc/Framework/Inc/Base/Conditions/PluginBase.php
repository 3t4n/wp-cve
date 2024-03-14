<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Conditions;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;
use FPFramework\Helpers\StringHelper;

abstract class PluginBase extends Condition
{
	/**
	 * Category/Taxonomy.
	 * 
	 * @var string
	 */
	protected $taxonomy = null;

    /**
     * The component's Single Page view name
     *
     * @var string
     */
    protected $postTypeSingle = 'post';

	protected $request = [];

	/**
	 * Class constructor
	 *
	 * @param	array  $options		The rule options. Expected properties: selection, value, params
	 * @param   object $factory		The framework's factory class.
	 */
	public function __construct($options = null, $factory = null)
	{
		parent::__construct($options, $factory);
        
        $this->request = $this->getRequest();
    }

	private function getRequest()
	{
		$object = get_queried_object();

        $request = new \stdClass;

        $request->id = $object instanceof \WP_Term ? (int) $object->term_id : ($object instanceof \WP_Post ? (int) $object->ID : null);
        $request->post_type = isset($object->post_type) ? $object->post_type : null;
        $request->taxonomy = $object && $object instanceof \WP_Term ? $object->taxonomy : null;

		return $request;
	}
    /**
     *  Base assignment check
     * 
     *  @return bool
     */
	public function pass()
	{	
		return $this->passByOperator();
	}

    /**
     *  Indicates whether the current view concerncs a Single Page view
     *
     *  @return  boolean
     */
    public function isSinglePage()
    {
        return ($this->request->post_type === $this->postTypeSingle);
    }

    /**
     *  Indicates whether the current view concerns a Category view
     *
     *  @return  boolean
     */
    protected function isCategoryPage()
    {
        return $this->request->taxonomy === $this->taxonomy;
    }

	/**
	 * Check whether this page passes the validation
	 *
	 * @return void
	 */
	protected function passSinglePage()
	{
        if (empty($this->selection) || !$this->isSinglePage())
        {
            return false;
		}
		
        if (!is_array($this->selection))
        {
            $this->selection = Functions::makeArray($this->selection);
		}
		
		return $this->passByOperator();
	}

    /**
	 * Checks whether the current page is within the selected categories
	 *
	 * @return	boolean
	 */
    protected function passCategories()
    {
        if (empty($this->selection))
        {
            return false;
		}

		// Include Children switch: 0 = No, 1 = Yes, 2 = Child Only
		$inc_children  = $this->params->get('inc_children');

		// Setup supported views
		$view_single   = $this->params->get('view_single', true);
		$view_category = $this->params->get('view_category', false);

		// Check if we are in a valid context
		if (!($view_category && $this->isCategoryPage()) && !($view_single && $this->isSinglePage()))
		{
			return false;
		}

		// Start Checks
		$pass = false;

		// Get current page assosiated category IDs. It can be a single ID of the current Category view or multiple IDs assosiated to active item.
		$catids = $this->getCategoryIds();
		$catids = is_array($catids) ? $catids : (array) $catids;

		foreach ($catids as $catid)
		{
			$pass = in_array($catid, $this->selection);

			if ($pass)
			{
				// If inc_children is either disabled or set to 'Also on Childs', there's no need for further checks. 
				// The condition is already passed.
				if (in_array($this->params->get('inc_children'), [0, 1]))
				{
					break;
				}

				// We are here because we need childs only. Disable pass and continue checking parent IDs.
				$pass = false;
			}

			// Pass check for child items
			if (!$pass && $this->params->get('inc_children'))
			{
				// Get parent categories
				$parent_ids = get_ancestors($catid, $this->taxonomy);

				foreach ($parent_ids as $id)
				{
					if (in_array($id, $this->selection))
					{
						$pass = true;
						break 2;
					}
				}

				unset($parent_ids);
			}
		}

		return $pass;
	}

    /**
	 *  Returns category IDs based
	 *
	 *  @return  array
	 */
	protected function getCategoryIDs()
	{
		$id = $this->request->id;

		// Make sure we have an ID.
		if (empty($id))
		{
			return;
		}

		// If this is a Category page, return the Category ID from the Query String
		if ($this->isCategoryPage())
		{
			return (array) $id;
		}

		// If this is a Single Page, return all assosiated Category IDs.
		if ($this->isSinglePage())
		{
			return $this->getSinglePageCategories($id);
		}
	}

    /**
     * Get single page's assosiated categories
     *
     * @param   Integer  The Single Page id
	 * 
     * @return  array
     */
    abstract protected function getSinglePageCategories($id);
}