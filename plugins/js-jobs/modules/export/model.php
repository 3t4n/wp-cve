<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSExportModel {

    function setExport($jobid, $resumeid, $socialprofileid = null) {
        //for job title
        if (is_numeric($jobid)) {
            $query = "SELECT title FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE id = " . $jobid;
            $jobtitle = jsjobs::$_db->get_var($query);
        }
        if ($socialprofileid != null) {
            $result = JSJOBSincluder::getObjectClass('socialmedia')->getExportResumes($socialprofileid);
        } else {
            $result = $this->getExportResumes($resumeid);
        }
        if (!$result) {
            return false;
        } else {
            $result = $this->makeArrayForExport($result);
            // Empty data vars
            $data = "";
            // We need tabbed data
            $sep = "\t";
            if (isset($jobtitle)) {
                $data .= "Job Title" . $sep . $jobtitle . "\n" . "\n";
            }

            foreach ($result[0] as $sectionName => $section) {
                if ($sectionName == 'personal') {
                    $fields = (array_keys($section));
                    $columns = count($fields);
                    $data .= "Personal Insformation" . "\n" . "\n";
                    // Put the name of all fields to $out.
                    for ($i = 0; $i < $columns; $i++) {
                        $data .= $fields[$i] . $sep;
                    }
                    $data .= "\n";

                    // If count rows is nothing show o records.
                    if (count($section) == 0) {
                        $data .= "\n(0) Records Found!\n";
                    } else {
                        $line = '';
                        // Now replace several things for MS Excel
                        foreach ($section as $value) {
                            $value = jsjobslib::jsjobs_str_replace('"', '""', $value);
                            $line .= '"' . $value . '"' . "\t";
                        }
                        $data .= jsjobslib::jsjobs_trim($line) . "\n";
                        $data = jsjobslib::jsjobs_str_replace("\r", "", $data);
                    }
                } elseif ($sectionName == 'skills' OR $sectionName == 'resume') {
                    
                } else {
                    $data .= "\n" . jsjobslib::jsjobs_ucfirst($sectionName) . "\n" . "\n";
                    for ($m = 0; $m < count($section); $m++) {
                        $fields = (array_keys($section[$m]));
                        $columns = count($fields);
                        // Put the name of all fields to $out.
                        for ($i = 0; $i < $columns; $i++) {
                            if ($m != 0) {
                                $data .= $fields[$i] . "-" . $m . $sep;
                            } else {
                                $data .= $fields[$i] . $sep;
                            }
                        }
                        $data .= "\n";
                        // Counting rows and push them into a for loop
                        $row = $section[$m];
                        $line = '';
                        // Now replace several things for MS Excel
                        foreach ($row as $value) {
                            $value = jsjobslib::jsjobs_str_replace('"', '""', $value);
                            $line .= '"' . $value . '"' . "\t";
                        }
                        $data .= jsjobslib::jsjobs_trim($line) . "\n" . "\n";

                        $data = jsjobslib::jsjobs_str_replace("\r", "", $data);

                        // If count rows is nothing show o records.
                        if (count($result) == 0) {
                            $data .= "\n(0) Records Found!\n";
                        }
                    }
                }
            }
            return $data;
        }
    }

    function makeArrayForExport($result) {        
        $fieldsordering = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
        $fieldsarray = array();
        foreach ($fieldsordering AS $field) {
            $fieldsarray['fieldtitles'][$field->section][$field->field] = $field->fieldtitle;
            $fieldsarray[2][$field->section][$field->field] = $field->required;
        }
        $ff = $fieldsarray[2];
        $fieldsordering = array();
        foreach ($ff AS $section => $fields) {
            foreach ($fields AS $key => $value) {
                $fieldsordering[$section][$key] = 1; // all fields were published it is maintained in model
            }
        }

        $keyString = '';
        $returnvalue = array();
        $i = 0;
        foreach($fieldsordering AS $section => $fields){
            switch($section){
                case 1: // personal
                    foreach($fields AS $key => $value){
                        switch ($key) {
                            case "application_title":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->application_title) ? $result['personal']->application_title : "";
                                break;
                            case "first_name":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->first_name) ? $result['personal']->first_name : "";  
                                break;
                            case "middle_name":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->middle_name) ? $result['personal']->middle_name : "";
                                break;
                            case "last_name":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->last_name) ? $result['personal']->last_name : "";
                                break;
                            case "email_address":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->email_address) ? $result['personal']->email_address : "";
                                break;
                            case "nationality":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->nationality) ? $result['personal']->nationality : "";
                                break;
                            case "date_of_birth":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->date_of_birth) ? $result['personal']->date_of_birth : "";
                                break;
                            case "gender":
                                if($result['personal']->gender == 1){
                                    $result['personal']->gender = __('Male','js-jobs');
                                }elseif($result['personal']->gender == 2){
                                    $result['personal']->gender = __('Female','js-jobs');
                                }else{
                                    $result['personal']->gender = __('Does not matter','js-jobs');
                                }
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->gender) ? $result['personal']->gender : "";
                                break;
                            case "iamavailable":
                                $result['personal']->iamavailable = isset($result['personal']->iamavailable) ? $result['personal']->iamavailable : "0";
                                if($result['personal']->iamavailable == 1){
                                    $result['personal']->iamavailable = __('Yes','js-jobs');
                                }else{
                                    $result['personal']->iamavailable = __('No','js-jobs');
                                }
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = $result['personal']->iamavailable;
                                break;
                            case "searchable":
                                $result['personal']->searchable = isset($result['personal']->searchable) ? $result['personal']->searchable : "0";
                                if($result['personal']->searchable == 1){
                                    $result['personal']->searchable = __('Yes','js-jobs');
                                }else{
                                    $result['personal']->searchable = __('No','js-jobs');
                                }
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = $result['personal']->searchable;
                                break;
                            case "home_phone":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->home_phone) ? $result['personal']->home_phone : "";
                                break;
                            case "work_phone":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->work_phone) ? $result['personal']->work_phone : "";
                                break;
                            case "job_category":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->job_category) ? $result['personal']->job_category : "";
                                break;
                            case "salary":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->salary) ? $result['personal']->salary : "";
                                break;
                            case "jobtype":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->jobtype) ? $result['personal']->jobtype : "";
                                break;
                            case "heighestfinisheducation":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->heighestfinisheducation) ? $result['personal']->heighestfinisheducation : "";
                                break;
                            case "date_start":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->date_start) ? $result['personal']->date_start : "";
                                break;
                            case "total_experience":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = isset($result['personal']->total_experience) ? $result['personal']->total_experience : "";
                                break;
                            case "driving_license":
                                $result['personal']->driving_license = isset($result['personal']->driving_license) ? $result['personal']->driving_license : "0";
                                if($result['personal']->driving_license == 1){
                                    $result['personal']->driving_license = __('Yes','js-jobs');
                                }else{
                                    $result['personal']->driving_license = __('No','js-jobs');
                                }
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = $result['personal']->driving_license;
                                break;
                            case "license_no":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = $result['personal']->license_no;
                                break;
                            case "license_country":
                                $returnvalue['personal'][$fieldsarray['fieldtitles'][$section][$key]] = $result['personal']->license_country;
                                break;
                            default:
                                $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $result['personal']->params); // 11 view resume
                                if (is_array($array))
                                    $returnvalue['personal'][$array['title']] = $array['value'];
                                break;
                        }
                    }
                break;
                case 2: // address
                    $k = 0;
                    foreach($result['addresses'] AS $address){
                        foreach($fields AS $key => $value){
                            switch ($key) {
                                case "address_city":
                                    $returnvalue['address'][$k][__('City','js-jobs')] = $address->address_city_name;
                                    $returnvalue['address'][$k][__('State','js-jobs')] = $address->address_state_name;
                                    $returnvalue['address'][$k][__('Country')] = $address->address_country_name;
                                    break;
                                case "address_zipcode":
                                    $returnvalue['address'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $address->address_zipcode;
                                    break;
                                case "address":
                                    $returnvalue['address'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $address->address;
                                    break;
                                default:
                                    $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $address->params); // 11 view resume
                                    if (is_array($array))
                                        $returnvalue['address'][$k][$array['title']] = $array['value'];
                                    break;
                            }
                        }
                        $k++;
                    }
                break;
                case 3: // education
                    $k = 0;
                    foreach($result['institutes'] AS $institute){
                        foreach($fields AS $key => $value){
                            switch ($key) {
                                case "institute":
                                    $returnvalue['institute'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $institute->institute;
                                    break;
                                case "institute_city":
                                    $returnvalue['institute'][$k][__('City','js-jobs')] = $institute->institute_city_name;
                                    $returnvalue['institute'][$k][__('State','js-jobs')] = $institute->institute_state_name;
                                    $returnvalue['institute'][$k][__('Country')] = $institute->institute_country_name;
                                    break;
                                case "institute_address":
                                    $returnvalue['institute'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $institute->institute_address;
                                    break;
                                case "institute_certificate_name":
                                    $returnvalue['institute'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $institute->institute_certificate_name;
                                    break;
                                case "institute_study_area":
                                    $returnvalue['institute'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $institute->institute_study_area;
                                    break;
                                default:
                                    $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $institute->params); // 11 view resume
                                    if (is_array($array))
                                        $returnvalue['institute'][$k][$array['title']] = $array['value'];
                                    break;
                            }
                        }
                        $k++;
                    }
                break;
                case 4: // employer
                    $k = 0;
                    foreach($result['employers'] AS $employer){
                        foreach($fields AS $key => $value){
                            switch ($key) {
                                case "employer":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer;
                                    break;
                                case "employer_city":
                                    $returnvalue['employer'][$k][__('City','js-jobs')] = $employer->employer_city_name;
                                    $returnvalue['employer'][$k][__('State','js-jobs')] = $employer->employer_state_name;
                                    $returnvalue['employer'][$k][__('Country')] = $employer->employer_country_name;
                                    break;
                                case "employer_position":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_position;
                                    break;
                                case "employer_resp":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_resp;
                                    break;
                                case "employer_pay_upon_leaving":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_pay_upon_leaving;
                                    break;
                                case "employer_supervisor":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_supervisor;
                                    break;
                                case "employer_from_date":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_from_date;
                                    break;
                                case "employer_to_date":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_to_date;
                                    break;
                                case "employer_leave_reason":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_leave_reason;
                                    break;
                                case "employer_zip":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_zip;
                                    break;
                                case "employer_phone":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_phone;
                                    break;
                                case "employer_address":
                                    $returnvalue['employer'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $employer->employer_address;
                                    break;
                                default:
                                    $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $employer->params); // 11 view resume
                                    if (is_array($array))
                                        $returnvalue['employer'][$k][$array['title']] = $array['value'];
                                    break;
                            }
                        }
                        $k++;
                    }
                break;
                case 5: // skills
                    foreach($fields AS $key => $value){
                        switch ($key) {
                            case "skills":
                                $returnvalue['skills'][$fieldsarray['fieldtitles'][$section][$key]] = $result['personal']->skills;
                                break;
                            default:
                                $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $result['personal']->params); // 11 view resume
                                if (is_array($array))
                                    $returnvalue['skills'][$array['title']] = $array['value'];
                                break;
                        }
                    }
                break;
                case 6: // resume
                    foreach($fields AS $key => $value){
                        switch ($key) {
                            case "resume":
                                $returnvalue['resume'][$fieldsarray['fieldtitles'][$section][$key]] = $result['personal']->resume;
                                break;
                            default:
                                $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $result['personal']->params); // 11 view resume
                                if (is_array($array))
                                    $returnvalue['resume'][$array['title']] = $array['value'];
                                break;
                        }
                    }
                break;
                case 7: // reference
                    $k = 0;
                    foreach($result['references'] AS $reference){
                        foreach($fields AS $key => $value){
                            switch ($key) {
                                case "reference":
                                    $returnvalue['reference'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $reference->reference;
                                    break;
                                case "reference_city":
                                    $returnvalue['reference'][$k][__('City','js-jobs')] = $reference->reference_city_name;
                                    $returnvalue['reference'][$k][__('State','js-jobs')] = $reference->reference_state_name;
                                    $returnvalue['reference'][$k][__('Country')] = $reference->reference_country_name;
                                    break;
                                case "reference_name":
                                    $returnvalue['reference'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $reference->reference_name;
                                    break;
                                case "reference_zipcode":
                                    $returnvalue['reference'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $reference->reference_zipcode;
                                    break;
                                case "reference_address":
                                    $returnvalue['reference'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $reference->reference_address;
                                    break;
                                case "reference_phone":
                                    $returnvalue['reference'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $reference->reference_phone;
                                    break;
                                case "reference_relation":
                                    $returnvalue['reference'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $reference->reference_relation;
                                    break;
                                case "reference_years":
                                    $returnvalue['reference'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $reference->reference_years;
                                    break;
                                default:
                                    $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $reference->params); // 11 view resume
                                    if (is_array($array))
                                        $returnvalue['reference'][$k][$array['title']] = $array['value'];
                                    break;
                            }
                        }
                        $k++;
                    }
                break;
                case 8: // language
                    $k = 0;
                    foreach($result['languages'] AS $language){
                        foreach($fields AS $key => $value){
                            switch ($key) {
                                case "language":
                                    $returnvalue['language'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $language->language;
                                    break;
                                case "language_reading":
                                    $returnvalue['language'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $language->language_reading;
                                    break;
                                case "language_writing":
                                    $returnvalue['language'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $language->language_writing;
                                    break;
                                case "language_address":
                                    $returnvalue['language'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $language->language_address;
                                    break;
                                case "language_understanding":
                                    $returnvalue['language'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $language->language_understanding;
                                    break;
                                case "language_relation":
                                    $returnvalue['language'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $language->language_relation;
                                    break;
                                case "language_where_learned":
                                    $returnvalue['language'][$k][$fieldsarray['fieldtitles'][$section][$key]] = $language->language_where_learned;
                                    break;
                                default:
                                    $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $language->params); // 11 view resume
                                    if (is_array($array))
                                        $returnvalue['language'][$k][$array['title']] = $array['value'];
                                    break;
                            }
                        }
                        $k++;
                    }
                break;
            }
        }
        $resume[0] = $returnvalue;
        return $resume;
    }

    /* END EXPORT RESUMES */

    function setAllExport($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;
        //for job title
        $query = "SELECT title FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE id = " . $jobid;
        $jobtitle = jsjobs::$_db->get_var($query);
        $resumeids = $this->getResumeIdsForAllExport($jobid);
        // Empty data vars
        $data = "";
        // We need tabbed data
        $sep = "\t";
        $data .= "Job Title" . $sep . $jobtitle . "\n" . "\n";
        for ($n = 0; $n < count($resumeids); $n++) {
            $resumeNumber = $n + 1;
            $data .= "Resume-" . $resumeNumber . "\n";
            $resumeid = $resumeids[$n]->resumeid;
            if ($resumeids[$n]->socialapplied == 1) {
                $profileid = $resumeids[$n]->socialprofileid;
                $result = JSJOBSincluder::getObjectClass('socialmedia')->getExportResumes($profileid);
            } else {
                $result = $this->getExportResumes($resumeid);
            }
            if (!$result) {
                return false;
            } else {
                $result = $this->makeArrayForExport($result);
                foreach ($result[0] as $sectionName => $section) {
                    if ($sectionName == 'personal') {
                        $fields = (array_keys($section));
                        $columns = count($fields);
                        $data .= "Personal Insformation" . "\n" . "\n";
                        // Put the name of all fields to $out.
                        for ($i = 0; $i < $columns; $i++) {
                            $data .= $fields[$i] . $sep;
                        }
                        $data .= "\n";

                        // If count rows is nothing show o records.
                        if (count($section) == 0) {
                            $data .= "\n(0) Records Found!\n";
                        } else {
                            $line = '';
                            // Now replace several things for MS Excel
                            foreach ($section as $value) {
                                $value = jsjobslib::jsjobs_str_replace('"', '""', $value);
                                $line .= '"' . $value . '"' . "\t";
                            }
                            $data .= jsjobslib::jsjobs_trim($line) . "\n";
                            $data = jsjobslib::jsjobs_str_replace("\r", "", $data);
                        }
                    } elseif ($sectionName == 'skills' OR $sectionName == 'resume') {
                        
                    } else {
                        $data .= "\n" . jsjobslib::jsjobs_ucfirst($sectionName) . "\n" . "\n";
                        for ($m = 0; $m < count($section); $m++) {
                            $fields = (array_keys($section[$m]));
                            $columns = count($fields);
                            // Put the name of all fields to $out.
                            for ($i = 0; $i < $columns; $i++) {
                                if ($m != 0) {
                                    $data .= $fields[$i] . "-" . $m . $sep;
                                } else {
                                    $data .= $fields[$i] . $sep;
                                }
                            }
                            $data .= "\n";
                            // Counting rows and push them into a for loop
                            $row = $section[$m];
                            $line = '';
                            // Now replace several things for MS Excel
                            foreach ($row as $value) {
                                $value = jsjobslib::jsjobs_str_replace('"', '""', $value);
                                $line .= '"' . $value . '"' . "\t";
                            }
                            $data .= jsjobslib::jsjobs_trim($line) . "\n" . "\n";

                            $data = jsjobslib::jsjobs_str_replace("\r", "", $data);

                            // If count rows is nothing show o records.
                            if (count($result) == 0) {
                                $data .= "\n(0) Records Found!\n";
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    function getResumeIdsForAllExport($jobid) {
        if (!is_numeric($jobid))
            return false;
        $query = "SELECT jobapply.cvid AS resumeid, jobapply.socialapplied, jobapply.socialprofileid 
                FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply 
                WHERE jobapply.jobid =" . $jobid;
        $resumeids = jsjobs::$_db->get_results($query);
        return $resumeids;
    }

    function getExportResumes($resumeid) {
        if (!is_numeric($resumeid))
            return false;
        $query = "SELECT resume.application_title, resume.params 
                    , resume.first_name, resume.last_name, resume.middle_name
                    , resume.gender, resume.email_address, resume.home_phone, resume.work_phone 
                    , resume.cell, nationality_country.name AS nationality, resume.iamavailable, resume.searchable 
                    , resume.job_category AS categoryid, resume.jobsalaryrangestart, resume.jobsalaryrangetype 
                    , resume.date_start, resume.desiredsalarystart, countries.name AS license_country 
                    , resume.djobsalaryrangetype, resume.dcurrencyid, resume.can_work, resume.available 
                    , exp.title AS total_experience, resume.skills, resume.driving_license, resume.license_no
                    , resume.packageid, resume.paymenthistoryid, resume.currencyid, resume.job_subcategory AS job_subcategoryid 
                    , resume.date_of_birth, resume.video, resume.isgoldresume, resume.isfeaturedresume, resume.serverstatus 
                    , resume.nationality AS nationalityid, resume.serverid, heighesteducation.title AS heighestfinisheducation 
                    , resume.last_modified, resume.hits, resume.keywords, resume.alias, resume.resume 
                    , currency.symbol, cat.cat_title AS job_category, salarystarttble.rangestart 
                    , salaryendtble.rangeend, jobtypetbl.title AS jobtype, resume.jobtype AS jobtypeid 
                    , CONCAT(resume.alias,'-',resume.id) AS resumealiasid 
                    , salarytype.title AS salarytype 
                    , CONCAT(currency.symbol,salarystarttble.rangestart,'-',salaryendtble.rangeend,'/',salarytype.title) AS salary 
                    
                    FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON resume.experienceid = exp.id 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON resume.job_category = cat.id 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtypetbl ON resume.jobtype = jobtypetbl.id 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS heighesteducation ON resume.heighestfinisheducation = heighesteducation.id 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS nationality_country ON resume.nationality = nationality_country.id 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salarystarttble ON resume.jobsalaryrangestart = salarystarttble.id 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryendtble ON resume.jobsalaryrangeend = salaryendtble.id 
                    LEFT JOIN  `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salarytype ON resume.jobsalaryrangetype = salarytype.id 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON resume.license_country = countries.id 
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = resume.currencyid 
                    WHERE resume.id = " . $resumeid;

        $resume = jsjobs::$_db->get_row($query);
        $result['personal'] = $resume;

        $query = "SELECT address.id, address.resumeid, address.address, address.params 
                    , address.address_city AS address_cityid, address.address_zipcode 
                    , states.name AS address_state_name, cities.name AS address_city_name 
                    , countries.name AS address_country_name

                    FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS address
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS cities ON cities.id = address.address_city
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS states ON states.id = cities.stateid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON countries.id = cities.countryid

                    WHERE address.resumeid = " . $resumeid;

        $resume = jsjobs::$_db->get_results($query);
        $result['addresses'] = $resume;

        $query = "SELECT institute.id, institute.resumeid, institute.institute, institute.params 
                    , institute.institute_address, institute.institute_city AS institute_cityid 
                    , institute.institute_certificate_name, institute.institute_study_area 
                    , countries.name AS institute_country_name, cities.name AS institute_city_name 
                    , states.name AS institute_state_name 
                    
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS institute
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS cities ON institute.institute_city = cities.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS states ON cities.stateid = states.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON cities.countryid = countries.id

                    WHERE institute.resumeid = " . $resumeid;

        $resume = jsjobs::$_db->get_results($query);
        $result['institutes'] = $resume;

        $query = "SELECT employer.id, employer.resumeid, employer.employer, employer.employer_address, employer.params 
                    , employer.employer_city AS employer_cityid, employer.employer_position 
                    , employer.employer_pay_upon_leaving, employer.employer_supervisor 
                    , states.name AS employer_state_name, employer.last_modified, employer.employer_resp 
                    , countries.name AS employer_country_name, cities.name AS employer_city_name 
                    , employer.employer_from_date, employer.employer_to_date 
                    , employer.employer_leave_reason, employer.employer_zip 
                    , employer.employer_phone
                    
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumeemployers` AS employer
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS cities ON employer.employer_city = cities.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS states ON cities.stateid = states.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON cities.countryid = countries.id

                    WHERE employer.resumeid = " . $resumeid;

        $resume = jsjobs::$_db->get_results($query);
        $result['employers'] = $resume;

        $query = "SELECT reference.id, reference.resumeid, reference.reference, reference.params 
                    , reference.reference_name, reference.reference_zipcode 
                    , reference.reference_city AS reference_cityid, reference.reference_address 
                    , reference.reference_phone, reference.reference_relation 
                    , reference.reference_years, reference.last_modified, states.name AS reference_state_name 
                    , countries.name AS reference_country_name, cities.name AS reference_city_name 
                    
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumereferences` AS reference
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS cities ON reference.reference_city = cities.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS states ON cities.stateid = states.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON cities.countryid = countries.id

                    WHERE reference.resumeid = " . $resumeid;

        $resume = jsjobs::$_db->get_results($query);
        $result['references'] = $resume;

        $query = "SELECT language.id, language.resumeid, language.language, language.params 
                    , language.language_reading, language.language_writing 
                    , language.language_understanding, language.language_where_learned 
                    , language.last_modified 
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumelanguages` AS language WHERE language.resumeid = " . $resumeid;

        $resume = jsjobs::$_db->get_results($query);
        $result['languages'] = $resume;

        return $result;
    }
    function getMessagekey(){
        $key = 'export';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
