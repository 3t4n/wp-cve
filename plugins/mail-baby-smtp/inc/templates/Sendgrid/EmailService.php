<?php
/**
 * WP SendGrid Mailer Plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace WPMailPlus;

interface EmailService
{
     /**
      * Retrieve token
      */
     public function get_token();

     /**
      * Send Mail
      * @param mixed $to
      * @param string $subject
      * @param string $message
      * @param string $headers
      * @param array $attachments
      */
     public function send_mail($to, $subject, $message, $headers, $attachments);

     /**
      * Prepare attachment object
      * @param $attachment_path
      * @return mixed
      */
     public function prepareAttachment($attachment_path);
}