<?php
/* 
Description: Core Library OFX Export functions
 
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

include 'stageshowlib_export.php';

if (!class_exists('StageShowLibOFXExportAdminClass')) 
{
	define('INDENTSIZE', 2);
	define('INDENTMARK', ' ');
	
	define('TRNTYPE_SALE', 'PAYMENT');
	define('TRNTYPE_FEE',  'FEE');
		
	define('TBD', '******** TBD ********');
	
	class StageShowLibOFXExportAdminClass extends StageShowLibExportAdminClass // Define class
	{
		var $indent;
		
		function __construct($myDBaseObj) //constructor	
		{
			parent::__construct($myDBaseObj);
			
			$this->indent = 0;
			
	  		// FUNCTIONALITY: Export - Settings, Tickets or Summary
			if ( StageShowLibUtilsClass::IsElementSet('post', 'downloadexport' ) )
			{
				$this->fileName = 'stageshow';	
							
				if ( StageShowLibUtilsClass::IsElementSet('post', 'download' ) ) 
				{
					switch ($_POST['export_type'])
					{          
						default :
							$this->fileExtn = 'ofx';
							$this->Export('application/x-ofx');
							break;
					}
				}			       
			}
			else
				die;
		}

		function Export($application, $charset = 'utf-8', $content = '')
		{
			parent::Export($application);
			$this->ofx_content();
		}

		function ofx_output($line = '', $isPair = true)
		{
			$indentLength = $this->indent * INDENTSIZE;						
			if ($isPair)
			{
				if (StageShowLibMigratePHPClass::Safe_substr($line, 0, 1) == '<')
				{
					if (StageShowLibMigratePHPClass::Safe_substr($line, 1, 1) == '/')
					{
						if ($this->indent > 0)
							$this->indent--;
						$indentLength = $this->indent * INDENTSIZE;
					}
					else
					{
						$this->indent++;
					}
				}
			}
			for (;$indentLength > 0; $indentLength--)
				$line = INDENTMARK . $line;
			
			if ( $this->myDBaseObj->isDbgOptionSet('Dev_ShowSQL')
				|| $this->myDBaseObj->isDbgOptionSet('Dev_ShowDBOutput') )
			{
				$line = StageShowLibMigratePHPClass::Safe_htmlspecialchars($line);	
				$line = StageShowLibMigratePHPClass::Safe_str_replace(INDENTMARK, "&nbsp;", $line);					
				$line = StageShowLibMigratePHPClass::Safe_str_replace("\n", "<br>\n", $line);					
			}
			StageShowLibEscapingClass::Safe_EchoHTML($line);
		}
		
		function ofx_line($line = '', $isPair = true)
		{
			$this->ofx_output($line."\n", $isPair);
		}
		
		function ofx_datetime($timestamp = 0)
		{
			if ($timestamp == 0)
				$timestamp = current_time('timestamp');
			$timeAndDate = date('YmdHis', $timestamp);
			
			$timezoneOffset = $timestamp = date('Z')/60*60;
			$timeAndDate .= '['.$timezoneOffset.':'.date('T').']';
			
			return $timeAndDate;
		}
		
		function ofx_header()
		{
			$this->ofx_line('OFXHEADER:100');
			$this->ofx_line('DATA:OFXSGML');
			$this->ofx_line('VERSION:102');
			$this->ofx_line('SECURITY:NONE');
			$this->ofx_line('ENCODING:USASCII');
			$this->ofx_line('CHARSET:1252');
			$this->ofx_line('COMPRESSION:NONE');
			$this->ofx_line('OLDFILEUID:NONE');
			$this->ofx_line('NEWFILEUID:NONE');
			$this->ofx_line();
		}
		
		function ofx_status($code, $severity)
		{
			$this->ofx_line('<STATUS>');
			
			$this->ofx_line('<CODE>'.$code, false);
			$this->ofx_line('<SEVERITY>'.$severity, false);
			
			$this->ofx_line('</STATUS>');
		}

		function ofx_signon()
		{
			$timestamp = $this->ofx_datetime();
			
			$this->ofx_line('<SIGNONMSGSRSV1>');
			$this->ofx_line('<SONRS>');
			
			$this->ofx_status('0', 'INFO');
			$this->ofx_line('<DTSERVER>'.$timestamp, false);
			$this->ofx_line('<LANGUAGE>'.'ENG', false);
			
			$this->ofx_line('</SONRS>');
			$this->ofx_line('</SIGNONMSGSRSV1>');
			$this->ofx_line();
		}
		
		
		function ofx_acctfrom()
		{
			$bankId = 'PAYPAL';
			$acctId = $this->myDBaseObj->adminOptions['PayPalAPIEMail'];
			$acctType = 'CHECKING';
			
			$this->ofx_line('<BANKACCTFROM>');
			
			$this->ofx_line('<BANKID>'.$bankId, false);
			$this->ofx_line('<ACCTID>'.$acctId, false);
			$this->ofx_line('<ACCTTYPE>'.$acctType, false);
			
			$this->ofx_line('</BANKACCTFROM>');
		}
		
		function ofx_ledger($balamt = '0.00')
		{
			$balDate = $this->ofx_datetime();
			
			$this->ofx_line('<LEDGERBAL>');
			
			$this->ofx_line('<BALAMT>'.$balamt, false);
			$this->ofx_line('<DTASOF>'.$balDate, false);
			
			$this->ofx_line('</LEDGERBAL>');			
		}
		
		function ofx_TxnId($saleTxnId, $trntype)
		{	
			return $saleTxnId;
		}
		
		function ofx_transaction($sale, $trntype, $salePaid, $memo='', $index=1)
		{			
			$this->ofx_line('<STMTTRN>');
			
			$saletimestamp = StageShowLibMigratePHPClass::Safe_strtotime( $sale->saleDateTime );
			$saleTxnId = $this->ofx_TxnId($sale->saleTxnId, $trntype);
			
			$this->ofx_line('<TRNTYPE>'.$trntype, false);
			$this->ofx_line('<DTPOSTED>'.$this->ofx_datetime($saletimestamp), false);
			$this->ofx_line('<TRNAMT>'.$salePaid, false);
			$this->ofx_line('<FITID>'.$saleTxnId, false);
			$this->ofx_line('<NAME>'.$this->myDBaseObj->GetSaleName($sale), false);
			
			if ($memo != '')
				$this->ofx_line('<MEMO>'.$memo, false);
				
			$this->ofx_line('</STMTTRN>');
			$this->ofx_line();
		}
		
		function ofx_statement_start_ts()
		{	
			return '';		
		}
		
		function ofx_statement_end_ts()
		{			
			return '';		
		}
		
		function ofx_statement_transactions()
		{			
			$firstsaleTimestamp = $this->ofx_statement_start_ts();
			$lastsaleTimestamp = $this->ofx_statement_end_ts();
			if ( ($firstsaleTimestamp == '') || ($firstsaleTimestamp == '') )
				return;
							
			$this->ofx_line('<DTSTART>'.$this->ofx_datetime($firstsaleTimestamp), false);
			$this->ofx_line('<DTEND>'.$this->ofx_datetime($lastsaleTimestamp), false);
			$this->ofx_line();			
		}
		
		function ofx_statement()
		{			
			$this->ofx_line('<STMTRS>');
			$this->ofx_line('<CURDEF>'.$this->myDBaseObj->adminOptions['PayPalCurrency'], false);
			
			$this->ofx_acctfrom();
			
			$this->ofx_line('<BANKTRANLIST>');

			$this->ofx_statement_transactions();
			
			$this->ofx_line('</BANKTRANLIST>');
			
			$this->ofx_ledger();
			
			$this->ofx_line('</STMTRS>');
		}
		
		
		function ofx_data()
		{			
			$this->ofx_line('<BANKMSGSRSV1>');
			$this->ofx_line('<STMTTRNRS>');
			
			$this->ofx_line('<TRNUID>1', false);
			$this->ofx_status('0', 'INFO');
			
			$this->ofx_statement();
			
			$this->ofx_line('</STMTTRNRS>');
			$this->ofx_line('</BANKMSGSRSV1>');
		}
		
		function ofx_content()
		{
			$this->ofx_header();
			
			$this->ofx_line('<OFX>');
			
			$this->ofx_signon();
			$this->ofx_data();
			
			$this->ofx_line('</OFX>');
		}

	}
}

