<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Controllers/PollController.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Controllers;

use DateTime;
use FelixTzWPModernPolls\Models\Polls;
use FelixTzWPModernPolls\Models\PollInfos;
use FelixTzWPModernPolls\Helpers\AppHelper;


class PollController
{
    public $polls;
    public $settings;
    public $pollInfos;
    public $lockController;
    public $settingsController;
    public $templateController;

    public function __construct()
    {
        $this->polls = new Polls();
        $this->pollInfos = new PollInfos();

        $this->lockController = new LockController();
        $this->settingsController = new SettingsController();
        $this->templateController = new TemplateController();

        $settings = $this->settingsController->getAll();
        $this->settings = $settings[0];
    }

    public function create($POST)
    {
        $_SESSION['mpp_lastPost'] = $POST;
        $_SESSION['mpp_error'] = [];
        $error = false;

        $question = wp_kses_post(trim($POST['mpp_question']));
        $content = wp_kses_post($POST['mpp_content']);
        $template = (int)sanitize_key($_POST['mpp_template']);

        $showResultNever = isset($_POST['mpp_showResultNever']) ? (int)sanitize_key($_POST['mpp_showResultNever']) : 0;
        $showResultBefore = isset($_POST['mpp_showResultBefore']) ? (int)sanitize_key($_POST['mpp_showResultBefore']) : 0;
        $showResultAfter = isset($_POST['mpp_showResultAfter']) ? (int)sanitize_key($_POST['mpp_showResultAfter']) : 0;

        if ($showResultNever == -1) {
            $_SESSION['mpp_lastPost']['mpp_showResult'] = -1;
            $showresult = -1;
        } elseif ($showResultBefore == 1) {
            $_SESSION['mpp_lastPost']['mpp_showResult'] = 1;
            $showresult = 1;
        } else {
            $_SESSION['mpp_lastPost']['mpp_showResult'] = 0;
            $showresult = 0;
        }

        // Poll Start Date
        $start_day = (int)sanitize_key($_POST['mpp_start_day']);
        $start_month = (int)sanitize_key($_POST['mpp_start_month']);
        $start_year = (int)sanitize_key($_POST['mpp_start_year']);
        $start_hour = (int)sanitize_key($_POST['mpp_start_hour']);
        $start_minute = (int)sanitize_key($_POST['mpp_start_minute']);
        $start_second = (int)sanitize_key($_POST['mpp_start_second']);
        $start = gmmktime($start_hour, $start_minute, $start_second, $start_month, $start_day, $start_year);
        if ($start > current_time('timestamp')) {
            $active = -1;
        } else {
            $active = 1;
        }

        // Poll End Date
        $expire = isset($_POST['mpp-expire']) ? (int)sanitize_key($_POST['mpp-expire']) : 0;
        if ($expire === 1) {
            $end = '';
        } else {
            $end_day = (int)sanitize_key($_POST['mpp_end_day']);
            $end_month = (int)sanitize_key($_POST['mpp_end_month']);
            $end_year = (int)sanitize_key($_POST['mpp_end_year']);
            $end_hour = (int)sanitize_key($_POST['mpp_end_hour']);
            $end_minute = (int)sanitize_key($_POST['mpp_end_minute']);
            $end_second = (int)sanitize_key($_POST['mpp_end_second']);
            $end = gmmktime($end_hour, $end_minute, $end_second, $end_month, $end_day, $end_year);
            if ($end <= current_time('timestamp')) {
                $active = 0;
            }
        }

        $_SESSION['mpp_lastPost']['mpp_start'] = $start;
        $_SESSION['mpp_lastPost']['mpp_end'] = $end;

        if (empty($question)) {
            $error = true;
            $_SESSION['mpp_error']['question'] = true;
        }

        /*
         * _POST mpp_answers is / should be an array sanitize follows for each value
         */
        $answers = $_POST['mpp_answers'];

        if (!is_array($answers)) {
            $error = true;
            $_SESSION['mpp_error']['answerNoArray'] = true;
        } else {
            $answerCount = 0;
            foreach ($answers as $answer) {
                $answer = sanitize_text_field($answer);
                if (empty($answer)) {
                    $error = true;
                    $_SESSION['mpp_error']['answer'][$answerCount] = true;
                }
                $answerCount++;
            }
        }
        if ($error == true) {
            return -1;
        }

        // Mutilple Poll
        $multiple_allow = (int)sanitize_key($_POST['mpp_multiple_allow']);
        if ($multiple_allow === 1) {
            $multiple = (int)sanitize_key($_POST['mpp_multiple']);
        } else {
            $multiple = 0;
        }

        $insertId = $this->polls->insert($question, $content, $start, $end, $multiple, $active, $showresult, $template);

        if (!$insertId) return $insertId;

        // Add Poll Answers
        /*
         * _POST mpp_answers is / should be an array sanitize follows for each value
         */
        $answers = $_POST['mpp_answers'];
        foreach ($answers as $answer) {
            $answer = sanitize_text_field($answer);
            if (!empty($answer)) {
                if (!$this->pollInfos->insert($insertId, $answer)) {
                    return 'Error: 67890';
                }
            }
        }

        return $insertId;
    }

