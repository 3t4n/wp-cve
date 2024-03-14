<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScaptcha {

    function getCaptchaForForm() {
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('captcha');
        $rand = $this->randomNumber();
        JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable($rand,'','jsjobs_spamcheckid','captcha');
        $jsjobs_rot13 = mt_rand(0, 1);
        JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable($jsjobs_rot13,'','jsjobs_rot13','captcha');
        $operator = 2;
        if ($operator == 2) {
            $tcalc = $config_array['owncaptcha_calculationtype'];
        }
        $max_value = 20;
        $negativ = 1;
        $operend_1 = mt_rand($negativ, $max_value);
        $operend_2 = mt_rand($negativ, $max_value);
        $operand = $config_array['owncaptcha_totaloperand'];
        if ($operand == 3) {
            $operend_3 = mt_rand($negativ, $max_value);
        }

        if ($config_array['owncaptcha_calculationtype'] == 2) { // Subtraction
            if ($config_array['owncaptcha_subtractionans'] == 1) {
                $ans = $operend_1 - $operend_2;
                if ($ans < 0) {
                    $one = $operend_2;
                    $operend_2 = $operend_1;
                    $operend_1 = $one;
                }
                if ($operand == 3) {
                    $ans = $operend_1 - $operend_2 - $operend_3;
                    if ($ans < 0) {
                        if ($operend_1 < $operend_2) {
                            $one = $operend_2;
                            $operend_2 = $operend_1;
                            $operend_1 = $one;
                        }
                        if ($operend_1 < $operend_3) {
                            $one = $operend_3;
                            $operend_3 = $operend_1;
                            $operend_1 = $one;
                        }
                    }
                }
            }
        }

        if ($tcalc == 0)
            $tcalc = mt_rand(1, 2);

        if ($tcalc == 1) { // Addition
            if ($jsjobs_rot13 == 1) { // ROT13 coding
                if ($operand == 2) {
                    JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable(jsjobslib::jsjobs_str_rot13(jsjobslib::jsjobs_safe_encoding($operend_1 + $operend_2)),'','jsjobs_spamcheckresult','captcha');
                } elseif ($operand == 3) {
                    JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable(jsjobslib::jsjobs_str_rot13(jsjobslib::jsjobs_safe_encoding($operend_1 + $operend_2 + $operend_3)),'','jsjobs_spamcheckresult','captcha');
                }
            } else {
                if ($operand == 2) {
                    JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable(jsjobslib::jsjobs_safe_encoding($operend_1 + $operend_2),'','jsjobs_spamcheckresult','captcha');
                } elseif ($operand == 3) {
                    JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable(jsjobslib::jsjobs_safe_encoding($operend_1 + $operend_2 + $operend_3),'','jsjobs_spamcheckresult','captcha');
                }
            }
        } elseif ($tcalc == 2) { // Subtraction
            if ($jsjobs_rot13 == 1) {
                if ($operand == 2) {
                    JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable(jsjobslib::jsjobs_str_rot13(jsjobslib::jsjobs_safe_encoding($operend_1 - $operend_2)),'','jsjobs_spamcheckresult','captcha');
                } elseif ($operand == 3) {
                    JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable(jsjobslib::jsjobs_str_rot13(jsjobslib::jsjobs_safe_encoding($operend_1 - $operend_2 - $operend_3)),'','jsjobs_spamcheckresult','captcha');
                }
            } else {
                if ($operand == 2) {
                    JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable(jsjobslib::jsjobs_safe_encoding($operend_1 - $operend_2),'','jsjobs_spamcheckresult','captcha');
                } elseif ($operand == 3) {
                    JSJOBSincluder::getObjectClass('wpjobnotification')->addSessionNotificationDataToTable(jsjobslib::jsjobs_safe_encoding($operend_1 - $operend_2 - $operend_3),'','jsjobs_spamcheckresult','captcha');
                }
            }
        }
        $add_string = "";
        $add_string .= '<div><label for="' . $rand . '">';

        if ($tcalc == 1) {
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . __('Plus', 'js-jobs') . ' ' . $operend_2 . ' ' . __('Equals', 'js-jobs') . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . __('Plus', 'js-jobs') . ' ' . $operend_2 . ' ' . __('Plus', 'js-jobs') . ' ' . $operend_3 . ' ' . __('Equals', 'js-jobs') . ' ';
            }
        } elseif ($tcalc == 2) {
            $converttostring = 0;
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . __('Minus', 'js-jobs') . ' ' . $operend_2 . ' ' . __('Equals', 'js-jobs') . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . __('Minus', 'js-jobs') . ' ' . $operend_2 . ' ' . __('Minus', 'js-jobs') . ' ' . $operend_3 . ' ' . __('Equals', 'js-jobs') . ' ';
            }
        }

        $add_string .= '</label>';
        $add_string .= '<input type="text" name="' . $rand . '" id="' . $rand . '" size="3" class="inputbox ' . $rand . '" value="" data-validation="required" />';
        $add_string .= '</div>';

        return $add_string;
    }

    function randomNumber() {
        $pw = '';

        // first character has to be a letter
        $characters = range('a', 'z');
        $pw .= $characters[mt_rand(0, 25)];

        // other characters arbitrarily
        $numbers = range(0, 9);
        $characters = array_merge($characters, $numbers);

        $pw_length = mt_rand(4, 12);

        for ($i = 0; $i < $pw_length; $i++) {
            $pw .= $characters[mt_rand(0, 35)];
        }
        return $pw;
    }

    private function performChecks() {
        $jsjobs_rot13 = JSJOBSincluder::getObjectClass('wpjobnotification')->getNotificationDatabySessionId('jsjobs_rot13','captcha',true);
        if ($jsjobs_rot13 == 1) {
            $spamcheckresult = jsjobslib::jsjobs_safe_decoding(jsjobslib::jsjobs_str_rot13(JSJOBSincluder::getObjectClass('wpjobnotification')->getNotificationDatabySessionId('jsjobs_spamcheckresult','captcha',true)));
        } else {
            $spamcheckresult = jsjobslib::jsjobs_safe_decoding(JSJOBSincluder::getObjectClass('wpjobnotification')->getNotificationDatabySessionId('jsjobs_spamcheckresult','captcha',true));
        }
        $spamcheck = JSJOBSincluder::getObjectClass('wpjobnotification')->getNotificationDatabySessionId('jsjobs_spamcheckid','captcha',true);
        $spamcheck = JSJOBSrequest::getVar($spamcheck, '', 'post');
        if (!is_numeric($spamcheckresult) || $spamcheckresult != $spamcheck) {
            return false; // Failed
        }
        return true;
    }

    function checkCaptchaUserForm() {
        if (!$this->performChecks())
            $return = 2;
        else
            $return = 1;
        return $return;
    }

}

?>
