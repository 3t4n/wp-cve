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

use FPFramework\Libs\Registry;
use FireBox\Core\Helpers\BoxHelper;

class Log
{
    /**
     * Factory
     * 
     * @var  Factory
     */
    private $factory;

    public function __construct($factory = null)
    {
        if (!$factory)
        {
            $factory = new \FPFramework\Base\Factory();
        }

        $this->factory = $factory;
    }
    
    /**
     *  Logs box events to the database
     *
     *  @param   integer  $boxid       The box id
     *  @param   string   $page        The page the box appeared
     *  @param   string   $referrer    The referrer
     *  @param   integer  $event_id     Event id: 1=Open, 2=Close
     *  @param   integer  $box_log_id  The box log ID of the open event that we are closing
     *
     *  @return  bool     Returns a boolean indicating if the event logged successfully
     */
    public function track($boxid, $event_id = 1, $box_log_id = null, $page = '', $referrer = '')
    {
    	// Making sure we have a valid Boxid
        if (!$boxid)
        {
            return;
        }

        // open event
        if ($event_id == 1)
        {
            // Get visitor's token id
            if (!$visitorID = $this->factory->getVisitorID())
            {
                return;
            }

            
            $country_value = '';
            

            
    
            // Everything seems OK. Let's save data to db.
            $data = (object) [
                'sessionid'  => $this->factory->getSession()->getSessionID(),
                'visitorid'  => $visitorID,
                'user'       => $this->factory->getUser()->ID,
                'box'        => $boxid,
                'page'       => $page,
                'country'    => $country_value,
                'device'     => $this->factory->getDevice(),
                'referrer'   => $referrer,
                'date'       => $this->factory->getDate()->format('Y-m-d H:i:s')
            ];

            return $this->handleOpen($data);
        }

        // other events
        return $this->handleOtherEvents($box_log_id, $event_id);
    }

    /**
     * Handle the logging when opening a box
     * 
     * @param   array  $passed_data
     * 
     * @return  mixed
     */
    private function handleOpen($passed_data)
    {
        // Insert the object into the firebox logs table
        try
        {
            $log_id = firebox()->tables->boxlog->insert($passed_data);

            // clean up
            $this->clean();
            return $log_id;
        } 
        catch (Exception $e)
        {}
    }

    /**
     * Handle the logging of other events
     * 
     * @param   int     $box_log_id
     * @param   int     $event_id
     * @param   string  $event_source
     * @param   string  $event_label
     * 
     * @return  mixed
     */
    private function handleOtherEvents($box_log_id, $event_id, $event_source = '', $event_label = '')
    {
        if (!$box_log_id)
        {
            return;
        }
        
        // Insert the object into the firebox logs details table
        try
        {
            $data = [
                'log_id' => $box_log_id,
                'event' => $this->parseEvent($event_id),
                'event_source' => $event_source,
                'event_label' => $event_label,
                'date' => $this->factory->getDate()->format('Y-m-d H:i:s')
            ];
            
            firebox()->tables->boxlogdetails->insert($data);

            // clean up
            $this->clean();
            return;
        }
        catch (Exception $e)
        {
        }
    }

    /**
     * Translates the event ID to name
     * 
     * @param   int     $id
     * 
     * @return  string
     */
    private function parseEvent($id)
    {
        $name = '';

        switch ($id)
        {
            case 1:
                $name = 'open';
                break;
            case 2:
                $name = 'close';
                break;
            case 3:
                $name = 'conversion';
                break;
        }

        return $name;
    }

    /**
     *  Removes old rows from the logs table
     *  Runs every 12 hours with a self-check
     *
     *  @return void
     */
    private function clean()
    {
		// cache key
		$hash = md5('fireboxclean');

		// check cache
		if ($params = wp_cache_get($hash))
		{
			return;
        }

        // Removes rows older than x days
        $stats = \FireBox\Core\Helpers\BoxHelper::getParams();
        $stats = new Registry($stats);

        $days = $stats->get('statsdays', 730);

        global $wpdb;

        $sql = 'DELETE bl, bld
                FROM `' . firebox()->tables->boxlog->getFullTableName() . '` as bl
                    LEFT JOIN `' . firebox()->tables->boxlogdetails->getFullTableName() . '` as bld
                        ON bld.log_id = bl.id
                WHERE
                    bl.date < DATE_SUB(NOW(), INTERVAL ' . $wpdb->prepare('%d', $days) . ' DAY)';

        firebox()->tables->boxlog->executeRaw($sql);
        
		// set cache
		wp_cache_set($hash, $stats, $hash, 720);

        return true;
    }
}