    public function edit($POST)
    {
        $_SESSION['mpp_lastPost'] = $POST;
        $_SESSION['mpp_error'] = [];
        $error = false;

        $id = (int)sanitize_key($_POST['mpp_id']);
        $question = wp_kses_post(trim($POST['mpp_question']));
        $content = wp_kses_post($POST['mpp_content']);
        $template = (int)sanitize_key($_POST['mpp_template']);

        $showResultNever = isset($_POST['mpp_showResultNever']) ? (int)sanitize_key($_POST['mpp_showResultNever']) : 0;
        $showResultBefore = isset($_POST['mpp_showResultBefore']) ? (int)sanitize_key($_POST['mpp_showResultBefore']) : 0;
        $showResultAfter = isset($_POST['mpp_showResultAfter']) ? (int)sanitize_key($_POST['mpp_showResultAfter']) : 0;

        if ($showResultNever == -1) {
            $_SESSION['mpp_lastPost']['mpp_showResult'] = -1;
            $showresult = -1;
        } elseif ($showResultBefore == 1) {
            $_SESSION['mpp_lastPost']['mpp_showResult'] = 1;
            $showresult = 1;
        } else {
            $_SESSION['mpp_lastPost']['mpp_showResult'] = 0;
            $showresult = 0;
        }

        // Poll Start Date
        $start_day = (int)sanitize_key($_POST['mpp_start_day']);
        $start_month = (int)sanitize_key($_POST['mpp_start_month']);
        $start_year = (int)sanitize_key($_POST['mpp_start_year']);
        $start_hour = (int)sanitize_key($_POST['mpp_start_hour']);
        $start_minute = (int)sanitize_key($_POST['mpp_start_minute']);
        $start_second = (int)sanitize_key($_POST['mpp_start_second']);
        $start = gmmktime($start_hour, $start_minute, $start_second, $start_month, $start_day, $start_year);
        if ($start > current_time('timestamp')) {
            $active = -1;
        } else {
            $active = 1;
        }

        // Poll End Date
        $expire = isset($_POST['mpp-expire']) ? (int)sanitize_key($_POST['mpp-expire']) : 0;
        if ($expire === 1) {
            $end = '';
        } else {
            $end_day = (int)sanitize_key($_POST['mpp_end_day']);
            $end_month = (int)sanitize_key($_POST['mpp_end_month']);
            $end_year = (int)sanitize_key($_POST['mpp_end_year']);
            $end_hour = (int)sanitize_key($_POST['mpp_end_hour']);
            $end_minute = (int)sanitize_key($_POST['mpp_end_minute']);
            $end_second = (int)sanitize_key($_POST['mpp_end_second']);
            $end = gmmktime($end_hour, $end_minute, $end_second, $end_month, $end_day, $end_year);
            if ($end <= current_time('timestamp')) {
                $active = 0;
            }
        }

        $_SESSION['mpp_lastPost']['mpp_start'] = $start;
        $_SESSION['mpp_lastPost']['mpp_end'] = $end;

        if (empty($question)) {
            $error = true;
            $_SESSION['mpp_error']['question'] = true;
        }

        /*
         * _POST mpp_answers is / should be an array sanitize follows for each value
         */
        $answers = $_POST['mpp_answers'];
        $answerCount = 0;

        if (!is_array($answers)) {
            $error = true;
            $_SESSION['mpp_error']['answerNoArray'] = true;
        } else {
            foreach ($answers as $answer) {
                $answer = sanitize_text_field($answer);
                if (empty($answer)) {
                    $error = true;
                    $_SESSION['mpp_error']['answer'][$answerCount] = true;
                }
                $answerCount++;
            }
        }
        if ($error == true) {
            return -1;
        }

        // Mutilple Poll
        $multiple_allow = (int)sanitize_key($_POST['mpp_multiple_allow']);
        if ($multiple_allow === 1) {
            $multiple = (int)sanitize_key($_POST['mpp_multiple']);
        } else {
            $multiple = 0;
        }

        $this->polls->update($id, $question, $content, $start, $end, $multiple, $active, $showresult, $template);

        // Add Poll Answers
        /*
         * _POST mpp_answers is / should be an array sanitize follows for each value
         */
        $answers = $_POST['mpp_answers'];
        $this->pollInfos->delete($id);
        foreach ($answers as $answer) {
            $answer = sanitize_text_field($answer);
            if (!empty($answer)) {
                if (!$this->pollInfos->insert($id, $answer)) {
                    return 'Error: 67890';
                }
            }
        }

        return 1;
    }

