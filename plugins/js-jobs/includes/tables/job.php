<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobTable extends JSJOBStable {

    public $id = '';
    public $uid = '';
    public $companyid = '';
    public $title = '';
    public $alias = '';
    public $jobcategory = '';
    public $jobtype = '';
    public $jobstatus = '';
    public $jobsalaryrange = '';
    public $salaryrangetype = '';
    public $hidesalaryrange = '';
    public $description = '';
    public $qualifications = '';
    public $prefferdskills = '';
    public $applyinfo = '';
    public $company = '';
    public $country = '';
    public $state = '';
    public $county = '';
    public $city = '';
    public $zipcode = '';
    public $address1 = '';
    public $address2 = '';
    public $companyurl = '';
    public $contactname = '';
    public $contactphone = '';
    public $contactemail = '';
    public $showcontact = '';
    public $noofjobs = '';
    public $reference = '';
    public $duration = '';
    public $heighestfinisheducation = '';
    public $created = '';
    public $created_by = '';
    public $modified = '';
    public $modified_by = '';
    public $hits = '';
    public $experience = '';
    public $startpublishing = '';
    public $stoppublishing = '';
    public $departmentid = '';
    public $shift = '';
    public $sendemail = '';
    public $metadescription = '';
    public $metakeywords = '';
    public $agreement = '';
    public $ordering = '';
    public $aboutjobfile = '';
    public $status = '';
    public $educationminimax = '';
    public $educationid = '';
    public $mineducationrange = '';
    public $maxeducationrange = '';
    public $iseducationminimax = '';
    public $degreetitle = '';
    public $careerlevel = '';
    public $experienceminimax = '';
    public $experienceid = '';
    public $minexperiencerange = '';
    public $maxexperiencerange = '';
    public $isexperienceminimax = '';
    public $experiencetext = '';
    public $workpermit = '';
    public $requiredtravel = '';
    public $agefrom = '';
    public $ageto = '';
    public $salaryrangefrom = '';
    public $salaryrangeto = '';
    public $gender = '';
    public $map = '';
    public $packageid = '';
    public $paymenthistoryid = '';
    public $subcategoryid = '';
    public $currencyid = '';
    public $jobid = '';
    public $longitude = '';
    public $latitude = '';
    public $isgoldjob = 2;  // FOR THE ECASE OF NEW JOB 
    public $isfeaturedjob = 2; //FOR THE CASE OF NEW JOB 
    public $startgolddate = '';
    public $startfeatureddate = '';
    public $endgolddate = '';
    public $endfeatureddate = '';
    public $raf_gender = '';
    public $raf_degreelevel = '';
    public $raf_experience = '';
    public $raf_age = '';
    public $raf_education = '';
    public $raf_category = '';
    public $raf_subcategory = '';
    public $raf_location = '';
    public $serverstatus = '';
    public $serverid = '';
    public $joblink = '';
    public $jobapplylink = '';
    public $tags = '';
    public $params = '';

    public function check() {
        if ($this->companyid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('jobs', 'id'); // tablename, primarykey
    }

}

?>