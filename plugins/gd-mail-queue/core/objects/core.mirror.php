<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_mirror_phpmailer {
    private $_extras_keys = array(
        'CharSet',
        'ContentType',
        'Encoding',
        'From',
        'FromName',
        'Sender'
    );

    private $_smtp_keys = array(
        'Host' => '',
        'Port' => '',
        'Helo' => '',
        'SMTPSecure' => '',
        'SMTPAutoTLS' => true,
        'SMTPAuth' => false,
        'SMTPOptions' => array(),
        'Username' => '',
        'Password' => '',
        'AuthType' => '',
        'Timeout' => 300,
        'SMTPDebug' => 0,
        'SMTPKeepAlive' => false
    );

    public $to = array();
    public $cc = array();
    public $bcc = array();
    public $reply_to = array();

    public $headers = array();
    public $attachments = array();
    public $extras = array();
    public $mailer = array();

    public $subject = '';
    public $body = '';
    public $alt_body = '';

    public $type = 'mail';
    public $is_html = false;

    /** @param PHPMailer\PHPMailer\PHPMailer $phpmailer
      * @param string $type */
    public function __construct($phpmailer, $type = 'mail') {
        $this->to = $phpmailer->getToAddresses();
        $this->cc = $phpmailer->getCcAddresses();
        $this->bcc = $phpmailer->getBccAddresses();
        $this->reply_to = $phpmailer->getReplyToAddresses();

        $this->headers = $phpmailer->getCustomHeaders();
        $this->attachments = $phpmailer->getAttachments();

        foreach ($this->_extras_keys as $key) {
            $value = $phpmailer->$key;

            if (!empty($value)) {
                $this->extras[$key] = $value;
            }
        }

        $this->is_html = isset($this->extras['ContentType']) && $this->extras['ContentType'] == 'text/html';

        $this->subject = $phpmailer->Subject;
        $this->body = $phpmailer->Body;
        $this->alt_body = $phpmailer->AltBody;

        if (!$this->is_html) {
            $this->is_html = !empty($this->body) && !empty($this->alt_body);
        }

        $this->type = $type;

        $this->mailer['Mailer'] = $phpmailer->Mailer;

        if ($phpmailer->Mailer == 'smtp') {
            foreach ($this->_smtp_keys as $key => $default) {
                $value = $phpmailer->$key;

                if ($value !== $default) {
                    $this->mailer[$key] = $value;
                }
            }
        }
    }

    public function is_html() {
        return $this->is_html;
    }

    public function is_queue_eligible($queue = 'all') {
        switch ($queue) {
            case 'all':
                $is = true;
                break;
            case 'cc':
                $is = !empty($this->cc);
                break;
            case 'bcc':
                $is = !empty($this->bcc);
                break;
            case 'ccbcc':
                $is = !empty($this->cc) || !empty($this->bcc);
                break;
            default:
                $is = count($this->to) > 1;
                break;
        }

        return apply_filters('gdmaq_phpmailer_is_eligible', $is, $this, $queue);
    }

    public function get_sender() {
        return isset($this->extras['Sender']) ? isset($this->extras['Sender']) : '';
    }

    public function get_from() {
        if (isset($this->extras['From'])) {
            return array(
                $this->extras['From'],
                isset($this->extras['FromName']) ? $this->extras['FromName'] : '');
        } else {
            return array();
        }
    }

    public function get_recipients() {
        $list = $this->to;

        if (!empty($this->cc)) {
            $list = array_merge($list, $this->cc);
        }

        if (!empty($this->bcc)) {
            $list = array_merge($list, $this->bcc);
        }

        return $list;
    }

    public function get_emails_by_type() {
        $list = array(
            'from' => array($this->get_from()),
            'to' => $this->to);

        if (!empty($this->cc)) {
            $list['cc'] = $this->cc;
        }

        if (!empty($this->bcc)) {
            $list['bcc'] = $this->bcc;
        }

        if (!empty($this->reply_to)) {
            $list['reply_to'] = $this->reply_to;
        }

        return $list;
    }

    public function get_attachments() {
        $list = array();

        foreach ($this->attachments as $file) {
            $list[] = $file[0];
        }

        return $list;
    }

    public function add_to_queue() {
        $extras = $this->extras;

        if (!empty($this->reply_to)) {
            $extras['ReplyTo'] = array_values($this->reply_to);
        }

        $args = array(
            'to' => $this->get_recipients(),
            'type' => $this->type,
            'subject' => $this->subject,
            'plain' => $this->is_html() ? $this->alt_body : $this->body,
            'html' => $this->is_html() ? $this->body : '',
            'headers' => $this->headers,
            'attachments' => $this->get_attachments(),
            'extras' => $extras
        );

        gdmaq_mail_to_queue($args);
    }

    public function get_email_object() {
        return new gdmaq_core_email('mirror', $this);
    }
}
