<?php

namespace SendWP;

use SendWP\API\Request;

/**
 * A decorater for sending API based transactional email.
 *
 * @TODO Maybe extract formatting methods.
 */
class Mailer implements MailerInterface
{
    protected $phpmailer;
    protected $request;

    public static function factory(&$phpmailer)
    {
        $request = \SendWP\API\Request::create('postmaster');
        $phpmailer = new self($phpmailer, $request);
        return $phpmailer;
    }

    public function __construct($phpmailer, Request $request)
    {
        $this->phpmailer = $phpmailer;
        $this->request = $request;
    }

    /**
     * Check for property/method in $phpmailer.
     */
    public function __get($name)
    {
        if (property_exists($this->phpmailer, $name)) {
            return $this->phpmailer->$name;
        }
        return '';
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->phpmailer, $name)) {
            return call_user_func([ $this->phpmailer, $name ], $arguments);
        }
        return null;
    }

    public function getAttachments()
    {
        $attachments = $this->phpmailer->getAttachments();

        // Format the attachments, per service requirement.
        $attachments = array_map([ $this, 'formatAttachment' ], $attachments);

        return $attachments;
    }

    public function send()
    {
        $to_emails = array_map([ $this, 'formatEmails' ], $this->getToAddresses());
        $cc_emails = array_map([ $this, 'formatEmails' ], $this->getCcAddresses());
        $bcc_emails = array_map([ $this, 'formatEmails' ], $this->getBccAddresses());

        $args = [
            'body' => [
                'to' => json_encode( (array) $to_emails ),
                'from' => $this->From,
                'from_name' => $this->FromName,
                'subject' => $this->Subject,
            ],
        ];
        
        if('text/html' == $this->ContentType) {
            $args[ 'body' ][ 'body' ] = $this->Body;
            $args[ 'body' ][ 'altbody' ] = $this->AltBody;
        } else {
            $args[ 'body' ][ 'altbody' ] = $this->Body;
        }

        if($reply_to = $this->getReplyToAddresses()){
            /**
             * $reply_to is an array of arrays.
             * [ [ $address, $name ], [...] ]
             * To get a string value for the address, we need to reset it twice.
             */
            $reply_to = reset($reply_to);
            $args['body']['reply_to'] = reset($reply_to);
        }

        if( ! empty( $cc_emails ) ) {
            $args[ 'body' ][ 'cc' ] = json_encode( (array) $cc_emails );
        }

        if( ! empty( $bcc_emails ) ) {
            $args[ 'body' ][ 'bcc' ] = json_encode( (array) $bcc_emails );
        }

        if ($attachments = $this->getAttachments()) {
            $args[ 'body' ][ 'attachments' ] = $attachments;
        }

        // Response is empty...
        $response = $this->request->post($args);

        return true; // Sent by the Service.
    }

    protected function formatEmails($emails)
    {
        return reset($emails);
    }

    protected function formatAttachment($attachment)
    {
        return [
            'filename' => $attachment[1], // $filename per PHPMailer docs.
            'filedata' => file_get_contents($attachment[0]) // $path per PHPMailer docs.
        ];
    }
}
