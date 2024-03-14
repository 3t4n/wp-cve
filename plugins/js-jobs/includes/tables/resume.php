<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumeTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $created = '';
    public $last_modified = '';
    public $published = '';
    public $hits = '';
    public $application_title = '';
    public $keywords = '';
    public $alias = '';
    public $first_name = '';
    public $last_name = '';
    public $middle_name = '';
    public $gender = '';
    public $email_address = '';
    public $home_phone = '';
    public $work_phone = '';
    public $cell = '';
    public $nationality = '';
    public $iamavailable = '';
    public $searchable = '';
    public $photo = '';
    public $job_category = '';
    public $jobsalaryrangestart = '';
    public $jobsalaryrangeend = '';
    public $jobsalaryrangetype = '';
    public $jobtype = '';
    public $heighestfinisheducation = '';
    public $status = '';
    public $resume = '';
    public $date_start = '';
    public $desiredsalarystart = '';
    public $desiredsalaryend = '';
    public $djobsalaryrangetype = '';
    public $dcurrencyid = '';
    public $can_work = '';
    public $available = '';
    public $unavailable = '';
    public $experienceid = '';
    public $skills = '';
    public $driving_license = '';
    public $license_no = '';
    public $license_country = '';
    public $packageid = '';
    public $paymenthistoryid = '';
    public $currencyid = '';
    public $job_subcategory = '';
    public $date_of_birth = '';
    public $videotype = '';
    public $video = '';
    public $isgoldresume = 2; // For the case of new resume
    public $startgolddate = '';
    public $endgolddate = '';
    public $isfeaturedresume = 2; // For the case of new resume
    public $startfeatureddate = '';
    public $endfeatureddate = '';
    public $serverstatus = '';
    public $serverid = '';
    public $tags = '';
    public $facebook = '';
    public $googleplus = '';
    public $linkedin = '';
    public $twitter = '';
    public $params = '';

    function __construct() {
        parent::__construct('resume', 'id'); // tablename, primarykey
    }

}

?>