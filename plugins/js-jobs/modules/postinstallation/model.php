<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSPostinstallationModel {

    function updateInstallationStatusConfiguration(){
            $flag = get_option('jsjobs_post_installation');
            if($flag == false){
                add_option( 'jsjobs_post_installation', '1', '', 'yes' );
            }else{
                update_option( 'jsjobs_post_installation', '1');
            }
    }

    function storeconfigurations($data){
        if (empty($data))
            return false;
        $error = false;
        unset($data['action']);
        unset($data['form_request']);
        unset($data['step']);
        foreach ($data as $key => $value) {
            $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET `configvalue` = '" . $value . "' WHERE `configname`= '" . $key . "'";
            if (!jsjobsdb::query($query)) {
                $error = true;
            }
        }

        if ($error)
            return JSJOBS_SAVE_ERROR;
        else
            return JSJOBS_SAVED;
    }

    function getConfigurationValues(){
        $this->updateInstallationStatusConfiguration();
        $query = "SELECT configvalue,configname  FROM`" . jsjobs::$_db->prefix . "js_job_config`";
        $data = jsjobsdb::get_results($query);
        $config_array = array();
        foreach ($data as $config) {
            if($config->configname == 'offline'){
                $config_array['offline']=$config->configvalue;
            }
            if($config->configname == 'title'){
                $config_array['title']=$config->configvalue;
            }
            if($config->configname == 'adminemailaddress'){
                $config_array['adminemailaddress']=$config->configvalue;
            }
            if($config->configname == 'mailfromaddress'){
                $config_array['mailfromaddress']=$config->configvalue;
            }
            if($config->configname == 'system_slug'){
                $config_array['system_slug']=$config->configvalue;
            }
            if($config->configname == 'disable_employer'){
                $config_array['disable_employer']=$config->configvalue;
            }
            if($config->configname == 'cur_location'){
                $config_array['cur_location']=$config->configvalue;
            }
            if($config->configname == 'companyautoapprove'){
                $config_array['companyautoapprove']=$config->configvalue;
            }
            if($config->configname == 'jobautoapprove'){
                $config_array['jobautoapprove']=$config->configvalue;
            }
            if($config->configname == 'empautoapprove'){
                $config_array['empautoapprove']=$config->configvalue;
            }
            if($config->configname == 'newdays'){
                $config_array['newdays']=$config->configvalue;
            }
            if($config->configname == 'searchjobtag'){
                $config_array['searchjobtag']=$config->configvalue;
            }
            if($config->configname == 'visitor_can_apply_to_job'){
                $config_array['visitor_can_apply_to_job']=$config->configvalue;
            }
            if($config->configname == 'visitor_can_post_job'){
                $config_array['visitor_can_post_job']=$config->configvalue;
            }
            if($config->configname == 'employerview_js_controlpanel'){
                $config_array['employerview_js_controlpanel']=$config->configvalue;
            }
            if($config->configname == 'data_directory'){
                $config_array['data_directory']=$config->configvalue;
            }
            if($config->configname == 'date_format'){
                $config_array['date_format']=$config->configvalue;
            }
            if($config->configname == 'mailfromname'){
                $config_array['mailfromname']=$config->configvalue;
            }
            if($config->configname == 'showemployerlink'){
                $config_array['showemployerlink']=$config->configvalue;
            }
            if($config->configname == 'system_have_gold_job'){
                $config_array['system_have_gold_job']=$config->configvalue;
            }
            if($config->configname == 'system_have_featured_job'){
                $config_array['system_have_featured_job']=$config->configvalue;
            }
            if($config->configname == 'allow_jobshortlist'){
                $config_array['allow_jobshortlist']=$config->configvalue;
            }
            if($config->configname == 'allow_tellafriend'){
                $config_array['allow_tellafriend']=$config->configvalue;
            }
            if($config->configname == 'employer_defaultgroup'){
                $config_array['employer_defaultgroup']=$config->configvalue;
            }
            if($config->configname == 'jobseeker_defaultgroup'){
                $config_array['jobseeker_defaultgroup']=$config->configvalue;
            }
            if($config->configname == 'default_pageid'){
                $config_array['default_pageid']=$config->configvalue;
            }
        }
        jsjobs::$_data[0] = $config_array;

    }

    function installSampleData($insertsampledata, $jsmenu,$empmenu,$temp_data = 0) {
        $date = date('Y-m-d H:i:s');
        $curdate = date('Y-m-d H:i:s');
        $thirdydaydate = date('Y-m-d', jsjobslib::jsjobs_strtotime($curdate. ' + 30 days'));


        if($jsmenu == 1){
            $query = "SELECT COUNT(ID) FROM ".jsjobs::$_db->prefix."posts WHERE post_content LIKE '%[jsjobs_jobseeker_controlpanel]%'";
            $pageexists = jsjobs::$_db->get_var($query);
            if($pageexists == 0){
                $post = array(
                    'post_name' => 'js-jobs-jobseeker-controlpanel',
                    'post_title' => 'Job seeker',
                    'post_status' => 'publish',
                    'post_content' => '[jsjobs_jobseeker_controlpanel]',
                    'post_type' => 'page'
                );
                $post_ID = wp_insert_post($post);
            }
        }
        if($empmenu == 1){
            $query = "SELECT COUNT(ID) FROM ".jsjobs::$_db->prefix."posts WHERE post_content LIKE '%[jsjobs_employer_controlpanel]%'";
            $pageexists = jsjobs::$_db->get_var($query);
            if($pageexists == 0){
                $post = array(
                    'post_name' => 'js-jobs-employer-controlpanel',
                    'post_title' => 'Employer',
                    'post_status' => 'publish',
                    'post_content' => '[jsjobs_employer_controlpanel]',
                    'post_type' => 'page'
                );
                $post_ID = wp_insert_post($post);
            }            
        }
        if ($insertsampledata == 1) {
            $jobseeker_id = 0;
            $employer_id = 0;
            /* insert new jobseeker */
            if(isset($_POST['jobseeker_id']) && is_numeric($_POST['jobseeker_id']) && $_POST['jobseeker_id'] != 0){
                $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "users` where ID= ". $_POST['jobseeker_id'];
                $jobseeker_data =jsjobsdb::get_row($query);
                $insert_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_users`
                            (id,uid,first_name,roleid,emailaddress,status,created)
                              VALUES('',".$_POST['jobseeker_id'].",'".$jobseeker_data->user_login."',2,'".$jobseeker_data->user_email."',1,'$date');";
                jsjobsdb::query($insert_query);
                $jobseeker_id = jsjobs::$_db->insert_id;
            }
            
            /* insert new employer */
            if(isset($_POST['employer_id']) && is_numeric($_POST['employer_id']) && $_POST['employer_id'] != 0){
                $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "users` where ID= ". $_POST['employer_id'];
                $employer_data =jsjobsdb::get_row($query);
                $insert_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_users`
                            (id,uid,first_name,roleid,emailaddress,status,created)
                              VALUES('',".$_POST['employer_id'].",'". $employer_data->user_login ."',1,'". $employer_data->user_email ."',1,'$date');";
                jsjobsdb::query($insert_query);
                $employer_id = jsjobs::$_db->insert_id;
            }

            //  first company
            if($employer_id > 0 && is_numeric($employer_id)){
                $cityid = '69785';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 13, 'Buruj Solution', 'buruj-solution', 'http://www.burujsolutions.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Buruj Solutions', '', NULL, 'sampledata@info.com', '2010-06-16 00:00:00', '', '', 'We aligns itself with modern and advanced concepts in IT industry to help its customers by providing value added software. We performs thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, '70150', '', 'WAPDA Town, Gujranwala ', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/burujsol/', '', '', '');";
                jsjobsdb::query($insert_company);
                $companyid = jsjobs::$_db->insert_id;


                //  second company
                $cityid1 = '69787';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 12, 'Joom Sky', 'joom-sky', 'http://www.joomsky.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Joom Sky', '', NULL, 'sampledata@joomsky.com', '2010-06-16 00:00:00', '', '', 'We aligns itself with modern and advanced concepts in IT industry to help its customers by providing value added software. We performs thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, '70176', '', 'Main Market WAPDA Town, Gujranwala ', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/joomsky/', '', '', '');";
                jsjobsdb::query($insert_company);
                $companyid1 = jsjobs::$_db->insert_id;

                $insert_companycity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companycities` (`companyid`, `cityid`) 
                VALUES( " . $companyid1 . ", " . $cityid1 . ");";
                jsjobsdb::query($insert_companycity);

                //  third company
                $cityid2 = '69795';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 18, 'Joom Shark', 'joom-shark', 'http://www.joomshark.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Joom Shark', '', NULL, 'sample@joomshark.com', '2010-06-16 00:00:00', '', '', 'We aligns itself with modern and advanced concepts in IT industry to help its customers by providing value added software. We performs thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, '70140', '', 'Main Market muhafiz Town, lahore ', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/joomshark/', '', '', '');";
                jsjobsdb::query($insert_company);
                $companyid2 = jsjobs::$_db->insert_id;
                
                $insert_companycity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companycities` (`companyid`, `cityid`) 
                VALUES( " . $companyid2 . ", " . $cityid2 . ");";
                jsjobsdb::query($insert_companycity);

                //  fourth company
                $cityid3 = '69820';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 28, 'Sample Company', 'sample-company', 'http://www.sample.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Sample Company', '', NULL, 'sample@sample.com', '2010-06-16 00:00:00', '', '', ' We perform thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, $cityid3, '', 'some streest in some city', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/', 'https://www.twitter.com/', 'https://www.gplus.com/', 'https://www.linkedin.com/');";
                jsjobsdb::query($insert_company);
                $companyid3 = jsjobs::$_db->insert_id;

                $insert_companycity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companycities` (`companyid`, `cityid`) 
                VALUES( " . $companyid3 . ", " . $cityid3 . ");";
                jsjobsdb::query($insert_companycity);

                //  fifth company
                $cityid4 = '69786';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 38, 'Sample Company 1', 'sample-company-1', 'http://www.sample1.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Sample Company', '', NULL, 'sample1@sample1.com', '2010-06-16 00:00:00', '', '', ' We perform thorough research on each given problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, $cityid4, '', 'some streest in some city', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/', 'https://www.twitter.com/', 'https://www.gplus.com/', 'https://www.linkedin.com/');";
                jsjobsdb::query($insert_company);
                $companyid4 = jsjobs::$_db->insert_id;

                $insert_companycity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companycities` (`companyid`, `cityid`) 
                VALUES( " . $companyid4 . ", " . $cityid4 . ");";
                jsjobsdb::query($insert_companycity);

                //  sixth company
                $cityid5 = '69788';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 75, 'Sample Company 2', 'sample-company-2', 'http://www.sample2.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Sample Company 2', '', NULL, 'sample2@sample2.com', '2010-06-16 00:00:00', '', '', ' problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, $cityid5, '', 'some streest in some city', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/', 'https://www.twitter.com/', 'https://www.gplus.com/', 'https://www.linkedin.com/');";
                jsjobsdb::query($insert_company);
                $companyid5 = jsjobs::$_db->insert_id;

                $insert_companycity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companycities` (`companyid`, `cityid`) 
                VALUES( " . $companyid5 . ", " . $cityid5 . ");";
                jsjobsdb::query($insert_companycity);

                //  seventh company
                $cityid6 = '69801';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 65, 'Sample Company 3', 'sample-company-3', 'http://www.sample3.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Sample Company 3', '', NULL, 'sample3@sample3.com', '2010-06-16 00:00:00', '', '', ' problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, $cityid6, '', 'some streest in some city', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/', 'https://www.twitter.com/', 'https://www.gplus.com/', 'https://www.linkedin.com/');";
                jsjobsdb::query($insert_company);
                $companyid6 = jsjobs::$_db->insert_id;

                $insert_companycity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companycities` (`companyid`, `cityid`) 
                VALUES( " . $companyid6 . ", " . $cityid6 . ");";
                jsjobsdb::query($insert_companycity);

                //  eighth company
                $cityid7 = '69792';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 45, 'Sample Company 4', 'sample-company-4', 'http://www.sample3.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Sample Company 4', '', NULL, 'sample4@sample4.com', '2010-06-16 00:00:00', '', '', ' problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, $cityid7, '', 'some streest in some city', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/', 'https://www.twitter.com/', 'https://www.gplus.com/', 'https://www.linkedin.com/');";
                jsjobsdb::query($insert_company);
                $companyid7 = jsjobs::$_db->insert_id;

                $insert_companycity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companycities` (`companyid`, `cityid`) 
                VALUES( " . $companyid7 . ", " . $cityid7 . ");";
                jsjobsdb::query($insert_companycity);

                //  ninth company
                $cityid8 = '69793';// cityids for companies
                $insert_company = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companies` (`uid`, `category`, `name`, `alias`, `url`, `logofilename`, `logoisfile`, `logo`, `smalllogofilename`, `smalllogoisfile`, `smalllogo`, `aboutcompanyfilename`, `aboutcompanyisfile`, `aboutcompanyfilesize`, `aboutcompany`, `contactname`, `contactphone`, `companyfax`, `contactemail`, `since`, `companysize`, `income`, `description`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `created`, `modified`, `hits`, `metadescription`, `metakeywords`, `status`, `packageid`, `paymenthistoryid`, `isgoldcompany`, `startgolddate`, `endgolddate`, `endfeatureddate`, `isfeaturedcompany`, `startfeatureddate`, `params`, `serverstatus`, `serverid`, `facebook`, `twitter`, `googleplus`, `linkedin`) VALUES( " . 
                    $employer_id . ", 25, 'Sample Company 5', 'sample-company-5', 'http://www.sample5.com', '', -1, NULL, NULL, -1, NULL, NULL, -1, NULL, NULL, 'Sample Company 5', '', NULL, 'sample5@sample5.com', '2010-06-16 00:00:00', '', '', ' problem and advises its customers on how their business growth aims can be achieved by the implementation of a specific and research-based software solution', '0', NULL, NULL, $cityid8, '', 'some streest in some city', '', '".$curdate."', NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, '".$curdate."', '".$thirdydaydate."', 1, NULL, NULL, NULL, 0, 'https://www.facebook.com/', 'https://www.twitter.com/', 'https://www.gplus.com/', 'https://www.linkedin.com/');";
                jsjobsdb::query($insert_company);
                $companyid8 = jsjobs::$_db->insert_id;

                $insert_companycity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_companycities` (`companyid`, `cityid`) 
                VALUES( " . $companyid8 . ", " . $cityid8 . ");";
                jsjobsdb::query($insert_companycity);
                //

                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`, `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid . ", 'PHP Developer', 'php-developer', '13', 1, 2, '', '2', 0, '<p><strong>Responsibilities</strong></p>\r\n<p> </p>\r\n<ul>\r\n<li>Work closely with Project Managers and other members of the Development Team to both develop detailed specification documents with clear project deliverables and timelines, and to ensure timely completion of deliverables.</li>\r\n<li>Produce project estimates during sales process, including expertise required, total number of people required, total number of development hours required, etc.</li>\r\n<li>Attend client meetings during the sales process and during development.</li>\r\n<li>Work with clients and Project Managers to build and refine graphic designs for websites. Must have strong skills in Photoshop, Fireworks, or equivalent application(s).</li>\r\n<li>Convert raw images and layouts from a graphic designer into CSS/XHTML themes.</li>\r\n<li>Determine appropriate architecture, and other technical solutions, and make relevant recommendations to clients.</li>\r\n<li>Communicate to the Project Manager with efficiency and accuracy any progress and/or delays. Engage in outside-the-box thinking to provide high value-of-service to clients.</li>\r\n<li>Alert colleagues to emerging technologies or applications and the opportunities to integrate them into operations and activities.</li>\r\n<li>Be actively involved in and contribute regularly to the development community of the CMS of your choice.</li>\r\n<li>Develop innovative, reusable Web-based tools for activism and community building.</li>\r\n</ul>\r\n<p> </p>', '', '<p><strong>Required Skills</strong></p>\r\n<ul>\r\n<li>BS in computer science or a related field, or significant equivalent experience</li>\r\n<li>3 years minimum experience with HTML/XHTML and CSS</li>\r\n<li>2 years minimum Web programming experience, including PHP, ASP or JSP</li>\r\n<li>1 year minimum experience working with relational database systems such as MySQL, MSSQL or Oracle and a good working knowledge of SQL</li>\r\n<li>Development experience using extensible web authoring tools</li>\r\n<li>Experience developing and implementing open source software projects</li>\r\n<li>Self-starter with strong self-management skills</li>\r\n<li>Ability to organize and manage multiple priorities</li>\r\n</ul>', NULL, '', '', '', '', $cityid, '', '', '', '', '', '', '', 0, 2, '', '3 month', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 2, 0, '" . $curdate . "', '".$thirdydaydate."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, 'Bs(cs)', 4, 1, 5, 5, 5, 1, '', '126', 1, 4, 6, 1, 1, 0, NULL, NULL, 0, 50, 1, 'mnBjp8kLQ', '74.3833333', '31.5166667', 0, '". $curdate ."', '". $thirdydaydate ."','0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid);
                
                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`,  `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid1 . ", 'Android Developer', 'games-developer', '13', 1, 2, '', '2', 0, '<p>Games developers are involved in the creation and production of games for personal computers, games consoles, social/online games, arcade games, tablets, mobile phones and other hand held devices. Their work involves either design (including art and animation) or programming.</p>\r\n<p>Games development is a fast-moving, multi-billion pound industry. The making of a game from concept to finished product can take up to three years and involve teams of up to 200 professionals.</p>\r\n<p>There are many stages, including creating and designing a game''s look and how it plays, animating characters and objects, creating audio, programming, localisation, testing and producing.</p>\r\n<p>The games developer job title covers a broad area of work and there are many specialisms within the industry. These include:</p>\r\n<ul>\r\n<li>quality assurance tester;</li>\r\n<li>programmer, with various specialisms such as network, engine, toolchain and artificial intelligence;</li>\r\n<li>audio engineer;</li>\r\n<li>artist, including concept artist, animator and 3D modeller;</li>\r\n<li>producer;</li>\r\n<li>editor;</li>\r\n<li>designer;</li>\r\n<li>special effects technician.</li>\r\n</ul>', '', '<h2>Typical work activities</h2>\r\n<p>Responsibilities vary depending on your specialist area but may include:</p>\r\n<ul>\r\n<li>developing designs and/or initial concept designs for games including game play;</li>\r\n<li>generating game scripts and storyboards;</li>\r\n<li>creating the visual aspects of the game at the concept stage;</li>\r\n<li>using 2D or 3D modelling and animation software, such as Maya, at the production stage;</li>\r\n<li>producing the audio features of the game, such as the character voices, music and sound effects;</li>\r\n<li>programming the game using programming languages such as C++;</li>\r\n<li>quality testing games in a systematic and thorough way to find problems or bugs and recording precisely where the problem was discovered;</li>\r\n<li>solving complex technical problems that occur within the game''s production;</li>\r\n<li>disseminating knowledge to colleagues, clients, publishers and gamers;</li>\r\n<li>understanding complex written information, ideas and instructions;</li>\r\n<li>working closely with team members to meet the needs of a project;</li>\r\n<li>planning resources and managing both the team and the process;</li>\r\n<li>performing effectively under pressure and meeting deadlines to ensure the game is completed on time.</li>\r\n</ul>', NULL, '', '', '', '', $cityid1, '', '', '', '', '', '', '', 0, 3, '', '', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 1, 0, '" . $curdate . "', '". $thirdydaydate ."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, 'Bs(cs)', 4, 1, 5, 5, 5, 1, '', '', 0, 4, 4, 4, 4, 0, NULL, NULL, 0, 50, 1, 'fVT7bgDmL', '74.3833333', '31.5166667', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '". $curdate ."', '". $thirdydaydate ."', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid1);


                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`, `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid2 . ", 'Accountant', 'accountant', '13', 1, 2, '', '2', 0, '<p><strong>Accountant Job </strong><strong>Duties:</strong></p>\r\n<ul>\r\n<li>Prepares asset, liability, and capital account entries by compiling and analyzing account information.</li>\r\n<li>Documents financial transactions by entering account information.</li>\r\n<li>Recommends financial actions by analyzing accounting options.</li>\r\n<li>Summarizes current financial status by collecting information; preparing balance sheet, profit and loss statement, and other reports.</li>\r\n<li>Substantiates financial transactions by auditing documents.</li>\r\n<li>Maintains accounting controls by preparing and recommending policies and procedures.</li>\r\n<li>Guides accounting clerical staff by coordinating activities and answering questions.</li>\r\n<li>Reconciles financial discrepancies by collecting and analyzing account information.</li>\r\n<li>Secures financial information by completing data base backups.</li>\r\n<li>Maintains financial security by following internal controls.</li>\r\n<li>Prepares payments by verifying documentation, and requesting disbursements.</li>\r\n<li>Answers accounting procedure questions by researching and interpreting accounting policy and regulations.</li>\r\n<li>Complies with federal, state, and local financial legal requirements by studying existing and new legislation, enforcing adherence to requirements, and advising management on needed actions.</li>\r\n<li>Prepares special financial reports by collecting, analyzing, and summarizing account information and trends.</li>\r\n<li>Maintains customer confidence and protects operations by keeping financial information confidential.</li>\r\n<li>Maintains professional and technical knowledge by attending educational workshops; reviewing professional publications; establishing personal networks; participating in professional societies.</li>\r\n<li>Accomplishes the result by performing the duty.</li>\r\n<li>Contributes to team effort by accomplishing related results as needed.</li>\r\n</ul>', '', '<p>Accounting, Corporate Finance, Reporting Skills, Attention to Detail, Deadline-Oriented, Reporting Research Results, SFAS Rules, Confidentiality, Time Management, Data Entry Management, General Math Skills</p>', NULL, '', '', '', '', $cityid2, '', '', '', '', '', '', '', 0, 1, '', '', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 1, 0, '" . $curdate . "', '". $thirdydaydate ."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, 'CA', 6, 1, 5, 5, 5, 1, '', '126', 1, 4, 4, 7, 7, 0, NULL, NULL, 0, 0, 1, 'pGLYCBVF7', '74.3833333', '31.5166667', 1, '". $curdate ."', '". $thirdydaydate ."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid2);

                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`,  `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid3 . ", 'Senior Software Engineer', 'senior-software-engineer', '13', 1, 2, '', '2', 0, '<p>You might be responsible for the replacement of a whole system based on the specifications provided by an IT analyst but often you''ll work with ''off the shelf'' software, modifying it and integrating it into the existing network. The skill in this is creating the code to link the systems together.</p>\r\n<p>You''ll also be responsible for:<br /><br /></p>\r\n<ul>\r\n<li>Reviewing current systems</li>\r\n<li>Presenting ideas for system improvements, including cost proposals</li>\r\n<li>Working closely with analysts, designers and staff</li>\r\n<li>Producing detailed specifications and writing the programme codes</li>\r\n<li>Testing the product in controlled, real situations before going live</li>\r\n<li>Preparation of training manuals for users</li>\r\n<li>Maintaining the systems once they are up and running</li>\r\n</ul>', '', '<p>Most employers will want you to have a BTEC HND at the very least to get a foot in the door, however some companies runthat will consider candidates with AS Levels.</p>\r\n<p>If you''ve got a degree it will , especially if it''s in an IT, science or maths based subject.</p>\r\n<p>If you''ve got a non-IT degree you might still be able to apply to a graduate trainee scheme, or you can take a postgraduate conversion course to get your CV up to scratch.</p>\r\n<p>It is possible to move into software development from another profession. If this is you, play-up your business and IT experience and be prepared to take some IT-based courses if necessary.</p>\r\n<p>The courses you''ll find open most doors are of course the programming qualifications such as:<br /><br /></p>\r\n<ul>\r\n<li>Java</li>\r\n<li>C++</li>\r\n<li>Smalltalk</li>\r\n<li>Visual Basic</li>\r\n<li>Oracle</li>\r\n<li>Linux</li>\r\n<li>NET</li>\r\n</ul>\r\n<p><br />Keeping up with the rapid pace of change is vital in this profession, so you should benefit from a good solid training programme, especially if you work for a larger organisation.</p>\r\n<p>You''ll learn from more senior programmers and will go on external courses to keep your professional skills up to date.Your training should focus on programming, systems analysis and software from recognised providers including the British Computer Society, e-skills, the Institute of Analysts and Programmers and the Institute for the Management of Information Systems.</p>\r\n<p>All the software vendors, including Microsoft and Sun run accredited training too.If you are self-employed then you should invest in training to keep your skills.</p>', NULL, '', '', '', '', $cityid3, '', '', '', '', '', '', '', 0, 1, '', '', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 2, 0, '" . $curdate . "', '". $thirdydaydate ."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, 'Bs(cs)', 6, 1, 12, 5, 5, 1, '', '126', 0, 4, 4, 8, 8, 0, NULL, NULL, 0, 50, 1, 'JnDLpkZB8', '74.3833333', '31.5166667', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '". $curdate ."', '". $thirdydaydate ."', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid3);

                //
                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`, `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid4 . ", 'Web Designer', 'web-designer', '13', 1, 2, '', '2', 0, '<p>An associate''s degree program related to web design, such as an Associate of Applied Science in Web Graphic Design, provides a student with a foundation in the design and technical aspects of creating a website. Students learn web design skills and build professional portfolios that highlight their skills and abilities. Common topics include:</p>\r\n<ul>\r\n<li>Fundamentals of design imaging</li>\r\n<li>Basic web design</li>\r\n<li>Animation</li>\r\n<li>Multimedia design</li>\r\n<li>Content management</li>\r\n<li>Editing for video and audio</li>\r\n<li>Multimedia programming and technology</li>\r\n</ul>\r\n<p>A bachelor''s degree program in multimedia or web design allows students to learn advanced skills needed for professional web design. Students develop artistic and creative abilities in addition to technical skills. Degree programs, such as a Bachelor of Science in Web Design and Interactive Media, cover:</p>\r\n<ul>\r\n<li>Databases</li>\r\n<li>Webpage scripting</li>\r\n<li>Programming</li>\r\n<li>Digital imaging</li>\r\n<li>Multimedia design</li>\r\n<li>Web development</li>\r\n</ul>', '', '<ul>\r\n<li>Writing and editing content</li>\r\n<li>Designing webpage layout</li>\r\n<li>Determining technical requirements</li>\r\n<li>Updating websites</li>\r\n<li>Creating back up files</li>\r\n<li>Solving code problems</li>\r\n</ul>', NULL, '', '', '', '', $cityid4, '', '', '', '', '', '', '', 0, 1, '', '', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 0, 0, '" . $curdate . "', '". $thirdydaydate ."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, '', 3, 1, 5, 5, 5, 1, '', '', 0, 4, 4, 1, 1, 0, NULL, NULL, 0, 50, 1, 'JZH6Nz2cm', '73.06137450000006', '33.697006', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '". $curdate ."', '". $thirdydaydate ."', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid4);

                //
                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`, `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid5 . ", 'WP Developer', 'wp-developer', '13', 1, 2, '', '2', 0, '<p>An associate''s degree program related to web design, such as an Associate of Applied Science in Web Graphic Design, provides a student with a foundation in the design and technical aspects of creating a website. Students learn web design skills and build professional portfolios that highlight their skills and abilities. Common topics include:</p>\r\n<ul>\r\n<li>Fundamentals of design imaging</li>\r\n<li>Basic web design</li>\r\n<li>Animation</li>\r\n<li>Multimedia design</li>\r\n<li>Content management</li>\r\n<li>Editing for video and audio</li>\r\n<li>Multimedia programming and technology</li>\r\n</ul>\r\n<p>A bachelor''s degree program in multimedia or web design allows students to learn advanced skills needed for professional web design. Students develop artistic and creative abilities in addition to technical skills. Degree programs, such as a Bachelor of Science in Web Design and Interactive Media, cover:</p>\r\n<ul>\r\n<li>Databases</li>\r\n<li>Webpage scripting</li>\r\n<li>Programming</li>\r\n<li>Digital imaging</li>\r\n<li>Multimedia design</li>\r\n<li>Web development</li>\r\n</ul>', '', '<ul>\r\n<li>Writing and editing content</li>\r\n<li>Designing webpage layout</li>\r\n<li>Determining technical requirements</li>\r\n<li>Updating websites</li>\r\n<li>Creating back up files</li>\r\n<li>Solving code problems</li>\r\n</ul>', NULL, '', '', '', '', $cityid5, '', '', '', '', '', '', '', 0, 1, '', '', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 0, 0, '" . $curdate . "', '". $thirdydaydate ."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, '', 3, 1, 5, 5, 5, 1, '', '', 0, 4, 4, 1, 1, 0, NULL, NULL, 0, 50, 1, 'JZH6Nz2cm', '73.06137450000006', '33.697006', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '". $curdate ."', '". $thirdydaydate ."', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid5);

                //
                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`, `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid6 . ", 'Senior Web Developer', 'senior-web-developer', '13', 1, 2, '', '2', 0, '<p>An associate''s degree program related to web design, such as an Associate of Applied Science in Web Graphic Design, provides a student with a foundation in the design and technical aspects of creating a website. Students learn web design skills and build professional portfolios that highlight their skills and abilities. Common topics include:</p>\r\n<ul>\r\n<li>Fundamentals of design imaging</li>\r\n<li>Basic web design</li>\r\n<li>Animation</li>\r\n<li>Multimedia design</li>\r\n<li>Content management</li>\r\n<li>Editing for video and audio</li>\r\n<li>Multimedia programming and technology</li>\r\n</ul>\r\n<p>A bachelor''s degree program in multimedia or web design allows students to learn advanced skills needed for professional web design. Students develop artistic and creative abilities in addition to technical skills. Degree programs, such as a Bachelor of Science in Web Design and Interactive Media, cover:</p>\r\n<ul>\r\n<li>Databases</li>\r\n<li>Webpage scripting</li>\r\n<li>Programming</li>\r\n<li>Digital imaging</li>\r\n<li>Multimedia design</li>\r\n<li>Web development</li>\r\n</ul>', '', '<ul>\r\n<li>Writing and editing content</li>\r\n<li>Designing webpage layout</li>\r\n<li>Determining technical requirements</li>\r\n<li>Updating websites</li>\r\n<li>Creating back up files</li>\r\n<li>Solving code problems</li>\r\n</ul>', NULL, '', '', '', '', $cityid6, '', '', '', '', '', '', '', 0, 1, '', '', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 0, 0, '" . $curdate . "', '". $thirdydaydate ."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, '', 3, 1, 5, 5, 5, 1, '', '', 0, 4, 4, 1, 1, 0, NULL, NULL, 0, 50, 1, 'JZH6Nz2cm', '73.06137450000006', '33.697006', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '". $curdate ."', '". $thirdydaydate ."', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid6);

                //
                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`, `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid7 . ", 'Junior PHP Developer', 'junior-php-developer', '13', 1, 2, '', '2', 0, '<p><strong>Responsibilities</strong></p>\r\n<p> </p>\r\n<ul>\r\n<li>Work closely with Project Managers and other members of the Development Team to both develop detailed specification documents with clear project deliverables and timelines, and to ensure timely completion of deliverables.</li>\r\n<li>Produce project estimates during sales process, including expertise required, total number of people required, total number of development hours required, etc.</li>\r\n<li>Attend client meetings during the sales process and during development.</li>\r\n<li>Work with clients and Project Managers to build and refine graphic designs for websites. Must have strong skills in Photoshop, Fireworks, or equivalent application(s).</li>\r\n<li>Convert raw images and layouts from a graphic designer into CSS/XHTML themes.</li>\r\n<li>Determine appropriate architecture, and other technical solutions, and make relevant recommendations to clients.</li>\r\n<li>Communicate to the Project Manager with efficiency and accuracy any progress and/or delays. Engage in outside-the-box thinking to provide high value-of-service to clients.</li>\r\n<li>Alert colleagues to emerging technologies or applications and the opportunities to integrate them into operations and activities.</li>\r\n<li>Be actively involved in and contribute regularly to the development community of the CMS of your choice.</li>\r\n<li>Develop innovative, reusable Web-based tools for activism and community building.</li>\r\n</ul>\r\n<p> </p>', '', '<p><strong>Required Skills</strong></p>\r\n<ul>\r\n<li>BS in computer science or a related field, or significant equivalent experience</li>\r\n<li>3 years minimum experience with HTML/XHTML and CSS</li>\r\n<li>2 years minimum Web programming experience, including PHP, ASP or JSP</li>\r\n<li>1 year minimum experience working with relational database systems such as MySQL, MSSQL or Oracle and a good working knowledge of SQL</li>\r\n<li>Development experience using extensible web authoring tools</li>\r\n<li>Experience developing and implementing open source software projects</li>\r\n<li>Self-starter with strong self-management skills</li>\r\n<li>Ability to organize and manage multiple priorities</li>\r\n</ul>', NULL, '', '', '', '', $cityid7, '', '', '', '', '', '', '', 0, 2, '', '3 month', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 2, 0, '" . $curdate . "', '".$thirdydaydate."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, 'Bs(cs)', 4, 1, 5, 5, 5, 1, '', '126', 1, 4, 6, 1, 1, 0, NULL, NULL, 0, 50, 1, 'mnBjp8kLQ', '74.3833333', '31.5166667', 0, '". $curdate ."', '". $thirdydaydate ."','0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid7);
                //            
                $insert_job = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobs` ( `uid`, `companyid`, `title`, `alias`, `jobcategory`, `jobtype`, `jobstatus`, `jobsalaryrange`, `salaryrangetype`, `hidesalaryrange`, `description`, `qualifications`, `prefferdskills`, `applyinfo`, `company`, `country`, `state`, `county`, `city`, `zipcode`, `address1`, `address2`, `companyurl`, `contactname`, `contactphone`, `contactemail`, `showcontact`, `noofjobs`, `reference`, `duration`, `heighestfinisheducation`, `created`, `created_by`, `modified`, `modified_by`, `hits`, `experience`, `startpublishing`, `stoppublishing`, `departmentid`, `shift`, `sendemail`, `metadescription`, `metakeywords`, `agreement`, `ordering`, `aboutjobfile`, `status`, `educationminimax`, `educationid`, `mineducationrange`, `maxeducationrange`, `iseducationminimax`, `degreetitle`, `careerlevel`, `experienceminimax`, `experienceid`, `minexperiencerange`, `maxexperiencerange`, `isexperienceminimax`, `experiencetext`, `workpermit`, `requiredtravel`, `agefrom`, `ageto`, `salaryrangefrom`, `salaryrangeto`, `gender`,  `map`, `packageid`, `paymenthistoryid`, `subcategoryid`, `currencyid`, `jobid`, `longitude`, `latitude`, `isgoldjob`, `startgolddate`, `endgolddate`, `startfeatureddate`, `endfeatureddate`, `isfeaturedjob`, `raf_gender`, `raf_degreelevel`, `raf_experience`, `raf_age`, `raf_education`, `raf_category`, `raf_subcategory`, `raf_location`, `jobapplylink`, `joblink`, `params`, `serverstatus`, `serverid`, `tags`) 
                    VALUES(" . $employer_id . ", " . $companyid8 . ", 'Junior Android Developer', 'junior-games-developer', '13', 1, 2, '', '2', 0, '<p>Games developers are involved in the creation and production of games for personal computers, games consoles, social/online games, arcade games, tablets, mobile phones and other hand held devices. Their work involves either design (including art and animation) or programming.</p>\r\n<p>Games development is a fast-moving, multi-billion pound industry. The making of a game from concept to finished product can take up to three years and involve teams of up to 200 professionals.</p>\r\n<p>There are many stages, including creating and designing a game''s look and how it plays, animating characters and objects, creating audio, programming, localisation, testing and producing.</p>\r\n<p>The games developer job title covers a broad area of work and there are many specialisms within the industry. These include:</p>\r\n<ul>\r\n<li>quality assurance tester;</li>\r\n<li>programmer, with various specialisms such as network, engine, toolchain and artificial intelligence;</li>\r\n<li>audio engineer;</li>\r\n<li>artist, including concept artist, animator and 3D modeller;</li>\r\n<li>producer;</li>\r\n<li>editor;</li>\r\n<li>designer;</li>\r\n<li>special effects technician.</li>\r\n</ul>', '', '<h2>Typical work activities</h2>\r\n<p>Responsibilities vary depending on your specialist area but may include:</p>\r\n<ul>\r\n<li>developing designs and/or initial concept designs for games including game play;</li>\r\n<li>generating game scripts and storyboards;</li>\r\n<li>creating the visual aspects of the game at the concept stage;</li>\r\n<li>using 2D or 3D modelling and animation software, such as Maya, at the production stage;</li>\r\n<li>producing the audio features of the game, such as the character voices, music and sound effects;</li>\r\n<li>programming the game using programming languages such as C++;</li>\r\n<li>quality testing games in a systematic and thorough way to find problems or bugs and recording precisely where the problem was discovered;</li>\r\n<li>solving complex technical problems that occur within the game''s production;</li>\r\n<li>disseminating knowledge to colleagues, clients, publishers and gamers;</li>\r\n<li>understanding complex written information, ideas and instructions;</li>\r\n<li>working closely with team members to meet the needs of a project;</li>\r\n<li>planning resources and managing both the team and the process;</li>\r\n<li>performing effectively under pressure and meeting deadlines to ensure the game is completed on time.</li>\r\n</ul>', NULL, '', '', '', '', $cityid8, '', '', '', '', '', '', '', 0, 3, '', '', '', '" . $curdate . "', 0, '0000-00-00 00:00:00', 0, 1, 0, '" . $curdate . "', '". $thirdydaydate ."', NULL, '1', 0, '', '', '', 0, NULL, 1, 1, 1, 1, 1, 1, 'Bs(cs)', 4, 1, 5, 5, 5, 1, '', '', 0, 4, 4, 4, 4, 0, NULL, NULL, 0, 50, 1, 'fVT7bgDmL', '74.3833333', '31.5166667', 0, '". $curdate ."', '". $thirdydaydate ."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', NULL, 0, '');";
                jsjobsdb::query($insert_job);
                $jobid = jsjobs::$_db->insert_id;
                $insetjobcities = $this->insertJobCities($jobid, $cityid8);
            }

            // resumes
        
            if($jobseeker_id > 0){
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample Data', 'sanmple data', 'sample-data', 'First name ', 'Last name', 'middle name ', '1', 'sampledata@info.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeemployers` ( `resumeid`, `employer`, `employer_position`, `employer_resp`, `employer_pay_upon_leaving`, `employer_supervisor`, `employer_from_date`, `employer_to_date`, `employer_leave_reason`, `employer_city`,`employer_zip`,`employer_phone`,`employer_address`,`created`,`last_modified`,`serverstatus`,`serverid`)
                                VALUES (" . $resumeid . ", 'Sample data', 'Software engineer','Project management', 65000, 'Sample', '".$date."','".$date."', 'No reason', 39882, 52250, '123456789', 'Sample address', '".$date."','0000-00-00 00:00:00',NULL,NULL);";
                jsjobsdb::query($resume_query);
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` ( `resumeid`, `institute`, `institute_city`, `institute_address`, `institute_certificate_name`, `institute_study_area`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Sample data', 39882,'Sample data', 'Sample', 'Sample','".$date."','0000-00-00 00:00:00',NULL,NULL);";
                jsjobsdb::query($resume_query);
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumelanguages` ( `resumeid`, `language`, `language_reading`, `language_writing`, `language_understanding`, `language_where_learned`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Sample data', 'Good','Good', 'Good', 'Sample place','".$date."','0000-00-00 00:00:00',NULL,NULL);";
                jsjobsdb::query($resume_query);
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumereferences` ( `resumeid`, `reference`, `reference_name`, `reference_city`, `reference_zipcode`, `reference_address`, `reference_phone`, `reference_relation`, `reference_years`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Sample data', 'Sample name', 39882, 52250, 'Sample address', '123456789', 'Sample relation', 'Sample years','".$date."','0000-00-00 00:00:00',NULL,NULL);";
                jsjobsdb::query($resume_query);
                
                // other resumes
      
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample Resume', 'sample resume', 'sample-resume', 'Jhon ', 'Doe', '-', '1', 'sample@resume.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid1, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);
                //
      
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample Resume 1', 'sample resume 1', 'sample-resume-1', 'Jane ', 'Doe', '-', '1', 'sample1@resume1.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid2, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);

                //
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample Resume 2', 'sample resume 2', 'sample-resume-2', 'First ', 'Last', '-', '1', 'sample3@resume3.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid3, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);
                //
      
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample Data', 'sample data 1', 'sample-data-1', 'Sample name ', 'Sample name', '-', '1', 'sample@email.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid4, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);
                //
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample Resume 4', 'sample resume 4', 'sample-resume-4', 'First-4', 'Last-4', '-', '1', 'sample4@resume4.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid5, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);
                //
      
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample Data 2', 'sample data 2', 'sample-data-2', 'Sample data name ', 'Sample data name', '-', '1', 'sampledata@email.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid6, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);
                //
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample 5', 'sample 4', 'sample-5', 'Sample first', 'Sample last', '-', '1', 'sample@resume.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;
                

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid7, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);
                //
                $resume_query = "INSERT INTO `".jsjobs::$_db->prefix."js_job_resume` ( `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrangestart`, `jobsalaryrangeend`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desiredsalarystart`, `desiredsalaryend`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `experienceid`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `videotype`, `video`, `isgoldresume`, `startgolddate`, `startfeatureddate`, `endgolddate`, `endfeatureddate`, `isfeaturedresume`, `serverstatus`, `serverid`, `tags`, `facebook`, `twitter`, `googleplus`, `linkedin`) 
                                VALUES ( ".$jobseeker_id.", '".$date."', '0000-00-00 00:00:00', 0, 0, 'Sample Resume 6', 'sample resume 6', 'sample-resume-6', 'Sample data name ', 'Sample data name', '-', '1', 'sampledata@email.com', '123456789', '123456789', '123456789', '1', 1, 1, '', 1, 1, 1, 1, 1, '1', 1, '<p>quick brown fox jumps over the lazy dog.</p>', '0000-00-00 00:00:00', 1, 1, 1, 1, '', '', '', 1, 'this is some text that i have written in skills section of reusme.', 0, '', '', 0, 0, 2, 0, '0000-00-00 00:00:00', 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, '', 0, '', '', '', '', '');";
                jsjobsdb::query($resume_query);
                $resumeid = jsjobs::$_db->insert_id;

                $resume_query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` ( `resumeid`, `address`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
                                VALUES (" . $resumeid . ", 'Gujranwala', $cityid8, 52250, 74.3833333, 31.5166667, '".$date."', '0000-00-00 00:00:00', NULL  , NULL);";
                jsjobsdb::query($resume_query);
                //

                $jobs = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE title='Web Designer' OR title='senior software engineer' OR title='Accountant' OR title='Games Developer' OR title='Php Developer';";
                $jobids = jsjobsdb::get_results($jobs);
             

                foreach ($jobids AS $jobid) {
                    $appliedjobs = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobapply` (`jobid`, `uid`, `cvid`, `apply_date`, `resumeview`, `comments`, `coverletterid`, `action_status`, `serverstatus`, `serverid`)
                        VALUES (" . $jobid->id . "," . $jobseeker_id . "," . $resumeid . ",'" . $date . "',0,NULL,NULL,1,NULL,NULL)";
                    jsjobsdb::query($appliedjobs);
                }
            }
            if ($temp_data == 1) {
                $product_type = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
                if($product_type == 'pro'){
                    $flag = 'p';
                }else{
                    $flag = 'f';
                }
                if(jsjobs::$theme_chk == 2){
                    $this->installSampleDataTemplateJobHub($flag);
                }
           }
            
        }
        return true;
    }

    function installSampleDataTemplateJobHub($flag) {
        $product_type = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
        if($flag == 'p'){
            return 0;
        }elseif($flag == 'f'){
            if($product_type != 'free'){
                return 0;
            }
            $pro  = 0;
        }elseif($flag == 'ftp'){
                return 0;
        }
        // Check for the rev slider
        $wp_upload_dir = wp_upload_dir();
        if (file_exists(ABSPATH . 'wp-admin/includes/class-pclzip.php')) {
            require_once ABSPATH . 'wp-admin/includes/class-pclzip.php';
            $archive = new PclZip(get_template_directory() . "/framework/plugins/sample-data.zip");
            $v_list = $archive->extract($wp_upload_dir["basedir"]);
        }
        if( ! function_exists("__update_post_meta")){
            function __update_post_meta( $post_id, $field_name, $value = "" ){
                if ( empty( $value ) OR ! $value ){
                    delete_post_meta( $post_id, $field_name );
                }elseif ( ! get_post_meta( $post_id, $field_name ) ){
                    add_post_meta( $post_id, $field_name, $value );
                }else{
                    update_post_meta( $post_id, $field_name, $value );
                }
            }
        }
        if( ! function_exists("uploadPostFeatureImage")){
            function uploadPostFeatureImage($filename,$parent_post_id){
                // Check the type of file. We"ll use this as the "post_mime_type".
                $filetype = wp_check_filetype( jsjobslib::jsjobs_basename( $filename ), null );
                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();
                // Prepare an array of post data for the attachment.
                $attachment = array(
                    "guid"           => $wp_upload_dir["url"] . "/" . jsjobslib::jsjobs_basename( $filename ), 
                    "post_mime_type" => $filetype["type"],
                    "post_title"     => jsjobslib::jsjobs_preg_replace( "/\.[^.]+$/", "", jsjobslib::jsjobs_basename( $filename ) ),
                    "post_content"   => "",
                    "post_status"    => "inherit"
                );
                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once( ABSPATH . "wp-admin/includes/image.php" );
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                set_post_thumbnail( $parent_post_id, $attach_id );
            }
        }
        $jh_pages = array();
        // Home
        $new_page_title = "Home";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["home"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["home"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["home"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["home"], "jh_show_header", 2);
        // Home 1
        $new_page_title = "Home 1";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["home1"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["home1"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["home1"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["home1"], "jh_show_header", 2);
        // home 2
        $new_page_title = "Home 2";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["home2"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["home2"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["home2"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["home2"], "jh_show_header", 2);

        $wp_upload_dir = wp_upload_dir();
        /* end of homepages */
        // Price table
        $new_page_title = "Pricing Table";
        $new_page_content = '<div class="jsjb-jh-pricicing-box-wrap"><div class="jsjb-jh-price-box-row"><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-1"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-2"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-3"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-4"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-5"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-6"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-7"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-8"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div><div class="col-md-4"><div class="jsjb-jh-price-box jsjb-jh-pkg-color-9"><div class="jsjb-jh-price-box-heading"><h3 class="jsjb-jh-price-box-heading-txt">Basic Package</h3><h5 class="jsjb-jh-price-box-title">Basic Package</h5></div><ul class="list-group jsjb-jh-price-box-crdts-list"> <li>Credits</li><li>50,000</li><li>Expire In 60 Days</li></ul><div class="jsjb-jh-price-box-price-wrp">$200</div><a class="jsjb-jh-price-box-bn-btn-txt" title="Buy Now" href="#">Buy Now</a></div></div></div></div>';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["pricing_table"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["pricing_table"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["pricing_table"], "jh_show_header", 1);
        update_post_meta($jh_pages["pricing_table"], "_wp_page_template", $new_page_template);
        // job hub pages
        $page_array[1] = "Jobseeker Control Panel";
        $page_array[2] = "Newest Jobs";
        $page_array[3] = "My Applied Jobs";
        $page_array[4] = "My Resume";
        $page_array[5] = "Search Job";
        $page_array[6] = "Jobs By Category";
        $page_array[8] = "Add Resume";
        $page_array[11] = "All Companies";
        
        $page_array[18] = "Jobseeker Stats";
        $page_array[19] = "Employer Control Panel";
        $page_array[20] = "My Jobs";
        $page_array[21] = "Add Job";
        $page_array[22] = "Resume Search";
        $page_array[23] = "Resume By Category";
        $page_array[24] = "My Companies";
        $page_array[25] = "Add Company";
        $page_array[32] = "Employer Stats";
        $page_array[33] = "Login";
        $page_array[34] = "Employer Registration";
        $page_array[35] = "Jobseeker Registration";
        $page_array[36] = "Thank You";
        foreach ($page_array as $key => $value) {
            // $value_string = jsjobslib::jsjobs_strtolower($value);
            // $value_string = sanitize_title($value_string);
            $value_string = jsjobslib::jsjobs_strtolower($value);
            $value_string = jsjobslib::jsjobs_str_replace(" ","_",$value_string);
            $new_page_title = $value;
            $new_page_content = '[vc_row][vc_column][jh_job_hub_pages page page="'.$key.'"][/vc_column][/vc_row]';
            $new_page_template = "templates/template-fullwidth.php";
            $page_check = get_page_by_title($new_page_title);
            $new_page = array(
                    "post_type" => "page",
                    "post_title" => $new_page_title,
                    "post_content" => $new_page_content,
                    "post_status" => "publish",
                    "post_author" => 1,
                    "post_parent" => 0,
            );
            if(!isset($page_check->ID)){
                $jh_pages[$value_string] = wp_insert_post($new_page);
            }else{
                $new_page["post_title"] = "Job Hub ".$new_page_title;
                $jh_pages[$value_string] = wp_insert_post($new_page);
            }
            update_post_meta($jh_pages[$value_string], "jh_show_header", 1);
            update_post_meta($jh_pages[$value_string], "_wp_page_template", $new_page_template);
        }
        // job hub pages end
        // News & Rumors
        $new_page_title = "News & Rumors";
        $new_page_content = '[vc_row][vc_column][jh_news_and_rumors style="3" posts_per_page="2"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-news_and_rumors.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["news_and_rumors"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["news_and_rumors"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["news_and_rumors"], "jh_show_header", 1);
        update_post_meta($jh_pages["news_and_rumors"], "_wp_page_template", $new_page_template);
        // FAQ
        $new_page_title = "FAQ";
        $new_page_content = '[vc_row][vc_column][vc_tta_accordion active_section="1" el_class="jsjb-jh-faq-wrap"][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754286724-c9ed48ed-c7f7" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754537433-695717d6-6bfc" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754536332-73927004-0a28" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754535606-7dffd7a9-2119" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754534808-bb9dfb79-6d18" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754534095-ec21098b-4397" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754529612-21aedc15-ddd9" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754528056-93a9c873-2efd" el_class="jsjb-jh-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][/vc_tta_accordion][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["faq"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["faq"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["faq"], "jh_show_header", 1);
        update_post_meta($jh_pages["faq"], "_wp_page_template", $new_page_template);
        // Our Team
        $new_page_title = "Our Team";
        $new_page_content = '[vc_row][vc_column][jh_team_memebers per_row="3" posts_per_page="4" style="3"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["ourteam"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["ourteam"] = wp_insert_post($new_page);
        }
        update_post_meta($jh_pages["ourteam"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["ourteam"], "jh_show_header", 1);
        // Contact Us
        $new_page_title = "Contact Us";
        $new_page_content = "";
        $new_page_template = "templates/template-contactus.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $jh_pages["contact_us"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["contact_us"] = wp_insert_post($new_page);        
        }
        update_post_meta($jh_pages["contact_us"], "_wp_page_template", $new_page_template);
        update_post_meta($jh_pages["contact_us"], "jh_show_header", 1);
        // Blog Page
        $new_page_title = "Blog";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );

        if(!isset($page_check->ID)){
            $jh_pages["blog"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Hub ".$new_page_title;
            $jh_pages["blog"] = wp_insert_post($new_page);
        }
        update_option("page_for_posts", $jh_pages["blog"]);
        // Update home page contents
        //Home
        $new_page_content = '[vc_row][vc_column][jh_job_search jsjobspageid="'.$jh_pages["newest_jobs"].'" style="7" subtitle="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text"][/vc_column][/vc_row][vc_row][vc_column][jh_feature_3box][/vc_column][/vc_row][vc_row][vc_column][jh_jobs_category jsjobspageid="'.$jh_pages["newest_jobs"].'" style="2" category="1" category1="1" category2="1" category3="1" category4="1" category5="1" category6="1" category7="1"][/vc_column][/vc_row][vc_row][vc_column][jh_5count_box style="2" count1="587" count2="146" count3="919" count4="796"][/vc_column][/vc_row][vc_row][vc_column][jh_jobs jsjobspageid="'.$jh_pages["newest_jobs"].'" title1="Latest Jobs" noofjobs="6" style="2"][/vc_column][/vc_row][vc_row][vc_column][jh_job_hub_custom_link jsjobspageid="'.$jh_pages["newest_jobs"].'" style="6" title="REGISTER AS EMPLOYER" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut veniam ." description1="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut veniam ."][/vc_column][/vc_row][vc_row][vc_column][jh_price_tables style="2" posts_per_page="3"][/vc_column][/vc_row][vc_row][vc_column][jh_companies jsjobspageid="'.$jh_pages["newest_jobs"].'" companytype="1" style="1" bgcolor="#ffffff" title="Popular Companies" scrollstyle="2" speed="1" posts_per_page="10"][/vc_column][/vc_row][vc_row][vc_column][jh_shortdescription_with_btn][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $jh_pages["home"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );
        //Home 1
        $new_page_content = '[vc_row][vc_column][jh_job_search jsjobspageid="'.$jh_pages["newest_jobs"].'" style="8"][/vc_column][/vc_row][vc_row][vc_column][jh_latest_featured_jobs jsjobspageid="'.$jh_pages["newest_jobs"].'" title="Latest Jobs" heading="Featured Jobs" noofjobs="6"][/vc_column][/vc_row][vc_row][vc_column][jh_job_hub_custom_link jsjobspageid="'.$jh_pages["newest_jobs"].'" style="7" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo"][/vc_column][/vc_row][vc_row][vc_column][jh_latest_resume jsjobspageid="'.$jh_pages["newest_jobs"].'" style="5" title="Latest Resume" description="Here you can see most recent resume added by users.You are able to set how many resume will be displayed ,order them or chose the specific taxonomy terms of resume." post_per_page="5" scrollstyle="2" speed="1"][/vc_column][/vc_row][vc_row][vc_column][jh_5count_box style="6" count1="587" count2="146" count3="919" count4="796"][/vc_column][/vc_row][vc_row][vc_column][jh_job_hub_custom_link jsjobspageid="'.$jh_pages["newest_jobs"].'" style="8" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo"][/vc_column][/vc_row][vc_row][vc_column][jh_news_and_rumors style="1"][/vc_column][/vc_row][vc_row][vc_column][jh_companies jsjobspageid="'.$jh_pages["newest_jobs"].'" companytype="1" style="2" title="Popular Companies" posts_per_page="6"][/vc_column][/vc_row][vc_row][vc_column][jh_shortdescription_with_btn][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $jh_pages["home1"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );
        //Home 2
        $new_page_content = '[vc_row video_bg="yes"][vc_column][vc_empty_space height="550px"][/vc_column][/vc_row][vc_row][vc_column][jh_job_search jsjobspageid="'.$jh_pages["newest_jobs"].'" style="2"][/vc_column][/vc_row][vc_row][vc_column][jh_latest_jobs_types_categories_cities jsjobspageid="'.$jh_pages["newest_jobs"].'" title="Latest Jobs" noofjobs="5" display_style="1" show_jobs_by_types="1" number_of_job_type="5" show_number_of_jobs_by_type="1" show_jobs_by_categories="1" number_of_categories="5" show_number_of_jobs_by_category="1" show_jobs_by_cities="1" number_of_cities="5" show_number_of_jobs_by_city="1"][/vc_column][/vc_row][vc_row][vc_column][jh_image_and_text_box_with_links][/vc_column][/vc_row][vc_row][vc_column][jh_latest_resume jsjobspageid="'.$jh_pages["newest_jobs"].'" style="4" title="Latest Resume" description="Here you can see most recent resume added by users.You are able to set how many resume will be displayed ,order them or chose the specific taxonomy terms of resume." post_per_page="5"][/vc_column][/vc_row][vc_row][vc_column][jh_news_and_rumors style="4"][/vc_column][/vc_row][vc_row][vc_column][jh_testimonial style="4" heading="Kind Words From Happy Candidates" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et ."][/vc_column][/vc_row][vc_row el_class="jsjb-jh-companies-carasole-without-bg"][vc_column][jh_companies jsjobspageid="'.$jh_pages["newest_jobs"].'" companytype="1" style="2" title="Popular Companies" posts_per_page="6"][/vc_column][/vc_row][vc_row][vc_column][jh_post_add style="1"][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $jh_pages["home2"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );
        // Update WP Options
        $wp_page_array = array();
        foreach ($page_array as $key => $value) {
            $value_string = jsjobslib::jsjobs_strtolower($value);
            $value_string = jsjobslib::jsjobs_str_replace(" ","_",$value_string);
            $wp_page_array[$value_string] = $jh_pages[$value_string];
        }
        update_option("job-hub-layout", $wp_page_array);
        // ----------------Posts -------- //
        $new_page_title = "Lorem ipsum dolor sit amet, consectetur adipiscing.";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post3.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet, consectetur";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post1.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post5.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post4.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // ----------------Custom posts -------- //
        // News & Rumors
        $new_page_title = "Advertising For Your Business";
        $new_page_content = 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using "Content here, content here", making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for "lorem ipsum" will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).';
        $new_page = array(
                "post_type" => "jh_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_1.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Attract More Attention Sales";
        $new_page_content = '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<strong></strong>';
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_2.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Top fun activities tips for you ";
        $new_page_content = '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_3.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);


        // Team members
        $new_page_title = "Member 4";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jh_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Front-end Developer");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_1.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 3";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jh_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Project Manager");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 2";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jh_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Team Leader");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_3.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Member 1";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "jh_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Cheif executive office / CEO");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_4.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // Price Table
        $new_page_title = "Basic Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jh_price" , "$ 500");
        __update_post_meta($new_page_id, "jh_line1" , "1500 Credits");
        __update_post_meta($new_page_id, "jh_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jh_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jh_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jh_buynowlink" , "#");

        $new_page_title = "Business Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jh_price" , "$ 750");
        __update_post_meta($new_page_id, "jh_line1" , "2500 Credits");
        __update_post_meta($new_page_id, "jh_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jh_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jh_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jh_buynowlink" , "#");

        $new_page_title = "Complete Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "jh_price" , "$ 1500");
        __update_post_meta($new_page_id, "jh_line1" , "6000 Credits");
        __update_post_meta($new_page_id, "jh_line2" , "New Company 500 Credits");
        __update_post_meta($new_page_id, "jh_line3" , "New Job 250 Credits");
        __update_post_meta($new_page_id, "jh_line4" , "Featured Job 100 Credits");
        __update_post_meta($new_page_id, "jh_buynowlink" , "#");

        // TESTIMONIAL
        $new_page_title = "Auro Navanth";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam iaculis quam sit amet dolor fermentum, in porta nisi egestas. Nullam convallis laoreet gravida. Pellentesque sed.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );

        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_1.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Naro MathDoe";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at tempus velit. Aliquam et diam convallis, tempus ligula ut, placerat sem. Nulla condimentum nulla a.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "MARY DOE";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce accumsan vitae massa vel aliquet. Morbi sed nibh eget lectus consequat tempor. Aliquam erat volutpat. Nam.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_3.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Robert Lafore";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent eleifend lacinia enim at dapibus. Nam eget accumsan neque. Nam felis augue, egestas ut varius vel.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_4.jpeg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Auro Navanth";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam commodo laoreet neque, vitae facilisis quam eleifend a. In consectetur purus quis arcu dictum, sit amet.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "jh_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_5.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);
        // Pages and custom post are created Now create Menu ----------------

        update_option( "page_on_front", $jh_pages["home"] );
        update_option( "show_on_front", "page" );       

        // MENU
        // Check if the menu exists
        $menu_name = "Job Hub";
        $menu_exists = wp_get_nav_menu_object( $menu_name );

        // If it doesn"t exist, let"s create it.
        if( !$menu_exists){
            $menu_id = wp_create_nav_menu($menu_name);

            $locations = get_theme_mod("nav_menu_locations");
            $locations["primary"] = $menu_id;
            set_theme_mod( "nav_menu_locations", $locations );

            $itemData =  array(
                "menu-item-object-id" => $jh_pages["home"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_home = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["home1"],
                "menu-item-parent-id" => $parent_home,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["home2"],
                "menu-item-parent-id" => $parent_home,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            // Job seeker
            $itemData =  array(
                "menu-item-title" => "Job Seeker",
                "menu-item-object-id" => $jh_pages["jobseeker_control_panel"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            $parent_jobseeker = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Jobs",
                "menu-item-object-id" => $jh_pages["newest_jobs"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Search Job",
                "menu-item-object-id" => $jh_pages["search_job"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Jobs By Categories",
                "menu-item-object-id" => $jh_pages["jobs_by_category"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Add Resume",
                "menu-item-object-id" => $jh_pages["add_resume"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "My Applied Jobs",
                "menu-item-object-id" => $jh_pages["my_applied_jobs"],
                "menu-item-parent-id" => $parent_jobseeker,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            // employer
            $itemData =  array(
                "menu-item-title" => "Employer",
                "menu-item-object-id" => $jh_pages["employer_control_panel"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            $parent_employer = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Add Job",
                "menu-item-object-id" => $jh_pages["add_job"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "My Jobs",
                "menu-item-object-id" => $jh_pages["my_jobs"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Resume Search",
                "menu-item-object-id" => $jh_pages["resume_search"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Resumes By Categories",
                "menu-item-object-id" => $jh_pages["resume_by_category"],
                "menu-item-parent-id" => $parent_employer,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Pages",
                "menu-item-object-id" => $jh_pages["blog"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_pages = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["news_and_rumors"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["faq"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["blog"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["pricing_table"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["thank_you"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            if($pro == 1){

            $itemData =  array(
                "menu-item-title" => "Credits",
                "menu-item-object-id" => $jh_pages["jobseeker_credits_pack"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_credits = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Job Seeker Credits Pack",
                "menu-item-object-id" => $jh_pages["jobseeker_credits_pack"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_credits = wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Job Seeker Credits Log",
                "menu-item-object-id" => $jh_pages["jobseeker_credits_log"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Job Seeker Rate List",
                "menu-item-object-id" => $jh_pages["jobseeker_rate_list"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Employer Credits Pack",
                "menu-item-object-id" => $jh_pages["employer_credits_pack"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Employer Credits Log",
                "menu-item-object-id" => $jh_pages["employer_credits_log"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Employer Rate List",
                "menu-item-object-id" => $jh_pages["employer_rate_list"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            }

            $itemData =  array(
                "menu-item-object-id" => $jh_pages["ourteam"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $jh_pages["contact_us"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            }
            $widget_positions = get_option("sidebars_widgets");
            // Woocommerce sidebar
                $widget_positions["woocommerce-sidebar"][] = "woocommerce_widget_cart-1";
                $widget_woocommerce_widget_cart_array[1] = array("title" => "My Cart");
                $widget_woocommerce_widget_cart_array["_multiwidget"] = 1;
                // Left sidebar
                $widget_positions["left-sidebar"][] = "search-1";
                $search_array[1] = array("title" => "Search");
                $search_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "recent-posts-1";
                $recent_posts_array[1] = array("title" => "Recent Posts", "number" => 5);
                $recent_posts_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "recent-comments-1";
                $recent_comments_array[1] = array("title" => "Recent Comments", "number" => 5);
                $recent_comments_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "archives-1";
                $archives_array[1] = array("title" => "Archives");
                $archives_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "categories-1";
                $categories_array[1] = array("title" => "Categories");
                $categories_array["_multiwidget"] = 1;
                $widget_positions["left-sidebar"][] = "meta-1";
                $meta_array[1] = array("title" => "Meta");
                $meta_array["_multiwidget"] = 1;
                // Right sidebar
                $widget_positions["right-sidebar"][] = "calendar-1";
                $calendar_array[1] = array("title" => "Calendar");
                $calendar_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "widget_jsjb_recent_comments-1";
                $widget_jsjb_recent_comments_array[1] = array("title" => "Job Hub Recent Comments", "count" => 2);
                $widget_jsjb_recent_comments_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "widget_jsjb_recent_posts-1";
                $widget_jsjb_recent_posts_array[1] = array("title" => "Job Hub Recent Posts", "category" => "");
                $widget_jsjb_recent_posts_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "nav_menu-1";
                $nav_menu_array[1] = array("title" => "Custom Menu", "nav_menu" => "");
                $nav_menu_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "pages-1";
                $pages_array[1] = array("title" => "Pages", "sortby" => "post_title");
                $pages_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "tag_cloud-1";
                $tag_cloud_array[1] = array("title" => "Tag Cloud", "taxonomy" => "post_tag");
                $tag_cloud_array["_multiwidget"] = 1;
                $widget_positions["right-sidebar"][] = "text-1";
                $text_array[1] = array("title" => "Text Heading", "text" => "Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum");
                $text_array["_multiwidget"] = 1;
                // News and rumors
                $widget_positions["news_and_rumors"][] = "search-2";
                $search_array[2] = array("title" => "Search");
                $search_array["_multiwidget"] = 1;
                $widget_positions["news_and_rumors"][] = "recent-posts-2";
                $recent_posts_array[2] = array("title" => "Recent Posts", "number" => 5);
                $recent_posts_array["_multiwidget"] = 1;
                // footer1
                if (!is_active_sidebar( 'footer1' ) ) { // check if widget was activated from template. to avoid dupilication
                    $widget_positions["footer1"][] = "widget_jsjb_footeraboutus-1";
                    $widget_jsjb_footeraboutus_array[1] = array("title" => "Job Hub", "description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.");
                    $widget_jsjb_footeraboutus_array["_multiwidget"] = 1;
                }
                // footer2
                $widget_positions["footer2"][] = "widget_jsjb_footerusefullinks-1";
                $widget_jsjb_footerusefullinks_array[1] = array(
                    "title" => "Useful Links", 
                    "title1"=>"Newest Jobs", "link1"=> get_the_permalink($jh_pages["newest_jobs"]), 
                    "title3"=>"Search Job", "link3"=> get_the_permalink($jh_pages["search_job"]), 
                    "title2"=>"Resume Search", "link2"=> get_the_permalink($jh_pages["resume_search"]), 
                    "title4"=>"Shortlisted Jobs", "link4"=> get_the_permalink($jh_pages["shortlisted_jobs"]), 
                    "title5"=>"All Companies", "link5"=> get_the_permalink($jh_pages["all_companies"]), 
                    "title6"=>"", "link6"=> "#", 
                    "title7"=>"", "link7"=> "#", 
                    "title8"=>"", "link8"=> "#", 
                    "title9"=>"", "link9"=> "#", 
                    "title10"=>"", "link10"=> "#", 
                    );
                $widget_jsjb_footerusefullinks_array["_multiwidget"] = 1;
                // footer3
                // $widget_positions["footer3"][] = "widget_jsjs_footercompaniesimages-1";
                $widget_jsjs_footercompaniesimages_array[1] = array("title" => "Featured Companies", "companytype" => 1, "max_images"=>9, "column"=>3);
                $widget_jsjs_footercompaniesimages_array["_multiwidget"] = 1;
                // footer4
                if (!is_active_sidebar( 'footer3' ) ) { // check if widget was activated from template. to avoid dupilication
                    $widget_positions["footer3"][] = "widget_jsjb_footercontactus-1";
                    $widget_jsjb_footercontactus_array[1] = array("title" => "Contact Us", "email" => "jobhub@yourdomain.com", "address"=>"At vero eos et accusamus et iusto odio dignissimos", "phone"=>"+1234567890");
                    $widget_jsjb_footercontactus_array["_multiwidget"] = 1;
                }

                update_option("widget_"."widget_woocommerce_widget_cart"  , $widget_woocommerce_widget_cart_array);
                update_option("widget_"."search"  , $search_array);
                update_option("widget_"."recent-posts"  , $recent_posts_array);
                update_option("widget_"."recent-comments"  , $recent_comments_array);
                update_option("widget_"."archives"  , $archives_array);
                update_option("widget_"."categories"  , $categories_array);
                update_option("widget_"."meta"  , $meta_array);
                update_option("widget_"."calendar"  , $calendar_array);
                update_option("widget_"."widget_jsjb_recent_comments"  , $widget_jsjb_recent_comments_array);
                update_option("widget_"."widget_jsjb_recent_posts"  , $widget_jsjb_recent_posts_array);
                update_option("widget_"."nav_menu"  , $nav_menu_array);
                update_option("widget_"."pages"  , $pages_array);
                update_option("widget_"."tag_cloud"  , $tag_cloud_array);
                update_option("widget_"."text"  , $text_array);
                update_option("widget_"."widget_jsjb_footerusefullinks"  , $widget_jsjb_footerusefullinks_array);
                update_option("widget_"."widget_jsjs_footercompaniesimages"  , $widget_jsjs_footercompaniesimages_array);
                if (!is_active_sidebar( 'footer3' ) ) { // check if widget was activated from template. to avoid dupilication
                    update_option("widget_"."widget_jsjb_footercontactus"  , $widget_jsjb_footercontactus_array);
                }
                if (!is_active_sidebar( 'footer1' ) ) { // check if widget was activated from template. to avoid dupilication
                    update_option("widget_"."widget_jsjb_footeraboutus"  , $widget_jsjb_footeraboutus_array);
                }

                // update this array at last
                update_option( "sidebars_widgets" , $widget_positions);
                
                global $wpdb;
                $pageid = $wpdb->get_var("Select id FROM `" . $wpdb->prefix . "posts` WHERE post_name = 'js-support-ticket-controlpanel'");
                if(is_numeric($pageid) && $pageid > 0){
                    update_post_meta($pageid, "_wp_page_template", "templates/template-fullwidth.php");        
                }
                // Update the configuration default page to Vehicles
                $query = "UPDATE `".$wpdb->prefix."js_job_config` SET configvalue = '".$jh_pages["newest_jobs"]."' WHERE configname = 'default_pageid'";
                $wpdb->query($query);
                update_option("rewrite_rules", "");
            return 1;
        
    }

    function insertJobCities($jobid, $cityid) {
        $insert_jobcity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobcities` (`jobid`, `cityid`) 
        VALUES( " . $jobid . ", " . $cityid . ");";
        jsjobsdb::query($insert_jobcity);
        return true;
    }

    function getPageList() {
        $query = "SELECT ID AS id, post_title AS text FROM `" . jsjobs::$_db->prefix . "posts` WHERE post_type = 'page' AND post_status = 'publish' ";
        $pages = jsjobs::$_db->get_results($query);
        return $pages;
    }

    function getListOfDemoVersions() {
        $post_data = array();// data to posted with curl call
        $url = "https://setup.joomsky.com/jobmanagertheme/demoimporter/getdemos.php";
        $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
        if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
            $call_result = $response['body'];
        }else{
            $call_result = false;
            if(!is_wp_error($response)){
               $error = $response['response']['message'];
           }else{
                $error = $response->get_error_message();
           }
        }
        if($call_result){
            $result = json_decode($call_result,true);
        }else{
            $result = array();
        }

        if(isset($result['result_flag']) && $result['result_flag'] == 1){
            $response = $result['result_data'];
        }
        $combo_array = array();
        $result = array();
        if(is_array($response)){
            foreach ($response as $key => $value) {
                $combo_array[] = (object)['id'=>$key+1,'text'=>$value['name']];
                $result[$key+1] = $value;
            }
        }
        jsjobs::$_data[0] = $combo_array;
        jsjobs::$_data[1] = $result;
        return ;
    }
    
    function getDemo($demoid,$foldername,$demo_overwrite =0) {
        if($demoid == ''){
            die('demo not seleceted');
        }
        if($demo_overwrite == 1){
            $this->removeJobManagerDemoData();    
        }
        $url = 'https://setup.joomsky.com/jobmanagertheme/demoimporter/demos/'.$foldername.'/democode.php';
        $post_data = array();
        $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
        if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
            $call_result = $response['body'];
        }else{
            $call_result = false;
            if(!is_wp_error($response)){
               $error = $response['response']['message'];
           }else{
                $error = $response->get_error_message();
           }
        }

        if($call_result){
           $return_string = $call_result;
           $pro = 0;
           $return_string;
           update_option('job_manager_demno_id',$demoid);// record the id of the demo currently imported.
        }
        return;
    }

    function removeJobManagerDemoData(){
        delete_option("widget_widget_woocommerce_widget_cart");
        delete_option("widget_search");
        delete_option("widget_recent-posts");
        delete_option("widget_recent-comments");
        delete_option("widget_archives");
        delete_option("widget_categories");
        delete_option("widget_meta");
        delete_option("widget_calendar");
        delete_option("widget_widget_cm_recent_comments");
        delete_option("widget_widget_cm_recent_posts");
        delete_option("widget_nav_menu");
        delete_option("widget_pages");
        delete_option("widget_tag_cloud");
        delete_option("widget_text");
        delete_option("widget_widget_cm_footeraboutus");
        delete_option("widget_widget_cm_footerusefullinks");
        delete_option("widget_widget_cm_footervehicleimages");
        delete_option("widget_widget_cm_footercontactus");

        $widget_positions = get_option("sidebars_widgets");
        unset($widget_positions["footer1"]);
        unset($widget_positions["footer2"]);
        unset($widget_positions["footer3"]);
        unset($widget_positions["footer4"]);
        unset($widget_positions["left-sidebar"]);
        unset($widget_positions["right-sidebar"]);
        unset($widget_positions["news_and_rumors"]);

        update_option( "sidebars_widgets" , $widget_positions);

        $menu_name = "Job Manager";
        $del_flag = wp_delete_nav_menu($menu_name);
        $menu_exists = wp_get_nav_menu_object( $menu_name );

        $pages_array = get_option('job_manager_demo_pages_ids');
        if(!empty($pages_array) && is_array($pages_array) ){
            foreach ($pages_array as $key => $value) {
                wp_delete_post($value,true);
            }
        }

        $post_array = get_option('job_manager_demo_post_ids');
        if(!empty($post_array) && is_array($post_array) ){
            foreach ($post_array as $key => $value) {
                wp_delete_post($value,true);
            }
        }
        return;
    } 
    

    function getMessagekey(){
        $key = 'postinstallation';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


    function uploadPostFeatureImage($filename,$parent_post_id){
        // Check the type of file. We"ll use this as the "post_mime_type".
        $filetype = wp_check_filetype( jsjobslib::jsjobs_basename( $filename ), null );
        // Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();
        // Prepare an array of post data for the attachment.
        $attachment = array(
            "guid"           => $wp_upload_dir["url"] . "/" . jsjobslib::jsjobs_basename( $filename ), 
            "post_mime_type" => $filetype["type"],
            "post_title"     => jsjobslib::jsjobs_preg_replace( "/\.[^.]+$/", "", jsjobslib::jsjobs_basename( $filename ) ),
            "post_content"   => "",
            "post_status"    => "inherit"
        );
        // Insert the attachment.
        $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once( ABSPATH . "wp-admin/includes/image.php" );
        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        set_post_thumbnail( $parent_post_id, $attach_id );
    }


    function createSamplePosts($post_type,$post_title,$post_content,$file_path = '',$postmeta = array()){
        $new_page_template = '';
        $new_post = array(
                        "post_type" => $post_type,
                        "post_title" => $post_title,
                        "post_content" => $post_content,
                        "post_status" => "publish",
                        "post_author" => 1,
                        "post_parent" => 0,
                );
        $post_id = wp_insert_post($new_post);
        update_post_meta($post_id, "_wp_page_template", $new_page_template);
        if($file_path != ''){
            $this->uploadPostFeatureImage($file_path,$post_id);
        }
        if(!empty($postmeta)){
            foreach ($postmeta as $meta_key => $meta_value) {
                __update_post_meta($post_id, $meta_key, $meta_value);
            }
        }
        return $post_id;
    }


    function createSampleMenuItem($menu_id,$wp_pageid,$parent_id = 0,$title=''){
        $itemData = array();
        if($title != ''){
            $itemData['menu-item-title'] = $title;
        }
        $itemData["menu-item-object-id"] = $wp_pageid;
        $itemData["menu-item-parent-id"] = $parent_id;
        $itemData["menu-item-object"] = "page";
        $itemData["menu-item-type"] = "post_type";
        $itemData["menu-item-status"] = "publish";
        // original sample
        /*
            $itemData =  array(
                "menu-item-object-id" => $cm_pages["rate_list"],
                "menu-item-parent-id" => $parent_credits,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
        */
        $return_parent_id = wp_update_nav_menu_item($menu_id, 0, $itemData);
        return $return_parent_id;
    }

    function createTemplatePagesGeneric($page_title,$page_content,$page_template,$show_header,$file_path=''){
        $new_page = array(
                "post_type" => "page",
                "post_title" => $page_title,
                "post_content" => $page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $page_check = get_page_by_title($page_title);
        if(!isset($page_check->ID)){
            $page_id = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "Job Manager ".$page_title;
            $page_check = get_page_by_title($new_page["post_title"]);
            if(!isset($page_check->ID)){
                $page_id = wp_insert_post($new_page);
            }else{
                $page_id = $page_check->ID;
            }
        }
        if(isset($page_id) && $page_id != ''){
            update_post_meta($page_id, "jm_show_header", $show_header);
            update_post_meta($page_id, "jm_headerimage_url", $file_path);
            if($page_template != ''){
                update_post_meta($page_id, "_wp_page_template", $page_template);
            }
        }

        return $page_id;
    }

    function getWpUsersList() {
        $query = "SELECT id,CONCAT(user_login,' ( ',display_name,' ) - ',id) AS text  FROM `" . jsjobs::$_db->prefix . "users`";
        $users = jsjobs::$_db->get_results($query);
        $data[0] = (object) array('id' => 0, 'text' => __('Select User', 'js-jobs'));
        foreach ($users as $user) {
            $data[] = $user;
        }
        return $data;
    }

}?>
