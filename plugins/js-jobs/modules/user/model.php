<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSUserModel {

    function jsGetPrefix(){
        global $wpdb;
        if(is_multisite()) {
            $prefix = $wpdb->base_prefix;
        }else{
            $prefix = jsjobs::$_db->prefix;
        }
        return $prefix;
    }

    function getAllUsers() {

        //Filters
        $searchname = jsjobs::$_search['user']['searchname'];
        $searchusername = jsjobs::$_search['user']['searchusername'];
        $searchrole = jsjobs::$_search['user']['searchrole'];
        $searchcompany = jsjobs::$_search['user']['searchcompany'];
        $searchresume = jsjobs::$_search['user']['searchresume'];

        jsjobs::$_data['filter']['searchname'] = $searchname;
        jsjobs::$_data['filter']['searchusername'] = $searchusername;
        jsjobs::$_data['filter']['searchrole'] = $searchrole;
        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchresume'] = $searchresume;

        $clause = " WHERE ";
        $inquery = '';
        if ($searchname) {
            $inquery .= $clause . "(LOWER(a.first_name) LIKE '%" . $searchname . "%' OR LOWER(a.last_name) LIKE '%" . $searchname . "%')";
            $clause = " AND ";
        }
        if ($searchusername) {
            $inquery .= $clause . " LOWER(u.user_login) LIKE '%" . $searchusername . "%'";
            $clause = " AND ";
        }
        if ($searchcompany) {
            $inquery .= $clause . " LOWER(company.name) LIKE '%" . $searchcompany . "%'";
            $clause = " AND ";
        }
        if ($searchresume) {
            $inquery .= $clause . " ( LOWER(resume.first_name) LIKE '%" . $searchresume . "%'
                        OR LOWER(resume.last_name) LIKE '%" . $searchresume . "%'
                        OR LOWER(resume.middle_name) LIKE '%" . $searchresume . "%')";
            $clause = " AND ";
        }
        if ($searchrole){
            if (is_numeric($searchrole))
                $inquery .= $clause . "a.roleid = " . $searchrole;
        }
        //Pagination
        $query = 'SELECT a.id '
                . ' FROM `' . jsjobs::$_db->prefix . 'js_job_users` AS a'
                . ' LEFT JOIN `' . $this->jsGetPrefix() . 'users` AS u ON u.id = a.uid '
                . ' LEFT JOIN `' . jsjobs::$_db->prefix . 'js_job_companies` AS company ON company.uid = a.id'
                . ' LEFT JOIN `' . jsjobs::$_db->prefix . 'js_job_resume` AS resume ON resume.uid = a.id ';
        $query .= $inquery;
        $query .= " GROUP BY a.id ";
        $total = jsjobsdb::get_results($query);
        $total = count($total);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = 'SELECT a.*,u.user_login,u.id AS wpuid,
                 company.name AS companyname, resume.first_name AS resume_first_name, resume.last_name AS resume_last_name'
                . ' FROM ' . jsjobs::$_db->prefix . 'js_job_users AS a'
                . ' LEFT JOIN ' . $this->jsGetPrefix() . 'users AS u ON u.id = a.uid '
                . 'LEFT JOIN ' . jsjobs::$_db->prefix . 'js_job_companies AS company ON company.uid = a.id '
                . 'LEFT JOIN ' . jsjobs::$_db->prefix . 'js_job_resume AS resume ON resume.uid = a.id ';
        $query.=$inquery;
        $query .= ' GROUP BY a.id LIMIT ' . JSJOBSpagination::$_offset . ',' . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }

    function enforceDeleteUser($uid) {
        if (!is_numeric($uid))
            return false;

        $roleid = $this->getUserRoleByUid($uid);

        if (!is_numeric($roleid)) {
            // this user has no role
            // what to do then ?
        } else {

            $wp_uid = $this->getWPuidByOuruid($uid);

            if ($this->enforceDeleteOurUser($uid, $roleid)) {

                require_once(ABSPATH . 'wp-admin/includes/user.php' );

                if (wp_delete_user($wp_uid))
                    return JSJOBS_DELETED;
                else {
                    return JSJOBS_DELETE_ERROR;
                }
            } else {
                return JSJOBS_DELETE_ERROR;
            }
        }
    }

   function enforceDeleteOurUser($uid, $roleid) {
        if (!is_numeric($uid))
            return false;
        $query = '';

        if ($roleid == 1) { // employer
            $query = "DELETE u, job,comp,dep,jobcity,compcity
                        FROM
                        `" . jsjobs::$_db->prefix . "js_job_users` AS u
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON job.uid = u.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS jobcity ON jobcity.jobid = job.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS comp ON comp.uid = u.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companycities` AS compcity ON compcity.companyid = comp.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_departments` AS dep ON dep.companyid = comp.id
                WHERE u.id = " . $uid;
        }

        if ($roleid == 2) { // seeker
            $query = "DELETE u,resume , ra, re,rf,ri,rl,rref,rs,ja,cvl
                        FROM `" . jsjobs::$_db->prefix . "js_job_users` AS u
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumes` AS resume ON resume.uid = u.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS ra ON ra.resumeid = resume.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumeemployers` AS re ON re.resumeid = resume.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumefiles` AS rf ON rf.resumeid = resume.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS ri ON ri.resumeid = resume.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumelanguages` AS rl ON rl.resumeid = resume.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumereferences` AS rref ON rref.resumeid = resume.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumesearches` AS rs ON rs.resumeid = resume.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobapply` AS ja ON ja.uid = u.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_coverletters` AS cvl ON cvl.uid = u.id
                WHERE u.id = " . $uid;
        }
        if($query != ''){
            if (jsjobsdb::query($query)) {
                return true;
            } else {
                return false;
            }
        }
    }

    function getUserRoleByUid($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT roleid FROM `" . jsjobs::$_db->prefix . "js_job_users` WHERE id = " . $uid;
        $result = jsjobsdb::get_var($query);
        return $result;
    }

    function getUserRoleByWPUid($wpuid) {
        if (!is_numeric($wpuid))
            return false;
        $query = "SELECT roleid FROM `" . jsjobs::$_db->prefix . "js_job_users` WHERE uid = " . $wpuid;
        $result = jsjobsdb::get_var($query);
        return $result;
    }

    function getUserIDByWPUid($wpuid) {
        if (!is_numeric($wpuid))
            return false;
        $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_users` WHERE uid = " . $wpuid;
        $result = jsjobsdb::get_var($query);
        return $result;
    }

    function getWPuidByOuruid($our_uid) {
        if (!is_numeric($our_uid))
            return false;
        $query = "SELECT uid AS wpuid FROM `" . jsjobs::$_db->prefix . "js_job_users` WHERE id = " . $our_uid;
        $result = jsjobsdb::get_var($query);
        return $result;
    }

    function changeUserStatus($userid){
        if(!is_numeric($userid)) return false;
        $row = JSJOBSincluder::getJSTable('users');
        if($row->load($userid)){
            $row->columns['status'] = 1 - $row->status;
            if($row->store()){
                if($row->columns['status'] == 1){
                    return JSJOBS_ENABLED;
                }else{
                    return JSJOBS_DISABLED;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function assignUserRole($data){

        if(empty($data))
            return false;
        if(! is_numeric($data['uid']))
            return false;
        if(! is_numeric($data['roleid']))
            return false;

        $arr = array();
        $arr['uid'] = $data['uid'];
        $arr['roleid'] = $data['roleid'];
        $arr['first_name'] = $data['payer_firstname'];
        $arr['emailaddress'] = $data['payer_emailadress'];
        $arr['status'] = 1;
        $arr['created'] = date("Y-m-d H:i:s");

        $row = JSJOBSincluder::getJSTable('users');
        if (!$row->bind($arr)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->check()) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }
        return JSJOBS_SAVED;
    }

    function deleteUser($uid) {
        if (!is_numeric($uid))
            return false;
        $roleid = $this->getUserRoleByUid($uid);
        if (!is_numeric($roleid)) {
            // this user has no role
            // what to do then ?
        } else {
            if ($this->userCanDelete($uid, $roleid)) {
                $wp_uid = $this->getWPuidByOuruid($uid);

                if ($this->deleteOurUser($uid)) {
                    require_once(ABSPATH . 'wp-admin/includes/user.php' );
                    if (wp_delete_user($wp_uid)) {
                        return JSJOBS_DELETED;
                    } else {
                        return JSJOBS_DELETE_ERROR;
                    }
                } else {
                    return JSJOBS_DELETE_ERROR;
                }
            } else {
                return JSJOBS_IN_USE;
            }
        }
    }

    function userCanDelete($uid, $roleid) {

        if ($roleid == 1) { // employer
            $query = "SELECT
                    (SELECT COUNT(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job WHERE job.uid = $uid )
                +   (SELECT COUNT(comp.id) FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS comp WHERE comp.uid = $uid )
                +   (SELECT COUNT(dep.id) FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS dep WHERE dep.uid = $uid )
                AS total
            ";
        }

        if ($roleid == 2) { // seeker
            $query = "SELECT
                    (SELECT COUNT(resume.id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume WHERE resume.uid = $uid )
                +   (SELECT COUNT(cvl.id) FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` AS cvl WHERE cvl.uid = $uid )

                AS total
            ";
        }

        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return false;
        else
            return true;
    }

    function deleteOurUser($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_users` WHERE id = " . $uid;
        if (jsjobsdb::query($query)) {
            return true;
        } else {
            return false;
        }
    }

    function getUserStats() {
        //Filters
        $searchname = jsjobs::$_search['user']['searchname'];
        $searchusername = jsjobs::$_search['user']['searchusername'];
        jsjobs::$_data['filter']['searchname'] = $searchname;
        jsjobs::$_data['filter']['searchusername'] = $searchusername;

        $clause = " WHERE ";
        $inquery = "";
        if ($searchname) {
            $inquery .= $clause . " (LOWER(a.first_name) LIKE '%" . $searchname . "%' OR LOWER(a.last_name) LIKE '%" . $searchname . "%')";
            $clause = 'AND';
        }
        if ($searchusername)
            $inquery .= $clause . " LOWER(a.user_login) LIKE '%" . $searchusername . "%'";

        //Pagination
        $query = "SELECT COUNT(a.ID) FROM " . $this->jsGetPrefix() . "users AS a";
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT a.id AS id, CONCAT(a.first_name,' ',a.last_name) AS name, u.user_login AS username
                ,(SELECT name FROM " . jsjobs::$_db->prefix . "js_job_companies WHERE uid=a.id limit 1 ) AS companyname
                ,(SELECT CONCAT(first_name,' ',last_name) FROM " . jsjobs::$_db->prefix . "js_job_resume WHERE uid=a.id limit 1 ) AS resumename
                ,(SELECT count(id) FROM " . jsjobs::$_db->prefix . "js_job_companies WHERE uid=a.id ) AS companies
                ,(SELECT count(id) FROM " . jsjobs::$_db->prefix . "js_job_jobs WHERE uid=a.id ) AS jobs
                ,(SELECT count(id) FROM " . jsjobs::$_db->prefix . "js_job_resume WHERE uid=a.id ) AS resumes
                FROM " . jsjobs::$_db->prefix . "js_job_users AS a
                LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = a.uid";
        $query.=$inquery;
        $query .= ' GROUP BY a.id LIMIT ' . JSJOBSpagination::$_offset . ',' . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function getUserStatsCompanies($companyuid) {
        if (is_numeric($companyuid) == false)
            return false;

        //Pagination
        $query = "SELECT COUNT(company.id)
                  FROM " . jsjobs::$_db->prefix . "js_job_companies AS company
	              WHERE company.uid = " . $companyuid;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT company.*,cat.cat_title"
                . " FROM " . jsjobs::$_db->prefix . "js_job_companies AS company"
                . " JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON cat.id=company.category"
                . " LEFT JOIN " . jsjobs::$_db->prefix . "js_job_cities AS city ON city.id=company.city"
                . " LEFT JOIN " . jsjobs::$_db->prefix . "js_job_countries AS country ON country.id=city.countryid
		          WHERE company.uid = " . $companyuid;
        $query .= " ORDER BY company.name LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function getWPRoleNameById($id) {
        $rolename = "";
        if ($id) {
            $user = new WP_User($id);
            $rolename = $user->roles[0];
        }
        return $rolename;
    }

    function getUserStatsJobs($jobuid) {
        if (is_numeric($jobuid) == false)
            return false;

        //Pagination
        $query = "SELECT COUNT(job.id)
                FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job WHERE job.uid = " . $jobuid;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT job.*,company.name AS companyname,cat.cat_title,jobtype.title AS jobtypetitle"
                . " FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job"
                . " LEFT JOIN " . jsjobs::$_db->prefix . "js_job_companies AS company ON company.id=job.companyid"
                . " LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON cat.id=job.jobcategory"
                . " LEFT JOIN " . jsjobs::$_db->prefix . "js_job_jobtypes AS jobtype ON jobtype.id=job.jobtype
		   WHERE job.uid = " . $jobuid;
        $query .= " ORDER BY job.title LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }

    function getuserlistajax() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $userlimit = JSJOBSrequest::getVar('userlimit', null, 0);
        $maxrecorded = 3;
        //Filters
        $uname = JSJOBSrequest::getVar('uname');
        $name = JSJOBSrequest::getVar('name');
        $email = JSJOBSrequest::getVar('email');
        $listfor = JSJOBSrequest::getVar('listfor');

        jsjobs::$_data['filter']['name'] = $name;
        jsjobs::$_data['filter']['uname'] = $uname;
        jsjobs::$_data['filter']['email'] = $email;

        $inquery = "";

        if ($name != null) {
            $inquery .= " AND ( user.first_name LIKE '%" . $name . "%' OR user.last_name LIKE '%" . $name . "%' ) ";
        }
        if ($uname != null) {
            $inquery .= " AND  u.user_login LIKE  '%" . $uname . "%' ";
        }
        if ($email != null)
            $inquery .= " AND user.emailaddress LIKE '%" . $email . "%' ";

        if ($listfor == 1) {
            $status = "WHERE 1 = 1"; //to get all users
        } else {
            $status = "WHERE user.roleid =1";
        }


        $query = "SELECT COUNT(user.id)
                FROM " . jsjobs::$_db->prefix . "js_job_users AS user
                LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = user.uid
                $status ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        $limit = $userlimit * $maxrecorded;
        if ($limit >= $total) {
            $limit = 0;
        }

        //Data
        $query = "SELECT user.id AS userid,user.first_name,user.last_name,user.emailaddress
                    ,u.user_login
                FROM " . jsjobs::$_db->prefix . "js_job_users AS user
                LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = user.uid
                $status ";
        $query .= $inquery;
        $query .= " ORDER BY user.id LIMIT $limit, $maxrecorded";
        $users = jsjobsdb::get_results($query);

        $html = $this->makeUserList($users, $total, $maxrecorded, $userlimit);
        return $html;
    }

    function getAllRoleLessUsersAjax() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $userlimit = JSJOBSrequest::getVar('userlimit', null, 0);
        $maxrecorded = 3;
        //Filters

        $name = JSJOBSrequest::getVar('name');
        $uname = JSJOBSrequest::getVar('uname');
        $email = JSJOBSrequest::getVar('email');

        jsjobs::$_data['filter']['name'] = $name;
        jsjobs::$_data['filter']['uname'] = $uname;
        jsjobs::$_data['filter']['email'] = $email;

        $inquery = "";

        if ($uname != null) {
            $inquery .= " AND ( user.user_login LIKE '%" . $uname . "%' ) ";
        }

        if ($name != null) {
            $inquery .= " AND ( user.display_name LIKE '%" . $name . "%' ) ";
        }

        if ($email != null) {
            $inquery .= " AND ( user.user_email LIKE '%" . $email . "%' ) ";
        }

        $query = "SELECT COUNT( user.ID ) AS total
                    FROM `" . $this->jsGetPrefix() . "users` AS user
                    WHERE NOT EXISTS( SELECT jsuser.id FROM `" . jsjobs::$_db->prefix . "js_job_users` AS jsuser WHERE user.ID = jsuser.uid) AND
                    NOT EXISTS(SELECT umeta_id FROM `".jsjobs::$_db->prefix."usermeta` WHERE meta_value LIKE '%administrator%' AND user_id = user.id)";
        $query .= $inquery;
        $query .= " GROUP BY user.ID";
        $total = jsjobsdb::get_var($query);

        $limit = $userlimit * $maxrecorded;
        if ($limit >= $total) {
            $limit = 0;
        }

        // Data
        $query = "SELECT DISTINCT user.ID AS userid, user.user_login , user.user_email AS emailaddress, user.display_name AS name
                    FROM `" . $this->jsGetPrefix() . "users` AS user
                    WHERE NOT EXISTS( SELECT jsuser.id FROM `" . jsjobs::$_db->prefix . "js_job_users` AS jsuser WHERE user.ID = jsuser.uid) AND
                    NOT EXISTS(SELECT umeta_id FROM `".jsjobs::$_db->prefix."usermeta` WHERE meta_value LIKE '%administrator%' AND user_id = user.ID)";

        $query .= $inquery;
        $query .= " ORDER BY user.ID ASC LIMIT $limit, $maxrecorded";
        $users = jsjobsdb::get_results($query);

        $html = $this->makeUserList($users, $total, $maxrecorded, $userlimit , true);
        return $html;
    }

    function makeUserList($users, $total, $maxrecorded, $userlimit , $assignrole = false) {
        $html = '';
        if (!empty($users)) {
            if (is_array($users)) {

                $html .= '
                    <div id="records">';

                $html .='
                <div id="user-list-header">
                    <div class="js-col-md-1 user-id">' . __('ID', 'js-jobs') . '</div>
                    <div class="js-col-md-3 user-name">' . __('Name', 'js-jobs') . '</div>
                    <div class="js-col-md-3 user-name-n">' . __('User Name', 'js-jobs') . '</div>
                    <div class="js-col-md-5 user-email">' . __('Email Address', 'js-jobs') . '</div>

                </div>';

                foreach ($users AS $user) {
                    if($assignrole){
                        $username = $user->name;
                    }else{
                        $username = $user->first_name . ' ' . $user->last_name;
                    }
                    $html .='
                        <div class="user-records-wrapper" >
                            <div class="js-col-xs-12 js-col-md-1 user-id">
                                ' . $user->userid . '
                            </div>
                            <div class="js-col-xs-12 js-col-md-3 user-name">
                                <a href="#" class="js-userpopup-link" data-id=' . $user->userid . ' data-name="' . $username . '" data-email="' . $user->emailaddress . '" >' . $username . '</a>
                            </div>
                            <div class="js-col-xs-12 js-col-md-3 user-name-n">
                                ' . $user->user_login . '
                            </div>
                            <div class="js-col-xs-12 js-col-md-5 user-email">
                                ' . $user->emailaddress . '
                            </div>
                        </div>';
                }
            }
            $num_of_pages = ceil($total / $maxrecorded);
            $num_of_pages = ($num_of_pages > 0) ? ceil($num_of_pages) : floor($num_of_pages);
            if ($num_of_pages > 0) {
                $page_html = '';
                $prev = $userlimit;
                if ($prev > 0) {
                    $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist(' . ($prev - 1) . ');">' . __('Previous', 'js-jobs') . '</a>';
                }
                for ($i = 0; $i < $num_of_pages; $i++) {
                    if ($i == $userlimit)
                        $page_html .= '<span class="jsst_userlink selected" >' . ($i + 1) . '</span>';
                    else
                        $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist(' . $i . ');">' . ($i + 1) . '</a>';
                }
                $next = $userlimit + 1;
                if ($next < $num_of_pages) {
                    $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist(' . $next . ');">' . __('Next', 'js-jobs') . '</a>';
                }
                if ($page_html != '') {
                    $html .= '<div class="jsst_userpages">' . $page_html . '</div>';
                }
            }
        } else {
            $html = JSJOBSlayout::getAdminPopupNoRecordFound();
        }
        $html .= '</div>';
        return $html;
    }

    function checkUserBySocialID($socialid) {
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_users` WHERE socialid = '" . $socialid . "'";
        $result = jsjobs::$_db->get_var($query);
        return $result;
    }

    function getUserData($id){
        if (!is_numeric($id))
            return false;
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_users` WHERE id = " . $id ;
        jsjobs::$_data[0] = jsjobs::$_db->get_row($query);
        //employer
        if(jsjobs::$_data[0]->roleid == 1){
            $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE uid=".$id;
            jsjobs::$_data['jobs'] = jsjobs::$_db->get_var($query);

            $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE uid=".$id;
            jsjobs::$_data['companies'] = jsjobs::$_db->get_var($query);

            $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_departments` WHERE uid=".$id;
            jsjobs::$_data['department'] = jsjobs::$_db->get_var($query);

            $query = "SELECT COUNT(jobapply.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` as jobapply
            JOIN ".jsjobs::$_db->prefix."js_job_jobs AS job ON job.id = jobapply.jobid  WHERE job.uid=".$id;
            jsjobs::$_data['jobapply'] = jsjobs::$_db->get_var($query);
        }elseif(jsjobs::$_data[0]->roleid == 2){
            //jobseeker
            $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE uid=".$id;
            jsjobs::$_data['resume'] = jsjobs::$_db->get_var($query);

            $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` WHERE uid=".$id;
            jsjobs::$_data['coverletter'] = jsjobs::$_db->get_var($query);

            $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply`  WHERE uid=".$id;
            jsjobs::$_data['jobapply'] = jsjobs::$_db->get_var($query);
        }
        return ;
    }

    function getChangeRolebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $query = "SELECT a.*,a.created AS dated,u.user_login,u.id AS wpuid"
                . " FROM " . jsjobs::$_db->prefix . "js_job_users AS a"
                . " LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = a.uid"
                . " WHERE a.id = " . $c_id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function storeUserRole($data) {
        if (empty($data))
            return false;
        $row = JSJOBSincluder::getJSTable('users');
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->check()) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }
        return JSJOBS_SAVED;
    }

    function getUserIdByCompanyid(){
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $companyid = JSJOBSrequest::getVar('companyid');
        if(!is_numeric($companyid)) return false;
        $query = "SELECT uid FROM `".jsjobs::$_db->prefix."js_job_companies` WHERE id = ".$companyid;
        $companyid = jsjobs::$_db->get_var($query);
        return $companyid;
    }

    function getUserDetailsById($u_id){
        if (is_numeric($u_id) == false)
            return false;
        $query = "SELECT user.emailaddress AS email,CONCAT(first_name,' ',last_name) AS name,user.roleid "
                . " FROM " . jsjobs::$_db->prefix . "js_job_users AS user"
                . " WHERE user.id = " . $u_id;
        return jsjobsdb::get_row($query);
    }

    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'user') ) {
            die( 'Security check Failed' ); 
        }
        $jsjob_search_array = array();
        $jsjob_search_array['searchname'] = JSJOBSrequest::getVar('searchname');
        $jsjob_search_array['searchusername'] = JSJOBSrequest::getVar('searchusername');
        $jsjob_search_array['searchrole'] = JSJOBSrequest::getVar('searchrole');
        $jsjob_search_array['searchcompany'] = JSJOBSrequest::getVar('searchcompany');
        $jsjob_search_array['searchresume'] = JSJOBSrequest::getVar('searchresume');
        $jsjob_search_array['search_from_user'] = 1;
        return $jsjob_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'user') ) {
            die( 'Security check Failed' ); 
        }
        $jsjob_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_user']) && $wpjp_search_cookie_data['search_from_user'] == 1){
            $jsjob_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
            $jsjob_search_array['searchusername'] = $wpjp_search_cookie_data['searchusername'];
            $jsjob_search_array['searchrole'] = $wpjp_search_cookie_data['searchrole'];
            $jsjob_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
            $jsjob_search_array['searchresume'] = $wpjp_search_cookie_data['searchresume'];
        }
        return $jsjob_search_array;
    }

    function setSearchVariableForSearch($jsjob_search_array){
        jsjobs::$_search['user']['searchname'] = isset($jsjob_search_array['searchname']) ? $jsjob_search_array['searchname'] : null;
        jsjobs::$_search['user']['searchusername'] = isset($jsjob_search_array['searchusername']) ? $jsjob_search_array['searchusername'] : null;
        jsjobs::$_search['user']['searchrole'] = isset($jsjob_search_array['searchrole']) ? $jsjob_search_array['searchrole'] : null;
        jsjobs::$_search['user']['searchcompany'] = isset($jsjob_search_array['searchcompany']) ? $jsjob_search_array['searchcompany'] : null;
        jsjobs::$_search['user']['searchresume'] = isset($jsjob_search_array['searchresume']) ? $jsjob_search_array['searchresume'] : null;
    }

    function getMessagekey(){
        $key = 'user';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}
?>
