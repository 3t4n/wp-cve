<?php

namespace AForms\Infra;

class WpMailer 
{
    protected $to = null;
    protected $fromName = null;
    protected $fromAddr = null;
    protected $bcc = null;
    protected $subject = null;
    protected $textBody = null;
    protected $attachments = '';
    protected $returnPath = null;

    public function setTo($to) 
    {
        $this->to = $to;
        return $this;
    }

    public function setFrom($name, $addr) 
    {
        $this->fromName = $name;
        $this->fromAddr = $addr;
        return $this;
    }

    public function setReturnPath($returnPath) 
    {
        $this->returnPath = $returnPath;
        return $this;
    }

    public function setBcc($bcc) 
    {
        $this->bcc = $bcc;
        return $this;
    }

    public function setSubject($subject) 
    {
        $this->subject = $subject;
        return $this;
    }

    public function setTextBody($textBody) 
    {
        $this->textBody = $textBody;
        return $this;
    }

    public function setAttachments($attachments) 
    {
        $this->attachments = $attachments;
        return $this;
    }

    protected function assembleHeaders() 
    {
        $headers = array();
        $headers[] = "From: ".$this->fromName." <".$this->fromAddr.">";
        if ($this->bcc) {
            $headers[] = "Bcc: ".$this->bcc;
        }
        return $headers;
    }

    public function send() 
    {
        if ($this->returnPath) {
            add_filter('phpmailer_init', array($this, 'injectSender'), 10, 2);
        }
        $x = wp_mail($this->to, $this->subject, $this->textBody, $this->assembleHeaders(), $this->attachments);
        if ($this->returnPath) {
            remove_filter('phpmailer_init', array($this, 'injectSender'), 10);
        }
        
        return $this;
    }

    public function injectSender($phpmailer) 
    {
        $phpmailer->Sender = $this->returnPath;
    }

    public function clear() 
    {
        $this->to = null;
        $this->fromName = null;
        $this->fromAddr = null;
        $this->bcc = null;
        $this->subject = null;
        $this->textBody = null;
        $this->attachments = '';
        $this->returnPath = null;

        return $this;
    }
}