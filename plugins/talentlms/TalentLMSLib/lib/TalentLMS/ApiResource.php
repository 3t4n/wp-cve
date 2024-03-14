<?php

abstract class TalentLMS_ApiResource
{

    protected static function _scopedRetrieve($class, $id)
    {
        $url = self::_instanceUrl($class, $id);
        $response = TalentLMS_ApiRequestor::request('get', $url);
        
        return $response;
    }
    
    protected static function _scopedAll($class)
    {
        $url = self::_classUrl($class);
        $response = TalentLMS_ApiRequestor::request('get', $url);
        
        return $response;
    }
    
    protected static function _scopedLogin($class, $params)
    {
        self::_validateCall('login', $class, $params);
        $url = self::_postUrl('login');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
        
        return $response;
    }
    
    protected static function _scopedLogout($class, $params)
    {
        self::_validateCall('logout', $class, $params);
        $url = self::_postUrl('logout');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedSignup($class, $params)
    {
        self::_validateCall('signup', $class, $params);
        $url = self::_postUrl('signup');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedAddUserToCourse($class, $params)
    {
        self::_validateCall('addUser', $class, $params);
        $url = self::_postUrl('addUser');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedRemoveUserFromCourse($class, $params)
    {
        $url = self::_instanceUrlByParams('removeUserFromCourse', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }

    protected static function _scopedResetUserProgress($class, $params)
    {
        $url = self::_instanceUrlByParams('resetUserProgress', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);

        return $response;
    }
    
    protected static function _scopedGotoCourse($class, $params)
    {
        $url = self::_instanceUrlByParams('gotoCourse', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
        
        return $response;
    }
    
    protected static function _scopedBuyCourse($class, $params)
    {
        self::_validateCall('buyCourse', $class, $params);
        $url = self::_postUrl('buyCourse');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedBuyCategoryCourses($class, $params)
    {
        self::_validateCall('buyCategoryCourses', $class, $params);
        $url = self::_postUrl('buyCategoryCourses');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedRetrieveLeafsAndCourses($class, $id)
    {
        $url = self::_instanceUrlByMethodName('leafsAndCourses', $id);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedGetCustomRegistrationFields($class)
    {
        $url = self::_classUrlByMethodName('customRegistrationFields');
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedSetUserStatus($class, $params)
    {
        $url = self::_instanceUrlByParams('userSetStatus', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
        
        return $response;
    }

    protected static function _scopedSetBranchStatus($class, $params)
    {
        $url = self::_instanceUrlByParams('branchSetStatus', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);

        return $response;
    }
    
    protected static function _scopedAddUserToGroup($class, $params)
    {
        $url = self::_instanceUrlByParams('addUserToGroup', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedRemoveUserFromGroup($class, $params)
    {
        $url = self::_instanceUrlByParams('removeUserFromGroup', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedAddCourseToGroup($class, $params)
    {
        $url = self::_instanceUrlByParams('addCourseToGroup', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedAddUserToBranch($class, $params)
    {
        $url = self::_instanceUrlByParams('addUserToBranch', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }

    protected static function _scopedRemoveUserFromBranch($class, $params)
    {
        $url = self::_instanceUrlByParams('removeUserFromBranch', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);

        return $response;
    }
    
    protected static function _scopedAddCourseToBranch($class, $params)
    {
        $url = self::_instanceUrlByParams('addCourseToBranch', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedForgotUsername($class, $params)
    {
        $url = self::_instanceUrlByParams('forgotUsername', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedForgotPassword($class, $params)
    {
        $url = self::_instanceUrlByParams('forgotPassword', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedExtendedUserRetrieve($class, $params)
    {
        $url = self::_instanceUrlByParams('users', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
        
        return $response;
    }
    
    protected static function _scopedGetRateLimit($class)
    {
        $url = self::_instanceUrlByParams('getRateLimit', array());
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedGetUsersProgressInUnits($class, $params)
    {
        $url = self::_instanceUrlByParams('getUsersProgressInUnits', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedGetTestAnswers($class, $params)
    {
        $url = self::_instanceUrlByParams('getTestAnswers', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedGetSurveyAnswers($class, $params)
    {
        $url = self::_instanceUrlByParams('getSurveyAnswers', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }

    protected static function _scopedGetIltSessions($class, $params)
    {
        $url = self::_instanceUrlByParams('getIltSessions', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);

        return $response;
    }
    
    protected static function _scopedCreateCourse($class, $params)
    {
        self::_validateCall('create', $class, $params);
        $url = self::_postUrl('createCourse');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedCreateGroup($class, $params)
    {
        self::_validateCall('create', $class, $params);
        $url = self::_postUrl('createGroup');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedCreateBranch($class, $params)
    {
        self::_validateCall('create', $class, $params);
        $url = self::_postUrl('createBranch');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedEditUser($class, $params)
    {
        self::_validateCall('editUser', $class, $params);
        $url = self::_postUrl('editUser');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);
    
        return $response;
    }
    
    protected static function _scopedGetUsersByCustomField($class, $params)
    {
        $url = self::_instanceUrlByParams('getUsersByCustomField', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }
    
    protected static function _scopedGetUserStatusInCourse($class, $params)
    {
        $url = self::_instanceUrlByParams('getUserStatusInCourse', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }

    protected static function _scopedGetCustomCourseFields($class)
    {
        $url = self::_classUrlByMethodName('customCourseFields');
        $response = TalentLMS_ApiRequestor::request('get', $url);

        return $response;
    }

    protected static function _scopedGetCoursesByCustomField($class, $params)
    {
        $url = self::_instanceUrlByParams('getCoursesByCustomField', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);

        return $response;
    }
    
    protected static function _scopedGetTimeline($class, $params)
    {
        $url = self::_instanceUrlByParams('getTimeline', $params);
        $response = TalentLMS_ApiRequestor::request('get', $url);
    
        return $response;
    }

    protected static function _scopedDeleteGroup($class, $params)
    {
        self::_validateCall('delete', $class, $params);
        $url = self::_postUrl('deleteGroup');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);

        return $response;
    }

    protected static function _scopedDeleteBranch($class, $params)
    {
        self::_validateCall('delete', $class, $params);
        $url = self::_postUrl('deleteBranch');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);

        return $response;
    }

    protected static function _scopedDeleteCourse($class, $params)
    {
        self::_validateCall('delete', $class, $params);
        $url = self::_postUrl('deleteCourse');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);

        return $response;
    }

    protected static function _scopedDeleteUser($class, $params)
    {
        self::_validateCall('delete', $class, $params);
        $url = self::_postUrl('deleteUser');
        $response = TalentLMS_ApiRequestor::request('post', $url, $params);

        return $response;
    }
    
    protected static function _instanceUrl($class, $id)
    {
        $base = self::_classUrl($class);
        $url = $base."/id:".$id;
        
        return $url;
    }
    
    protected static function _classUrl($class)
    {
        $class = str_replace('TalentLMS_', '', $class);
        $class = strtolower($class);
        
        if ($class == 'user') {
            return "/users";
        } elseif ($class == 'course') {
            return "/courses";
        } elseif ($class == 'category') {
            return "/categories";
        } elseif ($class == 'branch') {
            return "/branches";
        } elseif ($class == 'group') {
            return "/groups";
        } elseif ($class == 'siteinfo') {
            return "/siteinfo";
        }
    }
    
    protected static function _instanceUrlByMethodName($method, $id)
    {
        $base = self::_classUrlByMethodName($method);
        $url = $base."/id:".$id;
    
        return $url;
    }
    
    protected static function _instanceUrlByParams($method, $params)
    {
        $base = self::_classUrlByMethodName($method);
        $url = $base."/";
        
        foreach ($params as $key => $value) {
            if ($key == 'logout_redirect' || $key == 'course_completed_redirect' || $key == 'redirect_url' || $key == 'domain_url') {
                $url .= $key.':'.base64_encode($value).',';
            } else {
                $url .= $key.':'.$value.',';
            }
        }
        
        $url = trim($url, ',');
    
        return $url;
    }
    
    protected static function _classUrlByMethodName($method)
    {
        if ($method == 'leafsAndCourses') {
            return "/categoryleafsandcourses";
        } elseif ($method == 'customRegistrationFields') {
            return "/getcustomregistrationfields";
        } elseif ($method == 'userSetStatus') {
            return "/usersetstatus";
        } elseif ($method == 'branchSetStatus') {
            return "/branchsetstatus";
        } elseif ($method == 'gotoCourse') {
            return "/gotocourse";
        } elseif ($method == 'addUserToGroup') {
            return "/addusertogroup";
        } elseif ($method == 'removeUserFromGroup') {
            return "/removeuserfromgroup";
        } elseif ($method == 'addCourseToGroup') {
            return "/addcoursetogroup";
        } elseif ($method == 'addUserToBranch') {
            return "/addusertobranch";
        } elseif ($method == 'removeUserFromBranch') {
            return "/removeuserfrombranch";
        } elseif ($method == 'addCourseToBranch') {
            return "/addcoursetobranch";
        } elseif ($method == 'forgotUsername') {
            return "/forgotusername";
        } elseif ($method == 'forgotPassword') {
            return "/forgotpassword";
        } elseif ($method == 'users') {
            return "/users";
        } elseif ($method == 'getRateLimit') {
            return "/ratelimit";
        } elseif ($method == 'getUsersProgressInUnits') {
            return "/getusersprogressinunits";
        } elseif ($method == 'getTestAnswers') {
            return "/gettestanswers";
        } elseif ($method == 'getSurveyAnswers') {
            return "/getsurveyanswers";
        } elseif ($method == 'getIltSessions') {
            return "/getiltsessions";
        } elseif ($method == 'getUserStatusInCourse') {
            return "/getuserstatusincourse";
        } elseif ($method == 'customCourseFields') {
            return "/getcustomcoursefields";
        } elseif ($method == 'getCoursesByCustomField') {
            return "/getcoursesbycustomfield";
        } elseif ($method == 'getTimeline') {
            return "/gettimeline";
        } elseif ($method == 'removeUserFromCourse') {
            return "/removeuserfromcourse";
        } elseif ($method == 'resetUserProgress') {
            return "/resetuserprogress";
        } elseif ($method == 'getUsersByCustomField') {
            return "/getusersbycustomfield";
        }
    }
    
    protected static function _postUrl($method)
    {
        if ($method == 'login') {
            return "/userlogin";
        } elseif ($method == 'logout') {
            return "/userlogout";
        } elseif ($method == 'addUser') {
            return "/addusertocourse";
        } elseif ($method == 'signup') {
            return "/usersignup";
        } elseif ($method == 'buyCourse') {
            return "/buycourse";
        } elseif ($method == 'buyCategoryCourses') {
            return "/buycategorycourses";
        } elseif ($method == 'createCourse') {
            return "/createcourse";
        } elseif ($method == 'createGroup') {
            return "/creategroup";
        } elseif ($method == 'createBranch') {
            return "/createbranch";
        } elseif ($method == 'editUser') {
            return "/edituser";
        } elseif ($method == 'deleteGroup') {
            return "/deletegroup";
        } elseif ($method == 'deleteBranch') {
            return "/deletebranch";
        } elseif ($method == 'deleteCourse') {
            return "/deletecourse";
        } elseif ($method == 'deleteUser') {
            return "/deleteuser";
        }
    }
    
    private static function _validateCall($method, $class, $params = null)
    {
        if ($params && !is_array($params)) {
            throw new TalentLMS_ApiError("You must pass an array as the first argument to ".$class.'::'.$method."() method calls.");
        }
    }
}
