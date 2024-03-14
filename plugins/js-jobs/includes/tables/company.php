<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScompanyTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $category = '';
    public $name = '';
    public $alias = '';
    public $url = '';
    public $logofilename = '';
    public $logoisfile = '';
    public $logo = '';
    public $smalllogofilename = '';
    public $smalllogoisfile = '';
    public $smalllogo = '';
    public $aboutcompanyfilename = '';
    public $aboutcompanyisfile = '';
    public $aboutcompanyfilesize = '';
    public $aboutcompany = '';
    public $contactname = '';
    public $contactphone = '';
    public $companyfax = '';
    public $contactemail = '';
    public $since = '';
    public $companysize = '';
    public $income = '';
    public $description = '';
    public $country = '';
    public $state = '';
    public $county = '';
    public $city = '';
    public $zipcode = '';
    public $address1 = '';
    public $address2 = '';
    public $created = '';
    public $modified = '';
    public $hits = '';
    public $metadescription = '';
    public $metakeywords = '';
    public $status = '';
    public $packageid = '';
    public $paymenthistoryid = '';
    public $isgoldcompany = 2; // For the case of new company store
    public $startgolddate = '';
    public $endgolddate = '';
    public $isfeaturedcompany = 2; // For the case of new company store
    public $startfeatureddate = '';
    public $endfeatureddate = '';
    public $serverstatus = '';
    public $serverid = '';
    public $facebook = '';
    public $googleplus = '';
    public $linkedin = '';
    public $twitter = '';
    public $params = '';

    function __construct() {
        parent::__construct('companies', 'id'); // tablename, primarykey
    }

}

?>