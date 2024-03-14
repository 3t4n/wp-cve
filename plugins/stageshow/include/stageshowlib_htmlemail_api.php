<?php
/* 
Description: Core Library EMail API functions
 
Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if (!class_exists('StageShowLibHTMLEMailAPIClass')) 
	{
	
	define('STAGESHOWLIB_FILENAME_EMAILLOG', 'EMailLog.log');
	class StageShowLibHTMLEMailAPIClass // Define class
	{	
		var $fileobjs = array();
		var $ourContentType = '';
		
		const EMBEDDED_IMAGE_MARKER = "data:image/png;base64,";

		var $parentObj;
		var $adminEMail;
		var $createMIMEmessages;
		var $tmpFiles = array();
		
		function __construct($ourParentObj)	
		{
			$this->parentObj = $ourParentObj;	
			$this->adminEMail = get_option('admin_email');		
				
			$this->mimeEncodingMode = $this->parentObj->getOption('MIMEEncoding');
			$this->createMIMEmessages = ($this->mimeEncodingMode == STAGESHOWLIB_MIMEENCODING_PLUGIN);
			
			
			if (file_exists(ABSPATH . WPINC . '/PHPMailer/PHPMailer.php'))
			{
				require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
				require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
				require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
				
				$this->phpMailerClass = "PHPMailer\PHPMailer\PHPMailer";
			}
			else
			{
				require_once ABSPATH . WPINC . '/class-phpmailer.php';
				require_once ABSPATH . WPINC . '/class-smtp.php';		
				
				$this->phpMailerClass = "PHPMailer";
			}
		}
				
		function createPHPMailerObj($DebugEMail, $EMailLogPath)
		{
			global $phpmailer;
	
			if (!is_object($phpmailer))
			{
				// There is no PHPMailer object - create one now 
				$phpmailer = new $this->phpMailerClass(true);		
			}
			
			$mailerClass = get_class($phpmailer);
			if ($DebugEMail)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Mailer Class = $mailerClass <br>\n");
				StageShowLibEscapingClass::Safe_EchoHTML('Mime Encoding Mode: '."{$this->mimeEncodingMode} <br>\n");
			}
			
			if (StageShowLibMigratePHPClass::Safe_strpos($mailerClass, 'PHPMailer') === false)
			{
		  		if (!$this->createMIMEmessages)
		  		{
		  			// Using PHPMailer class methods - Check that they exist
		  			$missingMethods = '';
		  			
		  			if (!method_exists($phpmailer, 'addEmbeddedImage'))
		  				$missingMethods .= 'addEmbeddedImage ';
		  			
		  			if (!method_exists($phpmailer, 'addAttachment'))
		  				$missingMethods .= 'addAttachment ';
		  			
		  			if (!method_exists($phpmailer, 'addStringEmbeddedImage'))
		  				$missingMethods .= 'addStringEmbeddedImage ';
		  			
		  			if ($missingMethods != '')
		  			{
		  				StageShowLibEscapingClass::Safe_EchoHTML("************************************* <br>\n");
		  				StageShowLibEscapingClass::Safe_EchoHTML(sprintf(__('Mailer class (%s) has missing methods', 'stageshow'), $mailerClass)." ($missingMethods) <br>\n");
		  				StageShowLibEscapingClass::Safe_EchoHTML(__('Any email attachments will be missing', 'stageshow')." <br>\n");
		  				StageShowLibEscapingClass::Safe_EchoHTML("************************************* <br>\n");
		  			}
		  		}
				
			}
			
			if ($DebugEMail)
				$phpmailer->SMTPDebug = 3; // SMTP::DEBUG_CONNECTION;
			
			$phpmailer->EMailLogPath = $EMailLogPath;
		}
		
		static function DoEmbeddedFileImage($filePath)
		{
			$imagedata = file_get_contents($filePath);
			$imageBase64 = chunk_split(base64_encode($imagedata));
				
			$imageFile = basename($filePath);
			
			$embeddedImage = self::EMBEDDED_IMAGE_MARKER."$imageBase64";
			return $embeddedImage;
		}
		
		function AddFileImage($filePath)
		{
			$imageObj = new stdClass();
			
			$imageFile = basename($filePath);
			$CIDFile = 'File.'.$imageFile;
			
			$imageObj->path = $filePath;
			$imageObj->file = $imageFile;
			$imageObj->cid = $CIDFile;
			
			$this->AddImage($imageObj);
			return $CIDFile;
		}
		
		function AddImage($imageObj)
		{
			// Check if image is already in list
			foreach ($this->fileobjs as $currImageObj)
			{
				if (!isset($currImageObj->cid))
					continue;
				
				if ($currImageObj->cid === $imageObj->cid)
					return true;
			}
 			
			$this->fileobjs[] = $imageObj;
			return true;
		}
		
		function AddAttachment($filePath)
		{
			$fileObj = new stdClass();
			
			$filename = basename($filePath);
								
			$fileObj->path = $filePath;
			$fileObj->file = $filename;
			
			$this->fileobjs[] = $fileObj;
		}
			
		function AddAttachmentFromData($bin, $filePath, $type = '')
		{
			$fileObj = new stdClass();
			
			$imageFile = basename($filePath);
			
			if ($type != '')
				$fileObj->mimeType = $type;
			
			$fileObj->file = $imageFile;			
			$fileObj->bin = $bin;
			
			$this->fileobjs[] = $fileObj;
		}
		
		function html2text($html)
		{
			// FUNCTIONALITY: EMail - Create TEXT content from HTML content
			
			// Change <br> and <p> to line feeds
			$text = $html;
			
			$bodyPosn = StageShowLibMigratePHPClass::Safe_strpos($html, '<body');
			if ($bodyPosn > 0) $text = StageShowLibMigratePHPClass::Safe_substr($html, $bodyPosn);
				
			// Convert HTML Anchor to ... Anchor_Text(Anchor_HREF)					
			$noOfMatches = preg_match_all('|\<a.*?href=(.*?)\>(.*?)\<\/a\>|', $text, $regexResults);
			for ($i=0; $i<$noOfMatches; $i++)
			{
				$origLink = $regexResults[0][$i];
				$origURL  = $regexResults[1][$i];
				$origText = $regexResults[2][$i];

				$origURL = StageShowLibMigratePHPClass::Safe_str_replace('"', '', $origURL);
				$origURL = StageShowLibMigratePHPClass::Safe_str_replace('mailto:', '', $origURL);
				
				if ($origText == $origURL)
					$targetText = $origText;
				else if ($origText == '')
					$targetText = '';
				else
					$targetText = "$origText($origURL)";
				
				$text = StageShowLibMigratePHPClass::Safe_str_replace($origLink, $targetText, $text);
			}
			
			$text = StageShowLibMigratePHPClass::Safe_htmlspecialchars_decode($text);

			$search = array (
				"'&nbsp;'si",							// Space
				"'<script[^>]*?>.*?</script>'si",		// Javascript
				"'([\r\n])[\s]+'",						// White space
				"'<(br|p|\/tr).*?>'i",					// End of Line
				"'<[/!]*?[^<>]*?>'si");					// All HTML tags
				
			$replace = array (
				"",
				"",
				"",
				"\n",
				"");

			$text = preg_replace($search, $replace, $text);
			
			return $text;
		}
		
		function addMIMEEncodedMessage(&$message)
		{
			// Create a unique boundary string using the MD5 algorithm to generate a random hash
			$MIMEMarker = md5(date('r', time()));
			$this->MIMEboundaryA  = "Part_A_".$MIMEMarker;
			$this->MIMEboundaryB  = "Part_B_".$MIMEMarker;

			$this->ourContentType .= "multipart/alternative; boundary=\"$this->MIMEboundaryA\"";	// boundary string and mime type specification

			// Build the MIME encoded email body
			$message  = '';
			$message .= "This is a message with multiple parts in MIME format\n";
			$message .= "--$this->MIMEboundaryA\n";
			$message .= "Content-Type: text/plain\n";
			$message .= "Content-Transfer-Encoding: 8bit\n";
			$message .= "\n";
			$message .= $this->contentTEXT;
			$message .= "\n--$this->MIMEboundaryA\n";
			
			$message .= "Content-Type: multipart/related; boundary=\"$this->MIMEboundaryB\"\n\n";
			$message .= "--$this->MIMEboundaryB\n";
			
			$message .= "Content-Type: text/html; charset=\"utf-8\"\n";
			$message .= "Content-Transfer-Encoding: 8bit\n";
			//$message .= "Content-Transfer-Encoding: quoted-printable\n";
			$message .= "\n";
			
			$message .= $this->contentHTML;
			$message .= "\n";

			foreach ($this->fileobjs as $fileobj)
			{
				if (isset($fileobj->cid))
					$message .= $this->OutputMIMEImage($fileobj);
				else
					$message .= $this->OutputMIMEFile($fileobj);
			}
						
			$message .= "--$this->MIMEboundaryB--\n";				
			$message .= "\n";
			$message .= "--$this->MIMEboundaryA--\n";
		}		
		
		function sendMail($to, $from, $subject, $content, $headers = '')
		{
			global $phpmailer;
			
			$this->DebugEMail = $this->parentObj->getDbgOption('Dev_ShowEMailMsgs') || isset($this->parentObj->showEMailMsgs);

			$EMailLogPath = $this->parentObj->getDbgOption('Dev_LogEMailMsgs') ? $this->parentObj->getOption('LogsFolderPath') : '';
			$this->createPHPMailerObj($this->DebugEMail, $EMailLogPath);

			if (StageShowLibMigratePHPClass::Safe_strlen($headers) > 0) $headers .= "\r\n";
			$headers .= "MIME-Version: 1.0";
								
	  		// FUNCTIONALITY: EMail - Send MIME format EMail with text and HTML versions
			if ((StageShowLibMigratePHPClass::Safe_strlen($content) > 0) && (StageShowLibMigratePHPClass::Safe_stripos($content, '<html>') !== false))
			{
				$this->contentHTML = $content;
				$this->contentTEXT = $this->html2text($this->contentHTML);

				// Tidy up the line ends
				$search = array (
					"'\r\n'",		// CR LF
					"'\r'");		// CR
					
				$replace = array (
					"\n",
					"\n");

				$this->contentHTML = preg_replace($search, $replace, $this->contentHTML);
				
				if (current_user_can(STAGESHOWLIB_CAPABILITY_SYSADMIN)) 
				{
					if ($phpmailer->hasLineLongerThanMax($this->contentTEXT))
					{
						StageShowLibEscapingClass::Safe_EchoHTML("WARNING: Text Content has one or more lines longer than SMTP allows<br>\n\n\n");
					}
					
					if ($phpmailer->hasLineLongerThanMax($this->contentHTML))
					{
						StageShowLibEscapingClass::Safe_EchoHTML("WARNING: HTML Content has one or more lines longer than SMTP allows<br>\n\n\n");
					}					
				}
				      			
		  		if (!$this->createMIMEmessages)
		  		{
		  			// Use PHPMailer to format email message
					$this->ourContentType = "text/html";
					$message = $content;		  			
		  		}
				else
				{		      			
					$message = '';				
					$this->addMIMEEncodedMessage($message);				
				}
			}
			else
			{
				$this->ourContentType = "text/plain";
				$message = $content;
			}
		
			$replyTo = $from;
			
			// Add the MIME headers - separated with \r\n
			if (StageShowLibMigratePHPClass::Safe_strlen($headers) > 0) $headers .= "\r\n";
			$headers .= "Content-Type: {$this->ourContentType}";
			$headers .= "\r\nFrom: $from";	
			$headers .= "\r\nReply-To: $replyTo";	

			if ($this->DebugEMail)
			{
				// FUNCTIONALITY: General - Echo EMail when Dev_ShowEMailMsgs selected - Body Encoded with htmlspecialchars
				$html_email = "To:<br>\n";
				$html_email .= StageShowLibMigratePHPClass::Safe_htmlspecialchars($to);
				$html_email .= "<br>\n<br>\n";
				$html_email .= "Subject:<br>\n";
				$html_email .= StageShowLibMigratePHPClass::Safe_htmlspecialchars($subject);
				$html_email .= "<br>\n<br>\n";
				$html_email .= "Headers:<br>\n";
				$html_email .= StageShowLibMigratePHPClass::Safe_str_replace("\r\n", "<br>\r\n", StageShowLibMigratePHPClass::Safe_htmlspecialchars($headers));
				$html_email .= "<br>\n<br>\n";
/*
				$html_email .= "Message:<br>\n";
				$html_email .= StageShowLibMigratePHPClass::Safe_htmlspecialchars($message);
				$html_email .= "<br>\n<br>\n";
*/
				StageShowLibEscapingClass::Safe_EchoHTML($html_email);
			}
					
			$bracket_pos = StageShowLibMigratePHPClass::Safe_strpos( $from, '<' );
			if ( $bracket_pos !== false ) 
				{
				// Text before the bracketed email is the "From" name.
				if ( $bracket_pos > 0 ) 
				{
					$from_name = StageShowLibMigratePHPClass::Safe_substr( $from, 0, $bracket_pos - 1 );
					$from_name = StageShowLibMigratePHPClass::Safe_str_replace( '"', '', $from_name );
					$from_name = StageShowLibMigratePHPClass::Safe_trim( $from_name );
				}

				$from_email = StageShowLibMigratePHPClass::Safe_substr( $from, $bracket_pos + 1 );
				$from_email = StageShowLibMigratePHPClass::Safe_str_replace( '>', '', $from_email );
				$from_email = StageShowLibMigratePHPClass::Safe_trim( $from_email );
			} 
			else
			{
				$from_email = StageShowLibMigratePHPClass::Safe_trim( $from );
				$from_name = '';
			}

			$this->StageShowLib_From = $from_email;
			$this->StageShowLib_FromName = $from_name;
								
			// Register a function to get EMail sent result
			add_action('wp_mail_succeeded', array(&$this, 'Get_EMailResult'));
																		
			// Register a function to get any EMail errors
			add_action('wp_mail_failed', array(&$this, 'Get_EMailErrors'));

		  	if (!$this->createMIMEmessages)
		  	{
				add_action( 'phpmailer_init', array(&$this, 'AddTextEMailBody') );
				add_action( 'phpmailer_init', array(&$this, 'AddFilesToEMail') );
		  	}
		  	else
		  	{
				add_action( 'phpmailer_init', array(&$this, 'FixPHPMailer') );
		  	}
	
			// Register PHPMailer Filters
			add_filter('wp_mail_from', array(&$this, 'FilterMailFrom'), 10, 1);
			add_filter('wp_mail_from_name', array(&$this, 'FilterMailFromName'), 10, 1);
			add_filter('wp_mail_content_type', array(&$this, 'FilterMailContentType'), 10, 1);
			
			// FUNCTIONALITY: General - Send EMail
			$toList = explode(';', $to);
			$rtnStatus = 'OK';
			if (!$this->parentObj->getDbgOption('Dev_BlockEMailSend'))
			{
				if ($this->DebugEMail)
					StageShowLibEscapingClass::Safe_EchoHTML(("Calling wp_mail <br>\n"));

				if (!wp_mail($toList, $subject, $message, $headers))
				{
					$rtnStatus = $this->lastError;
				}

				// Tidy up any temp files
				foreach ($this->tmpFiles as $filePath)
				{
					if ($this->DebugEMail)
						StageShowLibEscapingClass::Safe_EchoHTML(("Deleting temporary file: $filePath <br>\n"));
					unlink($filePath);
				}
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<br><strong>Sending of EMails Blocked </strong><br><br>\n");
			}

			return $rtnStatus;
		}	
		
		function GetMIMETypeFromName($filePath)
		{
			$fileTypePosn = StageShowLibMigratePHPClass::Safe_strripos($filePath, '.');
			if (!$fileTypePosn) return;
				
			$fileExtn = StageShowLibMigratePHPClass::Safe_strtolower(StageShowLibMigratePHPClass::Safe_substr($filePath, $fileTypePosn+1));
			$type = 'application/'.$fileExtn;
			
			return $type;
		}
		
		function OutputMIMEFile($fileobj)
		{
			if (isset($fileobj->bin))
			{
				$bin = $fileobj->bin;
			}
			else
			{
				$bin = file_get_contents($fileobj->path);
			}
			if (StageShowLibMigratePHPClass::Safe_strlen($bin) == 0) return;
				
			$image = chunk_split(base64_encode($bin));
			
			$message = '';

			$mimeType = isset($fileobj->mimeType) ? $fileobj->mimeType : $this->GetMIMETypeFromName($fileobj->file);
						
			$message .= "--$this->MIMEboundaryB\n";
			$message .= 'Content-Type: "'.$mimeType.'"; name="'.$fileobj->file."\n";
			$message .= 'Content-Disposition: attachment; filename="'.$fileobj->file.'" '."\n";
			$message .= 'Content-Transfer-Encoding: base64'."\n";
			$message .= "\n";

			$message .= $image;
			$message .= "\n";
			
			return $message;
		}
		
		function OutputMIMEImage($imageobj)
		{
			$message = '';

			$message .= "--$this->MIMEboundaryB\n";
			$message .= "Content-Type: image/png; name=\"".$imageobj->file."\"\n";
			$message .= "Content-Transfer-Encoding: base64\n";
			$message .= "Content-ID: <".$imageobj->cid.">\n";
						
			//$message .= "Content-Disposition: inline; filename=\"".$imageobj->file."\"\n";
			$message .= "\n";

			if (isset($imageobj->image))
				$message .= $imageobj->image;
			else
			{
				if (!isset($imageobj->bin))
				{
					$filePath = $imageobj->path;
					$imageobj->bin = file_get_contents($filePath);
				}
				$message .= chunk_split ( base64_encode ( $imageobj->bin ) );
			}

			$message .= "\n";
			
			return $message;
		}		
		
		function FixPHPMailer($phpmailer)
		{
			/*
			This function fixes a problem that wp_mail has with multipart MIME EMail Messages
			
			The wp_mail() function is defined by pluggable.php. At line 514 (WP v6.1) it checks if the
			Content-Type header contains both the multipart keyword and that a boundary is defined. If
			both these conditions are met it adds a custom header for this Content-Type. This is encoded 
			and added to the headers sent to the Mail Server by the PHPMailer class.  
			
			The PHPMailer class may also call the getMailMIME() function and this will also create a 
			Content-Type header which is added directly (un-encoded) to the EMail headers.
			
			The result is that it is possible for wp_mail() to include two Content-Type headers in the 
			request sent to the EMail Server, which may result in the mail server not sending the email.
			
			This function checks for the presence of multiple Content-Type headers, and removes all 
			those in the custom headers when this condition is met.
			*/
			
			global $phpmailer;
			$customHeaders = $phpmailer->getCustomHeaders();

			// Check if MIME header has Content-Type defined '
			$mailMIME = $phpmailer->getMailMIME();
			if (StageShowLibMigratePHPClass::Safe_strpos($mailMIME, 'Content-Type') === false)
				return;
			
			// Clear the custom headers
			$phpmailer->clearCustomHeaders();
			
			// Now put back everything except the Content-Type
			foreach ($customHeaders as $customHeader)
			{
				if ($customHeader[0] == 'Content-Type')
				{
					if ($this->DebugEMail)
						StageShowLibEscapingClass::Safe_EchoHTML((__('Duplicate Content-Type header removed from PHPMailer', 'stageshow')."<br>\n"));
					continue;
				}
				
				$phpmailer->addCustomHeader($customHeader[0]. $customHeader[1]);
			}
			$customHeaders = $phpmailer->getCustomHeaders();
		}
		
		function AddTextEMailBody($phpmailer)
		{			
	        // don't run if sending plain text email already
	        // don't run if altbody is set
	        if ( ($phpmailer->ContentType ===  'text/plain') || ! empty($phpmailer->AltBody) ) 
	        {
	            return;
	        }
	        
			// Add text to EMail
	        $phpmailer->AltBody = $this->contentTEXT;			
		}
		
		function AddFilesToEMail($phpmailer)
		{
			$encoding = 'base64';
			
			// Add Embedded Images
			foreach ($this->fileobjs as $fileobj)
			{
				$path = isset($fileobj->path) ? $fileobj->path : '';
				$file = $fileobj->file;
				$type = isset($fileobj->type) ? $fileobj->type : '';
				$cid = '';
				$disposition = 'attachment';
				if (isset($fileobj->cid))
				{
					$cid = $fileobj->cid;
					$disposition = 'inline';
				}
				
				if ($path != '')
				{
					// Data to be read from file
					if ($cid != '')
					{					
						if ($this->DebugEMail)
							StageShowLibEscapingClass::Safe_EchoHTML("Calling addEmbeddedImage($file) <br>\n");
						$phpmailer->addEmbeddedImage($path, $cid, $file, $encoding, $type, $disposition);        
					}
					else
					{
						if ($this->DebugEMail)
							StageShowLibEscapingClass::Safe_EchoHTML("Calling addAttachment($file) <br>\n");
						$phpmailer->addAttachment($path, $file, $encoding, $type, $disposition);
					}
				}
				else 
				{
					// Data included in object
					{
						// Copy data to a temporary file
						$ts = time();
						$sid = $this->parentObj->GetSessionID();
						$imgFilePath = sys_get_temp_dir()."/ssimage_{$ts}_{$sid}.png";	
						$this->tmpFiles[] = $imgFilePath;
						
						if ($this->DebugEMail)
							StageShowLibEscapingClass::Safe_EchoHTML(("Adding image to temporary file: $imgFilePath <br>\n"));
						file_put_contents($imgFilePath, $fileobj->bin);
						
						if ($this->DebugEMail)
							StageShowLibEscapingClass::Safe_EchoHTML("Calling addEmbeddedImage($file) <br>\n");
						$phpmailer->addEmbeddedImage($imgFilePath, $cid, $file, $encoding, $type, $disposition);    
					}
				}

				
			}			
		}
		
		function FilterMailFrom($mailFrom)
		{
			$mailFrom = $this->StageShowLib_From;		
			return $mailFrom;
		}
		
		function FilterMailFromName($mailFromName)
		{
			$mailFromName = $this->StageShowLib_FromName;		
			return $mailFromName;
		}

		function FilterMailContentType($contentType)
		{
 			$contentType = $this->ourContentType;
			return $contentType;
		}

		function Get_EMailResult($mail_data)
		{ 
			if ($this->DebugEMail)
			{
				StageShowLibEscapingClass::Safe_EchoHTML(("wp_mail response: wp_mail_succeeded <br>\n"));
			}
				
		}
		
		function Get_EMailErrors($excp)
		{
			$errMsg = $excp->get_error_message();
			$this->lastError = $errMsg;
			if ($this->DebugEMail)
			{
				$htmlErrMsg = StageShowLibMigratePHPClass::Safe_htmlspecialchars($errMsg);
				StageShowLibEscapingClass::Safe_EchoHTML(("wp_mail response: wp_mail_failed <br>\n"));
				StageShowLibEscapingClass::Safe_EchoHTML(("error msg: $htmlErrMsg <br>\n"));
			}
		}

	}
}