    public function getPollList()
    {
        return $this->polls->getAll();
    }

    public function getPoll($id)
    {
        return $this->polls->get($id);
    }

    public function getPollAnswers($id)
    {
        return $this->pollInfos->getAnswers($id);
    }

    public function getPollAnswerInfos($id)
    {
        return $this->pollInfos->getAnswerInfos($id);
    }

    public function getResultHandle($id)
    {
        $poll = $this->polls->get($id);
        return $poll->showresult;
    }

    public function isOpen($id)
    {
        $poll = $this->polls->get($id);
        if ($poll && $poll->active) {

            if (!empty($poll->expiry) && current_time('timestamp') >= $poll->expiry) {
                $this->polls->close($id);
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $this->polls->delete($id);
        $this->pollInfos->delete($id);
    }

    public function showPoll($id)
    {
        $poll = $this->polls->get($id);

        if ($poll->multiple <= 1) {
            $startTag = '{{answerTemplate_single}}';
            $endTag = '{{/answerTemplate_single}}';
            $removeStartTag = '{{answerTemplate_multi}}';
            $removeEndTag = '{{/answerTemplate_multi}}';
            $answerTag = 'name="answer"';
        } else {
            $startTag = '{{answerTemplate_multi}}';
            $endTag = '{{/answerTemplate_multi}}';
            $removeStartTag = '{{answerTemplate_single}}';
            $removeEndTag = '{{/answerTemplate_single}}';
            $answerTag = 'name="answers[]"';
        }

        $answers = $this->pollInfos->getAnswerInfos($id);
        $template = $this->templateController->get($poll->template_id);
        wp_enqueue_style('mpp-template-' . $template->dir, plugins_url('resources/views/templates/' . $template->dir . '/' . $template->dir . '.css', FelixTzWPModernPollsFile), false, FelixTzWPModernPollsVersion, 'all');
        $templateHTML = file_get_contents(FelixTzWPModernPollsDir . 'resources/views/templates/' . $template->dir . '/vote.html');
        $templateHTML = preg_replace($this->regex('#', $removeStartTag, $removeEndTag), '', $templateHTML);


        preg_match($this->regex('#', $startTag, $endTag), $templateHTML, $answerTemplate);

        $answerString = '';
        foreach ($answers as $answer) {
            $answerString .= str_replace(["{{answerText}}", "{{answerTag}}", "{{answerTagID}}", "{{answerID}}"],
                [$answer->answer, $answerTag, 'mpp-answer_' . $answer->id, $answer->id],
                $answerTemplate[1]);
        }

        $hash = uniqid();

        $idField = '<input type="hidden" id="mpp_id" value="'.$id.'" />';
        $nonceField = '<input type="hidden" id="mpp_nonce"  value="' . wp_create_nonce("mpp-nonce_" . $hash) . '" />';

        $templateHTML = str_replace(
            ["{{hash}}", "{{id}}", "{{idField}}", "{{nonceField}}", "{{question}}", "{{maxChecked}}"],
            [$hash, $id, $idField, $nonceField, $poll->question, $poll->multiple],
            $templateHTML
        );

        if ($poll->showresult == 1) {
            $resultButton = '<button onclick="mpp_result(\'' . $hash . '\')">' . __('Results', FelixTzWPModernPollsTextdomain) . '</button>';
        } else {
            $resultButton = '';
        }

        $templateHTML = str_replace(
            ['{{voteButton}}', '{{resultButton}}'],
            [
                '<button onclick="mpp_vote(\'' . $hash . '\')">' . __('Vote', FelixTzWPModernPollsTextdomain) . '</button>',
                $resultButton
            ],
            $templateHTML
        );

        return preg_replace($this->regex('#', $startTag, $endTag), $answerString, $templateHTML);
    }

    public function showDisabled($id, $ajax = false)
    {
        $poll = $this->polls->get($id);

        if ($poll->multiple <= 1) {
            $startTag = '{{answerTemplate_single}}';
            $endTag = '{{/answerTemplate_single}}';
            $removeStartTag = '{{answerTemplate_multi}}';
            $removeEndTag = '{{/answerTemplate_multi}}';
            $answerTag = 'disabled';
        } else {
            $startTag = '{{answerTemplate_multi}}';
            $endTag = '{{/answerTemplate_multi}}';
            $removeStartTag = '{{answerTemplate_vote}}';
            $removeEndTag = '{{/answerTemplate_vote}}';
            $answerTag = 'disabled';
        }

        $answers = $this->pollInfos->getAnswerInfos($id);
        $template = $this->templateController->get($poll->template_id);
        if (!$ajax) {
            wp_enqueue_style('mpp-template', plugins_url('resources/views/templates/' . $template->dir . '/' . $template->dir . '.css', FelixTzWPModernPollsView), false, FelixTzWPModernPollsVersion, 'all');
        }
        $templateHTML = file_get_contents(FelixTzWPModernPollsDir . 'resources/views/templates/' . $template->dir . '/vote.html');
        $templateHTML = preg_replace($this->regex('#', $removeStartTag, $removeEndTag), '', $templateHTML);

        preg_match($this->regex('#', $startTag, $endTag), $templateHTML, $answerTemplate);

        $answerString = '';
        foreach ($answers as $answer) {
            $answerString .= str_replace(["{{answerText}}", "{{answerTag}}", "{{answerTagID}}", "{{answerID}}"],
                [$answer->answer, $answerTag, 'mpp-answer_' . $answer->id, $answer->id],
                $answerTemplate[1]);
        }

        $nonceField = '<input type="hidden"/>';

        $templateHTML = str_replace(
            ["{{id}}", "{{nonceField}}", "{{question}}"],
            [$id, $nonceField, $poll->question],
            $templateHTML
        );

        $templateHTML = str_replace(
            ['{{voteButton}}', '{{resultButton}}'],
            ['', ''],
            $templateHTML
        );

        return preg_replace($this->regex('#', $startTag, $endTag), $answerString, $templateHTML);
    }

    public function showResult($id, $hash, $ajax = false)
    {
        $poll = $this->polls->get($id);

        $answers = $this->pollInfos->getAnswerInfos($id);
        $template = $this->templateController->get($poll->template_id);
        if (!$ajax) {
            wp_enqueue_style('mpp-template', plugins_url('resources/views/templates/' . $template->dir . '/' . $template->dir . '.css', FelixTzWPModernPollsFile), false, FelixTzWPModernPollsVersion, 'all');
        }
        $templateHTML = file_get_contents(FelixTzWPModernPollsDir . 'resources/views/templates/' . $template->dir . '/result.html');

        $answerNames   = '';
        $answerData    = '';
        $answerDataSet = '';
        $isFirst = true;
        foreach ($answers as $answer) {
            if ($isFirst) {
                $answerNames = '"' . $answer->answer . '"';
                $answerData = '"' . $answer->votes . '"';
                $isFirst = false;
            } else {
                $answerNames .= ', "' . $answer->answer . '"';
                $answerData .= ', "' . $answer->votes . '"';
            }
        }

        if (!$ajax) {
            $backButton = '';
        } else {
            $backButton = '<button onclick="mpp_result(\'' . $hash . '\', true)">' . __('Back to Vote', FelixTzWPModernPollsTextdomain) . '</button>';
        }

        $templateHTML = str_replace(
            ['{{hash}}', '{{question}}', '{{backButton}}', '{{answerNames}}', '{{answerData}}'],
            [
                $hash,
                $poll->question,
                $backButton,
                $answerNames,
                $answerData
            ],
            $templateHTML
        );

        return $templateHTML;
    }

    public function open($id)
    {
        return $this->polls->open($id);
    }

    public function close($id)
    {
        return $this->polls->close($id);
    }

    public function vote($id, $hash, $answers, $userIdent, $userID)
    {
        $poll = $this->polls->get($id);
        if ($this->polls->isOpen($id) > 0) {
            if (!$this->lockController->hasVoted($id)) {
                if (!empty($userIdent)) {
                    $user = $userIdent;
                } else {
                    $user = __('Guest', FelixTzWPModernPollsTextdomain);
                }

                $answerCount = count($answers);
                if ($answerCount < 1) {
                    printf(_e('Error, please Contact the Administrator and tell him the the Error-Code %s. Thank you.', FelixTzWPModernPollsTextdomain), 'PC-001');
                    exit();
                }

                $user = sanitize_text_field($user);
                $userID = (int)$userID;
                $ip = AppHelper::getIpAddress();
                $host = @gethostbyaddr($ip);
                $timestamp = current_time('timestamp');

                $lock_ip = $this->settings->log_ip;
                $lock_cookie = $this->settings->log_cookie;
                $lock_user = $this->settings->log_user;

                if ( (int) $lock_cookie === 1) {
                    setcookie('mpp_' . $id, '1', time() + ( (int) $this->settings->log_expire * 60), "/");
                }

                $i = 0;
                foreach ($answers as $answer) {
                    $answer = sanitize_text_field($answer);
                    $qryUpdate = $this->pollInfos->vote($id, $answer);
                    if (!$qryUpdate) {
                        unset($answers[$i]);
                    }
                    $i++;
                }
                $qryUpdate = $this->polls->vote($id, $answerCount);
                if ($qryUpdate) {
                    foreach ($answers as $answer) {
                        $this->lockController->add($id, $answer, $ip, $host, $timestamp, $user, $userID);
                    }

                    switch ($poll->showresult) {
                        case -1 :
                            return $this->showSuccess($id);

                        case 0:
                        case 1 :
                            echo $this->showResult($id, $hash);
                            break;

                    }

                } else {
                    printf(__('Error, please Contact the Administrator and tell him the the Error-Code %s. Thank you.', FelixTzWPModernPollsTextdomain), 'PC-002');
                } // End if($vote_a)
            } else {
                printf(__('Error, please Contact the Administrator and tell him the the Error-Code %s. Thank you.', FelixTzWPModernPollsTextdomain), 'PC-003');
            } // End if($check_voted)
        } else {
            printf(__('Error, please Contact the Administrator and tell him the the Error-Code %s. Thank you.', FelixTzWPModernPollsTextdomain), 'PC-004');
        }  // End if($is_poll_open > 0)

    }

    public function showSuccess($id)
    {
        $poll = $this->polls->get($id);
        $template = $this->templateController->get($poll->template_id);

        wp_enqueue_style('mpp-template', plugins_url('resources/views/templates/' . $template->dir . '/' . $template->dir . '.css', FelixTzWPModernPollsFile), false, FelixTzWPModernPollsVersion, 'all');
        $templateHTML = file_get_contents(FelixTzWPModernPollsDir . 'resources/views/templates/' . $template->dir . '/success.html');

        $templateHTML = str_replace(
            ["{{id}}", "{{question}}"],
            [$id, $poll->question],
            $templateHTML
        );

        return $templateHTML;
    }

    public function regex($delimiter, $startTag, $endTag)
    {
        return $delimiter . preg_quote($startTag, $delimiter)
            . '(.*?)'
            . preg_quote($endTag, $delimiter)
            . $delimiter
            . 's';
    }
}