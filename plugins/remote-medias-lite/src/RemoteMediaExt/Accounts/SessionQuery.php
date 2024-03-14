<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts;

use WPRemoteMediaExt\WPCore\Cache\Transient;

/*
*   Tansient can hit database max_allowed_packet
*   to prevent this a different transient created when reaching max of $maxdatacount (400)
*
*/
class SessionQuery
{
    public static $domain = 'ocs';

    protected $maxdatacount = 400;
    protected $dataIndex = 0;
    protected $key;
    protected $data = array();
    protected $isFull = false;

    protected $dataTransient;
    protected $paramsTransient;

    public function __construct($key, $serviceSlug = '')
    {
        self::$domain.= $serviceSlug;

        $this->setKey($key);

        $this->paramsTransient = new Transient(self::$domain.$this->keyparams, 15*MINUTE_IN_SECONDS);
        $this->dataTransient   = $this->getDataTransient();
    }

    public function setMaxPageDataCount($maxdatacount)
    {
        $this->maxdatacount = $maxdatacount;
    }

    public static function reset($serviceSlug = '')
    {
        global $wpdb;

        self::$domain.= $serviceSlug;
        
        $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE ('%transient_timeout_".self::$domain."%')");
        $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE ('%transient_".self::$domain."%')");
    }

    public function setKey($key)
    {
        $this->key = $key;
        $this->keyparams = substr($key, 0, strlen($key)-8).'_params';

        return $this;
    }

    public function getLastPage()
    {
        $params = $this->paramsTransient->get();
        if (empty($params) || empty($params['lastpage'])) {
            return 0;
        }

        return $params['lastpage'];
    }

    public function getDataIndex()
    {
        $totalcount = 0;
        $params = $this->paramsTransient->get();
        if (!empty($params) && !empty($params['totalcount'])) {
            $totalcount = $params['totalcount'];
        }

        return floor($totalcount / $this->maxdatacount);
    }

    public function getDataTransient($index = null)
    {
        $index = !is_null($index) ? $index :$this->getDataIndex();

        return $this->dataTransient = new Transient(
            self::$domain.$this->key.'-'.$index,
            15*MINUTE_IN_SECONDS
        );
    }

    /*
    *   Could enjoy some code optimization, for clarity in growth potential :)
    */
    public function load($newdata, $fullcount = 40)
    {
        $params = $this->paramsTransient->get();

        $currentIndex = $this->getDataIndex();
        $data   = $this->getDataTransient($currentIndex)->get();

        if (is_array($data) && count($data) >= $this->maxdatacount) {
            $currentIndex++;
            $data = $this->getDataTransient($currentIndex)->get();
        }
        
        if ($data === false) {
            $data = array();
        }
        
        if ($params === false) {
            $params = array();
            $params['lastpage'] = 0;
            $params['totalcount'] = 0;
        }

        if (empty($params['totalcount'])) {
            $params['totalcount'] = 0;
        }

        $data = array_merge($data, $newdata);
        $excessdata = array();

        //If too much data to fit in single cache field
        if (count($data) > $this->maxdatacount) {
            $excessdata = array_slice($data, $this->maxdatacount);
            $data = array_slice($data, 0, $this->maxdatacount);
        }
        
        $this->getDataTransient($currentIndex)->set($data);

        if (!empty($excessdata)) {
            $excessIndex = $currentIndex+1;
            $this->getDataTransient($excessIndex)->set($excessdata);
        }

        $params['totalcount']   += count($newdata);
        $params['lastpage']     = $params['lastpage'] + 1;
        $params['full']         = false;

        if (count($newdata) < $fullcount) {
            $params['full'] = true;
        }

        //Update cache with added data and params
        $this->paramsTransient->set($params);
    }

    public function setFull($full = true)
    {
        $params = $this->paramsTransient->get();
        if (empty($params)) {
            $params = array();
        }
        $params['full'] = $full;
        $this->paramsTransient->set($params);
        $this->isFull = $full;
    }

    public function isFull()
    {
        $params = $this->paramsTransient->get();
        if (empty($params) || empty($params['full'])) {
            return false;
        }

        return $params['full'];
    }

    public function clear()
    {
        $this->dataTransient->delete();
        $this->paramsTransient->delete();
    }

    public function getData($index)
    {
        $data = $this->getDataTransient($index)->get();
        if ($data === false) {
            return null;
        }
        
        return $data;
    }

    public function dataIndexFromOffset($offset)
    {
        return floor($offset / $this->maxdatacount);
    }

    /*
    * @return null if no data fetched
    */
    public function get($page = 1, $perpage = 40)
    {
        $offset = ($page - 1) * $perpage;

        $dataPage = $this->dataIndexFromOffset($offset);
        $dataPageOffset = $dataPage * $this->maxdatacount;

        $data = $this->getData($dataPage);
        if (empty($data) && !$this->isFull()) {
            return null;
        }

        $relOffset = $offset - $dataPageOffset;

        $page    = intval($page);
        $perpage = intval($perpage);

        if (!isset($data[$relOffset])) {
            return $this->isFull() ? array() : null;
        }

        //if all data contain in the current data page return it
        if ($dataPageOffset + $this->maxdatacount) {
            return array_slice($data, $relOffset, $perpage);
        }

        //Add data from next dataPage
        $dataPageOffset += $this->maxdatacount;
        $nextpagedata = $this->getData($dataPage+1);
        if (empty($nextpagedata) && !$this->isFull()) {
            return array_slice($data, $relOffset, $perpage);
        }

        return array_slice(array_merge($data, $nextpagedata), $relOffset, $perpage);
    }
}
