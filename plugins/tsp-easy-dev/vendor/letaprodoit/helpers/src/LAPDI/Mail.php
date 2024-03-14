<?php
/**
 * Mail Class
 *
 * @package		LetAProDoIT.Helpers
 * @filename	Mail.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Global functions used by various services
 *
 */	
class LAPDI_Mail
{
    private $mail;
    private $from_email;
    private $from_name;

    /**
     * Constructor
     *
     * @since 1.0.1
     *
     * @param string $from - From email
     * @param string $name - From name
     * @param bool $smtp_on - SMTP on/off
     *
     * @return void
     *
     */
    function __construct($from, $name, $smtp_on) 
    {
        $this->mail = new PHPMailer( true );

        $this->from_email = $from;
        $this->from_name = $name;

        $this->configContact();

        if ($smtp_on)
            $this->configSMTP();
    }

    /**
     * Configure SMTP
     *
     * @since 1.0.1
     *
     * @param void
     *
     * @return void
     *
     */
    private function configSMTP()
    {
        if (isset($this->mail))
        {
            // Additional settings…
            $this->mail->isSMTP();
    
        	//if (LAPDI_Config::get('app.debug'))
        	//    $this->mail->SMTPDebug  = 2;
    
            $this->mail->Host = LAPDI_Settings::$smtp_host;
            $this->mail->SMTPAuth = true; // Force it to use Username and Password to authenticate
            $this->mail->Port = LAPDI_Settings::$smtp_port;
            $this->mail->Username = LAPDI_Settings::$smtp_user;
            $this->mail->Password = LAPDI_Settings::$smtp_pass;
            $this->mail->SMTPSecure = LAPDI_Settings::$smtp_secure;
        }
    }

    /**
     * Configure SMTP
     *
     * @since 1.0.1
     *
     * @param void
     *
     * @return void
     *
     */
    private function configContact()
    {
        if (isset($this->mail))
        {
            // Additional settings…
            $this->mail->From = $this->from_email;
            $this->mail->FromName = $this->from_name;
        }
    }

    /**
     * Funciton to send mail using PHPMailer
     *
     * @since 1.0.0
     *
     * @param string $to - the email address
     * @param string $subject - the email subject
     * @param string $body - the email body
     * @param optional string $attachment - the email attachment
     *
     * @return void
     *
     */
    public function send($to, $subject, $body, $attachment = null)
    {
        try
        {
	        if (LAPDI_Config::get('app.debug'))
	            LAPDI_Log::info("Preparing mail...");

	        $this->mail->setFrom($this->mail->From, $this->mail->FromName);
	        
	        if (LAPDI_Settings::$admin_notify)
	        {
                if (LAPDI_Config::get('app.debug'))
                    LAPDI_Log::info("Adding admin to BCC...");

	        	$this->mail->AddBCC(LAPDI_Settings::$admin_email, LAPDI_Settings::$admin_email);
	        }
	        
	        $plain_text = strip_tags($body, "<br>");
	        $plain_text = preg_replace("/\<br\>/", "\n", $plain_text);
	        	
	        $this->mail->addReplyTo($this->mail->From, $this->mail->FromName);
	        $this->mail->addAddress($to);
	        $this->mail->Subject = utf8_decode($subject);
	        $this->mail->msgHTML($body);
	        $this->mail->AltBody = $plain_text;
	        
	        if (file_exists($attachment))
	        {
	            $this->mail->AddAttachment( $attachment , basename($attachment) );
	        }

            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("Sending mail...");

            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("Sending mail content {$plain_text}...");

	        if(!$this->mail->Send())
	        {
		        if (LAPDI_Config::get('app.debug'))
		            LAPDI_Log::info("DID NOT send email, Response: {$this->mail->ErrorInfo}.");
			}
			else
			{
		        if (LAPDI_Config::get('app.debug'))
		            LAPDI_Log::info("Message sent!");
			}   

			$this->mail->ClearAddresses();
			$this->mail->ClearAllRecipients();
        }
		catch (phpmailerException $e) 
		{
	        if (LAPDI_Config::get('app.debug'))
	            LAPDI_Log::info("PHPMailer: DID NOT send email, Response: {$e->errorMessage()}.");
		} 
		catch (Exception $e) 
		{
	        if (LAPDI_Config::get('app.debug'))
	            LAPDI_Log::info("Exception: DID NOT send email, Response: {$e->errorMessage()}.");
		}
 	}
}

/**
 * TSP_Mail
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_Mail instead
 *
 * @return void
 *
 */
class TSP_Mail extends LAPDI_Mail
{

}// end class