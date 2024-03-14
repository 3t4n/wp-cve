<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_captcha {

    function MJTC_getCaptchaForForm() {
        $rand = $this->MJTC_randomNumber();
        MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable($rand,'','majesticsupport_spamcheckid');
        $majesticsupport_rot13 = mt_rand(0, 1);
        MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable($majesticsupport_rot13,'','majesticsupport_rot13');

        $operator = 2;
        if ($operator == 2) {
            $tcalc = majesticsupport::$_config['owncaptcha_calculationtype'];
        }
        $max_value = 20;
        $negativ = 1;
        $operend_1 = mt_rand($negativ, $max_value);
        $operend_2 = mt_rand($negativ, $max_value);
        $operand = majesticsupport::$_config['owncaptcha_totaloperand'];
        if ($operand == 3) {
            $operend_3 = mt_rand($negativ, $max_value);
        }

        if (majesticsupport::$_config['owncaptcha_calculationtype'] == 2) { // Subtraction
            if (majesticsupport::$_config['owncaptcha_subtractionans'] == 1) {
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
            if ($majesticsupport_rot13 == 1) { // ROT13 coding
                if ($operand == 2) {
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(MJTC_majesticsupportphplib::MJTC_str_rot13(MJTC_majesticsupportphplib::MJTC_safe_encoding($operend_1 + $operend_2)),'','majesticsupport_spamcheckresult');
                } elseif ($operand == 3) {
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(MJTC_majesticsupportphplib::MJTC_str_rot13(MJTC_majesticsupportphplib::MJTC_safe_encoding($operend_1 + $operend_2 + $operend_3)),'','majesticsupport_spamcheckresult');
                }
            } else {
                if ($operand == 2) {
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(MJTC_majesticsupportphplib::MJTC_safe_encoding($operend_1 + $operend_2),'','majesticsupport_spamcheckresult');
                } elseif ($operand == 3) {
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(MJTC_majesticsupportphplib::MJTC_safe_encoding($operend_1 + $operend_2 + $operend_3),'','majesticsupport_spamcheckresult');
                }
            }
        } elseif ($tcalc == 2) { // Subtraction
            if ($majesticsupport_rot13 == 1) {
                if ($operand == 2) {
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(MJTC_majesticsupportphplib::MJTC_str_rot13(MJTC_majesticsupportphplib::MJTC_safe_encoding($operend_1 - $operend_2)),'','majesticsupport_spamcheckresult');
                } elseif ($operand == 3) {
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(MJTC_majesticsupportphplib::MJTC_str_rot13(MJTC_majesticsupportphplib::MJTC_safe_encoding($operend_1 - $operend_2 - $operend_3)),'','majesticsupport_spamcheckresult');
                }
            } else {
                if ($operand == 2) {
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(MJTC_majesticsupportphplib::MJTC_safe_encoding($operend_1 - $operend_2),'','majesticsupport_spamcheckresult');
                } elseif ($operand == 3) {
                    MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable(MJTC_majesticsupportphplib::MJTC_safe_encoding($operend_1 - $operend_2 - $operend_3),'','majesticsupport_spamcheckresult');
                }
            }
        }
        $add_string = "";
        $add_string .= '<div><label for="' . esc_attr($rand) . '">';

        if ($tcalc == 1) {
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . esc_html(__('Plus', 'majestic-support')) . ' ' . $operend_2 . ' ' . esc_html(__('Equals', 'majestic-support')) . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . esc_html(__('Plus', 'majestic-support')) . ' ' . $operend_2 . ' ' . esc_html(__('Plus', 'majestic-support')) . ' ' . $operend_3 . ' ' . esc_html(__('Equals', 'majestic-support')) . ' ';
            }
        } elseif ($tcalc == 2) {
            $converttostring = 0;
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . esc_html(__('Minus', 'majestic-support')) . ' ' . $operend_2 . ' ' . esc_html(__('Equals', 'majestic-support')) . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . esc_html(__('Minus', 'majestic-support')) . ' ' . $operend_2 . ' ' . esc_html(__('Minus', 'majestic-support')) . ' ' . $operend_3 . ' ' . esc_html(__('Equals', 'majestic-support')) . ' ';
            }
        }

        $add_string .= '</label>';
        $add_string .= '<input type="text" name="' . esc_attr($rand) . '" id="' . esc_attr($rand) . '" size="3" class="inputbox mjtc-support-recaptcha ' . esc_attr($rand) . '" value="" data-validation="required" />';
        $add_string .= '</div>';

        return $add_string;
    }

    function MJTC_randomNumber() {
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

    private function MJTC_performChecks() {
        $majesticsupport_rot13 = MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_getNotificationDatabySessionId('majesticsupport_rot13',true);
        if($majesticsupport_rot13 == 1){
            $spamcheckresult = MJTC_majesticsupportphplib::MJTC_safe_decoding(MJTC_majesticsupportphplib::MJTC_str_rot13(MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_getNotificationDatabySessionId('majesticsupport_spamcheckresult',true)));
        } else {
            $majesticsupport_spamcheckresult = MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_getNotificationDatabySessionId('majesticsupport_spamcheckresult',true);
            if ($majesticsupport_spamcheckresult != '') {
                $spamcheckresult = MJTC_majesticsupportphplib::MJTC_safe_decoding($majesticsupport_spamcheckresult);
            } else {
                $spamcheckresult = '';
            }
        }
        $spamcheck = MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_getNotificationDatabySessionId('majesticsupport_spamcheckid',true);
        $spamcheck = MJTC_request::MJTC_getVar($spamcheck, '', 'post');
        if (!is_numeric($spamcheckresult) || $spamcheckresult != $spamcheck) {
            return false; // Failed
        }
        return true;
    }

    function MJTC_checkCaptchaUserForm() {
        if (!$this->MJTC_performChecks())
            $return = 2;
        else
            $return = 1;
        return $return;
    }

}

?>
