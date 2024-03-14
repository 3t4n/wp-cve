<?php
if (!defined('ABSPATH')) { exit; }

class gdmaq_core_email {
    public $to = array();
    public $cc = array();
    public $bcc = array();

    public $sender = '';
    public $from = array();
    public $reply_to = array();

    public $headers = array();
    public $attachments = array();
    public $extras = array();

    public $subject = '';
    public $plain = '';
    public $html = '';

    public $type = '';
    public $is_html = false;

    public function __construct($source, $item) {
        if ($source == 'queue') {
            $this->to[] = array(
                $item->to_email,
                $item->to_name
            );

            $this->subject = $item->subject;
            $this->plain = $item->plain;
            $this->html = $item->html;

            if (!empty($this->html)) {
                $this->is_html = true;
            }

            $this->type = $item->type;

            $this->extras = !empty($item->extras) ? json_decode($item->extras) : array();
            $this->headers = !empty($item->headers) ? json_decode($item->headers) : array();
            $this->attachments = !empty($item->attachments) ? json_decode($item->attachments) : array();

            if (!isset($this->extras->ContentType)) {
                $this->extras->ContentType = $this->is_html ? 'text/html' : 'text/plain';
            }

            if (isset($this->extras->ReplyTo) && !empty($this->extras->ReplyTo)) {
                $this->reply_to = $this->extras->ReplyTo;
            }

            if (isset($this->extras->Sender) && !empty($this->extras->Sender)) {
                $this->sender = $this->extras->Sender;
            }

            if (isset($this->extras->From) && !empty($this->extras->From)) {
                $this->from = array(
                    $this->extras->From,
                    isset($this->extras->FromName) ? $this->extras->FromName : ''
                );
            }

            foreach (array('From', 'FromName', 'Sender', 'ReplyTo') as $key) {
                if (isset($this->extras->$key)) {
                    unset($this->extras->$key);
                }
            }
        } else if ($source == 'log') {
            foreach ($item->emails as $type => $emails) {
                if (!empty($emails)) {
                    foreach ($emails as $email) {
                        if ($type == 'from') {
                            $this->from = array($email->email, $email->name);
                        } else {
                            $this->{$type}[] = array($email->email, $email->name);
                        }
                    }
                }
            }

            $this->headers = $item->headers;
            $this->extras = $item->extras;
            $this->attachments = $item->attachments;

            $this->subject = $item->subject;
            $this->plain = $item->plain;
            $this->html = $item->html;

            if (!empty($this->html)) {
                $this->is_html = true;
            }

            $this->type = $item->type;

            if (isset($this->extras->Sender) && !empty($this->extras->Sender)) {
                $this->sender = $this->extras->Sender;
            }

            foreach (array('Sender') as $key) {
                if (isset($this->extras->$key)) {
                    unset($this->extras->$key);
                }
            }

            foreach ($this->headers as $id => $header) {
                if ($header[0] == 'X-Mailer-Processor') {
                    unset($this->headers[$id]);
                }
            }

            if (!empty($this->headers)) {
                $this->headers = array_values($this->headers);
            } else {
                $this->headers = array();
            }
        } else if ($source == 'mirror') {
            $this->to = $item->to;
            $this->cc = $item->cc;
            $this->bcc = $item->bcc;

            $this->sender = $item->get_sender();
            $this->from = $item->get_from();
            $this->reply_to = $item->reply_to;

            $this->headers = $item->headers;
            $this->extras = $item->extras;
            $this->attachments = $item->get_attachments();

            $this->subject = $item->subject;
            $this->is_html = $item->is_html;

            if ($this->is_html) {
                $this->html = $item->body;
                $this->plain = $item->alt_body;
            } else {
                $this->plain = $item->body;
            }

            $this->type = $item->type;

            foreach (array('From', 'FromName', 'Sender') as $key) {
                if (isset($this->extras->$key)) {
                    unset($this->extras->$key);
                }
            }
        }

        $this->validate_attachments();
    }

    public function is_html() {
        return $this->is_html;
    }

	public function is_valid() {
		return !empty($this->to);
	}

    public function validate_attachments() {
        foreach ($this->attachments as $id => $path) {
            if (!file_exists($path)) {
                unset($this->attachments[$id]);
            }
        }

        $this->attachments = array_values($this->attachments);
    }

    public function get_emails_by_type() {
        $list = array(
            'from' => array($this->from),
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

    /** @param bool|PHPMailer\PHPMailer\PHPMailer $phpmailer
      * @param bool $queue
      * @return bool|PHPMailer\PHPMailer\PHPMailer
      * @throws PHPMailer\PHPMailer\PHPMailerException */
    public function build_phpmailer($phpmailer = false, $queue = false) {
        if ($phpmailer === false) {
            $phpmailer = new PHPMailer\PHPMailer\PHPMailer(true);
            $phpmailer->isMail();
        }

        if (!empty($this->from)) {
            $phpmailer->setFrom($this->from[0], $this->from[1], false);
        } else {
            $_em = gdmaq_default_from();
            $phpmailer->setFrom($_em['email'], $_em['name'], false);
        }

        if (!empty($this->sender)) {
            $phpmailer->Sender = $this->sender;
        }

        if (!empty($this->reply_to)) {
            foreach ($this->reply_to as $rto) {
                $phpmailer->addReplyTo($rto[0], $rto[1]);
            }
        }

        foreach ($this->extras as $key => $value) {
            $phpmailer->$key = $value;
        }

        $phpmailer->Subject = $this->subject;
        $phpmailer->Body = !empty($this->html) ? $this->html : $this->plain;
        $phpmailer->AltBody = !empty($this->html) ? $this->plain : '';

        if (!empty($this->html)) {
            $phpmailer->isHTML();
        }

        $phpmailer->addAddress($this->to[0][0], $this->to[0][1]);

        if (!empty($this->attachments)) {
            $status['attachments'] = count($this->attachments);

            foreach ($this->attachments as $attachment) {
                $phpmailer->addAttachment($attachment);
            }
        }

        if (!empty($this->headers)) {
            foreach ($this->headers as $header) {
                $phpmailer->addCustomHeader($header[0], $header[1]);
            }
        }

        if ($queue) {
            if (gdmaq_queue()->reply && !empty(gdmaq_queue()->reply_email)) {
                $phpmailer->clearReplyTos();
                $phpmailer->addReplyTo(gdmaq_queue()->reply_email, gdmaq_queue()->reply_name);
            }

            if (gdmaq_queue()->from && !empty(gdmaq_queue()->from_email)) {
                $phpmailer->setFrom(gdmaq_queue()->from_email, gdmaq_queue()->from_name, false);
            }

            if (gdmaq_queue()->sender && !empty(gdmaq_queue()->sender_email)) {
                $phpmailer->Sender = gdmaq_queue()->sender_email;
            }

            if (gdmaq_queue()->header) {
                $phpmailer->addCustomHeader('X-Mailer-Processor', 'GD Mail Queue '.gdmaq_settings()->plugin_version());
            }
        }

        return $phpmailer;
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

    public function add_to_queue() {
        $extras = $this->extras;

        if (!empty($this->from)) {
            $extras->From = $this->from[0];
            $extras->FromName = $this->from[1];
        }

        if (!empty($this->reply_to)) {
            $extras->ReplyTo = $this->reply_to;
        }

        $args = array(
            'to' => $this->get_recipients(),
            'type' => $this->type,
            'subject' => $this->subject,
            'plain' => $this->plain,
            'html' => $this->html,
            'headers' => $this->headers,
            'attachments' => $this->attachments,
            'extras' => $extras
        );

        gdmaq_mail_to_queue($args);
    }
}
